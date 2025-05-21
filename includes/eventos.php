<div class="eventos-container">
    <?php foreach ($eventos as $evento): ?>
    <div class="evento">
        <div class="e-superior">
            <div class="e-identifica">
                <img src="assets/uploads/<?php echo !empty($evento['img_user']) ? htmlspecialchars($evento['img_user']) : 'default-user.png'; ?>" class="e-fotinha">
                <div>
                    <p class="e-nome">
                        <?php echo htmlspecialchars($evento['nome_user'] . ' ' . $evento['sobrenome_user']); ?>
                    </p>
                    <span class="e-tempo" data-evento="<?php echo $evento['data_inicio_evento']; ?>">
                        <?php echo tempoParaEvento($evento['data_inicio_evento']); ?>
                    </span>
                </div>
            </div>
            
            <div class="superior-direita">
                <button class="e-mais"><i class="fas fa-ellipsis-v"></i></button>
                <div class="pop-mais">
                    <ul class="pop-i-list">
                        <li><i class="fas fa-share"></i></li>
                        <li><i class="fas fa-exclamation-triangle"></i></li>
                    </ul>
                    <ul class="pop-list">
                        <li><a href="#" class="letter-list">compartilhar</a></li>
                        <li><a href="#" class="letter-list">denunciar</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="e-centro">
            <h3 class="e-titulo"><?php echo htmlspecialchars($evento['nome_evento']); ?></h3>
            
            <?php if (!empty($evento['img_evento'])): ?>
            <img src="assets/uploads/<?php echo htmlspecialchars($evento['img_evento']); ?>" class="e-img" loading="lazy">
            <?php endif; ?>
            
            <p class="e-texto"><?php echo htmlspecialchars($evento['descricao_evento']); ?></p>
            
            <div class="e-info">
                <div class="e-data">
                    <i class="fas fa-calendar-alt"></i>
                    <span><?php echo formatarDataEvento(
                        $evento['data_inicio_evento'],
                        $evento['data_termino_evento'],
                        $evento['horario_inicio'],
                        $evento['hora_termino']
                    ); ?></span>
                </div>
                
                <div class="e-valores">
                    <div><i class="fas fa-walking"></i> Pedestre: R$ <?php echo htmlspecialchars($evento['valor_pedestre']); ?></div>
                    <div><i class="fas fa-car"></i> Exposição: R$ <?php echo htmlspecialchars($evento['valor_exposicao']); ?></div>
                </div>
            </div>
        </div>
        
        <div class="e-inferior">
            <div class="inferior-esquerda">
                <button class="e-participar" data-evento-id="<?php echo $evento['id_evento']; ?>">
                    <i class="fas fa-check-circle"></i>
                    <span>Participar</span>
                    <span class="participantes-count"><?php echo $evento['participantes_count']; ?></span>
                </button>
            </div>
            
            <div class="inferior-direita">
                <span class="e-data-post" data-tempo="<?php echo $evento['data_post']; ?>">
                    Postado <?php echo tempoDecorrido($evento['data_post']); ?>
                </span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>