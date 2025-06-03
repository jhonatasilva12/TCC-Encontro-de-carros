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
                            <input type="hidden" class="link" value="post.php?id=<?= $post['id_post'] ?>">
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
