<?php
ob_start();
session_start();

// TEMPORÁRIO: Verificação de login desabilitada
// if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
//     header('Location: login.php');
//     exit;
// }

// Inicializar sistema local
require_once 'init-api.php';

// Função para upload de capas
function uploadCover($file, $jogos_id) {
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
    $old_files = glob($upload_dir . $jogos_id . '.*');
    foreach ($old_files as $old_file) {
        unlink($old_file);
    }
    
    // Nome do arquivo
    $filename = $jogos_id . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'error' => 'Erro ao salvar arquivo'];
    }
}

// Inicializar array de mangás se não existir
if (!isset($_SESSION['jogos'])) {
    $_SESSION['jogos'] = [];
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
                        $_POST['capitulos_total'] = 0; // Set to 0 for ongoing jogos
                    } elseif (empty($_POST['capitulos_total'])) {
                        $_POST['capitulos_total'] = 0; // Default value if not set
                    }
                    
                    // Set current chapter based on status
                    if ($status === 'completado' && !empty($_POST['capitulos_total'])) {
                        $_POST['capitulo_atual'] = $_POST['capitulos_total']; // Set to total for completed jogos
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
                    
                    $jogos_id = uniqid();
                    
                    $jogos = [
                        'id' => $jogos_id,
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
                        $upload_result = uploadCover($_FILES['cover_image'], $jogos_id);
                        if ($upload_result['success']) {
                            $jogos['custom_cover'] = $upload_result['filename'];
                        }
                    }
                    
                    $_SESSION['jogos'][] = $jogos;
                }
                break;
                
            case 'edit':
                if (isset($_POST['id']) && !empty($_POST['nome'])) {
                    foreach ($_SESSION['jogos'] as &$jogos) {
                        if ($jogos['id'] === $_POST['id']) {
                            $jogos['nome'] = $_POST['nome'];
                            $jogos['status'] = $_POST['status'];
                            $jogos['capitulos_total'] = (int)$_POST['capitulos_total'];
                            $jogos['finalizado'] = isset($_POST['finalizado']) ? true : false;
                            $jogos['capitulo_atual'] = (int)$_POST['capitulo_atual'];
                            $jogos['em_lancamento'] = isset($_POST['em_lancamento']) ? true : false;
                            $jogos['generos'] = !empty($_POST['generos']) ? $_POST['generos'] : [];
                            $jogos['data_atualizacao'] = date('Y-m-d H:i:s');
                            
                            // Set total chapters based on em_lancamento status
                            if ($jogos['em_lancamento']) {
                                $jogos['capitulos_total'] = 0;
                            }
                            
                            // Set current chapter based on status
                            if ($jogos['status'] === 'completado' && $jogos['capitulos_total'] > 0) {
                                $jogos['capitulo_atual'] = $jogos['capitulos_total'];
                            }
                            
                            // Processar upload de nova capa
                            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                                $upload_result = uploadCover($_FILES['cover_image'], $jogos['id']);
                                if ($upload_result['success']) {
                                    $jogos['custom_cover'] = $upload_result['filename'];
                                }
                            }
                            
                            break;
                        }
                    }
                }
                break;
                
            case 'delete':
                if (isset($_POST['id'])) {
                    $_SESSION['jogos'] = array_filter($_SESSION['jogos'], function($jogos) {
                        return $jogos['id'] !== $_POST['id'];
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
                
                if (isset($_POST['jogos_order'])) {
                    try {
                        // Decodificar JSON se necessário
                        $jogosOrder = $_POST['jogos_order'];
                        if (is_string($jogosOrder)) {
                            $jogosOrder = json_decode($jogosOrder, true);
                        }
                        
                        // Debug: verificar ordem decodificada
                        error_log('jogos order decoded: ' . print_r($jogosOrder, true));
                        
                        if (!is_array($jogosOrder) || empty($jogosOrder)) {
                            echo json_encode(['success' => false, 'message' => 'Array de ordem inválido']);
                            exit;
                        }
                        
                        // Reordenar jogos na sessão
                        $reorderedJogos = [];
                        foreach ($jogosOrder as $jogosId) {
                            foreach ($_SESSION['jogos'] as $jogos) {
                                if ($jogos['id'] === $jogosId) {
                                    $reorderedJogos[] = $jogos;
                                    break;
                                }
                            }
                        }
                        
                        // Verificar se todos os jogos foram encontrados
                        if (count($reorderedJogos) !== count($jogosOrder)) {
                            echo json_encode(['success' => false, 'message' => 'Alguns jogos não foram encontrados']);
                            exit;
                        }
                        
                        // Atualizar sessão
                        $_SESSION['jogos'] = $reorderedJogos;
                        
                        echo json_encode(['success' => true, 'message' => 'Ordem atualizada com sucesso']);
                    } catch (Exception $e) {
                        error_log('Error in update_order: ' . $e->getMessage());
                        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dados inválidos - game_order não encontrado']);
                }
                exit;
        }
        
        // Redirecionar para evitar reenvio do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Filtrar jogos por pesquisa
$termo_pesquisa = isset($_GET['search']) ? trim($_GET['search']) : '';
$jogos_filtrados = $_SESSION['jogos'];
$resultados_api = []; // Initialize API results array

if (!empty($termo_pesquisa)) {
    // Busca apenas local
    $jogos_filtrados = array_filter($_SESSION['jogos'], function($jogos) use ($termo_pesquisa) {
        return stripos($jogos['nome'], $termo_pesquisa) !== false ||
               (isset($jogos['comentario']) && stripos($jogos['comentario'], $termo_pesquisa) !== false);
    });
}

// Função para converter status para texto completo
function getStatusText($status) {
    $statusMap = [
        'jogando' => 'Jogando',
        'pretendo' => 'Pretendo Jogar',
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
$total_jogos = count($_SESSION['jogos']);
$jogos_jogando = count(array_filter($_SESSION['jogos'], fn($m) => $m['status'] === 'jogando'));
$jogos_pretendo = count(array_filter($_SESSION['jogos'], fn($m) => $m['status'] === 'pretendo'));
$jogos_abandonados = count(array_filter($_SESSION['jogos'], fn($m) => $m['status'] === 'abandonado'));
$jogos_finalizados = count(array_filter($_SESSION['jogos'], fn($m) => $m['finalizado']));

// Incluir o template PHP
include 'template-games.php';
?>
