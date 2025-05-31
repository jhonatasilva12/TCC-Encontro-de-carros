<?php
// Seus requires existentes
require_once('banco/db_connect.php'); 
require_once('banco/autentica.php');
require_once('includes/search.php');

// O require da navbar está aqui, então a incluiremos uma vez.
// O require de funcoes.php é crucial para a classe MeetCarFunctions.
require_once 'includes/funcoes.php';

// Inicie a sessão se ainda não estiver iniciada, para obter o ID do usuário logado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crie uma instância da classe MeetCarFunctions
$meetcar = new MeetCarFunctions();

// Obtenha o ID do usuário logado da sessão. Se não houver, use null (ou 0, como a função aceita)
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
    
    <link rel="stylesheet" href="assets/css/style.css"> 
    <style>
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin-left: 120px; /* Ajuste para compensar a navbar lateral */
            padding-top: 100px; /* Espaço para a barra de pesquisa ou topo da página */
            width: calc(100% - 120px); /* Ajusta a largura com base na navbar */
            box-sizing: border-box; /* Inclui padding e border no cálculo da largura */
        }

        .main-content h1 {
            color: var(--letra-normal-geral);
            margin-bottom: 30px;
            font-size: 2.5em;
            text-align: center;
        }

        .group-card {
            display: flex;
            background-color: var(--fundo-post); /* Pode ser um fundo diferente para grupos se preferir */
            border: 2px solid var(--borda-post);
            border-radius: 15px;
            margin-bottom: 25px;
            padding: 20px;
            box-shadow: 0 4px 8px var(--sombra);
            width: 70vw; /* Largura do card */
            max-width: 800px; /* Largura máxima para não ficar muito grande em telas largas */
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            z-index: 1; /* Para garantir que não conflita com elementos fixos */
        }

        .group-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .group-image {
            flex-shrink: 0; /* Impede que a imagem encolha */
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
            border: 3px solid var(--borda-base);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex; /* Para centralizar o "FOTO" */
            align-items: center;
            justify-content: center;
            color: var(--texto-secundario); /* Cor para o texto "FOTO" */
        }

        .group-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .group-info {
            flex-grow: 1; /* Permite que as informações ocupem o espaço restante */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .group-name {
            font-size: 1.8em;
            font-weight: bold;
            color: var(--letra-normal-geral);
            margin-bottom: 8px;
        }

        .group-description {
            font-size: 1em;
            color: var(--texto-secundario);
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .group-theme-tag {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            margin-bottom: 10px;
            white-space: nowrap; /* Evita quebra de linha */
            border: 1px solid rgba(0, 0, 0, 0.1); /* Borda sutil */
        }

        .group-members,
        .group-creator {
            font-size: 0.9em;
            color: var(--letra-normal-geral);
            margin-top: 5px;
        }

        .user-is-member {
            font-weight: bold;
            color: #28a745; /* Verde para indicar participação */
            margin-left: 5px;
        }

        /* Responsividade */
        @media (max-width: 900px) {
            .group-card {
                flex-direction: column; /* Empilha imagem e info em telas menores */
                align-items: center;
                width: 85vw;
                padding: 15px;
            }

            .group-image {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .group-info {
                text-align: center;
            }
        }

        @media (max-width: 660px) { /* Ajustes para a navbar inferior em mobile */
            .main-content {
                margin-left: 0; /* Remove margem lateral */
                padding-bottom: 100px; /* Espaço para a navbar inferior */
                width: 100%; /* Ocupa a largura total */
            }

            .main-content h1 {
                font-size: 2em;
                margin-top: 20px; /* Ajusta se a barra de pesquisa ficar muito em cima */
            }

            .group-card {
                width: 90vw; /* Ainda mais estreito para mobile */
            }
        }
    </style>
</head>
<body>

    <?PHP 
    //  navbar 
    Include_once 'includes/navbar.php';
    ?>

    <div class="main-content">
        <h1>Comunidade de Grupos</h1>
        <?php if (!empty($grupos)): ?>
            <?php foreach ($grupos as $grupo): ?>
                <div class="group-card">
                    <div class="group-image">
                        <?php if (!empty($grupo['img_grupo'])): ?>
                            <img src="<?php echo htmlspecialchars($grupo['img_grupo']); ?>" alt="Imagem do Grupo">
                        <?php else: ?>
                            FOTO
                        <?php endif; ?>
                    </div>
                    <div class="group-info">
                        <div class="group-name"><?php echo htmlspecialchars($grupo['nome_grupo']); ?></div>
                        <div class="group-description">
                            <?php
                            // Limita a descrição para não ficar muito longa
                            $descricao = htmlspecialchars($grupo['descricao_grupo']);
                            if (strlen($descricao) > 150) {
                                echo substr($descricao, 0, 150) . '...';
                            } else {
                                echo $descricao;
                            }
                            ?>
                        </div>
                        <?php if (!empty($grupo['nome_temas'])): ?>
                            <span class="group-theme-tag"
                                style="background-color: <?php echo htmlspecialchars($grupo['cor_fundo']); ?>;
                                       color: <?php echo ($grupo['cor_letras'] == 1) ? '#FFFFFF' : '#000000';  ?>">
                                <?php echo htmlspecialchars($grupo['nome_temas']); ?>
                            </span>
                        <?php endif; ?>
                        <div class="group-members">
                            Membros: <?php echo $grupo['membros_count']; ?>
                            <?php if ($grupo['user_participando']): ?>
                                <span class="user-is-member">(Você participa)</span>
                            <?php endif; ?>
                        </div>
                        <div class="group-creator">
                            Criado por: <?php echo htmlspecialchars($grupo['nome_user'] . ' ' . $grupo['sobrenome_user']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum grupo encontrado. Seja o primeiro a criar um!</p>
        <?php endif; ?>
    </div>

    <?php
    //require_once de criacao.php
    require_once('includes/criacao.php');
    ?>

    <script src="assets/js/index.js?v=<?= time() ?>"></script>
</body>
</html>