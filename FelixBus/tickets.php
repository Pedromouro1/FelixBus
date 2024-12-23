<?php
session_start();
require 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Busca os bilhetes do usuário
$stmt = $conn->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY date_time DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tickets = $result->fetch_all(MYSQLI_ASSOC);

// Compra de bilhete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_ticket'])) {
    $route = $_POST['route'];
    $date_time = $_POST['date_time'];
    $price = floatval($_POST['price']);

    // Verifica o saldo da carteira
    $wallet_stmt = $conn->prepare("SELECT balance FROM wallet WHERE user_id = ?");
    $wallet_stmt->bind_param("i", $user_id);
    $wallet_stmt->execute();
    $wallet_result = $wallet_stmt->get_result();
    $wallet = $wallet_result->fetch_assoc();

    if (!$wallet || $wallet['balance'] < $price) {
        $error = "Saldo insuficiente na carteira.";
    } else {
        // Deduz o valor da carteira
        $update_wallet_stmt = $conn->prepare("UPDATE wallet SET balance = balance - ? WHERE user_id = ?");
        $update_wallet_stmt->bind_param("di", $price, $user_id);
        $update_wallet_stmt->execute();

        // Registra o bilhete
        $ticket_stmt = $conn->prepare("INSERT INTO tickets (user_id, route, date_time, price) VALUES (?, ?, ?, ?)");
        $ticket_stmt->bind_param("issd", $user_id, $route, $date_time, $price);
        $ticket_stmt->execute();

        header("Location: tickets.php");
        exit();
    }
}

// Alterar/Anular bilhete
if (isset($_GET['cancel_ticket']) && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verifica se o bilhete pertence ao usuário
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $ticket = $stmt->get_result()->fetch_assoc();

    if ($ticket) {
        $cancel_stmt = $conn->prepare("UPDATE tickets SET status = 'canceled' WHERE id = ?");
        $cancel_stmt->bind_param("i", $id);
        $cancel_stmt->execute();
        header("Location: tickets.php");
        exit();
    } else {
        $error = "Bilhete não encontrado ou não autorizado.";
    }
}
?>