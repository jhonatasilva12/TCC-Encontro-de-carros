// insert_tb_post.php
<?php
include_once('./db_connect.php');

$data_post = $_POST['data_post'];
$fk_id_tipo_post = $_POST['fk_id_tipo_post'];
$fk_id_user = $_POST['fk_id_user'];
$imagem_post = $_POST['imagem_post'] ?? null; // Opcional
$likes_post = $_POST['likes_post'];
$texto_post = $_POST['texto_post'];
$titulo_post = $_POST['titulo_post'] ?? null; // Opcional

$query = "INSERT INTO tb_post (data_post, fk_id_tipo_post, fk_id_user, imagem_post, likes_post, texto_post, titulo_post) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $data_post);
$stmt->bindValue(2, $fk_id_tipo_post);
$stmt->bindValue(3, $fk_id_user);
$stmt->bindValue(4, $imagem_post);
$stmt->bindValue(5, $likes_post);
$stmt->bindValue(6, $texto_post);
$stmt->bindValue(7, $titulo_post);

if ($stmt->execute()) {
    echo "Post inserido com sucesso!";
} else {
    print_r($stmt->errorInfo());
    echo "Erro ao inserir post.";
}
?>
