<?php 


// db_connect.php - conexão com o banco 

try {

$pdo = new PDO('mysql:host=localhost;dbname=db_meetcar', 'root', '' );
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
     echo "Erro ao conectar o banco" . $e->getMessage();
     exit;
}


?>
