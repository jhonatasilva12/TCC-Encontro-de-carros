<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['eventoId'])) {
    $_SESSION['evento_selecionado'] = $data['eventoId'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'ID do evento não recebido']);
}
?>