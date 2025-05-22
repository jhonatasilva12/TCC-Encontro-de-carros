<?php
include_once('./../conexao.php');

// Inicialização de variaveis

$comentario = "";
$imagem_comentario = null;
$like_comentario = 0;       
$data_comentario = "";       
$fk_id_post = "";
$fk_id_user = "";

// Processamento da imagem 
if (isset($_FILES['imagem_comentario']) && $_FILES['imagem_comentario']['error'] == 0) {
    $ext = pathinfo($_FILES['imagem_comentario']['name'], PATHINFO_EXTENSION);
    $nome_imagem = 'coment_' . uniqid() . '.' . $ext;
    $diretorio = "assets/images/posts/";

    if (!move_uploaded_file($_FILES['imagem_comentario']['tmp_name'], $diretorio . $nome_imagem)) {
        die("Erro: Não foi possível salvar a imagem.");
    }
    $imagem_comentario = $nome_imagem;
}

// Dados do formulário
$comentario = $_POST['comentario'];
$fk_id_post = $_POST['fk_id_post'];
$fk_id_user = $_POST['fk_id_user'];

// Query 
$query = "INSERT INTO tb_comentario (
   
    comentario,
    imagem_comentario,
    like_comentario,
    data_comentario,     // Será preenchida pelo MySQL
    fk_id_post,
    fk_id_user
) VALUES (NULL, ?, ?, ?, NOW(), ?, ?)";  

$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $comentario);
$stmt->bindValue(2, $imagem_comentario);
$stmt->bindValue(3, $like_comentario);
$stmt->bindValue(4, $fk_id_post);
$stmt->bindValue(5, $fk_id_user);

if ($stmt->execute()) {
    header("Location: ver_post.php?id=" . $fk_id_post . "&sucesso=1");
} else {
    print_r($stmt->errorInfo());
    echo "Erro: Comentário não pôde ser salvo.";
}
?>

// o atribulto data_comentario n sei se e necessario deichar, pq pelo que eu sei ele ja vai ir automaticamente por conta do mysql, mas se for preciso e so tirar
