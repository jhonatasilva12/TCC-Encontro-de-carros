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
                                <?php if ($item['tipo'] === 'post' && $item['dados']['fk_id_user'] === $_SESSION['user_id']): ?>
                                    <li>
                                        <a class="delete-content" data-type="<?= $item['tipo'] ?>" data-id="<?= $item['tipo'] === 'post' ? $item['dados']['id_post'] : $item['dados']['id_evento'] ?>">
                                            <i class="fas fa-trash-alt"></i> excluir <?= $item['tipo'] ?>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a>
                                            <i class="fas fa-exclamation-triangle"></i> denunciar
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="p-centro">
                    <?php if (!empty($post['titulo_post'])): ?>
                    <h3 class="p-titulo"><?php echo htmlspecialchars($post['titulo_post']); ?></h3>
                    <?php endif; ?>
                    <p class="p-texto"><?php echo htmlspecialchars($post['texto_post']); ?></p>
                    <?php if (!empty($post['imagem_post'])): ?>
                    <img src="./assets/images/posts/<?php echo htmlspecialchars($post['imagem_post']); ?>" class="p-img" loading="lazy">
                    <?php endif; ?>
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
                        <a href="post.php?id=<?= $post['id_post'] ?>" class="link-post">
                            <button class="p-comentario">
                                <i class="fas fa-comment"></i>
                                <span class="p-count"><?php echo $post['comentarios_count']; ?></span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        
        <?php else: 
            $evento = $item['dados']; ?>
            <div class="evento" data-id="<?= $evento['id_evento'] ?>">
                <div class="e-superior">
                    <div class="e-identifica">
                        <img src="./assets/images/users/<?php echo !empty($evento['img_user']) ? htmlspecialchars($evento['img_user']) : 'user_padrao.jpg'; ?>" class="e-fotinha">
                        <div>
                            <p class="e-nome">
                                <?php echo htmlspecialchars($evento['nome_user'] . ' ' . $evento['sobrenome_user']); ?>
                            </p>
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
                                <?php if ($item['tipo'] === 'evento' && $item['dados']['fk_id_criador'] === $_SESSION['user_id']): ?>
                                    <li>
                                        <a class="delete-content" data-type="<?= $item['tipo'] ?>" data-id="<?= $item['tipo'] === 'post' ? $item['dados']['id_post'] : $item['dados']['id_evento'] ?>">
                                            <i class="fas fa-trash-alt"></i> excluir <?= $item['tipo'] ?>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a>
                                            <i class="fas fa-exclamation-triangle"></i> denunciar
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="e-centro">
                    <h3 class="e-titulo"><?php echo htmlspecialchars($evento['nome_evento']); ?></h3>
                    
                    <?php if (!empty($evento['img_evento'])): ?>
                    <img src="./assets/images/events/<?php echo htmlspecialchars($evento['img_evento']); ?>" class="p-img" loading="lazy">
                    <?php endif; ?>
                    
                    <p class="e-texto"><?php echo htmlspecialchars($evento['descricao_evento']); ?></p>
                    
                    <div class="e-info">
                        <div class="e-data">
                            <i class="fas fa-calendar-alt"></i>
                            <span>
                                <?php 
                                echo $meetcar->formatarDataEventoSimples(
                                    $evento['data_inicio_evento'],
                                    $evento['data_termino_evento']
                                ); 
                                ?>
                            </span>
                        </div>
                        <div class="e-val"></div>
                    </div>
                
                <div class="e-inferior">
                    <div class="inferior-esquerda">
                        <button class="e-participar <?= $evento['user_participando'] ? 'inscrito' : '' ?>" 
                                data-evento-id="<?= $evento['id_evento'] ?>"
                                data-user-participando="<?= $evento['user_participando'] ? '1' : '0' ?>">
                            <i class="fas fa-check-circle"></i>
                            <span>Participar</span>
                            <span class="participantes-count"><?= $evento['participantes_count'] ?></span>
                        </button>
                    </div>
                    
                    <div class="inferior-direita">
                        <span class="e-tempo">
                            <?= $meetcar->tempoParaEvento($evento['data_inicio_evento']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="fundo-img">
    <button class="fecha-img">x</button>
    <img src="./assets/images/posts/<?php echo htmlspecialchars($post['imagem_post']); ?>" class="img-full">
</div>
