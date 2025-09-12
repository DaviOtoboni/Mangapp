<?php
session_start();

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

// Verificar se os dados foram enviados
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['order']) || !is_array($input['order'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados de ordem inválidos']);
    exit;
}

if (!isset($input['view_type']) || !in_array($input['view_type'], ['list', 'cards'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tipo de visualização inválido']);
    exit;
}

try {
    // Incluir arquivo de configuração do banco
    require_once 'config/database.php';
    
    // Conectar ao banco de dados
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Preparar query para atualizar a ordem
    $sql = "UPDATE mangas SET ordem = :ordem WHERE id = :manga_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    
    $success_count = 0;
    $errors = [];
    
    // Atualizar cada mangá com sua nova ordem
    foreach ($input['order'] as $index => $manga_id) {
        try {
            $stmt->execute([
                'ordem' => $index + 1,
                'manga_id' => $manga_id,
                'user_id' => $_SESSION['user_id']
            ]);
            
            if ($stmt->rowCount() > 0) {
                $success_count++;
            } else {
                $errors[] = "Mangá ID {$manga_id} não encontrado ou não pertence ao usuário";
            }
        } catch (PDOException $e) {
            $errors[] = "Erro ao atualizar mangá ID {$manga_id}: " . $e->getMessage();
        }
    }
    
    // Resposta de sucesso
    if ($success_count > 0) {
        $response = [
            'success' => true,
            'message' => "Ordem atualizada com sucesso para {$success_count} mangá(s)",
            'updated_count' => $success_count,
            'view_type' => $input['view_type']
        ];
        
        if (!empty($errors)) {
            $response['warnings'] = $errors;
        }
        
        echo json_encode($response);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nenhum mangá foi atualizado',
            'errors' => $errors
        ]);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro de conexão com o banco de dados',
        'error' => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor',
        'error' => $e->getMessage()
    ]);
}
?>
