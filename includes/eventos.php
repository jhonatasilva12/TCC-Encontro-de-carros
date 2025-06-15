<div class="evento" data-id="<?= $evento['id_evento'] ?>">
    <div class="e-superior">
        <div class="e-identifica">
            <a href="user.php?id=<?= $evento['id_user'] ?>">
                <img src="./assets/images/users/<?php echo !empty($evento['img_user']) ? htmlspecialchars($evento['img_user']) : 'user_padrao.jpg'; ?>" class="e-fotinha">
            </a>
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
                    <?php if ($evento['id_user'] === $_SESSION['user_id']) { ?>
                        <li>
                            <a class="delete-content" data-type="event" data-id="<?= $evento['id_evento'] ?>">
                                <i class="fas fa-trash-alt"></i> excluir evento
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
        
    <div class="e-centro">
        <h3 class="e-titulo"><?php echo htmlspecialchars($evento['nome_evento']); ?></h3>
        
        <p class="e-texto"><?php echo htmlspecialchars($evento['descricao_evento']); ?></p>
        
        <div class="e-info">
            <div class="e-divisa">
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
            <div class="e-divisa">
                <i class="fas fa-money-bill"></i>
                <span>Pedestre: <?php if ($evento['valor_pedestre'] === '0') {
                    echo "gratis";

                } else {
                    
                    echo "R$ " . htmlspecialchars($evento['valor_pedestre']); 
                
                } ?></span>
                
            </div>
            <div class="e-divisa">
                
                <i class="fas fa-money-bill"></i>
                <span>Exposição: <?php if ($evento['valor_exposicao'] === '0') {
                    echo "gratis";

                } else {
                    
                    echo "R$ " . htmlspecialchars($evento['valor_exposicao']); 
                
                } ?></span>
                
            </div>

            <div class="e-divisa">
                <i class="fas fa-location-dot"></i>
                <label><?php echo htmlspecialchars($evento['rua_evento']) . ", " . htmlspecialchars($evento['numero_evento']) . " - " . htmlspecialchars($evento['cidade_evento']) . ", " . htmlspecialchars($evento['estado_evento']) ?></label>
            </div>

        </div>

        <?php if (!empty($evento['img_evento'])) {
            $ext = pathinfo($evento['img_evento'], PATHINFO_EXTENSION);
            $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov']);
            
            if ($isVideo) { ?>
                <video class="p-video" controls controlsList="nodownload" loop>
                    <source src="./assets/images/events/<?= htmlspecialchars($evento['img_evento']) ?>" type="video/<?= $ext ?>">
                </video>
            <?php } else { ?>
                <img src="./assets/images/events/<?php echo htmlspecialchars($evento['img_evento']); ?>" class="p-img" loading="lazy">
            <?php }
        } ?>
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
