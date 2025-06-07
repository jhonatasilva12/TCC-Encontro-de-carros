<!DOCTYPE html>
<?php
require_once('banco/autentica.php');
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');

$meetcar = new MeetCarFunctions();

$groupId = $_GET['id'] ?? null;

$posts = $meetcar->buscarPostsPorGrupo($groupId); 
$eventos = $meetcar->buscarEventosPorGrupo($groupId);
$grupo = $meetcar->buscarGrupoPorId($groupId);

if (!$groupId) {
    header("Location: ./index.php?grupo=nao+existe");
    exit;
}

$conteudos = [];

foreach ($posts as $post) {
    $conteudos[] = [
        'tipo' => 'post',
        'data' => $post['data_post'],
        'dados' => $post
    ];
}

foreach ($eventos as $evento) {
    $conteudos[] = [
        'tipo' => 'evento',
        'data' => $evento['data_post'],
        'dados' => $evento
    ];
}

// ordena por data (do mais novo pro mais antigo)
usort($conteudos, function($a, $b) {
    return strtotime($b['data']) - strtotime($a['data']);
});
?>

<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
        <script src='https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js'></script>
        <link rel="icon" href="./assets/images/logo.png">
        <title>MeetCar</title>
        <link rel="stylesheet" href="assets/css/styles.css">

    </head>
    <body>

    <div class="geral">
        <main class="hero">

            <?php require_once('includes/feed.php'); ?> <!--posts e eventos organizados por ordem de chegada-->

        </main>

        <?php require_once('includes/navbar.php');
        require_once('includes/search-box.php');?>

    </div> <!--fim geral-->

    <?php
    require_once('includes/user_box.php');
    
    require_once('includes/criacao.php'); //Ele fica aqui em baixo (antes de </body>) pra ficar sobreposto de tudo
    ?>

    <script src="assets/js/index.js?v=<?= time() ?>"></script>
    
  </body>
</html>
