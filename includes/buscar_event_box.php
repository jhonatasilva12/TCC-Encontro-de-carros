<?php
require_once('funcoes.php');
session_start();

$eventId = $_GET['eventId'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if ($eventId && $userId) {
    $meetcar = new MeetCarFunctions();
    $evBox = $meetcar->buscarEventoPorId($userId, $eventId);

    if ($evBox) {
        ob_start();
        ?>
        <div class="ev-centro">
            <h3 class="ev-titulo"><?= htmlspecialchars($evBox['nome_evento']) ?></h3>
            <p class="ev-texto"><?= htmlspecialchars($evBox['descricao_evento']) ?></p>
            <div class="ev-info">
                <div class="ev-divisa">
                    <i class="fas fa-calendar-alt"></i>
                    <span><?= $meetcar->formatarDataEventoSimples($evBox['data_inicio_evento'], $evBox['data_termino_evento']) ?></span>
                </div>
                <div class="ev-divisa">
                    <i class="fas fa-money-bill"></i>
                    <span>Pedestre: <?= ($evBox['valor_pedestre'] === '0') ? "gratis" : "R$ " . htmlspecialchars($evBox['valor_pedestre']) ?></span>
                </div>
                <div class="ev-divisa">
                    <i class="fas fa-money-bill"></i>
                    <span>Exposição: <?= ($evBox['valor_exposicao'] === '0') ? "gratis" : "R$ " . htmlspecialchars($evBox['valor_exposicao']) ?></span>
                </div>
                <div class="ev-divisa">
                    <i class="fas fa-location-dot"></i>
                    <label><?= htmlspecialchars($evBox['rua_evento']) . ", " . htmlspecialchars($evBox['numero_evento']) . " - " . htmlspecialchars($evBox['cidade_evento']) . ", " . htmlspecialchars($evBox['estado_evento']) ?></label>
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
        <?php
        $html = ob_get_clean();
        echo $html; // retorna o HTML prontinho pra substituição
        exit;
    }
}

echo "<h3>Não foi possível carregar o evento. Tente novamente.</h3>";