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

// Construir a query SQL dinamicamente com filtros
$sql = "SELECT * FROM rotas WHERE 1=1";
$params = [];
$types = '';

if (!empty($filterOrigem)) {
    $sql .= " AND Origem LIKE ?";
    $params[] = '%' . $filterOrigem . '%';
    $types .= 's';
}
if (!empty($filterDestino)) {
    $sql .= " AND Destino LIKE ?";
    $params[] = '%' . $filterDestino . '%';
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Processar os resultados em uma matriz
$rotas = [];
while ($row = $result->fetch_assoc()) {
    $rotas[] = $row;
}

// Calcular o menor preço, se houver resultados
$menorPreco = null;
if (!empty($rotas)) {
    $menorPreco = min(array_column($rotas, 'Preço'));
}



?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Consultar Rotas</title>
    <link rel="stylesheet" href="style_consultar_rotas.css">
    <style>
        /* Seu CSS permanece o mesmo */
    </style>
</head>
<body>
    <h1>Consultar Rotas</h1>

    <!-- Barra de pesquisa com filtros -->
    <form method="GET">
        <input type="text" name="origem" placeholder="Origem..." value="<?= htmlspecialchars($filterOrigem) ?>">
        <input type="text" name="destino" placeholder="Destino..." value="<?= htmlspecialchars($filterDestino) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <!-- Tabela de resultados -->
    <table>
        <tr>
            <th>Origem</th>
            <th>Destino</th>
            <th>Preço (€)</th>
            <th>Capacidade</th>
            <th>Horário</th>
            <th>Ação</th>
        </tr>
        <?php if (!empty($rotas)): ?>
            <?php foreach ($rotas as $row): ?>
            <tr class="<?= $row['Preço'] == $menorPreco ? 'highlight' : '' ?>">
                <td><?= htmlspecialchars($row['Origem']) ?></td>
                <td><?= htmlspecialchars($row['Destino']) ?></td>
                <td><?= htmlspecialchars($row['Preço']) ?></td>
                <td><?= htmlspecialchars($row['Capacidade']) ?></td>
                <td><?= htmlspecialchars($row['Horário']) ?></td>
                <td>
                    <a href="tickets.php?id=<?= $row['Id'] ?>" class="continue-button">Continuar</a>
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

<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        h1 {
            color: #009e2f;
        }
        form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        form input, form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            padding: 10px 15px;
            background-color: #009e2f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #007a24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #99cc00;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .continue-button {
            padding: 8px 15px;
            background-color: #009e2f;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }
        .continue-button:hover {
            background-color: #007a24;
        }
        .back-button {
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }
        .back-button:hover {
            background-color: #555;
        }
        .highlight {
            background-color: #e0ffe0;
            font-weight: bold;
        }
    </style>