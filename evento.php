<?php
require('includes/funcoes.php');
require_once('banco/db_connect.php');
require_once('banco/autentica.php');
$meetcar = new MeetCarFunctions();

$userId = $_SESSION['user_id'] ?? null; 

// pega o id do evento da url
$eventId = $_GET['id'] ?? null;

if (!$eventId) {
    header("Location: ./index.php?evento=nao+existe");
    exit;
}

// busca o evento em específico
$evento = $meetcar->buscarEventoPorId($userId, $eventId);

if (!$evento) {
    header("Location: ./index.php?erro=evento+nao+encontrado");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <script src='https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js'></script>
    <link rel="icon" href="./assets/images/logo.png">
    <title>MeetCar - <?= htmlspecialchars($evento['nome_evento'] ?? 'Evento') ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="geral">
        <main class="hero">
            <div class="post-detalhe">
                <!-- Conteúdo do evento -->
                <?php require('includes/eventos.php'); ?>
            </div>
        </main>
        
    <?php include('includes/navbar.php'); ?>
    </div>

    <?php require('includes/user_box.php'); ?>
    

    <script src="assets/js/index.js?v=<?= time() ?>"></script>
</body>
</html>
