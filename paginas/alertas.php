<?php
include("../basedados/basedados.h");
session_start();

// Recebe os dados do formulário
$titulo = $_POST['titulo'];
$conteudo = $_POST['conteudo'];
$tipo = $_POST['tipo'];
$data_criacao = $_POST['data_criacao'];
$ativo = $_POST['ativo'];

// Prepara a consulta SQL para inserir o alerta
$sql = "INSERT INTO Alertas (Titulo, Conteudo, Tipo, Data_criacao, Ativo) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $titulo, $conteudo, $tipo, $data_criacao, $ativo);

// Executa a consulta
if ($stmt->execute()) {
    echo "<script>
            alert('Alerta inserido com sucesso!');
            window.location.href = 'pagina_inicial.html'; 
          </script>";
} else {
    echo "<script>
            alert('Erro ao inserir alerta.');
            window.location.href = 'pagina_inicial.html'; 
          </script>";
}

// Fecha a conexão
$conn->close();
?>
