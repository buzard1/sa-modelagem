-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 20, 2025 at 12:53 AM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sa_mobilerepair`
--

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cpf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`cpf`, `telefone`, `nome`, `email`, `endereco`) VALUES
('03975482656', '(084) 6833-9911', 'Ana Oliveira', 'mendesbryan@farias.com', 'Aeroporto Carvalho'),
('06483725108', '+55 (041) 5379 3853', 'Lorenzo Rocha', 'da-rosaana-julia@martins.net', 'Núcleo Daniela Ramos'),
('07956183286', '+55 84 4766-3212', 'Sra. Beatriz Moreira', 'vnogueira@alves.com', 'Estação da Cunha'),
('08413567939', '+55 61 9351 5965', 'Dr. João Lucas Fernandes', 'uda-conceicao@almeida.com', 'Morro Almeida'),
('09378524141', '84 4249 4680', 'Kaique Campos', 'xfernandes@fernandes.com', 'Favela de Cardoso'),
('10459863720', '+55 61 1421-6366', 'Bianca Costela', 'correiapedro@martins.org', 'Vila de Almeida'),
('12.312.312/3123-12', '(12) 31231-2312', 'teste', 'teste@teste', 'asdasd'),
('123', '(12) 31231-2312', 'testess', 'testeste@asdas', 'peidpo anal'),
('12479856011', '+55 41 3834 8547', 'Eduardo Cunha', 'cecilia20@bol.com.br', 'Passarela Davi da Rocha'),
('14506923707', '61 8827-6874', 'Heloísa da Conceição', 'xcardoso@bol.com.br', 'Viaduto Vitor Aragão'),
('14573698264', '+55 (081) 7010 6223', 'Leandro Duarte', 'vitorpinto@alves.org', 'Aeroporto Murilo Araújo'),
('15069273868', '+55 31 6337-7982', 'Davi Lucas Vieira', 'joao-guilhermefogaca@das.com', 'Passarela de Lopes'),
('20439816505', '+55 71 9384-1644', 'Danilo Dias', 'marcos-vinicius91@ig.com.br', 'Fazenda Bruno Almeida'),
('20916547876', '61 3471 3222', 'Davi Lucas Gomes', 'barroscatarina@vieira.br', 'Área Enzo Carvalho'),
('23047651906', '+55 81 5354-1092', 'Elisa Vieira', 'castropietro@hotmail.com', 'Núcleo Mariana da Conceição'),
('23175468071', '+55 (084) 2464-5011', 'Ana Júlia da Mota', 'da-luzdaniela@santos.com', 'Colônia Campos'),
('23478560144', '+55 (031) 8054 3379', 'Luiz Fernando Melo', 'fbarbosa@nunes.net', 'Largo de Carvalho'),
('27890564120', '+55 31 5287 3551', 'Isaac Lopes', 'joao-gabrielda-mata@hotmail.com', 'Vila Sarah Costa'),
('28903647556', '(031) 5462 4580', 'Ana Julia Caldeira', 'alicia07@da.com', 'Área de Melo'),
('31542890624', '(021) 9236-8108', 'Dra. Marina Nunes', 'zda-rosa@hotmail.com', 'Sítio Pietra Peixoto'),
('32085649700', '0900 932 9553', 'Clarice Porto', 'melobruna@uol.com.br', 'Quadra Cunha'),
('35460921716', '+55 (071) 0932-6313', 'Calebe Carvalho', 'peixotobruno@santos.br', 'Rodovia Moraes'),
('37109864510', '+55 71 6601 9874', 'Emanuelly Nascimento', 'goncalvesrafael@ig.com.br', 'Estação de Viana'),
('38125760903', '41 5057-2681', 'Calebe Pereira', 'danilo65@bol.com.br', 'Recanto de Moura'),
('39261874509', '+55 41 3748-9340', 'Agatha Campos', 'benicio16@oliveira.org', 'Estrada Viana'),
('39765042116', '31 9748 6726', 'Enrico Costela', 'luiza14@rezende.net', 'Setor Valentina Jesus'),
('40367289547', '+55 84 0410 8578', 'Amanda Santos', 'dmoreira@gmail.com', 'Conjunto de da Cruz'),
('40796283150', '+55 51 8244 7293', 'Davi Silva', 'vduarte@bol.com.br', 'Largo Santos'),
('47183956237', '+55 (021) 1084-8963', 'Dra. Alice Lopes', 'lorenzomelo@yahoo.com.br', 'Lagoa de Cavalcanti'),
('48352076965', '0300-018-5541', 'Juan Peixoto', 'bruno22@gmail.com', 'Vereda de Souza'),
('49216583746', '0300-155-6549', 'Sarah da Paz', 'benjaminmartins@uol.com.br', 'Campo Aragão'),
('50732496152', '+55 (031) 9290 1760', 'Ana Laura Duarte', 'luiz-otaviosilva@uol.com.br', 'Parque Cunha'),
('51.251.251/2511-52', '(12) 31245-1241', 'testeeee', 'teste@teste1d', 'asdasdadasd'),
('56904728167', '(051) 3062-9559', 'Luana da Conceição', 'joao-lucas72@viana.com', 'Ladeira Sales'),
('57812064930', '(051) 3370-1729', 'Isaac Costela', 'bruno09@da.com', 'Fazenda de Rocha'),
('59784031205', '+55 (011) 8815-1304', 'Marina Silva', 'camposrafaela@bol.com.br', 'Travessa de Correia'),
('62198754355', '0500-159-0783', 'Diogo Viana', 'beatriz61@ig.com.br', 'Fazenda Rodrigues'),
('62594837164', '+55 81 4208-8510', 'Srta. Brenda da Costa', 'maria-cecilia34@farias.com', 'Área de Correia'),
('64721053835', '81 9884-0806', 'Breno Aragão', 'wlopes@uol.com.br', 'Viaduto Almeida'),
('64825379109', '+55 51 9021 6454', 'Enzo Gabriel Novaes', 'da-rosacaue@monteiro.br', 'Trevo Silva'),
('67012385995', '+55 31 3136-2567', 'Luiz Miguel Barros', 'ian79@pereira.org', 'Condomínio da Rosa'),
('67582943074', '(084) 8798-4168', 'Laura da Rosa', 'matheusmartins@hotmail.com', 'Área Pires'),
('67894301213', '+55 71 8691-8204', 'Valentina Nunes', 'jesusmilena@sales.br', 'Sítio Silva'),
('68097531410', '21 4818 3897', 'Lara Cardoso', 'helena25@freitas.br', 'Vila Rodrigues'),
('70216459885', '(051) 8746 5840', 'Ana Lívia Cunha', 'das-nevesbreno@uol.com.br', 'Fazenda Barbosa'),
('70529168430', '+55 84 8222 3799', 'Diego Ribeiro', 'lavinia66@azevedo.com', 'Lagoa de das Neves'),
('71820359603', '21 0902 6040', 'Francisco Aragão', 'monteiropietra@uol.com.br', 'Passarela de Azevedo'),
('72359048104', '0800 161 1206', 'João Gabriel Rodrigues', 'ana-livia53@yahoo.com.br', 'Campo de Oliveira'),
('75189304639', '31 3137-6779', 'Lavínia Gonçalves', 'uda-costa@yahoo.com.br', 'Colônia Lima'),
('75194862067', '0500 317 9814', 'Sophie Barbosa', 'fsilva@nunes.com', 'Alameda de Peixoto'),
('76028451967', '61 8474 1528', 'Marcos Vinicius da Mata', 'mcostela@yahoo.com.br', 'Vale de Silveira'),
('80364275162', '84 0526 9685', 'Manuela Pinto', 'nogueirabryan@pereira.br', 'Lago de Araújo'),
('80957642130', '+55 (051) 4911 9846', 'Thiago Silveira', 'zda-mata@barbosa.br', 'Quadra Azevedo'),
('82407651901', '(084) 7267 0756', 'Davi da Mota', 'gnascimento@barros.com', 'Recanto da Mata'),
('83127640544', '(061) 6723-4653', 'Alana Souza', 'caueda-mota@uol.com.br', 'Alameda Isabel Viana'),
('83154972005', '(031) 2519-1348', 'Bruno da Luz', 'almeidamaria-julia@hotmail.com', 'Travessa Martins'),
('85417026344', '+55 31 9618 2676', 'Ana Clara Duarte', 'da-rochamaria-eduarda@araujo.com', 'Feira Maria Ferreira'),
('85712304635', '21 0430 3220', 'Benjamin Rodrigues', 'biancaporto@bol.com.br', 'Quadra Alves'),
('86352407900', '(061) 1334-3275', 'Júlia da Mota', 'maria-vitoria56@uol.com.br', 'Quadra Maria Eduarda da Rocha'),
('86542390189', '+55 61 9652 2586', 'Sr. Raul Costela', 'yasminvieira@cunha.com', 'Aeroporto Esther Martins'),
('86714093250', '+55 71 3340-6597', 'Bruno Correia', 'egoncalves@bol.com.br', 'Viela Lucca da Costa'),
('90731285441', '(011) 5891 1668', 'Lorena Cardoso', 'crezende@ig.com.br', 'Condomínio Davi Lucca das Neves'),
('97042513606', '61 1871-6668', 'Manuela Campos', 'nmoreira@da.com', 'Viela Nina Sales');

-- --------------------------------------------------------

--
-- Table structure for table `estoque`
--

DROP TABLE IF EXISTS `estoque`;
CREATE TABLE IF NOT EXISTS `estoque` (
  `id_estoque` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_estoque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estoque`
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
-- Table structure for table `fornecedor`
--

DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT,
  `nome_fornecedor` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fornecedor`
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
-- Table structure for table `ordem_serv`
--

DROP TABLE IF EXISTS `ordem_serv`;
CREATE TABLE IF NOT EXISTS `ordem_serv` (
  `id_ordem_serv` int(11) NOT NULL AUTO_INCREMENT,
  `Aparelho` varchar(100) DEFAULT NULL,
  `servico` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `tipo_pagamento` varchar(50) DEFAULT NULL,
  `problema` varchar(255) DEFAULT NULL,
  `idcliente` varchar(20) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `data_entrada` date DEFAULT NULL,
  `data_saida` date DEFAULT NULL,
  PRIMARY KEY (`id_ordem_serv`),
  KEY `idcliente` (`idcliente`),
  KEY `idusuario` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordem_serv`
--

INSERT INTO `ordem_serv` (`id_ordem_serv`, `Aparelho`, `servico`, `status`, `valor`, `tipo_pagamento`, `problema`, `idcliente`, `idusuario`, `data_entrada`, `data_saida`) VALUES
(1, 'Realme GT', 'Reparo de câmera', 'Em andamento', 811.38, 'Dinheiro', 'Tela quebrada', '68097531410', 2, '2024-05-17', NULL),
(2, 'Nokia X30', 'Troca de tela', 'Cancelado', 905.66, 'Pix', 'Botão power travado', '64825379109', 59, '2024-08-02', '2024-08-04'),
(3, 'iPhone SE', 'Troca de botão power', 'Concluído', 651.01, 'Cartão Crédito', 'Câmera não funciona', '20916547876', 10, '2024-08-20', '2024-08-23'),
(4, 'Xiaomi Redmi Note 12', 'Troca de alto-falante', 'Concluído', 790.14, 'Boleto Bancário', 'Sistema lento', '37109864510', 60, '2024-11-17', '2024-11-20'),
(5, 'iPhone SE', 'Troca de alto-falante', 'Em andamento', 795.63, 'Boleto Bancário', 'Microfone mudo', '67582943074', 27, '2025-01-10', NULL),
(6, 'Motorola Edge 30', 'Troca de tela', 'Cancelado', 950.90, 'Pix', 'Sistema lento', '10459863720', 38, '2024-06-06', '2024-06-08'),
(7, 'Samsung Galaxy S21', 'Reparo de câmera', 'Cancelado', 414.70, 'Dinheiro', 'Tela quebrada', '86352407900', 24, '2024-05-17', '2024-05-19'),
(8, 'Nokia X30', 'Desbloqueio de conta', 'Cancelado', 296.33, 'Dinheiro', 'Sistema lento', '48352076965', 31, '2024-06-15', '2024-06-17'),
(9, 'Motorola Edge 30', 'Formatação', 'Cancelado', 984.94, 'Pix', 'Botão power travado', '75189304639', 6, '2025-04-12', '2025-04-14'),
(10, 'iPhone 14', 'Atualização de software', 'Cancelado', 563.98, 'Pix', 'Sistema travando', '70529168430', 49, '2024-11-07', '2024-11-09'),
(11, 'Asus ROG Phone 5', 'Troca de alto-falante', 'Concluído', 258.83, 'Dinheiro', 'Microfone mudo', '59784031205', 24, '2025-01-31', '2025-02-03'),
(12, 'iPhone 13 Mini', 'Atualização de software', 'Em andamento', 187.81, 'Cartão Débito', 'Câmera não funciona', '12479856011', 48, '2025-03-14', NULL),
(13, 'iPhone SE', 'Troca de bateria', 'Cancelado', 340.92, 'Pix', 'Botão power travado', '39261874509', 6, '2025-02-09', '2025-02-11'),
(14, 'Nokia X30', 'Troca de microfone', 'Cancelado', 108.14, 'Boleto Bancário', 'Sistema lento', '80957642130', 40, '2025-04-24', '2025-04-26'),
(15, 'Xiaomi Poco X5 Pro', 'Formatação', 'Concluído', 567.69, 'Boleto Bancário', 'Conta Google bloqueada', '97042513606', 23, '2024-09-13', '2024-09-16'),
(16, 'Realme GT', 'Troca de botão power', 'Cancelado', 762.81, 'Boleto Bancário', 'Tela quebrada', '76028451967', 18, '2024-10-02', '2024-10-04'),
(17, 'iPhone 13 Mini', 'Desbloqueio de conta', 'Cancelado', 522.50, 'Pix', 'Sistema lento', '14573698264', 7, '2025-04-02', '2025-04-04'),
(18, 'Xiaomi Poco X5 Pro', 'Troca de tela', 'Concluído', 961.96, 'Dinheiro', 'Câmera não funciona', '31542890624', 36, '2024-09-06', '2024-09-09'),
(19, 'Realme GT', 'Substituição de conector de carga', 'Em andamento', 980.47, 'Cartão Crédito', 'Sistema lento', '39765042116', 60, '2024-07-28', NULL),
(20, 'Nokia X30', 'Atualização de software', 'Em andamento', 750.86, 'Boleto Bancário', 'Microfone mudo', '47183956237', 28, '2024-04-28', NULL),
(21, 'OnePlus Nord CE', 'Troca de microfone', 'Em andamento', 100.11, 'Boleto Bancário', 'Microfone mudo', '38125760903', 42, '2024-06-15', NULL),
(22, 'iPhone 14', 'Troca de alto-falante', 'Concluído', 900.75, 'Cartão Crédito', 'Conta Google bloqueada', '72359048104', 10, '2024-07-19', '2024-07-22'),
(23, 'Nokia X30', 'Troca de tela', 'Em andamento', 471.33, 'Cartão Débito', 'Conector de carga danificado', '64721053835', 11, '2025-02-17', NULL),
(24, 'iPhone 14', 'Formatação', 'Cancelado', 652.27, 'Dinheiro', 'Sistema travando', '40367289547', 22, '2025-04-21', '2025-04-23'),
(25, 'OnePlus Nord CE', 'Troca de alto-falante', 'Concluído', 957.98, 'Boleto Bancário', 'Conector de carga danificado', '85417026344', 11, '2024-12-16', '2024-12-19'),
(26, 'iPhone 13 Mini', 'Atualização de software', 'Concluído', 707.75, 'Cartão Débito', 'Conector de carga danificado', '75194862067', 47, '2024-06-15', '2024-06-18'),
(27, 'OnePlus Nord CE', 'Desbloqueio de conta', 'Em andamento', 747.28, 'Boleto Bancário', 'Microfone mudo', '49216583746', 56, '2024-05-27', NULL),
(28, 'Motorola Edge 30', 'Desbloqueio de conta', 'Cancelado', 817.93, 'Dinheiro', 'Conector de carga danificado', '35460921716', 49, '2024-07-11', '2024-07-13'),
(29, 'iPhone SE', 'Troca de tela', 'Cancelado', 994.83, 'Cartão Crédito', 'Tela quebrada', '20439816505', 41, '2024-09-01', '2024-09-03'),
(30, 'Asus ROG Phone 5', 'Substituição de conector de carga', 'Em andamento', 768.11, 'Dinheiro', 'Sistema lento', '62198754355', 7, '2024-07-03', NULL),
(31, 'Xiaomi Redmi Note 12', 'Troca de microfone', 'Em andamento', 178.60, 'Cartão Crédito', 'Conector de carga danificado', '23175468071', 35, '2024-10-17', NULL),
(32, 'Nokia X30', 'Troca de microfone', 'Em andamento', 605.92, 'Dinheiro', 'Conector de carga danificado', '32085649700', 1, '2025-01-13', NULL),
(33, 'Xiaomi Redmi Note 12', 'Reparo de câmera', 'Cancelado', 723.94, 'Dinheiro', 'Câmera não funciona', '08413567939', 52, '2025-01-03', '2025-01-05'),
(34, 'OnePlus Nord CE', 'Troca de alto-falante', 'Em andamento', 924.18, 'Cartão Débito', 'Botão power travado', '06483725108', 53, '2024-06-20', NULL),
(35, 'Samsung Galaxy S21', 'Atualização de software', 'Cancelado', 401.00, 'Boleto Bancário', 'Alto-falante chiando', '27890564120', 46, '2025-02-01', '2025-02-03'),
(36, 'Asus ROG Phone 5', 'Troca de bateria', 'Em andamento', 788.84, 'Cartão Crédito', 'Tela quebrada', '03975482656', 28, '2024-12-23', NULL),
(37, 'iPhone 13 Mini', 'Troca de microfone', 'Em andamento', 118.31, 'Cartão Débito', 'Sistema travando', '15069273868', 9, '2024-06-01', NULL),
(38, 'iPhone 13 Mini', 'Troca de bateria', 'Cancelado', 248.22, 'Boleto Bancário', 'Microfone mudo', '14506923707', 38, '2024-05-20', '2024-05-22'),
(39, 'Asus ROG Phone 5', 'Troca de alto-falante', 'Concluído', 289.58, 'Cartão Crédito', 'Sistema travando', '83127640544', 39, '2024-06-09', '2024-06-12'),
(40, 'Nokia X30', 'Troca de botão power', 'Concluído', 421.29, 'Boleto Bancário', 'Conta Google bloqueada', '23047651906', 39, '2024-07-10', '2024-07-13'),
(41, 'Samsung Galaxy A54', 'Formatação', 'Em andamento', 699.16, 'Boleto Bancário', 'Botão power travado', '67012385995', 34, '2024-07-29', NULL),
(42, 'Samsung Galaxy S21', 'Substituição de conector de carga', 'Cancelado', 159.51, 'Cartão Débito', 'Tela quebrada', '70216459885', 57, '2024-09-03', '2024-09-05'),
(43, 'LG K62+', 'Atualização de software', 'Cancelado', 865.37, 'Pix', 'Microfone mudo', '57812064930', 23, '2024-09-13', '2024-09-15'),
(44, 'iPhone 13 Mini', 'Troca de microfone', 'Cancelado', 386.02, 'Boleto Bancário', 'Sistema travando', '67894301213', 46, '2025-03-16', '2025-03-18'),
(45, 'Samsung Galaxy S21', 'Desbloqueio de conta', 'Cancelado', 322.12, 'Dinheiro', 'Sistema lento', '62594837164', 36, '2024-06-27', '2024-06-29'),
(46, 'LG K62+', 'Troca de microfone', 'Concluído', 329.11, 'Boleto Bancário', 'Bateria descarregando rápido', '50732496152', 50, '2024-09-28', '2024-10-01'),
(47, 'Motorola Edge 30', 'Troca de tela', 'Em andamento', 961.59, 'Dinheiro', 'Sistema travando', '86542390189', 58, '2024-11-05', NULL),
(48, 'Samsung Z Flip', 'Troca de microfone', 'Em andamento', 555.95, 'Boleto Bancário', 'Sistema travando', '80364275162', 44, '2024-07-30', NULL),
(49, 'iPhone SE', 'Substituição de conector de carga', 'Concluído', 786.34, 'Cartão Crédito', 'Câmera não funciona', '40796283150', 57, '2024-09-08', '2024-09-11'),
(50, 'Nokia X30', 'Troca de alto-falante', 'Cancelado', 120.17, 'Pix', 'Câmera não funciona', '23478560144', 19, '2024-10-13', '2024-10-15'),
(51, 'iPhone 14', 'Atualização de software', 'Concluído', 770.08, 'Dinheiro', 'Conta Google bloqueada', '85712304635', 18, '2025-01-12', '2025-01-15'),
(52, 'Xiaomi Redmi Note 12', 'Formatação', 'Concluído', 481.50, 'Boleto Bancário', 'Conector de carga danificado', '56904728167', 51, '2024-08-02', '2024-08-05'),
(53, 'Samsung Galaxy A54', 'Substituição de conector de carga', 'Cancelado', 516.58, 'Cartão Débito', 'Sistema lento', '83154972005', 12, '2025-03-08', '2025-03-10'),
(54, 'Samsung Galaxy A54', 'Reparo de câmera', 'Concluído', 461.20, 'Dinheiro', 'Microfone mudo', '09378524141', 57, '2024-04-26', '2024-04-29'),
(55, 'Asus ROG Phone 5', 'Reparo de câmera', 'Cancelado', 282.86, 'Cartão Crédito', 'Microfone mudo', '71820359603', 2, '2025-03-15', '2025-03-17'),
(56, 'Asus ROG Phone 5', 'Formatação', 'Cancelado', 283.44, 'Pix', 'Botão power travado', '86714093250', 35, '2024-10-15', '2024-10-17'),
(57, 'Samsung Galaxy A54', 'Reparo de câmera', 'Cancelado', 560.07, 'Boleto Bancário', 'Câmera não funciona', '90731285441', 57, '2024-06-30', '2024-07-02'),
(58, 'Samsung Galaxy A54', 'Substituição de conector de carga', 'Cancelado', 134.06, 'Boleto Bancário', 'Câmera não funciona', '28903647556', 31, '2024-06-24', '2024-06-26'),
(59, 'Samsung Z Flip', 'Formatação', 'Em andamento', 553.54, 'Cartão Crédito', 'Bateria descarregando rápido', '07956183286', 49, '2024-10-06', NULL),
(60, 'OnePlus Nord CE', 'Troca de alto-falante', 'Concluído', 924.93, 'Pix', 'Conector de carga danificado', '82407651901', 43, '2024-09-23', '2024-09-26');

-- --------------------------------------------------------

--
-- Table structure for table `produto`
--

DROP TABLE IF EXISTS `produto`;
CREATE TABLE IF NOT EXISTS `produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(100) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `idfornecedor` int(11) DEFAULT NULL,
  `idestoque` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `idfornecedor` (`idfornecedor`),
  KEY `idestoque` (`idestoque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produto`
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
-- Table structure for table `recover_senha`
--

DROP TABLE IF EXISTS `recover_senha`;
CREATE TABLE IF NOT EXISTS `recover_senha` (
  `id_recover` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_expiracao` datetime NOT NULL,
  `utilizado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_recover`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recover_senha`
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
(101, 41, 'token_i1j2k3l4m5n6o7p8q9r0', '2025-05-04 14:13:42', '2025-05-05 14:13:42', 1),
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
-- Table structure for table `servico_produto`
--

DROP TABLE IF EXISTS `servico_produto`;
CREATE TABLE IF NOT EXISTS `servico_produto` (
  `quantidade` int(11) DEFAULT NULL,
  `id_produto` int(11) NOT NULL,
  `id_ordem_serv` int(11) NOT NULL,
  PRIMARY KEY (`id_produto`,`id_ordem_serv`),
  KEY `id_ordem_serv` (`id_ordem_serv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `servico_produto`
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
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `nome_completo` varchar(100) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `senha`, `cargo`, `nome_completo`, `ativo`) VALUES
(1, 'omoura@uol.com.br', 'O6QnRUdm(w', 'Atendente', 'Ana Sophia Barbosa', 1),
(2, 'anthony06@ribeiro.net', '6H4Z(!Qj&7', 'Técnico', 'Natália Cardoso', 1),
(3, 'mbarros@cardoso.br', '123', 'Tecnico', 'Sr. Rodrigo Almeida', 1),
(4, 'anthony34@gmail.com', '123', 'Gerente', 'Luana Novaes', 1),
(5, 'joana00@yahoo.com.br', '^$2f^9duRM', 'Atendente', 'Alícia Duarte', 1),
(6, 'ninajesus@yahoo.com.br', 'KD!7iUuuw%', 'Atendente', 'Vitor Gabriel da Conceição', 1),
(7, 'marinamendes@melo.com', '(5%Mfp_Ku)', 'Gerente', 'Agatha Santos', 1),
(8, 'vicente76@da.br', '123', 'Tecnico', 'Marcos Vinicius Caldeira', 1),
(9, 'ana-carolina32@cardoso.com', 'j$9xrAm)HR', 'Atendente', 'Dr. João Mendes', 1),
(10, 'fogacaalice@hotmail.com', '(3$WXho!2!', 'Vendedor', 'João Guilherme Campos', 1),
(11, 'ngoncalves@ig.com.br', '8c0hYt6y*W', 'Técnico', 'João Vitor Alves', 1),
(12, 'clara70@cavalcanti.org', 'wq1StCD_5_', 'Vendedor', 'Heitor Cardoso', 1),
(13, 'sbarros@yahoo.com.br', 'i^%E8ZbrfI', 'Técnico', 'Ana Beatriz da Cruz', 1),
(14, 'gribeiro@uol.com.br', 'r(P25Hz@n8', 'Vendedor', 'Calebe da Rocha', 1),
(15, 'emanuel22@ig.com.br', 'j5DhO5Mt*j', 'Vendedor', 'Augusto da Rocha', 1),
(16, 'da-rosafrancisco@gmail.com', '#FB58Kxa%x', 'Vendedor', 'Gabriel Pires', 1),
(17, 'pintoleticia@souza.br', '^11jMexp$v', 'Gerente', 'Dr. Benício Nogueira', 1),
(18, 'pietraviana@sales.com', 'w@1rIXk$mp', 'Gerente', 'Sophie Caldeira', 1),
(19, 'vcavalcanti@dias.net', '$%$0V9g^cR', 'Vendedor', 'Eduarda Peixoto', 1),
(20, 'pereiramaria-alice@peixoto.org', 'S8_4SYx$S$', 'Gerente', 'Alana da Paz', 1),
(21, 'rezendeenzo@hotmail.com', '_X99LBOuqv', 'Técnico', 'Helena da Luz', 1),
(22, 'erick12@caldeira.br', 'G5NUU$+g@K', 'Atendente', 'Dr. Leandro Gonçalves', 1),
(23, 'breno28@fogaca.br', '(!OdiMrLF7', 'Gerente', 'Luana Castro', 1),
(24, 'cferreira@bol.com.br', '$2GwHJu1OW', 'Técnico', 'Joaquim Vieira', 1),
(25, 'santosnatalia@ig.com.br', '#N^3fV1l6t', 'Técnico', 'Vitor Gabriel da Rosa', 1),
(26, 'davi-luccasouza@hotmail.com', 'T@30uDWg*$', 'Vendedor', 'Giovanna Azevedo', 1),
(27, 'erocha@uol.com.br', '@a)HAg)rM2', 'Gerente', 'Igor da Rocha', 1),
(28, 'guilhermeoliveira@viana.org', ')m2wWkDfu(', 'Atendente', 'Ana Vitória Alves', 1),
(29, 'moreiragabrielly@yahoo.com.br', ')7E7BYvm_c', 'Gerente', 'Ana Laura Pereira', 1),
(30, 'gabriellynascimento@hotmail.com', '7qRMcjWw%l', 'Atendente', 'Murilo Caldeira', 1),
(31, 'wdias@hotmail.com', 'G2^HDzd8_W', 'Gerente', 'Sofia Castro', 1),
(32, 'silvajuan@jesus.br', '0OMfm_8l#r', 'Atendente', 'Mariane Correia', 1),
(33, 'ida-rocha@novaes.com', '#01o0VpRk+', 'Atendente', 'Miguel Alves', 1),
(34, 'costakevin@ig.com.br', 'V7B77Erw#D', 'Gerente', 'Dra. Yasmin Fernandes', 1),
(35, 'moreiraagatha@hotmail.com', 'KrX(2Em_$j', 'Atendente', 'Pedro Alves', 1),
(36, 'rochajuan@uol.com.br', 'O&fC0K$zt^', 'Técnico', 'Sra. Ana Martins', 1),
(37, 'ferreirakaique@gmail.com', 'i7Phl^N7@F', 'Técnico', 'Luiz Fernando Gonçalves', 1),
(38, 'valves@moreira.br', 'D&q4Me*xO)', 'Gerente', 'Vitória Vieira', 1),
(39, 'yuri06@pereira.com', 'E31Dh#yi!%', 'Gerente', 'Maria Vitória Souza', 1),
(40, 'joaquim03@pinto.br', '%2I6T4fsdH', 'Técnico', 'Thomas Alves', 1),
(41, 'lunamelo@uol.com.br', '2)GMJ7kI)7', 'Gerente', 'Caio Dias', 1),
(42, 'lorenacosta@porto.net', 'x9Nk0SQI!B', 'Vendedor', 'Melissa Moura', 1),
(43, 'vmoura@nunes.com', '7nKUZXhm(%', 'Vendedor', 'Kaique Correia', 1),
(44, 'isabellyteixeira@ig.com.br', 'J+445CT8Ik', 'Técnico', 'Elisa Cardoso', 1),
(45, 'vianarenan@viana.org', 'i6!2ShUY^$', 'Gerente', 'Srta. Letícia Lopes', 1),
(46, 'oliveiramurilo@oliveira.com', 'G&!mn0tEyQ', 'Técnico', 'Ryan Viana', 1),
(47, 'enzo-gabriel84@barros.com', 'o0(xBbQQ)#', 'Técnico', 'Danilo Cardoso', 1),
(48, 'gpereira@bol.com.br', '@4x!xuewfD', 'Vendedor', 'Bruno Costela', 1),
(49, 'qmoreira@fogaca.net', 'lLi8RZ&s7%', 'Técnico', 'Sofia Duarte', 1),
(50, 'julia99@vieira.net', '4lnC8FBh!3', 'Gerente', 'Guilherme Souza', 1),
(51, 'heloisaoliveira@alves.com', '(1R3Ci@DOq', 'Técnico', 'Breno Cardoso', 1),
(52, 'gpereira@pereira.br', '*4Ie#+beHJ', 'Atendente', 'Srta. Marcela da Rosa', 1),
(53, 'nferreira@uol.com.br', 'bO85+J2g2)', 'Técnico', 'Gustavo Henrique das Neves', 1),
(54, 'ninacosta@uol.com.br', 'E_I8lOkvEu', 'Vendedor', 'Emilly Sales', 1),
(55, 'giovanna58@hotmail.com', ')5U_9joiUa', 'Vendedor', 'Stella Ferreira', 1),
(56, 'ysouza@duarte.com', ')12cOmk@ws', 'Técnico', 'Maria Eduarda Peixoto', 1),
(57, 'ana-livia10@bol.com.br', 'Ny_5S1Csi&', 'Técnico', 'Laís Freitas', 1),
(58, 'ana-vitoria90@gmail.com', 'vjB6I1qA(_', 'Técnico', 'Lucas Duarte', 1),
(59, 'raul66@da.br', '#Tv@ZyxtX5', 'Vendedor', 'Maria Fernanda Farias', 1),
(60, 'da-conceicaocaua@hotmail.com', 'Lr8zI7sc^W', 'Técnico', 'Bernardo Pinto', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ordem_serv`
--
ALTER TABLE `ordem_serv`
  ADD CONSTRAINT `idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `ordem_serv_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`cpf`);

--
-- Constraints for table `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`idfornecedor`) REFERENCES `fornecedor` (`id_fornecedor`),
  ADD CONSTRAINT `produto_ibfk_2` FOREIGN KEY (`idestoque`) REFERENCES `estoque` (`id_estoque`);

--
-- Constraints for table `recover_senha`
--
ALTER TABLE `recover_senha`
  ADD CONSTRAINT `id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Constraints for table `servico_produto`
--
ALTER TABLE `servico_produto`
  ADD CONSTRAINT `servico_produto_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `servico_produto_ibfk_2` FOREIGN KEY (`id_ordem_serv`) REFERENCES `ordem_serv` (`id_ordem_serv`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
