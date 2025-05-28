<?php
require_once ('db_connect.php');
require_once ('autentica.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['comentario'])) {
    $pdo->beginTransaction();
    
    try {
        $query = "INSERT INTO tb_comentario (texto_comentario, fk_id_user, fk_id_post, data_comentario) 
                VALUES (?, ?, ?, NOW())";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            $_POST['comentario'],
            $_SESSION['user_id'],
            $_POST['post_id']
        ]);
        
        $pdo->commit();
        header("Location: ../post.php?id=" . $_POST['post_id']);
    } catch (Exception $e) {
        header("Location: post.php?id=" . $_POST['post_id'] . "&erro=enviar+comentario");
    }
    exit;
}

header("Location: ../index.php");