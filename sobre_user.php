<?php
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');
require_once('banco/autentica.php');

$meetcar = new MeetCarFunctions();
$userId = $_GET['id'] ?? null;


$posts = $meetcar->buscarPostsPorUser($userId);
$eventos = $meetcar->buscarEventosPorUser($userId);

$user = $meetcar->buscarUserPorId($userId);

if (!$user) {

    header("Location: index.php?error=usuario_nao_existe");
    exit();
}

$totalUserLikes = $meetcar->contarLikesPorUser($userId);
$totalUserEvents = $meetcar->contarEventosPorUser($userId);
$totalUserGroups = $meetcar->contarGruposPorUser($userId);

$message = '';
if (isset($_GET['status'])) {
    $messages = [
        'success' => 'Informações atualizadas com sucesso!',
        'error' => 'Erro ao atualizar informações. Tente novamente.',
        'no_changes' => 'Nenhuma alteração foi detectada.',
        'validation_error' => 'Por favor, preencha todos os campos corretamente.'
    ];

    $color = [
        'success' => 'green',
        'error' => 'red',
        'no_changes' => 'blue',
        'validation_error' => 'orange'
    ];

    if (isset($messages[$_GET['status']])) {
        $message = '<p style="color: ' . $color[$_GET['status']] . ';">' . $messages[$_GET['status']] . '</p>';
    } elseif (isset($_GET['message'])) {
        $message = '<p style="color: red;">' . htmlspecialchars($_GET['message']) . '</p>';
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Perfil de <?= htmlspecialchars($user['nome_user']) ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="geral">
        <main class="hero">

            <?php require_once('includes/search-box.php'); ?>
            <div class="alert"><?= $message ?></div>

            <div id="separa-sub" class="profile-container">

                <div class="profile-photo-area">
                    <div class="profile-photo">
                        <img src="./assets/images/users/<?= htmlspecialchars($user['img_user']) ?>"
                            alt="Foto de Perfil">
                    </div>
                    <div class="likes-box">
                        <i class="fa-solid fa-heart"></i> <?= $totalUserLikes ?> Likes
                    </div>
                </div>

                <div class="user-info">
                    <h1 class="user-name">
                        <?php echo htmlspecialchars($user['nome_user']) . ' ' . htmlspecialchars($user['sobrenome_user']); ?>
                    </h1>
                </div>
                <?php if ($user['id_user'] == $_SESSION['user_id']) { ?>

                    <button class="edit-info-button" id="open-edit-profile-modal">
                        <i class="fa-solid fa-pen-to-square"></i> Editar Perfil
                    </button>

                    <div id="form-user">
                        <div class="form-modal">
                            <div class="header-form-criacao">
                                <button class="fecha-modal">X</button>
                                <h2>editar perfil</h2>
                            </div>

                            <form class="modal-container" action="./banco/atualizar.php" method="post"
                                enctype="multipart/form-data" autocomplete="off">
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="image-preview" id="groupPreview">
                                            <img id="previewGroup"
                                                src="./assets/images/users/<?= htmlspecialchars($user['img_user']) ?>"
                                                alt="Pré-visualização da imagem do perfil">
                                        </div>
                                        <label for="group-image">Imagem de Perfil</label>
                                        <input type="file" id="group-image" name="img_user"
                                            accept="image/jpg, image/png, image/jpeg">
                                    </div>

                                    <div class="form-group-div">
                                        <div class="form-group">
                                            <label for="nome_user">Nome*:</label>
                                            <input type="text" id="nome_user" name="nome_user"
                                                value="<?= htmlspecialchars($user['nome_user']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="sobrenome_user">Sobrenome:</label>
                                            <input type="text" id="sobrenome_user" name="sobrenome_user"
                                                value="<?= htmlspecialchars($user['sobrenome_user']) ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="data_nasc_user">Data de Nascimento:</label>
                                        <input type="date" id="data_nasc_user" name="data_nasc_user"
                                            value="<?= htmlspecialchars($user['data_nasc_user']) ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="telefone_user">Telefone*:</label>
                                        <input type="tel" id="telefone_user" name="telefone_user"
                                            value="<?= htmlspecialchars($user['telefone_user']) ?>" maxlength="15" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="cpf_user">CPF:</label>
                                        <input type="text" id="cpf_user" name="cpf_user"
                                            value="<?= htmlspecialchars($user['cpf_user']) ?>" maxlength="14">
                                    </div>

                                    <div class="form-group">
                                        <label for="bio_user">Bio:</label>
                                        <textarea id="bio_user" name="bio_user"
                                            maxlength="250"><?= htmlspecialchars($user['bio_user']) ?></textarea>
                                    </div>

                                    <button type="submit">Salvar Alterações</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mini-sub">
                        <div>
                            <img src="./assets/images/users/<?= htmlspecialchars($user['img_user']) ?>"
                                class="micro-foto">
                            <div>
                                <p class="titulo"><?php echo htmlspecialchars($user['nome_user']) . ' ' . htmlspecialchars($user['sobrenome_user']); ?></p>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!------------ fim ------------>

                <?php } ?>

                <?php if (!empty($user['bio_user'])) { ?>

                    <div class="bio-section">
                        <div class="bio-label">BIO</div>
                        <div class="bio-content"><?= htmlspecialchars($user['bio_user']) ?></div>
                    </div>

                <?php } ?>

                <div class="bottom-sections">
                    <button class="section-button">
                        <i class="fa-solid fa-calendar-alt"></i> Eventos (<?= $totalUserEvents ?>)
                    </button>
                    <div class="total-list" style="width: 100px; height: 100px;">

                    </div>
                    <button class="section-button">
                        <i class="fa-solid fa-users"></i> Grupos (<?= $totalUserGroups ?>)
                    </button>
                </div>
            </div>
            <?php require('includes/feed.php') ?>

        </main>

        <?php require_once('includes/navbar.php');
        require_once('includes/search-box.php'); ?>

    </div> <!--fim geral-->

    <?php
    require_once('includes/user_box.php');
    ?>

    <script src="assets/js/index.js?v=<?= time() ?>"></script>

</body>

</html>