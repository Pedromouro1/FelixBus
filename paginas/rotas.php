<?php
session_start();

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "FelixBus");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializar filtro de pesquisa
$filterOrigem = $_GET['origem'] ?? '';
$filterDestino = $_GET['destino'] ?? '';
$filterPreco = $_GET['preco'] ?? '';

// Construir a query SQL dinamicamente com filtros
$sql = "SELECT * FROM rotas";
if (!empty($filterOrigem) || !empty($filterDestino) || !empty($filterPreco)) {
    $sql .= " WHERE 1=1";
    if (!empty($filterOrigem)) {
        $sql .= " AND Origem LIKE '%" . $conn->real_escape_string($filterOrigem) . "%'";
    }
    if (!empty($filterDestino)) {
        $sql .= " AND Destino LIKE '%" . $conn->real_escape_string($filterDestino) . "%'";
    }
    if (!empty($filterPreco)) {
        $sql .= " AND Preço <= " . $conn->real_escape_string($filterPreco);
    }
}

$result = $conn->query($sql);

// Obter os resultados em um array para evitar múltiplas leituras
$rows = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

// Determinar o preço mínimo (se necessário)
$minPrice = !empty($rows) ? min(array_column($rows, 'Preço')) : null;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Consultar Rotas</title>
    <link rel="stylesheet" href="style_consultar_rotas.css">
    <link rel="stylesheet" href="styleCliente.css">
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
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
            <tr class="<?= ($row['Preço'] == $minPrice) ? 'highlight' : '' ?>">
                <td><?= $row['Id'] ?></td>
                <td><?= htmlspecialchars($row['Origem']) ?></td>
                <td><?= htmlspecialchars($row['Destino']) ?></td>
                <td><?= htmlspecialchars($row['Preço']) ?></td>
                <td><?= htmlspecialchars($row['Capacidade']) ?></td>
                <td><?= htmlspecialchars($row['Horário']) ?></td>
                <td><?= htmlspecialchars($row['Data_criacao']) ?></td>
                <td>
                    <a href="comprar_bilhete.php?id=<?= $row['Id'] ?>" class="continue-button">Continuar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Nenhuma rota encontrada.</td>
            </tr>
        <?php endif; ?>
    </table>

    <a href="pagina_inicial.php" class="back-button">Voltar</a>
</body>
</html>