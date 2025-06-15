<?php
include_once './db_connect.php';
session_start();

$maxFileSize = 50 * 1024 * 1024; //50mb
$diretorio = "../assets/images/events/";
$imagem_evento = null;

if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] == UPLOAD_ERR_OK) {

    if ($_FILES['imagem_evento']['size'] > $maxFileSize) {
        $_SESSION['erro'] = "Arquivo muito grande. Tamanho máximo permitido: 10MB";
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
    $mime = finfo_file($fileInfo, $_FILES['imagem_evento']['tmp_name']);
    finfo_close($fileInfo);

    if (!array_key_exists($mime, $permitidos)) {
        $_SESSION['erro'] = "Tipo de arquivo não permitido. Formatos aceitos: JPG, PNG, GIF, MP4, MOV, AVI";
        header("Location: ../index.php?erro=tipo_arquivo");
        exit;
    }

    $extensao = $permitidos[$mime];
    $nome_imagem = 'event_' . uniqid() . '.' . $extensao;

    if (!move_uploaded_file($_FILES['imagem_evento']['tmp_name'], $diretorio . $nome_imagem)) {
        $_SESSION['erro'] = "Erro ao salvar o arquivo. Verifique as permissões do diretório.";
        header("Location: ../index.php?erro=upload_falhou");
        exit;
    }
    
    $imagem_evento = $nome_imagem;
}

try {
    $pdo->beginTransaction();
    
    //formatação das datas para o padrão mysql
    $data_inicio = date('Y-m-d H:i:s', strtotime($_POST['data_inicio_evento']));
    $data_termino = !empty($_POST['data_termino_evento']) ? 
        date('Y-m-d H:i:s', strtotime($_POST['data_termino_evento'])) : null;

    $query = "INSERT INTO tb_evento (
        nome_evento,
        img_evento,
        descricao_evento,
        data_post,
        data_inicio_evento,
        data_termino_evento,
        valor_pedestre,
        valor_exposicao,
        rua_evento,
        numero_evento,
        cidade_evento,
        estado_evento,
        fk_id_criador,
        fk_id_grupo
    ) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($query);
    $success = $stmt->execute([
        $_POST['nome_evento'],
        $imagem_evento,
        $_POST['descricao_evento'],
        $data_inicio,
        $data_termino,
        $_POST['valor_pedestre'],
        $_POST['valor_exposicao'],
        $_POST['rua'],
        $_POST['numero'],
        $_POST['cidade'],
        $_POST['estado'],
        $_SESSION['user_id'],
        $_POST['fk_id_grupo'] ?? null
    ]);
    
    if (!$success || $stmt->rowCount() === 0) {
        throw new Exception("Falha na inserção do evento");
    }
    
    $eventoId = $pdo->lastInsertId();
    $pdo->commit();
    
    $_SESSION['sucesso'] = "Evento criado com sucesso!";
    header("Location: ../index.php");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    
    if (!empty($imagem_evento) && file_exists($diretorio . $imagem_evento)) {
        unlink($diretorio . $imagem_evento);
    }
    
    error_log("Erro no banco ao criar evento: " . $e->getMessage());
    
    $mensagem = "Erro ao criar evento: ";
    if (strpos($e->getMessage(), 'foreign key') !== false) {
        $mensagem .= "Dados de relacionamento inválidos";
    } elseif (strpos($e->getMessage(), 'Column count') !== false) {
        $mensagem .= "Dados incompletos";
    } else {
        $mensagem .= "Erro no banco de dados";
    }
    
    $_SESSION['erro'] = $mensagem;
    header("Location: ../index.php?erro=bd_evento");
    exit;
} catch (Exception $e) {
    error_log("Erro geral ao criar evento: " . $e->getMessage());
    $_SESSION['erro'] = "Erro inesperado ao criar evento";
    header("Location: ../index.php?erro=evento_geral");
    exit;
}
?>