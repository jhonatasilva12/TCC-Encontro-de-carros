<?php
include_once './db_connect.php';
session_start();

$imagem_grupo = null;
if (isset($_FILES['imagem_grupo']) && $_FILES['imagem_grupo']['error'] == UPLOAD_ERR_OK) { // Corrigido
    
    $permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($fileInfo, $_FILES['imagem_grupo']['tmp_name']); // Corrigido
    finfo_close($fileInfo);

    if (!in_array($mime, $permitidos)) {
        $_SESSION['erro'] = "Tipo de arquivo não permitido";
        header("Location: ../index.php?erro=img_tipo");
        exit;
    }

    $extensao = pathinfo($_FILES['imagem_grupo']['name'], PATHINFO_EXTENSION); // Corrigido
    $nome_imagem = 'group_' . uniqid() . '.' . $extensao; 
    $diretorio = "../assets/images/groups/";

    if (!move_uploaded_file($_FILES['imagem_grupo']['tmp_name'], $diretorio . $nome_imagem)) { // Corrigido
        $_SESSION['erro'] = "Erro ao salvar a imagem";
        header("Location: ../index.php?erro=img_salvar");
        exit;
    }
    $imagem_grupo = $nome_imagem;
}

try {
    $pdo->beginTransaction();
    
    // Insere o grupo
    $query = "INSERT INTO tb_grupo (
        nome_grupo,
        descricao_grupo,
        img_grupo,
        fk_id_user,
        data_criacao
    ) VALUES (?, ?, ?, ?, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        $_POST['nome_grupo'],
        $_POST['descricao_grupo'] ?? null,
        $imagem_grupo,
        $_SESSION['user_id']
    ]);
    
    $grupoId = $pdo->lastInsertId();
    
    // insere relação dele com o tema
    if (!empty($_POST['fk_id_temas_grupo'])) {
        $stmtTema = $pdo->prepare("INSERT INTO grupo_tegru (fk_id_grupo, fk_id_temas_grupo) VALUES (?, ?)");
        $stmtTema->execute([
            $grupoId,
            $_POST['fk_id_temas_grupo']
        ]);
    }

    $stmtTema = $pdo->prepare("INSERT INTO user_grupo (fk_id_grupo, fk_id_user) VALUES (?, ?)");
    $stmtTema->execute([
        $grupoId,
        $_SESSION['user_id']
    ]);
    
    $pdo->commit();
    header("Location: ../grupo.php?id=" . $grupoId);
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Erro ao criar grupo: " . $e->getMessage());
    header("Location: ../grupo.php?erro=criar+grupo");
}
?>
