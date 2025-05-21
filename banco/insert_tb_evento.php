<?php
include_once('./../conexao.php');
session_start();

// Processamento da imagem
$imagem_evento = null;
if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] == 0) {
    $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['imagem_evento']['type'], $permitidos)) {
        header("Location: ../index.php?erro=ext+img");
        exit;
    }
    
    if ($_FILES['imagem_evento']['size'] > 2097152) {
        header("Location: ../index.php.php?erro=img+grande");
        exit;
    }

    $ext = pathinfo($_FILES['imagem_evento']['name'], PATHINFO_EXTENSION);
    $nome_imagem = 'evento_' . uniqid() . '.' . $ext;
    $diretorio = "../uploads/eventos/";

    if (!move_uploaded_file($_FILES['imagem_evento']['tmp_name'], $diretorio . $nome_imagem)) {
        header("Location: ../index.php?erro=mover+img");
        exit;
    }
    $imagem_evento = $nome_imagem;
}

// Formatação das datas
$data_inicio = $_POST['event-start-date'];
if (!empty($_POST['event-start-time'])) {
    $data_inicio .= ' ' . $_POST['event-start-time'];
}

$data_termino = $_POST['event-end-date'] ?? null;
if ($data_termino && !empty($_POST['event-end-time'])) {
    $data_termino .= ' ' . $_POST['event-end-time'];
}

try {
    $pdo->beginTransaction();
    
    $query = "INSERT INTO tb_evento (
        nome_evento,
        img_evento,
        descricao_evento,
        data_inicio_evento,
        data_termino_evento,
        horario_inicio,
        hora_termino,
        valor_pedestre,
        valor_exposicao,
        fk_id_criador,
        data_post
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        $_POST['event-name'],
        $imagem_evento,
        $_POST['event-description'],
        $data_inicio,
        $data_termino,
        $_POST['event-start-time'] ?? null,
        $_POST['event-end-time'] ?? null,
        $_POST['event-pedestrian-price'],
        $_POST['event-exhibition-price'],
        $_SESSION['user_id']
    ]);
    
    $eventoId = $pdo->lastInsertId();
    $pdo->commit();
    
    header("Location: ../index.php");
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Erro ao criar evento: " . $e->getMessage());
    header("Location: ../index.php?erro=criar+evento");
}
?>