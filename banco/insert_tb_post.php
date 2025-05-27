<?php
include_once './db_connect.php';
session_start();

// Processa upload da imagem
$imagem_post = null;
if (isset($_FILES['imagem_post']) && $_FILES['imagem_post']['error'] == UPLOAD_ERR_OK) {
    
    // Verifica o tipo de arquivo
    $permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($fileInfo, $_FILES['imagem_post']['tmp_name']);
    finfo_close($fileInfo);

    if (!in_array($mime, $permitidos)) {
        $_SESSION['erro'] = "Tipo de arquivo não permitido. Apenas JPG, PNG e GIF são aceitos.";
        header("Location: ../index.php?erro=img_tipo");
        exit;
    }

    $extensao = pathinfo($_FILES['imagem_post']['name'], PATHINFO_EXTENSION);
    $nome_imagem = 'post_' . uniqid() . '.' . $extensao; 
    $diretorio = "../assets/images/posts/";

    if (!move_uploaded_file($_FILES['imagem_post']['tmp_name'], $diretorio . $nome_imagem)) {
        $_SESSION['erro'] = "Erro ao salvar a imagem";
        header("Location: ../index.php?erro=img_salvar");
        exit;
    }
    $imagem_post = $nome_imagem;
}

try {
    $pdo->beginTransaction();
    
    // Validação dos dados
    if (empty($_POST['fk_id_tipo_post']) || empty($_POST['texto_post'])) {
        throw new Exception('Tipo de post e texto são obrigatórios');
    }

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
        $_POST['titulo_post'] ?? null // Corrigido: adiciona o título (ou null se não existir)
    ]);
    
    $pdo->commit();
    $_SESSION['sucesso'] = "Post criado com sucesso!";
    header("Location: ../index.php");
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Erro ao criar post: " . $e->getMessage());
    $_SESSION['erro'] = "Erro ao criar post: " . 
        (strpos($e->getMessage(), 'foreign key') ? "Tipo de post inválido" : 
        (strpos($e->getMessage(), 'Column count') ? "Dados incompletos" : "Erro no banco de dados"));
    header("Location: ../index.php?erro=post");
    exit;
} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
    header("Location: ../index.php?erro=post");
    exit;
}