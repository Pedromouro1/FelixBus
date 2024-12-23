<?php
// Conexão com a base de dados
$servername = "localhost";
$username = "root"; // Usuário padrão do MySQL
$password = ""; // Senha do MySQL
$dbname = "FelixBus";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $usernameOrEmail = $_POST['user'];
    $password = $_POST['pass'];

    // Prevenir SQL Injection
    $usernameOrEmail = $conn->real_escape_string($usernameOrEmail);
    $password = $conn->real_escape_string($password);

    // Verificar se o e-mail e a senha são específicos do administrador
    if ($usernameOrEmail === 'admin@gmail.com' && $password === 'admin') {
        // Login bem-sucedido
        echo "<script>alert('Login bem-sucedido!');</script>";
        header("Location: ../admin/pagina_inicial_admin.html");
        exit();
    } else {
        // Senha incorreta ou usuário não encontrado
        echo "<script>alert('Usuário ou senha incorretos.');</script>";
    }
}

// Fechar conexão
$conn->close();
?>
