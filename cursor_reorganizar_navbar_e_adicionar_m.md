# Reorganizar navbar e adicionar modal
_Exported on 8/28/2025 at 00:45:08 GMT-3 from Cursor (1.5.5)_

---

**User**

Colocar os botões animes e jogos, no lado direto da navbar, onde está o campo de pesquisa, ao lado esquerdo do botão de alternancia de tema, e coloque o campo de pesquisa, ao lado direito da logo, onde fica os botões animes e jogos.
o campo "Adicionar Novo Mangá" deve ser um modal, como um elemento que aparece por cima da pagina atual, sem carregar uma nova pagina

---

**Cursor**

Vou ajudar você a reorganizar a navbar e transformar o formulário "Adicionar Novo Mangá" em um modal. Primeiro, preciso examinar a estrutura atual do projeto para entender como está implementado.


Agora vou implementar as mudanças solicitadas:

1. Mover os botões "Animes" e "Jogos" para o lado direito da navbar (ao lado esquerdo do botão de alternância de tema)
2. Mover o campo de pesquisa para o lado esquerdo da logo (onde estavam os botões)
3. Transformar o formulário "Adicionar Novo Mangá" em um modal


```php
        /* Navbar */
        .navbar {
            background-color: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-bar {
            position: relative;
            min-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 1rem;
            padding-left: 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: var(--bg-tertiary);
            color: var(--primary-color);
        }

        .user-icon {
            color: var(--text-secondary);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .user-icon:hover {
            background-color: var(--bg-tertiary);
            color: var(--primary-color);
        }

        /* Add Manga Button */
        .add-manga-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-manga-btn:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: var(--bg-primary);
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: var(--shadow-lg);
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.25rem;
        }

        .close-btn:hover {
            color: var(--text-primary);
        }
```

Agora vou atualizar a estrutura HTML da navbar para reorganizar os elementos conforme solicitado:


```php
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-left">
                    <a href="#" class="logo">
                        <i class="fas fa-book-open"></i>
                        MagApp
                    </a>
                    <div class="nav-links">
                        <a href="#" class="nav-link">Animes</a>
                        <a href="#" class="nav-link">Jogos</a>
                    </div>
                </div>
                <div class="navbar-right">
                    <div class="search-bar">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Pesquisar globalmente...">
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

->

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-left">
                    <a href="#" class="logo">
                        <i class="fas fa-book-open"></i>
                        MagApp
                    </a>
                    <div class="search-bar">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Pesquisar globalmente...">
                    </div>
                </div>
                <div class="navbar-right">
                    <div class="nav-links">
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
```

Agora vou atualizar o CSS responsivo para garantir que a nova organização funcione bem em dispositivos móveis:


```php
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-content {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-left {
                flex-direction: column;
                gap: 1rem;
            }

            .search-bar {
                min-width: auto;
                width: 100%;
            }

            .manga-item {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .manga-details {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-buttons {
                justify-content: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

->

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-content {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-left {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-right {
                flex-direction: column;
                gap: 1rem;
            }

            .search-bar {
                min-width: auto;
                width: 100%;
            }

            .nav-links {
                justify-content: center;
            }

            .manga-item {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .manga-details {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-buttons {
                justify-content: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
```

Agora vou atualizar o JavaScript para incluir a funcionalidade de fechar o modal de adicionar mangá quando o formulário for enviado:


```php
        // Add Manga Modal functions
        function openAddMangaModal() {
            const modal = document.getElementById('addMangaModal');
            modal.classList.add('show');
        }

        function closeAddMangaModal() {
            const modal = document.getElementById('addMangaModal');
            modal.classList.remove('show');
        }

->

        // Add Manga Modal functions
        function openAddMangaModal() {
            const modal = document.getElementById('addMangaModal');
            modal.classList.add('show');
        }

        function closeAddMangaModal() {
            const modal = document.getElementById('addMangaModal');
            modal.classList.remove('show');
        }

        // Close add manga modal after form submission
        document.getElementById('addMangaForm').addEventListener('submit', function() {
            setTimeout(() => {
                closeAddMangaModal();
            }, 100);
        });
```

Perfeito! Agora vou verificar se há algum problema com o CSS que precisa ser ajustado. Vou também garantir que o modal tenha um estilo adequado:


```php
        .add-manga-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-manga-btn:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }

->

        .add-manga-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .add-manga-btn:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }
```

Excelente! Agora todas as mudanças solicitadas foram implementadas com sucesso. Vou fazer um resumo do que foi alterado:

## Resumo das Alterações Implementadas:

### 1. **Reorganização da Navbar:**
- ✅ **Campo de pesquisa** foi movido para o lado esquerdo da logo (onde estavam os botões Animes e Jogos)
- ✅ **Botões "Animes" e "Jogos"** foram movidos para o lado direito da navbar, ao lado esquerdo do botão de alternância de tema

### 2. **Formulário "Adicionar Novo Mangá" transformado em Modal:**
- ✅ O formulário foi removido da página principal
- ✅ Foi criado um modal que aparece por cima da página atual
- ✅ Adicionado um botão "Adicionar Mangá" que abre o modal
- ✅ O modal inclui todos os campos originais do formulário
- ✅ Funcionalidade JavaScript para abrir/fechar o modal
- ✅ O modal fecha automaticamente após o envio do formulário

### 3. **Melhorias na Interface:**
- ✅ Estilo responsivo atualizado para a nova organização
- ✅ Modal com design consistente com o resto da aplicação
- ✅ Botão de adicionar mangá com estilo destacado
- ✅ Funcionalidade de fechar modal clicando fora dele

### 4. **Funcionalidades Mantidas:**
- ✅ Todas as funcionalidades PHP de adicionar, editar e excluir mangás
- ✅ Sistema de temas (claro/escuro)
- ✅ Pesquisa de mangás
- ✅ Métricas e lista de mangás

A aplicação agora tem uma interface mais organizada e moderna, com o formulário de adicionar mangá funcionando como um modal elegante que não interrompe o fluxo da página principal.

---

**User**

alterações dentro do modal
-colocar botão de adicionar mangá ao lado direito
-adicionar uma campo de marcação "Em lançamento", onde se for selecionado, deve tirar a obrigatoriedade do campo total de capitulos
-adicionar na lista de status, completado, caso esse status seja selecionado, a opção de capitulo atual não deve ser mais obrigatória

---

**Cursor**

Vou implementar as alterações solicitadas no modal de adicionar mangá. Vou fazer as seguintes mudanças:

1. Mover o botão "Adicionar Mangá" para o lado direito
2. Adicionar campo "Em lançamento" que remove obrigatoriedade do total de capítulos
3. Adicionar status "Completado" que remove obrigatoriedade do capítulo atual
4. Ajustar a validação JavaScript para essas novas regras


```php
     <!-- Add Manga Modal -->
     <div id="addMangaModal" class="modal">
         <div class="modal-content">
             <div class="modal-header">
                 <h3 class="modal-title">Adicionar Novo Mangá</h3>
                 <button class="close-btn" onclick="closeAddMangaModal()">&times;</button>
             </div>
             <form method="POST" id="addMangaForm">
                 <input type="hidden" name="action" value="add">
                 <div class="form-grid">
                     <div class="form-group">
                         <label class="form-label">Nome do Mangá *</label>
                         <input type="text" name="nome" id="addNome" class="form-input" required>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Status *</label>
                         <select name="status" id="addStatus" class="form-select" required>
                             <option value="">Selecione...</option>
                             <option value="lendo">Lendo</option>
                             <option value="pretendo">Pretendo Ler</option>
                             <option value="abandonado">Abandonado</option>
                         </select>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Total de Capítulos *</label>
                         <input type="number" name="capitulos_total" id="addCapitulosTotal" class="form-input" min="1" required>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Capítulo Atual</label>
                         <input type="number" name="capitulo_atual" id="addCapituloAtual" class="form-input" min="0" value="0">
                     </div>
                     <div class="form-group">
                         <label class="form-label">Finalizado</label>
                         <div class="checkbox-group">
                             <input type="checkbox" name="finalizado" id="addFinalizado" class="form-checkbox">
                             <span>Mangá finalizado</span>
                         </div>
                     </div>
                 </div>
                 <button type="submit" class="submit-btn">
                     <i class="fas fa-plus"></i> Adicionar Mangá
                 </button>
             </form>
         </div>
     </div>

->

     <!-- Add Manga Modal -->
     <div id="addMangaModal" class="modal">
         <div class="modal-content">
             <div class="modal-header">
                 <h3 class="modal-title">Adicionar Novo Mangá</h3>
                 <button class="close-btn" onclick="closeAddMangaModal()">&times;</button>
             </div>
             <form method="POST" id="addMangaForm">
                 <input type="hidden" name="action" value="add">
                 <div class="form-grid">
                     <div class="form-group">
                         <label class="form-label">Nome do Mangá *</label>
                         <input type="text" name="nome" id="addNome" class="form-input" required>
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
                         <label class="form-label">Total de Capítulos *</label>
                         <input type="number" name="capitulos_total" id="addCapitulosTotal" class="form-input" min="1" required>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Capítulo Atual</label>
                         <input type="number" name="capitulo_atual" id="addCapituloAtual" class="form-input" min="0" value="0">
                     </div>
                     <div class="form-group">
                         <label class="form-label">Finalizado</label>
                         <div class="checkbox-group">
                             <input type="checkbox" name="finalizado" id="addFinalizado" class="form-checkbox">
                             <span>Mangá finalizado</span>
                         </div>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Em Lançamento</label>
                         <div class="checkbox-group">
                             <input type="checkbox" name="em_lancamento" id="addEmLancamento" class="form-checkbox" onchange="toggleRequiredFields()">
                             <span>Mangá em lançamento</span>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="submit" class="submit-btn">
                         <i class="fas fa-plus"></i> Adicionar Mangá
                     </button>
                 </div>
             </form>
         </div>
     </div>
```

Agora vou adicionar o CSS para o modal-footer para posicionar o botão à direita:


```php
        .close-btn:hover {
            color: var(--text-primary);
        }

->

        .close-btn:hover {
            color: var(--text-primary);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
```

Agora vou atualizar o modal de edição para incluir as mesmas opções:


```php
     <!-- Edit Modal -->
     <div id="editModal" class="modal">
         <div class="modal-content">
             <div class="modal-header">
                 <h3 class="modal-title">Editar Mangá</h3>
                 <button class="close-btn" onclick="closeEditModal()">&times;</button>
             </div>
             <form method="POST" id="editForm">
                 <input type="hidden" name="action" value="edit">
                 <input type="hidden" name="id" id="editId">
                 <div class="form-grid">
                     <div class="form-group">
                         <label class="form-label">Nome do Mangá *</label>
                         <input type="text" name="nome" id="editNome" class="form-input" required>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Status *</label>
                         <select name="status" id="editStatus" class="form-select" required>
                             <option value="lendo">Lendo</option>
                             <option value="pretendo">Pretendo Ler</option>
                             <option value="abandonado">Abandonado</option>
                         </select>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Total de Capítulos *</label>
                         <input type="number" name="capitulos_total" id="editCapitulosTotal" class="form-input" min="1" required>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Capítulo Atual</label>
                         <input type="number" name="capitulo_atual" id="editCapituloAtual" class="form-input" min="0">
                     </div>
                     <div class="form-group">
                         <label class="form-label">Finalizado</label>
                         <div class="checkbox-group">
                             <input type="checkbox" name="finalizado" id="editFinalizado" class="form-checkbox">
                             <span>Mangá finalizado</span>
                         </div>
                     </div>
                 </div>
                 <button type="submit" class="submit-btn">
                     <i class="fas fa-save"></i> Salvar Alterações
                 </button>
             </form>
         </div>
     </div>

->

     <!-- Edit Modal -->
     <div id="editModal" class="modal">
         <div class="modal-content">
             <div class="modal-header">
                 <h3 class="modal-title">Editar Mangá</h3>
                 <button class="close-btn" onclick="closeEditModal()">&times;</button>
             </div>
             <form method="POST" id="editForm">
                 <input type="hidden" name="action" value="edit">
                 <input type="hidden" name="id" id="editId">
                 <div class="form-grid">
                     <div class="form-group">
                         <label class="form-label">Nome do Mangá *</label>
                         <input type="text" name="nome" id="editNome" class="form-input" required>
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
                         <label class="form-label">Total de Capítulos *</label>
                         <input type="number" name="capitulos_total" id="editCapitulosTotal" class="form-input" min="1" required>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Capítulo Atual</label>
                         <input type="number" name="capitulo_atual" id="editCapituloAtual" class="form-input" min="0">
                     </div>
                     <div class="form-group">
                         <label class="form-label">Finalizado</label>
                         <div class="checkbox-group">
                             <input type="checkbox" name="finalizado" id="editFinalizado" class="form-checkbox">
                             <span>Mangá finalizado</span>
                         </div>
                     </div>
                     <div class="form-group">
                         <label class="form-label">Em Lançamento</label>
                         <div class="checkbox-group">
                             <input type="checkbox" name="em_lancamento" id="editEmLancamento" class="form-checkbox" onchange="toggleEditRequiredFields()">
                             <span>Mangá em lançamento</span>
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
```

Agora vou adicionar as funções JavaScript para controlar a obrigatoriedade dos campos:


```php
        // Add Manga Modal functions
        function openAddMangaModal() {
            const modal = document.getElementById('addMangaModal');
            modal.classList.add('show');
        }

        function closeAddMangaModal() {
            const modal = document.getElementById('addMangaModal');
            modal.classList.remove('show');
        }

        // Close add manga modal after form submission
        document.getElementById('addMangaForm').addEventListener('submit', function() {
            setTimeout(() => {
                closeAddMangaModal();
            }, 100);
        });

->

        // Add Manga Modal functions
        function openAddMangaModal() {
            const modal = document.getElementById('addMangaModal');
            modal.classList.add('show');
            // Reset required fields when opening modal
            toggleRequiredFields();
        }

        function closeAddMangaModal() {
            const modal = document.getElementById('addMangaModal');
            modal.classList.remove('show');
        }

        // Function to toggle required fields based on status and em_lancamento
        function toggleRequiredFields() {
            const status = document.getElementById('addStatus').value;
            const emLancamento = document.getElementById('addEmLancamento').checked;
            const totalCapitulos = document.getElementById('addCapitulosTotal');
            const capituloAtual = document.getElementById('addCapituloAtual');
            
            // If "Em Lançamento" is checked, remove required from total chapters
            if (emLancamento) {
                totalCapitulos.removeAttribute('required');
                totalCapitulos.disabled = true;
                totalCapitulos.value = '';
                totalCapitulos.placeholder = 'N/A - Em lançamento';
            } else {
                totalCapitulos.setAttribute('required', 'required');
                totalCapitulos.disabled = false;
                totalCapitulos.placeholder = '';
            }
            
            // If status is "Completado", remove required from current chapter
            if (status === 'completado') {
                capituloAtual.removeAttribute('required');
                capituloAtual.disabled = true;
                capituloAtual.value = '';
                capituloAtual.placeholder = 'N/A - Completado';
            } else {
                capituloAtual.removeAttribute('required');
                capituloAtual.disabled = false;
                capituloAtual.placeholder = '';
            }
        }

        // Function to toggle required fields in edit modal
        function toggleEditRequiredFields() {
            const status = document.getElementById('editStatus').value;
            const emLancamento = document.getElementById('editEmLancamento').checked;
            const totalCapitulos = document.getElementById('editCapitulosTotal');
            const capituloAtual = document.getElementById('editCapituloAtual');
            
            // If "Em Lançamento" is checked, remove required from total chapters
            if (emLancamento) {
                totalCapitulos.removeAttribute('required');
                totalCapitulos.disabled = true;
                totalCapitulos.placeholder = 'N/A - Em lançamento';
            } else {
                totalCapitulos.setAttribute('required', 'required');
                totalCapitulos.disabled = false;
                totalCapitulos.placeholder = '';
            }
            
            // If status is "Completado", remove required from current chapter
            if (status === 'completado') {
                capituloAtual.removeAttribute('required');
                capituloAtual.disabled = true;
                capituloAtual.value = '';
                capituloAtual.placeholder = 'N/A - Completado';
            } else {
                capituloAtual.removeAttribute('required');
                capituloAtual.disabled = false;
                capituloAtual.placeholder = '';
            }
        }

        // Close add manga modal after form submission
        document.getElementById('addMangaForm').addEventListener('submit', function() {
            setTimeout(() => {
                closeAddMangaModal();
            }, 100);
        });
```

Agora vou atualizar a função `openEditModal` para incluir o campo "Em Lançamento" e chamar a função de validação:


```php
        // Edit modal functions
        function openEditModal(mangaId) {
            const modal = document.getElementById('editModal');
            const mangas = <?php echo json_encode($_SESSION['mangas']); ?>;
            const manga = mangas.find(m => m.id === mangaId);
            
            if (manga) {
                document.getElementById('editId').value = manga.id;
                document.getElementById('editNome').value = manga.nome;
                document.getElementById('editStatus').value = manga.status;
                document.getElementById('editCapitulosTotal').value = manga.capitulos_total;
                document.getElementById('editCapituloAtual').value = manga.capitulo_atual;
                document.getElementById('editFinalizado').checked = manga.finalizado;
                
                modal.classList.add('show');
            }
        }

->

        // Edit modal functions
        function openEditModal(mangaId) {
            const modal = document.getElementById('editModal');
            const mangas = <?php echo json_encode($_SESSION['mangas']); ?>;
            const manga = mangas.find(m => m.id === mangaId);
            
            if (manga) {
                document.getElementById('editId').value = manga.id;
                document.getElementById('editNome').value = manga.nome;
                document.getElementById('editStatus').value = manga.status;
                document.getElementById('editCapitulosTotal').value = manga.capitulos_total;
                document.getElementById('editCapituloAtual').value = manga.capitulo_atual;
                document.getElementById('editFinalizado').checked = manga.finalizado;
                // Set em_lancamento field (default to false if not exists)
                document.getElementById('editEmLancamento').checked = manga.em_lancamento || false;
                
                modal.classList.add('show');
                // Apply field validation rules
                toggleEditRequiredFields();
            }
        }
```

Agora vou atualizar o PHP para processar o novo campo "em_lancamento":


```php
            case 'add':
                if (!empty($_POST['nome']) && !empty($_POST['status']) && !empty($_POST['capitulos_total'])) {
                    $manga = [
                        'id' => uniqid(),
                        'nome' => $_POST['nome'],
                        'status' => $_POST['status'],
                        'capitulos_total' => (int)$_POST['capitulos_total'],
                        'finalizado' => isset($_POST['finalizado']) ? true : false,
                        'capitulo_atual' => (int)$_POST['capitulo_atual'],
                        'data_criacao' => date('Y-m-d H:i:s')
                    ];
                    $_SESSION['mangas'][] = $manga;
                }
                break;

->

            case 'add':
                if (!empty($_POST['nome']) && !empty($_POST['status'])) {
                    // Validate required fields based on conditions
                    $em_lancamento = isset($_POST['em_lancamento']) ? true : false;
                    $status = $_POST['status'];
                    
                    // Check if total chapters is required (not em_lancamento)
                    if ($em_lancamento && empty($_POST['capitulos_total'])) {
                        $_POST['capitulos_total'] = 0; // Set to 0 for ongoing manga
                    }
                    
                    // Check if current chapter is required (not completado)
                    if ($status === 'completado' && empty($_POST['capitulo_atual'])) {
                        $_POST['capitulo_atual'] = $_POST['capitulos_total']; // Set to total for completed manga
                    }
                    
                    $manga = [
                        'id' => uniqid(),
                        'nome' => $_POST['nome'],
                        'status' => $_POST['status'],
                        'capitulos_total' => (int)$_POST['capitulos_total'],
                        'finalizado' => isset($_POST['finalizado']) ? true : false,
                        'capitulo_atual' => (int)$_POST['capitulo_atual'],
                        'em_lancamento' => $em_lancamento,
                        'data_criacao' => date('Y-m-d H:i:s')
                    ];
                    $_SESSION['mangas'][] = $manga;
                }
                break;
```

Agora vou atualizar o case 'edit' para incluir o campo "em_lancamento":


```php
            case 'edit':
                if (isset($_POST['id']) && !empty($_POST['nome'])) {
                    foreach ($_SESSION['mangas'] as &$manga) {
                        if ($manga['id'] === $_POST['id']) {
                            $manga['nome'] = $_POST['nome'];
                            $manga['status'] = $_POST['status'];
                            $manga['capitulos_total'] = (int)$_POST['capitulos_total'];
                            $manga['finalizado'] = isset($_POST['finalizado']) ? true : false;
                            $manga['capitulo_atual'] = (int)$_POST['capitulo_atual'];
                            break;
                        }
                    }
                }
                break;

->

            case 'edit':
                if (isset($_POST['id']) && !empty($_POST['nome'])) {
                    foreach ($_SESSION['mangas'] as &$manga) {
                        if ($manga['id'] === $_POST['id']) {
                            $manga['nome'] = $_POST['nome'];
                            $manga['status'] = $_POST['status'];
                            $manga['capitulos_total'] = (int)$_POST['capitulos_total'];
                            $manga['finalizado'] = isset($_POST['finalizado']) ? true : false;
                            $manga['capitulo_atual'] = (int)$_POST['capitulo_atual'];
                            $manga['em_lancamento'] = isset($_POST['em_lancamento']) ? true : false;
                            break;
                        }
                    }
                }
                break;
```

Agora vou atualizar a exibição da lista de mangás para mostrar o status "Em Lançamento" quando aplicável:


```php
                                <div class="manga-details">
                                    <span>Capítulo <?php echo $manga['capitulo_atual']; ?> de <?php echo $manga['capitulos_total']; ?></span>
                                    <?php if ($manga['finalizado']): ?>
                                        <span>• Finalizado</span>
                                    <?php endif; ?>
                                    <span>• <?php echo date('d/m/Y', strtotime($manga['data_criacao'])); ?></span>
                                </div>

->

                                <div class="manga-details">
                                    <?php if (isset($manga['em_lancamento']) && $manga['em_lancamento']): ?>
                                        <span>Em lançamento</span>
                                    <?php else: ?>
                                        <span>Capítulo <?php echo $manga['capitulo_atual']; ?> de <?php echo $manga['capitulos_total']; ?></span>
                                    <?php endif; ?>
                                    <?php if ($manga['finalizado']): ?>
                                        <span>• Finalizado</span>
                                    <?php endif; ?>
                                    <span>• <?php echo date('d/m/Y', strtotime($manga['data_criacao'])); ?></span>
                                </div>
```

Agora vou atualizar o CSS para melhorar a aparência dos campos desabilitados:


```php
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

->

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input:disabled, .form-select:disabled {
            background-color: var(--bg-tertiary);
            color: var(--text-secondary);
            cursor: not-allowed;
        }
```

Perfeito! Agora todas as alterações solicitadas foram implementadas com sucesso. Vou fazer um resumo do que foi implementado:

## Resumo das Alterações Implementadas no Modal:

### 1. **Botão "Adicionar Mangá" posicionado à direita:**
- ✅ Criado um `modal-footer` com CSS para alinhar o botão à direita
- ✅ Aplicado tanto no modal de adicionar quanto no de editar

### 2. **Campo "Em Lançamento":**
- ✅ Adicionado checkbox "Em Lançamento" no modal
- ✅ Quando marcado, remove a obrigatoriedade do campo "Total de Capítulos"
- ✅ Campo fica desabilitado e mostra placeholder "N/A - Em lançamento"
- ✅ Aplicado tanto no modal de adicionar quanto no de editar

### 3. **Status "Completado":**
- ✅ Adicionado "Completado" na lista de status disponíveis
- ✅ Quando selecionado, remove a obrigatoriedade do campo "Capítulo Atual"
- ✅ Campo fica desabilitado e mostra placeholder "N/A - Completado"

### 4. **Validação Inteligente dos Campos:**
- ✅ Função JavaScript `toggleRequiredFields()` para controlar obrigatoriedade
- ✅ Função `toggleEditRequiredFields()` para o modal de edição
- ✅ Campos são automaticamente habilitados/desabilitados baseado nas seleções
- ✅ Validação PHP atualizada para processar os novos campos

### 5. **Melhorias na Interface:**
- ✅ Campos desabilitados têm estilo visual diferenciado
- ✅ Placeholders informativos para campos N/A
- ✅ Lógica aplicada tanto no modal de adicionar quanto no de editar
- ✅ Lista de mangás atualizada para mostrar "Em lançamento" quando aplicável

### 6. **Funcionalidades PHP Atualizadas:**
- ✅ Processamento do campo `em_lancamento` no backend
- ✅ Validação condicional baseada nos novos campos
- ✅ Suporte para mangás em lançamento e completados
- ✅ Compatibilidade com mangás existentes

A aplicação agora oferece uma experiência mais flexível e intuitiva para gerenciar diferentes tipos de mangás, seja em lançamento, completados, ou com status variados, mantendo a validação apropriada para cada situação.

