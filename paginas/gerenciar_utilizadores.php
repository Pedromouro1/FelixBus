<?php
include("../basedados/basedados.h");
session_start();

// Verificar permissão de administrador
if (!isset($_SESSION['user_perfil']) || $_SESSION['user_perfil'] !== 'administrador') {
    echo "<script>alert('Acesso negado!'); window.location.href = 'pagina_inicial.html';</script>";
    exit();
}

//Ações de criar/editar/excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $perfil = $_POST['perfil'];
   //se existir cria
    if ($action === 'create') {
        $password = $_POST['password'];
        $sql = "INSERT INTO utilizadores (nome, email, password, perfil) VALUES ('$nome', '$email', '$password', '$perfil')";
    } elseif ($action === 'edit') {
    //se existir edita
        $sql = "UPDATE utilizadores SET nome = '$nome', email = '$email', perfil = '$perfil' WHERE id = $id";
    }

    $message = $conn->query($sql) ? "Operação realizada com sucesso!" : "Erro: " . $conn->error;
}
                    
//  Açao de excluir
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM utilizadores WHERE id = $id";
    $message = $conn->query($sql) ? "Utilizador excluído com sucesso!" : "Erro: " . $conn->error;
}

// Inicializar filtro de pesquisa
$search = $_GET['search'] ?? '';

// Determinar a direção da ordenação 
$orderDirection = ($_GET['direction'] ?? 'asc') === 'asc' ? 'asc' : 'desc';

// Consultar utilizadores com filtro de pesquisa e ordenação por nome
$sql = "SELECT * FROM utilizadores 
        WHERE nome LIKE '%$search%' OR email LIKE '%$search%' OR perfil LIKE '%$search%' 
        ORDER BY nome $orderDirection";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Utilizadores</title>
    <script>
        function showForm(action, user = null) {
            document.getElementById('form-section').style.display = 'block';
            document.getElementById('action').value = action;

            if (action === 'edit' && user) {
                document.getElementById('id').value = user.id;
                document.getElementById('nome').value = user.nome;
                document.getElementById('email').value = user.email;
                document.getElementById('perfil').value = user.perfil;
                document.getElementById('password').style.display = 'none';
            } else {
                document.getElementById('id').value = '';
                document.getElementById('nome').value = '';
                document.getElementById('email').value = '';
                document.getElementById('perfil').value = 'cliente';
                document.getElementById('password').style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <h1>Gerenciar Utilizadores</h1>
    <?php if (isset($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <!-- Barra de pesquisa -->
    <form method="GET">
        <input type="text" name="search" placeholder="Pesquisar por Nome, Email ou Perfil..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <button onclick="showForm('create')">Criar Novo Utilizador</button>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>
                <a href="?search=<?= htmlspecialchars($search) ?>&direction=<?= $orderDirection === 'asc' ? 'desc' : 'asc' ?>">
                    Nome
                </a>
            </th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['perfil']) ?></td>
            <td>
                <button onclick='showForm("edit", <?= json_encode($row) ?>)'>Editar</button>
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
            <input type="text" name="password" id="password"><br>
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
    <button onclick="window.location.href='pagina_inicial_admin.php';">Inicio</button>
    <button onclick="window.location.href='gerenciar_utilizadores.php';">Voltar</button>
</body>
<link rel="stylesheet" href="style_gerenciar_utilizadores.css">
</html>