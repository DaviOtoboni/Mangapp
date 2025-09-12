<?php
/**
 * Gerenciador de Capítulos
 * 
 * Esta classe gerencia capítulos de mangás/manhwas/manhuas
 * com suporte a múltiplas fontes de dados.
 */

class ChapterManager 
{
    /**
     * Obter capítulos de um manga
     */
    public static function getChapters(int $mangaId, string $source = null): array 
    {
        $sql = "
            SELECT * FROM manga_chapters 
            WHERE manga_id = :manga_id
        ";
        
        $params = ['manga_id' => $mangaId];
        
        if ($source) {
            $sql .= " AND source = :source";
            $params['source'] = $source;
        }
        
        $sql .= " ORDER BY 
            CASE 
                WHEN chapter_number REGEXP '^[0-9]+$' THEN CAST(chapter_number AS UNSIGNED)
                ELSE 999999 
            END ASC,
            chapter_number ASC,
            published_at DESC
        ";
        
        return Database::query($sql, $params, true);
    }
    
    /**
     * Obter capítulos de uma fonte específica
     */
    public static function getChaptersFromSource(string $source, string $externalId): array 
    {
        try {
            // Sistema local apenas - APIs externas removidas
            if (false) { // Desabilitado
                // Código removido
                    return $details['chapters'];
                }
            }
            
            return [];
            
        } catch (Exception $e) {
            error_log("Erro ao buscar capítulos da fonte $source: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Sincronizar capítulos de uma fonte externa
     */
    public static function syncChaptersFromSource(int $mangaId, string $source, string $externalId): array 
    {
        try {
            $externalChapters = self::getChaptersFromSource($source, $externalId);
            
            if (empty($externalChapters)) {
                return ['synced' => 0, 'errors' => ['Nenhum capítulo encontrado na fonte']];
            }
            
            $synced = 0;
            $errors = [];
            
            foreach ($externalChapters as $chapter) {
                try {
                    // Verificar se capítulo já existe
                    $existing = Database::queryOne(
                        "SELECT id FROM manga_chapters 
                         WHERE manga_id = :manga_id 
                         AND source = :source 
                         AND external_chapter_id = :external_id",
                        [
                            'manga_id' => $mangaId,
                            'source' => $source,
                            'external_id' => $chapter['id'] ?? ''
                        ]
                    );
                    
                    if (!$existing) {
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
                        
                        $synced++;
                    }
                    
                } catch (Exception $e) {
                    $errors[] = "Erro ao sincronizar capítulo {$chapter['chapter']}: " . $e->getMessage();
                }
            }
            
            return ['synced' => $synced, 'errors' => $errors];
            
        } catch (Exception $e) {
            return ['synced' => 0, 'errors' => ['Erro na sincronização: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Marcar capítulo como lido
     */
    public static function markAsRead(int $chapterId, int $userId = 1): bool 
    {
        // Por enquanto, vamos atualizar o progresso na biblioteca
        // Buscar o manga_id do capítulo
        $chapter = Database::queryOne(
            "SELECT manga_id, chapter_number FROM manga_chapters WHERE id = :id",
            ['id' => $chapterId]
        );
        
        if (!$chapter) {
            return false;
        }
        
        // Buscar entrada na biblioteca
        $libraryEntry = Database::queryOne(
            "SELECT id, progress FROM user_library 
             WHERE user_id = :user_id AND manga_id = :manga_id",
            ['user_id' => $userId, 'manga_id' => $chapter['manga_id']]
        );
        
        if ($libraryEntry) {
            $chapterNum = (int)$chapter['chapter_number'];
            $currentProgress = (int)$libraryEntry['progress'];
            
            // Atualizar progresso se este capítulo é maior que o atual
            if ($chapterNum > $currentProgress) {
                return UserLibrary::updateProgress($libraryEntry['id'], $chapterNum, $userId);
            }
        }
        
        return true;
    }
    
    /**
     * Obter próximo capítulo
     */
    public static function getNextChapter(int $chapterId): ?array 
    {
        $currentChapter = Database::queryOne(
            "SELECT manga_id, chapter_number FROM manga_chapters WHERE id = :id",
            ['id' => $chapterId]
        );
        
        if (!$currentChapter) {
            return null;
        }
        
        return Database::queryOne("
            SELECT * FROM manga_chapters 
            WHERE manga_id = :manga_id 
            AND (
                CASE 
                    WHEN chapter_number REGEXP '^[0-9]+$' THEN CAST(chapter_number AS UNSIGNED)
                    ELSE 999999 
                END > 
                CASE 
                    WHEN :current_chapter REGEXP '^[0-9]+$' THEN CAST(:current_chapter AS UNSIGNED)
                    ELSE 999999 
                END
            )
            ORDER BY 
                CASE 
                    WHEN chapter_number REGEXP '^[0-9]+$' THEN CAST(chapter_number AS UNSIGNED)
                    ELSE 999999 
                END ASC
            LIMIT 1
        ", [
            'manga_id' => $currentChapter['manga_id'],
            'current_chapter' => $currentChapter['chapter_number']
        ]);
    }
    
    /**
     * Obter capítulo anterior
     */
    public static function getPreviousChapter(int $chapterId): ?array 
    {
        $currentChapter = Database::queryOne(
            "SELECT manga_id, chapter_number FROM manga_chapters WHERE id = :id",
            ['id' => $chapterId]
        );
        
        if (!$currentChapter) {
            return null;
        }
        
        return Database::queryOne("
            SELECT * FROM manga_chapters 
            WHERE manga_id = :manga_id 
            AND (
                CASE 
                    WHEN chapter_number REGEXP '^[0-9]+$' THEN CAST(chapter_number AS UNSIGNED)
                    ELSE 999999 
                END < 
                CASE 
                    WHEN :current_chapter REGEXP '^[0-9]+$' THEN CAST(:current_chapter AS UNSIGNED)
                    ELSE 999999 
                END
            )
            ORDER BY 
                CASE 
                    WHEN chapter_number REGEXP '^[0-9]+$' THEN CAST(chapter_number AS UNSIGNED)
                    ELSE 999999 
                END DESC
            LIMIT 1
        ", [
            'manga_id' => $currentChapter['manga_id'],
            'current_chapter' => $currentChapter['chapter_number']
        ]);
    }
    
    /**
     * Obter estatísticas de capítulos
     */
    public static function getChapterStats(int $mangaId): array 
    {
        $stats = Database::queryOne("
            SELECT 
                COUNT(*) as total_chapters,
                COUNT(DISTINCT source) as sources_count,
                COUNT(DISTINCT language) as languages_count,
                MIN(published_at) as first_published,
                MAX(published_at) as last_published
            FROM manga_chapters 
            WHERE manga_id = :manga_id
        ", ['manga_id' => $mangaId], true);
        
        // Capítulos por fonte
        $bySource = Database::query("
            SELECT source, COUNT(*) as count 
            FROM manga_chapters 
            WHERE manga_id = :manga_id 
            GROUP BY source
        ", ['manga_id' => $mangaId], true);
        
        // Capítulos por idioma
        $byLanguage = Database::query("
            SELECT language, COUNT(*) as count 
            FROM manga_chapters 
            WHERE manga_id = :manga_id 
            GROUP BY language
        ", ['manga_id' => $mangaId], true);
        
        $stats['by_source'] = array_column($bySource, 'count', 'source');
        $stats['by_language'] = array_column($byLanguage, 'count', 'language');
        
        return $stats;
    }
    
    /**
     * Buscar capítulos por termo
     */
    public static function searchChapters(int $mangaId, string $query): array 
    {
        return Database::query("
            SELECT * FROM manga_chapters 
            WHERE manga_id = :manga_id 
            AND (
                chapter_number LIKE :query 
                OR title LIKE :query
            )
            ORDER BY 
                CASE 
                    WHEN chapter_number REGEXP '^[0-9]+$' THEN CAST(chapter_number AS UNSIGNED)
                    ELSE 999999 
                END ASC
        ", [
            'manga_id' => $mangaId,
            'query' => "%$query%"
        ]);
    }
    
    /**
     * Obter capítulos recentes
     */
    public static function getRecentChapters(int $limit = 10): array 
    {
        return Database::query("
            SELECT 
                mc.*,
                m.title as manga_title,
                m.content_type
            FROM manga_chapters mc
            JOIN manga m ON mc.manga_id = m.id
            WHERE mc.published_at IS NOT NULL
            ORDER BY mc.published_at DESC
            LIMIT :limit
        ", ['limit' => $limit]);
    }
}