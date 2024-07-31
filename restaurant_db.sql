-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-07-2024 a las 10:18:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurant_db`
--

DELIMITER $$
--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `incrementar_cantidad_compras` (`cliente_dni` VARCHAR(20)) RETURNS INT(11)  BEGIN
    DECLARE nueva_cantidad_compras INT;
    DECLARE nuevo_tipo_cliente_id INT;

    -- Incrementar la cantidad de compras
    UPDATE clientes 
    SET CantidadCompras = CantidadCompras + 1 
    WHERE Dni = cliente_dni;

    -- Obtener la nueva cantidad de compras
    SELECT CantidadCompras INTO nueva_cantidad_compras
    FROM clientes
    WHERE Dni = cliente_dni;

    -- Determinar el nuevo tipo de cliente
    IF nueva_cantidad_compras BETWEEN 0 AND 5 THEN
        SET nuevo_tipo_cliente_id = 1; -- Nuevo
    ELSEIF nueva_cantidad_compras BETWEEN 6 AND 20 THEN
        SET nuevo_tipo_cliente_id = 2; -- Frecuente
    ELSEIF nueva_cantidad_compras BETWEEN 21 AND 50 THEN
        SET nuevo_tipo_cliente_id = 3; -- VIP
    ELSE
        SET nuevo_tipo_cliente_id = 4; -- Premium
    END IF;

    -- Actualizar el tipo de cliente
    UPDATE clientes 
    SET TipoClienteID = nuevo_tipo_cliente_id
    WHERE Dni = cliente_dni;

    RETURN nueva_cantidad_compras;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `incrementar_contador_modificador` (`modification_id` INT) RETURNS INT(11)  BEGIN
    UPDATE modificaciones_plato 
    SET counter = counter + 1 
    WHERE id = modification_id;

    RETURN (SELECT counter FROM modificaciones_plato WHERE id = modification_id);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Entradas'),
(2, 'Sopas'),
(3, 'Platos Principales'),
(4, 'Postres'),
(5, 'Bebidas'),
(6, 'Guarniciones'),
(7, 'Combos'),
(8, 'Promociones'),
(14, 'Especialidad de la casa'),
(22, 'Patrióticos'),
(23, 'Navideños'),
(24, 'Mariscos'),
(26, 'Especialidad de la casa'),
(27, 'Jueves');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('general','role','restricted') NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `chats`
--

INSERT INTO `chats` (`id`, `name`, `type`, `role_id`, `created_at`) VALUES
(1, 'Chat General del Restaurante', 'general', NULL, '2024-07-30 21:34:55'),
(2, 'Chat de Gerencia', 'role', 1, '2024-07-30 21:34:55'),
(3, 'Chat de Cocina', 'role', 3, '2024-07-30 21:34:55'),
(4, 'Chat de Servicio', 'role', 9, '2024-07-30 21:34:55'),
(5, 'Chat de Bebidas', 'restricted', NULL, '2024-07-30 21:34:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_permissions`
--

CREATE TABLE `chat_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `granted_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `chat_permissions`
--

INSERT INTO `chat_permissions` (`id`, `user_id`, `chat_id`, `granted_by`, `created_at`) VALUES
(1, 2, 5, 6, '2024-07-30 21:39:50'),
(2, 3, 5, 6, '2024-07-30 21:39:50'),
(3, 5, 5, 6, '2024-07-30 21:39:50'),
(4, 4, 5, 6, '2024-07-30 21:39:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `Dni` varchar(8) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `TipoClienteID` int(11) DEFAULT NULL,
  `CantidadCompras` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`Dni`, `Nombre`, `Email`, `Telefono`, `Direccion`, `TipoClienteID`, `CantidadCompras`) VALUES
('11223344', 'Carlos López', 'carlos.lopez@example.com', '112233445', 'Boulevard de los Sueños 789', 3, 25),
('12345678', 'Juan Pérez', 'juan.perez@example.com', '123456789', 'Calle Falsa 123', 1, 3),
('21223344', 'Pepe Pérez', '', '', '', 1, 0),
('72363424', 'LUZ MELINDA TOLENTINO ARANDA', '', '', '', 1, 0),
('72712421', 'MELANNY IVETT VILLEGAS CHAVEZ', '', '', '', 1, 0),
('72893091', 'HENRY KENIDING TARAZONA LAZARO', '', '', '', 2, 15),
('72893092', 'RODRIGO JUANDIEGO TEJADA GAITAN', '', '', '', 1, 1),
('72893099', 'EVITA NERY VELASQUEZ PABLO', '', '', '', 1, 0),
('72893301', 'GREG GIANCARLOS ARCELA SEDANO', '', '', '', 1, 0),
('72893421', 'FLORA LLASCANOA MENDOZA', '', '', '', 1, 0),
('72893453', 'RONALD PAUL CHULLO SOTO', '', '', '', 1, 0),
('73078411', 'JOSE DANIEL SEBASTIAN REYES QUIROZ', '', '', '', 1, 0),
('73252134', 'REYNA YSABEL WILLYANA CHERO FARRO', '', '', '', 1, 0),
('73954521', 'ANA CLAUDIA AMAYA HUARCAYA', '', '', '', 1, 0),
('76234532', 'JUAN MANUEL HUILLCA CRUZ', '', '', '', 1, 0),
('77234313', 'ANYELA LIZET CAYAO TORRES', '', '', '', 1, 1),
('77665544', 'ANGELO BOSCO AGUIRRE HIDALGO', '', '', '', 1, 0),
('87654321', 'María García', 'maria.garcia@example.com', '987654321', 'Avenida Siempre Viva 456', 2, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comidas`
--

CREATE TABLE `comidas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `disponibilidad` tinyint(1) DEFAULT 1,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comidas`
--

INSERT INTO `comidas` (`id`, `nombre`, `descripcion`, `precio`, `disponibilidad`, `categoria_id`) VALUES
(1, 'Ceviche', 'Pescado marinado en jugo de limón con cebolla, ají y cilantro.', 15.00, 1, 1),
(2, 'Papa a la Huancaína', 'Papas hervidas cubiertas con una salsa de queso y ají amarillo.', 8.00, 1, 1),
(3, 'Sopa a la Criolla', 'Sopa de carne con fideos, leche, huevo y especias.', 10.00, 1, 2),
(4, 'Ají de Gallina', 'Pollo desmenuzado en una salsa de ají amarillo, leche y pan.', 12.00, 1, 3),
(5, 'Lomo Saltado', 'Salteado de carne de res con cebolla, tomate y papas fritas.', 14.00, 1, 3),
(6, 'Arroz con Pollo', 'Pollo cocido con arroz verde, cilantro, arvejas y zanahorias.', 11.00, 1, 3),
(7, 'Suspiro a la Limeña', 'Postre hecho de leche condensada, yemas de huevo y merengue.', 6.00, 1, 4),
(8, 'Picarones', 'Buñuelos de calabaza y camote servidos con miel de chancaca.', 7.00, 1, 4),
(9, 'Chicha Morada', 'Bebida de maíz morado con piña, canela y clavo.', 3.00, 1, 5),
(10, 'Inca Kola', 'Refresco peruano de sabor dulce y color amarillo.', 2.50, 1, 5),
(11, 'Papas Fritas', 'Papas fritas crujientes servidas con ketchup.', 4.00, 1, 6),
(12, 'Ensalada Mixta', 'Ensalada de lechuga, tomate, cebolla, pepino y zanahoria.', 5.00, 1, 6),
(13, 'Combo Lomo Saltado', 'Lomo Saltado con arroz, papas fritas y una bebida.', 18.00, 1, 7),
(14, 'Combo Pollo a la Brasa', 'Pollo a la brasa con papas fritas, ensalada y una bebida.', 20.00, 1, 7),
(15, 'Promoción 2x1 Ceviche', 'Dos ceviches al precio de uno.', 15.00, 1, 8),
(16, 'Promoción Postre Gratis', 'Un postre gratis por cada plato principal.', 0.00, 1, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_mesa`
--

CREATE TABLE `estados_mesa` (
  `id_estado_mesa` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_mesa`
--

INSERT INTO `estados_mesa` (`id_estado_mesa`, `nombre`, `descripcion`) VALUES
(1, 'Disponible', 'Mesa lista para ser ocupada'),
(2, 'Ocupada', 'Mesa actualmente en uso por clientes'),
(3, 'Reservada', 'Mesa apartada para una reservación futura'),
(4, 'Limpieza', 'Mesa siendo preparada para el próximo cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` varchar(100) NOT NULL,
  `Fecha` date NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `dni_cliente` varchar(20) NOT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `Fecha`, `Total`, `dni_cliente`, `estado`) VALUES
('28072024000624557X9JNSGURVZ', '2024-07-28', 42.00, '72893091', 'Pendiente'),
('28072024001235568UU3ML6ZHL1', '2024-07-28', 42.00, '72893091', 'Pendiente'),
('280720240013286345WWCHUPWID', '2024-07-28', 14.00, '72893091', 'Pendiente'),
('28072024001513473H6VY2C976L', '2024-07-28', 14.00, '72893091', 'Pendiente'),
('28072024001739206UO2VJ9LHVV', '2024-07-28', 28.00, '72893091', 'Pendiente'),
('28072024001828216HARTR2PPR6', '2024-07-28', 42.00, '72893091', 'Pendiente'),
('28072024002730039BY3O5C1TUB', '2024-07-28', 14.00, '72893091', 'Pendiente'),
('28072024002939444UP6PO8E7YD', '2024-07-28', 28.00, '72893091', 'Pendiente'),
('28072024003302927LX3FNPH9IP', '2024-07-28', 14.00, '72893091', 'Pendiente'),
('2807202400352191091XBF5SG8X', '2024-07-28', 28.00, '72893091', 'Pendiente'),
('28072024003724760MFXM67LP6A', '2024-07-28', 42.00, '72893091', 'Pendiente'),
('28072024004119838H4RW9Q4T2R', '2024-07-28', 28.00, '72893091', 'Pendiente'),
('28072024004541741TJZFFM347E', '2024-07-28', 28.00, '72893091', 'Pendiente'),
('28072024004648602Y8OIGTLWN8', '2024-07-28', 14.00, '72893091', 'Pendiente'),
('28072024005150147JB5OPCT7T9', '2024-07-28', 0.00, '72893091', 'Pendiente'),
('28072024005235224Y7G2BFOHSL', '2024-07-28', 14.00, '72893091', 'Pendiente'),
('280720240052498621TTF8IHC4F', '2024-07-28', 28.00, '72893091', 'Pendiente'),
('28072024005303907S5H6AG20GA', '2024-07-28', 14.00, '72893091', 'Pendiente'),
('28072024005345091ZSC2CDMVFY', '2024-07-28', 14.00, '72893091', 'Pendiente'),
('28072024005407947FIV8IW1PMF', '2024-07-28', 70.00, '72893091', 'Pendiente'),
('280720240101275822G6CM1439O', '2024-07-28', 14.00, '72893092', 'Pendiente'),
('28072024010209404QM71ZQBIYC', '2024-07-28', 28.00, '72893091', 'Pendiente'),
('28072024220027581M01BHVDGL7', '2024-07-29', 12.00, '77234313', 'Pendiente'),
('29072024033121033RQV3IOGTQG', '2024-07-29', 14.00, '72893091', 'Pendiente'),
('29072024033625608RXH1DAA7P1', '2024-07-29', 7.00, '72893091', 'Pendiente'),
('29072024033825494Z0G837A309', '2024-07-29', 7.00, '72893091', 'Pendiente'),
('29072024034525932Q2YUINVL2A', '2024-07-29', 7.00, '72893091', 'Pendiente'),
('29072024034806311VBC8FMA0AC', '2024-07-29', 7.00, '72893091', 'Pendiente'),
('29072024035146964M1WRR5K631', '2024-07-29', 7.00, '72893091', 'Pendiente'),
('29072024035341233184IZHCOR0', '2024-07-29', 7.00, '72893091', 'Pendiente'),
('29072024035911462FYTY2X1DM3', '2024-07-29', 7.00, '72893091', 'Pendiente'),
('F001', '2024-07-01', 150.75, '11223344', 'Pendiente'),
('F002', '2024-07-02', 200.50, '12345678', 'Pendiente'),
('F003', '2024-07-03', 300.00, '87654321', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id_mesa` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `id_estado_mesa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id_mesa`, `capacidad`, `id_estado_mesa`) VALUES
(41, 2, 2),
(42, 2, 1),
(43, 4, 1),
(44, 4, 1),
(45, 4, 2),
(46, 4, 2),
(47, 4, 1),
(48, 4, 1),
(49, 6, 1),
(50, 6, 1),
(51, 6, 1),
(52, 6, 1),
(53, 8, 1),
(54, 8, 1),
(55, 8, 2),
(56, 10, 1),
(57, 10, 1),
(58, 12, 1),
(59, 16, 1),
(60, 20, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `user_id`, `content`, `created_at`) VALUES
(31, 1, 2, '¡Bienvenidos al nuevo sistema de chat del restaurante! Aquí podremos comunicarnos mejor entre todos los departamentos.', '2024-07-30 21:38:35'),
(32, 1, 3, 'Gracias por la implementación. Recordemos usarlo para mejorar nuestra coordinación.', '2024-07-30 21:38:35'),
(33, 2, 2, 'Reunión de gerencia mañana a las 9:00 AM para discutir los resultados del mes.', '2024-07-30 21:38:35'),
(34, 2, 3, 'Entendido, prepararé el informe de ventas para la reunión.', '2024-07-30 21:38:35'),
(35, 3, 2, 'Atención equipo de cocina: hemos actualizado el menú para el evento del viernes.', '2024-07-30 21:38:35'),
(36, 3, 3, 'Recibido, Chef. ¿Podemos revisar juntos los nuevos platos esta tarde?', '2024-07-30 21:38:35'),
(37, 4, 5, 'Recordatorio: siempre pregunten por alergias o restricciones dietéticas al tomar la orden.', '2024-07-30 21:38:35'),
(38, 4, 4, 'Entendido. También informo que tenemos una reserva grande para el sábado, 20 personas.', '2024-07-30 21:38:35'),
(39, 5, 6, 'Nuevo café especial disponible desde hoy. Pruébenlo para poder recomendarlo a los clientes.', '2024-07-30 21:38:35'),
(40, 5, 5, 'Gracias por el aviso. Por cierto, estamos por agotar el whisky premium, ¿podemos hacer un pedido?', '2024-07-30 21:38:35'),
(44, 1, 3, 'hi', '2024-07-30 22:15:42'),
(46, 1, 5, 'hi', '2024-07-30 22:17:04'),
(48, 1, 5, 'q tal', '2024-07-30 22:29:20'),
(125, 1, 5, 'hola', '2024-07-31 07:57:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modificaciones_plato`
--

CREATE TABLE `modificaciones_plato` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('agregar','quitar','modificar') NOT NULL,
  `color` varchar(7) NOT NULL,
  `counter` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modificaciones_plato`
--

INSERT INTO `modificaciones_plato` (`id`, `name`, `category`, `color`, `counter`) VALUES
(1, 'Sin Sal', 'quitar', '#ff0000', 7),
(2, 'Extra Picante', 'agregar', '#00ff00', 1),
(3, 'Vegetariano', 'modificar', '#0000ff', 1),
(4, 'Sin Gluten', 'quitar', '#ff0000', 2),
(5, 'Papas', 'quitar', '#ff0000', 1),
(10, 'Sal', 'agregar', '#00ff00', 1),
(11, 'A lo pobre', 'modificar', '#0000ff', 1),
(12, 'Salsa ostion', 'agregar', '#00ff00', 1),
(13, 'Platano', 'quitar', '#ff0000', 0),
(14, 'Taypa', 'modificar', '#0000ff', 5),
(15, 'Tallarin', 'quitar', '#ff0000', 0),
(16, 'Huevo', 'agregar', '#00ff00', 1),
(17, 'Platano', 'agregar', '#00ff00', 0),
(18, 'Jugoso', 'modificar', '#0000ff', 1),
(19, 'Crocante', 'modificar', '#0000ff', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` int(11) NOT NULL,
  `FacturaID` varchar(255) NOT NULL,
  `hora_orden` datetime NOT NULL DEFAULT current_timestamp(),
  `ComidaID` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'Completada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `FacturaID`, `hora_orden`, `ComidaID`, `Cantidad`, `Precio`, `estado`) VALUES
(1, 'F001', '2024-07-28 21:19:41', 1, 2, 12.50, 'Completada'),
(2, 'F001', '2024-07-28 21:19:41', 2, 1, 8.75, 'Completada'),
(3, 'F002', '2024-07-28 21:19:41', 3, 3, 7.25, 'Completada'),
(4, 'F003', '2024-07-28 21:19:41', 1, 1, 12.50, 'Completada'),
(5, 'F003', '2024-07-28 21:19:41', 2, 2, 8.75, 'Completada'),
(10, '28072024001513473H6VY2C976L', '2024-07-28 21:19:41', 5, 1, 14.00, 'Completada'),
(11, '28072024001739206UO2VJ9LHVV', '2024-07-28 21:19:41', 5, 2, 28.00, 'Completada'),
(12, '28072024001828216HARTR2PPR6', '2024-07-28 21:19:41', 5, 3, 42.00, 'Completada'),
(13, '28072024002730039BY3O5C1TUB', '2024-07-28 21:19:41', 5, 1, 14.00, 'Completada'),
(14, '28072024002939444UP6PO8E7YD', '2024-07-28 21:19:41', 5, 2, 28.00, 'Completada'),
(15, '28072024003302927LX3FNPH9IP', '2024-07-28 21:19:41', 5, 1, 14.00, 'Completada'),
(16, '2807202400352191091XBF5SG8X', '2024-07-28 21:19:41', 5, 2, 28.00, 'Completada'),
(17, '28072024003724760MFXM67LP6A', '2024-07-28 21:19:41', 5, 3, 42.00, 'Completada'),
(18, '28072024004119838H4RW9Q4T2R', '2024-07-28 21:19:41', 5, 2, 28.00, 'Completada'),
(19, '28072024004541741TJZFFM347E', '2024-07-28 21:19:41', 5, 2, 28.00, 'Completada'),
(20, '28072024004648602Y8OIGTLWN8', '2024-07-28 21:19:41', 5, 1, 14.00, 'Completada'),
(21, '28072024005150147JB5OPCT7T9', '2024-07-28 21:19:41', 5, 0, 0.00, 'Completada'),
(22, '28072024005235224Y7G2BFOHSL', '2024-07-28 21:19:41', 5, 1, 14.00, 'Completada'),
(23, '280720240052498621TTF8IHC4F', '2024-07-28 21:19:41', 5, 2, 28.00, 'Completada'),
(24, '28072024005303907S5H6AG20GA', '2024-07-28 21:19:41', 5, 1, 14.00, 'Completada'),
(25, '28072024005345091ZSC2CDMVFY', '2024-07-28 21:19:41', 5, 1, 14.00, 'Completada'),
(26, '28072024005407947FIV8IW1PMF', '2024-07-28 21:19:41', 5, 5, 70.00, 'Completada'),
(27, '280720240101275822G6CM1439O', '2024-07-28 21:19:41', 5, 1, 14.00, 'Completada'),
(28, '28072024010209404QM71ZQBIYC', '2024-07-28 21:19:41', 5, 2, 28.00, 'Completada'),
(29, '28072024220027581M01BHVDGL7', '2024-07-28 22:00:58', 7, 2, 12.00, 'Completada'),
(30, '28072024220027581M01BHVDGL7', '2024-07-28 22:01:06', 7, 2, 12.00, 'Completada'),
(31, '29072024033121033RQV3IOGTQG', '2024-07-29 03:31:50', 8, 2, 14.00, 'Completada'),
(32, '29072024033625608RXH1DAA7P1', '2024-07-29 03:36:36', 8, 1, 7.00, 'Completada'),
(33, '29072024033825494Z0G837A309', '2024-07-29 03:38:36', 8, 1, 7.00, 'Completada'),
(34, '29072024034525932Q2YUINVL2A', '2024-07-29 03:45:43', 8, 1, 7.00, 'Completada'),
(35, '29072024034806311VBC8FMA0AC', '2024-07-29 03:48:19', 8, 1, 7.00, 'Completada'),
(36, '29072024035146964M1WRR5K631', '2024-07-29 03:52:08', 8, 1, 7.00, 'Completada'),
(37, '29072024035341233184IZHCOR0', '2024-07-29 03:53:54', 8, 1, 7.00, 'Completada'),
(38, '29072024035911462FYTY2X1DM3', '2024-07-29 03:59:26', 8, 1, 7.00, 'Completada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_modificaciones`
--

CREATE TABLE `ordenes_modificaciones` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) NOT NULL,
  `modificacion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_modificaciones`
--

INSERT INTO `ordenes_modificaciones` (`id`, `orden_id`, `modificacion_id`) VALUES
(7, 1, 1),
(8, 1, 3),
(9, 2, 1),
(14, 19, 1),
(15, 20, 12),
(16, 20, 14),
(17, 21, 1),
(18, 21, 14),
(19, 22, 16),
(20, 23, 10),
(21, 24, 4),
(22, 25, 2),
(23, 26, 5),
(24, 27, 3),
(25, 28, 4),
(26, 29, 1),
(27, 30, 1),
(28, 33, 14),
(29, 34, 14),
(30, 36, 11),
(31, 36, 14),
(32, 37, 1),
(33, 38, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_mesa` int(11) DEFAULT NULL,
  `dni_cliente` varchar(20) DEFAULT NULL,
  `hora_reserva` datetime NOT NULL,
  `numero_invitados` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_mesa`, `dni_cliente`, `hora_reserva`, `numero_invitados`, `estado`) VALUES
(21, 41, '12345678', '2024-07-30 19:00:00', 4, 'Confirmada'),
(22, 42, '72363424', '2024-07-30 20:30:00', 2, 'Pendiente'),
(23, 43, '72712421', '2024-07-31 18:00:00', 6, 'Confirmada'),
(24, 44, '72893091', '2024-07-31 19:30:00', 2, 'Cancelada'),
(25, 45, '72893092', '2024-08-01 20:00:00', 8, 'Confirmada'),
(26, 41, '72893099', '2024-08-01 18:30:00', 3, 'Pendiente'),
(27, 42, '72893301', '2024-08-02 19:00:00', 4, 'Confirmada'),
(28, 43, '72893421', '2024-08-02 20:30:00', 5, 'Confirmada'),
(29, 44, '72893453', '2024-08-03 18:00:00', 2, 'Pendiente'),
(30, 45, '73078411', '2024-08-03 19:30:00', 7, 'Confirmada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Gerente', 'Responsable de la gestión general del restaurante.', '2024-06-24 22:33:02'),
(2, 'Subgerente', 'Asiste al gerente en la gestión del restaurante.', '2024-06-24 22:33:02'),
(3, 'Chef Ejecutivo', 'Encargado de la cocina y de la creación del menú.', '2024-06-24 22:33:02'),
(4, 'Sous Chef', 'Asistente directo del chef ejecutivo.', '2024-06-24 22:33:02'),
(5, 'Cocinero', 'Prepara los alimentos según las recetas del restaurante.', '2024-06-24 22:33:02'),
(6, 'Ayudante de Cocina', 'Asiste a los cocineros en la preparación de los alimentos.', '2024-06-24 22:33:02'),
(7, 'Pastelero', 'Especialista en postres y productos de repostería.', '2024-06-24 22:33:02'),
(8, 'Barista', 'Prepara y sirve bebidas de café y otras bebidas.', '2024-06-24 22:33:02'),
(9, 'Mesero', 'Atiende a los clientes y toma sus órdenes.', '2024-06-24 22:33:02'),
(10, 'Host/Hostess', 'Recibe a los clientes y los acompaña a sus mesas.', '2024-06-24 22:33:02'),
(11, 'Barman', 'Prepara y sirve bebidas alcohólicas.', '2024-06-24 22:33:02'),
(12, 'Lavaplatos', 'Encargado de lavar los platos y utensilios de cocina.', '2024-06-24 22:33:02'),
(13, 'Personal de Limpieza', 'Mantiene las áreas del restaurante limpias.', '2024-06-24 22:33:02'),
(14, 'Cajero', 'Maneja las transacciones y el dinero del restaurante.', '2024-06-24 22:33:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposclientes`
--

CREATE TABLE `tiposclientes` (
  `id` int(11) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `MinCompras` int(11) NOT NULL,
  `MaxCompras` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiposclientes`
--

INSERT INTO `tiposclientes` (`id`, `Descripcion`, `MinCompras`, `MaxCompras`) VALUES
(1, 'Nuevo', 0, 5),
(2, 'Frecuente', 6, 20),
(3, 'VIP', 21, 50),
(4, 'Premium', 51, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `dni`, `username`, `password`, `email`, `telefono`, `rol_id`, `estado`) VALUES
(2, '72893091', 'keniding', '$2y$10$yq8nNZYam2m0lcvh6IsIreTlcnkEcvav/ZihTWmKcR0UvnzYIDufS', 'u20310552@utp.edu.pe', '999666111', 3, 1),
(3, '42325253', 'acacsa', '$2y$10$Py6uXBpJaTZqF.o3xur8P.REC5Pkg3GDyiXoFq4bcXo1LyDnXRsBS', 'u20310552@utp.edu.pe', '51998760722', 4, 1),
(4, '32562362', 'gianna', '$2y$10$Suc9cUxxU2mOeZq7xFKNWeN6R0pwSf1j.NhFHnWmMlhyGCnf0wDzW', 'u20310552@utp.edu.pe', '51998760722', 10, 1),
(5, '12345678', 'alfa', '$2y$10$axbTD6re1tSc1WjaLW5eo.Fig0xFumfbzZWmUJrv.UZ/spJR3SsAi', 'jaisan2030@gmail.com', '51998760722', 9, 1),
(6, '65476323', 'keniding', '$2y$10$3G/WDZBozWg82KlGaQJpIOy27udZSm5UZyuNhc8rAUCZyw.hLr0qO', 'u20310552@utp.edu.pe', '51998760722', 2, 1),
(9, '12345678', 'John Doe', '$2y$10$.n6kRwNVoRizL0tEcdP7h.OKoextUObMJ1fZ8u5kt42kRS58RMwm.', 'john@example.com', '1234567890', 2, 1),
(10, '22345678', 'Johns Does', '$2y$10$PchLEGNtqKYgsACXpQtbr.FHoodiYYqi3K0WvVJ3uCV9YC.F/cj7e', 'johns@examples.com', '1234566580', 2, 1),
(30, '32345678', 'Johana Martinez', '$2y$10$AJD.ullt825hEd40EvRlQudfHnM3jDuLGTHEpVb9dMyn.RxLRyRcS', 'johana@examples.com', '2434566580', 1, 0),
(31, '728930912', 'keniding', '$2y$10$aWEEOm2Uqp1.5QRGUlIBeOoFTKfBpQsc3m50muQmZ3Gku4e0pJrJi', 'kenidingh@gmail.com', '51998760722', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `uso_mesa`
--

CREATE TABLE `uso_mesa` (
  `id_uso` int(11) NOT NULL,
  `id_factura` varchar(255) DEFAULT NULL,
  `id_mesa` int(11) DEFAULT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `uso_mesa`
--

INSERT INTO `uso_mesa` (`id_uso`, `id_factura`, `id_mesa`, `hora_inicio`, `hora_fin`) VALUES
(6, '28072024000624557X9JNSGURVZ', 41, '2024-07-28 00:06:24', '2024-07-28 02:06:24'),
(7, '28072024001235568UU3ML6ZHL1', 42, '2024-07-28 00:12:35', '2024-07-28 02:12:35'),
(8, '280720240013286345WWCHUPWID', 43, '2024-07-28 00:13:28', '2024-07-28 02:13:28'),
(9, '28072024001513473H6VY2C976L', 44, '2024-07-28 00:15:13', '2024-07-28 02:15:13'),
(10, '28072024001739206UO2VJ9LHVV', 45, '2024-07-28 00:17:39', '2024-07-28 02:17:39'),
(11, '28072024000624557X9JNSGURVZ', 41, '2024-07-28 00:06:24', '2024-07-28 02:06:24'),
(12, '28072024001235568UU3ML6ZHL1', 42, '2024-07-28 00:12:35', '2024-07-28 02:12:35'),
(13, '280720240013286345WWCHUPWID', 43, '2024-07-28 00:13:28', '2024-07-28 01:13:28'),
(14, '28072024001513473H6VY2C976L', 43, '2024-07-28 01:15:13', '2024-07-28 02:15:13'),
(15, '28072024001739206UO2VJ9LHVV', 43, '2024-07-28 02:17:39', '2024-07-28 03:17:39'),
(16, '28072024001828216HARTR2PPR6', 44, '2024-07-28 00:18:28', '2024-07-28 01:48:28'),
(17, '28072024002730039BY3O5C1TUB', 45, '2024-07-28 00:27:30', '2024-07-28 01:57:30'),
(18, '28072024000624557X9JNSGURVZ', 41, '2024-07-29 03:44:52', NULL),
(19, '29072024035146964M1WRR5K631', 45, '2024-07-29 03:52:09', NULL),
(20, '29072024035341233184IZHCOR0', 46, '2024-07-29 03:53:54', NULL),
(21, '29072024035911462FYTY2X1DM3', 55, '2024-07-29 03:59:27', NULL);

--
-- Disparadores `uso_mesa`
--
DELIMITER $$
CREATE TRIGGER `after_insert_uso_mesa` AFTER INSERT ON `uso_mesa` FOR EACH ROW BEGIN
    UPDATE mesas
    SET id_estado_mesa = 2 -- Aquí 2 representa el estado de "en uso"
    WHERE id_mesa = NEW.id_mesa;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `chat_permissions`
--
ALTER TABLE `chat_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `granted_by` (`granted_by`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`Dni`),
  ADD KEY `TipoClienteID` (`TipoClienteID`);

--
-- Indices de la tabla `comidas`
--
ALTER TABLE `comidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `estados_mesa`
--
ALTER TABLE `estados_mesa`
  ADD PRIMARY KEY (`id_estado_mesa`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dni_cliente` (`dni_cliente`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id_mesa`),
  ADD KEY `idx_estado_mesa` (`id_estado_mesa`);

--
-- Indices de la tabla `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `modificaciones_plato`
--
ALTER TABLE `modificaciones_plato`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_factura` (`FacturaID`),
  ADD KEY `fk_comida` (`ComidaID`);

--
-- Indices de la tabla `ordenes_modificaciones`
--
ALTER TABLE `ordenes_modificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_id` (`orden_id`),
  ADD KEY `modificacion_id` (`modificacion_id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `idx_reserva_mesa` (`id_mesa`),
  ADD KEY `idx_reserva_cliente` (`dni_cliente`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tiposclientes`
--
ALTER TABLE `tiposclientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `uso_mesa`
--
ALTER TABLE `uso_mesa`
  ADD PRIMARY KEY (`id_uso`),
  ADD KEY `idx_uso_mesa_factura` (`id_factura`),
  ADD KEY `idx_uso_mesa_mesa` (`id_mesa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `chat_permissions`
--
ALTER TABLE `chat_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `comidas`
--
ALTER TABLE `comidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `estados_mesa`
--
ALTER TABLE `estados_mesa`
  MODIFY `id_estado_mesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id_mesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de la tabla `modificaciones_plato`
--
ALTER TABLE `modificaciones_plato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `ordenes_modificaciones`
--
ALTER TABLE `ordenes_modificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `tiposclientes`
--
ALTER TABLE `tiposclientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `uso_mesa`
--
ALTER TABLE `uso_mesa`
  MODIFY `id_uso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `chat_permissions`
--
ALTER TABLE `chat_permissions`
  ADD CONSTRAINT `chat_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `chat_permissions_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `chat_permissions_ibfk_3` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`TipoClienteID`) REFERENCES `tiposclientes` (`id`);

--
-- Filtros para la tabla `comidas`
--
ALTER TABLE `comidas`
  ADD CONSTRAINT `comidas_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `fk_dni_cliente` FOREIGN KEY (`dni_cliente`) REFERENCES `clientes` (`Dni`);

--
-- Filtros para la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD CONSTRAINT `mesas_ibfk_1` FOREIGN KEY (`id_estado_mesa`) REFERENCES `estados_mesa` (`id_estado_mesa`);

--
-- Filtros para la tabla `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `fk_comida` FOREIGN KEY (`ComidaID`) REFERENCES `comidas` (`id`),
  ADD CONSTRAINT `fk_factura` FOREIGN KEY (`FacturaID`) REFERENCES `facturas` (`id`);

--
-- Filtros para la tabla `ordenes_modificaciones`
--
ALTER TABLE `ordenes_modificaciones`
  ADD CONSTRAINT `ordenes_modificaciones_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`),
  ADD CONSTRAINT `ordenes_modificaciones_ibfk_2` FOREIGN KEY (`modificacion_id`) REFERENCES `modificaciones_plato` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id_mesa`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`dni_cliente`) REFERENCES `clientes` (`Dni`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `uso_mesa`
--
ALTER TABLE `uso_mesa`
  ADD CONSTRAINT `uso_mesa_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id`),
  ADD CONSTRAINT `uso_mesa_ibfk_2` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id_mesa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
