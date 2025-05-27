<?php
include_once './db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

$eventoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$eventoId) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Verifica se já está participando
    $stmt = $pdo->prepare("SELECT * FROM evento_user WHERE fk_id_user = ? AND fk_id_evento = ?");
    $stmt->execute([$_SESSION['user_id'], $eventoId]);
    
    if ($stmt->rowCount() > 0) {
        // Remove a participação
        $stmt = $pdo->prepare("DELETE FROM evento_user WHERE fk_id_user = ? AND fk_id_evento = ?");
        $stmt->execute([$_SESSION['user_id'], $eventoId]);
        $participando = false;
    } else {
        // Adiciona participação
        $stmt = $pdo->prepare("INSERT INTO evento_user (fk_id_user, fk_id_evento) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $eventoId]);
        $participando = true;
    }
    
    // Conta o total de participantes
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM evento_user WHERE fk_id_evento = ?");
    $stmt->execute([$eventoId]);
    $total = $stmt->fetch()['total'];
    
    $pdo->commit();
    
    echo json_encode(['success' => true, 'participando' => $participando, 'total' => $total]);
} catch (PDOException $e) {
    $pdo->rollBack();
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['error' => $e->getMessage()]);
}