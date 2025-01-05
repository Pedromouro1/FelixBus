<?php
session_start();
include("basedados/basedados.h");

if (!isset($_SESSION['Utilizador_id']) || ($_SESSION['user_perfil'] !== 'cliente' && $_SESSION['user_perfil'] !== 'administrador')) {
    echo "<script>alert('Acesso negado! Apenas clientes podem acessar esta página.'); window.location.href = 'PgLogin.html';</script>";
    exit();
}

// Obter o saldo atual do Utilizador
$utilizador_id = $_SESSION['Utilizador_id'];
$query = "SELECT Saldo FROM saldo WHERE Utilizador_id = ?";
$consulta = $conn->prepare($query);
$consulta->bind_param('i', $utilizador_id); // passa o id como parâmetro
$consulta->execute();
$result = $consulta->get_result();
$saldo = $result->fetch_assoc()['Saldo'] ?? 0; // saldo padrão 0 se não encontrado

// Verificar se o formulário foi requisitado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao']; // tipo de ação: adicionar ou retirar
    $valor = floatval($_POST['valor']); // converte para número decimal

    if ($valor > 0) { // verifica se o valor é válido
        if ($acao === 'adicionar') {
            $saldo += $valor; // adiciona
        } elseif ($acao === 'retirar') {
            if ($saldo >= $valor) {
                $saldo -= $valor; // remove
            } else {
                $erro = 'Saldo insuficiente.';
            }
        }

        // Atualiza a base de dados
        if (!isset($erro)) {
            $update_query = "UPDATE saldo SET Saldo = ? WHERE Utilizador_id = ?"; // query para atualizar o saldo
            $consultaPreparada = $conn->prepare($update_query);
            $consultaPreparada->bind_param('di', $saldo, $utilizador_id); // passa o saldo e o id como parâmetro
            if ($consultaPreparada->execute()) {
                $mensagem = "Saldo atualizado com sucesso!";
            } else {
                $erro = "Erro ao atualizar o saldo: " . $consultaPreparada->error;
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
        <!-- Para o saldo aparecer com 2 casas decimais -->
        <p><strong id="current-balance">€ <?php echo number_format($saldo, 2, ',', '.'); ?></strong></p>
  <!-- Mensagens de feedback -->
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