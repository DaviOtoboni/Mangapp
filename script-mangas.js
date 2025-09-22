
// Toggle theme
function toggleTheme() {
    const body = document.documentElement;
    const currentTheme = body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    body.setAttribute('data-theme', newTheme);
    
    // Send theme change to server
    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = '<input type="hidden" name="action" value="toggle_theme">';
    document.body.appendChild(form);
    form.submit();
}

// Toggle do menu do usuário
function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Fechar menu do usuário ao clicar fora
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    const dropdown = document.getElementById('userDropdown');
    
    if (userMenu && dropdown && !userMenu.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Switch view mode
function switchView(viewMode) {
    console.log('=== DEBUG: switchView chamada ===');
    console.log('Modo solicitado:', viewMode);
    
    try {
        // Hide all view content
        const allViews = document.querySelectorAll('.view-content');
        console.log('Total de views encontradas:', allViews.length);
        
        allViews.forEach(content => {
            console.log('Ocultando view:', content.id, 'Classes atuais:', content.className);
            content.classList.remove('active');
        });
        
        // Show selected view
        const targetView = document.getElementById(viewMode + '-view');
        console.log('View alvo encontrada:', targetView ? targetView.id : 'NÃO ENCONTRADA');
        
        // Control list header visibility
        const listHeader = document.getElementById('list-header');
        if (listHeader) {
            if (viewMode === 'list') {
                listHeader.style.display = 'flex';
            } else {
                listHeader.style.display = 'none';
            }
        }
        
        if (targetView) {
            targetView.classList.add('active');
            console.log('View ativada:', targetView.id, 'Classes finais:', targetView.className);
            
            // Force display block to ensure visibility
            targetView.style.display = 'block';
            
            // Verificar se há conteúdo dentro da view
            const mangaBlocks = targetView.querySelectorAll('.manga-block');
            const cardsGrid = targetView.querySelector('.cards-grid');
            
            console.log('Mangás encontrados na view:', mangaBlocks.length);
            console.log('Cards grid encontrado:', cardsGrid ? 'SIM' : 'NÃO');
            
            if (cardsGrid) {
                console.log('Cards grid classes:', cardsGrid.className);
                console.log('Cards grid style:', cardsGrid.style.cssText);
                console.log('Cards grid children:', cardsGrid.children.length);
                
                // Cards grid já está configurado no CSS
                // Removido para evitar conflitos
            }
            
            if (mangaBlocks.length === 0 && viewMode === 'cards') {
                console.warn('⚠️ NENHUM MANGÁ ENCONTRADO NA VIEW CARDS!');
                console.log('Estrutura da view:', targetView.innerHTML.substring(0, 1000));
                
                // Check if there are any hidden elements
                const hiddenElements = targetView.querySelectorAll('[style*="display: none"], [style*="visibility: hidden"]');
                console.log('Elementos ocultos encontrados:', hiddenElements.length);
                hiddenElements.forEach(el => {
                    console.log('Elemento oculto:', el.tagName, el.className, el.style.cssText);
                });
            } else if (mangaBlocks.length > 0) {
                console.log('✅ Mangás encontrados na view cards, forçando visibilidade...');
                mangaBlocks.forEach((block, index) => {
                    // Não aplicar display: block nos cards, deixar o CSS gerenciar
                    block.style.visibility = 'visible';
                    console.log(`Bloco ${index + 1}:`, block.style.cssText);
                });
            }
        } else {
            console.error('❌ VIEW NÃO ENCONTRADA:', viewMode + '-view');
            return;
        }
        
        // Update active button
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Find and activate the correct button
        const activeBtn = document.querySelector(`[data-view="${viewMode}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
            console.log('Botão ativado:', activeBtn.textContent.trim());
        } else {
            console.warn('⚠️ Botão não encontrado para view:', viewMode);
        }
        
        // Persist view preference using ViewStateManager
        viewStateManager.setCurrentView(viewMode);
        
        
        // Forçar layout correto dos cards se for view de cards
        if (viewMode === 'cards') {
            setTimeout(() => {
                forceCardsLayout();
            }, 100);
        }
        
    } catch (error) {
        console.error('❌ Erro ao alternar visualização:', error);
    }
    
    console.log('=== FIM DEBUG ===');
}

// Delete confirmation modal functions
function openDeleteModal(mangaId, mangaName) {
    errorHandler.safeExecute(() => {
        const modal = errorHandler.validateElement('deleteModal', 'Open Delete Modal');
        const idField = errorHandler.validateElement('deleteMangaId', 'Delete Modal ID Field');
        const nameField = errorHandler.validateElement('deleteMangaName', 'Delete Modal Name Field');
        
        if (modal && idField && nameField) {
            idField.value = mangaId;
            nameField.textContent = mangaName;
            modal.classList.add('show');
        }
    }, 'openDeleteModal');
}

function closeDeleteModal() {
    errorHandler.safeExecute(() => {
        const modal = errorHandler.validateElement('deleteModal', 'Close Delete Modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }, 'closeDeleteModal');
}

// Add Manga Modal functions
function openAddMangaModal() {
    errorHandler.safeExecute(() => {
        const modal = errorHandler.validateElement('addMangaModal', 'Open Add Modal');
        if (modal) {
            modal.classList.add('show');
            
            // Reset form fields
            const form = document.getElementById('addMangaForm');
            if (form) {
                form.reset();
            }
            
            // Reset checkboxes
            const emLancamento = document.getElementById('addEmLancamento');
            const finalizado = document.getElementById('addFinalizado');
            if (emLancamento) emLancamento.checked = false;
            if (finalizado) finalizado.checked = false;
            
            // Set default value for total chapters
            const totalCapitulos = document.getElementById('addCapitulosTotal');
            if (totalCapitulos) {
                totalCapitulos.value = '0';
            }
            
            // Reset required fields when opening modal
            toggleRequiredFields();
        }
    }, 'openAddMangaModal');
}

function closeAddMangaModal() {
    errorHandler.safeExecute(() => {
        const modal = errorHandler.validateElement('addMangaModal', 'Close Add Modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }, 'closeAddMangaModal');
}

// Modal Validation System
class ModalValidator {
    constructor() {
        this.disabledStyle = 'background-color: var(--bg-tertiary); color: var(--text-secondary); cursor: not-allowed;';
    }
    
    validateElement(elementId, callback) {
        const element = document.getElementById(elementId);
        if (!element) {
            console.warn(`Elemento não encontrado: ${elementId}`);
            return null;
        }
        if (callback) {
            const result = callback(element);
            return result;
        }
        return element;
    }
    
    setFieldState(element, state) {
        if (!element) return;
        
        // Set required attribute
        if (state.required) {
            element.setAttribute('required', 'required');
        } else {
            element.removeAttribute('required');
        }
        
        // Set disabled state
        element.disabled = state.disabled || false;
        
        // Set placeholder
        if (state.placeholder !== undefined) {
            element.placeholder = state.placeholder;
        }
        
        // Set value if provided
        if (state.value !== undefined) {
            element.value = state.value;
        }
        
        // Apply visual styling for disabled fields
        if (state.disabled) {
            element.style.cssText = this.disabledStyle;
        } else {
            element.style.cssText = '';
        }
    }
    
    validateAddModal() {
        const emLancamentoCheckbox = document.getElementById('addEmLancamento');
        const totalCapitulosField = document.getElementById('addCapitulosTotal');
        const capituloAtualField = document.getElementById('addCapituloAtual');
        const statusField = document.getElementById('addStatus');
        
        if (!emLancamentoCheckbox || !totalCapitulosField || !capituloAtualField || !statusField) {
            return;
        }
        
        const emLancamento = emLancamentoCheckbox.checked;
        const status = statusField.value;
        
        // Lógica para o campo "Total de Capítulos" (quando em lançamento)
        if (emLancamento) {
            // Bloquear completamente o campo
            totalCapitulosField.disabled = true;
            totalCapitulosField.readOnly = true;
            totalCapitulosField.value = '0';
            totalCapitulosField.placeholder = '';
            totalCapitulosField.removeAttribute('required');
            totalCapitulosField.setAttribute('readonly', 'readonly');
            totalCapitulosField.setAttribute('tabindex', '-1');
            totalCapitulosField.setAttribute('aria-disabled', 'true');
            totalCapitulosField.style.cssText = this.disabledStyle;
            
            // Adicionar classe CSS para bloqueio visual
            totalCapitulosField.classList.add('field-blocked');
        } else {
            // Desbloquear o campo
            totalCapitulosField.disabled = false;
            totalCapitulosField.readOnly = false;
            totalCapitulosField.placeholder = 'Ex: 100';
            totalCapitulosField.removeAttribute('required');
            totalCapitulosField.removeAttribute('readonly');
            totalCapitulosField.removeAttribute('tabindex');
            totalCapitulosField.removeAttribute('aria-disabled');
            totalCapitulosField.style.cssText = '';
            totalCapitulosField.classList.remove('field-blocked');
            
            if (!totalCapitulosField.value) {
                totalCapitulosField.value = '0';
            }
        }
        
        // Lógica para o campo "Capítulo Atual" (quando status for completado)
        if (status === 'completado') {
            // Bloquear completamente o campo
            capituloAtualField.disabled = true;
            capituloAtualField.readOnly = true;
            capituloAtualField.value = totalCapitulosField.value || '0';
            capituloAtualField.placeholder = '';
            capituloAtualField.removeAttribute('required');
            capituloAtualField.setAttribute('readonly', 'readonly');
            capituloAtualField.setAttribute('tabindex', '-1');
            capituloAtualField.setAttribute('aria-disabled', 'true');
            capituloAtualField.style.cssText = this.disabledStyle;
            
            // Adicionar classe CSS para bloqueio visual
            capituloAtualField.classList.add('field-blocked');
        } else {
            // Desbloquear o campo
            capituloAtualField.disabled = false;
            capituloAtualField.readOnly = false;
            capituloAtualField.placeholder = 'Ex: 10';
            capituloAtualField.removeAttribute('required');
            capituloAtualField.removeAttribute('readonly');
            capituloAtualField.removeAttribute('tabindex');
            capituloAtualField.removeAttribute('aria-disabled');
            capituloAtualField.style.cssText = '';
            capituloAtualField.classList.remove('field-blocked');
        }
        
        console.log('validateAddModal executada - Em lançamento:', emLancamento, 
                   'Status:', status,
                   'Total capítulos desabilitado:', totalCapitulosField.disabled,
                   'Capítulo atual desabilitado:', capituloAtualField.disabled);
    }
    
    validateEditModal() {
        const emLancamentoCheckbox = document.getElementById('editEmLancamento');
        const totalCapitulosField = document.getElementById('editCapitulosTotal');
        const capituloAtualField = document.getElementById('editCapituloAtual');
        const statusField = document.getElementById('editStatus');
        
        if (!emLancamentoCheckbox || !totalCapitulosField || !capituloAtualField || !statusField) {
            return;
        }
        
        const emLancamento = emLancamentoCheckbox.checked;
        const status = statusField.value;
        
        // Lógica para o campo "Total de Capítulos" (quando em lançamento)
        if (emLancamento) {
            // Bloquear completamente o campo
            totalCapitulosField.disabled = true;
            totalCapitulosField.readOnly = true;
            totalCapitulosField.value = '0';
            totalCapitulosField.placeholder = '';
            totalCapitulosField.removeAttribute('required');
            totalCapitulosField.setAttribute('readonly', 'readonly');
            totalCapitulosField.setAttribute('tabindex', '-1');
            totalCapitulosField.setAttribute('aria-disabled', 'true');
            totalCapitulosField.style.cssText = this.disabledStyle;
            
            // Adicionar classe CSS para bloqueio visual
            totalCapitulosField.classList.add('field-blocked');
        } else {
            // Desbloquear o campo
            totalCapitulosField.disabled = false;
            totalCapitulosField.readOnly = false;
            totalCapitulosField.placeholder = 'Ex: 100';
            totalCapitulosField.removeAttribute('required');
            totalCapitulosField.removeAttribute('readonly');
            totalCapitulosField.removeAttribute('tabindex');
            totalCapitulosField.removeAttribute('aria-disabled');
            totalCapitulosField.style.cssText = '';
            totalCapitulosField.classList.remove('field-blocked');
            
            if (!totalCapitulosField.value) {
                totalCapitulosField.value = '0';
            }
        }
        
        // Lógica para o campo "Capítulo Atual" (quando status for completado)
        if (status === 'completado') {
            // Bloquear completamente o campo
            capituloAtualField.disabled = true;
            capituloAtualField.readOnly = true;
            capituloAtualField.value = totalCapitulosField.value || '0';
            capituloAtualField.placeholder = '';
            capituloAtualField.removeAttribute('required');
            capituloAtualField.setAttribute('readonly', 'readonly');
            capituloAtualField.setAttribute('tabindex', '-1');
            capituloAtualField.setAttribute('aria-disabled', 'true');
            capituloAtualField.style.cssText = this.disabledStyle;
            
            // Adicionar classe CSS para bloqueio visual
            capituloAtualField.classList.add('field-blocked');
        } else {
            // Desbloquear o campo
            capituloAtualField.disabled = false;
            capituloAtualField.readOnly = false;
            capituloAtualField.placeholder = 'Ex: 10';
            capituloAtualField.removeAttribute('required');
            capituloAtualField.removeAttribute('readonly');
            capituloAtualField.removeAttribute('tabindex');
            capituloAtualField.removeAttribute('aria-disabled');
            capituloAtualField.style.cssText = '';
            capituloAtualField.classList.remove('field-blocked');
        }
        
        console.log('validateEditModal executada - Em lançamento:', emLancamento, 
                   'Status:', status,
                   'Total capítulos desabilitado:', totalCapitulosField.disabled,
                   'Capítulo atual desabilitado:', capituloAtualField.disabled);
    }
}

// Initialize Modal Validator
const modalValidator = new ModalValidator();

// Function to toggle required fields based on status and em_lancamento
function toggleRequiredFields() {
    try {
        modalValidator.validateAddModal();
        
        // Force update after a small delay to ensure DOM is ready
        setTimeout(() => {
            modalValidator.validateAddModal();
        }, 10);
    } catch (error) {
        console.error('Erro na validação do modal de adicionar:', error);
    }
}

// Function to toggle required fields in edit modal
function toggleEditRequiredFields() {
    try {
        modalValidator.validateEditModal();
    } catch (error) {
        console.error('Erro na validação do modal de edição:', error);
    }
}

// Edit modal functions
function openEditModal(mangaId) {
    try {
        const modal = document.getElementById('editModal');
        if (!modal) {
            console.error('Modal de edição não encontrado');
            return;
        }
        
        const mangas = window.mangaData || []; // Get manga data from global variable
        const manga = mangas.find(m => m.id === mangaId);
        
        if (!manga) {
            console.error('Mangá não encontrado:', mangaId);
            return;
        }
        
        // Populate form fields with validation
        modalValidator.validateElement('editId', el => el.value = manga.id);
        modalValidator.validateElement('editNome', el => el.value = manga.nome);
        modalValidator.validateElement('editStatus', el => el.value = manga.status);
        modalValidator.validateElement('editCapitulosTotal', el => el.value = manga.capitulos_total);
        modalValidator.validateElement('editCapituloAtual', el => el.value = manga.capitulo_atual);
        // Preencher gêneros múltiplos (checkboxes)
        const editGenerosCheckboxes = document.querySelectorAll('#editModal input[name="generos[]"]');
        if (editGenerosCheckboxes.length > 0) {
            // Limpar seleções anteriores
            editGenerosCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Selecionar gêneros do mangá
            const generos = manga.generos || [];
            if (Array.isArray(generos)) {
                generos.forEach(genero => {
                    const checkbox = Array.from(editGenerosCheckboxes).find(cb => cb.value === genero);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
        }
        modalValidator.validateElement('editFinalizado', el => el.checked = manga.finalizado);
        modalValidator.validateElement('editEmLancamento', el => el.checked = manga.em_lancamento || false);
        // Configurar preview da capa
        const editCoverPreview = document.getElementById('editCoverPreview');
        if (editCoverPreview) {
            let coverUrl = '';
            let hasCover = false;
            
            // Função para testar extensões sequencialmente
            const testImageExtensions = async (extensions, mangaId) => {
                for (const ext of extensions) {
                    const testUrl = `covers/originals/${mangaId}.${ext}`;
                    try {
                        const response = await fetch(testUrl, { method: 'HEAD' });
                        if (response.ok) {
                            editCoverPreview.innerHTML = `<img src="${testUrl}" alt="Capa atual">`;
                            return true;
                        }
                    } catch (error) {
                        // Continuar para próxima extensão
                    }
                }
                return false;
            };
            
            // Verificar se tem capa personalizada
            const extensions = ['jpg', 'png', 'webp'];
            testImageExtensions(extensions, manga.id).then(found => {
                if (!found) {
                    // Se não tem capa personalizada, verificar dados da API
                    if (manga.api_data && manga.api_data.images) {
                        if (manga.api_data.images.jpg && manga.api_data.images.jpg.large_image_url) {
                            coverUrl = manga.api_data.images.jpg.large_image_url;
                        } else if (manga.api_data.images.jpg && manga.api_data.images.jpg.image_url) {
                            coverUrl = manga.api_data.images.jpg.image_url;
                        }
                        
                        if (coverUrl) {
                            editCoverPreview.innerHTML = `<img src="${coverUrl}" alt="Capa da API">`;
                            hasCover = true;
                        }
                    }
                    
                    // Se não tem nenhuma capa, mostrar placeholder
                    if (!hasCover) {
                        editCoverPreview.innerHTML = `
                            <div class="cover-placeholder-upload">
                                <i class="fas fa-image"></i>
                                <span>Clique para alterar capa</span>
                            </div>
                        `;
                    }
                }
            });
        }
        
        modal.classList.add('show');
        
        // Apply field validation rules after populating
        setTimeout(() => {
            toggleEditRequiredFields();
        }, 50);
        
    } catch (error) {
        console.error('Erro ao abrir modal de edição:', error);
    }
}

function closeEditModal() {
    errorHandler.safeExecute(() => {
        const modal = errorHandler.validateElement('editModal', 'Close Edit Modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }, 'closeEditModal');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const addMangaModal = document.getElementById('addMangaModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    if (event.target === addMangaModal) {
        closeAddMangaModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}

// View State Manager Class
class ViewStateManager {
    constructor() {
        this.storageKey = 'mangaViewMode';
        this.defaultView = 'list';
        this.validViews = ['list', 'cards'];
    }
    
    getCurrentView() {
        try {
            if (typeof Storage !== 'undefined') {
                const saved = localStorage.getItem(this.storageKey);
                console.log('🔍 Valor salvo no localStorage:', saved);
                if (saved && this.validViews.includes(saved)) {
                    console.log('✅ Usando visualização salva:', saved);
                    return saved;
                } else {
                    console.log('⚠️ Valor inválido ou não encontrado, usando padrão:', this.defaultView);
                }
            } else {
                console.warn('⚠️ localStorage não disponível');
            }
        } catch (error) {
            console.warn('❌ Erro ao acessar localStorage:', error);
        }
        return this.defaultView;
    }
    
    setCurrentView(view) {
        if (!this.validViews.includes(view)) {
            console.warn('❌ Modo de visualização inválido:', view);
            return false;
        }
        
        try {
            if (typeof Storage !== 'undefined') {
                localStorage.setItem(this.storageKey, view);
                console.log('💾 Preferência de visualização salva:', view);
                
                // Verify it was saved correctly
                const verification = localStorage.getItem(this.storageKey);
                if (verification === view) {
                    console.log('✅ Verificação: valor salvo corretamente');
                    return true;
                } else {
                    console.error('❌ Verificação falhou: esperado', view, 'mas encontrado', verification);
                    return false;
                }
            } else {
                console.warn('⚠️ localStorage não disponível para salvar');
            }
        } catch (error) {
            console.warn('❌ Erro ao salvar preferência:', error);
        }
        return false;
    }
    
    initializeView() {
        const currentView = this.getCurrentView();
        console.log('Inicializando com visualização:', currentView);
        
        // Force remove any existing active classes first
        document.querySelectorAll('.view-content').forEach(view => {
            view.classList.remove('active');
        });
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Apply the saved view
        switchView(currentView);
        return currentView;
    }
    
    clearPreference() {
        try {
            if (typeof Storage !== 'undefined') {
                localStorage.removeItem(this.storageKey);
                console.log('Preferência de visualização removida');
                return true;
            }
        } catch (error) {
            console.warn('Erro ao limpar preferência:', error);
        }
        return false;
    }
}

// Initialize View State Manager
const viewStateManager = new ViewStateManager();

// Search Results Manager
class SearchResultsManager {
    constructor() {
        this.searchResultsElement = document.getElementById('search-results');
        this.searchTermElement = document.getElementById('search-term');
    }
    
    updateSearchDisplay(searchTerm) {
        if (!this.searchResultsElement || !this.searchTermElement) {
            console.warn('Elementos de resultado de pesquisa não encontrados');
            return;
        }
        
        if (searchTerm && searchTerm.trim()) {
            this.searchTermElement.textContent = searchTerm.trim();
            this.searchResultsElement.style.display = 'inline';
        } else {
            this.searchResultsElement.style.display = 'none';
        }
    }
    
    getSearchTerm() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('search') || '';
    }
    
    initializeSearchDisplay() {
        const currentSearch = this.getSearchTerm();
        this.updateSearchDisplay(currentSearch);
    }
}

// Initialize Search Results Manager
const searchResultsManager = new SearchResultsManager();

// Error Handler Class
class ErrorHandler {
    constructor() {
        this.setupGlobalErrorHandling();
    }
    
    setupGlobalErrorHandling() {
        window.addEventListener('error', (event) => {
            console.error('Erro JavaScript global:', event.error);
            this.logError('Global Error', event.error);
        });
        
        window.addEventListener('unhandledrejection', (event) => {
            console.error('Promise rejeitada não tratada:', event.reason);
            this.logError('Unhandled Promise Rejection', event.reason);
        });
    }
    
    logError(context, error) {
        const errorInfo = {
            context: context,
            message: error?.message || 'Erro desconhecido',
            stack: error?.stack || 'Stack não disponível',
            timestamp: new Date().toISOString(),
            userAgent: navigator.userAgent
        };
        
        console.group('🚨 Erro Capturado');
        console.error('Contexto:', errorInfo.context);
        console.error('Mensagem:', errorInfo.message);
        console.error('Stack:', errorInfo.stack);
        console.error('Timestamp:', errorInfo.timestamp);
        console.groupEnd();
        
        // Aqui você poderia enviar o erro para um serviço de logging
        // this.sendToLoggingService(errorInfo);
    }
    
    safeExecute(fn, context = 'Unknown', fallback = null) {
        try {
            return fn();
        } catch (error) {
            this.logError(context, error);
            if (fallback && typeof fallback === 'function') {
                try {
                    return fallback();
                } catch (fallbackError) {
                    this.logError(`${context} - Fallback`, fallbackError);
                }
            }
            return null;
        }
    }
    
    validateElement(elementId, context = 'Element Validation') {
        const element = document.getElementById(elementId);
        if (!element) {
            this.logError(context, new Error(`Elemento não encontrado: ${elementId}`));
            return null;
        }
        return element;
    }
}

// Initialize Error Handler
const errorHandler = new ErrorHandler();

// Função para forçar layout correto dos cards
function forceCardsLayout() {
    console.log('🔧 Forçando layout correto dos cards...');
    
    const cardsView = document.getElementById('cards-view');
    const cardsGrid = document.querySelector('.cards-grid');
    
    if (cardsView && cardsGrid) {
        // Garantir que a view está ativa
        if (cardsView.classList.contains('active')) {
            console.log('✅ Cards view ativa, aplicando layout...');
            
            // Forçar estilos CSS inline para garantir grid
            cardsGrid.style.cssText = `
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 1.5rem !important;
                padding: 1.5rem !important;
                visibility: visible !important;
                opacity: 1 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            `;
            
            // Garantir que os cards individuais não tenham display: block
            const mangaBlocks = cardsGrid.querySelectorAll('.manga-block');
            mangaBlocks.forEach((block, index) => {
                // Remover qualquer display: block que possa ter sido aplicado
                if (block.style.display === 'block') {
                    block.style.display = '';
                    console.log(`Card ${index + 1}: display: block removido`);
                }
            });
            
            console.log('✅ Layout dos cards forçado com sucesso');
        } else {
            console.log('ℹ️ Cards view não está ativa, pulando...');
        }
    } else {
        console.log('⚠️ Cards view ou grid não encontrados');
    }
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// System Validation Class
class SystemValidator {
    constructor() {
        this.validationResults = [];
    }
    
    validateViewSwitching() {
        console.log('🧪 Testando mudança de visualização...');
        
        const listView = document.getElementById('list-view');
        const cardsView = document.getElementById('cards-view');
        const listBtn = document.querySelector('[data-view="list"]');
        const cardsBtn = document.querySelector('[data-view="cards"]');
        
        const results = {
            listViewExists: !!listView,
            cardsViewExists: !!cardsView,
            listBtnExists: !!listBtn,
            cardsBtnExists: !!cardsBtn,
            viewStateManager: !!viewStateManager
        };
        
        console.log('Resultados da validação de views:', results);
        return results;
    }
    
    validateModalSystem() {
        console.log('🧪 Testando sistema de modais...');
        
        const addModal = document.getElementById('addMangaModal');
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');
        
        const results = {
            addModalExists: !!addModal,
            editModalExists: !!editModal,
            deleteModalExists: !!deleteModal,
            modalValidator: !!modalValidator
        };
        
        console.log('Resultados da validação de modais:', results);
        return results;
    }
    
    validateSearchSystem() {
        console.log('🧪 Testando sistema de pesquisa...');
        
        const searchForm = document.querySelector('.search-form-inline');
        const searchInput = document.querySelector('.search-input-inline');
        
        const results = {
            searchFormExists: !!searchForm,
            searchInputExists: !!searchInput,
            searchResultsManager: !!searchResultsManager
        };
        
        console.log('Resultados da validação de pesquisa:', results);
        return results;
    }
    
    validatePersistence() {
        console.log('🧪 Testando persistência de estado...');
        
        const hasLocalStorage = typeof Storage !== 'undefined';
        let canWriteStorage = false;
        
        try {
            localStorage.setItem('test', 'test');
            localStorage.removeItem('test');
            canWriteStorage = true;
        } catch (e) {
            canWriteStorage = false;
        }
        
        const results = {
            localStorageAvailable: hasLocalStorage,
            canWriteToStorage: canWriteStorage,
            viewStateManagerExists: !!viewStateManager
        };
        
        console.log('Resultados da validação de persistência:', results);
        return results;
    }
    
    runFullValidation() {
        console.group('🔍 Executando Validação Completa do Sistema');
        
        this.validationResults = [
            { name: 'View Switching', results: this.validateViewSwitching() },
            { name: 'Modal System', results: this.validateModalSystem() },
            { name: 'Search System', results: this.validateSearchSystem() },
            { name: 'Persistence', results: this.validatePersistence() }
        ];
        
        let allPassed = true;
        this.validationResults.forEach(test => {
            const passed = Object.values(test.results).every(result => result === true);
            console.log(`${passed ? '✅' : '❌'} ${test.name}:`, test.results);
            if (!passed) allPassed = false;
        });
        
        console.log(`\n${allPassed ? '🎉' : '⚠️'} Validação ${allPassed ? 'PASSOU' : 'FALHOU'}`);
        console.groupEnd();
        
        return { allPassed, results: this.validationResults };
    }
}

// Initialize System Validator
const systemValidator = new SystemValidator();

// Test Functions (for development/debugging)
function testViewSwitching() {
    console.log('🧪 Testando mudança de visualização programaticamente...');
    
    // Test switching to cards
    console.log('Mudando para visualização em cards...');
    switchView('cards');
    
    setTimeout(() => {
        const cardsView = document.getElementById('cards-view');
        const isActive = cardsView && cardsView.classList.contains('active');
        console.log('Cards view ativa:', isActive);
        
        // Test switching back to list
        console.log('Mudando para visualização em lista...');
        switchView('list');
        
        setTimeout(() => {
            const listView = document.getElementById('list-view');
            const isListActive = listView && listView.classList.contains('active');
            console.log('List view ativa:', isListActive);
        }, 300);
    }, 300);
}

function testModalFunctionality() {
    console.log('🧪 Testando funcionalidade dos modais...');
    
    // Test add modal
    console.log('Abrindo modal de adicionar...');
    openAddMangaModal();
    
    setTimeout(() => {
        const addModal = document.getElementById('addMangaModal');
        const isVisible = addModal && addModal.classList.contains('show');
        console.log('Modal de adicionar visível:', isVisible);
        
        if (isVisible) {
            console.log('Fechando modal de adicionar...');
            closeAddMangaModal();
        }
    }, 500);
}


// Test persistence functionality
function testPersistence() {
    console.log('🧪 Testando persistência de visualização...');
    
    // Test saving cards view
    console.log('1. Salvando preferência para "cards"...');
    const saveResult = viewStateManager.setCurrentView('cards');
    console.log('Resultado do salvamento:', saveResult);
    
    // Test retrieving saved view
    console.log('2. Recuperando preferência salva...');
    const retrievedView = viewStateManager.getCurrentView();
    console.log('Visualização recuperada:', retrievedView);
    
    // Test switching and persistence
    console.log('3. Testando mudança para cards...');
    switchView('cards');
    
    setTimeout(() => {
        console.log('4. Verificando se foi salvo após mudança...');
        const afterSwitchView = viewStateManager.getCurrentView();
        console.log('Visualização após mudança:', afterSwitchView);
        
        // Test clearing preference
        console.log('5. Limpando preferência...');
        viewStateManager.clearPreference();
        const afterClear = viewStateManager.getCurrentView();
        console.log('Visualização após limpeza:', afterClear);
    }, 500);
}

// Make test functions available globally for manual testing
window.testViewSwitching = testViewSwitching;
window.testModalFunctionality = testModalFunctionality;
window.testPersistence = testPersistence;
window.forceApplyViewState = forceApplyViewState;
window.runSystemValidation = () => systemValidator.runFullValidation();


// Load persisted view preference
function loadPersistedView() {
    return viewStateManager.initializeView();
}

// Force apply view state (fallback function)
function forceApplyViewState() {
    console.log('🔧 Forçando aplicação do estado de visualização...');
    
    const savedView = viewStateManager.getCurrentView();
    console.log('Estado salvo detectado:', savedView);
    
    // Force remove all active states
    document.querySelectorAll('.view-content').forEach(view => {
        view.classList.remove('active');
        view.style.display = 'none';
    });
    
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Apply the correct state
    const targetView = document.getElementById(savedView + '-view');
    const targetBtn = document.querySelector(`[data-view="${savedView}"]`);
    
    if (targetView && targetBtn) {
        targetView.classList.add('active');
        targetView.style.display = 'block';
        targetBtn.classList.add('active');
        
        console.log('✅ Estado forçado aplicado para:', savedView);
    } else {
        console.error('❌ Não foi possível aplicar estado para:', savedView);
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DEBUG: Página carregada ===');
    
    // Verificar se as views existem
    const listView = document.getElementById('list-view');
    const cardsView = document.getElementById('cards-view');
    
    console.log('List view encontrada:', listView ? 'SIM' : 'NÃO');
    console.log('Cards view encontrada:', cardsView ? 'SIM' : 'NÃO');
    
    if (listView) {
        console.log('List view classes:', listView.className);
        const mangaItems = listView.querySelectorAll('.manga-item');
        console.log('Mangás na list view:', mangaItems.length);
    }
    
    if (cardsView) {
        console.log('Cards view classes:', cardsView.className);
        const mangaBlocks = cardsView.querySelectorAll('.manga-block');
        console.log('Mangás na cards view:', mangaBlocks.length);
        
        if (mangaBlocks.length === 0) {
            console.warn('⚠️ PROBLEMA: Cards view não tem mangás!');
            console.log('Conteúdo da cards view:', cardsView.innerHTML.substring(0, 1000));
        } else {
            console.log('✅ Mangás encontrados na cards view');
            mangaBlocks.forEach((block, index) => {
                console.log(`Card ${index + 1}:`, block.className, block.style.cssText);
            });
        }
    }
    
    // Verificar botões de toggle
    const toggleButtons = document.querySelectorAll('.view-btn');
    console.log('Botões de toggle encontrados:', toggleButtons.length);
    toggleButtons.forEach(btn => {
        console.log('Botão:', btn.textContent.trim(), 'data-view:', btn.getAttribute('data-view'));
    });
    
    // Initialize search display
    searchResultsManager.initializeSearchDisplay();
    
    // Initialize list header visibility
    const listHeader = document.getElementById('list-header');
    if (listHeader) {
        // Check if list view is active by default
        const listView = document.getElementById('list-view');
        if (listView && listView.classList.contains('active')) {
            listHeader.style.display = 'flex';
        } else {
            listHeader.style.display = 'none';
        }
    }
    
    console.log('🔄 Iniciando carregamento de preferência de visualização...');
    
    // Check current state before loading preference
    const listViewBefore = document.getElementById('list-view');
    const cardsViewBefore = document.getElementById('cards-view');
    console.log('Estado inicial - List view ativa:', listViewBefore?.classList.contains('active'));
    console.log('Estado inicial - Cards view ativa:', cardsViewBefore?.classList.contains('active'));
    
    // Load persisted view preference immediately
    const loadedView = loadPersistedView();
    console.log('Visualização carregada:', loadedView);
    
    // Check state after loading preference
    setTimeout(() => {
        const listViewAfter = document.getElementById('list-view');
        const cardsViewAfter = document.getElementById('cards-view');
        console.log('Estado final - List view ativa:', listViewAfter?.classList.contains('active'));
        console.log('Estado final - Cards view ativa:', cardsViewAfter?.classList.contains('active'));
        
        const activeBtn = document.querySelector('.view-btn.active');
        console.log('Botão ativo:', activeBtn?.getAttribute('data-view'));
        
        // Verify if the state is correct, if not, force apply it
        const savedView = viewStateManager.getCurrentView();
        const currentActiveView = document.querySelector('.view-content.active');
        const expectedViewId = savedView + '-view';
        
        if (!currentActiveView || currentActiveView.id !== expectedViewId) {
            console.warn('⚠️ Estado inconsistente detectado, forçando correção...');
            forceApplyViewState();
        } else {
            console.log('✅ Estado de visualização correto aplicado');
        }
    }, 300);
    
    // Run system validation after everything is loaded
    setTimeout(() => {
        systemValidator.runFullValidation();
    }, 1000);
    
    
    // Forçar layout correto dos cards após carregamento
    setTimeout(() => {
        forceCardsLayout();
    }, 1000);
    
    console.log('=== FIM DEBUG CARREGAMENTO ===');
    
    // Close add manga modal after form submission
    errorHandler.safeExecute(() => {
        const addMangaForm = document.getElementById('addMangaForm');
        if (addMangaForm) {
            addMangaForm.addEventListener('submit', function() {
                setTimeout(() => {
                    closeAddMangaModal();
                }, 100);
            });
        }
    }, 'Add Manga Form Event Listener');
    
    // Close delete modal after form submission
    errorHandler.safeExecute(() => {
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function() {
                setTimeout(() => {
                    closeDeleteModal();
                }, 100);
            });
        }
    }, 'Delete Form Event Listener');
    
    // Add event listeners for checkboxes
    const emLancamentoCheckbox = document.getElementById('addEmLancamento');
    const finalizadoCheckbox = document.getElementById('addFinalizado');
    
    if (emLancamentoCheckbox) {
        emLancamentoCheckbox.addEventListener('change', function() {
            toggleRequiredFields();
            toggleMutuallyExclusive(this, 'addFinalizado');
        });
    }
    
    if (finalizadoCheckbox) {
        finalizadoCheckbox.addEventListener('change', function() {
            toggleMutuallyExclusive(this, 'addEmLancamento');
        });
    }
    
    // Auto-hide success messages
    setTimeout(() => {
        // You can add success message display here if needed
    }, 3000);
});

// Function to block/unblock total chapters field
function blockTotalChapters(checkbox) {
    const totalChaptersField = document.getElementById('addCapitulosTotal');
    const finalizadoCheckbox = document.getElementById('addFinalizado');
    
    if (!totalChaptersField) {
        console.error('Campo Total de Capítulos não encontrado!');
        return;
    }
    
    if (checkbox.checked) {
        // BLOQUEAR CAMPO
        totalChaptersField.disabled = true;
        totalChaptersField.readOnly = true;
        totalChaptersField.value = '0';
        totalChaptersField.placeholder = '';
        totalChaptersField.style.backgroundColor = '#333';
        totalChaptersField.style.color = '#666';
        totalChaptersField.style.cursor = 'not-allowed';
        totalChaptersField.style.opacity = '0.7';
        
        // Desmarcar checkbox finalizado se estiver marcado
        if (finalizadoCheckbox && finalizadoCheckbox.checked) {
            finalizadoCheckbox.checked = false;
        }
        
        console.log('✅ Campo Total de Capítulos BLOQUEADO');
    } else {
        // DESBLOQUEAR CAMPO
        totalChaptersField.disabled = false;
        totalChaptersField.readOnly = false;
        totalChaptersField.placeholder = 'Ex: 100';
        totalChaptersField.style.backgroundColor = '';
        totalChaptersField.style.color = '';
        totalChaptersField.style.cursor = '';
        totalChaptersField.style.opacity = '';
        
        console.log('✅ Campo Total de Capítulos DESBLOQUEADO');
    }
}

// Function to make checkboxes mutually exclusive
function toggleMutuallyExclusive(currentCheckbox, otherCheckboxId) {
    try {
        const otherCheckbox = document.getElementById(otherCheckboxId);
        if (otherCheckbox && currentCheckbox.checked) {
            otherCheckbox.checked = false;
        }
    } catch (error) {
        console.error('Erro ao alternar checkboxes mutuamente exclusivos:', error);
    }
}

// Function to handle em_lancamento checkbox change
function handleEmLancamentoChange(checkbox) {
    const field = document.getElementById('addCapitulosTotal');
    const finalizado = document.getElementById('addFinalizado');
    
    if (checkbox.checked) {
        field.disabled = true;
        field.readOnly = true;
        field.value = '0';
        field.placeholder = '';
        field.style.backgroundColor = '#333';
        field.style.color = '#666';
        field.style.setProperty('cursor', 'not-allowed', 'important');
        field.style.opacity = '0.7';
        field.classList.add('field-blocked');
        if (finalizado) finalizado.checked = false;
        console.log('✅ Campo BLOQUEADO');
    } else {
        field.disabled = false;
        field.readOnly = false;
        field.placeholder = 'Ex: 100';
        field.style.backgroundColor = '';
        field.style.color = '';
        field.style.cursor = '';
        field.style.opacity = '';
        field.classList.remove('field-blocked');
        console.log('✅ Campo DESBLOQUEADO');
    }
    
    // Chama a função de validação para aplicar a classe field-blocked
    if (typeof toggleRequiredFields === 'function') {
        toggleRequiredFields();
    }
}

// Function to handle edit em_lancamento checkbox change
function handleEditEmLancamentoChange(checkbox) {
    const field = document.getElementById('editCapitulosTotal');
    const capituloAtualField = document.getElementById('editCapituloAtual');
    const finalizado = document.getElementById('editFinalizado');
    
    if (checkbox.checked) {
        // Bloqueia o campo 'Total de Capítulos'
        field.disabled = true;
        field.readOnly = true;
        field.value = '0';
        field.placeholder = '';
        field.style.backgroundColor = '#333';
        field.style.color = '#666';
        field.style.setProperty('cursor', 'not-allowed', 'important');
        field.style.opacity = '0.7';
        field.classList.add('field-blocked');
        
        // Desbloqueia o campo 'Capítulo Atual' (caso estivesse bloqueado)
        capituloAtualField.disabled = false;
        capituloAtualField.readOnly = false;
        capituloAtualField.placeholder = 'Ex: 10';
        capituloAtualField.style.backgroundColor = '';
        capituloAtualField.style.color = '';
        capituloAtualField.style.cursor = '';
        capituloAtualField.style.opacity = '';
        capituloAtualField.classList.remove('field-blocked');
        
        if (finalizado) finalizado.checked = false;
        console.log('✅ Campo Total de Capítulos BLOQUEADO, Capítulo Atual DESBLOQUEADO');
    } else {
        // Desbloqueia o campo 'Total de Capítulos'
        field.disabled = false;
        field.readOnly = false;
        field.placeholder = 'Ex: 100';
        field.style.backgroundColor = '';
        field.style.color = '';
        field.style.cursor = '';
        field.style.opacity = '';
        field.classList.remove('field-blocked');
        console.log('✅ Campo Total de Capítulos DESBLOQUEADO');
    }
    
    // Chama a função de validação
    if (typeof toggleEditRequiredFields === 'function') {
        toggleEditRequiredFields();
    }
}

// Function to handle finalizado checkbox change
function handleFinalizadoChange(checkbox) {
    const emLancamento = document.getElementById('addEmLancamento');
    if (checkbox.checked) {
        if (emLancamento) emLancamento.checked = false;
        console.log('✅ Mangá finalizado marcado');
    } else {
        console.log('✅ Mangá finalizado desmarcado');
    }
}

// Function to handle edit finalizado checkbox change
function handleEditFinalizadoChange(checkbox) {
    const emLancamento = document.getElementById('editEmLancamento');
    if (checkbox.checked) {
        if (emLancamento) emLancamento.checked = false;
        console.log('✅ Mangá finalizado marcado');
    } else {
        console.log('✅ Mangá finalizado desmarcado');
    }
}

// SortableJS Drag and Drop Manager
class SortableManager {
    constructor() {
        this.listSortable = null;
        this.cardsSortable = null;
        this.notification = null;
        this.init();
    }
    
    init() {
        this.createNotification();
        this.initializeSortable();
        
        // Reinitialize when view changes
        document.addEventListener('viewChanged', () => {
            setTimeout(() => this.initializeSortable(), 100);
        });
    }
    
    createNotification() {
        this.notification = document.createElement('div');
        this.notification.className = 'drag-notification';
        this.notification.innerHTML = '<i class="fas fa-check-circle"></i> <span>Ordem atualizada com sucesso!</span>';
        document.body.appendChild(this.notification);
    }
    
    showNotification(message, isError = false) {
        this.notification.querySelector('span').textContent = message;
        this.notification.className = `drag-notification ${isError ? 'error' : ''}`;
        this.notification.classList.add('show');
        
        setTimeout(() => {
            this.notification.classList.remove('show');
        }, 3000);
    }
    
    initializeSortable() {
        // Destroy existing instances
        if (this.listSortable) {
            this.listSortable.destroy();
        }
        if (this.cardsSortable) {
            this.cardsSortable.destroy();
        }
        
        // Initialize list view sortable
        const listView = document.getElementById('list-view');
        if (listView) {
            this.listSortable = new Sortable(listView, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onStart: (evt) => {
                    this.addDragHandles(listView);
                },
                onEnd: (evt) => {
                    this.updateOrder('list');
                }
            });
        }
        
        // Initialize cards view sortable
        const cardsGrid = document.querySelector('.cards-grid');
        if (cardsGrid) {
            this.cardsSortable = new Sortable(cardsGrid, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onStart: (evt) => {
                    this.addDragHandles(cardsGrid);
                },
                onEnd: (evt) => {
                    this.updateOrder('cards');
                }
            });
        }
        
        // Add drag handles to existing items
        this.addDragHandles(listView);
        this.addDragHandles(cardsGrid);
    }
    
    addDragHandles(container) {
        if (!container) return;
        
        const items = container.querySelectorAll('.manga-item, .manga-block');
        items.forEach(item => {
            // Check if drag handle already exists
            if (!item.querySelector('.drag-handle')) {
                const dragHandle = document.createElement('div');
                dragHandle.className = 'drag-handle';
                dragHandle.innerHTML = '<i class="fas fa-grip-vertical"></i>';
                
                // Make item position relative for absolute positioning of handle
                item.style.position = 'relative';
                item.insertBefore(dragHandle, item.firstChild);
            }
        });
    }
    
    async updateOrder(viewType) {
        const container = viewType === 'list' ? 
            document.getElementById('list-view') : 
            document.querySelector('.cards-grid');
            
        if (!container) {
            console.error('Container not found for view type:', viewType);
            return;
        }
        
        // Para lista, pegar os .manga-item, para cards pegar .manga-block
        const items = viewType === 'list' ? 
            container.querySelectorAll('.manga-item') : 
            container.querySelectorAll('.manga-block');
            
        const newOrder = Array.from(items).map(el => el.dataset.mangaId);
        
        // Debug: verificar se os IDs estão corretos
        console.log('New order array:', newOrder);
        console.log('Container children:', container.children);
        
        // Verificar se há IDs válidos
        if (newOrder.length === 0 || newOrder.some(id => !id)) {
            console.error('Invalid manga IDs found:', newOrder);
            this.showNotification('Erro: IDs de mangá inválidos', true);
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('action', 'update_order');
            formData.append('manga_order', JSON.stringify(newOrder));
            
            // Debug: verificar dados enviados
            console.log('Sending data:', {
                action: 'update_order',
                manga_order: JSON.stringify(newOrder)
            });
            
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData
            });
            
            // Debug: verificar resposta
            const responseText = await response.text();
            console.log('Response text:', responseText);
            
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON response:', responseText);
                this.showNotification('Erro: Resposta inválida do servidor', true);
                return;
            }
            
            if (result.success) {
                this.showNotification('Ordem atualizada com sucesso!');
                console.log('Order updated successfully:', newOrder);
            } else {
                this.showNotification('Erro ao atualizar ordem: ' + result.message, true);
                console.error('Error updating order:', result.message);
            }
        } catch (error) {
            console.error('Error updating order:', error);
            this.showNotification('Erro de conexão ao atualizar ordem', true);
        }
    }
}

// Initialize Sortable Manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Wait for SortableJS to be available
    if (typeof Sortable !== 'undefined') {
        window.sortableManager = new SortableManager();
    } else {
        console.error('SortableJS not loaded');
    }
});

// Make functions globally available
window.blockTotalChapters = blockTotalChapters;
window.toggleMutuallyExclusive = toggleMutuallyExclusive;
window.handleEmLancamentoChange = handleEmLancamentoChange;
window.handleEditEmLancamentoChange = handleEditEmLancamentoChange;
window.handleFinalizadoChange = handleFinalizadoChange;
window.handleEditFinalizadoChange = handleEditFinalizadoChange;

// Test function availability
function testFunctionAvailability() {
    console.log('blockTotalChapters disponível:', typeof blockTotalChapters);
    console.log('toggleMutuallyExclusive disponível:', typeof toggleMutuallyExclusive);
}

// Test blocking functionality
function testBlocking() {
    console.log('🧪 TESTANDO BLOQUEIO DO CAMPO...');
    
    // Open modal
    openAddMangaModal();
    
    setTimeout(() => {
        const checkbox = document.getElementById('addEmLancamento');
        const field = document.getElementById('addCapitulosTotal');
        
        if (!checkbox || !field) {
            console.error('❌ Elementos não encontrados!');
            return;
        }
        
        console.log('1. Estado inicial:');
        console.log('   Checkbox marcado:', checkbox.checked);
        console.log('   Campo desabilitado:', field.disabled);
        console.log('   Campo readonly:', field.readOnly);
        
        // Mark checkbox
        console.log('2. Marcando checkbox...');
        checkbox.checked = true;
        checkbox.dispatchEvent(new Event('change'));
        
        setTimeout(() => {
            console.log('3. Estado após marcar:');
            console.log('   Checkbox marcado:', checkbox.checked);
            console.log('   Campo desabilitado:', field.disabled);
            console.log('   Campo readonly:', field.readOnly);
            console.log('   Valor:', field.value);
            
            // Test editing
            const originalValue = field.value;
            field.value = '999';
            const canEdit = field.value !== originalValue;
            
            if (canEdit) {
                console.log('❌ ERRO: Campo ainda pode ser editado!');
            } else {
                console.log('✅ SUCESSO: Campo está bloqueado!');
            }
            
            closeAddMangaModal();
        }, 100);
    }, 100);
}

// Test checkbox blocking functionality
function testCheckboxBlocking() {
    console.log('🧪 Testando bloqueio do campo Total de Capítulos...');
    
    // Open modal
    openAddMangaModal();
    
    setTimeout(() => {
        const emLancamentoCheckbox = document.getElementById('addEmLancamento');
        const totalCapitulosField = document.getElementById('addCapitulosTotal');
        
        if (!emLancamentoCheckbox || !totalCapitulosField) {
            console.error('Elementos não encontrados!');
            return;
        }
        
        console.log('1. Estado inicial:');
        console.log('   Checkbox marcado:', emLancamentoCheckbox.checked);
        console.log('   Campo desabilitado:', totalCapitulosField.disabled);
        console.log('   Campo readonly:', totalCapitulosField.readOnly);
        console.log('   Valor:', totalCapitulosField.value);
        
        // Mark checkbox
        console.log('2. Marcando checkbox "Mangá em lançamento"...');
        emLancamentoCheckbox.checked = true;
        emLancamentoCheckbox.dispatchEvent(new Event('change'));
        
        setTimeout(() => {
            console.log('3. Estado após marcar checkbox:');
            console.log('   Checkbox marcado:', emLancamentoCheckbox.checked);
            console.log('   Campo desabilitado:', totalCapitulosField.disabled);
            console.log('   Campo readonly:', totalCapitulosField.readOnly);
            console.log('   Valor:', totalCapitulosField.value);
            console.log('   Placeholder:', totalCapitulosField.placeholder);
            
            // Test if field is actually blocked
            const originalValue = totalCapitulosField.value;
            totalCapitulosField.value = '999';
            const canEdit = totalCapitulosField.value !== originalValue;
            console.log('   Campo editável:', canEdit);
            
            if (canEdit) {
                console.log('❌ ERRO: Campo ainda pode ser editado!');
            } else {
                console.log('✅ Campo bloqueado corretamente!');
            }
            
            // Unmark checkbox
            console.log('4. Desmarcando checkbox...');
            emLancamentoCheckbox.checked = false;
            emLancamentoCheckbox.dispatchEvent(new Event('change'));
            
            setTimeout(() => {
                console.log('5. Estado após desmarcar checkbox:');
                console.log('   Checkbox marcado:', emLancamentoCheckbox.checked);
                console.log('   Campo desabilitado:', totalCapitulosField.disabled);
                console.log('   Campo readonly:', totalCapitulosField.readOnly);
                console.log('   Valor:', totalCapitulosField.value);
                
                closeAddMangaModal();
                console.log('✅ Teste concluído!');
            }, 500);
        }, 500);
    }, 500);
}

