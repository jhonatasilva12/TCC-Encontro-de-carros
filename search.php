<!DOCTYPE html>
<?php
require_once('banco/autentica.php');
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');

$meetcar = new MeetCarFunctions();
$userId = $_SESSION['user_id'] ?? null;

// Processar a pesquisa
$termo_pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$resultados = [];

if (!empty($termo_pesquisa)) {
    // Buscar em eventos
    $resultados['eventos'] = $meetcar->buscarEventosPorTermo($termo_pesquisa, $userId);
    
    // Buscar em grupos
    $resultados['grupos'] = $meetcar->buscarGruposPorTermo($termo_pesquisa, $userId);
    
    // Buscar em usuários
    $resultados['usuarios'] = $meetcar->buscarUsuariosPorTermo($termo_pesquisa);
    
    // Buscar em posts
    $resultados['posts'] = $meetcar->buscarPostsPorTermo($termo_pesquisa, $userId);
}
?>

<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
        <script src='https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js'></script>
        <link rel="icon" href="./assets/images/logo.png">
        <title>MeetCar - Pesquisa</title>
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>
    <body>
        
        <div class="geral">
            <main class="hero">
                <div class="termo-pesquisado">
                    Resultados para: <strong><?= htmlspecialchars($termo_pesquisa) ?></strong>
                </div>
                

                <?php if (!empty($termo_pesquisa)) { ?>

                
                <!------------------------------- grupos ---------------------------------------->
                <div class="tab-content" id="tab-3">
                    <div class="categoria-resultados">
                        <?php if (!empty($resultados['grupos'])) { ?>
                            <div class="lista-resultados">
                                <?php foreach ($resultados['grupos'] as $grupo) {
                                    include('includes/grupos.php');
                                } ?>
                            </div>
                        <?php } else { ?>
                            <p class="sem-resultados">Nenhum grupo encontrado</p>
                        <?php } ?>
                    </div>
                </div>

                
                <!------------------------- users -------------------------------->
                <div class="tab-content" id="tab-4">
                    <div class="categoria-resultados">
                        <?php if (!empty($resultados['usuarios'])) { ?>
                            <div class="lista-resultados">
                                <?php foreach ($resultados['usuarios'] as $user) {
                                    include('includes/users.php');
                                } ?>
                            </div>
                        <?php } else { ?>
                            <p class="sem-resultados">Nenhum usuário encontrado</p>
                        <?php } ?>
                    </div>
                </div>

                
                <!------------------------ eventos -------------------------------->
                <div class="tab-content" id="tab-2">
                    <div class="categoria-resultados">
                        <?php if (!empty($resultados['eventos'])) { ?>
                            <div class="lista-resultados">
                                <?php foreach ($resultados['eventos'] as $evento) {
                                    include('includes/eventos.php');
                                } ?>
                            </div>
                        <?php } else { ?>
                            <p class="sem-resultados">Nenhum evento encontrado</p>
                        <?php } ?>
                    </div>
                </div>


                <!--------------------------- Posts -------------------------------------->
                <div class="tab-content ativo" id="tab-1">
                    <div class="categoria-resultados">
                        <?php if (!empty($resultados['posts'])) { ?>
                            <div class="lista-resultados">
                                <?php foreach ($resultados['posts'] as $post) {
                                    include('includes/posts.php');
                                } ?>
                            </div>
                        <?php } else { ?>
                            <p class="sem-resultados">Nenhum post encontrado</p>
                        <?php } ?>
                    </div>
                </div>

                <?php } else { ?>
                    <div class="sem-resultados">
                        <h2>Digite um termo para iniciar suas pesquisas</h2>
                        <p>Use o campo de pesquisa para encontrar eventos, grupos, usuários e posts</p>
                    </div>
                <?php } ?>

            </main>

            <?php require_once('includes/navbar.php'); 
            require_once('includes/search-box.php');?>

        </div>

        <?php 
        include('includes/criacao.php');
        include_once('includes/user_box.php');
        ?>

        <script src="assets/js/index.js?v=<?= time() ?>"></script>
    </body>
</html>
