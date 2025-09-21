<?php
ob_start();
session_start();

// Inicializar sistema local
require_once 'init-api.php';

// Função para upload de capas
function uploadCover($file, $anime_id) {
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
    
    // Remover capas antigas do mesmo anime
    $old_files = glob($upload_dir . $anime_id . '.*');
    foreach ($old_files as $old_file) {
        unlink($old_file);
    }
    
    // Nome do arquivo
    $filename = $anime_id . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'error' => 'Erro ao salvar arquivo'];
    }
}

// Inicializar array de animes se não existir
if (!isset($_SESSION['animes'])) {
    $_SESSION['animes'] = [];
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
                        $_POST['capitulos_total'] = 0; // Set to 0 for ongoing anime
                    } elseif (empty($_POST['capitulos_total'])) {
                        $_POST['capitulos_total'] = 0; // Default value if not set
                    }
                    
                    // Set current chapter based on status
                    if ($status === 'completado' && !empty($_POST['capitulos_total'])) {
                        $_POST['capitulo_atual'] = $_POST['capitulos_total']; // Set to total for completed anime
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
                    
                    $anime_id = uniqid();
                    
                    $anime = [
                        'id' => $anime_id,
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
                        $upload_result = uploadCover($_FILES['cover_image'], $anime_id);
                        if ($upload_result['success']) {
                            $anime['custom_cover'] = $upload_result['filename'];
                        }
                    }
                    
                    $_SESSION['animes'][] = $anime;
                }
                break;
                
            case 'edit':
                if (isset($_POST['id']) && !empty($_POST['nome'])) {
                    foreach ($_SESSION['animes'] as &$anime) {
                        if ($anime['id'] === $_POST['id']) {
                            $anime['nome'] = $_POST['nome'];
                            $anime['status'] = $_POST['status'];
                            $anime['capitulos_total'] = (int)$_POST['capitulos_total'];
                            $anime['finalizado'] = isset($_POST['finalizado']) ? true : false;
                            $anime['capitulo_atual'] = (int)$_POST['capitulo_atual'];
                            $anime['em_lancamento'] = isset($_POST['em_lancamento']) ? true : false;
                            $anime['generos'] = !empty($_POST['generos']) ? $_POST['generos'] : [];
                            $anime['data_atualizacao'] = date('Y-m-d H:i:s');
                            
                            // Set total chapters based on em_lancamento status
                            if ($anime['em_lancamento']) {
                                $anime['capitulos_total'] = 0;
                            }
                            
                            // Set current chapter based on status
                            if ($anime['status'] === 'completado' && $anime['capitulos_total'] > 0) {
                                $anime['capitulo_atual'] = $anime['capitulos_total'];
                            }
                            
                            // Processar upload de nova capa
                            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                                $upload_result = uploadCover($_FILES['cover_image'], $anime['id']);
                                if ($upload_result['success']) {
                                    $anime['custom_cover'] = $upload_result['filename'];
                                }
                            }
                            
                            break;
                        }
                    }
                }
                break;
                
            case 'delete':
                if (isset($_POST['id'])) {
                    $_SESSION['animes'] = array_filter($_SESSION['animes'], function($anime) {
                        return $anime['id'] !== $_POST['id'];
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
                
                if (isset($_POST['anime_order'])) {
                    try {
                        // Decodificar JSON se necessário
                        $animeOrder = $_POST['anime_order'];
                        if (is_string($animeOrder)) {
                            $animeOrder = json_decode($animeOrder, true);
                        }
                        
                        // Debug: verificar ordem decodificada
                        error_log('anime order decoded: ' . print_r($animeOrder, true));
                        
                        if (!is_array($animeOrder) || empty($animeOrder)) {
                            echo json_encode(['success' => false, 'message' => 'Array de ordem inválido']);
                            exit;
                        }
                        
                        // Reordenar animes na sessão
                        $reorderedanimes = [];
                        foreach ($animeOrder as $animeId) {
                            foreach ($_SESSION['animes'] as $anime) {
                                if ($anime['id'] === $animeId) {
                                    $reorderedanimes[] = $anime;
                                    break;
                                }
                            }
                        }
                        
                        // Verificar se todos os animes foram encontrados
                        if (count($reorderedanimes) !== count($animeOrder)) {
                            echo json_encode(['success' => false, 'message' => 'Alguns animes não foram encontrados']);
                            exit;
                        }
                        
                        // Atualizar sessão
                        $_SESSION['animes'] = $reorderedanimes;
                        
                        echo json_encode(['success' => true, 'message' => 'Ordem atualizada com sucesso']);
                    } catch (Exception $e) {
                        error_log('Error in update_order: ' . $e->getMessage());
                        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dados inválidos - anime_order não encontrado']);
                }
                exit;
        }
        
        // Redirecionar para evitar reenvio do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Filtrar animes por pesquisa
$termo_pesquisa = isset($_GET['search']) ? trim($_GET['search']) : '';
$animes_filtrados = $_SESSION['animes'];
$resultados_api = []; // Initialize API results array

if (!empty($termo_pesquisa)) {
    // Busca apenas local
    $animes_filtrados = array_filter($_SESSION['animes'], function($anime) use ($termo_pesquisa) {
        return stripos($anime['nome'], $termo_pesquisa) !== false ||
               (isset($anime['comentario']) && stripos($anime['comentario'], $termo_pesquisa) !== false);
    });
}

// Função para converter status para texto completo
function getStatusText($status) {
    $statusMap = [
        'assistindo' => 'Assistindo',
        'pretendo' => 'Pretendo Assistir',
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
$total_animes = count($_SESSION['animes']);
$animes_assistindo = count(array_filter($_SESSION['animes'], fn($m) => $m['status'] === 'assistindo'));
$animes_pretendo = count(array_filter($_SESSION['animes'], fn($m) => $m['status'] === 'pretendo'));
$animes_abandonados = count(array_filter($_SESSION['animes'], fn($m) => $m['status'] === 'abandonado'));
$animes_finalizados = count(array_filter($_SESSION['animes'], fn($m) => $m['finalizado']));

// Incluir o template PHP
include 'template-animes.php';
?>
