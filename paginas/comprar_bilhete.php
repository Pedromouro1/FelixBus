<?php
session_start();
$conn = new mysqli("localhost", "root", "", "FelixBus");

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o usuário está logado
if (!isset($_SESSION['Utilizador_id'])) {
    die("Acesso negado. Faça login para comprar um bilhete.");
}

// Obter o ID da rota
$rota_id = $_GET['id'] ?? null;
if (!$rota_id) {
    echo "<p>ID da rota não especificado.</p><a href='index.php' class='button'>Voltar para a página inicial</a>";
    exit;
}

// Buscar informações da rota
$sql = "SELECT * FROM rotas WHERE Id = $rota_id";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo "<p>Rota não encontrada.</p><a href='index.php' class='button'>Voltar para a página inicial</a>";
    exit;
}

$rota = $result->fetch_assoc();

// Verificar capacidade
if ($rota['Capacidade'] <= 0) {
    echo "<p>Não há lugares disponíveis para esta rota.</p><a href='rotas.php' class='button'>Voltar para a página inicial</a>";
    exit;
}

// Verificar saldo do cliente
$utilizador_id = $_SESSION['Utilizador_id'];
$sql_saldo = "SELECT Saldo FROM saldo WHERE Utilizador_id = $utilizador_id";
$result_saldo = $conn->query($sql_saldo);
if ($result_saldo->num_rows === 0) {
    echo "<p>Saldo não encontrado.</p><a href='index.php' class='button'>Voltar para a página inicial</a>";
    exit;
}

$saldo = $result_saldo->fetch_assoc()['Saldo'];
if ($saldo < $rota['Preço']) {
    echo "<p>Saldo insuficiente para comprar esta viagem.</p><a href='carteira.php' class='button'>Voltar para a página inicial</a>";
    exit;
}

// Confirmar a compra (exibir tela de confirmação antes de registrar a compra)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar capacidade da rota
    $nova_capacidade = $rota['Capacidade'] - 1;
    $sql_update_capacidade = "UPDATE rotas SET Capacidade = $nova_capacidade WHERE Id = $rota_id";
    $conn->query($sql_update_capacidade);

    // Atualizar saldo do cliente
    $novo_saldo = $saldo - $rota['Preço'];
    $sql_update_saldo = "UPDATE saldo SET Saldo = $novo_saldo WHERE Utilizador_id = $utilizador_id";
    $conn->query($sql_update_saldo);

    // Obter saldo atual da FelixBus antes da transferência
    $sql_saldo_felixbus = "SELECT Saldo FROM saldo WHERE Utilizador_id = 1";
    $saldo_felixbus_antes = $conn->query($sql_saldo_felixbus)->fetch_assoc()['Saldo'];

    // Transferir valor para a conta FelixBus (Utilizador_id = 1)
    $valor_transferencia = $rota['Preço'];
    $sql_update_saldo_felixbus = "UPDATE saldo SET Saldo = Saldo + $valor_transferencia WHERE Utilizador_id = 1";
    $conn->query($sql_update_saldo_felixbus);

    // Obter saldo da FelixBus depois da transferência
    $saldo_felixbus_depois = $saldo_felixbus_antes + $valor_transferencia;

    // Registrar operação na tabela de auditoria
    $sql_auditoria_cliente = "
        INSERT INTO auditoria (operacao, carteira_origem, carteira_destino, valor, saldo_origem_antes, saldo_origem_depois, saldo_destino_antes, saldo_destino_depois) 
        VALUES ('Compra de bilhete - Rota $rota_id', $utilizador_id, 1, $valor_transferencia, $saldo, $novo_saldo, $saldo_felixbus_antes, $saldo_felixbus_depois)";
    $conn->query($sql_auditoria_cliente);

    // Registrar o bilhete
    $data_viagem = $rota['Data_criacao']; // Ajuste aqui conforme necessário
    $horario = $rota['Horário'];
    $sql_insert_bilhete = "
        INSERT INTO bilhetes (Utilizador_id, Rota_id, Data_viagem, Horario, Status) 
        VALUES ($utilizador_id, $rota_id, '$data_viagem', '$horario', 'Confirmado')";
    $conn->query($sql_insert_bilhete);

    echo "<p>Bilhete comprado com sucesso!</p><a href='pagina_inicial.php' class='button'>Voltar para a página inicial</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Compra</title>
    <link rel="stylesheet" href="style_comprar_bilhetes.css">
</head>
<body>
    <div class="container">
        <h1>Confirmar Compra</h1>
        <p>Tem certeza de que deseja comprar um bilhete para a rota abaixo?</p>
        <p><strong>Origem:</strong> <?= htmlspecialchars($rota['Origem']) ?></p>
        <p><strong>Destino:</strong> <?= htmlspecialchars($rota['Destino']) ?></p>
        <p><strong>Preço:</strong> €<?= htmlspecialchars($rota['Preço']) ?></p>
        <p><strong>Capacidade Restante:</strong> <?= htmlspecialchars($rota['Capacidade']) ?></p>

        <form method="POST">
            <button type="submit" class="button">Confirmar Compra</button>
        </form>

        <a href="rotas.php" class="button">Cancelar</a>
    </div>
</body>
</html>