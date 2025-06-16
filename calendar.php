<!DOCTYPE html>
<?php
require_once('banco/autentica.php');
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');

$meetcar = new MeetCarFunctions();

$userId = $_SESSION['user_id']; ?>

<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script> <!--icones-->
    <script src='https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js'></script> <!--zoom de imagem-->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet"> <!--calendario-->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script> <!--calendario-->
    <link rel="icon" href="./assets/images/logo.png">
    <title>MeetCar</title>
    <link rel="stylesheet" href="assets/css/styles.css">

  </head>
  <body style="overflow-y: scroll;">

    <div class="geral">
        
      <main class="hero">

        <div id="calendar">
          <!-- API do calendario -->
        </div>

      </main>

      <?php require_once('includes/navbar.php');
      require_once('includes/search-box.php'); ?>

    </div> <!--fim geral-->

    <?php
    require_once('includes/user_box.php');
    require_once('includes/criacao.php'); //Ele fica aqui em baixo (antes de </body>) pra ficar sobreposto de tudo
    ?> 

    <script src="assets/js/index.js?v=<?= time() ?>"></script>
    
  </body>
</html>
