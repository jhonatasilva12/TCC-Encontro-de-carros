<?php
include_once('./db_connect.php');

$cpf_user = $_POST['cpf_user'] ?? null; // Opcional
$data_nasc_user = $_POST['data_nasc_user'];
$email_user = $_POST['email_user'];
$nome_user = $_POST['nome_user'];
$senha_user = password_hash($_POST['senha_user'], PASSWORD_DEFAULT);
$sobrenome_user = $_POST['sobrenome_user'] ?? null; // Opcional
$telefone_user = $_POST['telefone_user'] ?? null; // Opcional

$query = "INSERT INTO tb_user (cpf_user, data_nasc_user, email_user, nome_user, senha_user, sobrenome_user, telefone_user) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $cpf_user);
$stmt->bindValue(2, $data_nasc_user);
$stmt->bindValue(3, $email_user);
$stmt->bindValue(4, $nome_user);
$stmt->bindValue(5, $senha_user);
$stmt->bindValue(6, $sobrenome_user);
$stmt->bindValue(7, $telefone_user);

if ($stmt->execute()) {
    echo "Usuário inserido com sucesso!";
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao inserir usuário.";
}
?>