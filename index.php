<?php
require_once('banco/db_connect.php'); 
require_once('banco/autentica.php');
require_once('includes/search.php');
require_once('includes/criacao.php');
require_once('includes/navbar.php');
require_once('funcoes.php');
$meetcar = new MeetCarFunctions();
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <title>Navbar</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    
  </head>
  <body>

    <script defer src="assets/js/index.js"></script>

  <div class="geral">

    <!--local correto dos require include (caso não dê certo)-->

      
    <main class="hero">

<!-----------post---------->
    <?require_once('includes/posts.php');?>

<!----------evento--------->
  <?require_once('includes/eventos.php');?>

    </main>

    <!--local correto dos require include (caso não dê certo)-->
  
  </div> <!--fim geral-->
  <!--local correto dos require include (caso não dê certo)-->

    
  </body>
</html>
