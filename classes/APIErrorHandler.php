<?php
/**
 * Tratador de Erros para APIs
 * 
 * Esta classe gerencia erros específicos das APIs de forma robusta,
 * implementando retry com backoff exponencial e degradação graciosa.
 */

class APIErrorHandler 
{
    private static array $errorCounts = [];
    private static array $lastErrors = [];
    
    // Mapeamento de erros para mensagens amigáveis
    private const ERROR_MESSAGES = [
        'rate_limit' => 'Muitas requisições. Aguarde alguns segundos e tente novamente.',
        'not_found' => 'Conteúdo não encontrado.',
        'server_error' => 'Erro no servidor da API. Tente novamente mais tarde.',
        'network_error' => 'Erro de conexão. Verifique sua internet.',
        'invalid_response' => 'Resposta inválida da API.',
        'timeout' => 'Tempo limite excedido. Tente novamente.',
        'api_unavailable' => 'API temporariamente indisponível.',
        'authentication_failed' => 'Falha na autenticação com a API.',
        'quota_exceeded' => 'Cota de requisições excedida.',
        'maintenance' => 'API em manutenção. Tente mais tarde.'
    ];
    
    // Configurações de retry
    private const RETRY_CONFIG = [
        'max_attempts' => 3,
        'base_delay' => 1, // segundos
        'max_delay' => 30, // segundos
        'backoff_multiplier' => 2
    ];
    
    /**
     * Tratar erro de API com retry automático
     */
    public static function handleAPIError(Exception $e, string $apiName, callable $retryCallback = null): array 
    {
        $errorType = self::classifyError($e);
        $errorMessage = self::getErrorMessage($errorType);
        
        // Registrar erro
        self::logError($apiName, $errorType, $e->getMessage());
        
        // Verificar se deve tentar retry
        if ($retryCallback && self::shouldRetry($errorType, $apiName)) {
            return self::executeWithRetry($retryCallback, $apiName, $errorType);
        }
        
        return [
            'success' => false,
            'error_type' => $errorType,
            'message' => $errorMessage,
            'api' => $apiName,
            'can_retry' => self::canRetry($errorType),
            'retry_after' => self::getRetryDelay($apiName, $errorType)
        ];
    }
    
    /**
     * Executar operação com retry automático
     */
    public static function executeWithRetry(callable $operation, string $apiName, string $context = 'operation'): array 
    {
        $attempts = 0;
        $maxAttempts = self::RETRY_CONFIG['max_attempts'];
        $lastError = null;
        
        while ($attempts < $maxAttempts) {
            try {
                $result = $operation();
                
                // Resetar contador de erros em caso de sucesso
                self::resetErrorCount($apiName);
                
                return [
                    'success' => true,
                    'data' => $result,
                    'attempts' => $attempts + 1
                ];
                
            } catch (Exception $e) {
                $attempts++;
                $lastError = $e;
                $errorType = self::classifyError($e);
                
                // Log da tentativa
                error_log("Tentativa $attempts/$maxAttempts falhou para $apiName ($context): " . $e->getMessage());
                
                // Se não deve fazer retry ou é a última tentativa
                if (!self::canRetry($errorType) || $attempts >= $maxAttempts) {
                    break;
                }
                
                // Aguardar antes da próxima tentativa
                $delay = self::calculateBackoffDelay($attempts);
                sleep($delay);
            }
        }
        
        // Todas as tentativas falharam
        return self::handleAPIError($lastError, $apiName);
    }
    
    /**
     * Classificar tipo de erro
     */
    private static function classifyError(Exception $e): string 
    {
        $message = strtolower($e->getMessage());
        $code = $e->getCode();
        
        // Classificar por código HTTP
        switch ($code) {
            case 429:
                return 'rate_limit';
            case 404:
                return 'not_found';
            case 401:
            case 403:
                return 'authentication_failed';
            case 500:
            case 502:
            case 503:
                return 'server_error';
            case 504:
                return 'timeout';
        }
        
        // Classificar por mensagem
        if (strpos($message, 'rate limit') !== false || strpos($message, 'too many requests') !== false) {
            return 'rate_limit';
        }
        
        if (strpos($message, 'timeout') !== false || strpos($message, 'timed out') !== false) {
            return 'timeout';
        }
        
        if (strpos($message, 'connection') !== false || strpos($message, 'network') !== false) {
            return 'network_error';
        }
        
        if (strpos($message, 'not found') !== false) {
            return 'not_found';
        }
        
        if (strpos($message, 'invalid') !== false || strpos($message, 'malformed') !== false) {
            return 'invalid_response';
        }
        
        if (strpos($message, 'maintenance') !== false) {
            return 'maintenance';
        }
        
        if (strpos($message, 'quota') !== false || strpos($message, 'limit exceeded') !== false) {
            return 'quota_exceeded';
        }
        
        // Erro genérico de servidor
        if ($code >= 500) {
            return 'server_error';
        }
        
        return 'unknown';
    }
    
    /**
     * Obter mensagem amigável para o erro
     */
    private static function getErrorMessage(string $errorType): string 
    {
        return self::ERROR_MESSAGES[$errorType] ?? 'Erro desconhecido. Tente novamente mais tarde.';
    }
    
    /**
     * Verificar se deve tentar retry
     */
    private static function shouldRetry(string $errorType, string $apiName): bool 
    {
        // Não fazer retry para alguns tipos de erro
        $noRetryErrors = ['not_found', 'authentication_failed', 'invalid_response'];
        
        if (in_array($errorType, $noRetryErrors)) {
            return false;
        }
        
        // Verificar se não excedeu o limite de tentativas
        $errorCount = self::getErrorCount($apiName);
        return $errorCount < self::RETRY_CONFIG['max_attempts'];
    }
    
    /**
     * Verificar se pode fazer retry
     */
    private static function canRetry(string $errorType): bool 
    {
        $retryableErrors = ['rate_limit', 'server_error', 'network_error', 'timeout', 'api_unavailable'];
        return in_array($errorType, $retryableErrors);
    }
    
    /**
     * Calcular delay para backoff exponencial
     */
    private static function calculateBackoffDelay(int $attempt): int 
    {
        $delay = self::RETRY_CONFIG['base_delay'] * pow(self::RETRY_CONFIG['backoff_multiplier'], $attempt - 1);
        return min($delay, self::RETRY_CONFIG['max_delay']);
    }
    
    /**
     * Obter delay recomendado para retry
     */
    private static function getRetryDelay(string $apiName, string $errorType): int 
    {
        switch ($errorType) {
            case 'rate_limit':
                return 60; // 1 minuto
            case 'server_error':
                return 300; // 5 minutos
            case 'network_error':
                return 30; // 30 segundos
            case 'timeout':
                return 10; // 10 segundos
            default:
                return 60; // 1 minuto padrão
        }
    }
    
    /**
     * Registrar erro
     */
    private static function logError(string $apiName, string $errorType, string $message): void 
    {
        // Incrementar contador de erros
        if (!isset(self::$errorCounts[$apiName])) {
            self::$errorCounts[$apiName] = 0;
        }
        self::$errorCounts[$apiName]++;
        
        // Registrar último erro
        self::$lastErrors[$apiName] = [
            'type' => $errorType,
            'message' => $message,
            'timestamp' => time()
        ];
        
        // Log detalhado
        $logMessage = "API Error [$apiName]: $errorType - $message";
        error_log($logMessage);
        
        // Log em arquivo específico se configurado
        if (defined('API_ERROR_LOG') && API_ERROR_LOG) {
            $logFile = __DIR__ . '/../logs/api_errors.log';
            $timestamp = date('Y-m-d H:i:s');
            $logEntry = "[$timestamp] $logMessage" . PHP_EOL;
            file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        }
    }
    
    /**
     * Obter contador de erros
     */
    private static function getErrorCount(string $apiName): int 
    {
        return self::$errorCounts[$apiName] ?? 0;
    }
    
    /**
     * Resetar contador de erros
     */
    private static function resetErrorCount(string $apiName): void 
    {
        self::$errorCounts[$apiName] = 0;
    }
    
    /**
     * Obter estatísticas de erros
     */
    public static function getErrorStats(): array 
    {
        return [
            'error_counts' => self::$errorCounts,
            'last_errors' => self::$lastErrors,
            'total_errors' => array_sum(self::$errorCounts)
        ];
    }
    
    /**
     * Verificar saúde das APIs
     */
    public static function checkAPIHealth(array $apis): array 
    {
        $health = [];
        
        foreach ($apis as $apiName => $api) {
            $errorCount = self::getErrorCount($apiName);
            $lastError = self::$lastErrors[$apiName] ?? null;
            
            // Determinar status de saúde
            $status = 'healthy';
            if ($errorCount > 5) {
                $status = 'degraded';
            }
            if ($errorCount > 10) {
                $status = 'unhealthy';
            }
            
            // Verificar se último erro foi recente
            if ($lastError && (time() - $lastError['timestamp']) < 300) { // 5 minutos
                $status = $status === 'healthy' ? 'degraded' : $status;
            }
            
            $health[$apiName] = [
                'status' => $status,
                'error_count' => $errorCount,
                'last_error' => $lastError,
                'available' => $status !== 'unhealthy'
            ];
        }
        
        return $health;
    }
    
    /**
     * Implementar circuit breaker
     */
    public static function isCircuitOpen(string $apiName): bool 
    {
        $errorCount = self::getErrorCount($apiName);
        $lastError = self::$lastErrors[$apiName] ?? null;
        
        // Abrir circuito se muitos erros recentes
        if ($errorCount > 10) {
            return true;
        }
        
        // Abrir circuito se último erro foi muito recente e crítico
        if ($lastError && (time() - $lastError['timestamp']) < 60) {
            $criticalErrors = ['server_error', 'api_unavailable', 'maintenance'];
            if (in_array($lastError['type'], $criticalErrors)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Tentar fechar circuito (half-open state)
     */
    public static function tryCloseCircuit(string $apiName): bool 
    {
        $lastError = self::$lastErrors[$apiName] ?? null;
        
        // Tentar fechar se último erro foi há mais de 5 minutos
        if (!$lastError || (time() - $lastError['timestamp']) > 300) {
            self::resetErrorCount($apiName);
            return true;
        }
        
        return false;
    }
    
    /**
     * Obter fallback response
     */
    public static function getFallbackResponse(string $operation, array $context = []): array 
    {
        $fallbacks = [
            'search' => [
                'success' => true,
                'data' => [],
                'message' => 'Busca temporariamente indisponível. Tente novamente mais tarde.',
                'fallback' => true
            ],
            'details' => [
                'success' => false,
                'data' => null,
                'message' => 'Detalhes temporariamente indisponíveis.',
                'fallback' => true
            ],
            'chapters' => [
                'success' => true,
                'data' => [],
                'message' => 'Capítulos temporariamente indisponíveis.',
                'fallback' => true
            ]
        ];
        
        return $fallbacks[$operation] ?? [
            'success' => false,
            'message' => 'Serviço temporariamente indisponível.',
            'fallback' => true
        ];
    }
}