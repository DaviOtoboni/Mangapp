<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/CacheManager.php';

/**
 * Monitor de cache para debugging e otimização
 * Fornece métricas detalhadas e ferramentas de análise
 */
class CacheMonitor {
    private $cache;
    private $logFile;
    
    public function __construct(CacheManager $cache = null) {
        $this->cache = $cache ?? new CacheManager();
        $this->logFile = CACHE_DIR . 'cache_monitor.log';
    }
    
    /**
     * Obter relatório detalhado do cache
     */
    public function getDetailedReport(): array {
        $stats = $this->cache->getStats();
        $files = glob(JIKAN_CACHE_DIR . '*.json');
        
        $report = [
            'summary' => $stats,
            'files' => [],
            'performance' => $this->getPerformanceMetrics(),
            'recommendations' => []
        ];
        
        // Analisar cada arquivo de cache
        foreach ($files as $file) {
            $fileInfo = $this->analyzeFile($file);
            if ($fileInfo) {
                $report['files'][] = $fileInfo;
            }
        }
        
        // Gerar recomendações
        $report['recommendations'] = $this->generateRecommendations($report);
        
        return $report;
    }
    
    /**
     * Analisar arquivo individual de cache
     */
    private function analyzeFile(string $filePath): ?array {
        if (!file_exists($filePath)) {
            return null;
        }
        
        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }
        
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'file' => basename($filePath),
                'status' => 'corrupted',
                'error' => json_last_error_msg(),
                'size' => filesize($filePath),
                'created' => filemtime($filePath)
            ];
        }
        
        $now = time();
        $isExpired = isset($data['expires_at']) && $now > $data['expires_at'];
        $age = $now - ($data['created_at'] ?? filemtime($filePath));
        $ttl = ($data['expires_at'] ?? 0) - $now;
        
        return [
            'file' => basename($filePath),
            'key' => $data['key'] ?? 'unknown',
            'status' => $isExpired ? 'expired' : 'valid',
            'size' => filesize($filePath),
            'size_formatted' => $this->formatBytes(filesize($filePath)),
            'created_at' => $data['created_at'] ?? filemtime($filePath),
            'expires_at' => $data['expires_at'] ?? null,
            'age_seconds' => $age,
            'age_formatted' => $this->formatDuration($age),
            'ttl_seconds' => max(0, $ttl),
            'ttl_formatted' => $ttl > 0 ? $this->formatDuration($ttl) : 'Expirado',
            'type' => $this->detectCacheType($data['key'] ?? ''),
            'data_size' => strlen(json_encode($data['data'] ?? []))
        ];
    }
    
    /**
     * Detectar tipo de cache baseado na chave
     */
    private function detectCacheType(string $key): string {
        if (strpos($key, 'search_') === 0) {
            return 'search';
        } elseif (strpos($key, 'manga_') === 0) {
            return 'manga_details';
        } else {
            return 'unknown';
        }
    }
    
    /**
     * Obter métricas de performance
     */
    private function getPerformanceMetrics(): array {
        $files = glob(JIKAN_CACHE_DIR . '*.json');
        $totalSize = 0;
        $totalFiles = count($files);
        $validFiles = 0;
        $expiredFiles = 0;
        $corruptedFiles = 0;
        $searchCaches = 0;
        $mangaCaches = 0;
        $oldestFile = null;
        $newestFile = null;
        
        foreach ($files as $file) {
            $size = filesize($file);
            $totalSize += $size;
            $mtime = filemtime($file);
            
            if ($oldestFile === null || $mtime < $oldestFile) {
                $oldestFile = $mtime;
            }
            
            if ($newestFile === null || $mtime > $newestFile) {
                $newestFile = $mtime;
            }
            
            $content = file_get_contents($file);
            if ($content === false) {
                $corruptedFiles++;
                continue;
            }
            
            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $corruptedFiles++;
                continue;
            }
            
            $key = $data['key'] ?? '';
            if (strpos($key, 'search_') === 0) {
                $searchCaches++;
            } elseif (strpos($key, 'manga_') === 0) {
                $mangaCaches++;
            }
            
            $isExpired = isset($data['expires_at']) && time() > $data['expires_at'];
            if ($isExpired) {
                $expiredFiles++;
            } else {
                $validFiles++;
            }
        }
        
        return [
            'total_files' => $totalFiles,
            'valid_files' => $validFiles,
            'expired_files' => $expiredFiles,
            'corrupted_files' => $corruptedFiles,
            'search_caches' => $searchCaches,
            'manga_caches' => $mangaCaches,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
            'average_file_size' => $totalFiles > 0 ? round($totalSize / $totalFiles) : 0,
            'oldest_file' => $oldestFile,
            'newest_file' => $newestFile,
            'cache_age_range' => $oldestFile && $newestFile ? $newestFile - $oldestFile : 0,
            'hit_rate_estimate' => $this->estimateHitRate()
        ];
    }
    
    /**
     * Estimar taxa de acerto do cache
     */
    private function estimateHitRate(): float {
        // Esta é uma estimativa baseada na idade dos arquivos
        // Em um sistema real, você manteria contadores de hits/misses
        $files = glob(JIKAN_CACHE_DIR . '*.json');
        $recentFiles = 0;
        $cutoff = time() - 3600; // Arquivos criados na última hora
        
        foreach ($files as $file) {
            if (filemtime($file) > $cutoff) {
                $recentFiles++;
            }
        }
        
        $totalFiles = count($files);
        return $totalFiles > 0 ? ($recentFiles / $totalFiles) * 100 : 0;
    }
    
    /**
     * Gerar recomendações de otimização
     */
    private function generateRecommendations(array $report): array {
        $recommendations = [];
        $stats = $report['summary'];
        $performance = $report['performance'];
        
        // Recomendações baseadas no tamanho do cache
        if ($stats['total_size_mb'] > 100) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Cache muito grande',
                'message' => "O cache está ocupando {$stats['total_size_mb']} MB. Considere reduzir o tempo de expiração ou implementar limpeza mais frequente.",
                'action' => 'cleanup'
            ];
        }
        
        // Recomendações baseadas em arquivos expirados
        $expiredPercentage = $stats['total_files'] > 0 ? ($stats['expired_entries'] / $stats['total_files']) * 100 : 0;
        if ($expiredPercentage > 30) {
            $recommendations[] = [
                'type' => 'info',
                'title' => 'Muitos arquivos expirados',
                'message' => sprintf("%.1f%% dos arquivos de cache estão expirados. Execute uma limpeza.", $expiredPercentage),
                'action' => 'cleanup_expired'
            ];
        }
        
        // Recomendações baseadas em arquivos corrompidos
        if ($performance['corrupted_files'] > 0) {
            $recommendations[] = [
                'type' => 'error',
                'title' => 'Arquivos corrompidos detectados',
                'message' => "{$performance['corrupted_files']} arquivos de cache estão corrompidos e devem ser removidos.",
                'action' => 'fix_corrupted'
            ];
        }
        
        // Recomendações baseadas na distribuição de tipos
        $searchRatio = $performance['total_files'] > 0 ? ($performance['search_caches'] / $performance['total_files']) * 100 : 0;
        if ($searchRatio > 70) {
            $recommendations[] = [
                'type' => 'info',
                'title' => 'Muitos caches de busca',
                'message' => sprintf("%.1f%% do cache são resultados de busca. Considere reduzir o TTL de buscas.", $searchRatio),
                'action' => 'optimize_search_ttl'
            ];
        }
        
        // Recomendações baseadas na taxa de acerto estimada
        if ($performance['hit_rate_estimate'] < 50) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Taxa de acerto baixa',
                'message' => sprintf("Taxa de acerto estimada: %.1f%%. O cache pode não estar sendo efetivo.", $performance['hit_rate_estimate']),
                'action' => 'review_strategy'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Executar ação de otimização
     */
    public function executeOptimization(string $action): array {
        $result = ['success' => false, 'message' => '', 'details' => []];
        
        switch ($action) {
            case 'cleanup':
                $cleaned = $this->cache->clear();
                $result['success'] = $cleaned;
                $result['message'] = $cleaned ? 'Cache limpo com sucesso' : 'Erro ao limpar cache';
                break;
                
            case 'cleanup_expired':
                $count = $this->cache->cleanupExpired();
                $result['success'] = true;
                $result['message'] = "$count arquivos expirados removidos";
                $result['details']['files_removed'] = $count;
                break;
                
            case 'fix_corrupted':
                $count = $this->fixCorruptedFiles();
                $result['success'] = true;
                $result['message'] = "$count arquivos corrompidos removidos";
                $result['details']['files_fixed'] = $count;
                break;
                
            default:
                $result['message'] = "Ação '$action' não reconhecida";
        }
        
        $this->logAction($action, $result);
        return $result;
    }
    
    /**
     * Corrigir arquivos corrompidos
     */
    private function fixCorruptedFiles(): int {
        $files = glob(JIKAN_CACHE_DIR . '*.json');
        $fixed = 0;
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if ($content === false) {
                unlink($file);
                $fixed++;
                continue;
            }
            
            json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                unlink($file);
                $fixed++;
            }
        }
        
        return $fixed;
    }
    
    /**
     * Registrar ação no log
     */
    private function logAction(string $action, array $result): void {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'success' => $result['success'],
            'message' => $result['message'],
            'details' => $result['details'] ?? []
        ];
        
        $logLine = json_encode($logEntry) . "\n";
        file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Formatar bytes em formato legível
     */
    private function formatBytes(int $bytes): string {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }
        
        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }
    
    /**
     * Formatar duração em formato legível
     */
    private function formatDuration(int $seconds): string {
        if ($seconds < 60) {
            return $seconds . 's';
        } elseif ($seconds < 3600) {
            return round($seconds / 60) . 'm';
        } elseif ($seconds < 86400) {
            return round($seconds / 3600, 1) . 'h';
        } else {
            return round($seconds / 86400, 1) . 'd';
        }
    }
    
    /**
     * Obter logs de ações
     */
    public function getActionLogs(int $limit = 50): array {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) {
            return [];
        }
        
        $logs = [];
        $lines = array_reverse(array_slice($lines, -$limit));
        
        foreach ($lines as $line) {
            $log = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $logs[] = $log;
            }
        }
        
        return $logs;
    }
}