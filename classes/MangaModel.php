<?php
/**
 * Modelo de Dados para Manga/Manhwa/Manhua
 * 
 * Esta classe gerencia os dados de manga no banco de dados
 * com suporte a múltiplas fontes (Jikan, MangaDx, etc.)
 */

class MangaModel 
{
    /**
     * Salvar manga no banco de dados
     */
    public static function save(array $mangaData): int 
    {
        // Verificar se já existe por fonte e ID externo
        if (isset($mangaData['source']) && isset($mangaData['id'])) {
            $existing = self::findBySourceId($mangaData['source'], $mangaData['id']);
            if ($existing) {
                self::update($existing['id'], $mangaData);
                return $existing['id'];
            }
        }
        
        // Verificar duplicata por título e tipo
        $duplicate = self::findDuplicate($mangaData);
        if ($duplicate) {
            // Adicionar nova fonte ao manga existente
            self::addSource($duplicate['id'], $mangaData['source'], $mangaData['id']);
            self::update($duplicate['id'], $mangaData);
            return $duplicate['id'];
        }
        
        return Database::transaction(function() use ($mangaData) {
            // Inserir manga principal
            $mangaId = Database::insert('manga', [
                'title' => $mangaData['title'] ?? '',
                'title_english' => $mangaData['titleEnglish'] ?? null,
                'title_original' => $mangaData['titleOriginal'] ?? null,
                'synopsis' => $mangaData['synopsis'] ?? null,
                'content_type' => $mangaData['type'] ?? 'manga',
                'origin_country' => $mangaData['originCountry'] ?? 'Japan',
                'status' => $mangaData['status'] ?? 'Unknown',
                'content_rating' => $mangaData['contentRating'] ?? null,
                'year' => $mangaData['year'] ?? null,
                'score' => $mangaData['score'] ?? 0.00,
                'members' => $mangaData['members'] ?? 0,
                'cover_image' => $mangaData['coverImage'] ?? null
            ]);
            
            // Adicionar fonte
            if (isset($mangaData['source']) && isset($mangaData['id'])) {
                self::addSource($mangaId, $mangaData['source'], $mangaData['id']);
            }
            
            // Adicionar gêneros
            if (!empty($mangaData['genres'])) {
                self::addGenres($mangaId, $mangaData['genres']);
            }
            
            // Adicionar autores
            if (!empty($mangaData['authors'])) {
                self::addAuthors($mangaId, $mangaData['authors'], 'author');
            }
            
            // Adicionar artistas
            if (!empty($mangaData['artists'])) {
                self::addAuthors($mangaId, $mangaData['artists'], 'artist');
            }
            
            // Adicionar capítulos se fornecidos
            if (!empty($mangaData['chapters'])) {
                self::addChapters($mangaId, $mangaData['chapters'], $mangaData['source']);
            }
            
            return $mangaId;
        });
    }
    
    /**
     * Atualizar dados de um manga existente
     */
    public static function update(int $mangaId, array $mangaData): bool 
    {
        $updateData = [];
        
        // Campos que podem ser atualizados
        $updatableFields = [
            'title', 'title_english', 'title_original', 'synopsis',
            'content_type', 'origin_country', 'status', 'content_rating',
            'year', 'score', 'members', 'cover_image'
        ];
        
        foreach ($updatableFields as $field) {
            $dataKey = self::mapFieldToDataKey($field);
            if (isset($mangaData[$dataKey])) {
                $updateData[$field] = $mangaData[$dataKey];
            }
        }
        
        if (empty($updateData)) {
            return false;
        }
        
        $updated = Database::update('manga', $updateData, ['id' => $mangaId]);
        
        // Atualizar gêneros se fornecidos
        if (isset($mangaData['genres'])) {
            self::replaceGenres($mangaId, $mangaData['genres']);
        }
        
        // Atualizar autores se fornecidos
        if (isset($mangaData['authors']) || isset($mangaData['artists'])) {
            self::replaceAuthors($mangaId, $mangaData['authors'] ?? [], $mangaData['artists'] ?? []);
        }
        
        return $updated > 0;
    }
    
    /**
     * Buscar manga por ID
     */
    public static function findById(int $id): ?array 
    {
        $manga = Database::queryOne(
            "SELECT * FROM manga WHERE id = :id",
            ['id' => $id],
            true
        );
        
        if (!$manga) {
            return null;
        }
        
        return self::enrichMangaData($manga);
    }
    
    /**
     * Buscar manga por fonte e ID externo
     */
    public static function findBySourceId(string $source, string $externalId): ?array 
    {
        $result = Database::queryOne(
            "SELECT m.* FROM manga m 
             JOIN manga_sources ms ON m.id = ms.manga_id 
             WHERE ms.source = :source AND ms.external_id = :external_id",
            ['source' => $source, 'external_id' => $externalId],
            true
        );
        
        if (!$result) {
            return null;
        }
        
        return self::enrichMangaData($result);
    }
    
    /**
     * Buscar possível duplicata por título e tipo
     */
    public static function findDuplicate(array $mangaData): ?array 
    {
        $title = $mangaData['title'] ?? '';
        $type = $mangaData['type'] ?? 'manga';
        
        if (empty($title)) {
            return null;
        }
        
        // Buscar por título exato
        $result = Database::queryOne(
            "SELECT * FROM manga 
             WHERE title = :title AND content_type = :type",
            ['title' => $title, 'type' => $type],
            true
        );
        
        if ($result) {
            return self::enrichMangaData($result);
        }
        
        // Buscar por título similar (sem case sensitivity)
        $result = Database::queryOne(
            "SELECT * FROM manga 
             WHERE LOWER(title) = LOWER(:title) AND content_type = :type",
            ['title' => $title, 'type' => $type],
            true
        );
        
        if ($result) {
            return self::enrichMangaData($result);
        }
        
        return null;
    }
    
    /**
     * Buscar mangás por termo
     */
    public static function search(string $query, array $filters = []): array 
    {
        $sql = "SELECT DISTINCT m.* FROM manga m";
        $joins = [];
        $where = [];
        $params = [];
        
        // Busca por título
        if (!empty($query)) {
            $where[] = "(m.title LIKE :query OR m.title_english LIKE :query OR m.title_original LIKE :query)";
            $params['query'] = "%$query%";
        }
        
        // Filtro por tipo
        if (!empty($filters['type'])) {
            $where[] = "m.content_type = :type";
            $params['type'] = $filters['type'];
        }
        
        // Filtro por status
        if (!empty($filters['status'])) {
            $where[] = "m.status = :status";
            $params['status'] = $filters['status'];
        }
        
        // Filtro por gênero
        if (!empty($filters['genre'])) {
            $joins[] = "JOIN manga_genres mg ON m.id = mg.manga_id";
            $where[] = "mg.genre = :genre";
            $params['genre'] = $filters['genre'];
        }
        
        // Filtro por fonte
        if (!empty($filters['source'])) {
            $joins[] = "JOIN manga_sources ms ON m.id = ms.manga_id";
            $where[] = "ms.source = :source";
            $params['source'] = $filters['source'];
        }
        
        // Construir query
        if (!empty($joins)) {
            $sql .= " " . implode(" ", $joins);
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        // Ordenação
        $orderBy = $filters['sort'] ?? 'score';
        $orderDir = $filters['order'] ?? 'DESC';
        $sql .= " ORDER BY m.$orderBy $orderDir";
        
        // Limite
        $limit = $filters['limit'] ?? 20;
        $offset = $filters['offset'] ?? 0;
        $sql .= " LIMIT $limit OFFSET $offset";
        
        $results = Database::query($sql, $params, true);
        
        return array_map([self::class, 'enrichMangaData'], $results);
    }
    
    /**
     * Adicionar fonte a um manga
     */
    public static function addSource(int $mangaId, string $source, string $externalId, string $sourceUrl = null): void 
    {
        try {
            Database::insert('manga_sources', [
                'manga_id' => $mangaId,
                'source' => $source,
                'external_id' => $externalId,
                'source_url' => $sourceUrl
            ]);
        } catch (Exception $e) {
            // Ignorar se já existe (UNIQUE constraint)
            if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                throw $e;
            }
        }
    }
    
    /**
     * Adicionar gêneros a um manga
     */
    public static function addGenres(int $mangaId, array $genres): void 
    {
        foreach ($genres as $genre) {
            if (!empty($genre)) {
                try {
                    Database::insert('manga_genres', [
                        'manga_id' => $mangaId,
                        'genre' => $genre
                    ]);
                } catch (Exception $e) {
                    // Ignorar duplicatas
                }
            }
        }
    }
    
    /**
     * Substituir gêneros de um manga
     */
    public static function replaceGenres(int $mangaId, array $genres): void 
    {
        Database::transaction(function() use ($mangaId, $genres) {
            Database::delete('manga_genres', ['manga_id' => $mangaId]);
            self::addGenres($mangaId, $genres);
        });
    }
    
    /**
     * Adicionar autores/artistas a um manga
     */
    public static function addAuthors(int $mangaId, array $authors, string $role = 'author'): void 
    {
        foreach ($authors as $author) {
            if (!empty($author)) {
                try {
                    Database::insert('manga_authors', [
                        'manga_id' => $mangaId,
                        'name' => $author,
                        'role' => $role
                    ]);
                } catch (Exception $e) {
                    // Ignorar duplicatas
                }
            }
        }
    }
    
    /**
     * Substituir autores de um manga
     */
    public static function replaceAuthors(int $mangaId, array $authors, array $artists): void 
    {
        Database::transaction(function() use ($mangaId, $authors, $artists) {
            Database::delete('manga_authors', ['manga_id' => $mangaId]);
            self::addAuthors($mangaId, $authors, 'author');
            self::addAuthors($mangaId, $artists, 'artist');
        });
    }
    
    /**
     * Adicionar capítulos a um manga
     */
    public static function addChapters(int $mangaId, array $chapters, string $source): void 
    {
        foreach ($chapters as $chapter) {
            try {
                Database::insert('manga_chapters', [
                    'manga_id' => $mangaId,
                    'source' => $source,
                    'external_chapter_id' => $chapter['id'] ?? '',
                    'chapter_number' => $chapter['chapter'] ?? '',
                    'title' => $chapter['title'] ?? null,
                    'language' => $chapter['language'] ?? 'en',
                    'pages' => $chapter['pages'] ?? 0,
                    'published_at' => $chapter['published_at'] ?? null,
                    'external_url' => $chapter['external_url'] ?? null
                ]);
            } catch (Exception $e) {
                // Ignorar duplicatas
            }
        }
    }
    
    /**
     * Enriquecer dados do manga com informações relacionadas
     */
    private static function enrichMangaData(array $manga): array 
    {
        $mangaId = $manga['id'];
        
        // Buscar fontes
        $sources = Database::query(
            "SELECT * FROM manga_sources WHERE manga_id = :manga_id",
            ['manga_id' => $mangaId],
            true
        );
        
        // Buscar gêneros
        $genres = Database::query(
            "SELECT genre FROM manga_genres WHERE manga_id = :manga_id",
            ['manga_id' => $mangaId],
            true
        );
        
        // Buscar autores
        $authors = Database::query(
            "SELECT name, role FROM manga_authors WHERE manga_id = :manga_id",
            ['manga_id' => $mangaId],
            true
        );
        
        // Organizar dados
        $manga['sources'] = $sources;
        $manga['genres'] = array_column($genres, 'genre');
        $manga['authors'] = array_column(array_filter($authors, fn($a) => $a['role'] === 'author'), 'name');
        $manga['artists'] = array_column(array_filter($authors, fn($a) => $a['role'] === 'artist'), 'name');
        
        return $manga;
    }
    
    /**
     * Mapear campo do banco para chave dos dados
     */
    private static function mapFieldToDataKey(string $field): string 
    {
        $mapping = [
            'title_english' => 'titleEnglish',
            'title_original' => 'titleOriginal',
            'content_type' => 'type',
            'origin_country' => 'originCountry',
            'content_rating' => 'contentRating',
            'cover_image' => 'coverImage'
        ];
        
        return $mapping[$field] ?? $field;
    }
    
    /**
     * Obter estatísticas do banco
     */
    public static function getStats(): array 
    {
        $stats = Database::queryOne("
            SELECT 
                COUNT(*) as total_manga,
                COUNT(CASE WHEN content_type = 'manga' THEN 1 END) as manga_count,
                COUNT(CASE WHEN content_type = 'manhwa' THEN 1 END) as manhwa_count,
                COUNT(CASE WHEN content_type = 'manhua' THEN 1 END) as manhua_count,
                AVG(score) as avg_score
            FROM manga
        ", [], true);
        
        $sources = Database::query("
            SELECT source, COUNT(*) as count 
            FROM manga_sources 
            GROUP BY source
        ", [], true);
        
        $stats['sources'] = array_column($sources, 'count', 'source');
        
        return $stats;
    }
}