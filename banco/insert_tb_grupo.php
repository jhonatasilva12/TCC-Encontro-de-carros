<?php
include_once('./../conexao.php');

// Inicialização de variáveis
$nome_grupo = "";
$descricao_grupo = null;
$imagem_grupo = null;
$fk_id_criador_grupo = "";
$fk_id_temas_grupo = ""; // Nova chave estrangeira

// Processamento da imagem
if (isset($_FILES['imagem_grupo']) && $_FILES['imagem_grupo']['error'] == 0) {
    $ext = pathinfo($_FILES['imagem_grupo']['name'], PATHINFO_EXTENSION);
    $nome_imagem = 'grupo_' . uniqid() . '.' . $ext;
    $diretorio = "../uploads/grupos/";

    if (!move_uploaded_file($_FILES['imagem_grupo']['tmp_name'], $diretorio . $nome_imagem)) {
        die("Erro ao enviar imagem.");
    }
    $imagem_grupo = $nome_imagem;
}

// Dados do formulário
$nome_grupo = $_POST['nome_grupo'];
$descricao_grupo = $_POST['descricao_grupo'] ?? null;
$fk_id_criador_grupo = $_POST['fk_id_criador_grupo'];
$fk_id_temas_grupo = $_POST['fk_id_temas_grupo']; // Novo campo

// Query completa 
$query = "INSERT INTO tb_grupo (
    nome_grupo,
    descricao_grupo,
    imagem_grupo,
    fk_id_criador_grupo,
    fk_id_temas_grupo
) VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $nome_grupo);
$stmt->bindValue(2, $descricao_grupo);
$stmt->bindValue(3, $imagem_grupo);
$stmt->bindValue(4, $fk_id_criador_grupo);
$stmt->bindValue(5, $fk_id_temas_grupo);

if ($stmt->execute()) {
    header("Location: grupo.php?id=" . $pdo->lastInsertId() . "&sucesso=1");
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao criar grupo.";
}
?>
