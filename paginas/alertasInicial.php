<?php
include("basedados/basedados.h");
session_start();

// Variáveis de pesquisa com valores recebidos via GET
$filterTitulo = $_GET['titulo'] ?? '';

// Construir a query SQL incluindo os filtros
$sql = "SELECT * FROM alertas";
if (!empty($filterTitulo)) {
    $sql .= " WHERE Titulo LIKE '%" . $conn->real_escape_string($filterTitulo) . "%'";
}

// Executa a consulta e armazena os resultados
$result = $conn->query($sql);

// Se houver resultados, são armazenados no array
$resultado = [];
if ($result && $result->num_rows > 0) {
    while ($linha = $result->fetch_assoc()) {
        $resultado[] = $linha; // Adiciona a linha ao array de resultados
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
        <?php if (!empty($resultado)): ?>
            <?php foreach ($resultado as $linha): ?>
            <tr>
                <td><?= htmlspecialchars($linha['Titulo']) ?></td>
                <td><?= htmlspecialchars($linha['Conteudo']) ?></td>
                <td><?= htmlspecialchars(ucfirst($linha['Tipo'])) ?></td>
                <td><?= htmlspecialchars($linha['Data_criacao']) ?></td>
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
