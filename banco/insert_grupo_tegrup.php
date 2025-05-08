<?php
include_once('./../conexao.php');

// Inicialização de variáveis
$fk_id_grupo = "";
$fk_id_temas_grupo = "";

// Dados do formulário
$fk_id_grupo = $_POST['fk_id_grupo'];
$fk_id_temas_grupo = $_POST['fk_id_temas_grupo'];

// Validação básica n sei se fiz certo, essa parte peguei pela internet 
if (empty($fk_id_grupo) || empty($fk_id_temas_grupo)) {
    die("IDs de grupo e tema são obrigatórios!");
}

// Query completa
$query = "INSERT INTO grupo_tegru (
    fk_id_grupo,
    fk_id_temas_grupo
) VALUES (?, ?)";

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $fk_id_grupo);
$stmt->bindValue(2, $fk_id_temas_grupo);

if ($stmt->execute()) {
    header("Location: grupo.php?id=" . $fk_id_grupo . "&tema=sucesso");
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao vincular tema ao grupo.";
}
?>
