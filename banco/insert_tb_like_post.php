<?php
include_once('./../conexao.php');

// Inicialização de variáveis 
$fk_id_post = "";
$fk_id_user = "";

// Dados do formulário 
$fk_id_post = $_POST['fk_id_post'];
$fk_id_user = $_POST['fk_id_user'];

// Query completa
$query = "INSERT INTO like_post (
    fk_id_post,
    fk_id_user
) VALUES (?, ?)";

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $fk_id_post);
$stmt->bindValue(2, $fk_id_user);

if ($stmt->execute()) {
    header("Location: ver_post.php?id=" . $fk_id_post . "&curtida=sucesso");
} else {
    print_r($stmt->errorInfo());
    echo "Erro: Não foi possível registrar a curtida.";
}
?>
