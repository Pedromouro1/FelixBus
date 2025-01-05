-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05-Jan-2025 às 21:35
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `felixbus`
create database felixbus;
use felixbus;
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `alertas`
--

CREATE TABLE `alertas` (
  `Id` int(11) NOT NULL,
  `Titulo` varchar(100) NOT NULL,
  `Conteudo` text NOT NULL,
  `Tipo` enum('alerta','Informacao','Promocao') NOT NULL,
  `Data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `Ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `alertas`
--

INSERT INTO `alertas` (`Id`, `Titulo`, `Conteudo`, `Tipo`, `Data_criacao`, `Ativo`) VALUES
(1, 'Manutençao', 'O site teve em manutençao', 'alerta', '2024-12-29 22:55:05', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `operacao` varchar(255) DEFAULT NULL,
  `carteira_origem` int(11) DEFAULT NULL,
  `carteira_destino` int(11) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `saldo_origem_antes` decimal(10,2) DEFAULT NULL,
  `saldo_origem_depois` decimal(10,2) DEFAULT NULL,
  `saldo_destino_antes` decimal(10,2) DEFAULT NULL,
  `saldo_destino_depois` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `bilhetes`
--

CREATE TABLE `bilhetes` (
  `Id` int(11) NOT NULL,
  `Utilizador_id` int(11) NOT NULL,
  `Rota_id` int(11) NOT NULL,
  `Data_viagem` date NOT NULL,
  `Horario` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `rotas`
--

CREATE TABLE `rotas` (
  `Id` int(11) NOT NULL,
  `Origem` varchar(100) NOT NULL,
  `Destino` varchar(100) NOT NULL,
  `Preço` decimal(10,2) NOT NULL,
  `Capacidade` int(11) NOT NULL,
  `Horário` time NOT NULL,
  `Data_criacao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `rotas`
--

INSERT INTO `rotas` (`Id`, `Origem`, `Destino`, `Preço`, `Capacidade`, `Horário`, `Data_criacao`) VALUES
(14, 'Castelo Branco', 'Sardoal', 10.00, 2, '02:15:00', '2025-01-03'),
(15, 'Abrantes', 'Castelo Branco', 12.00, 3, '02:16:00', '2025-01-03'),
(16, 'Rossio', 'Alentejo', 16.00, 12, '03:16:00', '2025-01-03');

-- --------------------------------------------------------

--
-- Estrutura da tabela `saldo`
--

CREATE TABLE `saldo` (
  `Id` int(11) NOT NULL,
  `Utilizador_id` int(11) NOT NULL,
  `Saldo` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `saldo`
--

INSERT INTO `saldo` (`Id`, `Utilizador_id`, `Saldo`) VALUES
(4, 1, 1.00),
(5, 26, 1.00),
(6, 24, 1.00),
(7, 25, 1.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `perfil` enum('visitante','cliente','funcionário','administrador') NOT NULL DEFAULT 'visitante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id`, `nome`, `email`, `password`, `perfil`) VALUES
(1, 'Felixbus', 'felixbus@gmail.com', '202cb962ac59075b964b07152d234b70', 'administrador'),
(24, 'cliente', 'cliente@gmail.com', '4983a0ab83ed86e0e7213c8783940193', 'cliente'),
(25, 'funcionario', 'funcionario@gmail.com', 'cc7a84634199040d54376793842fe035', 'funcionário'),
(26, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'administrador');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`Id`);

--
-- Índices para tabela `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `bilhetes`
--
ALTER TABLE `bilhetes`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Utilizador_id` (`Utilizador_id`),
  ADD KEY `Rota_id` (`Rota_id`);

--
-- Índices para tabela `rotas`
--
ALTER TABLE `rotas`
  ADD PRIMARY KEY (`Id`);

--
-- Índices para tabela `saldo`
--
ALTER TABLE `saldo`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Utilizador_id` (`Utilizador_id`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alertas`
--
ALTER TABLE `alertas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `bilhetes`
--
ALTER TABLE `bilhetes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `rotas`
--
ALTER TABLE `rotas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `saldo`
--
ALTER TABLE `saldo`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `bilhetes`
--
ALTER TABLE `bilhetes`
  ADD CONSTRAINT `bilhetes_ibfk_1` FOREIGN KEY (`Utilizador_id`) REFERENCES `utilizadores` (`id`),
  ADD CONSTRAINT `bilhetes_ibfk_2` FOREIGN KEY (`Rota_id`) REFERENCES `rotas` (`Id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `saldo`
--
ALTER TABLE `saldo`
  ADD CONSTRAINT `saldo_ibfk_1` FOREIGN KEY (`Utilizador_id`) REFERENCES `utilizadores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
