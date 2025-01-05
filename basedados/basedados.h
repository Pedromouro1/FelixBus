<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FelixBus";
 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Nao foi possivel ligar com a base de dados: " . $conn->connect_error);
}
?>