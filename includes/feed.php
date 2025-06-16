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
