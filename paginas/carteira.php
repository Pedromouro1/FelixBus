<?php
session_start();
require 'db_connection.php';

function getWalletBalance($conn, $user_id) {
    // Busca o saldo atual do usuário
    $stmt = $conn->prepare("SELECT Saldo FROM saldo WHERE Utilizador_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $wallet = $result->fetch_assoc();
    $stmt->close();

    return $wallet ? $wallet['Saldo'] : null;
}

function createWallet($conn, $user_id) {
    // Cria a carteira para o usuário
    $stmt = $conn->prepare("INSERT INTO saldo (Utilizador_id, Saldo) VALUES (?, 0.00)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

function updateWalletBalance($conn, $user_id, $amount, $action) {
    // Atualiza o saldo com base na ação (adicionar ou retirar)
    $query = ($action === 'add') 
        ? "UPDATE saldo SET Saldo = Saldo + ? WHERE Utilizador_id = ?"
        : "UPDATE saldo SET Saldo = Saldo - ? WHERE Utilizador_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("di", $amount, $user_id);
    $stmt->execute();
    $stmt->close();
}

try {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Inicia uma transação
    $conn->begin_transaction();

    // Busca o saldo ou cria a carteira, caso não exista
    $balance = getWalletBalance($conn, $user_id);
    if ($balance === null) {
        createWallet($conn, $user_id);
        $balance = 0.00;
    }

    // Manipula as operações de saldo
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

        // Validação do valor inserido
        if ($amount === false || $amount <= 0) {
            throw new Exception("Valor inválido. Insira um valor positivo.");
        }

        if ($action === 'add') {
            // Adicionar saldo
            updateWalletBalance($conn, $user_id, $amount, 'add');
        } elseif ($action === 'withdraw') {
            // Verifica saldo antes de retirar
            if ($balance >= $amount) {
                updateWalletBalance($conn, $user_id, $amount, 'withdraw');
            } else {
                throw new Exception("Saldo insuficiente.");
            }
        } else {
            throw new Exception("Ação inválida.");
        }

        // Confirma a transação
        $conn->commit();

        // Redireciona para a página atual para exibir as alterações
        header("Location: wallet.php");
        exit();
    }
} catch (Exception $e) {
    // Reverte a transação em caso de erro
    if ($conn->in_transaction) {
        $conn->rollback();
    }

    // Define a mensagem de erro para exibição
    $error = $e->getMessage();
} finally {
    // Fecha o statement e a conexão
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>
