-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/06/2025 às 23:43
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

--
-- Despejando dados para a tabela `evento_user`
--

INSERT INTO `evento_user` (`fk_id_user`, `fk_id_evento`) VALUES
(6, 8);

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupo_tegru`
--

CREATE TABLE `grupo_tegru` (
  `fk_id_grupo` int(11) NOT NULL,
  `fk_id_temas_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `grupo_tegru`
--

INSERT INTO `grupo_tegru` (`fk_id_grupo`, `fk_id_temas_grupo`) VALUES
(4, 6);

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
  `data_comentario` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_id_user` int(11) NOT NULL,
  `fk_id_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_evento`
--

CREATE TABLE `tb_evento` (
  `id_evento` int(11) NOT NULL,
  `nome_evento` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `img_evento` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descricao_evento` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_inicio_evento` datetime NOT NULL,
  `data_termino_evento` datetime DEFAULT NULL,
  `valor_pedestre` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `valor_exposicao` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rua_evento` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `numero_evento` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cidade_evento` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `estado_evento` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fk_id_criador` int(11) NOT NULL,
  `fk_id_grupo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `tb_evento`
--

INSERT INTO `tb_evento` (`id_evento`, `nome_evento`, `img_evento`, `descricao_evento`, `data_post`, `data_inicio_evento`, `data_termino_evento`, `valor_pedestre`, `valor_exposicao`, `rua_evento`, `numero_evento`, `cidade_evento`, `estado_evento`, `fk_id_criador`, `fk_id_grupo`) VALUES
(8, 'FTG Meeting', 'event_683cc321ac754.jpeg', 'Prepare-se para um encontro épico! O FTG Meeting reúne os amantes de automobilismo para um espetáculo de máquinas incríveis, pilotos talentosos e uma atmosfera vibrante cheia de adrenalina.', '2025-06-01 21:16:17', '2025-05-22 20:00:00', '2025-05-23 00:00:00', '15,00', '50,00', 'Av. Cruzeiro do Sul', '1000', 'são paulo', 'SP', 6, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_grupo`
--

CREATE TABLE `tb_grupo` (
  `id_grupo` int(11) NOT NULL,
  `nome_grupo` varchar(50) NOT NULL,
  `img_grupo` varchar(36) DEFAULT NULL,
  `descricao_grupo` varchar(600) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_grupo`
--

INSERT INTO `tb_grupo` (`id_grupo`, `nome_grupo`, `img_grupo`, `descricao_grupo`, `data_criacao`, `fk_id_user`) VALUES
(4, 'penguinos no chat', 'group_683c9ad2156f1.png', 'só os pinguins mais estupendos que você poderia imaginar', '2025-06-01 18:24:18', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_post`
--

CREATE TABLE `tb_post` (
  `id_post` int(11) NOT NULL,
  `titulo_post` varchar(50) DEFAULT NULL,
  `imagem_post` varchar(36) DEFAULT NULL,
  `texto_post` varchar(500) NOT NULL,
  `data_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_id_user` int(11) NOT NULL,
  `fk_id_tipo_post` int(11) NOT NULL,
  `fk_id_grupo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_tipo_post`
--

CREATE TABLE `tb_tipo_post` (
  `id_tipo_post` int(11) NOT NULL,
  `nome_tipo_post` char(15) DEFAULT NULL,
  `cor_fundo` varchar(7) DEFAULT NULL,
  `cor_letra` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_tipo_post`
--

INSERT INTO `tb_tipo_post` (`id_tipo_post`, `nome_tipo_post`, `cor_fundo`, `cor_letra`) VALUES
(14, 'teste', '#fff947', 0),
(15, 'verde', '#24ff3d', 0),
(16, 'vermelho', '#ff2e2e', 0),
(19, 'fofo', '#ff94e2', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `nome_user` varchar(50) NOT NULL,
  `sobrenome_user` varchar(50) DEFAULT NULL,
  `bio_user` varchar(250) NOT NULL,
  `img_user` varchar(36) DEFAULT NULL,
  `data_nasc_user` date DEFAULT NULL,
  `telefone_user` varchar(15) DEFAULT NULL,
  `cpf_user` varchar(14) DEFAULT NULL,
  `email_user` varchar(100) NOT NULL,
  `senha_user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `nome_user`, `sobrenome_user`, `bio_user`, `img_user`, `data_nasc_user`, `telefone_user`, `cpf_user`, `email_user`, `senha_user`) VALUES
(6, 'david', 'de jesus almeida', 'talvez o único que utilizou Pokémon para fazer os testes de post, evento e até grupos no site. Quem sabe? eu mesmo não sei kkkkkkkkkkkk', 'user_david.png', NULL, '(11) 22222-2222', NULL, 'dwtazer@gmail.com', '$2y$10$.b4Nc9dgLuUpO7vfWXSai.aG2pJePLfIbIj8wio9.jqM/.YfRvUi6'),
(9, 'tester', 'padrão', '', 'user_padrao.jpg', NULL, '(99) 99999-9999', NULL, 'tester@gmail.com', '$2y$10$I./0s6S9mLoykNOJ38wzLumKkXFidIisjAoq2eqyuIEv5lFfE12o.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `temas_grupo`
--

CREATE TABLE `temas_grupo` (
  `id_temas_grupo` int(11) NOT NULL,
  `nome_temas` varchar(15) NOT NULL,
  `descricao_temas` varchar(100) NOT NULL,
  `cor_fundo` varchar(7) DEFAULT NULL,
  `cor_letras` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `temas_grupo`
--

INSERT INTO `temas_grupo` (`id_temas_grupo`, `nome_temas`, `descricao_temas`, `cor_fundo`, `cor_letras`) VALUES
(6, 'random', '', '#000000', 1),
(7, 'carros', '', '#990000', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_grupo`
--

CREATE TABLE `user_grupo` (
  `fk_id_user` int(11) NOT NULL,
  `fk_id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `user_grupo`
--

INSERT INTO `user_grupo` (`fk_id_user`, `fk_id_grupo`) VALUES
(6, 4);

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
  ADD KEY `fk_id_criador` (`fk_id_criador`),
  ADD KEY `fk_id_grupo` (`fk_id_grupo`);

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
  ADD KEY `fk_id_tipo_post` (`fk_id_tipo_post`),
  ADD KEY `fk_id_grupo` (`fk_id_grupo`);

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
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tb_evento`
--
ALTER TABLE `tb_evento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tb_grupo`
--
ALTER TABLE `tb_grupo`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_post`
--
ALTER TABLE `tb_post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `tb_tipo_post`
--
ALTER TABLE `tb_tipo_post`
  MODIFY `id_tipo_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `temas_grupo`
--
ALTER TABLE `temas_grupo`
  MODIFY `id_temas_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `tb_evento_ibfk_1` FOREIGN KEY (`fk_id_criador`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `tb_evento_ibfk_2` FOREIGN KEY (`fk_id_grupo`) REFERENCES `tb_grupo` (`id_grupo`);

--
-- Restrições para tabelas `tb_post`
--
ALTER TABLE `tb_post`
  ADD CONSTRAINT `tb_post_ibfk_1` FOREIGN KEY (`fk_id_grupo`) REFERENCES `tb_grupo` (`id_grupo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
