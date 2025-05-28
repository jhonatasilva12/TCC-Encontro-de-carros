<?php
// Desative a saída de erros HTML para a API
ini_set('display_errors', 0);
header('Content-Type: application/json');

session_start();
require_once 'db_connect.php';

try {
    // Verifique o método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido', 405);
    }

    // Verifique autenticação
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Não autorizado', 401);
    }

    // Obtenha os dados JSON
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON inválido', 400);
    }

    $contentType = $input['type'] ?? '';
    $contentId = filter_var($input['id'] ?? 0, FILTER_VALIDATE_INT);

    // Validações
    if (!in_array($contentType, ['post', 'evento'])) {
        throw new Exception('Tipo de conteúdo inválido', 400);
    }

    if (!$contentId) {
        throw new Exception('ID inválido', 400);
    }

    $pdo->beginTransaction();

    if ($contentType === 'post') {
        // Verificação de propriedade do post
        $stmt = $pdo->prepare("SELECT fk_id_user, imagem_post FROM tb_post WHERE id_post = ?");
        $stmt->execute([$contentId]);
        $content = $stmt->fetch();

        if (!$content) {
            throw new Exception('Post não encontrado', 404);
        }

        if ($content['fk_id_user'] !== $_SESSION['user_id']) {
            throw new Exception('Permissão negada', 403);
        }

        // Excluir dependências
        $pdo->prepare("DELETE FROM tb_comentario WHERE fk_id_post = ?")->execute([$contentId]);
        $pdo->prepare("DELETE FROM likes_post WHERE fk_id_post = ?")->execute([$contentId]);

        // Excluir post
        $pdo->prepare("DELETE FROM tb_post WHERE id_post = ?")->execute([$contentId]);

        // Excluir imagem se existir
        if ($content['imagem_post']) {
            $imagePath = "../assets/images/posts/" . $content['imagem_post'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    } else {
        // Lógica para eventos (similar)
        $stmt = $pdo->prepare("SELECT fk_id_criador, img_evento FROM tb_evento WHERE id_evento = ?");
        $stmt->execute([$contentId]);
        $content = $stmt->fetch();

        if (!$content) {
            throw new Exception('Evento não encontrado', 404);
        }

        if ($content['fk_id_criador'] !== $_SESSION['user_id']) {
            throw new Exception('Permissão negada', 403);
        }

        // Excluir dependências
        $pdo->prepare("DELETE FROM evento_user WHERE fk_id_evento = ?")->execute([$contentId]);

        // Excluir evento
        $pdo->prepare("DELETE FROM tb_evento WHERE id_evento = ?")->execute([$contentId]);

        // Excluir imagem se existir
        if ($content['img_evento']) {
            $imagePath = "../assets/images/events/" . $content['img_evento'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => ucfirst($contentType) . ' excluído com sucesso'
    ]);

} catch (Exception $e) {
    http_response_code(is_int($e->getCode()) ? $e->getCode() : 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
    
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
}