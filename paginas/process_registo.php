<?php

include("basedados/basedados.h");
session_start();

// Obtém os dados do formulário
$user = $_POST['user'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$confirm_pass = $_POST['confirm-pass'];

// Verifica se as passwords coincidem
if ($pass !== $confirm_pass) {
    echo "<script>
            alert('As passwords não são iguais. Tente novamente.');
            window.location.href = 'PgRegisto.html';
          </script>";
    exit();
}

// Verificar se o e-mail já está registrado
$sql_check_email = "SELECT COUNT(*) FROM utilizadores WHERE Email = ?";
$preparaconsulta_check_email = $conn->prepare($sql_check_email);
$preparaconsulta_check_email->bind_param("s", $email);
$preparaconsulta_check_email->execute();
$preparaconsulta_check_email->bind_result($email_exists);
$preparaconsulta_check_email->fetch();

//resultado da consulta SELECT
$preparaconsulta_check_email->free_result();

// Se o e-mail já existir, exibe uma mensagem de erro
if ($email_exists > 0) {
    echo "<script>
            alert('Este e-mail já está registrado. Tente novamente com outro e-mail.');
            window.location.href = 'PgRegisto.html';
          </script>";
    exit();
}

// Criptografa a senha com MD5
$hashed_password = md5($pass);

// Prepara a consulta para inserir os dados
$sql = "INSERT INTO utilizadores (Nome, Email, Password) VALUES (?, ?, ?)";
$preparaconulta = $conn->prepare($sql);
$preparaconulta->bind_param("sss", $user, $email, $hashed_password); 

// Executa a consulta e verifica sucesso
if ($preparaconulta->execute()) {
    echo "<script>
            alert('Registro realizado com sucesso! Pode fazer login agora.');
            window.location.href = 'PgLogin.html';
          </script>";
} else {
    echo "<script>
            alert('Erro ao registrar utilizador. Tente novamente.');
            window.location.href = 'PgRegisto.html';
          </script>";
}

// Fecha a conexão
$conn->close();
?>