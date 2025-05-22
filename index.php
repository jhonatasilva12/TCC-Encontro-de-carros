<?php
require_once('banco/db_connect.php'); 
require_once('banco/autentica.php');
require_once('includes/search.php');
require_once('includes/criacao.php');
require_once('includes/navbar.php');
require_once('includes/funcoes.php');
$meetcar = new MeetCarFunctions();
$posts = $meetcar->buscarPosts();
$eventos = $meetcar->buscarEventos();
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <link rel="icon" href="./assets/images/logo.png">
    <title>MeetCar</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    
  </head>
  <body>

    <script defer src="assets/js/index.js"></script>

  <div class="geral">

    <!--local correto dos require include (caso não dê certo)-->

      
    <main class="hero">

<!-----------post---------->
    <?php
    require_once('includes/posts.php');
    ?>

<!----------evento--------->
  <?php
  require_once('includes/eventos.php');
  ?>

    </main>

    <!--local correto dos require include (caso não dê certo)-->
  
  </div> <!--fim geral-->
  <!--local correto dos require include (caso não dê certo)-->

    
  </body>
</html>
