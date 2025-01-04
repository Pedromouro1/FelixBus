<?php
session_start();

// Conexão com a base de dados
$servername = "localhost";
$username = "root";
$password = "";
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
    $encryptedPassword = md5($password); // Criptografa a senha inserida com MD5

    // Consultar a base de dados
    $sql = "SELECT * FROM utilizadores WHERE (nome = '$usernameOrEmail' OR email = '$usernameOrEmail')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Comparar a senha criptografada
        if ($encryptedPassword === $user['password']) { // Certifique-se que a coluna se chama 'password'
            // Iniciar a sessão do usuário
            $_SESSION['Utilizador_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_perfil'] = $user['perfil'];
            $_SESSION['logged_in'] = true;

            // Verificar o perfil do utilizador
            if ($user['perfil'] === 'administrador') {
                echo "<script>
                        alert('Bem vindo, Administrador!');
                        window.location.href = 'pagina_inicial_admin.html';
                      </script>";
                exit();
            } elseif ($user['perfil'] === 'funcionário') {
                echo "<script>
                        alert('Bem vindo, Funcionário!');
                        window.location.href = 'pagina_inicial_funcionario.html';
                      </script>";
                exit();
            } else {
                echo "<script>
                        alert('Bem vindo!');
                        window.location.href = 'pagina_inicial.php';
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('Password incorreta, tente novamente.');
                    window.location.href = 'PgLogin.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Utilizador não encontrado, tente novamente.');
                window.location.href = 'PgLogin.html';
              </script>";
    }
}

// Fechar conexão
$conn->close();
?>