<?php
ob_start();
session_start();

// Inicializar sistema local
require_once 'init-api.php';

// Função para upload de capas
function uploadCover($file, $manga_id) {
    $upload_dir = 'covers/originals/';
    
    // Criar diretório se não existir
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Validar tipo de arquivo
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'error' => 'Tipo de arquivo não permitido'];
    }
    
    // Validar tamanho (máximo 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'error' => 'Arquivo muito grande (máximo 5MB)'];
    }
    
    // Determinar extensão
    $extension = '';
    switch ($file['type']) {
        case 'image/jpeg':
        case 'image/jpg':
            $extension = 'jpg';
            break;
        case 'image/png':
            $extension = 'png';
            break;
        case 'image/webp':
            $extension = 'webp';
            break;
    }
    
    // Remover capas antigas do mesmo mangá
    $old_files = glob($upload_dir . $manga_id . '.*');
    foreach ($old_files as $old_file) {
        unlink($old_file);
    }
    
    // Nome do arquivo
    $filename = $manga_id . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'error' => 'Erro ao salvar arquivo'];
    }
}

// Inicializar array de mangás se não existir
if (!isset($_SESSION['mangas'])) {
    $_SESSION['mangas'] = [];
}

// Inicializar tema se não existir
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

// Processar formulários
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if (!empty($_POST['nome']) && !empty($_POST['status'])) {
                    // Validate required fields based on conditions
                    $em_lancamento = isset($_POST['em_lancamento']) ? true : false;
                    $status = $_POST['status'];
                    
                    // Set total chapters based on em_lancamento status
                    if ($em_lancamento) {
                        $_POST['capitulos_total'] = 0; // Set to 0 for ongoing manga
                    } elseif (empty($_POST['capitulos_total'])) {
                        $_POST['capitulos_total'] = 0; // Default value if not set
                    }
                    
                    // Set current chapter based on status
                    if ($status === 'completado' && !empty($_POST['capitulos_total'])) {
                        $_POST['capitulo_atual'] = $_POST['capitulos_total']; // Set to total for completed manga
                    } elseif (empty($_POST['capitulo_atual'])) {
                        $_POST['capitulo_atual'] = 0; // Default value if not set
                    }
                    
                    // Processar dados da API se disponíveis
                    $api_data = null;
                    if (!empty($_POST['api_data'])) {
                        try {
                            $api_data = json_decode($_POST['api_data'], true);
                        } catch (Exception $e) {
                            // Ignorar erro de JSON inválido
                        }
                    }
                    
                    $manga_id = uniqid();
                    
                    $manga = [
                        'id' => $manga_id,
                        'nome' => $_POST['nome'],
                        'status' => $_POST['status'],
                        'capitulos_total' => (int)$_POST['capitulos_total'],
                        'finalizado' => isset($_POST['finalizado']) ? true : false,
                        'capitulo_atual' => (int)$_POST['capitulo_atual'],
                        'em_lancamento' => $em_lancamento,
                        'generos' => !empty($_POST['generos']) ? $_POST['generos'] : [],
                        'data_criacao' => date('Y-m-d H:i:s'),
                        'data_atualizacao' => date('Y-m-d H:i:s'),
                        'api_data' => $api_data, // Armazenar dados da API
                        'imported_from_api' => !empty($api_data) // Flag para indicar origem
                    ];
                    
                    // Processar upload de capa
                    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                        $upload_result = uploadCover($_FILES['cover_image'], $manga_id);
                        if ($upload_result['success']) {
                            $manga['custom_cover'] = $upload_result['filename'];
                        }
                    }
                    
                    $_SESSION['mangas'][] = $manga;
                }
                break;
                
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
                            $manga['generos'] = !empty($_POST['generos']) ? $_POST['generos'] : [];
                            $manga['data_atualizacao'] = date('Y-m-d H:i:s');
                            
                            // Set total chapters based on em_lancamento status
                            if ($manga['em_lancamento']) {
                                $manga['capitulos_total'] = 0;
                            }
                            
                            // Set current chapter based on status
                            if ($manga['status'] === 'completado' && $manga['capitulos_total'] > 0) {
                                $manga['capitulo_atual'] = $manga['capitulos_total'];
                            }
                            
                            // Processar upload de nova capa
                            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                                $upload_result = uploadCover($_FILES['cover_image'], $manga['id']);
                                if ($upload_result['success']) {
                                    $manga['custom_cover'] = $upload_result['filename'];
                                }
                            }
                            
                            break;
                        }
                    }
                }
                break;
                
            case 'delete':
                if (isset($_POST['id'])) {
                    $_SESSION['mangas'] = array_filter($_SESSION['mangas'], function($manga) {
                        return $manga['id'] !== $_POST['id'];
                    });
                }
                break;
                
            case 'toggle_theme':
                $_SESSION['theme'] = $_SESSION['theme'] === 'light' ? 'dark' : 'light';
                echo json_encode(['success' => true, 'theme' => $_SESSION['theme']]);
                exit;
                
            case 'update_order':
                // Debug: verificar dados recebidos
                error_log('Update order - POST data: ' . print_r($_POST, true));
                
                if (isset($_POST['manga_order'])) {
                    try {
                        // Decodificar JSON se necessário
                        $mangaOrder = $_POST['manga_order'];
                        if (is_string($mangaOrder)) {
                            $mangaOrder = json_decode($mangaOrder, true);
                        }
                        
                        // Debug: verificar ordem decodificada
                        error_log('Manga order decoded: ' . print_r($mangaOrder, true));
                        
                        if (!is_array($mangaOrder) || empty($mangaOrder)) {
                            echo json_encode(['success' => false, 'message' => 'Array de ordem inválido']);
                            exit;
                        }
                        
                        // Reordenar mangás na sessão
                        $reorderedMangas = [];
                        foreach ($mangaOrder as $mangaId) {
                            foreach ($_SESSION['mangas'] as $manga) {
                                if ($manga['id'] === $mangaId) {
                                    $reorderedMangas[] = $manga;
                                    break;
                                }
                            }
                        }
                        
                        // Verificar se todos os mangás foram encontrados
                        if (count($reorderedMangas) !== count($mangaOrder)) {
                            echo json_encode(['success' => false, 'message' => 'Alguns mangás não foram encontrados']);
                            exit;
                        }
                        
                        // Atualizar sessão
                        $_SESSION['mangas'] = $reorderedMangas;
                        
                        echo json_encode(['success' => true, 'message' => 'Ordem atualizada com sucesso']);
                    } catch (Exception $e) {
                        error_log('Error in update_order: ' . $e->getMessage());
                        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dados inválidos - manga_order não encontrado']);
                }
                exit;
        }
        
        // Redirecionar para evitar reenvio do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Filtrar mangás por pesquisa
$termo_pesquisa = isset($_GET['search']) ? trim($_GET['search']) : '';
$mangas_filtrados = $_SESSION['mangas'];
$resultados_api = []; // Initialize API results array

if (!empty($termo_pesquisa)) {
    // Busca apenas local
    $mangas_filtrados = array_filter($_SESSION['mangas'], function($manga) use ($termo_pesquisa) {
        return stripos($manga['nome'], $termo_pesquisa) !== false ||
               (isset($manga['comentario']) && stripos($manga['comentario'], $termo_pesquisa) !== false);
    });
}

// Função para converter status para texto completo
function getStatusText($status) {
    $statusMap = [
        'lendo' => 'Lendo',
        'pretendo' => 'Pretendo Ler',
        'abandonado' => 'Abandonado',
        'completado' => 'Completado'
    ];
    
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

// Calcular métricas
$total_mangas = count($_SESSION['mangas']);
$mangas_lendo = count(array_filter($_SESSION['mangas'], fn($m) => $m['status'] === 'lendo'));
$mangas_pretendo = count(array_filter($_SESSION['mangas'], fn($m) => $m['status'] === 'pretendo'));
$mangas_abandonados = count(array_filter($_SESSION['mangas'], fn($m) => $m['status'] === 'abandonado'));
$mangas_finalizados = count(array_filter($_SESSION['mangas'], fn($m) => $m['finalizado']));

// Incluir o template PHP
include 'template-games.php';
?>
