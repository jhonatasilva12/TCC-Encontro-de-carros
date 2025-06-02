<!DOCTYPE html>
<?php
require_once('banco/autentica.php');
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');
require_once('includes/search-box.php');
$meetcar = new MeetCarFunctions();
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

      
      <?php require_once('includes/navbar.php'); ?>

    </div> <!--fim geral-->

  
    <?php require_once('includes/criacao.php'); ?> <!--Ele fica aqui em baixo (antes de </body>) pra ficar sobreposto de tudo-->

    <script src="assets/js/index.js?v=<?= time() ?>"></script>
    
  </body>
</html>
