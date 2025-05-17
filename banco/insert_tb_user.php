<?php
session_start();
require_once 'db_connect.php'; // Arquivo com configurações do banco

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coletar dados do formulário
    $nome = $_POST['firstiname'] ?? '';
    $sobrenome = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['number'] ?? '';
    $senha = $_POST['password'] ?? '';
    $confirmacao = $_POST['confirmpassword'] ?? '';
    $genero = $_POST['gender'] ?? '';

    // Validações básicas
    if (empty($nome) || empty($email) || empty($senha)) {
        die("Preencha todos os campos obrigatórios");
    }

    if ($senha !== $confirmacao) {
        die("As senhas não coincidem");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email inválido");
    }

    // Conexão com o banco
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar se email já existe
        $stmt = $conn->prepare("SELECT id_user FROM tb_user WHERE email_user = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            die("Este email já está cadastrado");
        }

        // Hash da senha (nunca armazene senhas em texto puro)
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir novo usuário
        $stmt = $conn->prepare("INSERT INTO tb_user 
                               (nome_user, sobrenome_user, email_user, telefone_user, senha_user) 
                               VALUES (?, ?, ?, ?, ?)");
        
        $stmt->execute([$nome, $sobrenome, $email, $telefone, $senhaHash]);

        // Redirecionar para login com sucesso
        header("Location: ../login.html?cadastro=sucesso");
        exit();
        
    } catch(PDOException $e) {
        die("Erro no cadastro: " . $e->getMessage());
    }
}
?>