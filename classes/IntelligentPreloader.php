<?php
/**
 * Sistema de Pré-carregamento Inteligente
 * 
 * Implementa pré-carregamento baseado em padrões de uso
 * e popularidade para melhorar performance percebida.
 */

class IntelligentPreloader 
{
    private ?CacheManager $cache = null;
    private ?DatabaseOptimizer $dbOptimizer = null;
    private array $config;
    private array $userBehavior = [];
    private array $preloadStats = [];
    
    public function __construct(array $config = []) 
    {
        $this->config = array_merge([
            'enable_preloading' => true,
            'max_preload_items' => 20,
            'preload_threshold_score' => 0.7,
            'user_behavior_window' => 7, // days
            'popular_content_limit' => 50,
            'cache_preload_ttl' => 1800, // 30 minutes
            'background_preload' => true,
            'preload_strategies' => [
                'popular_content' => true,
                'user_patterns' => true,
                'related_content' => true,
                'trending' => true
            ]
        ], $config);
        
        if (class_exists('CacheManager')) {
            $this->cache = CacheManager::getInstance();
        }
        
        if (class_exists('DatabaseOptimizer')) {
            $this->dbOptimizer = new DatabaseOptimizer();
        }
        
        $this->loadUserBehavior();
    }
    
    /**
     * Executar pré-carregamento inteligente
     */
    public function executeIntelligentPreload(array $context = []): array 
    {
        if (!$this->config['enable_preloading']) {
            return [];
        }
        
        $startTime = microtime(true);
        $preloadedItems = [];
        
        try {
            // Obter candidatos para pré-carregamento
            $candidates = $this->getPreloadCandidates($context);
            
            // Priorizar candidatos
            $prioritized = $this->prioritizeCandidates($candidates, $context);
            
            // Limitar número de itens
            $toPreload = array_slice($prioritized, 0, $this->config['max_preload_items']);
            
            // Executar pré-carregamento
            foreach ($toPreload as $item) {
                $preloaded = $this->preloadItem($item);
                if ($preloaded) {
                    $preloadedItems[] = $preloaded;
                }
            }
            
            $this->recordPreloadStats('intelligent_preload', microtime(true) - $startTime, count($preloadedItems));
            
            return $preloadedItems;
            
        } catch (Exception $e) {
            error_log("Erro no pré-carregamento inteligente: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Pré-carregar conteúdo popular
     */
    public function preloadPopularContent(): array 
    {
        $cacheKey = 'preload_popular_content';
        
        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }
        
        $startTime = microtime(true);
        
        try {
            // Obter conteúdo popular baseado em estatísticas
            $popularItems = $this->getPopularContent();
            
            $preloaded = [];
            foreach ($popularItems as $item) {
                $preloadedItem = $this->preloadMangaDetails($item);
                if ($preloadedItem) {
                    $preloaded[] = $preloadedItem;
                }
            }
            
            // Cache por 30 minutos
            if ($this->cache) {
                $this->cache->set($cacheKey, $preloaded, $this->config['cache_preload_ttl']);
            }
            
            $this->recordPreloadStats('popular_content', microtime(true) - $startTime, count($preloaded));
            
            return $preloaded;
            
        } catch (Exception $e) {
            error_log("Erro no pré-carregamento de conteúdo popular: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Pré-carregar baseado em padrões do usuário
     */
    public function preloadUserPatterns(string $userId = null): array 
    {
        $userId = $userId ?: $this->getCurrentUserId();
        
        if (!$userId) {
            return [];
        }
        
        $cacheKey = "preload_user_patterns_{$userId}";
        
        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }
        
        $startTime = microtime(true);
        
        try {
            // Analisar padrões do usuário
            $patterns = $this->analyzeUserPatterns($userId);
            
            // Gerar recomendações baseadas nos padrões
            $recommendations = $this->generatePatternBasedRecommendations($patterns);
            
            $preloaded = [];
            foreach ($recommendations as $item) {
                $preloadedItem = $this->preloadMangaDetails($item);
                if ($preloadedItem) {
                    $preloaded[] = $preloadedItem;
                }
            }
            
            // Cache por 1 hora
            if ($this->cache) {
                $this->cache->set($cacheKey, $preloaded, 3600);
            }
            
            $this->recordPreloadStats('user_patterns', microtime(true) - $startTime, count($preloaded));
            
            return $preloaded;
            
        } catch (Exception $e) {
            error_log("Erro no pré-carregamento baseado em padrões: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Pré-carregar conteúdo relacionado
     */
    public function preloadRelatedContent(array $currentManga): array 
    {
        $cacheKey = 'preload_related_' . md5(serialize($currentManga));
        
        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }
        
        $startTime = microtime(true);
        
        try {
            // Encontrar conteúdo relacionado
            $related = $this->findRelatedContent($currentManga);
            
            $preloaded = [];
            foreach ($related as $item) {
                $preloadedItem = $this->preloadMangaDetails($item);
                if ($preloadedItem) {
                    $preloaded[] = $preloadedItem;
                }
            }
            
            // Cache por 2 horas
            if ($this->cache) {
                $this->cache->set($cacheKey, $preloaded, 7200);
            }
            
            $this->recordPreloadStats('related_content', microtime(true) - $startTime, count($preloaded));
            
            return $preloaded;
            
        } catch (Exception $e) {
            error_log("Erro no pré-carregamento de conteúdo relacionado: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obter candidatos para pré-carregamento
     */
    private function getPreloadCandidates(array $context): array 
    {
        $candidates = [];
        
        // Candidatos baseados em popularidade
        if ($this->config['preload_strategies']['popular_content']) {
            $popular = $this->getPopularContent();
            foreach ($popular as $item) {
                $candidates[] = [
                    'type' => 'popular',
                    'item' => $item,
                    'score' => $item['popularity_score'] ?? 0.5
                ];
            }
        }
        
        // Candidatos baseados em padrões do usuário
        if ($this->config['preload_strategies']['user_patterns']) {
            $userPatterns = $this->getUserPatternCandidates($context);
            foreach ($userPatterns as $item) {
                $candidates[] = [
                    'type' => 'user_pattern',
                    'item' => $item,
                    'score' => $item['pattern_score'] ?? 0.6
                ];
            }
        }
        
        // Candidatos de conteúdo relacionado
        if ($this->config['preload_strategies']['related_content'] && isset($context['current_manga'])) {
            $related = $this->findRelatedContent($context['current_manga']);
            foreach ($related as $item) {
                $candidates[] = [
                    'type' => 'related',
                    'item' => $item,
                    'score' => $item['similarity_score'] ?? 0.4
                ];
            }
        }
        
        // Candidatos trending
        if ($this->config['preload_strategies']['trending']) {
            $trending = $this->getTrendingContent();
            foreach ($trending as $item) {
                $candidates[] = [
                    'type' => 'trending',
                    'item' => $item,
                    'score' => $item['trending_score'] ?? 0.7
                ];
            }
        }
        
        return $candidates;
    }
    
    /**
     * Priorizar candidatos
     */
    private function prioritizeCandidates(array $candidates, array $context): array 
    {
        // Aplicar pesos baseados no contexto
        foreach ($candidates as &$candidate) {
            $candidate['final_score'] = $this->calculateFinalScore($candidate, $context);
        }
        
        // Ordenar por score final
        usort($candidates, function($a, $b) {
            return $b['final_score'] <=> $a['final_score'];
        });
        
        // Filtrar por threshold
        return array_filter($candidates, function($candidate) {
            return $candidate['final_score'] >= $this->config['preload_threshold_score'];
        });
    }
    
    /**
     * Calcular score final
     */
    private function calculateFinalScore(array $candidate, array $context): float 
    {
        $baseScore = $candidate['score'];
        
        // Pesos por tipo
        $typeWeights = [
            'popular' => 1.0,
            'user_pattern' => 1.2,
            'related' => 0.8,
            'trending' => 1.1
        ];
        
        $typeWeight = $typeWeights[$candidate['type']] ?? 1.0;
        
        // Ajustes baseados no contexto
        $contextBonus = 0;
        
        // Bonus se o usuário está navegando ativamente
        if (isset($context['user_active']) && $context['user_active']) {
            $contextBonus += 0.1;
        }
        
        // Bonus baseado no horário (usuários mais ativos em certas horas)
        $hour = (int)date('H');
        if ($hour >= 19 && $hour <= 23) { // Horário de pico
            $contextBonus += 0.05;
        }
        
        // Bonus baseado no tipo de conteúdo preferido do usuário
        if (isset($context['user_preferences'])) {
            $userPrefs = $context['user_preferences'];
            $itemType = $candidate['item']['content_type'] ?? 'manga';
            
            if (isset($userPrefs['preferred_types']) && in_array($itemType, $userPrefs['preferred_types'])) {
                $contextBonus += 0.15;
            }
        }
        
        return min(1.0, ($baseScore * $typeWeight) + $contextBonus);
    }
    
    /**
     * Pré-carregar item específico
     */
    private function preloadItem(array $candidate): ?array 
    {
        try {
            $item = $candidate['item'];
            
            switch ($candidate['type']) {
                case 'popular':
                case 'trending':
                case 'user_pattern':
                case 'related':
                    return $this->preloadMangaDetails($item);
                    
                default:
                    return null;
            }
            
        } catch (Exception $e) {
            error_log("Erro ao pré-carregar item: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Pré-carregar detalhes do mangá
     */
    private function preloadMangaDetails(array $manga): ?array 
    {
        try {
            $mangaId = $manga['id'] ?? null;
            $source = $manga['source'] ?? 'local';
            
            if (!$mangaId) {
                return null;
            }
            
            // Verificar se já está em cache
            $cacheKey = "preload_manga_{$source}_{$mangaId}";
            if ($this->cache && $this->cache->has($cacheKey)) {
                return $manga; // Já pré-carregado
            }
            
            // Simular carregamento de dados adicionais
            $enrichedData = $this->enrichMangaData($manga);
            
            // Salvar no cache de pré-carregamento
            if ($this->cache) {
                $this->cache->set($cacheKey, $enrichedData, $this->config['cache_preload_ttl']);
            }
            
            return $enrichedData;
            
        } catch (Exception $e) {
            error_log("Erro ao pré-carregar detalhes do mangá: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Enriquecer dados do mangá
     */
    private function enrichMangaData(array $manga): array 
    {
        // Adicionar dados calculados que são caros de computar
        $enriched = $manga;
        
        // Calcular score de popularidade
        $enriched['popularity_score'] = $this->calculatePopularityScore($manga);
        
        // Adicionar estatísticas de leitura
        $enriched['reading_stats'] = $this->getReadingStats($manga['id'] ?? null);
        
        // Adicionar informações de atualização
        $enriched['update_info'] = $this->getUpdateInfo($manga);
        
        // Pré-processar gêneros e tags
        $enriched['processed_genres'] = $this->processGenres($manga['generos'] ?? []);
        
        return $enriched;
    }
    
    /**
     * Obter conteúdo popular
     */
    private function getPopularContent(): array 
    {
        // Simular busca de conteúdo popular
        // Em implementação real, isso viria do banco de dados
        return [
            [
                'id' => 'popular_1',
                'nome' => 'Manga Popular 1',
                'popularity_score' => 0.9,
                'source' => 'jikan'
            ],
            [
                'id' => 'popular_2',
                'nome' => 'Manhwa Popular 1',
                'popularity_score' => 0.85,
                'source' => 'mangadx'
            ]
        ];
    }
    
    /**
     * Obter conteúdo trending
     */
    private function getTrendingContent(): array 
    {
        // Simular busca de conteúdo trending
        return [
            [
                'id' => 'trending_1',
                'nome' => 'Manga Trending 1',
                'trending_score' => 0.8,
                'source' => 'jikan'
            ]
        ];
    }
    
    /**
     * Analisar padrões do usuário
     */
    private function analyzeUserPatterns(string $userId): array 
    {
        // Simular análise de padrões
        return [
            'preferred_genres' => ['Action', 'Adventure'],
            'preferred_types' => ['manga', 'manhwa'],
            'reading_time' => ['19:00', '22:00'],
            'completion_rate' => 0.75
        ];
    }
    
    /**
     * Gerar recomendações baseadas em padrões
     */
    private function generatePatternBasedRecommendations(array $patterns): array 
    {
        // Simular geração de recomendações
        return [
            [
                'id' => 'pattern_1',
                'nome' => 'Recomendação Baseada em Padrão',
                'pattern_score' => 0.8,
                'source' => 'jikan'
            ]
        ];
    }
    
    /**
     * Encontrar conteúdo relacionado
     */
    private function findRelatedContent(array $manga): array 
    {
        // Simular busca de conteúdo relacionado
        return [
            [
                'id' => 'related_1',
                'nome' => 'Manga Relacionado 1',
                'similarity_score' => 0.7,
                'source' => 'jikan'
            ]
        ];
    }
    
    /**
     * Obter candidatos baseados em padrões do usuário
     */
    private function getUserPatternCandidates(array $context): array 
    {
        // Implementação simplificada
        return [];
    }
    
    /**
     * Calcular score de popularidade
     */
    private function calculatePopularityScore(array $manga): float 
    {
        $score = 0.5; // Base score
        
        if (isset($manga['score']) && $manga['score'] > 0) {
            $score += ($manga['score'] / 10) * 0.3;
        }
        
        if (isset($manga['members']) && $manga['members'] > 0) {
            $score += min(0.2, log($manga['members']) / 100);
        }
        
        return min(1.0, $score);
    }
    
    /**
     * Obter estatísticas de leitura
     */
    private function getReadingStats(?string $mangaId): array 
    {
        if (!$mangaId) {
            return [];
        }
        
        // Simular estatísticas
        return [
            'total_readers' => rand(100, 10000),
            'completion_rate' => rand(60, 95) / 100,
            'avg_rating' => rand(70, 95) / 10
        ];
    }
    
    /**
     * Obter informações de atualização
     */
    private function getUpdateInfo(array $manga): array 
    {
        return [
            'last_checked' => date('Y-m-d H:i:s'),
            'needs_update' => rand(0, 1) === 1,
            'update_frequency' => 'weekly'
        ];
    }
    
    /**
     * Processar gêneros
     */
    private function processGenres(array $genres): array 
    {
        $processed = [];
        
        foreach ($genres as $genre) {
            $processed[] = [
                'name' => is_array($genre) ? $genre['name'] : $genre,
                'slug' => strtolower(str_replace(' ', '-', is_array($genre) ? $genre['name'] : $genre)),
                'color' => $this->getGenreColor(is_array($genre) ? $genre['name'] : $genre)
            ];
        }
        
        return $processed;
    }
    
    /**
     * Obter cor do gênero
     */
    private function getGenreColor(string $genre): string 
    {
        $colors = [
            'Action' => '#ff6b6b',
            'Adventure' => '#4ecdc4',
            'Comedy' => '#ffe66d',
            'Drama' => '#a8e6cf',
            'Romance' => '#ff8b94'
        ];
        
        return $colors[$genre] ?? '#95a5a6';
    }
    
    /**
     * Carregar comportamento do usuário
     */
    private function loadUserBehavior(): void 
    {
        // Simular carregamento de comportamento do usuário
        $this->userBehavior = [
            'session_start' => time(),
            'pages_viewed' => 0,
            'searches_made' => 0,
            'items_clicked' => 0
        ];
    }
    
    /**
     * Obter ID do usuário atual
     */
    private function getCurrentUserId(): ?string 
    {
        return session_id() ?: null;
    }
    
    /**
     * Registrar estatísticas de pré-carregamento
     */
    private function recordPreloadStats(string $type, float $time, int $itemsPreloaded): void 
    {
        if (!isset($this->preloadStats[$type])) {
            $this->preloadStats[$type] = [
                'count' => 0,
                'total_time' => 0,
                'total_items' => 0,
                'avg_time' => 0,
                'avg_items' => 0
            ];
        }
        
        $stats = &$this->preloadStats[$type];
        $stats['count']++;
        $stats['total_time'] += $time;
        $stats['total_items'] += $itemsPreloaded;
        $stats['avg_time'] = $stats['total_time'] / $stats['count'];
        $stats['avg_items'] = $stats['total_items'] / $stats['count'];
    }
    
    /**
     * Obter estatísticas de pré-carregamento
     */
    public function getPreloadStats(): array 
    {
        return $this->preloadStats;
    }
    
    /**
     * Pré-carregamento baseado em machine learning simples
     */
    public function executeMLBasedPreload(array $userHistory = []): array 
    {
        $startTime = microtime(true);
        
        try {
            // Analisar padrões do histórico do usuário
            $patterns = $this->analyzeUserHistoryPatterns($userHistory);
            
            // Gerar predições baseadas nos padrões
            $predictions = $this->generateMLPredictions($patterns);
            
            // Pré-carregar baseado nas predições
            $preloaded = [];
            foreach ($predictions as $prediction) {
                if ($prediction['confidence'] > 0.7) {
                    $item = $this->preloadMangaDetails($prediction['manga']);
                    if ($item) {
                        $preloaded[] = array_merge($item, [
                            'ml_confidence' => $prediction['confidence'],
                            'prediction_reason' => $prediction['reason']
                        ]);
                    }
                }
            }
            
            $this->recordPreloadStats('ml_based', microtime(true) - $startTime, count($preloaded));
            
            return $preloaded;
            
        } catch (Exception $e) {
            error_log("Erro no pré-carregamento ML: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Analisar padrões do histórico do usuário
     */
    private function analyzeUserHistoryPatterns(array $history): array 
    {
        $patterns = [
            'genre_preferences' => [],
            'type_preferences' => [],
            'time_patterns' => [],
            'completion_patterns' => [],
            'rating_patterns' => []
        ];
        
        if (empty($history)) {
            return $patterns;
        }
        
        // Analisar preferências de gênero
        $genreCounts = [];
        foreach ($history as $item) {
            if (isset($item['generos'])) {
                foreach ($item['generos'] as $genre) {
                    $genreCounts[$genre] = ($genreCounts[$genre] ?? 0) + 1;
                }
            }
        }
        arsort($genreCounts);
        $patterns['genre_preferences'] = array_slice(array_keys($genreCounts), 0, 5);
        
        // Analisar preferências de tipo
        $typeCounts = [];
        foreach ($history as $item) {
            $type = $item['content_type'] ?? 'manga';
            $typeCounts[$type] = ($typeCounts[$type] ?? 0) + 1;
        }
        arsort($typeCounts);
        $patterns['type_preferences'] = array_keys($typeCounts);
        
        // Analisar padrões de horário (se disponível)
        $hourCounts = [];
        foreach ($history as $item) {
            if (isset($item['access_time'])) {
                $hour = (int)date('H', strtotime($item['access_time']));
                $hourCounts[$hour] = ($hourCounts[$hour] ?? 0) + 1;
            }
        }
        arsort($hourCounts);
        $patterns['time_patterns'] = array_slice(array_keys($hourCounts), 0, 3);
        
        return $patterns;
    }
    
    /**
     * Gerar predições baseadas em ML simples
     */
    private function generateMLPredictions(array $patterns): array 
    {
        $predictions = [];
        
        // Simular predições baseadas nos padrões
        $candidateManga = $this->getCandidateMangaForML($patterns);
        
        foreach ($candidateManga as $manga) {
            $confidence = $this->calculateMLConfidence($manga, $patterns);
            $reason = $this->generatePredictionReason($manga, $patterns);
            
            if ($confidence > 0.5) {
                $predictions[] = [
                    'manga' => $manga,
                    'confidence' => $confidence,
                    'reason' => $reason
                ];
            }
        }
        
        // Ordenar por confiança
        usort($predictions, function($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });
        
        return array_slice($predictions, 0, 10);
    }
    
    /**
     * Obter candidatos para ML
     */
    private function getCandidateMangaForML(array $patterns): array 
    {
        // Simular busca de candidatos baseada nos padrões
        $candidates = [];
        
        // Buscar por gêneros preferidos
        foreach ($patterns['genre_preferences'] as $genre) {
            $candidates = array_merge($candidates, $this->getMangaByGenre($genre));
        }
        
        // Buscar por tipos preferidos
        foreach ($patterns['type_preferences'] as $type) {
            $candidates = array_merge($candidates, $this->getMangaByType($type));
        }
        
        // Remover duplicatas
        $uniqueCandidates = [];
        $seen = [];
        
        foreach ($candidates as $candidate) {
            $id = $candidate['id'] ?? uniqid();
            if (!isset($seen[$id])) {
                $uniqueCandidates[] = $candidate;
                $seen[$id] = true;
            }
        }
        
        return array_slice($uniqueCandidates, 0, 50);
    }
    
    /**
     * Calcular confiança da predição ML
     */
    private function calculateMLConfidence(array $manga, array $patterns): float 
    {
        $confidence = 0.0;
        $factors = 0;
        
        // Fator gênero
        if (isset($manga['generos']) && !empty($patterns['genre_preferences'])) {
            $genreMatch = 0;
            foreach ($manga['generos'] as $genre) {
                if (in_array($genre, $patterns['genre_preferences'])) {
                    $genreMatch++;
                }
            }
            $genreScore = $genreMatch / max(1, count($manga['generos']));
            $confidence += $genreScore * 0.4;
            $factors++;
        }
        
        // Fator tipo
        if (isset($manga['content_type']) && !empty($patterns['type_preferences'])) {
            $typeIndex = array_search($manga['content_type'], $patterns['type_preferences']);
            if ($typeIndex !== false) {
                $typeScore = 1 - ($typeIndex / count($patterns['type_preferences']));
                $confidence += $typeScore * 0.3;
            }
            $factors++;
        }
        
        // Fator popularidade
        if (isset($manga['score']) && $manga['score'] > 0) {
            $popularityScore = min(1.0, $manga['score'] / 10);
            $confidence += $popularityScore * 0.2;
            $factors++;
        }
        
        // Fator novidade (mangás mais recentes têm bonus)
        if (isset($manga['data_criacao'])) {
            $daysSinceAdded = (time() - strtotime($manga['data_criacao'])) / 86400;
            $noveltyScore = max(0, 1 - ($daysSinceAdded / 30)); // Bonus para mangás adicionados nos últimos 30 dias
            $confidence += $noveltyScore * 0.1;
            $factors++;
        }
        
        return $factors > 0 ? $confidence / $factors : 0.0;
    }
    
    /**
     * Gerar razão da predição
     */
    private function generatePredictionReason(array $manga, array $patterns): string 
    {
        $reasons = [];
        
        if (isset($manga['generos']) && !empty($patterns['genre_preferences'])) {
            $matchingGenres = array_intersect($manga['generos'], $patterns['genre_preferences']);
            if (!empty($matchingGenres)) {
                $reasons[] = "Gêneros preferidos: " . implode(', ', $matchingGenres);
            }
        }
        
        if (isset($manga['content_type']) && in_array($manga['content_type'], $patterns['type_preferences'])) {
            $reasons[] = "Tipo preferido: " . $manga['content_type'];
        }
        
        if (isset($manga['score']) && $manga['score'] > 8) {
            $reasons[] = "Alta avaliação: " . $manga['score'];
        }
        
        return implode('; ', $reasons) ?: 'Baseado no perfil geral';
    }
    
    /**
     * Buscar mangá por gênero (simulado)
     */
    private function getMangaByGenre(string $genre): array 
    {
        // Simular busca por gênero
        return [
            [
                'id' => 'genre_' . $genre . '_1',
                'nome' => 'Manga ' . $genre . ' 1',
                'generos' => [$genre, 'Adventure'],
                'content_type' => 'manga',
                'score' => rand(70, 95) / 10
            ]
        ];
    }
    
    /**
     * Buscar mangá por tipo (simulado)
     */
    private function getMangaByType(string $type): array 
    {
        // Simular busca por tipo
        return [
            [
                'id' => 'type_' . $type . '_1',
                'nome' => ucfirst($type) . ' Popular 1',
                'generos' => ['Action', 'Drama'],
                'content_type' => $type,
                'score' => rand(75, 90) / 10
            ]
        ];
    }
    
    /**
     * Pré-carregamento com priorização dinâmica
     */
    public function executeDynamicPriorityPreload(array $context = []): array 
    {
        $startTime = microtime(true);
        
        try {
            // Obter métricas atuais do sistema
            $systemMetrics = $this->getSystemMetrics();
            
            // Ajustar estratégia baseada nas métricas
            $strategy = $this->adaptPreloadStrategy($systemMetrics, $context);
            
            // Executar pré-carregamento com estratégia adaptada
            $preloaded = $this->executeAdaptedPreload($strategy, $context);
            
            $this->recordPreloadStats('dynamic_priority', microtime(true) - $startTime, count($preloaded));
            
            return $preloaded;
            
        } catch (Exception $e) {
            error_log("Erro no pré-carregamento dinâmico: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obter métricas do sistema
     */
    private function getSystemMetrics(): array 
    {
        return [
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'memory_limit' => $this->parseMemoryLimit(ini_get('memory_limit')),
            'load_average' => sys_getloadavg()[0] ?? 0,
            'cache_hit_rate' => $this->getCacheHitRate(),
            'active_users' => $this->getActiveUsersCount()
        ];
    }
    
    /**
     * Adaptar estratégia de pré-carregamento
     */
    private function adaptPreloadStrategy(array $metrics, array $context): array 
    {
        $strategy = [
            'max_items' => $this->config['max_preload_items'],
            'enable_ml' => true,
            'enable_popular' => true,
            'enable_related' => true,
            'cache_ttl' => $this->config['cache_preload_ttl']
        ];
        
        // Reduzir pré-carregamento se memória estiver baixa
        $memoryUsagePercent = $metrics['memory_usage'] / $metrics['memory_limit'];
        if ($memoryUsagePercent > 0.8) {
            $strategy['max_items'] = (int)($strategy['max_items'] * 0.5);
            $strategy['enable_ml'] = false;
        }
        
        // Reduzir pré-carregamento se carga do sistema estiver alta
        if ($metrics['load_average'] > 2.0) {
            $strategy['max_items'] = (int)($strategy['max_items'] * 0.7);
            $strategy['enable_related'] = false;
        }
        
        // Aumentar cache TTL se hit rate estiver baixo
        if ($metrics['cache_hit_rate'] < 0.5) {
            $strategy['cache_ttl'] *= 2;
        }
        
        return $strategy;
    }
    
    /**
     * Executar pré-carregamento adaptado
     */
    private function executeAdaptedPreload(array $strategy, array $context): array 
    {
        $preloaded = [];
        
        // Pré-carregamento popular (sempre habilitado)
        if ($strategy['enable_popular']) {
            $popular = array_slice($this->preloadPopularContent(), 0, $strategy['max_items'] / 2);
            $preloaded = array_merge($preloaded, $popular);
        }
        
        // Pré-carregamento ML se habilitado
        if ($strategy['enable_ml'] && isset($context['user_history'])) {
            $ml = array_slice($this->executeMLBasedPreload($context['user_history']), 0, $strategy['max_items'] / 4);
            $preloaded = array_merge($preloaded, $ml);
        }
        
        // Pré-carregamento relacionado se habilitado
        if ($strategy['enable_related'] && isset($context['current_manga'])) {
            $related = array_slice($this->preloadRelatedContent($context['current_manga']), 0, $strategy['max_items'] / 4);
            $preloaded = array_merge($preloaded, $related);
        }
        
        return array_slice($preloaded, 0, $strategy['max_items']);
    }
    
    /**
     * Parsear limite de memória
     */
    private function parseMemoryLimit(string $limit): int 
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int)$limit;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }
    
    /**
     * Obter taxa de hit do cache
     */
    private function getCacheHitRate(): float 
    {
        if ($this->cache) {
            $stats = $this->cache->getPerformanceStats();
            return $stats['hit_rate'] / 100;
        }
        
        return 0.5; // Valor padrão
    }
    
    /**
     * Obter contagem de usuários ativos
     */
    private function getActiveUsersCount(): int 
    {
        // Simular contagem de usuários ativos
        return rand(10, 100);
    }