<?php
include_once('./../conexao.php');

// Inicialização de variáveis 
$fk_id_evento = "";
$fk_id_user = "";

// Dados do formulário
$fk_id_evento = $_POST['fk_id_evento'];
$fk_id_user = $_POST['fk_id_user'];

// Query completa 
$query = "INSERT INTO evento_user (
    fk_id_evento,
    fk_id_user
) VALUES (?, ?)";

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $fk_id_evento);
$stmt->bindValue(2, $fk_id_user);

if ($stmt->execute()) {
    header("Location: ver_evento.php?id=" . $fk_id_evento . "&inscricao=sucesso");
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao inscrever no evento.";
}
?>
