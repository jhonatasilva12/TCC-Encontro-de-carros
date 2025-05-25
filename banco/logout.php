<?php
session_start();

// Limpar todos os dados da sessão
$_SESSION = array();

// Se desejar destruir a sessão completamente, apague também o cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir a sessão
session_destroy();

// Redirecionar para login
header("Location: ../login.html");
exit();
?>