<?php
include("../basedados/basedados.h");
session_start();

// Verificar se o Utilizador está logado e é um cliente
if (!isset($_SESSION['Utilizador_id']) || ($_SESSION['user_perfil'] !== 'cliente' && $_SESSION['user_perfil'] !== 'administrador')) {
    echo "<script>alert('Acesso negado! Apenas clientes podem acessar esta página.'); window.location.href = 'pagina_inicial.php';</script>";
    exit();
}

// ID do Utilizador logado
$Utilizador_id = $_SESSION['Utilizador_id'];

// Inicializar filtro de pesquisa
$filterRota_id = $_GET['rota_id'] ?? '';
$filterHorario = $_GET['horario'] ?? '';

// Inicializar $tipodado e $dado 
$tipodado = 'i'; // 'i' para Inteiro (Utilizador_id)
$dado = [$Utilizador_id];

// Construir a query SQL dinamicamente com filtros
$sql = "SELECT * FROM bilhetes WHERE Utilizador_id = ?";
if (!empty($filterRota_id)) {
    $sql .= " AND Rota_id LIKE ?";
    $dado [] = '%' . $filterRota_id . '%';
    $tipodado .= 's';
}
if (!empty($filterHorario)) {
    $sql .= " AND Horario LIKE ?";
    $dado [] = '%' . $filterHorario . '%';
    $tipodado .= 's';
}

$preparaconsulta = $conn->prepare($sql);
if ($tipodado) {
    $preparaconsulta->bind_param($tipodado, ...$dado );
}
$preparaconsulta->execute();
$result = $preparaconsulta->get_result();

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
    <link rel="stylesheet" href="styleCliente.css"> 
    <title>Consultar bilhetes</title>
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
                    <th>ID da Rota</th>
                    <th>Data da Viagem</th>
                    <th>Horário</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bilhetes as $bilhete): ?>
                    <tr>
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
<a href="pagina_inicial.php" class="back-button">Voltar</a>
</html>