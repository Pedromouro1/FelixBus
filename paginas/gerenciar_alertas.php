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
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $tipo = $_POST['tipo'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if ($action === 'create') {
        $sql = "INSERT INTO alertas (Titulo, Conteudo, Tipo, Ativo) VALUES ('$titulo', '$conteudo', '$tipo', $ativo)";
    } elseif ($action === 'edit') {
        $sql = "UPDATE alertas SET Titulo = '$titulo', Conteudo = '$conteudo', Tipo = '$tipo', Ativo = $ativo WHERE Id = $id";
    }

    $message = $conn->query($sql) ? "Operação realizada com sucesso!" : "Erro: " . $conn->error;
}

// Excluir alerta (via GET)
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM alertas WHERE Id = $id";
    $message = $conn->query($sql) ? "Alerta excluído com sucesso!" : "Erro: " . $conn->error;
}

// Buscar alertas para listar
$result = $conn->query("SELECT * FROM alertas");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Alertas</title>
    <link rel="stylesheet" href="style_gerenciar_alertas.css">
    <script>
        function showForm(action, alert = null) {
            document.getElementById('form-section').style.display = 'block';
            document.getElementById('action').value = action;

            if (action === 'edit' && alert) {
                document.getElementById('id').value = alert.Id;
                document.getElementById('titulo').value = alert.Titulo;
                document.getElementById('conteudo').value = alert.Conteudo;
                document.getElementById('tipo').value = alert.Tipo;
                document.getElementById('ativo').checked = alert.Ativo === "1";
            } else {
                document.getElementById('id').value = '';
                document.getElementById('titulo').value = '';
                document.getElementById('conteudo').value = '';
                document.getElementById('tipo').value = 'alerta';
                document.getElementById('ativo').checked = true;
            }
        }
    </script>
</head>
<body>
    <h1>Gerenciar Alertas</h1>
    <?php if (isset($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <button onclick="showForm('create')">Criar Novo Alerta</button>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Conteúdo</th>
            <th>Tipo</th>
            <th>Data Criação</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Id'] ?></td>
            <td><?= $row['Titulo'] ?></td>
            <td><?= $row['Conteudo'] ?></td>
            <td><?= $row['Tipo'] ?></td>
            <td><?= $row['Data_criacao'] ?></td>
            <td><?= $row['Ativo'] ? 'Sim' : 'Não' ?></td>
            <td>
                <button onclick='showForm("edit", <?= json_encode($row) ?>)'>Editar</button>
                <a href="?action=delete&id=<?= $row['Id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este alerta?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Formulário para criar/editar alertas -->
    <div id="form-section" style="display: none;">
        <h2>Formulário de Alerta</h2>
        <form method="POST">
            <input type="hidden" name="action" id="action">
            <input type="hidden" name="id" id="id">
            <label>Título:</label><br>
            <input type="text" name="titulo" id="titulo" required><br>
            <label>Conteúdo:</label><br>
            <textarea name="conteudo" id="conteudo" rows="5" required></textarea><br>
            <label>Tipo:</label><br>
            <select name="tipo" id="tipo" required>
                <option value="alerta">Alerta</option>
                <option value="Informacao">Informação</option>
                <option value="Promocao">Promoção</option>
            </select><br>
            <label>Ativo:</label>
            <input type="checkbox" name="ativo" id="ativo" value="1"><br><br>
            <button type="submit">Salvar</button>
            <button type="button" onclick="document.getElementById('form-section').style.display = 'none';">Cancelar</button>
        </form>
    </div>
    <button type="button" onclick="window.location.href='pagina_inicial_admin.html';">Voltar</button>
</body>
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Alertas</title>
    <link rel="stylesheet" href="style_gerenciar_alertas.css">
</head>
</html>
