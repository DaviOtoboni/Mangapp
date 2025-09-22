<?php
/**
 * Página de entrada do MangApp
 * Redireciona para dashboard (login temporariamente desabilitado)
 */

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// TEMPORÁRIO: Desabilitar sistema de login
// Simular usuário logado para acesso direto
$_SESSION['user_logged_in'] = true;
$_SESSION['username'] = 'usuario_demo';
$_SESSION['login_time'] = time();

// Redirecionar diretamente para o dashboard
header('Location: dashboard.php');
exit;
?>
