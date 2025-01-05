<?php
include("basedados/basedados.h");
session_start();

// varaiveis de pesquisa com valores recebidos via get
$filterTitulo = $_GET['titulo'] ?? '';
$filterTipo = $_GET['tipo'] ?? '';

// Construir a query SQL incluindo os filtros
$sql = "SELECT * FROM alertas";
if (!empty($filterTitulo) || !empty($filterTipo)) {
    $sql .= " WHERE 1=1";
    if (!empty($filterTitulo)) {  //filtro para o titulo
        $sql .= " AND Titulo LIKE '%" . $conn->real_escape_string($filterTitulo) . "%'";
    }
    if (!empty($filterTipo)) {    //filtro para o tipo
        $sql .= " AND Tipo = '" . $conn->real_escape_string($filterTipo) . "'";
    }
}
//executa e armazena
$result = $conn->query($sql);

// Faz um array para aramazenar os resultados
$rows = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row; //adiciona a linha ao array de resultados
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Consultar Alertas</title>
    <link rel="stylesheet" href="style_alertas.css">
    <link rel="stylesheet" href="styleCliente.css">
</head>
<body>
    <h1>Consultar Alertas</h1>

    <!-- Barra de pesquisa com filtros -->
    <form method="GET">
        <input type="text" name="titulo" placeholder="Título..." value="<?= htmlspecialchars($filterTitulo) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <!-- Tabela de resultados -->
    <table>
        <tr>
            <th>Título</th>
            <th>Conteúdo</th>
            <th>Tipo</th>
            <th>Data Criação</th>
        </tr>
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['Titulo']) ?></td>
                <td><?= htmlspecialchars($row['Conteudo']) ?></td>
                <td><?= htmlspecialchars(ucfirst($row['Tipo'])) ?></td>
                <td><?= htmlspecialchars($row['Data_criacao']) ?></td>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?> <!-- Caso não haja resultados -->
            <tr>
                <td colspan="6">Nenhum alerta encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>

    <a href="pagina_inicial.php" class="back-button">Voltar</a>
</body>
</html>