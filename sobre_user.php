<?php
require_once('includes/funcoes.php');
require_once('banco/db_connect.php');
require_once('banco/autentica.php');

$meetcar = new MeetCarFunctions();
$userId = $_GET['id'] ?? null;

$user = $meetcar->buscarUserPorId($userId);

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
    <style>
        /* Style temporario, tem que jogar para o styles.css */
        .profile-container {
            width: 100%;
            max-width: 900px;
            background-color: #fff;
            border-radius: 12px;
            padding: 30px 40px;
            box-sizing: border-box;
            display: grid;
            grid-template-columns: 160px 1fr;
            grid-template-rows: auto auto auto 1fr auto;
            gap: 25px 30px;
            margin-top: 10px;
            margin-bottom: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .profile-photo-area {
            grid-column: 1 / 2;
            grid-row: 1 / 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            gap: 20px;
        }

        .profile-photo {
            width: 130px;
            height: 130px;
            background-color: #e0e6ec;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 1.2em;
            color: #666;
            overflow: hidden;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .likes-box {
            padding: 8px 20px;
            border: none;
            background-color: #e6f7ff;
            border-radius: 20px;
            text-align: center;
            font-size: 0.95em;
            font-weight: 600;
            color: #007bff;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }

        .likes-box:hover {
            background-color: #cceeff;
            transform: translateY(-2px);
        }

        .user-info {
            grid-column: 2 / 3;
            grid-row: 1 / 3;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 20px;
        }

        .user-name {
            font-size: 2.2em;
            font-weight: 700;
            margin-bottom: 8px;
            border-bottom: none;
        }

        .edit-info-button {
            grid-column: 2 / 3;
            grid-row: 1 / 2;
            justify-self: end;
            align-self: start;
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }

        .edit-info-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .bio-section {
            grid-column: 1 / 3;
            grid-row: 4 / 5;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }

        .bio-label {
            font-weight: 600;
            font-size: 1.1em;
            color: #555;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bio-content {
            min-height: 100px;
            line-height: 1.6;
            color: #444;
            background-color: #fdfdfd;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #eee;
            overflow-y: auto;
            max-height: 180px;
        }

        .bottom-sections {
            grid-column: 1 / 3;
            grid-row: 5 / 6;
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }

        .section-button {
            padding: 12px 30px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            flex-grow: 1;
            max-width: 220px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .section-button:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {

            .profile-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto 1fr auto;
                padding: 25px;
                gap: 20px;
                margin: 20px auto;
            }

            .profile-photo-area {
                grid-column: 1 / 2;
                grid-row: 1 / 2;
                justify-content: center;
                margin-bottom: 15px;
            }

            .user-info {
                grid-column: 1 / 2;
                grid-row: 2 / 3;
                text-align: center;
                padding-left: 0;
            }

            .user-name {
                font-size: 1.8em;
            }

            .edit-info-button {
                grid-column: 1 / 2;
                grid-row: 3 / 4;
                justify-self: center;
                width: 70%;
                margin-top: 10px;
            }

            .bio-section {
                grid-column: 1 / 2;
                grid-row: 4 / 5;
                margin-top: 20px;
            }

            .bottom-sections {
                grid-column: 1 / 2;
                grid-row: 5 / 6;
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            .section-button {
                width: 80%;
                max-width: none;
            }
        }

        @media (max-width: 480px) {
            .profile-container {
                padding: 15px;
                margin: 20px auto;
            }

            .profile-photo {
                width: 100px;
                height: 100px;
            }

            .user-name {
                font-size: 1.5em;
            }

            .edit-info-button,
            .section-button {
                font-size: 0.85em;
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="geral">
        <main class="hero">

            <?php require_once('includes/search-box.php'); ?>

            <div class="profile-container">

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
                    <div class="message-box"><?= $message ?></div>
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
                            <form class="modal-container" action="./banco/insert_tb_grupo.php" method="post"
                                enctype="multipart/form-data" autocomplete="off">
                                <div class="form-group">

                                </div>
                            </form>
                        </div>
                    </div>
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