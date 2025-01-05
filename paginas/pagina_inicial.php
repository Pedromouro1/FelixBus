<?php
include("../basedados/basedados.h");
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styleCliente.css">
  <title>Página Inicial - FelixBus</title>
</head>
<body>
  <!-- Cabeçalho -->
  <header>
    <div class="logo">FelixBus</div>
    <nav>
      <a href="rotas.php">Rotas</a>
      <a href="tickets.php">Bilhetes</a>
      <a href="carteira.php">Carteira</a>
      <a href="alertasInicial.php">Alertas</a>
    </nav>
    <div class="user-actions">
  <?php if ($loggedIn): ?>
    <a href="perfil.php">Perfil</a>
    <a href="logout.php">Logout</a>
  <?php else: ?>
    <a href="PgLogin.html">Login</a>
    <a href="Pgregisto.html">Registo</a>
  <?php endif; ?>
   </div>
  </header>

  <!-- Banner -->
  <div class="banner">
    <h1>Descobre Portugal e a Europa</h1>
  </div>

  

<!-- Informações da Empresa -->
<div class="company-info">
  <h2>Sobre a FelixBus</h2>
  <p><strong>Localização:</strong> Avenida Principal, 123, Lisboa, Portugal</p>
  
  <p><strong>Contactos:</strong> +351 123 456 789 | <a href="mailto:info@felixbus.com">info@felixbus.com</a></p>
  <p><strong>Horários de Funcionamento:</strong></p>
  <ul>
    <br>Segunda a Sexta: 08:00 - 20:00</br>
    <br>Sábado: 09:00 - 18:00</br>
    <br>Domingo: Encerrado</br>
  </ul>

  <div class="map-container">
    <iframe 
    src="https://www.google.com/maps/embed/v1/place?q=Avenida%20Principal,%20123,%20Lisboa,%20Portugal&key=AIzaSyBeU8lhlwJ1zWfn-N1P2m2JLWpUtzjzoz4" 
      width="100%" 
      height="300" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy">
    </iframe>
  </div>

</div>

  <!-- Rodapé -->
  <footer>
    &copy; 2024 FelixBus. Todos os direitos reservados.
  </footer>
</body>
</html>