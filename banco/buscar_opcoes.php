<?php

header('Content-Type: application/json');

try {
    
    require_once './db_connect.php';
    // Buscar tipos de post
    $stmt = $pdo->query("SELECT * FROM tb_tipo_post");
    $tiposPost = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Buscar temas de grupo
    $stmt = $pdo->query("SELECT * FROM temas_grupo");
    $temasGrupo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'tiposPost' => $tiposPost,
        'temasGrupo' => $temasGrupo,
        'countTipos' => count($tiposPost),
        'countTemas' => count($temasGrupo)
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>