<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['emailo'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        die("Preencha email e senha");
    }

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Buscar usuário
        $stmt = $conn->prepare("SELECT id_user, nome_user, senha_user FROM tb_user WHERE email_user = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha_user'])) {
            // Criar sessão
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_nome'] = $user['nome_user'];
            $_SESSION['logged_in'] = true;

            // Redirecionar para área logada
            header("Location: ../navbar.php");
            exit();
        } else {
            header("Location: ../login.html?credenciais=invalidas");
        }
    } catch(PDOException $e) {
        die("Erro no login: " . $e->getMessage());
    }
}
?>