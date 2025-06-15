<?php
require_once('./autentica.php');
require_once('../includes/funcoes.php');

if (!isset($_SESSION['user_id'])) {
    die("ERRO: Não autenticado");
}

$grupoId = (int)$_GET['id'] ?? 0;
if ($grupoId <= 0) {
    die("ERRO: ID inválido");
}

$meetcar = new MeetCarFunctions();

// Remove do grupo
$stmt = $meetcar->getPdo()->prepare("DELETE FROM user_grupo WHERE fk_id_user = ? AND fk_id_grupo = ?");
if ($stmt->execute([$_SESSION['user_id'], $grupoId])) {
    echo "SUCESSO";
} else {
    echo "ERRO: Falha ao sair do grupo";
}