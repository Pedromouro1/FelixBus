<?php
include("../basedados/basedados.h");
session_start();

// Verificar se o utilizador está logado e tem perfil de funcionário
if (!isset($_SESSION['Utilizador_id']) || ($_SESSION['user_perfil'] !== 'funcionário' && $_SESSION['user_perfil'] !== 'administrador')) {
    echo "<script>alert('Acesso negado! Apenas funcionários podem acessar esta página.'); window.location.href = 'PgLogin.html';</script>";
    exit();
}

//Ações de criar/editar/excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $utilizador_id = $_POST['utilizador_id'];
    $rota_id = $_POST['rota_id'];
    $data_viagem = $_POST['data_viagem'];
    $horario = $_POST['horario'];

    // Verificar se o ID do Utilizador existe
    $user_check = $conn->query("SELECT Id FROM utilizadores WHERE Id = '$utilizador_id'");

    // Verificar se o ID da Rota existe
    $rota_check = $conn->query("SELECT Id FROM rotas WHERE Id = '$rota_id'");

     // Verifica se o utilizador ou a rota existem na base de dados
    if ($user_check->num_rows === 0) {
        $message = "Nao existe esse utilizador";
    } elseif ($rota_check->num_rows === 0) {
        $message = "Nao existe essa rota";
    } else {
        // Se existirem cria
        if ($action === 'create') {
            $sql = "INSERT INTO bilhetes (Utilizador_id, Rota_id, Data_viagem, Horario ) VALUES ('$utilizador_id', '$rota_id', '$data_viagem', '$horario')";
        // Se existirem edita
        } elseif ($action === 'edit') {
            $sql = "UPDATE bilhetes SET Utilizador_id = '$utilizador_id', Rota_id = '$rota_id', Data_viagem = '$data_viagem', Horario = '$horario', WHERE Id = $id";
        }
        //executa a query e retorna uma mensagem
        $message = $conn->query($sql) ? "Operação realizada com sucesso!" : "Erro: " . $conn->error;
    }
}

// Açao de excluir
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id']; // ID do bilhete a ser excluído
    $sql = "DELETE FROM bilhetes WHERE Id = $id"; // Query para excluir
    $message = $conn->query($sql) ? "Bilhete excluído com sucesso!" : "Erro: " . $conn->error;
}

// Inicializar filtro de pesquisa 
$search = $_GET['search'] ?? '';

// Consultar bilhetes com filtro de pesquisa
$sql = "SELECT * FROM bilhetes WHERE Utilizador_id LIKE '%$search%' OR Rota_id LIKE '%$search%'";
$result = $conn->query($sql); //executa a comsulta
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

            } else {
                document.getElementById('id').value = '';
                document.getElementById('utilizador_id').value = '';
                document.getElementById('rota_id').value = '';
                document.getElementById('data_viagem').value = '';
                document.getElementById('horario').value = '';
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
    <table >
        <tr>
            <th>ID</th>
            <th>Utilizador</th>
            <th>Rota</th>
            <th>Data da Viagem</th>
            <th>Horário</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Id'] ?></td>
            <td><?= $row['Utilizador_id'] ?></td>
            <td><?= $row['Rota_id'] ?></td>
            <td><?= $row['Data_viagem'] ?></td>
            <td><?= $row['Horario'] ?></td>            <td>
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
            <button type="submit">Salvar</button>
            <button type="button" onclick="document.getElementById('form-section').style.display = 'none';">Cancelar</button>
        </form>
    </div>
    <button type="submit" onclick="window.location.href='pagina_inicial_funcionario.php';">Inicio</button>
    <button type="submit" onclick="window.location.href='gerenciar_bilhetes_FA.php';">Voltar</button>
</body>
</html>
