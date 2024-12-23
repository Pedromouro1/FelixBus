<?php
session_start();
require 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Busca o saldo atual
$stmt = $conn->prepare("SELECT balance FROM wallet WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wallet = $result->fetch_assoc();

if (!$wallet) {
    // Cria a carteira se não existir
    $stmt = $conn->prepare("INSERT INTO wallet (user_id, balance) VALUES (?, 0.00)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $wallet = ['balance' => 0.00];
}

// Adicionar ou Retirar Saldo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $action = $_POST['action'];

    if ($action === 'add') {
        $stmt = $conn->prepare("UPDATE wallet SET balance = balance + ? WHERE user_id = ?");
        $stmt->bind_param("di", $amount, $user_id);
        $stmt->execute();
    } elseif ($action === 'withdraw') {
        if ($wallet['balance'] >= $amount) {
            $stmt = $conn->prepare("UPDATE wallet SET balance = balance - ? WHERE user_id = ?");
            $stmt->bind_param("di", $amount, $user_id);
            $stmt->execute();
        } else {
            $error = "Saldo insuficiente.";
        }
    }

    // Atualiza o saldo
    header("Location: wallet.php");
    exit();
}
?>