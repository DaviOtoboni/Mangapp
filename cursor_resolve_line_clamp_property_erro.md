# Resolve line-clamp property error
_Exported on 9/5/2025 at 00:46:13 GMT-3 from Cursor (1.5.9)_

---

**User**

For the code present, we get this error:
```
Also define the standard property 'line-clamp' for compatibility
```
How can I resolve this? If you propose a fix, please make it concise.

---

**Cursor**

O erro está relacionado ao uso de propriedades `-webkit-` sem a propriedade padrão correspondente. Para resolver isso, você precisa adicionar a propriedade `line-clamp` padrão junto com as propriedades `-webkit-`.


```css
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
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

/* SEÇÃO CORRIGIDA - Botões da navbar */
.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    background: var(--bg-primary);
    display: inline-block;
}

.nav-link:hover {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}

.nav-link.active {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(99, 102, 241, 0.2);
}

.theme-toggle {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.user-icon {
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.user-icon:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}
/* FIM DA SEÇÃO CORRIGIDA */

/* Main Content */
.main-content {
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background-color: var(--bg-primary);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    text-align: center;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-card.total .metric-value { color: var(--primary-color); }
.metric-card.lendo .metric-value { color: var(--success-color); }
.metric-card.pretendo .metric-value { color: var(--warning-color); }
.metric-card.abandonado .metric-value { color: var(--danger-color); }
.metric-card.finalizados .metric-value { color: var(--secondary-color); }

/* Add Manga Form */
.add-manga-section {
    background-color: var(--bg-primary);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input:disabled, .form-select:disabled {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
    border-color: var(--border-color) !important;
}

.form-input:disabled::placeholder, .form-select:disabled::placeholder {
    color: var(--text-secondary) !important;
    opacity: 0.8 !important;
}

.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary-color);
}

.submit-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* Manga List */
.manga-list {
    background-color: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.manga-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
}

.manga-list-title h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.manga-list-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.search-results {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 400;
}

.manga-list-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-inline {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    min-width: 200px;
}

.search-input-inline:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-btn-inline {
    padding: 0.5rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn-inline:hover {
    background-color: var(--primary-hover);
}

.add-manga-btn-inline {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.add-manga-btn-inline:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    color: var(--text-secondary);
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-btn:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.view-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
}

.manga-item {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

/* List Header */
.list-header {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.list-column {
    display: flex;
    align-items: center;
}

/* View Content */
.view-content {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.view-content.active {
    display: block !important;
    opacity: 1;
}

/* Manga Comment */
.manga-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.manga-item:last-child {
    border-bottom: none;
}

/* Blocks View */
.blocks-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
}

.block-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.block-status {
    font-size: 0.75rem;
}

.block-content {
    padding: 1rem;
}

.block-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.block-progress {
    margin-bottom: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.block-comment {
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: var(--bg-tertiary);
    border-radius: 0.5rem;
}

.comment-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.comment-text {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-top: 1px solid var(--border-color);
    justify-content: flex-end;
}

.manga-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.manga-details {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-lendo { background-color: #dcfce7; color: #166534; }
.status-pretendo { background-color: #fef3c7; color: #92400e; }
.status-abandonado { background-color: #fee2e2; color: #991b1b; }
.status-completado { background-color: #dbeafe; color: #1e40af; }

.progress-bar {
    width: 100px;
    height: 8px;
    background-color: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background-color: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background-color: #d97706;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
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
    max-width: 800px;
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    gap: 1rem;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.warning-text {
    color: var(--danger-color);
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-secondary {
    background-color: var(--text-secondary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

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

    .manga-list-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .manga-list-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .view-toggle {
        justify-content: center;
    }

    .search-form-inline {
        width: 100%;
    }

    .search-input-inline {
        min-width: auto;
        flex: 1;
    }

    .blocks-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .manga-block {
        max-width: 100%;
    }
    
    .block-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .block-actions {
        justify-content: center;
    }

    .list-header {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
        display: none; /* Hide header on mobile */
    }

    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        text-align: center;
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

@media (max-width: 1024px) and (min-width: 769px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .manga-list-actions {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .search-input-inline {
        min-width: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .page-title {
        font-size: 2rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .view-toggle {
        width: 100%;
    }
    
    .view-btn {
        flex: 1;
        justify-content: center;
    }
}

/* ===== MANGA COVERS ===== */
.manga-cover {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.manga-block:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder span {
    font-size: 0.75rem;
    font-weight: 500;
}

.api-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: var(--shadow);
}

/* ===== MANGA COVERS - LIST VIEW ===== */
.manga-cover-small {
    width: 60px;
    height: 80px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
    margin-right: 1rem;
}

.cover-image-small {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder-small {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder-small i {
    font-size: 1.25rem;
    opacity: 0.5;
}

.manga-text-info {
    flex: 1;
}

/* ===== COVER UPLOAD ===== */
.cover-upload-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-file {
    display: none;
}

.cover-preview {
    width: 120px;
    height: 160px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.cover-placeholder-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    text-align: center;
    padding: 1rem;
}

.cover-placeholder-upload i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder-upload span {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-primary);
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== IMPROVED MANGA BLOCKS ===== */
.blocks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.manga-block {
    background: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: fit-content;
}

.manga-block:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.manga-block.imported-from-api {
    border-left: 4px solid #667eea;
}

.block-content {
    padding: 1rem;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.block-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.block-status {
    flex-shrink: 0;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.block-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
}

.detail-item {
    font-size: 0.875rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-item i {
    width: 14px;
    text-align: center;
    opacity: 0.7;
}

.block-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background-color: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 35px;
    text-align: right;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.block-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

/* ===== IMPROVED LIST VIEW ===== */
.manga-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.list-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.manga-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    transition: all 0.2s ease;
}

.manga-item:hover {
    background: var(--bg-secondary);
}

.manga-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-lendo {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pretendo {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-abandonado {
    background-color: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-completado {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* ===== API INDICATOR ===== */
.api-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .manga-cover {
        height: 160px;
    }
    
    .list-header,
    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .manga-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .manga-cover-small {
        width: 50px;
        height: 70px;
        margin-right: 0.75rem;
    }
}

@media (max-width: 480px) {
    .blocks-grid {
        grid-template-columns: 1fr;
    }
    
    .cover-preview {
        width: 100px;
        height: 130px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.manga-block {
    animation: fadeIn 0.3s ease;
}

.manga-item {
    animation: fadeIn 0.2s ease;
}

/* ===== LOADING STATES ===== */
.cover-loading {
    background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===== FORM IMPROVEMENTS ===== */
.form-group-full {
    grid-column: 1 / -1;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Layout Customizado */
.modal-form-layout {
    display: flex;
    gap: 2rem;
    align-items: stretch;
    min-height: 500px;
}

.modal-left {
    flex: 0 0 250px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-left .form-group {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-upload-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-preview {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-left .cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-row {
    display: flex;
    gap: 2rem;
    align-items: center;
    margin-top: 1rem;
}

.checkbox-row .checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsividade para o modal */
@media (max-width: 768px) {
    .modal-form-layout {
        flex-direction: column;
        gap: 1rem;
        min-height: auto;
    }
    
    .modal-left {
        flex: none;
        align-self: center;
    }
    
    .modal-left .cover-preview {
        height: 250px;
        width: 200px;
    }
    
    .checkbox-row {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

/* Garantir que o input file seja invisível mas funcional */
#addCoverImage {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
```

Pronto! Adicionei a propriedade `line-clamp: 2;` padrão junto com a propriedade `-webkit-line-clamp: 2;`. Isso resolve o aviso de compatibilidade, pois agora você tem tanto a versão prefixada do WebKit quanto a propriedade padrão, garantindo melhor compatibilidade entre navegadores.

---

**User**

Colocar o sombreamento dos botões da navbar, somente na parte de baixo do elemento

---

**Cursor**

Vou modificar o sombreamento dos botões da navbar para que apareça apenas na parte inferior dos elementos.


```css
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
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

/* SEÇÃO CORRIGIDA - Botões da navbar */
.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    background: var(--bg-primary);
    display: inline-block;
}

.nav-link:hover {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}

.nav-link.active {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(99, 102, 241, 0.2);
}

.theme-toggle {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.user-icon {
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.user-icon:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}
/* FIM DA SEÇÃO CORRIGIDA */

/* Main Content */
.main-content {
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background-color: var(--bg-primary);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    text-align: center;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-card.total .metric-value { color: var(--primary-color); }
.metric-card.lendo .metric-value { color: var(--success-color); }
.metric-card.pretendo .metric-value { color: var(--warning-color); }
.metric-card.abandonado .metric-value { color: var(--danger-color); }
.metric-card.finalizados .metric-value { color: var(--secondary-color); }

/* Add Manga Form */
.add-manga-section {
    background-color: var(--bg-primary);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input:disabled, .form-select:disabled {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
    border-color: var(--border-color) !important;
}

.form-input:disabled::placeholder, .form-select:disabled::placeholder {
    color: var(--text-secondary) !important;
    opacity: 0.8 !important;
}

.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary-color);
}

.submit-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* Manga List */
.manga-list {
    background-color: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.manga-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
}

.manga-list-title h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.manga-list-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.search-results {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 400;
}

.manga-list-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-inline {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    min-width: 200px;
}

.search-input-inline:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-btn-inline {
    padding: 0.5rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn-inline:hover {
    background-color: var(--primary-hover);
}

.add-manga-btn-inline {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.add-manga-btn-inline:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    color: var(--text-secondary);
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-btn:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.view-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
}

.manga-item {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

/* List Header */
.list-header {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.list-column {
    display: flex;
    align-items: center;
}

/* View Content */
.view-content {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.view-content.active {
    display: block !important;
    opacity: 1;
}

/* Manga Comment */
.manga-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.manga-item:last-child {
    border-bottom: none;
}

/* Blocks View */
.blocks-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
}

.block-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.block-status {
    font-size: 0.75rem;
}

.block-content {
    padding: 1rem;
}

.block-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.block-progress {
    margin-bottom: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.block-comment {
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: var(--bg-tertiary);
    border-radius: 0.5rem;
}

.comment-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.comment-text {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-top: 1px solid var(--border-color);
    justify-content: flex-end;
}

.manga-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.manga-details {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-lendo { background-color: #dcfce7; color: #166534; }
.status-pretendo { background-color: #fef3c7; color: #92400e; }
.status-abandonado { background-color: #fee2e2; color: #991b1b; }
.status-completado { background-color: #dbeafe; color: #1e40af; }

.progress-bar {
    width: 100px;
    height: 8px;
    background-color: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background-color: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background-color: #d97706;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
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
    max-width: 800px;
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    gap: 1rem;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.warning-text {
    color: var(--danger-color);
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-secondary {
    background-color: var(--text-secondary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

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

    .manga-list-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .manga-list-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .view-toggle {
        justify-content: center;
    }

    .search-form-inline {
        width: 100%;
    }

    .search-input-inline {
        min-width: auto;
        flex: 1;
    }

    .blocks-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .manga-block {
        max-width: 100%;
    }
    
    .block-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .block-actions {
        justify-content: center;
    }

    .list-header {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
        display: none; /* Hide header on mobile */
    }

    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        text-align: center;
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

@media (max-width: 1024px) and (min-width: 769px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .manga-list-actions {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .search-input-inline {
        min-width: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .page-title {
        font-size: 2rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .view-toggle {
        width: 100%;
    }
    
    .view-btn {
        flex: 1;
        justify-content: center;
    }
}

/* ===== MANGA COVERS ===== */
.manga-cover {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.manga-block:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder span {
    font-size: 0.75rem;
    font-weight: 500;
}

.api-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: var(--shadow);
}

/* ===== MANGA COVERS - LIST VIEW ===== */
.manga-cover-small {
    width: 60px;
    height: 80px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
    margin-right: 1rem;
}

.cover-image-small {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder-small {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder-small i {
    font-size: 1.25rem;
    opacity: 0.5;
}

.manga-text-info {
    flex: 1;
}

/* ===== COVER UPLOAD ===== */
.cover-upload-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-file {
    display: none;
}

.cover-preview {
    width: 120px;
    height: 160px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.cover-placeholder-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    text-align: center;
    padding: 1rem;
}

.cover-placeholder-upload i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder-upload span {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-primary);
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== IMPROVED MANGA BLOCKS ===== */
.blocks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.manga-block {
    background: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: fit-content;
}

.manga-block:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.manga-block.imported-from-api {
    border-left: 4px solid #667eea;
}

.block-content {
    padding: 1rem;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.block-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.block-status {
    flex-shrink: 0;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.block-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
}

.detail-item {
    font-size: 0.875rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-item i {
    width: 14px;
    text-align: center;
    opacity: 0.7;
}

.block-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background-color: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 35px;
    text-align: right;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.block-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

/* ===== IMPROVED LIST VIEW ===== */
.manga-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.list-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.manga-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    transition: all 0.2s ease;
}

.manga-item:hover {
    background: var(--bg-secondary);
}

.manga-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-lendo {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pretendo {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-abandonado {
    background-color: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-completado {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* ===== API INDICATOR ===== */
.api-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .manga-cover {
        height: 160px;
    }
    
    .list-header,
    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .manga-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .manga-cover-small {
        width: 50px;
        height: 70px;
        margin-right: 0.75rem;
    }
}

@media (max-width: 480px) {
    .blocks-grid {
        grid-template-columns: 1fr;
    }
    
    .cover-preview {
        width: 100px;
        height: 130px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.manga-block {
    animation: fadeIn 0.3s ease;
}

.manga-item {
    animation: fadeIn 0.2s ease;
}

/* ===== LOADING STATES ===== */
.cover-loading {
    background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===== FORM IMPROVEMENTS ===== */
.form-group-full {
    grid-column: 1 / -1;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Layout Customizado */
.modal-form-layout {
    display: flex;
    gap: 2rem;
    align-items: stretch;
    min-height: 500px;
}

.modal-left {
    flex: 0 0 250px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-left .form-group {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-upload-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-preview {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-left .cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-row {
    display: flex;
    gap: 2rem;
    align-items: center;
    margin-top: 1rem;
}

.checkbox-row .checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsividade para o modal */
@media (max-width: 768px) {
    .modal-form-layout {
        flex-direction: column;
        gap: 1rem;
        min-height: auto;
    }
    
    .modal-left {
        flex: none;
        align-self: center;
    }
    
    .modal-left .cover-preview {
        height: 250px;
        width: 200px;
    }
    
    .checkbox-row {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

/* Garantir que o input file seja invisível mas funcional */
#addCoverImage {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
```

```css
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
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

/* SEÇÃO CORRIGIDA - Botões da navbar */
.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    background: var(--bg-primary);
    display: inline-block;
}

.nav-link:hover {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}

.nav-link.active {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(99, 102, 241, 0.2);
}

.theme-toggle {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.user-icon {
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.user-icon:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}
/* FIM DA SEÇÃO CORRIGIDA */

/* Main Content */
.main-content {
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background-color: var(--bg-primary);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    text-align: center;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-card.total .metric-value { color: var(--primary-color); }
.metric-card.lendo .metric-value { color: var(--success-color); }
.metric-card.pretendo .metric-value { color: var(--warning-color); }
.metric-card.abandonado .metric-value { color: var(--danger-color); }
.metric-card.finalizados .metric-value { color: var(--secondary-color); }

/* Add Manga Form */
.add-manga-section {
    background-color: var(--bg-primary);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input:disabled, .form-select:disabled {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
    border-color: var(--border-color) !important;
}

.form-input:disabled::placeholder, .form-select:disabled::placeholder {
    color: var(--text-secondary) !important;
    opacity: 0.8 !important;
}

.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary-color);
}

.submit-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* Manga List */
.manga-list {
    background-color: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.manga-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
}

.manga-list-title h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.manga-list-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.search-results {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 400;
}

.manga-list-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-inline {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    min-width: 200px;
}

.search-input-inline:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-btn-inline {
    padding: 0.5rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn-inline:hover {
    background-color: var(--primary-hover);
}

.add-manga-btn-inline {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.add-manga-btn-inline:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    color: var(--text-secondary);
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-btn:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.view-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
}

.manga-item {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

/* List Header */
.list-header {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.list-column {
    display: flex;
    align-items: center;
}

/* View Content */
.view-content {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.view-content.active {
    display: block !important;
    opacity: 1;
}

/* Manga Comment */
.manga-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.manga-item:last-child {
    border-bottom: none;
}

/* Blocks View */
.blocks-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
}

.block-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.block-status {
    font-size: 0.75rem;
}

.block-content {
    padding: 1rem;
}

.block-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.block-progress {
    margin-bottom: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.block-comment {
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: var(--bg-tertiary);
    border-radius: 0.5rem;
}

.comment-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.comment-text {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-top: 1px solid var(--border-color);
    justify-content: flex-end;
}

.manga-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.manga-details {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-lendo { background-color: #dcfce7; color: #166534; }
.status-pretendo { background-color: #fef3c7; color: #92400e; }
.status-abandonado { background-color: #fee2e2; color: #991b1b; }
.status-completado { background-color: #dbeafe; color: #1e40af; }

.progress-bar {
    width: 100px;
    height: 8px;
    background-color: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background-color: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background-color: #d97706;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
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
    max-width: 800px;
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    gap: 1rem;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.warning-text {
    color: var(--danger-color);
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-secondary {
    background-color: var(--text-secondary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

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

    .manga-list-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .manga-list-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .view-toggle {
        justify-content: center;
    }

    .search-form-inline {
        width: 100%;
    }

    .search-input-inline {
        min-width: auto;
        flex: 1;
    }

    .blocks-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .manga-block {
        max-width: 100%;
    }
    
    .block-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .block-actions {
        justify-content: center;
    }

    .list-header {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
        display: none; /* Hide header on mobile */
    }

    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        text-align: center;
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

@media (max-width: 1024px) and (min-width: 769px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .manga-list-actions {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .search-input-inline {
        min-width: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .page-title {
        font-size: 2rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .view-toggle {
        width: 100%;
    }
    
    .view-btn {
        flex: 1;
        justify-content: center;
    }
}

/* ===== MANGA COVERS ===== */
.manga-cover {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.manga-block:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder span {
    font-size: 0.75rem;
    font-weight: 500;
}

.api-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: var(--shadow);
}

/* ===== MANGA COVERS - LIST VIEW ===== */
.manga-cover-small {
    width: 60px;
    height: 80px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
    margin-right: 1rem;
}

.cover-image-small {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder-small {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder-small i {
    font-size: 1.25rem;
    opacity: 0.5;
}

.manga-text-info {
    flex: 1;
}

/* ===== COVER UPLOAD ===== */
.cover-upload-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-file {
    display: none;
}

.cover-preview {
    width: 120px;
    height: 160px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.cover-placeholder-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    text-align: center;
    padding: 1rem;
}

.cover-placeholder-upload i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder-upload span {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-primary);
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== IMPROVED MANGA BLOCKS ===== */
.blocks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.manga-block {
    background: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: fit-content;
}

.manga-block:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.manga-block.imported-from-api {
    border-left: 4px solid #667eea;
}

.block-content {
    padding: 1rem;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.block-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.block-status {
    flex-shrink: 0;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.block-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
}

.detail-item {
    font-size: 0.875rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-item i {
    width: 14px;
    text-align: center;
    opacity: 0.7;
}

.block-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background-color: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 35px;
    text-align: right;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.block-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

/* ===== IMPROVED LIST VIEW ===== */
.manga-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.list-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.manga-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    transition: all 0.2s ease;
}

.manga-item:hover {
    background: var(--bg-secondary);
}

.manga-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-lendo {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pretendo {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-abandonado {
    background-color: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-completado {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* ===== API INDICATOR ===== */
.api-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .manga-cover {
        height: 160px;
    }
    
    .list-header,
    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .manga-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .manga-cover-small {
        width: 50px;
        height: 70px;
        margin-right: 0.75rem;
    }
}

@media (max-width: 480px) {
    .blocks-grid {
        grid-template-columns: 1fr;
    }
    
    .cover-preview {
        width: 100px;
        height: 130px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.manga-block {
    animation: fadeIn 0.3s ease;
}

.manga-item {
    animation: fadeIn 0.2s ease;
}

/* ===== LOADING STATES ===== */
.cover-loading {
    background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===== FORM IMPROVEMENTS ===== */
.form-group-full {
    grid-column: 1 / -1;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Layout Customizado */
.modal-form-layout {
    display: flex;
    gap: 2rem;
    align-items: stretch;
    min-height: 500px;
}

.modal-left {
    flex: 0 0 250px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-left .form-group {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-upload-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-preview {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-left .cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-row {
    display: flex;
    gap: 2rem;
    align-items: center;
    margin-top: 1rem;
}

.checkbox-row .checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsividade para o modal */
@media (max-width: 768px) {
    .modal-form-layout {
        flex-direction: column;
        gap: 1rem;
        min-height: auto;
    }
    
    .modal-left {
        flex: none;
        align-self: center;
    }
    
    .modal-left .cover-preview {
        height: 250px;
        width: 200px;
    }
    
    .checkbox-row {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

/* Garantir que o input file seja invisível mas funcional */
#addCoverImage {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
```

```css
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
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

/* SEÇÃO CORRIGIDA - Botões da navbar */
.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    background: var(--bg-primary);
    display: inline-block;
}

.nav-link:hover {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}

.nav-link.active {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(99, 102, 241, 0.2);
}

.theme-toggle {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.user-icon {
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.user-icon:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}
/* FIM DA SEÇÃO CORRIGIDA */

/* Main Content */
.main-content {
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background-color: var(--bg-primary);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    text-align: center;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-card.total .metric-value { color: var(--primary-color); }
.metric-card.lendo .metric-value { color: var(--success-color); }
.metric-card.pretendo .metric-value { color: var(--warning-color); }
.metric-card.abandonado .metric-value { color: var(--danger-color); }
.metric-card.finalizados .metric-value { color: var(--secondary-color); }

/* Add Manga Form */
.add-manga-section {
    background-color: var(--bg-primary);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input:disabled, .form-select:disabled {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
    border-color: var(--border-color) !important;
}

.form-input:disabled::placeholder, .form-select:disabled::placeholder {
    color: var(--text-secondary) !important;
    opacity: 0.8 !important;
}

.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary-color);
}

.submit-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* Manga List */
.manga-list {
    background-color: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.manga-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
}

.manga-list-title h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.manga-list-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.search-results {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 400;
}

.manga-list-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-inline {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    min-width: 200px;
}

.search-input-inline:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-btn-inline {
    padding: 0.5rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn-inline:hover {
    background-color: var(--primary-hover);
}

.add-manga-btn-inline {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.add-manga-btn-inline:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    color: var(--text-secondary);
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-btn:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.view-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
}

.manga-item {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

/* List Header */
.list-header {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.list-column {
    display: flex;
    align-items: center;
}

/* View Content */
.view-content {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.view-content.active {
    display: block !important;
    opacity: 1;
}

/* Manga Comment */
.manga-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.manga-item:last-child {
    border-bottom: none;
}

/* Blocks View */
.blocks-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
}

.block-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.block-status {
    font-size: 0.75rem;
}

.block-content {
    padding: 1rem;
}

.block-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.block-progress {
    margin-bottom: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.block-comment {
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: var(--bg-tertiary);
    border-radius: 0.5rem;
}

.comment-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.comment-text {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-top: 1px solid var(--border-color);
    justify-content: flex-end;
}

.manga-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.manga-details {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-lendo { background-color: #dcfce7; color: #166534; }
.status-pretendo { background-color: #fef3c7; color: #92400e; }
.status-abandonado { background-color: #fee2e2; color: #991b1b; }
.status-completado { background-color: #dbeafe; color: #1e40af; }

.progress-bar {
    width: 100px;
    height: 8px;
    background-color: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background-color: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background-color: #d97706;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
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
    max-width: 800px;
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    gap: 1rem;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.warning-text {
    color: var(--danger-color);
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-secondary {
    background-color: var(--text-secondary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

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

    .manga-list-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .manga-list-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .view-toggle {
        justify-content: center;
    }

    .search-form-inline {
        width: 100%;
    }

    .search-input-inline {
        min-width: auto;
        flex: 1;
    }

    .blocks-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .manga-block {
        max-width: 100%;
    }
    
    .block-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .block-actions {
        justify-content: center;
    }

    .list-header {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
        display: none; /* Hide header on mobile */
    }

    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        text-align: center;
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

@media (max-width: 1024px) and (min-width: 769px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .manga-list-actions {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .search-input-inline {
        min-width: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .page-title {
        font-size: 2rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .view-toggle {
        width: 100%;
    }
    
    .view-btn {
        flex: 1;
        justify-content: center;
    }
}

/* ===== MANGA COVERS ===== */
.manga-cover {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.manga-block:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder span {
    font-size: 0.75rem;
    font-weight: 500;
}

.api-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: var(--shadow);
}

/* ===== MANGA COVERS - LIST VIEW ===== */
.manga-cover-small {
    width: 60px;
    height: 80px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
    margin-right: 1rem;
}

.cover-image-small {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder-small {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder-small i {
    font-size: 1.25rem;
    opacity: 0.5;
}

.manga-text-info {
    flex: 1;
}

/* ===== COVER UPLOAD ===== */
.cover-upload-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-file {
    display: none;
}

.cover-preview {
    width: 120px;
    height: 160px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.cover-placeholder-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    text-align: center;
    padding: 1rem;
}

.cover-placeholder-upload i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder-upload span {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-primary);
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== IMPROVED MANGA BLOCKS ===== */
.blocks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.manga-block {
    background: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: fit-content;
}

.manga-block:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.manga-block.imported-from-api {
    border-left: 4px solid #667eea;
}

.block-content {
    padding: 1rem;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.block-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.block-status {
    flex-shrink: 0;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.block-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
}

.detail-item {
    font-size: 0.875rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-item i {
    width: 14px;
    text-align: center;
    opacity: 0.7;
}

.block-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background-color: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 35px;
    text-align: right;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.block-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

/* ===== IMPROVED LIST VIEW ===== */
.manga-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.list-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.manga-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    transition: all 0.2s ease;
}

.manga-item:hover {
    background: var(--bg-secondary);
}

.manga-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-lendo {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pretendo {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-abandonado {
    background-color: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-completado {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* ===== API INDICATOR ===== */
.api-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .manga-cover {
        height: 160px;
    }
    
    .list-header,
    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .manga-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .manga-cover-small {
        width: 50px;
        height: 70px;
        margin-right: 0.75rem;
    }
}

@media (max-width: 480px) {
    .blocks-grid {
        grid-template-columns: 1fr;
    }
    
    .cover-preview {
        width: 100px;
        height: 130px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.manga-block {
    animation: fadeIn 0.3s ease;
}

.manga-item {
    animation: fadeIn 0.2s ease;
}

/* ===== LOADING STATES ===== */
.cover-loading {
    background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===== FORM IMPROVEMENTS ===== */
.form-group-full {
    grid-column: 1 / -1;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Layout Customizado */
.modal-form-layout {
    display: flex;
    gap: 2rem;
    align-items: stretch;
    min-height: 500px;
}

.modal-left {
    flex: 0 0 250px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-left .form-group {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-upload-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-preview {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-left .cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-row {
    display: flex;
    gap: 2rem;
    align-items: center;
    margin-top: 1rem;
}

.checkbox-row .checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsividade para o modal */
@media (max-width: 768px) {
    .modal-form-layout {
        flex-direction: column;
        gap: 1rem;
        min-height: auto;
    }
    
    .modal-left {
        flex: none;
        align-self: center;
    }
    
    .modal-left .cover-preview {
        height: 250px;
        width: 200px;
    }
    
    .checkbox-row {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

/* Garantir que o input file seja invisível mas funcional */
#addCoverImage {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
```

```css
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
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

/* SEÇÃO CORRIGIDA - Botões da navbar */
.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    background: var(--bg-primary);
    display: inline-block;
}

.nav-link:hover {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}

.nav-link.active {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(99, 102, 241, 0.2);
}

.theme-toggle {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.user-icon {
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.user-icon:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}
/* FIM DA SEÇÃO CORRIGIDA */

/* Main Content */
.main-content {
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background-color: var(--bg-primary);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    text-align: center;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-card.total .metric-value { color: var(--primary-color); }
.metric-card.lendo .metric-value { color: var(--success-color); }
.metric-card.pretendo .metric-value { color: var(--warning-color); }
.metric-card.abandonado .metric-value { color: var(--danger-color); }
.metric-card.finalizados .metric-value { color: var(--secondary-color); }

/* Add Manga Form */
.add-manga-section {
    background-color: var(--bg-primary);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input:disabled, .form-select:disabled {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
    border-color: var(--border-color) !important;
}

.form-input:disabled::placeholder, .form-select:disabled::placeholder {
    color: var(--text-secondary) !important;
    opacity: 0.8 !important;
}

.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary-color);
}

.submit-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* Manga List */
.manga-list {
    background-color: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.manga-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
}

.manga-list-title h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.manga-list-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.search-results {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 400;
}

.manga-list-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-inline {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    min-width: 200px;
}

.search-input-inline:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-btn-inline {
    padding: 0.5rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn-inline:hover {
    background-color: var(--primary-hover);
}

.add-manga-btn-inline {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.add-manga-btn-inline:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    color: var(--text-secondary);
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-btn:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.view-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
}

.manga-item {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

/* List Header */
.list-header {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.list-column {
    display: flex;
    align-items: center;
}

/* View Content */
.view-content {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.view-content.active {
    display: block !important;
    opacity: 1;
}

/* Manga Comment */
.manga-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.manga-item:last-child {
    border-bottom: none;
}

/* Blocks View */
.blocks-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
}

.block-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.block-status {
    font-size: 0.75rem;
}

.block-content {
    padding: 1rem;
}

.block-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.block-progress {
    margin-bottom: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.block-comment {
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: var(--bg-tertiary);
    border-radius: 0.5rem;
}

.comment-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.comment-text {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-top: 1px solid var(--border-color);
    justify-content: flex-end;
}

.manga-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.manga-details {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-lendo { background-color: #dcfce7; color: #166534; }
.status-pretendo { background-color: #fef3c7; color: #92400e; }
.status-abandonado { background-color: #fee2e2; color: #991b1b; }
.status-completado { background-color: #dbeafe; color: #1e40af; }

.progress-bar {
    width: 100px;
    height: 8px;
    background-color: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background-color: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background-color: #d97706;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
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
    max-width: 800px;
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    gap: 1rem;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.warning-text {
    color: var(--danger-color);
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-secondary {
    background-color: var(--text-secondary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

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

    .manga-list-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .manga-list-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .view-toggle {
        justify-content: center;
    }

    .search-form-inline {
        width: 100%;
    }

    .search-input-inline {
        min-width: auto;
        flex: 1;
    }

    .blocks-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .manga-block {
        max-width: 100%;
    }
    
    .block-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .block-actions {
        justify-content: center;
    }

    .list-header {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
        display: none; /* Hide header on mobile */
    }

    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        text-align: center;
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

@media (max-width: 1024px) and (min-width: 769px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .manga-list-actions {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .search-input-inline {
        min-width: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .page-title {
        font-size: 2rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .view-toggle {
        width: 100%;
    }
    
    .view-btn {
        flex: 1;
        justify-content: center;
    }
}

/* ===== MANGA COVERS ===== */
.manga-cover {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.manga-block:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder span {
    font-size: 0.75rem;
    font-weight: 500;
}

.api-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: var(--shadow);
}

/* ===== MANGA COVERS - LIST VIEW ===== */
.manga-cover-small {
    width: 60px;
    height: 80px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
    margin-right: 1rem;
}

.cover-image-small {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder-small {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder-small i {
    font-size: 1.25rem;
    opacity: 0.5;
}

.manga-text-info {
    flex: 1;
}

/* ===== COVER UPLOAD ===== */
.cover-upload-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-file {
    display: none;
}

.cover-preview {
    width: 120px;
    height: 160px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.cover-placeholder-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    text-align: center;
    padding: 1rem;
}

.cover-placeholder-upload i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder-upload span {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-primary);
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== IMPROVED MANGA BLOCKS ===== */
.blocks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.manga-block {
    background: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: fit-content;
}

.manga-block:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.manga-block.imported-from-api {
    border-left: 4px solid #667eea;
}

.block-content {
    padding: 1rem;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.block-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.block-status {
    flex-shrink: 0;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.block-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
}

.detail-item {
    font-size: 0.875rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-item i {
    width: 14px;
    text-align: center;
    opacity: 0.7;
}

.block-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background-color: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 35px;
    text-align: right;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.block-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

/* ===== IMPROVED LIST VIEW ===== */
.manga-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.list-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.manga-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    transition: all 0.2s ease;
}

.manga-item:hover {
    background: var(--bg-secondary);
}

.manga-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-lendo {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pretendo {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-abandonado {
    background-color: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-completado {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* ===== API INDICATOR ===== */
.api-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .manga-cover {
        height: 160px;
    }
    
    .list-header,
    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .manga-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .manga-cover-small {
        width: 50px;
        height: 70px;
        margin-right: 0.75rem;
    }
}

@media (max-width: 480px) {
    .blocks-grid {
        grid-template-columns: 1fr;
    }
    
    .cover-preview {
        width: 100px;
        height: 130px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.manga-block {
    animation: fadeIn 0.3s ease;
}

.manga-item {
    animation: fadeIn 0.2s ease;
}

/* ===== LOADING STATES ===== */
.cover-loading {
    background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===== FORM IMPROVEMENTS ===== */
.form-group-full {
    grid-column: 1 / -1;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Layout Customizado */
.modal-form-layout {
    display: flex;
    gap: 2rem;
    align-items: stretch;
    min-height: 500px;
}

.modal-left {
    flex: 0 0 250px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-left .form-group {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-upload-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-preview {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-left .cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-row {
    display: flex;
    gap: 2rem;
    align-items: center;
    margin-top: 1rem;
}

.checkbox-row .checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsividade para o modal */
@media (max-width: 768px) {
    .modal-form-layout {
        flex-direction: column;
        gap: 1rem;
        min-height: auto;
    }
    
    .modal-left {
        flex: none;
        align-self: center;
    }
    
    .modal-left .cover-preview {
        height: 250px;
        width: 200px;
    }
    
    .checkbox-row {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

/* Garantir que o input file seja invisível mas funcional */
#addCoverImage {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
```

```css
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
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

/* SEÇÃO CORRIGIDA - Botões da navbar */
.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    background: var(--bg-primary);
    display: inline-block;
}

.nav-link:hover {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}

.nav-link.active {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(99, 102, 241, 0.2);
}

.theme-toggle {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.user-icon {
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.user-icon:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}
/* FIM DA SEÇÃO CORRIGIDA */

/* Main Content */
.main-content {
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background-color: var(--bg-primary);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    text-align: center;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-card.total .metric-value { color: var(--primary-color); }
.metric-card.lendo .metric-value { color: var(--success-color); }
.metric-card.pretendo .metric-value { color: var(--warning-color); }
.metric-card.abandonado .metric-value { color: var(--danger-color); }
.metric-card.finalizados .metric-value { color: var(--secondary-color); }

/* Add Manga Form */
.add-manga-section {
    background-color: var(--bg-primary);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input:disabled, .form-select:disabled {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
    border-color: var(--border-color) !important;
}

.form-input:disabled::placeholder, .form-select:disabled::placeholder {
    color: var(--text-secondary) !important;
    opacity: 0.8 !important;
}

.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary-color);
}

.submit-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* Manga List */
.manga-list {
    background-color: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.manga-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
}

.manga-list-title h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.manga-list-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.search-results {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 400;
}

.manga-list-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-inline {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    min-width: 200px;
}

.search-input-inline:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-btn-inline {
    padding: 0.5rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn-inline:hover {
    background-color: var(--primary-hover);
}

.add-manga-btn-inline {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.add-manga-btn-inline:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    color: var(--text-secondary);
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-btn:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.view-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
}

.manga-item {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

/* List Header */
.list-header {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.list-column {
    display: flex;
    align-items: center;
}

/* View Content */
.view-content {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.view-content.active {
    display: block !important;
    opacity: 1;
}

/* Manga Comment */
.manga-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.manga-item:last-child {
    border-bottom: none;
}

/* Blocks View */
.blocks-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
}

.block-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.block-status {
    font-size: 0.75rem;
}

.block-content {
    padding: 1rem;
}

.block-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.block-progress {
    margin-bottom: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.block-comment {
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: var(--bg-tertiary);
    border-radius: 0.5rem;
}

.comment-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.comment-text {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-top: 1px solid var(--border-color);
    justify-content: flex-end;
}

.manga-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.manga-details {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-lendo { background-color: #dcfce7; color: #166534; }
.status-pretendo { background-color: #fef3c7; color: #92400e; }
.status-abandonado { background-color: #fee2e2; color: #991b1b; }
.status-completado { background-color: #dbeafe; color: #1e40af; }

.progress-bar {
    width: 100px;
    height: 8px;
    background-color: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background-color: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background-color: #d97706;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
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
    max-width: 800px;
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    gap: 1rem;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.warning-text {
    color: var(--danger-color);
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-secondary {
    background-color: var(--text-secondary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

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

    .manga-list-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .manga-list-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .view-toggle {
        justify-content: center;
    }

    .search-form-inline {
        width: 100%;
    }

    .search-input-inline {
        min-width: auto;
        flex: 1;
    }

    .blocks-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .manga-block {
        max-width: 100%;
    }
    
    .block-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .block-actions {
        justify-content: center;
    }

    .list-header {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
        display: none; /* Hide header on mobile */
    }

    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        text-align: center;
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

@media (max-width: 1024px) and (min-width: 769px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .manga-list-actions {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .search-input-inline {
        min-width: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .page-title {
        font-size: 2rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .view-toggle {
        width: 100%;
    }
    
    .view-btn {
        flex: 1;
        justify-content: center;
    }
}

/* ===== MANGA COVERS ===== */
.manga-cover {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.manga-block:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder span {
    font-size: 0.75rem;
    font-weight: 500;
}

.api-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: var(--shadow);
}

/* ===== MANGA COVERS - LIST VIEW ===== */
.manga-cover-small {
    width: 60px;
    height: 80px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
    margin-right: 1rem;
}

.cover-image-small {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder-small {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder-small i {
    font-size: 1.25rem;
    opacity: 0.5;
}

.manga-text-info {
    flex: 1;
}

/* ===== COVER UPLOAD ===== */
.cover-upload-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-file {
    display: none;
}

.cover-preview {
    width: 120px;
    height: 160px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.cover-placeholder-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    text-align: center;
    padding: 1rem;
}

.cover-placeholder-upload i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder-upload span {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-primary);
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== IMPROVED MANGA BLOCKS ===== */
.blocks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.manga-block {
    background: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: fit-content;
}

.manga-block:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.manga-block.imported-from-api {
    border-left: 4px solid #667eea;
}

.block-content {
    padding: 1rem;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.block-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.block-status {
    flex-shrink: 0;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.block-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
}

.detail-item {
    font-size: 0.875rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-item i {
    width: 14px;
    text-align: center;
    opacity: 0.7;
}

.block-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background-color: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 35px;
    text-align: right;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.block-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

/* ===== IMPROVED LIST VIEW ===== */
.manga-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.list-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.manga-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    transition: all 0.2s ease;
}

.manga-item:hover {
    background: var(--bg-secondary);
}

.manga-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-lendo {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pretendo {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-abandonado {
    background-color: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-completado {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* ===== API INDICATOR ===== */
.api-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .manga-cover {
        height: 160px;
    }
    
    .list-header,
    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .manga-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .manga-cover-small {
        width: 50px;
        height: 70px;
        margin-right: 0.75rem;
    }
}

@media (max-width: 480px) {
    .blocks-grid {
        grid-template-columns: 1fr;
    }
    
    .cover-preview {
        width: 100px;
        height: 130px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.manga-block {
    animation: fadeIn 0.3s ease;
}

.manga-item {
    animation: fadeIn 0.2s ease;
}

/* ===== LOADING STATES ===== */
.cover-loading {
    background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===== FORM IMPROVEMENTS ===== */
.form-group-full {
    grid-column: 1 / -1;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Layout Customizado */
.modal-form-layout {
    display: flex;
    gap: 2rem;
    align-items: stretch;
    min-height: 500px;
}

.modal-left {
    flex: 0 0 250px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-left .form-group {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-upload-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-preview {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-left .cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-row {
    display: flex;
    gap: 2rem;
    align-items: center;
    margin-top: 1rem;
}

.checkbox-row .checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsividade para o modal */
@media (max-width: 768px) {
    .modal-form-layout {
        flex-direction: column;
        gap: 1rem;
        min-height: auto;
    }
    
    .modal-left {
        flex: none;
        align-self: center;
    }
    
    .modal-left .cover-preview {
        height: 250px;
        width: 200px;
    }
    
    .checkbox-row {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

/* Garantir que o input file seja invisível mas funcional */
#addCoverImage {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
```

```css
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
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

/* SEÇÃO CORRIGIDA - Botões da navbar */
.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    background: var(--bg-primary);
    display: inline-block;
}

.nav-link:hover {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}

.nav-link.active {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(99, 102, 241, 0.2);
}

.theme-toggle {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.user-icon {
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.user-icon:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}
/* FIM DA SEÇÃO CORRIGIDA */

/* Main Content */
.main-content {
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background-color: var(--bg-primary);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    text-align: center;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-card.total .metric-value { color: var(--primary-color); }
.metric-card.lendo .metric-value { color: var(--success-color); }
.metric-card.pretendo .metric-value { color: var(--warning-color); }
.metric-card.abandonado .metric-value { color: var(--danger-color); }
.metric-card.finalizados .metric-value { color: var(--secondary-color); }

/* Add Manga Form */
.add-manga-section {
    background-color: var(--bg-primary);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input:disabled, .form-select:disabled {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
    border-color: var(--border-color) !important;
}

.form-input:disabled::placeholder, .form-select:disabled::placeholder {
    color: var(--text-secondary) !important;
    opacity: 0.8 !important;
}

.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary-color);
}

.submit-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* Manga List */
.manga-list {
    background-color: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.manga-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
}

.manga-list-title h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.manga-list-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.search-results {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 400;
}

.manga-list-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-inline {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    min-width: 200px;
}

.search-input-inline:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-btn-inline {
    padding: 0.5rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn-inline:hover {
    background-color: var(--primary-hover);
}

.add-manga-btn-inline {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.add-manga-btn-inline:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    color: var(--text-secondary);
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-btn:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.view-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
}

.manga-item {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

/* List Header */
.list-header {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.list-column {
    display: flex;
    align-items: center;
}

/* View Content */
.view-content {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.view-content.active {
    display: block !important;
    opacity: 1;
}

/* Manga Comment */
.manga-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.manga-item:last-child {
    border-bottom: none;
}

/* Blocks View */
.blocks-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
}

.block-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.block-status {
    font-size: 0.75rem;
}

.block-content {
    padding: 1rem;
}

.block-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.block-progress {
    margin-bottom: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.block-comment {
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: var(--bg-tertiary);
    border-radius: 0.5rem;
}

.comment-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.comment-text {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-top: 1px solid var(--border-color);
    justify-content: flex-end;
}

.manga-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.manga-details {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-lendo { background-color: #dcfce7; color: #166534; }
.status-pretendo { background-color: #fef3c7; color: #92400e; }
.status-abandonado { background-color: #fee2e2; color: #991b1b; }
.status-completado { background-color: #dbeafe; color: #1e40af; }

.progress-bar {
    width: 100px;
    height: 8px;
    background-color: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background-color: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background-color: #d97706;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
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
    max-width: 800px;
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    gap: 1rem;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.warning-text {
    color: var(--danger-color);
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-secondary {
    background-color: var(--text-secondary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

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

    .manga-list-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .manga-list-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .view-toggle {
        justify-content: center;
    }

    .search-form-inline {
        width: 100%;
    }

    .search-input-inline {
        min-width: auto;
        flex: 1;
    }

    .blocks-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .manga-block {
        max-width: 100%;
    }
    
    .block-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .block-actions {
        justify-content: center;
    }

    .list-header {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
        display: none; /* Hide header on mobile */
    }

    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        text-align: center;
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

@media (max-width: 1024px) and (min-width: 769px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .manga-list-actions {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .search-input-inline {
        min-width: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .page-title {
        font-size: 2rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .view-toggle {
        width: 100%;
    }
    
    .view-btn {
        flex: 1;
        justify-content: center;
    }
}

/* ===== MANGA COVERS ===== */
.manga-cover {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.manga-block:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder span {
    font-size: 0.75rem;
    font-weight: 500;
}

.api-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: var(--shadow);
}

/* ===== MANGA COVERS - LIST VIEW ===== */
.manga-cover-small {
    width: 60px;
    height: 80px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
    margin-right: 1rem;
}

.cover-image-small {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder-small {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder-small i {
    font-size: 1.25rem;
    opacity: 0.5;
}

.manga-text-info {
    flex: 1;
}

/* ===== COVER UPLOAD ===== */
.cover-upload-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-file {
    display: none;
}

.cover-preview {
    width: 120px;
    height: 160px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.cover-placeholder-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    text-align: center;
    padding: 1rem;
}

.cover-placeholder-upload i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder-upload span {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-primary);
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== IMPROVED MANGA BLOCKS ===== */
.blocks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.manga-block {
    background: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: fit-content;
}

.manga-block:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.manga-block.imported-from-api {
    border-left: 4px solid #667eea;
}

.block-content {
    padding: 1rem;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.block-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.block-status {
    flex-shrink: 0;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.block-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
}

.detail-item {
    font-size: 0.875rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-item i {
    width: 14px;
    text-align: center;
    opacity: 0.7;
}

.block-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background-color: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 35px;
    text-align: right;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.block-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

/* ===== IMPROVED LIST VIEW ===== */
.manga-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.list-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.manga-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    transition: all 0.2s ease;
}

.manga-item:hover {
    background: var(--bg-secondary);
}

.manga-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-lendo {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pretendo {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-abandonado {
    background-color: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-completado {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* ===== API INDICATOR ===== */
.api-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .manga-cover {
        height: 160px;
    }
    
    .list-header,
    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .manga-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .manga-cover-small {
        width: 50px;
        height: 70px;
        margin-right: 0.75rem;
    }
}

@media (max-width: 480px) {
    .blocks-grid {
        grid-template-columns: 1fr;
    }
    
    .cover-preview {
        width: 100px;
        height: 130px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.manga-block {
    animation: fadeIn 0.3s ease;
}

.manga-item {
    animation: fadeIn 0.2s ease;
}

/* ===== LOADING STATES ===== */
.cover-loading {
    background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===== FORM IMPROVEMENTS ===== */
.form-group-full {
    grid-column: 1 / -1;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Layout Customizado */
.modal-form-layout {
    display: flex;
    gap: 2rem;
    align-items: stretch;
    min-height: 500px;
}

.modal-left {
    flex: 0 0 250px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-left .form-group {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-upload-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-preview {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-left .cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-row {
    display: flex;
    gap: 2rem;
    align-items: center;
    margin-top: 1rem;
}

.checkbox-row .checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsividade para o modal */
@media (max-width: 768px) {
    .modal-form-layout {
        flex-direction: column;
        gap: 1rem;
        min-height: auto;
    }
    
    .modal-left {
        flex: none;
        align-self: center;
    }
    
    .modal-left .cover-preview {
        height: 250px;
        width: 200px;
    }
    
    .checkbox-row {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

/* Garantir que o input file seja invisível mas funcional */
#addCoverImage {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
```

```css
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
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

/* SEÇÃO CORRIGIDA - Botões da navbar */
.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    background: var(--bg-primary);
    display: inline-block;
}

.nav-link:hover {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}

.nav-link.active {
    color: var(--primary-color);
    background-color: var(--bg-tertiary);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(99, 102, 241, 0.2);
}

.theme-toggle {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.user-icon {
    color: var(--text-secondary);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.user-icon:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transform: translateY(-1px);
}
/* FIM DA SEÇÃO CORRIGIDA */

/* Main Content */
.main-content {
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background-color: var(--bg-primary);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    text-align: center;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-card.total .metric-value { color: var(--primary-color); }
.metric-card.lendo .metric-value { color: var(--success-color); }
.metric-card.pretendo .metric-value { color: var(--warning-color); }
.metric-card.abandonado .metric-value { color: var(--danger-color); }
.metric-card.finalizados .metric-value { color: var(--secondary-color); }

/* Add Manga Form */
.add-manga-section {
    background-color: var(--bg-primary);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input:disabled, .form-select:disabled {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
    border-color: var(--border-color) !important;
}

.form-input:disabled::placeholder, .form-select:disabled::placeholder {
    color: var(--text-secondary) !important;
    opacity: 0.8 !important;
}

.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary-color);
}

.submit-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* Manga List */
.manga-list {
    background-color: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.manga-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
}

.manga-list-title h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.manga-list-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.search-results {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 400;
}

.manga-list-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-inline {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    min-width: 200px;
}

.search-input-inline:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-btn-inline {
    padding: 0.5rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn-inline:hover {
    background-color: var(--primary-hover);
}

.add-manga-btn-inline {
    background-color: var(--success-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.add-manga-btn-inline:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    color: var(--text-secondary);
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-btn:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.view-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);
}

.manga-item {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

/* List Header */
.list-header {
    display: grid;
    grid-template-columns: 1fr 120px 150px 200px 120px 120px;
    gap: 2rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.list-column {
    display: flex;
    align-items: center;
}

/* View Content */
.view-content {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.view-content.active {
    display: block !important;
    opacity: 1;
}

/* Manga Comment */
.manga-comment {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.manga-item:last-child {
    border-bottom: none;
}

/* Blocks View */
.blocks-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.manga-block:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-color);
}

.block-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.block-status {
    font-size: 0.75rem;
}

.block-content {
    padding: 1rem;
}

.block-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.block-progress {
    margin-bottom: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.block-comment {
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: var(--bg-tertiary);
    border-radius: 0.5rem;
}

.comment-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.comment-text {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background-color: var(--bg-tertiary);
    border-top: 1px solid var(--border-color);
    justify-content: flex-end;
}

.manga-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.manga-details {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-lendo { background-color: #dcfce7; color: #166534; }
.status-pretendo { background-color: #fef3c7; color: #92400e; }
.status-abandonado { background-color: #fee2e2; color: #991b1b; }
.status-completado { background-color: #dbeafe; color: #1e40af; }

.progress-bar {
    width: 100px;
    height: 8px;
    background-color: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background-color: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background-color: #d97706;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
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
    max-width: 800px;
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    gap: 1rem;
}

.modal-body {
    margin-bottom: 1.5rem;
}

.warning-text {
    color: var(--danger-color);
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-secondary {
    background-color: var(--text-secondary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

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

    .manga-list-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .manga-list-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .view-toggle {
        justify-content: center;
    }

    .search-form-inline {
        width: 100%;
    }

    .search-input-inline {
        min-width: auto;
        flex: 1;
    }

    .blocks-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .manga-block {
        max-width: 100%;
    }
    
    .block-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .block-actions {
        justify-content: center;
    }

    .list-header {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
        display: none; /* Hide header on mobile */
    }

    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        text-align: center;
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

@media (max-width: 1024px) and (min-width: 769px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .manga-list-actions {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .search-input-inline {
        min-width: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .page-title {
        font-size: 2rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .view-toggle {
        width: 100%;
    }
    
    .view-btn {
        flex: 1;
        justify-content: center;
    }
}

/* ===== MANGA COVERS ===== */
.manga-cover {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.manga-block:hover .cover-image {
    transform: scale(1.05);
}

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder span {
    font-size: 0.75rem;
    font-weight: 500;
}

.api-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: var(--shadow);
}

/* ===== MANGA COVERS - LIST VIEW ===== */
.manga-cover-small {
    width: 60px;
    height: 80px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
    margin-right: 1rem;
}

.cover-image-small {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder-small {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.cover-placeholder-small i {
    font-size: 1.25rem;
    opacity: 0.5;
}

.manga-text-info {
    flex: 1;
}

/* ===== COVER UPLOAD ===== */
.cover-upload-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-file {
    display: none;
}

.cover-preview {
    width: 120px;
    height: 160px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.cover-placeholder-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-secondary);
    text-align: center;
    padding: 1rem;
}

.cover-placeholder-upload i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.cover-placeholder-upload span {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-primary);
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== IMPROVED MANGA BLOCKS ===== */
.blocks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.manga-block {
    background: var(--bg-primary);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: fit-content;
}

.manga-block:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.manga-block.imported-from-api {
    border-left: 4px solid #667eea;
}

.block-content {
    padding: 1rem;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.block-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.block-status {
    flex-shrink: 0;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.block-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
}

.detail-item {
    font-size: 0.875rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-item i {
    width: 14px;
    text-align: center;
    opacity: 0.7;
}

.block-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background-color: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success-color) 0%, var(--primary-color) 100%);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 35px;
    text-align: right;
}

.block-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

.block-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

/* ===== IMPROVED LIST VIEW ===== */
.manga-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.list-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.manga-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    align-items: center;
    transition: all 0.2s ease;
}

.manga-item:hover {
    background: var(--bg-secondary);
}

.manga-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-lendo {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pretendo {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-abandonado {
    background-color: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-completado {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* ===== API INDICATOR ===== */
.api-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .blocks-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .manga-cover {
        height: 160px;
    }
    
    .list-header,
    .manga-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .manga-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .manga-cover-small {
        width: 50px;
        height: 70px;
        margin-right: 0.75rem;
    }
}

@media (max-width: 480px) {
    .blocks-grid {
        grid-template-columns: 1fr;
    }
    
    .cover-preview {
        width: 100px;
        height: 130px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.manga-block {
    animation: fadeIn 0.3s ease;
}

.manga-item {
    animation: fadeIn 0.2s ease;
}

/* ===== LOADING STATES ===== */
.cover-loading {
    background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===== FORM IMPROVEMENTS ===== */
.form-group-full {
    grid-column: 1 / -1;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Modal Layout Customizado */
.modal-form-layout {
    display: flex;
    gap: 2rem;
    align-items: stretch;
    min-height: 500px;
}

.modal-left {
    flex: 0 0 250px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-left .form-group {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-upload-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modal-left .cover-preview {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-left .cover-preview:hover {
    border-color: var(--primary-color);
    background-color: var(--bg-tertiary);
}

.modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-row {
    display: flex;
    gap: 2rem;
    align-items: center;
    margin-top: 1rem;
}

.checkbox-row .checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsividade para o modal */
@media (max-width: 768px) {
    .modal-form-layout {
        flex-direction: column;
        gap: 1rem;
        min-height: auto;
    }
    
    .modal-left {
        flex: none;
        align-self: center;
    }
    
    .modal-left .cover-preview {
        height: 250px;
        width: 200px;
    }
    
    .checkbox-row {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

/* Garantir que o input file seja invisível mas funcional */
#addCoverImage {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}
```

Pronto! Modifiquei o sombreamento de todos os botões da navbar para que apareça apenas na parte inferior dos elementos. As mudanças incluem:

- **`.nav-link`**: Alterado de `box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1)` para `box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1)`
- **`.nav-link:hover`**: Alterado para `box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15)`
- **`.nav-link.active`**: Alterado para `box-shadow: 0 3px 0 rgba(99, 102, 241, 0.2)`
- **`.theme-toggle`**: Alterado para `box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1)`
- **`.theme-toggle:hover`**: Alterado para `box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15)`
- **`.user-icon`**: Alterado para `box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1)`
- **`.user-icon:hover`**: Alterado para `box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15)`

Agora todos os botões da navbar têm sombreamento apenas na parte inferior, criando um efeito visual mais sutil e elegante.

