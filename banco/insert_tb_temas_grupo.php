<?php
include_once('./../conexao.php');

// Inicialização de variáveis
$nome_temas = "";
$descricao_temas = "";

// Dados do formulário
$nome_temas = $_POST['nome_temas'];
$descricao_temas = $_POST['descricao_temas'];

// Validação básica
if (empty($nome_temas)) {
    die("Nome do tema é obrigatório!");
}

// Query completa
$query = "INSERT INTO temas_grupo (
    nome_temas,
    descricao_temas
) VALUES (?, ?)";

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $nome_temas);
$stmt->bindValue(2, $descricao_temas);

if ($stmt->execute()) {
    header("Location: lista_temas.php?sucesso=1");
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao cadastrar tema.";
}
?>
