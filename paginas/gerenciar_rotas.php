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
    $origem = $_POST['origem'];
    $destino = $_POST['destino'];
    $preco = $_POST['preco'];
    $capacidade = $_POST['capacidade'];
    $horario = $_POST['horario'];

    if ($action === 'create') {
        $sql = "INSERT INTO rotas (Origem, Destino, Preço, Capacidade, Horário, Data_criacao) 
                VALUES ('$origem', '$destino', '$preco', '$capacidade', '$horario', CURDATE())";
    } elseif ($action === 'edit') {
        $sql = "UPDATE rotas SET Origem = '$origem', Destino = '$destino', Preço = '$preco', Capacidade = '$capacidade', Horário = '$horario' WHERE Id = $id";
    }

    $message = $conn->query($sql) ? "Operação realizada com sucesso!" : "Erro: " . $conn->error;
}

// Excluir rota (via GET)
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM rotas WHERE Id = $id";
    $message = $conn->query($sql) ? "Rota excluída com sucesso!" : "Erro: " . $conn->error;
}

// Buscar rotas para listar
$result = $conn->query("SELECT * FROM rotas");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Rotas</title>
    <script>
        // Mostrar o formulário para criar ou editar
        function showForm(action, route = null) {
            document.getElementById('form-section').style.display = 'block';
            document.getElementById('action').value = action;

            if (action === 'edit' && route) {
                document.getElementById('id').value = route.Id;
                document.getElementById('origem').value = route.Origem;
                document.getElementById('destino').value = route.Destino;
                document.getElementById('preco').value = route.Preço;
                document.getElementById('capacidade').value = route.Capacidade;
                document.getElementById('horario').value = route.Horário;
            } else {
                document.getElementById('id').value = '';
                document.getElementById('origem').value = '';
                document.getElementById('destino').value = '';
                document.getElementById('preco').value = '';
                document.getElementById('capacidade').value = '';
                document.getElementById('horario').value = '';
            }
        }
    </script>
</head>
<body>
    <h1>Gerenciar Rotas</h1>
    <?php if (isset($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <button onclick="showForm('create')">Criar Nova Rota</button>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Origem</th>
            <th>Destino</th>
            <th>Preço</th>
            <th>Capacidade</th>
            <th>Horário</th>
            <th>Data Criação</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Id'] ?></td>
            <td><?= $row['Origem'] ?></td>
            <td><?= $row['Destino'] ?></td>
            <td><?= $row['Preço'] ?></td>
            <td><?= $row['Capacidade'] ?></td>
            <td><?= $row['Horário'] ?></td>
            <td><?= $row['Data_criacao'] ?></td>
            <td>
                <button onclick='showForm("edit", <?= json_encode($row) ?>)'>Editar</button>
                <a href="?action=delete&id=<?= $row['Id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esta rota?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Formulário para criar/editar rotas -->
    <div id="form-section" style="display: none;">
        <h2>Formulário de Rota</h2>
        <form method="POST">
            <input type="hidden" name="action" id="action">
            <input type="hidden" name="id" id="id">
            <label>Origem:</label><br>
            <input type="text" name="origem" id="origem" required><br>
            <label>Destino:</label><br>
            <input type="text" name="destino" id="destino" required><br>
            <label>Preço:</label><br>
            <input type="number" step="0.01" name="preco" id="preco" required><br>
            <label>Capacidade:</label><br>
            <input type="number" name="capacidade" id="capacidade" required><br>
            <label>Horário:</label><br>
            <input type="time" name="horario" id="horario" required><br><br>
            <button type="submit">Salvar</button>
            <button type="button" onclick="document.getElementById('form-section').style.display = 'none';">Cancelar</button>
        </form>
    </div>

    <button type="button" onclick="window.location.href='pagina_inicial_admin.html';">Voltar</button>
</body>
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Rotas</title>
    <link rel="stylesheet" href="style_gerenciar_rotas.css">
</head>
</html>
