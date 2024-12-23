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

    // Consultar a base de dados
    $sql = "SELECT * FROM utilizadores WHERE (Nome = '$usernameOrEmail' OR Email = '$usernameOrEmail')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Verificar a senha
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) { // Comparar senha com hash
            // Login bem-sucedido
            echo "<script>alert('Login bem-sucedido!');</script>";
            header("Location: ../Felixbus/pagina_inicial_logado.html");
            exit();
        } else {
            // Senha incorreta
            echo "<script>alert('Senha incorreta. Tente novamente.');</script>";
        }
    } else {
        // Usuário não encontrado
        echo "<script>alert('Usuário ou e-mail não encontrado.');</script>";
    }
}

// Fechar conexão
$conn->close();
?>
