<a href="./user.php?id=<?= $user['id_user'] ?>">
    <div class="mini-user">
        <div class="u-fotinha">
            <img src="assets/images/users/<?php echo htmlspecialchars($user['img_user']); ?>" alt="Imagem do usuário">
        </div>
        <div class="u-centro">
            <div class="u-nome">
                <?php echo htmlspecialchars($user['nome_user']) . ' ' . htmlspecialchars($user['sobrenome_user']); ?>
            </div>
            <?php if (!empty($user['bio_user'])) { ?>
                <div class="u-bio">
                    <?php echo htmlspecialchars($user['bio_user']); ?>
                </div>
            <?php } ?>
            <div class="g-letrinhas">
                posts: <?php echo htmlspecialchars($user['posts_count']) ?>
            </div>
        </div>
    </div>
</a>