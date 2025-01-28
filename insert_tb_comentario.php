// insert_tb_comentario.php
<?php
include_once('./db_connect.php');

$data_comentario = $_POST['data_comentario'];
$fk_id_post = $_POST['fk_id_post'];
$fk_id_user = $_POST['fk_id_user'];
$imagem_comentario = $_POST['imagem_comentario'] ?? null; // Imagem é opcional
$likes_comentario = $_POST['likes_comentario'];
$texto_comentario = $_POST['texto_comentario'];

$query = "INSERT INTO tb_comentario (data_comentario, fk_id_post, fk_id_user, imagem_comentario, likes_comentario, texto_comentario) 
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $data_comentario);
$stmt->bindValue(2, $fk_id_post);
$stmt->bindValue(3, $fk_id_user);
$stmt->bindValue(4, $imagem_comentario);
$stmt->bindValue(5, $likes_comentario);
$stmt->bindValue(6, $texto_comentario);

if ($stmt->execute()) {
    echo "Comentário inserido com sucesso!";
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao inserir comentário.";
}
?>