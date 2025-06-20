<?php
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

// ordena por data (do mais novo pro mais antigo)
usort($conteudos, function ($a, $b) {
    return strtotime($b['data']) - strtotime($a['data']);
});
?>

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