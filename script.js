
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
                    block.style.display = 'block';
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
        
        // Drag and drop já está inicializado, não precisa reinicializar
        // Removido para evitar múltiplas inicializações
        
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

// ===== DRAG AND DROP SIMPLES =====
let listSortable = null;
let cardsSortable = null;

// Flag para evitar múltiplas inicializações
let dragDropInitialized = false;

function initDragDrop() {
    console.log('🔄 Inicializando Drag and Drop...');
    
    // Verificar se já foi inicializado
    if (dragDropInitialized) {
        console.log('⚠️ Drag and Drop já foi inicializado, ignorando...');
        return;
    }
    
    // Verificar se Sortable está disponível
    if (typeof Sortable === 'undefined') {
        console.error('❌ SortableJS não está carregado!');
        return;
    }
    
    // Destruir instâncias anteriores
    if (listSortable) {
        listSortable.destroy();
        listSortable = null;
    }
    if (cardsSortable) {
        cardsSortable.destroy();
        cardsSortable = null;
    }
    
    // Inicializar lista
    const listContainer = document.querySelector('#list-view');
    if (listContainer) {
        const listItems = listContainer.querySelectorAll('[data-manga-id]');
        console.log('Itens na lista:', listItems.length);
        
        if (listItems.length > 0) {
            try {
                listSortable = new Sortable(listContainer, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onStart: (evt) => {
                        console.log('Drag iniciado na lista:', evt.item.dataset.mangaId);
                    },
                    onEnd: (evt) => {
                        console.log('Drag finalizado na lista:', evt.item.dataset.mangaId);
                        updateOrder('list');
                    }
                });
                console.log('✅ Lista sortable inicializada');
            } catch (error) {
                console.error('❌ Erro na lista:', error);
            }
        }
    }
    
    // Inicializar cards
    const cardsContainer = document.querySelector('#cards-view');
    if (cardsContainer) {
        const cardItems = cardsContainer.querySelectorAll('[data-manga-id]');
        console.log('Itens nos cards:', cardItems.length);
        
        if (cardItems.length > 0) {
            try {
                cardsSortable = new Sortable(cardsContainer, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onStart: (evt) => {
                        console.log('Drag iniciado nos cards:', evt.item.dataset.mangaId);
                    },
                    onEnd: (evt) => {
                        console.log('Drag finalizado nos cards:', evt.item.dataset.mangaId);
                        updateOrder('cards');
                    }
                });
                console.log('✅ Cards sortable inicializados');
            } catch (error) {
                console.error('❌ Erro nos cards:', error);
            }
        }
    }
    
    // Aplicar ordem salva após inicializar
    setTimeout(() => {
        applySavedOrder();
    }, 100);
    
    // Marcar como inicializado
    dragDropInitialized = true;
    console.log('✅ Drag and Drop inicializado');
}

function updateOrder(viewType) {
    console.log('🔄 updateOrder chamada para:', viewType);
    
    const container = viewType === 'list' 
        ? document.querySelector('#list-view')
        : document.querySelector('#cards-view');
        
    if (!container) {
        console.error('❌ Container não encontrado para:', viewType);
        return;
    }
    
    const items = container.querySelectorAll('[data-manga-id]');
    console.log('Itens encontrados:', items.length);
    
    if (items.length === 0) {
        console.warn('⚠️ Nenhum item encontrado para salvar ordem');
        // Não retornar aqui, pode ser que a página ainda esteja carregando
        return;
    }
    
    const order = Array.from(items).map(item => item.dataset.mangaId);
    
    console.log('Nova ordem:', order);
    
    try {
        // Salvar no localStorage
        localStorage.setItem(`mangaOrder_${viewType}`, JSON.stringify(order));
        console.log('✅ Ordem salva no localStorage');
        
        // Enviar para o servidor
        saveOrderToServer(order, viewType);
        
    } catch (error) {
        console.error('❌ Erro ao salvar ordem:', error);
    }
}

// Função para enviar ordem para o servidor
async function saveOrderToServer(order, viewType) {
    try {
        console.log('📤 Enviando ordem para o servidor...');
        
        const response = await fetch('update-order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order: order,
                view_type: viewType
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log('✅ Ordem salva no servidor:', result.message);
            
            // Mostrar notificação de sucesso
            if (typeof showNotification === 'function') {
                showNotification(`Ordem salva! ${result.message}`);
            } else {
                console.log('ℹ️ Função showNotification não disponível');
            }
        } else {
            console.error('❌ Erro do servidor:', result.message);
            
            // Mostrar notificação de erro
            if (typeof showNotification === 'function') {
                showNotification(`Erro: ${result.message}`, 'error');
            }
        }
        
    } catch (error) {
        console.error('❌ Erro ao enviar para servidor:', error);
        
        // Mostrar notificação de erro de rede
        if (typeof showNotification === 'function') {
            showNotification('Erro de conexão. Ordem salva localmente.', 'error');
        }
    }
}

// Função para restaurar ordem salva
function restoreOrder(viewType) {
    console.log('🔄 restoreOrder chamada para:', viewType);
    
    const container = viewType === 'list' 
        ? document.querySelector('#list-view')
        : document.querySelector('#cards-view');
        
    if (!container) {
        console.error('❌ Container não encontrado para:', viewType);
        return false;
    }
    
    const savedOrder = localStorage.getItem(`mangaOrder_${viewType}`);
    if (!savedOrder) {
        console.log('ℹ️ Nenhuma ordem salva encontrada para:', viewType);
        return false;
    }
    
    console.log('📦 Ordem salva encontrada:', savedOrder);
    
    try {
        const order = JSON.parse(savedOrder);
        console.log('Ordem salva encontrada:', order);
        
        const items = container.querySelectorAll('[data-manga-id]');
        if (items.length === 0) return false;
        
        // Criar um mapa de IDs para elementos
        const itemMap = new Map();
        items.forEach(item => {
            itemMap.set(item.dataset.mangaId, item);
        });
        
        // Reordenar elementos conforme ordem salva
        const orderedItems = [];
        order.forEach(id => {
            if (itemMap.has(id)) {
                orderedItems.push(itemMap.get(id));
            }
        });
        
        // Adicionar itens que não estavam na ordem salva (novos mangás)
        items.forEach(item => {
            if (!order.includes(item.dataset.mangaId)) {
                orderedItems.push(item);
            }
        });
        
        // Aplicar nova ordem no DOM
        orderedItems.forEach(item => {
            container.appendChild(item);
        });
        
        console.log('Ordem restaurada com sucesso!');
        return true;
        
    } catch (error) {
        console.error('Erro ao restaurar ordem:', error);
        return false;
    }
}

// Função para aplicar ordem em ambas as visualizações
function applySavedOrder() {
    console.log('🔄 Aplicando ordem salva...');
    
    // Verificar se há mangás na página
    const listView = document.querySelector('#list-view');
    const cardsView = document.querySelector('#cards-view');
    
    if (listView) {
        const listItems = listView.querySelectorAll('[data-manga-id]');
        console.log('Mangás na lista:', listItems.length);
    }
    
    if (cardsView) {
        const cardItems = cardsView.querySelectorAll('[data-manga-id]');
        console.log('Mangás nos cards:', cardItems.length);
    }
    
    const listRestored = restoreOrder('list');
    const cardsRestored = restoreOrder('cards');
    
    if (listRestored || cardsRestored) {
        console.log('✅ Ordem restaurada com sucesso!');
        showNotification('Ordem dos mangás restaurada!');
    } else {
        console.log('ℹ️ Nenhuma ordem salva encontrada');
    }
}

// Função para limpar ordem salva (útil para testes)
function clearSavedOrder() {
    localStorage.removeItem('mangaOrder_list');
    localStorage.removeItem('mangaOrder_cards');
    console.log('🗑️ Ordem salva removida');
    showNotification('Ordem salva removida!');
}

// Função para verificar se há ordem salva
function hasSavedOrder() {
    const listOrder = localStorage.getItem('mangaOrder_list');
    const cardsOrder = localStorage.getItem('mangaOrder_cards');
    return !!(listOrder || cardsOrder);
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

// Funções de teste para drag and drop
window.testDragDrop = function() {
    console.log('🧪 Testando Drag and Drop...');
    initDragDrop();
};

// Funções de teste para sistema de ordem
window.testOrderSystem = function() {
    console.log('🧪 Testando Sistema de Ordem...');
    console.log('1. Há ordem salva?', hasSavedOrder());
    console.log('2. Aplicando ordem salva...');
    applySavedOrder();
};

window.clearOrder = function() {
    console.log('🧪 Limpando ordem salva...');
    clearSavedOrder();
};

window.debugOrder = function() {
    console.log('🔍 DEBUG DO SISTEMA DE ORDEM');
    console.log('============================');
    
    const listOrder = localStorage.getItem('mangaOrder_list');
    const cardsOrder = localStorage.getItem('mangaOrder_cards');
    
    console.log('1. Ordem da lista salva:', listOrder ? JSON.parse(listOrder) : 'Nenhuma');
    console.log('2. Ordem dos cards salva:', cardsOrder ? JSON.parse(cardsOrder) : 'Nenhuma');
    console.log('3. Há ordem salva?', hasSavedOrder());
    
    const listView = document.querySelector('#list-view');
    const cardsView = document.querySelector('#cards-view');
    
    if (listView) {
        const listItems = listView.querySelectorAll('[data-manga-id]');
        const currentListOrder = Array.from(listItems).map(item => item.dataset.mangaId);
        console.log('4. Ordem atual da lista:', currentListOrder);
    }
    
    if (cardsView) {
        const cardItems = cardsView.querySelectorAll('[data-manga-id]');
        const currentCardsOrder = Array.from(cardItems).map(item => item.dataset.mangaId);
        console.log('5. Ordem atual dos cards:', currentCardsOrder);
    }
    
    console.log('\nComandos disponíveis:');
    console.log('- testOrderSystem() - Testar sistema');
    console.log('- clearOrder() - Limpar ordem salva');
    console.log('- applySavedOrder() - Aplicar ordem salva');
};

window.testSimpleDrag = function() {
    console.log('🧪 Teste Simples de Drag and Drop...');
    
    if (typeof Sortable === 'undefined') {
        console.error('❌ SortableJS não está carregado!');
        return;
    }
    
    const testContainer = document.createElement('div');
    testContainer.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border: 2px solid #007bff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 10000;
        min-width: 300px;
    `;
    
    testContainer.innerHTML = `
        <h3>Teste Simples</h3>
        <div id="test-list">
            <div data-id="1" style="padding: 10px; background: #f0f0f0; margin: 5px; cursor: grab; border-radius: 4px;">
                <span class="drag-handle" style="margin-right: 10px;">⋮⋮</span> Item 1
            </div>
            <div data-id="2" style="padding: 10px; background: #f0f0f0; margin: 5px; cursor: grab; border-radius: 4px;">
                <span class="drag-handle" style="margin-right: 10px;">⋮⋮</span> Item 2
            </div>
            <div data-id="3" style="padding: 10px; background: #f0f0f0; margin: 5px; cursor: grab; border-radius: 4px;">
                <span class="drag-handle" style="margin-right: 10px;">⋮⋮</span> Item 3
            </div>
        </div>
        <button onclick="this.parentElement.remove()" style="margin-top: 10px; padding: 5px 10px;">Fechar</button>
    `;
    
    document.body.appendChild(testContainer);
    
    try {
        const sortable = new Sortable(document.getElementById('test-list'), {
            handle: '.drag-handle',
            animation: 150,
            onStart: (evt) => {
                console.log('✅ Drag iniciado:', evt.item.dataset.id);
            },
            onEnd: (evt) => {
                console.log('✅ Drag finalizado:', evt.item.dataset.id);
                const order = Array.from(evt.to.children).map(item => item.dataset.id);
                console.log('Nova ordem:', order);
            }
        });
        
        console.log('✅ Teste simples criado! Tente arrastar os itens.');
        
    } catch (error) {
        console.error('❌ Erro no teste simples:', error);
        testContainer.remove();
    }
};

window.debugDragDrop = function() {
    console.log('🔍 DEBUG SIMPLES DO DRAG AND DROP');
    console.log('==================================');
    
    console.log('1. SortableJS:', typeof Sortable !== 'undefined' ? 'OK' : 'ERRO');
    
    const listView = document.querySelector('#list-view');
    const cardsView = document.querySelector('#cards-view');
    console.log('2. List view:', listView ? 'OK' : 'ERRO');
    console.log('3. Cards view:', cardsView ? 'OK' : 'ERRO');
    
    if (listView) {
        const items = listView.querySelectorAll('[data-manga-id]');
        const handles = listView.querySelectorAll('.drag-handle');
        console.log('4. Itens na lista:', items.length);
        console.log('5. Handles na lista:', handles.length);
    }
    
    if (cardsView) {
        const items = cardsView.querySelectorAll('[data-manga-id]');
        const handles = cardsView.querySelectorAll('.drag-handle');
        console.log('6. Itens nos cards:', items.length);
        console.log('7. Handles nos cards:', handles.length);
    }
    
    console.log('8. List Sortable:', listSortable ? 'ATIVA' : 'INATIVA');
    console.log('9. Cards Sortable:', cardsSortable ? 'ATIVA' : 'INATIVA');
    
    console.log('\nPara testar:');
    console.log('- testDragDrop() - Reinicializar');
    console.log('- testSimpleDrag() - Teste isolado');
};

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
    
    // Initialize Drag and Drop uma única vez
    setTimeout(() => {
        initDragDrop();
    }, 500);
    
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

