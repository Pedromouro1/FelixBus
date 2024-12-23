<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
    header("Location: ../FelixBus/PgLogin.html"); // Redireciona para a página de login se não estiver logado
    exit();
}
?>
