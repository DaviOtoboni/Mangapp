<?php
/**
 * Configuração do Supabase para MangApp
 * Implementação usando cURL para chamadas diretas à API
 */

// Evitar acesso direto
if (!defined('MANGAPP_API_INITIALIZED') && !defined('LOCAL_SYSTEM_INITIALIZED') && !defined('TESTING_MODE')) {
    die('Acesso direto não permitido');
}

// Configurações do Supabase
define('SUPABASE_URL', 'https://yeceuxmjmsxovjucdnce.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InllY2V1eG1qbXN4b3ZqdWNkbmNlIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTg1NjE4MDIsImV4cCI6MjA3NDEzNzgwMn0.U1mG74hUU3eGRWFfm-14RplOI2Q-W6ntkcfBoBn8MI4');

// Configurações de autenticação
define('SUPABASE_AUTH_TABLE', 'users');
define('SUPABASE_SESSION_KEY', 'supabase_session');

// Função para fazer requisições HTTP para o Supabase
function makeSupabaseRequest($endpoint, $method = 'GET', $data = null, $headers = []) {
    $url = SUPABASE_URL . $endpoint;
    
    $defaultHeaders = [
        'Content-Type: application/json',
        'apikey: ' . SUPABASE_ANON_KEY,
        'Authorization: Bearer ' . SUPABASE_ANON_KEY
    ];
    
    $headers = array_merge($defaultHeaders, $headers);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log('Erro cURL: ' . $error);
        return ['success' => false, 'error' => $error];
    }
    
    $decodedResponse = json_decode($response, true);
    
    return [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'data' => $decodedResponse,
        'http_code' => $httpCode,
        'raw_response' => $response
    ];
}

// Função para fazer login com Supabase
function loginWithSupabase($email, $password) {
    $data = [
        'email' => $email,
        'password' => $password
    ];
    
    $response = makeSupabaseRequest('/auth/v1/token?grant_type=password', 'POST', $data);
    
    if ($response['success'] && isset($response['data']['access_token'])) {
        return [
            'success' => true,
            'user' => $response['data'],
            'session' => $response['data']
        ];
    } else {
        $message = 'Credenciais inválidas';
        $error_code = '';
        
        if (isset($response['data']['msg'])) {
            $message = $response['data']['msg'];
        }
        if (isset($response['data']['error_description'])) {
            $message = $response['data']['error_description'];
        }
        if (isset($response['data']['error_code'])) {
            $error_code = $response['data']['error_code'];
        }
        
        // Mensagens específicas para diferentes erros
        if ($error_code === 'email_not_confirmed') {
            $message = 'Email não confirmado. Verifique sua caixa de entrada e clique no link de confirmação.';
        } elseif ($error_code === 'invalid_credentials') {
            $message = 'Email ou senha incorretos.';
        } elseif ($error_code === 'too_many_requests') {
            $message = 'Muitas tentativas de login. Tente novamente em alguns minutos.';
        }
        
        // Adicionar debug
        $debug = 'HTTP Code: ' . ($response['http_code'] ?? 'N/A');
        if (isset($response['data'])) {
            $debug .= ' | Response: ' . json_encode($response['data']);
        }
        
        return [
            'success' => false, 
            'message' => $message,
            'error_code' => $error_code,
            'debug' => $debug
        ];
    }
}

// Função para registrar usuário no Supabase
function registerWithSupabase($email, $password, $username) {
    $data = [
        'email' => $email,
        'password' => $password,
        'data' => [
            'username' => $username
        ]
    ];
    
    $response = makeSupabaseRequest('/auth/v1/signup', 'POST', $data);
    
    // A API retorna o usuário diretamente, não dentro de um objeto 'user'
    if ($response['success'] && isset($response['data']['id'])) {
        return [
            'success' => true,
            'user' => $response['data'],
            'message' => 'Conta criada com sucesso! Verifique seu email para confirmar a conta.'
        ];
    } else {
        $message = 'Erro ao criar conta';
        $debug = '';
        
        if (isset($response['data']['msg'])) {
            $message = $response['data']['msg'];
        }
        
        if (isset($response['data']['error_description'])) {
            $message = $response['data']['error_description'];
        }
        
        // Adicionar informações de debug
        $debug = 'HTTP Code: ' . ($response['http_code'] ?? 'N/A');
        if (isset($response['data'])) {
            $debug .= ' | Response: ' . json_encode($response['data']);
        }
        if (isset($response['error'])) {
            $debug .= ' | cURL Error: ' . $response['error'];
        }
        
        return [
            'success' => false, 
            'message' => $message,
            'debug' => $debug
        ];
    }
}

// Função para fazer logout no Supabase
function logoutWithSupabase() {
    $response = makeSupabaseRequest('/auth/v1/logout', 'POST');
    return $response['success'];
}

// Função para verificar se usuário está logado
function isUserLoggedInSupabase() {
    // Verificação simples baseada nos dados da sessão
    return isset($_SESSION['user_logged_in']) && 
           $_SESSION['user_logged_in'] === true && 
           isset($_SESSION['user_id']) && 
           isset($_SESSION['user_email']);
}

// Função para obter usuário atual
function getCurrentUserSupabase() {
    if (isset($_SESSION['supabase_access_token'])) {
        $headers = [
            'Authorization: Bearer ' . $_SESSION['supabase_access_token']
        ];
        
        $response = makeSupabaseRequest('/auth/v1/user', 'GET', null, $headers);
        if ($response['success'] && isset($response['data']['id'])) {
            return $response['data'];
        }
    }
    
    return null;
}

// Função para salvar dados do usuário na tabela personalizada
function saveUserToDatabase($userData) {
    $data = [
        'id' => $userData['id'],
        'email' => $userData['email'],
        'username' => $userData['user_metadata']['username'] ?? '',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $response = makeSupabaseRequest('/rest/v1/' . SUPABASE_AUTH_TABLE, 'POST', $data);
    
    // Se der erro de duplicação, não é problema (usuário já existe)
    if (!$response['success'] && isset($response['data']['code']) && $response['data']['code'] === '23505') {
        return true; // Usuário já existe, consideramos sucesso
    }
    
    return $response['success'];
}

// Marcar como inicializado
if (!defined('SUPABASE_INITIALIZED')) {
    define('SUPABASE_INITIALIZED', true);
}
?>
