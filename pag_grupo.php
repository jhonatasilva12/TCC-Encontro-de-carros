<?php

require_once('banco/db_connect.php'); 
require_once('banco/autentica.php');
require_once 'includes/funcoes.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$meetcar = new MeetCarFunctions();


$userId = $_SESSION['user_id'];

$grupos = $meetcar->buscarGrupos($userId);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeetCar - Grupos</title>
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="geral">

        <main class="hero">
            <h1>Comunidade de Grupos</h1>
            <?php if (!empty($grupos)) { ?>
                <?php foreach ($grupos as $grupo) {
                    include('includes/grupos.php');
                } ?>
            <?php } else { ?>
                <p>Nenhum grupo foi encontrado. Seja o primeiro a criar um com a ferramenta de criação!</p>
            <?php } ?>
        </main>

        <?php
        require_once('includes/user_box.php');
        require_once('includes/search-box.php');
        require_once('includes/navbar.php');
        require_once('includes/criacao.php');
        ?>
    </div>

    

    <script src="assets/js/index.js?v=<?= time() ?>"></script>
</body>
</html>
