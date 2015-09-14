-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 14-Set-2015 às 14:02
-- Versão do servidor: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `centerar_site2`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_datsogallery_catg`
--

CREATE TABLE IF NOT EXISTS `jos_datsogallery_catg` (
`cid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `parent` varchar(255) NOT NULL DEFAULT '0',
  `description` text,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `published` char(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Extraindo dados da tabela `jos_datsogallery_catg`
--

INSERT INTO `jos_datsogallery_catg` (`cid`, `name`, `parent`, `description`, `ordering`, `user_id`, `access`, `approved`, `published`, `date`) VALUES
(1, 'Chaves de Comando', '0', '', 1, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(2, 'Compressores', '0', '', 83, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(3, 'Condensadores', '0', '', 82, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(4, 'Evaporadores', '0', '', 74, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(5, 'Motores de Ventilação', '0', '', 67, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(6, 'Válvulas', '0', '', 52, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(7, 'Abraçadeiras', '0', '', 94, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(8, 'Adaptadores', '0', '', 93, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(9, 'Anéis O''Ring', '0', '', 92, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(10, 'Bocais', '0', '', 91, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(11, 'Botões', '0', '', 90, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(12, 'Canos', '0', '', 88, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(13, 'Capas', '0', '', 87, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(17, 'Caixas Evaporadoras', '0', '', 89, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(16, 'Ferramentas e máquinas', '0', '', 73, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(18, 'Chicotes', '0', '', 84, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(19, 'Kits para Instalação', '0', 'Kits para Instalação', 69, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(20, 'Chaveta', '0', '', 85, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(21, 'Gaxetas', '0', '', 70, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(22, 'Palhetas', '0', '', 66, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(23, 'Parafusos', '0', '', 65, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(24, 'Pinos', '0', '', 64, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(25, 'Placas', '0', '', 63, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(26, 'Polias', '0', '', 62, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(27, 'Pressostatos', '0', '', 61, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(28, 'Resistências', '0', '', 60, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(29, 'Ruelas', '0', '', 58, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(30, 'Filtros Secadores', '0', '', 71, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(31, 'Eletroventiladores', '0', '', 78, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(32, 'Conexões', '0', '', 81, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(33, 'Eletromagnéticos', '0', '', 79, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(34, 'Emendas', '0', '', 77, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(35, 'Espelhos Compressores', '0', '', 76, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(36, 'Esticadores', '0', '', 75, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(37, 'Diversos', '0', 'peças diversas', 80, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(38, 'Saídas', '0', 'saidas para compressores', 57, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(39, 'Filtros Anti-Pólen', '0', 'Filtros Anti-Pólen', 72, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(41, 'Mangueiras', '0', '', 68, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(42, 'Rolamentos', '0', '', 59, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(43, 'Selos', '0', '', 56, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(44, 'Sensores', '0', '', 55, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(45, 'Tampa', '0', '', 54, 213, 1, 1, '1', '2014-11-11 16:50:00'),
(46, 'Termostatos', '0', '', 53, 213, 1, 1, '1', '2014-11-11 16:50:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jos_datsogallery_catg`
--
ALTER TABLE `jos_datsogallery_catg`
 ADD PRIMARY KEY (`cid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jos_datsogallery_catg`
--
ALTER TABLE `jos_datsogallery_catg`
MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
