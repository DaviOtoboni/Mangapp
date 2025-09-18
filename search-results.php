<?php 
ob_start();
session_start();

// Inicializar sistema local
require_once 'init-api.php';

// Inicializar array de mangás se não existir
if (!isset($_SESSION['mangas'])) {
    $_SESSION['mangas'] = [];
}

// Inicializar tema se não existir
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

// Processar toggle de tema
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_theme') {
    $_SESSION['theme'] = $_SESSION['theme'] === 'light' ? 'dark' : 'light';
    echo json_encode(['success' => true, 'theme' => $_SESSION['theme']]);
    exit;
}

// Processar adição de mangá da API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_from_api') {
    if (!empty($_POST['api_data'])) {
        try {
            $api_data = json_decode($_POST['api_data'], true);
            
            $manga = [
                'id' => uniqid(),
                'nome' => $api_data['title'],
                'status' => 'pretendo', // Status padrão
                'capitulos_total' => $api_data['chapters'] ?? 0,
                'finalizado' => false,
                'capitulo_atual' => 0,
                'em_lancamento' => ($api_data['status'] ?? '') === 'Publishing',
                'comentario' => 'Importado da API - ' . (isset($api_data['synopsis']) ? substr($api_data['synopsis'], 0, 100) . '...' : ''),
                'data_criacao' => date('Y-m-d H:i:s'),
                'api_data' => $api_data,
                'imported_from_api' => true
            ];
            
            $_SESSION['mangas'][] = $manga;
            
            // Redirecionar para a página principal
            header('Location: index-mangas.php?added=1');
            exit;
        } catch (Exception $e) {
            $error_message = "Erro ao adicionar mangá: " . $e->getMessage();
        }
    }
}

// Obter termo de pesquisa
$termo_pesquisa = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$resultados_locais = [];
$resultados_api = [];
$error_message = '';

if (!empty($termo_pesquisa)) {
    // Busca local primeiro
    $resultados_locais = array_filter($_SESSION['mangas'], function($manga) use ($termo_pesquisa) {
        return stripos($manga['nome'], $termo_pesquisa) !== false ||
               (isset($manga['comentario']) && stripos($manga['comentario'], $termo_pesquisa) !== false);
    });
    
    // Buscar na API
    if (strlen($termo_pesquisa) >= 2) {
        try {
            // Sistema local apenas - sem API externa
            $apiResults = [];
            
            if (!empty($apiResults['data'])) {
                $resultados_api = $apiResults['data'];
            }
        } catch (Exception $e) {
            $error_message = "Erro na busca: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?php echo $_SESSION['theme']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Busca - MangApp</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS externo APENAS para navbar -->
    <link rel="stylesheet" href="styles-mangas.css">
    
    <style>
        /* Sobrescrever apenas estilos base que conflitam */
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --border-color: #e5e7eb;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] {
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --bg-tertiary: #374151;
            --border-color: #4b5563;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navbar usa CSS externo (styles.css) */
        
        /* Main Content - Estilos específicos da página de busca */
        .main-content {
            padding: 2rem 0;
        }

        .search-info {
            background: var(--bg-primary);
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .search-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .search-input-form {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-secondary {
            background-color: var(--text-secondary);
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .results-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .manga-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.2s;
        }

        .manga-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .manga-info {
            display: flex;
            gap: 1rem;
        }

        .manga-cover {
            width: 80px;
            height: 110px;
            object-fit: cover;
            border-radius: 0.5rem;
            flex-shrink: 0;
        }

        .manga-details {
            flex: 1;
        }

        .manga-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .manga-meta {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .manga-synopsis {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.4;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .manga-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            margin-right: 0.5rem;
        }

        .badge-success {
            background-color: var(--success-color);
            color: white;
        }

        .badge-warning {
            background-color: var(--warning-color);
            color: white;
        }

        .badge-info {
            background-color: var(--info-color);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .error-message {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        [data-theme="dark"] .error-message {
            background-color: #7f1d1d;
            border-color: #991b1b;
            color: #fca5a5;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .results-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body data-theme="<?php echo $_SESSION['theme']; ?>">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-left">
                    <a href="index-mangas.php" class="logo">
                        <i class="fas fa-book-open"></i>
                        MangApp
                    </a>
                    <div class="search-bar">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Pesquisar mangás..." 
                               value="<?php echo htmlspecialchars($termo_pesquisa); ?>"
                               onkeypress="if(event.key==='Enter') window.location.href='search-results.php?q='+encodeURIComponent(this.value)">
                    </div>
                </div>
                <div class="navbar-right">
                    <div class="nav-links">
                        <a href="index-mangas.php" class="nav-link">
                            Mangás
                        </a>
                        <a href="#" class="nav-link">Animes</a>
                        <a href="#" class="nav-link">Jogos</a>
                    </div>
                    <button class="theme-toggle" onclick="toggleTheme()">
                        <i class="fas fa-moon"></i>
                    </button>
                    <a href="#" class="user-icon">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container">
        <div class="search-info">
            <form class="search-form" method="GET">
                <input type="text" name="q" class="search-input-form" 
                       placeholder="Digite o nome do mangá..." 
                       value="<?php echo htmlspecialchars($termo_pesquisa); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="index-mangas.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </form>
            
            <?php if (!empty($termo_pesquisa)): ?>
                <p>Resultados para: <strong>"<?php echo htmlspecialchars($termo_pesquisa); ?>"</strong></p>
                <p>Locais: <?php echo count($resultados_locais); ?> | API: <?php echo count($resultados_api); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($resultados_locais)): ?>
            <div class="results-section">
                <h2 class="section-title">
                    <i class="fas fa-home"></i> Seus Mangás (<?php echo count($resultados_locais); ?>)
                </h2>
                <div class="results-grid">
                    <?php foreach ($resultados_locais as $manga): ?>
                        <div class="manga-card">
                            <div class="manga-info">
                                <div class="manga-details">
                                    <h3 class="manga-title"><?php echo htmlspecialchars($manga['nome']); ?></h3>
                                    <div class="manga-meta">
                                        <span class="badge badge-<?php echo $manga['status'] === 'lendo' ? 'success' : ($manga['status'] === 'pretendo' ? 'info' : 'warning'); ?>">
                                            <?php echo ucfirst($manga['status']); ?>
                                        </span>
                                        <?php if ($manga['capitulos_total'] > 0): ?>
                                            <?php echo $manga['capitulo_atual']; ?>/<?php echo $manga['capitulos_total']; ?> capítulos
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($manga['comentario'])): ?>
                                        <p class="manga-synopsis"><?php echo htmlspecialchars($manga['comentario']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($resultados_api)): ?>
            <div class="results-section">
                <h2 class="section-title">
                    <i class="fas fa-cloud"></i> Resultados da API (<?php echo count($resultados_api); ?>)
                </h2>
                <div class="results-grid">
                    <?php foreach ($resultados_api as $manga): ?>
                        <div class="manga-card">
                            <div class="manga-info">
                                <?php if (!empty($manga['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($manga['image_url']); ?>" 
                                         alt="Capa" class="manga-cover"
                                         onerror="this.style.display='none'">
                                <?php endif; ?>
                                <div class="manga-details">
                                    <h3 class="manga-title"><?php echo htmlspecialchars($manga['title']); ?></h3>
                                    <div class="manga-meta">
                                        <?php if (!empty($manga['status'])): ?>
                                            <span class="badge badge-info"><?php echo htmlspecialchars($manga['status']); ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($manga['chapters'])): ?>
                                            <?php echo $manga['chapters']; ?> capítulos
                                        <?php endif; ?>
                                        <?php if (!empty($manga['score'])): ?>
                                            | ⭐ <?php echo $manga['score']; ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($manga['synopsis'])): ?>
                                        <p class="manga-synopsis"><?php echo htmlspecialchars($manga['synopsis']); ?></p>
                                    <?php endif; ?>
                                    <div class="manga-actions">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="add_from_api">
                                            <input type="hidden" name="api_data" value="<?php echo htmlspecialchars(json_encode($manga)); ?>">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Adicionar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($resultados_locais) && empty($resultados_api) && !empty($termo_pesquisa)): ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Nenhum resultado encontrado</h3>
                <p>Nenhum mangá encontrado para "<strong><?php echo htmlspecialchars($termo_pesquisa); ?></strong>"</p>
                <p>Tente usar termos diferentes ou verificar a ortografia.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($termo_pesquisa)): ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Digite um termo para buscar</h3>
                <p>Use o campo acima para buscar mangás em sua coleção e na API.</p>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            body.setAttribute('data-theme', newTheme);
            
            // Atualizar ícone imediatamente
            const icon = document.querySelector('.theme-toggle i');
            icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            
            // Salvar tema via AJAX
            fetch('search-results.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=toggle_theme'
            }).catch(error => {
                console.error('Erro ao salvar tema:', error);
            });
        }

        // Atualizar ícone do tema na inicialização
        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.body.getAttribute('data-theme');
            const icon = document.querySelector('.theme-toggle i');
            icon.className = currentTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        });
    </script>
</body>
</html>