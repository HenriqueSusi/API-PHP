-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/11/2026 às 21:05
-- Versão do servidor: 10.11.16-MariaDB
-- Versão do PHP: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gerenciador_tarefa`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa`
--

CREATE TABLE `tarefa` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `prioridade` enum('Baixa','Média','Alta') DEFAULT 'Média',
  `data_prazo` date DEFAULT NULL,
  `status` enum('Pendente','Concluída') DEFAULT 'Pendente',
  `criado_em` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefa`
--

INSERT INTO `tarefa` (`id`, `titulo`, `descricao`, `prioridade`, `data_prazo`, `status`, `criado_em`) VALUES
(1, 'Estudar APIs em PHP', 'Aprendendo a editar tarefas', 'Média', '2026-11-12', 'Pendente', '2026-11-05 19:08:08'),
(5, 'Aprender a Nadar', 'Aula de natação', 'Alta', '2026-12-01', 'Pendente', '2026-11-11 20:04:04'),
(6, 'Jogar Futebol', 'Dar aula no fut', 'Baixa', '2026-11-12', 'Pendente', '2026-11-11 20:04:20'),
(7, 'Teste concluida', 'Conclusao', 'Alta', '2026-12-05', 'Concluída', '2026-11-11 20:04:35');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tarefa`
--
ALTER TABLE `tarefa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tarefa`
--
ALTER TABLE `tarefa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
