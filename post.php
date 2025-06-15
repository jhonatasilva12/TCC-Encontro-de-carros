<?php
require('includes/funcoes.php');
require_once('banco/db_connect.php');
require_once('banco/autentica.php');
$meetcar = new MeetCarFunctions();

$userId = $_SESSION['user_id'] ?? null; 

// pega o id do post da url
$postId = $_GET['id'] ?? null;

if (!$postId) {
    header("Location: ./index.php?post=nao+existe");
    exit;
}

// busca o post em específico
$post = $meetcar->buscarPostPorId($postId);

if (!$post) {
    header("Location: ./index.php?erro=post+nao+encontrado");
    exit;
}

// busca os comentários desse post
$comentarios = $meetcar->buscarComentariosPorPost($postId);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <script src='https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js'></script>
    <link rel="icon" href="./assets/images/logo.png">
    <title>MeetCar - <?= htmlspecialchars($post['titulo_post'] ?? 'Post') ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="geral">
        <main class="hero">
            <div class="post-detalhe">
                <!-- Conteúdo do post -->
                <div class="post" data-id="<?= $post['id_post'] ?>">
                    <div class="p-superior">
                        <div class="p-identifica">
                            <img src="assets/images/users/<?php echo !empty($post['img_user']) ? htmlspecialchars($post['img_user']) : 'user_padrao.jpg'; ?>" class="p-fotinha">
                            <div>
                                <p class="p-nome">
                                    <?php echo htmlspecialchars($post['nome_user']) . ' ' . htmlspecialchars($post['sobrenome_user']); ?>
                                </p>
                                <span class="p-tag" style="background-color: <?php echo htmlspecialchars($post['cor_fundo']); ?>; color: <?php echo $post['cor_letra'] ? '#fff' : '#000'; ?>">
                                    <?php echo htmlspecialchars($post['nome_tipo_post']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="superior-direita">
                            <button class="mais"><i class="fas fa-ellipsis-v"></i></button>
                            <div class="pop-mais">
                                <ul class="pop-list">
                                    <li>
                                        <a>
                                            <i class="fas fa-share"></i> Compartilhar
                                        </a>
                                    </li>
                                    <?php if ($post['fk_id_user'] === $_SESSION['user_id']) { ?>
                                        <li>
                                            <a class="delete-content" data-type="post" data-id="<?= $post['id_post'] ?>">
                                                <i class="fas fa-trash-alt"></i> excluir post
                                            </a>
                                        </li>
                                    <?php } else { ?>
                                        <li>
                                            <a>
                                                <i class="fas fa-exclamation-triangle"></i> denunciar
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="p-centro">
                        <?php if (!empty($post['titulo_post'])) { ?>
                        <h3 class="p-titulo"><?php echo htmlspecialchars($post['titulo_post']); ?></h3>
                        <?php } ?>
                        <p class="p-texto"><?php echo htmlspecialchars($post['texto_post']); ?></p>
                        
                        <?php if (!empty($post['imagem_post'])) {
                            $ext = pathinfo($post['imagem_post'], PATHINFO_EXTENSION);
                            $video = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov']);
                            
                            if ($video) { ?>
                                <video class="p-video" controls controlsList="nodownload" loop>
                                    <source src="./assets/images/posts/<?= htmlspecialchars($post['imagem_post']) ?>">
                                </video>
                            <?php } else { ?>
                                <img src="./assets/images/posts/<?= htmlspecialchars($post['imagem_post']) ?>" class="p-img" loading="lazy">
                            <?php }
                        } ?>
                    </div>

                    <div class="p-inferior">
                        <div class="inferior-esquerda">
                            <button class="p-vote <?= $post['user_liked'] ? 'liked' : '' ?>" 
                                    data-post-id="<?= $post['id_post'] ?>"
                                    data-user-liked="<?= $post['user_liked'] ? '1' : '0' ?>">
                                <i class="fas fa-thumbs-up"></i>
                                <span class="p-count"><?= $post['likes_count'] ?></span>
                            </button>
                        </div>
                        <div class="inferior-direita">
                            <span class="p-tempo" data-tempo="<?= date('Y-m-d H:i:s', strtotime($post['data_post'])) ?>">
                                <?= $meetcar->tempoDecorrido($post['data_post']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            

            <!--------- seção de comentários --------->
                <span class="p-count" style="text-align: left;"><h3><?php echo $post['comentarios_count']; ?> Comentário(s)</h3></span>
                
                <!-- formulário para novo comentário -->
                <form class="form-comentario" method="post" action="banco/insert_tb_comentario.php" autocomplete="off">
                    <textarea class="com-text" name="comentario" placeholder="Adicione um comentário..." required></textarea>
                    <button type="submit">Comentar</button>
                    <input type="hidden" name="post_id" value="<?= $postId ?>">
                </form>
                
                <!-- lista de comentários -->
                <?php foreach ($comentarios as $comentario) { ?>
                    <div class="comentario">
                        <div class="com-superior">
                            <img src="./assets/images/users/<?= htmlspecialchars($comentario['img_user']) ?>" alt="Foto do usuário">
                            <span><?= htmlspecialchars($comentario['nome_user']) ?></span>
                        </div>
                        <div class="com-centro">
                            <p class="p-texto"><?= htmlspecialchars($comentario['texto_comentario']) ?></p>
                        </div>
                        <div class="com-inferior">
                            <span class="p-tempo"><?= $meetcar->tempoDecorrido($comentario['data_comentario']) ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </main>
        
    <?php include('includes/navbar.php'); ?>
    </div>

    <?php if (!empty($post['imagem_post'])) { ?>
    <div class="fundo-img">
        <button class="fecha-img">x</button>
        <img src="./assets/images/posts/<?php echo htmlspecialchars($post['imagem_post']); ?>" class="img-full">
    </div>
    <?php }
        require('includes/user_box.php');
    ?>

    <script src="assets/js/index.js?v=<?= time() ?>"></script>
</body>
</html>
