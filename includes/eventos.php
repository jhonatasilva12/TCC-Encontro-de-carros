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

        <?php if (!empty($evento['img_evento'])): ?>
        <img src="./assets/images/events/<?php echo htmlspecialchars($evento['img_evento']); ?>" class="p-img" loading="lazy">
        <?php endif; ?>
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