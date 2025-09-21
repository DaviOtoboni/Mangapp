<?php
/**
 * Arquivo de Configuração Simples do MangApp
 */

// Evitar acesso direto (exceto durante testes)
if (!defined('MANGAPP_API_INITIALIZED') && !defined('LOCAL_SYSTEM_INITIALIZED') && !defined('TESTING_MODE')) {
    die('Acesso direto não permitido');
}

// Configurações básicas do sistema
define('MANGAPP_VERSION', '1.0.0');
define('MANGAPP_NAME', 'MangApp - Gerenciador de Mangás');

// Configurações de upload
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']);
define('UPLOAD_DIR', 'covers/originals/');

// Configurações de sessão
define('SESSION_MANGAS_KEY', 'mangas');
define('SESSION_THEME_KEY', 'theme');

// Configurações de debug
define('DEBUG_MODE', false);
define('LOG_ERRORS', true);

// Configurações de tema
define('DEFAULT_THEME', 'light');

// Configurações de status de manga
define('MANGA_STATUSES', [
    'assistindo' => 'Assistindo',
    'pretendo' => 'Pretendo Ler',
    'abandonado' => 'Abandonado',
    'completado' => 'Completado'
]);

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de encoding
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

// Configurações de erro
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Função helper para obter configuração
function getConfig($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}

// Função helper para verificar se debug está ativo
function isDebugMode() {
    return DEBUG_MODE;
}

// Função helper para sanitizar dados
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Função helper para formatar tamanho de arquivo
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 2) . ' ' . $units[$pow];
}

// Função helper para obter status de manga em português
function getMangaStatusText($status) {
    $statuses = getConfig('MANGA_STATUSES', []);
    return isset($statuses[$status]) ? $statuses[$status] : ucfirst($status);
}

// Marcar como inicializado
if (!defined('LOCAL_SYSTEM_INITIALIZED')) {
    define('LOCAL_SYSTEM_INITIALIZED', true);
}
?>
