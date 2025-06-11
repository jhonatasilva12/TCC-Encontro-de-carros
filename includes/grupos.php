<a href="./grupo.php?id=<?= $grupo['id_grupo'] ?>">
    <div class="grupo">
        <div class="g-fotinha">
            <?php if (!empty($grupo['img_grupo'])) { ?>
                <img src="assets/images/groups/<?php echo htmlspecialchars($grupo['img_grupo']); ?>" alt="Imagem do Grupo">
            <?php } ?>
        </div>
        <div class="g-info">
            <div class="g-nome"><?php echo htmlspecialchars($grupo['nome_grupo']); ?></div>
            <?php if (!empty($grupo['nome_temas'])) { ?>
                <span class="p-tag" style="background-color: <?php echo htmlspecialchars($grupo['cor_fundo']); ?>; color: <?php echo ($grupo['cor_letras'] == 1) ? '#FFFFFF' : '#000000';  ?>">
                    <?php echo htmlspecialchars($grupo['nome_temas']); ?>
                </span>
            <?php } ?>
            <div class="g-desc">
                <?php
                    $descricao = htmlspecialchars($grupo['descricao_grupo']);
                if (strlen($descricao) > 150) {
                    echo substr($descricao, 0, 150) . '...';
                } else {
                    echo $descricao;
                }
                ?>
            </div>
            <div class="g-letrinhas">
                Membros: <?php echo $grupo['membros_count']; ?>
                <?php if ($grupo['user_participando']) { ?>
                    <span style="font-weight: bold; color: #28a745; margin-left: 5px;">
                        (Você participa)
                    </span>
                <?php } ?>
            </div>
            <div class="g-letrinhas">
                Criado por: <?php echo htmlspecialchars($grupo['nome_user'] . ' ' . $grupo['sobrenome_user']); ?>
            </div>
            <div class="g-letrinhas">
            <span class="p-tempo">
                criado <?= $meetcar->tempoDecorrido($grupo['data_criacao']) ?>
            </span>
            </div>
        </div>
    </div>
</a>
