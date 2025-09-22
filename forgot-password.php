<?php
/**
 * Página de Recuperação de Senha do MangApp
 * Permite solicitar redefinição de senha
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

// Processar formulário de recuperação
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'forgot_password') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error_message = 'Por favor, insira seu email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Por favor, insira um email válido.';
    } else {
        // Simular verificação de email (em produção, verificar no banco de dados)
        $valid_emails = ['teste@email.com', 'admin@mangapp.com', 'usuario@mangapp.com'];
        
        if (in_array($email, $valid_emails)) {
            $success_message = 'Instruções de recuperação foram enviadas para seu email.';
        } else {
            $error_message = 'Email não encontrado em nossa base de dados.';
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
    <title>Esqueceu a Senha - MangApp</title>
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
                            <i class="fas fa-key"></i>
                        </div>
                        <h1 class="login-title">Esqueceu a Senha?</h1>
                        <p class="login-subtitle">Digite seu email para receber instruções de recuperação</p>
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

                    <form method="POST" class="login-form" id="forgotPasswordForm">
                        <input type="hidden" name="action" value="forgot_password">
                        
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input" 
                                   placeholder="Ex: email@email.com"
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   required>
                        </div>

                        <button type="submit" class="login-btn">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Instruções
                        </button>
                    </form>

                    <div class="login-footer">
                        <p>Lembrou da senha? <a href="login.php" class="register-link">Fazer login</a></p>
                        <p>Não tem uma conta? <a href="register.php" class="register-link">Criar conta</a></p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="script-login.js"></script>
    <script>
        // Validação específica para recuperação de senha
        function validateForgotPasswordForm() {
            const email = document.getElementById('email').value.trim();
            
            if (!email) {
                showAlert('Por favor, insira seu email.', 'error');
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showAlert('Por favor, insira um email válido.', 'error');
                return false;
            }
            
            return true;
        }
        
        // Adicionar validação ao formulário
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            if (!validateForgotPasswordForm()) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
