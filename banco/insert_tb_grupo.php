<?php
include_once('./../conexao.php');
session_start();

// Processamento da imagem
$imagem_grupo = null;
if (isset($_FILES['imagem_grupo']) && $_FILES['imagem_grupo']['error'] == 0) {
    $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['imagem_grupo']['type'], $permitidos)) {
        header("Location: ../grupo.php?erro=img+incompativel");
        exit;
    }

    $ext = pathinfo($_FILES['imagem_grupo']['name'], PATHINFO_EXTENSION);
    $nome_imagem = 'grupo_' . uniqid() . '.' . $ext;
    $diretorio = "../assets/images/groups/";

    if (!move_uploaded_file($_FILES['imagem_grupo']['tmp_name'], $diretorio . $nome_imagem)) {
        header("Location: ../grupo.php?erro=mover+img");
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
    
    // insere relação com tema
    if (!empty($_POST['fk_id_temas_grupo'])) {
        $stmtTema = $pdo->prepare("INSERT INTO grupo_tegru (fk_id_grupo, fk_id_temas_grupo) VALUES (?, ?)");
        $stmtTema->execute([$grupoId, $_POST['fk_id_temas_grupo']]);
    }
    
    $pdo->commit();
    header("Location: ../grupo.php?id=" . $grupoId . "&sucesso=1");
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Erro ao criar grupo: " . $e->getMessage());
    header("Location: ../grupo.php?erro=criar+grupo");
}
?>
