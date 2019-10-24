-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 24-Out-2019 às 03:49
-- Versão do servidor: 10.1.36-MariaDB
-- versão do PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_expediente`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `anexos`
--

CREATE TABLE `anexos` (
  `id` mediumint(12) NOT NULL,
  `arquivo` varchar(100) NOT NULL,
  `tam` decimal(10,2) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `referencia` int(9) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` mediumint(12) NOT NULL,
  `genero` text CHARACTER SET latin1 NOT NULL,
  `nome` varchar(100) CHARACTER SET latin1 NOT NULL,
  `cargo` varchar(100) CHARACTER SET latin1 NOT NULL,
  `orgao` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `setor` varchar(100) CHARACTER SET latin1 NOT NULL,
  `codigo` varchar(20) CHARACTER SET latin1 NOT NULL,
  `endereco` varchar(250) CHARACTER SET latin1 NOT NULL,
  `cep` varchar(20) CHARACTER SET latin1 NOT NULL,
  `cidade` varchar(100) CHARACTER SET latin1 NOT NULL,
  `telefone` varchar(20) CHARACTER SET latin1 NOT NULL,
  `email` varchar(20) CHARACTER SET latin1 NOT NULL,
  `voip` varchar(20) CHARACTER SET latin1 NOT NULL,
  `unidade` varchar(20) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documentos`
--

CREATE TABLE `documentos` (
  `id` mediumint(12) NOT NULL,
  `emissor` int(9) NOT NULL,
  `destinatario` int(9) NOT NULL,
  `interessado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `assunto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `texto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` double NOT NULL,
  `data` date NOT NULL,
  `recebido` date NOT NULL,
  `prazo` date NOT NULL,
  `tipo` int(1) NOT NULL,
  `resposta` int(9) NOT NULL,
  `unidade` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventos`
--

CREATE TABLE `eventos` (
  `id` mediumint(12) NOT NULL,
  `data` datetime NOT NULL,
  `descricao` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `modelos`
--

CREATE TABLE `modelos` (
  `id` int(9) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `oficios_html`
--

CREATE TABLE `oficios_html` (
  `id` mediumint(9) NOT NULL,
  `arquivo` varchar(100) NOT NULL,
  `referencia` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidades`
--

CREATE TABLE `unidades` (
  `id` mediumint(9) NOT NULL,
  `cod` varchar(12) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sigla` varchar(20) NOT NULL,
  `end` text NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `tel` varchar(13) NOT NULL,
  `email` varchar(100) NOT NULL,
  `voip` varchar(9) NOT NULL,
  `chave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` mediumint(9) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `funcao` varchar(100) NOT NULL,
  `unidade` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anexos`
--
ALTER TABLE `anexos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oficios_html`
--
ALTER TABLE `oficios_html`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anexos`
--
ALTER TABLE `anexos`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modelos`
--
ALTER TABLE `modelos`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oficios_html`
--
ALTER TABLE `oficios_html`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unidades`
--
ALTER TABLE `unidades`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
