<?php
include_once('./../conexao.php');

// Inicialização de variáveis 
$nome_user = "";
$sobrenome_user = "";
$email_user = "";
$senha_user = "";
$cpf_user = "";
$data_nasc_user = "";
$telefone_user = "";
$imagem_user = null;

// Processamento da foto 
if (isset($_FILES['imagem_user']) && $_FILES['imagem_user']['error'] == 0) {
    $ext = pathinfo($_FILES['imagem_user']['name'], PATHINFO_EXTENSION);
    $nome_imagem = 'user_' . md5(time()) . '.' . $ext;
    $diretorio = "../uploads/usuarios/";

    if (!move_uploaded_file($_FILES['imagem_user']['tmp_name'], $diretorio . $nome_imagem)) {
        die("Erro ao salvar a imagem.");
    }
    $imagem_user = $nome_imagem;
}

// Dados do formulário
$nome_user = $_POST['nome_user'];
$sobrenome_user = $_POST['sobrenome_user'] ?? null;
$email_user = $_POST['email_user'];
$senha_user = password_hash($_POST['senha_user'], PASSWORD_BCRYPT);
$cpf_user = $_POST['cpf_user'] ?? null;
$data_nasc_user = $_POST['data_nasc_user'] ?? null;
$telefone_user = $_POST['telefone_user'] ?? null;

// Query completa 
$query = "INSERT INTO tb_user (
    nome_user,
    sobrenome_user,
    email_user,
    senha_user,
    cpf_user,
    data_nasc_user,
    telefone_user,
    imagem_user
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $nome_user);
$stmt->bindValue(2, $sobrenome_user);
$stmt->bindValue(3, $email_user);
$stmt->bindValue(4, $senha_user);
$stmt->bindValue(5, $cpf_user);
$stmt->bindValue(6, $data_nasc_user);
$stmt->bindValue(7, $telefone_user);
$stmt->bindValue(8, $imagem_user);

if ($stmt->execute()) {
    header("Location: login.php?cadastro=sucesso");
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao cadastrar usuário.";
}
?>
