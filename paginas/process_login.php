<?php
session_start(); 

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
        $user = $result->fetch_assoc();

        if ($password === $user['password']) { // Comparar diretamente

            $_SESSION['user_id'] = $user['id'];          // ID do usuário
            $_SESSION['user_nome'] = $user['nome'];      // Nome do usuário
            $_SESSION['user_perfil'] = $user['perfil'];  // Perfil do usuário
            // Verificar o cargo do utilizador
            if ($user['perfil'] === 'administrador') {
                // Redirecionar para a página do administrador
                echo "<script> alert('Bem vindo! Administrador!');
                window.location.href = 'pagina_inicial_admin.html';
              </script>";
              exit();
            } else {
                 // Exibir mensagem e redirecionar para a página do utilizador comum
                echo "<script>alert('Bem vindo!');
                window.location.href = 'pagina_inicial.html';
              </script>";
              exit();
            }
        } else {
            echo "<script>alert('Password incorreta, tente novamente.');
                window.location.href = 'PgLogin.html';
              </script>";
        }
    } else {
        echo "<script>alert('Utilizador nao encontrado, tente novamente.');
        window.location.href = 'PgLogin.html';
      </script>";
    }
}

// Fechar conexão
$conn->close();
?>