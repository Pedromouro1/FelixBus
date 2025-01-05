<?php
include("basedados/basedados.h");
session_start();

// Destrói todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona para a página inicial
header("Location: pagina_inicial.php");
exit;
?>