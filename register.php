<?php
/**
 * Página de Registro do MangApp
 * Permite criação de nova conta
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

// Processar formulário de registro
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms_accepted = isset($_POST['terms_accepted']);
    
    // Validações
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'Por favor, preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Por favor, insira um email válido.';
    } elseif (strlen($username) < 3) {
        $error_message = 'O nome de usuário deve ter pelo menos 3 caracteres.';
    } elseif (strlen($password) < 6) {
        $error_message = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'As senhas não coincidem.';
    } elseif (!$terms_accepted) {
        $error_message = 'Você deve aceitar os termos de uso.';
    } else {
        // Simular verificação de usuário existente (em produção, verificar no banco de dados)
        $existing_users = ['admin', 'usuario', 'teste'];
        
        if (in_array(strtolower($username), $existing_users)) {
            $error_message = 'Este nome de usuário já está em uso.';
        } else {
            // Simular criação de conta (em produção, salvar no banco de dados)
            $success_message = 'Conta criada com sucesso! Você pode fazer login agora.';
            
            // Redirecionar para login após 2 segundos
            header('refresh:2;url=login.php');
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
    <title>Criar Conta - MangApp</title>
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
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h1 class="login-title">Criar Conta</h1>
                        <p class="login-subtitle">Crie sua conta e comece a gerenciar seus mangás</p>
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

                    <form method="POST" class="login-form" id="registerForm">
                        <input type="hidden" name="action" value="register">
                        
                        <div class="form-group">
                            <label for="username" class="form-label">
                                <i class="fas fa-user"></i>
                                Nome de Usuário
                            </label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   class="form-input" 
                                   placeholder="Digite seu nome de usuário"
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input" 
                                   placeholder="Digite seu email"
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
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
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-lock"></i>
                                Confirmar Senha
                            </label>
                            <div class="password-input-container">
                                <input type="password" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       class="form-input password-input" 
                                       placeholder="Confirme sua senha"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="terms_accepted" name="terms_accepted" class="form-checkbox" required>
                                <label for="terms_accepted" class="checkbox-label">
                                    Eu aceito os <a href="#" class="terms-link">Termos de Uso</a> e <a href="#" class="privacy-link">Política de Privacidade</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="login-btn">
                            <i class="fas fa-user-plus"></i>
                            Criar Conta
                        </button>
                    </form>

                    <div class="login-footer">
                        <p>Já tem uma conta? <a href="login.php" class="register-link">Fazer login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="script-login.js"></script>
    <script>
        // Validação específica para registro
        function validateRegisterForm() {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const termsAccepted = document.getElementById('terms_accepted').checked;
            
            if (!username || !email || !password || !confirmPassword) {
                showAlert('Por favor, preencha todos os campos.', 'error');
                return false;
            }
            
            if (username.length < 3) {
                showAlert('O nome de usuário deve ter pelo menos 3 caracteres.', 'error');
                return false;
            }
            
            if (password.length < 6) {
                showAlert('A senha deve ter pelo menos 6 caracteres.', 'error');
                return false;
            }
            
            if (password !== confirmPassword) {
                showAlert('As senhas não coincidem.', 'error');
                return false;
            }
            
            if (!termsAccepted) {
                showAlert('Você deve aceitar os termos de uso.', 'error');
                return false;
            }
            
            return true;
        }
        
        // Adicionar validação ao formulário
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (!validateRegisterForm()) {
                e.preventDefault();
            }
        });
        
        // Validação em tempo real da confirmação de senha
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const parent = this.parentNode;
            
            parent.classList.remove('valid', 'invalid');
            
            if (confirmPassword) {
                if (password === confirmPassword) {
                    parent.classList.add('valid');
                } else {
                    parent.classList.add('invalid');
                }
            }
        });
    </script>
</body>
</html>
