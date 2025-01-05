<?php
include("../basedados/basedados.h");
session_start();

// Verificar se o utilizador está logado e tem perfil de funcionário
if (!isset($_SESSION['Utilizador_id']) || ($_SESSION['user_perfil'] !== 'funcionário' && $_SESSION['user_perfil'] !== 'administrador')) {
    echo "<script>alert('Acesso negado! Apenas funcionários podem acessar esta página.'); window.location.href = 'PgLogin.html';</script>";
    exit();
}

//Ações de criar/editar/excluir saldo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $utilizador_id = $_POST['utilizador_id'];
    $saldo = $_POST['saldo'];

    // Verificar se o Utilizador existe
    $user_check = $conn->query("SELECT Id FROM utilizadores WHERE Id = '$utilizador_id'");

    if ($user_check->num_rows === 0) {
        $message = "Esse utilizador nao existe";
    } else {
        //se existir cria
        if ($action === 'create') {
            $sql = "INSERT INTO saldo (Utilizador_id, Saldo) VALUES ('$utilizador_id', '$saldo')";
        } elseif ($action === 'edit') {
        //se existir edita
            $sql = "UPDATE saldo SET Utilizador_id = '$utilizador_id', Saldo = '$saldo' WHERE Id = $id";
        }

        $message = $conn->query($sql) ? "Operação realizada com sucesso!" : "Erro: " . $conn->error;
    }
}

//  Açao de excluir
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id']; // ID do saldo a ser excluído
    $sql = "DELETE FROM saldo WHERE Id = $id";  // Query para excluir
    $message = $conn->query($sql) ? "Registro excluído com sucesso!" : "Erro: " . $conn->error;
}

// Inicializar filtro de pesquisa
$search = $_GET['search'] ?? '';

// Consultar saldos com filtro de pesquisa
$sql = "SELECT * FROM saldo WHERE Utilizador_id LIKE '%$search%' OR Saldo LIKE '%$search%'";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Saldos</title>
    <script>
        // Mostrar o formulário para criar ou editar
        function showForm(action, saldo = null) {
            document.getElementById('form-section').style.display = 'block';
            document.getElementById('action').value = action;

            if (action === 'edit' && saldo) {
                document.getElementById('id').value = saldo.Id;
                document.getElementById('utilizador_id').value = saldo.Utilizador_id;
                document.getElementById('saldo').value = saldo.Saldo;
            } else {
                document.getElementById('id').value = '';
                document.getElementById('utilizador_id').value = '';
                document.getElementById('saldo').value = '';
            }
        }
    </script>
</head>
<body>
    <h1>Gerenciar Saldos</h1>
    <?php if (isset($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <!-- Barra de pesquisa -->
    <form method="GET">
        <input type="text" name="search" placeholder="Pesquisar por Utilizador ID ou Saldo..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <button onclick="showForm('create')">Adicionar Novo Saldo</button>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Utilizador ID</th>
            <th>Saldo</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Id'] ?></td>
            <td><?= $row['Utilizador_id'] ?></td>
            <td><?= number_format($row['Saldo'], 2, ',', '.') ?></td>
            <td>
                <button onclick='showForm("edit", <?= json_encode($row) ?>)'>Editar</button>
                <a href="?action=delete&id=<?= $row['Id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este registro?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Formulário para criar/editar saldos -->
    <div id="form-section" style="display: none;">
        <h2>Formulário de Saldo</h2>
        <form method="POST">
            <input type="hidden" name="action" id="action">
            <input type="hidden" name="id" id="id">
            <label>Utilizador ID:</label><br>
            <input type="number" name="utilizador_id" id="utilizador_id" required><br>
            <label>Saldo:</label><br>
            <input type="number" step="0.01" name="saldo" id="saldo" required><br><br>
            <button type="submit">Salvar</button>
            <button type="button" onclick="document.getElementById('form-section').style.display = 'none';">Cancelar</button>
        </form>
    </div>


    
    <button type="submit" onclick="window.location.href='pagina_inicial_funcionario.php';">Inicio</button>
    <button type="submit" onclick="window.location.href='gerenciar_saldo_FA.php';">Voltar</button>
</body>
<link rel="stylesheet" href="style_gerenciar_rotas.css">
</html>
