-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2021 a las 04:02:00
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbsara_alternative`
--

DELIMITER $$
--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CAP_FIRST` (`input` VARCHAR(255)) RETURNS VARCHAR(255) CHARSET latin1 BEGIN
	DECLARE len INT;
	DECLARE i INT;

	SET len   = CHAR_LENGTH(input);
	SET input = LOWER(input);
	SET i = 0;

	WHILE (i < len) DO
		IF (MID(input,i,1) = ' ' OR i = 0) THEN
			IF (i < len) THEN
				SET input = CONCAT(
					LEFT(input,i),
					UPPER(MID(input,i + 1,1)),
					RIGHT(input,len - i - 1)
				);
			END IF;
		END IF;
		SET i = i + 1;
	END WHILE;

	RETURN input;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_apps`
--

CREATE TABLE `sara_apps` (
  `id` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Detalles` varchar(255) DEFAULT NULL,
  `Slug` varchar(10) DEFAULT NULL,
  `Icono` varchar(50) DEFAULT NULL,
  `Color` varchar(10) DEFAULT NULL,
  `Navegacion` varchar(30) NOT NULL DEFAULT 'Superior',
  `ToolbarSize` int(11) NOT NULL DEFAULT 30,
  `Procesos` varchar(1000) NOT NULL DEFAULT '[]',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_apps_pages`
--

CREATE TABLE `sara_apps_pages` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `Indice` int(11) NOT NULL DEFAULT 0,
  `Titulo` varchar(255) NOT NULL,
  `Tipo` varchar(50) NOT NULL,
  `Config` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Consulta` text DEFAULT NULL,
  `EjecutarAutom` varchar(1) NOT NULL DEFAULT 'N',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_bdds_listas`
--

CREATE TABLE `sara_bdds_listas` (
  `id` int(10) NOT NULL,
  `bdd_id` int(10) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Indice` varchar(150) DEFAULT NULL,
  `IndiceCod` varchar(150) DEFAULT NULL,
  `IndiceDes` varchar(150) DEFAULT NULL,
  `Detalle` varchar(150) DEFAULT NULL,
  `Llave` varchar(150) DEFAULT NULL,
  `DetalleCod` varchar(150) DEFAULT NULL,
  `DetalleDes` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_bots`
--

CREATE TABLE `sara_bots` (
  `id` int(11) NOT NULL,
  `Nombre` varchar(500) NOT NULL,
  `Estado` varchar(50) NOT NULL DEFAULT 'Espera',
  `config` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `lastrun_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_bots_logs`
--

CREATE TABLE `sara_bots_logs` (
  `id` int(11) NOT NULL,
  `bot_id` int(11) NOT NULL,
  `bot_paso_id` int(11) DEFAULT NULL,
  `Estado` varchar(500) DEFAULT NULL,
  `Mensaje` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_bots_pasos`
--

CREATE TABLE `sara_bots_pasos` (
  `id` int(11) NOT NULL,
  `bot_id` int(11) NOT NULL,
  `Indice` int(11) NOT NULL,
  `Tipo` varchar(100) NOT NULL,
  `Nombre` varchar(500) NOT NULL,
  `config` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_bots_variables`
--

CREATE TABLE `sara_bots_variables` (
  `id` int(11) NOT NULL,
  `bot_id` int(11) NOT NULL,
  `Nombre` varchar(500) NOT NULL,
  `Valor` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_comentarios`
--

CREATE TABLE `sara_comentarios` (
  `id` int(11) NOT NULL,
  `Entidad` varchar(50) DEFAULT NULL,
  `Entidad_id` varchar(30) DEFAULT NULL,
  `Grupo` varchar(50) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `Comentario` varchar(5000) NOT NULL,
  `Op1` int(11) DEFAULT NULL,
  `Op2` varchar(30) DEFAULT NULL,
  `Op3` varchar(50) DEFAULT NULL,
  `Op4` varchar(1000) DEFAULT NULL,
  `Op5` int(11) DEFAULT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_configuracion`
--

CREATE TABLE `sara_configuracion` (
  `id` int(11) NOT NULL,
  `Configuracion` varchar(250) NOT NULL,
  `Valor` varchar(5000) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades`
--

CREATE TABLE `sara_entidades` (
  `id` int(11) NOT NULL,
  `bdd_id` int(11) NOT NULL,
  `proceso_id` int(11) DEFAULT NULL,
  `Ruta` varchar(1000) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Tipo` enum('Tabla','Vista') NOT NULL DEFAULT 'Tabla',
  `Tabla` varchar(255) NOT NULL,
  `campo_llaveprim` int(11) DEFAULT NULL,
  `campo_orderby` int(11) DEFAULT NULL,
  `campo_orderbydir` varchar(5) NOT NULL DEFAULT 'DESC',
  `max_rows` int(100) NOT NULL DEFAULT 100,
  `config` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_campos`
--

CREATE TABLE `sara_entidades_campos` (
  `id` int(11) NOT NULL,
  `entidad_id` int(11) NOT NULL,
  `Indice` int(11) NOT NULL,
  `Columna` varchar(1000) NOT NULL,
  `Alias` varchar(100) DEFAULT NULL,
  `Tipo` varchar(30) NOT NULL DEFAULT 'Texto',
  `Defecto` varchar(255) DEFAULT NULL,
  `Requerido` tinyint(1) NOT NULL DEFAULT 1,
  `Visible` tinyint(1) NOT NULL DEFAULT 1,
  `Unico` tinyint(1) NOT NULL DEFAULT 0,
  `Editable` tinyint(1) NOT NULL DEFAULT 1,
  `Buscable` tinyint(1) NOT NULL DEFAULT 0,
  `Desagregable` tinyint(1) NOT NULL DEFAULT 0,
  `Op1` int(100) DEFAULT NULL,
  `Op2` int(100) DEFAULT NULL,
  `Op3` int(100) DEFAULT NULL,
  `Op4` varchar(50) DEFAULT NULL,
  `Op5` varchar(50) DEFAULT NULL,
  `Config` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_cargadores`
--

CREATE TABLE `sara_entidades_cargadores` (
  `id` int(11) NOT NULL,
  `entidad_id` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Plantilla` varchar(255) DEFAULT NULL,
  `Config` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_editores`
--

CREATE TABLE `sara_entidades_editores` (
  `id` int(11) NOT NULL,
  `entidad_id` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Ancho` int(11) NOT NULL DEFAULT 400,
  `Secciones` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_editores_campos`
--

CREATE TABLE `sara_entidades_editores_campos` (
  `id` int(11) NOT NULL,
  `editor_id` int(11) NOT NULL,
  `seccion_id` int(11) DEFAULT NULL,
  `Indice` int(11) NOT NULL DEFAULT 0,
  `Etiqueta` varchar(255) DEFAULT NULL,
  `campo_id` int(11) DEFAULT NULL,
  `Tipo` varchar(30) DEFAULT NULL,
  `Ancho` int(11) NOT NULL DEFAULT 100,
  `Visible` tinyint(1) NOT NULL DEFAULT 1,
  `Editable` tinyint(1) NOT NULL DEFAULT 1,
  `Op1` int(100) DEFAULT NULL,
  `Op2` int(100) DEFAULT NULL,
  `Op3` varchar(1000) DEFAULT NULL,
  `Op4` varchar(1000) DEFAULT NULL,
  `Op5` varchar(1000) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_entidades_grids`
--

CREATE TABLE `sara_entidades_grids` (
  `id` int(11) NOT NULL,
  `entidad_id` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Config` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Ruta` text DEFAULT NULL,
  `Llaves` text DEFAULT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT 1,
  `campo_id` int(11) DEFAULT NULL,
  `externalgrid_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Locked` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_feedback`
--

CREATE TABLE `sara_feedback` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `Tema` varchar(1000) NOT NULL,
  `Comentario` text NOT NULL,
  `Estado` varchar(100) NOT NULL DEFAULT 'Pendiente',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_iconos`
--

CREATE TABLE `sara_iconos` (
  `id` int(11) NOT NULL,
  `Icono` varchar(100) NOT NULL,
  `IconoFull` varchar(100) NOT NULL,
  `IconoLabel` varchar(100) NOT NULL,
  `Categoria` varchar(100) NOT NULL,
  `Estilo` varchar(100) NOT NULL,
  `Unicode` varchar(50) NOT NULL,
  `PalabrasClave` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_iconos`
--

INSERT INTO `sara_iconos` (`id`, `Icono`, `IconoFull`, `IconoLabel`, `Categoria`, `Estilo`, `Unicode`, `PalabrasClave`, `created_at`, `updated_at`) VALUES
(1, '500px', 'fab fa-500px', '500px', 'Marcas', 'brands', 'f26e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'accessible-icon', 'fab fa-accessible-icon', 'Accessible Icon', 'Marcas', 'brands', 'f368', 'accessibility, handicap, person, wheelchair, wheelchair-alt', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'accusoft', 'fab fa-accusoft', 'Accusoft', 'Marcas', 'brands', 'f369', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'acquisitions-incorporated', 'fab fa-acquisitions-incorporated', 'Acquisitions Incorporated', 'Marcas', 'brands', 'f6af', 'Dungeons & Dragons, d&d, dnd, fantasy, game, gaming, tabletop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'ad', 'fas fa-ad', 'Ad', 'Otros', 'solid', 'f641', 'advertisement, media, newspaper, promotion, publicity', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'address-book', 'fas fa-address-book', 'Address Book', 'Otros', 'solid', 'f2b9', 'contact, directory, index, little black book, rolodex', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'address-book', 'far fa-address-book', 'Address Book', 'Otros', 'regular', 'f2b9', 'contact, directory, index, little black book, rolodex', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'address-card', 'fas fa-address-card', 'Address Card', 'Otros', 'solid', 'f2bb', 'about, contact, id, identification, postcard, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'address-card', 'far fa-address-card', 'Address Card', 'Otros', 'regular', 'f2bb', 'about, contact, id, identification, postcard, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'adjust', 'fas fa-adjust', 'adjust', 'Otros', 'solid', 'f042', 'contrast, dark, light, saturation', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'adn', 'fab fa-adn', 'App.net', 'Marcas', 'brands', 'f170', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'adobe', 'fab fa-adobe', 'Adobe', 'Marcas', 'brands', 'f778', 'acrobat, app, design, illustrator, indesign, photoshop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'adversal', 'fab fa-adversal', 'Adversal', 'Marcas', 'brands', 'f36a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'affiliatetheme', 'fab fa-affiliatetheme', 'affiliatetheme', 'Marcas', 'brands', 'f36b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'air-freshener', 'fas fa-air-freshener', 'Air Freshener', 'Otros', 'solid', 'f5d0', 'car, deodorize, fresh, pine, scent', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'airbnb', 'fab fa-airbnb', 'Airbnb', 'Marcas', 'brands', 'f834', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'algolia', 'fab fa-algolia', 'Algolia', 'Marcas', 'brands', 'f36c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'align-center', 'fas fa-align-center', 'align-center', 'Editores', 'solid', 'f037', 'format, middle, paragraph, text', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'align-justify', 'fas fa-align-justify', 'align-justify', 'Editores', 'solid', 'f039', 'format, paragraph, text', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'align-left', 'fas fa-align-left', 'align-left', 'Editores', 'solid', 'f036', 'format, paragraph, text', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'align-right', 'fas fa-align-right', 'align-right', 'Editores', 'solid', 'f038', 'format, paragraph, text', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'alipay', 'fab fa-alipay', 'Alipay', 'Marcas', 'brands', 'f642', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'allergies', 'fas fa-allergies', 'Allergies', 'Otros', 'solid', 'f461', 'allergy, freckles, hand, hives, pox, skin, spots', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'amazon', 'fab fa-amazon', 'Amazon', 'Marcas', 'brands', 'f270', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'amazon-pay', 'fab fa-amazon-pay', 'Amazon Pay', 'Marcas', 'brands', 'f42c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'ambulance', 'fas fa-ambulance', 'ambulance', 'Otros', 'solid', 'f0f9', 'emergency, emt, er, help, hospital, support, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'american-sign-language-interpreting', 'fas fa-american-sign-language-interpreting', 'American Sign Language Interpreting', 'Otros', 'solid', 'f2a3', 'asl, deaf, finger, hand, interpret, speak', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'amilia', 'fab fa-amilia', 'Amilia', 'Marcas', 'brands', 'f36d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'anchor', 'fas fa-anchor', 'Anchor', 'Otros', 'solid', 'f13d', 'berth, boat, dock, embed, link, maritime, moor, secure', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'android', 'fab fa-android', 'Android', 'Marcas', 'brands', 'f17b', 'robot', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'angellist', 'fab fa-angellist', 'AngelList', 'Marcas', 'brands', 'f209', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'angle-double-down', 'fas fa-angle-double-down', 'Angle Double Down', 'Otros', 'solid', 'f103', 'arrows, caret, download, expand', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'angle-double-left', 'fas fa-angle-double-left', 'Angle Double Left', 'Otros', 'solid', 'f100', 'arrows, back, caret, laquo, previous, quote', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'angle-double-right', 'fas fa-angle-double-right', 'Angle Double Right', 'Otros', 'solid', 'f101', 'arrows, caret, forward, more, next, quote, raquo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'angle-double-up', 'fas fa-angle-double-up', 'Angle Double Up', 'Otros', 'solid', 'f102', 'arrows, caret, collapse, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'angle-down', 'fas fa-angle-down', 'angle-down', 'Otros', 'solid', 'f107', 'arrow, caret, download, expand', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'angle-left', 'fas fa-angle-left', 'angle-left', 'Otros', 'solid', 'f104', 'arrow, back, caret, less, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'angle-right', 'fas fa-angle-right', 'angle-right', 'Otros', 'solid', 'f105', 'arrow, care, forward, more, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'angle-up', 'fas fa-angle-up', 'angle-up', 'Otros', 'solid', 'f106', 'arrow, caret, collapse, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'angry', 'fas fa-angry', 'Angry Face', 'Otros', 'solid', 'f556', 'disapprove, emoticon, face, mad, upset', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'angry', 'far fa-angry', 'Angry Face', 'Otros', 'regular', 'f556', 'disapprove, emoticon, face, mad, upset', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'angrycreative', 'fab fa-angrycreative', 'Angry Creative', 'Marcas', 'brands', 'f36e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'angular', 'fab fa-angular', 'Angular', 'Marcas', 'brands', 'f420', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'ankh', 'fas fa-ankh', 'Ankh', 'Otros', 'solid', 'f644', 'amulet, copper, coptic christianity, copts, crux ansata, egypt, venus', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'app-store', 'fab fa-app-store', 'App Store', 'Marcas', 'brands', 'f36f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'app-store-ios', 'fab fa-app-store-ios', 'iOS App Store', 'Marcas', 'brands', 'f370', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'apper', 'fab fa-apper', 'Apper Systems AB', 'Marcas', 'brands', 'f371', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'apple', 'fab fa-apple', 'Apple', 'Marcas', 'brands', 'f179', 'fruit, ios, mac, operating system, os, osx', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'apple-alt', 'fas fa-apple-alt', 'Fruit Apple', 'Otros', 'solid', 'f5d1', 'fall, fruit, fuji, macintosh, orchard, seasonal, vegan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'apple-pay', 'fab fa-apple-pay', 'Apple Pay', 'Marcas', 'brands', 'f415', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'archive', 'fas fa-archive', 'Archive', 'Otros', 'solid', 'f187', 'box, package, save, storage', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'archway', 'fas fa-archway', 'Archway', 'Otros', 'solid', 'f557', 'arc, monument, road, street, tunnel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'arrow-alt-circle-down', 'fas fa-arrow-alt-circle-down', 'Alternate Arrow Circle Down', 'Otros', 'solid', 'f358', 'arrow-circle-o-down, download', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'arrow-alt-circle-down', 'far fa-arrow-alt-circle-down', 'Alternate Arrow Circle Down', 'Otros', 'regular', 'f358', 'arrow-circle-o-down, download', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'arrow-alt-circle-left', 'fas fa-arrow-alt-circle-left', 'Alternate Arrow Circle Left', 'Otros', 'solid', 'f359', 'arrow-circle-o-left, back, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'arrow-alt-circle-left', 'far fa-arrow-alt-circle-left', 'Alternate Arrow Circle Left', 'Otros', 'regular', 'f359', 'arrow-circle-o-left, back, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'arrow-alt-circle-right', 'fas fa-arrow-alt-circle-right', 'Alternate Arrow Circle Right', 'Otros', 'solid', 'f35a', 'arrow-circle-o-right, forward, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'arrow-alt-circle-right', 'far fa-arrow-alt-circle-right', 'Alternate Arrow Circle Right', 'Otros', 'regular', 'f35a', 'arrow-circle-o-right, forward, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'arrow-alt-circle-up', 'fas fa-arrow-alt-circle-up', 'Alternate Arrow Circle Up', 'Otros', 'solid', 'f35b', 'arrow-circle-o-up', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'arrow-alt-circle-up', 'far fa-arrow-alt-circle-up', 'Alternate Arrow Circle Up', 'Otros', 'regular', 'f35b', 'arrow-circle-o-up', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'arrow-circle-down', 'fas fa-arrow-circle-down', 'Arrow Circle Down', 'Otros', 'solid', 'f0ab', 'download', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'arrow-circle-left', 'fas fa-arrow-circle-left', 'Arrow Circle Left', 'Otros', 'solid', 'f0a8', 'back, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'arrow-circle-right', 'fas fa-arrow-circle-right', 'Arrow Circle Right', 'Otros', 'solid', 'f0a9', 'forward, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'arrow-circle-up', 'fas fa-arrow-circle-up', 'Arrow Circle Up', 'Otros', 'solid', 'f0aa', 'upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'arrow-down', 'fas fa-arrow-down', 'arrow-down', 'Otros', 'solid', 'f063', 'download', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'arrow-left', 'fas fa-arrow-left', 'arrow-left', 'Otros', 'solid', 'f060', 'back, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'arrow-right', 'fas fa-arrow-right', 'arrow-right', 'Otros', 'solid', 'f061', 'forward, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'arrow-up', 'fas fa-arrow-up', 'arrow-up', 'Otros', 'solid', 'f062', 'forward, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'arrows-alt', 'fas fa-arrows-alt', 'Alternate Arrows', 'Otros', 'solid', 'f0b2', 'arrow, arrows, bigger, enlarge, expand, fullscreen, move, position, reorder, resize', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'arrows-alt-h', 'fas fa-arrows-alt-h', 'Alternate Arrows Horizontal', 'Otros', 'solid', 'f337', 'arrows-h, expand, horizontal, landscape, resize, wide', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'arrows-alt-v', 'fas fa-arrows-alt-v', 'Alternate Arrows Vertical', 'Otros', 'solid', 'f338', 'arrows-v, expand, portrait, resize, tall, vertical', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'artstation', 'fab fa-artstation', 'Artstation', 'Marcas', 'brands', 'f77a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'assistive-listening-systems', 'fas fa-assistive-listening-systems', 'Assistive Listening Systems', 'Otros', 'solid', 'f2a2', 'amplify, audio, deaf, ear, headset, hearing, sound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'asterisk', 'fas fa-asterisk', 'asterisk', 'Otros', 'solid', 'f069', 'annotation, details, reference, star', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'asymmetrik', 'fab fa-asymmetrik', 'Asymmetrik, Ltd.', 'Marcas', 'brands', 'f372', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'at', 'fas fa-at', 'At', 'Otros', 'solid', 'f1fa', 'address, author, e-mail, email, handle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'atlas', 'fas fa-atlas', 'Atlas', 'Otros', 'solid', 'f558', 'book, directions, geography, globe, map, travel, wayfinding', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'atlassian', 'fab fa-atlassian', 'Atlassian', 'Marcas', 'brands', 'f77b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'atom', 'fas fa-atom', 'Atom', 'Otros', 'solid', 'f5d2', 'atheism, chemistry, ion, nuclear, science', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'audible', 'fab fa-audible', 'Audible', 'Marcas', 'brands', 'f373', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'audio-description', 'fas fa-audio-description', 'Audio Description', 'Otros', 'solid', 'f29e', 'blind, narration, video, visual', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'autoprefixer', 'fab fa-autoprefixer', 'Autoprefixer', 'Marcas', 'brands', 'f41c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'avianex', 'fab fa-avianex', 'avianex', 'Marcas', 'brands', 'f374', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'aviato', 'fab fa-aviato', 'Aviato', 'Marcas', 'brands', 'f421', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'award', 'fas fa-award', 'Award', 'Otros', 'solid', 'f559', 'honor, praise, prize, recognition, ribbon, trophy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'aws', 'fab fa-aws', 'Amazon Web Services (AWS)', 'Marcas', 'brands', 'f375', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'baby', 'fas fa-baby', 'Baby', 'Otros', 'solid', 'f77c', 'child, diaper, doll, human, infant, kid, offspring, person, sprout', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'baby-carriage', 'fas fa-baby-carriage', 'Baby Carriage', 'Otros', 'solid', 'f77d', 'buggy, carrier, infant, push, stroller, transportation, walk, wheels', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'backspace', 'fas fa-backspace', 'Backspace', 'Otros', 'solid', 'f55a', 'command, delete, erase, keyboard, undo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'backward', 'fas fa-backward', 'backward', 'Otros', 'solid', 'f04a', 'previous, rewind', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'bacon', 'fas fa-bacon', 'Bacon', 'Otros', 'solid', 'f7e5', 'blt, breakfast, ham, lard, meat, pancetta, pork, rasher', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'balance-scale', 'fas fa-balance-scale', 'Balance Scale', 'Otros', 'solid', 'f24e', 'balanced, justice, legal, measure, weight', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'balance-scale-left', 'fas fa-balance-scale-left', 'Balance Scale (Left-Weighted)', 'Otros', 'solid', 'f515', 'justice, legal, measure, unbalanced, weight', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'balance-scale-right', 'fas fa-balance-scale-right', 'Balance Scale (Right-Weighted)', 'Otros', 'solid', 'f516', 'justice, legal, measure, unbalanced, weight', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'ban', 'fas fa-ban', 'ban', 'Otros', 'solid', 'f05e', 'abort, ban, block, cancel, delete, hide, prohibit, remove, stop, trash', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'band-aid', 'fas fa-band-aid', 'Band-Aid', 'Otros', 'solid', 'f462', 'bandage, boo boo, first aid, ouch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'bandcamp', 'fab fa-bandcamp', 'Bandcamp', 'Marcas', 'brands', 'f2d5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'barcode', 'fas fa-barcode', 'barcode', 'Otros', 'solid', 'f02a', 'info, laser, price, scan, upc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'bars', 'fas fa-bars', 'Bars', 'Otros', 'solid', 'f0c9', 'checklist, drag, hamburger, list, menu, nav, navigation, ol, reorder, settings, todo, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'baseball-ball', 'fas fa-baseball-ball', 'Baseball Ball', 'Otros', 'solid', 'f433', 'foul, hardball, league, leather, mlb, softball, sport', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'basketball-ball', 'fas fa-basketball-ball', 'Basketball Ball', 'Otros', 'solid', 'f434', 'dribble, dunk, hoop, nba', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'bath', 'fas fa-bath', 'Bath', 'Otros', 'solid', 'f2cd', 'clean, shower, tub, wash', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'battery-empty', 'fas fa-battery-empty', 'Battery Empty', 'Otros', 'solid', 'f244', 'charge, dead, power, status', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'battery-full', 'fas fa-battery-full', 'Battery Full', 'Otros', 'solid', 'f240', 'charge, power, status', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'battery-half', 'fas fa-battery-half', 'Battery 1/2 Full', 'Otros', 'solid', 'f242', 'charge, power, status', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'battery-quarter', 'fas fa-battery-quarter', 'Battery 1/4 Full', 'Otros', 'solid', 'f243', 'charge, low, power, status', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'battery-three-quarters', 'fas fa-battery-three-quarters', 'Battery 3/4 Full', 'Otros', 'solid', 'f241', 'charge, power, status', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'battle-net', 'fab fa-battle-net', 'Battle.net', 'Marcas', 'brands', 'f835', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'bed', 'fas fa-bed', 'Bed', 'Otros', 'solid', 'f236', 'lodging, rest, sleep, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'beer', 'fas fa-beer', 'beer', 'Otros', 'solid', 'f0fc', 'alcohol, ale, bar, beverage, brewery, drink, lager, liquor, mug, stein', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'behance', 'fab fa-behance', 'Behance', 'Marcas', 'brands', 'f1b4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 'behance-square', 'fab fa-behance-square', 'Behance Square', 'Marcas', 'brands', 'f1b5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'bell', 'fas fa-bell', 'bell', 'Otros', 'solid', 'f0f3', 'alarm, alert, chime, notification, reminder', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'bell', 'far fa-bell', 'bell', 'Otros', 'regular', 'f0f3', 'alarm, alert, chime, notification, reminder', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'bell-slash', 'fas fa-bell-slash', 'Bell Slash', 'Otros', 'solid', 'f1f6', 'alert, cancel, disabled, notification, off, reminder', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'bell-slash', 'far fa-bell-slash', 'Bell Slash', 'Otros', 'regular', 'f1f6', 'alert, cancel, disabled, notification, off, reminder', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'bezier-curve', 'fas fa-bezier-curve', 'Bezier Curve', 'Otros', 'solid', 'f55b', 'curves, illustrator, lines, path, vector', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'bible', 'fas fa-bible', 'Bible', 'Otros', 'solid', 'f647', 'book, catholicism, christianity, god, holy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'bicycle', 'fas fa-bicycle', 'Bicycle', 'Otros', 'solid', 'f206', 'bike, gears, pedal, transportation, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'biking', 'fas fa-biking', 'Biking', 'Otros', 'solid', 'f84a', 'bicycle, bike, cycle, cycling, ride, wheel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'bimobject', 'fab fa-bimobject', 'BIMobject', 'Marcas', 'brands', 'f378', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'binoculars', 'fas fa-binoculars', 'Binoculars', 'Otros', 'solid', 'f1e5', 'glasses, magnify, scenic, spyglass, view', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'biohazard', 'fas fa-biohazard', 'Biohazard', 'Medicina', 'solid', 'f780', 'danger, dangerous, hazmat, medical, radioactive, toxic, waste, zombie', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 'birthday-cake', 'fas fa-birthday-cake', 'Birthday Cake', 'Otros', 'solid', 'f1fd', 'anniversary, bakery, candles, celebration, dessert, frosting, holiday, party, pastry', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'bitbucket', 'fab fa-bitbucket', 'Bitbucket', 'Marcas', 'brands', 'f171', 'atlassian, bitbucket-square, git', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'bitcoin', 'fab fa-bitcoin', 'Bitcoin', 'Marcas', 'brands', 'f379', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'bity', 'fab fa-bity', 'Bity', 'Marcas', 'brands', 'f37a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'black-tie', 'fab fa-black-tie', 'Font Awesome Black Tie', 'Marcas', 'brands', 'f27e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'blackberry', 'fab fa-blackberry', 'BlackBerry', 'Marcas', 'brands', 'f37b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 'blender', 'fas fa-blender', 'Blender', 'Otros', 'solid', 'f517', 'cocktail, milkshake, mixer, puree, smoothie', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 'blender-phone', 'fas fa-blender-phone', 'Blender Phone', 'Otros', 'solid', 'f6b6', 'appliance, cocktail, communication, fantasy, milkshake, mixer, puree, silly, smoothie', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 'blind', 'fas fa-blind', 'Blind', 'Otros', 'solid', 'f29d', 'cane, disability, person, sight', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 'blog', 'fas fa-blog', 'Blog', 'Otros', 'solid', 'f781', 'journal, log, online, personal, post, web 2.0, wordpress, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 'blogger', 'fab fa-blogger', 'Blogger', 'Marcas', 'brands', 'f37c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 'blogger-b', 'fab fa-blogger-b', 'Blogger B', 'Marcas', 'brands', 'f37d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 'bluetooth', 'fab fa-bluetooth', 'Bluetooth', 'Marcas', 'brands', 'f293', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 'bluetooth-b', 'fab fa-bluetooth-b', 'Bluetooth', 'Marcas', 'brands', 'f294', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 'bold', 'fas fa-bold', 'bold', 'Editores', 'solid', 'f032', 'emphasis, format, text', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 'bolt', 'fas fa-bolt', 'Lightning Bolt', 'Otros', 'solid', 'f0e7', 'electricity, lightning, weather, zap', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 'bomb', 'fas fa-bomb', 'Bomb', 'Otros', 'solid', 'f1e2', 'error, explode, fuse, grenade, warning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 'bone', 'fas fa-bone', 'Bone', 'Otros', 'solid', 'f5d7', 'calcium, dog, skeletal, skeleton, tibia', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 'bong', 'fas fa-bong', 'Bong', 'Otros', 'solid', 'f55c', 'aparatus, cannabis, marijuana, pipe, smoke, smoking', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 'book', 'fas fa-book', 'book', 'Otros', 'solid', 'f02d', 'diary, documentation, journal, library, read', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 'book-dead', 'fas fa-book-dead', 'Book of the Dead', 'Otros', 'solid', 'f6b7', 'Dungeons & Dragons, crossbones, d&d, dark arts, death, dnd, documentation, evil, fantasy, halloween, holiday, necronomicon, read, skull, spell', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 'book-medical', 'fas fa-book-medical', 'Medical Book', 'Otros', 'solid', 'f7e6', 'diary, documentation, health, history, journal, library, read, record', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 'book-open', 'fas fa-book-open', 'Book Open', 'Otros', 'solid', 'f518', 'flyer, library, notebook, open book, pamphlet, reading', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 'book-reader', 'fas fa-book-reader', 'Book Reader', 'Otros', 'solid', 'f5da', 'flyer, library, notebook, open book, pamphlet, reading', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 'bookmark', 'fas fa-bookmark', 'bookmark', 'Otros', 'solid', 'f02e', 'favorite, marker, read, remember, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 'bookmark', 'far fa-bookmark', 'bookmark', 'Otros', 'regular', 'f02e', 'favorite, marker, read, remember, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 'bootstrap', 'fab fa-bootstrap', 'Bootstrap', 'Marcas', 'brands', 'f836', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 'border-all', 'fas fa-border-all', 'Border All', 'Otros', 'solid', 'f84c', 'cell, grid, outline, stroke, table', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 'border-none', 'fas fa-border-none', 'Border None', 'Otros', 'solid', 'f850', 'cell, grid, outline, stroke, table', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 'border-style', 'fas fa-border-style', 'Border Style', 'Otros', 'solid', 'f853', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 'bowling-ball', 'fas fa-bowling-ball', 'Bowling Ball', 'Otros', 'solid', 'f436', 'alley, candlepin, gutter, lane, strike, tenpin', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 'box', 'fas fa-box', 'Box', 'Otros', 'solid', 'f466', 'archive, container, package, storage', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 'box-open', 'fas fa-box-open', 'Box Open', 'Otros', 'solid', 'f49e', 'archive, container, package, storage, unpack', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 'boxes', 'fas fa-boxes', 'Boxes', 'Otros', 'solid', 'f468', 'archives, inventory, storage, warehouse', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 'braille', 'fas fa-braille', 'Braille', 'Otros', 'solid', 'f2a1', 'alphabet, blind, dots, raised, vision', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 'brain', 'fas fa-brain', 'Brain', 'Otros', 'solid', 'f5dc', 'cerebellum, gray matter, intellect, medulla oblongata, mind, noodle, wit', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 'bread-slice', 'fas fa-bread-slice', 'Bread Slice', 'Otros', 'solid', 'f7ec', 'bake, bakery, baking, dough, flour, gluten, grain, sandwich, sourdough, toast, wheat, yeast', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 'briefcase', 'fas fa-briefcase', 'Briefcase', 'Otros', 'solid', 'f0b1', 'bag, business, luggage, office, work', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 'briefcase-medical', 'fas fa-briefcase-medical', 'Medical Briefcase', 'Otros', 'solid', 'f469', 'doctor, emt, first aid, health', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 'broadcast-tower', 'fas fa-broadcast-tower', 'Broadcast Tower', 'Otros', 'solid', 'f519', 'airwaves, antenna, radio, reception, waves', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 'broom', 'fas fa-broom', 'Broom', 'Otros', 'solid', 'f51a', 'clean, firebolt, fly, halloween, nimbus 2000, quidditch, sweep, witch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 'brush', 'fas fa-brush', 'Brush', 'Otros', 'solid', 'f55d', 'art, bristles, color, handle, paint', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 'btc', 'fab fa-btc', 'BTC', 'Marcas', 'brands', 'f15a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 'buffer', 'fab fa-buffer', 'Buffer', 'Marcas', 'brands', 'f837', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 'bug', 'fas fa-bug', 'Bug', 'Otros', 'solid', 'f188', 'beetle, error, insect, report', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 'building', 'fas fa-building', 'Building', 'Otros', 'solid', 'f1ad', 'apartment, business, city, company, office, work', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 'building', 'far fa-building', 'Building', 'Otros', 'regular', 'f1ad', 'apartment, business, city, company, office, work', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 'bullhorn', 'fas fa-bullhorn', 'bullhorn', 'Otros', 'solid', 'f0a1', 'announcement, broadcast, louder, megaphone, share', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 'bullseye', 'fas fa-bullseye', 'Bullseye', 'Otros', 'solid', 'f140', 'archery, goal, objective, target', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 'burn', 'fas fa-burn', 'Burn', 'Otros', 'solid', 'f46a', 'caliente, energy, fire, flame, gas, heat, hot', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 'buromobelexperte', 'fab fa-buromobelexperte', 'B?rom?bel-Experte GmbH & Co. KG.', 'Marcas', 'brands', 'f37f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 'bus', 'fas fa-bus', 'Bus', 'Otros', 'solid', 'f207', 'public transportation, transportation, travel, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 'bus-alt', 'fas fa-bus-alt', 'Bus Alt', 'Otros', 'solid', 'f55e', 'mta, public transportation, transportation, travel, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 'business-time', 'fas fa-business-time', 'Business Time', 'Otros', 'solid', 'f64a', 'alarm, briefcase, business socks, clock, flight of the conchords, reminder, wednesday', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, 'buy-n-large', 'fab fa-buy-n-large', 'Buy n Large', 'Marcas', 'brands', 'f8a6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, 'buysellads', 'fab fa-buysellads', 'BuySellAds', 'Marcas', 'brands', 'f20d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, 'calculator', 'fas fa-calculator', 'Calculator', 'Otros', 'solid', 'f1ec', 'abacus, addition, arithmetic, counting, math, multiplication, subtraction', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, 'calendar', 'fas fa-calendar', 'Calendar', 'Otros', 'solid', 'f133', 'calendar-o, date, event, schedule, time, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, 'calendar', 'far fa-calendar', 'Calendar', 'Otros', 'regular', 'f133', 'calendar-o, date, event, schedule, time, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, 'calendar-alt', 'fas fa-calendar-alt', 'Alternate Calendar', 'Otros', 'solid', 'f073', 'calendar, date, event, schedule, time, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, 'calendar-alt', 'far fa-calendar-alt', 'Alternate Calendar', 'Otros', 'regular', 'f073', 'calendar, date, event, schedule, time, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, 'calendar-check', 'fas fa-calendar-check', 'Calendar Check', 'Otros', 'solid', 'f274', 'accept, agree, appointment, confirm, correct, date, done, event, ok, schedule, select, success, tick, time, todo, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, 'calendar-check', 'far fa-calendar-check', 'Calendar Check', 'Otros', 'regular', 'f274', 'accept, agree, appointment, confirm, correct, date, done, event, ok, schedule, select, success, tick, time, todo, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, 'calendar-day', 'fas fa-calendar-day', 'Calendar with Day Focus', 'Otros', 'solid', 'f783', 'date, detail, event, focus, schedule, single day, time, today, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, 'calendar-minus', 'fas fa-calendar-minus', 'Calendar Minus', 'Otros', 'solid', 'f272', 'calendar, date, delete, event, negative, remove, schedule, time, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, 'calendar-minus', 'far fa-calendar-minus', 'Calendar Minus', 'Otros', 'regular', 'f272', 'calendar, date, delete, event, negative, remove, schedule, time, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, 'calendar-plus', 'fas fa-calendar-plus', 'Calendar Plus', 'Otros', 'solid', 'f271', 'add, calendar, create, date, event, new, positive, schedule, time, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, 'calendar-plus', 'far fa-calendar-plus', 'Calendar Plus', 'Otros', 'regular', 'f271', 'add, calendar, create, date, event, new, positive, schedule, time, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, 'calendar-times', 'fas fa-calendar-times', 'Calendar Times', 'Otros', 'solid', 'f273', 'archive, calendar, date, delete, event, remove, schedule, time, when, x', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, 'calendar-times', 'far fa-calendar-times', 'Calendar Times', 'Otros', 'regular', 'f273', 'archive, calendar, date, delete, event, remove, schedule, time, when, x', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, 'calendar-week', 'fas fa-calendar-week', 'Calendar with Week Focus', 'Otros', 'solid', 'f784', 'date, detail, event, focus, schedule, single week, time, today, when', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, 'camera', 'fas fa-camera', 'camera', 'Otros', 'solid', 'f030', 'image, lens, photo, picture, record, shutter, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, 'camera-retro', 'fas fa-camera-retro', 'Retro Camera', 'Otros', 'solid', 'f083', 'image, lens, photo, picture, record, shutter, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, 'campground', 'fas fa-campground', 'Campground', 'Otros', 'solid', 'f6bb', 'camping, fall, outdoors, teepee, tent, tipi', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, 'canadian-maple-leaf', 'fab fa-canadian-maple-leaf', 'Canadian Maple Leaf', 'Marcas', 'brands', 'f785', 'canada, flag, flora, nature, plant', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, 'candy-cane', 'fas fa-candy-cane', 'Candy Cane', 'Otros', 'solid', 'f786', 'candy, christmas, holiday, mint, peppermint, striped, xmas', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, 'cannabis', 'fas fa-cannabis', 'Cannabis', 'Otros', 'solid', 'f55f', 'bud, chronic, drugs, endica, endo, ganja, marijuana, mary jane, pot, reefer, sativa, spliff, weed, whacky-tabacky', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, 'capsules', 'fas fa-capsules', 'Capsules', 'Otros', 'solid', 'f46b', 'drugs, medicine, pills, prescription', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, 'car', 'fas fa-car', 'Car', 'Otros', 'solid', 'f1b9', 'auto, automobile, sedan, transportation, travel, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, 'car-alt', 'fas fa-car-alt', 'Alternate Car', 'Otros', 'solid', 'f5de', 'auto, automobile, sedan, transportation, travel, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, 'car-battery', 'fas fa-car-battery', 'Car Battery', 'Otros', 'solid', 'f5df', 'auto, electric, mechanic, power', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, 'car-crash', 'fas fa-car-crash', 'Car Crash', 'Otros', 'solid', 'f5e1', 'accident, auto, automobile, insurance, sedan, transportation, vehicle, wreck', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, 'car-side', 'fas fa-car-side', 'Car Side', 'Otros', 'solid', 'f5e4', 'auto, automobile, sedan, transportation, travel, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, 'caret-down', 'fas fa-caret-down', 'Caret Down', 'Otros', 'solid', 'f0d7', 'arrow, dropdown, expand, menu, more, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, 'caret-left', 'fas fa-caret-left', 'Caret Left', 'Otros', 'solid', 'f0d9', 'arrow, back, previous, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, 'caret-right', 'fas fa-caret-right', 'Caret Right', 'Otros', 'solid', 'f0da', 'arrow, forward, next, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, 'caret-square-down', 'fas fa-caret-square-down', 'Caret Square Down', 'Otros', 'solid', 'f150', 'arrow, caret-square-o-down, dropdown, expand, menu, more, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(211, 'caret-square-down', 'far fa-caret-square-down', 'Caret Square Down', 'Otros', 'regular', 'f150', 'arrow, caret-square-o-down, dropdown, expand, menu, more, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(212, 'caret-square-left', 'fas fa-caret-square-left', 'Caret Square Left', 'Otros', 'solid', 'f191', 'arrow, back, caret-square-o-left, previous, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(213, 'caret-square-left', 'far fa-caret-square-left', 'Caret Square Left', 'Otros', 'regular', 'f191', 'arrow, back, caret-square-o-left, previous, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(214, 'caret-square-right', 'fas fa-caret-square-right', 'Caret Square Right', 'Otros', 'solid', 'f152', 'arrow, caret-square-o-right, forward, next, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(215, 'caret-square-right', 'far fa-caret-square-right', 'Caret Square Right', 'Otros', 'regular', 'f152', 'arrow, caret-square-o-right, forward, next, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(216, 'caret-square-up', 'fas fa-caret-square-up', 'Caret Square Up', 'Otros', 'solid', 'f151', 'arrow, caret-square-o-up, collapse, triangle, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(217, 'caret-square-up', 'far fa-caret-square-up', 'Caret Square Up', 'Otros', 'regular', 'f151', 'arrow, caret-square-o-up, collapse, triangle, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, 'caret-up', 'fas fa-caret-up', 'Caret Up', 'Otros', 'solid', 'f0d8', 'arrow, collapse, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, 'carrot', 'fas fa-carrot', 'Carrot', 'Otros', 'solid', 'f787', 'bugs bunny, orange, vegan, vegetable', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, 'cart-arrow-down', 'fas fa-cart-arrow-down', 'Shopping Cart Arrow Down', 'Otros', 'solid', 'f218', 'download, save, shopping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, 'cart-plus', 'fas fa-cart-plus', 'Add to Shopping Cart', 'Otros', 'solid', 'f217', 'add, create, new, positive, shopping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, 'cash-register', 'fas fa-cash-register', 'Cash Register', 'Otros', 'solid', 'f788', 'buy, cha-ching, change, checkout, commerce, leaerboard, machine, pay, payment, purchase, store', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, 'cat', 'fas fa-cat', 'Cat', 'Otros', 'solid', 'f6be', 'feline, halloween, holiday, kitten, kitty, meow, pet', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, 'cc-amazon-pay', 'fab fa-cc-amazon-pay', 'Amazon Pay Credit Card', 'Marcas', 'brands', 'f42d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, 'cc-amex', 'fab fa-cc-amex', 'American Express Credit Card', 'Marcas', 'brands', 'f1f3', 'amex', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, 'cc-apple-pay', 'fab fa-cc-apple-pay', 'Apple Pay Credit Card', 'Marcas', 'brands', 'f416', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, 'cc-diners-club', 'fab fa-cc-diners-club', 'Diner\'s Club Credit Card', 'Marcas', 'brands', 'f24c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(228, 'cc-discover', 'fab fa-cc-discover', 'Discover Credit Card', 'Marcas', 'brands', 'f1f2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(229, 'cc-jcb', 'fab fa-cc-jcb', 'JCB Credit Card', 'Marcas', 'brands', 'f24b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(230, 'cc-mastercard', 'fab fa-cc-mastercard', 'MasterCard Credit Card', 'Marcas', 'brands', 'f1f1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(231, 'cc-paypal', 'fab fa-cc-paypal', 'Paypal Credit Card', 'Marcas', 'brands', 'f1f4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(232, 'cc-stripe', 'fab fa-cc-stripe', 'Stripe Credit Card', 'Marcas', 'brands', 'f1f5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, 'cc-visa', 'fab fa-cc-visa', 'Visa Credit Card', 'Marcas', 'brands', 'f1f0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, 'centercode', 'fab fa-centercode', 'Centercode', 'Marcas', 'brands', 'f380', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, 'centos', 'fab fa-centos', 'Centos', 'Marcas', 'brands', 'f789', 'linux, operating system, os', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, 'certificate', 'fas fa-certificate', 'certificate', 'Otros', 'solid', 'f0a3', 'badge, star, verified', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, 'chair', 'fas fa-chair', 'Chair', 'Otros', 'solid', 'f6c0', 'furniture, seat, sit', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(238, 'chalkboard', 'fas fa-chalkboard', 'Chalkboard', 'Otros', 'solid', 'f51b', 'blackboard, learning, school, teaching, whiteboard, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(239, 'chalkboard-teacher', 'fas fa-chalkboard-teacher', 'Chalkboard Teacher', 'Otros', 'solid', 'f51c', 'blackboard, instructor, learning, professor, school, whiteboard, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, 'charging-station', 'fas fa-charging-station', 'Charging Station', 'Otros', 'solid', 'f5e7', 'electric, ev, tesla, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, 'chart-area', 'fas fa-chart-area', 'Area Chart', 'Otros', 'solid', 'f1fe', 'analytics, area, chart, graph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, 'chart-bar', 'fas fa-chart-bar', 'Bar Chart', 'Otros', 'solid', 'f080', 'analytics, bar, chart, graph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, 'chart-bar', 'far fa-chart-bar', 'Bar Chart', 'Otros', 'regular', 'f080', 'analytics, bar, chart, graph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(244, 'chart-line', 'fas fa-chart-line', 'Line Chart', 'Otros', 'solid', 'f201', 'activity, analytics, chart, dashboard, gain, graph, increase, line', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(245, 'chart-pie', 'fas fa-chart-pie', 'Pie Chart', 'Otros', 'solid', 'f200', 'analytics, chart, diagram, graph, pie', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(246, 'check', 'fas fa-check', 'Check', 'Otros', 'solid', 'f00c', 'accept, agree, checkmark, confirm, correct, done, notice, notification, notify, ok, select, success, tick, todo, yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(247, 'check-circle', 'fas fa-check-circle', 'Check Circle', 'Otros', 'solid', 'f058', 'accept, agree, confirm, correct, done, ok, select, success, tick, todo, yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(248, 'check-circle', 'far fa-check-circle', 'Check Circle', 'Otros', 'regular', 'f058', 'accept, agree, confirm, correct, done, ok, select, success, tick, todo, yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(249, 'check-double', 'fas fa-check-double', 'Double Check', 'Otros', 'solid', 'f560', 'accept, agree, checkmark, confirm, correct, done, notice, notification, notify, ok, select, success, tick, todo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(250, 'check-square', 'fas fa-check-square', 'Check Square', 'Otros', 'solid', 'f14a', 'accept, agree, checkmark, confirm, correct, done, ok, select, success, tick, todo, yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(251, 'check-square', 'far fa-check-square', 'Check Square', 'Otros', 'regular', 'f14a', 'accept, agree, checkmark, confirm, correct, done, ok, select, success, tick, todo, yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(252, 'cheese', 'fas fa-cheese', 'Cheese', 'Otros', 'solid', 'f7ef', 'cheddar, curd, gouda, melt, parmesan, sandwich, swiss, wedge', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(253, 'chess', 'fas fa-chess', 'Chess', 'Otros', 'solid', 'f439', 'board, castle, checkmate, game, king, rook, strategy, tournament', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(254, 'chess-bishop', 'fas fa-chess-bishop', 'Chess Bishop', 'Otros', 'solid', 'f43a', 'board, checkmate, game, strategy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(255, 'chess-board', 'fas fa-chess-board', 'Chess Board', 'Otros', 'solid', 'f43c', 'board, checkmate, game, strategy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(256, 'chess-king', 'fas fa-chess-king', 'Chess King', 'Otros', 'solid', 'f43f', 'board, checkmate, game, strategy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(257, 'chess-knight', 'fas fa-chess-knight', 'Chess Knight', 'Otros', 'solid', 'f441', 'board, checkmate, game, horse, strategy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(258, 'chess-pawn', 'fas fa-chess-pawn', 'Chess Pawn', 'Otros', 'solid', 'f443', 'board, checkmate, game, strategy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(259, 'chess-queen', 'fas fa-chess-queen', 'Chess Queen', 'Otros', 'solid', 'f445', 'board, checkmate, game, strategy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(260, 'chess-rook', 'fas fa-chess-rook', 'Chess Rook', 'Otros', 'solid', 'f447', 'board, castle, checkmate, game, strategy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(261, 'chevron-circle-down', 'fas fa-chevron-circle-down', 'Chevron Circle Down', 'Otros', 'solid', 'f13a', 'arrow, download, dropdown, menu, more', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(262, 'chevron-circle-left', 'fas fa-chevron-circle-left', 'Chevron Circle Left', 'Otros', 'solid', 'f137', 'arrow, back, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(263, 'chevron-circle-right', 'fas fa-chevron-circle-right', 'Chevron Circle Right', 'Otros', 'solid', 'f138', 'arrow, forward, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(264, 'chevron-circle-up', 'fas fa-chevron-circle-up', 'Chevron Circle Up', 'Otros', 'solid', 'f139', 'arrow, collapse, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(265, 'chevron-down', 'fas fa-chevron-down', 'chevron-down', 'Otros', 'solid', 'f078', 'arrow, download, expand', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(266, 'chevron-left', 'fas fa-chevron-left', 'chevron-left', 'Otros', 'solid', 'f053', 'arrow, back, bracket, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(267, 'chevron-right', 'fas fa-chevron-right', 'chevron-right', 'Otros', 'solid', 'f054', 'arrow, bracket, forward, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(268, 'chevron-up', 'fas fa-chevron-up', 'chevron-up', 'Otros', 'solid', 'f077', 'arrow, collapse, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(269, 'child', 'fas fa-child', 'Child', 'Otros', 'solid', 'f1ae', 'boy, girl, kid, toddler, young', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(270, 'chrome', 'fab fa-chrome', 'Chrome', 'Marcas', 'brands', 'f268', 'browser', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(271, 'chromecast', 'fab fa-chromecast', 'Chromecast', 'Marcas', 'brands', 'f838', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(272, 'church', 'fas fa-church', 'Church', 'Otros', 'solid', 'f51d', 'building, cathedral, chapel, community, religion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(273, 'circle', 'fas fa-circle', 'Circle', 'Otros', 'solid', 'f111', 'circle-thin, diameter, dot, ellipse, notification, round', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(274, 'circle', 'far fa-circle', 'Circle', 'Otros', 'regular', 'f111', 'circle-thin, diameter, dot, ellipse, notification, round', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(275, 'circle-notch', 'fas fa-circle-notch', 'Circle Notched', 'Otros', 'solid', 'f1ce', 'circle-o-notch, diameter, dot, ellipse, round, spinner', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(276, 'city', 'fas fa-city', 'City', 'Otros', 'solid', 'f64f', 'buildings, busy, skyscrapers, urban, windows', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(277, 'clinic-medical', 'fas fa-clinic-medical', 'Medical Clinic', 'Otros', 'solid', 'f7f2', 'doctor, general practitioner, hospital, infirmary, medicine, office, outpatient', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(278, 'clipboard', 'fas fa-clipboard', 'Clipboard', 'Otros', 'solid', 'f328', 'copy, notes, paste, record', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(279, 'clipboard', 'far fa-clipboard', 'Clipboard', 'Otros', 'regular', 'f328', 'copy, notes, paste, record', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(280, 'clipboard-check', 'fas fa-clipboard-check', 'Clipboard with Check', 'Otros', 'solid', 'f46c', 'accept, agree, confirm, done, ok, select, success, tick, todo, yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(281, 'clipboard-list', 'fas fa-clipboard-list', 'Clipboard List', 'Otros', 'solid', 'f46d', 'checklist, completed, done, finished, intinerary, ol, schedule, tick, todo, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(282, 'clock', 'fas fa-clock', 'Clock', 'Otros', 'solid', 'f017', 'date, late, schedule, time, timer, timestamp, watch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(283, 'clock', 'far fa-clock', 'Clock', 'Otros', 'regular', 'f017', 'date, late, schedule, time, timer, timestamp, watch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(284, 'clone', 'fas fa-clone', 'Clone', 'Otros', 'solid', 'f24d', 'arrange, copy, duplicate, paste', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(285, 'clone', 'far fa-clone', 'Clone', 'Otros', 'regular', 'f24d', 'arrange, copy, duplicate, paste', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(286, 'closed-captioning', 'fas fa-closed-captioning', 'Closed Captioning', 'Otros', 'solid', 'f20a', 'cc, deaf, hearing, subtitle, subtitling, text, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(287, 'closed-captioning', 'far fa-closed-captioning', 'Closed Captioning', 'Otros', 'regular', 'f20a', 'cc, deaf, hearing, subtitle, subtitling, text, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(288, 'cloud', 'fas fa-cloud', 'Cloud', 'Otros', 'solid', 'f0c2', 'atmosphere, fog, overcast, save, upload, weather', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(289, 'cloud-download-alt', 'fas fa-cloud-download-alt', 'Alternate Cloud Download', 'Otros', 'solid', 'f381', 'download, export, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(290, 'cloud-meatball', 'fas fa-cloud-meatball', 'Cloud with (a chance of) Meatball', 'Otros', 'solid', 'f73b', 'FLDSMDFR, food, spaghetti, storm', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(291, 'cloud-moon', 'fas fa-cloud-moon', 'Cloud with Moon', 'Otros', 'solid', 'f6c3', 'crescent, evening, lunar, night, partly cloudy, sky', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(292, 'cloud-moon-rain', 'fas fa-cloud-moon-rain', 'Cloud with Moon and Rain', 'Otros', 'solid', 'f73c', 'crescent, evening, lunar, night, partly cloudy, precipitation, rain, sky, storm', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(293, 'cloud-rain', 'fas fa-cloud-rain', 'Cloud with Rain', 'Otros', 'solid', 'f73d', 'precipitation, rain, sky, storm', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(294, 'cloud-showers-heavy', 'fas fa-cloud-showers-heavy', 'Cloud with Heavy Showers', 'Otros', 'solid', 'f740', 'precipitation, rain, sky, storm', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sara_iconos` (`id`, `Icono`, `IconoFull`, `IconoLabel`, `Categoria`, `Estilo`, `Unicode`, `PalabrasClave`, `created_at`, `updated_at`) VALUES
(295, 'cloud-sun', 'fas fa-cloud-sun', 'Cloud with Sun', 'Otros', 'solid', 'f6c4', 'clear, day, daytime, fall, outdoors, overcast, partly cloudy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(296, 'cloud-sun-rain', 'fas fa-cloud-sun-rain', 'Cloud with Sun and Rain', 'Otros', 'solid', 'f743', 'day, overcast, precipitation, storm, summer, sunshower', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(297, 'cloud-upload-alt', 'fas fa-cloud-upload-alt', 'Alternate Cloud Upload', 'Otros', 'solid', 'f382', 'cloud-upload, import, save, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(298, 'cloudscale', 'fab fa-cloudscale', 'cloudscale.ch', 'Marcas', 'brands', 'f383', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(299, 'cloudsmith', 'fab fa-cloudsmith', 'Cloudsmith', 'Marcas', 'brands', 'f384', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(300, 'cloudversify', 'fab fa-cloudversify', 'cloudversify', 'Marcas', 'brands', 'f385', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(301, 'cocktail', 'fas fa-cocktail', 'Cocktail', 'Otros', 'solid', 'f561', 'alcohol, beverage, drink, gin, glass, margarita, martini, vodka', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(302, 'code', 'fas fa-code', 'Code', 'Otros', 'solid', 'f121', 'brackets, code, development, html', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(303, 'code-branch', 'fas fa-code-branch', 'Code Branch', 'Otros', 'solid', 'f126', 'branch, code-fork, fork, git, github, rebase, svn, vcs, version', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(304, 'codepen', 'fab fa-codepen', 'Codepen', 'Marcas', 'brands', 'f1cb', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(305, 'codiepie', 'fab fa-codiepie', 'Codie Pie', 'Marcas', 'brands', 'f284', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(306, 'coffee', 'fas fa-coffee', 'Coffee', 'Otros', 'solid', 'f0f4', 'beverage, breakfast, cafe, drink, fall, morning, mug, seasonal, tea', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(307, 'cog', 'fas fa-cog', 'cog', 'Otros', 'solid', 'f013', 'gear, mechanical, settings, sprocket, wheel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(308, 'cogs', 'fas fa-cogs', 'cogs', 'Otros', 'solid', 'f085', 'gears, mechanical, settings, sprocket, wheel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(309, 'coins', 'fas fa-coins', 'Coins', 'Otros', 'solid', 'f51e', 'currency, dime, financial, gold, money, penny', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(310, 'columns', 'fas fa-columns', 'Columns', 'Otros', 'solid', 'f0db', 'browser, dashboard, organize, panes, split', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(311, 'comment', 'fas fa-comment', 'comment', 'Otros', 'solid', 'f075', 'bubble, chat, commenting, conversation, feedback, message, note, notification, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(312, 'comment', 'far fa-comment', 'comment', 'Otros', 'regular', 'f075', 'bubble, chat, commenting, conversation, feedback, message, note, notification, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(313, 'comment-alt', 'fas fa-comment-alt', 'Alternate Comment', 'Otros', 'solid', 'f27a', 'bubble, chat, commenting, conversation, feedback, message, note, notification, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(314, 'comment-alt', 'far fa-comment-alt', 'Alternate Comment', 'Otros', 'regular', 'f27a', 'bubble, chat, commenting, conversation, feedback, message, note, notification, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(315, 'comment-dollar', 'fas fa-comment-dollar', 'Comment Dollar', 'Otros', 'solid', 'f651', 'bubble, chat, commenting, conversation, feedback, message, money, note, notification, pay, sms, speech, spend, texting, transfer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(316, 'comment-dots', 'fas fa-comment-dots', 'Comment Dots', 'Otros', 'solid', 'f4ad', 'bubble, chat, commenting, conversation, feedback, message, more, note, notification, reply, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(317, 'comment-dots', 'far fa-comment-dots', 'Comment Dots', 'Otros', 'regular', 'f4ad', 'bubble, chat, commenting, conversation, feedback, message, more, note, notification, reply, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(318, 'comment-medical', 'fas fa-comment-medical', 'Alternate Medical Chat', 'Otros', 'solid', 'f7f5', 'advice, bubble, chat, commenting, conversation, diagnose, feedback, message, note, notification, prescription, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(319, 'comment-slash', 'fas fa-comment-slash', 'Comment Slash', 'Otros', 'solid', 'f4b3', 'bubble, cancel, chat, commenting, conversation, feedback, message, mute, note, notification, quiet, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(320, 'comments', 'fas fa-comments', 'comments', 'Otros', 'solid', 'f086', 'bubble, chat, commenting, conversation, feedback, message, note, notification, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(321, 'comments', 'far fa-comments', 'comments', 'Otros', 'regular', 'f086', 'bubble, chat, commenting, conversation, feedback, message, note, notification, sms, speech, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(322, 'comments-dollar', 'fas fa-comments-dollar', 'Comments Dollar', 'Otros', 'solid', 'f653', 'bubble, chat, commenting, conversation, feedback, message, money, note, notification, pay, sms, speech, spend, texting, transfer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(323, 'compact-disc', 'fas fa-compact-disc', 'Compact Disc', 'Otros', 'solid', 'f51f', 'album, bluray, cd, disc, dvd, media, movie, music, record, video, vinyl', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(324, 'compass', 'fas fa-compass', 'Compass', 'Otros', 'solid', 'f14e', 'directions, directory, location, menu, navigation, safari, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(325, 'compass', 'far fa-compass', 'Compass', 'Otros', 'regular', 'f14e', 'directions, directory, location, menu, navigation, safari, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(326, 'compress', 'fas fa-compress', 'Compress', 'Otros', 'solid', 'f066', 'collapse, fullscreen, minimize, move, resize, shrink, smaller', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(327, 'compress-arrows-alt', 'fas fa-compress-arrows-alt', 'Alternate Compress Arrows', 'Otros', 'solid', 'f78c', 'collapse, fullscreen, minimize, move, resize, shrink, smaller', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(328, 'concierge-bell', 'fas fa-concierge-bell', 'Concierge Bell', 'Otros', 'solid', 'f562', 'attention, hotel, receptionist, service, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(329, 'confluence', 'fab fa-confluence', 'Confluence', 'Marcas', 'brands', 'f78d', 'atlassian', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(330, 'connectdevelop', 'fab fa-connectdevelop', 'Connect Develop', 'Marcas', 'brands', 'f20e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(331, 'contao', 'fab fa-contao', 'Contao', 'Marcas', 'brands', 'f26d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(332, 'cookie', 'fas fa-cookie', 'Cookie', 'Otros', 'solid', 'f563', 'baked good, chips, chocolate, eat, snack, sweet, treat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(333, 'cookie-bite', 'fas fa-cookie-bite', 'Cookie Bite', 'Otros', 'solid', 'f564', 'baked good, bitten, chips, chocolate, eat, snack, sweet, treat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(334, 'copy', 'fas fa-copy', 'Copy', 'Otros', 'solid', 'f0c5', 'clone, duplicate, file, files-o, paper, paste', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(335, 'copy', 'far fa-copy', 'Copy', 'Otros', 'regular', 'f0c5', 'clone, duplicate, file, files-o, paper, paste', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(336, 'copyright', 'fas fa-copyright', 'Copyright', 'Otros', 'solid', 'f1f9', 'brand, mark, register, trademark', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(337, 'copyright', 'far fa-copyright', 'Copyright', 'Otros', 'regular', 'f1f9', 'brand, mark, register, trademark', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(338, 'cotton-bureau', 'fab fa-cotton-bureau', 'Cotton Bureau', 'Marcas', 'brands', 'f89e', 'clothing, t-shirts, tshirts', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(339, 'couch', 'fas fa-couch', 'Couch', 'Otros', 'solid', 'f4b8', 'chair, cushion, furniture, relax, sofa', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(340, 'cpanel', 'fab fa-cpanel', 'cPanel', 'Marcas', 'brands', 'f388', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(341, 'creative-commons', 'fab fa-creative-commons', 'Creative Commons', 'Marcas', 'brands', 'f25e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(342, 'creative-commons-by', 'fab fa-creative-commons-by', 'Creative Commons Attribution', 'Marcas', 'brands', 'f4e7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(343, 'creative-commons-nc', 'fab fa-creative-commons-nc', 'Creative Commons Noncommercial', 'Marcas', 'brands', 'f4e8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(344, 'creative-commons-nc-eu', 'fab fa-creative-commons-nc-eu', 'Creative Commons Noncommercial (Euro Sign)', 'Marcas', 'brands', 'f4e9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(345, 'creative-commons-nc-jp', 'fab fa-creative-commons-nc-jp', 'Creative Commons Noncommercial (Yen Sign)', 'Marcas', 'brands', 'f4ea', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(346, 'creative-commons-nd', 'fab fa-creative-commons-nd', 'Creative Commons No Derivative Works', 'Marcas', 'brands', 'f4eb', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(347, 'creative-commons-pd', 'fab fa-creative-commons-pd', 'Creative Commons Public Domain', 'Marcas', 'brands', 'f4ec', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(348, 'creative-commons-pd-alt', 'fab fa-creative-commons-pd-alt', 'Alternate Creative Commons Public Domain', 'Marcas', 'brands', 'f4ed', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(349, 'creative-commons-remix', 'fab fa-creative-commons-remix', 'Creative Commons Remix', 'Marcas', 'brands', 'f4ee', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(350, 'creative-commons-sa', 'fab fa-creative-commons-sa', 'Creative Commons Share Alike', 'Marcas', 'brands', 'f4ef', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(351, 'creative-commons-sampling', 'fab fa-creative-commons-sampling', 'Creative Commons Sampling', 'Marcas', 'brands', 'f4f0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(352, 'creative-commons-sampling-plus', 'fab fa-creative-commons-sampling-plus', 'Creative Commons Sampling +', 'Marcas', 'brands', 'f4f1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(353, 'creative-commons-share', 'fab fa-creative-commons-share', 'Creative Commons Share', 'Marcas', 'brands', 'f4f2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(354, 'creative-commons-zero', 'fab fa-creative-commons-zero', 'Creative Commons CC0', 'Marcas', 'brands', 'f4f3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(355, 'credit-card', 'fas fa-credit-card', 'Credit Card', 'Otros', 'solid', 'f09d', 'buy, checkout, credit-card-alt, debit, money, payment, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(356, 'credit-card', 'far fa-credit-card', 'Credit Card', 'Otros', 'regular', 'f09d', 'buy, checkout, credit-card-alt, debit, money, payment, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(357, 'critical-role', 'fab fa-critical-role', 'Critical Role', 'Marcas', 'brands', 'f6c9', 'Dungeons & Dragons, d&d, dnd, fantasy, game, gaming, tabletop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(358, 'crop', 'fas fa-crop', 'crop', 'Otros', 'solid', 'f125', 'design, frame, mask, resize, shrink', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(359, 'crop-alt', 'fas fa-crop-alt', 'Alternate Crop', 'Otros', 'solid', 'f565', 'design, frame, mask, resize, shrink', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(360, 'cross', 'fas fa-cross', 'Cross', 'Otros', 'solid', 'f654', 'catholicism, christianity, church, jesus', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(361, 'crosshairs', 'fas fa-crosshairs', 'Crosshairs', 'Otros', 'solid', 'f05b', 'aim, bullseye, gpd, picker, position', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(362, 'crow', 'fas fa-crow', 'Crow', 'Otros', 'solid', 'f520', 'bird, bullfrog, fauna, halloween, holiday, toad', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(363, 'crown', 'fas fa-crown', 'Crown', 'Otros', 'solid', 'f521', 'award, favorite, king, queen, royal, tiara', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(364, 'crutch', 'fas fa-crutch', 'Crutch', 'Otros', 'solid', 'f7f7', 'cane, injury, mobility, wheelchair', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(365, 'css3', 'fab fa-css3', 'CSS 3 Logo', 'Marcas', 'brands', 'f13c', 'code', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(366, 'css3-alt', 'fab fa-css3-alt', 'Alternate CSS3 Logo', 'Marcas', 'brands', 'f38b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(367, 'cube', 'fas fa-cube', 'Cube', 'Otros', 'solid', 'f1b2', '3d, block, dice, package, square, tesseract', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(368, 'cubes', 'fas fa-cubes', 'Cubes', 'Otros', 'solid', 'f1b3', '3d, block, dice, package, pyramid, square, stack, tesseract', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(369, 'cut', 'fas fa-cut', 'Cut', 'Otros', 'solid', 'f0c4', 'clip, scissors, snip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(370, 'cuttlefish', 'fab fa-cuttlefish', 'Cuttlefish', 'Marcas', 'brands', 'f38c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(371, 'd-and-d', 'fab fa-d-and-d', 'Dungeons & Dragons', 'Marcas', 'brands', 'f38d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(372, 'd-and-d-beyond', 'fab fa-d-and-d-beyond', 'D&D Beyond', 'Marcas', 'brands', 'f6ca', 'Dungeons & Dragons, d&d, dnd, fantasy, gaming, tabletop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(373, 'dashcube', 'fab fa-dashcube', 'DashCube', 'Marcas', 'brands', 'f210', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(374, 'database', 'fas fa-database', 'Database', 'Otros', 'solid', 'f1c0', 'computer, development, directory, memory, storage', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(375, 'deaf', 'fas fa-deaf', 'Deaf', 'Otros', 'solid', 'f2a4', 'ear, hearing, sign language', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(376, 'delicious', 'fab fa-delicious', 'Delicious', 'Marcas', 'brands', 'f1a5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(377, 'democrat', 'fas fa-democrat', 'Democrat', 'Otros', 'solid', 'f747', 'american, democratic party, donkey, election, left, left-wing, liberal, politics, usa', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(378, 'deploydog', 'fab fa-deploydog', 'deploy.dog', 'Marcas', 'brands', 'f38e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(379, 'deskpro', 'fab fa-deskpro', 'Deskpro', 'Marcas', 'brands', 'f38f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(380, 'desktop', 'fas fa-desktop', 'Desktop', 'Otros', 'solid', 'f108', 'computer, cpu, demo, desktop, device, imac, machine, monitor, pc, screen', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(381, 'dev', 'fab fa-dev', 'DEV', 'Marcas', 'brands', 'f6cc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(382, 'deviantart', 'fab fa-deviantart', 'deviantART', 'Marcas', 'brands', 'f1bd', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(383, 'dharmachakra', 'fas fa-dharmachakra', 'Dharmachakra', 'Otros', 'solid', 'f655', 'buddhism, buddhist, wheel of dharma', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(384, 'dhl', 'fab fa-dhl', 'DHL', 'Marcas', 'brands', 'f790', 'Dalsey, Hillblom and Lynn, german, package, shipping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(385, 'diagnoses', 'fas fa-diagnoses', 'Diagnoses', 'Otros', 'solid', 'f470', 'analyze, detect, diagnosis, examine, medicine', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(386, 'diaspora', 'fab fa-diaspora', 'Diaspora', 'Marcas', 'brands', 'f791', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(387, 'dice', 'fas fa-dice', 'Dice', 'Otros', 'solid', 'f522', 'chance, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(388, 'dice-d20', 'fas fa-dice-d20', 'Dice D20', 'Otros', 'solid', 'f6cf', 'Dungeons & Dragons, chance, d&d, dnd, fantasy, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(389, 'dice-d6', 'fas fa-dice-d6', 'Dice D6', 'Otros', 'solid', 'f6d1', 'Dungeons & Dragons, chance, d&d, dnd, fantasy, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(390, 'dice-five', 'fas fa-dice-five', 'Dice Five', 'Otros', 'solid', 'f523', 'chance, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(391, 'dice-four', 'fas fa-dice-four', 'Dice Four', 'Otros', 'solid', 'f524', 'chance, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(392, 'dice-one', 'fas fa-dice-one', 'Dice One', 'Otros', 'solid', 'f525', 'chance, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(393, 'dice-six', 'fas fa-dice-six', 'Dice Six', 'Otros', 'solid', 'f526', 'chance, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(394, 'dice-three', 'fas fa-dice-three', 'Dice Three', 'Otros', 'solid', 'f527', 'chance, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(395, 'dice-two', 'fas fa-dice-two', 'Dice Two', 'Otros', 'solid', 'f528', 'chance, gambling, game, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(396, 'digg', 'fab fa-digg', 'Digg Logo', 'Marcas', 'brands', 'f1a6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(397, 'digital-ocean', 'fab fa-digital-ocean', 'Digital Ocean', 'Marcas', 'brands', 'f391', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(398, 'digital-tachograph', 'fas fa-digital-tachograph', 'Digital Tachograph', 'Otros', 'solid', 'f566', 'data, distance, speed, tachometer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(399, 'directions', 'fas fa-directions', 'Directions', 'Otros', 'solid', 'f5eb', 'map, navigation, sign, turn', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(400, 'discord', 'fab fa-discord', 'Discord', 'Marcas', 'brands', 'f392', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(401, 'discourse', 'fab fa-discourse', 'Discourse', 'Marcas', 'brands', 'f393', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(402, 'divide', 'fas fa-divide', 'Divide', 'Otros', 'solid', 'f529', 'arithmetic, calculus, division, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(403, 'dizzy', 'fas fa-dizzy', 'Dizzy Face', 'Otros', 'solid', 'f567', 'dazed, dead, disapprove, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(404, 'dizzy', 'far fa-dizzy', 'Dizzy Face', 'Otros', 'regular', 'f567', 'dazed, dead, disapprove, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(405, 'dna', 'fas fa-dna', 'DNA', 'Otros', 'solid', 'f471', 'double helix, genetic, helix, molecule, protein', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(406, 'dochub', 'fab fa-dochub', 'DocHub', 'Marcas', 'brands', 'f394', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(407, 'docker', 'fab fa-docker', 'Docker', 'Marcas', 'brands', 'f395', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(408, 'dog', 'fas fa-dog', 'Dog', 'Otros', 'solid', 'f6d3', 'animal, canine, fauna, mammal, pet, pooch, puppy, woof', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(409, 'dollar-sign', 'fas fa-dollar-sign', 'Dollar Sign', 'Otros', 'solid', 'f155', '$, cost, dollar-sign, money, price, usd', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(410, 'dolly', 'fas fa-dolly', 'Dolly', 'Otros', 'solid', 'f472', 'carry, shipping, transport', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(411, 'dolly-flatbed', 'fas fa-dolly-flatbed', 'Dolly Flatbed', 'Otros', 'solid', 'f474', 'carry, inventory, shipping, transport', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(412, 'donate', 'fas fa-donate', 'Donate', 'Otros', 'solid', 'f4b9', 'contribute, generosity, gift, give', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(413, 'door-closed', 'fas fa-door-closed', 'Door Closed', 'Otros', 'solid', 'f52a', 'enter, exit, locked', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(414, 'door-open', 'fas fa-door-open', 'Door Open', 'Otros', 'solid', 'f52b', 'enter, exit, welcome', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(415, 'dot-circle', 'fas fa-dot-circle', 'Dot Circle', 'Otros', 'solid', 'f192', 'bullseye, notification, target', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(416, 'dot-circle', 'far fa-dot-circle', 'Dot Circle', 'Otros', 'regular', 'f192', 'bullseye, notification, target', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(417, 'dove', 'fas fa-dove', 'Dove', 'Otros', 'solid', 'f4ba', 'bird, fauna, flying, peace, war', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(418, 'download', 'fas fa-download', 'Download', 'Otros', 'solid', 'f019', 'export, hard drive, save, transfer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(419, 'draft2digital', 'fab fa-draft2digital', 'Draft2digital', 'Marcas', 'brands', 'f396', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(420, 'drafting-compass', 'fas fa-drafting-compass', 'Drafting Compass', 'Otros', 'solid', 'f568', 'design, map, mechanical drawing, plot, plotting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(421, 'dragon', 'fas fa-dragon', 'Dragon', 'Otros', 'solid', 'f6d5', 'Dungeons & Dragons, d&d, dnd, fantasy, fire, lizard, serpent', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(422, 'draw-polygon', 'fas fa-draw-polygon', 'Draw Polygon', 'Otros', 'solid', 'f5ee', 'anchors, lines, object, render, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(423, 'dribbble', 'fab fa-dribbble', 'Dribbble', 'Marcas', 'brands', 'f17d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(424, 'dribbble-square', 'fab fa-dribbble-square', 'Dribbble Square', 'Marcas', 'brands', 'f397', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(425, 'dropbox', 'fab fa-dropbox', 'Dropbox', 'Marcas', 'brands', 'f16b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(426, 'drum', 'fas fa-drum', 'Drum', 'Otros', 'solid', 'f569', 'instrument, music, percussion, snare, sound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(427, 'drum-steelpan', 'fas fa-drum-steelpan', 'Drum Steelpan', 'Otros', 'solid', 'f56a', 'calypso, instrument, music, percussion, reggae, snare, sound, steel, tropical', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(428, 'drumstick-bite', 'fas fa-drumstick-bite', 'Drumstick with Bite Taken Out', 'Otros', 'solid', 'f6d7', 'bone, chicken, leg, meat, poultry, turkey', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(429, 'drupal', 'fab fa-drupal', 'Drupal Logo', 'Marcas', 'brands', 'f1a9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(430, 'dumbbell', 'fas fa-dumbbell', 'Dumbbell', 'Otros', 'solid', 'f44b', 'exercise, gym, strength, weight, weight-lifting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(431, 'dumpster', 'fas fa-dumpster', 'Dumpster', 'Otros', 'solid', 'f793', 'alley, bin, commercial, trash, waste', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(432, 'dumpster-fire', 'fas fa-dumpster-fire', 'Dumpster Fire', 'Otros', 'solid', 'f794', 'alley, bin, commercial, danger, dangerous, euphemism, flame, heat, hot, trash, waste', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(433, 'dungeon', 'fas fa-dungeon', 'Dungeon', 'Otros', 'solid', 'f6d9', 'Dungeons & Dragons, building, d&d, dnd, door, entrance, fantasy, gate', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(434, 'dyalog', 'fab fa-dyalog', 'Dyalog', 'Marcas', 'brands', 'f399', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(435, 'earlybirds', 'fab fa-earlybirds', 'Earlybirds', 'Marcas', 'brands', 'f39a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(436, 'ebay', 'fab fa-ebay', 'eBay', 'Marcas', 'brands', 'f4f4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(437, 'edge', 'fab fa-edge', 'Edge Browser', 'Marcas', 'brands', 'f282', 'browser, ie', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(438, 'edit', 'fas fa-edit', 'Edit', 'Otros', 'solid', 'f044', 'edit, pen, pencil, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(439, 'edit', 'far fa-edit', 'Edit', 'Otros', 'regular', 'f044', 'edit, pen, pencil, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(440, 'egg', 'fas fa-egg', 'Egg', 'Otros', 'solid', 'f7fb', 'breakfast, chicken, easter, shell, yolk', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(441, 'eject', 'fas fa-eject', 'eject', 'Otros', 'solid', 'f052', 'abort, cancel, cd, discharge', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(442, 'elementor', 'fab fa-elementor', 'Elementor', 'Marcas', 'brands', 'f430', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(443, 'ellipsis-h', 'fas fa-ellipsis-h', 'Horizontal Ellipsis', 'Otros', 'solid', 'f141', 'dots, drag, kebab, list, menu, nav, navigation, ol, reorder, settings, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(444, 'ellipsis-v', 'fas fa-ellipsis-v', 'Vertical Ellipsis', 'Otros', 'solid', 'f142', 'dots, drag, kebab, list, menu, nav, navigation, ol, reorder, settings, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(445, 'ello', 'fab fa-ello', 'Ello', 'Marcas', 'brands', 'f5f1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(446, 'ember', 'fab fa-ember', 'Ember', 'Marcas', 'brands', 'f423', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(447, 'empire', 'fab fa-empire', 'Galactic Empire', 'Marcas', 'brands', 'f1d1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(448, 'envelope', 'fas fa-envelope', 'Envelope', 'Otros', 'solid', 'f0e0', 'e-mail, email, letter, mail, message, notification, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(449, 'envelope', 'far fa-envelope', 'Envelope', 'Otros', 'regular', 'f0e0', 'e-mail, email, letter, mail, message, notification, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(450, 'envelope-open', 'fas fa-envelope-open', 'Envelope Open', 'Otros', 'solid', 'f2b6', 'e-mail, email, letter, mail, message, notification, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(451, 'envelope-open', 'far fa-envelope-open', 'Envelope Open', 'Otros', 'regular', 'f2b6', 'e-mail, email, letter, mail, message, notification, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(452, 'envelope-open-text', 'fas fa-envelope-open-text', 'Envelope Open-text', 'Otros', 'solid', 'f658', 'e-mail, email, letter, mail, message, notification, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(453, 'envelope-square', 'fas fa-envelope-square', 'Envelope Square', 'Otros', 'solid', 'f199', 'e-mail, email, letter, mail, message, notification, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(454, 'envira', 'fab fa-envira', 'Envira Gallery', 'Marcas', 'brands', 'f299', 'leaf', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(455, 'equals', 'fas fa-equals', 'Equals', 'Otros', 'solid', 'f52c', 'arithmetic, even, match, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(456, 'eraser', 'fas fa-eraser', 'eraser', 'Otros', 'solid', 'f12d', 'art, delete, remove, rubber', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(457, 'erlang', 'fab fa-erlang', 'Erlang', 'Marcas', 'brands', 'f39d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(458, 'ethereum', 'fab fa-ethereum', 'Ethereum', 'Marcas', 'brands', 'f42e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(459, 'ethernet', 'fas fa-ethernet', 'Ethernet', 'Otros', 'solid', 'f796', 'cable, cat 5, cat 6, connection, hardware, internet, network, wired', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(460, 'etsy', 'fab fa-etsy', 'Etsy', 'Marcas', 'brands', 'f2d7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(461, 'euro-sign', 'fas fa-euro-sign', 'Euro Sign', 'Otros', 'solid', 'f153', 'currency, dollar, exchange, money', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(462, 'evernote', 'fab fa-evernote', 'Evernote', 'Marcas', 'brands', 'f839', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(463, 'exchange-alt', 'fas fa-exchange-alt', 'Alternate Exchange', 'Otros', 'solid', 'f362', 'arrow, arrows, exchange, reciprocate, return, swap, transfer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(464, 'exclamation', 'fas fa-exclamation', 'exclamation', 'Otros', 'solid', 'f12a', 'alert, danger, error, important, notice, notification, notify, problem, warning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(465, 'exclamation-circle', 'fas fa-exclamation-circle', 'Exclamation Circle', 'Otros', 'solid', 'f06a', 'alert, danger, error, important, notice, notification, notify, problem, warning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(466, 'exclamation-triangle', 'fas fa-exclamation-triangle', 'Exclamation Triangle', 'Otros', 'solid', 'f071', 'alert, danger, error, important, notice, notification, notify, problem, warning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(467, 'expand', 'fas fa-expand', 'Expand', 'Otros', 'solid', 'f065', 'arrow, bigger, enlarge, resize', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(468, 'expand-arrows-alt', 'fas fa-expand-arrows-alt', 'Alternate Expand Arrows', 'Otros', 'solid', 'f31e', 'arrows-alt, bigger, enlarge, move, resize', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(469, 'expeditedssl', 'fab fa-expeditedssl', 'ExpeditedSSL', 'Marcas', 'brands', 'f23e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(470, 'external-link-alt', 'fas fa-external-link-alt', 'Alternate External Link', 'Otros', 'solid', 'f35d', 'external-link, new, open, share', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(471, 'external-link-square-alt', 'fas fa-external-link-square-alt', 'Alternate External Link Square', 'Otros', 'solid', 'f360', 'external-link-square, new, open, share', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(472, 'eye', 'fas fa-eye', 'Eye', 'Otros', 'solid', 'f06e', 'look, optic, see, seen, show, sight, views, visible', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(473, 'eye', 'far fa-eye', 'Eye', 'Otros', 'regular', 'f06e', 'look, optic, see, seen, show, sight, views, visible', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(474, 'eye-dropper', 'fas fa-eye-dropper', 'Eye Dropper', 'Otros', 'solid', 'f1fb', 'beaker, clone, color, copy, eyedropper, pipette', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(475, 'eye-slash', 'fas fa-eye-slash', 'Eye Slash', 'Otros', 'solid', 'f070', 'blind, hide, show, toggle, unseen, views, visible, visiblity', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(476, 'eye-slash', 'far fa-eye-slash', 'Eye Slash', 'Otros', 'regular', 'f070', 'blind, hide, show, toggle, unseen, views, visible, visiblity', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(477, 'facebook', 'fab fa-facebook', 'Facebook', 'Marcas', 'brands', 'f09a', 'facebook-official, social network', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(478, 'facebook-f', 'fab fa-facebook-f', 'Facebook F', 'Marcas', 'brands', 'f39e', 'facebook', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(479, 'facebook-messenger', 'fab fa-facebook-messenger', 'Facebook Messenger', 'Marcas', 'brands', 'f39f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(480, 'facebook-square', 'fab fa-facebook-square', 'Facebook Square', 'Marcas', 'brands', 'f082', 'social network', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(481, 'fan', 'fas fa-fan', 'Fan', 'Otros', 'solid', 'f863', 'ac, air conditioning, blade, blower, cool, hot', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(482, 'fantasy-flight-games', 'fab fa-fantasy-flight-games', 'Fantasy Flight-games', 'Marcas', 'brands', 'f6dc', 'Dungeons & Dragons, d&d, dnd, fantasy, game, gaming, tabletop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(483, 'fast-backward', 'fas fa-fast-backward', 'fast-backward', 'Otros', 'solid', 'f049', 'beginning, first, previous, rewind, start', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(484, 'fast-forward', 'fas fa-fast-forward', 'fast-forward', 'Otros', 'solid', 'f050', 'end, last, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(485, 'fax', 'fas fa-fax', 'Fax', 'Otros', 'solid', 'f1ac', 'business, communicate, copy, facsimile, send', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(486, 'feather', 'fas fa-feather', 'Feather', 'Otros', 'solid', 'f52d', 'bird, light, plucked, quill, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(487, 'feather-alt', 'fas fa-feather-alt', 'Alternate Feather', 'Otros', 'solid', 'f56b', 'bird, light, plucked, quill, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(488, 'fedex', 'fab fa-fedex', 'FedEx', 'Marcas', 'brands', 'f797', 'Federal Express, package, shipping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(489, 'fedora', 'fab fa-fedora', 'Fedora', 'Marcas', 'brands', 'f798', 'linux, operating system, os', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(490, 'female', 'fas fa-female', 'Female', 'Otros', 'solid', 'f182', 'human, person, profile, user, woman', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(491, 'fighter-jet', 'fas fa-fighter-jet', 'fighter-jet', 'Otros', 'solid', 'f0fb', 'airplane, fast, fly, goose, maverick, plane, quick, top gun, transportation, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(492, 'figma', 'fab fa-figma', 'Figma', 'Marcas', 'brands', 'f799', 'app, design, interface', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(493, 'file', 'fas fa-file', 'File', 'Otros', 'solid', 'f15b', 'document, new, page, pdf, resume', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(494, 'file', 'far fa-file', 'File', 'Otros', 'regular', 'f15b', 'document, new, page, pdf, resume', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(495, 'file-alt', 'fas fa-file-alt', 'Alternate File', 'Otros', 'solid', 'f15c', 'document, file-text, invoice, new, page, pdf', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(496, 'file-alt', 'far fa-file-alt', 'Alternate File', 'Otros', 'regular', 'f15c', 'document, file-text, invoice, new, page, pdf', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(497, 'file-archive', 'fas fa-file-archive', 'Archive File', 'Otros', 'solid', 'f1c6', '.zip, bundle, compress, compression, download, zip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(498, 'file-archive', 'far fa-file-archive', 'Archive File', 'Otros', 'regular', 'f1c6', '.zip, bundle, compress, compression, download, zip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(499, 'file-audio', 'fas fa-file-audio', 'Audio File', 'Otros', 'solid', 'f1c7', 'document, mp3, music, page, play, sound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(500, 'file-audio', 'far fa-file-audio', 'Audio File', 'Otros', 'regular', 'f1c7', 'document, mp3, music, page, play, sound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(501, 'file-code', 'fas fa-file-code', 'Code File', 'Otros', 'solid', 'f1c9', 'css, development, document, html', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(502, 'file-code', 'far fa-file-code', 'Code File', 'Otros', 'regular', 'f1c9', 'css, development, document, html', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(503, 'file-contract', 'fas fa-file-contract', 'File Contract', 'Otros', 'solid', 'f56c', 'agreement, binding, document, legal, signature', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(504, 'file-csv', 'fas fa-file-csv', 'File CSV', 'Otros', 'solid', 'f6dd', 'document, excel, numbers, spreadsheets, table', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(505, 'file-download', 'fas fa-file-download', 'File Download', 'Otros', 'solid', 'f56d', 'document, export, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(506, 'file-excel', 'fas fa-file-excel', 'Excel File', 'Otros', 'solid', 'f1c3', 'csv, document, numbers, spreadsheets, table', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(507, 'file-excel', 'far fa-file-excel', 'Excel File', 'Otros', 'regular', 'f1c3', 'csv, document, numbers, spreadsheets, table', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(508, 'file-export', 'fas fa-file-export', 'File Export', 'Otros', 'solid', 'f56e', 'download, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(509, 'file-image', 'fas fa-file-image', 'Image File', 'Otros', 'solid', 'f1c5', 'document, image, jpg, photo, png', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(510, 'file-image', 'far fa-file-image', 'Image File', 'Otros', 'regular', 'f1c5', 'document, image, jpg, photo, png', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(511, 'file-import', 'fas fa-file-import', 'File Import', 'Otros', 'solid', 'f56f', 'copy, document, send, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(512, 'file-invoice', 'fas fa-file-invoice', 'File Invoice', 'Otros', 'solid', 'f570', 'account, bill, charge, document, payment, receipt', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(513, 'file-invoice-dollar', 'fas fa-file-invoice-dollar', 'File Invoice with US Dollar', 'Otros', 'solid', 'f571', '$, account, bill, charge, document, dollar-sign, money, payment, receipt, usd', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(514, 'file-medical', 'fas fa-file-medical', 'Medical File', 'Otros', 'solid', 'f477', 'document, health, history, prescription, record', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(515, 'file-medical-alt', 'fas fa-file-medical-alt', 'Alternate Medical File', 'Otros', 'solid', 'f478', 'document, health, history, prescription, record', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(516, 'file-pdf', 'fas fa-file-pdf', 'PDF File', 'Otros', 'solid', 'f1c1', 'acrobat, document, preview, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(517, 'file-pdf', 'far fa-file-pdf', 'PDF File', 'Otros', 'regular', 'f1c1', 'acrobat, document, preview, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(518, 'file-powerpoint', 'fas fa-file-powerpoint', 'Powerpoint File', 'Otros', 'solid', 'f1c4', 'display, document, keynote, presentation', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(519, 'file-powerpoint', 'far fa-file-powerpoint', 'Powerpoint File', 'Otros', 'regular', 'f1c4', 'display, document, keynote, presentation', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(520, 'file-prescription', 'fas fa-file-prescription', 'File Prescription', 'Medicina', 'solid', 'f572', 'document, drugs, medical, medicine, rx', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(521, 'file-signature', 'fas fa-file-signature', 'File Signature', 'Otros', 'solid', 'f573', 'John Hancock, contract, document, name', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(522, 'file-upload', 'fas fa-file-upload', 'File Upload', 'Otros', 'solid', 'f574', 'document, import, page, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(523, 'file-video', 'fas fa-file-video', 'Video File', 'Otros', 'solid', 'f1c8', 'document, m4v, movie, mp4, play', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(524, 'file-video', 'far fa-file-video', 'Video File', 'Otros', 'regular', 'f1c8', 'document, m4v, movie, mp4, play', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(525, 'file-word', 'fas fa-file-word', 'Word File', 'Otros', 'solid', 'f1c2', 'document, edit, page, text, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(526, 'file-word', 'far fa-file-word', 'Word File', 'Otros', 'regular', 'f1c2', 'document, edit, page, text, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(527, 'fill', 'fas fa-fill', 'Fill', 'Otros', 'solid', 'f575', 'bucket, color, paint, paint bucket', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(528, 'fill-drip', 'fas fa-fill-drip', 'Fill Drip', 'Otros', 'solid', 'f576', 'bucket, color, drop, paint, paint bucket, spill', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(529, 'film', 'fas fa-film', 'Film', 'Otros', 'solid', 'f008', 'cinema, movie, strip, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(530, 'filter', 'fas fa-filter', 'Filter', 'Otros', 'solid', 'f0b0', 'funnel, options, separate, sort', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(531, 'fingerprint', 'fas fa-fingerprint', 'Fingerprint', 'Otros', 'solid', 'f577', 'human, id, identification, lock, smudge, touch, unique, unlock', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(532, 'fire', 'fas fa-fire', 'fire', 'Otros', 'solid', 'f06d', 'burn, caliente, flame, heat, hot, popular', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(533, 'fire-alt', 'fas fa-fire-alt', 'Alternate Fire', 'Otros', 'solid', 'f7e4', 'burn, caliente, flame, heat, hot, popular', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(534, 'fire-extinguisher', 'fas fa-fire-extinguisher', 'fire-extinguisher', 'Otros', 'solid', 'f134', 'burn, caliente, fire fighter, flame, heat, hot, rescue', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(535, 'firefox', 'fab fa-firefox', 'Firefox', 'Marcas', 'brands', 'f269', 'browser', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(536, 'first-aid', 'fas fa-first-aid', 'First Aid', 'Medicina', 'solid', 'f479', 'emergency, emt, health, medical, rescue', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(537, 'first-order', 'fab fa-first-order', 'First Order', 'Marcas', 'brands', 'f2b0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(538, 'first-order-alt', 'fab fa-first-order-alt', 'Alternate First Order', 'Marcas', 'brands', 'f50a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(539, 'firstdraft', 'fab fa-firstdraft', 'firstdraft', 'Marcas', 'brands', 'f3a1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(540, 'fish', 'fas fa-fish', 'Fish', 'Otros', 'solid', 'f578', 'fauna, gold, seafood, swimming', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(541, 'fist-raised', 'fas fa-fist-raised', 'Raised Fist', 'Otros', 'solid', 'f6de', 'Dungeons & Dragons, d&d, dnd, fantasy, hand, ki, monk, resist, strength, unarmed combat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(542, 'flag', 'fas fa-flag', 'flag', 'Otros', 'solid', 'f024', 'country, notice, notification, notify, pole, report, symbol', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(543, 'flag', 'far fa-flag', 'flag', 'Otros', 'regular', 'f024', 'country, notice, notification, notify, pole, report, symbol', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(544, 'flag-checkered', 'fas fa-flag-checkered', 'flag-checkered', 'Otros', 'solid', 'f11e', 'notice, notification, notify, pole, racing, report, symbol', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(545, 'flag-usa', 'fas fa-flag-usa', 'United States of America Flag', 'Otros', 'solid', 'f74d', 'betsy ross, country, old glory, stars, stripes, symbol', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(546, 'flask', 'fas fa-flask', 'Flask', 'Otros', 'solid', 'f0c3', 'beaker, experimental, labs, science', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(547, 'flickr', 'fab fa-flickr', 'Flickr', 'Marcas', 'brands', 'f16e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(548, 'flipboard', 'fab fa-flipboard', 'Flipboard', 'Marcas', 'brands', 'f44d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(549, 'flushed', 'fas fa-flushed', 'Flushed Face', 'Otros', 'solid', 'f579', 'embarrassed, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(550, 'flushed', 'far fa-flushed', 'Flushed Face', 'Otros', 'regular', 'f579', 'embarrassed, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(551, 'fly', 'fab fa-fly', 'Fly', 'Marcas', 'brands', 'f417', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(552, 'folder', 'fas fa-folder', 'Folder', 'Otros', 'solid', 'f07b', 'archive, directory, document, file', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(553, 'folder', 'far fa-folder', 'Folder', 'Otros', 'regular', 'f07b', 'archive, directory, document, file', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(554, 'folder-minus', 'fas fa-folder-minus', 'Folder Minus', 'Otros', 'solid', 'f65d', 'archive, delete, directory, document, file, negative, remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(555, 'folder-open', 'fas fa-folder-open', 'Folder Open', 'Otros', 'solid', 'f07c', 'archive, directory, document, empty, file, new', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(556, 'folder-open', 'far fa-folder-open', 'Folder Open', 'Otros', 'regular', 'f07c', 'archive, directory, document, empty, file, new', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(557, 'folder-plus', 'fas fa-folder-plus', 'Folder Plus', 'Otros', 'solid', 'f65e', 'add, archive, create, directory, document, file, new, positive', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(558, 'font', 'fas fa-font', 'font', 'Otros', 'solid', 'f031', 'alphabet, glyph, text, type, typeface', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(559, 'font-awesome', 'fab fa-font-awesome', 'Font Awesome', 'Marcas', 'brands', 'f2b4', 'meanpath', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(560, 'font-awesome-alt', 'fab fa-font-awesome-alt', 'Alternate Font Awesome', 'Marcas', 'brands', 'f35c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(561, 'font-awesome-flag', 'fab fa-font-awesome-flag', 'Font Awesome Flag', 'Marcas', 'brands', 'f425', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(562, 'font-awesome-logo-full', 'far fa-font-awesome-logo-full', 'Font Awesome Full Logo', 'Otros', 'regular', 'f4e6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(563, 'font-awesome-logo-full', 'fas fa-font-awesome-logo-full', 'Font Awesome Full Logo', 'Otros', 'solid', 'f4e6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(564, 'font-awesome-logo-full', 'fab fa-font-awesome-logo-full', 'Font Awesome Full Logo', 'Marcas', 'brands', 'f4e6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(565, 'fonticons', 'fab fa-fonticons', 'Fonticons', 'Marcas', 'brands', 'f280', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(566, 'fonticons-fi', 'fab fa-fonticons-fi', 'Fonticons Fi', 'Marcas', 'brands', 'f3a2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(567, 'football-ball', 'fas fa-football-ball', 'Football Ball', 'Otros', 'solid', 'f44e', 'ball, fall, nfl, pigskin, seasonal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(568, 'fort-awesome', 'fab fa-fort-awesome', 'Fort Awesome', 'Marcas', 'brands', 'f286', 'castle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(569, 'fort-awesome-alt', 'fab fa-fort-awesome-alt', 'Alternate Fort Awesome', 'Marcas', 'brands', 'f3a3', 'castle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(570, 'forumbee', 'fab fa-forumbee', 'Forumbee', 'Marcas', 'brands', 'f211', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(571, 'forward', 'fas fa-forward', 'forward', 'Otros', 'solid', 'f04e', 'forward, next, skip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(572, 'foursquare', 'fab fa-foursquare', 'Foursquare', 'Marcas', 'brands', 'f180', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(573, 'free-code-camp', 'fab fa-free-code-camp', 'Free Code Camp', 'Marcas', 'brands', 'f2c5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(574, 'freebsd', 'fab fa-freebsd', 'FreeBSD', 'Marcas', 'brands', 'f3a4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(575, 'frog', 'fas fa-frog', 'Frog', 'Otros', 'solid', 'f52e', 'amphibian, bullfrog, fauna, hop, kermit, kiss, prince, ribbit, toad, wart', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(576, 'frown', 'fas fa-frown', 'Frowning Face', 'Otros', 'solid', 'f119', 'disapprove, emoticon, face, rating, sad', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(577, 'frown', 'far fa-frown', 'Frowning Face', 'Otros', 'regular', 'f119', 'disapprove, emoticon, face, rating, sad', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(578, 'frown-open', 'fas fa-frown-open', 'Frowning Face With Open Mouth', 'Otros', 'solid', 'f57a', 'disapprove, emoticon, face, rating, sad', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(579, 'frown-open', 'far fa-frown-open', 'Frowning Face With Open Mouth', 'Otros', 'regular', 'f57a', 'disapprove, emoticon, face, rating, sad', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(580, 'fulcrum', 'fab fa-fulcrum', 'Fulcrum', 'Marcas', 'brands', 'f50b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(581, 'funnel-dollar', 'fas fa-funnel-dollar', 'Funnel Dollar', 'Otros', 'solid', 'f662', 'filter, money, options, separate, sort', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(582, 'futbol', 'fas fa-futbol', 'Futbol', 'Otros', 'solid', 'f1e3', 'ball, football, mls, soccer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(583, 'futbol', 'far fa-futbol', 'Futbol', 'Otros', 'regular', 'f1e3', 'ball, football, mls, soccer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(584, 'galactic-republic', 'fab fa-galactic-republic', 'Galactic Republic', 'Marcas', 'brands', 'f50c', 'politics, star wars', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(585, 'galactic-senate', 'fab fa-galactic-senate', 'Galactic Senate', 'Marcas', 'brands', 'f50d', 'star wars', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(586, 'gamepad', 'fas fa-gamepad', 'Gamepad', 'Otros', 'solid', 'f11b', 'arcade, controller, d-pad, joystick, video, video game', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(587, 'gas-pump', 'fas fa-gas-pump', 'Gas Pump', 'Otros', 'solid', 'f52f', 'car, fuel, gasoline, petrol', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(588, 'gavel', 'fas fa-gavel', 'Gavel', 'Otros', 'solid', 'f0e3', 'hammer, judge, law, lawyer, opinion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(589, 'gem', 'fas fa-gem', 'Gem', 'Otros', 'solid', 'f3a5', 'diamond, jewelry, sapphire, stone, treasure', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(590, 'gem', 'far fa-gem', 'Gem', 'Otros', 'regular', 'f3a5', 'diamond, jewelry, sapphire, stone, treasure', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(591, 'genderless', 'fas fa-genderless', 'Genderless', 'Otros', 'solid', 'f22d', 'androgynous, asexual, sexless', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(592, 'get-pocket', 'fab fa-get-pocket', 'Get Pocket', 'Marcas', 'brands', 'f265', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(593, 'gg', 'fab fa-gg', 'GG Currency', 'Marcas', 'brands', 'f260', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(594, 'gg-circle', 'fab fa-gg-circle', 'GG Currency Circle', 'Marcas', 'brands', 'f261', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(595, 'ghost', 'fas fa-ghost', 'Ghost', 'Otros', 'solid', 'f6e2', 'apparition, blinky, clyde, floating, halloween, holiday, inky, pinky, spirit', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sara_iconos` (`id`, `Icono`, `IconoFull`, `IconoLabel`, `Categoria`, `Estilo`, `Unicode`, `PalabrasClave`, `created_at`, `updated_at`) VALUES
(596, 'gift', 'fas fa-gift', 'gift', 'Otros', 'solid', 'f06b', 'christmas, generosity, giving, holiday, party, present, wrapped, xmas', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(597, 'gifts', 'fas fa-gifts', 'Gifts', 'Otros', 'solid', 'f79c', 'christmas, generosity, giving, holiday, party, present, wrapped, xmas', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(598, 'git', 'fab fa-git', 'Git', 'Marcas', 'brands', 'f1d3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(599, 'git-alt', 'fab fa-git-alt', 'Git Alt', 'Marcas', 'brands', 'f841', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(600, 'git-square', 'fab fa-git-square', 'Git Square', 'Marcas', 'brands', 'f1d2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(601, 'github', 'fab fa-github', 'GitHub', 'Marcas', 'brands', 'f09b', 'octocat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(602, 'github-alt', 'fab fa-github-alt', 'Alternate GitHub', 'Marcas', 'brands', 'f113', 'octocat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(603, 'github-square', 'fab fa-github-square', 'GitHub Square', 'Marcas', 'brands', 'f092', 'octocat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(604, 'gitkraken', 'fab fa-gitkraken', 'GitKraken', 'Marcas', 'brands', 'f3a6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(605, 'gitlab', 'fab fa-gitlab', 'GitLab', 'Marcas', 'brands', 'f296', 'Axosoft', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(606, 'gitter', 'fab fa-gitter', 'Gitter', 'Marcas', 'brands', 'f426', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(607, 'glass-cheers', 'fas fa-glass-cheers', 'Glass Cheers', 'Otros', 'solid', 'f79f', 'alcohol, bar, beverage, celebration, champagne, clink, drink, holiday, new year\'s eve, party, toast', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(608, 'glass-martini', 'fas fa-glass-martini', 'Martini Glass', 'Otros', 'solid', 'f000', 'alcohol, bar, beverage, drink, liquor', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(609, 'glass-martini-alt', 'fas fa-glass-martini-alt', 'Alternate Glass Martini', 'Otros', 'solid', 'f57b', 'alcohol, bar, beverage, drink, liquor', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(610, 'glass-whiskey', 'fas fa-glass-whiskey', 'Glass Whiskey', 'Otros', 'solid', 'f7a0', 'alcohol, bar, beverage, bourbon, drink, liquor, neat, rye, scotch, whisky', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(611, 'glasses', 'fas fa-glasses', 'Glasses', 'Otros', 'solid', 'f530', 'hipster, nerd, reading, sight, spectacles, vision', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(612, 'glide', 'fab fa-glide', 'Glide', 'Marcas', 'brands', 'f2a5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(613, 'glide-g', 'fab fa-glide-g', 'Glide G', 'Marcas', 'brands', 'f2a6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(614, 'globe', 'fas fa-globe', 'Globe', 'Otros', 'solid', 'f0ac', 'all, coordinates, country, earth, global, gps, language, localize, location, map, online, place, planet, translate, travel, world', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(615, 'globe-africa', 'fas fa-globe-africa', 'Globe with Africa shown', 'Otros', 'solid', 'f57c', 'all, country, earth, global, gps, language, localize, location, map, online, place, planet, translate, travel, world', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(616, 'globe-americas', 'fas fa-globe-americas', 'Globe with Americas shown', 'Otros', 'solid', 'f57d', 'all, country, earth, global, gps, language, localize, location, map, online, place, planet, translate, travel, world', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(617, 'globe-asia', 'fas fa-globe-asia', 'Globe with Asia shown', 'Otros', 'solid', 'f57e', 'all, country, earth, global, gps, language, localize, location, map, online, place, planet, translate, travel, world', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(618, 'globe-europe', 'fas fa-globe-europe', 'Globe with Europe shown', 'Otros', 'solid', 'f7a2', 'all, country, earth, global, gps, language, localize, location, map, online, place, planet, translate, travel, world', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(619, 'gofore', 'fab fa-gofore', 'Gofore', 'Marcas', 'brands', 'f3a7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(620, 'golf-ball', 'fas fa-golf-ball', 'Golf Ball', 'Otros', 'solid', 'f450', 'caddy, eagle, putt, tee', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(621, 'goodreads', 'fab fa-goodreads', 'Goodreads', 'Marcas', 'brands', 'f3a8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(622, 'goodreads-g', 'fab fa-goodreads-g', 'Goodreads G', 'Marcas', 'brands', 'f3a9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(623, 'google', 'fab fa-google', 'Google Logo', 'Marcas', 'brands', 'f1a0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(624, 'google-drive', 'fab fa-google-drive', 'Google Drive', 'Marcas', 'brands', 'f3aa', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(625, 'google-play', 'fab fa-google-play', 'Google Play', 'Marcas', 'brands', 'f3ab', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(626, 'google-plus', 'fab fa-google-plus', 'Google Plus', 'Marcas', 'brands', 'f2b3', 'google-plus-circle, google-plus-official', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(627, 'google-plus-g', 'fab fa-google-plus-g', 'Google Plus G', 'Marcas', 'brands', 'f0d5', 'google-plus, social network', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(628, 'google-plus-square', 'fab fa-google-plus-square', 'Google Plus Square', 'Marcas', 'brands', 'f0d4', 'social network', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(629, 'google-wallet', 'fab fa-google-wallet', 'Google Wallet', 'Marcas', 'brands', 'f1ee', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(630, 'gopuram', 'fas fa-gopuram', 'Gopuram', 'Otros', 'solid', 'f664', 'building, entrance, hinduism, temple, tower', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(631, 'graduation-cap', 'fas fa-graduation-cap', 'Graduation Cap', 'Otros', 'solid', 'f19d', 'ceremony, college, graduate, learning, school, student', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(632, 'gratipay', 'fab fa-gratipay', 'Gratipay (Gittip)', 'Marcas', 'brands', 'f184', 'favorite, heart, like, love', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(633, 'grav', 'fab fa-grav', 'Grav', 'Marcas', 'brands', 'f2d6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(634, 'greater-than', 'fas fa-greater-than', 'Greater Than', 'Otros', 'solid', 'f531', 'arithmetic, compare, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(635, 'greater-than-equal', 'fas fa-greater-than-equal', 'Greater Than Equal To', 'Otros', 'solid', 'f532', 'arithmetic, compare, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(636, 'grimace', 'fas fa-grimace', 'Grimacing Face', 'Otros', 'solid', 'f57f', 'cringe, emoticon, face, teeth', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(637, 'grimace', 'far fa-grimace', 'Grimacing Face', 'Otros', 'regular', 'f57f', 'cringe, emoticon, face, teeth', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(638, 'grin', 'fas fa-grin', 'Grinning Face', 'Otros', 'solid', 'f580', 'emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(639, 'grin', 'far fa-grin', 'Grinning Face', 'Otros', 'regular', 'f580', 'emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(640, 'grin-alt', 'fas fa-grin-alt', 'Alternate Grinning Face', 'Otros', 'solid', 'f581', 'emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(641, 'grin-alt', 'far fa-grin-alt', 'Alternate Grinning Face', 'Otros', 'regular', 'f581', 'emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(642, 'grin-beam', 'fas fa-grin-beam', 'Grinning Face With Smiling Eyes', 'Otros', 'solid', 'f582', 'emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(643, 'grin-beam', 'far fa-grin-beam', 'Grinning Face With Smiling Eyes', 'Otros', 'regular', 'f582', 'emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(644, 'grin-beam-sweat', 'fas fa-grin-beam-sweat', 'Grinning Face With Sweat', 'Otros', 'solid', 'f583', 'embarass, emoticon, face, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(645, 'grin-beam-sweat', 'far fa-grin-beam-sweat', 'Grinning Face With Sweat', 'Otros', 'regular', 'f583', 'embarass, emoticon, face, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(646, 'grin-hearts', 'fas fa-grin-hearts', 'Smiling Face With Heart-Eyes', 'Otros', 'solid', 'f584', 'emoticon, face, love, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(647, 'grin-hearts', 'far fa-grin-hearts', 'Smiling Face With Heart-Eyes', 'Otros', 'regular', 'f584', 'emoticon, face, love, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(648, 'grin-squint', 'fas fa-grin-squint', 'Grinning Squinting Face', 'Otros', 'solid', 'f585', 'emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(649, 'grin-squint', 'far fa-grin-squint', 'Grinning Squinting Face', 'Otros', 'regular', 'f585', 'emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(650, 'grin-squint-tears', 'fas fa-grin-squint-tears', 'Rolling on the Floor Laughing', 'Otros', 'solid', 'f586', 'emoticon, face, happy, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(651, 'grin-squint-tears', 'far fa-grin-squint-tears', 'Rolling on the Floor Laughing', 'Otros', 'regular', 'f586', 'emoticon, face, happy, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(652, 'grin-stars', 'fas fa-grin-stars', 'Star-Struck', 'Otros', 'solid', 'f587', 'emoticon, face, star-struck', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(653, 'grin-stars', 'far fa-grin-stars', 'Star-Struck', 'Otros', 'regular', 'f587', 'emoticon, face, star-struck', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(654, 'grin-tears', 'fas fa-grin-tears', 'Face With Tears of Joy', 'Otros', 'solid', 'f588', 'LOL, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(655, 'grin-tears', 'far fa-grin-tears', 'Face With Tears of Joy', 'Otros', 'regular', 'f588', 'LOL, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(656, 'grin-tongue', 'fas fa-grin-tongue', 'Face With Tongue', 'Otros', 'solid', 'f589', 'LOL, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(657, 'grin-tongue', 'far fa-grin-tongue', 'Face With Tongue', 'Otros', 'regular', 'f589', 'LOL, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(658, 'grin-tongue-squint', 'fas fa-grin-tongue-squint', 'Squinting Face With Tongue', 'Otros', 'solid', 'f58a', 'LOL, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(659, 'grin-tongue-squint', 'far fa-grin-tongue-squint', 'Squinting Face With Tongue', 'Otros', 'regular', 'f58a', 'LOL, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(660, 'grin-tongue-wink', 'fas fa-grin-tongue-wink', 'Winking Face With Tongue', 'Otros', 'solid', 'f58b', 'LOL, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(661, 'grin-tongue-wink', 'far fa-grin-tongue-wink', 'Winking Face With Tongue', 'Otros', 'regular', 'f58b', 'LOL, emoticon, face', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(662, 'grin-wink', 'fas fa-grin-wink', 'Grinning Winking Face', 'Otros', 'solid', 'f58c', 'emoticon, face, flirt, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(663, 'grin-wink', 'far fa-grin-wink', 'Grinning Winking Face', 'Otros', 'regular', 'f58c', 'emoticon, face, flirt, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(664, 'grip-horizontal', 'fas fa-grip-horizontal', 'Grip Horizontal', 'Otros', 'solid', 'f58d', 'affordance, drag, drop, grab, handle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(665, 'grip-lines', 'fas fa-grip-lines', 'Grip Lines', 'Otros', 'solid', 'f7a4', 'affordance, drag, drop, grab, handle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(666, 'grip-lines-vertical', 'fas fa-grip-lines-vertical', 'Grip Lines Vertical', 'Otros', 'solid', 'f7a5', 'affordance, drag, drop, grab, handle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(667, 'grip-vertical', 'fas fa-grip-vertical', 'Grip Vertical', 'Otros', 'solid', 'f58e', 'affordance, drag, drop, grab, handle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(668, 'gripfire', 'fab fa-gripfire', 'Gripfire, Inc.', 'Marcas', 'brands', 'f3ac', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(669, 'grunt', 'fab fa-grunt', 'Grunt', 'Marcas', 'brands', 'f3ad', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(670, 'guitar', 'fas fa-guitar', 'Guitar', 'Otros', 'solid', 'f7a6', 'acoustic, instrument, music, rock, rock and roll, song, strings', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(671, 'gulp', 'fab fa-gulp', 'Gulp', 'Marcas', 'brands', 'f3ae', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(672, 'h-square', 'fas fa-h-square', 'H Square', 'Otros', 'solid', 'f0fd', 'directions, emergency, hospital, hotel, map', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(673, 'hacker-news', 'fab fa-hacker-news', 'Hacker News', 'Marcas', 'brands', 'f1d4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(674, 'hacker-news-square', 'fab fa-hacker-news-square', 'Hacker News Square', 'Marcas', 'brands', 'f3af', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(675, 'hackerrank', 'fab fa-hackerrank', 'Hackerrank', 'Marcas', 'brands', 'f5f7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(676, 'hamburger', 'fas fa-hamburger', 'Hamburger', 'Otros', 'solid', 'f805', 'bacon, beef, burger, burger king, cheeseburger, fast food, grill, ground beef, mcdonalds, sandwich', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(677, 'hammer', 'fas fa-hammer', 'Hammer', 'Otros', 'solid', 'f6e3', 'admin, fix, repair, settings, tool', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(678, 'hamsa', 'fas fa-hamsa', 'Hamsa', 'Otros', 'solid', 'f665', 'amulet, christianity, islam, jewish, judaism, muslim, protection', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(679, 'hand-holding', 'fas fa-hand-holding', 'Hand Holding', 'Otros', 'solid', 'f4bd', 'carry, lift', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(680, 'hand-holding-heart', 'fas fa-hand-holding-heart', 'Hand Holding Heart', 'Otros', 'solid', 'f4be', 'carry, charity, gift, lift, package', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(681, 'hand-holding-usd', 'fas fa-hand-holding-usd', 'Hand Holding US Dollar', 'Otros', 'solid', 'f4c0', '$, carry, dollar sign, donation, giving, lift, money, price', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(682, 'hand-lizard', 'fas fa-hand-lizard', 'Lizard (Hand)', 'Otros', 'solid', 'f258', 'game, roshambo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(683, 'hand-lizard', 'far fa-hand-lizard', 'Lizard (Hand)', 'Otros', 'regular', 'f258', 'game, roshambo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(684, 'hand-middle-finger', 'fas fa-hand-middle-finger', 'Hand with Middle Finger Raised', 'Otros', 'solid', 'f806', 'flip the bird, gesture, hate, rude', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(685, 'hand-paper', 'fas fa-hand-paper', 'Paper (Hand)', 'Otros', 'solid', 'f256', 'game, halt, roshambo, stop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(686, 'hand-paper', 'far fa-hand-paper', 'Paper (Hand)', 'Otros', 'regular', 'f256', 'game, halt, roshambo, stop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(687, 'hand-peace', 'fas fa-hand-peace', 'Peace (Hand)', 'Otros', 'solid', 'f25b', 'rest, truce', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(688, 'hand-peace', 'far fa-hand-peace', 'Peace (Hand)', 'Otros', 'regular', 'f25b', 'rest, truce', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(689, 'hand-point-down', 'fas fa-hand-point-down', 'Hand Pointing Down', 'Otros', 'solid', 'f0a7', 'finger, hand-o-down, point', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(690, 'hand-point-down', 'far fa-hand-point-down', 'Hand Pointing Down', 'Otros', 'regular', 'f0a7', 'finger, hand-o-down, point', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(691, 'hand-point-left', 'fas fa-hand-point-left', 'Hand Pointing Left', 'Otros', 'solid', 'f0a5', 'back, finger, hand-o-left, left, point, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(692, 'hand-point-left', 'far fa-hand-point-left', 'Hand Pointing Left', 'Otros', 'regular', 'f0a5', 'back, finger, hand-o-left, left, point, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(693, 'hand-point-right', 'fas fa-hand-point-right', 'Hand Pointing Right', 'Otros', 'solid', 'f0a4', 'finger, forward, hand-o-right, next, point, right', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(694, 'hand-point-right', 'far fa-hand-point-right', 'Hand Pointing Right', 'Otros', 'regular', 'f0a4', 'finger, forward, hand-o-right, next, point, right', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(695, 'hand-point-up', 'fas fa-hand-point-up', 'Hand Pointing Up', 'Otros', 'solid', 'f0a6', 'finger, hand-o-up, point', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(696, 'hand-point-up', 'far fa-hand-point-up', 'Hand Pointing Up', 'Otros', 'regular', 'f0a6', 'finger, hand-o-up, point', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(697, 'hand-pointer', 'fas fa-hand-pointer', 'Pointer (Hand)', 'Otros', 'solid', 'f25a', 'arrow, cursor, select', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(698, 'hand-pointer', 'far fa-hand-pointer', 'Pointer (Hand)', 'Otros', 'regular', 'f25a', 'arrow, cursor, select', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(699, 'hand-rock', 'fas fa-hand-rock', 'Rock (Hand)', 'Otros', 'solid', 'f255', 'fist, game, roshambo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(700, 'hand-rock', 'far fa-hand-rock', 'Rock (Hand)', 'Otros', 'regular', 'f255', 'fist, game, roshambo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(701, 'hand-scissors', 'fas fa-hand-scissors', 'Scissors (Hand)', 'Otros', 'solid', 'f257', 'cut, game, roshambo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(702, 'hand-scissors', 'far fa-hand-scissors', 'Scissors (Hand)', 'Otros', 'regular', 'f257', 'cut, game, roshambo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(703, 'hand-spock', 'fas fa-hand-spock', 'Spock (Hand)', 'Otros', 'solid', 'f259', 'live long, prosper, salute, star trek, vulcan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(704, 'hand-spock', 'far fa-hand-spock', 'Spock (Hand)', 'Otros', 'regular', 'f259', 'live long, prosper, salute, star trek, vulcan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(705, 'hands', 'fas fa-hands', 'Hands', 'Otros', 'solid', 'f4c2', 'carry, hold, lift', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(706, 'hands-helping', 'fas fa-hands-helping', 'Helping Hands', 'Otros', 'solid', 'f4c4', 'aid, assistance, handshake, partnership, volunteering', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(707, 'handshake', 'fas fa-handshake', 'Handshake', 'Otros', 'solid', 'f2b5', 'agreement, greeting, meeting, partnership', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(708, 'handshake', 'far fa-handshake', 'Handshake', 'Otros', 'regular', 'f2b5', 'agreement, greeting, meeting, partnership', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(709, 'hanukiah', 'fas fa-hanukiah', 'Hanukiah', 'Otros', 'solid', 'f6e6', 'candle, hanukkah, jewish, judaism, light', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(710, 'hard-hat', 'fas fa-hard-hat', 'Hard Hat', 'Otros', 'solid', 'f807', 'construction, hardhat, helmet, safety', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(711, 'hashtag', 'fas fa-hashtag', 'Hashtag', 'Otros', 'solid', 'f292', 'Twitter, instagram, pound, social media, tag', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(712, 'hat-cowboy', 'fas fa-hat-cowboy', 'Cowboy Hat', 'Otros', 'solid', 'f8c0', 'buckaroo, horse, jackeroo, john b., old west, pardner, ranch, rancher, rodeo, western, wrangler', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(713, 'hat-cowboy-side', 'fas fa-hat-cowboy-side', 'Cowboy Hat Side', 'Otros', 'solid', 'f8c1', 'buckaroo, horse, jackeroo, john b., old west, pardner, ranch, rancher, rodeo, western, wrangler', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(714, 'hat-wizard', 'fas fa-hat-wizard', 'Wizard\'s Hat', 'Otros', 'solid', 'f6e8', 'Dungeons & Dragons, accessory, buckle, clothing, d&d, dnd, fantasy, halloween, head, holiday, mage, magic, pointy, witch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(715, 'haykal', 'fas fa-haykal', 'Haykal', 'Otros', 'solid', 'f666', 'bahai, bah?\'?, star', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(716, 'hdd', 'fas fa-hdd', 'HDD', 'Otros', 'solid', 'f0a0', 'cpu, hard drive, harddrive, machine, save, storage', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(717, 'hdd', 'far fa-hdd', 'HDD', 'Otros', 'regular', 'f0a0', 'cpu, hard drive, harddrive, machine, save, storage', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(718, 'heading', 'fas fa-heading', 'heading', 'Editores', 'solid', 'f1dc', 'format, header, text, title', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(719, 'headphones', 'fas fa-headphones', 'headphones', 'Otros', 'solid', 'f025', 'audio, listen, music, sound, speaker', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(720, 'headphones-alt', 'fas fa-headphones-alt', 'Alternate Headphones', 'Otros', 'solid', 'f58f', 'audio, listen, music, sound, speaker', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(721, 'headset', 'fas fa-headset', 'Headset', 'Otros', 'solid', 'f590', 'audio, gamer, gaming, listen, live chat, microphone, shot caller, sound, support, telemarketer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(722, 'heart', 'fas fa-heart', 'Heart', 'Otros', 'solid', 'f004', 'favorite, like, love, relationship, valentine', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(723, 'heart', 'far fa-heart', 'Heart', 'Otros', 'regular', 'f004', 'favorite, like, love, relationship, valentine', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(724, 'heart-broken', 'fas fa-heart-broken', 'Heart Broken', 'Otros', 'solid', 'f7a9', 'breakup, crushed, dislike, dumped, grief, love, lovesick, relationship, sad', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(725, 'heartbeat', 'fas fa-heartbeat', 'Heartbeat', 'Otros', 'solid', 'f21e', 'ekg, electrocardiogram, health, lifeline, vital signs', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(726, 'helicopter', 'fas fa-helicopter', 'Helicopter', 'Otros', 'solid', 'f533', 'airwolf, apache, chopper, flight, fly, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(727, 'highlighter', 'fas fa-highlighter', 'Highlighter', 'Otros', 'solid', 'f591', 'edit, marker, sharpie, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(728, 'hiking', 'fas fa-hiking', 'Hiking', 'Otros', 'solid', 'f6ec', 'activity, backpack, fall, fitness, outdoors, person, seasonal, walking', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(729, 'hippo', 'fas fa-hippo', 'Hippo', 'Otros', 'solid', 'f6ed', 'animal, fauna, hippopotamus, hungry, mammal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(730, 'hips', 'fab fa-hips', 'Hips', 'Marcas', 'brands', 'f452', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(731, 'hire-a-helper', 'fab fa-hire-a-helper', 'HireAHelper', 'Marcas', 'brands', 'f3b0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(732, 'history', 'fas fa-history', 'History', 'Otros', 'solid', 'f1da', 'Rewind, clock, reverse, time, time machine', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(733, 'hockey-puck', 'fas fa-hockey-puck', 'Hockey Puck', 'Otros', 'solid', 'f453', 'ice, nhl, sport', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(734, 'holly-berry', 'fas fa-holly-berry', 'Holly Berry', 'Otros', 'solid', 'f7aa', 'catwoman, christmas, decoration, flora, halle, holiday, ororo munroe, plant, storm, xmas', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(735, 'home', 'fas fa-home', 'home', 'Otros', 'solid', 'f015', 'abode, building, house, main', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(736, 'hooli', 'fab fa-hooli', 'Hooli', 'Marcas', 'brands', 'f427', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(737, 'hornbill', 'fab fa-hornbill', 'Hornbill', 'Marcas', 'brands', 'f592', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(738, 'horse', 'fas fa-horse', 'Horse', 'Otros', 'solid', 'f6f0', 'equus, fauna, mammmal, mare, neigh, pony', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(739, 'horse-head', 'fas fa-horse-head', 'Horse Head', 'Otros', 'solid', 'f7ab', 'equus, fauna, mammmal, mare, neigh, pony', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(740, 'hospital', 'fas fa-hospital', 'hospital', 'Medicina', 'solid', 'f0f8', 'building, emergency room, medical center', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(741, 'hospital', 'far fa-hospital', 'hospital', 'Medicina', 'regular', 'f0f8', 'building, emergency room, medical center', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(742, 'hospital-alt', 'fas fa-hospital-alt', 'Alternate Hospital', 'Medicina', 'solid', 'f47d', 'building, emergency room, medical center', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(743, 'hospital-symbol', 'fas fa-hospital-symbol', 'Hospital Symbol', 'Otros', 'solid', 'f47e', 'clinic, emergency, map', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(744, 'hot-tub', 'fas fa-hot-tub', 'Hot Tub', 'Otros', 'solid', 'f593', 'bath, jacuzzi, massage, sauna, spa', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(745, 'hotdog', 'fas fa-hotdog', 'Hot Dog', 'Otros', 'solid', 'f80f', 'bun, chili, frankfurt, frankfurter, kosher, polish, sandwich, sausage, vienna, weiner', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(746, 'hotel', 'fas fa-hotel', 'Hotel', 'Otros', 'solid', 'f594', 'building, inn, lodging, motel, resort, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(747, 'hotjar', 'fab fa-hotjar', 'Hotjar', 'Marcas', 'brands', 'f3b1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(748, 'hourglass', 'fas fa-hourglass', 'Hourglass', 'Otros', 'solid', 'f254', 'hour, minute, sand, stopwatch, time', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(749, 'hourglass', 'far fa-hourglass', 'Hourglass', 'Otros', 'regular', 'f254', 'hour, minute, sand, stopwatch, time', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(750, 'hourglass-end', 'fas fa-hourglass-end', 'Hourglass End', 'Otros', 'solid', 'f253', 'hour, minute, sand, stopwatch, time', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(751, 'hourglass-half', 'fas fa-hourglass-half', 'Hourglass Half', 'Otros', 'solid', 'f252', 'hour, minute, sand, stopwatch, time', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(752, 'hourglass-start', 'fas fa-hourglass-start', 'Hourglass Start', 'Otros', 'solid', 'f251', 'hour, minute, sand, stopwatch, time', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(753, 'house-damage', 'fas fa-house-damage', 'Damaged House', 'Otros', 'solid', 'f6f1', 'building, devastation, disaster, home, insurance', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(754, 'houzz', 'fab fa-houzz', 'Houzz', 'Marcas', 'brands', 'f27c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(755, 'hryvnia', 'fas fa-hryvnia', 'Hryvnia', 'Otros', 'solid', 'f6f2', 'currency, money, ukraine, ukrainian', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(756, 'html5', 'fab fa-html5', 'HTML 5 Logo', 'Marcas', 'brands', 'f13b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(757, 'hubspot', 'fab fa-hubspot', 'HubSpot', 'Marcas', 'brands', 'f3b2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(758, 'i-cursor', 'fas fa-i-cursor', 'I Beam Cursor', 'Otros', 'solid', 'f246', 'editing, i-beam, type, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(759, 'ice-cream', 'fas fa-ice-cream', 'Ice Cream', 'Otros', 'solid', 'f810', 'chocolate, cone, dessert, frozen, scoop, sorbet, vanilla, yogurt', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(760, 'icicles', 'fas fa-icicles', 'Icicles', 'Otros', 'solid', 'f7ad', 'cold, frozen, hanging, ice, seasonal, sharp', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(761, 'icons', 'fas fa-icons', 'Icons', 'Otros', 'solid', 'f86d', 'bolt, emoji, heart, image, music, photo, symbols', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(762, 'id-badge', 'fas fa-id-badge', 'Identification Badge', 'Otros', 'solid', 'f2c1', 'address, contact, identification, license, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(763, 'id-badge', 'far fa-id-badge', 'Identification Badge', 'Otros', 'regular', 'f2c1', 'address, contact, identification, license, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(764, 'id-card', 'fas fa-id-card', 'Identification Card', 'Otros', 'solid', 'f2c2', 'contact, demographics, document, identification, issued, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(765, 'id-card', 'far fa-id-card', 'Identification Card', 'Otros', 'regular', 'f2c2', 'contact, demographics, document, identification, issued, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(766, 'id-card-alt', 'fas fa-id-card-alt', 'Alternate Identification Card', 'Otros', 'solid', 'f47f', 'contact, demographics, document, identification, issued, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(767, 'igloo', 'fas fa-igloo', 'Igloo', 'Otros', 'solid', 'f7ae', 'dome, dwelling, eskimo, home, house, ice, snow', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(768, 'image', 'fas fa-image', 'Image', 'Otros', 'solid', 'f03e', 'album, landscape, photo, picture', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(769, 'image', 'far fa-image', 'Image', 'Otros', 'regular', 'f03e', 'album, landscape, photo, picture', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(770, 'images', 'fas fa-images', 'Images', 'Otros', 'solid', 'f302', 'album, landscape, photo, picture', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(771, 'images', 'far fa-images', 'Images', 'Otros', 'regular', 'f302', 'album, landscape, photo, picture', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(772, 'imdb', 'fab fa-imdb', 'IMDB', 'Marcas', 'brands', 'f2d8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(773, 'inbox', 'fas fa-inbox', 'inbox', 'Otros', 'solid', 'f01c', 'archive, desk, email, mail, message', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(774, 'indent', 'fas fa-indent', 'Indent', 'Otros', 'solid', 'f03c', 'align, justify, paragraph, tab', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(775, 'industry', 'fas fa-industry', 'Industry', 'Otros', 'solid', 'f275', 'building, factory, industrial, manufacturing, mill, warehouse', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(776, 'infinity', 'fas fa-infinity', 'Infinity', 'Otros', 'solid', 'f534', 'eternity, forever, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(777, 'info', 'fas fa-info', 'Info', 'Editores', 'solid', 'f129', 'details, help, information, more, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(778, 'info-circle', 'fas fa-info-circle', 'Info Circle', 'Editores', 'solid', 'f05a', 'details, help, information, more, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(779, 'instagram', 'fab fa-instagram', 'Instagram', 'Marcas', 'brands', 'f16d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(780, 'intercom', 'fab fa-intercom', 'Intercom', 'Marcas', 'brands', 'f7af', 'app, customer, messenger', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(781, 'internet-explorer', 'fab fa-internet-explorer', 'Internet-explorer', 'Marcas', 'brands', 'f26b', 'browser, ie', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(782, 'invision', 'fab fa-invision', 'InVision', 'Marcas', 'brands', 'f7b0', 'app, design, interface', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(783, 'ioxhost', 'fab fa-ioxhost', 'ioxhost', 'Marcas', 'brands', 'f208', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(784, 'italic', 'fas fa-italic', 'italic', 'Editores', 'solid', 'f033', 'edit, emphasis, font, format, text, type', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(785, 'itch-io', 'fab fa-itch-io', 'itch.io', 'Marcas', 'brands', 'f83a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(786, 'itunes', 'fab fa-itunes', 'iTunes', 'Marcas', 'brands', 'f3b4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(787, 'itunes-note', 'fab fa-itunes-note', 'Itunes Note', 'Marcas', 'brands', 'f3b5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(788, 'java', 'fab fa-java', 'Java', 'Marcas', 'brands', 'f4e4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(789, 'jedi', 'fas fa-jedi', 'Jedi', 'Otros', 'solid', 'f669', 'crest, force, sith, skywalker, star wars, yoda', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(790, 'jedi-order', 'fab fa-jedi-order', 'Jedi Order', 'Marcas', 'brands', 'f50e', 'star wars', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(791, 'jenkins', 'fab fa-jenkins', 'Jenkis', 'Marcas', 'brands', 'f3b6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(792, 'jira', 'fab fa-jira', 'Jira', 'Marcas', 'brands', 'f7b1', 'atlassian', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(793, 'joget', 'fab fa-joget', 'Joget', 'Marcas', 'brands', 'f3b7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(794, 'joint', 'fas fa-joint', 'Joint', 'Otros', 'solid', 'f595', 'blunt, cannabis, doobie, drugs, marijuana, roach, smoke, smoking, spliff', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(795, 'joomla', 'fab fa-joomla', 'Joomla Logo', 'Marcas', 'brands', 'f1aa', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(796, 'journal-whills', 'fas fa-journal-whills', 'Journal of the Whills', 'Otros', 'solid', 'f66a', 'book, force, jedi, sith, star wars, yoda', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(797, 'js', 'fab fa-js', 'JavaScript (JS)', 'Marcas', 'brands', 'f3b8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(798, 'js-square', 'fab fa-js-square', 'JavaScript (JS) Square', 'Marcas', 'brands', 'f3b9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(799, 'jsfiddle', 'fab fa-jsfiddle', 'jsFiddle', 'Marcas', 'brands', 'f1cc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(800, 'kaaba', 'fas fa-kaaba', 'Kaaba', 'Otros', 'solid', 'f66b', 'building, cube, islam, muslim', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(801, 'kaggle', 'fab fa-kaggle', 'Kaggle', 'Marcas', 'brands', 'f5fa', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(802, 'key', 'fas fa-key', 'key', 'Otros', 'solid', 'f084', 'lock, password, private, secret, unlock', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(803, 'keybase', 'fab fa-keybase', 'Keybase', 'Marcas', 'brands', 'f4f5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(804, 'keyboard', 'fas fa-keyboard', 'Keyboard', 'Otros', 'solid', 'f11c', 'accessory, edit, input, text, type, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(805, 'keyboard', 'far fa-keyboard', 'Keyboard', 'Otros', 'regular', 'f11c', 'accessory, edit, input, text, type, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(806, 'keycdn', 'fab fa-keycdn', 'KeyCDN', 'Marcas', 'brands', 'f3ba', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(807, 'khanda', 'fas fa-khanda', 'Khanda', 'Otros', 'solid', 'f66d', 'chakkar, sikh, sikhism, sword', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(808, 'kickstarter', 'fab fa-kickstarter', 'Kickstarter', 'Marcas', 'brands', 'f3bb', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(809, 'kickstarter-k', 'fab fa-kickstarter-k', 'Kickstarter K', 'Marcas', 'brands', 'f3bc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(810, 'kiss', 'fas fa-kiss', 'Kissing Face', 'Otros', 'solid', 'f596', 'beso, emoticon, face, love, smooch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(811, 'kiss', 'far fa-kiss', 'Kissing Face', 'Otros', 'regular', 'f596', 'beso, emoticon, face, love, smooch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(812, 'kiss-beam', 'fas fa-kiss-beam', 'Kissing Face With Smiling Eyes', 'Otros', 'solid', 'f597', 'beso, emoticon, face, love, smooch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(813, 'kiss-beam', 'far fa-kiss-beam', 'Kissing Face With Smiling Eyes', 'Otros', 'regular', 'f597', 'beso, emoticon, face, love, smooch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(814, 'kiss-wink-heart', 'fas fa-kiss-wink-heart', 'Face Blowing a Kiss', 'Otros', 'solid', 'f598', 'beso, emoticon, face, love, smooch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(815, 'kiss-wink-heart', 'far fa-kiss-wink-heart', 'Face Blowing a Kiss', 'Otros', 'regular', 'f598', 'beso, emoticon, face, love, smooch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(816, 'kiwi-bird', 'fas fa-kiwi-bird', 'Kiwi Bird', 'Otros', 'solid', 'f535', 'bird, fauna, new zealand', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(817, 'korvue', 'fab fa-korvue', 'KORVUE', 'Marcas', 'brands', 'f42f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(818, 'landmark', 'fas fa-landmark', 'Landmark', 'Otros', 'solid', 'f66f', 'building, historic, memorable, monument, politics', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(819, 'language', 'fas fa-language', 'Language', 'Otros', 'solid', 'f1ab', 'dialect, idiom, localize, speech, translate, vernacular', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(820, 'laptop', 'fas fa-laptop', 'Laptop', 'Otros', 'solid', 'f109', 'computer, cpu, dell, demo, device, mac, macbook, machine, pc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(821, 'laptop-code', 'fas fa-laptop-code', 'Laptop Code', 'Otros', 'solid', 'f5fc', 'computer, cpu, dell, demo, develop, device, mac, macbook, machine, pc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(822, 'laptop-medical', 'fas fa-laptop-medical', 'Laptop Medical', 'Otros', 'solid', 'f812', 'computer, device, ehr, electronic health records, history', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(823, 'laravel', 'fab fa-laravel', 'Laravel', 'Marcas', 'brands', 'f3bd', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(824, 'lastfm', 'fab fa-lastfm', 'last.fm', 'Marcas', 'brands', 'f202', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(825, 'lastfm-square', 'fab fa-lastfm-square', 'last.fm Square', 'Marcas', 'brands', 'f203', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(826, 'laugh', 'fas fa-laugh', 'Grinning Face With Big Eyes', 'Otros', 'solid', 'f599', 'LOL, emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(827, 'laugh', 'far fa-laugh', 'Grinning Face With Big Eyes', 'Otros', 'regular', 'f599', 'LOL, emoticon, face, laugh, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(828, 'laugh-beam', 'fas fa-laugh-beam', 'Laugh Face with Beaming Eyes', 'Otros', 'solid', 'f59a', 'LOL, emoticon, face, happy, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(829, 'laugh-beam', 'far fa-laugh-beam', 'Laugh Face with Beaming Eyes', 'Otros', 'regular', 'f59a', 'LOL, emoticon, face, happy, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(830, 'laugh-squint', 'fas fa-laugh-squint', 'Laughing Squinting Face', 'Otros', 'solid', 'f59b', 'LOL, emoticon, face, happy, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(831, 'laugh-squint', 'far fa-laugh-squint', 'Laughing Squinting Face', 'Otros', 'regular', 'f59b', 'LOL, emoticon, face, happy, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(832, 'laugh-wink', 'fas fa-laugh-wink', 'Laughing Winking Face', 'Otros', 'solid', 'f59c', 'LOL, emoticon, face, happy, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(833, 'laugh-wink', 'far fa-laugh-wink', 'Laughing Winking Face', 'Otros', 'regular', 'f59c', 'LOL, emoticon, face, happy, smile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(834, 'layer-group', 'fas fa-layer-group', 'Layer Group', 'Otros', 'solid', 'f5fd', 'arrange, develop, layers, map, stack', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(835, 'leaf', 'fas fa-leaf', 'leaf', 'Otros', 'solid', 'f06c', 'eco, flora, nature, plant, vegan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(836, 'leanpub', 'fab fa-leanpub', 'Leanpub', 'Marcas', 'brands', 'f212', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(837, 'lemon', 'fas fa-lemon', 'Lemon', 'Otros', 'solid', 'f094', 'citrus, lemonade, lime, tart', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(838, 'lemon', 'far fa-lemon', 'Lemon', 'Otros', 'regular', 'f094', 'citrus, lemonade, lime, tart', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(839, 'less', 'fab fa-less', 'Less', 'Marcas', 'brands', 'f41d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(840, 'less-than', 'fas fa-less-than', 'Less Than', 'Otros', 'solid', 'f536', 'arithmetic, compare, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(841, 'less-than-equal', 'fas fa-less-than-equal', 'Less Than Equal To', 'Otros', 'solid', 'f537', 'arithmetic, compare, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(842, 'level-down-alt', 'fas fa-level-down-alt', 'Alternate Level Down', 'Otros', 'solid', 'f3be', 'arrow, level-down', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(843, 'level-up-alt', 'fas fa-level-up-alt', 'Alternate Level Up', 'Otros', 'solid', 'f3bf', 'arrow, level-up', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(844, 'life-ring', 'fas fa-life-ring', 'Life Ring', 'Otros', 'solid', 'f1cd', 'coast guard, help, overboard, save, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(845, 'life-ring', 'far fa-life-ring', 'Life Ring', 'Otros', 'regular', 'f1cd', 'coast guard, help, overboard, save, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(846, 'lightbulb', 'fas fa-lightbulb', 'Lightbulb', 'Otros', 'solid', 'f0eb', 'energy, idea, inspiration, light', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(847, 'lightbulb', 'far fa-lightbulb', 'Lightbulb', 'Otros', 'regular', 'f0eb', 'energy, idea, inspiration, light', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(848, 'line', 'fab fa-line', 'Line', 'Marcas', 'brands', 'f3c0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(849, 'link', 'fas fa-link', 'Link', 'Otros', 'solid', 'f0c1', 'attach, attachment, chain, connect', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(850, 'linkedin', 'fab fa-linkedin', 'LinkedIn', 'Marcas', 'brands', 'f08c', 'linkedin-square', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(851, 'linkedin-in', 'fab fa-linkedin-in', 'LinkedIn In', 'Marcas', 'brands', 'f0e1', 'linkedin', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(852, 'linode', 'fab fa-linode', 'Linode', 'Marcas', 'brands', 'f2b8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(853, 'linux', 'fab fa-linux', 'Linux', 'Marcas', 'brands', 'f17c', 'tux', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(854, 'lira-sign', 'fas fa-lira-sign', 'Turkish Lira Sign', 'Otros', 'solid', 'f195', 'currency, money, try, turkish', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(855, 'list', 'fas fa-list', 'List', 'Otros', 'solid', 'f03a', 'checklist, completed, done, finished, ol, todo, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(856, 'list-alt', 'fas fa-list-alt', 'Alternate List', 'Otros', 'solid', 'f022', 'checklist, completed, done, finished, ol, todo, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(857, 'list-alt', 'far fa-list-alt', 'Alternate List', 'Otros', 'regular', 'f022', 'checklist, completed, done, finished, ol, todo, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(858, 'list-ol', 'fas fa-list-ol', 'list-ol', 'Otros', 'solid', 'f0cb', 'checklist, completed, done, finished, numbers, ol, todo, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(859, 'list-ul', 'fas fa-list-ul', 'list-ul', 'Otros', 'solid', 'f0ca', 'checklist, completed, done, finished, ol, todo, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(860, 'location-arrow', 'fas fa-location-arrow', 'location-arrow', 'Otros', 'solid', 'f124', 'address, compass, coordinate, direction, gps, map, navigation, place', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(861, 'lock', 'fas fa-lock', 'lock', 'Otros', 'solid', 'f023', 'admin, lock, open, password, private, protect, security', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(862, 'lock-open', 'fas fa-lock-open', 'Lock Open', 'Otros', 'solid', 'f3c1', 'admin, lock, open, password, private, protect, security', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(863, 'long-arrow-alt-down', 'fas fa-long-arrow-alt-down', 'Alternate Long Arrow Down', 'Otros', 'solid', 'f309', 'download, long-arrow-down', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(864, 'long-arrow-alt-left', 'fas fa-long-arrow-alt-left', 'Alternate Long Arrow Left', 'Otros', 'solid', 'f30a', 'back, long-arrow-left, previous', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(865, 'long-arrow-alt-right', 'fas fa-long-arrow-alt-right', 'Alternate Long Arrow Right', 'Otros', 'solid', 'f30b', 'forward, long-arrow-right, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(866, 'long-arrow-alt-up', 'fas fa-long-arrow-alt-up', 'Alternate Long Arrow Up', 'Otros', 'solid', 'f30c', 'long-arrow-up, upload', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(867, 'low-vision', 'fas fa-low-vision', 'Low Vision', 'Otros', 'solid', 'f2a8', 'blind, eye, sight', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(868, 'luggage-cart', 'fas fa-luggage-cart', 'Luggage Cart', 'Otros', 'solid', 'f59d', 'bag, baggage, suitcase, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(869, 'lyft', 'fab fa-lyft', 'lyft', 'Marcas', 'brands', 'f3c3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(870, 'magento', 'fab fa-magento', 'Magento', 'Marcas', 'brands', 'f3c4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(871, 'magic', 'fas fa-magic', 'magic', 'Otros', 'solid', 'f0d0', 'autocomplete, automatic, mage, magic, spell, wand, witch, wizard', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(872, 'magnet', 'fas fa-magnet', 'magnet', 'Otros', 'solid', 'f076', 'Attract, lodestone, tool', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(873, 'mail-bulk', 'fas fa-mail-bulk', 'Mail Bulk', 'Otros', 'solid', 'f674', 'archive, envelope, letter, post office, postal, postcard, send, stamp, usps', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(874, 'mailchimp', 'fab fa-mailchimp', 'Mailchimp', 'Marcas', 'brands', 'f59e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(875, 'male', 'fas fa-male', 'Male', 'Otros', 'solid', 'f183', 'human, man, person, profile, user', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(876, 'mandalorian', 'fab fa-mandalorian', 'Mandalorian', 'Marcas', 'brands', 'f50f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(877, 'map', 'fas fa-map', 'Map', 'Otros', 'solid', 'f279', 'address, coordinates, destination, gps, localize, location, map, navigation, paper, pin, place, point of interest, position, route, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(878, 'map', 'far fa-map', 'Map', 'Otros', 'regular', 'f279', 'address, coordinates, destination, gps, localize, location, map, navigation, paper, pin, place, point of interest, position, route, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(879, 'map-marked', 'fas fa-map-marked', 'Map Marked', 'Otros', 'solid', 'f59f', 'address, coordinates, destination, gps, localize, location, map, navigation, paper, pin, place, point of interest, position, route, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(880, 'map-marked-alt', 'fas fa-map-marked-alt', 'Alternate Map Marked', 'Otros', 'solid', 'f5a0', 'address, coordinates, destination, gps, localize, location, map, navigation, paper, pin, place, point of interest, position, route, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(881, 'map-marker', 'fas fa-map-marker', 'map-marker', 'Otros', 'solid', 'f041', 'address, coordinates, destination, gps, localize, location, map, navigation, paper, pin, place, point of interest, position, route, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(882, 'map-marker-alt', 'fas fa-map-marker-alt', 'Alternate Map Marker', 'Otros', 'solid', 'f3c5', 'address, coordinates, destination, gps, localize, location, map, navigation, paper, pin, place, point of interest, position, route, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(883, 'map-pin', 'fas fa-map-pin', 'Map Pin', 'Otros', 'solid', 'f276', 'address, agree, coordinates, destination, gps, localize, location, map, marker, navigation, pin, place, position, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(884, 'map-signs', 'fas fa-map-signs', 'Map Signs', 'Otros', 'solid', 'f277', 'directions, directory, map, signage, wayfinding', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(885, 'markdown', 'fab fa-markdown', 'Markdown', 'Marcas', 'brands', 'f60f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(886, 'marker', 'fas fa-marker', 'Marker', 'Otros', 'solid', 'f5a1', 'design, edit, sharpie, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(887, 'mars', 'fas fa-mars', 'Mars', 'Otros', 'solid', 'f222', 'male', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(888, 'mars-double', 'fas fa-mars-double', 'Mars Double', 'Otros', 'solid', 'f227', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(889, 'mars-stroke', 'fas fa-mars-stroke', 'Mars Stroke', 'Otros', 'solid', 'f229', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(890, 'mars-stroke-h', 'fas fa-mars-stroke-h', 'Mars Stroke Horizontal', 'Otros', 'solid', 'f22b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(891, 'mars-stroke-v', 'fas fa-mars-stroke-v', 'Mars Stroke Vertical', 'Otros', 'solid', 'f22a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(892, 'mask', 'fas fa-mask', 'Mask', 'Otros', 'solid', 'f6fa', 'carnivale, costume, disguise, halloween, secret, super hero', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(893, 'mastodon', 'fab fa-mastodon', 'Mastodon', 'Marcas', 'brands', 'f4f6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(894, 'maxcdn', 'fab fa-maxcdn', 'MaxCDN', 'Marcas', 'brands', 'f136', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(895, 'mdb', 'fab fa-mdb', 'Material Design for Bootstrap', 'Marcas', 'brands', 'f8ca', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(896, 'medal', 'fas fa-medal', 'Medal', 'Otros', 'solid', 'f5a2', 'award, ribbon, star, trophy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(897, 'medapps', 'fab fa-medapps', 'MedApps', 'Marcas', 'brands', 'f3c6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(898, 'medium', 'fab fa-medium', 'Medium', 'Marcas', 'brands', 'f23a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(899, 'medium-m', 'fab fa-medium-m', 'Medium M', 'Marcas', 'brands', 'f3c7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sara_iconos` (`id`, `Icono`, `IconoFull`, `IconoLabel`, `Categoria`, `Estilo`, `Unicode`, `PalabrasClave`, `created_at`, `updated_at`) VALUES
(900, 'medkit', 'fas fa-medkit', 'medkit', 'Otros', 'solid', 'f0fa', 'first aid, firstaid, health, help, support', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(901, 'medrt', 'fab fa-medrt', 'MRT', 'Marcas', 'brands', 'f3c8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(902, 'meetup', 'fab fa-meetup', 'Meetup', 'Marcas', 'brands', 'f2e0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(903, 'megaport', 'fab fa-megaport', 'Megaport', 'Marcas', 'brands', 'f5a3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(904, 'meh', 'fas fa-meh', 'Neutral Face', 'Otros', 'solid', 'f11a', 'emoticon, face, neutral, rating', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(905, 'meh', 'far fa-meh', 'Neutral Face', 'Otros', 'regular', 'f11a', 'emoticon, face, neutral, rating', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(906, 'meh-blank', 'fas fa-meh-blank', 'Face Without Mouth', 'Otros', 'solid', 'f5a4', 'emoticon, face, neutral, rating', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(907, 'meh-blank', 'far fa-meh-blank', 'Face Without Mouth', 'Otros', 'regular', 'f5a4', 'emoticon, face, neutral, rating', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(908, 'meh-rolling-eyes', 'fas fa-meh-rolling-eyes', 'Face With Rolling Eyes', 'Otros', 'solid', 'f5a5', 'emoticon, face, neutral, rating', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(909, 'meh-rolling-eyes', 'far fa-meh-rolling-eyes', 'Face With Rolling Eyes', 'Otros', 'regular', 'f5a5', 'emoticon, face, neutral, rating', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(910, 'memory', 'fas fa-memory', 'Memory', 'Otros', 'solid', 'f538', 'DIMM, RAM, hardware, storage, technology', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(911, 'mendeley', 'fab fa-mendeley', 'Mendeley', 'Marcas', 'brands', 'f7b3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(912, 'menorah', 'fas fa-menorah', 'Menorah', 'Otros', 'solid', 'f676', 'candle, hanukkah, jewish, judaism, light', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(913, 'mercury', 'fas fa-mercury', 'Mercury', 'Otros', 'solid', 'f223', 'transgender', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(914, 'meteor', 'fas fa-meteor', 'Meteor', 'Otros', 'solid', 'f753', 'armageddon, asteroid, comet, shooting star, space', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(915, 'microchip', 'fas fa-microchip', 'Microchip', 'Otros', 'solid', 'f2db', 'cpu, hardware, processor, technology', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(916, 'microphone', 'fas fa-microphone', 'microphone', 'Otros', 'solid', 'f130', 'audio, podcast, record, sing, sound, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(917, 'microphone-alt', 'fas fa-microphone-alt', 'Alternate Microphone', 'Otros', 'solid', 'f3c9', 'audio, podcast, record, sing, sound, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(918, 'microphone-alt-slash', 'fas fa-microphone-alt-slash', 'Alternate Microphone Slash', 'Otros', 'solid', 'f539', 'audio, disable, mute, podcast, record, sing, sound, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(919, 'microphone-slash', 'fas fa-microphone-slash', 'Microphone Slash', 'Otros', 'solid', 'f131', 'audio, disable, mute, podcast, record, sing, sound, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(920, 'microscope', 'fas fa-microscope', 'Microscope', 'Otros', 'solid', 'f610', 'electron, lens, optics, science, shrink', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(921, 'microsoft', 'fab fa-microsoft', 'Microsoft', 'Marcas', 'brands', 'f3ca', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(922, 'minus', 'fas fa-minus', 'minus', 'Otros', 'solid', 'f068', 'collapse, delete, hide, minify, negative, remove, trash', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(923, 'minus-circle', 'fas fa-minus-circle', 'Minus Circle', 'Otros', 'solid', 'f056', 'delete, hide, negative, remove, shape, trash', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(924, 'minus-square', 'fas fa-minus-square', 'Minus Square', 'Otros', 'solid', 'f146', 'collapse, delete, hide, minify, negative, remove, shape, trash', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(925, 'minus-square', 'far fa-minus-square', 'Minus Square', 'Otros', 'regular', 'f146', 'collapse, delete, hide, minify, negative, remove, shape, trash', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(926, 'mitten', 'fas fa-mitten', 'Mitten', 'Otros', 'solid', 'f7b5', 'clothing, cold, glove, hands, knitted, seasonal, warmth', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(927, 'mix', 'fab fa-mix', 'Mix', 'Marcas', 'brands', 'f3cb', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(928, 'mixcloud', 'fab fa-mixcloud', 'Mixcloud', 'Marcas', 'brands', 'f289', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(929, 'mizuni', 'fab fa-mizuni', 'Mizuni', 'Marcas', 'brands', 'f3cc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(930, 'mobile', 'fas fa-mobile', 'Mobile Phone', 'Otros', 'solid', 'f10b', 'apple, call, cell phone, cellphone, device, iphone, number, screen, telephone', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(931, 'mobile-alt', 'fas fa-mobile-alt', 'Alternate Mobile', 'Otros', 'solid', 'f3cd', 'apple, call, cell phone, cellphone, device, iphone, number, screen, telephone', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(932, 'modx', 'fab fa-modx', 'MODX', 'Marcas', 'brands', 'f285', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(933, 'monero', 'fab fa-monero', 'Monero', 'Marcas', 'brands', 'f3d0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(934, 'money-bill', 'fas fa-money-bill', 'Money Bill', 'Otros', 'solid', 'f0d6', 'buy, cash, checkout, money, payment, price, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(935, 'money-bill-alt', 'fas fa-money-bill-alt', 'Alternate Money Bill', 'Otros', 'solid', 'f3d1', 'buy, cash, checkout, money, payment, price, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(936, 'money-bill-alt', 'far fa-money-bill-alt', 'Alternate Money Bill', 'Otros', 'regular', 'f3d1', 'buy, cash, checkout, money, payment, price, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(937, 'money-bill-wave', 'fas fa-money-bill-wave', 'Wavy Money Bill', 'Otros', 'solid', 'f53a', 'buy, cash, checkout, money, payment, price, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(938, 'money-bill-wave-alt', 'fas fa-money-bill-wave-alt', 'Alternate Wavy Money Bill', 'Otros', 'solid', 'f53b', 'buy, cash, checkout, money, payment, price, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(939, 'money-check', 'fas fa-money-check', 'Money Check', 'Otros', 'solid', 'f53c', 'bank check, buy, checkout, cheque, money, payment, price, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(940, 'money-check-alt', 'fas fa-money-check-alt', 'Alternate Money Check', 'Otros', 'solid', 'f53d', 'bank check, buy, checkout, cheque, money, payment, price, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(941, 'monument', 'fas fa-monument', 'Monument', 'Otros', 'solid', 'f5a6', 'building, historic, landmark, memorable', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(942, 'moon', 'fas fa-moon', 'Moon', 'Otros', 'solid', 'f186', 'contrast, crescent, dark, lunar, night', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(943, 'moon', 'far fa-moon', 'Moon', 'Otros', 'regular', 'f186', 'contrast, crescent, dark, lunar, night', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(944, 'mortar-pestle', 'fas fa-mortar-pestle', 'Mortar Pestle', 'Medicina', 'solid', 'f5a7', 'crush, culinary, grind, medical, mix, pharmacy, prescription, spices', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(945, 'mosque', 'fas fa-mosque', 'Mosque', 'Otros', 'solid', 'f678', 'building, islam, landmark, muslim', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(946, 'motorcycle', 'fas fa-motorcycle', 'Motorcycle', 'Otros', 'solid', 'f21c', 'bike, machine, transportation, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(947, 'mountain', 'fas fa-mountain', 'Mountain', 'Otros', 'solid', 'f6fc', 'glacier, hiking, hill, landscape, travel, view', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(948, 'mouse', 'fas fa-mouse', 'Mouse', 'Otros', 'solid', 'f8cc', 'click, computer, cursor, input, peripheral', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(949, 'mouse-pointer', 'fas fa-mouse-pointer', 'Mouse Pointer', 'Otros', 'solid', 'f245', 'arrow, cursor, select', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(950, 'mug-hot', 'fas fa-mug-hot', 'Mug Hot', 'Otros', 'solid', 'f7b6', 'caliente, cocoa, coffee, cup, drink, holiday, hot chocolate, steam, tea, warmth', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(951, 'music', 'fas fa-music', 'Music', 'Otros', 'solid', 'f001', 'lyrics, melody, note, sing, sound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(952, 'napster', 'fab fa-napster', 'Napster', 'Marcas', 'brands', 'f3d2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(953, 'neos', 'fab fa-neos', 'Neos', 'Marcas', 'brands', 'f612', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(954, 'network-wired', 'fas fa-network-wired', 'Wired Network', 'Otros', 'solid', 'f6ff', 'computer, connect, ethernet, internet, intranet', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(955, 'neuter', 'fas fa-neuter', 'Neuter', 'Otros', 'solid', 'f22c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(956, 'newspaper', 'fas fa-newspaper', 'Newspaper', 'Otros', 'solid', 'f1ea', 'article, editorial, headline, journal, journalism, news, press', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(957, 'newspaper', 'far fa-newspaper', 'Newspaper', 'Otros', 'regular', 'f1ea', 'article, editorial, headline, journal, journalism, news, press', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(958, 'nimblr', 'fab fa-nimblr', 'Nimblr', 'Marcas', 'brands', 'f5a8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(959, 'node', 'fab fa-node', 'Node.js', 'Marcas', 'brands', 'f419', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(960, 'node-js', 'fab fa-node-js', 'Node.js JS', 'Marcas', 'brands', 'f3d3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(961, 'not-equal', 'fas fa-not-equal', 'Not Equal', 'Otros', 'solid', 'f53e', 'arithmetic, compare, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(962, 'notes-medical', 'fas fa-notes-medical', 'Medical Notes', 'Otros', 'solid', 'f481', 'clipboard, doctor, ehr, health, history, records', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(963, 'npm', 'fab fa-npm', 'npm', 'Marcas', 'brands', 'f3d4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(964, 'ns8', 'fab fa-ns8', 'NS8', 'Marcas', 'brands', 'f3d5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(965, 'nutritionix', 'fab fa-nutritionix', 'Nutritionix', 'Marcas', 'brands', 'f3d6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(966, 'object-group', 'fas fa-object-group', 'Object Group', 'Otros', 'solid', 'f247', 'combine, copy, design, merge, select', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(967, 'object-group', 'far fa-object-group', 'Object Group', 'Otros', 'regular', 'f247', 'combine, copy, design, merge, select', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(968, 'object-ungroup', 'fas fa-object-ungroup', 'Object Ungroup', 'Otros', 'solid', 'f248', 'copy, design, merge, select, separate', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(969, 'object-ungroup', 'far fa-object-ungroup', 'Object Ungroup', 'Otros', 'regular', 'f248', 'copy, design, merge, select, separate', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(970, 'odnoklassniki', 'fab fa-odnoklassniki', 'Odnoklassniki', 'Marcas', 'brands', 'f263', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(971, 'odnoklassniki-square', 'fab fa-odnoklassniki-square', 'Odnoklassniki Square', 'Marcas', 'brands', 'f264', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(972, 'oil-can', 'fas fa-oil-can', 'Oil Can', 'Otros', 'solid', 'f613', 'auto, crude, gasoline, grease, lubricate, petroleum', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(973, 'old-republic', 'fab fa-old-republic', 'Old Republic', 'Marcas', 'brands', 'f510', 'politics, star wars', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(974, 'om', 'fas fa-om', 'Om', 'Otros', 'solid', 'f679', 'buddhism, hinduism, jainism, mantra', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(975, 'opencart', 'fab fa-opencart', 'OpenCart', 'Marcas', 'brands', 'f23d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(976, 'openid', 'fab fa-openid', 'OpenID', 'Marcas', 'brands', 'f19b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(977, 'opera', 'fab fa-opera', 'Opera', 'Marcas', 'brands', 'f26a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(978, 'optin-monster', 'fab fa-optin-monster', 'Optin Monster', 'Marcas', 'brands', 'f23c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(979, 'orcid', 'fab fa-orcid', 'ORCID', 'Marcas', 'brands', 'f8d2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(980, 'osi', 'fab fa-osi', 'Open Source Initiative', 'Marcas', 'brands', 'f41a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(981, 'otter', 'fas fa-otter', 'Otter', 'Otros', 'solid', 'f700', 'animal, badger, fauna, fur, mammal, marten', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(982, 'outdent', 'fas fa-outdent', 'Outdent', 'Otros', 'solid', 'f03b', 'align, justify, paragraph, tab', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(983, 'page4', 'fab fa-page4', 'page4 Corporation', 'Marcas', 'brands', 'f3d7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(984, 'pagelines', 'fab fa-pagelines', 'Pagelines', 'Marcas', 'brands', 'f18c', 'eco, flora, leaf, leaves, nature, plant, tree', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(985, 'pager', 'fas fa-pager', 'Pager', 'Otros', 'solid', 'f815', 'beeper, cellphone, communication', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(986, 'paint-brush', 'fas fa-paint-brush', 'Paint Brush', 'Otros', 'solid', 'f1fc', 'acrylic, art, brush, color, fill, paint, pigment, watercolor', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(987, 'paint-roller', 'fas fa-paint-roller', 'Paint Roller', 'Otros', 'solid', 'f5aa', 'acrylic, art, brush, color, fill, paint, pigment, watercolor', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(988, 'palette', 'fas fa-palette', 'Palette', 'Otros', 'solid', 'f53f', 'acrylic, art, brush, color, fill, paint, pigment, watercolor', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(989, 'palfed', 'fab fa-palfed', 'Palfed', 'Marcas', 'brands', 'f3d8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(990, 'pallet', 'fas fa-pallet', 'Pallet', 'Otros', 'solid', 'f482', 'archive, box, inventory, shipping, warehouse', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(991, 'paper-plane', 'fas fa-paper-plane', 'Paper Plane', 'Otros', 'solid', 'f1d8', 'air, float, fold, mail, paper, send', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(992, 'paper-plane', 'far fa-paper-plane', 'Paper Plane', 'Otros', 'regular', 'f1d8', 'air, float, fold, mail, paper, send', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(993, 'paperclip', 'fas fa-paperclip', 'Paperclip', 'Otros', 'solid', 'f0c6', 'attach, attachment, connect, link', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(994, 'parachute-box', 'fas fa-parachute-box', 'Parachute Box', 'Otros', 'solid', 'f4cd', 'aid, assistance, rescue, supplies', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(995, 'paragraph', 'fas fa-paragraph', 'paragraph', 'Editores', 'solid', 'f1dd', 'edit, format, text, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(996, 'parking', 'fas fa-parking', 'Parking', 'Otros', 'solid', 'f540', 'auto, car, garage, meter', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(997, 'passport', 'fas fa-passport', 'Passport', 'Otros', 'solid', 'f5ab', 'document, id, identification, issued, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(998, 'pastafarianism', 'fas fa-pastafarianism', 'Pastafarianism', 'Otros', 'solid', 'f67b', 'agnosticism, atheism, flying spaghetti monster, fsm', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(999, 'paste', 'fas fa-paste', 'Paste', 'Otros', 'solid', 'f0ea', 'clipboard, copy, document, paper', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1000, 'patreon', 'fab fa-patreon', 'Patreon', 'Marcas', 'brands', 'f3d9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1001, 'pause', 'fas fa-pause', 'pause', 'Otros', 'solid', 'f04c', 'hold, wait', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1002, 'pause-circle', 'fas fa-pause-circle', 'Pause Circle', 'Otros', 'solid', 'f28b', 'hold, wait', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1003, 'pause-circle', 'far fa-pause-circle', 'Pause Circle', 'Otros', 'regular', 'f28b', 'hold, wait', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1004, 'paw', 'fas fa-paw', 'Paw', 'Otros', 'solid', 'f1b0', 'animal, cat, dog, pet, print', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1005, 'paypal', 'fab fa-paypal', 'Paypal', 'Marcas', 'brands', 'f1ed', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1006, 'peace', 'fas fa-peace', 'Peace', 'Otros', 'solid', 'f67c', 'serenity, tranquility, truce, war', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1007, 'pen', 'fas fa-pen', 'Pen', 'Otros', 'solid', 'f304', 'design, edit, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1008, 'pen-alt', 'fas fa-pen-alt', 'Alternate Pen', 'Otros', 'solid', 'f305', 'design, edit, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1009, 'pen-fancy', 'fas fa-pen-fancy', 'Pen Fancy', 'Otros', 'solid', 'f5ac', 'design, edit, fountain pen, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1010, 'pen-nib', 'fas fa-pen-nib', 'Pen Nib', 'Otros', 'solid', 'f5ad', 'design, edit, fountain pen, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1011, 'pen-square', 'fas fa-pen-square', 'Pen Square', 'Otros', 'solid', 'f14b', 'edit, pencil-square, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1012, 'pencil-alt', 'fas fa-pencil-alt', 'Alternate Pencil', 'Otros', 'solid', 'f303', 'design, edit, pencil, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1013, 'pencil-ruler', 'fas fa-pencil-ruler', 'Pencil Ruler', 'Otros', 'solid', 'f5ae', 'design, draft, draw, pencil', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1014, 'penny-arcade', 'fab fa-penny-arcade', 'Penny Arcade', 'Marcas', 'brands', 'f704', 'Dungeons & Dragons, d&d, dnd, fantasy, game, gaming, pax, tabletop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1015, 'people-carry', 'fas fa-people-carry', 'People Carry', 'Otros', 'solid', 'f4ce', 'box, carry, fragile, help, movers, package', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1016, 'pepper-hot', 'fas fa-pepper-hot', 'Hot Pepper', 'Otros', 'solid', 'f816', 'buffalo wings, capsicum, chili, chilli, habanero, jalapeno, mexican, spicy, tabasco, vegetable', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1017, 'percent', 'fas fa-percent', 'Percent', 'Otros', 'solid', 'f295', 'discount, fraction, proportion, rate, ratio', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1018, 'percentage', 'fas fa-percentage', 'Percentage', 'Otros', 'solid', 'f541', 'discount, fraction, proportion, rate, ratio', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1019, 'periscope', 'fab fa-periscope', 'Periscope', 'Marcas', 'brands', 'f3da', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1020, 'person-booth', 'fas fa-person-booth', 'Person Entering Booth', 'Otros', 'solid', 'f756', 'changing, changing room, election, human, person, vote, voting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1021, 'phabricator', 'fab fa-phabricator', 'Phabricator', 'Marcas', 'brands', 'f3db', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1022, 'phoenix-framework', 'fab fa-phoenix-framework', 'Phoenix Framework', 'Marcas', 'brands', 'f3dc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1023, 'phoenix-squadron', 'fab fa-phoenix-squadron', 'Phoenix Squadron', 'Marcas', 'brands', 'f511', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1024, 'phone', 'fas fa-phone', 'Phone', 'Otros', 'solid', 'f095', 'call, earphone, number, support, telephone, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1025, 'phone-alt', 'fas fa-phone-alt', 'Alternate Phone', 'Otros', 'solid', 'f879', 'call, earphone, number, support, telephone, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1026, 'phone-slash', 'fas fa-phone-slash', 'Phone Slash', 'Otros', 'solid', 'f3dd', 'call, cancel, earphone, mute, number, support, telephone, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1027, 'phone-square', 'fas fa-phone-square', 'Phone Square', 'Otros', 'solid', 'f098', 'call, earphone, number, support, telephone, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1028, 'phone-square-alt', 'fas fa-phone-square-alt', 'Alternate Phone Square', 'Otros', 'solid', 'f87b', 'call, earphone, number, support, telephone, voice', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1029, 'phone-volume', 'fas fa-phone-volume', 'Phone Volume', 'Otros', 'solid', 'f2a0', 'call, earphone, number, sound, support, telephone, voice, volume-control-phone', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1030, 'photo-video', 'fas fa-photo-video', 'Photo Video', 'Otros', 'solid', 'f87c', 'av, film, image, library, media', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1031, 'php', 'fab fa-php', 'PHP', 'Marcas', 'brands', 'f457', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1032, 'pied-piper', 'fab fa-pied-piper', 'Pied Piper Logo', 'Marcas', 'brands', 'f2ae', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1033, 'pied-piper-alt', 'fab fa-pied-piper-alt', 'Alternate Pied Piper Logo', 'Marcas', 'brands', 'f1a8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1034, 'pied-piper-hat', 'fab fa-pied-piper-hat', 'Pied Piper-hat', 'Marcas', 'brands', 'f4e5', 'clothing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1035, 'pied-piper-pp', 'fab fa-pied-piper-pp', 'Pied Piper PP Logo (Old)', 'Marcas', 'brands', 'f1a7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1036, 'piggy-bank', 'fas fa-piggy-bank', 'Piggy Bank', 'Otros', 'solid', 'f4d3', 'bank, save, savings', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1037, 'pills', 'fas fa-pills', 'Pills', 'Otros', 'solid', 'f484', 'drugs, medicine, prescription, tablets', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1038, 'pinterest', 'fab fa-pinterest', 'Pinterest', 'Marcas', 'brands', 'f0d2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1039, 'pinterest-p', 'fab fa-pinterest-p', 'Pinterest P', 'Marcas', 'brands', 'f231', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1040, 'pinterest-square', 'fab fa-pinterest-square', 'Pinterest Square', 'Marcas', 'brands', 'f0d3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1041, 'pizza-slice', 'fas fa-pizza-slice', 'Pizza Slice', 'Otros', 'solid', 'f818', 'cheese, chicago, italian, mozzarella, new york, pepperoni, pie, slice, teenage mutant ninja turtles, tomato', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1042, 'place-of-worship', 'fas fa-place-of-worship', 'Place of Worship', 'Otros', 'solid', 'f67f', 'building, church, holy, mosque, synagogue', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1043, 'plane', 'fas fa-plane', 'plane', 'Otros', 'solid', 'f072', 'airplane, destination, fly, location, mode, travel, trip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1044, 'plane-arrival', 'fas fa-plane-arrival', 'Plane Arrival', 'Otros', 'solid', 'f5af', 'airplane, arriving, destination, fly, land, landing, location, mode, travel, trip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1045, 'plane-departure', 'fas fa-plane-departure', 'Plane Departure', 'Otros', 'solid', 'f5b0', 'airplane, departing, destination, fly, location, mode, take off, taking off, travel, trip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1046, 'play', 'fas fa-play', 'play', 'Otros', 'solid', 'f04b', 'audio, music, playing, sound, start, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1047, 'play-circle', 'fas fa-play-circle', 'Play Circle', 'Otros', 'solid', 'f144', 'audio, music, playing, sound, start, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1048, 'play-circle', 'far fa-play-circle', 'Play Circle', 'Otros', 'regular', 'f144', 'audio, music, playing, sound, start, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1049, 'playstation', 'fab fa-playstation', 'PlayStation', 'Marcas', 'brands', 'f3df', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1050, 'plug', 'fas fa-plug', 'Plug', 'Otros', 'solid', 'f1e6', 'connect, electric, online, power', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1051, 'plus', 'fas fa-plus', 'plus', 'Otros', 'solid', 'f067', 'add, create, expand, new, positive, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1052, 'plus-circle', 'fas fa-plus-circle', 'Plus Circle', 'Otros', 'solid', 'f055', 'add, create, expand, new, positive, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1053, 'plus-square', 'fas fa-plus-square', 'Plus Square', 'Otros', 'solid', 'f0fe', 'add, create, expand, new, positive, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1054, 'plus-square', 'far fa-plus-square', 'Plus Square', 'Otros', 'regular', 'f0fe', 'add, create, expand, new, positive, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1055, 'podcast', 'fas fa-podcast', 'Podcast', 'Otros', 'solid', 'f2ce', 'audio, broadcast, music, sound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1056, 'poll', 'fas fa-poll', 'Poll', 'Otros', 'solid', 'f681', 'results, survey, trend, vote, voting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1057, 'poll-h', 'fas fa-poll-h', 'Poll H', 'Otros', 'solid', 'f682', 'results, survey, trend, vote, voting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1058, 'poo', 'fas fa-poo', 'Poo', 'Otros', 'solid', 'f2fe', 'crap, poop, shit, smile, turd', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1059, 'poo-storm', 'fas fa-poo-storm', 'Poo Storm', 'Otros', 'solid', 'f75a', 'bolt, cloud, euphemism, lightning, mess, poop, shit, turd', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1060, 'poop', 'fas fa-poop', 'Poop', 'Otros', 'solid', 'f619', 'crap, poop, shit, smile, turd', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1061, 'portrait', 'fas fa-portrait', 'Portrait', 'Otros', 'solid', 'f3e0', 'id, image, photo, picture, selfie', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1062, 'pound-sign', 'fas fa-pound-sign', 'Pound Sign', 'Otros', 'solid', 'f154', 'currency, gbp, money', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1063, 'power-off', 'fas fa-power-off', 'Power Off', 'Otros', 'solid', 'f011', 'cancel, computer, on, reboot, restart', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1064, 'pray', 'fas fa-pray', 'Pray', 'Otros', 'solid', 'f683', 'kneel, preach, religion, worship', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1065, 'praying-hands', 'fas fa-praying-hands', 'Praying Hands', 'Otros', 'solid', 'f684', 'kneel, preach, religion, worship', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1066, 'prescription', 'fas fa-prescription', 'Prescription', 'Medicina', 'solid', 'f5b1', 'drugs, medical, medicine, pharmacy, rx', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1067, 'prescription-bottle', 'fas fa-prescription-bottle', 'Prescription Bottle', 'Medicina', 'solid', 'f485', 'drugs, medical, medicine, pharmacy, rx', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1068, 'prescription-bottle-alt', 'fas fa-prescription-bottle-alt', 'Alternate Prescription Bottle', 'Medicina', 'solid', 'f486', 'drugs, medical, medicine, pharmacy, rx', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1069, 'print', 'fas fa-print', 'print', 'Otros', 'solid', 'f02f', 'business, copy, document, office, paper', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1070, 'procedures', 'fas fa-procedures', 'Procedures', 'Otros', 'solid', 'f487', 'EKG, bed, electrocardiogram, health, hospital, life, patient, vital', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1071, 'product-hunt', 'fab fa-product-hunt', 'Product Hunt', 'Marcas', 'brands', 'f288', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1072, 'project-diagram', 'fas fa-project-diagram', 'Project Diagram', 'Otros', 'solid', 'f542', 'chart, graph, network, pert', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1073, 'pushed', 'fab fa-pushed', 'Pushed', 'Marcas', 'brands', 'f3e1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1074, 'puzzle-piece', 'fas fa-puzzle-piece', 'Puzzle Piece', 'Otros', 'solid', 'f12e', 'add-on, addon, game, section', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1075, 'python', 'fab fa-python', 'Python', 'Marcas', 'brands', 'f3e2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1076, 'qq', 'fab fa-qq', 'QQ', 'Marcas', 'brands', 'f1d6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1077, 'qrcode', 'fas fa-qrcode', 'qrcode', 'Editores', 'solid', 'f029', 'barcode, info, information, scan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1078, 'question', 'fas fa-question', 'Question', 'Editores', 'solid', 'f128', 'help, information, support, unknown', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1079, 'question-circle', 'fas fa-question-circle', 'Question Circle', 'Editores', 'solid', 'f059', 'help, information, support, unknown', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1080, 'question-circle', 'far fa-question-circle', 'Question Circle', 'Editores', 'regular', 'f059', 'help, information, support, unknown', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1081, 'quidditch', 'fas fa-quidditch', 'Quidditch', 'Otros', 'solid', 'f458', 'ball, bludger, broom, golden snitch, harry potter, hogwarts, quaffle, sport, wizard', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1082, 'quinscape', 'fab fa-quinscape', 'QuinScape', 'Marcas', 'brands', 'f459', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1083, 'quora', 'fab fa-quora', 'Quora', 'Marcas', 'brands', 'f2c4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1084, 'quote-left', 'fas fa-quote-left', 'quote-left', 'Otros', 'solid', 'f10d', 'mention, note, phrase, text, type', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1085, 'quote-right', 'fas fa-quote-right', 'quote-right', 'Otros', 'solid', 'f10e', 'mention, note, phrase, text, type', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1086, 'quran', 'fas fa-quran', 'Quran', 'Otros', 'solid', 'f687', 'book, islam, muslim, religion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1087, 'r-project', 'fab fa-r-project', 'R Project', 'Marcas', 'brands', 'f4f7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1088, 'radiation', 'fas fa-radiation', 'Radiation', 'Otros', 'solid', 'f7b9', 'danger, dangerous, deadly, hazard, nuclear, radioactive, warning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1089, 'radiation-alt', 'fas fa-radiation-alt', 'Alternate Radiation', 'Otros', 'solid', 'f7ba', 'danger, dangerous, deadly, hazard, nuclear, radioactive, warning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1090, 'rainbow', 'fas fa-rainbow', 'Rainbow', 'Otros', 'solid', 'f75b', 'gold, leprechaun, prism, rain, sky', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1091, 'random', 'fas fa-random', 'random', 'Otros', 'solid', 'f074', 'arrows, shuffle, sort, swap, switch, transfer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1092, 'raspberry-pi', 'fab fa-raspberry-pi', 'Raspberry Pi', 'Marcas', 'brands', 'f7bb', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1093, 'ravelry', 'fab fa-ravelry', 'Ravelry', 'Marcas', 'brands', 'f2d9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1094, 'react', 'fab fa-react', 'React', 'Marcas', 'brands', 'f41b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1095, 'reacteurope', 'fab fa-reacteurope', 'ReactEurope', 'Marcas', 'brands', 'f75d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1096, 'readme', 'fab fa-readme', 'ReadMe', 'Marcas', 'brands', 'f4d5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1097, 'rebel', 'fab fa-rebel', 'Rebel Alliance', 'Marcas', 'brands', 'f1d0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1098, 'receipt', 'fas fa-receipt', 'Receipt', 'Otros', 'solid', 'f543', 'check, invoice, money, pay, table', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1099, 'record-vinyl', 'fas fa-record-vinyl', 'Record Vinyl', 'Otros', 'solid', 'f8d9', 'LP, album, analog, music, phonograph, sound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1100, 'recycle', 'fas fa-recycle', 'Recycle', 'Otros', 'solid', 'f1b8', 'Waste, compost, garbage, reuse, trash', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1101, 'red-river', 'fab fa-red-river', 'red river', 'Marcas', 'brands', 'f3e3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1102, 'reddit', 'fab fa-reddit', 'reddit Logo', 'Marcas', 'brands', 'f1a1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1103, 'reddit-alien', 'fab fa-reddit-alien', 'reddit Alien', 'Marcas', 'brands', 'f281', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1104, 'reddit-square', 'fab fa-reddit-square', 'reddit Square', 'Marcas', 'brands', 'f1a2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1105, 'redhat', 'fab fa-redhat', 'Redhat', 'Marcas', 'brands', 'f7bc', 'linux, operating system, os', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1106, 'redo', 'fas fa-redo', 'Redo', 'Otros', 'solid', 'f01e', 'forward, refresh, reload, repeat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1107, 'redo-alt', 'fas fa-redo-alt', 'Alternate Redo', 'Otros', 'solid', 'f2f9', 'forward, refresh, reload, repeat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1108, 'registered', 'fas fa-registered', 'Registered Trademark', 'Otros', 'solid', 'f25d', 'copyright, mark, trademark', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1109, 'registered', 'far fa-registered', 'Registered Trademark', 'Otros', 'regular', 'f25d', 'copyright, mark, trademark', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1110, 'remove-format', 'fas fa-remove-format', 'Remove Format', 'Editores', 'solid', 'f87d', 'cancel, font, format, remove, style, text', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1111, 'renren', 'fab fa-renren', 'Renren', 'Marcas', 'brands', 'f18b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1112, 'reply', 'fas fa-reply', 'Reply', 'Otros', 'solid', 'f3e5', 'mail, message, respond', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1113, 'reply-all', 'fas fa-reply-all', 'reply-all', 'Otros', 'solid', 'f122', 'mail, message, respond', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1114, 'replyd', 'fab fa-replyd', 'replyd', 'Marcas', 'brands', 'f3e6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1115, 'republican', 'fas fa-republican', 'Republican', 'Otros', 'solid', 'f75e', 'american, conservative, election, elephant, politics, republican party, right, right-wing, usa', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1116, 'researchgate', 'fab fa-researchgate', 'Researchgate', 'Marcas', 'brands', 'f4f8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1117, 'resolving', 'fab fa-resolving', 'Resolving', 'Marcas', 'brands', 'f3e7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1118, 'restroom', 'fas fa-restroom', 'Restroom', 'Otros', 'solid', 'f7bd', 'bathroom, john, loo, potty, washroom, waste, wc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1119, 'retweet', 'fas fa-retweet', 'Retweet', 'Otros', 'solid', 'f079', 'refresh, reload, share, swap', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1120, 'rev', 'fab fa-rev', 'Rev.io', 'Marcas', 'brands', 'f5b2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1121, 'ribbon', 'fas fa-ribbon', 'Ribbon', 'Otros', 'solid', 'f4d6', 'badge, cause, lapel, pin', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1122, 'ring', 'fas fa-ring', 'Ring', 'Otros', 'solid', 'f70b', 'Dungeons & Dragons, Gollum, band, binding, d&d, dnd, engagement, fantasy, gold, jewelry, marriage, precious', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1123, 'road', 'fas fa-road', 'road', 'Otros', 'solid', 'f018', 'highway, map, pavement, route, street, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1124, 'robot', 'fas fa-robot', 'Robot', 'Otros', 'solid', 'f544', 'android, automate, computer, cyborg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1125, 'rocket', 'fas fa-rocket', 'rocket', 'Otros', 'solid', 'f135', 'aircraft, app, jet, launch, nasa, space', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1126, 'rocketchat', 'fab fa-rocketchat', 'Rocket.Chat', 'Marcas', 'brands', 'f3e8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1127, 'rockrms', 'fab fa-rockrms', 'Rockrms', 'Marcas', 'brands', 'f3e9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1128, 'route', 'fas fa-route', 'Route', 'Otros', 'solid', 'f4d7', 'directions, navigation, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1129, 'rss', 'fas fa-rss', 'rss', 'Otros', 'solid', 'f09e', 'blog, feed, journal, news, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1130, 'rss-square', 'fas fa-rss-square', 'RSS Square', 'Otros', 'solid', 'f143', 'blog, feed, journal, news, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1131, 'ruble-sign', 'fas fa-ruble-sign', 'Ruble Sign', 'Otros', 'solid', 'f158', 'currency, money, rub', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1132, 'ruler', 'fas fa-ruler', 'Ruler', 'Otros', 'solid', 'f545', 'design, draft, length, measure, planning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1133, 'ruler-combined', 'fas fa-ruler-combined', 'Ruler Combined', 'Otros', 'solid', 'f546', 'design, draft, length, measure, planning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1134, 'ruler-horizontal', 'fas fa-ruler-horizontal', 'Ruler Horizontal', 'Otros', 'solid', 'f547', 'design, draft, length, measure, planning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1135, 'ruler-vertical', 'fas fa-ruler-vertical', 'Ruler Vertical', 'Otros', 'solid', 'f548', 'design, draft, length, measure, planning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1136, 'running', 'fas fa-running', 'Running', 'Otros', 'solid', 'f70c', 'exercise, health, jog, person, run, sport, sprint', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1137, 'rupee-sign', 'fas fa-rupee-sign', 'Indian Rupee Sign', 'Otros', 'solid', 'f156', 'currency, indian, inr, money', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1138, 'sad-cry', 'fas fa-sad-cry', 'Crying Face', 'Otros', 'solid', 'f5b3', 'emoticon, face, tear, tears', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1139, 'sad-cry', 'far fa-sad-cry', 'Crying Face', 'Otros', 'regular', 'f5b3', 'emoticon, face, tear, tears', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1140, 'sad-tear', 'fas fa-sad-tear', 'Loudly Crying Face', 'Otros', 'solid', 'f5b4', 'emoticon, face, tear, tears', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1141, 'sad-tear', 'far fa-sad-tear', 'Loudly Crying Face', 'Otros', 'regular', 'f5b4', 'emoticon, face, tear, tears', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1142, 'safari', 'fab fa-safari', 'Safari', 'Marcas', 'brands', 'f267', 'browser', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1143, 'salesforce', 'fab fa-salesforce', 'Salesforce', 'Marcas', 'brands', 'f83b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1144, 'sass', 'fab fa-sass', 'Sass', 'Marcas', 'brands', 'f41e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1145, 'satellite', 'fas fa-satellite', 'Satellite', 'Otros', 'solid', 'f7bf', 'communications, hardware, orbit, space', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1146, 'satellite-dish', 'fas fa-satellite-dish', 'Satellite Dish', 'Otros', 'solid', 'f7c0', 'SETI, communications, hardware, receiver, saucer, signal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1147, 'save', 'fas fa-save', 'Save', 'Otros', 'solid', 'f0c7', 'disk, download, floppy, floppy-o', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1148, 'save', 'far fa-save', 'Save', 'Otros', 'regular', 'f0c7', 'disk, download, floppy, floppy-o', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1149, 'schlix', 'fab fa-schlix', 'SCHLIX', 'Marcas', 'brands', 'f3ea', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1150, 'school', 'fas fa-school', 'School', 'Otros', 'solid', 'f549', 'building, education, learn, student, teacher', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1151, 'screwdriver', 'fas fa-screwdriver', 'Screwdriver', 'Otros', 'solid', 'f54a', 'admin, fix, mechanic, repair, settings, tool', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1152, 'scribd', 'fab fa-scribd', 'Scribd', 'Marcas', 'brands', 'f28a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1153, 'scroll', 'fas fa-scroll', 'Scroll', 'Otros', 'solid', 'f70e', 'Dungeons & Dragons, announcement, d&d, dnd, fantasy, paper, script', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1154, 'sd-card', 'fas fa-sd-card', 'Sd Card', 'Otros', 'solid', 'f7c2', 'image, memory, photo, save', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1155, 'search', 'fas fa-search', 'Search', 'Otros', 'solid', 'f002', 'bigger, enlarge, find, magnify, preview, zoom', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1156, 'search-dollar', 'fas fa-search-dollar', 'Search Dollar', 'Otros', 'solid', 'f688', 'bigger, enlarge, find, magnify, money, preview, zoom', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1157, 'search-location', 'fas fa-search-location', 'Search Location', 'Otros', 'solid', 'f689', 'bigger, enlarge, find, magnify, preview, zoom', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1158, 'search-minus', 'fas fa-search-minus', 'Search Minus', 'Otros', 'solid', 'f010', 'minify, negative, smaller, zoom, zoom out', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1159, 'search-plus', 'fas fa-search-plus', 'Search Plus', 'Otros', 'solid', 'f00e', 'bigger, enlarge, magnify, positive, zoom, zoom in', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1160, 'searchengin', 'fab fa-searchengin', 'Searchengin', 'Marcas', 'brands', 'f3eb', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1161, 'seedling', 'fas fa-seedling', 'Seedling', 'Otros', 'solid', 'f4d8', 'flora, grow, plant, vegan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1162, 'sellcast', 'fab fa-sellcast', 'Sellcast', 'Marcas', 'brands', 'f2da', 'eercast', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1163, 'sellsy', 'fab fa-sellsy', 'Sellsy', 'Marcas', 'brands', 'f213', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1164, 'server', 'fas fa-server', 'Server', 'Otros', 'solid', 'f233', 'computer, cpu, database, hardware, network', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1165, 'servicestack', 'fab fa-servicestack', 'Servicestack', 'Marcas', 'brands', 'f3ec', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1166, 'shapes', 'fas fa-shapes', 'Shapes', 'Otros', 'solid', 'f61f', 'blocks, build, circle, square, triangle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1167, 'share', 'fas fa-share', 'Share', 'Otros', 'solid', 'f064', 'forward, save, send, social', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1168, 'share-alt', 'fas fa-share-alt', 'Alternate Share', 'Otros', 'solid', 'f1e0', 'forward, save, send, social', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1169, 'share-alt-square', 'fas fa-share-alt-square', 'Alternate Share Square', 'Otros', 'solid', 'f1e1', 'forward, save, send, social', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1170, 'share-square', 'fas fa-share-square', 'Share Square', 'Otros', 'solid', 'f14d', 'forward, save, send, social', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1171, 'share-square', 'far fa-share-square', 'Share Square', 'Otros', 'regular', 'f14d', 'forward, save, send, social', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1172, 'shekel-sign', 'fas fa-shekel-sign', 'Shekel Sign', 'Otros', 'solid', 'f20b', 'currency, ils, money', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1173, 'shield-alt', 'fas fa-shield-alt', 'Alternate Shield', 'Otros', 'solid', 'f3ed', 'achievement, award, block, defend, security, winner', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1174, 'ship', 'fas fa-ship', 'Ship', 'Otros', 'solid', 'f21a', 'boat, sea, water', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1175, 'shipping-fast', 'fas fa-shipping-fast', 'Shipping Fast', 'Otros', 'solid', 'f48b', 'express, fedex, mail, overnight, package, ups', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1176, 'shirtsinbulk', 'fab fa-shirtsinbulk', 'Shirts in Bulk', 'Marcas', 'brands', 'f214', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1177, 'shoe-prints', 'fas fa-shoe-prints', 'Shoe Prints', 'Otros', 'solid', 'f54b', 'feet, footprints, steps, walk', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1178, 'shopping-bag', 'fas fa-shopping-bag', 'Shopping Bag', 'Otros', 'solid', 'f290', 'buy, checkout, grocery, payment, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1179, 'shopping-basket', 'fas fa-shopping-basket', 'Shopping Basket', 'Otros', 'solid', 'f291', 'buy, checkout, grocery, payment, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1180, 'shopping-cart', 'fas fa-shopping-cart', 'shopping-cart', 'Otros', 'solid', 'f07a', 'buy, checkout, grocery, payment, purchase', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1181, 'shopware', 'fab fa-shopware', 'Shopware', 'Marcas', 'brands', 'f5b5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1182, 'shower', 'fas fa-shower', 'Shower', 'Otros', 'solid', 'f2cc', 'bath, clean, faucet, water', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1183, 'shuttle-van', 'fas fa-shuttle-van', 'Shuttle Van', 'Otros', 'solid', 'f5b6', 'airport, machine, public-transportation, transportation, travel, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1184, 'sign', 'fas fa-sign', 'Sign', 'Otros', 'solid', 'f4d9', 'directions, real estate, signage, wayfinding', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1185, 'sign-in-alt', 'fas fa-sign-in-alt', 'Alternate Sign In', 'Otros', 'solid', 'f2f6', 'arrow, enter, join, log in, login, sign in, sign up, sign-in, signin, signup', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1186, 'sign-language', 'fas fa-sign-language', 'Sign Language', 'Otros', 'solid', 'f2a7', 'Translate, asl, deaf, hands', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1187, 'sign-out-alt', 'fas fa-sign-out-alt', 'Alternate Sign Out', 'Otros', 'solid', 'f2f5', 'arrow, exit, leave, log out, logout, sign-out', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1188, 'signal', 'fas fa-signal', 'signal', 'Otros', 'solid', 'f012', 'bars, graph, online, reception, status', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1189, 'signature', 'fas fa-signature', 'Signature', 'Otros', 'solid', 'f5b7', 'John Hancock, cursive, name, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1190, 'sim-card', 'fas fa-sim-card', 'SIM Card', 'Otros', 'solid', 'f7c4', 'hard drive, hardware, portable, storage, technology, tiny', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1191, 'simplybuilt', 'fab fa-simplybuilt', 'SimplyBuilt', 'Marcas', 'brands', 'f215', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1192, 'sistrix', 'fab fa-sistrix', 'SISTRIX', 'Marcas', 'brands', 'f3ee', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1193, 'sitemap', 'fas fa-sitemap', 'Sitemap', 'Editores', 'solid', 'f0e8', 'directory, hierarchy, ia, information architecture, organization', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1194, 'sith', 'fab fa-sith', 'Sith', 'Marcas', 'brands', 'f512', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1195, 'skating', 'fas fa-skating', 'Skating', 'Otros', 'solid', 'f7c5', 'activity, figure skating, fitness, ice, person, winter', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1196, 'sketch', 'fab fa-sketch', 'Sketch', 'Marcas', 'brands', 'f7c6', 'app, design, interface', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1197, 'skiing', 'fas fa-skiing', 'Skiing', 'Otros', 'solid', 'f7c9', 'activity, downhill, fast, fitness, olympics, outdoors, person, seasonal, slalom', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1198, 'skiing-nordic', 'fas fa-skiing-nordic', 'Skiing Nordic', 'Otros', 'solid', 'f7ca', 'activity, cross country, fitness, outdoors, person, seasonal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1199, 'skull', 'fas fa-skull', 'Skull', 'Otros', 'solid', 'f54c', 'bones, skeleton, x-ray, yorick', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1200, 'skull-crossbones', 'fas fa-skull-crossbones', 'Skull & Crossbones', 'Otros', 'solid', 'f714', 'Dungeons & Dragons, alert, bones, d&d, danger, dead, deadly, death, dnd, fantasy, halloween, holiday, jolly-roger, pirate, poison, skeleton, warning', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1201, 'skyatlas', 'fab fa-skyatlas', 'skyatlas', 'Marcas', 'brands', 'f216', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1202, 'skype', 'fab fa-skype', 'Skype', 'Marcas', 'brands', 'f17e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1203, 'slack', 'fab fa-slack', 'Slack Logo', 'Marcas', 'brands', 'f198', 'anchor, hash, hashtag', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1204, 'slack-hash', 'fab fa-slack-hash', 'Slack Hashtag', 'Marcas', 'brands', 'f3ef', 'anchor, hash, hashtag', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1205, 'slash', 'fas fa-slash', 'Slash', 'Otros', 'solid', 'f715', 'cancel, close, mute, off, stop, x', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1206, 'sleigh', 'fas fa-sleigh', 'Sleigh', 'Otros', 'solid', 'f7cc', 'christmas, claus, fly, holiday, santa, sled, snow, xmas', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1207, 'sliders-h', 'fas fa-sliders-h', 'Horizontal Sliders', 'Otros', 'solid', 'f1de', 'adjust, settings, sliders, toggle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1208, 'slideshare', 'fab fa-slideshare', 'Slideshare', 'Marcas', 'brands', 'f1e7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1209, 'smile', 'fas fa-smile', 'Smiling Face', 'Otros', 'solid', 'f118', 'approve, emoticon, face, happy, rating, satisfied', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1210, 'smile', 'far fa-smile', 'Smiling Face', 'Otros', 'regular', 'f118', 'approve, emoticon, face, happy, rating, satisfied', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sara_iconos` (`id`, `Icono`, `IconoFull`, `IconoLabel`, `Categoria`, `Estilo`, `Unicode`, `PalabrasClave`, `created_at`, `updated_at`) VALUES
(1211, 'smile-beam', 'fas fa-smile-beam', 'Beaming Face With Smiling Eyes', 'Otros', 'solid', 'f5b8', 'emoticon, face, happy, positive', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1212, 'smile-beam', 'far fa-smile-beam', 'Beaming Face With Smiling Eyes', 'Otros', 'regular', 'f5b8', 'emoticon, face, happy, positive', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1213, 'smile-wink', 'fas fa-smile-wink', 'Winking Face', 'Otros', 'solid', 'f4da', 'emoticon, face, happy, hint, joke', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1214, 'smile-wink', 'far fa-smile-wink', 'Winking Face', 'Otros', 'regular', 'f4da', 'emoticon, face, happy, hint, joke', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1215, 'smog', 'fas fa-smog', 'Smog', 'Otros', 'solid', 'f75f', 'dragon, fog, haze, pollution, smoke, weather', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1216, 'smoking', 'fas fa-smoking', 'Smoking', 'Otros', 'solid', 'f48d', 'cancer, cigarette, nicotine, smoking status, tobacco', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1217, 'smoking-ban', 'fas fa-smoking-ban', 'Smoking Ban', 'Otros', 'solid', 'f54d', 'ban, cancel, no smoking, non-smoking', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1218, 'sms', 'fas fa-sms', 'SMS', 'Otros', 'solid', 'f7cd', 'chat, conversation, message, mobile, notification, phone, sms, texting', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1219, 'snapchat', 'fab fa-snapchat', 'Snapchat', 'Marcas', 'brands', 'f2ab', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1220, 'snapchat-ghost', 'fab fa-snapchat-ghost', 'Snapchat Ghost', 'Marcas', 'brands', 'f2ac', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1221, 'snapchat-square', 'fab fa-snapchat-square', 'Snapchat Square', 'Marcas', 'brands', 'f2ad', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1222, 'snowboarding', 'fas fa-snowboarding', 'Snowboarding', 'Otros', 'solid', 'f7ce', 'activity, fitness, olympics, outdoors, person', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1223, 'snowflake', 'fas fa-snowflake', 'Snowflake', 'Otros', 'solid', 'f2dc', 'precipitation, rain, winter', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1224, 'snowflake', 'far fa-snowflake', 'Snowflake', 'Otros', 'regular', 'f2dc', 'precipitation, rain, winter', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1225, 'snowman', 'fas fa-snowman', 'Snowman', 'Otros', 'solid', 'f7d0', 'decoration, frost, frosty, holiday', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1226, 'snowplow', 'fas fa-snowplow', 'Snowplow', 'Otros', 'solid', 'f7d2', 'clean up, cold, road, storm, winter', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1227, 'socks', 'fas fa-socks', 'Socks', 'Otros', 'solid', 'f696', 'business socks, business time, clothing, feet, flight of the conchords, wednesday', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1228, 'solar-panel', 'fas fa-solar-panel', 'Solar Panel', 'Otros', 'solid', 'f5ba', 'clean, eco-friendly, energy, green, sun', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1229, 'sort', 'fas fa-sort', 'Sort', 'Otros', 'solid', 'f0dc', 'filter, order', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1230, 'sort-alpha-down', 'fas fa-sort-alpha-down', 'Sort Alphabetical Down', 'Otros', 'solid', 'f15d', 'alphabetical, arrange, filter, order, sort-alpha-asc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1231, 'sort-alpha-down-alt', 'fas fa-sort-alpha-down-alt', 'Alternate Sort Alphabetical Down', 'Otros', 'solid', 'f881', 'alphabetical, arrange, filter, order, sort-alpha-asc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1232, 'sort-alpha-up', 'fas fa-sort-alpha-up', 'Sort Alphabetical Up', 'Otros', 'solid', 'f15e', 'alphabetical, arrange, filter, order, sort-alpha-desc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1233, 'sort-alpha-up-alt', 'fas fa-sort-alpha-up-alt', 'Alternate Sort Alphabetical Up', 'Otros', 'solid', 'f882', 'alphabetical, arrange, filter, order, sort-alpha-desc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1234, 'sort-amount-down', 'fas fa-sort-amount-down', 'Sort Amount Down', 'Otros', 'solid', 'f160', 'arrange, filter, number, order, sort-amount-asc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1235, 'sort-amount-down-alt', 'fas fa-sort-amount-down-alt', 'Alternate Sort Amount Down', 'Otros', 'solid', 'f884', 'arrange, filter, order, sort-amount-asc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1236, 'sort-amount-up', 'fas fa-sort-amount-up', 'Sort Amount Up', 'Otros', 'solid', 'f161', 'arrange, filter, order, sort-amount-desc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1237, 'sort-amount-up-alt', 'fas fa-sort-amount-up-alt', 'Alternate Sort Amount Up', 'Otros', 'solid', 'f885', 'arrange, filter, order, sort-amount-desc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1238, 'sort-down', 'fas fa-sort-down', 'Sort Down (Descending)', 'Otros', 'solid', 'f0dd', 'arrow, descending, filter, order, sort-desc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1239, 'sort-numeric-down', 'fas fa-sort-numeric-down', 'Sort Numeric Down', 'Otros', 'solid', 'f162', 'arrange, filter, numbers, order, sort-numeric-asc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1240, 'sort-numeric-down-alt', 'fas fa-sort-numeric-down-alt', 'Alternate Sort Numeric Down', 'Otros', 'solid', 'f886', 'arrange, filter, numbers, order, sort-numeric-asc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1241, 'sort-numeric-up', 'fas fa-sort-numeric-up', 'Sort Numeric Up', 'Otros', 'solid', 'f163', 'arrange, filter, numbers, order, sort-numeric-desc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1242, 'sort-numeric-up-alt', 'fas fa-sort-numeric-up-alt', 'Alternate Sort Numeric Up', 'Otros', 'solid', 'f887', 'arrange, filter, numbers, order, sort-numeric-desc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1243, 'sort-up', 'fas fa-sort-up', 'Sort Up (Ascending)', 'Otros', 'solid', 'f0de', 'arrow, ascending, filter, order, sort-asc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1244, 'soundcloud', 'fab fa-soundcloud', 'SoundCloud', 'Marcas', 'brands', 'f1be', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1245, 'sourcetree', 'fab fa-sourcetree', 'Sourcetree', 'Marcas', 'brands', 'f7d3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1246, 'spa', 'fas fa-spa', 'Spa', 'Otros', 'solid', 'f5bb', 'flora, massage, mindfulness, plant, wellness', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1247, 'space-shuttle', 'fas fa-space-shuttle', 'Space Shuttle', 'Otros', 'solid', 'f197', 'astronaut, machine, nasa, rocket, transportation', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1248, 'speakap', 'fab fa-speakap', 'Speakap', 'Marcas', 'brands', 'f3f3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1249, 'speaker-deck', 'fab fa-speaker-deck', 'Speaker Deck', 'Marcas', 'brands', 'f83c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1250, 'spell-check', 'fas fa-spell-check', 'Spell Check', 'Otros', 'solid', 'f891', 'dictionary, edit, editor, grammar, text', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1251, 'spider', 'fas fa-spider', 'Spider', 'Otros', 'solid', 'f717', 'arachnid, bug, charlotte, crawl, eight, halloween', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1252, 'spinner', 'fas fa-spinner', 'Spinner', 'Otros', 'solid', 'f110', 'circle, loading, progress', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1253, 'splotch', 'fas fa-splotch', 'Splotch', 'Otros', 'solid', 'f5bc', 'Ink, blob, blotch, glob, stain', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1254, 'spotify', 'fab fa-spotify', 'Spotify', 'Marcas', 'brands', 'f1bc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1255, 'spray-can', 'fas fa-spray-can', 'Spray Can', 'Otros', 'solid', 'f5bd', 'Paint, aerosol, design, graffiti, tag', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1256, 'square', 'fas fa-square', 'Square', 'Otros', 'solid', 'f0c8', 'block, box, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1257, 'square', 'far fa-square', 'Square', 'Otros', 'regular', 'f0c8', 'block, box, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1258, 'square-full', 'fas fa-square-full', 'Square Full', 'Otros', 'solid', 'f45c', 'block, box, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1259, 'square-root-alt', 'fas fa-square-root-alt', 'Alternate Square Root', 'Otros', 'solid', 'f698', 'arithmetic, calculus, division, math', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1260, 'squarespace', 'fab fa-squarespace', 'Squarespace', 'Marcas', 'brands', 'f5be', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1261, 'stack-exchange', 'fab fa-stack-exchange', 'Stack Exchange', 'Marcas', 'brands', 'f18d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1262, 'stack-overflow', 'fab fa-stack-overflow', 'Stack Overflow', 'Marcas', 'brands', 'f16c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1263, 'stackpath', 'fab fa-stackpath', 'Stackpath', 'Marcas', 'brands', 'f842', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1264, 'stamp', 'fas fa-stamp', 'Stamp', 'Otros', 'solid', 'f5bf', 'art, certificate, imprint, rubber, seal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1265, 'star', 'fas fa-star', 'Star', 'Otros', 'solid', 'f005', 'achievement, award, favorite, important, night, rating, score', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1266, 'star', 'far fa-star', 'Star', 'Otros', 'regular', 'f005', 'achievement, award, favorite, important, night, rating, score', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1267, 'star-and-crescent', 'fas fa-star-and-crescent', 'Star and Crescent', 'Otros', 'solid', 'f699', 'islam, muslim, religion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1268, 'star-half', 'fas fa-star-half', 'star-half', 'Otros', 'solid', 'f089', 'achievement, award, rating, score, star-half-empty, star-half-full', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1269, 'star-half', 'far fa-star-half', 'star-half', 'Otros', 'regular', 'f089', 'achievement, award, rating, score, star-half-empty, star-half-full', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1270, 'star-half-alt', 'fas fa-star-half-alt', 'Alternate Star Half', 'Otros', 'solid', 'f5c0', 'achievement, award, rating, score, star-half-empty, star-half-full', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1271, 'star-of-david', 'fas fa-star-of-david', 'Star of David', 'Otros', 'solid', 'f69a', 'jewish, judaism, religion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1272, 'star-of-life', 'fas fa-star-of-life', 'Star of Life', 'Medicina', 'solid', 'f621', 'doctor, emt, first aid, health, medical', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1273, 'staylinked', 'fab fa-staylinked', 'StayLinked', 'Marcas', 'brands', 'f3f5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1274, 'steam', 'fab fa-steam', 'Steam', 'Marcas', 'brands', 'f1b6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1275, 'steam-square', 'fab fa-steam-square', 'Steam Square', 'Marcas', 'brands', 'f1b7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1276, 'steam-symbol', 'fab fa-steam-symbol', 'Steam Symbol', 'Marcas', 'brands', 'f3f6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1277, 'step-backward', 'fas fa-step-backward', 'step-backward', 'Otros', 'solid', 'f048', 'beginning, first, previous, rewind, start', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1278, 'step-forward', 'fas fa-step-forward', 'step-forward', 'Otros', 'solid', 'f051', 'end, last, next', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1279, 'stethoscope', 'fas fa-stethoscope', 'Stethoscope', 'Otros', 'solid', 'f0f1', 'diagnosis, doctor, general practitioner, hospital, infirmary, medicine, office, outpatient', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1280, 'sticker-mule', 'fab fa-sticker-mule', 'Sticker Mule', 'Marcas', 'brands', 'f3f7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1281, 'sticky-note', 'fas fa-sticky-note', 'Sticky Note', 'Otros', 'solid', 'f249', 'message, note, paper, reminder, sticker', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1282, 'sticky-note', 'far fa-sticky-note', 'Sticky Note', 'Otros', 'regular', 'f249', 'message, note, paper, reminder, sticker', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1283, 'stop', 'fas fa-stop', 'stop', 'Otros', 'solid', 'f04d', 'block, box, square', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1284, 'stop-circle', 'fas fa-stop-circle', 'Stop Circle', 'Otros', 'solid', 'f28d', 'block, box, circle, square', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1285, 'stop-circle', 'far fa-stop-circle', 'Stop Circle', 'Otros', 'regular', 'f28d', 'block, box, circle, square', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1286, 'stopwatch', 'fas fa-stopwatch', 'Stopwatch', 'Otros', 'solid', 'f2f2', 'clock, reminder, time', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1287, 'store', 'fas fa-store', 'Store', 'Otros', 'solid', 'f54e', 'building, buy, purchase, shopping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1288, 'store-alt', 'fas fa-store-alt', 'Alternate Store', 'Otros', 'solid', 'f54f', 'building, buy, purchase, shopping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1289, 'strava', 'fab fa-strava', 'Strava', 'Marcas', 'brands', 'f428', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1290, 'stream', 'fas fa-stream', 'Stream', 'Otros', 'solid', 'f550', 'flow, list, timeline', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1291, 'street-view', 'fas fa-street-view', 'Street View', 'Otros', 'solid', 'f21d', 'directions, location, map, navigation', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1292, 'strikethrough', 'fas fa-strikethrough', 'Strikethrough', 'Editores', 'solid', 'f0cc', 'cancel, edit, font, format, text, type', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1293, 'stripe', 'fab fa-stripe', 'Stripe', 'Marcas', 'brands', 'f429', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1294, 'stripe-s', 'fab fa-stripe-s', 'Stripe S', 'Marcas', 'brands', 'f42a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1295, 'stroopwafel', 'fas fa-stroopwafel', 'Stroopwafel', 'Otros', 'solid', 'f551', 'caramel, cookie, dessert, sweets, waffle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1296, 'studiovinari', 'fab fa-studiovinari', 'Studio Vinari', 'Marcas', 'brands', 'f3f8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1297, 'stumbleupon', 'fab fa-stumbleupon', 'StumbleUpon Logo', 'Marcas', 'brands', 'f1a4', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1298, 'stumbleupon-circle', 'fab fa-stumbleupon-circle', 'StumbleUpon Circle', 'Marcas', 'brands', 'f1a3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1299, 'subscript', 'fas fa-subscript', 'subscript', 'Editores', 'solid', 'f12c', 'edit, font, format, text, type', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1300, 'subway', 'fas fa-subway', 'Subway', 'Otros', 'solid', 'f239', 'machine, railway, train, transportation, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1301, 'suitcase', 'fas fa-suitcase', 'Suitcase', 'Otros', 'solid', 'f0f2', 'baggage, luggage, move, suitcase, travel, trip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1302, 'suitcase-rolling', 'fas fa-suitcase-rolling', 'Suitcase Rolling', 'Otros', 'solid', 'f5c1', 'baggage, luggage, move, suitcase, travel, trip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1303, 'sun', 'fas fa-sun', 'Sun', 'Otros', 'solid', 'f185', 'brighten, contrast, day, lighter, sol, solar, star, weather', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1304, 'sun', 'far fa-sun', 'Sun', 'Otros', 'regular', 'f185', 'brighten, contrast, day, lighter, sol, solar, star, weather', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1305, 'superpowers', 'fab fa-superpowers', 'Superpowers', 'Marcas', 'brands', 'f2dd', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1306, 'superscript', 'fas fa-superscript', 'superscript', 'Editores', 'solid', 'f12b', 'edit, exponential, font, format, text, type', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1307, 'supple', 'fab fa-supple', 'Supple', 'Marcas', 'brands', 'f3f9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1308, 'surprise', 'fas fa-surprise', 'Hushed Face', 'Otros', 'solid', 'f5c2', 'emoticon, face, shocked', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1309, 'surprise', 'far fa-surprise', 'Hushed Face', 'Otros', 'regular', 'f5c2', 'emoticon, face, shocked', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1310, 'suse', 'fab fa-suse', 'Suse', 'Marcas', 'brands', 'f7d6', 'linux, operating system, os', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1311, 'swatchbook', 'fas fa-swatchbook', 'Swatchbook', 'Otros', 'solid', 'f5c3', 'Pantone, color, design, hue, palette', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1312, 'swift', 'fab fa-swift', 'Swift', 'Marcas', 'brands', 'f8e1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1313, 'swimmer', 'fas fa-swimmer', 'Swimmer', 'Otros', 'solid', 'f5c4', 'athlete, head, man, olympics, person, pool, water', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1314, 'swimming-pool', 'fas fa-swimming-pool', 'Swimming Pool', 'Otros', 'solid', 'f5c5', 'ladder, recreation, swim, water', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1315, 'symfony', 'fab fa-symfony', 'Symfony', 'Marcas', 'brands', 'f83d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1316, 'synagogue', 'fas fa-synagogue', 'Synagogue', 'Otros', 'solid', 'f69b', 'building, jewish, judaism, religion, star of david, temple', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1317, 'sync', 'fas fa-sync', 'Sync', 'Otros', 'solid', 'f021', 'exchange, refresh, reload, rotate, swap', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1318, 'sync-alt', 'fas fa-sync-alt', 'Alternate Sync', 'Otros', 'solid', 'f2f1', 'exchange, refresh, reload, rotate, swap', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1319, 'syringe', 'fas fa-syringe', 'Syringe', 'Medicina', 'solid', 'f48e', 'doctor, immunizations, medical, needle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1320, 'table', 'fas fa-table', 'table', 'Otros', 'solid', 'f0ce', 'data, excel, spreadsheet', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1321, 'table-tennis', 'fas fa-table-tennis', 'Table Tennis', 'Otros', 'solid', 'f45d', 'ball, paddle, ping pong', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1322, 'tablet', 'fas fa-tablet', 'tablet', 'Otros', 'solid', 'f10a', 'apple, device, ipad, kindle, screen', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1323, 'tablet-alt', 'fas fa-tablet-alt', 'Alternate Tablet', 'Otros', 'solid', 'f3fa', 'apple, device, ipad, kindle, screen', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1324, 'tablets', 'fas fa-tablets', 'Tablets', 'Otros', 'solid', 'f490', 'drugs, medicine, pills, prescription', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1325, 'tachometer-alt', 'fas fa-tachometer-alt', 'Alternate Tachometer', 'Otros', 'solid', 'f3fd', 'dashboard, fast, odometer, speed, speedometer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1326, 'tag', 'fas fa-tag', 'tag', 'Otros', 'solid', 'f02b', 'discount, label, price, shopping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1327, 'tags', 'fas fa-tags', 'tags', 'Otros', 'solid', 'f02c', 'discount, label, price, shopping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1328, 'tape', 'fas fa-tape', 'Tape', 'Otros', 'solid', 'f4db', 'design, package, sticky', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1329, 'tasks', 'fas fa-tasks', 'Tasks', 'Otros', 'solid', 'f0ae', 'checklist, downloading, downloads, loading, progress, project management, settings, to do', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1330, 'taxi', 'fas fa-taxi', 'Taxi', 'Otros', 'solid', 'f1ba', 'cab, cabbie, car, car service, lyft, machine, transportation, travel, uber, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1331, 'teamspeak', 'fab fa-teamspeak', 'TeamSpeak', 'Marcas', 'brands', 'f4f9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1332, 'teeth', 'fas fa-teeth', 'Teeth', 'Otros', 'solid', 'f62e', 'bite, dental, dentist, gums, mouth, smile, tooth', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1333, 'teeth-open', 'fas fa-teeth-open', 'Teeth Open', 'Otros', 'solid', 'f62f', 'dental, dentist, gums bite, mouth, smile, tooth', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1334, 'telegram', 'fab fa-telegram', 'Telegram', 'Marcas', 'brands', 'f2c6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1335, 'telegram-plane', 'fab fa-telegram-plane', 'Telegram Plane', 'Marcas', 'brands', 'f3fe', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1336, 'temperature-high', 'fas fa-temperature-high', 'High Temperature', 'Otros', 'solid', 'f769', 'cook, mercury, summer, thermometer, warm', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1337, 'temperature-low', 'fas fa-temperature-low', 'Low Temperature', 'Otros', 'solid', 'f76b', 'cold, cool, mercury, thermometer, winter', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1338, 'tencent-weibo', 'fab fa-tencent-weibo', 'Tencent Weibo', 'Marcas', 'brands', 'f1d5', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1339, 'tenge', 'fas fa-tenge', 'Tenge', 'Otros', 'solid', 'f7d7', 'currency, kazakhstan, money, price', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1340, 'terminal', 'fas fa-terminal', 'Terminal', 'Otros', 'solid', 'f120', 'code, command, console, development, prompt', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1341, 'text-height', 'fas fa-text-height', 'text-height', 'Editores', 'solid', 'f034', 'edit, font, format, text, type', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1342, 'text-width', 'fas fa-text-width', 'Text Width', 'Editores', 'solid', 'f035', 'edit, font, format, text, type', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1343, 'th', 'fas fa-th', 'th', 'Otros', 'solid', 'f00a', 'blocks, boxes, grid, squares', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1344, 'th-large', 'fas fa-th-large', 'th-large', 'Otros', 'solid', 'f009', 'blocks, boxes, grid, squares', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1345, 'th-list', 'fas fa-th-list', 'th-list', 'Otros', 'solid', 'f00b', 'checklist, completed, done, finished, ol, todo, ul', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1346, 'the-red-yeti', 'fab fa-the-red-yeti', 'The Red Yeti', 'Marcas', 'brands', 'f69d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1347, 'theater-masks', 'fas fa-theater-masks', 'Theater Masks', 'Otros', 'solid', 'f630', 'comedy, perform, theatre, tragedy', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1348, 'themeco', 'fab fa-themeco', 'Themeco', 'Marcas', 'brands', 'f5c6', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1349, 'themeisle', 'fab fa-themeisle', 'ThemeIsle', 'Marcas', 'brands', 'f2b2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1350, 'thermometer', 'fas fa-thermometer', 'Thermometer', 'Otros', 'solid', 'f491', 'mercury, status, temperature', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1351, 'thermometer-empty', 'fas fa-thermometer-empty', 'Thermometer Empty', 'Otros', 'solid', 'f2cb', 'cold, mercury, status, temperature', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1352, 'thermometer-full', 'fas fa-thermometer-full', 'Thermometer Full', 'Otros', 'solid', 'f2c7', 'fever, hot, mercury, status, temperature', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1353, 'thermometer-half', 'fas fa-thermometer-half', 'Thermometer 1/2 Full', 'Otros', 'solid', 'f2c9', 'mercury, status, temperature', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1354, 'thermometer-quarter', 'fas fa-thermometer-quarter', 'Thermometer 1/4 Full', 'Otros', 'solid', 'f2ca', 'mercury, status, temperature', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1355, 'thermometer-three-quarters', 'fas fa-thermometer-three-quarters', 'Thermometer 3/4 Full', 'Otros', 'solid', 'f2c8', 'mercury, status, temperature', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1356, 'think-peaks', 'fab fa-think-peaks', 'Think Peaks', 'Marcas', 'brands', 'f731', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1357, 'thumbs-down', 'fas fa-thumbs-down', 'thumbs-down', 'Otros', 'solid', 'f165', 'disagree, disapprove, dislike, hand, social, thumbs-o-down', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1358, 'thumbs-down', 'far fa-thumbs-down', 'thumbs-down', 'Otros', 'regular', 'f165', 'disagree, disapprove, dislike, hand, social, thumbs-o-down', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1359, 'thumbs-up', 'fas fa-thumbs-up', 'thumbs-up', 'Otros', 'solid', 'f164', 'agree, approve, favorite, hand, like, ok, okay, social, success, thumbs-o-up, yes, you got it dude', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1360, 'thumbs-up', 'far fa-thumbs-up', 'thumbs-up', 'Otros', 'regular', 'f164', 'agree, approve, favorite, hand, like, ok, okay, social, success, thumbs-o-up, yes, you got it dude', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1361, 'thumbtack', 'fas fa-thumbtack', 'Thumbtack', 'Otros', 'solid', 'f08d', 'coordinates, location, marker, pin, thumb-tack', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1362, 'ticket-alt', 'fas fa-ticket-alt', 'Alternate Ticket', 'Otros', 'solid', 'f3ff', 'movie, pass, support, ticket', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1363, 'times', 'fas fa-times', 'Times', 'Otros', 'solid', 'f00d', 'close, cross, error, exit, incorrect, notice, notification, notify, problem, wrong, x', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1364, 'times-circle', 'fas fa-times-circle', 'Times Circle', 'Otros', 'solid', 'f057', 'close, cross, exit, incorrect, notice, notification, notify, problem, wrong, x', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1365, 'times-circle', 'far fa-times-circle', 'Times Circle', 'Otros', 'regular', 'f057', 'close, cross, exit, incorrect, notice, notification, notify, problem, wrong, x', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1366, 'tint', 'fas fa-tint', 'tint', 'Otros', 'solid', 'f043', 'color, drop, droplet, raindrop, waterdrop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1367, 'tint-slash', 'fas fa-tint-slash', 'Tint Slash', 'Otros', 'solid', 'f5c7', 'color, drop, droplet, raindrop, waterdrop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1368, 'tired', 'fas fa-tired', 'Tired Face', 'Otros', 'solid', 'f5c8', 'angry, emoticon, face, grumpy, upset', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1369, 'tired', 'far fa-tired', 'Tired Face', 'Otros', 'regular', 'f5c8', 'angry, emoticon, face, grumpy, upset', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1370, 'toggle-off', 'fas fa-toggle-off', 'Toggle Off', 'Otros', 'solid', 'f204', 'switch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1371, 'toggle-on', 'fas fa-toggle-on', 'Toggle On', 'Otros', 'solid', 'f205', 'switch', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1372, 'toilet', 'fas fa-toilet', 'Toilet', 'Otros', 'solid', 'f7d8', 'bathroom, flush, john, loo, pee, plumbing, poop, porcelain, potty, restroom, throne, washroom, waste, wc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1373, 'toilet-paper', 'fas fa-toilet-paper', 'Toilet Paper', 'Otros', 'solid', 'f71e', 'bathroom, halloween, holiday, lavatory, prank, restroom, roll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1374, 'toolbox', 'fas fa-toolbox', 'Toolbox', 'Otros', 'solid', 'f552', 'admin, container, fix, repair, settings, tools', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1375, 'tools', 'fas fa-tools', 'Tools', 'Otros', 'solid', 'f7d9', 'admin, fix, repair, screwdriver, settings, tools, wrench', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1376, 'tooth', 'fas fa-tooth', 'Tooth', 'Otros', 'solid', 'f5c9', 'bicuspid, dental, dentist, molar, mouth, teeth', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1377, 'torah', 'fas fa-torah', 'Torah', 'Otros', 'solid', 'f6a0', 'book, jewish, judaism, religion, scroll', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1378, 'torii-gate', 'fas fa-torii-gate', 'Torii Gate', 'Otros', 'solid', 'f6a1', 'building, shintoism', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1379, 'tractor', 'fas fa-tractor', 'Tractor', 'Otros', 'solid', 'f722', 'agriculture, farm, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1380, 'trade-federation', 'fab fa-trade-federation', 'Trade Federation', 'Marcas', 'brands', 'f513', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1381, 'trademark', 'fas fa-trademark', 'Trademark', 'Otros', 'solid', 'f25c', 'copyright, register, symbol', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1382, 'traffic-light', 'fas fa-traffic-light', 'Traffic Light', 'Otros', 'solid', 'f637', 'direction, road, signal, travel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1383, 'train', 'fas fa-train', 'Train', 'Otros', 'solid', 'f238', 'bullet, commute, locomotive, railway, subway', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1384, 'tram', 'fas fa-tram', 'Tram', 'Otros', 'solid', 'f7da', 'crossing, machine, mountains, seasonal, transportation', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1385, 'transgender', 'fas fa-transgender', 'Transgender', 'Otros', 'solid', 'f224', 'intersex', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1386, 'transgender-alt', 'fas fa-transgender-alt', 'Alternate Transgender', 'Otros', 'solid', 'f225', 'intersex', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1387, 'trash', 'fas fa-trash', 'Trash', 'Otros', 'solid', 'f1f8', 'delete, garbage, hide, remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1388, 'trash-alt', 'fas fa-trash-alt', 'Alternate Trash', 'Otros', 'solid', 'f2ed', 'delete, garbage, hide, remove, trash-o', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1389, 'trash-alt', 'far fa-trash-alt', 'Alternate Trash', 'Otros', 'regular', 'f2ed', 'delete, garbage, hide, remove, trash-o', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1390, 'trash-restore', 'fas fa-trash-restore', 'Trash Restore', 'Otros', 'solid', 'f829', 'back, control z, oops, undo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1391, 'trash-restore-alt', 'fas fa-trash-restore-alt', 'Alternative Trash Restore', 'Otros', 'solid', 'f82a', 'back, control z, oops, undo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1392, 'tree', 'fas fa-tree', 'Tree', 'Otros', 'solid', 'f1bb', 'bark, fall, flora, forest, nature, plant, seasonal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1393, 'trello', 'fab fa-trello', 'Trello', 'Marcas', 'brands', 'f181', 'atlassian', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1394, 'tripadvisor', 'fab fa-tripadvisor', 'TripAdvisor', 'Marcas', 'brands', 'f262', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1395, 'trophy', 'fas fa-trophy', 'trophy', 'Otros', 'solid', 'f091', 'achievement, award, cup, game, winner', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1396, 'truck', 'fas fa-truck', 'truck', 'Otros', 'solid', 'f0d1', 'cargo, delivery, shipping, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1397, 'truck-loading', 'fas fa-truck-loading', 'Truck Loading', 'Otros', 'solid', 'f4de', 'box, cargo, delivery, inventory, moving, rental, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1398, 'truck-monster', 'fas fa-truck-monster', 'Truck Monster', 'Otros', 'solid', 'f63b', 'offroad, vehicle, wheel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1399, 'truck-moving', 'fas fa-truck-moving', 'Truck Moving', 'Otros', 'solid', 'f4df', 'cargo, inventory, rental, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1400, 'truck-pickup', 'fas fa-truck-pickup', 'Truck Side', 'Otros', 'solid', 'f63c', 'cargo, vehicle', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1401, 'tshirt', 'fas fa-tshirt', 'T-Shirt', 'Otros', 'solid', 'f553', 'clothing, fashion, garment, shirt', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1402, 'tty', 'fas fa-tty', 'TTY', 'Otros', 'solid', 'f1e4', 'communication, deaf, telephone, teletypewriter, text', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1403, 'tumblr', 'fab fa-tumblr', 'Tumblr', 'Marcas', 'brands', 'f173', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1404, 'tumblr-square', 'fab fa-tumblr-square', 'Tumblr Square', 'Marcas', 'brands', 'f174', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1405, 'tv', 'fas fa-tv', 'Television', 'Otros', 'solid', 'f26c', 'computer, display, monitor, television', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1406, 'twitch', 'fab fa-twitch', 'Twitch', 'Marcas', 'brands', 'f1e8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1407, 'twitter', 'fab fa-twitter', 'Twitter', 'Marcas', 'brands', 'f099', 'social network, tweet', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1408, 'twitter-square', 'fab fa-twitter-square', 'Twitter Square', 'Marcas', 'brands', 'f081', 'social network, tweet', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1409, 'typo3', 'fab fa-typo3', 'Typo3', 'Marcas', 'brands', 'f42b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1410, 'uber', 'fab fa-uber', 'Uber', 'Marcas', 'brands', 'f402', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1411, 'ubuntu', 'fab fa-ubuntu', 'Ubuntu', 'Marcas', 'brands', 'f7df', 'linux, operating system, os', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1412, 'uikit', 'fab fa-uikit', 'UIkit', 'Marcas', 'brands', 'f403', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1413, 'umbraco', 'fab fa-umbraco', 'Umbraco', 'Marcas', 'brands', 'f8e8', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1414, 'umbrella', 'fas fa-umbrella', 'Umbrella', 'Otros', 'solid', 'f0e9', 'protection, rain, storm, wet', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1415, 'umbrella-beach', 'fas fa-umbrella-beach', 'Umbrella Beach', 'Otros', 'solid', 'f5ca', 'protection, recreation, sand, shade, summer, sun', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1416, 'underline', 'fas fa-underline', 'Underline', 'Editores', 'solid', 'f0cd', 'edit, emphasis, format, text, writing', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1417, 'undo', 'fas fa-undo', 'Undo', 'Otros', 'solid', 'f0e2', 'back, control z, exchange, oops, return, rotate, swap', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1418, 'undo-alt', 'fas fa-undo-alt', 'Alternate Undo', 'Otros', 'solid', 'f2ea', 'back, control z, exchange, oops, return, swap', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1419, 'uniregistry', 'fab fa-uniregistry', 'Uniregistry', 'Marcas', 'brands', 'f404', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1420, 'universal-access', 'fas fa-universal-access', 'Universal Access', 'Otros', 'solid', 'f29a', 'accessibility, hearing, person, seeing, visual impairment', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1421, 'university', 'fas fa-university', 'University', 'Otros', 'solid', 'f19c', 'bank, building, college, higher education - students, institution', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1422, 'unlink', 'fas fa-unlink', 'unlink', 'Otros', 'solid', 'f127', 'attachment, chain, chain-broken, remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1423, 'unlock', 'fas fa-unlock', 'unlock', 'Otros', 'solid', 'f09c', 'admin, lock, password, private, protect', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1424, 'unlock-alt', 'fas fa-unlock-alt', 'Alternate Unlock', 'Otros', 'solid', 'f13e', 'admin, lock, password, private, protect', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1425, 'untappd', 'fab fa-untappd', 'Untappd', 'Marcas', 'brands', 'f405', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1426, 'upload', 'fas fa-upload', 'Upload', 'Otros', 'solid', 'f093', 'hard drive, import, publish', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1427, 'ups', 'fab fa-ups', 'UPS', 'Marcas', 'brands', 'f7e0', 'United Parcel Service, package, shipping', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1428, 'usb', 'fab fa-usb', 'USB', 'Marcas', 'brands', 'f287', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1429, 'user', 'fas fa-user', 'User', 'Otros', 'solid', 'f007', 'account, avatar, head, human, man, person, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1430, 'user', 'far fa-user', 'User', 'Otros', 'regular', 'f007', 'account, avatar, head, human, man, person, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1431, 'user-alt', 'fas fa-user-alt', 'Alternate User', 'Otros', 'solid', 'f406', 'account, avatar, head, human, man, person, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1432, 'user-alt-slash', 'fas fa-user-alt-slash', 'Alternate User Slash', 'Otros', 'solid', 'f4fa', 'account, avatar, head, human, man, person, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1433, 'user-astronaut', 'fas fa-user-astronaut', 'User Astronaut', 'Otros', 'solid', 'f4fb', 'avatar, clothing, cosmonaut, nasa, space, suit', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1434, 'user-check', 'fas fa-user-check', 'User Check', 'Otros', 'solid', 'f4fc', 'accept, check, person, verified', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1435, 'user-circle', 'fas fa-user-circle', 'User Circle', 'Otros', 'solid', 'f2bd', 'account, avatar, head, human, man, person, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1436, 'user-circle', 'far fa-user-circle', 'User Circle', 'Otros', 'regular', 'f2bd', 'account, avatar, head, human, man, person, profile', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1437, 'user-clock', 'fas fa-user-clock', 'User Clock', 'Otros', 'solid', 'f4fd', 'alert, person, remind, time', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1438, 'user-cog', 'fas fa-user-cog', 'User Cog', 'Otros', 'solid', 'f4fe', 'admin, cog, person, settings', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1439, 'user-edit', 'fas fa-user-edit', 'User Edit', 'Otros', 'solid', 'f4ff', 'edit, pen, pencil, person, update, write', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1440, 'user-friends', 'fas fa-user-friends', 'User Friends', 'Otros', 'solid', 'f500', 'group, people, person, team, users', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1441, 'user-graduate', 'fas fa-user-graduate', 'User Graduate', 'Otros', 'solid', 'f501', 'cap, clothing, commencement, gown, graduation, person, student', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1442, 'user-injured', 'fas fa-user-injured', 'User Injured', 'Otros', 'solid', 'f728', 'cast, injury, ouch, patient, person, sling', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1443, 'user-lock', 'fas fa-user-lock', 'User Lock', 'Otros', 'solid', 'f502', 'admin, lock, person, private, unlock', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1444, 'user-md', 'fas fa-user-md', 'Doctor', 'Medicina', 'solid', 'f0f0', 'job, medical, nurse, occupation, physician, profile, surgeon', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1445, 'user-minus', 'fas fa-user-minus', 'User Minus', 'Otros', 'solid', 'f503', 'delete, negative, remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1446, 'user-ninja', 'fas fa-user-ninja', 'User Ninja', 'Otros', 'solid', 'f504', 'assassin, avatar, dangerous, deadly, sneaky', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1447, 'user-nurse', 'fas fa-user-nurse', 'Nurse', 'Otros', 'solid', 'f82f', 'doctor, midwife, practitioner, surgeon', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1448, 'user-plus', 'fas fa-user-plus', 'User Plus', 'Otros', 'solid', 'f234', 'add, avatar, positive, sign up, signup, team', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1449, 'user-secret', 'fas fa-user-secret', 'User Secret', 'Otros', 'solid', 'f21b', 'clothing, coat, hat, incognito, person, privacy, spy, whisper', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1450, 'user-shield', 'fas fa-user-shield', 'User Shield', 'Otros', 'solid', 'f505', 'admin, person, private, protect, safe', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1451, 'user-slash', 'fas fa-user-slash', 'User Slash', 'Otros', 'solid', 'f506', 'ban, delete, remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1452, 'user-tag', 'fas fa-user-tag', 'User Tag', 'Otros', 'solid', 'f507', 'avatar, discount, label, person, role, special', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1453, 'user-tie', 'fas fa-user-tie', 'User Tie', 'Otros', 'solid', 'f508', 'avatar, business, clothing, formal, professional, suit', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1454, 'user-times', 'fas fa-user-times', 'Remove User', 'Otros', 'solid', 'f235', 'archive, delete, remove, x', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1455, 'users', 'fas fa-users', 'Users', 'Otros', 'solid', 'f0c0', 'friends, group, people, persons, profiles, team', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1456, 'users-cog', 'fas fa-users-cog', 'Users Cog', 'Otros', 'solid', 'f509', 'admin, cog, group, person, settings, team', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1457, 'usps', 'fab fa-usps', 'United States Postal Service', 'Marcas', 'brands', 'f7e1', 'american, package, shipping, usa', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1458, 'ussunnah', 'fab fa-ussunnah', 'us-Sunnah Foundation', 'Marcas', 'brands', 'f407', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1459, 'utensil-spoon', 'fas fa-utensil-spoon', 'Utensil Spoon', 'Otros', 'solid', 'f2e5', 'cutlery, dining, scoop, silverware, spoon', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1460, 'utensils', 'fas fa-utensils', 'Utensils', 'Otros', 'solid', 'f2e7', 'cutlery, dining, dinner, eat, food, fork, knife, restaurant', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1461, 'vaadin', 'fab fa-vaadin', 'Vaadin', 'Marcas', 'brands', 'f408', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1462, 'vector-square', 'fas fa-vector-square', 'Vector Square', 'Otros', 'solid', 'f5cb', 'anchors, lines, object, render, shape', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1463, 'venus', 'fas fa-venus', 'Venus', 'Otros', 'solid', 'f221', 'female', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1464, 'venus-double', 'fas fa-venus-double', 'Venus Double', 'Otros', 'solid', 'f226', 'female', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1465, 'venus-mars', 'fas fa-venus-mars', 'Venus Mars', 'Otros', 'solid', 'f228', 'Gender', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1466, 'viacoin', 'fab fa-viacoin', 'Viacoin', 'Marcas', 'brands', 'f237', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1467, 'viadeo', 'fab fa-viadeo', 'Video', 'Marcas', 'brands', 'f2a9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1468, 'viadeo-square', 'fab fa-viadeo-square', 'Video Square', 'Marcas', 'brands', 'f2aa', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1469, 'vial', 'fas fa-vial', 'Vial', 'Otros', 'solid', 'f492', 'experiment, lab, sample, science, test, test tube', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1470, 'vials', 'fas fa-vials', 'Vials', 'Otros', 'solid', 'f493', 'experiment, lab, sample, science, test, test tube', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1471, 'viber', 'fab fa-viber', 'Viber', 'Marcas', 'brands', 'f409', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1472, 'video', 'fas fa-video', 'Video', 'Otros', 'solid', 'f03d', 'camera, film, movie, record, video-camera', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1473, 'video-slash', 'fas fa-video-slash', 'Video Slash', 'Otros', 'solid', 'f4e2', 'add, create, film, new, positive, record, video', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1474, 'vihara', 'fas fa-vihara', 'Vihara', 'Otros', 'solid', 'f6a7', 'buddhism, buddhist, building, monastery', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1475, 'vimeo', 'fab fa-vimeo', 'Vimeo', 'Marcas', 'brands', 'f40a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1476, 'vimeo-square', 'fab fa-vimeo-square', 'Vimeo Square', 'Marcas', 'brands', 'f194', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1477, 'vimeo-v', 'fab fa-vimeo-v', 'Vimeo', 'Marcas', 'brands', 'f27d', 'vimeo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1478, 'vine', 'fab fa-vine', 'Vine', 'Marcas', 'brands', 'f1ca', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1479, 'vk', 'fab fa-vk', 'VK', 'Marcas', 'brands', 'f189', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1480, 'vnv', 'fab fa-vnv', 'VNV', 'Marcas', 'brands', 'f40b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1481, 'voicemail', 'fas fa-voicemail', 'Voicemail', 'Otros', 'solid', 'f897', 'answer, inbox, message, phone', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1482, 'volleyball-ball', 'fas fa-volleyball-ball', 'Volleyball Ball', 'Otros', 'solid', 'f45f', 'beach, olympics, sport', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1483, 'volume-down', 'fas fa-volume-down', 'Volume Down', 'Otros', 'solid', 'f027', 'audio, lower, music, quieter, sound, speaker', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1484, 'volume-mute', 'fas fa-volume-mute', 'Volume Mute', 'Otros', 'solid', 'f6a9', 'audio, music, quiet, sound, speaker', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1485, 'volume-off', 'fas fa-volume-off', 'Volume Off', 'Otros', 'solid', 'f026', 'audio, ban, music, mute, quiet, silent, sound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1486, 'volume-up', 'fas fa-volume-up', 'Volume Up', 'Otros', 'solid', 'f028', 'audio, higher, louder, music, sound, speaker', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1487, 'vote-yea', 'fas fa-vote-yea', 'Vote Yea', 'Otros', 'solid', 'f772', 'accept, cast, election, politics, positive, yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1488, 'vr-cardboard', 'fas fa-vr-cardboard', 'Cardboard VR', 'Otros', 'solid', 'f729', '3d, augment, google, reality, virtual', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1489, 'vuejs', 'fab fa-vuejs', 'Vue.js', 'Marcas', 'brands', 'f41f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1490, 'walking', 'fas fa-walking', 'Walking', 'Otros', 'solid', 'f554', 'exercise, health, pedometer, person, steps', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1491, 'wallet', 'fas fa-wallet', 'Wallet', 'Otros', 'solid', 'f555', 'billfold, cash, currency, money', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1492, 'warehouse', 'fas fa-warehouse', 'Warehouse', 'Otros', 'solid', 'f494', 'building, capacity, garage, inventory, storage', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1493, 'water', 'fas fa-water', 'Water', 'Otros', 'solid', 'f773', 'lake, liquid, ocean, sea, swim, wet', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1494, 'wave-square', 'fas fa-wave-square', 'Square Wave', 'Otros', 'solid', 'f83e', 'frequency, pulse, signal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1495, 'waze', 'fab fa-waze', 'Waze', 'Marcas', 'brands', 'f83f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1496, 'weebly', 'fab fa-weebly', 'Weebly', 'Marcas', 'brands', 'f5cc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1497, 'weibo', 'fab fa-weibo', 'Weibo', 'Marcas', 'brands', 'f18a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1498, 'weight', 'fas fa-weight', 'Weight', 'Otros', 'solid', 'f496', 'health, measurement, scale, weight', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1499, 'weight-hanging', 'fas fa-weight-hanging', 'Hanging Weight', 'Otros', 'solid', 'f5cd', 'anvil, heavy, measurement', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1500, 'weixin', 'fab fa-weixin', 'Weixin (WeChat)', 'Marcas', 'brands', 'f1d7', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1501, 'whatsapp', 'fab fa-whatsapp', 'What\'s App', 'Marcas', 'brands', 'f232', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1502, 'whatsapp-square', 'fab fa-whatsapp-square', 'What\'s App Square', 'Marcas', 'brands', 'f40c', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1503, 'wheelchair', 'fas fa-wheelchair', 'Wheelchair', 'Otros', 'solid', 'f193', 'accessible, handicap, person', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1504, 'whmcs', 'fab fa-whmcs', 'WHMCS', 'Marcas', 'brands', 'f40d', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1505, 'wifi', 'fas fa-wifi', 'WiFi', 'Otros', 'solid', 'f1eb', 'connection, hotspot, internet, network, wireless', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1506, 'wikipedia-w', 'fab fa-wikipedia-w', 'Wikipedia W', 'Marcas', 'brands', 'f266', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1507, 'wind', 'fas fa-wind', 'Wind', 'Otros', 'solid', 'f72e', 'air, blow, breeze, fall, seasonal, weather', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1508, 'window-close', 'fas fa-window-close', 'Window Close', 'Otros', 'solid', 'f410', 'browser, cancel, computer, development', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1509, 'window-close', 'far fa-window-close', 'Window Close', 'Otros', 'regular', 'f410', 'browser, cancel, computer, development', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1510, 'window-maximize', 'fas fa-window-maximize', 'Window Maximize', 'Otros', 'solid', 'f2d0', 'browser, computer, development, expand', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1511, 'window-maximize', 'far fa-window-maximize', 'Window Maximize', 'Otros', 'regular', 'f2d0', 'browser, computer, development, expand', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1512, 'window-minimize', 'fas fa-window-minimize', 'Window Minimize', 'Otros', 'solid', 'f2d1', 'browser, collapse, computer, development', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1513, 'window-minimize', 'far fa-window-minimize', 'Window Minimize', 'Otros', 'regular', 'f2d1', 'browser, collapse, computer, development', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1514, 'window-restore', 'fas fa-window-restore', 'Window Restore', 'Otros', 'solid', 'f2d2', 'browser, computer, development', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1515, 'window-restore', 'far fa-window-restore', 'Window Restore', 'Otros', 'regular', 'f2d2', 'browser, computer, development', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1516, 'windows', 'fab fa-windows', 'Windows', 'Marcas', 'brands', 'f17a', 'microsoft, operating system, os', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1517, 'wine-bottle', 'fas fa-wine-bottle', 'Wine Bottle', 'Otros', 'solid', 'f72f', 'alcohol, beverage, cabernet, drink, glass, grapes, merlot, sauvignon', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1518, 'wine-glass', 'fas fa-wine-glass', 'Wine Glass', 'Otros', 'solid', 'f4e3', 'alcohol, beverage, cabernet, drink, grapes, merlot, sauvignon', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `sara_iconos` (`id`, `Icono`, `IconoFull`, `IconoLabel`, `Categoria`, `Estilo`, `Unicode`, `PalabrasClave`, `created_at`, `updated_at`) VALUES
(1519, 'wine-glass-alt', 'fas fa-wine-glass-alt', 'Alternate Wine Glas', 'Otros', 'solid', 'f5ce', 'alcohol, beverage, cabernet, drink, grapes, merlot, sauvignon', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1520, 'wix', 'fab fa-wix', 'Wix', 'Marcas', 'brands', 'f5cf', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1521, 'wizards-of-the-coast', 'fab fa-wizards-of-the-coast', 'Wizards of the Coast', 'Marcas', 'brands', 'f730', 'Dungeons & Dragons, d&d, dnd, fantasy, game, gaming, tabletop', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1522, 'wolf-pack-battalion', 'fab fa-wolf-pack-battalion', 'Wolf Pack Battalion', 'Marcas', 'brands', 'f514', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1523, 'won-sign', 'fas fa-won-sign', 'Won Sign', 'Otros', 'solid', 'f159', 'currency, krw, money', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1524, 'wordpress', 'fab fa-wordpress', 'WordPress Logo', 'Marcas', 'brands', 'f19a', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1525, 'wordpress-simple', 'fab fa-wordpress-simple', 'Wordpress Simple', 'Marcas', 'brands', 'f411', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1526, 'wpbeginner', 'fab fa-wpbeginner', 'WPBeginner', 'Marcas', 'brands', 'f297', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1527, 'wpexplorer', 'fab fa-wpexplorer', 'WPExplorer', 'Marcas', 'brands', 'f2de', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1528, 'wpforms', 'fab fa-wpforms', 'WPForms', 'Marcas', 'brands', 'f298', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1529, 'wpressr', 'fab fa-wpressr', 'wpressr', 'Marcas', 'brands', 'f3e4', 'rendact', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1530, 'wrench', 'fas fa-wrench', 'Wrench', 'Otros', 'solid', 'f0ad', 'construction, fix, mechanic, plumbing, settings, spanner, tool, update', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1531, 'x-ray', 'fas fa-x-ray', 'X-Ray', 'Medicina', 'solid', 'f497', 'health, medical, radiological images, radiology, skeleton', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1532, 'xbox', 'fab fa-xbox', 'Xbox', 'Marcas', 'brands', 'f412', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1533, 'xing', 'fab fa-xing', 'Xing', 'Marcas', 'brands', 'f168', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1534, 'xing-square', 'fab fa-xing-square', 'Xing Square', 'Marcas', 'brands', 'f169', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1535, 'y-combinator', 'fab fa-y-combinator', 'Y Combinator', 'Marcas', 'brands', 'f23b', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1536, 'yahoo', 'fab fa-yahoo', 'Yahoo Logo', 'Marcas', 'brands', 'f19e', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1537, 'yammer', 'fab fa-yammer', 'Yammer', 'Marcas', 'brands', 'f840', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1538, 'yandex', 'fab fa-yandex', 'Yandex', 'Marcas', 'brands', 'f413', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1539, 'yandex-international', 'fab fa-yandex-international', 'Yandex International', 'Marcas', 'brands', 'f414', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1540, 'yarn', 'fab fa-yarn', 'Yarn', 'Marcas', 'brands', 'f7e3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1541, 'yelp', 'fab fa-yelp', 'Yelp', 'Marcas', 'brands', 'f1e9', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1542, 'yen-sign', 'fas fa-yen-sign', 'Yen Sign', 'Otros', 'solid', 'f157', 'currency, jpy, money', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1543, 'yin-yang', 'fas fa-yin-yang', 'Yin Yang', 'Otros', 'solid', 'f6ad', 'daoism, opposites, taoism', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1544, 'yoast', 'fab fa-yoast', 'Yoast', 'Marcas', 'brands', 'f2b1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1545, 'youtube', 'fab fa-youtube', 'YouTube', 'Marcas', 'brands', 'f167', 'film, video, youtube-play, youtube-square', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1546, 'youtube-square', 'fab fa-youtube-square', 'YouTube Square', 'Marcas', 'brands', 'f431', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1547, 'zhihu', 'fab fa-zhihu', 'Zhihu', 'Marcas', 'brands', 'f63f', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_indicadores`
--

CREATE TABLE `sara_indicadores` (
  `id` int(11) NOT NULL,
  `Ruta` varchar(1000) NOT NULL,
  `proceso_id` int(11) NOT NULL DEFAULT 1,
  `Indicador` varchar(255) NOT NULL,
  `Definicion` text DEFAULT NULL,
  `TipoDato` varchar(50) NOT NULL DEFAULT 'Porcentaje',
  `Decimales` int(2) NOT NULL DEFAULT 0,
  `Formula` varchar(255) NOT NULL DEFAULT 'a / b',
  `Sentido` varchar(3) NOT NULL DEFAULT 'ASC',
  `FrecuenciaAnalisis` int(10) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_indicadores_metas`
--

CREATE TABLE `sara_indicadores_metas` (
  `id` int(11) NOT NULL,
  `indicador_id` int(11) NOT NULL,
  `PeriodoDesde` int(8) NOT NULL DEFAULT 200001,
  `Meta` decimal(24,4) NOT NULL DEFAULT 0.0000,
  `Meta2` decimal(24,4) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_indicadores_valores`
--

CREATE TABLE `sara_indicadores_valores` (
  `id` int(11) NOT NULL,
  `indicador_id` int(11) NOT NULL,
  `Anio` int(5) NOT NULL,
  `valores` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_indicadores_variables`
--

CREATE TABLE `sara_indicadores_variables` (
  `id` int(11) NOT NULL,
  `indicador_id` int(11) NOT NULL,
  `Letra` varchar(1) NOT NULL,
  `Tipo` varchar(30) NOT NULL DEFAULT 'Variable',
  `variable_id` int(11) NOT NULL,
  `Op1` varchar(100) DEFAULT NULL,
  `Op2` varchar(100) DEFAULT NULL,
  `Op3` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_logs`
--

CREATE TABLE `sara_logs` (
  `id` int(10) NOT NULL,
  `usuario_id` int(10) NOT NULL,
  `Evento` varchar(50) NOT NULL,
  `Op1` int(10) DEFAULT NULL,
  `Op2` int(10) DEFAULT NULL,
  `Op3` varchar(100) DEFAULT NULL,
  `Estado` varchar(3) NOT NULL DEFAULT 'A',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_perfiles`
--

CREATE TABLE `sara_perfiles` (
  `id` int(11) NOT NULL,
  `Perfil` varchar(100) NOT NULL,
  `Perfil_Show` varchar(300) NOT NULL,
  `Orden` int(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_perfiles`
--

INSERT INTO `sara_perfiles` (`id`, `Perfil`, `Perfil_Show`, `Orden`, `created_at`, `updated_at`) VALUES
(1, 'Usuario General', 'Usuarios', 3, '2020-03-02 14:00:00', '2020-03-02 14:00:00'),
(2, 'Responsable', 'Responsable', 1, '2020-03-02 14:00:00', '2020-03-02 14:00:00'),
(3, 'Analista', 'Analistas', 2, '2020-03-02 14:00:00', '2020-03-02 14:00:00'),
(4, 'Auditor', 'Auditores', 4, '2020-03-02 14:00:00', '2020-03-02 14:00:00'),
(5, 'Asesor', 'Asesores', 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Practicante', 'Practicantes', 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_perfiles_secciones`
--

CREATE TABLE `sara_perfiles_secciones` (
  `id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `seccion_id` varchar(50) NOT NULL,
  `Level` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sara_perfiles_secciones`
--

INSERT INTO `sara_perfiles_secciones` (`id`, `perfil_id`, `seccion_id`, `Level`, `created_at`, `updated_at`) VALUES
(1, 2, 'MisIndicadores', 3, '2020-03-06 00:00:00', '2020-03-06 00:00:00'),
(2, 3, 'MisIndicadores', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2, 'IngresarDatos', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 3, 'IngresarDatos', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 2, 'MiProceso', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 3, 'MiProceso', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_procesos`
--

CREATE TABLE `sara_procesos` (
  `id` int(12) NOT NULL,
  `Proceso` varchar(100) NOT NULL,
  `Tipo` varchar(30) NOT NULL,
  `padre_id` int(11) DEFAULT NULL,
  `responsable_id` int(11) DEFAULT NULL,
  `CDC` varchar(1000) DEFAULT NULL,
  `Ruta` varchar(1000) DEFAULT NULL,
  `Introduccion` varchar(1000) DEFAULT NULL,
  `Op1` varchar(100) NOT NULL,
  `Op2` varchar(100) NOT NULL,
  `Op3` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `sara_recientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `sara_recientes` (
`Titulo` text
,`Url` varchar(27)
,`Icono` varchar(50)
,`usuario_id` int(10)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_scorecards`
--

CREATE TABLE `sara_scorecards` (
  `id` int(11) NOT NULL,
  `Ruta` varchar(1000) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Secciones` text NOT NULL,
  `config` varchar(2000) NOT NULL DEFAULT '[]',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_scorecards_card`
--

CREATE TABLE `sara_scorecards_card` (
  `id` int(11) NOT NULL,
  `scorecard_id` int(11) NOT NULL,
  `Indice` int(11) NOT NULL,
  `seccion_id` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL DEFAULT 'Indicador',
  `elemento_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_scorecards_nodos`
--

CREATE TABLE `sara_scorecards_nodos` (
  `id` int(11) NOT NULL,
  `scorecard_id` int(11) NOT NULL,
  `Nodo` varchar(100) DEFAULT NULL,
  `padre_id` int(11) DEFAULT NULL,
  `Indice` int(11) NOT NULL,
  `tipo` varchar(30) NOT NULL DEFAULT 'Nodo',
  `elemento_id` int(11) DEFAULT NULL,
  `peso` decimal(13,1) NOT NULL DEFAULT 1.0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_scorecards_nodos_valores`
--

CREATE TABLE `sara_scorecards_nodos_valores` (
  `id` int(11) NOT NULL,
  `nodo_id` int(11) NOT NULL,
  `Anio` int(5) NOT NULL,
  `valores` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
('Apps', 'Aplicaciones', 21, 'fa-cubes', 'A'),
('BDD', 'Conexiones', 5, 'fa-database', 'A'),
('Bots', 'Bots', 9999, 'fa-robot', 'A'),
('Configuracion', 'Configuración', 99999, 'fa-cogs', 'A'),
('Consultas', 'Consultas SQL', 9999, 'fa-bolt', 'A'),
('Entidades', 'Entidades', 10, 'fa-chess', 'A'),
('Funciones', 'Funciones', 50, 'fa-tools', 'I'),
('Indicadores', 'Indicadores', 16, 'fa-chart-line', 'A'),
('IngresarDatos', 'Ingresar Datos', 100, 'fa-edit', 'A'),
('Integraciones', 'Integraciones', 9998, 'fa-share-alt', 'A'),
('MiProceso', 'Mi Proceso', 102, 'fa-cube', 'A'),
('MisIndicadores', 'Mis Indicadores', 101, 'fa-chart-line', 'A'),
('Procesos', 'Procesos', 32, 'fa-sitemap', 'A'),
('Scorecards', 'Tableros', 20, 'fa-th-large', 'A'),
('Usuarios', 'Usuarios', 30, 'fa-users', 'A'),
('Variables', 'Variables', 15, 'fa-superscript', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_usuarios`
--

CREATE TABLE `sara_usuarios` (
  `id` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(70) DEFAULT NULL,
  `Cedula` varchar(30) DEFAULT NULL,
  `Nombres` varchar(255) NOT NULL,
  `CDC_id` int(11) DEFAULT NULL,
  `isGod` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sara_usuarios`
--

INSERT INTO `sara_usuarios` (`id`, `Email`, `Password`, `Cedula`, `Nombres`, `CDC_id`, `isGod`, `created_at`, `updated_at`) VALUES
(183, 'corrego@comfamiliar.com', '$2y$10$uduzNMl6VznR9O.xDjKkeuYTO.3zQiYlqQEjO6bIx6as5o5kqscPG', '1093217141', 'Vicious Grant', 3010, 1, '2017-12-26 16:37:15', '2021-10-25 03:06:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_usuarios_asignacion`
--

CREATE TABLE `sara_usuarios_asignacion` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nodo_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_usuario_apps`
--

CREATE TABLE `sara_usuario_apps` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `favorito` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_variables`
--

CREATE TABLE `sara_variables` (
  `id` int(11) NOT NULL,
  `Ruta` varchar(1000) NOT NULL,
  `proceso_id` int(11) DEFAULT NULL,
  `Variable` varchar(255) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `TipoDato` varchar(50) NOT NULL DEFAULT 'Numero',
  `Decimales` int(2) NOT NULL DEFAULT 0,
  `Tipo` varchar(50) NOT NULL DEFAULT 'Valor Fijo',
  `Frecuencia` int(100) NOT NULL DEFAULT 1,
  `Acumulada` varchar(10) NOT NULL DEFAULT 'No',
  `grid_id` int(11) DEFAULT NULL,
  `ColPeriodo` int(11) DEFAULT NULL,
  `Agrupador` varchar(20) NOT NULL DEFAULT 'count',
  `Col` int(11) DEFAULT NULL,
  `Filtros` text NOT NULL,
  `DiasDesde` int(10) DEFAULT NULL,
  `DiasHasta` int(10) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sara_variables_valores`
--

CREATE TABLE `sara_variables_valores` (
  `id` int(15) NOT NULL,
  `variable_id` int(11) NOT NULL,
  `Periodo` int(8) NOT NULL,
  `Valor` decimal(24,4) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura para la vista `sara_recientes`
--
DROP TABLE IF EXISTS `sara_recientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sara_recientes`  AS  select concat(`a`.`Titulo`,concat(' - ',`p`.`Titulo`)) AS `Titulo`,concat('/#/a/',concat(`a`.`Slug`,convert(concat('/',`p`.`id`) using latin1))) AS `Url`,`a`.`Icono` AS `Icono`,`l`.`usuario_id` AS `usuario_id` from ((`sara_logs` `l` join `sara_apps_pages` `p` on(`l`.`Op1` = `p`.`id`)) join `sara_apps` `a` on(`p`.`app_id` = `a`.`id`)) where 1 = 1 and `l`.`Evento` = 'AppPage' order by `l`.`created_at` desc ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `sara_apps`
--
ALTER TABLE `sara_apps`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_apps_pages`
--
ALTER TABLE `sara_apps_pages`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `sara_bdds_listas`
--
ALTER TABLE `sara_bdds_listas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bdd_id` (`bdd_id`);

--
-- Indices de la tabla `sara_bots`
--
ALTER TABLE `sara_bots`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_bots_logs`
--
ALTER TABLE `sara_bots_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot_id` (`bot_id`,`bot_paso_id`);

--
-- Indices de la tabla `sara_bots_pasos`
--
ALTER TABLE `sara_bots_pasos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_bots_variables`
--
ALTER TABLE `sara_bots_variables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot_id` (`bot_id`);

--
-- Indices de la tabla `sara_comentarios`
--
ALTER TABLE `sara_comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Entidad_id` (`Entidad_id`,`usuario_id`);

--
-- Indices de la tabla `sara_configuracion`
--
ALTER TABLE `sara_configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_entidades`
--
ALTER TABLE `sara_entidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bdd_id` (`bdd_id`),
  ADD KEY `proceso_id` (`proceso_id`);

--
-- Indices de la tabla `sara_entidades_campos`
--
ALTER TABLE `sara_entidades_campos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entidad_id` (`entidad_id`);

--
-- Indices de la tabla `sara_entidades_cargadores`
--
ALTER TABLE `sara_entidades_cargadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_entidades_editores`
--
ALTER TABLE `sara_entidades_editores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_entidades_editores_campos`
--
ALTER TABLE `sara_entidades_editores_campos`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `sara_feedback`
--
ALTER TABLE `sara_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_iconos`
--
ALTER TABLE `sara_iconos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_indicadores`
--
ALTER TABLE `sara_indicadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_indicadores_metas`
--
ALTER TABLE `sara_indicadores_metas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indicador_id` (`indicador_id`);

--
-- Indices de la tabla `sara_indicadores_valores`
--
ALTER TABLE `sara_indicadores_valores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nodo_id` (`indicador_id`,`Anio`,`created_at`,`updated_at`);

--
-- Indices de la tabla `sara_indicadores_variables`
--
ALTER TABLE `sara_indicadores_variables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indicador_id` (`indicador_id`);

--
-- Indices de la tabla `sara_logs`
--
ALTER TABLE `sara_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_perfiles`
--
ALTER TABLE `sara_perfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_perfiles_secciones`
--
ALTER TABLE `sara_perfiles_secciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perfil_id` (`perfil_id`),
  ADD KEY `seccion_id` (`seccion_id`);

--
-- Indices de la tabla `sara_procesos`
--
ALTER TABLE `sara_procesos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_scorecards`
--
ALTER TABLE `sara_scorecards`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_scorecards_card`
--
ALTER TABLE `sara_scorecards_card`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_scorecards_nodos`
--
ALTER TABLE `sara_scorecards_nodos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scorecard_id` (`scorecard_id`,`padre_id`,`Indice`);

--
-- Indices de la tabla `sara_scorecards_nodos_valores`
--
ALTER TABLE `sara_scorecards_nodos_valores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nodo_id` (`nodo_id`,`Anio`,`created_at`,`updated_at`);

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
-- Indices de la tabla `sara_usuarios_asignacion`
--
ALTER TABLE `sara_usuarios_asignacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_usuario_apps`
--
ALTER TABLE `sara_usuario_apps`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sara_variables`
--
ALTER TABLE `sara_variables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Frecuencia` (`Frecuencia`);

--
-- Indices de la tabla `sara_variables_valores`
--
ALTER TABLE `sara_variables_valores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `var_periodo` (`variable_id`,`Periodo`),
  ADD KEY `Periodo` (`Periodo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sara_apps`
--
ALTER TABLE `sara_apps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_apps_pages`
--
ALTER TABLE `sara_apps_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_bdds`
--
ALTER TABLE `sara_bdds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_bdds_favoritos`
--
ALTER TABLE `sara_bdds_favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_bdds_listas`
--
ALTER TABLE `sara_bdds_listas`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_bots`
--
ALTER TABLE `sara_bots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_bots_logs`
--
ALTER TABLE `sara_bots_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_bots_pasos`
--
ALTER TABLE `sara_bots_pasos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_bots_variables`
--
ALTER TABLE `sara_bots_variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_comentarios`
--
ALTER TABLE `sara_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_configuracion`
--
ALTER TABLE `sara_configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades`
--
ALTER TABLE `sara_entidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_campos`
--
ALTER TABLE `sara_entidades_campos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_cargadores`
--
ALTER TABLE `sara_entidades_cargadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_editores`
--
ALTER TABLE `sara_entidades_editores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_editores_campos`
--
ALTER TABLE `sara_entidades_editores_campos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_grids`
--
ALTER TABLE `sara_entidades_grids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_grids_columnas`
--
ALTER TABLE `sara_entidades_grids_columnas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_grids_filtros`
--
ALTER TABLE `sara_entidades_grids_filtros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_entidades_restricciones`
--
ALTER TABLE `sara_entidades_restricciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_feedback`
--
ALTER TABLE `sara_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_iconos`
--
ALTER TABLE `sara_iconos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1548;

--
-- AUTO_INCREMENT de la tabla `sara_indicadores`
--
ALTER TABLE `sara_indicadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_indicadores_metas`
--
ALTER TABLE `sara_indicadores_metas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_indicadores_valores`
--
ALTER TABLE `sara_indicadores_valores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_indicadores_variables`
--
ALTER TABLE `sara_indicadores_variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_logs`
--
ALTER TABLE `sara_logs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_perfiles`
--
ALTER TABLE `sara_perfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sara_perfiles_secciones`
--
ALTER TABLE `sara_perfiles_secciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sara_procesos`
--
ALTER TABLE `sara_procesos`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_scorecards`
--
ALTER TABLE `sara_scorecards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_scorecards_card`
--
ALTER TABLE `sara_scorecards_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_scorecards_nodos`
--
ALTER TABLE `sara_scorecards_nodos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_scorecards_nodos_valores`
--
ALTER TABLE `sara_scorecards_nodos_valores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_usuarios`
--
ALTER TABLE `sara_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3442;

--
-- AUTO_INCREMENT de la tabla `sara_usuarios_asignacion`
--
ALTER TABLE `sara_usuarios_asignacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_usuario_apps`
--
ALTER TABLE `sara_usuario_apps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_variables`
--
ALTER TABLE `sara_variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sara_variables_valores`
--
ALTER TABLE `sara_variables_valores`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sara_entidades`
--
ALTER TABLE `sara_entidades`
  ADD CONSTRAINT `sara_entidades_ibfk_1` FOREIGN KEY (`proceso_id`) REFERENCES `sara_procesos` (`id`);

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

--
-- Filtros para la tabla `sara_indicadores_metas`
--
ALTER TABLE `sara_indicadores_metas`
  ADD CONSTRAINT `sara_indicadores_metas_ibfk_1` FOREIGN KEY (`indicador_id`) REFERENCES `sara_indicadores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sara_indicadores_variables`
--
ALTER TABLE `sara_indicadores_variables`
  ADD CONSTRAINT `sara_indicadores_variables_ibfk_1` FOREIGN KEY (`indicador_id`) REFERENCES `sara_indicadores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sara_perfiles_secciones`
--
ALTER TABLE `sara_perfiles_secciones`
  ADD CONSTRAINT `sara_perfiles_secciones_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `sara_perfiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sara_perfiles_secciones_ibfk_2` FOREIGN KEY (`seccion_id`) REFERENCES `sara_secciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sara_scorecards_nodos`
--
ALTER TABLE `sara_scorecards_nodos`
  ADD CONSTRAINT `sara_scorecards_nodos_ibfk_1` FOREIGN KEY (`scorecard_id`) REFERENCES `sara_scorecards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
