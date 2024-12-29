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

// Processar ações de criar/editar/excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $utilizador_id = $_POST['utilizador_id'];
    $rota_id = $_POST['rota_id'];
    $data_viagem = $_POST['data_viagem'];
    $horario = $_POST['horario'];
    $status = $_POST['status'];

    if ($action === 'create') {
        $sql = "INSERT INTO bilhetes (Utilizador_id, Rota_id, Data_viagem, Horario, Status) VALUES ('$utilizador_id', '$rota_id', '$data_viagem', '$horario', '$status')";
    } elseif ($action === 'edit') {
        $sql = "UPDATE bilhetes SET Utilizador_id = '$utilizador_id', Rota_id = '$rota_id', Data_viagem = '$data_viagem', Horario = '$horario', Status = '$status' WHERE Id = $id";
    }

    $message = $conn->query($sql) ? "Operação realizada com sucesso!" : "Erro: " . $conn->error;
}

// Excluir bilhete (via GET)
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM bilhetes WHERE Id = $id";
    $message = $conn->query($sql) ? "Bilhete excluído com sucesso!" : "Erro: " . $conn->error;
}

// Inicializar filtro de pesquisa
$search = $_GET['search'] ?? '';

// Consultar bilhetes com filtro de pesquisa
$sql = "SELECT * FROM bilhetes WHERE Utilizador_id LIKE '%$search%' OR Rota_id LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Bilhetes</title>
    <link rel="stylesheet" href="style_gerenciar_bilhetes.css">
    <script>
        function showForm(action, ticket = null) {
            document.getElementById('form-section').style.display = 'block';
            document.getElementById('action').value = action;

            if (action === 'edit' && ticket) {
                document.getElementById('id').value = ticket.Id;
                document.getElementById('utilizador_id').value = ticket.Utilizador_id;
                document.getElementById('rota_id').value = ticket.Rota_id;
                document.getElementById('data_viagem').value = ticket.Data_viagem;
                document.getElementById('horario').value = ticket.Horario;
                document.getElementById('status').value = ticket.Status;
            } else {
                document.getElementById('id').value = '';
                document.getElementById('utilizador_id').value = '';
                document.getElementById('rota_id').value = '';
                document.getElementById('data_viagem').value = '';
                document.getElementById('horario').value = '';
                document.getElementById('status').value = 'ativo';
            }
        }
    </script>
</head>
<body>
    <h1>Gerenciar Bilhetes</h1>
    <?php if (isset($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <!-- Barra de pesquisa -->
    <form method="GET">
        <input type="text" name="search" placeholder="Pesquisar por ID do Utilizador ou ID da Rota..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <button onclick="showForm('create')">Criar Novo Bilhete</button>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Utilizador</th>
            <th>Rota</th>
            <th>Data da Viagem</th>
            <th>Horário</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Id'] ?></td>
            <td><?= $row['Utilizador_id'] ?></td>
            <td><?= $row['Rota_id'] ?></td>
            <td><?= $row['Data_viagem'] ?></td>
            <td><?= $row['Horario'] ?></td>
            <td><?= ucfirst($row['Status']) ?></td>
            <td>
                <button onclick='showForm("edit", <?= json_encode($row) ?>)'>Editar</button>
                <a href="?action=delete&id=<?= $row['Id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este bilhete?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Formulário para criar/editar bilhetes -->
    <div id="form-section" style="display: none;">
        <h2>Formulário de Bilhete</h2>
        <form method="POST">
            <input type="hidden" name="action" id="action">
            <input type="hidden" name="id" id="id">
            <label>ID do Utilizador:</label><br>
            <input type="number" name="utilizador_id" id="utilizador_id" required><br>
            <label>ID da Rota:</label><br>
            <input type="number" name="rota_id" id="rota_id" required><br>
            <label>Data da Viagem:</label><br>
            <input type="date" name="data_viagem" id="data_viagem" required><br>
            <label>Horário:</label><br>
            <input type="time" name="horario" id="horario" required><br>
            <label>Status:</label><br>
            <select name="status" id="status" required>
                <option value="ativo">Ativo</option>
                <option value="usado">Usado</option>
                <option value="cancelado">Cancelado</option>
            </select><br><br>
            <button type="submit">Salvar</button>
            <button type="button" onclick="document.getElementById('form-section').style.display = 'none';">Cancelar</button>
        </form>
    </div>
    <button type="submit" onclick="window.location.href='pagina_inicial_admin.html';">Inicio</button>
    <button type="submit" onclick="window.location.href='gerenciar_bilhetes.php';">Voltar</button>
</body>
</html>
