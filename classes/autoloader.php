<?php
/**
 * Autoloader para classes do MangApp
 * 
 * Este autoloader carrega automaticamente as classes necessárias
 * para o sistema local de gerenciamento de mangás.
 */

// Registrar autoloader
spl_autoload_register('mangapp_autoloader');

function mangapp_autoloader($className) 
{
    // Diretório base das classes
    $baseDir = __DIR__ . '/';
    
    // Mapeamento de classes para arquivos
    $classMap = [
        // Interfaces
        'APIInterface' => 'APIInterface.php',
        'APIException' => 'APIInterface.php',
        'NormalizedMangaData' => 'APIInterface.php',
        
        // Gerenciadores
        'ContentTypeDetector' => 'ContentTypeDetector.php',
        'RateLimiter' => 'RateLimiter.php',
        
        // Banco de Dados
        'Database' => 'Database.php',
        'Migration' => 'Migration.php',
        'MangaModel' => 'MangaModel.php',
        'UserLibrary' => 'UserLibrary.php',
        'ChapterManager' => 'ChapterManager.php',
        
        // Utilitários
        'APIMonitor' => 'APIMonitor.php',
        'CacheMonitor' => 'CacheMonitor.php',
        'MangaDataMigration' => 'MangaDataMigration.php'
    ];
    
    // Verificar se a classe está no mapeamento
    if (isset($classMap[$className])) {
        $file = $baseDir . $classMap[$className];
        
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // Fallback: tentar carregar pelo nome da classe
    $file = $baseDir . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
}

/**
 * Carregar configurações do sistema local
 */
function loadLocalConfig(): array 
{
    // Configuração simplificada para sistema local
    return [
        'local' => [
            'name' => 'Sistema Local',
            'enabled' => true,
            'cache_enabled' => true
        ]
    ];
}

/**
 * Inicializar sistema local
 */
function initializeLocalSystem(): void 
{
    // Sistema local apenas - APIs externas removidas
    // Configuração simplificada para sistema local
}