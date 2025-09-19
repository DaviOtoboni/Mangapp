<?php
ob_start();
session_start();

// Inicializar sistema local
require_once 'init-api.php';

// Inicializar arrays se não existirem
if (!isset($_SESSION['mangas'])) {
    $_SESSION['mangas'] = [];
}
if (!isset($_SESSION['animes'])) {
    $_SESSION['animes'] = [];
}
if (!isset($_SESSION['jogos'])) {
    $_SESSION['jogos'] = [];
}

// Inicializar tema se não existir
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

// Processar toggle de tema
if (isset($_POST['action']) && $_POST['action'] === 'toggle_theme') {
    $_SESSION['theme'] = $_SESSION['theme'] === 'light' ? 'dark' : 'light';
    echo json_encode(['success' => true, 'theme' => $_SESSION['theme']]);
    exit;
}

// Função para converter status para texto completo
function getStatusText($status, $type = 'manga') {
    if ($type === 'anime') {
        $statusMap = [
            'lendo' => 'Assistindo',
            'pretendo' => 'Pretendo Assistir',
            'abandonado' => 'Abandonado',
            'completado' => 'Completado'
        ];
    } elseif ($type === 'jogo') {
        $statusMap = [
            'jogando' => 'Jogando',
            'pretendo' => 'Pretendo Jogar',
            'abandonado' => 'Abandonado',
            'completado' => 'Completado'
        ];
    } else {
        $statusMap = [
            'lendo' => 'Lendo',
            'pretendo' => 'Pretendo Ler',
            'abandonado' => 'Abandonado',
            'completado' => 'Completado'
        ];
    }
    
    return isset($statusMap[$status]) ? $statusMap[$status] : ucfirst($status);
}

// Função para formatar gêneros para exibição
function formatGenres($generos) {
    if (empty($generos) || !is_array($generos)) {
        return 'Não definido';
    }
    
    // Filtrar valores vazios e aplicar ucfirst
    $generosLimpos = array_filter($generos, function($genero) {
        return !empty(trim($genero));
    });
    
    if (empty($generosLimpos)) {
        return 'Não definido';
    }
    
    return implode(', ', array_map('ucfirst', $generosLimpos));
}

// Função para obter capa do item
function getCoverUrl($item, $type) {
    $cover_url = '';
    $has_custom_cover = false;
    
    // Verificar se tem capa personalizada
    if (!empty($item['custom_cover']) && file_exists("covers/originals/{$item['custom_cover']}")) {
        $cover_url = "covers/originals/{$item['custom_cover']}";
        $has_custom_cover = true;
    } elseif (file_exists("covers/originals/{$item['id']}.jpg") || file_exists("covers/originals/{$item['id']}.png") || file_exists("covers/originals/{$item['id']}.webp")) {
        $extensions = ['jpg', 'png', 'webp'];
        foreach ($extensions as $ext) {
            if (file_exists("covers/originals/{$item['id']}.{$ext}")) {
                $cover_url = "covers/originals/{$item['id']}.{$ext}";
                $has_custom_cover = true;
                break;
            }
        }
    }
    
    // Se não tem capa personalizada, verificar dados da API
    if (!$has_custom_cover && !empty($item['api_data'])) {
        if (isset($item['api_data']['images']['jpg']['large_image_url'])) {
            $cover_url = $item['api_data']['images']['jpg']['large_image_url'];
        } elseif (isset($item['api_data']['images']['jpg']['image_url'])) {
            $cover_url = $item['api_data']['images']['jpg']['image_url'];
        }
    }
    
    return $cover_url;
}

// Obter itens recentes (últimos 4 de cada categoria)
$mangas_recentes = array_slice(array_reverse($_SESSION['mangas']), 0, 4);
$animes_recentes = array_slice(array_reverse($_SESSION['animes']), 0, 4);
$jogos_recentes = array_slice(array_reverse($_SESSION['jogos']), 0, 4);

// Calcular métricas gerais
$total_mangas = count($_SESSION['mangas']);
$total_animes = count($_SESSION['animes']);
$total_jogos = count($_SESSION['jogos']);
$total_itens = $total_mangas + $total_animes + $total_jogos;
?>

<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?php echo $_SESSION['theme']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MangApp</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles-mangas.css">
</head>
<body data-theme="<?php echo $_SESSION['theme']; ?>">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-left">
                    <a href="dashboard.php" class="logo">
                        <i class="fas fa-tachometer-alt"></i>
                        MangApp
                    </a>
                    <div class="search-bar">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Pesquisar..." 
                               onkeypress="if(event.key==='Enter') window.location.href='search-results.php?q='+encodeURIComponent(this.value)">
                    </div>
                </div>
                <div class="navbar-right">
                    <div class="nav-links">
                        <a href="dashboard.php" class="nav-link active">Dashboard</a>
                        <a href="index-mangas.php" class="nav-link">Mangás</a>
                        <a href="index-animes.php" class="nav-link">Animes</a>
                        <a href="index-games.php" class="nav-link">Jogos</a>
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Visão geral do seu progresso em seus mangás, animes e jogos</p>
            </div>

            <!-- Metrics -->
            <div class="metrics-grid">
                <div class="metric-card total">
                    <div class="metric-value"><?php echo $total_itens; ?></div>
                    <div class="metric-label">Total de Itens</div>
                </div>
                <div class="metric-card lendo">
                    <div class="metric-value"><?php echo $total_mangas; ?></div>
                    <div class="metric-label">Mangás</div>
                </div>
                <div class="metric-card pretendo">
                    <div class="metric-value"><?php echo $total_animes; ?></div>
                    <div class="metric-label">Animes</div>
                </div>
                <div class="metric-card abandonado">
                    <div class="metric-value"><?php echo $total_jogos; ?></div>
                    <div class="metric-label">Jogos</div>
                </div>
            </div>

            <!-- Dashboard Sections -->
            <div class="dashboard-sections">
                <!-- Mangás Recentes -->
                <div class="dashboard-section" style="border-radius: 0.75rem;">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fas fa-book-open"></i>
                            <h3>Mangás Adicionados Recentemente</h3>
                        </div>
                        <div class="section-count"><?php echo count($mangas_recentes); ?></div>
                    </div>
                    <div class="section-content">
                        <?php if (empty($mangas_recentes)): ?>
                            <div class="empty-state">

                                <h3 style="padding: 0.5rem 0 0 0; white-space: nowrap">Nenhum mangá adicionado</h3>

                                <p>Comece adicionando seu primeiro mangá!</p>

                                <button onclick="openAddMangaModal()" class="btn-mangas">
                                    <a href="index-mangas.php" class="btn">
                                        <i class="fas fa-plus"></i> Adicionar Mangá
                                    </a>
                                </button>

                            </div>
                        <?php else: ?>
                            <div class="recent-items">
                                <?php foreach ($mangas_recentes as $manga): ?>
                                    <div class="recent-item">
                                        <div class="item-cover">
                                            <?php 
                                            $cover_url = getCoverUrl($manga, 'manga');
                                            if (!empty($cover_url)): 
                                            ?>
                                                <img src="<?php echo htmlspecialchars($cover_url); ?>" 
                                                     alt="Capa de <?php echo htmlspecialchars($manga['nome']); ?>"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <?php endif; ?>
                                            <div class="cover-placeholder" <?php echo !empty($cover_url) ? 'style="display: none;"' : ''; ?>>
                                                <i class="fas fa-book"></i>
                                            </div>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-title"><?php echo htmlspecialchars($manga['nome']); ?></div>
                                            <div class="item-meta">
                                                <span class="item-status status-<?php echo $manga['status']; ?>">
                                                    <?php echo getStatusText($manga['status'], 'manga'); ?>
                                                </span>
                                                <span><i class="fas fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($manga['data_criacao'])); ?></span>
                                                <?php if (!empty($manga['generos'])): ?>
                                                    <span><i class="fas fa-tags"></i> <?php echo formatGenres($manga['generos']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="section-footer">
                                <a href="index-mangas.php" class="btn">
                                    <i class="fas fa-arrow-right"></i> Ver Todos os Mangás
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Animes Recentes -->
                <div class="dashboard-section" style="border-radius: 0.75rem;">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fas fa-tv"></i>
                            <h3>Animes Adicionados Recentemente</h3>
                        </div>
                        <div class="section-count"><?php echo count($animes_recentes); ?></div>
                    </div>
                    <div class="section-content">
                        <?php if (empty($animes_recentes)): ?>
                            <div class="empty-state">
                                
                                <h3>Nenhum anime adicionado</h3>
                                <p>Comece adicionando seu primeiro anime!</p>

                                <button onclick="openAddAnimeModal()" class="btn-animes">
                                <a href="index-animes.php" class="btn">
                                    <i class="fas fa-plus"></i> Adicionar Anime
                                </a>
                                </button>

                            </div>
                        <?php else: ?>
                            <div class="recent-items">
                                <?php foreach ($animes_recentes as $anime): ?>
                                    <div class="recent-item">
                                        <div class="item-cover">
                                            <?php 
                                            $cover_url = getCoverUrl($anime, 'anime');
                                            if (!empty($cover_url)): 
                                            ?>
                                                <img src="<?php echo htmlspecialchars($cover_url); ?>" 
                                                     alt="Capa de <?php echo htmlspecialchars($anime['nome']); ?>"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <?php endif; ?>
                                            <div class="cover-placeholder" <?php echo !empty($cover_url) ? 'style="display: none;"' : ''; ?>>
                                                <i class="fas fa-tv"></i>
                                            </div>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-title"><?php echo htmlspecialchars($anime['nome']); ?></div>
                                            <div class="item-meta">
                                                <span class="item-status status-<?php echo $anime['status']; ?>">
                                                    <?php echo getStatusText($anime['status'], 'anime'); ?>
                                                </span>
                                                <span><i class="fas fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($anime['data_criacao'])); ?></span>
                                                <?php if (!empty($anime['generos'])): ?>
                                                    <span><i class="fas fa-tags"></i> <?php echo formatGenres($anime['generos']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="section-footer">
                                <a href="index-animes.php" class="btn">
                                    <i class="fas fa-arrow-right"></i> Ver Todos os Animes
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Jogos Recentes -->
                <div class="dashboard-section" style="border-radius: 0.75rem;">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fas fa-gamepad"></i>
                            <h3>Jogos Adicionados Recentemente</h3>
                        </div>
                        <div class="section-count"><?php echo count($jogos_recentes); ?></div>
                    </div>
                    <div class="section-content">
                        <?php if (empty($jogos_recentes)): ?>
                            <div class="empty-state">
                                
                                <h3>Nenhum jogo adicionado</h3>
                                <p>Comece adicionando seu primeiro jogo!</p>

                                <button onclick="openAddJogoModal()" class="btn-jogos">
                                <a href="index-games.php" class="btn">
                                    <i class="fas fa-plus"></i> Adicionar Jogo
                                </a>
                                </button>

                            </div>
                        <?php else: ?>
                            <div class="recent-items">
                                <?php foreach ($jogos_recentes as $jogo): ?>
                                    <div class="recent-item">
                                        <div class="item-cover">
                                            <?php 
                                            $cover_url = getCoverUrl($jogo, 'jogo');
                                            if (!empty($cover_url)): 
                                            ?>
                                                <img src="<?php echo htmlspecialchars($cover_url); ?>" 
                                                     alt="Capa de <?php echo htmlspecialchars($jogo['nome']); ?>"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <?php endif; ?>
                                            <div class="cover-placeholder" <?php echo !empty($cover_url) ? 'style="display: none;"' : ''; ?>>
                                                <i class="fas fa-gamepad"></i>
                                            </div>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-title"><?php echo htmlspecialchars($jogo['nome']); ?></div>
                                            <div class="item-meta">
                                                <span class="item-status status-<?php echo $jogo['status']; ?>">
                                                    <?php echo getStatusText($jogo['status'], 'jogo'); ?>
                                                </span>
                                                <span><i class="fas fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($jogo['data_criacao'])); ?></span>
                                                <?php if (!empty($jogo['generos'])): ?>
                                                    <span><i class="fas fa-tags"></i> <?php echo formatGenres($jogo['generos']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="section-footer">
                                <a href="index-games.php" class="btn">
                                    <i class="fas fa-arrow-right"></i> Ver Todos os Jogos
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>

        .btn-mangas, .btn-animes, .btn-jogos {
            background-color: var(--success-color);
            padding: 0.5rem 1rem;
            margin: 1rem 0;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        
        /* Dashboard specific styles */
        .dashboard-sections {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .dashboard-section {
            background: var(--bg-primary);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(155deg, #6366f194 0% 0%, #00f3ff36 100%);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            font-size: 1.25rem;
        }

        .section-title h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .section-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .section-content {
            padding: 1.5rem;
        }

        .recent-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .recent-item {
            display: flex;
            flex-direction: column;
            background: var(--bg-secondary);
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .recent-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .item-cover {
            width: 100%;
            height: 200px;
            border-radius: 0;
            overflow: hidden;
            background: var(--bg-tertiary);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .item-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .recent-item:hover .item-cover img {
            transform: scale(1.05);
        }

        .cover-placeholder {
            color: var(--text-muted);
            font-size: 3rem;
        }

        .item-info {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .item-title {
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            /*min-height: 2.6rem;*/
        }

        .item-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .item-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .item-meta span:last-child {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-meta i {
            width: 14px;
            text-align: center;
        }

        .item-status {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-lendo { background: #dbeafe; color: #1e40af; }
        .status-pretendo { background: #fef3c7; color: #92400e; }
        .status-abandonado { background: #fee2e2; color: #991b1b; }
        .status-completado { background: #d1fae5; color: #065f46; }
        .status-jogando { background: #dbeafe; color: #1e40af; }

        .section-footer {
            margin-top: 1rem;
            text-align: center;
        }

        .section-footer .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .recent-items {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.75rem;
            }
            
            .item-cover {
                height: 180px;
            }
            
            .item-info {
                padding: 0.75rem;
            }
            
            .item-title {
                font-size: 0.9rem;
                min-height: 2.3rem;
            }
            
            .item-meta {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .recent-items {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .item-cover {
                height: 160px;
            }
        }
    </style>

    <script>
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
            fetch('dashboard.php', {
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
            if (icon) {
                icon.className = currentTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        });
    </script>
</body>
</html>
