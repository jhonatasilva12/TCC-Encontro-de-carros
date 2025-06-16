<?php 
$meetcar = new MeetCarFunctions();

$userId = $_SESSION['user_id'] ?? null; 
$eventId = $_SESSION['evento_selecionado'] ?? null;
$usuario = $meetcar->buscarUserPorId($userId);

$evBox = $meetcar->buscarEventoPorId($userId, $eventId);

?>

<div class="tabs" style="display: none;">
    <div id="box-1" class="tab ativo" data-tab="1"><i class="fas fa-image"></i>Posts</div>
    <div id="box-2" class="tab" data-tab="2"><i class="fas fa-calendar-days"></i>Eventos</div>
    <div id="box-3" class="tab" data-tab="3"><i class="fas fa-users"></i>Grupos</div>
    <div id="box-4" class="tab" data-tab="4"><i class="fas fa-user"></i>Usuários</div>
</div>

<div class="event-box" style="display: none;">
    <div class="ev-centro">
        <h3 class="ev-titulo"><?php echo htmlspecialchars($evBox['nome_evento']); ?></h3>
        
        <p class="ev-texto"><?php echo htmlspecialchars($evBox['descricao_evento']); ?></p>
        
        <div class="ev-info">
            <div class="ev-divisa">
                <i class="fas fa-calendar-alt"></i>
                <span>
                    <?php 
                    echo $meetcar->formatarDataEventoSimples(
                        $evBox['data_inicio_evento'],
                        $evBox['data_termino_evento']
                    ); 
                    ?>
                </span>
            </div>
            <div class="ev-divisa">
                <i class="fas fa-money-bill"></i>
                <span>Pedestre: <?php if ($evBox['valor_pedestre'] === '0') {
                    echo "gratis";

                } else {
                    
                    echo "R$ " . htmlspecialchars($evBox['valor_pedestre']); 
                
                } ?></span>
                
            </div>
            
            <div class="ev-divisa">
                
                <i class="fas fa-money-bill"></i>
                <span>Exposição: <?php if ($evBox['valor_exposicao'] === '0') {
                    echo "gratis";

                } else {
                    
                    echo "R$ " . htmlspecialchars($evBox['valor_exposicao']); 
                
                } ?></span>
                
            </div>

            <div class="ev-divisa">
                <i class="fas fa-location-dot"></i>
                <label><?php echo htmlspecialchars($evBox['rua_evento']) . ", " . htmlspecialchars($evBox['numero_evento']) . " - " . htmlspecialchars($evBox['cidade_evento']) . ", " . htmlspecialchars($evBox['estado_evento']) ?></label>
            </div>

        </div>
    </div>
        
    <div class="ev-inferior">
            <button class="e-participar <?= $evBox['user_participando'] ? 'inscrito' : '' ?>" 
                    data-evento-id="<?= $evBox['id_evento'] ?>"
                    data-user-participando="<?= $evBox['user_participando'] ? '1' : '0' ?>">
                <i class="fas fa-check-circle"></i>
                <span>Participar</span>
                <span class="participantes-count"><?= $evBox['participantes_count'] ?></span>
            </button>

            <span class="ev-tempo">
                <?= $meetcar->tempoParaEvento($evBox['data_inicio_evento']) ?>
            </span>
    </div>

    <a href="evento.php?id=<?= $evBox['id_evento'] ?>"><i class="fa-solid fa-eye"></i>ver mais</a>
</div>





<div class="user-box" data-id="<?= $usuario['id_user'] ?>">

    <div class="box-topo">
        <img src="assets/images/users/<?php echo ($usuario['img_user']); ?>" alt="imagem do user logado">
        <label><?php echo htmlspecialchars($usuario['nome_user']) . ' ' . htmlspecialchars($usuario['sobrenome_user']); ?></label>
    </div>
    <?php if (!empty($usuario['bio_user'])) { ?>
        <div class="box-centro">
            <p><?php echo htmlspecialchars($usuario['bio_user']); ?></p>
        </div>
    <?php } ?>

    <div class="box-inferior">
        <a href="user.php?id=<?= $usuario['id_user'] ?>"><i class="fas fa-user-alt"></i>ver mais</a>
        <a href="banco/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
    
</div>

<div class="fundo-img">
    <button class="fecha-img">×</button>
    
    <div class="media-container">
        <!-- conteudo gerado pelo js -->
    </div>
</div>