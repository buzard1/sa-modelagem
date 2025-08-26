-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/08/2025 às 19:18
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
-- Banco de dados: `sa_mobilerepair`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`cpf`, `telefone`, `nome`, `email`, `endereco`) VALUES
('039.754.826-56', '(084) 6833-9911', 'Ana Oliveira', 'mendesbryan@farias.com', 'Aeroporto Carvalho'),
('064.837.251-08', '+55 (041) 5379 3853', 'Lorenzo Rocha', 'da-rosaana-julia@martins.net', 'Núcleo Daniela Ramos'),
('079.561.832-86', '+55 84 4766-3212', 'Sra. Beatriz Moreira', 'vnogueira@alves.com', 'Estação da Cunha'),
('084.135.679-39', '+55 61 9351 5965', 'Dr. João Lucas Fernandes', 'uda-conceicao@almeida.com', 'Morro Almeida'),
('093.785.241-41', '84 4249 4680', 'Kaique Campos', 'xfernandes@fernandes.com', 'Favela de Cardoso'),
('104.598.637-20', '+55 61 1421-6366', 'Bianca Costela', 'correiapedro@martins.org', 'Vila de Almeida'),
('12.312.312/3123-12', '(12) 31231-2312', 'teste', 'teste@teste', 'asdasd'),
('123', '(12) 31231-2312', 'testess', 'testeste@asdas', 'peidpo anal'),
('124.798.560-11', '+55 41 3834 8547', 'Eduardo Cunha', 'cecilia20@bol.com.br', 'Passarela Davi da Rocha'),
('145.069.237-07', '61 8827-6874', 'Heloísa da Conceição', 'xcardoso@bol.com.br', 'Viaduto Vitor Aragão'),
('145.736.982-64', '+55 (081) 7010 6223', 'Leandro Duarte', 'vitorpinto@alves.org', 'Aeroporto Murilo Araújo'),
('150.692.738-68', '+55 31 6337-7982', 'Davi Lucas Vieira', 'joao-guilhermefogaca@das.com', 'Passarela de Lopes'),
('204.398.165-05', '+55 71 9384-1644', 'Danilo Dias', 'marcos-vinicius91@ig.com.br', 'Fazenda Bruno Almeida'),
('209.165.478-76', '61 3471 3222', 'Davi Lucas Gomes', 'barroscatarina@vieira.br', 'Área Enzo Carvalho'),
('230.476.519-06', '+55 81 5354-1092', 'Elisa Vieira', 'castropietro@hotmail.com', 'Núcleo Mariana da Conceição'),
('231.754.680-71', '+55 (084) 2464-5011', 'Ana Júlia da Mota', 'da-luzdaniela@santos.com', 'Colônia Campos'),
('234.785.601-44', '+55 (031) 8054 3379', 'Luiz Fernando Melo', 'fbarbosa@nunes.net', 'Largo de Carvalho'),
('278.905.641-20', '+55 31 5287 3551', 'Isaac Lopes', 'joao-gabrielda-mata@hotmail.com', 'Vila Sarah Costa'),
('289.036.475-56', '(031) 5462 4580', 'Ana Julia Caldeira', 'alicia07@da.com', 'Área de Melo'),
('315.428.906-24', '(021) 9236-8108', 'Dra. Marina Nunes', 'zda-rosa@hotmail.com', 'Sítio Pietra Peixoto'),
('320.856.497-00', '0900 932 9553', 'Clarice Porto', 'melobruna@uol.com.br', 'Quadra Cunha'),
('354.609.217-16', '+55 (071) 0932-6313', 'Calebe Carvalho', 'peixotobruno@santos.br', 'Rodovia Moraes'),
('371.098.645-10', '+55 71 6601 9874', 'Emanuelly Nascimento', 'goncalvesrafael@ig.com.br', 'Estação de Viana'),
('381.257.609-03', '41 5057-2681', 'Calebe Pereira', 'danilo65@bol.com.br', 'Recanto de Moura'),
('392.618.745-09', '+55 41 3748-9340', 'Agatha Campos', 'benicio16@oliveira.org', 'Estrada Viana'),
('397.650.421-16', '31 9748 6726', 'Enrico Costela', 'luiza14@rezende.net', 'Setor Valentina Jesus'),
('403.672.895-47', '+55 84 0410 8578', 'Amanda Santos', 'dmoreira@gmail.com', 'Conjunto de da Cruz'),
('407.962.831-50', '+55 51 8244 7293', 'Davi Silva', 'vduarte@bol.com.br', 'Largo Santos'),
('471.839.562-37', '+55 (021) 1084-8963', 'Dra. Alice Lopes', 'lorenzomelo@yahoo.com.br', 'Lagoa de Cavalcanti'),
('483.520.769-65', '0300-018-5541', 'Juan Peixoto', 'bruno22@gmail.com', 'Vereda de Souza'),
('492.165.837-46', '0300-155-6549', 'Sarah da Paz', 'benjaminmartins@uol.com.br', 'Campo Aragão'),
('507.324.961-52', '+55 (031) 9290 1760', 'Ana Laura Duarte', 'luiz-otaviosilva@uol.com.br', 'Parque Cunha'),
('51.251.251/2511-52', '(12) 31245-1241', 'testeeee', 'teste@teste1d', 'asdasdadasd'),
('569.047.281-67', '(051) 3062-9559', 'Luana da Conceição', 'joao-lucas72@viana.com', 'Ladeira Sales'),
('578.120.649-30', '(051) 3370-1729', 'Isaac Costela', 'bruno09@da.com', 'Fazenda de Rocha'),
('597.840.312-05', '+55 (011) 8815-1304', 'Marina Silva', 'camposrafaela@bol.com.br', 'Travessa de Correia'),
('621.987.543-55', '0500-159-0783', 'Diogo Viana', 'beatriz61@ig.com.br', 'Fazenda Rodrigues'),
('625.948.371-64', '+55 81 4208-8510', 'Srta. Brenda da Costa', 'maria-cecilia34@farias.com', 'Área de Correia'),
('647.210.538-35', '81 9884-0806', 'Breno Aragão', 'wlopes@uol.com.br', 'Viaduto Almeida'),
('648.253.791-09', '+55 51 9021 6454', 'Enzo Gabriel Novaes', 'da-rosacaue@monteiro.br', 'Trevo Silva'),
('670.123.859-95', '+55 31 3136-2567', 'Luiz Miguel Barros', 'ian79@pereira.org', 'Condomínio da Rosa'),
('675.829.430-74', '(084) 8798-4168', 'Laura da Rosa', 'matheusmartins@hotmail.com', 'Área Pires'),
('678.943.012-13', '+55 71 8691-8204', 'Valentina Nunes', 'jesusmilena@sales.br', 'Sítio Silva'),
('680.975.314-10', '21 4818 3897', 'Lara Cardoso', 'helena25@freitas.br', 'Vila Rodrigues'),
('702.164.598-85', '(051) 8746 5840', 'Ana Lívia Cunha', 'das-nevesbreno@uol.com.br', 'Fazenda Barbosa'),
('705.291.684-30', '+55 84 8222 3799', 'Diego Ribeiro', 'lavinia66@azevedo.com', 'Lagoa de das Neves'),
('718.203.596-03', '21 0902 6040', 'Francisco Aragão', 'monteiropietra@uol.com.br', 'Passarela de Azevedo'),
('723.590.481-04', '0800 161 1206', 'João Gabriel Rodrigues', 'ana-livia53@yahoo.com.br', 'Campo de Oliveira'),
('751.893.046-39', '31 3137-6779', 'Lavínia Gonçalves', 'uda-costa@yahoo.com.br', 'Colônia Lima'),
('751.948.620-67', '0500 317 9814', 'Sophie Barbosa', 'fsilva@nunes.com', 'Alameda de Peixoto'),
('760.284.519-67', '+55 61 8474 1528', 'Marcos Vinicius da Mata', 'mcostela@yahoo.com.br', 'Vale de Silveira'),
('803.642.751-62', '84 0526 9685', 'Manuela Pinto', 'nogueirabryan@pereira.br', 'Lago de Araújo'),
('809.576.421-30', '+55 (051) 4911 9846', 'Thiago Silveira', 'zda-mata@barbosa.br', 'Quadra Azevedo'),
('824.076.519-01', '(084) 7267 0756', 'Davi da Mota', 'gnascimento@barros.com', 'Recanto da Mata'),
('831.276.405-44', '(061) 6723-4653', 'Alana Souza', 'caueda-mota@uol.com.br', 'Alameda Isabel Viana'),
('831.549.720-05', '(031) 2519-1348', 'Bruno da Luz', 'almeidamaria-julia@hotmail.com', 'Travessa Martins'),
('854.170.263-44', '+55 31 9618 2676', 'Ana Clara Duarte', 'da-rochamaria-eduarda@araujo.com', 'Feira Maria Ferreira'),
('857.123.046-35', '21 0430 3220', 'Benjamin Rodrigues', 'biancaporto@bol.com.br', 'Quadra Alves'),
('863.524.079-00', '(061) 1334-3275', 'Júlia da Mota', 'maria-vitoria56@uol.com.br', 'Quadra Maria Eduarda da Rocha'),
('865.423.901-89', '+55 61 9652 2586', 'Sr. Raul Costela', 'yasminvieira@cunha.com', 'Aeroporto Esther Martins'),
('867.140.932-50', '+55 71 3340-6597', 'Bruno Correia', 'egoncalves@bol.com.br', 'Viela Lucca da Costa'),
('907.312.854-41', '(011) 5891 1668', 'Lorena Cardoso', 'crezende@ig.com.br', 'Condomínio Davi Lucca das Neves'),
('970.425.136-06', '61 1871-6668', 'Manuela Campos', 'nmoreira@da.com', 'Viela Nina Sales');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id_estoque` int(11) NOT NULL AUTO_INCREMENT,
  `quantidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_estoque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id_estoque`, `quantidade`) VALUES
(1, 8),
(2, 35),
(3, 71),
(4, 14),
(5, 37),
(6, 85),
(7, 88),
(8, 71),
(9, 29),
(10, 93),
(11, 70),
(12, 41),
(13, 60),
(14, 83),
(15, 98),
(16, 77),
(17, 8),
(18, 17),
(19, 32),
(20, 62),
(21, 57),
(22, 43),
(23, 44),
(24, 42),
(25, 99),
(26, 37),
(27, 70),
(28, 52),
(29, 56),
(30, 92),
(31, 38),
(32, 38),
(33, 94),
(34, 16),
(35, 81),
(36, 58),
(37, 40),
(38, 37),
(39, 32),
(40, 60),
(41, 12),
(42, 53),
(43, 18),
(44, 58),
(45, 88),
(46, 76),
(47, 23),
(48, 61),
(49, 21),
(50, 94),
(51, 12),
(52, 65),
(53, 53),
(54, 30),
(55, 72),
(56, 78),
(57, 28),
(58, 34),
(59, 53),
(60, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id_fornecedor` int(11) NOT NULL,
  `nome_fornecedor` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id_fornecedor`, `nome_fornecedor`, `telefone`, `email`) VALUES
(1, 'Carvalho', '+55 51 2191 2054', 'cardosoraul@hotmail.com'),
(2, 'Correia Carvalho Ltda.', '+55 (021) 2765-4034', 'rafaelamelo@cardoso.br'),
(3, 'da Mota Martins S/A', '0900-671-9417', 'lucca12@bol.com.br'),
(4, 'Cardoso Cunha Ltda.', '+55 (021) 0102 5683', 'ribeiromilena@monteiro.br'),
(5, 'Novaes Correia S/A', '11 4870-5585', 'isabel50@hotmail.com'),
(6, 'Rocha', '+55 (021) 9217-6890', 'da-cunhaotavio@uol.com.br'),
(7, 'Lopes S/A', '+55 (021) 1512 5153', 'cavalcantiemanuella@viana.com'),
(8, 'Pereira Peixoto Ltda.', '71 4195-2641', 'ramospedro-miguel@uol.com.br'),
(9, 'Novaes', '+55 (081) 9421-3116', 'da-rochapietra@da.com'),
(10, 'Silva Moraes e Filhos', '+55 (021) 4526 8167', 'caio22@uol.com.br'),
(11, 'Vieira', '(084) 3637-7097', 'renan44@bol.com.br'),
(12, 'Fogaça e Filhos', '0300 325 6180', 'nmoura@ig.com.br'),
(13, 'Nogueira - EI', '+55 (084) 9617 9337', 'aliciaaragao@novaes.br'),
(14, 'Moraes', '+55 (081) 4459 0521', 'davi-lucasda-luz@freitas.com'),
(15, 'Mendes', '+55 (061) 5272-8704', 'vitor-hugo36@pires.br'),
(16, 'Campos da Luz - ME', '+55 11 2738-0747', 'ana-carolina22@araujo.br'),
(17, 'Sales', '11 4360-8848', 'luiz-miguel80@teixeira.com'),
(18, 'Oliveira', '+55 (084) 2793-8182', 'cardosoyuri@santos.net'),
(19, 'Pereira', '+55 (031) 9366-3364', 'rezendecarlos-eduardo@hotmail.com'),
(20, 'Castro S.A.', '+55 (071) 5784-7403', 'catarina12@teixeira.br'),
(21, 'Ferreira', '+55 61 8361-9383', 'nunesdaniela@ig.com.br'),
(22, 'Lopes - ME', '+55 (081) 1091 4153', 'npeixoto@campos.br'),
(23, 'da Conceição', '+55 71 8382 0606', 'nmoraes@cavalcanti.net'),
(24, 'Duarte - EI', '+55 41 6735-9122', 'aalmeida@pinto.br'),
(25, 'Nogueira da Mata e Filhos', '71 9663 5087', 'natalia11@monteiro.com'),
(26, 'Gomes', '+55 (021) 0183-4857', 'vieiranathan@ferreira.com'),
(27, 'Teixeira', '0800 667 1916', 'isadora12@novaes.br'),
(28, 'da Conceição Ltda.', '+55 (081) 1476-9034', 'larissada-mota@barbosa.br'),
(29, 'Castro Pereira e Filhos', '+55 31 4754 0556', 'alvesgustavo-henrique@hotmail.com'),
(30, 'Costa', '(081) 1450 1439', 'jbarbosa@moreira.com'),
(31, 'Melo Novaes S.A.', '(021) 1430-5707', 'bviana@yahoo.com.br'),
(32, 'Gonçalves', '(051) 8756 9328', 'piresian@uol.com.br'),
(33, 'Peixoto', '+55 (021) 5024-4738', 'ananogueira@moura.org'),
(34, 'Porto', '+55 81 1272-6728', 'qmoreira@ig.com.br'),
(35, 'da Mota', '0500-857-2347', 'fogacavitor@martins.com'),
(36, 'das Neves', '(031) 0362-9819', 'analopes@teixeira.br'),
(37, 'Castro', '+55 (061) 6393 4740', 'nataliaribeiro@barbosa.br'),
(38, 'Moura', '+55 84 2160-0233', 'ribeiromilena@ig.com.br'),
(39, 'Barros S/A', '(084) 2588-4932', 'joao-pedro40@almeida.br'),
(40, 'Azevedo - EI', '31 2001-2162', 'maria-eduarda69@gmail.com'),
(41, 'Ramos Rodrigues S.A.', '(084) 7067 5658', 'igor98@hotmail.com'),
(42, 'Nogueira Dias - EI', '41 9214 7789', 'prezende@da.br'),
(43, 'Barbosa - EI', '0800-305-0652', 'ana-livialopes@campos.com'),
(44, 'Rocha Sales - EI', '0300 032 3616', 'santosalexandre@moura.com'),
(45, 'Fernandes', '41 1156-0183', 'rodriguescamila@correia.org'),
(46, 'Novaes Ltda.', '81 0541 1378', 'ccostela@uol.com.br'),
(47, 'Cavalcanti e Filhos', '41 7341 8301', 'maria-eduarda18@uol.com.br'),
(48, 'da Costa', '81 0608 9352', 'dmoraes@rezende.br'),
(49, 'Carvalho Ltda.', '+55 71 0193 1425', 'vianaana-vitoria@ig.com.br'),
(50, 'da Conceição', '(041) 5779 3176', 'maria-juliada-mota@peixoto.net'),
(51, 'da Conceição S.A.', '71 0244-7520', 'otaviocunha@yahoo.com.br'),
(52, 'Silva - ME', '41 3821-7616', 'catarinadas-neves@fogaca.com'),
(53, 'Gomes - EI', '+55 (011) 8194 7925', 'ydias@ig.com.br'),
(54, 'Pires', '+55 (011) 9104 9190', 'salesenzo@uol.com.br'),
(55, 'Nunes', '+55 (021) 0839-4365', 'claricecostela@gmail.com'),
(56, 'Pires - EI', '(021) 0296 3021', 'theo70@gmail.com'),
(57, 'da Rocha S.A.', '+55 (081) 8512 0844', 'marcos-viniciusfarias@yahoo.com.br'),
(58, 'da Costa Viana - EI', '+55 21 4213 6138', 'cavalcanticarlos-eduardo@fernandes.com'),
(59, 'Vieira Cardoso - ME', '11 7500-9602', 'milena69@azevedo.org'),
(60, 'Gonçalves', '+55 81 3646 9579', 'isismoraes@campos.br');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_serv`
--

CREATE TABLE `ordem_serv` (
  `id_ordem_serv` int(11) NOT NULL AUTO_INCREMENT,
  `Aparelho` varchar(100) DEFAULT NULL,
  `servico` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `tipo_pagamento` varchar(50) DEFAULT NULL,
  `problema` varchar(255) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `data_entrada` date DEFAULT NULL,
  `data_saida` date DEFAULT NULL,
  PRIMARY KEY (`id_ordem_serv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_serv`
--

INSERT INTO `ordem_serv` (`id_ordem_serv`, `Aparelho`, `servico`, `status`, `valor`, `tipo_pagamento`, `problema`, `cpf`, `idusuario`, `data_entrada`, `data_saida`) VALUES
(1, 'Realme GT', 'Reparo de câmera', 'Em andamento', 811.38, 'Dinheiro', 'Tela quebrada', '680.975.314-10', 2, '2024-05-17', NULL),
(2, 'Nokia X30', 'Troca de tela', 'Cancelado', 905.66, 'Pix', 'Botão power travado', '648.253.791-09', 59, '2024-08-02', '2024-08-04'),
(3, 'iPhone SE', 'Troca de botão power', 'Concluído', 651.01, 'Cartão Crédito', 'Câmera não funciona', '209.165.478-76', 10, '2024-08-20', '2024-08-23'),
(4, 'Xiaomi Redmi Note 12', 'Troca de alto-falante', 'Concluído', 790.14, 'Boleto Bancário', 'Sistema lento', '371.098.645-10', 60, '2024-11-17', '2024-11-20'),
(5, 'iPhone SE', 'Troca de alto-falante', 'Em andamento', 795.63, 'Boleto Bancário', 'Microfone mudo', '675.829.430-74', 27, '2025-01-10', NULL),
(6, 'Motorola Edge 30', 'Troca de tela', 'Cancelado', 950.90, 'Pix', 'Sistema lento', '104.598.637-20', 38, '2024-06-06', '2024-06-08'),
(7, 'Samsung Galaxy S21', 'Reparo de câmera', 'Cancelado', 414.70, 'Dinheiro', 'Tela quebrada', '863.524.079-00', 24, '2024-05-17', '2024-05-19'),
(8, 'Nokia X30', 'Desbloqueio de conta', 'Cancelado', 296.33, 'Dinheiro', 'Sistema lento', '483.520.769-65', 31, '2024-06-15', '2024-06-17'),
(9, 'Motorola Edge 30', 'Formatação', 'Cancelado', 984.94, 'Pix', 'Botão power travado', '751.893.046-39', 6, '2025-04-12', '2025-04-14'),
(10, 'iPhone 14', 'Atualização de software', 'Cancelado', 563.98, 'Pix', 'Sistema travando', '705.291.684-30', 49, '2024-11-07', '2024-11-09'),
(11, 'Asus ROG Phone 5', 'Troca de alto-falante', 'Concluído', 258.83, 'Dinheiro', 'Microfone mudo', '597.840.312-05', 24, '2025-01-31', '2025-02-03'),
(12, 'iPhone 13 Mini', 'Atualização de software', 'Em andamento', 187.81, 'Cartão Débito', 'Câmera não funciona', '124.798.560-11', 48, '2025-03-14', NULL),
(13, 'iPhone SE', 'Troca de bateria', 'Cancelado', 340.92, 'Pix', 'Botão power travado', '392.618.745-09', 6, '2025-02-09', '2025-02-11'),
(14, 'Nokia X30', 'Troca de microfone', 'Cancelado', 108.14, 'Boleto Bancário', 'Sistema lento', '809.576.421-30', 40, '2025-04-24', '2025-04-26'),
(15, 'Xiaomi Poco X5 Pro', 'Formatação', 'Concluído', 567.69, 'Boleto Bancário', 'Conta Google bloqueada', '970.425.136-06', 23, '2024-09-13', '2024-09-16'),
(16, 'Realme GT', 'Troca de botão power', 'Cancelado', 762.81, 'Boleto Bancário', 'Tela quebrada', '760.284.519-67', 18, '2024-10-02', '2024-10-04'),
(17, 'iPhone 13 Mini', 'Desbloqueio de conta', 'Cancelado', 522.50, 'Pix', 'Sistema lento', '145.736.982-64', 7, '2025-04-02', '2025-04-04'),
(18, 'Xiaomi Poco X5 Pro', 'Troca de tela', 'Concluído', 961.96, 'Dinheiro', 'Câmera não funciona', '315.428.906-24', 36, '2024-09-06', '2024-09-09'),
(19, 'Realme GT', 'Substituição de conector de carga', 'Em andamento', 980.47, 'Cartão Crédito', 'Sistema lento', '397.650.421-16', 60, '2024-07-28', NULL),
(20, 'Nokia X30', 'Atualização de software', 'Em andamento', 750.86, 'Boleto Bancário', 'Microfone mudo', '471.839.562-37', 28, '2024-04-28', NULL),
(21, 'OnePlus Nord CE', 'Troca de microfone', 'Em andamento', 100.11, 'Boleto Bancário', 'Microfone mudo', '381.257.609-03', 42, '2024-06-15', NULL),
(22, 'iPhone 14', 'Troca de alto-falante', 'Concluído', 900.75, 'Cartão Crédito', 'Conta Google bloqueada', '723.590.481-04', 10, '2024-07-19', '2024-07-22'),
(23, 'Nokia X30', 'Troca de tela', 'Em andamento', 471.33, 'Cartão Débito', 'Conector de carga danificado', '647.210.538-35', 11, '2025-02-17', NULL),
(24, 'iPhone 14', 'Formatação', 'Cancelado', 652.27, 'Dinheiro', 'Sistema travando', '403.672.895-47', 22, '2025-04-21', '2025-04-23'),
(25, 'OnePlus Nord CE', 'Troca de alto-falante', 'Concluído', 957.98, 'Boleto Bancário', 'Conector de carga danificado', '854.170.263-44', 11, '2024-12-16', '2024-12-19'),
(26, 'iPhone 13 Mini', 'Atualização de software', 'Concluído', 707.75, 'Cartão Débito', 'Conector de carga danificado', '751.948.620-67', 47, '2024-06-15', '2024-06-18'),
(27, 'OnePlus Nord CE', 'Desbloqueio de conta', 'Em andamento', 747.28, 'Boleto Bancário', 'Microfone mudo', '492.165.837-46', 56, '2024-05-27', NULL),
(28, 'Motorola Edge 30', 'Desbloqueio de conta', 'Cancelado', 817.93, 'Dinheiro', 'Conector de carga danificado', '354.609.217-16', 49, '2024-07-11', '2024-07-13'),
(29, 'iPhone SE', 'Troca de tela', 'Cancelado', 994.83, 'Cartão Crédito', 'Tela quebrada', '204.398.165-05', 41, '2024-09-01', '2024-09-03'),
(30, 'Asus ROG Phone 5', 'Substituição de conector de carga', 'Em andamento', 768.11, 'Dinheiro', 'Sistema lento', '621.987.543-55', 7, '2024-07-03', NULL),
(31, 'Xiaomi Redmi Note 12', 'Troca de microfone', 'Em andamento', 178.60, 'Cartão Crédito', 'Conector de carga danificado', '231.754.680-71', 35, '2024-10-17', NULL),
(32, 'Nokia X30', 'Troca de microfone', 'Em andamento', 605.92, 'Dinheiro', 'Conector de carga danificado', '320.856.497-00', 1, '2025-01-13', NULL),
(33, 'Xiaomi Redmi Note 12', 'Reparo de câmera', 'Cancelado', 723.94, 'Dinheiro', 'Câmera não funciona', '084.135.679-39', 52, '2025-01-03', '2025-01-05'),
(34, 'OnePlus Nord CE', 'Troca de alto-falante', 'Em andamento', 924.18, 'Cartão Débito', 'Botão power travado', '064.837.251-08', 53, '2024-06-20', NULL),
(35, 'Samsung Galaxy S21', 'Atualização de software', 'Cancelado', 401.00, 'Boleto Bancário', 'Alto-falante chiando', '278.905.641-20', 46, '2025-02-01', '2025-02-03'),
(36, 'Asus ROG Phone 5', 'Troca de bateria', 'Em andamento', 788.84, 'Cartão Crédito', 'Tela quebrada', '039.754.826-56', 28, '2024-12-23', NULL),
(37, 'iPhone 13 Mini', 'Troca de microfone', 'Em andamento', 118.31, 'Cartão Débito', 'Sistema travando', '150.692.738-68', 9, '2024-06-01', NULL),
(38, 'iPhone 13 Mini', 'Troca de bateria', 'Cancelado', 248.22, 'Boleto Bancário', 'Microfone mudo', '145.069.237-07', 38, '2024-05-20', '2024-05-22'),
(39, 'Asus ROG Phone 5', 'Troca de alto-falante', 'Concluído', 289.58, 'Cartão Crédito', 'Sistema travando', '831.276.405-44', 39, '2024-06-09', '2024-06-12'),
(40, 'Nokia X30', 'Troca de botão power', 'Concluído', 421.29, 'Boleto Bancário', 'Conta Google bloqueada', '230.476.519-06', 39, '2024-07-10', '2024-07-13'),
(41, 'Samsung Galaxy A54', 'Formatação', 'Em andamento', 699.16, 'Boleto Bancário', 'Botão power travado', '670.123.859-95', 34, '2024-07-29', NULL),
(42, 'Samsung Galaxy S21', 'Substituição de conector de carga', 'Cancelado', 159.51, 'Cartão Débito', 'Tela quebrada', '702.164.598-85', 57, '2024-09-03', '2024-09-05'),
(43, 'LG K62+', 'Atualização de software', 'Cancelado', 865.37, 'Pix', 'Microfone mudo', '578.120.649-30', 23, '2024-09-13', '2024-09-15'),
(44, 'iPhone 13 Mini', 'Troca de microfone', 'Cancelado', 386.02, 'Boleto Bancário', 'Sistema travando', '678.943.012-13', 46, '2025-03-16', '2025-03-18'),
(45, 'Samsung Galaxy S21', 'Desbloqueio de conta', 'Cancelado', 322.12, 'Dinheiro', 'Sistema lento', '625.948.371-64', 36, '2024-06-27', '2024-06-29'),
(46, 'LG K62+', 'Troca de microfone', 'Concluído', 329.11, 'Boleto Bancário', 'Bateria descarregando rápido', '507.324.961-52', 50, '2024-09-28', '2024-10-01'),
(47, 'Motorola Edge 30', 'Troca de tela', 'Em andamento', 961.59, 'Dinheiro', 'Sistema travando', '865.423.901-89', 58, '2024-11-05', NULL),
(48, 'Samsung Z Flip', 'Troca de microfone', 'Em andamento', 555.95, 'Boleto Bancário', 'Sistema magas travando', '803.642.751-62', 44, '2024-07-30', NULL),
(49, 'iPhone SE', 'Substituição de conector de carga', 'Concluído', 786.34, 'Cartão Crédito', 'Câmera não funciona', '407.962.831-50', 57, '2024-09-08', '2024-09-11'),
(50, 'Nokia X30', 'Troca de alto-falante', 'Cancelado', 120.17, 'Pix', 'Câmera não funciona', '234.785.601-44', 19, '2024-10-13', '2024-10-15'),
(51, 'iPhone 14', 'Atualização de software', 'Concluído', 770.08, 'Dinheiro', 'Conta Google bloqueada', '857.123.046-35', 18, '2025-01-12', '2025-01-15'),
(52, 'Xiaomi Redmi Note 12', 'Formatação', 'Concluído', 481.50, 'Boleto Bancário', 'Conector de carga danificado', '569.047.281-67', 51, '2024-08-02', '2024-08-05'),
(53, 'Samsung Galaxy A54', 'Substituição de conector de carga', 'Cancelado', 516.58, 'Cartão Débito', 'Sistema lento', '831.549.720-05', 12, '2025-03-08', '2025-03-10'),
(54, 'Samsung Galaxy A54', 'Reparo de câmera', 'Concluído', 461.20, 'Dinheiro', 'Microfone mudo', '093.785.241-41', 57, '2024-04-26', '2024-04-29'),
(55, 'Asus ROG Phone 5', 'Reparo de câmera', 'Cancelado', 282.86, 'Cartão Crédito', 'Microfone mudo', '718.203.596-03', 2, '2025-03-15', '2025-03-17'),
(56, 'Asus ROG Phone 5', 'Formatação', 'Cancelado', 283.44, 'Pix', 'Botão power travado', '867.140.932-50', 35, '2024-10-15', '2024-10-17'),
(57, 'Samsung Galaxy A54', 'Reparo de câmera', 'Cancelado', 560.07, 'Boleto Bancário', 'Câmera não funciona', '907.312.854-41', 57, '2024-06-30', '2024-07-02'),
(58, 'Samsung Galaxy A54', 'Substituição de conector de carga', 'Cancelado', 134.06, 'Boleto Bancário', 'Câmera não funciona', '289.036.475-56', 31, '2024-06-24', '2024-06-26'),
(59, 'Samsung Z Flip', 'Formatação', 'Em andamento', 553.54, 'Cartão Crédito', 'Bateria descarregando rápido', '079.561.832-86', 49, '2024-10-06', NULL),
(60, 'OnePlus Nord CE', 'Troca de alto-falante', 'Concluído', 924.93, 'Pix', 'Conector de carga danificado', '824.076.519-01', 43, '2024-09-23', '2024-09-26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(100) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `idfornecedor` int(11) DEFAULT NULL,
  `idestoque` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome_produto`, `valor`, `idfornecedor`, `idestoque`) VALUES
(1, 'Display completo', 136.22, 3, 56),
(2, 'Câmera frontal', 298.81, 9, 34),
(3, 'Cabo USB-C', 445.79, 6, 55),
(4, 'Display completo', 30.51, 8, 41),
(5, 'Bateria de celular', 47.54, 9, 37),
(6, 'Película de vidro', 491.21, 31, 47),
(7, 'Capinha de silicone', 182.40, 16, 37),
(8, 'Capinha de silicone', 94.57, 57, 28),
(9, 'Carregador turbo', 351.61, 14, 34),
(10, 'Carregador veicular', 390.95, 15, 15),
(11, 'Dock station', 231.70, 45, 50),
(12, 'Kit ferramenta para celular', 308.65, 57, 7),
(13, 'Sensor de proximidade', 156.55, 55, 26),
(14, 'Buzzer', 494.00, 50, 43),
(15, 'Cabo USB-C', 42.26, 25, 24),
(16, 'Carregador veicular', 69.89, 5, 43),
(17, 'Película privativa', 443.14, 48, 53),
(18, 'Kit ferramenta para celular', 46.42, 13, 28),
(19, 'Carregador sem fio', 230.84, 26, 15),
(20, 'Microfone de reposição', 46.04, 6, 31),
(21, 'Estabilizador de energia', 153.42, 45, 57),
(22, 'Anel de suporte', 198.07, 45, 17),
(23, 'Conjunto de lentes', 280.10, 47, 5),
(24, 'Estabilizador de energia', 308.24, 51, 45),
(25, 'Câmera frontal', 23.37, 52, 46),
(26, 'Placa-mãe de celular', 183.10, 53, 42),
(27, 'Cola UV', 58.26, 42, 28),
(28, 'Sensor de proximidade', 79.82, 39, 3),
(29, 'Capinha de silicone', 62.90, 50, 14),
(30, 'Conector de carga', 482.46, 59, 36),
(31, 'Carregador sem fio', 143.63, 45, 57),
(32, 'Película de vidro', 245.41, 31, 53),
(33, 'Câmera traseira', 450.81, 14, 19),
(34, 'Câmera frontal', 129.05, 60, 9),
(35, 'Alto-falante para smartphone', 110.17, 21, 50),
(36, 'Kit ferramenta para celular', 94.88, 43, 20),
(37, 'Microfone de reposição', 384.80, 13, 27),
(38, 'Câmera traseira', 460.06, 24, 44),
(39, 'Fone de ouvido Bluetooth', 68.36, 4, 1),
(40, 'Película privativa', 49.50, 16, 12),
(41, 'Carregador turbo', 349.85, 16, 53),
(42, 'Placa-mãe de celular', 51.52, 21, 32),
(43, 'Película de vidro', 324.45, 59, 41),
(44, 'Cabo USB-C', 202.90, 34, 43),
(45, 'Dock station', 219.73, 8, 22),
(46, 'Cola UV', 302.66, 57, 21),
(47, 'Câmera traseira', 186.78, 47, 17),
(48, 'Conjunto de lentes', 103.91, 50, 40),
(49, 'Cooler para celular gamer', 96.10, 35, 55),
(50, 'Cartão de memória 64GB', 340.13, 21, 23),
(51, 'Carregador veicular', 104.79, 30, 19),
(52, 'Cabo USB-C', 468.90, 59, 14),
(53, 'Dock station', 266.99, 38, 20),
(54, 'Película privativa', 244.81, 27, 56),
(55, 'Botão power', 487.79, 57, 14),
(56, 'Microfone de reposição', 477.37, 10, 14),
(57, 'Película privativa', 474.72, 50, 54),
(58, 'Fone de ouvido Bluetooth', 247.63, 7, 21),
(59, 'Botão power', 314.77, 55, 49),
(60, 'Película privativa', 68.03, 55, 28);

-- --------------------------------------------------------

--
-- Estrutura para tabela `recover_senha`
--

CREATE TABLE `recover_senha` (
  `id_recover` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_expiracao` datetime NOT NULL,
  `utilizado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `recover_senha`
--

INSERT INTO `recover_senha` (`id_recover`, `id_usuario`, `token`, `data_criacao`, `data_expiracao`, `utilizado`) VALUES
(61, 1, 'token_a1b2c3d4e5f6g7h8i9j0', '2025-05-05 14:13:42', '2025-05-06 14:13:42', 0),
(62, 2, 'token_j2k3l4m5n6o7p8q9r0s1', '2025-04-30 14:13:42', '2025-05-01 14:13:42', 1),
(63, 3, 'token_s3t4u5v6w7x8y9z0a1b2', '2025-04-25 14:13:42', '2025-04-26 14:13:42', 0),
(64, 4, 'token_c4d5e6f7g8h9i0j1k2l3', '2025-05-10 14:13:42', '2025-05-11 14:13:42', 1),
(65, 5, 'token_m5n6o7p8q9r0s1t2u3v4', '2025-05-03 14:13:42', '2025-05-04 14:13:42', 0),
(66, 6, 'token_w6x7y8z9a0b1c2d3e4f5', '2025-04-27 14:13:42', '2025-04-28 14:13:42', 0),
(67, 7, 'token_g7h8i9j0k1l2m3n4o5p6', '2025-04-20 14:13:42', '2025-04-21 14:13:42', 1),
(68, 8, 'token_q8r9s0t1u2v3w4x5y6z7', '2025-05-13 14:13:42', '2025-05-14 14:13:42', 0),
(69, 9, 'token_a9b0c1d2e3f4g5h6i7j8', '2025-05-08 14:13:42', '2025-05-09 14:13:42', 1),
(70, 10, 'token_k0l1m2n3o4p5q6r7s8t9', '2025-04-23 14:13:42', '2025-04-24 14:13:42', 0),
(71, 11, 'token_u1v2w3x4y5z6a7b8c9d0', '2025-05-06 14:13:42', '2025-05-07 14:13:42', 0),
(72, 12, 'token_e2f3g4h5i6j7k8l9m0n1', '2025-04-28 14:13:42', '2025-04-29 14:13:42', 1),
(73, 13, 'token_o3p4q5r6s7t8u9v0w1x2', '2025-05-02 14:13:42', '2025-05-03 14:13:42', 0),
(74, 14, 'token_y4z5a6b7c8d9e0f1g2h3', '2025-05-12 14:13:42', '2025-05-13 14:13:42', 0),
(75, 15, 'token_i5j6k7l8m9n0o1p2q3r4', '2025-04-26 14:13:42', '2025-04-27 14:13:42', 1),
(76, 16, 'token_s6t7u8v9w0x1y2z3a4b5', '2025-04-17 14:13:42', '2025-04-18 14:13:42', 0),
(77, 17, 'token_c7d8e9f0g1h2i3j4k5l6', '2025-05-09 14:13:42', '2025-05-10 14:13:42', 0),
(78, 18, 'token_m8n9o0p1q2r3s4t5u6v7', '2025-04-24 14:13:42', '2025-04-25 14:13:42', 1),
(79, 19, 'token_w9x0y1z2a3b4c5d6e7f8', '2025-05-07 14:13:42', '2025-05-08 14:13:42', 0),
(80, 20, 'token_g0h1i2j3k4l5m6n7o8p9', '2025-05-04 14:13:42', '2025-05-05 14:13:42', 0),
(81, 21, 'token_q1r2s3t4u5v6w7x8y9z0', '2025-04-15 14:13:42', '2025-04-16 14:13:42', 1),
(82, 22, 'token_a2b3c4d5e6f7g8h9i0j1', '2025-04-29 14:13:42', '2025-04-30 14:13:42', 0),
(83, 23, 'token_k3l4m5n6o7p8q9r0s1t2', '2025-04-22 14:13:42', '2025-04-23 14:13:42', 0),
(84, 24, 'token_u4v5w6x7y8z9a0b1c2d3', '2025-05-01 14:13:42', '2025-05-02 14:13:42', 1),
(85, 25, 'token_e5f6g7h8i9j0k1l2m3n4', '2025-05-14 14:13:42', '2025-05-15 14:13:42', 0),
(86, 26, 'token_o6p7q8r9s0t1u2v3w4x5', '2025-04-18 14:13:42', '2025-04-19 14:13:42', 0),
(87, 27, 'token_y7z8a9b0c1d2e3f4g5h6', '2025-04-21 14:13:42', '2025-04-22 14:13:42', 1),
(88, 28, 'token_i8j9k0l1m2n3o4p5q6r7', '2025-05-11 14:13:42', '2025-05-12 14:13:42', 0),
(89, 29, 'token_s9t0u1v2w3x4y5z6a7b8', '2025-04-19 14:13:42', '2025-04-20 14:13:42', 1),
(90, 30, 'token_c0d1e2f3g4h5i6j7k8l9', '2025-04-16 14:13:42', '2025-04-17 14:13:42', 0),
(91, 31, 'token_m1n2o3p4q5r6s7t8u9v0', '2025-05-05 14:13:42', '2025-05-06 14:13:42', 0),
(92, 32, 'token_w2x3y4z5a6b7c8d9e0f1', '2025-05-03 14:13:42', '2025-05-04 14:13:42', 1),
(93, 33, 'token_g3h4i5j6k7l8m9n0o1p2', '2025-04-25 14:13:42', '2025-04-26 14:13:42', 0),
(94, 34, 'token_q4r5s6t7u8v9w0x1y2z3', '2025-05-08 14:13:42', '2025-05-09 14:13:42', 0),
(95, 35, 'token_a5b6c7d8e9f0g1h2i3j4', '2025-04-27 14:13:42', '2025-04-28 14:13:42', 1),
(96, 36, 'token_k6l7m8n9o0p1q2r3s4t5', '2025-05-12 14:13:42', '2025-05-13 14:13:42', 0),
(97, 37, 'token_u7v8w9x0y1z2a3b4c5d6', '2025-05-06 14:13:42', '2025-05-07 14:13:42', 0),
(98, 38, 'token_e8f9g0h1i2j3k4l5m6n7', '2025-04-30 14:13:42', '2025-05-01 14:13:42', 1),
(99, 39, 'token_o9p0q1r2s3t4u5v6w7x8', '2025-04-23 14:13:42', '2025-04-24 14:13:42', 0),
(100, 40, 'token_y0z1a2b3c4d5e6f7g8h9', '2025-05-10 14:13:42', '2025-05-11 14:13:42', 0),
(101, 39, 'token_i1j2k3l4m5n6o7p8q9r0', '2025-05-04 14:13:42', '2025-05-05 14:13:42', 1),
(102, 42, 'token_s2t3u4v5w6x7y8z9a0b1', '2025-04-29 14:13:42', '2025-04-30 14:13:42', 0),
(103, 43, 'token_c3d4e5f6g7h8i9j0k1l2', '2025-04-26 14:13:42', '2025-04-27 14:13:42', 0),
(104, 44, 'token_m4n5o6p7q8r9s0t1u2v3', '2025-04-20 14:13:42', '2025-04-21 14:13:42', 1),
(105, 45, 'token_w5x6y7z8a9b0c1d2e3f4', '2025-05-02 14:13:42', '2025-05-03 14:13:42', 0),
(106, 46, 'token_g6h7i8j9k0l1m2n3o4p5', '2025-05-13 14:13:42', '2025-05-14 14:13:42', 0),
(107, 47, 'token_q7r8s9t0u1v2w3x4y5z6', '2025-04-15 14:13:42', '2025-04-16 14:13:42', 1),
(108, 48, 'token_a8b9c0d1e2f3g4h5i6j7', '2025-05-09 14:13:42', '2025-05-10 14:13:42', 0),
(109, 49, 'token_k9l0m1n2o3p4q5r6s7t8', '2025-04-28 14:13:42', '2025-04-29 14:13:42', 0),
(110, 50, 'token_u0v1w2x3y4z5a6b7c8d9', '2025-04-24 14:13:42', '2025-04-25 14:13:42', 1),
(111, 51, 'token_e1f2g3h4i5j6k7l8m9n0', '2025-05-01 14:13:42', '2025-05-02 14:13:42', 0),
(112, 52, 'token_o2p3q4r5s6t7u8v9w0x1', '2025-05-07 14:13:42', '2025-05-08 14:13:42', 0),
(113, 53, 'token_y3z4a5b6c7d8e9f0g1h2', '2025-04-22 14:13:42', '2025-04-23 14:13:42', 1),
(114, 54, 'token_i4j5k6l7m8n9o0p1q2r3', '2025-05-11 14:13:42', '2025-05-12 14:13:42', 0),
(115, 55, 'token_s5t6u7v8w9x0y1z2a3b4', '2025-04-18 14:13:42', '2025-04-19 14:13:42', 0),
(116, 56, 'token_c6d7e8f9g0h1i2j3k4l5', '2025-05-03 14:13:42', '2025-05-04 14:13:42', 1),
(117, 57, 'token_m7n8o9p0q1r2s3t4u5v6', '2025-04-27 14:13:42', '2025-04-28 14:13:42', 0),
(118, 58, 'token_w8x9y0z1a2b3c4d5e6f7', '2025-05-14 14:13:42', '2025-05-15 14:13:42', 0),
(119, 59, 'token_g9h0i1j2k3l4m5n6o7p8', '2025-04-25 14:13:42', '2025-04-26 14:13:42', 1),
(120, 60, 'token_q0r1s2t3u4v5w6x7y8z9', '2025-05-06 14:13:42', '2025-05-07 14:13:42', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico_produto`
--

CREATE TABLE `servico_produto` (
  `quantidade` int(11) DEFAULT NULL,
  `id_produto` int(11) NOT NULL,
  `id_ordem_serv` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servico_produto`
--

INSERT INTO `servico_produto` (`quantidade`, `id_produto`, `id_ordem_serv`) VALUES
(3, 2, 37),
(1, 3, 12),
(5, 3, 28),
(2, 3, 36),
(3, 4, 30),
(2, 4, 40),
(3, 6, 39),
(5, 7, 17),
(5, 7, 19),
(2, 7, 43),
(5, 9, 54),
(2, 10, 11),
(4, 10, 34),
(2, 12, 29),
(4, 15, 23),
(5, 16, 6),
(3, 16, 7),
(4, 17, 2),
(1, 17, 57),
(2, 18, 9),
(2, 18, 30),
(2, 18, 56),
(4, 19, 3),
(2, 19, 5),
(5, 19, 9),
(1, 19, 38),
(2, 20, 31),
(5, 20, 60),
(4, 21, 20),
(4, 22, 5),
(2, 23, 2),
(1, 24, 25),
(3, 24, 45),
(5, 25, 4),
(5, 26, 18),
(1, 26, 26),
(1, 27, 17),
(2, 27, 26),
(2, 28, 3),
(4, 28, 50),
(2, 30, 16),
(4, 30, 33),
(3, 32, 54),
(3, 33, 48),
(5, 33, 49),
(3, 33, 53),
(1, 34, 58),
(3, 35, 19),
(2, 35, 27),
(5, 35, 34),
(4, 35, 46),
(4, 36, 6),
(3, 36, 41),
(2, 37, 14),
(3, 37, 22),
(2, 38, 32),
(4, 40, 49),
(4, 41, 1),
(5, 41, 13),
(1, 41, 14),
(2, 41, 33),
(4, 41, 43),
(5, 42, 10),
(4, 42, 23),
(3, 43, 29),
(2, 43, 60),
(2, 45, 51),
(2, 45, 52),
(3, 46, 8),
(3, 46, 13),
(4, 48, 18),
(2, 48, 35),
(4, 48, 47),
(3, 52, 24),
(4, 53, 44),
(5, 55, 8),
(5, 55, 31),
(2, 55, 45),
(2, 55, 52),
(1, 56, 46),
(4, 57, 25),
(4, 57, 35),
(2, 59, 21),
(3, 59, 42),
(4, 59, 44),
(3, 59, 53),
(2, 59, 55),
(1, 59, 58),
(5, 59, 59),
(3, 60, 11),
(1, 60, 15),
(2, 60, 48);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `nome_completo` varchar(100) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  `senha_temporaria` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `senha`, `cargo`, `nome_completo`, `ativo`, `senha_temporaria`) VALUES
(1, 'omoura@uol.com.br', '$2y$10$Zvagyg9mQK5xrJ1tRYfpyeI4RGBglYnwv2x3bP5EULmWvujmf5.iW', 'Atendente', 'Ana Sophia Barbosa', 1, 0),
(2, 'anthony06@ribeiro.net', '$2y$10$nWDOiJwZNXJps30n6JnWz.JE.KJY9u046jeHUAIc3iKRrvR5BBfY2', 'Técnico', 'Natália Cardoso', 1, 1)