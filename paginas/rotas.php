<?php
session_start();
require 'db_connection.php';

$destino = $_SESSION['destino'];

// Obter os dados do usuário da base de dados
$sql = "SELECT * FROM rotas WHERE origem = '".$origem."' AND destino = '".$destino."' AND BETWEEN '" . $horario_inicio . "' AND  '" . $horario_fim . "' ORDER by id DESC;
$result = $conn->query($sql);s

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>alert('Usuário não encontrado!'); window.location.href = 'PgLogin.html';</script>";
    exit();
}

echo $sql;

?>