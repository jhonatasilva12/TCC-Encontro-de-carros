<?php

require_once('banco/db_connect.php'); 
require_once('banco/autentica.php');
require_once('includes/search-box.php');


require_once 'includes/funcoes.php';

// Inicie a sessão 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crie uma instância da classe MeetCarFunctions
$meetcar = new MeetCarFunctions();


$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; 

// Chama a função buscarGrupos passando o ID do usuário logado
$grupos = $meetcar->buscarGrupos($loggedInUserId);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeetCar - Grupos</title>
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="geral">

        <main class="hero">
            <h1>Comunidade de Grupos</h1>
            <?php if (!empty($grupos)) { ?>
                <?php foreach ($grupos as $grupo) { ?>
                    <div class="grupo">
                        <div class="g-fotinha">
                            <?php if (!empty($grupo['img_grupo'])) { ?>
                                <img src="assets/images/groups/<?php echo htmlspecialchars($grupo['img_grupo']); ?>" alt="Imagem do Grupo">
                            <?php } ?>
                        </div>
                        <div class="g-info">
                            <div class="g-nome"><?php echo htmlspecialchars($grupo['nome_grupo']); ?></div>
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
                            <?php if (!empty($grupo['nome_temas'])) { ?>
                                <span class="p-tag" style="background-color: <?php echo htmlspecialchars($grupo['cor_fundo']); ?>; color: <?php echo ($grupo['cor_letras'] == 1) ? '#FFFFFF' : '#000000';  ?>">
                                    <?php echo htmlspecialchars($grupo['nome_temas']); ?>
                                </span>
                            <?php } ?>
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
                <?php } ?>
            <?php } else { ?>
                <p>Nenhum grupo foi encontrado. Seja o primeiro a criar um!</p>
            <?php } ?>
        </main>

        <?php
        require_once('includes/user_box.php');
        require_once('includes/navbar.php');
        require_once('includes/criacao.php');
        ?>
    </div>

    

    <script src="assets/js/index.js?v=<?= time() ?>"></script>
</body>
</html>