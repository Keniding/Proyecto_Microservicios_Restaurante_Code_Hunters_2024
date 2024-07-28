-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-07-2024 a las 08:16:08
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
('72893091', 'HENRY KENIDING TARAZONA LAZARO', '', '', '', 2, 7),
('72893092', 'RODRIGO JUANDIEGO TEJADA GAITAN', '', '', '', 1, 1),
('72893099', 'EVITA NERY VELASQUEZ PABLO', '', '', '', 1, 0),
('72893301', 'GREG GIANCARLOS ARCELA SEDANO', '', '', '', 1, 0),
('72893421', 'FLORA LLASCANOA MENDOZA', '', '', '', 1, 0),
('72893453', 'RONALD PAUL CHULLO SOTO', '', '', '', 1, 0),
('73078411', 'JOSE DANIEL SEBASTIAN REYES QUIROZ', '', '', '', 1, 0),
('73252134', 'REYNA YSABEL WILLYANA CHERO FARRO', '', '', '', 1, 0),
('73954521', 'ANA CLAUDIA AMAYA HUARCAYA', '', '', '', 1, 0),
('76234532', 'JUAN MANUEL HUILLCA CRUZ', '', '', '', 1, 0),
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
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` varchar(100) NOT NULL,
  `Fecha` date NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `dni_cliente` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `Fecha`, `Total`, `dni_cliente`) VALUES
('28072024000624557X9JNSGURVZ', '2024-07-28', 42.00, '72893091'),
('28072024001235568UU3ML6ZHL1', '2024-07-28', 42.00, '72893091'),
('280720240013286345WWCHUPWID', '2024-07-28', 14.00, '72893091'),
('28072024001513473H6VY2C976L', '2024-07-28', 14.00, '72893091'),
('28072024001739206UO2VJ9LHVV', '2024-07-28', 28.00, '72893091'),
('28072024001828216HARTR2PPR6', '2024-07-28', 42.00, '72893091'),
('28072024002730039BY3O5C1TUB', '2024-07-28', 14.00, '72893091'),
('28072024002939444UP6PO8E7YD', '2024-07-28', 28.00, '72893091'),
('28072024003302927LX3FNPH9IP', '2024-07-28', 14.00, '72893091'),
('2807202400352191091XBF5SG8X', '2024-07-28', 28.00, '72893091'),
('28072024003724760MFXM67LP6A', '2024-07-28', 42.00, '72893091'),
('28072024004119838H4RW9Q4T2R', '2024-07-28', 28.00, '72893091'),
('28072024004541741TJZFFM347E', '2024-07-28', 28.00, '72893091'),
('28072024004648602Y8OIGTLWN8', '2024-07-28', 14.00, '72893091'),
('28072024005150147JB5OPCT7T9', '2024-07-28', 0.00, '72893091'),
('28072024005235224Y7G2BFOHSL', '2024-07-28', 14.00, '72893091'),
('280720240052498621TTF8IHC4F', '2024-07-28', 28.00, '72893091'),
('28072024005303907S5H6AG20GA', '2024-07-28', 14.00, '72893091'),
('28072024005345091ZSC2CDMVFY', '2024-07-28', 14.00, '72893091'),
('28072024005407947FIV8IW1PMF', '2024-07-28', 70.00, '72893091'),
('280720240101275822G6CM1439O', '2024-07-28', 14.00, '72893092'),
('28072024010209404QM71ZQBIYC', '2024-07-28', 28.00, '72893091'),
('F001', '2024-07-01', 150.75, '11223344'),
('F002', '2024-07-02', 200.50, '12345678'),
('F003', '2024-07-03', 300.00, '87654321');

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
(1, 'Sin Sal', 'quitar', '#ff0000', 4),
(2, 'Extra Picante', 'agregar', '#00ff00', 1),
(3, 'Vegetariano', 'modificar', '#0000ff', 1),
(4, 'Sin Gluten', 'quitar', '#ff0000', 2),
(5, 'Papas', 'quitar', '#ff0000', 1),
(10, 'Sal', 'agregar', '#00ff00', 1),
(11, 'A lo pobre', 'modificar', '#0000ff', 0),
(12, 'Salsa ostion', 'agregar', '#00ff00', 1),
(13, 'Platano', 'quitar', '#ff0000', 0),
(14, 'Taypa', 'modificar', '#0000ff', 2),
(15, 'Tallarin', 'quitar', '#ff0000', 0),
(16, 'Huevo', 'agregar', '#00ff00', 1),
(17, 'Platano', 'agregar', '#00ff00', 0),
(18, 'Jugoso', 'modificar', '#0000ff', 0),
(19, 'Crocante', 'modificar', '#0000ff', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` int(11) NOT NULL,
  `FacturaID` varchar(255) NOT NULL,
  `ComidaID` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `FacturaID`, `ComidaID`, `Cantidad`, `Precio`) VALUES
(1, 'F001', 1, 2, 12.50),
(2, 'F001', 2, 1, 8.75),
(3, 'F002', 3, 3, 7.25),
(4, 'F003', 1, 1, 12.50),
(5, 'F003', 2, 2, 8.75),
(10, '28072024001513473H6VY2C976L', 5, 1, 14.00),
(11, '28072024001739206UO2VJ9LHVV', 5, 2, 28.00),
(12, '28072024001828216HARTR2PPR6', 5, 3, 42.00),
(13, '28072024002730039BY3O5C1TUB', 5, 1, 14.00),
(14, '28072024002939444UP6PO8E7YD', 5, 2, 28.00),
(15, '28072024003302927LX3FNPH9IP', 5, 1, 14.00),
(16, '2807202400352191091XBF5SG8X', 5, 2, 28.00),
(17, '28072024003724760MFXM67LP6A', 5, 3, 42.00),
(18, '28072024004119838H4RW9Q4T2R', 5, 2, 28.00),
(19, '28072024004541741TJZFFM347E', 5, 2, 28.00),
(20, '28072024004648602Y8OIGTLWN8', 5, 1, 14.00),
(21, '28072024005150147JB5OPCT7T9', 5, 0, 0.00),
(22, '28072024005235224Y7G2BFOHSL', 5, 1, 14.00),
(23, '280720240052498621TTF8IHC4F', 5, 2, 28.00),
(24, '28072024005303907S5H6AG20GA', 5, 1, 14.00),
(25, '28072024005345091ZSC2CDMVFY', 5, 1, 14.00),
(26, '28072024005407947FIV8IW1PMF', 5, 5, 70.00),
(27, '280720240101275822G6CM1439O', 5, 1, 14.00),
(28, '28072024010209404QM71ZQBIYC', 5, 2, 28.00);

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
(25, 28, 4);

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

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dni_cliente` (`dni_cliente`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `comidas`
--
ALTER TABLE `comidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `modificaciones_plato`
--
ALTER TABLE `modificaciones_plato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `ordenes_modificaciones`
--
ALTER TABLE `ordenes_modificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
-- Restricciones para tablas volcadas
--

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
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
