<?php
/**
 * Sistema de Compressão de Responses da API
 * 
 * Implementa compressão de dados para reduzir uso de banda
 * e melhorar performance das requisições da API.
 */

class ResponseCompressor 
{
    private array $config;
    private array $compressionStats = [];
    
    public function __construct(array $config = []) 
    {
        $this->config = array_merge([
            'enable_gzip' => true,
            'enable_brotli' => false, // Requer extensão brotli
            'compression_level' => 6,
            'min_size' => 1024, // Não comprimir arquivos menores que 1KB
            'cache_compressed' => true,
            'cache_ttl' => 3600,
            'compressible_types' => [
                'application/json',
                'text/html',
                'text/css',
                'text/javascript',
                'application/javascript',
                'text/xml',
                'application/xml'
            ]
        ], $config);
    }
    
    /**
     * Comprimir response da API
     */
    public function compressAPIResponse(array $data, string $contentType = 'application/json'): array 
    {
        $startTime = microtime(true);
        
        try {
            // Serializar dados
            $originalData = json_encode($data, JSON_UNESCAPED_UNICODE);
            $originalSize = strlen($originalData);
            
            // Verificar se deve comprimir
            if (!$this->shouldCompress($originalSize, $contentType)) {
                return [
                    'data' => $originalData,
                    'compressed' => false,
                    'encoding' => null,
                    'original_size' => $originalSize,
                    'compressed_size' => $originalSize,
                    'compression_ratio' => 1.0
                ];
            }
            
            // Verificar cache de compressão
            $cacheKey = $this->generateCompressionCacheKey($originalData);
            if ($this->config['cache_compressed']) {
                $cached = $this->getCachedCompression($cacheKey);
                if ($cached) {
                    $this->recordCompressionStats('cache_hit', microtime(true) - $startTime, $originalSize, strlen($cached['data']));
                    return $cached;
                }
            }
            
            // Determinar melhor método de compressão
            $compressionMethod = $this->getBestCompressionMethod();
            
            // Comprimir dados
            $compressedData = $this->compressData($originalData, $compressionMethod);
            
            if ($compressedData === false) {
                // Fallback para dados não comprimidos
                return [
                    'data' => $originalData,
                    'compressed' => false,
                    'encoding' => null,
                    'original_size' => $originalSize,
                    'compressed_size' => $originalSize,
                    'compression_ratio' => 1.0
                ];
            }
            
            $compressedSize = strlen($compressedData);
            $compressionRatio = $originalSize > 0 ? $compressedSize / $originalSize : 1.0;
            
            $result = [
                'data' => $compressedData,
                'compressed' => true,
                'encoding' => $compressionMethod,
                'original_size' => $originalSize,
                'compressed_size' => $compressedSize,
                'compression_ratio' => $compressionRatio
            ];
            
            // Cache do resultado comprimido
            if ($this->config['cache_compressed']) {
                $this->setCachedCompression($cacheKey, $result);
            }
            
            $this->recordCompressionStats('compression', microtime(true) - $startTime, $originalSize, $compressedSize);
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Erro na compressão: " . $e->getMessage());
            
            // Retornar dados originais em caso de erro
            return [
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
                'compressed' => false,
                'encoding' => null,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Descomprimir response da API
     */
    public function decompressAPIResponse(string $data, string $encoding): ?string 
    {
        $startTime = microtime(true);
        
        try {
            $decompressed = $this->decompressData($data, $encoding);
            
            if ($decompressed !== false) {
                $this->recordCompressionStats('decompression', microtime(true) - $startTime, strlen($data), strlen($decompressed));
                return $decompressed;
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("Erro na descompressão: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Configurar headers de compressão para response HTTP
     */
    public function setCompressionHeaders(array $compressionResult): void 
    {
        if ($compressionResult['compressed']) {
            header('Content-Encoding: ' . $compressionResult['encoding']);
            header('Vary: Accept-Encoding');
            
            // Headers adicionais para cache
            header('X-Original-Size: ' . $compressionResult['original_size']);
            header('X-Compressed-Size: ' . $compressionResult['compressed_size']);
            header('X-Compression-Ratio: ' . round($compressionResult['compression_ratio'], 3));
        }
        
        // Header de tamanho do conteúdo
        header('Content-Length: ' . strlen($compressionResult['data']));
    }
    
    /**
     * Comprimir dados de busca para cache
     */
    public function compressSearchResults(array $results): string 
    {
        $serialized = serialize($results);
        
        if (strlen($serialized) < $this->config['min_size']) {
            return $serialized;
        }
        
        $compressed = gzcompress($serialized, $this->config['compression_level']);
        
        // Retornar comprimido apenas se for menor
        return strlen($compressed) < strlen($serialized) ? $compressed : $serialized;
    }
    
    /**
     * Descomprimir dados de busca do cache
     */
    public function decompressSearchResults(string $data): array 
    {
        // Tentar descomprimir primeiro
        $decompressed = @gzuncompress($data);
        
        if ($decompressed !== false) {
            $unserialized = @unserialize($decompressed);
            if ($unserialized !== false) {
                return $unserialized;
            }
        }
        
        // Fallback para dados não comprimidos
        $unserialized = @unserialize($data);
        return $unserialized !== false ? $unserialized : [];
    }
    
    /**
     * Verificar se deve comprimir
     */
    private function shouldCompress(int $size, string $contentType): bool 
    {
        // Verificar tamanho mínimo
        if ($size < $this->config['min_size']) {
            return false;
        }
        
        // Verificar tipo de conteúdo
        if (!in_array($contentType, $this->config['compressible_types'])) {
            return false;
        }
        
        // Verificar se compressão está habilitada
        return $this->config['enable_gzip'] || $this->config['enable_brotli'];
    }
    
    /**
     * Obter melhor método de compressão
     */
    private function getBestCompressionMethod(): string 
    {
        // Verificar suporte do cliente via Accept-Encoding
        $acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
        
        // Priorizar Brotli se disponível
        if ($this->config['enable_brotli'] && 
            function_exists('brotli_compress') && 
            strpos($acceptEncoding, 'br') !== false) {
            return 'br';
        }
        
        // Fallback para gzip
        if ($this->config['enable_gzip'] && 
            function_exists('gzencode') && 
            (strpos($acceptEncoding, 'gzip') !== false || strpos($acceptEncoding, '*') !== false)) {
            return 'gzip';
        }
        
        return 'none';
    }
    
    /**
     * Comprimir dados com método específico
     */
    private function compressData(string $data, string $method) 
    {
        switch ($method) {
            case 'br':
                if (function_exists('brotli_compress')) {
                    return brotli_compress($data, $this->config['compression_level']);
                }
                break;
                
            case 'gzip':
                return gzencode($data, $this->config['compression_level']);
                
            case 'deflate':
                return gzdeflate($data, $this->config['compression_level']);
                
            default:
                return false;
        }
        
        return false;
    }
    
    /**
     * Descomprimir dados com método específico
     */
    private function decompressData(string $data, string $method) 
    {
        switch ($method) {
            case 'br':
                if (function_exists('brotli_uncompress')) {
                    return brotli_uncompress($data);
                }
                break;
                
            case 'gzip':
                return gzdecode($data);
                
            case 'deflate':
                return gzinflate($data);
                
            default:
                return $data;
        }
        
        return false;
    }
    
    /**
     * Gerar chave de cache para compressão
     */
    private function generateCompressionCacheKey(string $data): string 
    {
        return 'compression_' . md5($data);
    }
    
    /**
     * Obter compressão do cache
     */
    private function getCachedCompression(string $cacheKey): ?array 
    {
        if (!class_exists('CacheManager')) {
            return null;
        }
        
        $cache = CacheManager::getInstance();
        return $cache->get($cacheKey);
    }
    
    /**
     * Salvar compressão no cache
     */
    private function setCachedCompression(string $cacheKey, array $result): void 
    {
        if (!class_exists('CacheManager')) {
            return;
        }
        
        $cache = CacheManager::getInstance();
        $cache->set($cacheKey, $result, $this->config['cache_ttl']);
    }
    
    /**
     * Registrar estatísticas de compressão
     */
    private function recordCompressionStats(string $operation, float $time, int $originalSize, int $finalSize): void 
    {
        if (!isset($this->compressionStats[$operation])) {
            $this->compressionStats[$operation] = [
                'count' => 0,
                'total_time' => 0,
                'total_original_size' => 0,
                'total_final_size' => 0,
                'avg_compression_ratio' => 0
            ];
        }
        
        $stats = &$this->compressionStats[$operation];
        $stats['count']++;
        $stats['total_time'] += $time;
        $stats['total_original_size'] += $originalSize;
        $stats['total_final_size'] += $finalSize;
        
        if ($stats['total_original_size'] > 0) {
            $stats['avg_compression_ratio'] = $stats['total_final_size'] / $stats['total_original_size'];
        }
    }
    
    /**
     * Obter estatísticas de compressão
     */
    public function getCompressionStats(): array 
    {
        $stats = $this->compressionStats;
        
        // Calcular estatísticas agregadas
        $totalOriginal = 0;
        $totalCompressed = 0;
        $totalOperations = 0;
        
        foreach ($stats as $operation => $data) {
            $totalOriginal += $data['total_original_size'];
            $totalCompressed += $data['total_final_size'];
            $totalOperations += $data['count'];
        }
        
        $stats['summary'] = [
            'total_operations' => $totalOperations,
            'total_bytes_processed' => $totalOriginal,
            'total_bytes_saved' => max(0, $totalOriginal - $totalCompressed),
            'overall_compression_ratio' => $totalOriginal > 0 ? $totalCompressed / $totalOriginal : 1.0,
            'bandwidth_saved_percent' => $totalOriginal > 0 ? ((1 - ($totalCompressed / $totalOriginal)) * 100) : 0
        ];
        
        return $stats;
    }
    
    /**
     * Otimizar response JSON
     */
    public function optimizeJSONResponse(array $data): array 
    {
        // Remover campos nulos ou vazios desnecessários
        $optimized = $this->removeEmptyFields($data);
        
        // Comprimir arrays de strings repetitivas
        $optimized = $this->compressRepeatedStrings($optimized);
        
        // Otimizar estruturas aninhadas
        $optimized = $this->optimizeNestedStructures($optimized);
        
        return $optimized;
    }
    
    /**
     * Remover campos vazios
     */
    private function removeEmptyFields(array $data): array 
    {
        $cleaned = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cleanedValue = $this->removeEmptyFields($value);
                if (!empty($cleanedValue)) {
                    $cleaned[$key] = $cleanedValue;
                }
            } elseif ($value !== null && $value !== '' && $value !== []) {
                $cleaned[$key] = $value;
            }
        }
        
        return $cleaned;
    }
    
    /**
     * Comprimir strings repetitivas
     */
    private function compressRepeatedStrings(array $data): array 
    {
        // Identificar strings que se repetem frequentemente
        $stringCounts = [];
        $this->countStrings($data, $stringCounts);
        
        // Criar dicionário para strings frequentes
        $dictionary = [];
        foreach ($stringCounts as $string => $count) {
            if ($count > 2 && strlen($string) > 10) {
                $dictionary[$string] = 'dict_' . count($dictionary);
            }
        }
        
        if (!empty($dictionary)) {
            $compressed = $this->replaceWithDictionary($data, $dictionary);
            $compressed['_dictionary'] = array_flip($dictionary);
            return $compressed;
        }
        
        return $data;
    }
    
    /**
     * Contar ocorrências de strings
     */
    private function countStrings(array $data, array &$counts): void 
    {
        foreach ($data as $value) {
            if (is_string($value)) {
                $counts[$value] = ($counts[$value] ?? 0) + 1;
            } elseif (is_array($value)) {
                $this->countStrings($value, $counts);
            }
        }
    }
    
    /**
     * Substituir strings por referências do dicionário
     */
    private function replaceWithDictionary(array $data, array $dictionary): array 
    {
        $result = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value) && isset($dictionary[$value])) {
                $result[$key] = $dictionary[$value];
            } elseif (is_array($value)) {
                $result[$key] = $this->replaceWithDictionary($value, $dictionary);
            } else {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
    
    /**
     * Otimizar estruturas aninhadas
     */
    private function optimizeNestedStructures(array $data): array 
    {
        // Detectar arrays de objetos similares
        foreach ($data as $key => $value) {
            if (is_array($value) && $this->isArrayOfSimilarObjects($value)) {
                $data[$key] = $this->optimizeObjectArray($value);
            } elseif (is_array($value)) {
                $data[$key] = $this->optimizeNestedStructures($value);
            }
        }
        
        return $data;
    }
    
    /**
     * Verificar se é array de objetos similares
     */
    private function isArrayOfSimilarObjects(array $array): bool 
    {
        if (count($array) < 2) {
            return false;
        }
        
        $firstKeys = null;
        foreach ($array as $item) {
            if (!is_array($item)) {
                return false;
            }
            
            if ($firstKeys === null) {
                $firstKeys = array_keys($item);
            } elseif (array_keys($item) !== $firstKeys) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Otimizar array de objetos
     */
    private function optimizeObjectArray(array $objects): array 
    {
        if (empty($objects)) {
            return $objects;
        }
        
        // Extrair chaves comuns
        $keys = array_keys($objects[0]);
        
        // Converter para formato tabular
        $optimized = [
            '_format' => 'tabular',
            '_keys' => $keys,
            '_data' => []
        ];
        
        foreach ($objects as $object) {
            $row = [];
            foreach ($keys as $key) {
                $row[] = $object[$key] ?? null;
            }
            $optimized['_data'][] = $row;
        }
        
        return $optimized;
    }
    
    /**
     * Comprimir response com streaming para grandes datasets
     */
    public function compressStreamingResponse(array $data, string $contentType = 'application/json'): Generator 
    {
        $chunkSize = 1024 * 8; // 8KB chunks
        
        try {
            $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
            $chunks = str_split($jsonData, $chunkSize);
            
            foreach ($chunks as $chunk) {
                if ($this->shouldCompress(strlen($chunk), $contentType)) {
                    yield $this->compressData($chunk, 'gzip');
                } else {
                    yield $chunk;
                }
            }
            
        } catch (Exception $e) {
            error_log("Erro no streaming de compressão: " . $e->getMessage());
            yield json_encode($data);
        }
    }
    
    /**
     * Otimizar response para mobile
     */
    public function optimizeForMobile(array $data): array 
    {
        // Remover campos desnecessários para mobile
        $mobileOptimized = $this->removeMobileUnnecessaryFields($data);
        
        // Comprimir imagens URLs para thumbnails
        $mobileOptimized = $this->optimizeImageUrlsForMobile($mobileOptimized);
        
        // Limitar arrays grandes
        $mobileOptimized = $this->limitArraysForMobile($mobileOptimized);
        
        return $mobileOptimized;
    }
    
    /**
     * Remover campos desnecessários para mobile
     */
    private function removeMobileUnnecessaryFields(array $data): array 
    {
        $fieldsToRemove = [
            'detailed_synopsis',
            'full_author_bio',
            'extended_metadata',
            'debug_info',
            'admin_notes'
        ];
        
        return $this->removeFieldsRecursive($data, $fieldsToRemove);
    }
    
    /**
     * Otimizar URLs de imagem para mobile
     */
    private function optimizeImageUrlsForMobile(array $data): array 
    {
        array_walk_recursive($data, function(&$value, $key) {
            if (in_array($key, ['capa_url', 'image_url', 'cover_image']) && is_string($value)) {
                // Adicionar parâmetros para thumbnail mobile
                if (strpos($value, '?') !== false) {
                    $value .= '&mobile=1&size=small';
                } else {
                    $value .= '?mobile=1&size=small';
                }
            }
        });
        
        return $data;
    }
    
    /**
     * Limitar arrays para mobile
     */
    private function limitArraysForMobile(array $data): array 
    {
        $limits = [
            'generos' => 5,
            'autores' => 3,
            'tags' => 8,
            'related_manga' => 5
        ];
        
        foreach ($limits as $field => $limit) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = array_slice($data[$field], 0, $limit);
            }
        }
        
        return $data;
    }
    
    /**
     * Remover campos recursivamente
     */
    private function removeFieldsRecursive(array $data, array $fieldsToRemove): array 
    {
        foreach ($fieldsToRemove as $field) {
            unset($data[$field]);
        }
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->removeFieldsRecursive($value, $fieldsToRemove);
            }
        }
        
        return $data;
    }
    
    /**
     * Comprimir com algoritmo adaptativo
     */
    public function compressAdaptive(array $data, string $contentType = 'application/json'): array 
    {
        $serialized = json_encode($data, JSON_UNESCAPED_UNICODE);
        $originalSize = strlen($serialized);
        
        // Testar diferentes métodos e escolher o melhor
        $methods = ['gzip', 'deflate'];
        $bestResult = null;
        $bestRatio = 1.0;
        
        foreach ($methods as $method) {
            if ($this->isCompressionMethodAvailable($method)) {
                $compressed = $this->compressData($serialized, $method);
                
                if ($compressed !== false) {
                    $ratio = strlen($compressed) / $originalSize;
                    
                    if ($ratio < $bestRatio) {
                        $bestRatio = $ratio;
                        $bestResult = [
                            'data' => $compressed,
                            'compressed' => true,
                            'encoding' => $method,
                            'original_size' => $originalSize,
                            'compressed_size' => strlen($compressed),
                            'compression_ratio' => $ratio
                        ];
                    }
                }
            }
        }
        
        // Se nenhuma compressão foi efetiva, retornar original
        if ($bestResult === null || $bestRatio > 0.9) {
            return [
                'data' => $serialized,
                'compressed' => false,
                'encoding' => null,
                'original_size' => $originalSize,
                'compressed_size' => $originalSize,
                'compression_ratio' => 1.0
            ];
        }
        
        return $bestResult;
    }
    
    /**
     * Verificar se método de compressão está disponível
     */
    private function isCompressionMethodAvailable(string $method): bool 
    {
        switch ($method) {
            case 'gzip':
                return function_exists('gzencode');
            case 'deflate':
                return function_exists('gzdeflate');
            case 'br':
                return function_exists('brotli_compress');
            default:
                return false;
        }
    }
}