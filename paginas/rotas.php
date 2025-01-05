<?php
include("basedados/basedados.h");
session_start();

// Inicializar filtro de pesquisa
$filterOrigem = $_GET['origem'] ?? '';
$filterDestino = $_GET['destino'] ?? '';
$filterPreco = $_GET['preco'] ?? '';

// query para os filtros
$sql = "SELECT * FROM rotas";
if (!empty($filterOrigem) || !empty($filterDestino) || !empty($filterPreco)) {
    $sql .= " WHERE 1=1"; // Se qualquer filtro for aplicado
    if (!empty($filterOrigem)) {
        // Adiciona o filtro de Origem se fornecido, usando LIKE 
        $sql .= " AND Origem LIKE '%" . $conn->real_escape_string($filterOrigem) . "%'";
    }
    if (!empty($filterDestino)) {
         // Adiciona o filtro de Destino se fornecido, usando LIKE
        $sql .= " AND Destino LIKE '%" . $conn->real_escape_string($filterDestino) . "%'";
    }
    if (!empty($filterPreco)) {
          // Adiciona o filtro de Preço se fornecido
        $sql .= " AND Preço <= " . $conn->real_escape_string($filterPreco);
    }
}
// Executa a consulta
$result = $conn->query($sql);

// Obter os resultados em um array para evitar múltiplas leituras
$resultado = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $resultado[] = $row;
    }
}

// Determinar o preço mínimo (se necessário)
$minPrice = !empty($resultado) ? min(array_column($resultado, 'Preço')) : null;
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
            <th>Id rota</th>
            <th>Origem</th>
            <th>Destino</th>
            <th>Preço (€)</th>
            <th>Capacidade</th>
            <th>Horário</th>
            <th>Data Criação</th>
            <th>Ação</th>
        </tr>
        <?php if (!empty($resultado)): ?>
            <?php foreach ($resultado as $row): ?>
            <tr class="<?= ($row['Preço'] == $minPrice) ? 'highlight' : '' ?>">
            <td><?= htmlspecialchars($row['Id']) ?></td>
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