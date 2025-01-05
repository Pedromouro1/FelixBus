<?php
include("../basedados/basedados.h");
session_start();

// Recebe os dados do formulário atraves do método POST
$titulo = $_POST['titulo'];
$conteudo = $_POST['conteudo'];
$tipo = $_POST['tipo'];
$data_criacao = $_POST['data_criacao'];
$ativo = $_POST['ativo'];

// Prepara a consulta SQL para inserir os dados na tabela
$sql = "INSERT INTO Alertas (Titulo, Conteudo, Tipo, Data_criacao, Ativo) VALUES (?, ?, ?, ?, ?)";
$consultaPreparada = $conn->prepare($sql); //para evitar sql injection
$consultaPreparada->bind_param("ssssi", $titulo, $conteudo, $tipo, $data_criacao, $ativo);

// Executa a consulta
if ($consultaPreparada->execute()) {
  //caso de 
    echo "<script>
            alert('Alerta inserido com sucesso!');
            window.location.href = 'pagina_inicial.html'; 
          </script>";
} else {
  //caso nao de
    echo "<script>
            alert('Erro ao inserir alerta.');
            window.location.href = 'pagina_inicial.html'; 
          </script>";
}

// Fecha a conexão
$conn->close();
?>
