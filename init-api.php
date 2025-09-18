<?php
/**
 * Arquivo de inicialização do Sistema de APIs do MangApp
 * Carrega todas as classes necessárias e inicializa o sistema multi-API
 */

// Verificar se já foi inicializado
if (defined('MANGAPP_API_INITIALIZED')) {
    return;
}

// Carregar autoloader para novas classes
require_once __DIR__ . '/classes/autoloader.php';

// Incluir configuração primeiro
require_once __DIR__ . '/config.php';

// Sistema local apenas - classes de API removidas
// Carregar apenas classes essenciais que existem

// Carregar MangaDataProcessor
if (!class_exists('MangaDataProcessor')) {
    require_once __DIR__ . '/classes/MangaDataProcessorSimple.php';
}

// Sistema local apenas - APIs externas removidas
// Classes de API removidas para simplificar o sistema

// Marcar como inicializado (apenas se não foi definido antes)
if (!defined('LOCAL_SYSTEM_INITIALIZED')) {
    define('LOCAL_SYSTEM_INITIALIZED', true);
}
if (!defined('MANGAPP_LOCAL_INITIALIZED')) {
    define('MANGAPP_LOCAL_INITIALIZED', true);
}

// Sistema de erros removido - sistema local simplificado

// Funções helper removidas - sistema local apenas
// APIs externas foram removidas para simplificar o projeto

// Log de inicialização
if (function_exists('error_log')) {
    error_log("Sistema Local MangApp inicializado com sucesso");
}
?>