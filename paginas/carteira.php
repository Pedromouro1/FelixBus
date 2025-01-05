<?php
session_start();
include("basedados/basedados.h");

if (!isset($_SESSION['Utilizador_id']) || $_SESSION['user_perfil'] !== 'cliente') {
    echo "<script>alert('Acesso negado! Apenas clientes podem acessar esta página.'); window.location.href = 'PgLogin.html';</script>";
    exit();
}

// Obter o saldo atual do Utilizador
$utilizador_id = $_SESSION['Utilizador_id'];
$query = "SELECT Saldo FROM saldo WHERE Utilizador_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $utilizador_id);
$stmt->execute();
$result = $stmt->get_result();
$saldo = $result->fetch_assoc()['Saldo'] ?? 0;

// Processar a ação de adicionar ou retirar saldo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'];
    $valor = floatval($_POST['valor']);

    if ($valor > 0) {
        if ($acao === 'adicionar') {
            $saldo += $valor;
        } elseif ($acao === 'retirar') {
            if ($saldo >= $valor) {
                $saldo -= $valor;
            } else {
                $erro = 'Saldo insuficiente.';
            }
        }

        // Atualizar o saldo na base de dados
        if (!isset($erro)) {
            $update_query = "UPDATE saldo SET Saldo = ? WHERE Utilizador_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param('di', $saldo, $utilizador_id);
            if ($stmt->execute()) {
                $mensagem = "Saldo atualizado com sucesso!";
            } else {
                $erro = "Erro ao atualizar o saldo: " . $stmt->error;
            }
        }
    } else {
        $erro = 'Por favor, insira um valor válido.';
    }
}
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
        <h2>Saldo Atual</h2>
        <p><strong id="current-balance">€ <?php echo number_format($saldo, 2, ',', '.'); ?></strong></p>

        <?php if (isset($mensagem)): ?>
            <div class="success"><?php echo $mensagem; ?></div>
        <?php elseif (isset($erro)): ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>

        <h2>Adicionar/Retirar Saldo</h2>
        <form method="POST">
            <label for="valor">Valor:</label>
            <input type="number" id="valor" name="valor" step="0.01" min="0.01" required>
            <div style="display: flex; justify-content: space-between;">
                <button type="submit" name="acao" value="adicionar">Adicionar Saldo</button>
                <button type="submit" name="acao" value="retirar">Levantar Saldo</button>
            </div>
        </form>
        <a href="pagina_inicial.php">Voltar</a>
    </main>
</body>
</html>