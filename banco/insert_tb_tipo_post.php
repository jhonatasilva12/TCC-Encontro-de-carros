<?php
include_once('./../conexao.php');

// Inicializa variáveis 
$nome_tipo_post = ""; 

// Recebe dados do formulário 
if (isset($_POST['nome_tipo_post'])) {
    $nome_tipo_post = $_POST['nome_tipo_post'];
}

// Validação básica 
if (empty($nome_tipo_post)) {
    die("Nome do tipo é obrigatório!");
}

// Query 
$query = "INSERT INTO tb_tipo_post (nome_tipo_post) VALUES (?)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $nome_tipo_post); 

// Execução e redirecionamento 
if ($stmt->execute()) {
    header("Location: lista_tipos_post.php?sucesso=1");
} else {
    print_r($stmt->errorInfo()); // Mesmo tratamento de erro
    echo "Erro ao cadastrar tipo de post.";
}
?>
