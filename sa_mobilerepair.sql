-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/09/2025 às 21:32
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
('000.111.222-33', '(51) 90011-2233', 'Clara Souza', 'clara.souza@email.com', 'Av. Borges de Medeiros, 1717, Porto Alegre, RS'),
('001.122.334-45', '(51) 90012-2345', 'Helena Oliveira', 'helena.oliveira@email.com', 'Av. Goethe, 2727, Porto Alegre, RS'),
('001.122.445-56', '(51) 90012-4455', 'Letícia Oliveira', 'leticia.oliveira@email.com', 'Av. Carlos Gomes, 3737, Porto Alegre, RS'),
('001.122.556-67', '(51) 90012-5566', 'Júlia Oliveira', 'julia.oliveira@email.com', 'Av. Nilo Peçanha, 4747, Porto Alegre, RS'),
('001.122.667-78', '(51) 90012-6677', 'Natália Oliveira', 'natalia.oliveira@email.com', 'Av. Osvaldo Aranha, 5757, Porto Alegre, RS'),
('012.345.678-90', '(51) 90123-4567', 'Mariana Rocha', 'mariana.rocha@email.com', 'Av. Ipiranga, 707, Porto Alegre, RS'),
('111.222.333-44', '(11) 91122-3344', 'Felipe Mendes', 'felipe.mendes@email.com', 'Rua Augusta, 808, São Paulo, SP'),
('112.233.445-56', '(11) 91223-3445', 'Eduardo Pereira', 'eduardo.pereira@email.com', 'Rua Oscar Freire, 1818, São Paulo, SP'),
('112.233.556-67', '(11) 91223-5566', 'Rafael Mendes', 'rafael.mendes2@email.com', 'Rua Haddock Lobo, 2828, São Paulo, SP'),
('112.233.667-78', '(11) 91223-6677', 'André Mendes', 'andre.mendes@email.com', 'Rua Bela Cintra, 3838, São Paulo, SP'),
('112.233.778-89', '(11) 91223-7788', 'Caio Mendes', 'caio.mendes@email.com', 'Rua Consolação, 4848, São Paulo, SP'),
('123.456.789-01', '(11) 91234-5678', 'João Silva', 'joao.silva@email.com', 'Rua das Flores, 123, São Paulo, SP'),
('222.333.444-55', '(11) 92233-4455', 'Camila Ribeiro', 'camila.ribeiro@email.com', 'Av. Paulista, 909, São Paulo, SP'),
('223.344.556-67', '(11) 92334-4556', 'Sofia Almeida', 'sofia.almeida@email.com', 'Av. Faria Lima, 1919, São Paulo, SP'),
('223.344.667-78', '(11) 92334-6677', 'Marina Costa', 'marina.costa@email.com', 'Av. Rebouças, 2929, São Paulo, SP'),
('223.344.778-89', '(11) 92334-7788', 'Vitória Costa', 'vitoria.costa@email.com', 'Av. Brigadeiro Faria Lima, 3939, São Paulo, SP'),
('223.344.889-90', '(11) 92334-8899', 'Aline Costa', 'aline.costa@email.com', 'Av. Morumbi, 4949, São Paulo, SP'),
('234.567.890-12', '(11) 92345-6789', 'Maria Oliveira', 'maria.oliveira@email.com', 'Av. Brasil, 456, São Paulo, SP'),
('333.444.555-66', '(21) 93344-5566', 'Thiago Gonçalves', 'thiago.goncalves@email.com', 'Rua Copacabana, 1010, Rio de Janeiro, RJ'),
('334.455.667-78', '(21) 93445-5678', 'Henrique Silva', 'henrique.silva@email.com', 'Rua Ipanema, 2020, Rio de Janeiro, RJ'),
('334.455.778-89', '(21) 93445-7788', 'Leonardo Silva', 'leonardo.silva@email.com', 'Rua Leblon, 3030, Rio de Janeiro, RJ'),
('334.455.889-90', '(21) 93445-8899', 'Igor Silva', 'igor.silva@email.com', 'Rua Barra da Tijuca, 4040, Rio de Janeiro, RJ'),
('334.455.990-01', '(21) 93445-9900', 'Vitor Silva', 'vitor.silva@email.com', 'Rua Gávea, 5050, Rio de Janeiro, RJ'),
('345.678.901-23', '(21) 93456-7890', 'Pedro Santos', 'pedro.santos@email.com', 'Rua do Sol, 789, Rio de Janeiro, RJ'),
('444.555.666-77', '(21) 94455-6677', 'Beatriz Almeida', 'beatriz.almeida@email.com', 'Av. Vieira Souto, 1111, Rio de Janeiro, RJ'),
('445.566.001-12', '(21) 94556-0011', 'Luana Almeida', 'luana.almeida@email.com', 'Av. Bartolomeu Mitre, 5151, Rio de Janeiro, RJ'),
('445.566.778-89', '(21) 94556-6789', 'Lívia Costa', 'livia.costa@email.com', 'Av. Delfim Moreira, 2121, Rio de Janeiro, RJ'),
('445.566.889-90', '(21) 94556-8899', 'Valentina Almeida', 'valentina.almeida@email.com', 'Av. Niemeyer, 3131, Rio de Janeiro, RJ'),
('445.566.990-01', '(21) 94556-9900', 'Cecília Almeida', 'cecilia.almeida@email.com', 'Av. das Américas, 4141, Rio de Janeiro, RJ'),
('456.789.012-34', '(21) 94567-8901', 'Ana Costa', 'ana.costa@email.com', 'Av. Atlântica, 101, Rio de Janeiro, RJ'),
('555.666.777-88', '(31) 95566-7788', 'Gabriel Costa', 'gabriel.costa@email.com', 'Rua Savassi, 1212, Belo Horizonte, MG'),
('556.677.001-12', '(31) 95667-0011', 'Enzo Santos', 'enzo.santos@email.com', 'Rua Mangabeiras, 4242, Belo Horizonte, MG'),
('556.677.112-23', '(31) 95667-1122', 'Nicolas Santos', 'nicolas.santos@email.com', 'Rua Ouro Preto, 5252, Belo Horizonte, MG'),
('556.677.889-90', '(31) 95667-7890', 'Mateus Santos', 'mateus.santos@email.com', 'Rua Pampulha, 2222, Belo Horizonte, MG'),
('556.677.990-01', '(31) 95667-9900', 'Gustavo Santos', 'gustavo.santos@email.com', 'Rua Belvedere, 3232, Belo Horizonte, MG'),
('567.890.123-45', '(31) 95678-9012', 'Lucas Pereira', 'lucas.pereira@email.com', 'Rua das Acácias, 202, Belo Horizonte, MG'),
('666.777.888-99', '(31) 96677-8899', 'Isabela Santos', 'isabela.santos@email.com', 'Av. Cristóvão Colombo, 1313, Belo Horizonte, MG'),
('667.788.001-12', '(31) 96778-0011', 'Manuela Lima', 'manuela.lima@email.com', 'Av. Raja Gabaglia, 3333, Belo Horizonte, MG'),
('667.788.112-23', '(31) 96778-1122', 'Lara Lima', 'lara.lima@email.com', 'Av. Antônio Carlos, 4343, Belo Horizonte, MG'),
('667.788.223-34', '(31) 96778-2233', 'Mirela Lima', 'mirela.lima@email.com', 'Av. Barão Homem de Melo, 5353, Belo Horizonte, MG'),
('667.788.990-01', '(31) 96778-8901', 'Larissa Lima', 'larissa.lima@email.com', 'Av. Amazonas, 2323, Belo Horizonte, MG'),
('678.901.234-56', '(31) 96789-0123', 'Fernanda Lima', 'fernanda.lima@email.com', 'Av. Afonso Pena, 303, Belo Horizonte, MG'),
('777.888.999-00', '(41) 97788-9900', 'Diego Lima', 'diego.lima@email.com', 'Rua das Palmeiras, 1414, Curitiba, PR'),
('778.899.001-12', '(41) 97889-9012', 'Bruno Ferreira', 'bruno.ferreira@email.com', 'Rua Comendador Araújo, 2424, Curitiba, PR'),
('778.899.112-23', '(41) 97889-1122', 'Rodrigo Ferreira', 'rodrigo.ferreira@email.com', 'Rua Barão do Rio Branco, 3434, Curitiba, PR'),
('778.899.223-34', '(41) 97889-2233', 'Fábio Ferreira', 'fabio.ferreira@email.com', 'Rua Presidente Faria, 4444, Curitiba, PR'),
('778.899.334-45', '(41) 97889-3344', 'Hugo Ferreira', 'hugo.ferreira@email.com', 'Rua Mateus Leme, 5454, Curitiba, PR'),
('789.012.345-67', '(41) 97890-1234', 'Rafael Almeida', 'rafael.almeida@email.com', 'Rua XV de Novembro, 404, Curitiba, PR'),
('888.999.000-11', '(41) 98899-0011', 'Laura Ferreira', 'laura.ferreira@email.com', 'Av. Sete de Setembro, 1515, Curitiba, PR'),
('889.900.112-23', '(41) 98990-0123', 'Alice Ribeiro', 'alice.ribeiro@email.com', 'Av. Manoel Ribas, 2525, Curitiba, PR'),
('889.900.223-34', '(41) 98990-2233', 'Bianca Ribeiro', 'bianca.ribeiro@email.com', 'Av. Vicente Machado, 3535, Curitiba, PR'),
('889.900.334-45', '(41) 98990-3344', 'Elisa Ribeiro', 'elisa.ribeiro@email.com', 'Av. Iguaçu, 4545, Curitiba, PR'),
('889.900.445-56', '(41) 98990-4455', 'Lorena Ribeiro', 'lorena.ribeiro@email.com', 'Av. João Gualberto, 5555, Curitiba, PR'),
('890.123.456-78', '(41) 98901-2345', 'Juliana Ferreira', 'juliana.ferreira@email.com', 'Av. Batel, 505, Curitiba, PR'),
('901.234.567-89', '(51) 99012-3456', 'Carlos Souza', 'carlos.souza@email.com', 'Rua da Praia, 606, Porto Alegre, RS'),
('990.011.223-34', '(51) 99001-1234', 'Guilherme Souza', 'guilherme.souza@email.com', 'Rua Padre Chagas, 2626, Porto Alegre, RS'),
('990.011.334-45', '(51) 99001-3344', 'Daniel Souza', 'daniel.souza@email.com', 'Rua Mostardeiro, 3636, Porto Alegre, RS'),
('990.011.445-56', '(51) 99001-4455', 'Samuel Souza', 'samuel.souza@email.com', 'Rua Protásio Alves, 4646, Porto Alegre, RS'),
('990.011.556-67', '(51) 99001-5566', 'Marcos Souza', 'marcos.souza@email.com', 'Rua Ramiro Barcelos, 5656, Porto Alegre, RS'),
('999.000.111-22', '(51) 99900-1122', 'Vinicius Oliveira', 'vinicius.oliveira@email.com', 'Rua dos Andradas, 1616, Porto Alegre, RS');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id_estoque` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id_estoque`, `quantidade`) VALUES
(1, 50),
(2, 30),
(3, 20),
(4, 100),
(5, 15),
(6, 25),
(7, 40),
(8, 60),
(9, 10),
(10, 80),
(11, 45),
(12, 35),
(13, 55),
(14, 70),
(15, 90),
(16, 12),
(17, 18),
(18, 22),
(19, 28),
(20, 32),
(21, 38),
(22, 42),
(23, 48),
(24, 52),
(25, 58),
(26, 62),
(27, 68),
(28, 72),
(29, 78),
(30, 82),
(31, 88),
(32, 92),
(33, 98),
(34, 14),
(35, 16),
(36, 24),
(37, 26),
(38, 34),
(39, 36),
(40, 44),
(41, 46),
(42, 54),
(43, 56),
(44, 64),
(45, 66),
(46, 74),
(47, 76),
(48, 84),
(49, 86),
(50, 94),
(51, 96),
(52, 8),
(53, 6),
(54, 4),
(55, 2),
(56, 0),
(57, 5),
(58, 3),
(59, 7),
(60, 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `cnpj` varchar(20) NOT NULL,
  `nome_fornecedor` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`cnpj`, `nome_fornecedor`, `telefone`, `email`) VALUES
('00.000.000/0001-81', 'Peças Nacionais', '(51) 90123-4567', 'nacional@pecas.com'),
('00.011.122/0001-71', 'Nacional Auto', '(51) 90011-2233', 'nacional@auto.com'),
('00.112.233/0001-61', 'Nacional RS', '(51) 90012-2345', 'rs@nacional.com'),
('00.112.244/0001-51', 'Peças PR', '(51) 90012-4455', 'pr@pecaspr.com'),
('00.112.255/0001-41', 'Componentes MG', '(51) 90012-5566', 'mg@componentesmg.com'),
('00.112.266/0001-31', 'Peças Sul', '(51) 90012-6677', 'sul@pecassul3.com'),
('11.122.233/0001-80', 'Fornecedora SP', '(11) 91122-3344', 'sp@fornecedora.com'),
('11.223.344/0001-70', 'Auto SP', '(11) 91223-3445', 'sp@autosp.com'),
('11.223.355/0001-60', 'Auto PR', '(11) 91223-5566', 'pr@autopr.com'),
('11.223.366/0001-50', 'Distribuidora RS', '(11) 91223-6677', 'rs@distribuidorars.com'),
('11.223.377/0001-40', 'Auto Sul', '(11) 91223-7788', 'sul@autosul2.com'),
('12.312.312/3123-12', 'Barbosa - EI', '(55) 51995-8961', 'araujobruna@pinto.br'),
('12.345.678/0001-90', 'Auto Peças LTDA', '(11) 91234-5678', 'contato@autopecas.com'),
('22.233.344/0001-79', 'Auto Rio', '(11) 92233-4455', 'rio@auto.com'),
('22.334.455/0001-69', 'Peças RJ', '(11) 92334-4556', 'rj@pecas.com'),
('22.334.466/0001-59', 'Peças SP', '(11) 92334-6677', 'sp@pecassp2.com'),
('22.334.477/0001-49', 'Componentes SP', '(11) 92334-7788', 'sp@componentessp.com'),
('22.334.488/0001-39', 'Peças RS', '(11) 92334-8899', 'rs@pecasrs2.com'),
('23.456.789/0001-89', 'Fornecedora Brasil', '(11) 92345-6789', 'vendas@fornecedora.com'),
('33.344.455/0001-78', 'Peças MG', '(21) 93344-5566', 'mg@pecas.com'),
('33.445.566/0001-68', 'Distribuidora MG', '(21) 93445-5678', 'mg@distribuidora.com'),
('33.445.577/0001-58', 'Distribuidora RJ', '(21) 93445-7788', 'rj@distribuidora.com'),
('33.445.588/0001-48', 'Auto MG', '(21) 93445-8899', 'mg@automg.com'),
('33.445.599/0001-38', 'SP Import', '(21) 93445-9900', 'sp@importsp.com'),
('34.567.890/0001-88', 'Peças Rio', '(21) 93456-7890', 'rio@pecasrio.com'),
('44.455.566/0001-77', 'Distribuidora PR', '(21) 94455-6677', 'pr@distribuidora.com'),
('44.556.600/0001-37', 'Minas Peças', '(21) 94556-0011', 'minas@pecasminas3.com'),
('44.556.677/0001-67', 'Componentes PR', '(21) 94556-6789', 'pr@componentes.com'),
('44.556.688/0001-57', 'Componentes MG', '(21) 94556-8899', 'mg@componentes.com'),
('44.556.699/0001-47', 'Peças Sul', '(21) 94556-9900', 'sul@pecassul2.com'),
('45.678.900/0001-87', 'Distribuidora Sul', '(21) 94567-8901', 'sul@distribuidora.com'),
('55.566.777/0001-76', 'Componentes RS', '(31) 95566-7788', 'rs@componentesrs.com'),
('55.667.711/0001-36', 'Auto RJ', '(31) 95667-1122', 'rj@autorj2.com'),
('55.667.788/0001-66', 'Auto RS', '(31) 95667-7890', 'rs@autors.com'),
('55.667.799/0001-56', 'Auto Sul', '(31) 95667-9900', 'sul@autosul.com'),
('55.667.800/0001-46', 'Importadora PR', '(31) 95667-0011', 'pr@importadorapr.com'),
('56.789.000/0001-86', 'Minas Auto', '(31) 95678-9012', 'minas@auto.com'),
('66.677.788/0001-75', 'Auto Minas', '(31) 96677-8899', 'minas@autominas.com'),
('66.778.800/0001-55', 'Peças RS', '(31) 96778-0011', 'rs@pecasrs.com'),
('66.778.899/0001-65', 'Peças Minas', '(31) 96778-8901', 'minas@pecasminas.com'),
('66.778.911/0001-45', 'Nacional MG', '(31) 96778-1122', 'mg@nacionalmg.com'),
('66.778.922/0001-35', 'Peças PR', '(31) 96778-2233', 'pr@pecaspr2.com'),
('67.890.000/0001-85', 'Paraná Peças', '(31) 96789-0123', 'parana@pecas.com'),
('77.788.999/0001-74', 'Peças Sul', '(41) 97788-9900', 'sul@pecassul.com'),
('77.889.900/0001-64', 'Sul Auto', '(41) 97889-9012', 'sul@sulauto.com'),
('77.889.911/0001-54', 'SP Import', '(41) 97889-1122', 'sp@import.com'),
('77.889.922/0001-44', 'Auto RS', '(41) 97889-2233', 'rs@autors2.com'),
('77.889.933/0001-34', 'Distribuidora RS', '(41) 97889-3344', 'rs@distribuidorars2.com'),
('78.900.000/0001-84', 'RS Componentes', '(41) 97890-1234', 'rs@componentes.com'),
('88.899.900/0001-73', 'SP Componentes', '(41) 98899-0011', 'sp@componentes.com'),
('88.990.011/0001-63', 'SP Peças', '(41) 98990-0123', 'sp@pecassp.com'),
('88.990.022/0001-53', 'Minas Peças', '(41) 98990-2233', 'minas@pecasminas2.com'),
('88.990.033/0001-43', 'Peças SP', '(41) 98990-3344', 'sp@pecassp3.com'),
('88.990.044/0001-33', 'Componentes SP', '(41) 98990-4455', 'sp@componentessp2.com'),
('89.000.000/0001-83', 'SP Auto', '(41) 98901-2345', 'sp@auto.com'),
('90.000.000/0001-82', 'Auto Import', '(51) 99012-3456', 'import@auto.com'),
('99.001.122/0001-62', 'Importadora MG', '(51) 99001-1234', 'mg@importadora.com'),
('99.001.133/0001-52', 'Auto RJ', '(51) 99001-3344', 'rj@autorj.com'),
('99.001.144/0001-42', 'Distribuidora RJ', '(51) 99001-4455', 'rj@distribuidorarj.com'),
('99.001.155/0001-32', 'Auto MG', '(51) 99001-5566', 'mg@automg2.com'),
('99.900.011/0001-72', 'Importadora RS', '(51) 99900-1122', 'rs@importadora.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_serv`
--

CREATE TABLE `ordem_serv` (
  `id_ordem_serv` int(11) NOT NULL,
  `aparelho` varchar(100) DEFAULT NULL,
  `servico` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `tipo_pagamento` varchar(50) DEFAULT NULL,
  `problema` varchar(255) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `data_entrada` date DEFAULT NULL,
  `data_saida` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_serv`
--

INSERT INTO `ordem_serv` (`id_ordem_serv`, `aparelho`, `servico`, `status`, `valor`, `tipo_pagamento`, `problema`, `cpf`, `idusuario`, `data_entrada`, `data_saida`) VALUES
(1, 'Smartphone Samsung', 'Troca de tela', 'Concluído', 300.00, 'Cartão', 'Tela quebrada', '123.456.789-01', 1, '2025-01-10', '2025-01-12'),
(2, 'Notebook Dell', 'Formatação', 'Em andamento', 150.00, 'Dinheiro', 'Lentidão', '234.567.890-12', 2, '2025-02-01', NULL),
(3, 'iPhone 12', 'Troca de bateria', 'Aguardando peça', 200.00, 'Pix', 'Bateria descarrega rápido', '345.678.901-23', 3, '2025-03-05', NULL),
(4, 'Smartphone Xiaomi', 'Reparo de conector', 'Concluído', 120.00, 'Cartão', 'Não carrega', '456.789.012-34', 4, '2025-04-10', '2025-04-12'),
(5, 'Notebook HP', 'Troca de HD', 'Concluído', 400.00, 'Dinheiro', 'HD com defeito', '567.890.123-45', 5, '2025-05-15', '2025-05-17'),
(6, 'iPad Air', 'Troca de tela', 'Em andamento', 500.00, 'Pix', 'Tela rachada', '678.901.234-56', 6, '2025-06-01', NULL),
(7, 'Smartphone Motorola', 'Reparo de câmera', 'Aguardando peça', 180.00, 'Cartão', 'Câmera não funciona', '789.012.345-67', 7, '2025-07-10', NULL),
(8, 'Notebook Lenovo', 'Limpeza interna', 'Concluído', 100.00, 'Dinheiro', 'Superaquecimento', '890.123.456-78', 8, '2025-08-05', '2025-08-06'),
(9, 'iPhone 11', 'Troca de botão', 'Concluído', 150.00, 'Pix', 'Botão liga/desliga quebrado', '901.234.567-89', 9, '2025-09-01', '2025-09-03'),
(10, 'Smartphone LG', 'Reparo de alto-falante', 'Em andamento', 120.00, 'Cartão', 'Sem som', '012.345.678-90', 10, '2025-10-10', NULL),
(11, 'Notebook Acer', 'Troca de memória RAM', 'Concluído', 250.00, 'Dinheiro', 'Lentidão', '111.222.333-44', 11, '2025-01-15', '2025-01-17'),
(12, 'iPhone 13', 'Troca de tela', 'Aguardando peça', 350.00, 'Pix', 'Tela quebrada', '222.333.444-55', 12, '2025-02-20', NULL),
(13, 'Smartphone Asus', 'Reparo de conector', 'Concluído', 130.00, 'Cartão', 'Não carrega', '333.444.555-66', 13, '2025-03-10', '2025-03-12'),
(14, 'Notebook Samsung', 'Formatação', 'Em andamento', 150.00, 'Dinheiro', 'Sistema corrompido', '444.555.666-77', 14, '2025-04-05', NULL),
(15, 'iPad Pro', 'Troca de bateria', 'Concluído', 300.00, 'Pix', 'Bateria descarrega rápido', '555.666.777-88', 15, '2025-05-20', '2025-05-22'),
(16, 'Smartphone Huawei', 'Reparo de câmera', 'Aguardando peça', 200.00, 'Cartão', 'Câmera embaçada', '666.777.888-99', 16, '2025-06-15', NULL),
(17, 'Notebook Asus', 'Troca de teclado', 'Concluído', 220.00, 'Dinheiro', 'Teclas não funcionam', '777.888.999-00', 17, '2025-07-01', '2025-07-03'),
(18, 'iPhone XR', 'Troca de tela', 'Em andamento', 280.00, 'Pix', 'Tela rachada', '888.999.000-11', 18, '2025-08-10', NULL),
(19, 'Smartphone Nokia', 'Reparo de microfone', 'Em andamento', 14000.00, 'Cartão', 'Microfone falhando', '999.000.111-22', 19, '2025-09-05', '2025-09-07'),
(20, 'Notebook Toshiba', 'Limpeza interna', 'Concluído', 100.00, 'Dinheiro', 'Superaquecimento', '000.111.222-33', 20, '2025-10-01', '2025-10-02'),
(21, 'iPhone SE', 'Troca de bateria', 'Aguardando peça', 180.00, 'Pix', 'Bateria descarrega rápido', '112.233.445-56', 21, '2025-01-20', NULL),
(22, 'Smartphone Oppo', 'Reparo de conector', 'Concluído', 120.00, 'Cartão', 'Não carrega', '223.344.556-67', 22, '2025-02-15', '2025-02-17'),
(23, 'Notebook Vaio', 'Troca de HD', 'Em andamento', 400.00, 'Dinheiro', 'HD com defeito', '334.455.667-78', 23, '2025-03-01', NULL),
(24, 'iPad Mini', 'Troca de tela', 'Concluído', 450.00, 'Pix', 'Tela quebrada', '445.566.778-89', 24, '2025-04-10', '2025-04-12'),
(25, 'Smartphone Vivo', 'Reparo de câmera', 'Aguardando peça', 190.00, 'Cartão', 'Câmera não funciona', '556.677.889-90', 25, '2025-05-05', NULL),
(26, 'Notebook Positivo', 'Formatação', 'Concluído', 150.00, 'Dinheiro', 'Lentidão', '667.788.990-01', 26, '2025-06-01', '2025-06-03'),
(27, 'iPhone 14', 'Troca de botão', 'Em andamento', 200.00, 'Pix', 'Botão volume quebrado', '778.899.001-12', 27, '2025-07-10', NULL),
(28, 'Smartphone Realme', 'Reparo de alto-falante', 'Concluído', 130.00, 'Cartão', 'Sem som', '889.900.112-23', 28, '2025-08-05', '2025-08-07'),
(29, 'Notebook LG', 'Troca de memória RAM', 'Concluído', 250.00, 'Dinheiro', 'Lentidão', '990.011.223-34', 29, '2025-09-01', '2025-09-03'),
(30, 'iPhone 8', 'Troca de tela', 'Aguardando peça', 250.00, 'Pix', 'Tela quebrada', '001.122.334-45', 30, '2025-10-10', NULL),
(31, 'Smartphone Lenovo', 'Reparo de conector', 'Concluído', 120.00, 'Cartão', 'Não carrega', '112.233.556-67', 31, '2025-01-15', '2025-01-17'),
(32, 'Notebook MSI', 'Limpeza interna', 'Em andamento', 100.00, 'Dinheiro', 'Superaquecimento', '223.344.667-78', 32, '2025-02-20', NULL),
(33, 'iPad Air 2', 'Troca de bateria', 'Concluído', 300.00, 'Pix', 'Bateria descarrega rápido', '334.455.778-89', 33, '2025-03-10', '2025-03-12'),
(34, 'Smartphone Sony', 'Reparo de câmera', 'Aguardando peça', 180.00, 'Cartão', 'Câmera embaçada', '445.566.889-90', 34, '2025-04-05', NULL),
(35, 'Notebook Apple', 'Troca de teclado', 'Concluído', 500.00, 'Dinheiro', 'Teclas não funcionam', '556.677.990-01', 35, '2025-05-20', '2025-05-22'),
(36, 'iPhone 7', 'Troca de tela', 'Em andamento', 220.00, 'Pix', 'Tela rachada', '667.788.001-12', 36, '2025-06-15', NULL),
(37, 'Smartphone TCL', 'Reparo de microfone', 'Concluído', 140.00, 'Cartão', 'Microfone falhando', '778.899.112-23', 37, '2025-07-01', '2025-07-03'),
(38, 'Notebook CCE', 'Formatação', 'Concluído', 150.00, 'Dinheiro', 'Sistema corrompido', '889.900.223-34', 38, '2025-08-10', '2025-08-12'),
(39, 'iPhone X', 'Troca de bateria', 'Aguardando peça', 200.00, 'Pix', 'Bateria descarrega rápido', '990.011.334-45', 39, '2025-09-05', NULL),
(40, 'Smartphone OnePlus', 'Reparo de conector', 'Concluído', 120.00, 'Cartão', 'Não carrega', '001.122.445-56', 40, '2025-10-01', '2025-10-03'),
(41, 'Notebook Gateway', 'Troca de HD', 'Em andamento', 400.00, 'Dinheiro', 'HD com defeito', '112.233.667-78', 41, '2025-01-20', NULL),
(42, 'iPad 9', 'Troca de tela', 'Concluído', 450.00, 'Pix', 'Tela quebrada', '223.344.778-89', 42, '2025-02-15', '2025-02-17'),
(43, 'Smartphone ZTE', 'Reparo de câmera', 'Aguardando peça', 190.00, 'Cartão', 'Câmera não funciona', '334.455.889-90', 43, '2025-03-01', NULL),
(44, 'Notebook Sony', 'Limpeza interna', 'Concluído', 100.00, 'Dinheiro', 'Superaquecimento', '445.566.990-01', 44, '2025-04-10', '2025-04-11'),
(45, 'iPhone 6s', 'Troca de botão', 'Em andamento', 150.00, 'Pix', 'Botão home quebrado', '556.677.001-12', 45, '2025-05-05', NULL),
(46, 'Smartphone Alcatel', 'Reparo de alto-falante', 'Concluído', 130.00, 'Cartão', 'Sem som', '667.788.112-23', 46, '2025-06-01', '2025-06-03'),
(47, 'Notebook Compaq', 'Troca de memória RAM', 'Concluído', 250.00, 'Dinheiro', 'Lentidão', '778.899.223-34', 47, '2025-07-10', '2025-07-12'),
(48, 'iPhone 12 Pro', 'Troca de tela', 'Aguardando peça', 350.00, 'Pix', 'Tela quebrada', '889.900.334-45', 48, '2025-08-05', NULL),
(49, 'Smartphone Infinix', 'Reparo de conector', 'Concluído', 120.00, 'Cartão', 'Não carrega', '990.011.445-56', 49, '2025-09-01', '2025-09-03'),
(50, 'Notebook Philcod', 'Formataçãoa', 'Concluído', 1500.00, 'Boleto', 'Sistema corrompido', '001.122.556-67', 50, '2025-10-10', '2025-09-16'),
(51, 'iPad 10', 'Troca de bateria', 'Concluído', 300.00, 'Pix', 'Bateria descarrega rápido', '112.233.778-89', 51, '2025-01-15', '2025-01-17'),
(52, 'Smartphone Tecno', 'Reparo de câmera', 'Aguardando peça', 180.00, 'Cartão', 'Câmera embaçada', '223.344.889-90', 52, '2025-02-20', NULL),
(53, 'Notebook Itautec', 'Troca de teclado', 'Concluído', 220.00, 'Dinheiro', 'Teclas não funcionam', '334.455.990-01', 53, '2025-03-10', '2025-03-12'),
(54, 'iPhone 11 Pro', 'Troca de tela', 'Em andamento', 280.00, 'Pix', 'Tela rachada', '445.566.001-12', 54, '2025-04-05', NULL),
(55, 'Smartphone Meizu', 'Reparo de microfone', 'Concluído', 140.00, 'Cartão', 'Microfone falhando', '556.677.112-23', 55, '2025-05-20', '2025-05-22'),
(56, 'Notebook Semp Toshiba', 'Limpeza interna', 'Concluído', 100.00, 'Dinheiro', 'Superaquecimento', '667.788.223-34', 56, '2025-06-15', '2025-06-16'),
(57, 'iPhone 13 Mini', 'Troca de bateria', 'Aguardando peça', 200.00, 'Pix', 'Bateria descarrega rápido', '778.899.334-45', 57, '2025-07-01', NULL),
(58, 'Smartphone Gionee', 'Reparo de conector', 'Concluído', 120.00, 'Cartão', 'Não carrega', '889.900.445-56', 58, '2025-08-10', '2025-08-12'),
(59, 'Notebook Multilaser', 'Troca de HD', 'Em andamento', 400.00, 'Dinheiro', 'HD com defeito', '990.011.556-67', 59, '2025-09-05', NULL),
(60, 'iPad Pro 11', 'Troca de tela', 'Concluído', 500.00, 'Pix', 'Tela quebrada', '001.122.667-78', 60, '2025-10-01', '2025-10-03'),
(61, 'teste', 'teste', 'pendente', 120.00, 'Pix', 'teste', '999.000.111-22', 1, '2025-09-16', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(100) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `idestoque` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome_produto`, `valor`, `cnpj`, `idestoque`) VALUES
(1, 'Tela Samsung A50', 200.00, '12.345.678/0001-90', 1),
(2, 'Bateria iPhone 12', 150.00, '23.456.789/0001-89', 2),
(3, 'Conector USB Xiaomi', 50.00, '34.567.890/0001-88', 3),
(4, 'HD Notebook 1TB', 300.00, '45.678.900/0001-87', 4),
(5, 'Tela iPad Air', 400.00, '56.789.000/0001-86', 5),
(6, 'Câmera Motorola G9', 120.00, '67.890.000/0001-85', 6),
(7, 'Teclado Notebook Dell', 180.00, '78.900.000/0001-84', 7),
(8, 'Memória RAM 8GB', 200.00, '89.000.000/0001-83', 8),
(9, 'Botão iPhone 11', 80.00, '90.000.000/0001-82', 9),
(10, 'Alto-falante LG K10', 60.00, '00.000.000/0001-81', 10),
(11, 'Tela Samsung A70', 250.00, '11.122.233/0001-80', 11),
(12, 'Bateria iPhone 13', 180.00, '22.233.344/0001-79', 12),
(13, 'Conector USB Asus', 55.00, '33.344.455/0001-78', 13),
(14, 'HD Notebook 500GB', 250.00, '44.455.566/0001-77', 14),
(15, 'Tela iPad Pro', 450.00, '55.566.777/0001-76', 15),
(16, 'Câmera Huawei P30', 130.00, '66.677.788/0001-75', 16),
(17, 'Teclado Notebook Asus', 200.00, '77.788.999/0001-74', 17),
(18, 'Memória RAM 16GB', 300.00, '88.899.900/0001-73', 18),
(19, 'Botão iPhone XR', 90.00, '99.900.011/0001-72', 19),
(20, 'Alto-falante Nokia', 70.00, '00.011.122/0001-71', 20),
(21, 'Tela Samsung S20', 300.00, '11.223.344/0001-70', 21),
(22, 'Bateria iPhone SE', 120.00, '22.334.455/0001-69', 22),
(23, 'Conector USB Oppo', 50.00, '33.445.566/0001-68', 23),
(24, 'HD Notebook 2TB', 350.00, '44.556.677/0001-67', 24),
(25, 'Tela iPad Mini', 400.00, '55.667.788/0001-66', 25),
(26, 'Câmera Vivo Y70', 140.00, '66.778.899/0001-65', 26),
(27, 'Teclado Notebook Lenovo', 190.00, '77.889.900/0001-64', 27),
(28, 'Memória RAM 4GB', 150.00, '88.990.011/0001-63', 28),
(29, 'Botão iPhone 14', 100.00, '99.001.122/0001-62', 29),
(30, 'Alto-falante Realme', 80.00, '00.112.233/0001-61', 30),
(31, 'Tela Samsung A20', 220.00, '11.223.355/0001-60', 31),
(32, 'Bateria iPhone 8', 130.00, '22.334.466/0001-59', 32),
(33, 'Conector USB Sony', 60.00, '33.445.577/0001-58', 33),
(34, 'HD Notebook 750GB', 280.00, '44.556.688/0001-57', 34),
(35, 'Tela iPad Air 2', 420.00, '55.667.799/0001-56', 35),
(36, 'Câmera TCL', 120.00, '66.778.800/0001-55', 36),
(37, 'Teclado Notebook Apple', 400.00, '77.889.911/0001-54', 37),
(38, 'Memória RAM 32GB', 400.00, '88.990.022/0001-53', 38),
(39, 'Botão iPhone 7', 70.00, '99.001.133/0001-52', 39),
(40, 'Alto-falante OnePlus', 90.00, '00.112.244/0001-51', 40),
(41, 'Tela Samsung A30', 230.00, '11.223.366/0001-50', 41),
(42, 'Bateria iPhone X', 140.00, '22.334.477/0001-49', 42),
(43, 'Conector USB ZTE', 55.00, '33.445.588/0001-48', 43),
(44, 'HD Notebook 1.5TB', 320.00, '44.556.699/0001-47', 44),
(45, 'Tela iPad 9', 430.00, '55.667.800/0001-46', 45),
(46, 'Câmera Alcatel', 110.00, '66.778.911/0001-45', 46),
(47, 'Teclado Notebook Compaq', 180.00, '77.889.922/0001-44', 47),
(48, 'Memória RAM 12GB', 250.00, '88.990.033/0001-43', 48),
(49, 'Botão iPhone 12 Pro', 110.00, '99.001.144/0001-42', 49),
(50, 'Alto-falante Infinix', 85.00, '00.112.255/0001-41', 50),
(51, 'Tela Samsung A10', 210.00, '11.223.377/0001-40', 51),
(52, 'Bateria iPhone 11 Pro', 150.00, '22.334.488/0001-39', 52),
(53, 'Conector USB Tecno', 50.00, '33.445.599/0001-38', 53),
(54, 'HD Notebook 1TB', 300.00, '44.556.600/0001-37', 54),
(55, 'Tela iPad 10', 450.00, '55.667.711/0001-36', 55),
(56, 'Câmera Meizu', 120.00, '66.778.922/0001-35', 56),
(57, 'Teclado Notebook Itautec', 190.00, '77.889.933/0001-34', 57),
(58, 'Memória RAM 8GB', 200.00, '88.990.044/0001-33', 58),
(59, 'Botão iPhone 13 Mini', 100.00, '99.001.155/0001-32', 59),
(60, 'Alto-falante Gionee', 80.00, '00.112.266/0001-31', 60);

-- --------------------------------------------------------

--
-- Estrutura para tabela `recover_senha`
--

CREATE TABLE `recover_senha` (
  `id_recover` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_expiracao` datetime DEFAULT NULL,
  `utilizado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `recover_senha`
--

INSERT INTO `recover_senha` (`id_recover`, `id_usuario`, `token`, `data_criacao`, `data_expiracao`, `utilizado`) VALUES
(1, 1, 'token123456', '2025-01-01 10:00:00', '2025-01-02 10:00:00', 1),
(2, 2, 'token234567', '2025-01-02 11:00:00', '2025-01-03 11:00:00', 0),
(3, 3, 'token345678', '2025-01-03 12:00:00', '2025-01-04 12:00:00', 1),
(4, 4, 'token456789', '2025-01-04 13:00:00', '2025-01-05 13:00:00', 0),
(5, 5, 'token567890', '2025-01-05 14:00:00', '2025-01-06 14:00:00', 1),
(6, 6, 'token678901', '2025-01-06 15:00:00', '2025-01-07 15:00:00', 0),
(7, 7, 'token789012', '2025-01-07 16:00:00', '2025-01-08 16:00:00', 1),
(8, 8, 'token890123', '2025-01-08 17:00:00', '2025-01-09 17:00:00', 0),
(9, 9, 'token901234', '2025-01-09 18:00:00', '2025-01-10 18:00:00', 1),
(10, 10, 'token012345', '2025-01-10 19:00:00', '2025-01-11 19:00:00', 0),
(11, 11, 'token112233', '2025-01-11 10:00:00', '2025-01-12 10:00:00', 1),
(12, 12, 'token223344', '2025-01-12 11:00:00', '2025-01-13 11:00:00', 0),
(13, 13, 'token334455', '2025-01-13 12:00:00', '2025-01-14 12:00:00', 1),
(14, 14, 'token445566', '2025-01-14 13:00:00', '2025-01-15 13:00:00', 0),
(15, 15, 'token556677', '2025-01-15 14:00:00', '2025-01-16 14:00:00', 1),
(16, 16, 'token667788', '2025-01-16 15:00:00', '2025-01-17 15:00:00', 0),
(17, 17, 'token778899', '2025-01-17 16:00:00', '2025-01-18 16:00:00', 1),
(18, 18, 'token889900', '2025-01-18 17:00:00', '2025-01-19 17:00:00', 0),
(19, 19, 'token990011', '2025-01-19 18:00:00', '2025-01-20 18:00:00', 1),
(20, 20, 'token001122', '2025-01-20 19:00:00', '2025-01-21 19:00:00', 0),
(21, 21, 'token112233', '2025-01-21 10:00:00', '2025-01-22 10:00:00', 1),
(22, 22, 'token223344', '2025-01-22 11:00:00', '2025-01-23 11:00:00', 0),
(23, 23, 'token334455', '2025-01-23 12:00:00', '2025-01-24 12:00:00', 1),
(24, 24, 'token445566', '2025-01-24 13:00:00', '2025-01-25 13:00:00', 0),
(25, 25, 'token556677', '2025-01-25 14:00:00', '2025-01-26 14:00:00', 1),
(26, 26, 'token667788', '2025-01-26 15:00:00', '2025-01-27 15:00:00', 0),
(27, 27, 'token778899', '2025-01-27 16:00:00', '2025-01-28 16:00:00', 1),
(28, 28, 'token889900', '2025-01-28 17:00:00', '2025-01-29 17:00:00', 0),
(29, 29, 'token990011', '2025-01-29 18:00:00', '2025-01-30 18:00:00', 1),
(30, 30, 'token001122', '2025-01-30 19:00:00', '2025-01-31 19:00:00', 0),
(31, 31, 'token112233', '2025-01-31 10:00:00', '2025-02-01 10:00:00', 1),
(32, 32, 'token223344', '2025-02-01 11:00:00', '2025-02-02 11:00:00', 0),
(33, 33, 'token334455', '2025-02-02 12:00:00', '2025-02-03 12:00:00', 1),
(34, 34, 'token445566', '2025-02-03 13:00:00', '2025-02-04 13:00:00', 0),
(35, 35, 'token556677', '2025-02-04 14:00:00', '2025-02-05 14:00:00', 1),
(36, 36, 'token667788', '2025-02-05 15:00:00', '2025-02-06 15:00:00', 0),
(37, 37, 'token778899', '2025-02-06 16:00:00', '2025-02-07 16:00:00', 1),
(38, 38, 'token889900', '2025-02-07 17:00:00', '2025-02-08 17:00:00', 0),
(39, 39, 'token990011', '2025-02-08 18:00:00', '2025-02-09 18:00:00', 1),
(40, 40, 'token001122', '2025-02-09 19:00:00', '2025-02-10 19:00:00', 0),
(41, 41, 'token112233', '2025-02-10 10:00:00', '2025-02-11 10:00:00', 1),
(42, 42, 'token223344', '2025-02-11 11:00:00', '2025-02-12 11:00:00', 0),
(43, 43, 'token334455', '2025-02-12 12:00:00', '2025-02-13 12:00:00', 1),
(44, 44, 'token445566', '2025-02-13 13:00:00', '2025-02-14 13:00:00', 0),
(45, 45, 'token556677', '2025-02-14 14:00:00', '2025-02-15 14:00:00', 1),
(46, 46, 'token667788', '2025-02-15 15:00:00', '2025-02-16 15:00:00', 0),
(47, 47, 'token778899', '2025-02-16 16:00:00', '2025-02-17 16:00:00', 1),
(48, 48, 'token889900', '2025-02-17 17:00:00', '2025-02-18 17:00:00', 0),
(49, 49, 'token990011', '2025-02-18 18:00:00', '2025-02-19 18:00:00', 1),
(50, 50, 'token001122', '2025-02-19 19:00:00', '2025-02-20 19:00:00', 0),
(51, 51, 'token112233', '2025-02-20 10:00:00', '2025-02-21 10:00:00', 1),
(52, 52, 'token223344', '2025-02-21 11:00:00', '2025-02-22 11:00:00', 0),
(53, 53, 'token334455', '2025-02-22 12:00:00', '2025-02-23 12:00:00', 1),
(54, 54, 'token445566', '2025-02-23 13:00:00', '2025-02-24 13:00:00', 0),
(55, 55, 'token556677', '2025-02-24 14:00:00', '2025-02-25 14:00:00', 1),
(56, 56, 'token667788', '2025-02-25 15:00:00', '2025-02-26 15:00:00', 0),
(57, 57, 'token778899', '2025-02-26 16:00:00', '2025-02-27 16:00:00', 1),
(58, 58, 'token889900', '2025-02-27 17:00:00', '2025-02-28 17:00:00', 0),
(59, 59, 'token990011', '2025-02-28 18:00:00', '2025-03-01 18:00:00', 1),
(60, 60, 'token001122', '2025-03-01 19:00:00', '2025-03-02 19:00:00', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico_produto`
--

CREATE TABLE `servico_produto` (
  `quantidade` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `id_ordem_serv` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servico_produto`
--

INSERT INTO `servico_produto` (`quantidade`, `id_produto`, `id_ordem_serv`) VALUES
(1, 1, 1),
(1, 2, 3),
(1, 3, 4),
(1, 4, 5),
(1, 5, 6),
(1, 6, 7),
(1, 7, 8),
(1, 8, 11),
(1, 9, 9),
(1, 10, 10),
(1, 11, 12),
(1, 12, 13),
(1, 13, 14),
(1, 14, 15),
(1, 15, 16),
(1, 16, 17),
(1, 17, 18),
(1, 18, 19),
(1, 19, 20),
(1, 20, 21),
(1, 21, 22),
(1, 22, 23),
(1, 23, 24),
(1, 24, 25),
(1, 25, 26),
(1, 26, 27),
(1, 27, 28),
(1, 28, 29),
(1, 29, 30),
(1, 30, 31),
(1, 31, 32),
(1, 32, 33),
(1, 33, 34),
(1, 34, 35),
(1, 35, 36),
(1, 36, 37),
(1, 37, 38),
(1, 38, 39),
(1, 39, 40),
(1, 40, 41),
(1, 41, 42),
(1, 42, 43),
(1, 43, 44),
(1, 44, 45),
(1, 45, 46),
(1, 46, 47),
(1, 47, 48),
(1, 48, 49),
(1, 49, 50),
(1, 50, 51),
(1, 51, 52),
(1, 52, 53),
(1, 53, 54),
(1, 54, 55),
(1, 55, 56),
(1, 56, 57),
(1, 57, 58),
(1, 58, 59),
(1, 59, 60),
(1, 60, 1);

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
  `senha_temporaria` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `senha`, `cargo`, `nome_completo`, `ativo`, `senha_temporaria`) VALUES
(1, 'joao.tecnico@email.com', 'senha123', 'Tecnico', 'João Mendes', 1, 0),
(2, 'maria.gerente@email.com', '$2y$10$aZUuNMg899Q5MbsJz4gMhOtY84EEe3k3OM6sg6na00aQb9ebO9A6m', 'Gerente', 'Maria Oliveira', 1, 0),
(3, 'pedro.atendente@email.com', 'senha789', 'Atendente', 'Pedro Santos', 1, 0),
(4, 'ana.tecnico@email.com', 'senha101', 'Tecnico', 'Ana Costa', 1, 0),
(5, 'lucas.gerente@email.com', 'senha102', 'Gerente', 'Lucas Pereira', 1, 0),
(6, 'fernanda.atendente@email.com', 'senha103', 'Atendente', 'Fernanda Lima', 1, 0),
(7, 'rafael.tecnico@email.com', 'senha104', 'Tecnico', 'Rafael Almeida', 1, 0),
(8, 'juliana.gerente@email.com', 'senha105', 'Gerente', 'Juliana Ferreira', 1, 0),
(9, 'carlos.atendente@email.com', 'senha106', 'Atendente', 'Carlos Souza', 1, 0),
(10, 'mariana.tecnico@email.com', 'senha107', 'Tecnico', 'Mariana Rocha', 1, 0),
(11, 'felipe.gerente@email.com', 'senha108', 'Gerente', 'Felipe Mendes', 1, 0),
(12, 'camila.atendente@email.com', 'senha109', 'Atendente', 'Camila Ribeiro', 1, 0),
(13, 'thiago.tecnico@email.com', 'senha110', 'Tecnico', 'Thiago Gonçalves', 1, 0),
(14, 'beatriz.gerente@email.com', 'senha111', 'Gerente', 'Beatriz Almeida', 1, 0),
(15, 'gabriel.atendente@email.com', 'senha112', 'Atendente', 'Gabriel Costa', 1, 0),
(16, 'isabela.tecnico@email.com', 'senha113', 'Tecnico', 'Isabela Santos', 1, 0),
(17, 'diego.gerente@email.com', 'senha114', 'Gerente', 'Diego Lima', 1, 0),
(18, 'laura.atendente@email.com', 'senha115', 'Atendente', 'Laura Ferreira', 1, 0),
(19, 'vinicius.tecnico@email.com', 'senha116', 'Tecnico', 'Vinicius Oliveira', 1, 0),
(20, 'clara.gerente@email.com', 'senha117', 'Gerente', 'Clara Souza', 1, 0),
(21, 'eduardo.atendente@email.com', 'senha118', 'Atendente', 'Eduardo Pereira', 1, 0),
(22, 'sofia.tecnico@email.com', '$2y$10$1iXjV0yZp0m4Vi57.V2H.udGg/PgMd0Zi9Vqg4d3Ozobn1qOQ6nOa', 'Tecnico', 'Sofia Almeida', 1, 0),
(23, 'henrique.gerente@email.com', 'senha120', 'Gerente', 'Henrique Silva', 1, 0),
(24, 'livia.atendente@email.com', 'senha121', 'Atendente', 'Lívia Costa', 1, 0),
(25, 'mateus.tecnico@email.com', 'senha122', 'Tecnico', 'Mateus Santos', 1, 0),
(26, 'larissa.gerente@email.com', 'senha123', 'Gerente', 'Larissa Lima', 1, 0),
(27, 'bruno.atendente@email.com', 'senha124', 'Atendente', 'Bruno Ferreira', 1, 0),
(28, 'alice.tecnico@email.com', 'senha125', 'Tecnico', 'Alice Ribeiro', 1, 0),
(29, 'guilherme.gerente@email.com', 'senha126', 'Gerente', 'Guilherme Souza', 1, 0),
(30, 'helena.atendente@email.com', 'senha127', 'Atendente', 'Helena Oliveira', 1, 0),
(31, 'rafael2.tecnico@email.com', 'senha128', 'Tecnico', 'Rafael Mendes', 1, 0),
(32, 'marina.gerente@email.com', 'senha129', 'Gerente', 'Marina Costa', 1, 0),
(33, 'leonardo.atendente@email.com', 'senha130', 'Atendente', 'Leonardo Silva', 1, 0),
(34, 'valentina.tecnico@email.com', 'senha131', 'Tecnico', 'Valentina Almeida', 1, 0),
(35, 'gustavo.gerente@email.com', 'senha132', 'Gerente', 'Gustavo Santos', 1, 0),
(36, 'manuela.atendente@email.com', 'senha133', 'Atendente', 'Manuela Lima', 1, 0),
(37, 'rodrigo.tecnico@email.com', 'senha134', 'Tecnico', 'Rodrigo Ferreira', 1, 0),
(38, 'bianca.gerente@email.com', 'senha135', 'Gerente', 'Bianca Ribeiro', 1, 0),
(39, 'daniel.atendente@email.com', 'senha136', 'Atendente', 'Daniel Souza', 1, 0),
(40, 'leticia.tecnico@email.com', 'senha137', 'TécnicoTecnico', 'Letícia Oliveira', 1, 0),
(41, 'andre.gerente@email.com', 'senha138', 'Gerente', 'André Mendes', 1, 0),
(42, 'vitoria.atendente@email.com', 'senha139', 'Atendente', 'Vitória Costa', 1, 0),
(43, 'igor.tecnico@email.com', 'senha140', 'Tecnico', 'Igor Silva', 1, 0),
(44, 'cecilia.gerente@email.com', 'senha141', 'Gerente', 'Cecília Almeida', 1, 0),
(45, 'enzo.atendente@email.com', 'senha142', 'Atendente', 'Enzo Santos', 1, 0),
(46, 'lara.tecnico@email.com', 'senha143', 'Tecnico', 'Lara Lima', 1, 0),
(47, 'fabio.gerente@email.com', 'senha144', 'Gerente', 'Fábio Ferreira', 1, 0),
(48, 'elisa.atendente@email.com', 'senha145', 'Atendente', 'Elisa Ribeiro', 1, 0),
(49, 'samuel.tecnico@email.com', 'senha146', 'Tecnico', 'Samuel Souza', 1, 0),
(50, 'julia.gerente@email.com', 'senha147', 'Gerente', 'Júlia Oliveira', 1, 0),
(51, 'caio.atendente@email.com', 'senha148', 'Atendente', 'Caio Mendes', 1, 0),
(52, 'aline.tecnico@email.com', 'senha149', 'Tecnico', 'Aline Costa', 1, 0),
(53, 'vitor.gerente@email.com', 'senha150', 'Gerente', 'Vitor Silva', 1, 0),
(54, 'luana.atendente@email.com', 'senha151', 'Atendente', 'Luana Almeida', 1, 0),
(55, 'nicolas.tecnico@email.com', 'senha152', 'Tecnico', 'Nicolas Santos', 1, 0),
(56, 'mirela.gerente@email.com', 'senha153', 'Gerente', 'Mirela Lima', 1, 0),
(57, 'hugo.atendente@email.com', 'senha154', 'Atendente', 'Hugo Ferreira', 1, 0),
(58, 'lorena.tecnico@email.com', 'senha155', 'Tecnico', 'Lorena Ribeiro', 1, 0),
(59, 'marcos.gerente@email.com', 'senha156', 'Gerente', 'Marcos Souza', 1, 0),
(60, 'natalia.atendente@email.com', 'senha157', 'Atendente', 'Natália Oliveira', 1, 0),
(61, 'admin@admin', '$2y$10$9KtqZ30MUTHjLhjsOU/k3OtganW3ePTVn7xO4IS6gFabKt6uA8XcK', 'Gerente', 'admin', 1, NULL),
(62, 'arthur-miguelsales@example.net', '$2y$10$CQ1v0pYFAcCzLbjpivb8UO4QspbSHpFjlwL3RWQID.qjTkt1JBSGy', 'Atendente', 'Joaquim Santos', 1, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cpf`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id_estoque`);

--
-- Índices de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`cnpj`);

--
-- Índices de tabela `ordem_serv`
--
ALTER TABLE `ordem_serv`
  ADD PRIMARY KEY (`id_ordem_serv`),
  ADD KEY `fk_ordem_cliente` (`cpf`),
  ADD KEY `fk_ordem_usuario` (`idusuario`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `fk_produto_fornecedor` (`cnpj`),
  ADD KEY `fk_produto_estoque` (`idestoque`);

--
-- Índices de tabela `recover_senha`
--
ALTER TABLE `recover_senha`
  ADD PRIMARY KEY (`id_recover`),
  ADD KEY `fk_recover_usuario` (`id_usuario`);

--
-- Índices de tabela `servico_produto`
--
ALTER TABLE `servico_produto`
  ADD KEY `fk_servico_produto` (`id_produto`),
  ADD KEY `fk_servico_ordem` (`id_ordem_serv`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id_estoque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `ordem_serv`
--
ALTER TABLE `ordem_serv`
  MODIFY `id_ordem_serv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `recover_senha`
--
ALTER TABLE `recover_senha`
  MODIFY `id_recover` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `ordem_serv`
--
ALTER TABLE `ordem_serv`
  ADD CONSTRAINT `fk_ordem_cliente` FOREIGN KEY (`cpf`) REFERENCES `cliente` (`cpf`),
  ADD CONSTRAINT `fk_ordem_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `fk_produto_estoque` FOREIGN KEY (`idestoque`) REFERENCES `estoque` (`id_estoque`),
  ADD CONSTRAINT `fk_produto_fornecedor` FOREIGN KEY (`cnpj`) REFERENCES `fornecedor` (`cnpj`);

--
-- Restrições para tabelas `recover_senha`
--
ALTER TABLE `recover_senha`
  ADD CONSTRAINT `fk_recover_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `servico_produto`
--
ALTER TABLE `servico_produto`
  ADD CONSTRAINT `fk_servico_ordem` FOREIGN KEY (`id_ordem_serv`) REFERENCES `ordem_serv` (`id_ordem_serv`),
  ADD CONSTRAINT `fk_servico_produto` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
