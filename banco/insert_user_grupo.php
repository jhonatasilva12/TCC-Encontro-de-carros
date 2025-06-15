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

// Verifica se já está no grupo
$stmt = $meetcar->getPdo()->prepare("SELECT 1 FROM user_grupo WHERE fk_id_user = ? AND fk_id_grupo = ?");
$stmt->execute([$_SESSION['user_id'], $grupoId]);

if ($stmt->fetch()) {
    die("AVISO: Você já está no grupo");
}

// Insere no grupo
$stmt = $meetcar->getPdo()->prepare("INSERT INTO user_grupo (fk_id_user, fk_id_grupo) VALUES (?, ?)");
if ($stmt->execute([$_SESSION['user_id'], $grupoId])) {
    echo "SUCESSO";
} else {
    echo "ERRO: Falha ao entrar no grupo";
}