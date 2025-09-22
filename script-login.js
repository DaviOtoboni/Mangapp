/**
 * Script para funcionalidades da página de login
 */

// Toggle de senha (suporte a múltiplos campos)
function togglePassword(fieldId = 'password') {
    const passwordInput = document.getElementById(fieldId);
    const toggleButton = passwordInput.parentNode.querySelector('.password-toggle i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleButton.className = 'fas fa-eye';
    }
}

// Toggle de tema
function toggleTheme() {
    const body = document.body;
    const currentTheme = body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    body.setAttribute('data-theme', newTheme);
    
    // Atualizar ícone do tema
    const icon = document.querySelector('.theme-toggle i');
    if (icon) {
        icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
    
    // Salvar tema via AJAX
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=toggle_theme'
    }).catch(error => {
        console.error('Erro ao salvar tema:', error);
    });
}

// Validação do formulário
function validateForm() {
    const usernameOrEmail = document.getElementById('username_or_email').value.trim();
    const password = document.getElementById('password').value;
    
    if (!usernameOrEmail || !password) {
        showAlert('Por favor, preencha todos os campos.', 'error');
        return false;
    }
    
    // Validação básica de email se for email
    if (usernameOrEmail.includes('@')) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(usernameOrEmail)) {
            showAlert('Por favor, insira um email válido.', 'error');
            return false;
        }
    }
    
    return true;
}

// Mostrar alertas
function showAlert(message, type = 'error') {
    // Remover alertas existentes
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Criar novo alerta
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
        ${message}
    `;
    
    // Inserir antes do formulário
    const form = document.querySelector('.login-form');
    form.parentNode.insertBefore(alert, form);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

// Animações de entrada
function addInputAnimations() {
    const inputs = document.querySelectorAll('.form-input');
    
    inputs.forEach(input => {
        // Adicionar classe de foco
        input.addEventListener('focus', function() {
            this.parentNode.classList.add('focused');
        });
        
        // Remover classe de foco
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentNode.classList.remove('focused');
            }
        });
        
        // Verificar se já tem valor ao carregar
        if (input.value) {
            input.parentNode.classList.add('focused');
        }
    });
}

// Efeito de loading no botão
function setLoadingState(button, loading = true) {
    if (loading) {
        button.disabled = true;
        button.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i>
            Entrando...
        `;
    } else {
        button.disabled = false;
        button.innerHTML = `
            <i class="fas fa-sign-in-alt"></i>
            Entrar
        `;
    }
}

// Validação em tempo real
function addRealTimeValidation() {
    const usernameInput = document.getElementById('username_or_email');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    // Validação do campo de usuário/email (login)
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            const value = this.value.trim();
            const parent = this.parentNode;
            
            // Remover classes de validação anteriores
            parent.classList.remove('valid', 'invalid');
            
            if (value) {
                if (value.includes('@')) {
                    // É um email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(value)) {
                        parent.classList.add('valid');
                    } else {
                        parent.classList.add('invalid');
                    }
                } else {
                    // É um nome de usuário
                    if (value.length >= 3) {
                        parent.classList.add('valid');
                    } else {
                        parent.classList.add('invalid');
                    }
                }
            }
        });
    }
    
    // Validação do campo de email (registro/recuperação)
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const value = this.value.trim();
            const parent = this.parentNode;
            
            parent.classList.remove('valid', 'invalid');
            
            if (value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(value)) {
                    parent.classList.add('valid');
                } else {
                    parent.classList.add('invalid');
                }
            }
        });
    }
    
    // Validação da senha
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const value = this.value;
            const parent = this.parentNode;
            
            parent.classList.remove('valid', 'invalid');
            
            if (value) {
                if (value.length >= 6) {
                    parent.classList.add('valid');
                } else {
                    parent.classList.add('invalid');
                }
            }
            
            // Se existe campo de confirmação, validar também
            if (confirmPasswordInput && confirmPasswordInput.value) {
                validatePasswordMatch();
            }
        });
    }
    
    // Validação da confirmação de senha
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    }
}

// Validação de confirmação de senha
function validatePasswordMatch() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (passwordInput && confirmPasswordInput) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const parent = confirmPasswordInput.parentNode;
        
        parent.classList.remove('password-match', 'password-mismatch');
        
        if (confirmPassword) {
            if (password === confirmPassword) {
                parent.classList.add('password-match');
            } else {
                parent.classList.add('password-mismatch');
            }
        }
    }
}

// Auto-focus no primeiro campo
function setAutoFocus() {
    const firstInput = document.querySelector('.form-input');
    if (firstInput) {
        setTimeout(() => {
            firstInput.focus();
        }, 500);
    }
}

// Efeitos visuais para o formulário
function addFormEffects() {
    const form = document.querySelector('.login-form');
    const inputs = document.querySelectorAll('.form-input');
    
    // Efeito de digitação
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value) {
                this.style.borderColor = 'var(--primary-color)';
            } else {
                this.style.borderColor = 'var(--border-color)';
            }
        });
    });
    
    // Efeito no submit
    form.addEventListener('submit', function(e) {
        if (validateForm()) {
            const submitButton = this.querySelector('.login-btn');
            setLoadingState(submitButton, true);
            
            // Simular delay de processamento
            setTimeout(() => {
                // O formulário será enviado normalmente
            }, 1000);
        } else {
            e.preventDefault();
        }
    });
}

// Melhorar acessibilidade
function improveAccessibility() {
    // Adicionar labels para screen readers
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        if (!input.getAttribute('aria-label')) {
            const label = input.previousElementSibling;
            if (label && label.classList.contains('form-label')) {
                input.setAttribute('aria-label', label.textContent.trim());
            }
        }
    });
    
    // Adicionar role para o formulário
    const form = document.querySelector('.login-form');
    if (form) {
        form.setAttribute('role', 'form');
        form.setAttribute('aria-label', 'Formulário de login');
    }
    
    // Adicionar navegação por teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.classList.contains('form-input')) {
            const form = e.target.closest('form');
            if (form) {
                const inputs = Array.from(form.querySelectorAll('.form-input'));
                const currentIndex = inputs.indexOf(e.target);
                const nextInput = inputs[currentIndex + 1];
                
                if (nextInput) {
                    e.preventDefault();
                    nextInput.focus();
                } else {
                    // Se for o último campo, submeter o formulário
                    const submitButton = form.querySelector('.login-btn');
                    if (submitButton && !submitButton.disabled) {
                        e.preventDefault();
                        submitButton.click();
                    }
                }
            }
        }
    });
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Configurar funcionalidades
    addInputAnimations();
    addRealTimeValidation();
    addFormEffects();
    improveAccessibility();
    setAutoFocus();
    
    // Atualizar ícone do tema na inicialização
    const currentTheme = document.body.getAttribute('data-theme');
    const icon = document.querySelector('.theme-toggle i');
    if (icon) {
        icon.className = currentTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
    
    // Adicionar efeito de entrada suave
    const loginCard = document.querySelector('.login-card');
    if (loginCard) {
        loginCard.style.opacity = '0';
        loginCard.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            loginCard.style.transition = 'all 0.6s ease';
            loginCard.style.opacity = '1';
            loginCard.style.transform = 'translateY(0)';
        }, 100);
    }
    
    // Adicionar efeito de hover nos campos
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach(group => {
        const input = group.querySelector('.form-input');
        if (input) {
            group.addEventListener('mouseenter', function() {
                if (!input.disabled) {
                    input.style.borderColor = 'var(--primary-color)';
                    input.style.boxShadow = '0 0 0 3px rgba(99, 102, 241, 0.1)';
                }
            });
            
            group.addEventListener('mouseleave', function() {
                if (!input.disabled && !input.matches(':focus')) {
                    input.style.borderColor = 'var(--border-color)';
                    input.style.boxShadow = 'none';
                }
            });
        }
    });
});

// Adicionar estilos dinâmicos para validação
const validationStyles = `
    .form-group.valid .form-input {
        border-color: var(--success-color) !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
    }
    
    .form-group.invalid .form-input {
        border-color: var(--danger-color) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .form-group.focused .form-label {
        color: var(--primary-color);
    }
    
    .login-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }
    
    .login-btn:disabled:hover {
        transform: none !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = validationStyles;
document.head.appendChild(styleSheet);
