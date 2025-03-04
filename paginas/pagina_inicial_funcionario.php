<?php
session_start();

// Verificar se o utilizador está logado e tem perfil de funcionário
if (!isset($_SESSION['Utilizador_id']) || ($_SESSION['user_perfil'] !== 'funcionário' && $_SESSION['user_perfil'] !== 'administrador')) {
    echo "<script>alert('Acesso negado! Apenas funcionários podem acessar esta página.'); window.location.href = 'PgLogin.html';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Funcionário - FelixBus</title>
  <link rel="stylesheet" href="styleAdminInicial.css">
</head>
<body>
  <!-- Barra de navegação -->
  <div id="navbar">
    <a href="pagina_inicial_funcionario.php">Página Inicial</a>
    <a href="gerenciar_saldo_FA.php">Visualizar Saldo</a>
    <a href="gerenciar_bilhetes_FA.php">Consultar Bilhetes</a>
    <a href="perfil_FA.php">Editar dados pessoais</a>
    <a href="PgLogin.html" class="logout-btn">Logout</a>
  </div>

  <!-- Conteúdo do painel de funcionário -->
  <div id="funcionario-content">
    <h1>Painel do Funcionário</h1>

    <!-- Card de boas-vindas -->
    <div class="card">
      <h3>Bem-vindo ao Painel de Funcionário!</h3>
      <p>Aqui você pode acessar informações importantes e realizar suas tarefas de forma eficiente.</p>
    </div>

    <!-- Card de Visualização de Rotas -->
    <div class="card">
      <h3>Visualizar Rotas</h3>
      <p>Consulte todas as rotas disponíveis e atualizações em tempo real.</p>
      <a href="gerenciar_saldo_FA.php">Acessar Visualização de Rotas</a>
    </div>


    <!-- Card de Consulta de Bilhetes -->
    <div class="card">
      <h3>Consultar Bilhetes</h3>
      <p>Verifique e gerencie os bilhetes comprados pelos clientes.</p>
      <a href="perfil_FA.php">Acessar Consulta de Bilhetes</a>
    </div>

    <div class="card">
        <h3>Editar dados pessoais</h3>
        <p>Editar os dados pessoais</p>
        <a href="consultar_bilhetes.php">Acessar Consulta de Bilhetes</a>
      </div>

  </div>
</body>
</html>