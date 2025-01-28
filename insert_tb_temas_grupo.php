<?php
include_once('./db_connect.php');

$descricao_temas = $_POST['descricao_temas'];
$nome_temas = $_POST['nome_temas'];

$query = "INSERT INTO temas_grupo (descricao_temas, nome_temas) VALUES (?, ?)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $descricao_temas);
$stmt->bindValue(2, $nome_temas);

if ($stmt->execute()) {
    echo "Tema de grupo inserido com sucesso!";
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao inserir tema de grupo.";
}
?>