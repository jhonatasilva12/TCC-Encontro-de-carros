<!DOCTYPE html>
<?php
require_once('banco/autentica.php');
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');
require_once('includes/search-box.php');
require_once('includes/navbar.php');

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
                <div class="tabs">
                    <div class="tab active" data-tab="1">Aba 1</div>
                    <div class="tab" data-tab="2">Aba 2</div>
                    <div class="tab" data-tab="3">Aba 3</div>
                    <div class="tab" data-tab="4">Aba 4</div>
                </div>

                <?php if (!empty($termo_pesquisa)): ?>

                
                <!------------------------------- grupos ---------------------------------------->
                <div class="tab-content active" id="tab-1">
                    <div class="categoria-resultados">
                        <h2 class="categoria-titulo">Grupos</h2>
                        <?php if (!empty($resultados['grupos'])): ?>
                            <div class="lista-resultados">
                                <?php foreach ($resultados['grupos'] as $grupo): ?>
                                    <div class="resultado-item">
                                        <?php if ($grupo['img_grupo']): ?>
                                            <img src="assets/images/groups/<?= htmlspecialchars($grupo['img_grupo']) ?>" alt="<?= htmlspecialchars($grupo['nome_grupo']) ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;">
                                        <?php endif; ?>
                                        <h3><?= htmlspecialchars($grupo['nome_grupo']) ?></h3>
                                        <p><?= htmlspecialchars(substr($grupo['descricao_grupo'], 0, 100)) ?>...</p>
                                        <p><i class="fas fa-users"></i> <?= $grupo['membros_count'] ?> membros</p>
                                        <a href="grupo.php?id=<?= $grupo['id_grupo'] ?>" class="btn-ver-mais">Ver grupo</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="sem-resultados">Nenhum grupo encontrado</p>
                        <?php endif; ?>
                    </div>
                </div>

                
                <!------------------------- users -------------------------------->
                <div class="tab-content" id="tab-2">
                    <div class="categoria-resultados">
                        <h2 class="categoria-titulo">Usuários</h2>
                        <?php if (!empty($resultados['usuarios'])): ?>
                            <div class="lista-resultados">
                                <?php foreach ($resultados['usuarios'] as $usuario): ?>
                                    <div class="resultado-item">
                                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                            <img src="assets/images/users/<?= htmlspecialchars($usuario['img_user']) ?>" alt="Foto do usuário" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                            <div>
                                                <h3><?= htmlspecialchars($usuario['nome_user']) ?> <?= htmlspecialchars($usuario['sobrenome_user']) ?></h3>
                                                <p><?= $usuario['posts_count'] ?> posts</p>
                                            </div>
                                        </div>
                                        <a href="perfil.php?id=<?= $usuario['id_user'] ?>" class="btn-ver-mais">Ver perfil</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="sem-resultados">Nenhum usuário encontrado</p>
                        <?php endif; ?>
                    </div>
                </div>

                
                <!------------------------ eventos -------------------------------->
                <div class="tab-content" id="tab-3">
                    <div class="categoria-resultados">
                        <h2 class="categoria-titulo">Eventos</h2>
                        <?php if (!empty($resultados['eventos'])): ?>
                            <div class="lista-resultados">
                                <?php foreach ($resultados['eventos'] as $evento):
                                    include_once('includes/eventos.php');
                                endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="sem-resultados">Nenhum evento encontrado</p>
                        <?php endif; ?>
                    </div>
                </div>


                <!--------------------------- Posts -------------------------------------->
                <div class="tab-content" id="tab-4">
                    <div class="categoria-resultados">
                        <h2 class="categoria-titulo">Posts</h2>
                        <?php if (!empty($resultados['posts'])) { ?>
                            <div class="lista-resultados">
                                <?php foreach ($resultados['posts'] as $post):
                                    include_once('includes/posts.php');
                                endforeach; ?>
                            </div>
                        <?php } else { ?>
                            <p class="sem-resultados">Nenhum post encontrado</p>
                        <?php } ?>
                    </div>
                </div>

                <?php else: ?>
                    <div class="sem-resultados">
                        <h2>Digite um termo para iniciar suas pesquisas</h2>
                        <p>Use o campo de pesquisa para encontrar eventos, grupos, usuários e posts</p>
                    </div>
                <?php endif; ?>

            </main>
        </div>

        <script src="assets/js/index.js?v=<?= time() ?>"></script>
    </body>
</html>
