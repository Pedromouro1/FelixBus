<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página Inicial - FelixBus</title>
  <!-- Copie os estilos do arquivo HTML original -->
</head>
<body>
  <!-- Cabeçalho -->
  <header>
    <div class="logo">FelixBus</div>
    <nav>
      <a href="rotas.html">Rotas</a>
      <a href="alertas.html">Alertas</a>
      <a href="tickets.html">Bilhetes</a>
      <a href="carteira.html">Carteira</a>
      <a href="#">Serviço</a>
      <a href="#">Ajuda</a>
    </nav>
    <div class="user-actions">
      <?php if ($loggedIn): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="PgLogin.html">Login</a>
        <a href="Pgregisto.html">Registo</a>
      <?php endif; ?>
    </div>
  </header>

  <!-- Copie o restante do conteúdo HTML original -->
</body>
</html>
