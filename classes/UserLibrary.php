<?php
/**
 * Gerenciador de Biblioteca do Usuário
 * 
 * Esta classe gerencia a biblioteca pessoal do usuário
 * com suporte a múltiplas fontes de dados e tipos de conteúdo.
 */

class UserLibrary 
{
    private static int $defaultUserId = 1; // Usuário padrão para sistema sem login
    
    /**
     * Adicionar manga à biblioteca
     */
    public static function addManga(array $mangaData, int $userId = null): array 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        try {
            return Database::transaction(function() use ($mangaData, $userId) {
                // Salvar manga no banco se não existir
                $mangaId = MangaModel::save($mangaData);
                
                // Verificar se já está na biblioteca
                $existing = self::findInLibrary($userId, $mangaId);
                if ($existing) {
                    return [
                        'success' => false,
                        'message' => 'Este manga já está na sua biblioteca',
                        'manga_id' => $mangaId,
                        'library_id' => $existing['id']
                    ];
                }
                
                // Adicionar à biblioteca
                $libraryId = Database::insert('user_library', [
                    'user_id' => $userId,
                    'manga_id' => $mangaId,
                    'status' => 'plan_to_read',
                    'progress' => 0,
                    'score' => null,
                    'notes' => 'Adicionado via API'
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Manga adicionado à biblioteca com sucesso',
                    'manga_id' => $mangaId,
                    'library_id' => $libraryId
                ];
            });
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao adicionar à biblioteca: ' . $e->getMessage(),
                'manga_id' => null,
                'library_id' => null
            ];
        }
    }
    
    /**
     * Remover manga da biblioteca
     */
    public static function removeManga(int $libraryId, int $userId = null): bool 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        return Database::delete('user_library', [
            'id' => $libraryId,
            'user_id' => $userId
        ]) > 0;
    }
    
    /**
     * Atualizar status de leitura
     */
    public static function updateStatus(int $libraryId, string $status, int $userId = null): bool 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        $validStatuses = ['reading', 'completed', 'on_hold', 'dropped', 'plan_to_read'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        return Database::update('user_library', 
            ['status' => $status], 
            ['id' => $libraryId, 'user_id' => $userId]
        ) > 0;
    }    
  
  /**
     * Atualizar progresso de leitura
     */
    public static function updateProgress(int $libraryId, int $progress, int $userId = null): bool 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        return Database::update('user_library', 
            ['progress' => max(0, $progress)], 
            ['id' => $libraryId, 'user_id' => $userId]
        ) > 0;
    }
    
    /**
     * Atualizar avaliação
     */
    public static function updateScore(int $libraryId, ?float $score, int $userId = null): bool 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        if ($score !== null) {
            $score = max(0, min(10, $score)); // Limitar entre 0 e 10
        }
        
        return Database::update('user_library', 
            ['score' => $score], 
            ['id' => $libraryId, 'user_id' => $userId]
        ) > 0;
    }
    
    /**
     * Atualizar notas
     */
    public static function updateNotes(int $libraryId, string $notes, int $userId = null): bool 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        return Database::update('user_library', 
            ['notes' => $notes], 
            ['id' => $libraryId, 'user_id' => $userId]
        ) > 0;
    }
    
    /**
     * Obter biblioteca do usuário
     */
    public static function getUserLibrary(int $userId = null, array $filters = []): array 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        $sql = "
            SELECT 
                ul.*,
                m.*,
                ul.id as library_id,
                m.id as manga_id
            FROM user_library ul
            JOIN manga m ON ul.manga_id = m.id
            WHERE ul.user_id = :user_id
        ";
        
        $params = ['user_id' => $userId];
        
        // Aplicar filtros
        if (!empty($filters['status'])) {
            $sql .= " AND ul.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['type'])) {
            $sql .= " AND m.content_type = :type";
            $params['type'] = $filters['type'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (m.title LIKE :search OR m.title_english LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        // Ordenação
        $orderBy = $filters['sort'] ?? 'added_at';
        $orderDir = $filters['order'] ?? 'DESC';
        $sql .= " ORDER BY ul.$orderBy $orderDir";
        
        // Limite
        if (isset($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
            if (isset($filters['offset'])) {
                $sql .= " OFFSET " . (int)$filters['offset'];
            }
        }
        
        $results = Database::query($sql, $params, true);
        
        // Enriquecer dados
        foreach ($results as &$item) {
            $item = self::enrichLibraryItem($item);
        }
        
        return $results;
    }
    
    /**
     * Verificar se manga está na biblioteca
     */
    public static function findInLibrary(int $userId, int $mangaId): ?array 
    {
        return Database::queryOne(
            "SELECT * FROM user_library WHERE user_id = :user_id AND manga_id = :manga_id",
            ['user_id' => $userId, 'manga_id' => $mangaId],
            true
        );
    }
    
    /**
     * Verificar se manga está na biblioteca por fonte
     */
    public static function findInLibraryBySource(int $userId, string $source, string $externalId): ?array 
    {
        return Database::queryOne("
            SELECT ul.*, m.* 
            FROM user_library ul
            JOIN manga m ON ul.manga_id = m.id
            JOIN manga_sources ms ON m.id = ms.manga_id
            WHERE ul.user_id = :user_id 
            AND ms.source = :source 
            AND ms.external_id = :external_id
        ", [
            'user_id' => $userId,
            'source' => $source,
            'external_id' => $externalId
        ], true);
    }
    
    /**
     * Obter estatísticas da biblioteca
     */
    public static function getLibraryStats(int $userId = null): array 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        $stats = Database::queryOne("
            SELECT 
                COUNT(*) as total_manga,
                COUNT(CASE WHEN ul.status = 'reading' THEN 1 END) as reading,
                COUNT(CASE WHEN ul.status = 'completed' THEN 1 END) as completed,
                COUNT(CASE WHEN ul.status = 'on_hold' THEN 1 END) as on_hold,
                COUNT(CASE WHEN ul.status = 'dropped' THEN 1 END) as dropped,
                COUNT(CASE WHEN ul.status = 'plan_to_read' THEN 1 END) as plan_to_read,
                COUNT(CASE WHEN m.content_type = 'manga' THEN 1 END) as manga_count,
                COUNT(CASE WHEN m.content_type = 'manhwa' THEN 1 END) as manhwa_count,
                COUNT(CASE WHEN m.content_type = 'manhua' THEN 1 END) as manhua_count,
                AVG(ul.score) as avg_score
            FROM user_library ul
            JOIN manga m ON ul.manga_id = m.id
            WHERE ul.user_id = :user_id
        ", ['user_id' => $userId], true);
        
        // Estatísticas por fonte
        $sources = Database::query("
            SELECT ms.source, COUNT(*) as count 
            FROM user_library ul
            JOIN manga m ON ul.manga_id = m.id
            JOIN manga_sources ms ON m.id = ms.manga_id
            WHERE ul.user_id = :user_id
            GROUP BY ms.source
        ", ['user_id' => $userId], true);
        
        $stats['sources'] = array_column($sources, 'count', 'source');
        
        return $stats;
    }
    
    /**
     * Enriquecer item da biblioteca com dados adicionais
     */
    private static function enrichLibraryItem(array $item): array 
    {
        // Buscar fontes
        $sources = Database::query(
            "SELECT * FROM manga_sources WHERE manga_id = :manga_id",
            ['manga_id' => $item['manga_id']],
            true
        );
        
        // Buscar gêneros
        $genres = Database::query(
            "SELECT genre FROM manga_genres WHERE manga_id = :manga_id",
            ['manga_id' => $item['manga_id']],
            true
        );
        
        $item['sources'] = $sources;
        $item['genres'] = array_column($genres, 'genre');
        
        return $item;
    }
    
    /**
     * Migrar dados da sessão para o banco
     */
    public static function migrateFromSession(int $userId = null): array 
    {
        $userId = $userId ?? self::$defaultUserId;
        
        if (!isset($_SESSION['mangas']) || empty($_SESSION['mangas'])) {
            return ['migrated' => 0, 'errors' => []];
        }
        
        $migrated = 0;
        $errors = [];
        
        foreach ($_SESSION['mangas'] as $sessionManga) {
            try {
                // Converter formato da sessão para formato da API
                $mangaData = [
                    'title' => $sessionManga['nome'] ?? '',
                    'type' => $sessionManga['type'] ?? 'manga',
                    'source' => $sessionManga['source'] ?? 'session',
                    'id' => $sessionManga['id'] ?? uniqid(),
                    'synopsis' => $sessionManga['comentario'] ?? null,
                    'status' => self::convertSessionStatus($sessionManga['status'] ?? 'pretendo'),
                    'chapters' => []
                ];
                
                // Se tem dados da API, usar eles
                if (isset($sessionManga['api_data'])) {
                    $mangaData = array_merge($mangaData, $sessionManga['api_data']);
                }
                
                $result = self::addManga($mangaData, $userId);
                
                if ($result['success']) {
                    // Atualizar progresso se disponível
                    if (isset($sessionManga['capitulo_atual']) && $result['library_id']) {
                        self::updateProgress($result['library_id'], (int)$sessionManga['capitulo_atual'], $userId);
                    }
                    
                    $migrated++;
                } else {
                    $errors[] = "Erro ao migrar '{$mangaData['title']}': {$result['message']}";
                }
                
            } catch (Exception $e) {
                $errors[] = "Erro ao migrar manga: " . $e->getMessage();
            }
        }
        
        return ['migrated' => $migrated, 'errors' => $errors];
    }
    
    /**
     * Converter status da sessão para status do banco
     */
    private static function convertSessionStatus(string $sessionStatus): string 
    {
        $mapping = [
            'lendo' => 'reading',
            'completo' => 'completed',
            'pausado' => 'on_hold',
            'dropado' => 'dropped',
            'pretendo' => 'plan_to_read'
        ];
        
        return $mapping[$sessionStatus] ?? 'plan_to_read';
    }
}