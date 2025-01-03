<?php
session_start();

// Configurações do banco de dados

$host = "localhost"; // Endereço do servidor (localhost para ambiente local)
$user = "root"; // Usuário do banco de dados
$password = ""; // Senha do banco de dados
$dbname = "FelixBus"; // Nome da base de dados

// Conexão com o banco de dados
$conexao = new mysqli($host, $user, $password, $dbname);

// Verifica se a conexão falhou
if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

// Obtém o ID do usuário atual da sessão
$utilizador_id = $_SESSION['Utilizador_id'] ?? null;

// Verifica se o ID do usuário está definido
if (!$utilizador_id) {
    die("Erro: Utilizador não autenticado.");
}

// Consulta para obter o saldo atual do usuário
$query = "SELECT Saldo FROM saldo WHERE Utilizador_id = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param('i', $utilizador_id);
$stmt->execute();
$result = $stmt->get_result();
$saldo = 0.00; // Valor inicial caso não exista saldo registrado

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $saldo = $row['Saldo'];
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao']; // Pode ser 'adicionar' ou 'retirar'
    $valor = floatval($_POST['valor']);

    if ($valor > 0) {
        if ($acao === 'adicionar') {
            $saldo += $valor; // Adiciona o valor ao saldo
        } elseif ($acao === 'retirar') {
            if ($saldo >= $valor) {
                $saldo -= $valor; // Subtrai o valor do saldo
            } else {
                $erro = 'Saldo insuficiente.';
            }
        }

        // Atualiza o saldo no banco de dados
        if (!isset($erro)) {
            $update_query = "UPDATE saldo SET Saldo = ? WHERE Utilizador_id = ?";
            $stmt = $conexao->prepare($update_query);
            $stmt->bind_param('di', $saldo, $utilizador_id);
            $stmt->execute();
        }
    } else {
        $erro = 'Por favor, insira um valor válido.';
    }
}

// Fecha a conexão
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleCliente.css">
    <title>Gestão da Carteira</title>
</head>
<body>
    <header>
        <h1>Gestão da Carteira</h1>
    </header>
    <main>
        <p>Bem-vindo</p>

        <h2>Saldo Atual</h2>
        <p><strong id="current-balance">€ <?php echo number_format($saldo, 2, ',', '.'); ?></strong></p>

        <?php if (isset($erro)): ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>

        <h2>Adicionar/Retirar Saldo</h2>
        <form method="POST">
            <label for="valor">Valor:</label>
            <input type="number" id="valor" name="valor" step="0.01" min="0.01" required>
            <div style="display: flex; justify-content: space-between;">
                <button type="submit" name="acao" value="adicionar">Adicionar Saldo</button>
                <button type="submit" name="acao" value="retirar">Retirar Saldo</button>
            </div>
        </form>
        <a href="pagina_inicial.php">Sair</a>
    </main>
</body>
</html>