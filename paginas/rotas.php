<?php
session_start();

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "FelixBus");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializar filtro de pesquisa
$search = $_GET['search'] ?? '';
$filterOrigem = $_GET['origem'] ?? '';
$filterDestino = $_GET['destino'] ?? '';
$filterPreco = $_GET['preco'] ?? '';

// Construir a query SQL dinamicamente com filtros
$sql = "SELECT * FROM rotas WHERE 1=1";
if (!empty($filterOrigem)) {
    $sql .= " AND Origem LIKE '%$filterOrigem%'";
}
if (!empty($filterDestino)) {
    $sql .= " AND Destino LIKE '%$filterDestino%'";
}
if (!empty($filterPreco)) {
    $sql .= " AND Preço <= $filterPreco";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Consultar Rotas</title>
    <link rel="stylesheet" href="style_consultar_rotas.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #98c21c;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .route {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Consultar Rotas</h1>

    <!-- Barra de pesquisa com filtros -->
    <form method="GET">
        <input type="text" name="origem" placeholder="Origem..." value="<?= htmlspecialchars($filterOrigem) ?>">
        <input type="text" name="destino" placeholder="Destino..." value="<?= htmlspecialchars($filterDestino) ?>">
        <input type="number" name="preco" placeholder="Preço máximo (€)" value="<?= htmlspecialchars($filterPreco) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <!-- Tabela de resultados -->
    <table>
        <tr>
            <th>ID</th>
            <th>Origem</th>
            <th>Destino</th>
            <th>Preço (€)</th>
            <th>Capacidade</th>
            <th>Horário</th>
            <th>Data Criação</th>
            <th>Ação</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr class="<?= $row['Preço'] == min(array_column($result->fetch_all(MYSQLI_ASSOC), 'Preço')) ? 'highlight' : '' ?>">
            <td><?= $row['Id'] ?></td>
            <td><?= htmlspecialchars($row['Origem']) ?></td>
            <td><?= htmlspecialchars($row['Destino']) ?></td>
            <td><?= htmlspecialchars($row['Preço']) ?></td>
            <td><?= htmlspecialchars($row['Capacidade']) ?></td>
            <td><?= htmlspecialchars($row['Horário']) ?></td>
            <td><?= htmlspecialchars($row['Data_criacao']) ?></td>
            <td>
                <a href="detalhes_rota.php?id=<?= $row['Id'] ?>" class="continue-button">Continuar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="pagina_inicial.php" class="back-button">Voltar</a>
</body>
</html>
