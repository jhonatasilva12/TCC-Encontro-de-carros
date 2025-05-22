<?php
include_once('./../conexao.php');
session_start();

// Processa upload da imagem
$imagem_post = null;
if (isset($_FILES['imagem_post']) && $_FILES['imagem_post']['error'] == 0) {
    // Verifica o tipo de arquivo
    $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['imagem_post']['type'], $permitidos)) {
        header("Location: ../index.php?erro=ext+img");
        exit;
    }

    $extensao = pathinfo($_FILES['imagem_post']['name'], PATHINFO_EXTENSION);
    $nome_imagem = uniqid() . '.' . $extensao; 
    $diretorio = "assets/images/posts/";

    if (!move_uploaded_file($_FILES['imagem_post']['tmp_name'], $diretorio . $nome_imagem)) {
        header("Location: ../index.php?erro=img+grupo");
        exit;
    }
    $imagem_post = $nome_imagem;
}

try {
    $pdo->beginTransaction();
    
    // Insere o post
    $query = "INSERT INTO tb_post (
        fk_id_user, 
        fk_id_tipo_post, 
        texto_post, 
        imagem_post, 
        titulo_post, 
        data_post
    ) VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['fk_id_tipo_post'],
        $_POST['texto_post'],
        $imagem_post,
        $_POST['titulo_post'] ?? null
    ]);
    
    $pdo->commit();
    header("Location: ../index.php");
} catch (PDOException $e) {
    $pdo->rollBack();
    // Log do erro
    error_log("Erro ao criar post: " . $e->getMessage());
    header("Location: ../index.php?erro=criar+post");
}
?>