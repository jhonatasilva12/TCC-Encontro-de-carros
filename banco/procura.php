<?php
// Conecta ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'db_meetcar');
// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query para buscar posts com informações do usuário
$sql = "SELECT p.*, u.nome_user, u.sobrenome_user
        FROM tb_post p
        JOIN tb_user u ON p.fk_id_user = u.id_user
        ORDER BY p.data_post DESC";
$result = $conn->query($sql);

// Array para armazenar os posts
$posts = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

$conn->close();
?>