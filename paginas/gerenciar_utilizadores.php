<?php
session_start();
// Verificar permissão de administrador
if (!isset($_SESSION['user_perfil']) || $_SESSION['user_perfil'] !== 'administrador') {
    echo "<script>alert('Acesso negado!'); window.location.href = 'pagina_inicial.html';</script>";
    exit();
}

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "FelixBus");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Processar as ações de criar/editar/excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $perfil = $_POST['perfil'];

    if ($action === 'create') {
        // Salvar senha diretamente (sem encriptação)
        $password = $_POST['password'];
        $sql = "INSERT INTO utilizadores (nome, email, password, perfil) VALUES ('$nome', '$email', '$password', '$perfil')";
    } elseif ($action === 'edit') {
        $sql = "UPDATE utilizadores SET nome = '$nome', email = '$email', perfil = '$perfil' WHERE id = $id";
    }

    $message = $conn->query($sql) ? "Operação realizada com sucesso!" : "Erro: " . $conn->error;
}

// Excluir utilizador (via GET)
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM utilizadores WHERE id = $id";
    $message = $conn->query($sql) ? "Utilizador excluído com sucesso!" : "Erro: " . $conn->error;
}

// Buscar utilizadores para listar
$result = $conn->query("SELECT * FROM utilizadores");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Utilizadores</title>
    <script>
        // Mostrar o formulário para criar ou editar
        function showForm(action, user = null) {
            document.getElementById('form-section').style.display = 'block';
            document.getElementById('action').value = action;

            if (action === 'edit' && user) {
                document.getElementById('id').value = user.id;
                document.getElementById('nome').value = user.nome;
                document.getElementById('email').value = user.email;
                document.getElementById('perfil').value = user.perfil;
                document.getElementById('password').style.display = 'none'; // Oculta campo de senha
            } else {
                document.getElementById('id').value = '';
                document.getElementById('nome').value = '';
                document.getElementById('email').value = '';
                document.getElementById('perfil').value = 'cliente';
                document.getElementById('password').style.display = 'block'; // Mostra campo de senha
            }
        }
    </script>
</head>
<body>
    <h1>Gerenciar Utilizadores</h1>
    <?php if (isset($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <button onclick="showForm('create')">Criar Novo Utilizador</button>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nome'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['perfil'] ?></td>
            <td>
                <!-- Botão Editar chama a função JavaScript com os dados do utilizador -->
                <button onclick='showForm("edit", <?= json_encode($row) ?>)'>Editar</button>
                <!-- Link para excluir utilizador -->
                <a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este utilizador?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Formulário para criar/editar utilizadores -->
    <div id="form-section" style="display: none;">
        <h2>Formulário de Utilizador</h2>
        <form method="POST">
            <input type="hidden" name="action" id="action">
            <input type="hidden" name="id" id="id">
            <label>Nome:</label><br>
            <input type="text" name="nome" id="nome" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" id="email" required><br>
            <label id="password-label">Password:</label><br>
            <input type="text" name="password" id="password"><br> <!-- Campo de senha visível -->
            <label>Perfil:</label><br>
            <select name="perfil" id="perfil" required>
                <option value="cliente">Cliente</option>
                <option value="administrador">Administrador</option>
                <option value="funcionário">Funcionário</option>
                <option value="visitante">Visitante</option>
            </select><br><br>
            <button type="submit">Salvar</button>
            <button type="button" onclick="document.getElementById('form-section').style.display = 'none';">Cancelar</button>
        </form>

    </div>
    <button type="submit" onclick="window.location.href='pagina_inicial_admin.html';">Voltar</button>
</body>
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Utilizadores</title>
    <link rel="stylesheet" href="style_gerenciar_utilizadores.css">
</head>
</html>