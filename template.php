<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?php echo $_SESSION['theme']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangApp - Gerenciador de Mangás</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    
    
    <!-- Scripts da integração API -->
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
                    <div class="search-bar">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Pesquisar mangás..." 
                               onkeypress="if(event.key==='Enter') window.location.href='search-results.php?q='+encodeURIComponent(this.value)">
                    </div>
                </div>
                <div class="navbar-right">
                    <div class="nav-links">
                        <a href="index.php" class="nav-link active">
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Gerenciador de Mangás</h1>
                <p class="page-subtitle">Controle seu progresso de leitura de mangás</p>
            </div>

            <!-- Metrics -->
            <div class="metrics-grid">
                <div class="metric-card total">
                    <div class="metric-value"><?php echo $total_mangas; ?></div>
                    <div class="metric-label">Total</div>
                </div>
                <div class="metric-card lendo">
                    <div class="metric-value"><?php echo $mangas_lendo; ?></div>
                    <div class="metric-label">Lendo</div>
                </div>
                <div class="metric-card pretendo">
                    <div class="metric-value"><?php echo $mangas_pretendo; ?></div>
                    <div class="metric-label">Pretendo Ler</div>
                </div>
                <div class="metric-card abandonado">
                    <div class="metric-value"><?php echo $mangas_abandonados; ?></div>
                    <div class="metric-label">Abandonados</div>
                </div>
                <div class="metric-card finalizados">
                    <div class="metric-value"><?php echo $mangas_finalizados; ?></div>
                    <div class="metric-label">Concluídos</div>
                </div>
            </div>

            <!-- Manga List -->
            <div class="manga-list">
                <div class="manga-list-header">
                    <div class="manga-list-title">
                        <h3>Lista de Mangás</h3>
                        <span class="search-results" id="search-results" style="display: none;">
                            - Resultados para: "<span id="search-term"></span>"
                        </span>
                    </div>
                    <div class="manga-list-actions">
                        <div class="view-toggle">
                            <button class="view-btn" onclick="switchView('list')" data-view="list">
                                <i class="fas fa-list"></i> Lista
                            </button>
                            <button class="view-btn" onclick="switchView('cards')" data-view="cards">
                                <i class="fas fa-th-large"></i> Cards
                            </button>
                        </div>
                        <form class="search-form-inline" method="GET">
                            <input type="text" name="search" class="search-input-inline" 
                                   placeholder="Pesquisar mangás..." 
                                   value="<?php echo htmlspecialchars($termo_pesquisa); ?>">
                            <button type="submit" class="search-btn-inline">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <button class="add-manga-btn-inline" onclick="openAddMangaModal()">
                            <i class="fas fa-plus"></i> Adicionar Mangá
                        </button>
                    </div>
                </div>
                
                <?php if (empty($mangas_filtrados) && empty($resultados_api)): ?>
                    <div class="empty-state" style="padding: 3rem 2rem; text-align: center; color: var(--text-secondary);">
                        <?php if (!empty($termo_pesquisa)): ?>
                            <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <h3 style="margin-bottom: 0.5rem; color: var(--text-primary);">Nenhum resultado encontrado</h3>
                            <p>Nenhum mangá encontrado para "<strong><?php echo htmlspecialchars($termo_pesquisa); ?></strong>"</p>
                            <div style="margin-top: 2rem;">
                                <a href="search-results.php?q=<?php echo urlencode($termo_pesquisa); ?>" 
                                   class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; cursor: pointer; margin-right: 1rem; text-decoration: none;">
                                    <i class="fas fa-search"></i> Buscar na API
                                </a>
                                <a href="?" style="color: var(--primary-color); text-decoration: none;">
                                    <i class="fas fa-arrow-left"></i> Voltar para lista completa
                                </a>
                            </div>
                        <?php else: ?>
                            <i class="fas fa-book-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <h3 style="margin-bottom: 0.5rem; color: var(--text-primary);">Nenhum mangá adicionado</h3>
                            <p>Comece adicionando seu primeiro mangá!</p>
                            <div style="margin-top: 1rem;">
                                <button onclick="openAddMangaModal()" style="margin-right: 1rem; padding: 0.75rem 1.5rem; background-color: var(--success-color); color: white; border: none; border-radius: 0.5rem; cursor: pointer;">
                                    <i class="fas fa-plus"></i> Adicionar Mangá
                                </button>
                                <a href="search-results.php" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; cursor: pointer; text-decoration: none; display: inline-block;">
                                    <i class="fas fa-search"></i> Buscar Mangás
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    
                    <!-- Sugestão para busca expandida -->
                    <?php if (!empty($termo_pesquisa) && count($mangas_filtrados) < 5): ?>
                        <div class="search-suggestion" style="background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; margin-bottom: 2rem; text-align: center;">
                            <p style="margin-bottom: 1rem; color: var(--text-secondary);">
                                <i class="fas fa-lightbulb"></i> Poucos resultados encontrados localmente
                            </p>
                            <a href="search-results.php?q=<?php echo urlencode($termo_pesquisa); ?>" 
                               class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem;">
                                <i class="fas fa-search"></i> Buscar mais resultados na API
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- List Header - Sempre visível quando há mangás -->
                    <div class="list-header" id="list-header">
                        <div class="list-column" style="flex: 2;">Mangá</div>
                        <div class="list-column" style="width: auto; min-width: 100px;">Gênero</div>
                        <div class="list-column" style="width: 120px; flex-shrink: 0;">Status</div>
                        <div class="list-column" style="width: 100px; flex-shrink: 0;">Ações</div>
                    </div>
                    
                    <!-- List View -->
                    <div id="list-view" class="view-content">
                        <?php foreach ($mangas_filtrados as $manga): ?>
                        <div class="manga-item <?php echo !empty($manga['imported_from_api']) ? 'imported-from-api' : ''; ?>" data-manga-id="<?php echo $manga['id']; ?>">
                            <!-- Mangá Info -->
                            <div class="manga-info-compact">
                                <div class="manga-cover-small">
                                    <?php 
                                    $cover_url = '';
                                    $has_custom_cover = false;
                                    
                                    // Verificar se tem capa personalizada (primeiro verificar campo custom_cover)
                                    if (!empty($manga['custom_cover']) && file_exists("covers/originals/{$manga['custom_cover']}")) {
                                        $cover_url = "covers/originals/{$manga['custom_cover']}";
                                        $has_custom_cover = true;
                                    } elseif (file_exists("covers/originals/{$manga['id']}.jpg") || file_exists("covers/originals/{$manga['id']}.png") || file_exists("covers/originals/{$manga['id']}.webp")) {
                                        $extensions = ['jpg', 'png', 'webp'];
                                        foreach ($extensions as $ext) {
                                            if (file_exists("covers/originals/{$manga['id']}.{$ext}")) {
                                                $cover_url = "covers/originals/{$manga['id']}.{$ext}";
                                                $has_custom_cover = true;
                                                break;
                                            }
                                        }
                                    }
                                    
                                    // Se não tem capa personalizada, verificar dados da API
                                    if (!$has_custom_cover && !empty($manga['api_data'])) {
                                        if (isset($manga['api_data']['images']['jpg']['large_image_url'])) {
                                            $cover_url = $manga['api_data']['images']['jpg']['large_image_url'];
                                        } elseif (isset($manga['api_data']['images']['jpg']['image_url'])) {
                                            $cover_url = $manga['api_data']['images']['jpg']['image_url'];
                                        }
                                    }
                                    ?>
                                    
                                    <?php if (!empty($cover_url)): ?>
                                        <img src="<?php echo htmlspecialchars($cover_url); ?>" 
                                             alt="Capa de <?php echo htmlspecialchars($manga['nome']); ?>"
                                             class="cover-image-small"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <?php endif; ?>
                                    
                                    <div class="cover-placeholder-small" <?php echo !empty($cover_url) ? 'style="display: none;"' : ''; ?>>
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                                
                                <div class="manga-text-compact">
                                    <div class="manga-title-compact">
                                        <?php echo htmlspecialchars($manga['nome']); ?>
                                        <?php if (!empty($manga['imported_from_api'])): ?>
                                            <span class="api-indicator">API</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="manga-meta-compact">
                                        <?php if (isset($manga['em_lancamento']) && $manga['em_lancamento']): ?>
                                            <span><i class="fas fa-clock"></i> Em lançamento</span>
                                        <?php endif; ?>
                                        <?php if ($manga['finalizado']): ?>
                                            <span>• <i class="fas fa-check-circle"></i> Finalizado</span>
                                        <?php endif; ?>
                                        <span>• <i class="fas fa-calendar-alt"></i> Atualizado: <?php echo isset($manga['data_atualizacao']) ? date('d/m/Y', strtotime($manga['data_atualizacao'])) : date('d/m/Y', strtotime($manga['data_criacao'])); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gêneros -->
                            <div class="genre-compact">
                                <?php 
                                // Compatibilidade com dados antigos (genero singular)
                                if (isset($manga['genero']) && !isset($manga['generos'])) {
                                    $manga['generos'] = !empty($manga['genero']) ? [$manga['genero']] : [];
                                }
                                echo formatGenres($manga['generos'] ?? []);
                                ?>
                            </div>
                            
                            <!-- Status -->
                            <div class="status-compact status-<?php echo $manga['status']; ?>">
                                <?php echo getStatusText($manga['status']); ?>
                            </div>
                            
                            
                            <!-- Ações -->
                            <div class="actions-compact" style="width: 100px;">
                                <button class="btn btn-edit" onclick="openEditModal('<?php echo $manga['id']; ?>')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-delete" onclick="openDeleteModal('<?php echo $manga['id']; ?>', '<?php echo htmlspecialchars($manga['nome']); ?>')" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Cards View -->
                    <div id="cards-view" class="view-content">
                        <div class="cards-grid">
                            <?php foreach ($mangas_filtrados as $manga): ?>
                                <div class="manga-block <?php echo !empty($manga['imported_from_api']) ? 'imported-from-api' : ''; ?>" data-manga-id="<?php echo $manga['id']; ?>">
                                    <!-- Capa do Mangá -->
                                    <div class="manga-cover">
                                        <?php 
                                        $cover_url = '';
                                        $has_custom_cover = false;
                                        
                                        // Verificar se tem capa personalizada (primeiro verificar campo custom_cover)
                                        if (!empty($manga['custom_cover']) && file_exists("covers/originals/{$manga['custom_cover']}")) {
                                            $cover_url = "covers/originals/{$manga['custom_cover']}";
                                            $has_custom_cover = true;
                                        } elseif (file_exists("covers/originals/{$manga['id']}.jpg") || file_exists("covers/originals/{$manga['id']}.png") || file_exists("covers/originals/{$manga['id']}.webp")) {
                                            $extensions = ['jpg', 'png', 'webp'];
                                            foreach ($extensions as $ext) {
                                                if (file_exists("covers/originals/{$manga['id']}.{$ext}")) {
                                                    $cover_url = "covers/originals/{$manga['id']}.{$ext}";
                                                    $has_custom_cover = true;
                                                    break;
                                                }
                                            }
                                        }
                                        
                                        // Se não tem capa personalizada, verificar dados da API
                                        if (!$has_custom_cover && !empty($manga['api_data'])) {
                                            if (isset($manga['api_data']['images']['jpg']['large_image_url'])) {
                                                $cover_url = $manga['api_data']['images']['jpg']['large_image_url'];
                                            } elseif (isset($manga['api_data']['images']['jpg']['image_url'])) {
                                                $cover_url = $manga['api_data']['images']['jpg']['image_url'];
                                            }
                                        }
                                        ?>
                                        
                                        <?php if (!empty($cover_url)): ?>
                                            <img src="<?php echo htmlspecialchars($cover_url); ?>" 
                                                 alt="Capa de <?php echo htmlspecialchars($manga['nome']); ?>"
                                                 class="cover-image"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <?php endif; ?>
                                        
                                        <!-- Placeholder quando não há capa -->
                                        <div class="cover-placeholder" <?php echo !empty($cover_url) ? 'style="display: none;"' : ''; ?>>
                                            <i class="fas fa-book"></i>
                                            <span>Sem Capa</span>
                                        </div>
                                        
                                        <!-- Indicador API -->
                                        <?php if (!empty($manga['imported_from_api'])): ?>
                                            <div class="api-badge">
                                                <i class="fas fa-cloud-download-alt"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="block-content">
                                        <div class="block-header">
                                            <h4 class="block-title" title="<?php echo htmlspecialchars($manga['nome']); ?>">
                                                <?php echo htmlspecialchars($manga['nome']); ?>
                                            </h4>
                                        </div>
                                        
                                        <div class="block-details">
                                            <span class="detail-item">
                                                <i class="fas fa-bookmark"></i> 
                                                <?php echo getStatusText($manga['status']); ?>
                                            </span>
                                            <?php if (isset($manga['em_lancamento']) && $manga['em_lancamento']): ?>
                                                <span class="detail-item">
                                                    <i class="fas fa-clock"></i> Em lançamento
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if ($manga['finalizado']): ?>
                                                <span class="detail-item">
                                                    <i class="fas fa-check-circle"></i> Finalizado
                                                </span>
                                            <?php endif; ?>
                                            
                                            <span class="detail-item">
                                                <i class="fas fa-calendar-alt"></i> 
                                                Atualizado: <?php echo isset($manga['data_atualizacao']) ? date('d/m/Y', strtotime($manga['data_atualizacao'])) : date('d/m/Y', strtotime($manga['data_criacao'])); ?>
                                            </span>
                                        </div>
                                        
                                        <!-- Gêneros -->
                                        <div class="block-genres">
                                            <?php 
                                            // Compatibilidade com dados antigos (genero singular)
                                            if (isset($manga['genero']) && !isset($manga['generos'])) {
                                                $manga['generos'] = !empty($manga['genero']) ? [$manga['genero']] : [];
                                            }
                                            echo formatGenres($manga['generos'] ?? []);
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <div class="block-actions">
                                        <button class="btn btn-edit" onclick="openEditModal('<?php echo $manga['id']; ?>')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-delete" onclick="openDeleteModal('<?php echo $manga['id']; ?>', '<?php echo htmlspecialchars($manga['nome']); ?>')" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirmar Exclusão</h3>
                <button class="close-btn" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o mangá "<span id="deleteMangaName"></span>"?</p>
                <p class="warning-text">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
                <form method="POST" id="deleteForm" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteMangaId">
                    <button type="submit" class="btn btn-delete">
                        <i class="fas fa-trash"></i> Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Manga Modal -->
    <div id="addMangaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Adicionar Novo Mangá</h3>
                <button class="close-btn" onclick="closeAddMangaModal()">&times;</button>
            </div>
            <form method="POST" id="addMangaForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="modal-form-layout">
                    <!-- Lado Esquerdo - Capa -->
                    <div class="modal-left">
                        <div class="form-group">
                            <label class="form-label">Capa do Mangá</label>
                        <div class="cover-upload-container">
                            <input type="file" name="cover_image" id="addCoverImage" class="form-file" accept="image/*" onchange="previewCover(this, 'addCoverPreview')">
                                <div class="cover-preview" id="addCoverPreview">
                                    <div class="cover-placeholder-upload">
                                        <span>capa</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lado Direito - Campos -->
                    <div class="modal-right">
                        <div class="form-group">
                            <label class="form-label">Nome do Mangá *</label>
                            <input type="text" name="nome" id="addNome" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Total de Capítulos</label>
                            <input type="number" name="capitulos_total" id="addCapitulosTotal" class="form-input" min="0" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Capítulo Atual</label>
                            <input type="number" name="capitulo_atual" id="addCapituloAtual" class="form-input" min="0" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status" id="addStatus" class="form-select" required onchange="toggleRequiredFields()">
                                <option value="">Selecione...</option>
                                <option value="lendo">Lendo</option>
                                <option value="pretendo">Pretendo Ler</option>
                                <option value="abandonado">Abandonado</option>
                                <option value="completado">Completado</option>
                            </select>
                    </div>
                        
                        <div class="form-group">
                            <label class="form-label">Gêneros</label>
                            <div class="genre-checkboxes">
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="ação">
                                    <span class="genre-label">Ação</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="aventura">
                                    <span class="genre-label">Aventura</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="romance">
                                    <span class="genre-label">Romance</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="isekai">
                                    <span class="genre-label">Isekai</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="terror">
                                    <span class="genre-label">Terror</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="comédia">
                                    <span class="genre-label">Comédia</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="drama">
                                    <span class="genre-label">Drama</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="fantasia">
                                    <span class="genre-label">Fantasia</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="ficção científica">
                                    <span class="genre-label">Ficção Científica</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="slice of life">
                                    <span class="genre-label">Slice of Life</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="esportes">
                                    <span class="genre-label">Esportes</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="supernatural">
                                    <span class="genre-label">Supernatural</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="mystery">
                                    <span class="genre-label">Mistério</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="psychological">
                                    <span class="genre-label">Psicológico</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="historical">
                                    <span class="genre-label">Histórico</span>
                                </label>
                            </div>
                    </div>
                    
                        <div class="checkbox-row">
                            <div class="checkbox-group">
                                <input type="checkbox" name="em_lancamento" id="addEmLancamento" class="form-checkbox" onchange="handleEmLancamentoChange(this)">
                                <span>Mangá em lançamento</span>
                        </div>
                            <div class="checkbox-group">
                                <input type="checkbox" name="finalizado" id="addFinalizado" class="form-checkbox" onchange="handleFinalizadoChange(this)">
                                <span>Mangá finalizado</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="justify-content: center;">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-plus"></i> Adicionar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Editar Mangá</h3>
                <button class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form method="POST" id="editForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editId">
                <div class="modal-form-layout">
                    <!-- Lado Esquerdo - Capa -->
                    <div class="modal-left">
                        <div class="form-group">
                            <label class="form-label">Capa do Mangá</label>
                        <div class="cover-upload-container">
                            <input type="file" name="cover_image" id="editCoverImage" class="form-file" accept="image/*" onchange="previewCover(this, 'editCoverPreview')">
                                <div class="cover-preview" id="editCoverPreview">
                                    <div class="cover-placeholder-upload">
                                        <span>capa</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lado Direito - Campos -->
                    <div class="modal-right">
                    <div class="form-group">
                        <label class="form-label">Nome do Mangá *</label>
                        <input type="text" name="nome" id="editNome" class="form-input" required>
                    </div>
                        
                        <div class="form-group">
                            <label class="form-label">Total de Capítulos</label>
                            <input type="number" name="capitulos_total" id="editCapitulosTotal" class="form-input" min="0" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Capítulo Atual</label>
                            <input type="number" name="capitulo_atual" id="editCapituloAtual" class="form-input" min="0" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status" id="editStatus" class="form-select" required onchange="toggleEditRequiredFields()">
                                <option value="lendo">Lendo</option>
                                <option value="pretendo">Pretendo Ler</option>
                                <option value="abandonado">Abandonado</option>
                                <option value="completado">Completado</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Gêneros</label>
                            <div class="genre-checkboxes">
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="ação">
                                    <span class="genre-label">Ação</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="aventura">
                                    <span class="genre-label">Aventura</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="romance">
                                    <span class="genre-label">Romance</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="isekai">
                                    <span class="genre-label">Isekai</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="terror">
                                    <span class="genre-label">Terror</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="comédia">
                                    <span class="genre-label">Comédia</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="drama">
                                    <span class="genre-label">Drama</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="fantasia">
                                    <span class="genre-label">Fantasia</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="ficção científica">
                                    <span class="genre-label">Ficção Científica</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="slice of life">
                                    <span class="genre-label">Slice of Life</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="esportes">
                                    <span class="genre-label">Esportes</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="supernatural">
                                    <span class="genre-label">Supernatural</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="mystery">
                                    <span class="genre-label">Mistério</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="psychological">
                                    <span class="genre-label">Psicológico</span>
                                </label>
                                <label class="genre-checkbox">
                                    <input type="checkbox" name="generos[]" value="historical">
                                    <span class="genre-label">Histórico</span>
                                </label>
                            </div>
                        </div>
                        <div class="checkbox-row">
                            <div class="checkbox-group">
                                <input type="checkbox" name="em_lancamento" id="editEmLancamento" class="form-checkbox" onchange="handleEditEmLancamentoChange(this)">
                                <span>Mangá em lançamento</span>
                        </div>
                            <div class="checkbox-group">
                                <input type="checkbox" name="finalizado" id="editFinalizado" class="form-checkbox" onchange="handleEditFinalizadoChange(this)">
                                <span>Mangá finalizado</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Make manga data available globally for JavaScript
        window.mangaData = <?php echo json_encode($_SESSION['mangas']); ?>;
        
        // Sistema de busca integrada com API
        let buscaTimeout;
        

        

        
        // Melhorar a busca na barra superior
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            const searchInputInline = document.querySelector('.search-input-inline');
            
            // Busca em tempo real na barra superior
            if (searchInput) {
                // Evitar redirecionamento automático se já há um valor de pesquisa
                const currentSearch = new URLSearchParams(window.location.search).get('search');
                if (currentSearch) {
                    console.log('Valor de pesquisa atual detectado, desabilitando redirecionamento automático');
                    searchInput.value = currentSearch;
                }
                
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(buscaTimeout);
                    const query = e.target.value.trim();
                    
                    // Só redirecionar se não há valor de pesquisa atual e query tem pelo menos 2 caracteres
                    if (query.length >= 2 && !currentSearch) {
                        buscaTimeout = setTimeout(() => {
                            // Redirecionar para busca
                            window.location.href = `?search=${encodeURIComponent(query)}`;
                        }, 1000); // Aguardar 1 segundo após parar de digitar
                    }
                });
                
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        clearTimeout(buscaTimeout);
                        const query = e.target.value.trim();
                        if (query) {
                            window.location.href = `?search=${encodeURIComponent(query)}`;
                        }
                    }
                });
            }
            
            // Adicionar placeholder dinâmico
            if (searchInput) {
                const placeholders = [
                    'Pesquisar mangás...',
                    'Ex: One Piece, Naruto...',
                    'Buscar na sua lista...',
                    'Encontrar mangás...'
                ];
                
                let currentPlaceholder = 0;
                setInterval(() => {
                    if (!searchInput.value) {
                        searchInput.placeholder = placeholders[currentPlaceholder];
                        currentPlaceholder = (currentPlaceholder + 1) % placeholders.length;
                    }
                }, 3000);
            }
        });
        
        // Adicionar estilos dinâmicos para melhor UX
        const additionalStyles = `
            .api-manga-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                border-color: #667eea;
            }
            
            .search-input:focus {
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                border-color: #667eea;
            }
            
            .api-results-section {
                animation: slideDown 0.3s ease;
            }
            
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .btn:hover {
                transform: translateY(-1px);
            }
            
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            }
            
            .loading-spinner {
                background: white;
                padding: 2rem;
                border-radius: 0.5rem;
                text-align: center;
            }
        `;
        
        const styleSheet = document.createElement('style');
        styleSheet.textContent = additionalStyles;
        document.head.appendChild(styleSheet);
        
        // ===== COVER UPLOAD FUNCTIONS =====
        function previewCover(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview da capa">`;
                };
                reader.readAsDataURL(file);
            } else {
                // Restaurar placeholder baseado no tipo de preview
                const isEdit = previewId.includes('edit');
                
                    preview.innerHTML = `
                        <div class="cover-placeholder-upload">
                            <i class="fas fa-image"></i>
                            <span>${isEdit ? 'Clique para alterar capa' : 'Clique para adicionar capa'}</span>
                        </div>
                    `;
            }
        }
        
        // Adicionar event listeners para os previews
        document.addEventListener('DOMContentLoaded', function() {
            // Preview para adicionar
            const addPreview = document.getElementById('addCoverPreview');
            if (addPreview) {
                addPreview.addEventListener('click', function() {
                    document.getElementById('addCoverImage').click();
                });
            }
            
            // Preview para editar
            const editPreview = document.getElementById('editCoverPreview');
            if (editPreview) {
                editPreview.addEventListener('click', function() {
                    document.getElementById('editCoverImage').click();
                });
            }
        });
    </script>
    <!-- SortableJS Library -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="script.js"></script>
    
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
            fetch('index.php', {
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
