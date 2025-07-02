-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-07-2025 a las 17:06:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `carpinteria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avances_produccion`
--

CREATE TABLE `avances_produccion` (
  `id` int(11) NOT NULL,
  `produccion_id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `porcentaje` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avances_produccion`
--

INSERT INTO `avances_produccion` (`id`, `produccion_id`, `descripcion`, `imagen`, `fecha`, `porcentaje`) VALUES
(1, 8, 'mod', 'img_6842faf428d2e7.70904481_istockphoto-628110806-1024x1024.jpg', '2025-06-06 14:28:04', 0),
(2, 6, 'un modelo trivial', 'uploads/produccion/img_6842fb4533e8a5.70162564_istockphoto-628110806-1024x1024.jpg', '2025-06-06 14:29:25', 0),
(5, 7, 'Avance automático para completar producción.', NULL, '2025-06-17 10:21:30', 100),
(6, 8, 'mejoramos la madera', NULL, '2025-06-17 14:06:29', 27),
(7, 8, 'montando todo', NULL, '2025-06-17 14:07:24', 62),
(8, 8, 'Avance automático para completar producción.', NULL, '2025-07-02 14:57:40', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `codigo_acceso` varchar(100) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `codigo`, `telefono`, `direccion`, `email`, `codigo_acceso`, `creado_en`) VALUES
(1, 'Rufina Batapa', '000121323', '555908967', 'lampert', 'rufina@gmail.com', 'RUBA25001', '2025-06-01 16:11:31'),
(2, 'Marieta manga', '1445785245641', '55120456', 'banapa', 'la@gmail.com', 'MAMA25002', '2025-06-03 14:27:32'),
(3, 'lucas moreno', '0001454788', '222001122', 'lamper', 'lucas@gmail.com', 'LUMO25003', '2025-06-03 14:27:44'),
(4, 'carmina', '0014578', '222547895', 'begoña 1', '', 'CACA25004', '2025-06-06 07:04:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `codigo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `proveedor_id`, `fecha`, `total`, `codigo`) VALUES
(1, 1, '2025-05-31', 115000.00, '#247'),
(2, 1, '2025-06-05', 435000.00, '#SIXBOKU-20250606-0001'),
(3, 1, '2025-06-06', 725000.00, '#SIXBOKU-20250606-0001');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre_empresa` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `iva` decimal(5,2) DEFAULT NULL,
  `nif` varchar(10) DEFAULT NULL,
  `moneda` varchar(10) DEFAULT NULL,
  `imagen` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `mision` text DEFAULT NULL,
  `historia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre_empresa`, `direccion`, `telefono`, `correo`, `logo`, `iva`, `nif`, `moneda`, `imagen`, `vision`, `mision`, `historia`) VALUES
(1, 'CARPINTERIA SIXBOKU SL', 'PERES MERCAMAR', '551718822', 'sixboku@carpinteria.net', 'uploads/configuracion/logo_6852743c38d23_WhatsApp Image 2025-05-16 at 22.17.25.jpeg', 15.00, NULL, 'XAF', 'uploads/configuracion/imagen_1748791116.jpg', 'Ser una referencia en el sector de la carpintería \r\nen Guinea Ecuatorial, destacando por la calidad, \r\nla creatividad y el trato cercano.', 'Crear muebles y soluciones de carpintería de alta calidad, \r\ncombinando tradición artesanal con innovación, para mejorar los \r\nespacios de nuestros clientes.', 'Nacimos como un pequeño taller en Ela-Nguema, fruto del esfuerzo \r\ny la pasión de nuestro fundador. Con el tiempo y gracias a la confianza \r\nde nuestros clientes, nos consolidamos en el Barrio Pérez, donde hoy \r\ntrabajamos con maquinaria moderna y un equipo profesional comprometido \r\ncon la excelencia.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compra`
--

CREATE TABLE `detalles_compra` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_compra`
--

INSERT INTO `detalles_compra` (`id`, `compra_id`, `material_id`, `cantidad`, `precio_unitario`, `stock`) VALUES
(4, 1, 6, 10, 3500.00, 0),
(5, 1, 4, 10, 5000.00, 0),
(6, 1, 5, 20, 1500.00, 0),
(7, 2, 1, 50, 2000.00, 50),
(8, 2, 2, 70, 3500.00, 70),
(9, 2, 6, 5, 4000.00, 5),
(10, 2, 4, 10, 7000.00, 10),
(11, 3, 3, 100, 2500.00, 100),
(12, 3, 1, 100, 3500.00, 100),
(13, 3, 2, 50, 2500.00, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido_material`
--

CREATE TABLE `detalles_pedido_material` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_pedido_material`
--

INSERT INTO `detalles_pedido_material` (`id`, `pedido_id`, `material_id`, `cantidad`) VALUES
(23, 11, 2, 5),
(24, 11, 3, 10),
(25, 11, 1, 2),
(26, 11, 5, 7),
(27, 12, 4, 8),
(28, 13, 5, 9),
(29, 14, 2, 2),
(30, 14, 3, 3),
(32, 16, 6, 7),
(33, 17, 6, 7),
(34, 18, 2, 1),
(35, 18, 1, 3),
(36, 18, 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_produccion`
--

CREATE TABLE `detalles_produccion` (
  `id` int(11) NOT NULL,
  `produccion_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_venta`
--

CREATE TABLE `detalles_venta` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `tipo` enum('producto','servicio') NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_venta`
--

INSERT INTO `detalles_venta` (`id`, `venta_id`, `tipo`, `producto_id`, `servicio_id`, `cantidad`, `precio_unitario`, `descuento`, `subtotal`) VALUES
(16, 15, '', NULL, NULL, 2, 58000.00, 0.00, 58000.00),
(17, 16, 'producto', 1, NULL, 2, 120000.00, 0.00, 240000.00),
(18, 17, 'producto', 1, NULL, 1, 120000.00, 0.00, 120000.00),
(19, 17, 'servicio', NULL, 3, 1, 5000.00, 0.00, 5000.00),
(20, 18, '', NULL, NULL, 1, 71000.00, 0.00, 71000.00),
(21, 19, 'producto', 9, NULL, 1, 170000.00, 0.00, 170000.00),
(22, 20, 'producto', 10, NULL, 3, 250000.00, 0.00, 750000.00),
(23, 20, 'servicio', NULL, 3, 5, 5000.00, 0.00, 25000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `genero` varchar(1) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `horario_trabajo` varchar(100) NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `salario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellido`, `fecha_nacimiento`, `codigo`, `genero`, `telefono`, `direccion`, `email`, `horario_trabajo`, `fecha_ingreso`, `creado_en`, `salario`) VALUES
(2, 'Jesus Crispín', 'TOPOLÁ BOÑAHO', '1997-06-30', '000175362', 'M', '551718822', 'Ela Nguema (Bisinga)', 'sirtopola@gmail.com', 'lunes - viernes', '2025-05-26', '2025-06-01 15:19:11', NULL),
(3, 'Bienvenido', 'Sipepe', '2000-06-14', '000144578', 'M', '555477895', 'bata', '', 'lunes - Sabado', '2017-06-07', '2025-06-06 06:50:09', NULL),
(4, 'Merin', 'Compe  Buale', '2008-05-06', '00080910', 'M', '555154578', 'begoña 1', 'martin@gmail.com', 'lunes - viernes', '2020-06-10', '2025-06-06 06:53:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `entidad` enum('produccion','proyecto','pedido','venta','factura','tareas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `nombre`, `entidad`) VALUES
(1, 'pendiente', 'factura'),
(2, 'pagada', 'factura'),
(3, 'anulada', 'factura'),
(4, 'cotizado', 'pedido'),
(5, 'aprobado', 'pedido'),
(6, 'en_produccion', 'pedido'),
(7, 'en_proceso', 'produccion'),
(8, 'pendiente', 'produccion'),
(9, 'finalizado', 'produccion'),
(10, 'finalizado', 'pedido'),
(11, 'entregado', 'pedido'),
(12, 'pendiente', 'tareas'),
(13, 'en_progreso', 'tareas'),
(14, 'completado', 'tareas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `saldo_pendiente` decimal(10,2) DEFAULT 0.00,
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `venta_id`, `fecha_emision`, `monto_total`, `saldo_pendiente`, `estado_id`) VALUES
(11, 15, '2025-06-17', 58000.00, 0.00, 2),
(12, 16, '2025-06-17', 240000.00, 0.00, 2),
(13, 17, '2025-06-17', 125000.00, 0.00, 2),
(14, 18, '2025-07-02', 71000.00, 0.00, 2),
(15, 19, '2025-07-02', 170000.00, 0.00, 2),
(16, 20, '2025-07-02', 775000.00, 0.00, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `unidad_medida` varchar(50) DEFAULT NULL,
  `stock_actual` int(11) DEFAULT 0,
  `stock_minimo` int(11) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materiales`
--

INSERT INTO `materiales` (`id`, `nombre`, `descripcion`, `unidad_medida`, `stock_actual`, `stock_minimo`, `creado_en`) VALUES
(1, 'Madera Pino', 'Madera blanda y económica para estructuras y muebles básicos.', 'metro cúbico', 137, 5, '2025-06-01 16:30:42'),
(2, 'Madera Cedro', 'Madera resistente y aromática, ideal para muebles finos.', 'metro cúbico', 115, 5, '2025-06-01 16:31:16'),
(3, 'Madera MDF', 'Tablero de fibra de densidad media, ideal para interiores.', 'hoja', 100, 5, '2025-06-01 16:31:55'),
(4, 'Cola de carpintero', 'Adhesivo blanco para unión de madera.', 'litro', 12, 2, '2025-06-01 16:32:31'),
(5, 'Tornillos para madera 100mm', 'Tornillos galvanizados para fijaciones pequeñas.', 'kg', 16, 5, '2025-06-01 16:34:06'),
(6, 'Barniz transparente', 'Barniz protector con acabado brillante o mate.', 'litro', 2, 2, '2025-06-01 16:35:32'),
(7, 'aceite cola', 'liquido transparente útil para la madera', 'litro', 0, 2, '2025-06-17 10:30:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_material`
--

CREATE TABLE `movimientos_material` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `tipo_movimiento` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `motivo` text DEFAULT NULL,
  `produccion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_material`
--

INSERT INTO `movimientos_material` (`id`, `material_id`, `tipo_movimiento`, `cantidad`, `fecha`, `motivo`, `produccion_id`) VALUES
(14, 6, 'salida', 7, '2025-06-06 13:58:50', 'barnizado', 6),
(15, 6, 'entrada', 5, '2025-06-06 14:07:26', 'retorno', 6),
(16, 6, 'entrada', 3, '2025-06-06 14:08:02', 'red', 6),
(17, 6, 'salida', 4, '2025-06-06 14:11:39', 'cag', 7),
(18, 6, 'salida', 1, '2025-06-06 14:15:27', 'mov', 7),
(19, 4, 'salida', 5, '2025-06-06 14:16:15', 'vmov', 8),
(20, 2, 'salida', 1, '2025-06-17 12:16:12', 'salida', 9),
(21, 1, 'salida', 3, '2025-06-17 12:16:32', 'sa', 9),
(22, 6, 'salida', 1, '2025-06-17 12:16:43', 'sa', 9),
(23, 4, 'salida', 3, '2025-07-02 14:56:21', 'sa', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `factura_id`, `monto_pagado`, `fecha_pago`, `metodo_pago`, `observaciones`) VALUES
(21, 11, 58000.00, '2025-06-17', 'efectivo', ''),
(22, 12, 240000.00, '2025-06-17', 'efectivo', ''),
(23, 13, 125000.00, '2025-06-18', 'efectivo', ''),
(24, 14, 71000.00, '2025-07-02', 'efectivo', ''),
(25, 15, 170000.00, '2025-07-02', 'efectivo', ''),
(26, 16, 775000.00, '2025-07-02', 'efectivo', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `proyecto` varchar(100) NOT NULL,
  `piezas` int(11) DEFAULT 1,
  `servicio_id` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_entrega` int(11) NOT NULL,
  `precio_obra` decimal(10,2) DEFAULT 0.00,
  `estimacion_total` decimal(10,2) DEFAULT NULL,
  `adelanto` decimal(10,2) DEFAULT 0.00,
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_id`, `proyecto`, `piezas`, `servicio_id`, `descripcion`, `fecha_solicitud`, `fecha_entrega`, `precio_obra`, `estimacion_total`, `adelanto`, `estado_id`) VALUES
(11, 4, 'aparador moderno', 1, NULL, 'un elgante articulo', '2025-06-06', 20, 80000.00, 140000.00, 0.00, 11),
(12, 4, 'aparador moderno', 1, NULL, 'un elgante articulo', '2025-06-06', 2, 15000.00, 71000.00, 50000.00, 11),
(13, 2, 'aparador moderno', 1, NULL, 'un elgante articulo', '2025-06-06', 0, 15000.00, 28500.00, 10000.00, 11),
(14, 3, 'aparador moderno', 1, 2, 'un elgante articulo', '2025-06-06', 15, 45000.00, 61500.00, 50000.00, 11),
(16, 1, 'aparador moderno', 0, NULL, 'un elgante articulo', '2025-06-06', 15, 30000.00, 58000.00, 0.00, 11),
(17, 1, 'aparador moderno', 2, NULL, 'un elgante articulo', '2025-06-06', 15, 30000.00, 58000.00, 29000.00, 11),
(18, 3, 'porta vinos de paret', 1, NULL, 'buen lujo', '2025-06-17', 10, 17000.00, 35000.00, 20000.00, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producciones`
--

CREATE TABLE `producciones` (
  `id` int(11) NOT NULL,
  `solicitud_id` int(11) DEFAULT NULL,
  `responsable_id` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producciones`
--

INSERT INTO `producciones` (`id`, `solicitud_id`, `responsable_id`, `fecha_inicio`, `fecha_fin`, `estado_id`, `created_at`) VALUES
(6, 17, 3, '2025-06-07', '2025-06-22', 7, '2025-06-06 12:20:49'),
(7, 17, 4, '2025-06-08', '2025-06-17', 9, '2025-06-06 14:11:21'),
(8, 12, 3, '2025-06-11', '2025-07-02', 9, '2025-06-06 14:15:59'),
(9, 18, 4, '2025-06-14', '2025-06-24', 7, '2025-06-17 12:15:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` text DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `imagen`, `precio_unitario`, `stock`) VALUES
(1, 'aparador moderno', 'un elgante articulo', 'uploads/productos/img_683d2a31893d7.jpg', 120000.00, 2),
(9, 'Mesa de Comedor Rústica', 'Mesa grande de madera maciza ideal para reuniones familiares. Hecha a mano con acabado envejecido', 'uploads/productos/img_68528805cf0c5.webp', 170000.00, 1),
(10, 'Puerta Artesanal', 'Un modelo un más perfeccionado adaptable a marcos frances', 'uploads/productos/img_6852a5903d369.jpeg', 250000.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `contacto`, `telefono`, `email`, `direccion`) VALUES
(1, 'Lucichat SL', 'Sulamita', '22501254', NULL, 'Bisinga'),
(2, 'Carlos SL', 'Margarine', '222144578', NULL, 'Semu');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(6, 'Administrador'),
(7, 'Vendedor'),
(8, 'Diseñador'),
(9, 'empleado'),
(10, 'Operario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_base` decimal(10,2) DEFAULT NULL,
  `unidad` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio_base`, `unidad`, `activo`, `creado_en`) VALUES
(1, 'Corte de madera', 'Corte de madera con maquinaria especializada según medidas requeridas.', 1500.00, 'por corte', 1, '2025-06-01 16:17:59'),
(2, 'Cepillado de madera', 'Alisado y nivelado de tablas o piezas de madera', 2000.00, 'por metro', 1, '2025-06-01 16:18:39'),
(3, 'Barnizado', 'Aplicación de barniz protector y decorativo sobre superficies de madera.', 5000.00, 'por metro cuadrado', 1, '2025-06-01 16:19:25'),
(4, 'Revestimiento en madera', 'Colocación de paneles o láminas decorativas de madera.', 15000.00, 'por metro cuadrado', 1, '2025-06-01 16:20:58'),
(5, 'Diseño y fabricación de mueble a medida', 'Fabricación personalizada según medidas del cliente', 125000.00, 'por pieza', 1, '2025-06-18 11:49:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas_produccion`
--

CREATE TABLE `tareas_produccion` (
  `id` int(11) NOT NULL,
  `produccion_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `responsable_id` int(11) DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas_produccion`
--

INSERT INTO `tareas_produccion` (`id`, `produccion_id`, `descripcion`, `responsable_id`, `estado_id`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 6, 'realiza dos manos de barnizado para el corte 4x8', 3, 14, '2025-06-06', '2025-06-06'),
(2, 8, 'haga un corte en la madera de 4x7', 4, 14, '2025-06-06', '2025-06-06'),
(3, 8, 'marca 05cm', 3, 14, '2025-06-17', '2025-06-17'),
(4, 6, 'fadsa', 3, 14, '2025-06-17', '2025-06-18'),
(5, 9, 'cartame madera de 2m', 4, 14, '2025-06-17', '2025-06-18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `imagen` text DEFAULT NULL,
  `rol_id` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `empleado_id`, `username`, `password`, `imagen`, `rol_id`, `activo`) VALUES
(1, 2, 'admin', '$2y$10$3vdW2OU0E56D4mjPYrrsQu3oc4qmDYv4VoCh9EM6PIGoBrD4YpkjG', 'uploads/usuarios/usuario_1748791171.jpg', 6, 1),
(2, 3, 'operario1', '$2y$10$i7wTTaXCtVEYHQFcBq55A.dKiqV5S5BPatDUuU7AQ.JtS/kT4vdGS', 'uploads/usuarios/usuario_1750166065.jpg', 10, 1),
(3, 4, 'operario2', '$2y$10$F2Vi53tpwwMJMSCmx.4uVujz7wyhrrbggMJyGFNS.O8yQrxMd46HS', 'uploads/usuarios/usuario_1750166091.png', 10, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `nombre_cliente` varchar(100) DEFAULT NULL,
  `dni_cliente` varchar(20) DEFAULT NULL,
  `direccion_cliente` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `nombre_cliente`, `dni_cliente`, `direccion_cliente`, `fecha`, `total`, `metodo_pago`) VALUES
(15, 1, NULL, NULL, NULL, '2025-06-17 10:22:46', 58000.00, 'efectivo'),
(16, 2, '', '', '', '2025-06-17 13:38:44', 240000.00, 'efectivo'),
(17, 4, '', '', '', '2025-06-17 14:47:35', 125000.00, 'efectivo'),
(18, 4, NULL, NULL, NULL, '2025-07-02 14:58:37', 71000.00, 'efectivo'),
(19, 3, '', '', '', '2025-07-02 15:00:05', 170000.00, 'efectivo'),
(20, 2, '', '', '', '2025-07-02 15:01:24', 775000.00, 'efectivo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `avances_produccion`
--
ALTER TABLE `avances_produccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produccion_id` (`produccion_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_id` (`compra_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indices de la tabla `detalles_pedido_material`
--
ALTER TABLE `detalles_pedido_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indices de la tabla `detalles_produccion`
--
ALTER TABLE `detalles_produccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produccion_id` (`produccion_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_material`
--
ALTER TABLE `movimientos_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`),
  ADD KEY `produccion_id` (`produccion_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_id` (`factura_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `producciones`
--
ALTER TABLE `producciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_id` (`solicitud_id`),
  ADD KEY `responsable_id` (`responsable_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tareas_produccion`
--
ALTER TABLE `tareas_produccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produccion_id` (`produccion_id`),
  ADD KEY `responsable_id` (`responsable_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `empleado_id` (`empleado_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `avances_produccion`
--
ALTER TABLE `avances_produccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido_material`
--
ALTER TABLE `detalles_pedido_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `detalles_produccion`
--
ALTER TABLE `detalles_produccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `movimientos_material`
--
ALTER TABLE `movimientos_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `producciones`
--
ALTER TABLE `producciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tareas_produccion`
--
ALTER TABLE `tareas_produccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `avances_produccion`
--
ALTER TABLE `avances_produccion`
  ADD CONSTRAINT `avances_produccion_ibfk_1` FOREIGN KEY (`produccion_id`) REFERENCES `producciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD CONSTRAINT `detalles_compra_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_compra_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_pedido_material`
--
ALTER TABLE `detalles_pedido_material`
  ADD CONSTRAINT `detalles_pedido_material_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_pedido_material_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_produccion`
--
ALTER TABLE `detalles_produccion`
  ADD CONSTRAINT `detalles_produccion_ibfk_1` FOREIGN KEY (`produccion_id`) REFERENCES `producciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_produccion_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_venta_ibfk_3` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_material`
--
ALTER TABLE `movimientos_material`
  ADD CONSTRAINT `movimientos_material_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_material_ibfk_2` FOREIGN KEY (`produccion_id`) REFERENCES `producciones` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `producciones`
--
ALTER TABLE `producciones`
  ADD CONSTRAINT `producciones_ibfk_1` FOREIGN KEY (`solicitud_id`) REFERENCES `pedidos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `producciones_ibfk_2` FOREIGN KEY (`responsable_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `producciones_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tareas_produccion`
--
ALTER TABLE `tareas_produccion`
  ADD CONSTRAINT `tareas_produccion_ibfk_1` FOREIGN KEY (`produccion_id`) REFERENCES `producciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tareas_produccion_ibfk_2` FOREIGN KEY (`responsable_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tareas_produccion_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
