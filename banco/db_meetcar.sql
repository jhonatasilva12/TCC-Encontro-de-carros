-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/05/2025 às 00:22
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_meetcar`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento_user`
--

CREATE TABLE `evento_user` (
  `fk_id_user` int(11) NOT NULL,
  `fk_id_evento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupo_tegru`
--

CREATE TABLE `grupo_tegru` (
  `fk_id_grupo` int(11) NOT NULL,
  `fk_id_temas_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `likes_comentario`
--

CREATE TABLE `likes_comentario` (
  `fk_id_user` int(11) NOT NULL,
  `fk_id_comentario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `likes_post`
--

CREATE TABLE `likes_post` (
  `fk_id_user` int(11) NOT NULL,
  `fk_id_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_comentario`
--

CREATE TABLE `tb_comentario` (
  `id_comentario` int(11) NOT NULL,
  `texto_comentario` varchar(300) NOT NULL,
  `imagem_comentario` varchar(36) DEFAULT NULL,
  `data_comentario` datetime NOT NULL,
  `fk_id_user` int(11) NOT NULL,
  `fk_id_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_evento`
--

CREATE TABLE `tb_evento` (
  `id_evento` int(11) NOT NULL,
  `nome_evento` varchar(30) NOT NULL,
  `descricao_evento` varchar(300) NOT NULL,
  `data_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_inicio_evento` datetime NOT NULL,
  `duracao_evento` varchar(15) DEFAULT NULL,
  `fk_id_criador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_grupo`
--

CREATE TABLE `tb_grupo` (
  `id_grupo` int(11) NOT NULL,
  `nome_grupo` varchar(30) NOT NULL,
  `descricao_grupo` varchar(350) DEFAULT NULL,
  `fk_id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_post`
--

CREATE TABLE `tb_post` (
  `id_post` int(11) NOT NULL,
  `titulo_post` varchar(20) DEFAULT NULL,
  `imagem_post` varchar(36) DEFAULT NULL,
  `texto_post` varchar(500) NOT NULL,
  `data_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_id_user` int(11) NOT NULL,
  `fk_id_tipo_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_tipo_post`
--

CREATE TABLE `tb_tipo_post` (
  `id_tipo_post` int(11) NOT NULL,
  `nome_tipo_post` char(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_tipo_post`
--

INSERT INTO `tb_tipo_post` (`id_tipo_post`, `nome_tipo_post`) VALUES
(1, 'avulso'),
(2, 'aviso');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `nome_user` varchar(50) NOT NULL,
  `sobrenome_user` varchar(50) DEFAULT NULL,
  `data_nasc_user` date DEFAULT NULL,
  `telefone_user` varchar(15) DEFAULT NULL,
  `cpf_user` varchar(14) DEFAULT NULL,
  `email_user` varchar(100) NOT NULL,
  `senha_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `temas_grupo`
--

CREATE TABLE `temas_grupo` (
  `id_temas_grupo` int(11) NOT NULL,
  `nome_temas` varchar(15) NOT NULL,
  `descricao_temas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_grupo`
--

CREATE TABLE `user_grupo` (
  `fk_id_user` int(11) NOT NULL,
  `fk_id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `evento_user`
--
ALTER TABLE `evento_user`
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_evento` (`fk_id_evento`);

--
-- Índices de tabela `grupo_tegru`
--
ALTER TABLE `grupo_tegru`
  ADD KEY `fk_id_grupo` (`fk_id_grupo`),
  ADD KEY `fk_id_temas_grupo` (`fk_id_temas_grupo`);

--
-- Índices de tabela `likes_comentario`
--
ALTER TABLE `likes_comentario`
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_comentario` (`fk_id_comentario`);

--
-- Índices de tabela `likes_post`
--
ALTER TABLE `likes_post`
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_post` (`fk_id_post`);

--
-- Índices de tabela `tb_comentario`
--
ALTER TABLE `tb_comentario`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_post` (`fk_id_post`);

--
-- Índices de tabela `tb_evento`
--
ALTER TABLE `tb_evento`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `fk_id_criador` (`fk_id_criador`);

--
-- Índices de tabela `tb_grupo`
--
ALTER TABLE `tb_grupo`
  ADD PRIMARY KEY (`id_grupo`),
  ADD KEY `fk_id_user` (`fk_id_user`);

--
-- Índices de tabela `tb_post`
--
ALTER TABLE `tb_post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_tipo_post` (`fk_id_tipo_post`);

--
-- Índices de tabela `tb_tipo_post`
--
ALTER TABLE `tb_tipo_post`
  ADD PRIMARY KEY (`id_tipo_post`);

--
-- Índices de tabela `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Índices de tabela `temas_grupo`
--
ALTER TABLE `temas_grupo`
  ADD PRIMARY KEY (`id_temas_grupo`);

--
-- Índices de tabela `user_grupo`
--
ALTER TABLE `user_grupo`
  ADD KEY `fk_id_user` (`fk_id_user`),
  ADD KEY `fk_id_grupo` (`fk_id_grupo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_comentario`
--
ALTER TABLE `tb_comentario`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_evento`
--
ALTER TABLE `tb_evento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_grupo`
--
ALTER TABLE `tb_grupo`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_post`
--
ALTER TABLE `tb_post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_tipo_post`
--
ALTER TABLE `tb_tipo_post`
  MODIFY `id_tipo_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `temas_grupo`
--
ALTER TABLE `temas_grupo`
  MODIFY `id_temas_grupo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `evento_user`
--
ALTER TABLE `evento_user`
  ADD CONSTRAINT `evento_user_ibfk_1` FOREIGN KEY (`fk_id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `evento_user_ibfk_2` FOREIGN KEY (`fk_id_evento`) REFERENCES `tb_evento` (`id_evento`);

--
-- Restrições para tabelas `grupo_tegru`
--
ALTER TABLE `grupo_tegru`
  ADD CONSTRAINT `grupo_tegru_ibfk_1` FOREIGN KEY (`fk_id_grupo`) REFERENCES `tb_grupo` (`id_grupo`),
  ADD CONSTRAINT `grupo_tegru_ibfk_2` FOREIGN KEY (`fk_id_temas_grupo`) REFERENCES `temas_grupo` (`id_temas_grupo`);

--
-- Restrições para tabelas `likes_comentario`
--
ALTER TABLE `likes_comentario`
  ADD CONSTRAINT `likes_comentario_ibfk_1` FOREIGN KEY (`fk_id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `likes_comentario_ibfk_2` FOREIGN KEY (`fk_id_comentario`) REFERENCES `tb_comentario` (`id_comentario`);

--
-- Restrições para tabelas `likes_post`
--
ALTER TABLE `likes_post`
  ADD CONSTRAINT `likes_post_ibfk_1` FOREIGN KEY (`fk_id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `likes_post_ibfk_2` FOREIGN KEY (`fk_id_post`) REFERENCES `tb_post` (`id_post`);

--
-- Restrições para tabelas `tb_comentario`
--
ALTER TABLE `tb_comentario`
  ADD CONSTRAINT `tb_comentario_ibfk_1` FOREIGN KEY (`fk_id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `tb_comentario_ibfk_2` FOREIGN KEY (`fk_id_post`) REFERENCES `tb_post` (`id_post`);

--
-- Restrições para tabelas `tb_evento`
--
ALTER TABLE `tb_evento`
  ADD CONSTRAINT `tb_evento_ibfk_1` FOREIGN KEY (`fk_id_criador`) REFERENCES `tb_user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
