<?php
$userId = $_SESSION['user_id'] ?? null; 

$posts = $meetcar->buscarPosts($userId); 
$eventos = $meetcar->buscarEventos($userId); 

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

// Ordena por data (do mais novo pro mais antigo)
usort($conteudos, function($a, $b) {
    return strtotime($b['data']) - strtotime($a['data']);
});
?>

<div class="feed-container">
    <?php foreach ($conteudos as $item): ?>
        <?php if ($item['tipo'] === 'post'): 
            $post = $item['dados']; ?>
            <?php include_once('includes/posts.php'); ?>
        
        <?php else: 
            $evento = $item['dados']; ?>
            <?php include_once('includes/eventos.php') ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="fundo-img">
    <button class="fecha-img">x</button>
    <img src="./assets/images/posts/<?php echo htmlspecialchars($post['imagem_post']); ?>" class="img-full">
</div>
