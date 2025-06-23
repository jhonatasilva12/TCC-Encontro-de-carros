<!DOCTYPE html>
<?php
require_once('banco/autentica.php');
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');

$meetcar = new MeetCarFunctions();

$groupId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

$posts = $meetcar->buscarPostsPorGrupo($groupId, $userId);
$eventos = $meetcar->buscarEventosPorGrupo($groupId, $userId);
$grupo = $meetcar->buscarGrupoPorId($groupId, $userId);

if (empty($grupo)) {
    header("Location: ./index.php?grupo=nao+existe");
    exit;
}

$grupo = $grupo[0];

?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <script src='https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js'></script>
    <link rel="icon" href="./assets/images/logo.png">
    <title>MeetCar - <?= $grupo['nome_grupo'] ?> </title>
    <link rel="stylesheet" href="assets/css/styles.css">

</head>

<body>

    <div class="geral">
        <main class="hero">
            <div class="sub-bar">
                <img src="assets/images/groups/<?= $grupo['img_grupo'] ?? 'grupo_padrao.jpg' ?>" class="g-fotinha">
                <div>
                    <p class="titulo"><?= htmlspecialchars($grupo['nome_grupo']) ?></p>
                    <span class="p-tag"
                        style="background-color: <?= $grupo['cor_fundo'] ?>; color: <?= $grupo['cor_letras'] ? 'white' : 'black' ?>; margin-bottom: 40px;">
                        <?= htmlspecialchars($grupo['nome_temas']) ?>
                    </span>
                    <p class="descricao"><?= htmlspecialchars($grupo['descricao_grupo']) ?></p>
                    <div class="inferior">
                        <div class="g-letrinhas">
                            Membros: <?php echo $grupo['membros_count']; ?>
                        </div>

                        <?php if ($meetcar->userParticipaGrupo($_SESSION['user_id'], $groupId)): ?>
                            <button onclick="sairGrupo(<?= $groupId ?>)"><i class="fas fa-circle-minus"></i> Sair</button>
                        <?php else: ?>
                            <button onclick="participarGrupo(<?= $groupId ?>)"><i class="fas fa-circle-plus"></i>
                                Participar</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <br>
            <hr id="separa-sub"><br>

            <?php

            if ($eventos == null && $posts == null) {
                echo "<h3>Não há nada neste grupo ainda, seja o primeiro a interagir!</h3>";
            }

            require_once('includes/feed.php'); ?> <!--posts e eventos organizados por ordem de chegada-->



            <div class="mini-sub">
                <div>
                    <img src="assets/images/groups/<?= $grupo['img_grupo'] ?? 'grupo_padrao.jpg' ?>" class="micro-foto">
                    <div>
                        <p class="titulo"><?= htmlspecialchars($grupo['nome_grupo']) ?></p>
                        <?php if ($meetcar->userParticipaGrupo($_SESSION['user_id'], $groupId)): ?>
                            <button onclick="sairGrupo(<?= $groupId ?>)"><i class="fas fa-circle-minus"></i> Sair</button>
                        <?php else: ?>
                            <button onclick="participarGrupo(<?= $groupId ?>)"><i class="fas fa-circle-plus"></i>
                                Participar</button>
                        <?php endif; ?>
                    </div>
                </div>
                <hr>
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