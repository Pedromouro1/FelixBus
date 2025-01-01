<?php
session_start(); // Iniciar a sessão

// Verificar se o usuário está logado
if (!isset($_SESSION['Utilizador_id'])) {
    die("Acesso negado. Faça login para visualizar os bilhetes.");
}

// ID do usuário logado
$Utilizador_id = $_SESSION['Utilizador_id'];

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "FelixBus");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializar filtro de pesquisa
$filterRota_id = $_GET['rota_id'] ?? '';
$filterHorario = $_GET['horario'] ?? '';

// Inicializar $types e $params
$types = 'i'; // 'i' para Inteiro (Utilizador_id)
$params = [$Utilizador_id];

// Construir a query SQL dinamicamente com filtros
$sql = "SELECT * FROM bilhetes WHERE Utilizador_id = ?";
if (!empty($filterRota_id)) {
    $sql .= " AND Rota_id LIKE ?";
    $params[] = '%' . $filterRota_id . '%';
    $types .= 's';
}
if (!empty($filterHorario)) {
    $sql .= " AND Horario LIKE ?";
    $params[] = '%' . $filterHorario . '%';
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Processar os resultados em uma matriz
$bilhetes = [];
while ($row = $result->fetch_assoc()) {
    $bilhetes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar bilhetes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        form input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            flex: 1;
        }
        form button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .no-results {
            margin-top: 20px;
            color: #555;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Consultar bilhetes</h1>
    <form method="GET">
        <input type="text" name="rota_id" placeholder="Rota ID..." value="<?= htmlspecialchars($filterRota_id) ?>">
        <input type="text" name="horario" placeholder="Horário..." value="<?= htmlspecialchars($filterHorario) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <?php if (!empty($bilhetes)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID do Utilizador</th>
                    <th>ID da Rota</th>
                    <th>Data da Viagem</th>
                    <th>Horário</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bilhetes as $bilhete): ?>
                    <tr>
                        <td><?= htmlspecialchars($bilhete['Utilizador_id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($bilhete['Rota_id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($bilhete['Data_viagem'] ?? '') ?></td>
                        <td><?= htmlspecialchars($bilhete['Horario'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-results">Nenhum bilhete encontrado.</p>
    <?php endif; ?>
</body>
</html>