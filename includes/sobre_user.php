<?php
session_start();
// A conexão já utiliza o db_connect.php de forma simplificada
$conn = require_once('banco/db_connect.php'); 

require_once('includes/search-box.php');
require_once('includes/funcoes.php'); 

// Inicialização de variáveis
$userProfileImagePath = '';
$userName = 'NOME E SOBRENOME';
$userBio = 'Aqui é onde a biografia do usuário será exibida. Pode conter algumas informações sobre a pessoa, seus interesses, ou qualquer texto descritivo. Esta área pode crescer e ter uma barra de rolagem se o conteúdo for muito longo. Adicione mais detalhes sobre suas paixões, habilidades e o que você faz.';
$userEmail = ''; 
$totalUserLikes = 0; 
$totalUserEvents = 0; 
$totalUserGroups = 0; 

$baseImagePath = 'assets/images/users/'; 

if (isset($_SESSION['user_id'])) {
    $loggedInUserId = $_SESSION['user_id'];

    try {
      
        $meetCarFunctions = new MeetCarFunctions(); 

        // Consulta para dados do usuário
        $stmt = $conn->prepare("SELECT nome_user, email_user, img_user, bio_user FROM tb_user WHERE id_user = ?");
        $stmt->execute([$loggedInUserId]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            $userName = htmlspecialchars($user_data['nome_user']);
            $userEmail = htmlspecialchars($user_data['email_user']);
            if (!empty($user_data['img_user'])) {
                $userProfileImagePath = htmlspecialchars($baseImagePath . $user_data['img_user']); 
            } else {
                $userProfileImagePath = 'assets/images/users/user_padrao.jpg';
            }
            if (isset($user_data['bio_user']) && !empty($user_data['bio_user'])) {
                $userBio = htmlspecialchars($user_data['bio_user']);
            }

            // Lógica para buscar o total de likes nos posts do usuário
            $stmtLikes = $conn->prepare("
                SELECT COUNT(lp.fk_id_post) AS total_likes
                FROM tb_post tp
                JOIN likes_post lp ON tp.id_post = lp.fk_id_post
                WHERE tp.fk_id_user = ?
            ");
            $stmtLikes->execute([$loggedInUserId]);
            $likes_data = $stmtLikes->fetch(PDO::FETCH_ASSOC);

            if ($likes_data) {
                $totalUserLikes = (int)$likes_data['total_likes'];
            }

          
            $totalUserEvents = $meetCarFunctions->contarEventosPorUser($loggedInUserId);
            $totalUserGroups = $meetCarFunctions->contarGruposPorUser($loggedInUserId);

        } else {
            $userProfileImagePath = 'assets/images/users/user_padrao.jpg';
        }
    } catch(PDOException $e) {
        error_log("Erro ao buscar dados do usuário ou contagens: " . $e->getMessage());
        $userProfileImagePath = 'assets/images/users/user_padrao.jpg';
    } finally {
      
        unset($meetCarFunctions); 
    }
} else {
    header('Location: login.html');
    exit();
}

// Mensagens de feedback
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $message = '<p style="color: green;">Informações atualizadas com sucesso!</p>';
    } elseif ($_GET['status'] == 'error') {
        $message = '<p style="color: red;">Erro ao atualizar informações. Tente novamente.</p>';
    } elseif ($_GET['status'] == 'no_changes') {
        $message = '<p style="color: blue;">Nenhuma alteração foi detectada.</p>';
    } elseif ($_GET['status'] == 'validation_error') {
        $message = '<p style="color: orange;">Por favor, preencha todos os campos corretamente.</p>';
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
    <title>Perfil de <?= $userName ?></title>
    <style>
        /* Style temporario, tem que jogar para o styles.css */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            color: #333;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            z-index: 0;
        }

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
            margin-left: auto;
            margin-right: auto;
            margin-top: 50px;
            margin-bottom: 50px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
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
            color: #222;
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
            transition: background-color 0.3s ease, transform 0.2s ease;
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

        /* ---  --- */

        @media (min-width: 769px) {
            body {
                padding-left: 220px;
                padding-right: 250px;
            }
        }
        @media (max-width: 768px) {
            body {
                padding-left: 0;
                padding-right: 0;
            }
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
            .edit-info-button, .section-button {
                font-size: 0.85em;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>

<?php

require_once('includes/search-box.php');
?>

<div class="profile-container">
    <div class="profile-photo-area">
        <div class="profile-photo">
            <?php if (!empty($userProfileImagePath)): ?>
                <img src="<?= $userProfileImagePath ?>" alt="Foto de Perfil de <?= $userName ?>">
            <?php else: ?>
                FOTO
            <?php endif; ?>
        </div>
        <div class="likes-box">
            <i class="fa-solid fa-heart"></i> <?= $totalUserLikes ?> Likes
        </div>
    </div>

    <div class="user-info">
        <h1 class="user-name"><?= $userName ?></h1>
        <div class="message-box">
            <?= $message ?>
        </div>
    </div>

    <button class="edit-info-button" id="open-edit-profile-modal">
        <i class="fa-solid fa-pen-to-square"></i> Editar Informação
    </button>

    <div class="bio-section">
        <div class="bio-label">BIO</div>
        <div class="bio-content">
            <?= $userBio ?>
        </div>
    </div>

    <div class="bottom-sections">
        <button class="section-button">
            <i class="fa-solid fa-calendar-alt"></i> Seus Eventos (<?= $totalUserEvents ?>)
        </button>
        <button class="section-button">
            <i class="fa-solid fa-users"></i> Seus Grupos (<?= $totalUserGroups ?>)
        </button>
    </div>
</div>

<?php
require_once('includes/criacao.php');
?>
<script src="assets/js/index.js?v=<?= time() ?>"></script>
</body>
</html>
