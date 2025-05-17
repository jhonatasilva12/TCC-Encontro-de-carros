<?php
function tempoDecorrido($dataPost) {
    $agora = new DateTime();
    $dataPost = new DateTime($dataPost);
    $diferenca = $agora->diff($dataPost);
    
    if ($diferenca->y > 0) {
        return $diferenca->y == 1 ? 'há 1 ano' : 'há ' . $diferenca->y . ' anos';
    } elseif ($diferenca->m > 0) {
        return $diferenca->m == 1 ? 'há 1 mês' : 'há ' . $diferenca->m . ' meses';
    } elseif ($diferenca->d > 0) {
        return $diferenca->d == 1 ? 'há 1 dia' : 'há ' . $diferenca->d . ' dias';
    } elseif ($diferenca->h > 0) {
        return $diferenca->h == 1 ? 'há 1 hora' : 'há ' . $diferenca->h . ' horas';
    } elseif ($diferenca->i > 0) {
        return $diferenca->i == 1 ? 'há 1 minuto' : 'há ' . $diferenca->i . ' minutos';
    } else {
        return 'há poucos segundos';
    }
}
?>