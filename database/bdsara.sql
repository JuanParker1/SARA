-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 29-06-2019 a las 00:14:17
-- Versión del servidor: 10.1.26-MariaDB
-- Versión de PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdsara`
--
CREATE DATABASE IF NOT EXISTS `bdsara` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bdsara`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_apps`
--

CREATE TABLE `sara_apps` (
  `Id` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Desc` varchar(255) DEFAULT NULL,
  `Icono` varchar(50) DEFAULT NULL,
  `Color` varchar(10) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_apps`
--

INSERT INTO `sara_apps` (`Id`, `Titulo`, `Desc`, `Icono`, `Color`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'CAC - Pacientes Oncológicos', NULL, 'fa-ribbon', '#be92d0', 183, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Listado de Fallecimientos', NULL, 'fa-book-dead', '#3a3a3a', 183, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Listado de Infecciones Intrahospitalarias', NULL, 'fa-bug', '#a0d873', 183, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_bdds`
--

CREATE TABLE `sara_bdds` (
  `id` int(11) NOT NULL,
  `Tipo` varchar(20) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Usuario` varchar(255) DEFAULT NULL,
  `Contraseña` varchar(255) DEFAULT NULL,
  `Op1` varchar(50) DEFAULT NULL,
  `Op2` varchar(50) DEFAULT NULL,
  `Op3` varchar(50) DEFAULT NULL,
  `Op4` varchar(50) DEFAULT NULL,
  `Op5` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_bdds`
--

INSERT INTO `sara_bdds` (`id`, `Tipo`, `Nombre`, `Usuario`, `Contraseña`, `Op1`, `Op2`, `Op3`, `Op4`, `Op5`, `created_at`, `updated_at`) VALUES
(1, 'ODBC_DB2', 'iSeries', 'SISCORREGO', 'comfa123', 'Salud', '10.25.2.8', 'BDSALUD', NULL, NULL, '0000-00-00 00:00:00', '2019-05-31 15:53:18'),
(4, 'MySQL', 'mysql local', 'root', '1234', NULL, 'localhost', 'bdsara', NULL, NULL, '2019-05-30 08:18:17', '2019-05-30 08:20:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_bdds_favoritos`
--

CREATE TABLE `sara_bdds_favoritos` (
  `id` int(11) NOT NULL,
  `bdd_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `Carpeta` varchar(255) NOT NULL DEFAULT 'General',
  `Nombre` varchar(255) NOT NULL,
  `Consulta` text,
  `EjecutarAutom` varchar(1) NOT NULL DEFAULT 'N',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_bdds_favoritos`
--

INSERT INTO `sara_bdds_favoritos` (`id`, `bdd_id`, `usuario_id`, `Carpeta`, `Nombre`, `Consulta`, `EjecutarAutom`, `created_at`, `updated_at`) VALUES
(1, 1, 183, 'General', 'Citas de Hoy', 'SELECT * FROM TBAGCITAS WHERE CIFECCITA = 20190530', 'N', '2019-05-30 00:00:00', '2019-05-30 16:10:50'),
(2, 1, 183, 'General', 'Buscar un Paciente', 'SELECT * FROM TBBDBENEFI WHERE BENUMDOCBE = \'1093217141\'', 'N', '2019-05-30 16:22:13', '2019-05-30 18:05:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades`
--

CREATE TABLE `sara_entidades` (
  `id` int(11) NOT NULL,
  `bdd_id` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Tipo` enum('Tabla','Vista') NOT NULL DEFAULT 'Tabla',
  `Tabla` varchar(255) NOT NULL,
  `campo_llaveprim` int(11) DEFAULT NULL,
  `campo_desc1` int(11) DEFAULT NULL,
  `campo_desc2` int(11) DEFAULT NULL,
  `campo_desc3` int(11) DEFAULT NULL,
  `campo_orderby` int(11) DEFAULT NULL,
  `campo_orderbydir` varchar(5) NOT NULL DEFAULT 'DESC',
  `max_rows` int(100) NOT NULL DEFAULT '100',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_entidades`
--

INSERT INTO `sara_entidades` (`id`, `bdd_id`, `Nombre`, `Tipo`, `Tabla`, `campo_llaveprim`, `campo_desc1`, `campo_desc2`, `campo_desc3`, `campo_orderby`, `campo_orderbydir`, `max_rows`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paciente', 'Tabla', 'TBBDBENEFI', 145, 150, 147, NULL, NULL, 'DESC', 100, '2019-05-31 00:00:00', '2019-06-13 18:05:41'),
(5, 1, 'Cita', 'Tabla', 'TBAGCITAS', 179, NULL, NULL, NULL, 181, 'DESC', 100, '2019-06-06 14:42:51', '2019-06-28 15:14:46'),
(6, 1, 'Agenda', 'Tabla', 'TBAGHORARI', 218, NULL, NULL, NULL, NULL, 'DESC', 100, '2019-06-06 14:43:22', '2019-06-13 14:26:31'),
(7, 1, 'Ingreso Clinica', 'Tabla', '', NULL, NULL, NULL, NULL, NULL, 'DESC', 100, '2019-06-06 14:43:29', '2019-06-13 14:26:42'),
(8, 1, 'Orden Cx', 'Tabla', '', NULL, NULL, NULL, NULL, NULL, 'DESC', 100, '2019-06-06 14:43:36', '2019-06-13 14:26:50'),
(9, 1, 'Egreso Clinica', 'Tabla', '', NULL, NULL, NULL, NULL, NULL, 'DESC', 100, '2019-06-06 14:43:44', '2019-06-13 14:26:37'),
(10, 1, 'Profesional', 'Tabla', 'TBBDPROFE', 209, 210, NULL, NULL, NULL, 'DESC', 100, '2019-06-13 10:14:24', '2019-06-13 14:26:56'),
(11, 1, 'Entidad Plan', 'Vista', 'ZZVISTASAL.VTENTPLA', 211, 213, 215, NULL, NULL, 'DESC', 100, '2019-06-13 10:59:06', '2019-06-13 11:01:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_campos`
--

CREATE TABLE `sara_entidades_campos` (
  `id` int(11) NOT NULL,
  `entidad_id` int(11) NOT NULL,
  `Indice` int(11) NOT NULL,
  `Columna` varchar(100) NOT NULL,
  `Alias` varchar(100) DEFAULT NULL,
  `Tipo` varchar(30) NOT NULL DEFAULT 'Texto',
  `Defecto` varchar(255) DEFAULT NULL,
  `Requerido` tinyint(1) NOT NULL DEFAULT '1',
  `Visible` tinyint(1) NOT NULL DEFAULT '1',
  `Editable` tinyint(1) NOT NULL DEFAULT '1',
  `Buscable` tinyint(1) NOT NULL DEFAULT '0',
  `Op1` int(100) DEFAULT NULL,
  `Op2` int(100) DEFAULT NULL,
  `Op3` int(100) DEFAULT NULL,
  `Op4` varchar(50) DEFAULT NULL,
  `Op5` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_entidades_campos`
--

INSERT INTO `sara_entidades_campos` (`id`, `entidad_id`, `Indice`, `Columna`, `Alias`, `Tipo`, `Defecto`, `Requerido`, `Visible`, `Editable`, `Buscable`, `Op1`, `Op2`, `Op3`, `Op4`, `Op5`, `created_at`, `updated_at`) VALUES
(145, 1, 0, '.BECODBENE', 'Codigo Paciente', 'Entero', NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 11:59:20', '2019-06-06 15:53:31'),
(146, 1, 1, '.TDTIPDOC', 'Tipo Documento', 'Texto', NULL, 0, 1, 1, 0, NULL, 2, NULL, NULL, NULL, '2019-06-06 11:59:20', '2019-06-06 16:33:12'),
(147, 1, 2, '.BENUMDOCBE', 'Número Documento', 'Texto', NULL, 0, 1, 1, 0, NULL, 15, NULL, NULL, NULL, '2019-06-06 11:59:20', '2019-06-13 11:04:35'),
(150, 1, 4, 'FNNOMBBENE(.BECODBENE)', 'Paciente', 'Texto', NULL, 0, 1, 0, 0, NULL, 30, NULL, NULL, NULL, '2019-06-06 11:59:20', '2019-06-14 11:34:59'),
(152, 1, 5, '.BETELBENE', 'Teléfono', 'Texto', NULL, 0, 1, 1, 0, NULL, 15, NULL, NULL, NULL, '2019-06-06 11:59:20', '2019-06-13 11:05:27'),
(154, 1, 6, '.BECODESTAD', 'Estado', 'Texto', NULL, 0, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2019-06-06 11:59:20', '2019-06-13 11:05:27'),
(171, 1, 7, '.BETIPSEX', 'Sexo', 'Texto', NULL, 0, 1, 1, 0, NULL, 1, NULL, NULL, NULL, '2019-06-06 11:59:20', '2019-06-13 11:05:27'),
(172, 1, 8, '.BEFECNACI', 'Fecha Nacimiento', 'Fecha', NULL, 0, 1, 1, 0, NULL, NULL, NULL, 'Ymd', NULL, '2019-06-06 11:59:20', '2019-06-13 11:05:27'),
(177, 1, 9, '.BECORREO', 'Correo', 'Texto', NULL, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 11:59:20', '2019-06-13 11:05:27'),
(179, 5, 0, '.CICODCITA', 'Código Cita', 'Entero', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-07 11:58:25'),
(180, 5, 1, '.PRCODPROF', 'Profesional', 'Entidad', NULL, 1, 1, 1, 0, 10, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-13 10:18:20'),
(181, 5, 2, '.CIFECCITA', 'Fecha', 'Fecha', NULL, 1, 1, 1, 1, NULL, NULL, NULL, 'Ymd', NULL, '2019-06-06 16:02:43', '2019-06-26 17:18:47'),
(182, 5, 3, '.CIHORINI', 'Inicio', 'Hora', NULL, 1, 1, 1, 1, NULL, NULL, NULL, 'Hi', NULL, '2019-06-06 16:02:43', '2019-06-26 17:18:47'),
(183, 5, 4, '.CIHORFIN', 'Fin', 'Hora', NULL, 1, 1, 1, 1, NULL, NULL, NULL, 'Hi', NULL, '2019-06-06 16:02:43', '2019-06-26 17:18:47'),
(184, 5, 5, '.ENCODENTID || .EPCODPLAN', 'Entidad Plan', 'Entidad', NULL, 1, 1, 1, 0, 11, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-13 11:01:39'),
(186, 5, 7, '.BECODBENE', 'Paciente', 'Entidad', NULL, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-06 18:09:45'),
(187, 5, 8, '.CINUMTELE', 'Teléfono', 'Texto', NULL, 1, 1, 1, 0, NULL, 15, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-13 11:02:03'),
(188, 5, 9, '.CIESTCAP', NULL, 'Booleano', NULL, 1, 1, 1, 0, NULL, NULL, NULL, 'S', 'N', '2019-06-06 16:02:43', '2019-06-07 11:24:00'),
(189, 5, 10, '.CICLACITA', NULL, 'Entidad', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-07 11:24:00'),
(190, 5, 11, '.TCCODTCIT', NULL, 'Entidad', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-07 11:24:00'),
(191, 5, 12, '.TRCODTRAT', NULL, 'Entero', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-06 17:26:00'),
(192, 5, 13, '.ASCODAREA', NULL, 'Entidad', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-07 11:24:00'),
(193, 5, 14, '.TSCODSERV', NULL, 'Entidad', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-07 11:24:00'),
(194, 5, 15, '.CACODCENAT', 'Centro Atención', 'Entidad', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-25 17:56:20'),
(195, 5, 16, '.CIDESOBSE', NULL, 'TextoLargo', NULL, 0, 1, 1, 0, 1000, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-07 10:39:53'),
(196, 5, 17, '.MRCODCONS', NULL, 'Entero', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-07 10:56:36'),
(197, 5, 18, '.COCODCTO', NULL, 'Entero', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-06 17:26:01'),
(198, 5, 19, '.HOCODHORA', 'Agenda', 'Entidad', NULL, 1, 1, 1, 0, 6, NULL, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-13 13:42:26'),
(199, 5, 20, '.MOCODMOTIV', NULL, 'Texto', NULL, 1, 1, 1, 0, NULL, 4, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-06 17:26:01'),
(200, 5, 21, '.CICODESTAD', 'Estado', 'Texto', NULL, 1, 1, 1, 0, NULL, 1, NULL, NULL, NULL, '2019-06-06 16:02:43', '2019-06-25 14:37:26'),
(209, 10, 0, '.PRCODPROF', 'Cod. Profesional', 'Entero', NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 10:15:25', '2019-06-13 10:15:25'),
(210, 10, 1, '.PRNOMPROF', 'Profesional', 'Texto', '', 1, 1, 0, 1, NULL, 50, NULL, NULL, NULL, '2019-06-13 10:15:25', '2019-06-13 10:17:47'),
(211, 11, 0, '.ID_ENTIDADPLAN', 'Cod. Entidad Plan', 'Texto', '', 1, 1, 0, 0, NULL, 8, NULL, NULL, NULL, '2019-06-13 10:59:46', '2019-06-13 11:01:25'),
(212, 11, 1, '.ENCODENTID', 'Cód. Entidad', 'Texto', '', 1, 1, 0, 0, NULL, 3, NULL, NULL, NULL, '2019-06-13 10:59:46', '2019-06-13 11:01:25'),
(213, 11, 2, '.ENTIDAD', 'Entidad', 'Texto', NULL, 0, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 10:59:46', '2019-06-13 11:01:26'),
(214, 11, 3, '.EPCODPLAN', 'Cod. Plan', 'Entero', NULL, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 10:59:46', '2019-06-13 11:01:26'),
(215, 11, 4, '.PLAN', 'Plan', 'Texto', NULL, 0, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 10:59:46', '2019-06-13 11:01:26'),
(216, 11, 5, '.EPTIPPLAN', 'Tipo Plan', 'Entero', NULL, 0, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 10:59:46', '2019-06-13 11:01:26'),
(217, 1, 3, '.TDTIPDOC || \':\' || .BENUMDOCBE', 'Documento', 'Texto', NULL, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 11:05:02', '2019-06-13 11:05:27'),
(218, 6, 0, '.HOCODHORA', 'Cod. Horario', 'Entero', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 13:18:12', '2019-06-13 13:22:15'),
(219, 6, 1, '.PRCODPROF', 'Profesional', 'Entidad', NULL, 1, 1, 1, 0, 10, NULL, NULL, NULL, NULL, '2019-06-13 13:18:12', '2019-06-13 13:22:15'),
(220, 6, 2, '.HOFECHORA', 'Fecha', 'Fecha', '', 1, 1, 1, 0, NULL, NULL, NULL, 'Ymd', NULL, '2019-06-13 13:18:12', '2019-06-13 13:18:12'),
(221, 6, 3, '.HOHORINI', 'Hora Inicial', 'Hora', NULL, 1, 1, 1, 0, NULL, NULL, NULL, 'Hi', NULL, '2019-06-13 13:18:12', '2019-06-13 13:22:15'),
(222, 6, 4, '.HOHORFIN', 'Hora Final', 'Hora', NULL, 1, 1, 1, 0, NULL, NULL, NULL, 'Hi', NULL, '2019-06-13 13:18:12', '2019-06-13 13:22:15'),
(223, 6, 5, '.COCODCONS', 'Cod. Consultorio', 'Texto', '', 1, 1, 1, 0, NULL, 5, NULL, NULL, NULL, '2019-06-13 13:18:12', '2019-06-13 13:22:15'),
(224, 6, 6, '.HOPORDISPO', 'Disponibilidad', 'Texto', NULL, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 13:18:12', '2019-06-13 13:22:15'),
(225, 6, 7, '.MOCODMOTIV', 'Motivo', 'Entero', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 13:18:12', '2019-06-13 13:22:15'),
(226, 6, 8, '.HOCODESTAD', 'Estado', 'Entero', NULL, 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2019-06-13 13:18:12', '2019-06-13 13:22:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_grids`
--

CREATE TABLE `sara_entidades_grids` (
  `id` int(11) NOT NULL,
  `entidad_id` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_entidades_grids`
--

INSERT INTO `sara_entidades_grids` (`id`, `entidad_id`, `Titulo`, `created_at`, `updated_at`) VALUES
(1, 5, 'Citas Recientes', '2019-06-06 17:45:23', '2019-06-26 15:02:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_grids_columnas`
--

CREATE TABLE `sara_entidades_grids_columnas` (
  `id` int(11) NOT NULL,
  `grid_id` int(11) NOT NULL,
  `Indice` int(11) NOT NULL,
  `Cabecera` varchar(255) DEFAULT NULL,
  `Tipo` varchar(20) NOT NULL DEFAULT 'Campo',
  `Ruta` text,
  `Llaves` text,
  `Visible` tinyint(1) NOT NULL DEFAULT '1',
  `campo_id` int(11) DEFAULT NULL,
  `externalgrid_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_entidades_grids_columnas`
--

INSERT INTO `sara_entidades_grids_columnas` (`id`, `grid_id`, `Indice`, `Cabecera`, `Tipo`, `Ruta`, `Llaves`, `Visible`, `campo_id`, `externalgrid_id`, `created_at`, `updated_at`) VALUES
(61, 1, 0, 'Fecha de Cita', 'Campo', '[5]', '[null]', 1, 181, NULL, '2019-06-17 14:19:49', '2019-06-28 15:42:53'),
(86, 1, 6, NULL, 'Campo', '[5,10]', '[null,180,210]', 1, 210, NULL, '2019-06-27 16:09:46', '2019-06-28 15:42:54'),
(88, 1, 3, NULL, 'Campo', '[5,1]', '[null,186,217]', 1, 217, NULL, '2019-06-27 16:38:26', '2019-06-28 15:42:54'),
(89, 1, 4, NULL, 'Campo', '[5,1]', '[null,186,150]', 1, 150, NULL, '2019-06-27 16:38:28', '2019-06-28 15:42:54'),
(90, 1, 5, NULL, 'Campo', '[5,1]', '[null,186,171]', 1, 171, NULL, '2019-06-27 16:38:34', '2019-06-28 15:42:54'),
(91, 1, 7, 'Centro', 'Campo', '[5]', '[null]', 1, 194, NULL, '2019-06-28 15:10:49', '2019-06-28 15:10:49'),
(92, 1, 1, NULL, 'Campo', '[5]', '[null]', 1, 182, NULL, '2019-06-28 15:42:42', '2019-06-28 15:42:54'),
(93, 1, 2, NULL, 'Campo', '[5]', '[null]', 1, 183, NULL, '2019-06-28 15:42:43', '2019-06-28 15:42:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_grids_filtros`
--

CREATE TABLE `sara_entidades_grids_filtros` (
  `id` int(11) NOT NULL,
  `grid_id` int(11) NOT NULL,
  `columna_id` int(11) NOT NULL,
  `Indice` int(11) NOT NULL,
  `Comparador` varchar(255) NOT NULL,
  `Valor` varchar(255) DEFAULT NULL,
  `Op1` varchar(255) NOT NULL,
  `Op2` varchar(255) NOT NULL,
  `Op3` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_entidades_grids_filtros`
--

INSERT INTO `sara_entidades_grids_filtros` (`id`, `grid_id`, `columna_id`, `Indice`, `Comparador`, `Valor`, `Op1`, `Op2`, `Op3`, `created_at`, `updated_at`) VALUES
(15, 1, 61, 0, '=', '-2 days', 'rel', '', 0, '2019-06-28 16:01:53', '2019-06-28 16:40:19'),
(17, 1, 91, 2, 'radios', NULL, '', '', 0, '2019-06-28 16:42:59', '2019-06-28 16:58:36'),
(18, 1, 86, 1, 'lista', NULL, '', '', 0, '2019-06-28 16:58:29', '2019-06-28 16:58:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_restricciones`
--

CREATE TABLE `sara_entidades_restricciones` (
  `id` int(11) NOT NULL,
  `entidad_id` int(11) NOT NULL,
  `campo_id` int(11) NOT NULL,
  `Comparador` varchar(50) NOT NULL,
  `Valor` varchar(255) DEFAULT NULL,
  `Op1` varchar(255) NOT NULL,
  `Op2` varchar(255) NOT NULL,
  `Op3` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_entidades_restricciones`
--

INSERT INTO `sara_entidades_restricciones` (`id`, `entidad_id`, `campo_id`, `Comparador`, `Valor`, `Op1`, `Op2`, `Op3`, `created_at`, `updated_at`) VALUES
(18, 5, 200, '=', 'A', '', '', 0, '2019-06-26 14:10:52', '2019-06-26 14:10:59'),
(19, 5, 186, 'no_nulo', NULL, '', '', 0, '2019-06-26 14:33:00', '2019-06-26 14:33:04'),
(22, 5, 194, 'no_nulo', NULL, '', '', 0, '2019-06-28 17:01:30', '2019-06-28 17:01:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_secciones`
--

CREATE TABLE `sara_secciones` (
  `id` varchar(50) NOT NULL,
  `Seccion` varchar(50) NOT NULL,
  `Orden` int(10) NOT NULL,
  `Icono` varchar(30) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_secciones`
--

INSERT INTO `sara_secciones` (`id`, `Seccion`, `Orden`, `Icono`, `Estado`) VALUES
('Apps', 'Apps', 20, 'fa-cubes', 'A'),
('BDD', 'Conexiones', 5, 'fa-database', 'A'),
('Entidades', 'Entidades', 10, 'fa-chess', 'A'),
('Roles', 'Roles', 31, 'fa-user-tag', 'A'),
('Usuarios', 'Usuarios', 30, 'fa-users', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_usuarios`
--

CREATE TABLE `sara_usuarios` (
  `id` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(70) NOT NULL,
  `Cedula` varchar(30) DEFAULT NULL,
  `Nombres` varchar(255) NOT NULL,
  `CDC_id` int(11) DEFAULT NULL,
  `isGod` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sara_usuarios`
--

INSERT INTO `sara_usuarios` (`id`, `Email`, `Password`, `Cedula`, `Nombres`, `CDC_id`, `isGod`, `created_at`, `updated_at`) VALUES
(1, 'aacosta@comfamiliar.com', '$2y$10$pzrtN.5XpDWSJrR9r4z6aeYWEbtQNlBP6/SMr0mRl.S/HlYosXrNC', '52039720', 'Adriana Maria Acosta Martinez', 5366, 0, '2018-02-09 12:25:12', '2018-10-01 19:38:21'),
(2, 'aagudelo@comfamiliar.com', '$2y$10$QfsB.ZpqPT9JE8vH5U0xq.4Ik4abMpxw31lQNeGgooPxW3CMOvS6G', '24686029', 'Alba Liria Agudelo Gutierrez', 2338, 0, '2018-02-14 20:35:03', '2018-04-17 19:06:51'),
(3, 'aaragon@comfamiliar.com', '$2y$10$cwur3Op8JtTAwMCOu4PMpe5iPJ.ipllxWanAYanYgUkwvdfdw0n0q', '42094112', 'Adriana Aragon Ospina', NULL, 0, '2018-07-05 08:07:47', '2018-07-05 08:07:47'),
(4, 'aarango@comfamiliar.com', '$2y$10$kGzFkFrrWfu/DKIVUzwY8eznmAN3sW/ckPSqQ5ImuhGwIxP1EFDDq', '42068049', 'Alba Lucía Arango Giraldo', 2326, 0, '2018-01-29 13:09:10', '2018-04-17 19:06:51'),
(5, 'aarenasa@comfamiliar.com', '$2y$10$A0a0Wv.N4DbAJybspCfHEug/MPsVYHBP7hZqvtfIbMmtL1kD5PpBu', '10139212', 'Alcides Arenas Aranzazu', NULL, 0, '2018-07-26 01:12:14', '2018-07-26 01:12:14'),
(6, 'aarias@comfamiliar.com', '$2y$10$3R3kQslssmU8TVtg0LnikuC3yUbrUfxOMDdmWVz6G/psO5CDRcP6a', '42120915', 'Angela Cristina Arias Hernandez', NULL, 0, '2018-04-20 15:05:29', '2018-04-20 15:05:29'),
(7, 'aariasm@comfamiliar.com', '$2y$10$MgBo1Uv8RUZn/m65x0m5seuJ6ycv8Ugot1r2WbR/UkgMNkzAUpNT.', '1225092197', 'Andres Felipe Arias Mejia', 5180, 0, '2018-03-05 14:25:10', '2018-04-17 19:06:51'),
(8, 'aaricapa@comfamiliar.com', '$2y$10$j1KB8ZxzS4zcrQsogtdxcuNVOsx6YBwrgSTj8zTpjvGpAfbhyFfIO', '1088237787', 'Andres Felipe Aricapa Yepes', NULL, 0, '2019-02-01 18:17:28', '2019-02-01 18:17:28'),
(9, 'aavila@comfamiliar.com', '$2y$10$HN2ktCiavkLNniH4q7Byk.CGwqgmC3p.H.2UhPX1dTbmIDKudKxDG', '1053857532', 'Angie Nayibe Avila Marin', 5349, 0, '2018-04-01 21:34:45', '2018-04-17 19:06:51'),
(10, 'abaez@comfamiliar.com', '$2y$10$hJuYCReWsP7VhZNKLaVks./TtIZ6edXmQrZMS/B4Na0V83TxFCXQi', '1088262529', 'Andres Alejandro Baez ', NULL, 0, '2018-06-17 01:02:57', '2018-06-17 01:02:57'),
(11, 'abedoyas@comfamiliar.com', '$2y$10$/cEryybweaaRk/nsO0Wb1./69AvgT2y67xBgyvHy8fDB/Dmtc1kbK', '42125912', 'Adriana Bedoya Ossa', NULL, 0, '2018-07-13 11:55:15', '2018-07-13 11:55:15'),
(12, 'abenavides@comfamiliar.com', '$2y$10$qmu/c2szZCQOUsZ0ZTd.y.OldMSZNojN0CYENTIHHXvS3eWGYRQLS', '42142739', 'Andrea Patricia Benavides Rivera', NULL, 0, '2018-08-23 15:12:30', '2018-08-23 15:12:30'),
(13, 'abetancur@comfamiliar.com', '$2y$10$RXUI5L2S4bVPDyAELbWC1eA5zFHW4G12Vx.QoUG.eQdL/oU90.4C2', '1088249025', 'Astrid Jhoana Betancur', 3211, 0, '2018-02-05 23:02:35', '2018-04-17 19:06:52'),
(14, 'abetancurs@comfamiliar.com', '$2y$10$Y1fMNW4Q6cpHuVIxzmxqNOkusMKe0bHc2hiToIVdHcmMDNvCRjrwq', '1060647765', 'angela maria betancur serna', 5150, 0, '2018-01-26 22:33:08', '2018-05-18 21:51:17'),
(15, 'abotina@comfamiliar.com', '$2y$10$2ybWLSTqniqhNIGoPXgawey6nvGlnzP8L7TUJzRcQrg5r0Ab0Md/C', '1085287975', 'Ana Melissa Botina Luna', NULL, 0, '2018-04-25 19:15:21', '2018-04-25 19:15:21'),
(16, 'abuitragog@comfamiliar.com', '$2y$10$Eh0ox5DmZST/9xJ4wIFQ1epRjXRGRRj0EeuxqZSIWhH92cI1KpWBe', '1057757942', 'Alba Marcela Buitrago Giraldo', NULL, 0, '2019-01-21 16:57:14', '2019-01-21 16:57:14'),
(17, 'acardonac@comfamiliar.com', '$2y$10$jmULcsVT6CtvilUHGLtGHeMa.QLNrg3pSx7jKx69skvUlOk9.I3De', '1093213677', 'Angelica Maria Cardona Castro', 5240, 0, '2018-03-22 15:00:25', '2018-04-17 19:06:52'),
(18, 'acarrascal@comfamiliar.com', '$2y$10$UnUGWZehNUSYj6.w4BdrIuxjIyZPvx7GJU0TTKiIC7wLar7GaxauW', '1053822625', 'Andres Felipe Carrascal Bolaños', 3101, 0, '2018-01-29 19:16:12', '2018-04-17 19:06:52'),
(19, 'acasas@comfamiliar.com', '$2y$10$y9CVTH4e2fJfiKeQE2w0cOBMKIKwxUsavyXSLnU2uganx2Z1U3un6', '42128082', 'Aida Yamileth Casas Ramirez', 2310, 0, '2018-01-29 14:51:48', '2018-04-17 19:06:52'),
(20, 'achavarro@comfamiliar.com', '$2y$10$RjBfkzbrgh74GNpHkIo1JO..fQ/y1R1Dx3Iii.GqC7ueewjKthq4G', '1088340871', 'Ana Maria Chavarro Agudelo', NULL, 0, '2018-05-11 23:19:57', '2018-05-11 23:19:57'),
(21, 'acuenca@comfamiliar.com', '$2y$10$ZoyziSCCH0Nw4VWEp7tgweT7S8JvH9yifwdAGREFgAU71mf.bA0KG', '1088009325', 'Anny Julieth Cuenca Ortiz', 800, 0, '2018-02-14 12:48:54', '2018-04-17 19:06:54'),
(22, 'adavila@comfamiliar.com', '$2y$10$6EDTMhprRPaY1rPT3LvuVeLofMbM3Kwho5tfSJsVZFXkMwYOLkln.', '42117082', 'Alba Nidia Davila', 3720, 0, '2018-01-11 12:57:13', '2019-04-02 14:43:46'),
(23, 'adiazg@comfamiliar.com', '$2y$10$DrnSUKacZltx4WCpiycd4usDCx.09rUfqKiB/lohiW6013cblA1/G', '18514075', 'Andres Alberto Diaz Gallego', 2310, 0, '2018-02-27 21:45:49', '2018-04-17 19:06:54'),
(24, 'aerazo@comfamiliar.com', '$2y$10$Vn1UMHXpk1rob/5UwehkA.kxrdNojz7XEZnedVxoTmrwre/MkHsJ6', '25180575', 'Astrid Carolina Erazo Ordoñez', 2140, 0, '2018-02-06 19:25:40', '2018-04-17 19:06:54'),
(25, 'afernandez@comfamiliar.com', '$2y$10$A2kETtPqipwtrED/K89.buUnSwiI5PUXguK2sMh37B5fiPzAjeF8S', '1088326519', 'Anyi Vanessa Fernandez Marin', NULL, 0, '2018-09-25 14:04:29', '2018-09-25 14:04:29'),
(26, 'afrancoc@comfamiliar.com', '$2y$10$fSTdWyWLFPwQRjPXukl4J.28VJj7aRztoqRdxwdLYW1jd4xRD7RZu', '1088015410', 'Andres Felipe Franco Correa', 3729, 0, '2018-02-15 16:50:40', '2018-05-02 16:50:13'),
(27, 'afrancom@comfamiliar.com', '$2y$10$3dsJn72ncRDK0GtkAvq7z.k4olXQDiU2OpIbmq//1iKRgsQeaUdE.', '1090335249 ', 'Ana Milena Franco Muriel', 5240, 0, '2018-02-15 22:38:59', '2018-04-17 19:08:55'),
(28, 'agallegod@comfamiliar.com', '$2y$10$fYUMR9FzdU3CBGzH4NT1MurhLqhvd14ROEdNQ/Nr9dYxOvWMNpLsi', '1088030675', 'Alejandro Gallego Davila', NULL, 0, '2018-04-20 09:17:09', '2018-04-20 09:17:09'),
(29, 'agarciar@comfamiliar.com', '$2y$10$6CwXNh2AF5mHJcWmDIEOdONoichyDTnHyDmPkoFm3gGdE6774Xe12', '42153471', 'Andrea del pilar Garcia Ruiz', 2140, 0, '2018-02-06 13:10:50', '2019-01-31 19:23:04'),
(30, 'agomez@comfamiliar.com', '$2y$10$lro2Bfo5GpxuHKpS16pPbudWgOjH400wEz98jlwPWsHsG7p30Flpu', '18518773', 'Alejandro Gomez Martinez', NULL, 0, '2018-06-26 20:04:03', '2018-06-26 20:04:03'),
(31, 'agrisalesn@comfamiliar.com', '$2y$10$GEJMUgg5sa6UINDLmQEuB.pSXVeDoQuDIw3/XTIE59hDRdPM6NQeG', '42016373', 'Arelis Grisales Narvaez', NULL, 0, '2018-10-02 15:20:26', '2018-10-02 15:20:26'),
(32, 'agutierrez@comfamiliar.com', '$2y$10$nap6274tVbVeDmbvENzH1eVJb0gHfMkT78pi926YVMUJ/EgGUsjkC', NULL, 'agutierrez', 700, 0, '2018-01-11 15:59:56', '2018-01-11 15:59:56'),
(33, 'agutierrezl@comfamiliar.com', '$2y$10$j5kBO2KcePGowr2KSvUCxuF4lk.Z1YlYdqtRXHEn86KqA63X2GSiq', '25172103', 'Ana Maria Gutierrez Londono', 3200, 0, '2018-01-31 21:26:31', '2018-04-17 19:08:55'),
(34, 'ahenao@comfamiliar.com', '$2y$10$EkOQaNW0B5fag1ZwiGbaH.A2gc/8pkaazNFOpKCmw/vkvLzmeu8Ia', '42119063', 'Ana Maria Henao Cardona', 5315, 0, '2018-04-15 09:59:53', '2018-04-17 19:08:55'),
(35, 'ahernandezz@comfamiliar.com', '$2y$10$Q6C06fVz6kwYEw9vaiLBRedIl5k6hrsRuLjyYht/Z2/es5KyzCJaa', '1088326213', 'Alejandra Hernandez Zapata', 5315, 0, '2018-03-22 23:32:11', '2018-04-17 19:08:55'),
(36, 'ahoyosc@comfamiliar.com', '$2y$10$iNJTG5gcbECaTPQQAEbVp.aWiCh0S.ASS89EXOfxahQEBQ/J3OaLW', '66873845', 'Ayde Lorena Hoyos Castillo', NULL, 0, '2019-01-25 06:16:48', '2019-01-25 06:16:48'),
(37, 'ajaramillo@comfamiliar.com', '$2y$10$whCqwT4jgBxZRlPdutVIEetn.5ZDAev1s4ScMQwzV.y0QFO2i3B5S', '24548288', 'Adiela Jaramillo', NULL, 0, '2018-04-23 12:15:46', '2018-04-23 12:15:46'),
(38, 'AJZAPATA@comfamiliar.com', '$2y$10$cVnzPZNk/n3p10FtrWG8aetHlsuEoLWVhoWtPBhZ23zlKX/yXNeqC', '33945343', 'Angela Juliana Zapata Cuervo', 5370, 0, '2018-04-03 06:35:46', '2018-04-17 19:08:56'),
(39, 'alargo@comfamiliar.com', '$2y$10$6FeitADAT0.BvVVqQEt2Q.peGJMyzjqlaq.F.BQigallxyyi0744e', '42027131', 'Amalfy Largo Herrera', NULL, 0, '2018-08-04 05:20:42', '2018-08-04 05:20:42'),
(40, 'alecastano@comfamiliar.com', '$2y$10$KmK.qfy6qaqrGilIdbC1c.Q6418kbSXAxqVK3gGLlNFT6DB6pXlQa', '41404568', 'Aleyda Castaño Cardona', 3101, 0, '2018-02-12 12:34:52', '2018-04-17 19:08:56'),
(41, 'alema@comfamiliar.com', '$2y$10$KWdotgeYRUmqajAK6oJozuyc1Tv0kPJj85/a5i7rJ4NSD2N4PvHtu', '4577627', 'Alvaro Lema Velasquez', NULL, 0, '2019-01-02 13:21:04', '2019-01-02 13:21:04'),
(42, 'allanos@comfamiliar.com', '$2y$10$Bf2WtG3p8N0ge3U14ZNVveOyuC7XAoysAYiFMGbXEnpF2J1JryG66', '1093224186', 'Andres Giovany Llanos Osorio', NULL, 0, '2018-07-09 16:45:33', '2018-07-09 16:45:33'),
(43, 'aloaiza@comfamiliar.com', '$2y$10$tK43/rfteyxjr7cDe1R46e0KmuLqhSSwh.mhnOOXXw8pr8xVajSQm', '42110004', 'Alix Loaiza Perez', NULL, 0, '2018-07-17 13:55:03', '2018-07-17 13:55:03'),
(44, 'alondonof@comfamiliar.com', '$2y$10$5JjAmmf9tklnBxFbINUBAOfBoc.67RZxbTQyjky57A0S48luX6Ou2', '1097388737', 'Alexander Londoño Franco', 5346, 0, '2018-02-09 16:11:11', '2018-04-17 19:08:56'),
(45, 'alopez@comfamiliar.com', '$2y$10$GdUMy/a.R.gldTsSJgAdGezrxU9CKixAtoG9Np7etHe6GHQeL.746', '42143107', 'Angela María Lopez Peréz', NULL, 0, '2018-08-24 21:12:32', '2018-08-24 21:12:32'),
(46, 'amarina@comfamiliar.com', '$2y$10$iJN8WigCD6Ac2HlkWgblv.51zAgwfCl.lk1JxJx4sBh.uBrguxsgq', '25001004', 'ana delia marin alarcon', NULL, 0, '2018-10-20 04:38:54', '2018-10-20 04:38:54'),
(47, 'amaring@comfamiliar.com', '$2y$10$j19cgd0f90M3UXq6V8Stbu3brkqL0fLo.rKA/bmoB8F0HZeHi6RKe', '4583671', 'Andres Felipe Marin Gonzalez', NULL, 0, '2018-10-14 15:05:11', '2018-10-14 15:05:11'),
(48, 'amarinm@comfamiliar.com', '$2y$10$UFV0cm6NZehfrofi43ZjdeR34IrWeFR2BB/tIiFUHHYR3EVgx0IEK', '42125142', 'Amanda Liliana Marin Muriel', NULL, 0, '2019-02-27 19:01:35', '2019-02-27 19:01:35'),
(49, 'amarmolejo@comfamiliar.com', '$2y$10$yMV6MzmTS3BR8P1ITsZBnuJxKG.nWCnsXeiWr1zgy3SR6eQ6txBMK', '1099320648', 'Alexandra Marmolejo Calderon', 900, 0, '2018-02-05 14:22:41', '2018-04-17 19:08:56'),
(50, 'amartinez@comfamiliar.com', '$2y$10$EPlRPkms/6eBp6SDrloumuqQkSG15UkzrjsAxMrUA8188bMNz92nO', '42095877', 'María Angelica Martinez Echeverry', 3729, 0, '2018-04-10 13:05:59', '2018-04-17 19:08:56'),
(51, 'amartineza@comfamiliar.com', '$2y$10$amagruvXX6jX7n2FIXWFa.945.4OcAA7C2vRC8YjxcUaT5T1n16w2', '42151727', 'Angela Maria Martinez Avila', 5370, 0, '2018-03-05 03:31:31', '2018-04-17 19:09:02'),
(52, 'amartinezd@comfamiliar.com', '$2y$10$CJnpYCV7N2vFmXWcfICjOOOF2KY5IEk0kUSVAoG.3y59B0AAmujBK', '1093229035', 'Angie Vanessa Martinez Duque', NULL, 0, '2018-08-03 19:31:19', '2018-08-03 19:31:19'),
(53, 'ameurer@comfamiliar.com', '$2y$10$tOE3NIX5qzeVhx8Q7rRiK.2S18/LZQScoBAUVXpK8dGf8UDae0NKa', '1088024096', 'Andrea Estefania Meurer ', NULL, 0, '2018-06-09 04:53:59', '2018-06-09 04:53:59'),
(54, 'ammarin@comfamiliar.com', '$2y$10$tn/68IrzzwbHd4ujQcT5c.trDZxhyG09jBEuitRYYHslHNzDZSlGe', '10122316', 'Angel Maria Marin Henao', 3300, 0, '2018-02-07 19:31:59', '2018-04-17 19:09:02'),
(55, 'amolano@comfamiliar.com', '$2y$10$sECBPtyG29CD1uhjFExRdOeD3cb5D62Kng23I3Mg50Xj.UjwzuT.O', '1088326893', 'Angelica Maria Molano Grajales', NULL, 0, '2018-12-12 18:31:18', '2018-12-12 18:31:18'),
(56, 'amolina@comfamiliar.com', '$2y$10$goAR5wlHGq4swbMxwI8GfuYiTcCe98nMdNyrGuMprbWUUaViCPMYK', '1088259018', 'Adriana Molina Rengifo', NULL, 0, '2018-05-01 11:26:51', '2018-05-01 11:26:51'),
(57, 'amontero@comfamiliar.com', '$2y$10$Zo15v8/sElCyImiyl7dG0.jZJAnY9PrlJ0diafQDerZk7DC2bNuH2', '1088021080', 'Aura Margarita Montero Prado', NULL, 0, '2018-09-10 15:25:35', '2018-09-10 15:25:35'),
(58, 'amorales@comfamiliar.com', '$2y$10$XACw9BjiaOtiFuwwqv5rNeMTVTPTUw4PYtI7wyCTZ/SyXlkO6XmRK', '1088249630', 'Alejandra Morales Arias', 5316, 0, '2018-03-28 23:36:34', '2018-04-17 19:09:02'),
(59, 'amoralesg@comfamiliar.com', '$2y$10$YazqNzdRKIkhhiZEwVkOz.6HsDMSVQ3pWbJD/hkwcrZlIKcCfevNC', '42015982', 'Alejandra Maria Morales Giraldo', 3011, 0, '2018-02-08 20:49:56', '2018-04-17 19:09:02'),
(60, 'amorenoc@comfamiliar.com', '$2y$10$zZCpik2CdIDKUSNe/DyhXuHTLuqifsUqx8b5YQVotJwMfemFhL9JK', '1088249606', 'Andrea Maria Moreno Cardenas', 5370, 0, '2018-03-04 01:15:50', '2018-04-17 19:09:02'),
(61, 'amorenop@comfamiliar.com', '$2y$10$BP04jXNTRL5CFaViKdG0LOUt5LE21CUsZyayRUPVxqAPhALvvr7vO', '1088257366', 'Angelica Moreno Posada', NULL, 0, '2018-04-18 18:36:38', '2018-04-18 18:36:38'),
(62, 'amosorio@comfamiliar.com', '$2y$10$SjDdCgueJuhDiJN5H98eau.i5UC0o8VmE8cb3Fnc02ytqD5Gq8RtK', '1087991115', 'Angela Maria Osorio Sanchez', NULL, 0, '2019-01-14 15:25:36', '2019-01-14 15:25:36'),
(63, 'amosquerav@comfamiliar.com', '$2y$10$NAe5Ki7Nh.J9QRxFP3XBi.O7886wKCMCN4RRFHQQhEhQY0gEM4/za', '1088315555', 'Angie Lizeth Mosquera Velez', NULL, 0, '2018-07-10 15:01:34', '2018-07-10 15:01:34'),
(64, 'amunozs@comfamiliar.com', '$2y$10$njVCmAf.gvUvwL4WHPRIJ.bQiI5ck/KAOYABq.XpxFA7fj0ZIoCG2', '1116240664', 'Alejandra Muñoz Sanabria', NULL, 0, '2019-03-08 23:21:42', '2019-03-08 23:21:42'),
(65, 'angarcia@comfamiliar.com', '$2y$10$dkBOcWUglcn650nfnCgWwegR75buKrrpyGm2myMT..nfdjtwwW5ky', '30359875', 'Angela Maria Garcia ', 5240, 0, '2018-04-16 09:36:42', '2018-11-20 07:54:37'),
(66, 'angsanchez@comfamiliar.com', '$2y$10$NFxCPi0ROKTM7uFtdVGr4.F9Isf0t54lTuJgK6mW1shnC1T2j4M0O', '42159480', 'Angela Maria Sanchez Lopez', 5341, 0, '2018-03-08 08:39:37', '2018-04-17 19:09:02'),
(67, 'anorena@comfamiliar.com', '$2y$10$X3DUGrvSUU5tsfJxaMjm3ur7M4qCjchgvExZCZJItEUXa0iiFZ4KW', '1088279734', 'Alejandra Noreña Herrera', NULL, 0, '2018-12-27 21:46:33', '2018-12-27 21:46:33'),
(68, 'aocampo@comfamiliar.com', '$2y$10$.75CIgam81Eaasw7lOY2OuSNU/YlDV6v9JReV5/hDERHN3tWjyMz6', '30283158', 'Alba Lucia Ocampo Florez', 3796, 0, '2018-04-02 16:33:25', '2018-09-17 19:10:43'),
(69, 'aocampoo@comfamiliar.com', '$2y$10$TIC396p1IBCyAxkzfziRyektsdWbjlH8/tDZ/cNZLfvDTxfbTiq2S', '67040078', 'Angelica Maria Ocampo Ospina', NULL, 0, '2018-04-27 12:13:53', '2019-05-16 16:33:00'),
(70, 'aorozco@comfamiliar.com', '$2y$10$ABAZHRYbmds78zR8fSDVgO5u5p.qUmapDh1FNnADsYq1TTiEe/DpW', '10011482', 'Andres Orozco Escobar', 3730, 0, '2018-01-31 14:37:15', '2018-04-17 19:09:02'),
(71, 'aospina@comfamiliar.com', '$2y$10$NoUs9QEAwb8q6FKc8inWkOeBeqxWdGJg0DuJbt3reTUGst4f7Xry2', '24339417', 'Alejandra Ospina Rodriguez', NULL, 0, '2018-07-28 23:33:50', '2018-07-28 23:33:50'),
(72, 'aospinam@comfamiliar.com', '$2y$10$xO7LzI7z.BJ6XUfcoZcI7.jY/kagGus47eQJMUPlCz.xXew41cHbu', '1088314366', 'Andrea Ospina Muñoz', NULL, 0, '2018-06-13 22:00:46', '2018-06-13 22:00:46'),
(73, 'aparra@comfamiliar.com', '$2y$10$8yi8eQ9LA2Pqj1VabZmD8.xXMH8LVZXc1rZGb0TeTPGrVMrM9StZ2', '42101150', 'Ana Ruth Parra Alzate', 5344, 0, '2018-03-04 07:39:30', '2018-04-17 19:09:03'),
(74, 'apatino@comfamiliar.com', '$2y$10$yar0/iGfph3I4ntsYFNVou9qq25LSglaD3zKkMfDI5mDKowDy.3xu', '30356396', 'Angela Maria Patiño Valvuena', NULL, 0, '2018-06-16 01:55:09', '2018-06-16 01:55:09'),
(75, 'apedraza@comfamiliar.com', '$2y$10$if5WnUDB.hbXbtTFpj.ygOxrEPCMrOLYoUbc/L2RhiDoDYxEH2ujO', '65734381', 'Adriana Pedraza Martinez', 5171, 0, '2018-02-17 07:25:38', '2018-04-17 19:09:08'),
(76, 'aposada@comfamiliar.com', '$2y$10$j3qJp8GoGfHSNrZG2MowU.3w611A2/m/oYZWSXBoKNgtEZloEpH0G', '1088296480', 'Angee Vanessa Posada Oviedo', 2326, 0, '2018-01-16 12:57:08', '2018-04-17 19:09:08'),
(77, 'aposadar@comfamiliar.com', '$2y$10$1qc7it8Kf2BrbWkDWYy7wOYpwK4006Lpbz1GutRP.aZHX.t8.hvnO', '30291192', 'Alba Rocio Posada Ramirez', 3101, 0, '2018-03-06 16:41:17', '2018-04-17 19:09:08'),
(78, 'arestrepod@comfamiliar.com', '$2y$10$HLeUGOhasRK/BwbYDTec5ewl7brZT4xDZpOn/FyCPrZuRP0AYcRkW', '1087996562', 'Angelly Restrepo Davila', 3206, 0, '2018-02-05 20:08:35', '2018-04-17 19:09:08'),
(79, 'ariascos@comfamiliar.com', '$2y$10$M9jH4O97H0ooTWgEzFASmeqV4DETyh5bNGiZgDwfTRgQaEoNWtkKy', '1093229358 ', 'Andredison Riascos Henao', 3101, 0, '2018-01-29 19:14:21', '2018-04-17 19:09:08'),
(80, 'arincon@comfamiliar.com', '$2y$10$fvQLECEiurqRPMimB.uv9u0i/DdjyBEYWel0.1iIygmyeSCnaKM2.', '1096646317', 'Angie Daniela Rincon Jaramillo', NULL, 0, '2018-06-29 18:00:05', '2018-06-29 18:00:05'),
(81, 'ariosm@comfamiliar.com', '$2y$10$dWHEoup5uz/vWsoUr2MWseDbJjJu6h1P8n7ekyt5yOJX7f3prKdxe', '24695792', 'Angela Andrea Rios Moreno', 5372, 0, '2018-03-17 04:53:29', '2018-04-17 19:09:08'),
(82, 'ariveraq@comfamiliar.com', '$2y$10$6C5.xeVAnGvz7T4Cc6Auxu0NWYdJmvQa/J/qf08dD6oLj70zC2pm.', '1088013285', 'Andres Mauricio Rivera Quiceno', NULL, 0, '2018-11-23 21:50:32', '2018-11-23 21:50:32'),
(83, 'arodriguez@comfamiliar.com', '$2y$10$DDmi/FgjB5RMQ/lBhnLM1.lxt.NIX/zfwV2k0yEQnR..fMQkNyzTq', '10004455', 'Alvaro Enrique Rodriguez Campeon', NULL, 0, '2019-01-29 22:41:36', '2019-01-29 22:41:36'),
(84, 'arodriguezo@comfamiliar.com', '$2y$10$WJM69CovfSJOi28Sqecf1uciml8jwp8KBPW517aceVg5kwNjdFNny', '75092329', 'Andres Felipe Rodriguez Ospina', NULL, 0, '2018-09-20 22:48:22', '2018-09-20 22:48:22'),
(85, 'arodriguezpa@comfamiliar.com', '$2y$10$n5Lwuv3rVzCzkh22DEHVPeN8R.JufvtV6hMxXtWo2pX06IyPOi7N6', '1088328656', 'Alejandro Rodriguez Patiã?o', NULL, 0, '2018-04-25 22:34:52', '2018-04-25 22:34:52'),
(86, 'arojasz@comfamiliar.com', '$2y$10$santcR/Z.rk1dq5EzClO4OZvrwj.zTtlB4tStX/aCx1cCuWaBvm9y', '1089744829', 'Alejandra Rojas Zapata', 5370, 0, '2018-02-08 09:23:18', '2018-04-17 19:09:08'),
(87, 'aromero@comfamiliar.com', '$2y$10$o0tyAIX0iI2mfPF4AR99WeWtuPaVTN2nc.hEa0BE5.FM/L2a9kOYK', '24551875', 'Andrea Marcela Romero Cardenas', 5341, 0, '2018-04-13 06:31:05', '2018-04-17 19:09:08'),
(88, 'aruizq@comfamiliar.com', '$2y$10$oNRDqSC4aiZ7fBush934iuWbZweHy5LbyPSiaQjTTBx9MfuQ9Uxd6', '10011175', 'Andres Felipe Ruiz Quintero', NULL, 0, '2018-11-20 22:19:20', '2018-11-20 22:19:20'),
(89, 'asaldarriagal@comfamiliar.com', '$2y$10$Kaaa7g3CXYgazgDa/g4QOOpGwzwoAN55dEB4UJ7QqNZZGZTC.9uEu', '42147324', 'Amalia Maria Saldarriaga Lopez', 5349, 0, '2018-03-09 05:10:33', '2018-04-17 19:09:08'),
(90, 'asanabria@comfamiliar.com', '$2y$10$ylLCQbcbNr3Bg2M.b7acmO5YJ8nFyKzfS1N6ABBoTBA7I6lMZrzky', '1016003221', 'Andres Felipe Sanabria Osorio', 3738, 0, '2018-01-15 17:45:33', '2018-04-17 19:09:08'),
(91, 'asanchezo@comfamiliar.com', '$2y$10$Ez/I2jbmBpSdSjTqDD/UZOJnqsntPSeCgf815ZokeKL/nlwfhuT/.', '10008780', 'Abelardo Sanchez Osorio', 5171, 0, '2018-02-08 16:25:53', '2018-04-17 19:09:15'),
(92, 'asanchezsa@comfamiliar.com', '$2y$10$8dAkhuw6B7FoLTcU0KRFZ.jkmJ4E.AOVdgei483YGiLRRcb2dsReK', '43738463', 'Alejandra Del socorro Sanchez Sanchez', 5171, 0, '2018-02-20 17:12:17', '2018-04-17 19:09:15'),
(93, 'atabaresm@comfamiliar.com', '$2y$10$uc8Boepky6mH.sTCKcckuOqXU15BXZp07..aCTVU52aSRE1KHyXG2', '30297645', 'Adriana Eugenia Tabares Marin', 2132, 0, '2018-02-06 16:26:00', '2018-04-17 19:09:16'),
(94, 'atapasco@comfamiliar.com', '$2y$10$gZ3RbmIsJAnocYHMcO7tEeHdB7z6nN98nNXT.VkmSEUsRhVbqtZAu', '42116793', 'ADRIANA MARIA TAPASCO GUAPACHA', 5315, 0, '2018-03-28 12:10:32', '2018-04-17 19:09:16'),
(95, 'atiradoc@comfamiliar.com', '$2y$10$8D6rVrZg7mdqFP1WV9eVYO9.PTngUcqI7zwWISrQ1Ie06OGD/Edui', '10030493', 'Andres Tirado Chujfi', NULL, 0, '2018-09-13 06:08:17', '2018-09-13 06:08:17'),
(96, 'atorresm@comfamiliar.com', '$2y$10$bzo2xYDSgLPFqftrtGSTOOEzfV3.OeSGnb1ghuLa62DtesCec8jRe', '92506577', 'Arley Torres Marin', NULL, 0, '2018-05-09 12:14:40', '2018-05-09 12:14:40'),
(97, 'atorrez@comfamiliar.com', '$2y$10$j8q4cdzpWkoUoOfQZRcPlusUTokxKgDu2sM4tQPgu7mCBP9gfySG2', '1093221188 ', 'Alejandro Torrez Agudelo', NULL, 0, '2018-08-01 04:22:48', '2018-08-01 04:22:48'),
(98, 'auribe@comfamiliar.com', '$2y$10$2XCWuDH1Bb8HoJ6i6s2Qv.uivN1QDgmkm.1gPmszZRef.kVxFO1xm', '25179627', 'Adriana Maria Uribe Marin', 5332, 0, '2018-01-29 19:06:02', '2018-04-17 19:09:16'),
(99, 'avalenram@comfamiliar.com', '$2y$10$SpcXE7ZmIlNBvLLtoxakiuTw1HvfOcNoAehUPXtxfTiuNopFw7X/G', '1088238067', 'Adrian Mauricio Valencia Ramirez', NULL, 0, '2019-04-10 02:06:41', '2019-04-10 02:06:41'),
(100, 'avelez@comfamiliar.com', '$2y$10$K0Wu47ZKP9qFAckzQ8wtZOT5dRsLuS83i20Uidlv/GXEj3sOYMCNu', '42105013', 'Angela Velez Rivera', NULL, 0, '2018-05-19 15:38:06', '2018-05-19 15:38:06'),
(101, 'avelezm@comfamiliar.com', '$2y$10$p698OrzWiTKiN3eNubN6XebTR1iGLsA7EbSKOOTYaLniFcJMCAuRq', '1112773375', 'Andres Fabian Velez Marin', NULL, 0, '2018-05-02 15:17:42', '2019-05-02 15:53:46'),
(102, 'ayaya@comfamiliar.com', '$2y$10$LG0MPS9o9q43qRFY.spCMOamsr4cgkVH6UoitsZ3ZNe3d9LWQByXO', '1087557873', 'Andres Felipe Yaya Roman', NULL, 0, '2018-09-07 19:00:54', '2018-09-07 19:00:54'),
(103, 'azapataga@comfamiliar.com', '$2y$10$ircZhBDN/xAyJBQ5o4ZfOeSvBXKTRd1dNzUmjNREjmKA56kEKr8K2', '1088282235 ', 'Alejandra Zapata Garcia', 2140, 0, '2018-01-30 14:49:01', '2018-04-17 19:09:16'),
(104, 'azapataz@comfamiliar.com', '$2y$10$QMR8ObsILXciZX6.n1j3ROH3w834DgAYgi5ChEZxHCxI6pamx8Geq', '18516987', 'Alexander Zapata Zapata', 2130, 0, '2018-02-15 21:34:32', '2018-04-17 19:09:16'),
(105, 'baguirre@comfamiliar.com', '$2y$10$.ozWmMC2cDoXU3WdbpD8OufSqMOfYuLhBBs/EoRo3ksEefW7VE0UK', '42094174', 'Beatriz Fabiola Aguirre Ramirez', NULL, 0, '2018-05-02 14:20:57', '2018-05-02 14:20:57'),
(106, 'balvarez@comfamiliar.com', '$2y$10$XGzWctgVF7BDH6JRjYMgEO2KifbLxbK3pd6x8JULtxrOsfDmUySLC', '42125400', 'Blanca Nidia Alvarez Tapasco', NULL, 0, '2019-03-01 21:05:47', '2019-03-01 21:05:47'),
(107, 'barbelaez@comfamiliar.com', '$2y$10$.dmVxDfQZYKVR40IFYQziudHOKfLGIlaYJxGn7VsCyJqHHICQWLBC', '1088268615', 'Bibiana Del socorro Arbelaez Naranjo', 5341, 0, '2018-02-20 23:37:07', '2018-04-17 19:09:16'),
(108, 'barias@comfamiliar.com', '$2y$10$UrzfLUPs5rUnSssp9Xdx2uMgxtAdQI.B0JxAyEIh4jf851gIJZota', '33966477', 'Biviana Arias Botero', NULL, 0, '2019-02-11 19:25:37', '2019-02-11 19:25:37'),
(109, 'bbernal@comfamiliar.com', '$2y$10$82mGk.1oPk8nLp7Cp/EDUOxHVtBE551.FlSe4gy/iJUICHIXNXeWq', '42104878', 'Beatriz Elena Bernal Saldarriaga', 5250, 0, '2018-01-12 13:46:24', '2018-10-11 12:36:52'),
(110, 'bcifuentes@comfamiliar.com', '$2y$10$stbWdSDIdtnQzX/W6A8mMuPefLG.GaFnkBoZPwo/KyIezRvTh/tvG', '31791581', 'BIBIANA ANDREA CIFUENTES MARTINEZ', 5315, 0, '2018-03-01 02:06:12', '2018-04-17 19:09:16'),
(111, 'bescobar@comfamiliar.com', '$2y$10$BN5LFrySHdft//lohLKPe.q7p/xMGn9JTtJ.5nSY3Syq1mz6cs0Hy', '42052803', 'Bertha de Jesus Escobar Toro', NULL, 0, '2019-02-06 19:45:35', '2019-02-06 19:45:35'),
(112, 'bgaona@comfamiliar.com', '$2y$10$.8VYUkXJFGozNRLISqafV.8AacLpKhohYH2cK0joA0wZt40RWhKja', '1099682374', 'Beatriz Adriana Gaona Hurtado', NULL, 0, '2018-12-06 19:18:49', '2018-12-06 19:18:49'),
(113, 'bgarcia@comfamiliar.com', '$2y$10$KRnia41bsbdDDluEJGRmsu1lSUeB9lLXsTpM1RwZsFMdZujHxKRvq', '24412560', 'Blanca Orfilia Garcia Villa', NULL, 0, '2018-04-23 19:28:53', '2018-04-23 19:28:53'),
(114, 'bgiraldo@comfamiliar.com', '$2y$10$FZTEuajpyPE5eQZmiZDG5OPPIttIUpTZRS5Ah9.iYW.ZmoEz6jZTK', '24327852', 'Beatriz Elena Giraldo Ossa', NULL, 0, '2018-05-08 15:18:49', '2018-05-08 15:18:49'),
(115, 'bhincapie@comfamiliar.com', '$2y$10$QlZY7ggt7lKDjNKShWEVeeFLKkfww2FJmU4ngyn0V1Gc/uCtPZMBu', '42071251', 'Beatriz Elena Hincapie Lopez', NULL, 0, '2018-10-12 21:45:22', '2018-10-12 21:45:22'),
(116, 'bisaza@comfamiliar.com', '$2y$10$iElSx/AdvdsA.kk7MHXGaOFeTm7uOSYcb8.wCyjGz3ap5Wh3K5UUy', '25159287', 'Berta Ligia Isaza Gómez', 5315, 0, '2018-04-01 16:11:39', '2018-04-17 19:09:20'),
(117, 'bluna@comfamiliar.com', '$2y$10$vA7JAs4bU8OfWdsYsgqRbebc4n4cpzHrzdUUcHvqZuz2miwwrvQum', '1004790933', 'Brigee Luna Gutierrez', NULL, 0, '2018-06-13 00:03:30', '2018-06-13 00:03:30'),
(118, 'bmena@comfamiliar.com', '$2y$10$xr4J4EFQK74GMh53ZWV4Q.VkitpWOunzeEFgbX9WCoL5Zj9OBlPeO', '31414126', 'Blanca Lucia Mena Rodriguez', NULL, 0, '2018-10-06 14:11:48', '2018-10-06 14:11:48'),
(119, 'bmonsalve@comfamiliar.com', '$2y$10$1ETqRaNTmiM/lMtWY/VW6.TkhkMCm6Y0I5qEYPeNbgDQUc.rBqUOe', '25162967', 'Beatriz Elena Monsalve Duque', NULL, 0, '2018-05-04 19:26:12', '2018-05-04 19:26:12'),
(120, 'bpalacio@comfamiliar.com', '$2y$10$ZE0WZoLEqOv7jL/PMKXkducGQKgDEeHOslg.zfeCTZXXxZyCeoAym', '30299603', 'Beatriz Fabiola Palacio Ramirez', 600, 0, '2018-02-12 16:34:54', '2018-04-17 19:09:20'),
(121, 'btangarife@comfamiliar.com', '$2y$10$s..8nmhfpb.bCqQu27NgNu3vCvtHmlRNKOIS.dqp2I1UMdJFdq8k.', '1225091259 ', 'Brandon Tangarife Vasquez', NULL, 0, '2018-06-19 19:30:50', '2018-06-19 19:30:50'),
(122, 'btrejos@comfamiliar.com', '$2y$10$RZ3amvGf.ayqNujSENMXF.nQMNIGwzQ/v1EOZQcyfpaJwy0wt87V.', '42109518', 'Beatriz Elena Trejos Velasco', 5240, 0, '2018-03-22 17:01:09', '2018-04-17 19:09:20'),
(123, 'bvelosa@comfamiliar.com', '$2y$10$Cbp3SALFtysXyECvrulA0OHKHiaoIuAJHJMb1jEWj3PT9mfjO3a/W', '1088292064', 'Brandon Lee Velosa Mosquera', NULL, 0, '2018-11-06 12:39:52', '2018-11-06 12:39:52'),
(124, 'bzapata@comfamiliar.com', '$2y$10$0RBMJuJ4yIQ9DJDdV8YTL.5kv73pBpFGoLzgmqtuR745e7IPVvgjq', '42002851', 'Beatriz Elena Zapata Ramirez', 5332, 0, '2018-03-05 08:44:50', '2018-04-17 19:09:20'),
(125, 'caflorez@comfamiliar.com', '$2y$10$.iYOWiIDHzU9PFi1/xla3ekrqniUZPOejcjV0i.Rc.nnrAgSqsEj6', '10134979', 'Carlos Florez Montoya', 3101, 0, '2018-01-30 11:56:50', '2018-04-17 19:09:20'),
(126, 'carboleda@comfamiliar.com', '$2y$10$/XiOsiUF8qcejWtegU67U.OFjN6je4AEnE/fM9y6yvSRju71gHTem', '42159008', 'Cristina Arboleda Gonzalez', NULL, 0, '2018-05-08 14:31:30', '2018-05-08 14:31:30'),
(127, 'catorres@comfamiliar.com', '$2y$10$SB.G0bEk/k50TMFXv7AGFe1CpXUcjhChfbNiLjZK./Gld6xXghere', '1093224373', 'Carolina Torres Garcia', NULL, 0, '2019-05-10 14:24:35', '2019-05-10 14:24:35'),
(128, 'cbadillo@comfamiliar.com', '$2y$10$XlAG9D7reHkKX7BciQvZSu/BNS4xOXz3daCOY4AbQMvkaY1dFHHoi', '75047655', 'Cesar Augusto Badillo Martinez', NULL, 0, '2018-06-22 23:26:52', '2018-06-22 23:26:52'),
(129, 'cbedoyam@comfamiliar.com', '$2y$10$vSjSObW0QRd37yj0fZ2Nz.Q0q.a7KyQ14Gw5K3mtc5yZeqirCxP9i', '1152683107', 'Carolina Bedoya Martinez', 5240, 0, '2018-02-19 05:53:37', '2018-04-17 19:09:20'),
(130, 'cbedoyar@comfamiliar.com', '$2y$10$U6UOEA5LRgwH2baYFMt7mOcjBxnCcYKCljwg.kXK6laPbdMAH2hZu', '96082719161', 'Carlos Adrian Bedoya Rendon', NULL, 0, '2018-05-10 11:24:11', '2018-05-10 11:24:11'),
(131, 'cbedoyas@comfamiliar.com', '$2y$10$b10Lra8gzxPhP7K85a/9J.a6rQRRC.I4T0aWZsaX4/igyg0XIHF76', '1088284570', 'Cristian Bedoya Salinas', NULL, 0, '2018-09-24 14:07:32', '2018-09-24 14:07:32'),
(132, 'cblandonb@comfamiliar.com', '$2y$10$wdGYolySpRR61JGlMbhkiuCv/JHqqEsNuIue2YguD9HOPiatEaTbW', '18493641', 'Carlos Mario Blandon Bueno', 3300, 0, '2018-02-24 17:12:35', '2018-04-17 19:09:20'),
(133, 'cburitica@comfamiliar.com', '$2y$10$rzk.jljtVO4TgljZhZnlGeocEBfGZ472qMPa6jBfpw6Et9CrMTAqW', '1090332239', 'Claudia Johana Buritica Escudero', 3206, 0, '2018-03-06 16:19:04', '2018-04-17 19:09:20'),
(134, 'ccamargo@comfamiliar.com', '$2y$10$7p.D30z6Vrz8AVjwt8//f.ckkm2eRHwpMCUXwAD2YYvJoGHMTlTNK', '42015947', 'Carolina Camargo Triana', 5366, 0, '2018-02-16 16:19:02', '2018-04-17 19:09:21'),
(135, 'ccardonam@comfamiliar.com', '$2y$10$8U1He0ibBTNnCWvkL4tCE.0svqbGwvwewMJm671m3T3HbhubpoKYq', '1088304600', 'Camila Cardona Monsalve', NULL, 0, '2018-05-21 04:58:29', '2018-05-21 04:58:29'),
(136, 'ccastano@comfamiliar.com', '$2y$10$22FTpTf/ZBXhZsUAUR4viO2X.j/fH1aH8OeF1PtzqnVHHhVsy5r8K', '42091580', 'Claudia Milena Castaño Gutierrez', 5372, 0, '2018-03-29 23:06:14', '2018-04-17 19:09:21'),
(137, 'ccastanoal@comfamiliar.com', '$2y$10$WQZqS1zoOQoSql3hUFCwqujy2t5vsJdzKMan6oT.NgN519TFkwB7a', '30354327', 'Claudia Lucia Castaño Alzate', 5315, 0, '2018-03-21 09:23:55', '2018-04-17 19:09:28'),
(138, 'ccastanog@comfamiliar.com', '$2y$10$58umDKQYVY0w6bDnCTlj6O2nYcLP3a9C7DVgtVDtmziu.PzHuv0He', '42083851', 'Clarena Beatriz Castaño Gomez', 3400, 0, '2018-01-10 18:34:45', '2018-04-17 19:09:28'),
(139, 'ccastanot@comfamiliar.com', '$2y$10$miCDQcMJCG3/ag34LNkt2Ofyv9VhdjlbVH4WjeIvJ4zejUJW44Gma', '30325232', 'Claudia Marina Castaño Tobon', NULL, 0, '2018-04-26 15:45:03', '2018-04-26 15:45:03'),
(140, 'ccorrea@comfamiliar.com', '$2y$10$L04Ry6pRW0bTV/p2.nYfoeGhvp/tIT9YCgQw5XCKU7Fy5nwFJzKbe', '42083041', 'Claudia Yaneth Correa Tabares', 600, 0, '2018-02-08 19:08:28', '2018-04-17 19:09:28'),
(141, 'ccortes@comfamiliar.com', '$2y$10$oI5ADplOLQrToVKMrfH93.YyW7RY6EaHypDC9EZaY5q6T1tiUsnsG', '10130325', 'Carlos Arturo Cortes Medina', NULL, 0, '2018-08-22 14:37:43', '2018-08-22 14:37:43'),
(142, 'cdavid@comfamiliar.com', '$2y$10$iQBcbOQZBzaLzS2bfYmaSeaC2p0Y0AGj5UR2twZVp1U2KZTh3V3Ei', '1088008275', 'Cindy Vanessa David Gonzalez', NULL, 0, '2019-01-15 18:05:13', '2019-01-15 18:05:13'),
(143, 'cduque@comfamiliar.com', '$2y$10$QdEZ09Cid7H73GwXZkqLuOHZ50TzyAx9DSUUYp0cjXaGO4StFaWui', '18596211', 'Carlos Enrique Duque ', NULL, 0, '2018-07-24 16:48:23', '2018-07-24 16:48:23'),
(144, 'cduqueb@comfamiliar.com', '$2y$10$4k1K.xjGrkj1RQdMUrCETeMdkN4ijtc92Mk7u2ceKmQId7PEFE7Di', '1088016938', 'Catalina Duque Buitrago', 3101, 0, '2018-02-09 14:57:35', '2018-04-17 19:09:28'),
(145, 'cecheverri@comfamiliar.com', '$2y$10$eOvuPCgSWA/pd7PMBnwyteop1Jzplklwuw11gsob78.rNUkHWmAaO', '33966926', 'Clara Marcela Echeverri Villegas', NULL, 0, '2018-04-30 21:54:33', '2018-04-30 21:54:33'),
(146, 'cescudero@comfamiliar.com', '$2y$10$1VwRTu4LUE3vBFb0cjVUau5/ba.BKEHNIGqmbAA/vq0ot6FUOrPqW', '42137194', 'Claudia Andrea Escudero Velasquez', 3743, 0, '2018-02-16 12:48:38', '2018-04-17 19:09:29'),
(147, 'cflorezo@comfamiliar.com', '$2y$10$ASPytlu6OidB6fzX88CtHeojdBCZ1vqaGVbsaj6Ysl2xvwlQ5yqhO', '1087987833', 'Carolina Florez Ospina', NULL, 0, '2019-04-02 14:41:39', '2019-04-02 14:41:39'),
(148, 'cgarzonh@comfamiliar.com', '$2y$10$0TZNWtO0m.nWolON9aPCjO8lP7dpt4GxREEXibFR.ONMqbkOSWJTC', '1087560065', 'Cindy Paola Garzon Hernandez', NULL, 0, '2018-08-01 22:01:05', '2018-08-01 22:01:05'),
(149, 'cgiraldor@comfamiliar.com', '$2y$10$zQ38WVrBZir6ggjgR1WAGeuzIoVo5tpwHBpXS7Pk1ktx2XN/rd74S', '1088009577', 'Claudia Patricia Giraldo Rojas', 3732, 0, '2018-01-30 16:14:22', '2019-01-24 16:47:41'),
(150, 'cgiraldoy@comfamiliar.com', '$2y$10$2IkgxZ0taXR8Wvx30yYuqO1CbCbyaM4gK6od.kWDF5FEbHjctFELq', '42119892', 'Claudia Ines Giraldo Yepes', NULL, 0, '2018-07-11 20:38:58', '2018-07-11 20:38:58'),
(151, 'cgomezp@comfamiliar.com', '$2y$10$1B0si6O5zt3a7AqU1Id/P.JkK1GeBpWVCwlT4PFeKJOTgdRShjmHW', '42119289', 'Claudia Milena Gomez Pineda', 5373, 0, '2018-04-17 17:42:06', '2018-04-17 19:09:29'),
(152, 'cgonzalezm@comfamiliar.com', '$2y$10$Ozv0XeiiTE7FCaAFAYeige8FVN2lboNDQWtIHPQsI/vAow1iMp2vm', '42019423', 'Claudia Marcela Gonzalez Marquez', 2338, 0, '2018-01-11 11:21:20', '2018-04-17 19:09:29'),
(153, 'cgonzalezmo@comfamiliar.com', '$2y$10$sGBUMqJyXod4HJIk.gG.Eug1/yOnTj1yLrV0gKeU7JFBRGnFzckwe', '1113594708', 'Carolina Gonzalez Montoya', 2011, 0, '2018-01-10 16:50:43', '2019-04-03 15:44:18'),
(154, 'chenaod@comfamiliar.com', '$2y$10$M16FnAM2zxP7uE3u4q9q3egKqFYe2ZjnjKNeTiG1rdFR6Vom84y4q', '1088027098', 'Carolina Henao Duque', 3206, 0, '2018-02-13 20:25:26', '2018-04-17 19:09:29'),
(155, 'chenaov@comfamiliar.com', '$2y$10$k/VFlzNpVLAJ3nlghCIxvu00P0y476Dr6zQ58fdXo84e.XmTlFY96', '42129406', 'Carmen Elena Henao Villegas', NULL, 0, '2018-06-15 21:39:12', '2018-06-15 21:39:12'),
(156, 'cherrera@comfamiliar.com', '$2y$10$dwSWv.ekYooV2FZy.Swi0.4BDSIjal410hNzQZGHQyYFRXrkGv9ha', '1088020016', 'Cristian David Herrera Londoño', 5315, 0, '2018-02-22 11:02:09', '2018-04-17 19:09:32'),
(157, 'churtado@comfamiliar.com', '$2y$10$ku45xcBuXDbt/E8fzUwSSuci1Tap1ufzXe3YKidUz.XOtX5udDxeO', '42103287', 'Claudia Elena Hurtado Tabares', 2336, 0, '2018-03-05 18:37:48', '2019-01-31 20:32:40'),
(158, 'cibarrat@comfamiliar.com', '$2y$10$iXHDgdLGvSoH6Bzn4BO.be3YQdkCh4K7gslb9S9Nk3OqskoOpO3HW', '42128107', 'CECILIA DE CARMEN IBARRA TAPASCO', NULL, 0, '2018-04-21 17:02:11', '2018-04-21 17:02:11'),
(159, 'civelez@comfamiliar.com', '$2y$10$DGssJ1kHO72hMhVC/a7XV.I2fh1b1kH4FcqwI8ViUqLIrI07aBIKC', '1088279975', 'Cindy Alexandra', 2310, 0, '2018-04-06 14:38:51', '2018-04-17 19:09:32'),
(160, 'cjflorez@comfamiliar.com', '$2y$10$aYP5Gx5eZuzV3WOsvIUV6ebzpgX9opFTtSQeVDAPebMbHb5zWXhOu', '10144998', 'Carlos Julio Florez Osorio', NULL, 0, '2019-02-05 22:10:56', '2019-02-05 22:10:56'),
(161, 'cjimenez@comfamiliar.com', '$2y$10$s7S9DCrFaGiX3D0dspoVuesuz2vrd4KfPKJlZ4XozMLLRssbSCCla', '52753948', 'Carolina Jimenez Restrepo', 5332, 0, '2018-03-05 21:05:56', '2018-04-17 19:09:32'),
(162, 'claherrera@comfamiliar.com', '$2y$10$zC/bkWesNGaLMbmwVIfS7uMfxx21Jyf64dTHJhdY1CL6B5MZlsZlW', '30404281', 'Claudia Constanza Herrera Grisales', NULL, 0, '2018-04-27 18:46:36', '2018-04-27 18:46:36'),
(163, 'clarenteria@comfamiliar.com', '$2y$10$5x20CPn7znmIcblPDrzpdO6KNVwU9NFYV7YR25am/D6Vf.mnbXhP2', '1078180454', 'Claudia Marcela Renteria Rios', 3101, 0, '2018-04-14 14:59:21', '2018-04-17 19:09:32'),
(164, 'clcaicedo@comfamiliar.com', '$2y$10$/oo5UYANlSbNEvOE7hr8rOLcUEIvltJxgPTro5KQUpWGLZsmZcR9e', '42132944', 'Claudia Alejandra Caicedo Pulido', NULL, 0, '2019-03-15 16:51:25', '2019-03-15 16:51:25'),
(165, 'clondonob@comfamiliar.com', '$2y$10$gIXXfevkR5lVGN1X6G2qP.79mom6f5EZ7hgCT2klIHEAW4S7.jOum', '42009180', 'Consuelo ', NULL, 0, '2018-11-05 21:25:12', '2018-11-05 21:25:12'),
(166, 'clopeze@comfamiliar.com', '$2y$10$KfOzCHWT/NZkBmx.Z/Rm7u.uS.EoEwhJy4uPe9m6L3000/i8E2YMu', '1088279459', 'Carlos Andres Lopez Escobar', NULL, 0, '2019-01-31 14:31:30', '2019-01-31 14:31:30'),
(167, 'clopezt@comfamiliar.com', '$2y$10$yN9Bvx5F/F8SoQyVobdUq.GgYEan5NBLoiS9NjLbVrHvOcxwXILNm', '30353066', 'Cielo Lopez Torres', 5351, 0, '2018-03-20 13:35:20', '2018-04-17 19:09:32'),
(168, 'clospina@comfamiliar.com', '$2y$10$SA/Z5qcdCfjRBs3cP211RuEoLYvzhe6O86jNeO8qiFKHHXFzQg3wW', '30395775 ', 'Claudia Yaneth Ospina ', 3011, 0, '2018-04-06 18:40:59', '2018-04-17 19:09:32'),
(169, 'cmarin@comfamiliar.com', '$2y$10$Zs3XirklyCqFu7OZqwEbh.gMbueGgaGKeLMBTcRm4/CvxvgLNUsS6', '33965463', 'Claudia Lorena Marin Mosquera', 2130, 0, '2018-03-08 19:07:32', '2018-04-17 19:09:32'),
(170, 'cmarulanda@comfamiliar.com', '$2y$10$FcxvPTUEGRAHtIquynNADOIAm5Bes4YcvFEKDGxCiUcx4rFtsKq9S', '42012498', 'Claudia Milena Marulanda Giraldo', 3100, 0, '2018-02-19 21:41:37', '2018-04-17 19:09:32'),
(171, 'cmarulandag@comfamiliar.com', '$2y$10$Rif/7sUb.arJ/OeXduvuEOWsf2NAItvOfEQGPgkFys2jvA7htKC3W', '1088308219', 'Catalina Marulanda Garcia', 5346, 0, '2018-03-03 07:35:11', '2018-04-17 19:09:32'),
(172, 'cmejia@comfamiliar.com', '$2y$10$N0CYvlWvxR8wGQ8zAwXce.jIeJzicTEKUsy8UKAd6vMgZSg5PYY1G', '1088320791', 'Carolina Mejia Vargas', NULL, 0, '2018-11-30 01:33:13', '2018-11-30 01:33:13'),
(173, 'cmejiac@comfamiliar.com', '$2y$10$a.PTROVK3yP9R4ikrBuj7OixCXujRflGoe4YvMp3Y1UacfRycUYtC', '1088240057', 'Carolina Mejia Caro', NULL, 0, '2019-01-02 15:23:25', '2019-01-02 15:23:25'),
(174, 'cmelo@comfamiliar.com', '$2y$10$9TK8vGd6N/k/9ciVYJqDPOoWAv.C634KPLUgqSYNOoV0gde4bPAAu', '1088262663', 'Cristina Melo Pineda', NULL, 0, '2018-08-27 21:55:20', '2018-08-27 21:55:20'),
(175, 'cmona@comfamiliar.com', '$2y$10$m1O3LXiicxkRPHt2sGoS7eziWf5phNr5qn8FWDVLDQj4YnpWzg1uS', '1088260896', 'Carolina Mona Londoño', 3410, 0, '2018-01-29 17:34:24', '2018-04-17 19:09:36'),
(176, 'cmontes@comfamiliar.com', '$2y$10$djm3ci4P6wu/tPlPfDmmp.bqbIhzez1sIdDlUzUGFEkzbihf60W1i', '10112187', 'Cesar Augusto Montes Diez', 3720, 0, '2018-01-10 16:27:04', '2018-04-17 19:09:36'),
(177, 'cmontoya@comfamiliar.com', '$2y$10$X92O49Tsm5jKE1WaJfZPXO.hWYrFnh63DFyGDgud/G6rhxOov8cn2', '42129799', 'Clara Ines Montoya Arana', 3721, 0, '2018-04-10 12:59:26', '2018-04-17 19:09:36'),
(178, 'cmontoyava@comfamiliar.com', '$2y$10$rToXSgRkiXbSe6gkPZDdEeO1YNw2YdHiApM//s5cUoNblf5x3i7dm', '1088538573 ', 'Cristian Montoya Valladales', NULL, 0, '2018-09-25 20:46:42', '2018-09-25 20:46:42'),
(179, 'cmsanchez@comfamiliar.com', '$2y$10$27f96SQYj2rvYdEIt3BN7Oe8p6BpfNn3/1rH0RlC3NyDdLQDGH4HS', '25165355', 'Claudia Maria Sanchez', NULL, 0, '2018-09-26 17:00:04', '2018-09-26 17:00:04'),
(180, 'cnader@comfamiliar.com', '$2y$10$O6tSvoPDQBcdCiy/qrEL7eK8uA7x/ey3akQuOyJzsdnMgZkQK3ura', '10026380', 'Cesar Alejandro Nader Vega', NULL, 0, '2018-05-22 21:29:25', '2018-05-22 21:29:25'),
(181, 'cnaranjo@comfamiliar.com', '$2y$10$AmbDuxTqtaaleWTH86T3CutJTYxqWfuHFhWsegmykNGJPZk8coZn.', '1088261413', 'Carolina Naranjo Giraldo', NULL, 0, '2019-02-28 16:10:11', '2019-02-28 16:10:11'),
(182, 'corozcoh@comfamiliar.com', '$2y$10$.VTNQGLKO6cCDriNJKJK2OQf1MhbHRlac90knMYx8fffU3zlaJqP.', '42146457', 'Carolina Orozco Henao', NULL, 0, '2018-05-16 18:16:43', '2018-05-16 18:16:43'),
(183, 'corrego@comfamiliar.com', '$2y$10$2YA.NlgmDsFTtMlf7e1ouOw6ZTdCi/zDIafFqVzmpABWccDW1loB.', '1093217141', 'Cristian Orrego', 2328, 1, '2017-12-26 16:37:15', '2018-06-14 21:17:11'),
(184, 'corregos@comfamiliar.com', '$2y$10$aO0UFUs5o6ByPERRCUP.meA3LfUf8tHeyafdYcwvk9LDGoGrOoPui', '42165101', 'Claudia Marcela Orrego Sanchez', NULL, 0, '2019-01-03 22:44:55', '2019-01-03 22:44:55'),
(185, 'cosorio@comfamiliar.com', '$2y$10$N6SA3ymTYr0gCOLqpItGQuQBxS3ZbjiO.HNHUvK7hMj76GXIYO8nC', '42132655', 'Claudia Lorena Osorio Cano', 100, 0, '2018-02-07 21:34:45', '2018-04-17 19:09:36'),
(186, 'cosorioa@comfamiliar.com', '$2y$10$LhUT6ZZ3I4GilB9oRln8EuWVzdZ0tkzUXiNmrb0/vltx/jeLHA7xe', '1088305062', 'Christian Alexis Osorio Aguirre', NULL, 0, '2018-07-17 15:45:07', '2018-07-17 15:45:07'),
(187, 'cosoriooc@comfamiliar.com', '$2y$10$ZaugrRft7XeLw7r1WNPGxOIxtThUm7W9QM3qRw4hrN2N2Hkn5cR26', '1093227256', 'Cristian Edilson Osorio Ocampo', NULL, 0, '2018-09-29 13:39:22', '2018-09-29 13:39:22'),
(188, 'cpantoja@comfamiliar.com', '$2y$10$afcZctmd.9KOXsdgVSexbujpdv65b2IDiM/bxtSa8Z3g8k4Z01CHK', '1088312888', 'Carolina Pantoja Osorio', 5341, 0, '2018-02-25 16:51:14', '2018-04-17 19:09:36'),
(189, 'cpazh@comfamiliar.com', '$2y$10$oCtGU26D4YsCTMPpiDzIKu8A8byV8kVG2kC.WGtB5q2pk.jkG4nzq', '1087418438', 'Camilo Paz', 2328, 0, '2018-01-29 15:39:40', '2018-04-17 19:09:36'),
(190, 'cperez@comfamiliar.com', '$2y$10$GrARLlSAV/AJvUldiq8OZuAnXwZBn6dhC3fHtXOBKKeK4t7EHU26q', '10011184', 'Carlos Alfonso Perez Rivera', 700, 0, '2018-01-05 19:16:29', '2019-04-30 14:34:21'),
(191, 'cperezg@comfamiliar.com', '$2y$10$wnpXVzGEInqsT7THv.Vc6.rdQoH1leVS4Jm/yzIjZeIS65/pyhbUO', '1088240468', 'Catalina Perez Gomez', 3320, 0, '2018-01-26 13:27:14', '2018-04-17 19:09:36'),
(192, 'cpineda@comfamiliar.com', '$2y$10$GYZh/NMdX9wcbOOFBTpiIOKKn4OH2WkQqtvmDXFuET6wQ4dUuZ0xO', '9874242', 'Cesar Augusto Pineda Gutierrez', NULL, 0, '2018-05-07 15:29:55', '2018-05-07 15:29:55'),
(193, 'craga@comfamiliar.com', '$2y$10$SPNnmQ5h4w1B921F5ectuuwrxCVgj7DuMUX.BweUPM/jd6rC1vsL6', '10089980', 'Carlos Alberto raga Marin', 2310, 0, '2018-02-16 13:21:59', '2019-03-01 13:52:09'),
(194, 'cramireze@comfamiliar.com', '$2y$10$42Je3U3GTbncs1rDfjub/OpF5wW6RpxHZRcbbXOFIJNRLVSISGft6', '1088345373', 'Camilo Ramirez Estrada', NULL, 0, '2018-08-23 12:53:18', '2018-08-23 12:53:18'),
(195, 'cramirezt@comfamiliar.com', '$2y$10$L05LA3N6j7pNHonS0HDRIu5yHeNK/shJUR030WArMo9g7wnRb0.Pq', '30384252', 'Cruz edilia Ramirez Taborda', NULL, 0, '2018-09-20 13:30:12', '2018-09-20 13:30:12'),
(196, 'cramirezta@comfamiliar.com', '$2y$10$k0WlxpIGli8DyDapoG.jNOfTVS8gGZsVt7PPMx1FMqylvVa3O.xYa', '1059699884', 'Carolina Ramirez Tapasco', 3320, 0, '2018-02-16 23:12:42', '2018-04-17 19:09:36'),
(197, 'cramos@comfamiliar.com', '$2y$10$.8LEQna.xAahAsYfkRUHne9vCyXu24RU0CcrHQuaWhzn7QVWaxWYe', '1088257413', 'Christian Felipe Ramos Delgado', 2310, 0, '2018-01-26 22:24:31', '2019-01-25 13:45:33'),
(198, 'crestrepola@comfamiliar.com', '$2y$10$Cec7llwMtUuiEqCCrdhP..FVNc/IDmy5GsOdv3u7JH5./DXHiNX82', '1093229600', 'Claudia Liliana Restrepo Largo', 5240, 0, '2018-02-16 16:33:52', '2018-04-17 19:09:39'),
(199, 'crguerrer@comfamiliar.com', '$2y$10$qnReW1Ue106R2aC4J8m9BO3kqdEeqcZ8TCnG8rOdmCjX1rMxJvR7C', '1121833514', 'Cristian Adrian Guerrero Pineda', NULL, 0, '2018-10-03 14:33:33', '2018-10-03 14:33:33'),
(200, 'criosr@comfamiliar.com', '$2y$10$oCzJxR3Z1XX/YdRC41/aQu3R59wI0ReQmJYfnKqKqbBh0zTWopAU6', '1088012625', 'Cristina Rios Restrepo', NULL, 0, '2018-09-02 13:26:28', '2019-02-17 13:57:31'),
(201, 'crodriguez@comfamiliar.com', '$2y$10$qi/NE1CUzbpo5ne/SsvuyufAAifO0qla1OcaJIUgmn7a.crS6WWem', '42087648', 'Ricardo Murillo Soto', 900, 0, '2018-01-29 12:07:47', '2018-04-17 19:09:39'),
(202, 'crodriguezc@comfamiliar.com', '$2y$10$Y6RMa5EiMYfJ6T3FMra1COEcfNHUZMFjRSVzjHhFlS.qcAn4xt4xO', '28272086', 'Cecilia Rodriguez Caceres', NULL, 0, '2019-04-01 05:08:36', '2019-04-01 05:08:36'),
(203, 'csanabria@comfamiliar.com', '$2y$10$tSKTILOhzrdlhT7HaZ0QD.Vnt0LqDF5UatexLj.Gi13HQ69SGteBK', '1094954552', 'Cristian Eduardo Sanabria Marin', 2310, 0, '2018-01-09 20:44:50', '2018-04-17 19:09:39'),
(204, 'csuarezl@comfamiliar.com', '$2y$10$a1ey4lPepFogw7Lg3WIYvemYlAryZTgwcOUeGz6HKv4/3Fg2hyzQO', '9894734', 'Carlos Gardel Suarez Largo', 5315, 0, '2018-02-17 03:21:41', '2018-04-17 19:09:39'),
(205, 'ctrujillo@comfamiliar.com', '$2y$10$VL5F4sF0CPoCPGv4dUt4uOZJDBsxZYxhe/0fDdQi56fA//LH2eW6u', '42131877', 'Catalina Trujillo Mejia', NULL, 0, '2018-05-10 23:45:11', '2018-05-10 23:45:11'),
(206, 'ctuzarma@comfamiliar.com', '$2y$10$KT60wEFoF.P/siDgmSqLa.JiPTx/d0hGFoWGWDGcWHhIayqp6oqKa', '1089720418', 'Cristian Alejandro Tuzarma Becerra', NULL, 0, '2018-04-27 04:49:11', '2018-04-27 04:49:11'),
(207, 'curibe@comfamiliar.com', '$2y$10$0WrWyaRCg7TIhgGwtOxZ9OJyjGXtoXx7Frm5tcyIE/4ZT4dyg4zua', '18522892', 'Carlos Andres Uribe Godoy', 5171, 0, '2018-02-08 19:12:15', '2018-04-17 19:09:39'),
(208, 'cusma@comfamiliar.com', '$2y$10$OjzRZdHfAC3u5GMOHNVa2uvzynNlHWdktzhIqiJXniOjFb8yPsZSe', '1088331807', 'Catalina Usma Echeverry', NULL, 0, '2018-06-17 05:56:14', '2018-06-17 05:56:14'),
(209, 'cvalencia@comfamiliar.com', '$2y$10$Lpl.opf0NdDPEhIbvzjX8u40cuG2qM50EK6Zmv6WxF40hxmbK57kC', '10129081', 'Carlos Fernando Valencia Jaramillo', 3738, 0, '2018-03-01 12:29:04', '2018-04-17 19:09:39'),
(210, 'cvalenciag@comfamiliar.com', '$2y$10$flbEtbE486aUYz4rTc4ifu/x3.Li5uZSrpYTdmRZOMgSds9tlX6ga', '1093215623', 'Claudia Marcela Valencia Gonzalez', 3300, 0, '2018-01-31 15:09:58', '2018-04-17 19:09:39'),
(211, 'cvalenciaq@comfamiliar.com', '$2y$10$8Bo2P7Qx.eOaFfs.f5H0peG.iA9Lr0LZAT3O10l/5mmOZrt7e9Ybi', '1088009066', 'Catalina Valencia Quintero', 3410, 0, '2018-02-09 15:27:49', '2018-04-17 19:09:39'),
(212, 'czuletag@comfamiliar.com', '$2y$10$iYQX7HiyvgjZL5t0rUfPdu9K6jXSzsn0Y1ZCG/Ximir0MoJCnx3fG', '1088329699 ', 'Cesar David Zuleta Garcia', NULL, 0, '2018-05-28 21:42:58', '2018-05-28 21:42:58'),
(213, 'czuluaga@comfamiliar.com', '$2y$10$ciuk0KjnVuoJ1ZFNKzqlr.C6iqTYsFYEjoMDs7fDNbz0wqRgCXQB2', '15920830', 'Cesar Antonio Zuluaga Alvarez', 2110, 0, '2018-02-09 13:57:08', '2018-11-28 14:57:57'),
(214, 'dabueno@comfamiliar.com', '$2y$10$H0LtadFyBa0XfBczHexwauwedZ9FtyWnGpmu0TxelezDcCd3Uvfhq', '1010093552', 'Dana Yeine Bueno Duque', NULL, 0, '2019-02-01 13:30:19', '2019-02-01 13:30:19'),
(215, 'dagudelobe@comfamiliar.com', '$2y$10$rXcS8RNEARh8cJOiQsQZ2eL7qalNcsZmA4YdkizfSAoik4km7GdSW', '42154893', 'Diana Maria Agudelo Bedoya', 5370, 0, '2018-02-03 17:46:02', '2018-04-17 19:09:45'),
(216, 'daguirre@comfamiliar.com', '$2y$10$36eTnq5n61mxpPrQYu4vw.48EdonNRzd2En5K5aji9zfZoh1c6c6a', '42005283', 'Diana Maria Aguirre Morales', 5315, 0, '2018-04-05 08:42:06', '2018-04-17 19:09:45'),
(217, 'daguirreg@comfamiliar.com', '$2y$10$EpCLiOPk0S5muQp8Zj78rOZ5QfAdJ3HTWiG7xFE2f67HjMtT9yf0m', '24586539', 'Diana Carolina Aguirre Grisales', 5335, 0, '2018-03-15 20:56:28', '2019-03-24 04:22:19'),
(218, 'dajaramillo@comfamiliar.com', '$2y$10$zlp..c4xXOaU.UZiH60baO84Ti.l1nLsypSr2FWSv9/yum9nty6yK', '1088312995', 'David Jaramillo Loaiza', NULL, 0, '2018-04-24 16:41:29', '2018-12-19 12:43:41'),
(219, 'dalcalde@comfamiliar.com', '$2y$10$5qeXUddJVxYpFBXi4lNEoe3Yd06ByIw4C/zw166kH0ojwxfDpNYnm', '1088312869', 'Daniela Alcalde Moscoso', NULL, 0, '2018-09-25 19:08:29', '2018-09-25 19:08:29'),
(220, 'dalmanza@comfamiliar.com', '$2y$10$NJOMS.K4Q2v5FrDWVYILf.aGZ.6.bF5RwaxtMdonfW2iHgAA3w4/O', '1088243213', 'Diego Alejandro Almanza Montoya', NULL, 0, '2019-02-25 14:18:43', '2019-03-12 13:32:18'),
(221, 'dalzate@comfamiliar.com', '$2y$10$4BZHMI5daYyMybomou5TGuVjM0VEjfATHcqRfQKEhbtO1zlxbpRcS', '1087997237', 'Diana Marcela Alzate Suarez', 5315, 0, '2018-02-15 05:49:35', '2018-04-17 19:09:46'),
(222, 'damaya@comfamiliar.com', '$2y$10$itomTiQtYFU4iESKomsnW.rqaLVfPm4WbSSszzIRXakRj8Pv5XyMa', '1088256157', 'David Amaya Hincapie', 3738, 0, '2018-02-07 15:42:58', '2018-04-17 19:09:46'),
(223, 'danieto@comfamiliar.com', '$2y$10$5X5Ug8ll/nU5Zl4pYYS8CeAV1hornviHiAEr1UsoROJhLU3YGqEyO', '1088358061', 'Daniel Nieto Castillo', 2310, 0, '2018-02-06 21:30:18', '2018-04-17 19:09:46'),
(224, 'darenas@comfamiliar.com', '$2y$10$zQpaO/9.L49loa9/Sv9jUOL8uL9BmPhvDUJPbbBz9HQOpI1vA4Emm', '42102663', 'Dora Isabel Arenas Castro', 3738, 0, '2018-01-16 14:07:37', '2018-04-17 19:09:46'),
(225, 'daricapa@comfamiliar.com', '$2y$10$fSk49BFMv3JQmSunHWjYreUaxq8IuSOkttlx.e5DKTDaHb5uHdTNu', '33917988', 'Diana Marcela Aricapa Zapata', 5140, 0, '2018-02-13 20:27:36', '2018-04-17 19:09:46'),
(226, 'dbedoyaq@comfamiliar.com', '$2y$10$aRU3jePNWt56rG5oMxCAPujim2XlzowTeD0tF51PNYdKlotFWsBEm', '1093225957', 'Dahiana Bedoya Quintero', 5315, 0, '2018-03-02 04:05:12', '2018-04-17 19:09:46'),
(227, 'dbetancur@comfamiliar.com', '$2y$10$JoqgCakxwZgCJ0OFN0QXMupRSuq.cTov4AieTOKmiv1QfgnzfyhHW', '1088293181', 'Daniela Betancur Botero', NULL, 0, '2018-11-07 16:34:36', '2018-11-07 16:34:36'),
(228, 'dblandon@comfamiliar.com', '$2y$10$Eka1oWtLGfrSjnMdJPLn/.cwC5r82.u3LKZFAcK5k6BiFMXSAl9py', '25158416', 'Dora Ines Blandon Arias', NULL, 0, '2018-05-31 16:09:15', '2018-05-31 16:09:15'),
(229, 'dcanob@comfamiliar.com', '$2y$10$arXTkK0Wg/kVmvjwYP9VA.AwenQLm/dlHxX.bCpCS13E1wsJHhWDG', '30360947 ', 'Diana Milena Cano Botero', NULL, 0, '2018-09-29 08:03:49', '2018-09-29 08:03:49'),
(230, 'dcarmona@comfamiliar.com', '$2y$10$kOOr/TgyJdFHFfNAn9rjNu2kaSl3gQWBDwnY2t0EUvJASseW.KJQu', '10111237', 'Diego Carmona Carmona', NULL, 0, '2019-03-20 16:50:47', '2019-03-20 16:50:47'),
(231, 'dcastanor@comfamiliar.com', '$2y$10$yiN67zI3ovxqiQYVbWzFRuX95PvIgFT8WccxZ2PdPOljHx7RvdK3C', '24791067', 'Deysy Cristina Castaño Ramirez', 5316, 0, '2018-04-13 06:40:05', '2018-04-17 19:09:46'),
(232, 'dcastanov@comfamiliar.com', '$2y$10$spmVRF7lu3AiJ13W8tFXM.x9ZDJwDi52V5KBPhY7Af1RIXK0UE65S', '1088027389', 'Dayhana Castaño Velasquez', NULL, 0, '2019-05-06 20:28:46', '2019-05-06 20:28:46'),
(233, 'dcastro@comfamiliar.com', '$2y$10$xYvcGutwv1r5rm1D0B7VCeOZArGGUNm99g.65zq7YWrxIWtEy3O12', '94532465', 'diego fernando castro osorio', NULL, 0, '2018-11-28 13:25:47', '2018-11-28 13:25:47'),
(234, 'dchacon@comfamiliar.com', '$2y$10$6r09d3q9Qi6csf3Xq0H9d.c1AhffomKME2J49Sb0ze9qyaPx2Ztfy', '1112787528', 'Daniela Chacon Muñoz', NULL, 0, '2018-04-30 19:54:00', '2018-04-30 19:54:00'),
(235, 'descobar@comfamiliar.com', '$2y$10$TwqiEz.EckzO.Gs.F3Auv.m8BtRLuW/eQitcZoriXzr8OJ65nCxmm', '42096986', 'Dioselina Escobar Cañaveral', NULL, 0, '2018-05-28 21:16:04', '2019-05-27 21:28:07'),
(236, 'descobarl@comfamiliar.com', '$2y$10$41Q7.aNUsOR3Qh25varQWujDVxrUJiUu55LQnylpRQLmSSy7FhY4O', '1093214158', 'Daniel Felipe Escobar Lopez', 3300, 0, '2018-01-31 14:44:28', '2018-04-17 19:09:50'),
(237, 'dfrancoo@comfamiliar.com', '$2y$10$wBOBCevavXw.SDvUeXvf0ey8sWSOwRH1Pm4EOB89hynrgJJBFPSIa', '1093214874', 'Diana Marcela Franco Orozco', NULL, 0, '2018-01-24 13:52:29', '2018-05-04 12:44:52'),
(238, 'dgallego@comfamiliar.com', '$2y$10$L4.aD878eQAUGFjI70tHL.WzcwO/9AkAPLYJLweBqc9gPKTEFsoam', '42083936', 'Dufay Gallego Diaz', NULL, 0, '2018-08-07 09:44:22', '2018-08-07 09:44:22'),
(239, 'dgallo@comfamiliar.com', '$2y$10$Hz2YtJN2PFAT7YZ6Q3GTHu3wFx1muUPBsd3mhvKv40HIxdJAzK80y', '4519244', 'Diego Alejandro Gallo Caicedo', NULL, 0, '2018-05-01 18:54:58', '2018-07-28 15:44:22'),
(240, 'dgalvism@comfamiliar.com', '$2y$10$QDNLAeqrMuGPqlLnyk6zqOMLoP7XsqO5a/y/bE/5CQF9ZHBy3QSPq', '1087996637', 'Diana Patricia Galvis Medina', 3732, 0, '2018-03-07 22:49:46', '2018-04-17 19:09:50'),
(241, 'dgarces@comfamiliar.com', '$2y$10$kU5M/HRr3V/IPUUjQBc6ve4294V0Mtuk758PRqgKyqC9/Ar5X5mt2', '18531990', 'Diego Fernando Garces Caro', NULL, 0, '2019-05-06 14:12:37', '2019-05-06 14:12:37'),
(242, 'dgarciag@comfamiliar.com', '$2y$10$dQJo/l/LsEAr4c4qEd5MfOk/QKXGdw8GFM1mnHX6ikP6cbOJSv2Z2', '42131421 ', 'Diana Marcela Garcia Giraldo', NULL, 0, '2018-05-01 10:34:18', '2018-05-01 10:34:18'),
(243, 'dgil@comfamiliar.com', '$2y$10$cwBZ3OrEhkxlRIt8HqPT0OJcoxQ1LyfmE.oBMm4CX2QQo1O/fpiHe', '1088249331', 'Diana Lucia Gil Montoya', 3206, 0, '2018-01-29 19:58:47', '2018-04-17 19:09:50'),
(244, 'dgiraldoh@comfamiliar.com', '$2y$10$VJVz8Q6RvURWCDri0kuyjO2Nvuw5vFAwGBrg.gcXCNqNPDtSAuO5W', '1115419884', 'Diana Marcela Giraldo Herrera', 3737, 0, '2018-02-28 13:37:47', '2018-04-17 19:09:50'),
(245, 'dgomezs@comfamiliar.com', '$2y$10$YTi63fP4TSBkxqKT/zNGfu.Ae.rdk2Y9XdgrqrqBjab19gScniDz6', '10006075', 'David Camilo Gomez Sarmiento', NULL, 0, '2018-11-09 20:34:12', '2018-11-09 20:34:12'),
(246, 'dgranada@comfamiliar.com', '$2y$10$JPC4z05lhob8yEUvzN7iOeO5AXRWwchCCluTgmdpa2NoS3VLJBJaC', '42150271', 'Diana Maria Granada Garcia', NULL, 0, '2019-04-16 18:53:33', '2019-04-16 18:53:33'),
(247, 'dgrisalesn@comfamiliar.com', '$2y$10$Bsg.l2cGHBtv6KeK9kfDNOjnS85RdMS3WE2wiTd0zoPr3t80RUrxe', '1053788322', 'Daniel Alexander Grisales Nieto', 5315, 0, '2018-02-21 13:19:16', '2018-04-17 19:09:51'),
(248, 'dhenaoma@comfamiliar.com', '$2y$10$tD8uxXc1Bm7vkFQ2waBlE.sg.Mj6Un0N1Txnmw05aKIKIX5q8dcxy', '42148246', 'Diana Maria Henao Martinez', 5344, 0, '2018-02-12 11:06:10', '2018-04-17 19:09:51'),
(249, 'dhernandez@comfamiliar.com', '$2y$10$99uwbUUmN9w43DAguwQUb.toFp3Px85rNrBBXWIq83l6Xeju.6Gyq', '42019113', 'Diana Milena Hernandez', 3300, 0, '2018-02-09 17:46:23', '2018-10-05 16:47:33'),
(250, 'dhernandezg@comfamiliar.com', '$2y$10$JJatrD/H7H2dD4uHG1P/2OwoXo/CVZFmZuJRZl2oE7FE7E.p3yRGC', '1096034497		', 'Deicy Tatiana Hernandez Gomez', NULL, 0, '2019-02-12 16:50:49', '2019-02-12 16:50:49'),
(251, 'diacruz@comfamiliar.com', '$2y$10$SIXR.il8dSa0lKkOaviB6urt3cQ7FgumglO27rtqFwFtyyohXdTP6', '1088010184', 'Diana Maria Cruz Zapata', 5351, 0, '2018-03-13 00:10:24', '2018-04-17 19:09:51'),
(252, 'dimartinez@comfamiliar.com', '$2y$10$Vavrcy04U8r3SbLku674AuOsF9a.A.HkMaR6HdohGH2uewIothE5O', '42159194', 'Diana Marcela Martinez ', NULL, 0, '2018-08-13 04:52:47', '2018-08-13 04:52:47'),
(253, 'diquintero@comfamiliar.com', '$2y$10$OWTSclDczTBbMlsnlpm6f.WZtqIqy8GazZFIpuDX6Q9HRZcNXZvvi', '42137079', 'Diana Patricia Quintero ', NULL, 0, '2019-05-13 13:22:42', '2019-05-13 13:22:42'),
(254, 'disaza@comfamiliar.com', '$2y$10$PIp./yzN1WXePfMT3WHCN.74zCpnncRtlABonaStxEpFLVrP3SzcG', '1112778898', 'Davinson De jesus Isaza Castaño', NULL, 0, '2018-09-26 20:37:47', '2018-09-26 20:37:47'),
(255, 'djuradog@comfamiliar.com', '$2y$10$2Ma9J4EmGfMW0olAK/OcBOOyZygb6jsxFwTbPiJz8.roEn4KdLl3.', '30404958', 'Diana Maria Jurado Garcia', NULL, 0, '2018-06-20 15:15:03', '2018-06-20 15:15:03'),
(256, 'dlopeza@comfamiliar.com', '$2y$10$G7GeLztaVO.2Sad4Iu8gJuL9bKHOlIEan5TxvF6IG3SQEy4Xc5FYi', '1088317613', 'Diana Patricia Lopez Agudelo', NULL, 0, '2018-10-20 06:53:59', '2018-10-20 06:53:59');
INSERT INTO `sara_usuarios` (`id`, `Email`, `Password`, `Cedula`, `Nombres`, `CDC_id`, `isGod`, `created_at`, `updated_at`) VALUES
(257, 'dlozano@comfamiliar.com', '$2y$10$62mgCIam7PZcUUek1c3xpO5tXWhd46YIeKrwZTyaL0tx3ID9HZMPi', '43977469', 'Diana Marcela Lozano Carrillo', 5342, 0, '2018-02-07 14:50:40', '2018-04-17 19:09:51'),
(258, 'dmartinezv@comfamiliar.com', '$2y$10$.u39FOd34KH8Zqvo.paw4ONjWThJ8yw4IR7QyQU3IqFj3Ro/eKnYe', '10124805', 'David Alberto Martinez Valencia', NULL, 0, '2019-01-03 22:20:38', '2019-01-03 22:20:38'),
(259, 'dmejia@comfamiliar.com', '$2y$10$3k.pnjcNonUdeo5iriRs2.CbSOLb1CbUTcry06k6QrKfdMzOHkSui', '41928440', 'Dulfary Mejia Vanegas', 5349, 0, '2018-04-14 13:55:28', '2018-04-17 19:09:51'),
(260, 'dmejiar@comfamiliar.com', '$2y$10$UqtYWLYwNir2ULD.lL5B9eYZfz2jen10BSvz0svNpJyrVqdEJQxqS', '1088300752', 'Daniela Mejia Ramirez', 2338, 0, '2018-01-30 14:16:33', '2018-04-17 19:10:00'),
(261, 'dmlopez@comfamiliar.com', '$2y$10$h7QABRV0.T9HhIkK5G1f3.SzwvE8Et3CG5.JCF1OqRPlgYX5YElr.', '42141586', 'Diana Maria Lopez Osorio', NULL, 0, '2019-02-09 16:31:59', '2019-02-09 16:31:59'),
(262, 'dmoralesg@comfamiliar.com', '$2y$10$VrqthIVkbhAzDrKUcfiOC..FF3LNoB6UdZCrl5pAk3Jsa0owPKQmO', '1094913236', 'Daniel Morales Gomez', 5351, 0, '2018-04-04 14:04:43', '2018-04-17 19:10:00'),
(263, 'dmoralest@comfamiliar.com', '$2y$10$rRGOLM7S6zsVcvmwanAbMu/cdHN47NFM88VTa8UXiroJjRXOv2YTO', '1087556024', 'Darwi Estiven Morales Tabares', NULL, 0, '2019-02-25 13:54:41', '2019-04-01 07:02:20'),
(264, 'dmunozca@comfamiliar.com', '$2y$10$5y2MoOLGt2KBQOfxIi340OO9YeCiuPUX/IWgNQ8WQbKRObY4i9QFO', '1088246989', 'Diana Carolina Muñoz Cataño', NULL, 0, '2018-09-11 18:43:22', '2018-09-11 18:43:22'),
(265, 'dmunozo@comfamiliar.com', '$2y$10$PX6uMxjJ/wo6YU1dt24VmOLt1l74I2qPvziZNYqseI4clhxsG0Hry', '1088325206', 'Diego Alejandro Muñoz Osorio', 3321, 0, '2018-03-07 13:17:33', '2019-03-07 22:07:49'),
(266, 'dosoriogo@comfamiliar.com', '$2y$10$5P6j6XyRzpHjEBwHU2Hmd.sGt7emlX.gG7Z193HqDvhvKRT0FKSvy', '42105078', 'Diana Paola Osorio Gomez', NULL, 0, '2018-05-25 15:11:52', '2018-05-25 15:11:52'),
(267, 'dpalacioc@comfamiliar.com', '$2y$10$u8wtyuIway8i7an7Nl/vye6xqtrX6aKt4vvdTa3vvoZlNbM751kPW', '1088292004', 'Diana Andrea Palacio Cuervo', NULL, 0, '2019-03-16 14:14:33', '2019-03-16 14:14:33'),
(268, 'dpinto@comfamiliar.com', '$2y$10$X/Lk90P9IYvWEmI7p94YDeop.00mzhiTIwP6JOwIGoR9ZnHmqMPw6', '42162324', 'Dayana Patricia Pinto Alzate', NULL, 0, '2019-05-06 12:30:25', '2019-05-06 12:30:25'),
(269, 'dplata@comfamiliar.com', '$2y$10$aAnO3Servzsv8p7HbjOpMuLzGXYHfPhhAqDeY8Bj4xgWMl8ii9Tci', '1114094095', 'Dina Marcela Plata Atehortua', 700, 0, '2018-01-29 12:37:34', '2018-04-17 19:10:00'),
(270, 'dpulgarin@comfamiliar.com', '$2y$10$EJPa1ztOXjiqeADsKIdMSexB89R1KwBniRak8nbTIAIEBfa7ipl2m', '25248908', 'Diana Carolina Pulgarin Montoya', 5351, 0, '2018-03-16 01:22:28', '2018-04-17 19:10:00'),
(271, 'dramirez@comfamiliar.com', '$2y$10$Y/ibSgxcmWF9EJOx2kTttukqMWGJzKfivHFSGrOilrSKeHWMy8ez.', '42102928', 'Dora patricia Ramirez Devia', NULL, 0, '2018-05-17 16:47:41', '2018-05-17 16:47:41'),
(272, 'drendon@comfamiliar.com', '$2y$10$vMkZvMZUPfWSzvN0.N.60e15VvXC0lv.YMoDkEpTJist5UQ2ZniLO', '33916437', 'Diana Maria Rendon Jarmillo', 5370, 0, '2018-04-10 08:44:41', '2018-04-17 19:10:00'),
(273, 'drendonb@comfamiliar.com', '$2y$10$LQ8zUqtIlMMWFoqlte.8Y.4kcArfXGrAxfbLb8WLk4iGX04hAlP8S', '1088280616', 'Daniela Rendon Betancourt', NULL, 0, '2019-05-20 22:36:54', '2019-05-20 22:36:54'),
(274, 'drestrepobe@comfamiliar.com', '$2y$10$5GtAhYyzCvuZQVYugynQ7eQSoP5l5PlsnBMOmiqpQ6GWDt2JrIUPW', '1087550855', 'Diego Fernando Restrepo Benjumea', 2330, 0, '2018-02-21 20:49:03', '2018-04-17 19:10:00'),
(275, 'drios@comfamiliar.com', '$2y$10$SrabilVquk2vJGM8DylrveEo2pwm8jptZou78RURShdnUbunWqkAW', '1088010462', 'Dayana Rios Cuartas', 5351, 0, '2018-02-07 16:01:33', '2018-05-03 14:05:15'),
(276, 'driosg@comfamiliar.com', '$2y$10$0FrcUcub8g6cqxUe8XhOy.pQrz4QaN7V8HSiIVJAO7t2DrECqDQVm', '1087989764', 'Diego Mauricio Rios Gallego', 3206, 0, '2018-02-22 13:19:20', '2018-04-17 19:10:01'),
(277, 'drodas@comfamiliar.com', '$2y$10$q40xVF0fO5/FZGwVC5ac2OnH/RPg1yBjWhx62TDjXMmhZaF/a0chS', '42145636', 'Diana Maria Rodas Orozco', 2000, 0, '2018-01-24 21:22:05', '2018-04-17 19:10:01'),
(278, 'drojasa@comfamiliar.com', '$2y$10$tyLHGag3MS.QClEKuok36eYo32CBiE/PDB2bsHDvxintN5f/9sYMG', '42073973', 'Diana Constanza Rojas Arias', 3737, 0, '2018-02-09 16:08:53', '2018-04-17 19:10:03'),
(279, 'drojasgu@comfamiliar.com', '$2y$10$sYEUzEuXtF7Wlo0W651MXuxqL8/1PKMk2nN8Rp0uCdNuMGCTQVugO', '1087553804', 'Deici Johana Rojas Gutierrez', 3744, 0, '2018-03-26 14:56:44', '2018-04-17 19:10:03'),
(280, 'drua@comfamiliar.com', '$2y$10$umhLzNgT9zR2mst53DsAkexVRnjUGwuhIbPNTuvRUVx5DhTzhAN7i', '1112906116', 'Daniel Augusto Rua Yepes', 5372, 0, '2018-02-09 15:57:59', '2018-04-17 19:10:04'),
(281, 'dsalazar@comfamiliar.com', '$2y$10$h2ZsVE1uALUGxzUSRVZ1r.llsi5Bbs8jWjMn1x.TrVXliPigHNNy2', '10128418', 'Diego Fernando Salazar Ocampo', NULL, 0, '2018-11-16 14:06:31', '2018-11-16 14:06:31'),
(282, 'dsalazarm@comfamiliar.com', '$2y$10$m5g0jrA/5JBqgwS.NAeKk.I9iawI29retZLydqVNztDeWklSXskeS', '31436048', 'Diana Alexandra Salazar Marles', 2110, 0, '2018-02-12 15:13:03', '2019-04-02 15:15:00'),
(283, 'dtamayo@comfamiliar.com', '$2y$10$jNo.FiKHGEyYJg52fI9mZ.cKWZeyftGZnX7X0mdZfmINFfVSF6sGa', '25174166', 'Diana Maria Tamayo Garcia', NULL, 0, '2018-04-20 13:07:16', '2018-06-15 19:59:56'),
(284, 'dtapasco@comfamiliar.com', '$2y$10$rxn0zgXdyxKd8Bhzsdvrr.VCNNQ/kPpwk/LGi8kxaPfe8Y666oU3q', '1088317912', 'Daniel Alejandro Tapasco Moreno', 3320, 0, '2018-02-02 16:28:28', '2018-04-17 19:10:04'),
(285, 'duchima@comfamiliar.com', '$2y$10$.EPcM5lIBXN6TscWpVfOHu9V06A4fqdDZUbv5pTikVnPN5uSrSdh.', '1093536961  ', 'Daniela Uchima Arango', NULL, 0, '2018-06-14 23:12:14', '2018-06-14 23:12:14'),
(286, 'dvalle@comfamiliar.com', '$2y$10$K3URSHpZeIExUEPyaa0VM.T1N1kJC1f2IEIuYeHDcSPwAg2CfwPKW', '1088023501', 'Daniela Valle Cardona', 3112, 0, '2018-01-30 19:59:55', '2019-02-01 23:03:17'),
(287, 'dvargas@comfamiliar.com', '$2y$10$3P37sqLthudxf.RwYqb5uedb9sESYQnm9igJC67QWavcEKGvocaM6', '29135134', 'Dignora Vargas', 2313, 0, '2018-02-06 10:41:01', '2018-04-17 19:10:04'),
(288, 'dvillada@comfamiliar.com', '$2y$10$jnDqje8nIlZRTfbB/C5M8.Zcmn8NC2S1wjtnQjnndgi5oDj/REq7a', '1088248299', 'Diana Isabel Villada Osorio', 3400, 0, '2018-02-06 18:31:48', '2018-04-17 19:10:04'),
(289, 'dzapata@comfamiliar.com', '$2y$10$KBdy2tUyZbusrhLcix.Lq.ZmFtROagjNmAyMlEJHt.TlZ/BpztYaW', '42145203', 'Diana Isabel Zapata Castaño', 5171, 0, '2018-03-08 10:56:31', '2018-04-17 19:10:04'),
(290, 'earias@comfamiliar.com', '$2y$10$KSAVTisw3.YuUDNfCATwK.mwZlpTbk7c3JZbcQkmYs54WEuJVSK4S', '42151015', 'Erica Arias Gallego', 2132, 0, '2018-01-05 16:01:24', '2018-04-17 19:10:04'),
(291, 'ebedoyas@comfamiliar.com', '$2y$10$tmgWY0iPK25GlcIKGbPRp.Hf3ABlTLNcRiz64EsbZIe7Gl7JJYp9S', '1088024322 ', 'Evelyn Bedoya Sepulveda', 2140, 0, '2018-02-02 22:33:06', '2019-05-20 12:23:58'),
(292, 'ebenitez@comfamiliar.com', '$2y$10$SDvtdRtQmWDCWMVLNQcV1eQ2kxJK0C46EEXnMVk5qcoGFtOXyibeC', '1112763296', 'Emerson Benitez Valencia', 800, 0, '2018-01-30 18:51:54', '2018-04-17 19:10:07'),
(293, 'ecamacho@comfamiliar.com', '$2y$10$1HVNKnNtA6d3oORlXIylt.eOgmuF9Mk1y3FuW81vfn0cXzrg1CmpG', '25249234  ', 'Erica Andrea Camacho Pamplona', 5315, 0, '2018-03-29 07:42:31', '2018-04-17 19:10:07'),
(294, 'ecanoo@comfamiliar.com', '$2y$10$.LdvgFEw3ynAW/xeyFR3L.lafkx6R7TTGDl6l22geh/XRs3.B02gq', '1087559437', 'Esther Juliana Cano Ortiz', 3101, 0, '2018-02-12 20:04:45', '2018-05-04 16:42:43'),
(295, 'ecardonaco@comfamiliar.com', '$2y$10$YJyoLunCcQT6rlCep2PpIu8l0S/8Vp7COI8WXdsQTrCR1c/hfAhNO', '1053789568', 'Eliana Cardona Correa', NULL, 0, '2018-04-25 20:50:40', '2018-04-25 20:50:40'),
(296, 'ecaro@comfamiliar.com', '$2y$10$PbFgMQxp6JvFi9Eckb1UWuf4VBM9YOAfY6GoW9QM5klcPqzSWJTJO', '1112761060', 'Elisabeth Tatiana Caro', NULL, 0, '2018-12-14 15:32:19', '2018-12-14 15:32:19'),
(297, 'ecasallas@comfamiliar.com', '$2y$10$gz4NXFus2j7nPZ568juJnuLkGzjHCJbfFFizxuC2Ef..wV9dATXpe', '42164096', 'Elsy Janeth Casallas Hernandez', 3300, 0, '2018-02-13 16:57:23', '2018-04-17 19:10:07'),
(298, 'ecastano@comfamiliar.com', '$2y$10$.vxvT8LwRop7ig8bjkWE2e1IX9D/9ps2U.GpzEBxENXIgpRFyrFxK', '1088019882', 'Erika Bibiana Castaño Zuluaga', 5342, 0, '2018-02-08 16:33:32', '2018-04-17 19:10:08'),
(299, 'ecolorado@comfamiliar.com', '$2y$10$W8XeKaCPAoS/w.GrRs7kC.2h7VClwEnzquvn6rGoT5IgnmVCRBG.i', '1087551448 ', 'Edison Javier Colorado Mejia', NULL, 0, '2018-04-30 00:24:46', '2018-04-30 00:24:46'),
(300, 'edperez@comfamiliar.com', '$2y$10$5ZlAL0VhKNu9WKrG4cGapueaJjIBn/LOaBe3ZDAdo8pb0qfCXV4rO', '10006635', 'Edwin Andres Perez Castro', NULL, 0, '2018-12-07 21:27:04', '2018-12-07 21:27:04'),
(301, 'eduque@comfamiliar.com', '$2y$10$FjsfvR8lm6K7FuW4HTeAbOxcLtUXHdmZTJzVlST14rJZcoU/sfIXa', '1088328216', 'Esteban Duque Caro', 3732, 0, '2018-02-08 15:45:56', '2018-04-17 19:10:08'),
(302, 'eflorez@comfamiliar.com', '$2y$10$kkEn/qLr0oeB/ZNjiyKmYeFMny25Rq4/Jt.lfYJzhRWIQH236Na36', '6282912', 'Edgar Leandro Florez Macias', NULL, 0, '2019-02-21 22:51:05', '2019-02-21 22:51:05'),
(303, 'efranco@comfamiliar.com', '$2y$10$t/yRkGlcM0Nc3JGeqGTs/upeWeEYwNe86h3CIKvS.hYo.OSPUKAFK', '42006309', 'Elsa Margoth Franco Henao', 3300, 0, '2018-01-26 13:32:46', '2019-05-02 15:36:25'),
(304, 'egarciaq@comfamiliar.com', '$2y$10$KyZoj0IE7UEG1Krgh2zIF.oBj2P./Ick3JGMK149GwuojtTA4MVMa', '4518654', 'Edwin Andres Garcia Quiceno', 3300, 0, '2018-04-06 19:40:17', '2018-04-17 19:10:08'),
(305, 'egiraldo@comfamiliar.com', '$2y$10$47KIquyklKMnVrAb0dnKnOYYw8zRv1vmLukafqSQlagP0Y6AbZ1ZO', '42065106', 'Elsy Giraldo Valencia', 5333, 0, '2018-02-08 20:51:40', '2018-04-17 19:10:08'),
(306, 'egomez@comfamiliar.com', '$2y$10$4isOHPOwd0DTuPNLIR7Bre4U2/5CW6BXukPvRGCONtz6XI57Usfli', '26391258', 'Enza Eliris Gomez', NULL, 0, '2018-11-17 21:42:14', '2018-11-17 21:42:14'),
(307, 'egranados@comfamiliar.com', '$2y$10$oBowcBcZLh3DvxVzsSHISeHxVwNj82dWi8pJTnHtZi50JVCOQ3E9C', '1088268998', 'Edward Leonardo Granados Cordoba', NULL, 0, '2018-12-06 16:47:00', '2018-12-06 16:47:00'),
(308, 'eguevara@comfamiliar.com', '$2y$10$WsK0RnI8VZSLpNne31xSk.iHAg4YEtfLnwlDTH8I/Msk1flQ03vb2', '30383691', 'Elizabeth Guevara Reina', 5372, 0, '2018-03-19 08:36:29', '2018-04-17 19:10:11'),
(309, 'egutierrez@comfamiliar.com', '$2y$10$uAH6oWhj5yV4CM1g4OV/VuBiDJfxOeg/sAUSohjCzwkpX9vt./Z9O', '10029591', 'Elkin Marino Gutierrez', 700, 0, '2018-01-10 16:39:55', '2018-04-17 19:10:11'),
(310, 'egutierrezb@comfamiliar.com', '$2y$10$V0PcOhLQYTXTW.3EXZTS.uUAlaY5gW5rEXn2IJPi0yuydTdTZDNae', '18513359', 'Eder James Gutierrez Blandon', 5171, 0, '2018-02-15 15:08:41', '2018-04-17 19:10:11'),
(311, 'ehernandez@comfamiliar.com', '$2y$10$YnW1/IcAGG28FyGOIY1Q5eUD6fDGnoh1aSbG4RJPBYPxiljNQEZNq', '10022416', 'Edison Hernan Hernandez Tabares', NULL, 0, '2018-11-13 15:31:23', '2018-11-13 15:31:23'),
(312, 'ehoyos@comfamiliar.com', '$2y$10$2aEV0iouNypZslavjpy1W.H/7b9NkiFT6vzdkqaeBtfbf8W9F7PWy', '1225092138', 'Ebelin Hoyos Zea', NULL, 0, '2018-11-01 18:48:18', '2019-02-15 13:51:55'),
(313, 'ejrico@comfamiliar.com', '$2y$10$Qv124cZ7s9bD3nnlrNHHv.Sz5RXcET3g4/irbJcKPAchI1AwHLBAK', '1112789877', 'Emilio Jose Rico Vicuña', 2313, 0, '2018-01-30 18:48:41', '2018-04-17 19:10:11'),
(314, 'elgarcia@comfamiliar.com', '$2y$10$mNvk9/luTyJK/edMdrXeCej7Ke9iZll36KvfRc/7qdCrGSoT9Mgh.', '30225635', 'Elizabeth Garcia', NULL, 0, '2018-04-23 19:45:32', '2018-05-31 20:32:27'),
(315, 'elondono@comfamiliar.com', '$2y$10$GIusfLgYcfNJZ9aFsQ2.eOMjN6nI7nsK1Z4WEp/m.H/Gcysoamgwa', '30316034', 'Elsa Victoria Londoño Cardona', NULL, 0, '2019-01-23 22:20:16', '2019-01-23 22:20:16'),
(316, 'emaldonado@comfamiliar.com', '$2y$10$YIVYVE2hvlY5Eqwu0E8X9.yVjNs7Vu6wwN6gL0Dmt9Z8rWAq87xhO', '1056302745', 'Eliana Esnella Maldonado Torres', NULL, 0, '2018-12-27 16:33:38', '2018-12-27 16:33:38'),
(317, 'emoralesg@comfamiliar.com', '$2y$10$s.tquyPDar0o8s7EyLaYOuYPo6bTzLNttpBH5qwl0ykXQnfBVixEy', '1112774295', 'Esmeralda Morales Gonzalez', 3732, 0, '2018-01-29 16:46:07', '2018-04-17 19:10:11'),
(318, 'emoscote@comfamiliar.com', '$2y$10$qyzt1yj/zATBaQTyGdgDZeHzNROlktGeaRpSRal0TfVsX7tbNcDsC', '23106559', 'Elvia Gregoria Moscote Pestana', NULL, 0, '2018-06-21 16:41:09', '2018-06-21 16:41:09'),
(319, 'eorrego@comfamiliar.com', '$2y$10$Fpq/7nXGu6Ej3HcqU7/owOz1ptPYR881pdoedADbyqOiDOUjnGnk6', '42086447', 'Elsa Patricia Orrego Gil', NULL, 0, '2018-11-16 13:58:11', '2018-11-16 13:58:11'),
(320, 'epareja@comfamiliar.com', '$2y$10$HDeTjB9vBVuKNEfN2G2Xt.xgZV6qW9xngCkVtD8xh2EJ2iVhhfQk2', '1127207385', 'Edwin Alexander Pareja Quintero', 3321, 0, '2018-02-18 02:36:39', '2018-04-17 19:10:11'),
(321, 'epenagos@comfamiliar.com', '$2y$10$AOloWG/b63b0LX1jjFPrpuHufG21wO8rqPYRs5rBs3ABMc18RJG1O', '53084332', 'erica penagos bedoya', NULL, 0, '2018-05-09 13:08:39', '2018-05-09 13:08:39'),
(322, 'epiedrahit@comfamiliar.com', '$2y$10$knO9aGuiHt5Kme2/bybxKu19RK.dfPtPCP./Tu2oF87ikLCV/Na0C', '24766613', 'Erika Andrea Piedrahita Gutierrez', NULL, 0, '2019-01-13 16:35:42', '2019-01-13 16:35:42'),
(323, 'epuentes@comfamiliar.com', '$2y$10$8kMOTWuU42DUXJy7S7cTxOeg0GX1LBBCDrNkvpzTz.MXqJGuJMS4K', '1088294206', 'Erika Lucia Puentes Betancourt', 800, 0, '2018-02-12 19:01:49', '2018-04-17 19:10:11'),
(324, 'eramirezt@comfamiliar.com', '$2y$10$eeO7ETorzwdpZXQ/MXkk6OG9Olaa.ekcP4YGC1iuU5YXFq2Zb10Wi', '1114883082 ', 'Ednna Rocio Ramirez Tobar', 3737, 0, '2018-02-15 18:44:03', '2018-04-17 19:10:11'),
(325, 'erengifo@comfamiliar.com', '$2y$10$fAuta9wfMrKa7G3sYKqvR.7n./lO6Lzs3O/SBTDJep30eJMGOjDcu', '1087998509', 'Erika Johana Rengifo Guapacha', 3320, 0, '2018-02-08 14:39:31', '2018-04-17 19:10:11'),
(326, 'erodriguezl@comfamiliar.com', '$2y$10$K1rgJOc.oCQw7TnlLg3/zOmt33FJvodhq31xP4wfgXECgEnBWHxDK', '42161079', 'Elsa Milena Rodriguez Lozada', 3720, 0, '2018-02-06 14:27:35', '2018-04-17 19:10:13'),
(327, 'esalazar@comfamiliar.com', '$2y$10$WPo1wsV1sUsbf0iH8Ujb.ObxMJiSVw1CsvOLsYloCreVsAoGSRWFS', '65710833', 'Emilce Salazar Quintero', 3030, 0, '2018-02-22 17:49:05', '2018-04-17 19:10:16'),
(328, 'esanchezm@comfamiliar.com', '$2y$10$6WoCcSuu.ResCkmYLGEwzOZkpjjMOcZr44I/14ExOVig0x9hedMLW', '1088288381', 'Eliana Sanchez Marquez', 3737, 0, '2018-04-12 20:10:35', '2018-04-17 19:10:16'),
(329, 'eserna@comfamiliar.com', '$2y$10$A190ydRUH2wD0N8Zad3YXuEzAfUilcKdgexyd8GvZzbn4qHuat3Z2', '30314405', 'Eugenia Del socorro Serna Zuluaga', NULL, 0, '2018-07-16 16:17:36', '2018-07-16 16:17:36'),
(330, 'esoto@comfamiliar.com', '$2y$10$30p2DWOKVkmg8VAb6Y.2VuqRcD4Gk0b22AE88x/jOHCamDjVTG0qy', '1093222592', 'Estefany Soto Gomez', 5180, 0, '2018-03-13 14:40:52', '2018-04-17 19:10:16'),
(331, 'evargas@comfamiliar.com', '$2y$10$lS/d1oXa6241Y1MGPkY39uZtqVKjagFwin7ddYnNZvXAZjHF4GwKu', '1088314416', 'Edwin Johan Vargas Medina', 2330, 0, '2018-03-01 19:37:22', '2018-04-17 19:10:16'),
(332, 'evargasm@comfamiliar.com', '$2y$10$.ySeqpBZqfSbmCPA6Yczs.U.6961M8SFRMxivdoLw81B4fg675x1q', '1088018259', 'Erika Alejandra Vargas Morales', NULL, 0, '2018-07-24 22:18:40', '2018-07-24 22:18:40'),
(333, 'evasqueza@comfamiliar.com', '$2y$10$..U1fXsJlECqh2L8Lxp3ieMVISm5ebHmeP/XcjS27G9VpbtSe8vSG', '1088304083', 'Estefania Vasquez Alzate', NULL, 0, '2019-01-28 12:29:26', '2019-01-28 12:29:26'),
(334, 'evillegas@comfamiliar.com', '$2y$10$An5HlgL8ZrOVl5gJ/SZ8wuh5bjEGyM6Pqu29zFYuonqMxD6Wv5d5i', '9868144', 'Edwin Alberto Villegas', 900, 0, '2018-02-14 13:35:03', '2018-04-17 19:10:16'),
(335, 'ezuleta@comfamiliar.com', '$2y$10$OUuVsmA.idXCqyfmAvTwOuel/tI/Mxu74alkyRFxOukLvAlz3UqXm', '1088242030', 'Eliana Marcela Zuleta Galvez', NULL, 0, '2018-06-05 17:30:08', '2018-06-05 17:30:08'),
(336, 'ezuletaa@comfamiliar.com', '$2y$10$Xyewe.fBRCPmv6/i.jkAguAzEVMO2wqMOkGOGqrKm8jzlu96lrlGi', '1087551416', 'Eliana Zuleta Alvarez', NULL, 0, '2019-04-25 18:04:53', '2019-04-25 18:04:53'),
(337, 'falzate@comfamiliar.com', '$2y$10$nz1MW1eK80j6R2JZx8EmsuPl5C.7sKTsnuSSt3AXiyYpU8KFpbYaW', '10116203', 'Felix Alzate Montoya', NULL, 0, '2018-08-23 15:17:20', '2018-08-23 15:17:20'),
(338, 'fariasa@comfamiliar.com', '$2y$10$ga8uI/JtLGzI2vsy2aUzf.tDLnl73UULG1QZuBLv.PYJ9IprbHYfq', '9850464', 'Fabian Alberto Arias Arango', 2132, 0, '2018-01-05 15:46:55', '2018-06-25 20:44:59'),
(339, 'fcalderon@comfamiliar.com', '$2y$10$3ZV4xgTlUZUcCg.rTUe/WejqFOaIlMnvLKLYkD6bRW2djYrGlzICG', '42105600', 'Francia Monica Calderon C.', 3737, 0, '2018-02-28 18:39:30', '2018-04-17 19:10:16'),
(340, 'fcardenas@comfamiliar.com', '$2y$10$rN1ynFU0NXnGd3ublusk2.YtlAGEU8y.Khz1/QKA0UEakIuNKnOGG', '4452804', 'Fernando Cardenas Rivera', NULL, 0, '2018-09-17 11:59:22', '2018-09-17 11:59:22'),
(341, 'fcarvajal@comfamiliar.com', '$2y$10$3v0eBKTQT4M.p3aLZeAkredG3MTo0.OaRUiFTM.zk3/vnBipLXGwO', '1090411299', 'Francy Mirley Carvajal Quintero', NULL, 0, '2019-04-02 18:54:51', '2019-04-02 18:54:51'),
(342, 'fdiaz@comfamiliar.com', '$2y$10$6Gs96eEjfOPrAAte9zunLupVRutVoZrJayqcvyrC31QmoNlBrprDa', '10105745', 'Fernando Diaz Lopez', 3729, 0, '2018-02-21 19:32:20', '2018-04-17 19:10:16'),
(343, 'fduque@comfamiliar.com', '$2y$10$d2QNhpqisPsMY60KrL0P1ukHkDJHbCg5xO9eRxIxeIzBzWGwjBV..', '89000703', 'Fabian Ariel Duque Rodriguez', 700, 0, '2018-01-30 14:46:41', '2018-07-03 15:20:22'),
(344, 'fgiraldom@comfamiliar.com', '$2y$10$w0fXI7r7GsTewSMPAIH5wu5e9zylFkCH1WedbhVb3IJbSqrXeFUTq', '10082806', 'Francisco Luis Giraldo Morales', 3730, 0, '2018-04-10 13:10:11', '2018-04-17 19:10:17'),
(345, 'fgomezl@comfamiliar.com', '$2y$10$NotsjixXyoWFJ1TIsd6g6edTFQ.Gpm2ualxYb2iRNhNhWSOiEOpK6', '1125271411', 'Felipe Gomez Lopez', NULL, 0, '2019-04-26 23:11:53', '2019-04-26 23:11:53'),
(346, 'fgonzalez@comfamiliar.com', '$2y$10$WEN9UoNeQRqzvjcqOcoFK..U5L3zoade2rSm5NSKsU5Hyqpf0eJUa', '1087984779', 'Fabian Alexander Gonzalez Arredondo', 3310, 0, '2018-01-26 13:46:52', '2018-04-17 19:10:19'),
(347, 'fmontes@comfamiliar.com', '$2y$10$eVxPKsP.f73nTF0mF94YD.o9hhLn1JyTGYnkGRk/P4vuZ.2Rb7siK', '42018637', 'Francia Elena Montes Garcia', NULL, 0, '2018-07-23 19:22:36', '2018-07-23 19:22:36'),
(348, 'fmontoya@comfamiliar.com', '$2y$10$nW0BNEnZ4di1QedvAg.Tbu/5x9fR2.YI7.0iZeeyHgGC0Kvq/GLxm', '16362538', 'Fernando Montoya navarrete', NULL, 0, '2018-09-17 04:42:13', '2018-09-17 04:42:13'),
(349, 'fmoreno@comfamiliar.com', '$2y$10$Hg//79B.aunwNo4gxGLdEuv.4sIwtynL7LVVh/myThhAQR2BUUavS', '18596923', 'Fabian Moreno Calderon', 800, 0, '2018-03-08 16:23:55', '2019-02-04 19:34:07'),
(350, 'fmurcia@comfamiliar.com', '$2y$10$kD0MNN3TrMf9sZUnXEIU4.CltdjuAx4zjbKFWtLS3C/kRZyFDraNa', '19260434', 'José Fernando Murcia Ríos', 2000, 0, '2018-03-14 23:00:18', '2018-04-17 19:10:20'),
(351, 'fosorio@comfamiliar.com', '$2y$10$33IrtXwk4WGHcUnF13shYeTbewJbI.xE9fVcDBBu4fUToU2AnZRdK', '10002162', 'Fredy Osorio Ramirez', NULL, 0, '2018-07-09 19:05:43', '2018-07-09 19:05:43'),
(352, 'framirez@comfamiliar.com', '$2y$10$bwWKLalZlQgC81V3pF/JKeK.GUKqTay0WgMvpKpM/Fx3h2Wq/yWwm', '42111142', 'Fanny Ramirez Collazos', 3202, 0, '2018-04-05 19:29:07', '2018-04-17 19:10:20'),
(353, 'ftovio@comfamiliar.com', '$2y$10$zRXFwtY9f.g4J3./ojsDb.p5LcWbJfG3EG3YZQWLJDvW7yS.7T5ha', '3837847', 'Fabian David Tovio Barboza', NULL, 0, '2018-09-16 10:04:18', '2018-09-16 10:04:18'),
(354, 'furibe@comfamiliar.com', '$2y$10$GEti.y2ltQyiwBHO.KVl7ORR2/FiDGbIhJwKDBixFuBLkummatezi', '1006381911', 'Fanny Julieth Uribe Muñoz', 3101, 0, '2018-03-21 19:59:01', '2018-08-15 15:43:13'),
(355, 'fvasquez@comfamiliar.com', '$2y$10$2ETlPKHyUKJ1KOW1W4f7oO1zdy1PbO0AoN9a9Iq693P1Gs.1ZZdca', '1088333756', 'Fabian Alexis Vasquez Moncada', 700, 0, '2018-01-29 14:55:17', '2018-04-17 19:10:20'),
(356, 'garango@comfamiliar.com', '$2y$10$BdTMH5lzOTu.Zlo5AlYn3eT0ZNavgalFVxAyWBhJ8uuHxhpAnjZMK', '42865091', 'Gloria Cecilia Arango Moncada', 3129, 0, '2018-02-07 17:05:45', '2018-04-17 19:10:20'),
(357, 'garcila@comfamiliar.com', '$2y$10$msjcFCc/q7idUnaRZg5iyeR0cpV00Bl/Qg9ggzE/Udftq0jEWDhP6', '42022161', 'Gladys Arcila Alvarez', NULL, 0, '2018-10-07 07:39:42', '2018-10-07 07:39:42'),
(358, 'gbadillo@comfamiliar.com', '$2y$10$4oOkDk8byTLM/LJkFgDulOLSUTd.upz6KzzYPCdpWHnhq0iV42KRa', '1093217970', 'Gustavo Andres Badillo Meneses', 3400, 0, '2018-01-29 18:37:34', '2018-04-17 19:10:20'),
(359, 'gbedoyas@comfamiliar.com', '$2y$10$pzuoYC3AVoQxSHrHs5haz.pM3XhNt2ZfVYD.CsjIApvt68u/sWZny', '25181815', 'Gloria Elena Bedoya Sanchez', NULL, 0, '2018-05-10 23:05:11', '2018-05-10 23:05:11'),
(360, 'gbeltran@comfamiliar.com', '$2y$10$NDV2dD0hesCUkQdQxF3sW.8IshAyxOQ0GFUb.4nwX4HTLjJ07Pb.W', '25160732', 'Gladys Beltran Rivera', NULL, 0, '2018-11-28 15:45:36', '2018-11-28 15:45:36'),
(361, 'gbermudez@comfamiliar.com', '$2y$10$pTge1ztADbGcLN.aJhbOAe8XjenE6JwNwyRweQPKyD4bwfY.1Hjq6', '10127132', 'Gustavo de Jesus Bermudez Villada', NULL, 0, '2018-07-30 15:45:47', '2018-07-30 15:45:47'),
(362, 'gbueno@comfamiliar.com', '$2y$10$s2/PPJkG9cqZqHg9TwMHDuDEpHDYv1Do.FAzOulqmzR6X5o9XNaiW', '10090921', 'Giovanny de Jesús Bueno', 2310, 0, '2018-02-02 14:41:39', '2018-04-17 19:10:20'),
(363, 'gburgos@comfamiliar.com', '$2y$10$WEIibcQSFCGjD.8WhSgDhOSkUdQZPk02pz4TrwOgDOFYgjqbWgjoS', '70093616', 'Guillermo Leon Burgos Delgado', NULL, 0, '2018-10-19 21:18:36', '2018-10-19 21:18:36'),
(364, 'gcandamil@comfamiliar.com', '$2y$10$g.zrwSNKQRh5mqwUlGKnL.APjntxgnBxNbvrdwRTXU9ZWqkqL9c7S', '42068351', 'Gloria amparo Candamil', 5332, 0, '2018-04-17 14:56:57', '2018-04-17 19:10:20'),
(365, 'gcardona@comfamiliar.com', '$2y$10$Y7eqVwmNkBV1hpzbxONFKeCacjSBGclQA8ZCb7UzAjJ3MOZtkjuSa', '33917609', 'Gloria Cristina Cardona Tapasco', NULL, 0, '2018-05-19 17:29:39', '2018-05-19 17:29:39'),
(366, 'gcardozo@comfamiliar.com', '$2y$10$ekukQ0NwvUHTSIGFqVKnjeX53LdDDVEEywKeMdBHmWgoyP2crAKFS', '33815709', 'Gloria Ines Cardozo Rodriguez', NULL, 0, '2018-04-20 05:57:12', '2018-04-20 05:57:12'),
(367, 'ggarcia@comfamiliar.com', '$2y$10$lAX3NJNRCN4eI63tlSZGiOAXHErxGIWg2z6AVg8IRCH8HGwE3S0pm', '18511546', 'Geovanny Garcia Rojas', 3030, 0, '2018-04-03 18:38:39', '2019-04-02 14:36:21'),
(368, 'ggilv@comfamiliar.com', '$2y$10$g55RPLCiCi14slkcdtVCResz44cydjTQyWUBcdrUc1ZmNcXgJPshu', '1059784936', 'Gustavo Adolfo Gil Velez', NULL, 0, '2018-12-21 14:11:27', '2018-12-21 14:11:27'),
(369, 'ggomez@comfamiliar.com', '$2y$10$YQUUuGPznrqoMysssJ3EV.jagW7Pu6VEJT.U4rDeTRNB1I0vKzGBe', '24413663', 'Gloria Elena Gomez Gallo', 5347, 0, '2018-04-16 05:04:48', '2018-04-17 19:10:24'),
(370, 'ggordillo@comfamiliar.com', '$2y$10$wQ6xLMNxqHRqUWVLg/8I.ucTPg1cHxlRSRRBOgy/pt6BvkfDb3Tei', '42101868', 'Gloria Janeth Gordillo Quintero', NULL, 0, '2018-08-12 08:29:31', '2018-08-12 08:29:31'),
(371, 'ghernandez@comfamiliar.com', '$2y$10$JjKhAVtbvhCegK5LIsYiQ.qTkyJq3TEhP3OlmSuwH9NfcNmx.ncS2', '42085652', 'Gloria Patricia Hernandez Ramirez', NULL, 0, '2018-05-03 20:15:09', '2018-05-03 20:15:09'),
(372, 'ghernandezc@comfamiliar.com', '$2y$10$vOoMc5eTl3QH9o8SI5rIX.E7KcnV8FlreZyh0KpCBn6Pldd3sgqPa', '24695000', 'Milena Hernádez Cifuentes', 3003, 0, '2018-02-09 16:21:24', '2018-04-17 19:10:24'),
(373, 'gjgomez@comfamiliar.com', '$2y$10$91xtmWTLP8TbaOj/mOxqmufq9vv2Qi04tmVukowZaLf120D4N6Lki', '10137376', 'Gustavo de Jesus Gomez Gomez', NULL, 0, '2018-09-30 04:54:02', '2018-09-30 04:54:02'),
(374, 'gjimenez@comfamiliar.com', '$2y$10$V1sZE0VIX1heUGa53jFfJOuQ.of9AeMis54knZF19lr0ZBNxDdh12', '42063025', 'Gloria Amparo Jimenez Jimenez', 2310, 0, '2018-01-09 20:38:26', '2018-04-17 19:10:24'),
(375, 'gjimenezs@comfamiliar.com', '$2y$10$1opBR5NIHYSUfujNhwa5Ieg38tLQFtOuMx.FR0GwExwokZIJtamWq', '31420942', 'Gloria Milena Jimenez Suarez', NULL, 0, '2018-12-18 12:54:26', '2018-12-18 12:54:26'),
(376, 'glogiraldo@comfamiliar.com', '$2y$10$eXjYcFlE7WD./gvlI55FDuLubQpdjSYNdRnbDdsKnKN.QfRJlmiHO', '42154125', 'Gloria Mercedes Giraldo Loaiza', NULL, 0, '2019-02-01 15:01:48', '2019-02-01 15:01:48'),
(377, 'gospina@comfamiliar.com', '$2y$10$Sjry7OXiFymo21SxqnREyOFRiohHQ3uud8CuAce/uPJ8hwx/JFgs6', '42003566', 'Gloria Ines Ospina Giraldo', 2313, 0, '2018-04-02 22:35:00', '2018-04-17 19:10:24'),
(378, 'gpalomeque@comfamiliar.com', '$2y$10$pgBu/h7zwJZ/N5HK5CWh3e.dlMdTlgAHuWhuOANpx9GTZ3n9.fbxC', '1010034195 ', 'Gloria Yaneth Palomeque Mena', NULL, 0, '2018-09-12 19:32:52', '2019-02-16 17:59:30'),
(379, 'gquirama@comfamiliar.com', '$2y$10$5fTKsXK.XZwDp9EIwSfW3ulgiKMwDa3BYy2Sx5BokdPuQpFwEpQIy', '1087989002', 'Gloria Marcela Quirama Naranjo', NULL, 0, '2019-02-05 12:58:21', '2019-02-05 12:58:21'),
(380, 'gramirezh@comfamiliar.com', '$2y$10$LX1oarpHIEFSxoeVhhfdPuJqCyt8v5EVCEgS0AXoWJksCYMGYzF1.', '1093222488 ', 'Gubier David Ramirez Hurtado', NULL, 0, '2018-06-17 15:03:04', '2018-06-17 15:03:04'),
(381, 'grengifo@comfamiliar.com', '$2y$10$7PoTdpkLncmvIIUYGdXjLetEcS7j0hMu6aoLyeUQsSGeeewSQYuyG', '38445458', 'Gloria Marleny Rengifo Ramirez', NULL, 0, '2018-09-20 21:38:04', '2018-09-20 21:38:04'),
(382, 'grestrepo@comfamiliar.com', '$2y$10$c5elVqymUQjSS74M64KOgOp3gRYQHWBZKD362ZO/EYzRkwKXViEj6', '25193875', 'Gladys Orfidia Restrepo Vasquez', NULL, 0, '2018-08-17 08:29:43', '2018-08-17 08:29:43'),
(383, 'grevelo@comfamiliar.com', '$2y$10$pQ62TdgMPcFQs0I3gOJsVeZixV0CyRZFVny6Zuzw50/yChKcSePai', '1085897898', 'German Camilo Revelo Pazmiño', 3737, 0, '2018-02-28 19:56:21', '2018-04-17 19:10:24'),
(384, 'grivas@comfamiliar.com', '$2y$10$2.YWOzFaGafOLjfO.Qe4u.ap4PTGI982xZLcbkVq/OrqoIVvJmCzG', '42079539', 'Gloria Esperanza Rivas Calvo', 3737, 0, '2018-01-30 11:51:24', '2018-04-17 19:10:24'),
(385, 'grojas@comfamiliar.com', '$2y$10$AKARgpxpQ4Yg9LDjwpe92uxVq7W0bf647mTvA770aioNOm07cuvnW', '1088009048', 'Geraldine Lizhet Rojas Acosta', 3792, 0, '2018-01-15 18:31:32', '2018-04-17 19:10:24'),
(386, 'gsarmiento@comfamiliar.com', '$2y$10$u5w5FtPN1yr.2219vCgeo.Unhz/QSB9DTT3jvlBBbfI51xR2iaiyK', '10129934', 'Gustavo Alfonso Sarmiento Triviño', 3720, 0, '2018-02-06 20:11:16', '2018-04-17 19:10:24'),
(387, 'gsoto@comfamiliar.com', '$2y$10$bhFSChjBGZsSuZJJsGouoO0YrLuVFR68IqgctDQR3i//Qk.jFjAla', '10137402', 'GUSTAVO ANTONIO SOTO REYNOSA', NULL, 0, '2019-03-07 11:32:07', '2019-03-07 11:32:07'),
(388, 'gsuaza@comfamiliar.com', '$2y$10$9kURFAR78lvgVuV9rssw4.3drXimGotKacetN/dJqHZntjxyNWiL6', '42007427', 'Gloria Cecilia Suaza Ayala', NULL, 0, '2018-11-13 22:48:47', '2019-02-24 22:16:06'),
(389, 'gtabares@comfamiliar.com', '$2y$10$blLStMBpFjOkW8RFs7zAfu6exJwYU0aNoFnUxR5Z1a241b5z3Azse', '10124705', 'German Tabares Naranjo', NULL, 0, '2018-10-26 12:45:00', '2018-10-26 12:45:00'),
(390, 'gtorresg@comfamiliar.com', '$2y$10$nLhBJybYJAhZqa7oDvoaguoFSAwMgpCiY9Bz6MelzrdxvHTDzdh0y', '1233499261', 'Geraldine Dayana Torres Gutierrez', 3101, 0, '2018-02-13 20:14:15', '2018-04-17 19:10:24'),
(391, 'gzamora@comfamiliar.com', '$2y$10$a/MIyAY3e/0U.qElZoFt7uZ8jROdQBRM.OQTPoCBM4TuDlT7xwtLe', '24549882', 'Graciela de Jesus Zamora Villegas', NULL, 0, '2019-04-27 13:44:17', '2019-04-27 13:44:17'),
(392, 'gzapata@comfamiliar.com', '$2y$10$cK673eVPU/oDrVTwXynDNedfm3UZofTPO45wUcFDdtNuUI4c4H5Fa', '10280829', 'Gerardo Antonio Zapata Valencia', NULL, 0, '2019-05-20 20:18:58', '2019-05-20 20:18:58'),
(393, 'hbueno@comfamiliar.com', '$2y$10$ekNmC4RYIzj73dWEI/0ZdOybrbM0g7LeR9FE7GC.FzxIkLeWywGSq', '10024642', 'Henry De jesus Bueno Morales', NULL, 0, '2018-12-16 10:04:31', '2018-12-16 10:04:31'),
(394, 'hgonzalez@comfamiliar.com', '$2y$10$88rIxGlJBsxATidr6MV2ceLAPby5HOu4qrsRv/4jtsbSr3y6xG0XS', '4585878', 'Haymer Orlando Gonzalez Valencia', NULL, 0, '2018-08-27 13:46:51', '2018-08-27 13:46:51'),
(395, 'hherrera@comfamiliar.com', '$2y$10$8jJTDF.k0lsZVVJPe.iQoOqqAljUTn7KbCa.EkwZSrOKpylvUQAnm', '10025061', 'Herson orlando herrera alzate', 5315, 0, '2018-04-12 07:48:34', '2018-04-17 19:10:27'),
(396, 'hmontoya@comfamiliar.com', '$2y$10$3asJ6LELFCvNLglrlRxTgOAb19fR3rqHacqWWISYyU/1tStPXeBTy', '1093221320', 'Hector Hernando Montoya Quiceno', 3713, 0, '2018-02-06 13:19:26', '2018-04-17 19:10:27'),
(397, 'hprieto@comfamiliar.com', '$2y$10$cp1lu/1HHM9/ChwobAQz6eq/ZktrPDnpJDF.QKLt22Dg7zvTaWCqm', '1088029352', 'Harvey Prieto Agudelo', NULL, 0, '2018-05-15 20:07:29', '2019-01-25 16:35:56'),
(398, 'hsalazarc@comfamiliar.com', '$2y$10$TaIbar/T2BR50SnIgrkXkux98J4C9AicKuJai3eDLaZ82.8/aVYpG', '1088295650', 'Hector Alejandro Salazar Carmona', 3014, 0, '2018-02-06 14:50:07', '2018-04-17 19:10:27'),
(399, 'iarroyave@comfamiliar.com', '$2y$10$JbEzn0ybhi0aDZSOkwDad.xWXmAUWPF8kmey/Q.rxxX5Go4JOSQn2', '1094904781', 'Ivonne Nathalia Arroyave Bermudez', NULL, 0, '2018-05-02 18:58:06', '2018-05-02 18:58:06'),
(400, 'ibetancur@comfamiliar.com', '$2y$10$n/CUgLEET6mqFoBF7/lgme6V2cNBvZxaW8irTIGNP6i2B.tg8rQsa', '1088269232 ', 'Isabella Betancur ', NULL, 0, '2019-05-14 00:31:30', '2019-05-14 00:31:30'),
(401, 'iespinosa@comfamiliar.com', '$2y$10$duk3OWHNwmwP8ue1pzRbZes5JIxYTkBC7tor0keAY7egwhrk7TLlG', '1088338339', 'Ingrid Yuliana Espinosa Hurtado', 2330, 0, '2018-02-13 21:11:42', '2018-04-17 19:10:27'),
(402, 'imeneses@comfamiliar.com', '$2y$10$QeeqLoutclMwkHa1g3BS0uALm/.FGaJOBuZxioeHVi1A7AaQtB/Ii', '42123040', 'Indira Yohana Meneses Ortiz', NULL, 0, '2018-08-31 12:14:45', '2018-08-31 12:14:45'),
(403, 'iprada@comfamiliar.com', '$2y$10$wKTjSKeU53xvGpEL0dfRWOH5bYrsCGZF/Zj2hQ/GEgR3Gj15X4rzG', '42089203', 'Isabel Prada Conde', 2110, 0, '2018-02-05 13:58:56', '2018-04-17 19:10:27'),
(404, 'jagudeloe@comfamiliar.com', '$2y$10$tBlXpcKr7YzRjlbmeSn8LesXRQjdt.42Qs7uSlGXW.HdoKvZYgC56', '1053772970', 'Juan Sebastián Agudelo Echeverry', NULL, 0, '2018-10-23 17:01:25', '2018-11-16 07:28:58'),
(405, 'jagudelog@comfamiliar.com', '$2y$10$UZ0Qqwye7KkVgHcaZyyCyeQ.IEupzdT0ivTLUMJxff.U4wkUZ8F3G', '10010322', 'Jorge Ivan Agudelo Giraldo', NULL, 0, '2019-02-04 14:58:58', '2019-02-04 14:58:58'),
(406, 'jagudelogi@comfamiliar.com', '$2y$10$eqjo0RGHMlbLgAHCdzC3tuRgZNFu/NrMvgfIbj0U0vl09Py.IR8Ta', '1088242338', 'Jessica Agudelo Giraldo', NULL, 0, '2019-01-03 14:20:50', '2019-01-03 14:20:50'),
(407, 'jaguirrea@comfamiliar.com', '$2y$10$/wLy8cVVvlRPQ6eOeyZ0MOhNyQD5J5c1HmMZvhdhJUSXgybk1Quqi', '1054991670', 'Juan Daniel Aguirre Arenas', NULL, 0, '2018-04-25 19:55:21', '2018-04-25 19:55:21'),
(408, 'jaguirree@comfamiliar.com', '$2y$10$HNFhebfaRGUvP4T/9HlNPuyPCfxQtY8t3exxHWZ9IuaAhbvi37nQ2', '10130507', 'Jose Jair Aguirre Espinosa', 2313, 0, '2018-01-29 19:13:33', '2018-04-17 19:10:27'),
(409, 'jaguirrem@comfamiliar.com', '$2y$10$iBq2td3Zbc0ZxObqQziCxOIHEvtBT8vVeA7hB.RJVnJq8HM0NYjKi', '4513382', 'Julian Aguirre Montoya', 3737, 0, '2018-02-09 20:04:23', '2018-04-17 19:10:27'),
(410, 'jalvarez@comfamiliar.com', '$2y$10$pDGoSlFjkySRJHt9AuBJj.K4Kkvmt/cV4f1fDFZKT0XZ45pkxAiAe', '1088317604', 'Juan Esteban Alvarez Henao', 2110, 0, '2018-01-30 22:31:38', '2018-04-17 19:10:27'),
(411, 'jalzateq@comfamiliar.com', '$2y$10$x2McxWMQqgxyZgZs5LLXGuRvQ2/.B8UQDStVhB45x65CT9NaXRYyi', '10122427', 'John Jairo Alzate Quintero', NULL, 0, '2018-05-18 02:57:00', '2018-05-18 02:57:00'),
(412, 'jamezquita@comfamiliar.com', '$2y$10$wuNLubmi/Aeb1DPYbXY4Q.rr8u0cOPSQWsiD9/CQYK./dbhymrciS', '18513234', 'Jhon Alejandro Mezquita Valencia', 2140, 0, '2018-02-28 15:50:02', '2018-04-17 19:10:27'),
(413, 'jarangom@comfamiliar.com', '$2y$10$629WJfai6TlqN50ItxZ2HeNUgWNtTqqlRDWTuDcGSSzK.TQR1I1MS', '9865222', 'Jhon Alejandro Arango Marin', 162707, 0, '2018-04-13 20:32:36', '2018-04-17 19:10:27'),
(414, 'jarestrepo@comfamiliar.com', '$2y$10$A4ka8ojKZigqiZOczUU.1eO1PhQAUycdky6UU4D6Y5MYBXZJUGXTy', '9956046', 'Jaime Restrepo Moreno', 900, 0, '2018-01-30 22:09:03', '2018-04-17 19:10:30'),
(415, 'jariaso@comfamiliar.com', '$2y$10$gdlVsNDsq9nMoYj6OtgxTuMiaFbGRW4/63K96UveMviEecWCCfSnO', '1112773113', 'Jaiber Ariel Arias Orozco', NULL, 0, '2018-05-02 14:51:31', '2018-05-02 14:51:31'),
(416, 'jariasr@comfamiliar.com', '$2y$10$yJGfQ/lw9r6.nBERCgYsSu/oriPT/CqrMwdRGH971pv2MUPGY.tj2', '1088537374', 'Juan Emilio Arias Restrepo', 5349, 0, '2018-03-28 06:24:06', '2018-04-17 19:10:30'),
(417, 'jayala@comfamiliar.com', '$2y$10$R6TDB1ePEnLu1cyB13fEmeOqbR6mMgikGbE75zHhD42ic.tbe/IBC', '1087556388', 'Jhony Alexander Ayala Velez', 3737, 0, '2018-01-30 12:36:39', '2018-04-17 19:10:30'),
(418, 'jbedoyav@comfamiliar.com', '$2y$10$2FJ2wEfxd6GcU/nXHmEhIO1lPjfSkB3psH9FJvp5G7iUHY.CIFbji', '10084742', 'Juan Bedoya Valencia', NULL, 0, '2018-04-20 12:38:29', '2018-04-20 12:38:29'),
(419, 'jberrio@comfamiliar.com', '$2y$10$3g7S6InMIAUxBXwDOabo3eWBREfPw2VA13Mld3sTv.ThSEhLL9afO', '15457177', 'Julio Alberto Berrio Betancur', 3737, 0, '2018-02-09 16:29:45', '2018-04-17 19:10:30'),
(420, 'jberriov@comfamiliar.com', '$2y$10$HR5G0fbJBSMZXMC7FlalyO/xl6./uWfBX3NotidcexX4MnV45l30K', '9870148', 'Juan Manuel Berrio Vinasco', NULL, 0, '2018-06-19 21:36:09', '2018-06-19 21:36:09'),
(421, 'jbolivar@comfamiliar.com', '$2y$10$KLQpHMuu3z78WND9ipNqOOMCPdbmz8bw3YCIlEhGG.HSLKQIBFHO2', '10029237', 'Jhon Alexander Bolivar Carvajal', 5240, 0, '2018-04-03 22:06:43', '2018-04-17 19:10:30'),
(422, 'jbonilla@comfamiliar.com', '$2y$10$ZpCjvV5oeN7nXo6JiqZGd.9hiDJKYDD0zzq3F43eL2hwJZiaJOcae', '14320774', 'Jose David Bonilla Echeverry', NULL, 0, '2018-06-01 15:58:18', '2018-06-01 16:27:05'),
(423, 'jbuenob@comfamiliar.com', '$2y$10$EZWmjuiefPC/nh2I0K/3HuJn0CXLsRcqWcvQXxoIBTix/Cd4U1rsm', '4519901', 'Juan Manuel Bueno Bueno', 2313, 0, '2018-02-07 01:01:23', '2018-04-17 19:10:31'),
(424, 'jbuitragoc@comfamiliar.com', '$2y$10$YKRL7/RIjt.vJtCKDgju1.mEzfNazCyQKMToZSWoXU8Gk36zZTlCe', '10129940', 'Jose Rafael Buitrago Caicedo', 5171, 0, '2018-02-15 14:55:31', '2018-04-17 19:10:32'),
(425, 'jcala@comfamiliar.com', '$2y$10$gYIVJzuRddODXof7OIWKS.LGuKjyTVTKZ.HIpK4I/6NIU6CMOI6B2', '1065586074', 'Jenny Paola Cala Niño', 3101, 0, '2018-03-01 23:09:11', '2018-04-17 19:10:32'),
(426, 'jcalderon@comfamiliar.com', '$2y$10$Xb01T5lS4vv01enCuKIm7Ov5Prk7biR4qQSYQVfegCuauEYSb1n5u', '94175005', 'Jose Antonio Calderon Correa', NULL, 0, '2018-06-14 12:38:59', '2018-06-14 12:38:59'),
(427, 'jcanasf@comfamiliar.com', '$2y$10$OnFYO/NRQYjO5wpCwrJgHet9QN8kB0iu15pZnj.EMDZvGh4ALxG6G', '1088011936', 'Jhoan Sebastian Cañas Franco', NULL, 0, '2018-07-19 21:10:37', '2018-07-19 21:10:37'),
(428, 'jcano@comfamiliar.com', '$2y$10$TwcqaVAwgEuCj7KvAD8ZIOhMRfj9t1VHqW0OklggWB15h5f5DUPcy', '10135312', 'José Jair Cano García', 3101, 0, '2018-02-09 15:10:32', '2018-04-17 19:10:32'),
(429, 'jcardenas@comfamiliar.com', '$2y$10$vc8COhorVhnoMo3jcCttRe5qNBf5mHZVohc.NeVJhEHDY4To0K/Ea', '10113309', 'jose fernando cardenas henao', NULL, 0, '2019-01-14 21:09:30', '2019-01-14 21:09:30'),
(430, 'jcardonari@comfamiliar.com', '$2y$10$bzV/lSjP/O4rojwaeFazkOS7VyUOXi7CbfBJAgkrrK40Sk3rzvkGi', '25101111', 'Jackeline Cardona Rios', NULL, 0, '2018-04-17 21:12:55', '2018-04-17 21:12:55'),
(431, 'jcardonav@comfamiliar.com', '$2y$10$FjY2VsvVnx.kBkMMba4CkuMmEhi9yphxWJa0GoX7Q3F5BoaNO09.W', '1087554350', 'Jessica Cardona Velarde', 3101, 0, '2018-02-09 22:30:48', '2018-06-01 21:29:01'),
(432, 'jcastanoj@comfamiliar.com', '$2y$10$XgIVaoYZS8GsDzKxNU5.5O/usSrNaF/eETJKjPF8LW/kxZcZtfg/C', '42163539', 'Johanna Castaño Jimenez', NULL, 0, '2018-04-19 22:23:24', '2018-04-19 22:23:24'),
(433, 'jcastrillon@comfamiliar.com', '$2y$10$XaLr8A2Ex4U36.gyuBnSme2aFeEBDs5QVaCzeLZY1N/iIXaPSpQsS', '10019403', 'Juan Ricardo Castrillon Amaya', 3737, 0, '2018-01-30 14:58:25', '2019-02-20 13:31:15'),
(434, 'jcastro@comfamiliar.com', '$2y$10$4yC02VFJDqsPf0uPNtv0qu6mlwTYbQv19qAU/TWd3P7L2I9h34ipK', '4579829', 'Jose Abel Castro Ortiz', 2310, 0, '2018-02-08 12:42:33', '2018-04-17 19:10:36'),
(435, 'jceballos@comfamiliar.com', '$2y$10$fl8gy1h8mqPPQMUwZwc2z.eJuG.Mdf5V3BYHzZujulhIFBViuu7rG', '10127528', 'Juan Carlos Ceballos Muñoz', 2310, 0, '2018-01-10 21:20:59', '2018-04-17 19:10:36'),
(436, 'jchica@comfamiliar.com', '$2y$10$JRm2d5pWy7eJYjHN.oyHrenQu2el7AAGNcPe2/zqm1SmtL/arwvLq', '18597815', 'Juan Pablo Chica Duque', 900, 0, '2018-02-07 19:38:24', '2018-04-17 19:10:36'),
(437, 'jclavijo@comfamiliar.com', '$2y$10$UgcGG9wstBq4DkDfJQ3dsOkHM53keR7FkE33soYCWGVIdL/miBEMy', '1088254275', 'Juliana Clavijo Garcia', 3320, 0, '2018-02-09 19:11:49', '2018-04-17 19:10:36'),
(438, 'jcorreaap@comfamiliar.com', '$2y$10$K.7foq9/POLPSscKJY6kn.Q1FMzFqcwAZ6eMHLfAmZg7uDB0NkY8S', '1087990710', 'Jennifer Correa Aparicio', NULL, 0, '2018-07-22 05:03:33', '2018-07-22 05:03:33'),
(439, 'jcorreap@comfamiliar.com', '$2y$10$aO.HZTwd6J.rs1ddKsVV2.FqoN/hxyBl1J.a2x0Dn3wU71v1pRvmy', '1088286591', 'Jenifer Carolina Correa Pelaez', 400, 0, '2018-02-12 15:19:46', '2018-04-17 19:10:36'),
(440, 'jcortessa@comfamiliar.com', '$2y$10$sINujrmPJR0zjJel3acfS.Yz.B6jcOv41H9zaX.0RzuLuHwr2otDG', '1087996755', 'Juliana Cortes Sanchez', 3737, 0, '2018-03-02 20:52:20', '2018-04-17 19:10:36'),
(441, 'jcrosthwaite@comfamiliar.com', '$2y$10$z0QsI0hk76Zyr81hkdymj.hn2h44rRrqcw7wUB6Na4pS.U9vR1mCS', '1088240993', 'Juan Pablo Crosthwaite Mejia', NULL, 0, '2018-05-25 18:20:47', '2018-05-25 18:20:47'),
(442, 'jcuellar@comfamiliar.com', '$2y$10$V8gIgCZv14QzDWOXzWkNGuJi2wZXll1mWUuGibBjdB.yucp3kQDNi', '4512296', 'Jorge Enrique Cuellar Betancourt', 700, 0, '2018-01-29 15:23:46', '2018-04-17 19:10:37'),
(443, 'jdelgadot@comfamiliar.com', '$2y$10$NcqibpmKp4OeN9uoknlRguIxrDbRbXmYrkEPTI7zLs9LTmIAuB1kG', '12136869', 'Jairo Delgado Tovar', NULL, 0, '2018-04-27 05:42:02', '2018-04-27 05:42:02'),
(444, 'jdorado@comfamiliar.com', '$2y$10$y3DRDAknDTgT6mlRNLBwn.LELz/nKbmmosj4j51gAlScCtXc93qGC', '76028725', 'Juan Pablo Dorado Gaitan', NULL, 0, '2018-08-17 13:59:01', '2018-08-17 13:59:01'),
(445, 'jecheverry@comfamiliar.com', '$2y$10$W7/3knugS7LicRktN9hqVeYkqzBkGj3lUIDXwU0uRA4dy3B.VPx9u', '10118939', 'José Davinson Echeverry Bermeo', 2120, 0, '2018-01-29 21:04:41', '2018-04-17 19:10:37'),
(446, 'jescobar@comfamiliar.com', '$2y$10$0oWefr13sbXm8949vKIWFOPb3pEatC3d.mdKvcctT5CEfwNQcdUzC', '42073125', 'Julieta Escobar', 3300, 0, '2018-02-06 21:21:37', '2018-04-17 19:10:37'),
(447, 'jespanas@comfamiliar.com', '$2y$10$3cJIWIE/ip/xEc4quFF0qOgvihxUfG4XjCxvIWjn8fKezowSM5BdK', '10120107', 'Jose Dario España Sanchez', NULL, 0, '2018-06-23 08:15:28', '2018-06-23 08:15:28'),
(448, 'jestradaa@comfamiliar.com', '$2y$10$UqWWVIGE5U3L3iI9y3ESJuBsa/xEtHAuXAGq5FMcer0D0hU6rfwwW', '9870042', 'Jorge Mario Estrada Alvarez', 3014, 0, '2018-02-02 18:51:20', '2018-04-17 19:11:21'),
(449, 'jfcastano@comfamiliar.com', '$2y$10$M6V2OjIPi7icxZCsujHQW.reE5Iql4sipt61VF3Gl5x3HUP9VVIni', '18465787', 'Jhon Faber Castaño belalcazar', 2313, 0, '2018-04-16 13:51:20', '2018-04-17 19:11:21'),
(450, 'jfgonzalez@comfamiliar.com', '$2y$10$qBHu0cUWEO5i6kJ/3kJrQupCWx3sDVH4/uKFr4i03jPukr.NKsNti', '9872341', 'Jhon Freddy Gonzalez Duque', NULL, 0, '2019-01-14 15:44:24', '2019-01-14 15:44:24'),
(451, 'jfloreza@comfamiliar.com', '$2y$10$HC0nD3r3TRSck05Q7NKmjO6KBGYwUALzHvVxxYUmD0xuS7vzn.DOu', '1112784415', 'Jaiver Alexis Florez Aldana', NULL, 0, '2018-09-25 12:08:51', '2018-09-25 12:08:51'),
(452, 'jfranco@comfamiliar.com', '$2y$10$CQp2QDQ.HRg.fjbecUJHsOjJvmEJSgacr9oqbTRuWJharty3t9cWu', '18606709', 'Jose Gregorio Franco Hernandez', 5140, 0, '2018-02-10 12:06:34', '2018-04-17 19:11:22'),
(453, 'jgallo@comfamiliar.com', '$2y$10$zcZ11jPBXFPug25iOKx2Ce0GIdrPuPajBwfpr0M9Hgnw08CoLNUBu', '4453833', 'Jose Francisco Gallo Henao', NULL, 0, '2018-05-30 19:38:24', '2018-05-30 19:38:24'),
(454, 'jgarciag@comfamiliar.com', '$2y$10$hIMUOKA.eNkpI486GkiFV.Plm9Vdiaz1P.PD4frhcxbiT7MAoqkLW', '10098777', 'Julio Cesar Garcia Garcia', 3400, 0, '2018-02-09 22:04:10', '2018-04-17 19:11:22'),
(455, 'jgarciame@comfamiliar.com', '$2y$10$wEXLwk5Xx/hFdg11GhKaM.LiWlcsydQivHoVkp7l94OP3Gu7Xm/d6', '1088332690', 'Jhoan Mauricio Garcia Mejia', NULL, 0, '2018-07-16 20:40:03', '2018-07-16 20:40:03'),
(456, 'jgarciar@comfamiliar.com', '$2y$10$3uMz3FoOsTDRXbtO/dfyguyO3L/.vsnFnQUyCFa8yebTgNckzAZBS', '10008370', 'Jhon Helbert Garcia Rojas', 5171, 0, '2018-02-15 03:10:10', '2018-04-17 19:11:22'),
(457, 'jgarciat@comfamiliar.com', '$2y$10$hJORMAXTYgIFcgaTqyyW0eD0Ef18qqdOF8nCAGfzlCSuGge2sc5iG', '7561625', 'Jhon Jarbis Garcia', NULL, 0, '2018-11-28 17:45:11', '2018-11-28 17:45:11'),
(458, 'jgarciaz@comfamiliar.com', '$2y$10$u/mkE6scubisgTs.l/ZhsuGoMzge8Imd.JpN4q9hoIAdr6uVuoU6C', '18523458', 'John Edison Garcia Zuluaga', NULL, 0, '2018-07-08 12:43:56', '2018-07-08 12:43:56'),
(459, 'jgaviria@comfamiliar.com', '$2y$10$mUdeKy8NrEuve79B8eUE7Oh6.br0Eu2NhvDV7yK5PwHSlP4KJClUG', '10116884', 'Jose Alvaro Gaviria Osorio', NULL, 0, '2019-05-02 23:31:20', '2019-05-02 23:31:20'),
(460, 'jgaviriaa@comfamiliar.com', '$2y$10$9Sh0e59GCHINBaUA3cas2ufY9w0IMHYOYkiONj8NVYcSGuaSaTgEq', '1088287553 ', 'Juan Felipe Gaviria Arce', NULL, 0, '2019-05-25 19:07:19', '2019-05-25 19:07:19'),
(461, 'jgiraldos@comfamiliar.com', '$2y$10$dWgedJZ4Q5coCJOEpFZxTOnyh98zBzA2rlIbqjEd8ZaIZjlASlPgu', '10014033', 'John Fredy Giraldo Soto', 2330, 0, '2018-01-29 12:57:34', '2018-04-17 19:11:22'),
(462, 'jgomezl@comfamiliar.com', '$2y$10$R2AoPFoJwl.MbnJDSOCN3evNEOStK8UIn3QnoYmxq6WaIafaVtBRS', '1088304428', 'Juan Sebastian Gomez Largo', 5346, 0, '2018-03-20 15:58:04', '2018-04-17 19:11:22'),
(463, 'jgomezo@comfamiliar.com', '$2y$10$uyVG0r00s7p8FITfi.maPeoK58pZ/mSVrhJa5SgDiUYWROWpByg2i', '18512085', 'Jorge Eliecer Gomez Osorio', 100, 0, '2018-01-10 21:28:54', '2018-04-17 19:11:22'),
(464, 'jguevara@comfamiliar.com', '$2y$10$bAc31FcH7ffKjAuRa2ch7ebhAxNdsYmz4MjHDKMvuUupVt1xD8zzG', '10013446', 'Julio Cesar Guevara Campos', NULL, 0, '2018-11-13 15:49:42', '2018-11-13 15:49:42'),
(465, 'jgutierreza@comfamiliar.com', '$2y$10$X5c0NTiHx6r9rqn9E.pu4O5jl6EEa.FJTg9Inebp8/Bwc8HEow0KC', '9861510', 'Juan Guillermo Gutierrez Aragon', NULL, 0, '2018-10-02 13:07:29', '2018-10-02 13:07:29'),
(466, 'jherrera@comfamiliar.com', '$2y$10$/pnNeNld3Y6IbF4bAiyMJO9xHqmCBNmqRize.cOAzWrWUTteSw2Ge', '10016306', 'Juan Pablo Herrera', NULL, 0, '2018-12-14 14:23:59', '2018-12-14 14:23:59'),
(467, 'jherreral@comfamiliar.com', '$2y$10$gBLq32KXWKxoyl8AFekM8uH3rgr7pEsR9/XmUybh6i8cHRDS4hTAu', '1088315727', 'Jenifer Yeraldin Herrera Loaiza', NULL, 0, '2018-07-19 18:19:33', '2018-07-19 18:19:33'),
(468, 'jholguin@comfamiliar.com', '$2y$10$VUAvqM47luWST2nXI9ibUOJmfqEgALwgKCU9kJ8U6lNJpcp6dbZsW', '71668773', 'Jose Wilmar Holguin Correa', NULL, 0, '2018-06-05 11:21:40', '2018-06-05 11:21:40'),
(469, 'jholguinp@comfamiliar.com', '$2y$10$4b5sOxUi/6Dsb2jJoOls.OUgPhvKUglMFkNvEJIiwcVk.2LvxaBrm', '1088320241', 'Juan Manuel Holguin Piedrahita', NULL, 0, '2018-05-31 17:56:16', '2018-05-31 17:56:16'),
(470, 'jholguinr@comfamiliar.com', '$2y$10$cNbP68Hpb/s/u.tmGLFNyu7MwYmoQ0HG5Xf.K1vv2VzOwrNkpsn4q', '1116246751', 'James Holguin Ramirez', NULL, 0, '2018-05-12 18:34:00', '2018-05-12 18:34:00'),
(471, 'jhurtado@comfamiliar.com', '$2y$10$4Yj9cPW.lecJ8sQYegmj6.4LlOF0ARJ3Q7mG4OgjxMBz1jdfI6Cpy', '1088261991', 'Jhon Mario Hurtado Tabares', 5170, 0, '2018-01-31 22:32:54', '2018-04-17 19:11:22'),
(472, 'jilondono@comfamiliar.com', '$2y$10$HtDz19uKoSaWxdhpuL5PoOTKV9YF162F7Ob43xkIsUuVDg3G3Z/X2', '1088283638', 'Jimy Alexander Londoño Rodriguez', NULL, 0, '2018-05-08 19:03:16', '2018-05-08 19:03:16'),
(473, 'jjaramillo@comfamiliar.com', '$2y$10$1DQOoESYWjvYMqLVIouzcuhjp3TCh8zhQnxGN07bao0uNjOIR6Ug.', '1088244598', 'Jhon Edison Jaramillo Gomez', NULL, 0, '2018-08-22 14:47:23', '2018-08-22 14:47:23'),
(474, 'jjaramilloc@comfamiliar.com', '$2y$10$Sdic3cvB.NPGeO/S.Kg7SOfDjgYKYHu7KN6zF5WvMviPEtzhfhtE2', '9872322', 'Juan Guillermo Jaramillo Correa', 2325, 0, '2018-03-21 13:05:11', '2018-04-17 19:11:22'),
(475, 'jjarbelaez@comfamiliar.com', '$2y$10$2eEM8lqU.6s9Tx5cf6IK8eAwgKvTiFVrD5xafJUK0VvQxDOEhVyjK', '70059792', 'Julian Jose Arbelaez Gallo', NULL, 0, '2018-09-04 15:48:50', '2018-09-04 15:48:50'),
(476, 'jjimenezo@comfamiliar.com', '$2y$10$khhBWLCjY/E8LWtCXLKCeu7VdEFjRxCIghgd2ghHw/Q1RGu.y/1vW', '1088339542 ', 'Jefferson Jimenez Ortiz', NULL, 0, '2018-12-17 14:13:18', '2018-12-17 14:13:18'),
(477, 'jlassor@comfamiliar.com', '$2y$10$HPyE.PiQEx.6dRgSF3OVh.ApZPbdgqxVNgHfnQdZwB1nyIrBbdQ2W', '24397984', 'Jennyfer Carolina Lasso Rodrigues', NULL, 0, '2018-05-31 05:21:49', '2018-05-31 05:21:49'),
(478, 'jlenis@comfamiliar.com', '$2y$10$d/HeN25jtgV9feK971q0Qe53.I44VYGMNfOP2gLfSjogRJWEb87n2', '1088290575', 'Juan David Lenis Restrepo', NULL, 0, '2018-08-31 15:23:03', '2018-08-31 15:23:03'),
(479, 'jloaizaqu@comfamiliar.com', '$2y$10$.kD7f4yGroUZAAy9pEASSeezHK8rSDxWy66DHENJrjnKMFVtj/cxG', '1093217446', 'Jonathan Stevens Loaiza Quintero', 3310, 0, '2018-02-12 19:50:33', '2018-04-17 19:11:25'),
(480, 'jloaizar@comfamiliar.com', '$2y$10$StZOgMQJwdzYZwEIW7934.cccvynIgnvDi/qM3r5rIIsI/s0qGLuK', '1088020389 ', 'Jessica Yuliana Loaiza Rios', NULL, 0, '2018-07-28 17:52:43', '2018-07-28 17:52:43'),
(481, 'jlondono@comfamiliar.com', '$2y$10$th8KR6Qx35/jjq.wScDK7Ootd852W0Wt2FBzC744ZHyNr.94cMw32', '10014491', 'Juan Carlos Londoño Grajales', 3201, 0, '2018-02-27 17:43:39', '2018-04-17 19:11:25'),
(482, 'jlopezbed@comfamiliar.com', '$2y$10$19kXp6O7XR5/5u6AU3qIXethmhaoQJHBn31xopGp9mS0.8OTMX7EG', '1088287750', 'Jorge Andres Lopez Bedoya', NULL, 0, '2019-05-07 18:59:33', '2019-05-07 18:59:33'),
(483, 'jlopezs@comfamiliar.com', '$2y$10$fSfePxi5y.xykPL5IuY0De26YHLfHl6ddDTyY9.YBVDIsPLrKZNsK', '1088301463', 'Jessica Lopez Sepulveda', 3131, 0, '2018-01-11 14:41:39', '2018-07-31 22:06:11'),
(484, 'jlopezsan@comfamiliar.com', '$2y$10$/151wqiYULvVKzsetBGyVO8KpBSA6ZsaQXiiw8oMSt5WryKYTGBs2', '10025805', 'Jhon Mario Lopez Santos', 3710, 0, '2018-03-12 16:46:31', '2018-04-17 19:11:25'),
(485, 'jmacias@comfamiliar.com', '$2y$10$AX4GTbUNFmkVPt5zA5jVvuWmIjEY1We/ET39Odna.QnAc8Mgvwnk.', '1113592285', 'Julian Norbey Macias Becerra', 5170, 0, '2018-01-10 18:34:59', '2018-04-17 19:11:25'),
(486, 'jmarinm@comfamiliar.com', '$2y$10$QXgAzMbJCuPkYZ9mpuNFW.B3A/.cMRi2F06up0Rg7E7iT8P9N5M.m', '18599331', 'Jhon Jaime Marin Mosquera', NULL, 0, '2018-04-18 18:08:00', '2018-04-18 18:08:00'),
(487, 'jmarinr@comfamiliar.com', '$2y$10$DTBiZ3EkNVehFXl1qN.Z2OxD7ih.XsLz9bj8RlkdfQW689OZSMRii', '10007681', 'Jhon Freddy Marin Restrepo', 2310, 0, '2018-02-01 13:47:30', '2018-04-17 19:11:25'),
(488, 'jmartinezr@comfamiliar.com', '$2y$10$p1MOvaYazB8X6z6OyMMjouI8oWjdeWG/G.goEFfXsYto3QROoY.Ce', '1088308980', 'Julieth Martinez Ramirez', NULL, 0, '2019-03-29 19:44:54', '2019-03-29 19:44:54'),
(489, 'jmartinezs@comfamiliar.com', '$2y$10$6NmY7YimkbxB.LRamImxgesQUvchVOUlSotsLRcIOzQW2fpV9SYSK', '79851667', 'JAIME ANDRES MARTINEZ SANCHEZ', 3729, 0, '2018-03-05 17:57:46', '2018-04-17 19:11:25'),
(490, 'jmartinezv@comfamiliar.com', '$2y$10$b5HGEcPb9SQvfFRE0heHouRxNUwudiSe3PL3kND.fBL/Tn8TkMiH.', '94464005', 'Jovanny Martinez Velsco', NULL, 0, '2018-10-26 21:07:43', '2018-10-26 21:07:43'),
(491, 'jmedinag@comfamiliar.com', '$2y$10$7E.PXXGhn9M1W/DvKafQ9.sMgDbOYkHmxEtsohXi9CLjY6K718Z7i', '94062979', 'Javier Medina Gomez', NULL, 0, '2018-11-23 12:11:04', '2018-11-23 12:11:04'),
(492, 'jmejiam@comfamiliar.com', '$2y$10$CklETNu5BF5w4SDfrT3VEe4/fJL/rWTFRg0nLbjtsWNtfa5HCtKry', '10122050', 'Jaime Mejia Molina', 3300, 0, '2018-02-08 14:30:19', '2018-04-17 19:11:25'),
(493, 'jmejiama@comfamiliar.com', '$2y$10$zr9ty7e/8Of7LiTpmpT2MujeL9tktGqh7OJyvdEZcVvaYGqabq/xC', '1093214529', 'Jorge Alberto Mejia Marin', 5347, 0, '2018-02-16 15:42:18', '2018-04-17 19:11:25'),
(494, 'jmejiamu@comfamiliar.com', '$2y$10$Ey4umwcdWtrDKd7V6/qd2uoyitH7D1ZnRMf8Fe5MgZNe/2WVIZOay', '1112783644', 'Juliet Mejia Muñoz', NULL, 0, '2018-10-05 14:02:19', '2018-10-05 14:02:19'),
(495, 'jmolano@comfamiliar.com', '$2y$10$XTn9ajTz.3ymW.jYQ5OkHe6aBdwuYVG2rNmfws7SBgVhqc4gl.4fC', '10110016', 'Jaime de Jesus Molano Hurtado', NULL, 0, '2019-04-11 03:13:31', '2019-04-11 03:13:31'),
(496, 'jmontenegro@comfamiliar.com', '$2y$10$q3zaqin.QCrC.IgNn48XiefGueGOBCPNmbRmcc8omKzsxsaJdO/le', '42141919', 'Johana Montenegro Amezquita', 2313, 0, '2018-01-29 15:26:34', '2018-04-17 19:11:25'),
(497, 'jmontesl@comfamiliar.com', '$2y$10$86pnIu4Ww/Oz7jNRhAK99u3plyyvcFAhJvdvyxD5n9YYADCd1Zhzy', '1088005034', 'Jessica Viviana Montes Loaiza', 5333, 0, '2018-02-09 14:41:44', '2018-04-17 19:11:26'),
(498, 'jmora@comfamiliar.com', '$2y$10$i1l7hbukrlNQvdiaZ7I0DeJreSTfBO8j3E9/LE6NdbQTCE3I0sWdK', '1094933491', 'Jeniffer Johanna Mora Velasquez', 2130, 0, '2018-02-20 22:31:42', '2018-04-17 19:11:29'),
(499, 'jmoralesgu@comfamiliar.com', '$2y$10$8Pk40UuHlo64UbOqaI1Iqeysf9ZpZ3J95opvOd7vQYoweQ0/ShZwq', '10112549', 'Jose Noraldo Morales Guapacha', NULL, 0, '2018-07-17 20:31:51', '2018-07-17 20:31:51'),
(500, 'jmorap@comfamiliar.com', '$2y$10$oIZDYi2faypCGoi2ngMTHOwO1XettEn9elJ6bLaiDnUbNg9npeJaS', '1088293515', 'Jorge Leonardo Mora Palacio', 2310, 0, '2018-01-09 15:20:26', '2018-04-17 19:11:29'),
(501, 'jmozo@comfamiliar.com', '$2y$10$1Z.z8JmsjYBpozoYbTYe/eHnAlENidigXg0y/MiFeylsc7bjdcDOG', '10012912', 'Juan de la cruz Mozo Betancurt', 3410, 0, '2018-02-09 12:46:52', '2018-04-17 19:11:29'),
(502, 'jmpulgarin@comfamiliar.com', '$2y$10$xb.rt04zcwPZGSbyBRmJ0e8kSHQ3FtVHLmcbPBCG9CWOz0zouhjNC', '9862592', 'Jorge Mario Pulgarin', NULL, 0, '2019-05-27 22:45:12', '2019-05-27 22:45:12'),
(503, 'jmvillada@comfamiliar.com', '$2y$10$9nAgP2ZZFC4zfU1/W3Ic3OWk9xXQVMAwWRVZ9NvV7XxCJHJCjXnTi', '9924173', 'Jorge Mario Villada', 2330, 0, '2018-03-05 11:58:25', '2018-04-17 19:11:29'),
(504, 'jnietoh@comfamiliar.com', '$2y$10$bOz4KmMOKFWNKMoyYYPvC.hfJ8FHxgxUME9rkYUWozoe..Uqr541q', '1088336165 ', 'Juan David Nieto Hernandez', NULL, 0, '2018-04-30 21:33:49', '2018-04-30 21:33:49'),
(505, 'jnoriega@comfamiliar.com', '$2y$10$qLGYWJ86JrII8hj7sgDLH.CLNnl3xclptoetg9lBHiJGkcN69MeOy', '1083892555', 'Juan David Noriega Cifuentes', NULL, 0, '2019-02-21 21:57:46', '2019-02-21 21:57:46'),
(506, 'jocampo@comfamiliar.com', '$2y$10$XVZwILKqqnSOWmdGez07cO1s0QSfDI//Usw3arakNX7JWxS11w.9i', '1087999625 ', 'Juliana Ocampo Bermudez ', 5140, 0, '2018-03-23 21:24:21', '2018-04-17 19:11:29'),
(507, 'jocampol@comfamiliar.com', '$2y$10$DISvqgtmrowNEj5CKi0Sd.QHaiGCm0kvVcQgt0aXo8gRvWAeEaJFe', '1087999153', 'Juan David Ocampo Lopez', NULL, 0, '2018-05-02 12:47:07', '2018-05-02 12:47:07'),
(508, 'jodmarin@comfamiliar.com', '$2y$10$H95Nnw11j39k6tmNJBFJO.Jtuni1qYfGgB2yaJe22B6fzxjiBqHQW', '1004626511', 'Jose Didier Marin cardona', NULL, 0, '2018-07-09 19:06:36', '2018-07-09 19:06:36'),
(509, 'jogil@comfamiliar.com', '$2y$10$Ti6.Aw0D2//OfP2C8bez/.RZP0uv/N0crh3EFWN1YYXedMLGlXPLO', '1087995210', 'John Alexander Gil Mazuera', 2011, 0, '2018-01-15 14:42:16', '2018-08-01 12:12:00'),
(510, 'johospina@comfamiliar.com', '$2y$10$PNKh1ttRYs9SJ/19iKj1i.tftZs67J..SoH5nEeHOK0/tY1GDse0C', '1088237241', 'Johanna Lopez Ospina ', NULL, 0, '2018-11-03 20:32:58', '2018-11-03 20:32:58'),
(511, 'joosorio@comfamiliar.com', '$2y$10$I/4jlGYzj8IIe3hIZ93Mj.2lsHFnmFtpCIrjrWRn2Z32/eekFgUX6', '18522258', 'Jose David Osorio Guzman', 5367, 0, '2018-01-30 00:59:48', '2018-04-17 19:11:29'),
(512, 'jortega@comfamiliar.com', '$2y$10$8DyGZSRHEEJMEqKTk4CIsOAMpSAIO2sZQtLOuxUHTM3U0HnsT.jaC', '42158368', 'Jenny Silvana Ortega Cano', 700, 0, '2018-01-10 16:14:52', '2018-08-01 16:40:33');
INSERT INTO `sara_usuarios` (`id`, `Email`, `Password`, `Cedula`, `Nombres`, `CDC_id`, `isGod`, `created_at`, `updated_at`) VALUES
(513, 'josoriob@comfamiliar.com', '$2y$10$GGTivL.hFs1A.Iox/8MRnOYEvbvaCpUCADTS4MZDk30HBlpgXqKEO', '1126592462 ', 'John Edward Osorio Bastidas', NULL, 0, '2018-07-04 12:54:15', '2018-07-04 12:54:15'),
(514, 'josoriog@comfamiliar.com', '$2y$10$oxmckBrV1ApsHBT0c6eNse9C7hjLxiWmxVkR3FKnivtLXvFuVBDyq', '1088322177', 'Juan David Osorio Guevara', 5315, 0, '2018-02-13 22:47:04', '2018-04-17 19:11:29'),
(515, 'josoriogu@comfamiliar.com', '$2y$10$gJ4Aem/Zgy0/Hojuyant1eb8SUae54Xc.39UQDvNn0.MIUUzHNX6.', '1088328511', 'Juliana Osorio Guapacha', 2110, 0, '2018-02-01 16:43:05', '2018-04-17 19:11:30'),
(516, 'jospina@comfamiliar.com', '$2y$10$91eYwShgbo4gYxytraFpSeoYDm0HBJ8jG0EACmdfFieoGjq7M27WK', '16464138', 'Juan Jose Ospina', NULL, 0, '2019-04-01 21:49:02', '2019-04-01 21:49:02'),
(517, 'jossa@comfamiliar.com', '$2y$10$knBpgytkGS3WghTnff7lq.CbpF0gH2dff5HzAuXriwiulpfKrSita', '4519912', 'Jorge Mario Ossa Grajales', 2330, 0, '2018-03-05 15:28:42', '2018-04-17 19:11:30'),
(518, 'jossac@comfamiliar.com', '$2y$10$Vceyp6Thvy7zSe5F5YVnIeW8bgX5hEfY1yjfK7So3haoNAZZjMb1e', '14569782', 'Jeison Ossa Cardona', 3743, 0, '2018-04-02 19:23:04', '2018-04-17 19:11:32'),
(519, 'joviedo@comfamiliar.com', '$2y$10$QgtRr8CgsjL15RE4bvDkWOotoJy0x.WFg0j2Mj3tP9y48H47/G9/K', '1098308407', 'Jhon Player Oviedo Montes', 3410, 0, '2018-03-23 22:38:13', '2018-07-31 14:52:49'),
(520, 'jperez@comfamiliar.com', '$2y$10$wB71oBKwBd8aTQ5yFVW2F.CyYWDPZS0fwU/XJ9bqHa4Uumjq9kWK6', '22897552', 'Janeth G Perez Paternina', 5342, 0, '2018-03-12 03:03:39', '2018-04-17 19:11:32'),
(521, 'jperezgo@comfamiliar.com', '$2y$10$ogtmKxlXiCyHkZJLvHRREOPPVpi4/G7kmLo2H75dWxfjmwnxAIo0W', '42157775', 'Juri Janeth Perez Gonzalez', NULL, 0, '2018-08-10 20:08:50', '2018-08-10 20:08:50'),
(522, 'jperezr@comfamiliar.com', '$2y$10$.8sDHXBg3sOl82.wvIwhseEfhwtySuhgK/L3HabgmAEgnbajMzMhq', '1088001414', 'Juliana Andrea Perez Rodas', NULL, 0, '2018-06-16 22:42:27', '2018-06-16 22:42:27'),
(523, 'jposada@comfamiliar.com', '$2y$10$2EO6zkwr6sXlQ1X0fR6/EOrcbkM.SQw0Uqh15IFRGIQGFZvGTAwvq', '1088246881', 'juan david posada', 2310, 0, '2018-01-29 12:05:39', '2018-04-17 19:11:33'),
(524, 'jposadac@comfamiliar.com', '$2y$10$NgjGFiCbCtJzKo9eBNNOu.1gZ3wNvUKXpFCE9Fy9p0ozQz6YOqrl.', '10050437', 'John Anderson Posada Castano', 3743, 0, '2018-02-19 20:11:02', '2018-04-17 19:11:33'),
(525, 'jpulgarin@comfamiliar.com', '$2y$10$zSSYzySG0yN0TmKwZDYViOjW5qYhjqhPeuCjyXsLOz/No2vrtsbb.', '18506957', 'Jeilder Manuel Pulgarin Cardona', 3702, 0, '2018-03-06 12:43:20', '2018-04-17 19:11:33'),
(526, 'jpvaron@comfamiliar.com', '$2y$10$i8WgbygiCmOjmwhkLGIGsOz8vOqHEPBTS2CjsE6lWP9AyFULPe5lG', '10034129', 'Juan Pablo Varon', NULL, 0, '2018-04-25 15:47:17', '2018-04-25 15:47:17'),
(527, 'jquiceno@comfamiliar.com', '$2y$10$mO1vZHiCrpN3TwJvii4KfudIowBWOPepXql8tpKx2PRhEVkeDR/Lq', '10132501', 'Juan Carlos Quiceno Bueno', 900, 0, '2018-01-15 16:54:30', '2018-08-03 15:51:29'),
(528, 'jramirez@comfamiliar.com', '$2y$10$hxx9Wq/g2ToExLiS5tPgQebgh02M7hFDkR7mluyZU5AsPPM9wzflq', '15920627', 'Jafet de Jesus Ramirez', 5333, 0, '2018-02-05 16:40:06', '2018-04-17 19:11:33'),
(529, 'jramirezlo@comfamiliar.com', '$2y$10$b8jIoqufSoVGg4fCiOoj7eQk6oJLniyHNyQyuXD8i6GFtLtVRzq7C', '1144140597', 'Julian Ramirez Lopez', NULL, 0, '2019-03-05 21:38:42', '2019-03-05 21:38:42'),
(530, 'jramirezram@comfamiliar.com', '$2y$10$FjmVQaOJYb4e4WOvOjnVsekayqMCjHa1A9aecpqXvIVNYf0eXCxf.', '10023426', 'Juan Carlos Ramirez Ramirez', NULL, 0, '2018-07-12 05:29:10', '2018-07-12 05:29:10'),
(531, 'jramirezt@comfamiliar.com', '$2y$10$5m29FjsWrpZToIyAjMmkjecJHmFtPofPKwoMZYLeOdUJC73Uzdv3O', '1112790956', 'Joulinne Andrea Ramirez Taborda', NULL, 0, '2018-06-20 14:36:46', '2018-06-20 14:36:46'),
(532, 'jrendonm@comfamiliar.com', '$2y$10$ENr6KoPfIEmeG0MRRNpv0e9SumckKaPvN7inLEOyn1lzdsadekwH6', '43181385', 'Janeth Sulima Rendon Marin', NULL, 0, '2019-04-26 21:29:47', '2019-04-26 21:29:47'),
(533, 'jrengifo@comfamiliar.com', '$2y$10$zsU4osjdFirR6PZP3zpGkuP0fm48bQKKRndf9g/JDqB0zzcrWXCJO', '18506325', 'Jose Fabian Rengifo', NULL, 0, '2018-07-27 12:15:49', '2018-07-27 12:15:49'),
(534, 'jrestrepo@comfamiliar.com', '$2y$10$VBBpV1huHhgIwfzTRFcnlOuHHBOW0bFSCTwzKPW1UkYj/tV8B7U.G', '93286892', 'Jose Nondier Restrepo Alvarez', NULL, 0, '2019-05-28 15:07:59', '2019-05-28 15:07:59'),
(535, 'jriverao@comfamiliar.com', '$2y$10$jI0wfScQUK0lFBQMbR3XhuIW5advcd7u..8snL5fMZGc9rSRhCNSy', '1026262057', 'Juan Pablo Rivera Ortiz', 3730, 0, '2018-03-24 14:46:41', '2018-04-17 19:11:33'),
(536, 'jrodriguezl@comfamiliar.com', '$2y$10$3ORSl2WyrxY.eVeb5AgRnuj.x0X5FVSeMI2dJgBea272ZHOS4VZDy', '1088012111', 'John Fredy Rodriguez Lopez', 5370, 0, '2018-03-28 03:30:56', '2018-04-17 19:11:33'),
(537, 'jromero@comfamiliar.com', '$2y$10$.Cf8O9rw5H01YMw7y7PpbOsOUmxmB9w8m8saJhJImB5Eg180QMvlO', '10125090', 'Juan Carlos Romero Cardona', NULL, 0, '2019-05-08 18:24:55', '2019-05-08 18:24:55'),
(538, 'jrosero@comfamiliar.com', '$2y$10$IIX0GTr5FekJulbRU0rOEO8MsmHerMrY4WUhJlFxqQcVoHjRbCw0q', '10115564', 'Jairo Rafael Rosero Cifuentes', NULL, 0, '2018-04-27 19:11:40', '2019-03-21 13:50:02'),
(539, 'jrosillo@comfamiliar.com', '$2y$10$h6d/o240IzGgSwPKpBDe0exYOcdRnrHcybwJ4KW5elXzkY.egyvt6', '14443860', 'Jorge Alberto Rosillo Pena', NULL, 0, '2018-08-03 16:41:41', '2018-08-03 16:41:41'),
(540, 'jruizca@comfamiliar.com', '$2y$10$6myhac6./xye3SUceznIZ.b5fS64ZTDZPHE.1WZYILFTgrQAAnBcu', '18522818', 'Jose Arturo Ruiz Campeon', NULL, 0, '2019-03-14 04:38:12', '2019-03-14 04:38:12'),
(541, 'jruizs@comfamiliar.com', '$2y$10$Abe6esa7gyjIC0sK3GlPjefO5oT20IK9zTvyOoWeToTeNj2HRbMXq', '1093222428', 'Jhon Jairo Ruiz Sanchez', 5316, 0, '2018-04-15 03:46:18', '2018-04-17 19:11:33'),
(542, 'jsalazar@comfamiliar.com', '$2y$10$Pu1pyWfFgS8AwFKjknABdO1.mSmljn7WMYtL/NtkpDiliwn9xpkMi', '8038504', 'Jhon Jairo Salazar Henao', NULL, 0, '2018-04-24 16:23:22', '2018-04-24 16:23:22'),
(543, 'jsanchez@comfamiliar.com', '$2y$10$huzgRmlW2MMShAqzm1auOOU6iJ3WGS1E9vG/Pi2tNf4PlY.nki74a', '1088264184', 'Juan Carlos Sanchez Upegui', 3300, 0, '2018-03-29 19:32:04', '2018-04-17 19:11:36'),
(544, 'jsanchezl@comfamiliar.com', '$2y$10$yO6rNF5RpKrf/g2gvM5uAuVAG.uBsRQcimWcd7iusBakZC3YTVSaS', '1088261341', 'Jhon Alexander Sanchez Ladino', 3410, 0, '2018-03-07 13:55:59', '2018-04-17 19:11:36'),
(545, 'jsanchezy@comfamiliar.com', '$2y$10$qku3dbcMdajx0yoIyXYYl.QR21j7YbZzBvFT7cpvE6Hwrqm.P0Vkq', '1126589974', 'Johana Sanchez Yate', NULL, 0, '2018-04-21 13:53:42', '2018-04-21 13:53:42'),
(546, 'jserna@comfamiliar.com', '$2y$10$fKpgADqv0g/lNig2.d2Zv.rEuDr5R/jG094BqpzcWx17sBMH8afl6', '1088257488', 'Juan David Serna M', 3300, 0, '2018-02-20 15:36:30', '2018-04-17 19:11:36'),
(547, 'jsierra@comfamiliar.com', '$2y$10$//EN0VDEBv4uBRS6kXCOUu605qAH0t91WfYG8ghvMD135pxOGTAO.', '75039753', 'jhon Freddy Sierra Arcila', 2310, 0, '2018-02-07 15:19:27', '2018-04-17 19:11:36'),
(548, 'jsmarin@comfamiliar.com', '$2y$10$G.QBz31OZXjhnjS6T7TdkuIIg71ee.7vVaDXbEZmuo0585hhi4Avq', '79954929', 'Juan Sebastian Marin Martinez', NULL, 0, '2018-10-29 15:35:52', '2018-10-29 15:35:52'),
(549, 'jsotoc@comfamiliar.com', '$2y$10$MFyAiFpMgzj2NRhK4jqEFOfe8TpjLg6MbKBZNu00/goVgWnYddE8u', '1088294532', 'Jessica Alejandra Soto Caballero', NULL, 0, '2018-05-28 19:44:48', '2018-05-28 19:44:48'),
(550, 'jtabordaj@comfamiliar.com', '$2y$10$WHoTdaXNOV4MFk5G9nb4zOf8bYLuypBKBmEFVom62gljdq7KGzbri', '1088001252 ', 'Jose Fernando Taborda Jimenez', NULL, 0, '2018-06-05 20:17:03', '2018-06-05 20:17:03'),
(551, 'jtoroc@comfamiliar.com', '$2y$10$xaEtnJQHLBksLaWjxcPqE.LoCDBkusNIo0AV/V.S5daSVZ5n8mT5S', '42160965', 'Juliana Toro Cardona', NULL, 0, '2018-05-03 06:10:30', '2018-05-03 06:10:30'),
(552, 'jtovaro@comfamiliar.com', '$2y$10$jpLMjRXYF/D1idTVaIUD1uU6y7oKrDc65Wp.33nYhJxnU/Dlsd3Km', '10138380', 'JAIRSON TOVAR OSPINA', 5372, 0, '2018-02-08 03:07:06', '2018-04-17 19:11:36'),
(553, 'juatoro@comfamiliar.com', '$2y$10$bZxC0OWxVF6ZQWeBmd8wJOSaVAj5BCJxNxTGkoKDEzMWvFibj5whS', '1093228036', 'Juan Pablo Toro Hurtado', 800, 0, '2018-02-07 21:25:22', '2018-04-17 19:11:36'),
(554, 'jucorrales@comfamiliar.com', '$2y$10$g96eh.CfnYLMpIF2FzE1teRVwYx9MzjovR2MQ8bJ86UCgo4DMr6pW', '1093217336', 'Julli Alexandra Corrales Cardona', NULL, 0, '2018-04-24 12:33:09', '2018-04-24 12:33:09'),
(555, 'juguetucue@comfamiliar.com', '$2y$10$iGATCJ5iTr4yXPZoI7pkgO20dWBXHOtyXR81TcXY17c0FtXX7RFdm', '1088333309', 'Juan Carlos Guetucue ', 700, 0, '2018-03-21 15:38:00', '2018-04-17 19:11:36'),
(556, 'jurestrepo@comfamiliar.com', '$2y$10$NSl3wDuNOQ0QFVEYXXE1E.A6kbGyDcF.tzQ8R6.wHY3vzokWWIME.', '1088021513', 'Juan David Restrepo Gomez', 2310, 0, '2018-01-09 20:37:58', '2018-04-17 19:11:36'),
(557, 'jusma@comfamiliar.com', '$2y$10$7TOTmyxzlDsleop5QeGW8OJVn.hJjMkJVqmaPuBy4Ds6nH4gTsFzi', '18596974', 'jose Manuel Usma Garcia', NULL, 0, '2018-04-20 13:34:55', '2018-04-20 13:34:55'),
(558, 'jvalencia@comfamiliar.com', '$2y$10$Kgtr8XsKneekKKQ0TadA..Ve0a9JwjcEZJXqIdJm/.gVf0e7MSwZ2', '9817144', 'Jhon Alexander Valencia Giraldo', 3738, 0, '2018-02-26 18:06:25', '2018-04-17 19:11:36'),
(559, 'jvalenciaa@comfamiliar.com', '$2y$10$Jb3xAbPVHBgz.1gv5WF.ceUHrs0NaRGV2QVbkHvwDF0cEZxAaFst.', '18504057', 'JOSE DUVIER VALENCIA ALVAREZ ', NULL, 0, '2018-05-11 22:31:00', '2018-05-11 22:31:00'),
(560, 'jvalenciaf@comfamiliar.com', '$2y$10$p1sOPh38UVq40/X4MzWlRueBXVP8tCdKe5.BfWdIzA2.hVbn9KSTS', '10111792', 'Juan Carlos Valencia Franco', NULL, 0, '2018-06-08 20:45:30', '2018-06-08 20:45:30'),
(561, 'jvalenciahe@comfamiliar.com', '$2y$10$eGo4qv4hl9ZCCZpDwiffqOR/PzIuTmHQx1AlqoAO74MJqz3.RqmR2', '1088249875', 'Johanna Andrea Valencia Henao', 3737, 0, '2018-02-09 15:50:03', '2018-07-17 13:42:11'),
(562, 'jvalenciam@comfamiliar.com', '$2y$10$CJmBfFJuJW7CRUfmPNIC9.tGulhsNx42DiI0.x1./zinGpxYPt1su', '1088340082 ', 'Jose Julian Valencia Moreno', NULL, 0, '2019-02-18 15:24:52', '2019-02-18 15:24:52'),
(563, 'jvalenciato@comfamiliar.com', '$2y$10$4.ARXzkqWjh4rgV8e2omzu1C1GuzlFc8laRH85vyJwUQbvVigpb16', '10014837', 'Jesus Alberto Valencia Toro', 700, 0, '2018-02-08 18:23:45', '2018-04-17 19:11:39'),
(564, 'jvalero@comfamiliar.com', '$2y$10$U5tXIap44BXZ49C1A9.KuOfILRh3.HwkO5P79zoEo2fxxOtN2xafK', '16232481', 'Jose Alexander Valero Hernandez', 2313, 0, '2018-02-12 23:54:15', '2018-04-17 19:11:39'),
(565, 'jvallejom@comfamiliar.com', '$2y$10$kS6Eab2v0gDXDAP2iu7Y5enaf0Hv8YBp/LdFaVMCH5ZXp5CD3En3e', '1088285248', 'Juliana Vallejo Mejia', NULL, 0, '2018-11-10 12:26:28', '2018-11-10 12:26:28'),
(566, 'jvanegasv@comfamiliar.com', '$2y$10$xGa9DSXFL5WWL508eWHEfueH9PUVbT6zCsqcnRb6OllyucR2X9/Za', '1088031251', 'Juliana Vanegas Villa', NULL, 0, '2019-05-22 21:29:04', '2019-05-22 21:29:04'),
(567, 'jvelandia@comfamiliar.com', '$2y$10$7zEieVs5Mld6nJBDLa6wFeg2O5xMdFD5UyGXeU/rcjqXvz7QGAQjy', '1088268870', 'Juan Sebastian Velandia Maya', NULL, 0, '2018-09-27 21:41:34', '2018-09-27 21:41:34'),
(568, 'jvelasquez@comfamiliar.com', '$2y$10$KhwG/iw/dR.rGWgik0ekTuRYL60Es7zKyKu4BjNMdDaTaCpVzPJVi', '18508623', 'Jorge Luis Velasquez', NULL, 0, '2019-02-13 18:39:16', '2019-02-13 18:39:16'),
(569, 'jvillamayor@comfamiliar.com', '$2y$10$B2jNndcZCILfk2TEsfG1u.gdX2aAfqx4SW8sHYKrVeheBHm5T1iUW', '7545441', 'Jhon Antonio Villamayor Otalvaro', 3206, 0, '2018-03-02 22:04:49', '2018-04-17 19:11:39'),
(570, 'jvillegasn@comfamiliar.com', '$2y$10$mM6vlyaLQCWX5ujSpO8AMuZ8wJdfye9z28tqA8X7zVOeV63cyA.AG', '1088339201', 'Juan Pablo Villegas Niño', NULL, 0, '2019-01-08 12:17:44', '2019-01-08 12:17:44'),
(571, 'kaduque@comfamiliar.com', '$2y$10$hFKMXcp/OC5/utyv/7J4COmhkvGWdJOr9Mt4GAoXPUKjxZ.Vi6syW', '1112778518', 'Karina Alejandra Duque Marin', NULL, 0, '2018-09-13 07:41:35', '2018-09-13 07:41:35'),
(572, 'kagrisales@comfamiliar.com', '$2y$10$hsV/HEjhJg3zJw6rOUTU3O04AEzKHenkbNJ70aYnbv7iOxbYoN2zW', '1093228774', 'Karen Samantha Grisales Lopez', NULL, 0, '2019-04-06 21:09:08', '2019-04-06 21:09:08'),
(573, 'kalvarez@comfamiliar.com', '$2y$10$FkkjgKeeQaTcj8lObwqCBehi0XG47tg6Qs5Qc9CUOGuqbxluuWSYm', '1061371970', 'Katherine Alvarez Ochoa', NULL, 0, '2019-02-21 12:43:13', '2019-02-21 12:43:13'),
(574, 'karbelaez@comfamiliar.com', '$2y$10$zvNv7hdUoNx7jFVmD3i5MOHnFPAH8ogOlAeZuH84TBfny2HltFe0i', '66845735', 'Kelly Arbelaez Londoño', 2130, 0, '2018-02-08 18:51:56', '2018-04-17 19:11:39'),
(575, 'karias@comfamiliar.com', '$2y$10$Jx6p9eU3uIqI7W1Sods6lewdoghQYvnx.BdidTqmLTP7Cd4HdBQUK', '75068794', 'Kevin Arias Acevedo', 5315, 0, '2018-03-11 02:59:49', '2018-04-17 19:11:39'),
(576, 'karlopez@comfamiliar.com', '$2y$10$d7BCMzAJDgxdsNMDZVwY3OlRM3Z1Ji4sFpP66sdJiHvZfN/IUJ2LC', '43986946', 'Karina Yerladiz Lopez Villa', NULL, 0, '2018-09-03 13:11:05', '2018-09-03 13:11:05'),
(577, 'kcano@comfamiliar.com', '$2y$10$DuP3VXSZVVM19l3/szGdPO2SXpQbzQ/8hCHmNVx/vsIa65vPCdDhS', '1088006420', 'Kely Jhohana Cano Herrera', NULL, 0, '2018-05-18 13:05:43', '2018-05-18 13:05:43'),
(578, 'kcorrea@comfamiliar.com', '$2y$10$kyFzxyTLgHO2J7NwG.5YsuZ3RDKTdPuE0ELiYjGULGnx5p9J.Fj4S', '1112784553', 'Kevin Andres Correa Alvarez', NULL, 0, '2018-05-23 21:06:02', '2018-05-23 21:06:02'),
(579, 'kecheverrid@comfamiliar.com', '$2y$10$I1YEQGJx0Yb5Y4KjB38hKunKxxmQguId8Mg3NHuHVuoksdU2suNRO', '1088266741', 'Katerine Echeverri Diaz', 3737, 0, '2018-02-09 13:17:24', '2018-04-17 19:11:39'),
(580, 'kgiraldo@comfamiliar.com', '$2y$10$xxAmBzcSiGdyA8TUDXnpw./AoVf4UZZC65Lmh5BX9wFrq7zVLqVES', '33967461', 'Kelly Johana Giraldo Florez', NULL, 0, '2018-09-11 13:42:22', '2018-09-11 13:42:22'),
(581, 'klopezc@comfamiliar.com', '$2y$10$PaZdmlfxAtz7Knnbnl.P/u/nh3jGTdBjKFDVUd/Ly0UieywAger7m', '1088310348', 'Karen Lopez Cardenas', NULL, 0, '2018-08-30 18:58:02', '2018-08-30 18:58:02'),
(582, 'klopezr@comfamiliar.com', '$2y$10$q2DvVPWrfSVPOj5A03mq5O39KIflOrOV36.Ilu21wURC1jYh3EfJO', '1088014986', 'Katherine Andrea Lopez Rendon', NULL, 0, '2019-05-06 15:28:03', '2019-05-06 15:28:03'),
(583, 'kmorenoc@comfamiliar.com', '$2y$10$k5e22TII88maZtcItnL4z.bmfVbFP0vfyoYdY1Ln4g1c.bkCwJNFS', '1088294044', 'Karen Andrea Moreno Chaves', NULL, 0, '2018-05-09 06:24:04', '2018-05-09 06:24:04'),
(584, 'kocampoc@comfamiliar.com', '$2y$10$l/EnxIi8fiqVrhAJzn38L.EBL6DsKqDA8xyqlUtMDjJwl0i86eKeK', '1087993657', 'Katherine Ocampo Castano', 5315, 0, '2018-02-05 20:46:30', '2018-04-17 19:11:39'),
(585, 'koconto@comfamiliar.com', '$2y$10$HxfPr9OttBqh7xGXXcGHTelaH4Mat..OY5CvtWRVizULBjB0NJsNC', '1143978965', 'Karen Oconto Marulanda', NULL, 0, '2018-10-12 23:40:03', '2018-10-12 23:40:03'),
(586, 'kpulgarin@comfamiliar.com', '$2y$10$nfxAZFpsebonFQsTXf7QFufLqp9hUCijUvWFWH9Aiv2HpKDoxauOS', '1088348740', 'Kevin Laureano Pulgarin Granada', NULL, 0, '2019-01-03 14:12:25', '2019-01-03 14:12:25'),
(587, 'kramirez@comfamiliar.com', '$2y$10$18T4RomDcoXp6J78RchqX.AvNvdEXxoUQUF3i1EqtS9cYSH9vgFaO', '1088317972', 'Kelly Johanna Ramirez Marin', NULL, 0, '2018-11-17 12:41:11', '2018-11-17 12:41:11'),
(588, 'ksalas@comfamiliar.com', '$2y$10$XZ8c0Azco65uZXnNOh8CPuLUU7a57iXEhXvaS0Al8pEAi6AMdR34y', '22589219', 'Katia Lorena Salas Narváez', NULL, 0, '2018-09-18 12:33:35', '2018-09-18 12:33:35'),
(589, 'ktsierra@comfamiliar.com', '$2y$10$9rQIZnsBx3D9BDF8Ad2d8.aXjfTWe/EdWCr1nqmfMdLK0eXF7jMh2', '1088300146', 'Karol Tatiana Sierra Rincon', 3737, 0, '2018-02-28 19:56:30', '2018-04-17 19:11:39'),
(590, 'lacosta@comfamiliar.com', '$2y$10$QKkOVjthnO20E8W2F3D9C.TS5PHc1YZXmVhk4QLSm6m9ykcYx2eV6', '42155604', 'Luisa Fernanda Acosta Delgado', NULL, 0, '2019-01-14 18:26:27', '2019-01-14 18:26:27'),
(591, 'lagudelo@comfamiliar.com', '$2y$10$5FY41Floiq1bljvqu7aeHuYnhMwlotF80GItMDExp4S9S5jmk/QzS', '1088276693', 'Luisa Marina Agudelo Osorno', NULL, 0, '2019-05-13 16:05:33', '2019-05-13 16:05:33'),
(592, 'lagudeloa@comfamiliar.com', '$2y$10$/zP75qU6jd4tqcGi2lCjkefY8bBmKYw3MRPbkex.awpElA91EOGUG', '30282474', 'Luz Estella Agudelo Araque', NULL, 0, '2018-05-03 07:42:41', '2018-05-03 07:42:41'),
(593, 'lagudelor@comfamiliar.com', '$2y$10$8kqvF5TVsbrs408suGR8ROniDwargYGWV/RfQ2s2rfQkz7fCBxnuW', '1088262014', 'Laura Del mar Agudelo Rendon', 3729, 0, '2018-02-23 19:20:56', '2018-04-17 19:11:40'),
(594, 'laloaiza@comfamiliar.com', '$2y$10$U..tSAfRuHtZhvbF7tYyL.Z10VAgTvrEPCQ0Lyjcp.R86konuE/By', '24348686', 'Luz Adriana Loaiza', 5347, 0, '2018-02-20 22:08:25', '2018-04-17 19:11:40'),
(595, 'lalvarezc@comfamiliar.com', '$2y$10$ayDVOPepooZCjWi8XOKj1.EKngaFBbbeGj3R5jxhr3rUY9kBoKcEu', '10126743', 'Luis Fernando Alvarez Correa', NULL, 0, '2019-01-18 21:47:20', '2019-01-18 21:47:20'),
(596, 'lalvarezr@comfamiliar.com', '$2y$10$uCDHRQfk4w4ubiBz4lJ12emKNL6pNQMlcltanadjEh/VzKua5LalG', '41953982', 'Leidy Alexandra Alvarez Rodas', NULL, 0, '2019-03-13 19:45:39', '2019-03-13 19:45:39'),
(597, 'lalzate@comfamiliar.com', '$2y$10$guVK7uB6NTV3WTSh4EotuO05kXTxKMGpjXSQDzn35ZldIgKTENU2S', '24341121', 'Lina Andrea Alzate Benjumea', 5372, 0, '2018-03-10 18:04:48', '2018-11-29 14:36:42'),
(598, 'larboleda@comfamiliar.com', '$2y$10$jkKHb.2sse/vkgbjQt8pGOKLPDItSHPFNAzj3z/a6zshDHCqc4d8i', '66700478', 'Luz Marina Arboleda Alvarez', 3300, 0, '2018-01-30 20:57:13', '2018-10-13 18:44:24'),
(599, 'larenas@comfamiliar.com', '$2y$10$l23v1v/lqVYbUt/aB83fUOR8XzP3y1e7A.CAvjHC4zimFzlu641qu', '33965673', 'Lida Yuleima Arenas Molina', 3321, 0, '2018-02-13 18:06:52', '2018-10-13 21:37:14'),
(600, 'lariasa@comfamiliar.com', '$2y$10$PAV5Ck1VklaEbdBHqzSNkei7TowK2wG80BadbbtyhON52ojZLbw0u', '42158063', 'Luz Mabel Arias Alzate', 5332, 0, '2018-04-13 04:53:42', '2018-04-17 19:11:45'),
(601, 'laurismendy@comfamiliar.com', '$2y$10$Oe47VSqCIZJss9gS0GeEl.BTva55HKptnB3CkOhlnuQ7vsPBwpTlC', '1088026340', 'Laura Carolina Arismendy Gomez', NULL, 0, '2019-05-03 12:34:54', '2019-05-03 12:34:54'),
(602, 'lbarco@comfamiliar.com', '$2y$10$804OoRjDvIVc9iikcW8ue.uNaczitTZZh6EdzvcJ7R8kQvQhDRzke', '1088318659', 'Leidy Daniela Barco Vargas', NULL, 0, '2018-04-19 18:03:18', '2018-04-19 18:03:18'),
(603, 'lbarriosm@comfamiliar.com', '$2y$10$8LY85BK9MCaVB6WeFS/HDO3Y9CHiDtOlHweXXTXiw5U9mn8jc5cTK', '10027212', 'Luis Hermides Barrios Moreno', NULL, 0, '2018-06-17 23:04:30', '2018-06-17 23:04:30'),
(604, 'lbedoya@comfamiliar.com', '$2y$10$1p6rOrCN0BfCHHzlFVnDl.f2/1vVTR3QFNu.fHnDrtP1G2z6kSHxC', '1087552874', 'Luis Mauricio Bedoya Benjumea', NULL, 0, '2018-04-24 20:51:05', '2019-05-24 20:24:06'),
(605, 'lbedoyac@comfamiliar.com', '$2y$10$U7VKmdo9xRzBd.dKwE..He7j0ZwlojXwHPBa6OiNInzsQMgNWtrB2', '1094952989', 'Liceth Johanna Bedoya Cubides', 3743, 0, '2018-03-02 21:46:40', '2018-04-17 19:11:45'),
(606, 'lbetancourta@comfamiliar.com', '$2y$10$Lvd4L3iR1L7PWgYEKB1x9OeyB5BOAq7Dzo9Cg59u6CDqX28fmmkp2', '1192741447', 'Luisa Maria Betancourt Acevedo', NULL, 0, '2018-06-27 15:11:32', '2018-06-27 15:11:32'),
(607, 'lbuenoa@comfamiliar.com', '$2y$10$CNxYe58oR/YoBElkmFaeGuCKbnDFLs0H4VyeyJerqS9az6BfPk2Bq', '34066178', 'Luz Viviana Bueno Araque', 5346, 0, '2018-02-18 07:10:50', '2018-04-17 19:11:45'),
(608, 'lcaicedo@comfamiliar.com', '$2y$10$bkyYsu2qOFZJZpkJv/CQ/OM2xFOuBs2oudY1Jwd8uoGYj6MT9y4TO', '1019015879', 'Lorena Astrid Caicedo Amaya', NULL, 0, '2018-07-04 15:02:23', '2018-07-04 15:02:23'),
(609, 'lcalle@comfamiliar.com', '$2y$10$Z1AAsajOiHuUDLV4BGkBfe3rmsB/HDH9jPqSTPl9Daj3rgAHteq4y', '42156407', 'Luz Sterly Calle Marulanda', NULL, 0, '2018-05-04 05:56:43', '2018-05-04 05:56:43'),
(610, 'lcalvov@comfamiliar.com', '$2y$10$Dz6WSJNw.VAGp0LfpCJivOTckQ0gsJPX5.vR6ezmphbUGWIOuKyQ.', '1088270970', 'Leonardo Calvo Vallejo', 3410, 0, '2018-01-31 19:32:14', '2018-12-17 16:33:03'),
(611, 'lcanizales@comfamiliar.com', '$2y$10$H54oP7cIg9igxtth4lr06./VNHmtBlIyZr6QeA9a.HnESOy0HJtym', '1087997327', 'Luisa Fernanda Canizales Grajales', NULL, 0, '2018-04-26 19:45:18', '2018-04-26 19:45:18'),
(612, 'lcardonaa@comfamiliar.com', '$2y$10$wnvBnJmJcWstR3PuE1CafeutbZDBv0Q0remXlESrwi/haQ8FKvBcu', '1088316601 ', 'Lady Paola Cardona Ardila', 3112, 0, '2018-02-09 22:03:16', '2018-04-17 19:11:45'),
(613, 'lcardonaos@comfamiliar.com', '$2y$10$QfJPrGkZlcdo.SjhWby5Turm1val.6Du21qM3XERV/NZkAycD8vK2', '30298636', 'Leonor Cardona Ospina', 5180, 0, '2018-02-13 12:22:21', '2018-04-17 19:11:46'),
(614, 'lcardonapo@comfamiliar.com', '$2y$10$RU6.oyIQGaAVvZP1B4lrI.AMvCGcSy3nc2mcQP7V7NZaYwKvqpgVG', '41947776', 'Luzdey Cardona Poveda', NULL, 0, '2018-12-07 23:45:44', '2018-12-07 23:45:44'),
(615, 'lcarvajall@comfamiliar.com', '$2y$10$kdTmYZRd4EdBc.lChzyltOegu9rUm4BAzEr5bnpMc4q41mVgghaJ.', '1088245845', 'Luz Nereida Carvajal Leon', NULL, 0, '2018-09-12 14:54:56', '2018-09-12 14:54:56'),
(616, 'lcastano@comfamiliar.com', '$2y$10$IcxrpCOVRCcnVTknEUE7Ne.uh.bxcv0NYr4AmK66HVtDFEDxLoLve', '42081693', 'Lucy Helena Castaño Rodriguez', 3710, 0, '2018-02-08 19:47:14', '2018-04-17 19:11:46'),
(617, 'lcastanoh@comfamiliar.com', '$2y$10$iwk1GbvlKjWtbLDgb5oLLejoKM4IvvQd520uHtZhOMbJRYvzpO1r.', '42108998', 'Luz Stella Castaño Herrera', 2310, 0, '2018-01-15 14:09:31', '2018-06-07 15:23:26'),
(618, 'lcastanop@comfamiliar.com', '$2y$10$jdNulvbdaxlU9ibqI4qgZ.kQidMsrj.kI8ApV9AlhoOswee0Gax2y', '1061371321', 'Luisa Fernanda Castaño Perez', 5240, 0, '2018-02-08 21:28:34', '2018-04-17 19:11:49'),
(619, 'lcastanos@comfamiliar.com', '$2y$10$olU/59D8LpLGz2VFRI5oP.OtufYvl2Z3pY7V4rH8aijSjKKiQcrca', '42062777', 'Luz Dary Castaño Sanchez', 5240, 0, '2018-03-07 12:05:54', '2019-02-27 20:32:55'),
(620, 'lcastellanos@comfamiliar.com', '$2y$10$FIcG9AihxRR8AGzF9SWufeXVcTQFPOq6ppvFdqpQmncGVN0z04Uwq', '1088250452', 'Luisa Fernanda Castellanos Cuartas', NULL, 0, '2018-05-08 18:32:30', '2018-05-08 18:32:30'),
(621, 'lcastrillon@comfamiliar.com', '$2y$10$nXvmkHMfZWAYss80ok.PfOvJvhShZS62m8wpYDVLNQ1mnfo0mGDAm', '24512414', 'Lina Daisury Castrillon Obando', NULL, 0, '2019-03-08 15:46:54', '2019-03-08 15:46:54'),
(622, 'lcatano@comfamiliar.com', '$2y$10$dazrExHUatBAZRO6ZRngr.fQSnSy0liVpeCV4vocDGePvuZdVNHuG', '1087996512', 'Lina Marcela Cataño Cuervo', 3408, 0, '2018-01-29 21:18:55', '2018-04-17 19:11:49'),
(623, 'lcifuentes@comfamiliar.com', '$2y$10$0V.bp.i01xKIdfXStoySWe4aX7uvv9PRByuC.AXO3jZu4kOr.mh6u', '1088270011', 'Laura Cifuentes Moncada', NULL, 0, '2019-02-16 15:43:36', '2019-02-16 15:43:36'),
(624, 'lcorrea@comfamiliar.com', '$2y$10$5KNLWvCe5c7y2XdUFjiGIOE3T1Lnjf7FnwiAqgXs7.Ih1I/n9edOa', '1094899429', 'Luisa Fernanda Correa', 400, 0, '2018-02-02 16:25:29', '2018-04-17 19:11:49'),
(625, 'lcortes@comfamiliar.com', '$2y$10$c.vtV5CwXYool/VqJeqvE.Apw6JztoWBg1NgZrEsaPGnS1F/D2Ts2', '1088316256', 'Luisa Lorena Cortes Cortes', 3206, 0, '2018-01-29 21:05:28', '2019-02-04 12:22:22'),
(626, 'lcortesh@comfamiliar.com', '$2y$10$Bt6fL7UOBFo2joAxcYKXIeMSWLOYnDfh/SqiOdIOWqPKJ5t3aqF6S', '25180495', 'Leydi Johana Cortes Herrera', NULL, 0, '2018-05-03 13:33:42', '2018-05-03 13:33:42'),
(627, 'ldiaz@comfamiliar.com', '$2y$10$QQB1/u8UgpUqX6jHxyhXS.RSyBKM3HmJvxsPOsfT9lBs3nLBJBeHu', '1088255249', 'Luz Adriana Diaz Ocampo', NULL, 0, '2018-08-09 04:16:30', '2018-08-09 04:16:30'),
(628, 'lduqueg@comfamiliar.com', '$2y$10$FY6bsd8jrItm2rQmzQ8avO./ykeuuY0/z7KSJYVHmaI4SbqDaC3xu', '42089496', 'Liliana Patricia Duque Gomez', 3300, 0, '2018-01-26 13:35:23', '2019-04-11 21:41:47'),
(629, 'lduran@comfamiliar.com', '$2y$10$3qYairgzf//UV273X4rJsOJLbY9jm7EkK.EDLf5dOn6xbN9kpIwpe', '42160163', 'Leidy Johana Duran Gomez', NULL, 0, '2018-10-18 17:56:07', '2018-10-18 17:56:07'),
(630, 'lebuitrago@comfamiliar.com', '$2y$10$4osu4DssgTWPCDXaKnz1IuVFeBL8pH7rM54q2d8Fh1HoZrpHqmHya', '1088023646', 'Laura Isabel Echeverry Buitrago', 2130, 0, '2018-02-26 13:42:29', '2019-02-21 19:01:22'),
(631, 'leloaiza@comfamiliar.com', '$2y$10$CflPoJ8WAlp4DdQLFLHi6.WPTl4reLCWkQAk5OtU8Pr10wzBk3dwu', '1087999756', 'Leidy Johanna Loaiza Agudelo', 5366, 0, '2018-03-25 03:57:42', '2018-04-17 19:11:49'),
(632, 'lescobar@comfamiliar.com', '$2y$10$k2RFp2lD6EXAOEGJp2qzk.87Lx9MIvlCUP/towhcDjS97mKdsQcd6', '1112618082', 'Liliana Maria Escobar Pineda', 5361, 0, '2018-01-29 12:17:25', '2018-04-17 19:11:50'),
(633, 'lespinosa@comfamiliar.com', '$2y$10$7iZozJcKf3qZqsULcgXnZebndKKhGUikvTD0C8oEvon7v4KDYtaS2', '42152939', 'Leidy Johanna Espinosa Restrepo', NULL, 0, '2019-03-30 11:19:22', '2019-03-30 11:19:22'),
(634, 'levargas@comfamiliar.com', '$2y$10$pFQtPlimiAmSFjS/tIJqS.EvbztQqSt7rOkZvMjzIpsMFvFhj6We.', '10001387', 'Luis Emilio Vargas', NULL, 0, '2019-05-27 18:29:57', '2019-05-27 18:29:57'),
(635, 'lfcorrea@comfamiliar.com', '$2y$10$fk5pn.sNMjIhGThkhgK7But.Rzl8TuaUXMmP.ycHrdjyL9Dej5WOm', '1088012394', 'Luisa Fernanda Correa Grisales', 2313, 0, '2018-02-09 14:54:08', '2018-06-01 22:57:06'),
(636, 'lfrancog@comfamiliar.com', '$2y$10$FWh3BXOcCO.13ACgIMr8wOIH3tmkESBvz2uZeLLc.wA0LUNE24922', '1036607877', 'Liliana Franco Garcia', 5367, 0, '2018-02-17 12:14:17', '2018-04-17 19:11:52'),
(637, 'lfvargas@comfamiliar.com', '$2y$10$BeJYB/d3QBYq3it.cXFSdeJwX3AL7cEyggI82SU/HDDbJ7fsE6fWK', '1088011282', 'Luisa Fernanda Vargas Salazar', NULL, 0, '2018-05-04 14:54:11', '2018-05-04 14:54:11'),
(638, 'lganan@comfamiliar.com', '$2y$10$Y7MKM3ujcxnYkRety/cR0umyZneeA1YpZvVVwtVG6pKGs27OtQnNW', '42084046', 'Luz Adiela Gañan Davila', NULL, 0, '2019-04-09 23:54:40', '2019-04-09 23:54:40'),
(639, 'lgarces@comfamiliar.com', '$2y$10$Fe93fuJ0Z4Pjh.mIStjioeQq49ArWXsR3r.Hf9GadDobvVIifg8qG', '1088352059', 'Luis Eduardo Garces Agudelo', 900, 0, '2018-01-29 21:55:24', '2018-04-17 19:11:52'),
(640, 'lgarcia@comfamiliar.com', '$2y$10$tnQLCdTbG5.HTcn7GRJROOHuEmj8cRzducLopbZEs6YUota8d.lKm', '18503165', 'Libaniel Garcia Colorado', NULL, 0, '2018-06-22 19:54:43', '2018-06-22 19:54:43'),
(641, 'lgarciam@comfamiliar.com', '$2y$10$UpWX83cQN7ihVToFJjQSCuPga8DiKYBjRituZGrZ6CUxbkpkSuWbO', '1088016548 ', 'Leidy Jhoana Garcia Mendoza', 5370, 0, '2018-02-08 20:51:51', '2018-04-17 19:11:52'),
(642, 'lgild@comfamiliar.com', '$2y$10$CpdFrCGZn/Zx6iINkFb2ZO0B51QNJHfkdMUXAm8weomW6GN7xzXoq', '42165367', 'Lina Mercedes Gil Diaz', 600, 0, '2018-02-09 16:56:30', '2019-04-12 14:58:26'),
(643, 'lgiraldom@comfamiliar.com', '$2y$10$laQQG1xuMEb40cKKeDiekOfR6XZVUQTZAiWaevSDBu4iEkU9QY0H6', '33965712', 'Luz Aleyda Giraldo Montes', NULL, 0, '2018-08-15 15:54:34', '2018-08-15 15:54:34'),
(644, 'lgiraldop@comfamiliar.com', '$2y$10$VY4NODFL/3CI6SicTnpaMOcP4bz91FTkgP5bKIQazGCXdu8Pk4SiG', '1112779967', 'Leidy Vanessa Giraldo Pulgarin', NULL, 0, '2018-12-06 00:03:47', '2018-12-06 00:03:47'),
(645, 'lgiraldor@comfamiliar.com', '$2y$10$JpKQtkdnxIHVa0s4Q./bnOIX2YTN4nTYVO0TmeSxVqn/hh8QYh4La', '42102605', 'Lina Maria Giraldo Ruiz', 5180, 0, '2018-02-09 16:17:41', '2018-04-17 19:11:53'),
(646, 'lgiraldoro@comfamiliar.com', '$2y$10$/wCczl1qhZ0jjzve3a.HJ.fWWabY46K6itJ2xpn4MfXVBtfBpvZDm', '42154240', 'Lina Marcela Giraldo Rodriguez', 5370, 0, '2018-02-16 21:56:33', '2018-04-17 19:11:53'),
(647, 'lgranadosm@comfamiliar.com', '$2y$10$HfGzIEszDOn/5hjprXEp/exP3kzZS27qugy885KGP8LdTv4wV0uC2', '1088283965', 'Leidy Jhoanna Granados Muñoz', 5351, 0, '2018-02-13 05:40:31', '2018-04-17 19:11:53'),
(648, 'lgrisalesr@comfamiliar.com', '$2y$10$uDcn0s9jGOr.c8KqukbVQu01nmGr/klQZ0wCE5eZ8eAmp/1mW316G', '1088288539 ', 'Leidy Lorena Grisales Ruiz', NULL, 0, '2018-09-10 07:42:33', '2018-09-10 07:42:33'),
(649, 'lguapacha@comfamiliar.com', '$2y$10$PSZY17KRoTGQU.NJiOtu2e.dM26mnDm5Zk6BDYh6oIPAy1Hf3vJiK', '42124625', 'Luceida Guapacha Ramirez', NULL, 0, '2019-03-14 16:24:56', '2019-03-14 16:24:56'),
(650, 'lguerrero@comfamiliar.com', '$2y$10$9N630LvNsiFR6U6k3NS8puHL7CdeoBdizXoKKXQMPa9zMef3JAz0m', '42142049', 'Luz Elena Guerrero', 5240, 0, '2018-02-15 13:58:14', '2018-04-17 19:11:53'),
(651, 'lheredia@comfamiliar.com', '$2y$10$qb7OjWHvx7tF5KXn/EJX1.wZeMohpsVbfYHHyv1ArB3JawzXF1ChK', '24625934', 'Luz Elena Heredia Rivera', NULL, 0, '2019-02-06 16:05:29', '2019-02-06 16:05:29'),
(652, 'lhernandezb@comfamiliar.com', '$2y$10$qsOxBke3habavjuTzbPtBuU3CJ5Aggu1j6EFIIIjXGgZ5.Lh2.8UW', '1112765001', 'leonardo andres hernandez becerra', NULL, 0, '2018-08-20 17:44:50', '2018-11-20 12:53:32'),
(653, 'lherrerao@comfamiliar.com', '$2y$10$QB6UmJxG4w47/lxwE1CTj.frmds8bI7I9U34EPaz26PuSgdoi2hIi', '1225088824', 'Luisa Maria Herrera Ocampo', 2140, 0, '2018-02-08 16:34:01', '2018-04-17 19:11:53'),
(654, 'lherrerav@comfamiliar.com', '$2y$10$F657b3y7HG7x8C7BERNUUetFxcFpzTx9JN98pQ6qVT1XqAg8uNGtu', '1088332433', 'Luisa Fernanda Herrera Vargas', NULL, 0, '2018-05-15 19:35:17', '2018-05-15 19:35:17'),
(655, 'lhosorio@comfamiliar.com', '$2y$10$xuQvfs5t5nBvAyHVKGIQnOj2BfUTCVzJf33mCiYyEswCAx2O9sdRu', '94528383', 'Luis Humberto Osorio Quiceno', 2132, 0, '2018-01-05 15:46:52', '2019-04-09 16:51:30'),
(656, 'lisaza@comfamiliar.com', '$2y$10$8mx2fUtd7ujeqk6qkFta4u8wicSRyKFEMzDLJuKozy1dTrq4oqvzi', '42009060', 'Luz Piedad Isaza Tabares', NULL, 0, '2018-09-14 17:07:26', '2018-09-14 17:07:26'),
(657, 'ljimenezs@comfamiliar.com', '$2y$10$8w04zZS6Q8lqjh6LiRfZt.xCTL1DCcWsg/614FNCxfjZ4WbUCYpRu', '25164421', 'Luz Mari Jimenez Salazar', NULL, 0, '2018-05-13 06:53:51', '2018-05-13 06:53:51'),
(658, 'llara@comfamiliar.com', '$2y$10$L97XMHgDLysG051ThK5ngeOs4GLnYxEljWBre2/1bZMFrrW5Z3xWu', '42148101', 'Lenis Yamileth Lara Biojo', 3743, 0, '2018-03-26 16:03:01', '2018-04-17 19:11:56'),
(659, 'lloaiza@comfamiliar.com', '$2y$10$Fu8eMMn0dvUo74QDCC4MbugBhrPAynl2PDvprs96qaeCy1QOBckb6', '24413706', 'Luz Fanny Loaiza Velez', 5344, 0, '2018-02-15 16:06:58', '2018-04-17 19:11:56'),
(660, 'llopezgo@comfamiliar.com', '$2y$10$VMPH0sZ58rb9KLEt3PaefOH6q1FH7gphCio/nuIH.Sevc.QT9LwLa', '42125825', 'Luz Estela Lopez Gomez', 200, 0, '2018-01-29 20:32:10', '2018-04-17 19:11:56'),
(661, 'lmarulandal@comfamiliar.com', '$2y$10$LQdvVxMiG2uObiTpgoesTu5eUaLVWT74NsA0AiddSvoiLhnlPT17O', '1088259907', 'Leidy Liliana Marulanda Largo', 3300, 0, '2018-04-11 15:59:30', '2018-04-17 19:11:56'),
(662, 'lmcardona@comfamiliar.com', '$2y$10$IXtrbJpDEShJC6EDzY0lo.BfB1xlJf31fOMzeyyEkvst.p.tiQt6i', '24628839', 'luz miriam cardona', NULL, 0, '2019-01-04 11:47:36', '2019-01-04 11:47:36'),
(663, 'lmejiad@comfamiliar.com', '$2y$10$BrAtTdCc.NuWY/v4HDIJvO7R6uJKk8eXEd8ra1ZSbqD4FLZnBZhKC', '1053796210', 'Lorena Fernanda Mejia Diaz', NULL, 0, '2018-12-19 14:36:20', '2018-12-19 14:36:20'),
(664, 'lmejiav@comfamiliar.com', '$2y$10$izPh0eFZp00KZspp6xYx8eiiF93NaIdpmwM58apLQjiSYHS9PeG3O', '1088306864', 'Lady Johanna Mejia Valderrama', NULL, 0, '2018-12-12 19:06:10', '2018-12-12 19:06:10'),
(665, 'lmlargo@comfamiliar.com', '$2y$10$KsYAoc5FMFCFPq2X8821qOGShxFD/Rmixozg81qZPHO5KUiwtfR8.', '1088315896', 'luz marina largo largo', 3300, 0, '2018-02-07 21:15:43', '2018-04-17 19:11:56'),
(666, 'lmolano@comfamiliar.com', '$2y$10$6snPQptqJ4wN55D6H9AdXuqW4Tdo7VRSPAs2eE7n/NGOgoiBkLf16', '1088317232', 'Lady Tatiana Molano Noreña', 3737, 0, '2018-03-14 12:48:01', '2018-04-17 19:11:56'),
(667, 'lmonsalve@comfamiliar.com', '$2y$10$eoIRx/9OFQNPDOOCpk2Xn.ROe0grXRMxL/fc9usgfqVl.vME.eVOi', '1087990111', 'Luz Adriana Monsalve Lopez', NULL, 0, '2018-05-24 14:51:00', '2018-05-24 14:51:00'),
(668, 'lmoralesh@comfamiliar.com', '$2y$10$.HOJXDFIm7fN0pXA/sL6uOZYJJSIG9InFTqKowx/j5kwbqcz2DY5e', '1088249544', 'Luisa Fernanda Morales Henao', NULL, 0, '2018-05-08 06:28:49', '2018-05-08 06:28:49'),
(669, 'lmperdomo@comfamiliar.com', '$2y$10$SLIQGtDHZ32//VsTYvhY.uEUolDan5Zp3Ud0owFQhvjG/0MebJ0Fi', '42148038', 'Leidy Milena Perdomo', NULL, 0, '2019-05-22 19:50:11', '2019-05-22 19:50:11'),
(670, 'lmrestrepo@comfamiliar.com', '$2y$10$HqhfmpkQ7sDFTe2b1KZn9.AN.RDJUkwijgMzRvzouSQk/ocZzfkYu', '42105080', 'Lina Marcela Restrepo Santa', NULL, 0, '2019-01-19 21:17:57', '2019-01-19 21:17:57'),
(671, 'lmrivera@comfamiliar.com', '$2y$10$BJoGh12zI.OntomNXYfHHe.rC/Hqnn4EgXgHFakbKC/rjHK8sNGHG', '30299741', 'Liliana Maria Rivera Garcia', NULL, 0, '2018-04-22 18:22:15', '2018-04-22 18:22:15'),
(672, 'lmunozg@comfamiliar.com', '$2y$10$acDQrOKt1ULjmwFOxx6ik.5eE2mnFNZw6/DlVuV7KcVaIvfUkYRjO', '1088278657', 'Laura Cristina Muñoz Gaitan', NULL, 0, '2019-03-11 15:38:21', '2019-03-11 15:38:21'),
(673, 'lmunozgar@comfamiliar.com', '$2y$10$TjiuxiQ9UKjLNaGJT19Ar..wua9v8vtfQJUTJ7wc7GA7RwvHPeMDK', '42162022', 'Luz Yamile Muñoz Garcia', NULL, 0, '2018-07-30 12:33:00', '2018-07-30 12:33:00'),
(674, 'lmurcia@comfamiliar.com', '$2y$10$RSnE/AuE3rewn5d1wzIwR.U5WXXWomDxEwxXDiz5uhe00JqjAVEd6', '25212853', 'Laura Eugenia Murcia Sanchez', NULL, 0, '2019-01-28 19:34:35', '2019-01-28 19:34:35'),
(675, 'lnovoa@comfamiliar.com', '$2y$10$dMIsBYelcbqs5tumapj37.wooN9GdtnunK8UWnGYVxzO5.2D1BQwm', '1088309964', 'Laura Maria Novoa Obando', 2120, 0, '2018-01-30 12:01:23', '2018-04-17 19:11:56'),
(676, 'locampog@comfamiliar.com', '$2y$10$IuaaVX7YJvbTgCZ8H.WDVenOsx.BKEF.LFrblPq161pyOSHJUc8zS', '1088314132', 'Luis Guillermo Ocampo Gomez', NULL, 0, '2018-04-18 15:55:24', '2018-09-17 20:50:07'),
(677, 'lochoa@comfamiliar.com', '$2y$10$QoLoP2BMmYkmG8kJaq6uwOsKsGB82rOJyDixdRNnJ43/Clw5s6.YG', '42162075', 'Lina Maria Ochoa', 800, 0, '2018-01-30 13:46:30', '2018-10-29 20:10:30'),
(678, 'lorozco@comfamiliar.com', '$2y$10$y30BTRT9Skc6suQKeKvamexB53wFta0dCc7JJEisJ/6TRtrWsASHC', '10143090', 'Leonardo Orozco Gaviria', NULL, 0, '2018-08-31 13:25:12', '2018-08-31 13:25:12'),
(679, 'lorrego@comfamiliar.com', '$2y$10$tZzUw2Qqeu7vd3maUytDxupD0UwfT8bE9bvZjfbN5hewQ6MF4n3F.', '1088283886', 'Leidy Johanna Orrego Gil', 3300, 0, '2018-01-11 17:54:42', '2019-04-06 20:50:53'),
(680, 'lortiz@comfamiliar.com', '$2y$10$.oQ44Xmsg7JdhkAyQPcf3.AS98fDfCsFnAxC6P200cgYR3PDcHB4C', '1089718545', 'Leidy Yurany Ortiz Serna', 5370, 0, '2018-02-12 08:50:51', '2018-04-17 19:11:56'),
(681, 'losorioc@comfamiliar.com', '$2y$10$SDS.QdRBhSRFSvskDTuYhe7GTFI0qXJBWbE5ismISGkNN7v6HMUFG', '42098382', 'Lilia Osorio Cardona', 5349, 0, '2018-02-08 19:45:43', '2018-04-17 19:12:01'),
(682, 'lpatino@comfamiliar.com', '$2y$10$LLJFvh9yoZDHDcdcPVCjJ.VryMrHqC8q6tJEyS/VjiLwEI2HeZNC2', '25180412', 'Leidy Yurani Patño Guevara', NULL, 0, '2018-06-06 17:02:09', '2018-06-06 17:02:09'),
(683, 'lpatinoh@comfamiliar.com', '$2y$10$8LYqu6FuTmsijis0NlxZ0eighBlCqn.D2PpsX2Xn0FXpN1uWhVdm.', '24693782', 'Luz Mery Patiño Hurtado', 2328, 0, '2018-01-09 15:18:48', '2018-04-17 19:12:01'),
(684, 'lpenas@comfamiliar.com', '$2y$10$qDyJmSsZ.Yh1mP2DyNmeCeezIUQNWm7pjMwIBmFUPheMDrpBgl4GG', '1088300313', 'Laura Catalina Penas Palacio', 3030, 0, '2018-01-31 19:46:38', '2018-04-17 19:12:02'),
(685, 'lperilla@comfamiliar.com', '$2y$10$d9q2StJ7WtSFBp.fjoj6wuzhr2a6SpxsUIY1di5VJzCPE3bpSw1Pu', '42127129', 'Liliana Perilla López', 2310, 0, '2018-01-09 13:33:23', '2018-05-17 13:10:47'),
(686, 'lquinterog@comfamiliar.com', '$2y$10$8HZLGqRg8Ki79xkF3NmJyu1dYYomb550HR70vykKgdUga0Q2T4DGC', '1088028561', 'Luisa Fernanda Quintero Galvis', 5346, 0, '2018-02-07 14:11:45', '2019-02-21 15:29:23'),
(687, 'lregalado@comfamiliar.com', '$2y$10$9CfMumlQeorFPw794ed.4ect90bMQVctkPNYzzkQxB9vN/BErPZ4q', '1054987434', 'Lady Susana Regalado Osorio', NULL, 0, '2018-08-13 16:21:38', '2018-08-13 16:21:38'),
(688, 'lrestrepov@comfamiliar.com', '$2y$10$98yB/II42NXU8atox0YqnuRIbk3zKkGlvyIPTXknnJzvqL3Dy7uGK', '42136940', 'Luz Amparo Restrepo Valencia', 3320, 0, '2018-04-15 16:25:05', '2018-04-17 19:12:02'),
(689, 'lrodriguez@comfamiliar.com', '$2y$10$RjwzYUgnYE5IoE.26xKUeuoidt77H5lJFtmZ8KwKJvKSBsRacEeBu', '34543111', 'Luz Marina Rodriguez Tovar', NULL, 0, '2019-03-15 22:16:03', '2019-03-15 22:16:03'),
(690, 'lrojash@comfamiliar.com', '$2y$10$jckcwAWQR210sqRi2q8ef.c7FseoJNUUKE5hHAM0rP2kWQvgZIIUS', '79053574', 'Luis Javier Rojas Hernandez', NULL, 0, '2019-03-01 23:22:48', '2019-03-01 23:22:48'),
(691, 'lruiz@comfamiliar.com', '$2y$10$7HQxhSWDeVIje5ofeOkElud7ulNSkiWvwjW.PVtXPamfXbV8y0hw6', '33965934', 'Luz Miriam Ruiz Tamayo', NULL, 0, '2018-10-31 16:21:49', '2018-10-31 16:21:49'),
(692, 'lruizr@comfamiliar.com', '$2y$10$wbtKw6adCG6o/HGznGvT4unuKS3O82NeETG8uoYmgrBADkxA4qTM.', '1088337137 ', 'Laura Marcela Ruiz Ramirez', 3112, 0, '2018-04-10 17:09:36', '2018-09-17 13:00:54'),
(693, 'lsalazar@comfamiliar.com', '$2y$10$EuNZUVfLWskDwEjp7qnQ5OYGLF.DZEiNob3qEaladOPUFoxk4HYum', '42093649', 'Liliana Salazar Bohórquez', 2132, 0, '2018-01-10 19:51:31', '2018-04-17 19:12:02'),
(694, 'lsalazarm@comfamiliar.com', '$2y$10$SccTM6K5zjmZTj3EBN7GyOdPtBW8Qc2ieVqa2kLfrs2YDz2IZLaLy', '52350710 ', 'Luz Stella Salazar Martinez', NULL, 0, '2019-01-18 22:19:33', '2019-01-18 22:19:33'),
(695, 'lsanchezh@comfamiliar.com', '$2y$10$CBSZTaqg7LXtcZ08B6.7ce6/Zo8Gsyfw46sziva91J34tAeQ53mTm', '1053787101', 'Lina Vanessa Sanchez Hernandez', NULL, 0, '2018-12-11 13:18:21', '2018-12-11 13:18:21'),
(696, 'lsaraza@comfamiliar.com', '$2y$10$YkqHbiqfrRTBp57.HiJhTOJQuI9qnhjM6rfrofsfKHMbMEi3DHsdG', '1040735677', 'Lina Marcela Saraza Castrillon', 3410, 0, '2018-01-17 18:34:28', '2018-04-17 19:12:02'),
(697, 'lsegura@comfamiliar.com', '$2y$10$qz1M8vcdXCR0mO6g4je1pO2/Pw6jpQyTWTfui3h4kNzcUaOdojYOW', '42082934', 'Luz Adriana Segura Duarte', 3737, 0, '2018-02-01 14:12:12', '2018-04-17 19:12:02'),
(698, 'lsuarezl@comfamiliar.com', '$2y$10$T7I0scsgZcrXbq5kxUU4cerPiozk.xM9A.HcxKAXx.8.Ft66jVRFG', '1125078213', 'Lesly Nezabeth Suarez Lopez', NULL, 0, '2018-06-21 06:03:23', '2018-06-21 06:03:23'),
(699, 'ltaquinas@comfamiliar.com', '$2y$10$AgVk.tbAXZ5xNkvcuU0OPOZgJmfMKa2OG/OnIWkmPzgiGt1/XGDtW', '42149681', 'Luz Adriana Taquinas Castro', 3312, 0, '2018-02-27 20:12:04', '2018-04-17 19:12:10'),
(700, 'ltrejosm@comfamiliar.com', '$2y$10$AOnIOiO/pb.iDf1592CQouPX0Cznax.w4j.f2BU3DoU5vwwCe9WdS', '1088324942', 'Laura Stefany Trejos Marin', NULL, 0, '2019-01-04 20:11:42', '2019-01-04 20:11:42'),
(701, 'ltrujillog@comfamiliar.com', '$2y$10$fHBAgmXodhKr9NPJ6TNb9OhDDs7qtckWTJtFuvKV9b5kiaZz8eTa6', '42018890', 'Luz Miriam Trujillo Garcia', 3100, 0, '2018-01-31 22:39:28', '2018-04-17 19:12:10'),
(702, 'lugalvis@comfamiliar.com', '$2y$10$al1Eox4RN6e6aIAwgo2azu/NRiDVFNc2pRcI.IUZ7w5zjz0HnF1W2', '1088307257', 'Luisa Fernanda Galvis Betancur', 3738, 0, '2018-03-27 13:12:47', '2018-04-17 19:12:10'),
(703, 'lugomez@comfamiliar.com', '$2y$10$AbuP7i.c1K2ry5WMVvZeqORi1ZmoCPOFU7bAJW5WBEIj/49.KPqNu', '1088329389', 'Luis Alfonso Gomez Reyes', 800, 0, '2018-01-30 21:47:44', '2018-04-17 19:12:10'),
(704, 'lurincon@comfamiliar.com', '$2y$10$IDYGBH2DYX7zNWGuD7CgoOxS2B4BzSlJw9/XJqEp27XeKwEXAKSRa', '38886871', 'Luz Adriana Rincon Rincon', NULL, 0, '2018-07-31 19:06:12', '2018-07-31 19:06:12'),
(705, 'lvalencia@comfamiliar.com', '$2y$10$eqXVxsK5hqQ9cHQJ5CSV.uVjr.UhEp4JKJ.KlEBlVJbFqHmJ.dfIq', '42154128', 'Luz Angela Valencia Hincapie', NULL, 0, '2019-02-01 15:48:49', '2019-02-01 15:48:49'),
(706, 'lvalenciag@comfamiliar.com', '$2y$10$Hg0cmQSjIQYT5CDGuG.7c.oALulpV4feRlPsCqIlpoVS4QsCNSL/6', '1088258832', 'Luis Fernando Valencia Garatejo', 5346, 0, '2018-02-11 04:54:23', '2018-08-15 15:53:22'),
(707, 'lvalenciao@comfamiliar.com', '$2y$10$OJL0T2WDGZ3r8K/giW86Ju1h8Zu0qLRvRnyCZKdaKAmhGjqFiav4G', ' 1112465730', 'Lady Johanna Valencia Ocampo', NULL, 0, '2019-05-09 15:43:47', '2019-05-09 15:43:47'),
(708, 'lvargas@comfamiliar.com', '$2y$10$Nv9F8UYClq.Vb4tBjFM16uUPPi6XJ7AO7nYkPefdl648Ojor64QK6', '25172798', 'Luz Edilma Vargas Aguirre', NULL, 0, '2018-08-28 15:54:53', '2018-08-28 15:54:53'),
(709, 'lvargasv@comfamiliar.com', '$2y$10$1kU8OzAJJwsotXP7PHNBc.9A7rRu/AjQGYpZsm8Ms0fDNZ9D3O99u', '25158829', 'Luz Eneida Vargas Vargas', 3721, 0, '2018-02-06 15:30:47', '2019-01-14 16:34:04'),
(710, 'lvelasquezg@comfamiliar.com', '$2y$10$mD39dV4kz/Snju9XH/IIh.r0ihkhce.QZ9suYlI5eTB7UafH91m/O', '1053775602		', 'Leila Tatiana Velasquez Garcia', NULL, 0, '2018-09-05 15:37:16', '2018-09-05 15:37:16'),
(711, 'lvelezbe@comfamiliar.com', '$2y$10$8uh0o.prXvYKQQTFwwJp2.0Ig5FJV5ghL1lw0kMecWQZHJMF/o.ZO', '1088270279', 'Lina Marcela Velez Betancuort', NULL, 0, '2018-12-07 20:30:03', '2018-12-07 20:30:03'),
(712, 'lvera@comfamiliar.com', '$2y$10$YykLlj0pj7ebt8ht.DMzW.QniwBrGctcrGr9WFjT.lb.VcBU4FaiC', '42114314', 'Liana Patricia Vera Martinez', 800, 0, '2018-02-09 15:50:36', '2019-03-21 22:00:17'),
(713, 'lvergara@comfamiliar.com', '$2y$10$bHcINw4L5YjGa8pzHQjetu.ZXeQgLtxFPzFC.V6K5S/mZo/4KfDhq', '66870433', 'Lina Alexandra Vergara Ospina', NULL, 0, '2018-10-01 02:08:12', '2018-10-01 02:08:12'),
(714, 'lvillegas@comfamiliar.com', '$2y$10$4T8xzoo3uCrtyiWzin0LTuRZf6g3gVqBOpmgZVz7JIHV.UuRjia6a', '10025577', 'Luis Fernando Villegas Echeverry', 2328, 0, '2017-12-27 21:45:28', '2018-04-17 19:12:11'),
(715, 'lwagner@comfamiliar.com', '$2y$10$qsn/YjgT/OHvqE9/i6gTcuVZ/mIWZL14WuInZ0pha6kR4L5uGeDnS', '42160517', 'Lina Constanza Wagner Rodriguez', NULL, 0, '2018-07-05 15:25:03', '2018-07-05 15:25:03'),
(716, 'lzapatac@comfamiliar.com', '$2y$10$e.LkOslxbHVxYx0njq5/semjT0cl330BNkMmiRBWl6oOFLC1SlWSG', '1088002289', 'Lina Marcela Zapata Cuartas', 3101, 0, '2018-03-14 12:59:18', '2018-04-17 19:12:11'),
(717, 'lzuleta@comfamiliar.com', '$2y$10$PNuLLE3glvMXeZauwZa98.mYm3kOM8ns.KUeYSUIoycMAp09AEx5O', '42160399', 'Leidy Johanna Zuleta Ramirez', 3737, 0, '2018-02-09 15:54:43', '2018-04-17 19:12:11'),
(718, 'maflorez@comfamiliar.com', '$2y$10$6EoVaiqQyQOc94F73mqfZeI6ai5.Ur6ZhhJPpUEsfRDkztlCdGUVO', '42135840', 'Maritza Florez Acosta', NULL, 0, '2019-01-22 12:40:44', '2019-01-22 12:40:44'),
(719, 'mahincapie@comfamiliar.com', '$2y$10$IkKDFHUhcl/N8Ksxf8AsqesgTZoAML8FxnAB27O7Dm3ow0sn8nBmq', '42077352', 'Margarita Hincapie Oyuela', 5341, 0, '2018-02-08 20:13:46', '2018-04-17 19:12:27'),
(720, 'mahurtado@comfamiliar.com', '$2y$10$63l9Wp.SoyRpRVYfhwK9.efntdLxqgp0dnQSZHBOB.kuh5ws4jgYu', '25161261', 'MARIA SOLEDAD HURTADO OSPINA', 5315, 0, '2018-02-26 09:02:25', '2018-04-17 19:12:27'),
(721, 'maisaza@comfamiliar.com', '$2y$10$8pbO/8GD2PTmJ3ItGK0fC.4lmhDt04cfK6/h12yu6if1JqkEoOa2O', '1089601900', 'Maria Carlota Isaza Agudelo', 3313, 0, '2018-02-10 16:38:31', '2018-04-17 19:12:27'),
(722, 'majaramillo@comfamiliar.com', '$2y$10$vn8Z.uTtOpEx7MEhJtFeveE5mYNxxcaXfIgRyP88gs7tWtmV8YZbW', '42119860', 'Maria Ninfa Jaramillo ', 5316, 0, '2018-02-17 03:25:16', '2018-04-25 07:46:59'),
(723, 'maloryher@comfamiliar.com', '$2y$10$H1LzJw/g4Jq7eUofNXKqO.rJoXr3FdG4YgzzMwJ6SHx1w9gvojv8K', '1088006918', 'Malory Hernandez Zapata', 3313, 0, '2018-01-26 20:17:44', '2018-05-24 21:38:25'),
(724, 'maquintero@comfamiliar.com', '$2y$10$HzG8ips4orUfF5x/O/GrPe.Tvzaly8s2Pr9dQ99AagEKDB9nUzRNO', '1088282874', 'Maribel Cristina Quintero Posada', 5351, 0, '2018-03-17 04:01:21', '2018-04-17 19:12:27'),
(725, 'marango@comfamiliar.com', '$2y$10$6ccl.2vwelNNCUhsOnFX3uWyFv3HgKZuf/Qn7UBdIqa6w74buYGCK', '42060072', 'Maria Lucy Arango Salazar', 0, 0, '2018-01-29 15:29:00', '2018-04-17 19:12:27'),
(726, 'marbusg@comfamiliar.com', '$2y$10$DgY4esnnfczVmxlmuNT7p.LckeMWMOiYJH5/1FsczfmJT3TH9bg3e', '1088325189', 'Maria Alejandra Bustos Granados', 2310, 0, '2018-01-09 20:44:07', '2018-04-17 19:12:27'),
(727, 'marenasf@comfamiliar.com', '$2y$10$xujga.UHfFIYY5ntq5p72uWeYkHM7TBm4JTe6e/Is01SSOxmPc4/6', '1225090552', 'Mariana Arenas Franco', NULL, 0, '2018-10-31 07:34:25', '2018-10-31 07:34:25'),
(728, 'marrubio@comfamiliar.com', '$2y$10$YiJHsaRxpGl4ObcWnSfafePb5S8tGnVHqyOunNEGbQFnRozbgceLC', '39811979', 'Martha Liliana Rubio ', NULL, 0, '2019-01-15 18:36:10', '2019-01-15 18:36:10'),
(729, 'matrejos@comfamiliar.com', '$2y$10$/XROLTM4ROfcglyJ4VREGett6X9iy6NdSJ22Z3jK4xoDgMkFJmFx6', '42125118', 'Maria Patricia Trejos ', NULL, 0, '2018-12-11 13:10:43', '2018-12-11 13:10:43'),
(730, 'mbecerra@comfamiliar.com', '$2y$10$fAb5cDlJjbVWyng0d/BMp.M6By8KwKgT5gSQJZnDlzhhM88qFcF7a', '25059378', 'Maria Dolly Becerra Trejos', 5370, 0, '2018-03-11 09:36:38', '2018-04-17 19:12:27'),
(731, 'mbedoyac@comfamiliar.com', '$2y$10$tGLIbwFOkHZDHCY0edODJ.2Yia9xCxTZj8GjeP9jyZgbkKlKaAcOW', '1088317692', 'Maria Alejandra Bedoya Cardona', 5347, 0, '2018-03-10 07:04:55', '2018-04-17 19:12:27'),
(732, 'mbermudez@comfamiliar.com', '$2y$10$EN.rlAHpiTUd2KfJtQJ1U.TCTyDhfbpwysYbHwGGXsfal0DpqyOZe', '25214055', 'Maria Teresa Bermudez Arana', NULL, 0, '2018-04-30 19:30:38', '2018-04-30 19:30:38'),
(733, 'mbetancurc@comfamiliar.com', '$2y$10$IOwXCcO9JF/jgrVcxz5NFOpQ5UCe04hhwgGDbvsu3z/tdnfxwkAWq', '25101681', 'Maria Claudia Betancur Cardona', 5315, 0, '2018-03-27 05:37:59', '2018-04-17 19:12:27'),
(734, 'mboteroa@comfamiliar.com', '$2y$10$uz9OYd5n3J/zGJPPVpSSGOnQhrLW6s7o.Ou8yP83UhXFdulFxw/qS', '30296742', 'Martha Alicia Botero Agudelo', NULL, 0, '2018-06-18 05:58:51', '2018-06-18 05:58:51'),
(735, 'mburiticav@comfamiliar.com', '$2y$10$r7erOaTdZTQfNR8Yr7wHcOIZkJ7RFMi.FRBJFpcdVKbDmOJF4Yiy.', '42059045', 'Maria Elena Buritica Velez', 3101, 0, '2018-02-12 19:34:07', '2018-04-17 19:12:27'),
(736, 'mcardenas@comfamiliar.com', '$2y$10$B6V3fNedf0fivBZETVZCC.xmOdK4Vqgr/8IPSghoWk29JphRXYBRW', '30358284', 'Maria Isleny Cardenas Bueno', NULL, 0, '2018-05-17 06:28:59', '2018-05-17 06:28:59'),
(737, 'mcardonac@comfamiliar.com', '$2y$10$hemrS8QAI/43jOC3tOdLROSYxWDx.e3MXJHLBlcWEnkYw0z7IgASi', '42144708', 'Martha Yulieth Cardona Cardona', NULL, 0, '2018-05-25 19:52:46', '2018-05-25 19:52:46'),
(738, 'mcardonaca@comfamiliar.com', '$2y$10$YDsNHaCYVt83FNFAZdyi7ug0d4flWdi.qa6vDrLusrLshubX2vXqy', '42146749', 'Monica Johana Cardona Carmona', 5370, 0, '2018-02-27 17:36:07', '2018-04-17 19:12:27'),
(739, 'mcardonao@comfamiliar.com', '$2y$10$WH7jz12D96H1Dj9plnrJBeHTTGquAdLixpQXw5qKzGa2RZcfJTUVi', '1088354200', 'Maria Camila Cardona Orozco', NULL, 0, '2019-03-01 00:00:18', '2019-03-01 00:00:18'),
(740, 'mcastano@comfamiliar.com', '$2y$10$eP4QLhRWFtlS76X9zLCOpekWkNYMxxQ6LzbbfBdeCEGo1AQlTWuRq', '42145170', 'maria isabel castaño pinzon', 5370, 0, '2018-03-11 05:42:42', '2018-04-17 19:12:27'),
(741, 'mcastilloa@comfamiliar.com', '$2y$10$oJO3Ajhhq2dJDEUJz1e7gOuFaQpm5oSeNPibYJdS/ESc2vIZbuOHC', '1112792581', 'Margarita Maria Castillo Adarve', 700, 0, '2018-02-01 14:46:03', '2018-04-17 19:12:27'),
(742, 'mcastrillon@comfamiliar.com', '$2y$10$6MtZQNa4.7YXqKBtnZ7kbeBc7atuxEps/3yK3Ah46z5hNie8URLjq', '1088030749', 'Maria Camila Castrillon Gomez', 5335, 0, '2018-02-20 17:53:58', '2018-08-06 13:37:19'),
(743, 'mcastror@comfamiliar.com', '$2y$10$lRVaSGq1p/1iqgibfajG0uRUiaUVyOy4aIOf/fCLJZbOjdF3toRJS', '1088294912', 'Mariana Valentina Castro Ramirez', 3408, 0, '2018-01-31 14:36:52', '2018-04-17 19:12:28'),
(744, 'mcorream@comfamiliar.com', '$2y$10$DSz1x6OYbTBcVPhZNoRkEO/cS8WOCnFOr/KU7zHbWf9DYG7aSY0YK', '1038626122', 'Maria Jose Correa Madrid', 3716, 0, '2018-02-26 13:46:51', '2018-04-17 19:12:28'),
(745, 'mctabares@comfamiliar.com', '$2y$10$.9SylVVvaH8qDpYVcz05segn4FU4qGVoS4ntMsQjyxt5COm5OGtBu', '42107544', 'Martha Cecilia Tabares', NULL, 0, '2018-04-23 17:45:00', '2018-04-23 17:45:00'),
(746, 'mcuartasc@comfamiliar.com', '$2y$10$XXTaq8o5.mam1u9.442jaOXMUj2.cI/qmzUHyQafxEsVZfGWmeHES', '1088279842', 'Maria Victoria Cuartas Castaño', 800, 0, '2018-02-22 21:21:58', '2018-04-17 19:12:28'),
(747, 'mdelgado@comfamiliar.com', '$2y$10$pWzv.jqfX9osUnBmnYjZyOPJjCCrj2XcylJiQF4dIYwCv03hAErEO', '36751412', 'Maria Jose1 Delgado Montoya', NULL, 0, '2018-10-25 16:31:31', '2018-10-25 16:31:31'),
(748, 'mdelgador@comfamiliar.com', '$2y$10$zQnpAphE68vScGshBW6dKOxnUj/A/vKR58LXNWDw1Rp4J.SyaquLy', '1087492091', 'Maria Fernanda Delgado Restrepo', 2130, 0, '2018-02-21 19:07:11', '2019-05-27 22:00:46'),
(749, 'mecheverri@comfamiliar.com', '$2y$10$uMEWd3vx43A1Leuaoxxw5.De60i.29Eh7NiNIqqYXZ8aYsiJ188/2', '32609682', 'Martha Luz Echeverri Florez', NULL, 0, '2019-01-24 14:52:31', '2019-01-24 14:52:31'),
(750, 'mfigueroa@comfamiliar.com', '$2y$10$9XFoGGqq7cGJ1vT7nDepZ.OMX7/KF6xnxRJJlsqcXIoO.LGK1KTV2', '10015949', 'Mauricio Figueroa Gutierrez', NULL, 0, '2018-07-17 13:22:10', '2018-07-17 13:22:10'),
(751, 'mflorez@comfamiliar.com', '$2y$10$lDrUGRJLy0z9f5N9cll83OjxJfS19WxNpCSqn45yMoKMSEivMItXO', '42002486', 'Martha lucia Florez Quintero', 3702, 0, '2018-01-30 20:59:55', '2018-04-17 19:12:28'),
(752, 'mfperez@comfamiliar.com', '$2y$10$b9UL50i4vSZxDgKeKHdA8OEGLosvtN15/zG3TYjkNg4kxYeQ4SuI.', '16076589', 'Manuel Fernando Perez', 3792, 0, '2018-01-30 13:28:48', '2018-04-17 19:12:28'),
(753, 'mfranco@comfamiliar.com', '$2y$10$jW5a6dJPkVHbq9qcmkPHKuuejUbYwWdLspDCsaNyN0gj3DQpMwPAy', '42124661', 'Maria Victoria Franco Chavez', NULL, 0, '2018-06-01 15:42:42', '2018-06-01 15:42:42'),
(754, 'mgaleano@comfamiliar.com', '$2y$10$JYBIlQJcqquhS2zxR4KumOzxCcGmiYVxLUrzzgPYUjrigftnmddhW', '24765847', 'Martha Liliana Galeano Galeano', 3112, 0, '2018-03-06 13:11:39', '2018-04-17 19:12:28'),
(755, 'mganan@comfamiliar.com', '$2y$10$hyPOzEOS6/d1RFByZJQFYuzbueBDvx45EF6y5BSG0uL4JwBC73dFG', '25059449', 'Margarita Maria Gañan Gallo', 5315, 0, '2018-03-18 06:19:55', '2018-04-17 19:12:28'),
(756, 'mgarcial@comfamiliar.com', '$2y$10$kMStvmeWtYvScUVbCi10FeGGexqGUlXpywZQ4KqsWrElO0N.uuXja', '25156801', 'Maria Lucila Garcia Londoño', NULL, 0, '2018-05-29 13:26:28', '2018-05-29 13:26:28'),
(757, 'mgarciaoso@comfamiliar.com', '$2y$10$SODMKVT5faGipztQwrTmNeQlR4hUZDvlM7aXuFObMA3hMIsFZQBpG', '10000579', 'Mauro Andres Garcia Osorio', NULL, 0, '2018-07-16 05:08:12', '2018-07-16 05:08:12'),
(758, 'mgiraldo@comfamiliar.com', '$2y$10$1I1ZOiS8.sBB/nPx83lpkeNzyI5dQfyL4GGi84Ovw3A1eyPZo2bdm', '42096710', 'Maria Elena Giraldo Barco', 600, 0, '2018-02-15 15:23:22', '2019-03-13 19:45:39'),
(759, 'mgiraldog@comfamiliar.com', '$2y$10$yZ6nPVOP6MqBLKe.aa0pzOOtxFkAl4okk2GmwZoo2jW8yLyPzH7OS', '42005835', 'miryam Giraldo Gallego', 5240, 0, '2018-03-15 16:17:45', '2018-04-17 19:12:28'),
(760, 'mgmunoz@comfamiliar.com', '$2y$10$iVdT9GU/8lPYkRoV.tq87.cqAgXQsEegdeGjePc4eiJLVXuIzVfzm', '42084816', 'Maria Gladis Muñoz', 2313, 0, '2018-04-03 12:34:31', '2018-04-17 19:12:28'),
(761, 'mgomez@comfamiliar.com', '$2y$10$q4ZRA1DDT.sd.NlgSIIa9u5Nf5MTKgPF/dYRRKPU57FhrAUTebE5u', '25175825', 'Maria Teresa Gomez Arango', NULL, 0, '2019-02-15 19:32:39', '2019-02-15 19:32:39'),
(762, 'mgomezc@comfamiliar.com', '$2y$10$4ZllkWD7Zd8SLHDjXXug6exYw/12J5rfd49CQDepLIlEXFxEj19Zm', '1093222204', 'Maria Alejandra Gomez Cono', 3101, 0, '2018-01-29 19:51:26', '2018-04-17 19:12:28'),
(763, 'mgrajalesr@comfamiliar.com', '$2y$10$a2FM9khNqpXte.o2Vxmyi.88oCrtKbwCF1zHUI4uYiw.6hOMjjsL6', '10032074', 'Mauricio Alexander Grajales Rincon', NULL, 0, '2019-04-04 16:30:12', '2019-04-04 16:30:12'),
(764, 'mgrajalesz@comfamiliar.com', '$2y$10$ixrbYwUBcjAN9bci/TXvu.vxuqhOP.6bY1sLW7sDCn5J0T7M/MtOe', '24686829', 'marleny grajales', NULL, 0, '2018-09-25 12:48:23', '2018-09-25 12:48:23'),
(765, 'mguzman@comfamiliar.com', '$2y$10$w82D.NgQDO6b5Y4C/0qHIewvi083cE/2Pfu8jPq8gSzeJBtopu68y', '42016198', 'Maria Elena Guzman Granados', NULL, 0, '2018-05-09 19:33:48', '2018-05-09 19:33:48'),
(766, 'mhenaod@comfamiliar.com', '$2y$10$2JIW1QEqVHj9AxD6qd9MtebZ/AwIa7glm4ctgQPtMIN0ydu2i0Wfy', '30317289', 'Maria Norma Henao Delgado', NULL, 0, '2018-10-09 19:21:57', '2018-10-09 19:21:57'),
(767, 'mhenaop@comfamiliar.com', '$2y$10$.5XG0osF9czq/XB.4P8Bq.GExfLDDX1EHt1A9JD1q00vV3anfaOuG', '34065276', 'Monica Henao Pescador', NULL, 0, '2018-05-31 00:18:08', '2018-12-06 21:25:18'),
(768, 'mhernandezz@comfamiliar.com', '$2y$10$6zGEDKusjn91suBWvo9a.eywvINNRq9vdh/nwcnwLZZEnIZWl871G', NULL, 'mhernandezz', 3410, 0, '2018-01-12 14:00:13', '2018-01-12 14:00:13');
INSERT INTO `sara_usuarios` (`id`, `Email`, `Password`, `Cedula`, `Nombres`, `CDC_id`, `isGod`, `created_at`, `updated_at`) VALUES
(769, 'mhincapie@comfamiliar.com', '$2y$10$GRhsAkux0iOw7y0hR/LBweCPIZV5..WPilLslhF2IzEkglhP48ldy', '1088278988', 'Maria Fernanda Hincapie', 2130, 0, '2018-01-30 16:14:09', '2018-04-17 19:12:28'),
(770, 'mhuertas@comfamiliar.com', '$2y$10$aRwpbFp47HUmrGGDxUA3Xu/tOUiJbpkfJj09R53wIfDs/rnbXiKhi', '51832237', 'Maria Claudia Huertas', NULL, 0, '2019-03-08 22:49:48', '2019-03-08 22:49:48'),
(771, 'mhurtado@comfamiliar.com', '$2y$10$inPklirSWnz33GWWgAzhhOmStqhZh6zC6Qk/8ecn6zIQGURBlHSP6', '42099966', 'Maria Nedi Hurtado Hernandez', NULL, 0, '2018-09-20 04:56:10', '2018-09-20 04:56:10'),
(772, 'miguapacha@comfamiliar.com', '$2y$10$7z6InufdqzSIGEyapMMUn.h3anJx4/RzV2jnms1s0cHlE7zaaNSSy', '1088237012', 'Miriam Orlancy Guapacha ', NULL, 0, '2018-09-01 17:05:42', '2018-09-01 17:05:42'),
(773, 'mjaramillo@comfamiliar.com', '$2y$10$VSkP4A/Y7grB0VnWsVaHqOXRUTLmCDyKG3SOHZXsv2XEKSafhtAKy', '42129018', 'Maria Judith Jaramillo Posada', NULL, 0, '2018-07-03 21:15:52', '2018-07-03 21:15:52'),
(774, 'mjaramillon@comfamiliar.com', '$2y$10$lHDXBbwosrYqA8evaPojQ.u.7mRrSYet13TmqGfrjjMjIcgentDGC', '80094062', 'Mauricio Jaramillo Narvaez', 700, 0, '2018-02-09 14:21:36', '2018-04-17 19:12:28'),
(775, 'mjaramillor@comfamiliar.com', '$2y$10$DA1fAFj6RJcd9BuxsufLteJTJs96JChUbQzbpALHj4sA9o3X0kGd2', '1088331065', 'Mateo Jaramillo Rincon', 3300, 0, '2018-02-16 17:58:10', '2018-04-17 19:12:29'),
(776, 'mjimenezs@comfamiliar.com', '$2y$10$P0I8EWEiwMiaWigMUQgmtOHlbmCR/CBtRQcL2fs5GMcOmDdut3kmO', '1088020444', 'Marlon Jimenez Sanchez', 3408, 0, '2018-02-09 15:18:21', '2018-04-17 19:12:29'),
(777, 'mkgiraldo@comfamiliar.com', '$2y$10$MR5Cm1Rn2BduE2PPqKgN/.KI3D6Gtxjl7Tk4aFu7XrPXJappuH5o6', '1093219739', 'Maria Katherine Giraldo Agudelo', NULL, 0, '2019-03-12 18:01:46', '2019-03-12 18:01:46'),
(778, 'mlgomez@comfamiliar.com', '$2y$10$RXA6tsmnjhuymfeTUxfY7Oz2cPgR96HSgCxq0cU/Ge.xsPuugIb3u', '30317878', 'Maria Leonora Gomez Alzate', 5341, 0, '2018-03-15 16:09:30', '2018-04-17 19:12:29'),
(779, 'mlinares@comfamiliar.com', '$2y$10$unfvhdSlesl.h3XxJcuaLO2KcA/xFrDIil/JqHPfUEtcwITEi/SbK', '1088314962', 'Monica Andrea Linares Jaramillo', 3320, 0, '2018-01-29 12:13:20', '2018-04-17 19:12:29'),
(780, 'mljimenez@comfamiliar.com', '$2y$10$dPLC5DbcMbiKOYFvl3NcS.hd6L7Wq6yOh7hPTKVvYGEURNnNnL8ea', '42135959', 'Mary Luz Jimenez Velasquez', NULL, 0, '2019-01-16 16:49:15', '2019-05-15 16:31:33'),
(781, 'mloaizag@comfamiliar.com', '$2y$10$rNsC7FnzyBhn27oKg60OZ.y2ludyBMxD6nwyy/DYzqyw1S1/K8fYe', '42121779', 'Martha Cecilia Loaiza Galeano', 5315, 0, '2018-03-08 00:55:33', '2018-04-17 19:12:29'),
(782, 'mloaizalo@comfamiliar.com', '$2y$10$kWxBDdhHY32jPxM6Bja/aeXvoU2.SnmZ56j2WuBz6wg9GbSic6gHK', '1088245837', 'Monica Alejandra Loaiza Loaiza', NULL, 0, '2018-05-11 22:49:45', '2018-05-11 22:49:45'),
(783, 'mloaizasa@comfamiliar.com', '$2y$10$2BBgFYhmWsYM70/eWGZcqufXCGUu8WxmefU5khIFlgAdjtZl.hbk2', '1087559426', 'Michael Andres Loaiza Saducea', 2329, 0, '2018-02-09 20:24:35', '2019-03-01 21:50:22'),
(784, 'mloaizav@comfamiliar.com', '$2y$10$edMDoDQzv.ARkgcrLgaT/OTfRYeI0bZpKTEFZ5cG.cZcGGYwD4EDC', '24414989', 'Marta Deisy Loaiza Velez', NULL, 0, '2018-10-23 08:40:01', '2018-10-23 08:40:01'),
(785, 'mlondonob@comfamiliar.com', '$2y$10$Jm9ldlMX11meXZdItiiEeurrZu.2yXLoGxvRPct3SWWWLWnXrepiS', '1088263617', 'Manuel Jose Londoño Builes', NULL, 0, '2018-05-11 23:03:08', '2018-05-11 23:03:08'),
(786, 'mlopera@comfamiliar.com', '$2y$10$I.XpjRvIhunm7DJncBvjzufbgzmVAIw2KStfMRG6n0C7UgrmtIxGm', '29926825', 'Marileny Lopera Quintero', NULL, 0, '2018-04-26 21:15:23', '2018-04-26 21:15:23'),
(787, 'mlopezl@comfamiliar.com', '$2y$10$ccG3wNwxitcaIyZ5zRiZsemcTLNw.6p//YSa/Iifq5JX85HscdmIK', '33967663', 'Maria López Largo', 2328, 0, '2018-01-09 20:46:41', '2018-04-17 19:12:29'),
(788, 'mlopezre@comfamiliar.com', '$2y$10$.7Wwc3spUnACnPMRpycHxuKYfK.WtIlyhvnIXnrjkAQvnyfKijCfK', '1087560485', 'Maria Paula Lopez Restrepo', NULL, 0, '2018-05-03 17:45:01', '2018-05-03 17:45:01'),
(789, 'mlopezy@comfamiliar.com', '$2y$10$uUpKs3BD2pSsNRcyastlDeVjr0vE0mp9/Zayv890QlK/uhrUqutiu', '24873256 ', 'Martha Lucia Lopez Yepes', 5349, 0, '2018-02-15 23:25:53', '2018-10-07 10:07:08'),
(790, 'mlopezz@comfamiliar.com', '$2y$10$.BEElDvWQ65U0zzVoxeBou4jv4LPrhEAXgzdNMbE9he194we.DNPu', '24763528', 'MARTHA CECILIA LOPEZ ZAPATA', 5341, 0, '2018-03-18 22:20:37', '2018-04-17 19:12:29'),
(791, 'mmafla@comfamiliar.com', '$2y$10$VgMM9KpXGlDtkhm8ogmdkOla1NPaMFwboLfeO8lkbbE2GV/LzAV0C', '1088260597', 'Martha Edith Mafla Roa', 3030, 0, '2018-04-12 16:27:03', '2018-04-17 19:12:29'),
(792, 'mmarinm@comfamiliar.com', '$2y$10$btkPh1Ne6VxaYCh.oaCKL.rQXoum1gz8SeYZZoMnairtpiv1pll7W', '42023349', 'Maria Elsy Marin Marin', NULL, 0, '2018-07-05 02:24:59', '2018-07-05 02:24:59'),
(793, 'mmarinz@comfamiliar.com', '$2y$10$02yxr7edBL04hRkRG6/Z.uFCHondWQPegWwEIbcVs9m.Dnz.K4XVi', '42027887', 'Maria Victoria Marin Zapata', 2313, 0, '2018-02-21 16:35:57', '2018-04-17 19:12:29'),
(794, 'mmarrugo@comfamiliar.com', '$2y$10$j5ym8uYICtLgA9.QRkRwNuzrLjXxfTroDrVC6pA854OwpVuNQk0fG', '7917779', 'MAIKER ELKIN MARRUGO JINETE', NULL, 0, '2019-02-04 12:20:51', '2019-02-04 12:20:51'),
(795, 'mmejia@comfamiliar.com', '$2y$10$5czWbpSYNHVSWEQx2yB31.hjEgv8JmLnSYtTOMgzfuwEG8FgYtmwK', '1088289666', 'Maira Alejandra Mejia Hernandez', 3701, 0, '2018-01-30 22:20:40', '2018-05-04 14:21:48'),
(796, 'mmendez@comfamiliar.com', '$2y$10$eelxKvjV4YTkMwLBn22nueLS2F1QnEYxUdSPzbliosp9vEz/VkdlG', '10113503', 'Mario Mendez Reyes', 2313, 0, '2018-03-12 13:55:32', '2018-10-25 00:42:59'),
(797, 'mmendieta@comfamiliar.com', '$2y$10$GRCvP4nkVFSO3MnsSaq/I.v.yg6kCoQU/5mpGmZMu7Ipn.fwX4RwS', '31307556', 'Melissa del Pilar Mendieta', NULL, 0, '2019-04-20 07:23:16', '2019-04-20 07:23:16'),
(798, 'mmhenao@comfamiliar.com', '$2y$10$75VAHB4gJO6/88HD8aksuu2LCEw/hojC38NfEG2tjKdcXKvf893F6', '42878183', 'Margarita Maria Henao', NULL, 0, '2018-08-23 15:14:34', '2018-08-23 15:14:34'),
(799, 'mmolano@comfamiliar.com', '$2y$10$nG9i89tyJ3UL5pA2aAPpMetyLqhfy5p9EG3yIcKAoIUTkNSbdWk/2', '42132770', 'Maria Zully Molano Ramirez', NULL, 0, '2018-10-18 15:45:19', '2018-10-18 15:45:19'),
(800, 'mmolinaro@comfamiliar.com', '$2y$10$rvw24q3ZTB82Zprjyn3aaeB3UPyvEo0hZN.tX67GixMBADvu3NH0O', '1093218878', 'Maria Angelica Molina Rodriguez', NULL, 0, '2019-05-06 19:13:47', '2019-05-06 19:13:47'),
(801, 'mmoncada@comfamiliar.com', '$2y$10$SDIl3iG1m.G7OJ0xGeKU4u3l16wMW5EE9.wYwKnNPLTReQoHAAbLK', '42002918', 'Maria Piedad Moncada Bedoya', NULL, 0, '2018-05-31 21:12:25', '2018-05-31 21:12:25'),
(802, 'mmontoyaq@comfamiliar.com', '$2y$10$FEp.Xykc129OzwXorRZY/.aM5PUwgXw8WnNt2Gbm38gjeO6/nEowe', '1007217514', 'Manuela Montoya Quintero', 900, 0, '2018-02-02 12:16:20', '2018-04-17 19:12:29'),
(803, 'mmontoyar@comfamiliar.com', '$2y$10$3L2fVydu6RlZH0quCRPoSenCdQ346c2Ow9jdzlIbQEjQiv3glgpbu', '42152102', 'martha elena montoya ramirez', NULL, 0, '2018-09-15 16:15:37', '2018-09-15 16:15:37'),
(804, 'mmontoyav@comfamiliar.com', '$2y$10$fgTin8wn/0Dt8lu84SdGiOd7CMvKassaJicMwhRviLKj5JJp6zPnG', '1088335258', 'Mariana Montoya Valencia', 5332, 0, '2018-02-02 19:12:51', '2019-03-11 21:04:31'),
(805, 'mmorales@comfamiliar.com', '$2y$10$utVbGY.BH0KucG4/9FB1kO8O5P0g8a.uTdDMUyI3pVYrM06aKiOC2', '31399930', 'maria nancy morales cabrera', 5372, 0, '2018-03-19 07:22:32', '2018-04-17 19:12:30'),
(806, 'mmunozt@comfamiliar.com', '$2y$10$QvKRjFBLXxIUYcH7bk9vMOeQG6mIHA/o/ybNJttNlh0udDmUxX2BC', '25247097', 'Maria Tereza Muñoz Torres', 5351, 0, '2018-03-22 02:52:10', '2018-04-17 19:12:30'),
(807, 'mnoriega@comfamiliar.com', '$2y$10$msQgWYzMHP/nZZ6ifV9IwejypKn00CcfcfKjT1YA0tb8A9aqGU32.', '1087551253', 'marcela noriega jaramillo', 700, 0, '2018-02-20 20:38:17', '2018-04-17 19:12:30'),
(808, 'mocampo@comfamiliar.com', '$2y$10$j8QgFe8BjOCMPd6V.6MGJ.YDEillv3daZU53Wjgqp4ah0bjqyHfvu', '30398051', 'Maria Lorena Ocampo Martinez', 5372, 0, '2018-02-15 21:00:46', '2018-04-17 19:12:30'),
(809, 'mocampoc@comfamiliar.com', '$2y$10$oqdHB1Ze3x.oIoYCA/HXjO086bIvHT7S9TzDjwnMBa7RdmsZipvke', '1093227340', 'Maria Jose Ocampo Cañon', NULL, 0, '2019-03-20 20:25:17', '2019-03-20 20:25:17'),
(810, 'mocampov@comfamiliar.com', '$2y$10$weNvwRodOhdmhdU9vvuSCeZIP/NQbscDAKu7VXpBDFS3QhEJAs/wS', '1088252284', 'Mauricio Ocampo Velasquez', NULL, 0, '2018-04-17 22:19:10', '2018-08-28 20:20:31'),
(811, 'mortiz@comfamiliar.com', '$2y$10$/Tx8hBVcSIKU7cr7wZSvo.q2EQ5yygz04S86W.fGJFVFzaUJwECc.', '42102794', 'Martha Lucía Ortiz García', 2325, 0, '2018-01-26 22:32:18', '2018-07-03 18:55:28'),
(812, 'mosorior@comfamiliar.com', '$2y$10$IeTXu6EDANOEZcCu8EWBJejmpV/7ZH2EfiysbR35Z6h98g9BiZbJa', '1093215669', 'Monica Osorio Ramirez', 800, 0, '2018-02-12 19:12:58', '2018-04-17 19:12:30'),
(813, 'mosoriori@comfamiliar.com', '$2y$10$HiZ2xwTY2ix6qsBo1C0eteh1MONSRYJoB/UI196K8RRhDvOa6oM/2', '1088025358', 'Maria Salome Osorio Rios', 2310, 0, '2018-01-30 19:52:54', '2018-04-17 19:12:30'),
(814, 'mospinac@comfamiliar.com', '$2y$10$6Tnmh78zrzFB7MjSqqG87OXv1Ou7tu6llVGFgQ4U.NNjdLyT2bj/G', '25174713', 'Maria Eugenia Ospina Cortes', NULL, 0, '2018-07-16 13:28:51', '2018-07-16 13:28:51'),
(815, 'mospinag@comfamiliar.com', '$2y$10$EzJ2KASCWiEK7qGe1YNV1ejzwrdZRcGU3OJmJ.lnj2Julan/t2v2K', '42164999', 'Maria Eugenia Ospina Grajales', 3101, 0, '2018-04-10 20:43:01', '2018-04-17 19:12:30'),
(816, 'moviedo@comfamiliar.com', '$2y$10$sylxzuUH9q0yfgSLJ.qtW.6hUA4F5OkWyZAeppCVgYvsusLb7nrzC', '41921431', 'Marielli Oviedo Sabogal', NULL, 0, '2018-09-18 07:54:09', '2018-09-18 07:54:09'),
(817, 'mpalaciom@comfamiliar.com', '$2y$10$nP0YTnQ0NAJSA5FumB7Rr.s0rHIg4e46uqOL6hrMPEgQrXXksVDoe', '42119795', 'Monica Palacio Molina', NULL, 0, '2018-05-21 04:26:18', '2018-05-21 04:26:18'),
(818, 'mpenag@comfamiliar.com', '$2y$10$oVWloSCXenSd3k39aPRdL.Lmkv4ZBAlP.CriIxcQtnW4ZZHN3Pr5y', '1093225945', 'Martin Peña Garzon', NULL, 0, '2019-03-14 19:48:12', '2019-03-14 19:48:12'),
(819, 'mpenav@comfamiliar.com', '$2y$10$5WPwxXdY1oqIc0/nc9JW5.G1Xt8bPwmBfW1Uhno6EbRd2XwK6OqBO', '1087557964', 'Monica Paola Peña Villaneda', 3300, 0, '2018-04-08 01:23:22', '2018-04-17 19:12:30'),
(820, 'mpereira@comfamiliar.com', '$2y$10$7VLnLkVa703VNyYod9apqeYnJyKMu25GXZtr0NzHG0Wv64Oelafbi', '14899315', 'Mauricio Pereira Millan', NULL, 0, '2018-09-21 21:08:42', '2018-09-21 21:08:42'),
(821, 'mquiceno@comfamiliar.com', '$2y$10$Y.kHQ9O2RDPO0bf4wBZIxeqmBjDH1OLXnEnARKEEMwkOx49iQts0S', '33965156', 'Maria Carmenza Quiceno Carvajal', 3112, 0, '2018-01-29 15:03:49', '2018-04-17 19:12:30'),
(822, 'mramirezc@comfamiliar.com', '$2y$10$8fvbnaHYxfQPN3OU6qZnX.QOraYkkTTk0SVxvGkQdifM0gpeByMzq', '1088008162', 'Maryuri Ramirez Cardona', NULL, 0, '2018-05-26 01:45:35', '2018-05-26 01:45:35'),
(823, 'mramirezf@comfamiliar.com', '$2y$10$CeCjKP1nD7xfrY9iROSIjORHVgfskQEQFxCkmQB133s77JZjxIR1G', '42102997', 'Maria Fabiola Ramirez Franco', NULL, 0, '2018-08-16 22:54:51', '2018-08-16 22:54:51'),
(824, 'mramirezga@comfamiliar.com', '$2y$10$YjOglTk0S0WSGjUfqHCpoeWOa64mGf2t4oQumT8OS4hESYBq8TXhi', '1094911423', 'Maria Fernanda Ramirez Garcia', 3310, 0, '2018-02-14 20:15:23', '2018-04-17 19:12:30'),
(825, 'mramirezmo@comfamiliar.com', '$2y$10$tbO2qmw1vzLtFCQHeBuyTeNaar7pXY4h9U5Kv3.NlrXA9bBQfIkPu', '24550962', 'Maricela Ramirez Montoya', NULL, 0, '2018-05-18 16:26:43', '2018-05-18 16:26:43'),
(826, 'mrendon@comfamiliar.com', '$2y$10$riI17ASqnXZpRY57aoNVJucsSLq38xotwbFjRCZejy5lQUFZGQVKe', '42095754', 'Maria Argenis Rendón Morales', 700, 0, '2018-02-21 19:30:27', '2018-04-17 19:12:30'),
(827, 'mrestrepo@comfamiliar.com', '$2y$10$T3KxvOJ2JlCb/l0o3AtWgOtWLedC7qszx3Zicc3t0TiQza6nWNafS', '1087547674', 'Mario Andres Restrepo Perez', NULL, 0, '2018-07-09 12:43:10', '2018-07-09 12:43:10'),
(828, 'mreyes@comfamiliar.com', '$2y$10$4eubUIqFyncmDcUgiHCEc.gRQFbS80c474EkWVxUZB0oNYJmpo5.u', '42165542', 'Maria Jhoana Reyes Castaño', NULL, 0, '2019-01-30 19:24:09', '2019-01-30 19:24:09'),
(829, 'mrodriguezc@comfamiliar.com', '$2y$10$lnd1STXIAFUV5a0KV89riuXKjlkHQc5nAJz8e33ZeCn5Z8Z1cd3S6', '1083904297', 'Milthon Arned Rodriguez Cruz', NULL, 0, '2019-03-16 22:21:27', '2019-03-16 22:21:27'),
(830, 'mruizo@comfamiliar.com', '$2y$10$r2cbCax1OlJwMZJj8B2m2u3DnsCSosq3VGzTXg.4NtkIrPiKDJGMu', '42116939', 'Marinela Ruiz Otalvaro', NULL, 0, '2019-03-04 18:18:03', '2019-03-04 18:18:03'),
(831, 'msalazarg@comfamiliar.com', '$2y$10$ifcQugHfPWT.WFoljBSSw.tCRPShNctdK6GrpqJ7Tqx6DFGm5UNBW', '25102024', 'Monica Liliana Salazar Granados', 5351, 0, '2018-02-14 20:00:54', '2019-01-22 21:35:37'),
(832, 'msalazarm@comfamiliar.com', '$2y$10$L2EIJ31HtN6Yb19REYGj.OCOilM/REgs2QZQivyAD/t9WlvJwH4mK', '42122154', 'Monica Bibiana Salazar Martinez', NULL, 0, '2018-06-01 19:15:12', '2018-06-01 19:15:12'),
(833, 'msanabria@comfamiliar.com', '$2y$10$fy7FW4279iRboQT26sRjd..0HDeEA6cXvR9LX3Has1Xt1845GMff6', '63507814', 'Magda Sanabria Jerez', 5335, 0, '2018-04-02 15:32:27', '2018-04-17 19:12:30'),
(834, 'msanchez@comfamiliar.com', '$2y$10$zUcl.gffWSLdB2vpLhv1NeArR/NNXU5JU831MxIc0Kx6Kq6hl82/a', '9817438', 'Martin Fernando Sanchez Palacio', 400, 0, '2018-01-10 21:10:00', '2018-04-17 19:12:30'),
(835, 'msanchezh@comfamiliar.com', '$2y$10$tqrN5WzTtcnngWVAR43gk.JH2nTG8pO1s4/QL7MCMY3023s.bK9E.', '1088335889', 'Mariana Sanchez Henao', NULL, 0, '2019-02-27 20:32:17', '2019-02-27 20:32:17'),
(836, 'msierra@comfamiliar.com', '$2y$10$rK4B63moiail2JWQ9Wt7KOAITxCGyMJykkJ3LFaaU08XCXJs5st0i', '30293035', 'maria Olga Sierra Patiño', NULL, 0, '2018-05-30 15:07:38', '2018-05-30 15:07:38'),
(837, 'mvalenciar@comfamiliar.com', '$2y$10$yen44VabkQEArrRjvRlmtuW8Kra6t54vWW9O5hxJzN59WDNWUkMMy', '1088329828', 'Manuela Valencia Rendon', NULL, 0, '2018-05-09 19:32:24', '2018-05-09 19:32:24'),
(838, 'mvargas@comfamiliar.com', '$2y$10$GdV2tJIlJzsb9tsXkdOHLuAIRDEfUlcyIuKDH1L29pqPoeew4xh.O', '10008717', 'Andrés Mauricio Vargas Gil', 700, 0, '2018-02-12 15:45:30', '2018-04-17 19:12:30'),
(839, 'mvelasquezr@comfamiliar.com', '$2y$10$/u8K7nzgYOZwNQGI8MJYdOB9NwqgzGFmXXFs82zx8LCtnZ.YStmjO', '42089207', 'Maria Eugenia Velazquez Ruiz', NULL, 0, '2018-07-13 22:13:25', '2018-07-13 22:13:25'),
(840, 'nacevedo@comfamiliar.com', '$2y$10$dGJr7lx52iGF4GvbP6ttdOD3EnS8q9SuCcQ5d4EWF60NUiyRFvIXe', '42021320', 'nubia ines acevedo', NULL, 0, '2018-06-29 15:38:54', '2018-06-29 15:38:54'),
(841, 'nacevedol@comfamiliar.com', '$2y$10$IHWe9UcrqTTnMJlu5pwlQeWC4ZT9bFeF7oH7LJuQQR.sGAQFrYJi.', '42146202', 'Nancy Johanna Acevedo Lopez', NULL, 0, '2018-06-14 06:57:56', '2018-06-14 06:57:56'),
(842, 'nalvarez@comfamiliar.com', '$2y$10$jeVfrcxp2qitDAP0ap.ZaukiePISPO2P5vlGsJTwOzGBDuEOgjPfa', '10005626 ', 'Nestor Fernando Alvarez Tabares', NULL, 0, '2018-12-12 20:06:53', '2018-12-12 20:06:53'),
(843, 'narias@comfamiliar.com', '$2y$10$tnASq1xHwh9F2M6LpKQj1eZEEdzq9UammBoxteX.udx7ksh2B/2yy', '1088326365', 'Nathalia Andrea Arias Torres', NULL, 0, '2018-05-12 19:15:37', '2018-05-12 19:15:37'),
(844, 'narodrigue@comfamiliar.com', '$2y$10$0O4Oj16U4WNMBT7frkkkjuOV7EN2LTvaAyn4Zda.QI7vrzxYPwew2', '1088327597', 'Natalia Rodriguez Rengifo', NULL, 0, '2018-11-01 18:38:03', '2019-04-03 16:05:39'),
(845, 'nbarreto@comfamiliar.com', '$2y$10$Gql6dJxaxoUWsm8yrdVQ5uQ30mCVzLYTXTwJ3bvNC08tKnpk/Aywu', '29111486', 'Nydia Patricia Barreto Parra', NULL, 0, '2018-05-26 14:29:54', '2018-05-26 14:29:54'),
(846, 'nbedoyaa@comfamiliar.com', '$2y$10$rK02X9YbcdTNBf2/J.KVVeQyAMb3f942iEJpNVFRDKq3Qhihqvdee', '1088312672', 'Nathalia Bedoya Agudelo', NULL, 0, '2018-06-19 13:35:31', '2018-07-09 12:31:24'),
(847, 'nbuitrago@comfamiliar.com', '$2y$10$ldx6ef0b1egreHquUVqlW.BzrP83QrQU/VCQ5KSN2YEE7UwV0r/c2', '42155643', 'Nini Johana Buitrago Salazar', NULL, 0, '2019-01-28 18:46:44', '2019-01-28 18:46:44'),
(848, 'ncastanon@comfamiliar.com', '$2y$10$lMkJGqGlGIyKPmTZi1h4ve./T4W8BISrppTn3P5N3ov1G4v/CGMuC', '42111822', 'Norma Liliana Castaño Noreña', NULL, 0, '2018-05-15 02:02:01', '2018-05-15 02:02:01'),
(849, 'ndelgador@comfamiliar.com', '$2y$10$4BC3IUa.NXtLCkWdEsMhdOiCQq5QpxDcQLRH/1zOzg4yJL3F5sVeG', '42013701', 'Nelliria Delgado Ramirez', NULL, 0, '2019-01-18 18:57:48', '2019-05-23 20:34:39'),
(850, 'ngiraldoc@comfamiliar.com', '$2y$10$mKabv6uUAdGu9DbkOqUmDeQndgRHTQApHOVImMxmXs/NTByeIpLMS', '1112128848', 'Nahun Alejandro Giraldo Corrales', NULL, 0, '2018-09-26 22:55:59', '2018-09-26 22:55:59'),
(851, 'ngomeza@comfamiliar.com', '$2y$10$fZ.staO6tH//0ogIaczqhumovELZtyJu9XB/1NJ5kU.wJ5jc3CAM6', '1088261174', 'Natalia Gomez Arango', NULL, 0, '2018-08-01 12:26:44', '2018-08-01 12:26:44'),
(852, 'ngomezv@comfamiliar.com', '$2y$10$4U2UADlmgBjskRlmG8MeyeorIdJ3fHItTw/9trkFw9wboduVgQBwO', '42152554', 'Nohelia Gomez Vera', 3300, 0, '2018-04-16 20:05:55', '2018-04-17 19:12:31'),
(853, 'ngrajales@comfamiliar.com', '$2y$10$JmIS/5nCme9djyc0YHhtM.YDvjA33O5JjTQwz4lvFN4zwn2fV3EjG', '42069126', 'nelcy Grajales Ortiz', NULL, 0, '2018-12-21 03:18:35', '2018-12-21 03:18:35'),
(854, 'ngrajalesv@comfamiliar.com', '$2y$10$KUez88WvnPe5nYbqLPIAD.OhGSuarry4I8SUqBI2v0p9EONVXIXY.', '66683622', 'Nohemy Grajales Vargas', 5315, 0, '2018-03-05 03:04:15', '2018-04-17 19:12:31'),
(855, 'ngrisales@comfamiliar.com', '$2y$10$oe77AiT/rYN4FG6NbQlkvuZSGd4dNjpDgZ8nAucbjcrh8JFqMrRsu', '42145045', 'Nini Johanna Grisales Gutierrez', 2320, 0, '2018-02-13 19:27:37', '2018-04-17 19:12:31'),
(856, 'nguecha@comfamiliar.com', '$2y$10$MgRqNddzPrO/eOxRXDVjruicnAloIUdwqRvLai/t9qH6TLrLQD7OG', '1088021200', 'Natalia Guecha Sanchez', 5332, 0, '2018-02-02 17:13:48', '2018-04-17 19:12:31'),
(857, 'ngutierrez@comfamiliar.com', '$2y$10$PlFSzYtbbjIOOjI6s7/J0.fKo.L8mZSCVcXme21usB09Ayrkoz7.C', '1088300839', 'Neiffi Lorena Gutierrez Perdomo', 3300, 0, '2018-02-12 20:55:42', '2018-04-17 19:12:31'),
(858, 'nherrera@comfamiliar.com', '$2y$10$JxucpxHFfsZ7duSDnYDI7ujjAmciJee3dgYcE8c04XRH5gmt5NH8m', '10144588', 'Nelson Herrera Ortiz', 3331, 0, '2018-02-01 11:52:43', '2018-04-17 19:12:31'),
(859, 'nhurtado@comfamiliar.com', '$2y$10$o6f.gqLUn0lyrZRSlOsSLOPskID8m1gSvDNZn1qrjfq3GtpKT/1Yy', '42146753', 'Natalia Hurtado Mejia', NULL, 0, '2018-08-30 16:12:16', '2018-08-30 16:12:16'),
(860, 'nlondono@comfamiliar.com', '$2y$10$nsjEcs06gX7oTF.H4ReCkOfb.PgqkdFMMYc2GYDT6cYwKUdPcXfU6', '42058735', 'Nelly Londono Carrasquilla', NULL, 0, '2018-07-15 22:30:06', '2018-07-15 22:30:06'),
(861, 'nlondonog@comfamiliar.com', '$2y$10$VGtFqESUFDqvdQgTJGX/xeOursXtQPt8ql/onhUKd3Ghzpr5p9EHS', '42147816', 'Nazly Johana Londoño Giraldo', NULL, 0, '2019-03-28 21:50:35', '2019-03-28 21:50:35'),
(862, 'nmarinv@comfamiliar.com', '$2y$10$ecwJXq9nnCr65NA195c.BeFtYsL67SynSlBL2kzxkQvSJ6yUV1Gva', '1088314022', 'Nathalia Marin Valencia', 2328, 0, '2017-12-26 21:41:42', '2019-05-22 20:04:50'),
(863, 'nmartinezl@comfamiliar.com', '$2y$10$vDhadqnRb0CWwZaWnxhFkOQtyFHgoljI9TIvDk5a.KKWkmqmFxuA.', '31430853', 'Natalia Alejandra Martinez Largo', NULL, 0, '2018-06-19 05:27:25', '2018-06-19 05:27:25'),
(864, 'nmoscoso@comfamiliar.com', '$2y$10$fFKpdXv/MyBS.iHGePusVe4wShkylmNaCeRW7nexmqKx6kfUDhp5a', '1088305160', 'Nelsy Julieth Moscoso Contreras', 3101, 0, '2018-02-01 13:04:35', '2018-04-17 19:12:31'),
(865, 'npalacio@comfamiliar.com', '$2y$10$d4Rl1BNtQ1f.va6z/8FLlOLP2aAwWkWh9xQAMuaOBh3MMD71KzQ9m', '1088299695', 'Nilsa Fernanda Palacio Garcia', NULL, 0, '2019-04-11 16:39:05', '2019-04-11 16:39:05'),
(866, 'npatino@comfamiliar.com', '$2y$10$pEGy2G5YiM1S6mi9BiYngufh2ZhCHdY.QJEic5eVg9h895p0.6yt2', '42103120', 'Noralba Patiño Patiño', NULL, 0, '2018-09-19 22:25:32', '2018-09-19 22:25:32'),
(867, 'npuerta@comfamiliar.com', '$2y$10$u4wve3Fy3x0g/xnYTtAbiuoYXdggYvU3CWrxsroxuQhvjbfCyjIyK', '10022689', 'Nestor Alexis Puerta Ramirez', NULL, 0, '2018-07-16 15:59:57', '2018-07-16 15:59:57'),
(868, 'nquinones@comfamiliar.com', '$2y$10$SJ7gwMMbLShx5bZsQgE6VOTfa.Kv3UwzKnauyy.jGI1Lbg/FG0tUK', '66677229', 'Noralba Quiñones Murillo', NULL, 0, '2018-09-28 13:06:40', '2018-09-28 13:06:40'),
(869, 'nramirezj@comfamiliar.com', '$2y$10$NLkrZjO13baUPbHjXsEPiu/pb7hqKcjEGox2yHuxqOoaJhtOFnayy', '1088252887', 'Natalia Ramirez Jaramillo', 3204, 0, '2018-03-16 16:37:05', '2018-04-17 19:12:31'),
(870, 'nrestrepov@comfamiliar.com', '$2y$10$WHaLO5CuQxEet6wvjOXd0Ova1ISo.tJ2Q6sIUOO.03IltWdCCVmna', '1088269487', 'Nathalia Restrepo Vasquez', 3030, 0, '2018-04-13 19:04:15', '2018-04-17 19:12:31'),
(871, 'nreyes@comfamiliar.com', '$2y$10$KSf/oIplUGiC2hL5W55JOe8AfCWT79DS7//FXJve2vvq/Ox/Hcp9C', '1088314334', 'Nathali Reyes Castaño', 3737, 0, '2018-02-28 13:34:09', '2018-04-17 19:12:31'),
(872, 'nriosb@comfamiliar.com', '$2y$10$0hbC5gsKq0rc.6qJFbsoLO.6VjRnV9DnadlRTBQ5mxcUGjJwwNYbu', '10004701', 'Nestor Fabio Rios Botero', 3206, 0, '2018-01-31 15:25:55', '2018-04-17 19:12:31'),
(873, 'nrodriguez@comfamiliar.com', '$2y$10$U1x57fXEH9QlIoARqlxDau6gNoxZy1PERInXQHtlKIkwEYzUJU4zO', '41920161', 'Nubia Rodriguez Osorio', NULL, 0, '2019-02-27 16:36:47', '2019-02-27 16:36:47'),
(874, 'nsanchezc@comfamiliar.com', '$2y$10$x6GYFa5B0WO0Zqe3XilHL..4kZ8/QuQt5s7mJBTcbCkF6v1esjPca', '1088265873', 'Natalia Yineth Sanchez Castro', NULL, 0, '2019-05-28 14:01:48', '2019-05-28 14:01:48'),
(875, 'nsanchezr@comfamiliar.com', '$2y$10$ZKE8Bpd5r0z3ZD5aFvZagOVuEFgZeKw1LIEoExS/.7ZhBxjoJ9u96', '1088017213', 'Nathalia Sanchez Ramirez', 2329, 0, '2018-02-08 21:03:04', '2018-04-17 19:12:31'),
(876, 'ntoro@comfamiliar.com', '$2y$10$RWfeMNpW8.jSEOZIFem4YuHAqP9o3G7/afsP/1q3CS9rgXM8fehmq', '1088300066', 'Nancy Toro Lazo', NULL, 0, '2018-05-03 13:32:37', '2018-05-03 13:32:37'),
(877, 'nvalenciam@comfamiliar.com', '$2y$10$aaaOt3I/9.GXctjMCultEOePdEqL39Nxfs9HtjrsN44UPSHftpeSG', '1093222992', 'Natali Valencia Mafla', NULL, 0, '2018-04-19 18:15:06', '2018-04-19 18:15:06'),
(878, 'nvega@comfamiliar.com', '$2y$10$jhej89PocUOA8sZUN081Cu5Mv1WtcpSssIzLVLeHVHQSk5NSLFrvS', '1087989307', 'Natalia Vega Arciniegas', 3734, 0, '2018-04-13 13:13:26', '2018-04-17 19:12:31'),
(879, 'nvelez@comfamiliar.com', '$2y$10$JX2Y2itnp7GQbg4cqy2NJO9X5dN.I8CjlmugJSHIhG0ZoHNuMLuRO', '1088294308', 'Natalia Velez Diez', 5335, 0, '2018-04-02 15:55:17', '2018-04-17 19:12:31'),
(880, 'nvilla@comfamiliar.com', '$2y$10$O5r.Pl4Zwz6syKMDvt9VUO2Tf3ft0vMK0ZP5fxtDOtizjod91UFZy', '33800077', 'Norma Patricia Villa', NULL, 0, '2018-05-16 19:00:37', '2018-05-16 19:00:37'),
(881, 'nvillegas@comfamiliar.com', '$2y$10$xFCc1tCaX8HTvFuQ9Rpwi.LYfIfx4/BVEfL3KuFt2LeNW.nsn0766', '42153375', 'Nini Jhoanna Vilegas Ramirez', NULL, 0, '2019-05-24 14:43:09', '2019-05-24 14:43:09'),
(882, 'nzaraza@comfamiliar.com', '$2y$10$p5et/67mF/Hz11bXSSYMxuXLNjMRwSDCvJAhI04H/xnOI52fPDzSe', '24347631', 'Nini Maryet Zaraza Sanchez', 5240, 0, '2018-02-10 00:30:18', '2018-04-17 19:12:32'),
(883, 'oarenas@comfamiliar.com', '$2y$10$xI3rYs50DAOnlOq/kYx43u824gk1dpMYvP9lyqGE2uYAg6GJX/trK', '42095734', 'olga Lucia Arenas Gil', 5351, 0, '2018-04-06 13:29:16', '2018-04-17 19:12:32'),
(884, 'ocamacho@comfamiliar.com', '$2y$10$z9Dti7U6oE7VD70R6bUWb.Mo79zW9QHy7NRyski.rjh4Im2Pgmx6K', '65703130', 'Olga Lucia Camacho Jimenez', 3796, 0, '2018-02-02 12:41:10', '2018-05-08 13:55:02'),
(885, 'ocano@comfamiliar.com', '$2y$10$qrIpH.65qSffBaK4gFXrieHaabHhW/t8PZ2KG0yTPqM2YCD1dZmeq', '94228392', 'OCLIDES DE JESUS CANO ARCE', 3300, 0, '2018-02-13 16:43:07', '2018-04-17 19:12:32'),
(886, 'ocordoba@comfamiliar.com', '$2y$10$7qAvUMPtuubRCvWYWmCz.uT4DbTU6CO8B8135mj8dTSxajsKed6AK', '10093424', 'Oscar de Jesus Cordoba Cuartas', NULL, 0, '2018-10-23 15:14:37', '2018-10-23 15:14:37'),
(887, 'oramirezj@comfamiliar.com', '$2y$10$p21vtG6gNhgY51TyWiI2guIaimt.oqgs3WBunzW6HqURIrVpaRAzi', '1088015222', 'Olga Paola Ramirez Jacome', NULL, 0, '2018-09-08 16:25:55', '2018-09-08 16:25:55'),
(888, 'osanchez@comfamiliar.com', '$2y$10$rHMSorqFhbnnmY7sC/5Xi.WLrHs5P/SyVcS.v5ogX6h1tj2W.1N6G', '51901798', 'Olga Sanchez Jovel', 3112, 0, '2018-04-10 21:11:44', '2018-04-17 19:12:32'),
(889, 'ovilla@comfamiliar.com', '$2y$10$PVmxrmoNyhOEefeGkr.Qsud/jGFthUd/10jNUMzZUPz09QJjWb5yq', '42085345', 'Olga Lucia Villa Lopez', NULL, 0, '2018-08-31 20:04:29', '2018-08-31 20:04:29'),
(890, 'ozuluaga@comfamiliar.com', '$2y$10$GRjQ4LkdhxCMDVCaOstwEuDDrSIIf2kETNzhxim5FSJm4e80Z7yde', '25058047', 'Olma Victoria Zuluaga Gallego', 3737, 0, '2018-04-09 20:19:15', '2019-05-09 18:52:46'),
(891, 'pcastano@comfamiliar.com', '$2y$10$PmEm0a5qBLPPG/18gKcbeuOkV62C6xutCuCEgDDr4JOsP9XwrHlyC', '41955414', 'Paula Andrea Castaño Aguirre', NULL, 0, '2018-11-28 18:08:10', '2018-11-28 18:08:10'),
(892, 'pecheverri@comfamiliar.com', '$2y$10$UchI6ldnD5UBXiw9ot/hRuKMmHvRBJab7NS.kpiYGPnDMoKqNYf8e', '1088287301', 'Paola Andrea Echeverri Gaviria', 2310, 0, '2018-01-29 22:36:07', '2018-04-17 19:12:32'),
(893, 'pflorez@comfamiliar.com', '$2y$10$8Ez8eEft0H74gn8HjVE0Seb8bahvOM904qAkIJvJp8ICg1hAa0Fmq', '10007193', 'Pablo Andres Florez', NULL, 0, '2018-10-12 18:58:58', '2018-10-12 18:58:58'),
(894, 'pfranco@comfamiliar.com', '$2y$10$wy.ukyDo/d7up0Rc7LsxA.5jzt7W06mdRa2UjwyH9N/QtUTAEt8Fm', '1093216593', 'Paula Andrea Franco Gutierrez', NULL, 0, '2018-07-26 23:10:14', '2018-07-26 23:10:14'),
(895, 'pgarcia@comfamiliar.com', '$2y$10$QHaO/PBu23Zcky7ER1BjT.5CCX5YqX6dpGHyhI8BDQ.OUQxAucrC6', '94433130', 'Pablo Cesar Garcia Londoño', 5150, 0, '2018-02-12 14:10:59', '2018-04-17 19:12:32'),
(896, 'pgil@comfamiliar.com', '$2y$10$cez2sl0sr0wMwyE9JO0UH.wEceC49IC9fCbb7U2dXNqG9bEqyeeem', '42130307', 'Paula Andrea Gil Gomez', NULL, 0, '2019-02-02 06:53:18', '2019-02-02 06:53:18'),
(897, 'pherrera@comfamiliar.com', '$2y$10$6C1Q0IToYjlQ5pP0rptt8.cVz3ZXRiK5fUrTyLJVQAga8YZWG6gKS', '42018803', 'Patricia Milena Herrera Monroy', NULL, 0, '2019-04-12 15:16:37', '2019-04-12 15:16:37'),
(898, 'plopez@comfamiliar.com', '$2y$10$skaH94l46cAp8wKGc4QxwOhEtB7sX.Ws65RBj1hLbj8T6Lqv4et0m', '42110548', 'Paola Yormeg Lopez Posada', NULL, 0, '2019-01-31 21:19:11', '2019-01-31 21:19:11'),
(899, 'pmarinh@comfamiliar.com', '$2y$10$YlPgvx9hqeoJOK87BIJiceUkLNOS676DeILVp1hpH4PcSB0KOaZaG', '1128627164', 'Paula Andrea Marin Herrera', 5349, 0, '2018-02-07 22:07:42', '2018-04-17 19:12:32'),
(900, 'posorio@comfamiliar.com', '$2y$10$Ulz57ckCX4oPNCiAGtzY5.IaCktBvUftZkq55oq1QoBgVd3QE8nZO', '18510635', 'Paulo Cesar Osorio Gomez', 2310, 0, '2018-01-24 14:31:57', '2018-04-17 19:12:32'),
(901, 'pramirez@comfamiliar.com', '$2y$10$S/zK7u2d8H4vE4RIZivXbe5hBo6u5itB48TamV1JIJ5/ELTzgtCY.', '4517970', 'Pedro Alexander Ramirez Valencia', NULL, 0, '2018-04-26 01:17:12', '2018-04-26 01:17:12'),
(902, 'prestrepo@comfamiliar.com', '$2y$10$S72FQnAnzIHKRVLQsRVs5e81Pdc45HWRtMPQcRvH0dKZ049x0PFmy', '42116221', 'Paula Andrea Restrepo Bermudez', 3030, 0, '2018-01-30 15:48:08', '2018-04-17 19:12:32'),
(903, 'prodriguez@comfamiliar.com', '$2y$10$SBep1OT2rndY/YGheRLoUua4RHClYl1i9pAnxvrbi4baaFa46A9RK', '27444343', 'Paola Emilse Rodriguez Enriquez', NULL, 0, '2018-06-07 12:37:25', '2018-06-07 12:37:25'),
(904, 'promero@comfamiliar.com', '$2y$10$jEue7hV9yunlVVVJIIWQHOvWexn/VRgJSaSiXzyeXojHQxzbvnZbu', '42136099', 'Paola Andrea Romero Montoya', 5240, 0, '2018-03-28 13:43:11', '2018-07-31 15:42:42'),
(905, 'psaavedra@comfamiliar.com', '$2y$10$yfAO7I7TXAvkA4FrrF2kQezTC4UJWxvuoqBotEKFXqth48jhNLDRG', '42015682', 'Patricia Saavedra Mesa', NULL, 0, '2018-09-14 14:41:41', '2018-09-14 14:41:41'),
(906, 'pserrano@comfamiliar.com', '$2y$10$/Q6GPpP090lfO0WgWGXei.DVkgBExakTTPYXD0KrKyvnsKGc.BWIS', '42065309', 'Patricia Serrano Jaramillo', NULL, 0, '2019-04-03 15:28:29', '2019-04-03 15:28:29'),
(907, 'pzuluaga@comfamiliar.com', '$2y$10$rBRzDIM5WQL.g6vauqhcFeSj/3D9IlHkrTuTf6BzIVpzflme242c6', '30394158', 'Paula Marcela Zuluaga Marin', NULL, 0, '2018-12-10 19:20:15', '2018-12-10 19:20:15'),
(908, 'rarango@comfamiliar.com', '$2y$10$Pb6j1L90q4EszOxfx1itUe.AqPYteRd61yL81x2qFnXsjzDosefXy', '10088408', 'Rogelio Arango Rios', 900, 0, '2018-02-05 22:35:15', '2018-04-17 19:12:32'),
(909, 'rgomezc@comfamiliar.com', '$2y$10$/KtUokvczDI5yUikM2KSputf6iFqhZ.iOmiKA3G1/NJ.oE0cakoGi', '18516252', 'Rafael Genaro Gomez Cardenas', 3410, 0, '2018-01-11 11:56:57', '2018-04-17 19:12:32'),
(910, 'rloaiza@comfamiliar.com', '$2y$10$Qt3vhTu8OUnbK.A/XwX7W.zKiXyVZbhdu.mMty5ByZuvx69LO3Luq', '10095614', 'Ricardo loaiza', 2130, 0, '2018-02-08 15:03:24', '2018-04-17 19:12:32'),
(911, 'rmedina@comfamiliar.com', '$2y$10$Jx57S1Uo8TBlP5t.GjDVE.b57HvkLET2c93Xs0NkqFT27r6ypTzl2', '40614170', 'Rosa Mildred Medina Berrio', NULL, 0, '2018-05-10 21:10:53', '2018-05-10 21:10:53'),
(912, 'rmurillo@comfamiliar.com', '$2y$10$0d.EJYZhP7Mah.r8ysjihueCeO67A3oM7VXigEQckVJEI9PiXMCCW', '1088260229', 'Ricardo Murillo Soto', 900, 0, '2018-01-10 22:22:22', '2019-04-02 16:04:07'),
(913, 'robueno@comfamiliar.com', '$2y$10$KlEF5/xnxRxffz1FNcCs7ePZfYh8NGIG4WCg7mBIFnWatlY8wdHlO', '1088316482', 'Robin Homero Bueno Melchor', NULL, 0, '2018-11-25 22:40:09', '2018-11-25 22:40:09'),
(914, 'rorlas@comfamiliar.com', '$2y$10$0mmcb97WM7Ym1JhI64rWh.PcGueqRlHpwR15THu0RggbN5CRm/YOK', '30301695', 'rosa matilde orlas llanos', NULL, 0, '2018-10-14 03:51:22', '2018-10-14 03:51:22'),
(915, 'rortizt@comfamiliar.com', '$2y$10$qwFs9dAYNFguydHicP.Dwux6BnmN8Mjxv8ALlNySnRatsvc7r2ROO', '1088249239', 'Rudber Ortiz Torres', 2140, 0, '2018-02-13 22:54:55', '2018-10-16 12:54:42'),
(916, 'rpineda@comfamiliar.com', '$2y$10$Jxy5/K67SIVkYhmhyDFR7eaC0HuAtdAUwSV.Ghk1YqBtktU633XX6', '28815238', 'Rocio Margarita Pineda Amortegui', 5315, 0, '2018-02-16 16:15:43', '2018-04-17 19:12:33'),
(917, 'rrestrepo@comfamiliar.com', '$2y$10$CMNy5LnUZi6SOwzN4GIYHOZ.esucMm993TTPoNG7bqgzPKCi/VxE.', '9867909', 'Ricardo Alejandro Restrepo ', 3320, 0, '2018-02-08 14:31:30', '2018-04-17 19:12:33'),
(918, 'rrios@comfamiliar.com', '$2y$10$NKC8..k3G.bEZwppp.NOuuhRKqfkKavh7OcxjfZpggTDn6H4VhqyK', '1093219287', 'Roberto Rios Jimenez', 5240, 0, '2018-03-29 07:34:13', '2018-06-19 00:57:28'),
(919, 'rrodriguezc@comfamiliar.com', '$2y$10$pV9F9OXdddBLpL1MOod0W.yRu3TBMRTgaYwGtOhWK337sQ0SuWbR.', '1088309640', 'Ricardo Andres Rodriguez Cardona', NULL, 0, '2018-05-23 03:16:48', '2018-05-23 03:16:48'),
(920, 'rtangarife@comfamiliar.com', '$2y$10$bfMtZB545/xkYXJ1rRyl5uAZ48rMcUEWg8YOqZkXA1QNd.sk2XmHa', '1088293822', 'Rodrigo Andres Tangarife Ladino', 3737, 0, '2018-02-09 20:03:52', '2018-06-26 12:55:14'),
(921, 'rtrujillo@comfamiliar.com', '$2y$10$d/5K3wmjQRlY/T0jSmSupObOorosS25raFepLk89LQZ3Oc5rOASuO', '10020825', 'Ruben Dario Trujillo Hernandez', 3300, 0, '2018-02-12 15:14:47', '2018-04-17 19:12:33'),
(922, 'sarangos@comfamiliar.com', '$2y$10$hxXxYQQ2dx3HrJ8nfS7Fn.8wRh4xEWGPpxI6yVetxXKhTs1W3.Bv.', '1093219170', 'Sandra Milena Arango Sanchez', 3206, 0, '2018-02-14 21:28:32', '2018-04-17 19:12:33'),
(923, 'sarteaga@comfamiliar.com', '$2y$10$hBSnc1j.WXRcMiVjryt6be3DMu3SrXsogqUWJxruzxE./hXl.foKy', '1087547397', 'Sorayda Isabel Arteaga Sanchez', NULL, 0, '2018-07-26 14:37:50', '2018-07-26 14:37:50'),
(924, 'sbecerra@comfamiliar.com', '$2y$10$h4sl4UGSA0D81ZteguID3.0Bv/ZeYVbUFWkUf0Ho9l/gDEpipMe66', '1087991981', 'Slendy Becerra merchan', NULL, 0, '2018-06-14 21:17:12', '2018-06-14 21:17:12'),
(925, 'sbenitez@comfamiliar.com', '$2y$10$NOXJUI5tZC9nG0AZpmVrsOLSK4LynaaqJPgjCQ7svr.0qB2m7O29i', '1053791981', 'Steven Alexander Benitez Gallego', NULL, 0, '2019-01-18 14:39:12', '2019-01-18 14:39:12'),
(926, 'scalle@comfamiliar.com', '$2y$10$J93iVKbGJtzHNakAjMF9nuH2/Nuy3ucGFT1Q4twnMI8C89CCGbbwq', '1093221634', 'Sandra Lorena Calle Mosquera', 3313, 0, '2018-01-10 16:48:00', '2018-04-17 19:12:34'),
(927, 'scastanop@comfamiliar.com', '$2y$10$kk7A20QlTQ7O/kpVBHp5neFN2FhiaA.PLxzgJuu6XQ3aVKfLv1DKm', '42132051', 'Sandra Liliana Castaño Parias', 5240, 0, '2018-03-21 22:02:01', '2018-04-17 19:12:34'),
(928, 'scifuentesc@comfamiliar.com', '$2y$10$PEAqMw4RrwZcVCmPgg1K7eKkmGs9FdwDl0HPmWAvF48ndzXGCK.rm', '1067860698', 'sergio andres cifuentes canabal', NULL, 0, '2018-11-19 15:39:23', '2018-11-19 15:39:23'),
(929, 'sdiaz@comfamiliar.com', '$2y$10$2Vg.pUhI7OeyosYzsfQ9GuCdSKGGyz.P.VzK3QrODNQewUYIcH9NO', '9993683', 'Silvio Antonio Díaz Restrepo', NULL, 0, '2018-12-27 21:58:44', '2018-12-27 21:58:44'),
(930, 'sdiazp@comfamiliar.com', '$2y$10$TBxOZqtU5zZ9/ucdddR5nOaSKUNeSA2cvp741p5J8061hqasgOsdu', '46367880', 'Sandra Carolina Diaz Parra', NULL, 0, '2018-12-13 15:01:37', '2018-12-13 15:01:37'),
(931, 'sduque@comfamiliar.com', '$2y$10$x7U021048K5dlMCzN2Wx5eqrDsPzTYZXogv3mrjwnEqpf9exs/edm', '42110255', 'Sandra Patricia Duque Mejia', NULL, 0, '2018-11-21 20:55:53', '2018-11-21 20:55:53'),
(932, 'secheverry@comfamiliar.com', '$2y$10$Q3mXNHPfWFi0XxRCT2QOduvGg0xGpfT8Uk5.U6LUbREDp9Ogu0.G2', '1088241381', 'Santiago Echeverry Morales', NULL, 0, '2019-03-04 18:38:48', '2019-03-04 18:38:48'),
(933, 'sgiraldo@comfamiliar.com', '$2y$10$xnO6mWDVKV5cBeVv6k5kVuIXVmbJ/iFveKLY5NzRMg8NFJofMOESy', '42109187', 'Sandra Milena Giraldo Paniagua', 2310, 0, '2018-01-29 15:35:23', '2018-04-17 19:12:35'),
(934, 'sgomezq@comfamiliar.com', '$2y$10$o4x1jaOcDhZnX8xCDIMsGu4Bay/D1tPI1Mjhysj3oEOkz.kPl76Sy', '24766455', 'Sandra Milena Gomez Quiroz', 5366, 0, '2018-02-28 18:42:53', '2018-04-17 19:12:35'),
(935, 'sgrajales@comfamiliar.com', '$2y$10$KZPCxv9cfsGJXR24E1VcROk3n0z9qVWZpgMr5Oi6thEuC73KNC3n.', '42115929', 'Sandra Milena Grajales Agudelo', 5361, 0, '2018-03-04 20:32:02', '2018-04-17 19:12:35'),
(936, 'sguerrero@comfamiliar.com', '$2y$10$GX5vAjee5zddJ2cWBcOnJebpX4ogumv6s318k7ug2S8B3GbEDWopy', '1088332651 ', 'Santiago Guerrero Urbano', 2313, 0, '2018-01-30 19:44:26', '2018-04-17 19:12:35'),
(937, 'slopez@comfamiliar.com', '$2y$10$mroN10rrRbuv1ZF4B5fgSu/HnALa0zgjUIHZ5YqYdBWx8uc51Xtyy', '42015740', 'Sandra Lisbet Lopez Duque', 3321, 0, '2018-03-14 00:33:38', '2018-04-17 19:12:35'),
(938, 'smesa@comfamiliar.com', '$2y$10$Uf7b2GZGFAuPrqe62rmiluMWMZnSbJwKnYQHZX.VQV3BlTY3aZ0oe', '1088035012', 'Santiago Mesa Ceballos', NULL, 0, '2018-12-17 18:50:09', '2018-12-17 18:50:09'),
(939, 'smontes@comfamiliar.com', '$2y$10$kpk6crvEvZYHyQXgEq5NLOeHJtabigc7BfX7SfbPzLpwFled5Zen.', '1053825286', 'Stephanie Montes Arango', 3300, 0, '2018-02-14 15:55:30', '2018-04-17 19:12:35'),
(940, 'smosquera@comfamiliar.com', '$2y$10$J6Z.iRxkJkKkeRVebV6lUeQMUYknjqWnGVVYITQJ9vzGQGpTMk0ry', '42157052', 'Sorany Janeth Mosquera Arce', 800, 0, '2018-02-08 14:15:42', '2018-04-17 19:12:35'),
(941, 'smunozh@comfamiliar.com', '$2y$10$q.ExP7d9tYI9qbcJsri21.PKiIb76G/.ULs7Q02oXkH51o99Cnufy', '25278898', 'Sandra Milena Muñoz Hernandez', 5240, 0, '2018-03-15 18:59:49', '2018-06-20 15:06:15'),
(942, 'sorozcot@comfamiliar.com', '$2y$10$WFW/28Yh5Gol43fD3ux.eOQNZbxB4Ses4bRUxqS41Dj78DCrtfytK', '18612484', 'Sandro Humberto Orozco Tabares', 5315, 0, '2018-03-07 09:23:41', '2018-04-17 19:12:36'),
(943, 'squiceno@comfamiliar.com', '$2y$10$nne48IoLX14HaL9nf9yuDe9PkOQ17WKTEJEHQKbb9PThLzHGGk./S', '42099212', 'Sandra Lorena Quiceno Ramirez', 5344, 0, '2018-02-26 16:24:28', '2018-04-17 19:12:36'),
(944, 'sraigosa@comfamiliar.com', '$2y$10$JXuCptWorhOhZC2cMqGIwO6e6uU.QecswHZnZDirHWTRaRnVpP2ey', '42067140', 'Silvia Elena Raigosa Rios', 5140, 0, '2018-03-20 13:15:04', '2018-04-17 19:12:36'),
(945, 'srendon@comfamiliar.com', '$2y$10$Gi/n6MyvpnGa728nHU8cBuAT3nlqA4561F3Mfl0IbRNTOtMb0Erqu', '42113728', 'Sandra Liliana Rendon Ossa', 3737, 0, '2018-02-22 15:12:58', '2018-04-17 19:12:36'),
(946, 'srengifo@comfamiliar.com', '$2y$10$.mTaDJiv5gcTIOGzILUQE.6yGAwmjYFvgmpH6FExEhAnU0IMIG8H6', '7551314', 'Saul Rengifo Prieto', 2310, 0, '2018-01-24 19:16:19', '2018-04-17 19:12:36'),
(947, 'srios@comfamiliar.com', '$2y$10$8dmkE6/HpDMMS2Z8BQh1fuLxJJjI63Glt61bI5sJODl6XYM.XMjJu', '42109977', 'Sandra Milena Rios Posada', NULL, 0, '2019-03-28 22:29:53', '2019-03-28 22:29:53'),
(948, 'srodas@comfamiliar.com', '$2y$10$pz73PWKbyQ03/H0ZGSb9.eowuqwWB15BeJMvWcwpRHNIHV.Gpb20W', '1088290764', 'Samir Esteven Rodas Lopez', NULL, 0, '2018-10-02 19:37:09', '2018-10-02 19:37:09'),
(949, 'ssalazars@comfamiliar.com', '$2y$10$jOL0wzZPLJR2uRTzpM1ae.U2lTKiZUwkM2ZaGn3xp9Q6Xor8upPKO', '1088313480 ', 'Sara Jazmin Salazar Sicacha', 3410, 0, '2018-01-29 15:01:51', '2018-04-17 19:12:36'),
(950, 'ssanchez@comfamiliar.com', '$2y$10$5ufmqcLdNlemHgmQazhcwOeTOxVQz59KLvQ2iqfJ.z5V2iLw33VxW', '30313500', 'Sandra Patricia Sanchez Hernandez', 3010, 0, '2018-02-09 14:50:35', '2018-04-17 19:12:36'),
(951, 'ssoba@comfamiliar.com', '$2y$10$9FQApX.1/oNCsegZbTSSHuijKeG8M9c1dEHJxfg25eSCyiLhXXjSy', '1088327949', 'Stefany Andrea Soba Cañaveral', 3729, 0, '2018-02-22 21:06:48', '2018-04-17 19:12:36'),
(952, 'ssoto@comfamiliar.com', '$2y$10$1/KxBWPDu5a.q5ARTE3.8e7F2l/kvQgIu/GHjksfeip0U2rq2h9lu', '42128341', 'Sandra Patricia Soto Marulanda', NULL, 0, '2019-03-17 07:22:30', '2019-03-17 07:22:30'),
(953, 'svalencia@comfamiliar.com', '$2y$10$d6/b8zW7mCkitS9PaolFFu4ZZB2VinKPLn9dpLAVRil1TDpc.0/Am', '42094093', 'Solbey Valencia Lopez', NULL, 0, '2018-09-11 18:45:20', '2018-09-11 18:45:20'),
(954, 'svelez@comfamiliar.com', '$2y$10$3vxcXmVXOAf5Q3lNZS0O/ObVS/hMc7Lf0Z4WgfyTZJUh.GMDL3Jlq', '1225092787', 'Stefania Velez Giraldo', 2310, 0, '2018-01-29 16:35:32', '2018-04-17 19:12:36'),
(955, 'svillada@comfamiliar.com', '$2y$10$s5te2k58/heQ6spoySbMiuNDYdbsOLNAFErFQ/S/JDdyxYyPOe6YW', '1088019045', 'Santiago Villada Alvarez', 2328, 0, '2018-01-17 21:53:43', '2018-04-17 19:12:36'),
(956, 'svillegasc@comfamiliar.com', '$2y$10$bZZY21t3MpRvhRTUy7ebr.mzvjyEqQGNz2D3/aCJ9ZLFOBPQ3Q9Sa', '42008341', 'Sandra Villegas Calderon', NULL, 0, '2018-09-28 20:50:27', '2018-09-28 20:50:27'),
(957, 'tiglesias@comfamiliar.com', '$2y$10$QKN3eNEbm58NomNgZF2JRe4zOoaszGVqAybEBhl8TMIbJcgCvIc9a', '1088348626', 'Tatiana Iglesias Rojas', 3206, 0, '2018-01-31 23:26:13', '2018-12-21 19:08:16'),
(958, 'uortiz@comfamiliar.com', '$2y$10$/S7vCQ.2Wl41XIUeGlskleq5hVdJBRfNpX/cHHMhX1Qt7LBvCgfT2', '1088260850', 'Ureny Margarita Ortiz Alarcon', 3325, 0, '2018-01-10 21:04:54', '2018-06-20 19:28:35'),
(959, 'uvargas@comfamiliar.com', '$2y$10$eHor.jYoHgJSJXwOBWvsPObHmTGb4NBv182CFf8r4DPjLLHD4frua', '1088014287', 'Uberney Vargas Restrepo', NULL, 0, '2019-04-18 05:19:37', '2019-04-18 05:19:37'),
(960, 'vante@comfamiliar.com', '$2y$10$CvSyVqO2fA5ARREQKVQaM.Tme5ngCNC1894vNe7aZbuQ13kl45Cgu', '42111519', 'Victoria Eugenia Ante Bedoya', NULL, 0, '2018-08-31 13:32:04', '2018-08-31 13:32:04'),
(961, 'vaortega@comfamiliar.com', '$2y$10$2VAYCc2CQGXrZgX7EmJeVudn6viIDmXCVHep6WGlf3N3dJN4snEDS', '1088336672', 'Valeria Ortega Tabares', NULL, 0, '2018-07-26 12:00:05', '2018-07-26 12:00:05'),
(962, 'vbedoya@comfamiliar.com', '$2y$10$nmk2/CM5v7sGIv5Rx5iZlOwSpMA6ph4atgnJxJM2/N6AbL8kqUSce', '1087994372', 'Veronica Alexandra Bedoya Carmona', NULL, 0, '2018-08-31 08:41:54', '2018-08-31 08:41:54'),
(963, 'vcano@comfamiliar.com', '$2y$10$0Qkh.Rdy/FKCKiO3FSVD5uenXtHS1wn0XZK0FNQIrM9MhUestfcFq', '1088252446', 'Viviana Paola Cano Orrego', 3101, 0, '2018-02-21 21:15:50', '2018-04-17 19:12:37'),
(964, 'vcastro@comfamiliar.com', '$2y$10$7EVz/ufxBh9M1Lhv2Nb2tem4KEWJZuv3ZhiWNQC4tPPuwMxGFpIFe', '1088329130', 'Valery Castro Villa', 700, 0, '2018-02-13 14:15:18', '2018-04-17 19:12:37'),
(965, 'vcorrear@comfamiliar.com', '$2y$10$9venYDIC/zYJXA6quCb13u3gQJl8ZSKQcWAN6lfWTQQRTRqs93arK', '1225089521', 'Valentina Correa Ramirez', NULL, 0, '2019-05-08 20:51:56', '2019-05-08 20:51:56'),
(966, 'vduque@comfamiliar.com', '$2y$10$UjiRxsgigwbroMkgAGSzjOD4dMoCljyduRzb8bzaq.R9K9HKv7lcq', '1088325862 ', 'Valentina Duque Gallego', 3101, 0, '2018-02-12 15:47:25', '2018-04-17 19:12:37'),
(967, 'vescobar@comfamiliar.com', '$2y$10$6XjPGimgFbfikMCaFeDvQejojaq0cZ5Az8lftr74ruxZ8VyDJJCdm', '1088291841', 'Vivian Liseth Escobar Duque', NULL, 0, '2018-11-19 15:24:47', '2018-11-19 15:24:47'),
(968, 'vflorez@comfamiliar.com', '$2y$10$21ygK.1rjpaXVm8elbUrL.jQAQTW1g1/nPCGJwyjja6OU02SAqGtm', '1088309003', 'Valentina Florez Galvis', 2120, 0, '2018-01-29 22:09:30', '2018-04-17 19:12:37'),
(969, 'vgomez@comfamiliar.com', '$2y$10$XvNdJPeXC6HazkE/LMVTq.RHaxmgXO9ewHUoZcUBDBjtcS0ooWvcq', '1088248200', 'Viviana Andrea Gomez Corrales', NULL, 0, '2018-10-26 19:50:02', '2018-10-26 19:50:02'),
(970, 'vguevara@comfamiliar.com', '$2y$10$vXfLR9n0cBpgApFrIkukaOX4CPHQF./.xKPEj3zcitFwg/tTwsy0u', '42159814', 'Viviana Guevara Gomez', 800, 0, '2018-02-15 19:54:26', '2018-04-17 19:12:37'),
(971, 'vjaramillo@comfamiliar.com', '$2y$10$RAhgR6FwX8UiABzNZD.0Qeh42zaev3tBC4o6rwuSJAThJEnEljOs2', '10118551', 'VICTOR MANUEL JARAMILLO GOMEZ ', 3331, 0, '2018-03-10 23:40:04', '2018-04-17 19:12:37'),
(972, 'vlozanom@comfamiliar.com', '$2y$10$GTCAJ5p/aFYZPfpxyN.ftu7Vg8Pd74Pg3/HtrlyWsnBsj.bPviunq', '1088025316', 'Victor David Lozano Marin', NULL, 0, '2018-06-14 12:16:08', '2018-06-14 12:16:08'),
(973, 'vmejia@comfamiliar.com', '$2y$10$pc6.W6uXk6QNM2v64Oru0.4NhFQuI9hbr1OOgZGw8oiGoWpOktlI6', '1112793236', 'Veronica Mejia Rojas', NULL, 0, '2019-05-02 13:57:29', '2019-05-02 13:57:29'),
(974, 'volmos@comfamiliar.com', '$2y$10$HJbEKV60CkNkPcrb.AHXTueXHw4MM4n6lbPpQCsyc.U74RNt8QC6S', '17386841', 'Victor Hugo Olmos', 3722, 0, '2018-02-26 18:56:36', '2018-04-17 19:12:37'),
(975, 'vperdomo@comfamiliar.com', '$2y$10$Mr6AGcO1rOXkVQZAR6Aty.vVX52aFNkxTgi/veoUh/cxor8hSR1RC', '1110473427', 'Valentina Perdomo Rengifo', NULL, 0, '2018-10-16 10:35:37', '2018-10-16 10:35:37'),
(976, 'vrincon@comfamiliar.com', '$2y$10$86Zu5vfCw4z34IlFXrJ2U.KksXMKop.BwjxptP./BFtdU.DWOhMpe', '42151583', 'Viviana Rincon Muñoz', 3796, 0, '2018-01-31 14:46:40', '2018-04-17 19:12:37'),
(977, 'vserna@comfamiliar.com', '$2y$10$zxiTmueRxCvPXH5Kcspxxu/X0WmKo96S22C5ot1vL8inQnuieMqNq', '42111752', 'Viviana Cornelia Serna Bedoya', NULL, 0, '2018-04-20 15:20:13', '2018-04-20 15:20:13'),
(978, 'vsuarez@comfamiliar.com', '$2y$10$6UoOG/WeCedJp6ir23cmveMpHuKk66We8gtzYz8DyLWIYlmXlHzt2', '1088265288', 'Viky Alejandra Hernandez Suarez', NULL, 0, '2018-08-03 15:03:02', '2018-08-03 15:03:02'),
(979, 'vvallejo@comfamiliar.com', '$2y$10$ChmZ5f2SKfrHXrMyjzSDy.ab1vj9Rc1aReSHLk0j/HY9M77e2S9.2', '42146173', 'Viviana Vallejo Muñoz', NULL, 0, '2018-05-07 10:41:11', '2018-05-07 10:41:11'),
(980, 'wbuitrago@comfamiliar.com', '$2y$10$lS8BwtgBaR1Z84q6gNLBcekmTiPSs2lkzo2zlrHmaE6WKoyQTlKyO', '10136000', 'Wilmar de Jesus Guitrago Agudelo', 2310, 0, '2018-01-30 19:04:32', '2019-02-28 13:25:26'),
(981, 'wgiraldo@comfamiliar.com', '$2y$10$GHk.kv2CWTUw9dZne6CWue115jx.hST5PpWyTPVUiSHPqFv2aktI.', '10007779', 'Wilmer Giraldo Loaiza', 2132, 0, '2018-01-15 13:06:36', '2019-04-03 16:45:16'),
(982, 'wpareja@comfamiliar.com', '$2y$10$.CJ1l5tPvdyfjEYqr3YxNurTbJ12CdkKUQDRQzp5U74c.Qsg55Cf2', '18604271', 'Wilson Pareja Castaño', NULL, 0, '2018-10-05 14:03:44', '2018-10-05 14:03:44'),
(983, 'wramirez@comfamiliar.com', '$2y$10$qNnkT6WPg.ijRCZ7mfku8.ZGlwGlowkXX9xUE8BUOW5roHxjdM4.e', '10118189', 'William Nelson Ramirez Leal', NULL, 0, '2018-06-15 21:37:07', '2018-06-15 21:37:07'),
(984, 'wrios@comfamiliar.com', '$2y$10$SPF4SDUsDTkV1X1qWAqRk.VCTkONGkb5oO89QaCDYHH47zTGZbyZ6', '9817875', 'Wilder Rios Aguirre', NULL, 0, '2018-09-13 15:08:06', '2018-09-13 15:08:06'),
(985, 'wruiz@comfamiliar.com', '$2y$10$rYZ3BiHYnY4C736x4rbnHuhGcX7LesvxrlSZCtORCEWz./fy3kblG', '16205145', 'Wiliam de Jesus Ruiz', NULL, 0, '2019-04-23 13:23:43', '2019-04-23 13:23:43'),
(986, 'wzapata@comfamiliar.com', '$2y$10$CGMAv0.ac6mzq6Z9Ka6p.OmijgNc1ROqtY58cR77Pm7q1a0c.bA.e', '4515399', 'Wilmar Leandro Zapata Colorado', 2330, 0, '2018-01-30 21:02:38', '2018-04-17 19:12:38'),
(987, 'xalzate@comfamiliar.com', '$2y$10$CHx4MxDtyRM2FIswf/fK1.cXkmLrij2088VNNJgTD2SXEXK2AHkHu', '1088245792', 'Xiomara Alzate Ramirez', NULL, 0, '2018-09-13 17:42:29', '2018-09-13 17:42:29'),
(988, 'xamejia@comfamiliar.com', '$2y$10$G/2mZZ8b486VPcnjXYa2lO01K7ix8NmMVIHowqze9QLlmgXji674i', '1088285169', 'Xiomara Alexandra Mejia Ladino', NULL, 0, '2018-10-04 12:23:41', '2018-10-04 12:23:41'),
(989, 'xcastano@comfamiliar.com', '$2y$10$eRAoDK5hr/k7ec9I3z1SrOuCptP6k7HRvP74Pm7Yyc/WkGXgnQj9C', '1085717689 ', 'Xiomara Castaño Alzate', 5140, 0, '2018-03-23 21:43:45', '2019-04-09 16:08:39'),
(990, 'xocampo@comfamiliar.com', '$2y$10$9ewHZMMVXgOgRTEQ/vWA/eE2srPNf/QX8RQAh3mNlNuhK2VLlXRtS', '1088007615', 'Xiomara Ocampo Martinez', 5332, 0, '2018-04-14 22:41:54', '2018-04-17 19:12:38'),
(991, 'yarango@comfamiliar.com', '$2y$10$YlCyQoE1/QdERxMVG5vnx.KK39rm6Q.42p5h.exYcSoeEeC7if4ce', '1093225625', 'Yenny Paola Arango Franco', 3112, 0, '2018-03-01 14:53:14', '2019-05-21 14:31:59'),
(992, 'yarangom@comfamiliar.com', '$2y$10$PsbOXIyBRoI.WTGoIStvzOxf2m9lTphviURBJ3z55HTWseqSYAQ0W', '1087997761', 'Yara Lucia Arango Marin', 2310, 0, '2018-02-08 14:08:40', '2018-10-30 14:08:55'),
(993, 'yavila@comfamiliar.com', '$2y$10$viSZMPHgckztTLvh6Uy2DO868q0r.4Da9jBMGeYEl6t79z0Q6AHlK', '38070790', 'Yadith Zorelly Avila Perez', 3722, 0, '2018-01-29 14:58:10', '2018-04-17 19:12:38'),
(994, 'ybedoyaa@comfamiliar.com', '$2y$10$9VvCMgwle71V8v7ENXo6KuTbL3tO9ADMmzFdYPwwoUEBQ.GDVCsRi', '1087555325', 'Yeison Javier Bedoya Acevedo', NULL, 0, '2018-07-13 02:10:03', '2018-07-13 02:10:03'),
(995, 'ybeltran@comfamiliar.com', '$2y$10$FHT6QZPfbXVU.fLznFmN1.GPrJJriienOOW4Q7qasQg3Oey9.WcFi', '42144479', 'Yudi Andrea Beltran Correa', NULL, 0, '2018-06-06 16:12:34', '2018-06-06 16:12:34'),
(996, 'ybermudez@comfamiliar.com', '$2y$10$ON0FmkwWXp3CNIReJQYef.dtNv8XPmhGuKGxuRM2KAh2jlTC4yoSa', '1088262243', 'Yesica Yohana Bermudez Higuita', 2140, 0, '2018-01-29 16:11:28', '2018-04-17 19:12:38'),
(997, 'ybermudezm@comfamiliar.com', '$2y$10$dTM49Dd7Tkm/5PzcuuqK5.bVt5kOW4TLJJ6969BfYbcymDRhwFQHa', '1088031288', 'Yennifer Bermudez Mejia', 3206, 0, '2018-02-21 18:18:49', '2018-04-17 19:12:38'),
(998, 'ybustamante@comfamiliar.com', '$2y$10$U.lvc7f2FsBnoSa6PcHLt.JtoHlLtlm9WpNRSb/lBEsGNABdRX3LG', '1112768375', 'Yulian Fernando Bustamante Jaramillo', NULL, 0, '2018-05-08 17:25:51', '2018-05-08 17:25:51'),
(999, 'ycanol@comfamiliar.com', '$2y$10$o7BpFhty5PO9JxrGSBChzOYVFik2GYHAxeIATLPOgX42a.I1O/JXe', '1088251415', 'Yuri Lorena Canol Bedoya', NULL, 0, '2019-01-11 18:44:26', '2019-01-11 18:44:26'),
(1000, 'ycarabali@comfamiliar.com', '$2y$10$f83Vc8Rs47gZ8zLVJeg.Hui3v40D1KMow4jtPkJ5wOF8r2ErnUzVe', '66743449', 'Yaneth Carabali Montaño', 5366, 0, '2018-04-01 23:28:27', '2018-04-17 19:12:38'),
(1001, 'ycontreras@comfamiliar.com', '$2y$10$c.qDMwm1.5HkhqAHDKXfOul9BxzLOwx0J0HaBGtwbxTyvSBhnDc..', '1090413952', 'Yordy Arenas', NULL, 0, '2018-07-03 21:59:29', '2018-07-03 21:59:29'),
(1002, 'ycruzs@comfamiliar.com', '$2y$10$IgisoQO0D6ewrxdTpBkzP.LgeQRnx7WUs3AUExa9/Eg22D8FL7aRC', '24742858', 'Yasmin Elena Cruz Saldarriaga', NULL, 0, '2018-06-30 15:02:06', '2018-06-30 15:02:06'),
(1003, 'ygomez@comfamiliar.com', '$2y$10$BXMGMwRTQ7KEWwkYmdIH3uC0foMuOKW2ZdznxrHJFY/p72PIj9MPK', '24628467', 'Yolanda Gomez Osorio', NULL, 0, '2018-04-24 16:13:00', '2018-04-24 16:13:00'),
(1004, 'ygomezh@comfamiliar.com', '$2y$10$GVfrEq2Yxe/AXMYDXxWY/.3wIltixWxlfnx/KJ.mLYFODgE4ZuymK', '1088275468', 'Yenni Paola Gomez Hurtado', 3206, 0, '2018-02-06 12:37:15', '2018-04-17 19:12:38'),
(1005, 'ygonzalezt@comfamiliar.com', '$2y$10$U2jJvxrRZDlIfAn.M4guKOOZTPoOKKC9s2MW.Hzgk42qnmGL3wtnW', '42155355', 'Yuri Lorena Gonzalez Torres', NULL, 0, '2018-09-15 06:48:03', '2018-09-15 06:48:03'),
(1006, 'ygrajales@comfamiliar.com', '$2y$10$rDvVDD57m9CVedOFdU3i6OlA2NM2K8cUJYL1T6egN642wS4ydXHyK', '1088014338', 'yuliana grajales estrada', NULL, 0, '2019-02-19 12:41:25', '2019-02-19 12:41:25'),
(1007, 'yguevara@comfamiliar.com', '$2y$10$7aDnI09.rhNH4eYpXIxtDu0T/KYe5dLuDDPTfrAk/t35EoVX62SuO', '1088252946', 'Yoisy Arbey Guevara Villa', NULL, 0, '2019-01-24 21:41:12', '2019-01-24 21:41:12'),
(1008, 'yhincapie@comfamiliar.com', '$2y$10$ho/titp6GO5SRcgNS1xwfuvqcP23WMsLq1WakMUutTx95n7ff0HU6', '42010318', 'María Yermith Hincapié Gómez', NULL, 0, '2018-10-04 22:25:13', '2018-10-04 22:25:13'),
(1009, 'ylancheros@comfamiliar.com', '$2y$10$q7dODiX9SGA5x3IoRhcgP.YLrsP.gKhiwmVzQ8ooqI64d1ld96PS2', '1110470217', 'Yenny Paola Lancheros Pineda', NULL, 0, '2018-07-27 21:35:32', '2018-07-27 21:35:32'),
(1010, 'ylondonoa@comfamiliar.com', '$2y$10$AlQPsYsdcDpSkKrGgtY5HOo9iPQwlQIMWXssnJdzaSzz41uGW.qJm', '1087555192 ', 'Yeferson Londoño Alzate', 3300, 0, '2018-03-08 20:48:06', '2018-04-17 19:12:38'),
(1011, 'ymarulanda@comfamiliar.com', '$2y$10$N4uJDeNQbyJIbWtpjFE5.ewapC0qdf3gZlOcKCzWCjBzpdNunJ/MW', '1088301861', 'Yirlay Marulanda Calderon', NULL, 0, '2018-05-22 16:06:47', '2018-05-22 16:06:47'),
(1012, 'ymejia@comfamiliar.com', '$2y$10$izO0JFnnI9rTY2CmgRHLH.lgKtFcbOYagMCEZakxm860wanA3mYwW', '1088004200', 'Yuliana Mejia Almansa', NULL, 0, '2018-10-18 06:13:10', '2018-10-18 06:13:10'),
(1013, 'ymonsalve@comfamiliar.com', '$2y$10$8ETEzm52XfuZKA0emDTz1u7yQ0gimhgU7wg6jyxLoDnFOIg9fTr26', '1088244808', 'Yury Monsalve Molina', 3738, 0, '2018-02-14 20:28:23', '2018-04-17 19:12:38'),
(1014, 'ymosquera@comfamiliar.com', '$2y$10$McdmpIqWqQt/isiJeeM9ouv/akwwEAheIprGL0qSHBxmgyd.i8M6S', '1093227266', 'Yesid Mosquera Largacha', 3321, 0, '2018-01-29 16:46:23', '2018-04-30 18:55:44'),
(1015, 'ymurillo@comfamiliar.com', '$2y$10$UfjaOUr/1mtwZSNE6UM9De3GCkLiNPUqWocxIzWjQnebVvSbc51sa', '25195456', 'Yaneth Edilma Murillo Cuervo', NULL, 0, '2018-09-24 13:33:01', '2018-09-24 13:33:01'),
(1016, 'yocampo@comfamiliar.com', '$2y$10$0jUwaHfQoNpQvNB.4iRCkeDJ1e9cRQmw5/4EA1N1nk2q7k3/lzm06', ' 42155879', 'Yudi Paola Ocampo ', NULL, 0, '2018-06-27 23:27:29', '2018-06-27 23:27:29'),
(1017, 'yospina@comfamiliar.com', '$2y$10$Jg8TqJhE8RWPhEQBi81gbufffQvCpmaMmqD1ELJPYijHmIBuhe7ZO', '1088030607', 'Yury Bibiana Ospina Bernal', NULL, 0, '2018-09-22 19:48:26', '2018-09-22 19:48:26'),
(1018, 'yquezada@comfamiliar.com', '$2y$10$8fjRVa2.XpEcBdyonX50YOgHj4.M94u.HYjzTuwmnaEx8hVHLNELW', '1088016374', 'Yuri Andrea Quezada Quintero', 5332, 0, '2018-01-29 20:31:46', '2018-04-17 19:12:38'),
(1019, 'yquinterom@comfamiliar.com', '$2y$10$/vKoZh8qZqi8BJz625djWeLN5NJdvAx7IW4DNIaA0SJYAjKAEn9xC', '1093225199 ', 'Yeimer Alejandro Quintero Morales', 3300, 0, '2018-01-27 14:28:08', '2019-04-16 18:54:24'),
(1020, 'yrestrepo@comfamiliar.com', '$2y$10$Yx7Bl.6MthgT1kZCiC4G4OrV5N.DKeAp.DEd.tnbSl.BgaqwV6/.y', '1088287259', 'yeimy Alexandra Restrepo G.', 3313, 0, '2018-02-14 20:26:29', '2018-04-17 19:12:39'),
(1021, 'yriascos@comfamiliar.com', '$2y$10$IKqWzZbat01r.Pgwrltlq.HDpba3nIfJt8mIjPx/.Ow9SKERBwzs6', '1093217534', 'Yazmin Riascos Henao', 3730, 0, '2018-04-11 16:25:14', '2018-04-17 19:12:39'),
(1022, 'yrios@comfamiliar.com', '$2y$10$ohzzZYVKUzfbQrEYHO4iC.HNZUX3VvNUkxN1wlCbeCsuCZdP2Wyzu', '1192723834 ', 'Yessica Alejandra Rios Rios', NULL, 0, '2018-05-10 16:02:17', '2018-05-10 16:02:17'),
(1023, 'ysanchezb@comfamiliar.com', '$2y$10$p7GWI8Miv8ARXw22hynar.wjcJj6XTf/fIKyMSLV/HL6VSK/49Tr2', '42164244', 'Yuly Marcela Sanchez Bustamante', NULL, 0, '2019-04-23 20:18:03', '2019-04-23 20:18:03'),
(1024, 'ysanchezr@comfamiliar.com', '$2y$10$HGue.ofXs6SFDZ2S2fd23.AcgVsCsLKivbFX4AXR5R43FVyG3lxXC', '1088320590', 'Yuri Andrea Sanchez Restrepo', NULL, 0, '2018-12-27 18:24:12', '2018-12-27 18:24:12');
INSERT INTO `sara_usuarios` (`id`, `Email`, `Password`, `Cedula`, `Nombres`, `CDC_id`, `isGod`, `created_at`, `updated_at`) VALUES
(1025, 'yserna@comfamiliar.com', '$2y$10$dE8FomR3hMOvQRD6iYvBFeOXpE7kVdb951nTAWkIE/8zJG4Q.EHpC', '24695448', 'Yeimi Del pilar Serna Ramirez', NULL, 0, '2018-07-20 01:42:00', '2018-07-20 01:42:00'),
(1026, 'ysilva@comfamiliar.com', '$2y$10$xeplzXHbJEXIUhTdcut0luOrn9JJ2RNeJBTUov4AuFV29yE0NDYoW', '33966591', 'Yury Viviana Silva Ospina', 3743, 0, '2018-03-05 22:20:53', '2018-04-17 19:12:39'),
(1027, 'ysolano@comfamiliar.com', '$2y$10$v4H62t0qIqBU3MINZzzP3O35hxZI3EI9GqWlynC0NWfq3734eI0LG', '30334795', 'Yesnelda Solano Muñoz', 5316, 0, '2018-02-10 17:03:00', '2018-04-17 19:12:39'),
(1028, 'yvalencia@comfamiliar.com', '$2y$10$EL3IJ/yiSuhtM2.WiSJMLeizNkrtDR4xlaaMULyzshIlOuUEAOGGm', '1088001766', 'Yuly Viviana Valencia Benjumea', NULL, 0, '2018-12-04 14:33:32', '2018-12-04 14:33:32'),
(1029, 'yvillegas@comfamiliar.com', '$2y$10$9L9z9bHlXVssbL6u0GWxwOd7sUsL1BRNmHIPC02QXb9U5yB.h4KUi', '24414513', 'Yuledy Esther Villegas Muñoz', NULL, 0, '2018-05-25 13:41:26', '2018-05-25 13:41:26'),
(1030, 'yyepes@comfamiliar.com', '$2y$10$SBfUF.jLwhJ2ctwaXHmgfudBVZ/FQmi19BNwPZ38c7EYwsu9c8h4W', '9861325', 'Yeison Andres Yepes ', 3321, 0, '2018-03-15 18:09:45', '2018-04-17 19:12:40'),
(1031, 'yzapata@comfamiliar.com', '$2y$10$lLB3VV64hnRjQCoOAuc8Cehnk.NcGhVU7I3.x4878QTjN/UP.3oAm', '1088007586', 'Yenny Alexandra Zapata Echeverry', NULL, 0, '2019-02-28 19:27:43', '2019-02-28 19:27:43'),
(1032, 'zarce@comfamiliar.com', '$2y$10$pWMYfNIzf8x6Le1cBJfMkuZ9Mb66oLOswtNv0OwXzdw1PT/DInfOC', '1088281455', 'Zulma Milena Arce Pinzon', NULL, 0, '2018-11-14 18:22:46', '2018-11-14 18:22:46'),
(1033, 'zdelgado@comfamiliar.com', '$2y$10$J0kNWoRx3yCMCD1YVzNkUu6JbYEZtrrdSdaW2MJOADL.SvfFi0DU.', '42013216', 'Zoraida Delgado Dias', NULL, 0, '2019-01-13 13:02:18', '2019-01-13 13:02:18'),
(1034, 'zperez@comfamiliar.com', '$2y$10$M4H7PUtW4AtMW6Im2W/zrOAZtoh.JFadvLFL.qoifOvkxMZQv5JJa', '1087996142', 'Zoraida Perez Agudelo', NULL, 0, '2018-06-21 09:36:37', '2018-06-21 09:36:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_usuario_apps`
--

CREATE TABLE `sara_usuario_apps` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `favorito` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_usuario_apps`
--

INSERT INTO `sara_usuario_apps` (`id`, `usuario_id`, `app_id`, `favorito`, `created_at`, `updated_at`) VALUES
(1, 183, 1, 1, '0000-00-00 00:00:00', '2019-05-28 17:34:30'),
(2, 183, 2, 1, '0000-00-00 00:00:00', '2019-05-31 15:13:11'),
(3, 183, 3, 0, '0000-00-00 00:00:00', '2019-05-28 17:29:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `sara_apps`
--
ALTER TABLE `sara_apps`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `sara_bdds`
--
ALTER TABLE `sara_bdds`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_bdds_favoritos`
--
ALTER TABLE `sara_bdds_favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bdd_id` (`bdd_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `sara_entidades`
--
ALTER TABLE `sara_entidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bdd_id` (`bdd_id`);

--
-- Indices de la tabla `sara_entidades_campos`
--
ALTER TABLE `sara_entidades_campos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entidad_id` (`entidad_id`);

--
-- Indices de la tabla `sara_entidades_grids`
--
ALTER TABLE `sara_entidades_grids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entidad_id` (`entidad_id`);

--
-- Indices de la tabla `sara_entidades_grids_columnas`
--
ALTER TABLE `sara_entidades_grids_columnas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grid_id` (`grid_id`);

--
-- Indices de la tabla `sara_entidades_grids_filtros`
--
ALTER TABLE `sara_entidades_grids_filtros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `columna_id` (`columna_id`),
  ADD KEY `grid_id` (`grid_id`);

--
-- Indices de la tabla `sara_entidades_restricciones`
--
ALTER TABLE `sara_entidades_restricciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_secciones`
--
ALTER TABLE `sara_secciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_usuarios`
--
ALTER TABLE `sara_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_usuario_apps`
--
ALTER TABLE `sara_usuario_apps`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sara_apps`
--
ALTER TABLE `sara_apps`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sara_bdds`
--
ALTER TABLE `sara_bdds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sara_bdds_favoritos`
--
ALTER TABLE `sara_bdds_favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sara_entidades`
--
ALTER TABLE `sara_entidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_campos`
--
ALTER TABLE `sara_entidades_campos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_grids`
--
ALTER TABLE `sara_entidades_grids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_grids_columnas`
--
ALTER TABLE `sara_entidades_grids_columnas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_grids_filtros`
--
ALTER TABLE `sara_entidades_grids_filtros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_restricciones`
--
ALTER TABLE `sara_entidades_restricciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `sara_usuarios`
--
ALTER TABLE `sara_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1035;

--
-- AUTO_INCREMENT de la tabla `sara_usuario_apps`
--
ALTER TABLE `sara_usuario_apps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sara_entidades_grids_columnas`
--
ALTER TABLE `sara_entidades_grids_columnas`
  ADD CONSTRAINT `sara_entidades_grids_columnas_ibfk_1` FOREIGN KEY (`grid_id`) REFERENCES `sara_entidades_grids` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sara_entidades_grids_filtros`
--
ALTER TABLE `sara_entidades_grids_filtros`
  ADD CONSTRAINT `sara_entidades_grids_filtros_ibfk_1` FOREIGN KEY (`columna_id`) REFERENCES `sara_entidades_grids_columnas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sara_entidades_grids_filtros_ibfk_2` FOREIGN KEY (`grid_id`) REFERENCES `sara_entidades_grids` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
