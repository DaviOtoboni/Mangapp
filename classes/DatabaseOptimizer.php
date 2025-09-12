<?php
/**
 * Otimizador de Consultas de Banco para Múltiplas Fontes
 * 
 * Implementa otimizações de performance para consultas
 * envolvendo múltiplas APIs e fontes de dados.
 */

class DatabaseOptimizer 
{
    private ?Database $db = null;
    private ?CacheManager $cache = null;
    private array $config;
    private array $queryStats = [];
    
    public function __construct(array $config = []) 
    {
        $this->config = array_merge([
            'enable_query_cache' => true,
            'cache_ttl' => 3600,
            'batch_size' => 100,
            'enable_prepared_statements' => true,
            'enable_query_optimization' => true,
            'log_slow_queries' => true,
            'slow_query_threshold' => 1.0, // seconds
            'enable_connection_pooling' => false
        ], $config);
        
        if (class_exists('Database')) {
            $this->db = Database::getInstance();
        }
        
        if (class_exists('CacheManager')) {
            $this->cache = CacheManager::getInstance();
        }
    }
    
    /**
     * Buscar mangás otimizado para múltiplas fontes
     */
    public function searchMangasOptimized(array $criteria = [], array $options = []): array 
    {
        $startTime = microtime(true);
        
        try {
            // Gerar chave de cache
            $cacheKey = $this->generateSearchCacheKey($criteria, $options);
            
            // Verificar cache primeiro
            if ($this->config['enable_query_cache'] && $this->cache) {
                $cached = $this->cache->get($cacheKey);
                if ($cached !== null) {
                    $this->recordQueryStats('search_cached', microtime(true) - $startTime);
                    return $cached;
                }
            }
            
            // Construir query otimizada
            $query = $this->buildOptimizedSearchQuery($criteria, $options);
            
            // Executar query
            $results = $this->executeOptimizedQuery($query['sql'], $query['params']);
            
            // Processar resultados
            $processedResults = $this->processSearchResults($results, $options);
            
            // Cache dos resultados
            if ($this->config['enable_query_cache'] && $this->cache && !empty($processedResults)) {
                $this->cache->set($cacheKey, $processedResults, $this->config['cache_ttl']);
            }
            
            $this->recordQueryStats('search_db', microtime(true) - $startTime);
            
            return $processedResults;
            
        } catch (Exception $e) {
            $this->recordQueryStats('search_error', microtime(true) - $startTime);
            error_log("Erro na busca otimizada: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Buscar mangás por múltiplas fontes em batch
     */
    public function getMangasBySourcesBatch(array $sourceIds): array 
    {
        if (empty($sourceIds)) {
            return [];
        }
        
        $startTime = microtime(true);
        
        try {
            // Dividir em batches para evitar queries muito grandes
            $batches = array_chunk($sourceIds, $this->config['batch_size']);
            $allResults = [];
            
            foreach ($batches as $batch) {
                $results = $this->getMangasBatchInternal($batch);
                $allResults = array_merge($allResults, $results);
            }
            
            $this->recordQueryStats('batch_fetch', microtime(true) - $startTime);
            
            return $allResults;
            
        } catch (Exception $e) {
            $this->recordQueryStats('batch_error', microtime(true) - $startTime);
            throw $e;
        }
    }
    
    /**
     * Atualizar múltiplos mangás em batch
     */
    public function updateMangasBatch(array $updates): bool 
    {
        if (empty($updates)) {
            return true;
        }
        
        $startTime = microtime(true);
        
        try {
            if (!$this->db) {
                throw new Exception("Database não disponível");
            }
            
            // Iniciar transação
            $this->db->beginTransaction();
            
            // Preparar statement uma vez
            $updateSql = $this->buildBatchUpdateQuery();
            $stmt = $this->db->prepare($updateSql);
            
            foreach ($updates as $update) {
                $params = $this->prepareBatchUpdateParams($update);
                $stmt->execute($params);
            }
            
            // Commit da transação
            $this->db->commit();
            
            $this->recordQueryStats('batch_update', microtime(true) - $startTime);
            
            return true;
            
        } catch (Exception $e) {
            if ($this->db) {
                $this->db->rollback();
            }
            $this->recordQueryStats('batch_update_error', microtime(true) - $startTime);
            throw $e;
        }
    }
    
    /**
     * Obter estatísticas de mangás por fonte
     */
    public function getMangaStatsBySource(): array 
    {
        $cacheKey = 'manga_stats_by_source';
        
        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }
        
        $startTime = microtime(true);
        
        try {
            $sql = "
                SELECT 
                    source,
                    content_type,
                    COUNT(*) as total,
                    COUNT(CASE WHEN status = 'lendo' THEN 1 END) as reading,
                    COUNT(CASE WHEN status = 'completado' THEN 1 END) as completed,
                    COUNT(CASE WHEN status = 'pretendo' THEN 1 END) as plan_to_read,
                    AVG(CASE WHEN score > 0 THEN score END) as avg_score,
                    MAX(data_criacao) as last_added
                FROM manga 
                GROUP BY source, content_type
                ORDER BY source, content_type
            ";
            
            $results = $this->executeOptimizedQuery($sql);
            
            // Processar resultados
            $stats = [];
            foreach ($results as $row) {
                $source = $row['source'];
                if (!isset($stats[$source])) {
                    $stats[$source] = [
                        'total' => 0,
                        'types' => [],
                        'status_counts' => [
                            'reading' => 0,
                            'completed' => 0,
                            'plan_to_read' => 0
                        ],
                        'avg_score' => 0,
                        'last_added' => null
                    ];
                }
                
                $stats[$source]['total'] += (int)$row['total'];
                $stats[$source]['types'][$row['content_type']] = (int)$row['total'];
                $stats[$source]['status_counts']['reading'] += (int)$row['reading'];
                $stats[$source]['status_counts']['completed'] += (int)$row['completed'];
                $stats[$source]['status_counts']['plan_to_read'] += (int)$row['plan_to_read'];
                
                if ($row['avg_score']) {
                    $stats[$source]['avg_score'] = round((float)$row['avg_score'], 2);
                }
                
                if ($row['last_added'] && (!$stats[$source]['last_added'] || $row['last_added'] > $stats[$source]['last_added'])) {
                    $stats[$source]['last_added'] = $row['last_added'];
                }
            }
            
            // Cache por 1 hora
            if ($this->cache) {
                $this->cache->set($cacheKey, $stats, 3600);
            }
            
            $this->recordQueryStats('stats_query', microtime(true) - $startTime);
            
            return $stats;
            
        } catch (Exception $e) {
            $this->recordQueryStats('stats_error', microtime(true) - $startTime);
            throw $e;
        }
    }
    
    /**
     * Construir query otimizada para busca
     */
    private function buildOptimizedSearchQuery(array $criteria, array $options): array 
    {
        $sql = "SELECT m.*, ms.external_id, ms.source as manga_source FROM manga m";
        $joins = [];
        $where = [];
        $params = [];
        $orderBy = [];
        
        // Join com manga_sources se necessário
        if (isset($criteria['source']) || isset($criteria['external_id'])) {
            $joins[] = "LEFT JOIN manga_sources ms ON m.id = ms.manga_id";
        } else {
            $sql .= ", manga_sources ms WHERE m.id = ms.manga_id";
        }
        
        // Filtros
        if (isset($criteria['title']) && !empty($criteria['title'])) {
            $where[] = "(m.nome LIKE ? OR m.titulo_ingles LIKE ? OR m.titulo_original LIKE ?)";
            $searchTerm = '%' . $criteria['title'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (isset($criteria['source'])) {
            $where[] = "ms.source = ?";
            $params[] = $criteria['source'];
        }
        
        if (isset($criteria['content_type'])) {
            $where[] = "m.content_type = ?";
            $params[] = $criteria['content_type'];
        }
        
        if (isset($criteria['status'])) {
            $where[] = "m.status = ?";
            $params[] = $criteria['status'];
        }
        
        if (isset($criteria['origin_country'])) {
            $where[] = "m.origin_country = ?";
            $params[] = $criteria['origin_country'];
        }
        
        // Filtro por score mínimo
        if (isset($criteria['min_score']) && $criteria['min_score'] > 0) {
            $where[] = "m.score >= ?";
            $params[] = $criteria['min_score'];
        }
        
        // Construir SQL final
        if (!empty($joins)) {
            $sql .= " " . implode(" ", $joins);
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        // Ordenação
        $sortBy = $options['sort'] ?? 'relevance';
        switch ($sortBy) {
            case 'title':
                $orderBy[] = "m.nome ASC";
                break;
            case 'score':
                $orderBy[] = "m.score DESC, m.nome ASC";
                break;
            case 'date_added':
                $orderBy[] = "m.data_criacao DESC";
                break;
            case 'status':
                $orderBy[] = "m.status ASC, m.nome ASC";
                break;
            default: // relevance
                if (isset($criteria['title'])) {
                    // Ordenar por relevância do título
                    $orderBy[] = "
                        CASE 
                            WHEN m.nome LIKE ? THEN 1
                            WHEN m.titulo_ingles LIKE ? THEN 2
                            WHEN m.titulo_original LIKE ? THEN 3
                            ELSE 4
                        END ASC, m.score DESC, m.nome ASC
                    ";
                    $exactMatch = $criteria['title'];
                    $params[] = $exactMatch;
                    $params[] = $exactMatch;
                    $params[] = $exactMatch;
                } else {
                    $orderBy[] = "m.score DESC, m.nome ASC";
                }
        }
        
        if (!empty($orderBy)) {
            $sql .= " ORDER BY " . implode(", ", $orderBy);
        }
        
        // Limite
        if (isset($options['limit']) && $options['limit'] > 0) {
            $sql .= " LIMIT ?";
            $params[] = (int)$options['limit'];
            
            if (isset($options['offset']) && $options['offset'] > 0) {
                $sql .= " OFFSET ?";
                $params[] = (int)$options['offset'];
            }
        }
        
        return ['sql' => $sql, 'params' => $params];
    }
    
    /**
     * Executar query otimizada
     */
    private function executeOptimizedQuery(string $sql, array $params = []): array 
    {
        if (!$this->db) {
            throw new Exception("Database não disponível");
        }
        
        $startTime = microtime(true);
        
        try {
            if ($this->config['enable_prepared_statements'] && !empty($params)) {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $results = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }
            
            $executionTime = microtime(true) - $startTime;
            
            // Log queries lentas
            if ($this->config['log_slow_queries'] && $executionTime > $this->config['slow_query_threshold']) {
                error_log("Query lenta detectada ({$executionTime}s): " . substr($sql, 0, 200));
            }
            
            return $results;
            
        } catch (Exception $e) {
            error_log("Erro na execução da query: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Processar resultados da busca
     */
    private function processSearchResults(array $results, array $options): array 
    {
        if (empty($results)) {
            return [];
        }
        
        $processed = [];
        
        foreach ($results as $row) {
            // Converter dados do banco para formato padrão
            $manga = [
                'id' => $row['id'],
                'nome' => $row['nome'],
                'titulo_ingles' => $row['titulo_ingles'],
                'titulo_original' => $row['titulo_original'],
                'source' => $row['manga_source'] ?? $row['source'] ?? 'local',
                'external_id' => $row['external_id'],
                'content_type' => $row['content_type'] ?? 'manga',
                'origin_country' => $row['origin_country'] ?? 'Japan',
                'status' => $row['status'],
                'score' => (float)($row['score'] ?? 0),
                'sinopse' => $row['sinopse'],
                'generos' => $this->parseGenres($row['generos'] ?? ''),
                'autores' => $this->parseAuthors($row['autores'] ?? ''),
                'capa_url' => $row['capa_url'],
                'capa_local' => $row['capa_local'],
                'data_criacao' => $row['data_criacao'],
                'capitulo_atual' => (int)($row['capitulo_atual'] ?? 0),
                'capitulos_total' => (int)($row['capitulos_total'] ?? 0),
                'em_lancamento' => (bool)($row['em_lancamento'] ?? false)
            ];
            
            $processed[] = $manga;
        }
        
        return $processed;
    }
    
    /**
     * Buscar mangás em batch interno
     */
    private function getMangasBatchInternal(array $ids): array 
    {
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        $sql = "
            SELECT m.*, ms.external_id, ms.source as manga_source 
            FROM manga m 
            LEFT JOIN manga_sources ms ON m.id = ms.manga_id 
            WHERE m.id IN ({$placeholders})
        ";
        
        return $this->executeOptimizedQuery($sql, $ids);
    }
    
    /**
     * Construir query para update em batch
     */
    private function buildBatchUpdateQuery(): string 
    {
        return "
            UPDATE manga SET 
                nome = ?, titulo_ingles = ?, titulo_original = ?,
                sinopse = ?, score = ?, status_publicacao = ?,
                generos = ?, autores = ?, capa_url = ?,
                capitulos_total = ?, data_atualizacao = NOW()
            WHERE id = ?
        ";
    }
    
    /**
     * Preparar parâmetros para update em batch
     */
    private function prepareBatchUpdateParams(array $update): array 
    {
        return [
            $update['nome'] ?? '',
            $update['titulo_ingles'] ?? null,
            $update['titulo_original'] ?? null,
            $update['sinopse'] ?? null,
            $update['score'] ?? 0,
            $update['status_publicacao'] ?? null,
            $this->serializeGenres($update['generos'] ?? []),
            $this->serializeAuthors($update['autores'] ?? []),
            $update['capa_url'] ?? null,
            $update['capitulos_total'] ?? 0,
            $update['id']
        ];
    }
    
    /**
     * Gerar chave de cache para busca
     */
    private function generateSearchCacheKey(array $criteria, array $options): string 
    {
        return 'search_optimized_' . md5(serialize($criteria) . serialize($options));
    }
    
    /**
     * Registrar estatísticas de query
     */
    private function recordQueryStats(string $type, float $executionTime): void 
    {
        if (!isset($this->queryStats[$type])) {
            $this->queryStats[$type] = [
                'count' => 0,
                'total_time' => 0,
                'avg_time' => 0,
                'max_time' => 0
            ];
        }
        
        $this->queryStats[$type]['count']++;
        $this->queryStats[$type]['total_time'] += $executionTime;
        $this->queryStats[$type]['avg_time'] = $this->queryStats[$type]['total_time'] / $this->queryStats[$type]['count'];
        $this->queryStats[$type]['max_time'] = max($this->queryStats[$type]['max_time'], $executionTime);
    }
    
    /**
     * Obter estatísticas de performance
     */
    public function getPerformanceStats(): array 
    {
        return $this->queryStats;
    }
    
    /**
     * Parsear gêneros do banco
     */
    private function parseGenres(string $genres): array 
    {
        if (empty($genres)) {
            return [];
        }
        
        $decoded = json_decode($genres, true);
        return is_array($decoded) ? $decoded : explode(',', $genres);
    }
    
    /**
     * Parsear autores do banco
     */
    private function parseAuthors(string $authors): array 
    {
        if (empty($authors)) {
            return [];
        }
        
        $decoded = json_decode($authors, true);
        return is_array($decoded) ? $decoded : explode(',', $authors);
    }
    
    /**
     * Serializar gêneros para o banco
     */
    private function serializeGenres(array $genres): string 
    {
        return json_encode($genres);
    }
    
    /**
     * Serializar autores para o banco
     */
    private function serializeAuthors(array $authors): string 
    {
        return json_encode($authors);
    }
    
    /**
     * Otimizar consultas com índices compostos
     */
    public function optimizeIndexes(): bool 
    {
        if (!$this->db) {
            return false;
        }
        
        try {
            // Índices para performance de busca multi-fonte
            $indexes = [
                "CREATE INDEX IF NOT EXISTS idx_manga_search ON manga (nome, titulo_ingles, titulo_original)",
                "CREATE INDEX IF NOT EXISTS idx_manga_source_type ON manga (source, content_type)",
                "CREATE INDEX IF NOT EXISTS idx_manga_status_score ON manga (status, score DESC)",
                "CREATE INDEX IF NOT EXISTS idx_manga_country_type ON manga (origin_country, content_type)",
                "CREATE INDEX IF NOT EXISTS idx_manga_sources_lookup ON manga_sources (source, external_id)",
                "CREATE INDEX IF NOT EXISTS idx_manga_sources_manga ON manga_sources (manga_id, source)",
                "CREATE INDEX IF NOT EXISTS idx_manga_date_created ON manga (data_criacao DESC)",
                "CREATE INDEX IF NOT EXISTS idx_manga_composite_search ON manga (content_type, status, score DESC, nome)"
            ];
            
            foreach ($indexes as $sql) {
                $this->db->exec($sql);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Erro ao otimizar índices: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Busca otimizada com full-text search
     */
    public function searchMangasFullText(string $query, array $options = []): array 
    {
        $startTime = microtime(true);
        
        try {
            // Preparar query para full-text search
            $searchTerms = $this->prepareSearchTerms($query);
            
            $sql = "
                SELECT m.*, ms.external_id, ms.source as manga_source,
                       MATCH(m.nome, m.titulo_ingles, m.titulo_original, m.sinopse) AGAINST (? IN BOOLEAN MODE) as relevance_score
                FROM manga m
                LEFT JOIN manga_sources ms ON m.id = ms.manga_id
                WHERE MATCH(m.nome, m.titulo_ingles, m.titulo_original, m.sinopse) AGAINST (? IN BOOLEAN MODE)
            ";
            
            $params = [$searchTerms, $searchTerms];
            
            // Adicionar filtros adicionais
            if (isset($options['content_type'])) {
                $sql .= " AND m.content_type = ?";
                $params[] = $options['content_type'];
            }
            
            if (isset($options['source'])) {
                $sql .= " AND ms.source = ?";
                $params[] = $options['source'];
            }
            
            $sql .= " ORDER BY relevance_score DESC, m.score DESC";
            
            if (isset($options['limit'])) {
                $sql .= " LIMIT ?";
                $params[] = (int)$options['limit'];
            }
            
            $results = $this->executeOptimizedQuery($sql, $params);
            $processed = $this->processSearchResults($results, $options);
            
            $this->recordQueryStats('fulltext_search', microtime(true) - $startTime);
            
            return $processed;
            
        } catch (Exception $e) {
            error_log("Erro na busca full-text: " . $e->getMessage());
            // Fallback para busca normal
            return $this->searchMangasOptimized(['title' => $query], $options);
        }
    }
    
    /**
     * Preparar termos de busca para full-text
     */
    private function prepareSearchTerms(string $query): string 
    {
        // Limpar e preparar query
        $query = trim($query);
        $terms = explode(' ', $query);
        
        $prepared = [];
        foreach ($terms as $term) {
            $term = trim($term);
            if (strlen($term) >= 2) {
                // Adicionar wildcard para busca parcial
                $prepared[] = "+{$term}*";
            }
        }
        
        return implode(' ', $prepared);
    }
    
    /**
     * Busca com cache inteligente baseado em popularidade
     */
    public function searchWithIntelligentCache(array $criteria, array $options = []): array 
    {
        $cacheKey = $this->generateSearchCacheKey($criteria, $options);
        
        // Verificar cache primeiro
        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }
        
        // Executar busca
        $results = $this->searchMangasOptimized($criteria, $options);
        
        // Determinar TTL baseado na popularidade da busca
        $ttl = $this->calculateCacheTTL($criteria, $results);
        
        // Cache com TTL dinâmico
        if ($this->cache && !empty($results)) {
            $this->cache->set($cacheKey, $results, $ttl);
        }
        
        return $results;
    }
    
    /**
     * Calcular TTL do cache baseado na popularidade
     */
    private function calculateCacheTTL(array $criteria, array $results): int 
    {
        $baseTTL = $this->config['cache_ttl'];
        
        // TTL maior para buscas que retornam muitos resultados (populares)
        $resultCount = count($results);
        if ($resultCount > 50) {
            return $baseTTL * 2; // 2 horas
        } elseif ($resultCount > 20) {
            return $baseTTL * 1.5; // 1.5 horas
        } elseif ($resultCount < 5) {
            return $baseTTL / 2; // 30 minutos
        }
        
        return $baseTTL; // 1 hora padrão
    }
    
    /**
     * Pré-carregar dados relacionados em batch
     */
    public function preloadRelatedData(array $mangaIds): array 
    {
        if (empty($mangaIds)) {
            return [];
        }
        
        $startTime = microtime(true);
        
        try {
            $placeholders = str_repeat('?,', count($mangaIds) - 1) . '?';
            
            // Buscar dados relacionados em uma única query
            $sql = "
                SELECT 
                    m.id,
                    m.nome,
                    m.generos,
                    m.autores,
                    m.content_type,
                    ms.source,
                    ms.external_id,
                    COUNT(CASE WHEN m2.generos LIKE CONCAT('%', JSON_EXTRACT(m.generos, '$[0]'), '%') THEN 1 END) as related_count
                FROM manga m
                LEFT JOIN manga_sources ms ON m.id = ms.manga_id
                LEFT JOIN manga m2 ON m2.id != m.id AND m2.content_type = m.content_type
                WHERE m.id IN ({$placeholders})
                GROUP BY m.id, m.nome, m.generos, m.autores, m.content_type, ms.source, ms.external_id
            ";
            
            $results = $this->executeOptimizedQuery($sql, $mangaIds);
            
            $this->recordQueryStats('preload_related', microtime(true) - $startTime);
            
            return $results;
            
        } catch (Exception $e) {
            error_log("Erro no pré-carregamento de dados relacionados: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Otimizar consultas com connection pooling simulado
     */
    public function executeWithConnectionPool(callable $operation): mixed 
    {
        $startTime = microtime(true);
        
        try {
            // Simular connection pooling com reutilização de conexão
            if (!$this->db) {
                $this->db = Database::getInstance();
            }
            
            // Executar operação
            $result = $operation($this->db);
            
            $this->recordQueryStats('pooled_operation', microtime(true) - $startTime);
            
            return $result;
            
        } catch (Exception $e) {
            $this->recordQueryStats('pooled_error', microtime(true) - $startTime);
            throw $e;
        }
    }
    
    /**
     * Análise de performance de queries
     */
    public function analyzeQueryPerformance(): array 
    {
        $analysis = [
            'total_queries' => 0,
            'total_time' => 0,
            'avg_time' => 0,
            'slow_queries' => 0,
            'cache_hit_rate' => 0,
            'recommendations' => []
        ];
        
        foreach ($this->queryStats as $type => $stats) {
            $analysis['total_queries'] += $stats['count'];
            $analysis['total_time'] += $stats['total_time'];
            
            if ($stats['avg_time'] > $this->config['slow_query_threshold']) {
                $analysis['slow_queries']++;
                $analysis['recommendations'][] = "Otimizar queries do tipo '{$type}' (tempo médio: {$stats['avg_time']}s)";
            }
        }
        
        if ($analysis['total_queries'] > 0) {
            $analysis['avg_time'] = $analysis['total_time'] / $analysis['total_queries'];
        }
        
        // Calcular cache hit rate
        $cacheHits = $this->queryStats['search_cached']['count'] ?? 0;
        $totalSearches = ($this->queryStats['search_db']['count'] ?? 0) + $cacheHits;
        
        if ($totalSearches > 0) {
            $analysis['cache_hit_rate'] = ($cacheHits / $totalSearches) * 100;
        }
        
        // Recomendações baseadas na análise
        if ($analysis['cache_hit_rate'] < 50) {
            $analysis['recommendations'][] = "Aumentar TTL do cache ou melhorar estratégia de cache";
        }
        
        if ($analysis['avg_time'] > 0.5) {
            $analysis['recommendations'][] = "Considerar otimização de índices ou normalização de dados";
        }
        
        return $analysis;
    }