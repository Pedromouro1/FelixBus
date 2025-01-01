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
  <style>
    /* Estilos gerais */
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f8f8f8;
    }

    /* Cabeçalho */
    header {
      background-color: #99cc00; /* Cor verde da FelixBus */
      padding: 10px 20px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header .logo {
      font-size: 28px;
      font-weight: bold;
    }

    header nav {
      display: flex;
      gap: 20px;
    }

    header nav a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      font-size: 14px;
    }

    .user-actions {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .user-actions a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      background-color: #009e2f;
      padding: 8px 15px;
      border-radius: 5px;
      font-size: 14px;
    }

    .user-actions a:hover {
      background-color: #007a24;
    }

    /* Seção de banner */
    .banner {
      background-image: url('FelixBus-1024x800.jpg'); /* Substitua pelo caminho correto */
      background-size: cover;
      background-position: center;
      height: 300px;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .banner h1 {
      margin: 0;
      font-size: 36px;
      font-weight: bold;
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
    }

    /* Formulário de busca */
    .search-form {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      margin-top: -50px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-form label {
      font-weight: bold;
      font-size: 14px;
    }

    .search-form input,
    .search-form select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }

    .search-form input[type="submit"] {
      background-color: #99cc00;
      color: white;
      border: none;
      cursor: pointer;
      font-weight: bold;
      padding: 10px 20px;
    }

    .search-form input[type="submit"]:hover {
      background-color: #85b800;
    }

    /* Informações da Empresa */
    .company-info {
      background-color: #f4f4f4;
      color: #333;
      text-align: center;
      padding: 20px;
      margin-top: 20px;
      border-top: 3px solid #99cc00;
    }

    .company-info h2 {
      margin: 0 0 10px;
      color: #009e2f;
    }

    .company-info p {
      margin: 5px 0;
    }

    .map-container {
  margin: 10px 0;
  border: 2px solid #99cc00;
  border-radius: 8px;
  overflow: hidden;
}

    .company-info a {
      color: #0066cc;
      text-decoration: none;
    }

    .company-info a:hover {
      text-decoration: underline;
    }

    /* Rodapé */
    footer {
      background-color: #333;
      color: white;
      text-align: center;
      padding: 10px;
      margin-top: 20px;

      
    }
  </style>
</head>
<body>
  <!-- Cabeçalho -->
  <header>
    <div class="logo">FelixBus</div>
    <nav>
      <a href="rotas.php">Rotas</a>
      <a href="tickets.html">Bilhetes</a>
      <a href="carteira.html">Carteira</a>
      <a href="#">Serviço</a>
      <a href="#">Ajuda</a>
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

  <!-- Formulário de busca -->
  <div class="search-form">
    <form action="resultados.php" method="GET">
      <div>
        <label>
          <input type="radio" name="tipo" value="ida" checked> Ida
        </label>
        <label>
          <input type="radio" name="tipo" value="ida_volta"> Ida e volta
        </label>
      </div>
      <div>
        <label for="de">De:</label>
        <input type="text" id="de" name="de" placeholder="Origem" required>
      </div>
      <div>
        <label for="para">Para:</label>
        <input type="text" id="para" name="para" placeholder="Destino" required>
      </div>
      <div>
        <label for="data">Ida:</label>
        <input type="date" id="data" name="data" required>
      </div>
      <div>
        <label for="passageiros">Passageiros:</label>
        <select id="passageiros" name="passageiros">
          <option value="1">1 Adulto(a)</option>
          <option value="2">2 Adultos(as)</option>
          <option value="3">3 Adultos(as)</option>
        </select>
      </div>
      <div>
        <input type="submit" value="Procurar">
      </div>
    </form>
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