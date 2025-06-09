<!DOCTYPE html>
<?php
require_once('banco/autentica.php');
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');

$meetcar = new MeetCarFunctions();

$groupId = $_GET['id'] ?? null;

$grupo = $meetcar->buscarGrupoPorId($groupId);
$posts = $meetcar->buscarPostsPorGrupo($groupId); 
$eventos = $meetcar->buscarEventosPorGrupo($groupId);

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

            <div class="grupo">
                <div class="g-fotinha">
                    <img src="assets/images/groups/<?php echo htmlspecialchars($grupo['img_grupo']); ?>" alt="Imagem do Grupo">
                </div>
                <div class="g-info">
                    <div class="g-nome"><?php echo htmlspecialchars($grupo['nome_grupo']); ?></div>
                    <?php if (!empty($grupo['nome_temas'])) { ?>
                        <span class="p-tag" style="background-color: <?php echo htmlspecialchars($grupo['cor_fundo']); ?>; color: <?php echo ($grupo['cor_letras'] == 1) ? '#FFFFFF' : '#000000';  ?>">
                            <?php echo htmlspecialchars($grupo['nome_temas']); ?>
                        </span>
                    <?php } ?>
                    <div class="g-desc">
                        <?php
                            $descricao = htmlspecialchars($grupo['descricao_grupo']);
                        if (strlen($descricao) > 150) {
                            echo substr($descricao, 0, 150) . '...';
                        } else {
                            echo $descricao;
                        }
                        ?>
                    </div>
                    <div class="g-letrinhas">
                        Membros: <?php echo $grupo['membros_count']; ?>
                        <?php if ($grupo['user_participando']) { ?>
                            <span style="font-weight: bold; color: #28a745; margin-left: 5px;">
                                (Você participa)
                            </span>
                        <?php } ?>
                    </div>
                    <div class="g-letrinhas">
                        Criado por: <?php echo htmlspecialchars($grupo['nome_user'] . ' ' . $grupo['sobrenome_user']); ?>
                    </div>
                </div>
            </div>
            <hr style="width: 50vw;">
            <br>

            <?php if (!empty($posts) && !empty($eventos)) { ?>
                <?php require_once('includes/feed.php'); ?>
            <?php } else { ?>
                <p>Nenhum post ou evento foi encontrado nesse grupo. Seja o primeiro a criar um com a ferramenta de criação enquanto em um grupo!</p>
            <?php } ?>

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
