<?php
include_once('./db_connect.php');

$fk_id_grupo = $_POST['fk_id_grupo'];
$fk_id_user = $_POST['fk_id_user'];

$query = "INSERT INTO user_grupo (fk_id_grupo, fk_id_user) VALUES (?, ?)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $fk_id_grupo);
$stmt->bindValue(2, $fk_id_user);

if ($stmt->execute()) {
    echo "Usuário vinculado ao grupo com sucesso!";
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao vincular usuário ao grupo.";
}
?>