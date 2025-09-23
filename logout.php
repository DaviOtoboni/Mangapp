<?php
/**
 * Página de Logout do MangApp
 * Destrói a sessão e redireciona para login
 */

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir configurações do Supabase
define('LOCAL_SYSTEM_INITIALIZED', true);
require_once 'config-supabase.php';

// Fazer logout no Supabase
logoutWithSupabase();

// Limpar token do Supabase da sessão
unset($_SESSION['supabase_access_token']);

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Destruir o cookie de sessão se existir
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir a sessão
session_destroy();

// Redirecionar para a página de login
header('Location: login.php?logged_out=1');
exit;
?>
