<?php
include("basedados/basedados.h");
session_start();

// Verificar permissão de administrador
if (!isset($_SESSION['user_perfil']) || ($_SESSION['user_perfil'] !== 'funcionário' && $_SESSION['user_perfil'] !== 'administrador')) {
    echo "<script>alert('Acesso negado!'); window.location.href = 'pagina_inicial.html';</script>";
    exit();
}

//Ações de criar/editar/excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $origem = $_POST['origem'];
    $destino = $_POST['destino'];
    $preco = $_POST['preco'];
    $capacidade = $_POST['capacidade'];
    $horario = $_POST['horario'];

    if ($action === 'create') {
        //para criar
        $sql = "INSERT INTO rotas (Origem, Destino, Preço, Capacidade, Horário, Data_criacao) 
                VALUES ('$origem', '$destino', '$preco', '$capacidade', '$horario', CURDATE())";
        //para editar
    } elseif ($action === 'edit') {
        $sql = "UPDATE rotas SET Origem = '$origem', Destino = '$destino', Preço = '$preco', Capacidade = '$capacidade', Horário = '$horario' WHERE Id = $id";
    }
    //executa a query e retorna uma mensagem
    $message = $conn->query($sql) ? "Operação realizada com sucesso!" : "Erro: " . $conn->error;
}

//  Açao de excluir
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id']; // ID da rota  a ser excluído
    $sql = "DELETE FROM rotas WHERE Id = $id"; // Query para excluir
    $message = $conn->query($sql) ? "Rota excluída com sucesso!" : "Erro: " . $conn->error;
}

// Inicializar filtro de pesquisa
$search = $_GET['search'] ?? '';

// Determinar a direção da ordenação 
$orderDirection = ($_GET['direction'] ?? 'asc') === 'asc' ? 'asc' : 'desc';

// Consultar rotas com filtro de pesquisa e ordenação por Origem
$sql = "SELECT * FROM rotas 
        WHERE Origem LIKE '%$search%' OR Destino LIKE '%$search%' OR Preço LIKE '%$search%' 
        ORDER BY Origem $orderDirection";
$result = $conn->query($sql);
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

    <!-- Barra de pesquisa -->
    <form method="GET">
        <input type="text" name="search" placeholder="Pesquisar por Origem, Destino ou Preço..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <button onclick="showForm('create')">Criar Nova Rota</button>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>
                <a href="?search=<?= htmlspecialchars($search) ?>&direction=<?= $orderDirection === 'asc' ? 'desc' : 'asc' ?>">
                    Origem
                </a>
            </th>
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
            <td><?= htmlspecialchars($row['Origem']) ?></td>
            <td><?= htmlspecialchars($row['Destino']) ?></td>
            <td><?= htmlspecialchars($row['Preço']) ?></td>
            <td><?= htmlspecialchars($row['Capacidade']) ?></td>
            <td><?= htmlspecialchars($row['Horário']) ?></td>
            <td><?= htmlspecialchars($row['Data_criacao']) ?></td>
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

    <button type="submit" onclick="window.location.href='pagina_inicial_admin.php';">Inicio</button>
    <button type="submit" onclick="window.location.href='gerenciar_rotas.php';">Voltar</button>
</body>
<link rel="stylesheet" href="style_gerenciar_rotas.css">
</html>
