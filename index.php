<?php
/**
 * Página de entrada do MangApp
 * Redireciona para login se não estiver autenticado
 */

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir configurações do Supabase
define('LOCAL_SYSTEM_INITIALIZED', true);
require_once 'config-supabase.php';

// Verificar se usuário está logado
if (isUserLoggedInSupabase()) {
    // Usuário logado, redirecionar para dashboard
    header('Location: dashboard.php');
    exit;
} else {
    // Usuário não logado, redirecionar para login
    header('Location: login.php');
    exit;
}
?>
