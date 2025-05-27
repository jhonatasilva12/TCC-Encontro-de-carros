<?php
include_once './db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$postId) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Verifica se já deu like
    $stmt = $pdo->prepare("SELECT * FROM likes_post WHERE fk_id_user = ? AND fk_id_post = ?");
    $stmt->execute([$_SESSION['user_id'], $postId]);
    
    if ($stmt->rowCount() > 0) {
        // Remove o like
        $stmt = $pdo->prepare("DELETE FROM likes_post WHERE fk_id_user = ? AND fk_id_post = ?");
        $stmt->execute([$_SESSION['user_id'], $postId]);
        $liked = false;
    } else {
        // Adiciona o like
        $stmt = $pdo->prepare("INSERT INTO likes_post (fk_id_user, fk_id_post) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $postId]);
        $liked = true;
    }
    
    // Conta o total de likes
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM likes_post WHERE fk_id_post = ?");
    $stmt->execute([$postId]);
    $total = $stmt->fetch()['total'];
    
    $pdo->commit();
    
    echo json_encode(['success' => true, 'liked' => $liked, 'total' => $total]);
} catch (PDOException $e) {
    $pdo->rollBack();
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['error' => $e->getMessage()]);
}