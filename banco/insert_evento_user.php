<?php
header('Content-Type: application/json');
session_start();

$eventoId = $_POST['evento_id'] ?? null;
$action = $_POST['action'] ?? 'join';

if (!$eventoId) {
    echo json_encode(['success' => false, 'error' => 'ID do evento inválido']);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'db_meetcar');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Erro de conexão']);
    exit;
}

$userId = $_SESSION['user_id'];

if ($action === 'join') {
    // Verifica se já está inscrito
    $check = $conn->prepare("SELECT 1 FROM evento_user WHERE fk_id_user = ? AND fk_id_evento = ?");
    $check->bind_param("ii", $userId, $eventoId);
    $check->execute();
    
    if ($check->get_result()->num_rows === 0) {
        // Adiciona participação
        $stmt = $conn->prepare("INSERT INTO evento_user (fk_id_user, fk_id_evento) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $eventoId);
        $stmt->execute();
    }
} else {
    // Remove participação
    $stmt = $conn->prepare("DELETE FROM evento_user WHERE fk_id_user = ? AND fk_id_evento = ?");
    $stmt->bind_param("ii", $userId, $eventoId);
    $stmt->execute();
}

// Obtém nova contagem
$count = $conn->query("SELECT COUNT(*) as count FROM evento_user WHERE fk_id_evento = $eventoId")->fetch_assoc()['count'];

$conn->close();

echo json_encode(['success' => true, 'new_count' => $count]);
?>