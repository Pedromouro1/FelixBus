<?php
session_start();

// Verificar permissão de administrador
if (!isset($_SESSION['user_perfil']) || $_SESSION['user_perfil'] !== 'administrador') {
    echo "<script>alert('Acesso negado voçe nao e adminstrador!'); window.location.href = 'pagina_inicial.php';</script>";
    exit(); 
}
?> 
 
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Administração - FelixBus</title>
  <link rel="stylesheet" href="styleAdminInicial.css">
</head>
<body>
  <!-- Barra de navegação -->
  <div id="navbar">
    <a href="pagina_inicial.php">Página Inicial</a>
    <a href="gerenciar_utilizadores.php">Gerenciar Utilizadores</a>
    <a href="gerenciar_rotas.php">Gerenciar Rotas</a>
    <a href="gerenciar_alertas.php">Gerenciar Alertas</a>
    <a href="gerenciar_bilhetes.php">Gerenciar Bilhetes</a>
    <a href="pagina_inicial_funcionario.php"> Pagina funcionário</a>
    <a href="PgLogin.html" class="logout-btn">Logout</a>
    
  </div>

  <!-- Conteúdo do painel de administração -->
  <div id="admin-content">
    <h1>Painel de Administração</h1>

    <!-- de boas-vindas -->
    <div class="card">
      <h3>Bem-vindo ao Painel de Administração, Admin!</h3>
      <p>Aqui você pode gerenciar todos os aspectos do sistema, incluindo utilizador, rotas, bilhetes e muito mais.</p>
    </div>

    <!--  de Gerenciamento de utilizador -->
    <div class="card">
      <h3>Gerenciar utilizador</h3>
      <p>Adicione, edite ou remova utilizadores do sistema.</p>
      <a href="gerenciar_utilizadores.php">Acessar Gerenciamento de utilizador</a>
    </div>

    <!--  de Gerenciamento de Rotas -->
    <div class="card">
      <h3>Gerenciar Rotas</h3>
      <p>Adicione, edite ou remova rotas de viagem no sistema.</p>
      <a href="gerenciar_rotas.php">Acessar Gerenciamento de Rotas</a>
    </div>

    <!-- de Gerenciamento de Alertas -->
    <div class="card">
      <h3>Gerenciar Alertas</h3>
      <p>Envie alertas e promoções para os utilizador do sistema.</p>
      <a href="gerenciar_alertas.php">Acessar Gerenciamento de Alertas</a>
    </div>

    <!--  de Gerenciamento de Bilhetes -->
    <div class="card">
      <h3>Gerenciar Bilhetes</h3>
      <p>Controle e gerencie os bilhetes comprados pelos utilizador.</p>
      <a href="gerenciar_bilhetes.php">Acessar Gerenciamento de Bilhetes</a>
      
    </div>

    <div class="card">
      <h3>Pagina funcionário</h3>
      <p>Acesso dos funcionarios</p>
      <a href="pagina_inicial_funcionario.php">Acessar Gerenciamento de Bilhetes</a>
      
    </div>

  </div>
</body>
</html>
