<?php
include("../basedados/basedados.h");
session_start();

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

    // Verificar se a consulta encontrou algum utilizador
    if ($result->num_rows > 0) {
         // Se encontrou o utilizador, extrai os dados da primeira linha
        $user = $result->fetch_assoc();

        // Comparar a senha criptografada
        if ($encryptedPassword === $user['password']) { // Certifique-se que a coluna se chama 'password'
            // Iniciar a sessão do Utilizador
            $_SESSION['Utilizador_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_perfil'] = $user['perfil'];
            $_SESSION['logged_in'] = true;

            // Verificar o perfil do utilizador
            if ($user['perfil'] === 'administrador') {
                echo "<script>
                        alert('Bem vindo, Administrador!');
                        window.location.href = 'pagina_inicial_admin.php';
                      </script>";
                exit();
            } elseif ($user['perfil'] === 'funcionário') {
                echo "<script>
                        alert('Bem vindo, Funcionário!');
                        window.location.href = 'pagina_inicial_funcionario.php';
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