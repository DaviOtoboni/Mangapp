<?php
/**
 * Página de Login do MangApp
 * Permite entrada com nome de usuário ou email e senha
 */

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se já está logado
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// Inicializar tema padrão se não estiver definido
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

// Processar formulário de login
$error_message = '';
$success_message = '';

// Verificar se foi redirecionado após logout
if (isset($_GET['logged_out']) && $_GET['logged_out'] == '1') {
    $success_message = 'Você foi desconectado com sucesso.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username_or_email = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username_or_email) || empty($password)) {
        $error_message = 'Por favor, preencha todos os campos.';
    } else {
        // Sistema de autenticação simples (em produção, usar hash de senha e banco de dados)
        $valid_users = [
            'admin' => 'admin123',
            'usuario' => 'senha123',
            'teste@email.com' => 'teste123'
        ];
        
        $login_successful = false;
        
        // Verificar se é email ou nome de usuário
        if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
            // É um email
            if (isset($valid_users[$username_or_email]) && $valid_users[$username_or_email] === $password) {
                $login_successful = true;
            }
        } else {
            // É um nome de usuário
            if (isset($valid_users[$username_or_email]) && $valid_users[$username_or_email] === $password) {
                $login_successful = true;
            }
        }
        
        if ($login_successful) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['username'] = $username_or_email;
            $_SESSION['login_time'] = time();
            
            // Redirecionar para o dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = 'Credenciais inválidas. Verifique seu nome de usuário/email e senha.';
        }
    }
}

// Processar toggle de tema
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_theme') {
    $_SESSION['theme'] = $_SESSION['theme'] === 'light' ? 'dark' : 'light';
    echo json_encode(['theme' => $_SESSION['theme']]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?php echo $_SESSION['theme']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MangApp</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles-login.css">
</head>
<body data-theme="<?php echo $_SESSION['theme']; ?>">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-left">
                    <a href="index.php" class="logo">
                        <i class="fas fa-book-open"></i>
                        MangApp
                    </a>
                </div>
                <div class="navbar-right">
                    <button class="theme-toggle" onclick="toggleTheme()">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="login-container">
                <div class="login-card">
                    <div class="login-header">
                        <div class="login-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h1 class="login-title">Entrar</h1>
                        <p class="login-subtitle">Acesse sua conta e gerencie seu progresso</p>
                    </div>

                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="login-form">
                        <input type="hidden" name="action" value="login">
                        
                        <div class="form-group">
                            <label for="username_or_email" class="form-label">
                                <i class="fas fa-user"></i>
                                Nome de usuário ou Email
                            </label>
                            <input type="text" 
                                   id="username_or_email" 
                                   name="username_or_email" 
                                   class="form-input" 
                                   placeholder="Digite seu nome de usuário ou email"
                                   value="<?php echo htmlspecialchars($_POST['username_or_email'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i>
                                Senha
                            </label>
                            <div class="password-input-container">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-input password-input" 
                                       placeholder="Digite sua senha"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options">
                            <div class="checkbox-group">
                                <input type="checkbox" id="remember_me" name="remember_me" class="form-checkbox">
                                <label for="remember_me" class="checkbox-label">Lembrar de mim</label>
                            </div>
                            <a href="forgot-password.php" class="forgot-password">Esqueceu a senha?</a>
                        </div>

                        <button type="submit" class="login-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            Entrar
                        </button>
                    </form>

                    <div class="login-footer">
                        <p>Não tem uma conta? <a href="register.php" class="register-link">Criar conta</a></p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="script-login.js"></script>
</body>
</html>
