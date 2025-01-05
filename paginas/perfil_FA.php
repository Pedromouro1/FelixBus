<?php
include("basedados/basedados.h");
session_start();

// Verificar se o utilizador está logado e tem perfil de funcionário
if (!isset($_SESSION['Utilizador_id']) || $_SESSION['user_perfil'] !== 'funcionário') {
    echo "<script>alert('Acesso negado! Apenas funcionários podem acessar esta página.'); window.location.href = 'PgLogin.html';</script>";
    exit();
}

// Obter o ID do Utilizador logado
$userId = $_SESSION['Utilizador_id'];

// Prevenir SQL Injection ao consultar a base de dados
$sql = $conn->prepare("SELECT * FROM utilizadores WHERE id = ?"); // Prepara a consulta com a utilização de placeholders
$sql->bind_param("i", $userId); // Vincula o parâmetro à consulta (ID do utilizador como inteiro)
$sql->execute();
$result = $sql->get_result(); // Obtém o resultado da consulta

// Verifica se o utilizador foi encontrado na base de dados
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();  // Armazena os dados do utilizador encontrado
} else { 
    echo "<script>alert('Utilizador não encontrado!'); window.location.href = 'PgLogin.html';</script>";
    exit();
}


// Atualizar os dados do Utilizador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirmPassword = $conn->real_escape_string($_POST['confirm_password']);

    // Verificar se as senhas coincidem
    if ($password !== $confirmPassword) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } else {
        // Hash da senha para maior segurança
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $updateSql = $conn->prepare("UPDATE utilizadores SET nome = ?, email = ?, password = ? WHERE id = ?");
        $updateSql->bind_param("sssi", $nome, $email, $hashedPassword, $userId);

        if ($updateSql->execute()) {
            echo "<script>alert('Dados atualizados com sucesso!'); window.location.href = 'perfil.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar dados: " . $conn->error . "');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleCliente.css">
    <title>Perfil do Utilizador</title>
</head>
<body>
    <div class="container">
        <h1>Perfil</h1>

      

        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirmar Senha:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Atualizar</button>
        </form>
        <a href="pagina_inicial_funcionario.php" class="back-button">Voltar</a>
    </div>
</body>
</html>
