<?php
include_once './db_connect.php';
session_start();

// Processamento da imagem
$imagem_evento = null;
if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] == UPLOAD_ERR_OK) {
    
    // Verifica o tipo de arquivo
    $permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($fileInfo, $_FILES['imagem_evento']['tmp_name']);
    finfo_close($fileInfo);

    if (!in_array($mime, $permitidos)) {
        $_SESSION['erro'] = "Tipo de arquivo não permitido. Apenas JPG, PNG e GIF são aceitos.";
        header("Location: ../index.php?erro=img_tipo");
        exit;
    }

    $extensao = pathinfo($_FILES['imagem_evento']['name'], PATHINFO_EXTENSION);
    $nome_imagem = 'event_' . uniqid() . '.' . $extensao; 
    $diretorio = "../assets/images/events/";

    // Verifica/Cria diretório
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    // Debug: Log do caminho completo
    error_log("Tentando salvar em: " . $diretorio . $nome_imagem);

    if (!move_uploaded_file($_FILES['imagem_evento']['tmp_name'], $diretorio . $nome_imagem)) {
        error_log("Erro ao mover arquivo: " . print_r(error_get_last(), true));
        $_SESSION['erro'] = "Erro ao salvar a imagem. Verifique permissões!";
        header("Location: ../index.php?erro=img_salvar");
        exit;
    }
    $imagem_evento = $nome_imagem;
}

try {
    $pdo->beginTransaction();
    
    // Formata as datas para o padrão MySQL
    $data_inicio = date('Y-m-d H:i:s', strtotime($_POST['data_inicio_evento']));
    $data_termino = !empty($_POST['data_termino_evento']) ? 
        date('Y-m-d H:i:s', strtotime($_POST['data_termino_evento'])) : null;

    $query = "INSERT INTO tb_evento (
        nome_evento,
        img_evento,
        descricao_evento,
        data_inicio_evento,
        data_termino_evento,
        valor_pedestre,
        valor_exposicao,
        fk_id_criador
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        $_POST['nome_evento'],
        $imagem_evento,
        $_POST['descricao_evento'],
        $data_inicio,
        $data_termino,
        $_POST['valor_pedestre'],
        $_POST['valor_exposicao'],
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