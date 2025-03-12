// insert_tb_grupo.php
<?php
include_once('./db_connect.php');

$descricao_grupo = $_POST['descricao_grupo'] ?? null; // Opcional
$fk_id_user = $_POST['fk_id_user'];
$nome_grupo = $_POST['nome_grupo'];

$query = "INSERT INTO tb_grupo (descricao_grupo, fk_id_user, nome_grupo) 
          VALUES (?, ?, ?)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $descricao_grupo);
$stmt->bindValue(2, $fk_id_user);
$stmt->bindValue(3, $nome_grupo);

if ($stmt->execute()) {
    echo "Grupo inserido com sucesso!";
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao inserir grupo.";
}
?>

