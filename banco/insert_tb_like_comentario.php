<?php
include_once('./../conexao.php');

// Inicialização de variáveis
$fk_id_comentario = "";
$fk_id_user = "";

// Dados do formulário
$fk_id_comentario = $_POST['fk_id_comentario'];
$fk_id_user = $_POST['fk_id_user'];

// Query completa
$query = "INSERT INTO like_comentario (
    fk_id_comentario,
    fk_id_user
) VALUES (?, ?)";

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $fk_id_comentario);
$stmt->bindValue(2, $fk_id_user);

if ($stmt->execute()) {
    header("Location: ver_post.php?id=" . $_POST['id_post_original'] . "&curtida_comentario=sucesso");
} else {
    print_r($stmt->errorInfo());
    echo "Erro: Não foi possível curtir o comentário.";
}
?>
