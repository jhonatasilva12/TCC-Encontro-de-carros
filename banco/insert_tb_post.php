<?php
include_once './db_connect.php';
session_start();

$maxFileSize = 50 * 1024 * 1024; //50mb
$uploadDir = "../assets/images/posts/";
$imagem_post = null;

if (isset($_FILES['imagem_post']) && $_FILES['imagem_post']['error'] == UPLOAD_ERR_OK) {
    
    if ($_FILES['imagem_post']['size'] > $maxFileSize) {
        $_SESSION['erro'] = "Arquivo muito grande. Tamanho máximo: 50MB";
        header("Location: ../index.php?erro=tamanho_arquivo");
        exit;
    }

    $permitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'video/mp4' => 'mp4',
        'video/quicktime' => 'mov',
        'video/x-msvideo' => 'avi'
    ];
    
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($fileInfo, $_FILES['imagem_post']['tmp_name']);
    finfo_close($fileInfo);

    if (!array_key_exists($mime, $permitidos)) {
        $_SESSION['erro'] = "Tipo de arquivo não permitido. Formatos aceitos: JPG, PNG, GIF, MP4, MOV, AVI";
        header("Location: ../index.php?erro=tipo_arquivo");
        exit;
    }

    $extensao = $permitidos[$mime];
    $nomeArquivo = 'post_' . uniqid() . '.' . $extensao;

    if (!move_uploaded_file($_FILES['imagem_post']['tmp_name'], $uploadDir . $nomeArquivo)) {
        $_SESSION['erro'] = "Erro ao salvar o arquivo. Verifique as permissões.";
        header("Location: ../index.php?erro=upload_falhou");
        exit;
    }
    
    $imagem_post = $nomeArquivo;
}

if (empty($_POST['fk_id_tipo_post']) || empty($_POST['texto_post'])) {
    $_SESSION['erro'] = "Tipo de post e texto são obrigatórios";
    header("Location: ../index.php?erro=campos_obrigatorios");
    exit;
}

try {
    $pdo->beginTransaction();
    
    $query = "INSERT INTO tb_post (
        titulo_post, 
        imagem_post, 
        texto_post, 
        data_post,
        fk_id_user, 
        fk_id_tipo_post, 
        fk_id_grupo
    ) VALUES (?, ?, ?, NOW(), ?, ?, ?)";

    $stmt = $pdo->prepare($query);
    $success = $stmt->execute([
        $_POST['titulo_post'] ?? null,
        $imagem_post,
        $_POST['texto_post'],
        $_SESSION['user_id'],
        $_POST['fk_id_tipo_post'],
        $_POST['fk_id_grupo'] ?? null
    ]);
    
    if (!$success || $stmt->rowCount() === 0) {
        throw new Exception("Nenhuma linha afetada na inserção");
    }
    
    $pdo->commit();
    $_SESSION['sucesso'] = "Post criado com sucesso!";
    header("Location: ../index.php");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    
    if ($imagem_post && file_exists($uploadDir . $imagem_post)) {
        unlink($uploadDir . $imagem_post);
    }
    
    error_log("Erro no banco: " . $e->getMessage());
    
    $mensagem = "Erro ao criar post: ";
    if (strpos($e->getMessage(), 'foreign key') !== false) {
        $mensagem .= "Tipo de post inválido";
    } elseif (strpos($e->getMessage(), 'Column count') !== false) {
        $mensagem .= "Dados incompletos";
    } else {
        $mensagem .= "Problema no banco de dados";
    }
    
    $_SESSION['erro'] = $mensagem;
    header("Location: ../index.php?erro=bd_post");
    exit;
} catch (Exception $e) {
    error_log("Erro geral: " . $e->getMessage());
    $_SESSION['erro'] = "Erro inesperado: " . $e->getMessage();
    header("Location: ../index.php?erro=post_geral");
    exit;
}