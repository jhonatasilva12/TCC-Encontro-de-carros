<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db_connect.php';
session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido', 405);
    }

    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Acesso não autorizado', 401);
    }

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON inválido', 400);
    }

    $tipo = $data['tipo'] ?? null;
    $nome = trim($data['nome'] ?? '');
    $corFundo = $data['cor_fundo'] ?? '#3498db';
    $corTexto = isset($data['cor_texto']) ? (int)$data['cor_texto'] : 1;

    // Validação da cor hexadecimal
    if (!preg_match('/^#[a-f0-9]{6}$/i', $corFundo)) {
        throw new Exception('Cor de fundo inválida', 400);
    }

    if (empty($tipo) || empty($nome)) {
        throw new Exception('Dados incompletos', 400);
    }

    if ($tipo === 'tipo_post') {
        $table = 'tb_tipo_post';
        $fields = [
            'nome_tipo_post' => $nome,
            'cor_fundo' => $corFundo,
            'cor_letra' => $corTexto
        ];
    } else {
        $table = 'temas_grupo';
        $fields = [
            'nome_temas' => $nome,
            'cor_fundo' => $corFundo,
            'cor_letras' => $corTexto
        ];
    }

    $columns = implode(', ', array_keys($fields));
    $placeholders = ':' . implode(', :', array_keys($fields));
    
    $stmt = $pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
    $stmt->execute($fields);
    
    echo json_encode([
        'success' => true,
        'id' => $pdo->lastInsertId(),
        'message' => 'Opção criada com sucesso!',
        'data' => $fields
    ]);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'received_data' => $data ?? null
    ]);
}