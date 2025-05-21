<?php
header('Content-Type: application/json');

// Verifica se o usuário está logado (implemente sua lógica de autenticação)
session_start();

$postId = $_POST['post_id'] ?? null;
$action = $_POST['action'] ?? 'like';

if (!$postId) {
    echo json_encode(['success' => false, 'error' => 'ID do post inválido']);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'db_meetcar');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Erro de conexão']);
    exit;
}

$userId = $_SESSION['user_id'];

if ($action === 'like') {
    // Verifica se já curtiu
    $check = $conn->prepare("SELECT 1 FROM likes_post WHERE fk_id_user = ? AND fk_id_post = ?");
    $check->bind_param("ii", $userId, $postId);
    $check->execute();
    
    if ($check->get_result()->num_rows === 0) {
        // Adiciona like
        $stmt = $conn->prepare("INSERT INTO likes_post (fk_id_user, fk_id_post) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
    }
} else {
    // Remove like
    $stmt = $conn->prepare("DELETE FROM likes_post WHERE fk_id_user = ? AND fk_id_post = ?");
    $stmt->bind_param("ii", $userId, $postId);
    $stmt->execute();
}

// Obtém nova contagem
$count = $conn->query("SELECT COUNT(*) as count FROM likes_post WHERE fk_id_post = $postId")->fetch_assoc()['count'];

$conn->close();

echo json_encode(['success' => true, 'new_count' => $count]);
?>