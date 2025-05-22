<?php
include_once('./db_connect.php');
session_start();


// Processa upload da imagem (se existir)
if (isset($_FILES['imagem_post']) && $_FILES['imagem_post']['error'] == 0) {
    $extensao = pathinfo($_FILES['imagem_post']['name'], PATHINFO_EXTENSION);
    $nome_imagem = uniqid() . '.' . $extensao; 
    $diretorio = "assets/images/banco/";

    if (move_uploaded_file($_FILES['imagem_post']['tmp_name'], $diretorio . $nome_imagem)) {
        $imagem_post = $nome_imagem;
    } else {
        die("Erro ao enviar imagem.");
    }
}

// Dados do formulário
$titulo_post = $_POST['titulo_post'] ?? null;
$texto_post = $_POST['texto_post'];
$fk_id_user = $_SESSION['user_id'];
$fk_id_tipo_post = $_POST['fk_id_tipo_post'];

// Query COMPLETA 
$query = "INSERT INTO tb_post (
    fk_id_user, 
    fk_id_tipo_post, 
    texto_post, 
    imagem_post, 
    titulo_post
) VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $fk_id_user);
$stmt->bindValue(2, $fk_id_tipo_post);
$stmt->bindValue(3, $texto_post);
$stmt->bindValue(4, $imagem_post);
$stmt->bindValue(5, $titulo_post);

if ($stmt->execute()) {
    header("Location: ../index.php");
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao criar post.";
}
?>
