<div class="feed-container">
    <?php
        foreach ($conteudos as $item) {
            if ($item['tipo'] === 'post') {
                $post = $item['dados'];
                include('includes/posts.php');
            } else {
                $evento = $item['dados'];
                include('includes/eventos.php');
            }
        }
    ?>
</div>

<div class="fundo-img">
    <button class="fecha-img">x</button>
    <img src="./assets/images/posts/<?php echo htmlspecialchars($post['imagem_post']); ?>" class="img-full">
</div>
