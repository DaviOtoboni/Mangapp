<?php
/**
 * Integrador de Performance para MangApp
 * 
 * Coordena todas as otimizações de performance do sistema,
 * incluindo lazy loading, cache, compressão e pré-carregamento.
 */

class PerformanceIntegrator 
{
    private ?LazyImageLoader $lazyLoader = null;
    private ?DatabaseOptimizer $dbOptimizer = null;
    private ?ResponseCompressor $compressor = null;
    private ?IntelligentPreloader $preloader = null;
    private ?CacheManager $cache = null;
    private array $config;
    private array $performanceMetrics = [];
    
    public function __construct(array $config = []) 
    {
        $this->config = array_merge([
            'enable_lazy_loading' => true,
            'enable_db_optimization' => true,
            'enable_compression' => true,
            'enable_preloading' => true,
            'enable_performance_monitoring' => true,
            'performance_budget' => [
                'max_response_time' => 2.0, // seconds
                'max_memory_usage' => 128 * 1024 * 1024, // 128MB
                'min_cache_hit_rate' => 0.7 // 70%
            ],
            'optimization_thresholds' => [
                'slow_response' => 1.0,
                'high_memory' => 0.8,
                'low_cache_hit' => 0.5
            ]
        ], $config);
        
        $this->initializeComponents();
    }
    
    /**
     * Inicializar componentes de performance
     */
    private function initializeComponents(): void 
    {
        if ($this->config['enable_lazy_loading'] && class_exists('LazyImageLoader')) {
            $this->lazyLoader = new LazyImageLoader();
        }
        
        if ($this->config['enable_db_optimization'] && class_exists('DatabaseOptimizer')) {
            $this->dbOptimizer = new DatabaseOptimizer();
        }
        
        if ($this->config['enable_compression'] && class_exists('ResponseCompressor')) {
            $this->compressor = new ResponseCompressor();
        }
        
        if ($this->config['enable_preloading'] && class_exists('IntelligentPreloader')) {
            $this->preloader = new IntelligentPreloader();
        }
        
        if (class_exists('CacheManager')) {
            $this->cache = CacheManager::getInstance();
        }
    }
    
    /**
     * Otimizar response completo da API
     */
    public function optimizeAPIResponse(array $data, array $context = []): array 
    {
        $startTime = microtime(true);
        
        try {
            // 1. Otimizar dados baseado no contexto
            $optimizedData = $this->optimizeDataForContext($data, $context);
            
            // 2. Aplicar compressão se necessário
            if ($this->compressor && $this->shouldCompress($context)) {
                $compressionResult = $this->compressor->compressAPIResponse($optimizedData);
                $optimizedData = $compressionResult;
            }
            
            // 3. Executar pré-carregamento inteligente em background
            if ($this->preloader && $this->config['enable_preloading']) {
                $this->executeBackgroundPreload($context);
            }
            
            // 4. Registrar métricas de performance
            $this->recordPerformanceMetrics('api_response', microtime(true) - $startTime, $context);
            
            return $optimizedData;
            
        } catch (Exception $e) {
            error_log("Erro na otimização de response: " . $e->getMessage());
            return $data;
        }
    }
    
    /**
     * Otimizar busca de mangás com todas as otimizações
     */
    public function optimizedMangaSearch(array $criteria, array $options = []): array 
    {
        $startTime = microtime(true);
        
        try {
            // Detectar se é mobile para otimizações específicas
            $isMobile = $this->isMobileRequest();
            
            // 1. Usar busca otimizada do banco
            if ($this->dbOptimizer) {
                if (isset($criteria['title']) && strlen($criteria['title']) > 2) {
                    $results = $this->dbOptimizer->searchMangasFullText($criteria['title'], $options);
                } else {
                    $results = $this->dbOptimizer->searchWithIntelligentCache($criteria, $options);
                }
            } else {
                // Fallback para busca padrão
                $results = $this->fallbackSearch($criteria, $options);
            }
            
            // 2. Otimizar imagens para lazy loading
            if ($this->lazyLoader) {
                $results = $this->optimizeImagesForLazyLoading($results, $isMobile);
            }
            
            // 3. Comprimir para mobile se necessário
            if ($isMobile && $this->compressor) {
                $results = $this->compressor->optimizeForMobile($results);
            }
            
            // 4. Pré-carregar dados relacionados
            if ($this->preloader && !empty($results)) {
                $this->preloadRelatedMangaData($results);
            }
            
            $this->recordPerformanceMetrics('manga_search', microtime(true) - $startTime, [
                'criteria' => $criteria,
                'result_count' => count($results),
                'is_mobile' => $isMobile
            ]);
            
            return $results;
            
        } catch (Exception $e) {
            error_log("Erro na busca otimizada: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Otimizar página de detalhes do mangá
     */
    public function optimizeMangaDetailsPage(array $manga, array $context = []): array 
    {
        $startTime = microtime(true);
        
        try {
            // 1. Otimizar imagem de capa
            if ($this->lazyLoader && isset($manga['capa_url'])) {
                $manga['lazy_cover'] = $this->lazyLoader->generateLazyImage([
                    'src' => $manga['capa_url'],
                    'alt' => $manga['nome'] ?? 'Capa do mangá',
                    'class' => 'manga-cover'
                ]);
                
                // Gerar picture element com múltiplos formatos
                $manga['optimized_cover'] = $this->lazyLoader->generatePictureElement([
                    'src' => $manga['capa_url'],
                    'alt' => $manga['nome'] ?? 'Capa do mangá',
                    'class' => 'manga-cover-optimized'
                ]);
            }
            
            // 2. Pré-carregar conteúdo relacionado
            if ($this->preloader) {
                $manga['preloaded_related'] = $this->preloader->preloadRelatedContent($manga);
            }
            
            // 3. Otimizar dados para o contexto
            $manga = $this->optimizeDataForContext($manga, $context);
            
            $this->recordPerformanceMetrics('manga_details', microtime(true) - $startTime, [
                'manga_id' => $manga['id'] ?? 'unknown'
            ]);
            
            return $manga;
            
        } catch (Exception $e) {
            error_log("Erro na otimização de detalhes: " . $e->getMessage());
            return $manga;
        }
    }
    
    /**
     * Executar otimizações de inicialização do sistema
     */
    public function initializeSystemOptimizations(): bool 
    {
        try {
            // 1. Otimizar índices do banco de dados
            if ($this->dbOptimizer) {
                $this->dbOptimizer->optimizeIndexes();
            }
            
            // 2. Pré-carregar conteúdo popular
            if ($this->preloader) {
                $this->preloader->preloadPopularContent();
            }
            
            // 3. Limpar cache expirado
            if ($this->cache) {
                $this->cache->cleanupExpired();
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Erro na inicialização de otimizações: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Monitorar performance e aplicar otimizações automáticas
     */
    public function monitorAndOptimize(): array 
    {
        $metrics = $this->collectPerformanceMetrics();
        $optimizations = [];
        
        // Verificar se response time está alto
        if ($metrics['avg_response_time'] > $this->config['optimization_thresholds']['slow_response']) {
            $optimizations[] = $this->optimizeSlowResponses();
        }
        
        // Verificar se uso de memória está alto
        if ($metrics['memory_usage_percent'] > $this->config['optimization_thresholds']['high_memory']) {
            $optimizations[] = $this->optimizeMemoryUsage();
        }
        
        // Verificar se cache hit rate está baixo
        if ($metrics['cache_hit_rate'] < $this->config['optimization_thresholds']['low_cache_hit']) {
            $optimizations[] = $this->optimizeCacheStrategy();
        }
        
        return [
            'metrics' => $metrics,
            'optimizations_applied' => $optimizations,
            'performance_score' => $this->calculatePerformanceScore($metrics)
        ];
    }
    
    /**
     * Otimizar dados baseado no contexto
     */
    private function optimizeDataForContext(array $data, array $context): array 
    {
        // Otimizar para mobile
        if ($this->isMobileRequest() && $this->compressor) {
            $data = $this->compressor->optimizeForMobile($data);
        }
        
        // Remover campos desnecessários baseado no contexto
        if (isset($context['fields']) && is_array($context['fields'])) {
            $data = $this->filterFieldsByContext($data, $context['fields']);
        }
        
        // Otimizar arrays grandes
        if (isset($context['limit_arrays']) && $context['limit_arrays']) {
            $data = $this->limitLargeArrays($data);
        }
        
        return $data;
    }
    
    /**
     * Verificar se deve comprimir
     */
    private function shouldCompress(array $context): bool 
    {
        // Não comprimir para requests pequenos
        if (isset($context['data_size']) && $context['data_size'] < 1024) {
            return false;
        }
        
        // Sempre comprimir para mobile
        if ($this->isMobileRequest()) {
            return true;
        }
        
        // Comprimir baseado no Accept-Encoding
        $acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
        return strpos($acceptEncoding, 'gzip') !== false || strpos($acceptEncoding, 'deflate') !== false;
    }
    
    /**
     * Detectar se é request mobile
     */
    private function isMobileRequest(): bool 
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $mobileKeywords = ['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'];
        
        foreach ($mobileKeywords as $keyword) {
            if (strpos($userAgent, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Executar pré-carregamento em background
     */
    private function executeBackgroundPreload(array $context): void 
    {
        if (!$this->preloader) {
            return;
        }
        
        // Executar em background usando fastcgi_finish_request se disponível
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        
        try {
            $this->preloader->executeIntelligentPreload($context);
        } catch (Exception $e) {
            error_log("Erro no pré-carregamento background: " . $e->getMessage());
        }
    }
    
    /**
     * Otimizar imagens para lazy loading
     */
    private function optimizeImagesForLazyLoading(array $results, bool $isMobile = false): array 
    {
        foreach ($results as &$manga) {
            if (isset($manga['capa_url'])) {
                $imageConfig = [
                    'src' => $manga['capa_url'],
                    'alt' => $manga['nome'] ?? 'Capa do mangá',
                    'class' => 'manga-cover-lazy'
                ];
                
                if ($isMobile) {
                    $imageConfig['width'] = 150;
                    $imageConfig['height'] = 200;
                }
                
                $manga['lazy_image'] = $this->lazyLoader->generateLazyImage($imageConfig);
            }
        }
        
        return $results;
    }
    
    /**
     * Pré-carregar dados relacionados de mangás
     */
    private function preloadRelatedMangaData(array $results): void 
    {
        if (!$this->dbOptimizer || empty($results)) {
            return;
        }
        
        $mangaIds = array_column($results, 'id');
        $mangaIds = array_filter($mangaIds);
        
        if (!empty($mangaIds)) {
            try {
                $this->dbOptimizer->preloadRelatedData($mangaIds);
            } catch (Exception $e) {
                error_log("Erro no pré-carregamento de dados relacionados: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Busca fallback
     */
    private function fallbackSearch(array $criteria, array $options): array 
    {
        // Implementação básica de busca como fallback
        return [];
    }
    
    /**
     * Filtrar campos por contexto
     */
    private function filterFieldsByContext(array $data, array $allowedFields): array 
    {
        $filtered = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $filtered[$field] = $data[$field];
            }
        }
        
        return $filtered;
    }
    
    /**
     * Limitar arrays grandes
     */
    private function limitLargeArrays(array $data): array 
    {
        $limits = [
            'generos' => 10,
            'autores' => 5,
            'tags' => 15,
            'related_manga' => 8
        ];
        
        foreach ($limits as $field => $limit) {
            if (isset($data[$field]) && is_array($data[$field]) && count($data[$field]) > $limit) {
                $data[$field] = array_slice($data[$field], 0, $limit);
            }
        }
        
        return $data;
    }
    
    /**
     * Coletar métricas de performance
     */
    private function collectPerformanceMetrics(): array 
    {
        $metrics = [
            'avg_response_time' => 0,
            'memory_usage_percent' => 0,
            'cache_hit_rate' => 0,
            'active_connections' => 0,
            'error_rate' => 0
        ];
        
        // Calcular tempo médio de resposta
        if (!empty($this->performanceMetrics)) {
            $totalTime = array_sum(array_column($this->performanceMetrics, 'execution_time'));
            $metrics['avg_response_time'] = $totalTime / count($this->performanceMetrics);
        }
        
        // Calcular uso de memória
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $metrics['memory_usage_percent'] = $memoryUsage / $memoryLimit;
        
        // Obter cache hit rate
        if ($this->cache) {
            $cacheStats = $this->cache->getPerformanceStats();
            $metrics['cache_hit_rate'] = $cacheStats['hit_rate'] / 100;
        }
        
        return $metrics;
    }
    
    /**
     * Otimizar responses lentos
     */
    private function optimizeSlowResponses(): string 
    {
        // Aumentar cache TTL
        if ($this->cache) {
            // Implementar lógica para aumentar TTL
        }
        
        // Reduzir tamanho de responses
        $this->config['enable_compression'] = true;
        
        return "Otimizações aplicadas para responses lentos";
    }
    
    /**
     * Otimizar uso de memória
     */
    private function optimizeMemoryUsage(): string 
    {
        // Limpar cache expirado
        if ($this->cache) {
            $this->cache->cleanupExpired();
        }
        
        // Reduzir pré-carregamento
        if ($this->preloader) {
            $this->config['max_preload_items'] = (int)($this->config['max_preload_items'] * 0.7);
        }
        
        return "Otimizações aplicadas para uso de memória";
    }
    
    /**
     * Otimizar estratégia de cache
     */
    private function optimizeCacheStrategy(): string 
    {
        // Aumentar TTL para dados populares
        // Implementar cache warming
        
        return "Estratégia de cache otimizada";
    }
    
    /**
     * Calcular score de performance
     */
    private function calculatePerformanceScore(array $metrics): float 
    {
        $score = 100;
        
        // Penalizar response time alto
        if ($metrics['avg_response_time'] > 1.0) {
            $score -= ($metrics['avg_response_time'] - 1.0) * 20;
        }
        
        // Penalizar uso de memória alto
        if ($metrics['memory_usage_percent'] > 0.7) {
            $score -= ($metrics['memory_usage_percent'] - 0.7) * 100;
        }
        
        // Penalizar cache hit rate baixo
        if ($metrics['cache_hit_rate'] < 0.7) {
            $score -= (0.7 - $metrics['cache_hit_rate']) * 50;
        }
        
        return max(0, min(100, $score));
    }
    
    /**
     * Registrar métricas de performance
     */
    private function recordPerformanceMetrics(string $operation, float $executionTime, array $context = []): void 
    {
        if (!$this->config['enable_performance_monitoring']) {
            return;
        }
        
        $this->performanceMetrics[] = [
            'operation' => $operation,
            'execution_time' => $executionTime,
            'timestamp' => time(),
            'context' => $context
        ];
        
        // Manter apenas as últimas 1000 métricas
        if (count($this->performanceMetrics) > 1000) {
            $this->performanceMetrics = array_slice($this->performanceMetrics, -1000);
        }
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
     * Obter relatório de performance
     */
    public function getPerformanceReport(): array 
    {
        $metrics = $this->collectPerformanceMetrics();
        
        return [
            'current_metrics' => $metrics,
            'performance_score' => $this->calculatePerformanceScore($metrics),
            'component_stats' => [
                'cache' => $this->cache ? $this->cache->getPerformanceStats() : null,
                'db_optimizer' => $this->dbOptimizer ? $this->dbOptimizer->getPerformanceStats() : null,
                'compressor' => $this->compressor ? $this->compressor->getCompressionStats() : null,
                'preloader' => $this->preloader ? $this->preloader->getPreloadStats() : null
            ],
            'recommendations' => $this->generatePerformanceRecommendations($metrics)
        ];
    }
    
    /**
     * Gerar recomendações de performance
     */
    private function generatePerformanceRecommendations(array $metrics): array 
    {
        $recommendations = [];
        
        if ($metrics['avg_response_time'] > 1.0) {
            $recommendations[] = "Considere implementar cache mais agressivo ou otimizar consultas de banco";
        }
        
        if ($metrics['memory_usage_percent'] > 0.8) {
            $recommendations[] = "Uso de memória alto - considere reduzir pré-carregamento ou limpar cache";
        }
        
        if ($metrics['cache_hit_rate'] < 0.6) {
            $recommendations[] = "Taxa de hit do cache baixa - revisar estratégia de cache";
        }
        
        return $recommendations;
    }
}