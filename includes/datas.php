<?php
require('./funcoes.php');

$meetCar = new MeetCarFunctions();
header('Content-Type: application/json');
echo $meetCar->buscaEventosJson();
?>