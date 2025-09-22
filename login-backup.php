<?php
/**
 * BACKUP - Configurações originais do sistema de login
 * Use este arquivo para reativar o sistema de login quando necessário
 */

// Para reativar o sistema de login, substitua o conteúdo de index.php por:

/*
<?php
/**
 * Página de entrada do MangApp
 * Redireciona para login ou dashboard baseado no status de autenticação
 */

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    // Usuário logado, redirecionar para o dashboard
    header('Location: dashboard.php');
} else {
    // Usuário não logado, redirecionar para login
    header('Location: login.php');
}
exit;
?>
*/

// Para reativar as verificações de login nas páginas principais,
// descomente as linhas que começam com "// TEMPORÁRIO:" e remova os comentários
// das linhas originais de verificação de autenticação.

// Exemplo para dashboard.php:
/*
// Verificar se o usuário está logado
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
*/

// Exemplo para index-mangas.php, index-animes.php, index-games.php:
/*
// Verificar se o usuário está logado
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
*/
?>
