<?php
/**
 * Verificação de Autenticação - MangApp
 * Incluir este arquivo em páginas que precisam de login
 */

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir configurações do Supabase
define('LOCAL_SYSTEM_INITIALIZED', true);
require_once 'config-supabase.php';

// Verificar se usuário está logado
if (!isUserLoggedInSupabase()) {
    // Usuário não logado, redirecionar para login
    $current_page = basename($_SERVER['PHP_SELF']);
    header('Location: login.php?redirect=' . urlencode($current_page));
    exit;
}

// Se chegou até aqui, usuário está logado
// Opcional: Atualizar dados do usuário na sessão
$current_user = getCurrentUserSupabase();
if ($current_user) {
    $_SESSION['user_id'] = $current_user['id'];
    $_SESSION['user_email'] = $current_user['email'];
    $_SESSION['username'] = $current_user['user_metadata']['username'] ?? $current_user['email'];
    $_SESSION['login_time'] = time();
}
?>
