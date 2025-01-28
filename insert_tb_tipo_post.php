<?php
include_once('./db_connect.php');

$nome_tipo_post = $_POST['nome_tipo_post'];

$query = "INSERT INTO tb_tipo_post (nome_tipo_post) VALUES (?)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $nome_tipo_post);

if ($stmt->execute()) {
    echo "Tipo de post inserido com sucesso!";
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao inserir tipo de post.";
}
?>
