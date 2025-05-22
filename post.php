<?php
require_once 'includes/funcoes.php';
$meetcar = new MeetCarFunctions();

// Pega o ID do post da URL
$postId = $_GET['id'] ?? null;

if (!$postId) {
    header("Location: ./index.php");
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
    <title><?= htmlspecialchars($post['titulo_post'] ?? 'Post') ?> - MeetCar</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="post-container">
          <?php
          $posts = $meetcar->buscarPosts();
          include_once 'includes/posts.php';
          ?>
        <div class="post-detalhe">
            <!-- Conteúdo do post (igualzinho o normal, mas diferente) -->
            <?php include 'includes/post-detalhe.php'; ?>
        </div>
        
        <!-- Seção de comentários -->
        <div class="comentarios-container">
            <h3>Comentários</h3>
            
            <!-- Formulário para novo comentário -->
            <form class="form-comentario" method="post" action="adicionar_comentario.php">
                <input type="hidden" name="post_id" value="<?= $postId ?>">
                <textarea name="comentario" required placeholder="Adicione um comentário..."></textarea>
                <button type="submit">Comentar</button>
            </form>
            
            <!-- Lista de comentários -->
            <?php foreach ($comentarios as $comentario): ?>
                <div class="comentario">
                    <div class="comentario-cabecalho">
                        <img src="./assets/images/users/<?= htmlspecialchars($comentario['img_user']) ?>" alt="Foto do usuário">
                        <span><?= htmlspecialchars($comentario['nome_user']) ?></span>
                        <span class="tempo"><?= MeetCarFunctions::tempoDecorrido($comentario['data_comentario']) ?></span>
                    </div>
                    <div class="comentario-texto">
                        <?= htmlspecialchars($comentario['texto_comentario']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>