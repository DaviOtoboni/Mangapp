<?php
/**
 * Página de entrada do MangApp
 * Redireciona para a página principal de mangás
 */

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirecionar para a página de mangás
header('Location: index-mangas.php');
exit;
?>
