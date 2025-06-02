-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 12:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carpinteria_tfg`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias_producto`
--

CREATE TABLE `categorias_producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) DEFAULT NULL,
  `codigo_acceso` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `codigo`, `telefono`, `direccion`, `creado_en`, `email`, `codigo_acceso`) VALUES
(13, 'francisco Bokochi', '000121323', '551718822', 'Sunko', '2025-05-24 10:10:46', 'fracis@gmail.com', 'FRBO25013'),
(14, 'Lucas Maroto', '0001213023', '222544778', 'lampert', '2025-05-26 23:49:07', 'lucas@gmail.com', 'LUMA25014'),
(15, 'mercedes', '000175362', '222144589', 'lampert', '2025-05-27 20:48:51', 'lisa@gmail.com', 'MEME25015'),
(20, 'Pedro Yamba', '00012141518', '551484943', 'los angeles', '2025-05-28 20:38:19', '', 'PEYA25020'),
(21, 'Jose Luis', '000121415', '222010223', 'baney', '2025-05-29 03:58:12', '', 'JOLU25021'),
(22, 'jesus', '00145478', '55120456', 'begoña 2', '2025-05-31 02:07:59', 'mar@gmail.com', 'JEJE25022'),
(23, 'manuel ela', '000124578', '222001122', 'begoña 2', '2025-05-31 02:12:50', 'jeny@gmail.com', 'MAEL25023'),
(24, 'lucas moreno', '0001454788', '222001122', 'lamper', '2025-05-31 02:17:10', 'lucas@gmail.com', 'LUMO25024'),
(25, 'Candida', '000124587', '222014578', 'lampert', '2025-05-31 07:14:13', '', 'CACA25025'),
(26, 'Ernesto Sales', '0001245860', '555147895', 'sunko', '2025-05-31 07:23:52', '', 'ERSA25026'),
(27, 'sinforosa mguema', '00124578', '22214547885', 'los angeles', '2025-05-31 08:09:57', '', 'SIMG25027'),
(28, 'sanches metete', '000014725', '222145478850', 'luba', '2025-05-31 08:23:41', '', 'SAME25028');

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `codigo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `compras`
--

INSERT INTO `compras` (`id`, `proveedor_id`, `fecha`, `total`, `codigo`) VALUES
(27, 5, '2025-05-25', 840000.00, 'PO-20250525-0002'),
(28, 5, '2025-05-25', 840000.00, 'PO-20250525-0003'),
(29, 6, '2025-05-25', 0.00, '#SIXBOKU-20250525-0007'),
(30, 5, '2025-05-25', 311000.00, '#SIXBOKU-20250525-0005'),
(31, 5, '2025-05-24', 150000.00, '#SIXBOKU-20250525-0006'),
(32, 8, '2025-05-20', 19500.00, '#78978'),
(33, 7, '2025-05-24', 7500.00, '#SIXBOKU-20250525-0005'),
(34, 5, '2025-05-24', 49500.00, '#78987'),
(35, 5, '2025-05-24', 30000.00, '#SIXBOKU-20250525-0005');

-- --------------------------------------------------------

--
-- Table structure for table `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre_empresa` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `iva` decimal(5,2) DEFAULT NULL,
  `moneda` varchar(10) DEFAULT NULL,
  `imagen` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `mision` text DEFAULT NULL,
  `historia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre_empresa`, `direccion`, `telefono`, `correo`, `logo`, `iva`, `moneda`, `imagen`, `vision`, `mision`, `historia`) VALUES
(1, 'CARPINTERIA SIXBOKU SL', 'PERES MERCAMAR', '551718822', 'sixboku@carpinteria.net', 'uploads/configuracion/logo_1748076937.jpg', 15.00, 'XAF', 'uploads/configuracion/imagen_1748076937.jpg', 'Ser una referencia en el sector de la carpintería \r\nen Guinea Ecuatorial, destacando por la calidad, \r\nla creatividad y el trato cercano.', 'Crear muebles y soluciones de carpintería de alta calidad, \r\ncombinando tradición artesanal con innovación, para mejorar los \r\nespacios de nuestros clientes.', 'Nacimos como un pequeño taller en Ela-Nguema, fruto del esfuerzo \r\ny la pasión de nuestro fundador. Con el tiempo y gracias a la confianza \r\nde nuestros clientes, nos consolidamos en el Barrio Pérez, donde hoy \r\ntrabajamos con maquinaria moderna y un equipo profesional comprometido \r\ncon la excelencia.');

-- --------------------------------------------------------

--
-- Table structure for table `detalles_compra`
--

CREATE TABLE `detalles_compra` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detalles_compra`
--

INSERT INTO `detalles_compra` (`id`, `compra_id`, `material_id`, `cantidad`, `precio_unitario`, `stock`) VALUES
(23, 27, 16, 120, 7000.00, 120),
(24, 28, 16, 120, 7000.00, 120),
(40, 29, 22, 100, 750.00, 0),
(41, 29, 21, 25, 5000.00, 0),
(42, 29, 19, 30, 20000.00, 0),
(55, 30, 19, 50, 1000.00, 0),
(56, 30, 18, 15, 15000.00, 0),
(57, 30, 16, 20, 1800.00, 0),
(60, 32, 22, 5, 1500.00, 5),
(61, 32, 20, 10, 1200.00, 10),
(62, 33, 22, 5, 1500.00, 5),
(63, 34, 20, 10, 1200.00, 10),
(64, 34, 22, 25, 1500.00, 25),
(65, 35, 23, 20, 1500.00, 20),
(66, 31, 16, 70, 2000.00, 0),
(67, 31, 17, 5, 2000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `detalles_solicitud_material`
--

CREATE TABLE `detalles_solicitud_material` (
  `id` int(11) NOT NULL,
  `solicitud_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalles_solicitud_material`
--

INSERT INTO `detalles_solicitud_material` (`id`, `solicitud_id`, `material_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 19, 5, 20000.00),
(5, 3, 16, 6, 7000.00),
(6, 3, 22, 2, 1500.00),
(7, 3, 19, 3, 20000.00),
(8, 3, 23, 1, 1500.00),
(9, 4, 18, 5, 15000.00),
(10, 4, 23, 2, 1500.00),
(11, 4, 19, 2, 20000.00),
(12, 4, 16, 10, 7000.00),
(25, 8, 19, 12, 20000.00),
(26, 8, 22, 2, 1500.00),
(27, 8, 19, 2, 20000.00),
(28, 9, 20, 2, 1200.00),
(29, 9, 19, 3, 20000.00),
(30, 9, 16, 5, 7000.00),
(31, 9, 21, 2, 5000.00),
(32, 10, 22, 3, 1500.00),
(33, 10, 18, 5, 15000.00),
(34, 10, 19, 7, 20000.00),
(35, 11, 16, 5, 7000.00),
(36, 11, 20, 5, 1200.00),
(37, 11, 23, 2, 1500.00),
(38, 11, 21, 2, 5000.00),
(39, 12, 19, 2, 20000.00),
(40, 12, 16, 3, 7000.00),
(41, 12, 22, 1, 1500.00),
(42, 12, 23, 0, 1500.00),
(43, 12, 18, 0, 15000.00),
(44, 12, 21, 1, 5000.00),
(45, 13, 16, 7, 7000.00),
(46, 13, 22, 2, 1500.00),
(47, 13, 18, 1, 15000.00),
(48, 14, 18, 2, 15000.00),
(49, 14, 20, 2, 1200.00),
(50, 14, 19, 2, 20000.00),
(51, 15, 22, 2, 1500.00),
(52, 15, 20, 2, 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `detalles_venta`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detalles_venta`
--

INSERT INTO `detalles_venta` (`id`, `venta_id`, `tipo`, `producto_id`, `servicio_id`, `cantidad`, `precio_unitario`, `descuento`, `subtotal`) VALUES
(26, 16, 'servicio', NULL, 19, 1, 1500.00, 0.00, 1500.00),
(27, 16, 'servicio', NULL, 12, 1, 3000.00, 0.00, 3000.00),
(28, 16, 'servicio', NULL, 11, 1, 5000.00, 0.00, 5000.00),
(37, 21, 'producto', 7, NULL, 1, 2000.00, 0.00, 2000.00),
(38, 21, 'servicio', NULL, 12, 1, 3000.00, 0.00, 3000.00),
(39, 22, 'producto', 7, NULL, 2, 120000.00, 0.00, 240000.00),
(40, 22, 'servicio', NULL, 16, 3, 200.00, 0.00, 600.00),
(41, 23, 'producto', 7, NULL, 3, 120000.00, 0.00, 360000.00),
(42, 23, 'servicio', NULL, 10, 3, 25000.00, 0.00, 75000.00),
(43, 24, 'producto', 15, NULL, 1, 180000.00, 0.00, 180000.00),
(44, 25, 'producto', 7, NULL, 2, 120000.00, 0.00, 240000.00),
(45, 25, 'servicio', NULL, 10, 2, 25000.00, 0.00, 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellido`, `fecha_nacimiento`, `codigo`, `genero`, `telefono`, `direccion`, `email`, `horario_trabajo`, `fecha_ingreso`, `creado_en`, `salario`) VALUES
(22, 'Jesus Crispín', 'TOPOLÁ BOÑAHO', '1997-06-30', '000175362', 'M', '551718822', 'Ela Nguema (Bisinga)', 'sirtopola@gmail.com', 'lunes - viernes', '2023-02-01', '2025-05-24 08:56:45', NULL),
(23, 'Lisa', 'Marquez', '2000-06-10', '000121323', 'F', '551718822', 'lampert', 'lisa@gmail.com', 'lunes - viernes', '2023-12-02', '2025-05-24 09:11:11', NULL),
(24, 'Carlos P', 'Bolete', '1997-02-20', '000145789', 'M', '222454886', 'Cruce Dragas', NULL, 'lunes -viernes 8:00 a.m - 15:00 p.m', '2022-05-10', '2025-05-31 02:00:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL,
  `estado` enum('pendiente','pagada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imagenes_producto`
--

CREATE TABLE `imagenes_producto` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `ruta_imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `imagenes_producto`
--

INSERT INTO `imagenes_producto` (`id`, `producto_id`, `ruta_imagen`) VALUES
(15, 7, 'uploads/productos/img_68362db26f371.jpeg'),
(16, 15, 'uploads/productos/img_683a63963e103.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `materiales`
--

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `unidad_medida` varchar(50) DEFAULT NULL,
  `stock_actual` int(11) DEFAULT 0,
  `stock_minimo` int(11) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materiales`
--

INSERT INTO `materiales` (`id`, `nombre`, `descripcion`, `unidad_medida`, `stock_actual`, `stock_minimo`, `creado_en`) VALUES
(16, 'Madera Pino', 'Tablas de pino cepillado de 2x4 pulgadas', 'unidad', 300, 10, '2025-05-24 09:30:34'),
(17, 'Clavos 80', 'Clavos de 2 pulgadas para estructuras', 'kg', 5, 5, '2025-05-24 09:31:27'),
(18, 'Tornillos para madera 1.5 pulgadas', 'Tornillos de 1.5 pulgadas con cabeza estrella', 'caja', 15, 5, '2025-05-24 09:32:09'),
(19, 'Pegamento de carpintero', 'Adhesivo vinílico blanco de uso general', 'litros', 80, 2, '2025-05-24 09:33:02'),
(20, 'Lija grano 120', 'Lija para madera grano fino 120', 'hoja', 15, 5, '2025-05-24 09:40:01'),
(21, 'tapa porros', 'un material', 'mml', 23, 12, '2025-05-25 13:37:12'),
(22, 'clavos de 100', 'clavos útiles para grapado', 'kg', 135, 5, '2025-05-25 13:53:50'),
(23, 'clavo 50', 'necesarios', 'kg', 19, 5, '2025-05-25 20:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `movimientos_material`
--

CREATE TABLE `movimientos_material` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `tipo_movimiento` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `motivo` text DEFAULT NULL,
  `produccion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movimientos_material`
--

INSERT INTO `movimientos_material` (`id`, `material_id`, `tipo_movimiento`, `cantidad`, `fecha`, `motivo`, `produccion_id`) VALUES
(5, 20, 'salida', 3, '2025-05-31 04:39:45', 'fin', 6),
(6, 20, 'salida', 2, '2025-05-31 04:40:47', 'usados para el proceso', 6),
(7, 21, 'salida', 2, '2025-05-31 04:40:47', 'usados para el proceso', 6),
(8, 23, 'salida', 1, '2025-05-31 04:40:47', 'usados para el proceso', 6),
(9, 16, 'salida', 3, '2025-05-31 04:40:47', 'usados para el proceso', 6),
(10, 16, 'salida', 2, '2025-05-31 04:48:23', 'concluir', 6);

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) DEFAULT NULL,
  `monto_pagado` decimal(10,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `producciones`
--

CREATE TABLE `producciones` (
  `id` int(11) NOT NULL,
  `proyecto_id` int(11) DEFAULT NULL,
  `responsable_id` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('pendiente','en proceso','terminado') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `producciones`
--

INSERT INTO `producciones` (`id`, `proyecto_id`, `responsable_id`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`) VALUES
(5, 4, 22, '2025-05-26', '2025-05-31', 'en proceso', '2025-05-26 23:10:27'),
(6, 5, 24, '2025-05-31', '2025-06-08', 'en proceso', '2025-05-31 02:24:39'),
(7, 5, NULL, NULL, NULL, 'pendiente', '2025-05-31 06:41:52'),
(8, 6, NULL, '2025-05-31', NULL, 'pendiente', '2025-05-31 07:20:06'),
(9, 7, NULL, '2025-05-31', NULL, 'pendiente', '2025-05-31 07:28:35'),
(10, 3, NULL, '2025-05-31', NULL, 'pendiente', '2025-05-31 08:11:43');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `solicitud_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio_unitario`, `stock`, `solicitud_id`) VALUES
(7, 'puertas modernas', 'En buen trabajo para un buen cliente', 120000.00, 4, NULL),
(15, 'aparador moderno', 'es un aparador de ensueños', 180000.00, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `contacto`, `telefono`, `email`, `direccion`) VALUES
(5, 'Fereteria Marat', 'Marat', '222213532', 'marat@mail.com', 'Ela Nguema'),
(6, 'Puesto Marcos Mba', 'marcos', '551905371', 'mba@gmail.com', 'Semu'),
(7, 'partiert SL', 'Patry', '222211445', NULL, 'fiston'),
(8, 'lostana SL', 'laura', '222141516', 'lostana@gmail.com', 'mercado central'),
(9, 'pinteria', 'manuel', '551718822', 'manuel@prueba.com', 'sampaka'),
(10, 'mercamar sl', 'marta', '222010335', NULL, 'semu');

-- --------------------------------------------------------

--
-- Table structure for table `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('pendiente','en_diseno','en_produccion','finalizado') DEFAULT 'pendiente',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `descripcion`, `estado`, `fecha_inicio`, `fecha_entrega`, `creado_en`) VALUES
(3, 'lijado de mueble', 'mejsuiufhdoifjuuvfg', '', '2025-05-26', '2025-05-31', '2025-05-26 22:03:21'),
(4, 'marcos francés', 'marcos tipicos de tamanio grande', 'pendiente', '2025-05-26', '2025-06-03', '2025-05-26 22:05:52'),
(5, 'aparador de pared', NULL, '', '2025-05-31', NULL, '2025-05-31 02:20:09'),
(6, 'Taburete de salon', NULL, '', '2025-05-31', NULL, '2025-05-31 07:18:19'),
(7, 'Armario de cocina', 'En artico de cocina para alojar cubiertos', '', '2025-05-31', NULL, '2025-05-31 07:28:04'),
(8, 'livin moderno', 'un mueble elegante', 'pendiente', '2025-05-31', '2025-06-08', '2025-05-31 08:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(25, 'Administrador'),
(26, 'Vendedor'),
(27, 'Diseñador'),
(28, 'Operario');

-- --------------------------------------------------------

--
-- Table structure for table `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_base` decimal(10,2) DEFAULT NULL,
  `unidad` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio_base`, `unidad`, `activo`, `creado_en`) VALUES
(10, 'Instalación de puertas', 'Colocación y ajuste de puertas interiores o exteriores de madera.', 25000.00, 'servicio', 1, '2025-05-24 22:28:58'),
(11, 'Armado de muebles', 'Ensamblaje de piezas de madera para crear muebles como estantes, mesas, etc.', 5000.00, 'unidad', 1, '2025-05-24 22:29:43'),
(12, 'Cepillado de madera', 'Cepillado para nivelar o ajustar tablas a medida', 3000.00, 'unidad', 0, '2025-05-24 22:41:05'),
(16, 'montaje de vitrina', 'mola mazo', 200.00, 'm2', 1, '2025-05-25 01:41:11'),
(19, 'past', 'fgfhsdf', 1500.00, 'corte', 0, '2025-05-25 11:21:48'),
(20, 'gdadf', 'rthgafgh', 2000.00, 'unidad', 0, '2025-05-25 11:25:12'),
(22, 'mascaraa', 'dsfljasdf8hoijdyfigfi', 2000.00, 'corte', 1, '2025-05-25 11:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `solicitudes_proyecto`
--

CREATE TABLE `solicitudes_proyecto` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `proyecto_id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_solicitud` date NOT NULL,
  `estado` enum('pendiente','cotizado','aprobado','en_produccion','finalizado') DEFAULT 'pendiente',
  `precio_obra` decimal(10,2) DEFAULT 0.00,
  `estimacion_total` decimal(10,2) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `solicitudes_proyecto`
--

INSERT INTO `solicitudes_proyecto` (`id`, `cliente_id`, `proyecto_id`, `descripcion`, `fecha_solicitud`, `estado`, `precio_obra`, `estimacion_total`, `servicio_id`) VALUES
(1, 20, 4, 'Cotización generada automáticamente', '2025-05-29', 'cotizado', 0.00, 105000.00, NULL),
(3, 13, 4, 'Cotización generada automáticamente', '2025-05-29', 'cotizado', 0.00, 131500.00, 10),
(4, 14, 3, 'Cotización generada automáticamente', '2025-05-29', 'cotizado', 0.00, 248000.00, 10),
(8, 21, 4, 'un buen trabajo dignifica al hombre', '2025-05-29', 'cotizado', 0.00, 333000.00, 10),
(9, 21, 3, '', '2025-05-29', 'cotizado', 30000.00, 162400.00, 10),
(10, 20, 4, '', '2025-05-29', 'cotizado', 35000.00, 279500.00, 10),
(11, 24, 5, 'parales ', '2025-05-31', 'aprobado', 15000.00, 72000.00, 12),
(12, 25, 6, 'es un mueble elegante versatil de color blanco con cristal integrado', '2025-05-31', 'aprobado', 20000.00, 90875.00, NULL),
(13, 26, 7, 'En artico de cocina para alojar cubiertos', '2025-05-31', 'aprobado', 20000.00, 87000.00, NULL),
(14, 27, 3, 'es un estilo moderno', '2025-05-31', 'aprobado', 15000.00, 87400.00, NULL),
(15, 28, 8, 'es un moderno livin de dimensiones considerables', '2025-05-31', 'cotizado', 15000.00, 20400.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `empleado_id` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `perfil` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol_id`, `empleado_id`, `activo`, `perfil`) VALUES
(23, 'admin@prueba.com', '$2y$10$J9p0pfRzLin.Peq1Pg3QcuROTvYkHcVodR60L4TTqDXg28ex1iMQi', 25, 22, 1, 'uploads/usuarios/usuario_1748077038.jpg'),
(24, 'vendedor@prueba.com', '$2y$10$CZV9YtOpR3VNJzLawU1zYusFPQ5Qs2a44IDSmqCuDAXs/yv/iwsf.', 26, 23, 1, 'uploads/usuarios/usuario_1748077972.jpg'),
(25, 'venderdor@prueba.com', '$2y$10$DHhW00OTYBC6I8c5NY76U.sFeESqLXgDGT2a1a0vOHoCJ4AtDCoQ6', 26, 24, 1, 'uploads/usuarios/usuario_1748656952.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `fecha`, `total`, `metodo_pago`) VALUES
(16, 13, '2025-05-26 21:05:32', 9500.00, 'efectivo'),
(21, 15, '2025-05-27 21:07:10', 5000.00, 'efectivo'),
(22, 14, '2025-05-27 21:35:17', 240600.00, 'efectivo'),
(23, 20, '2025-05-28 23:00:07', 435000.00, 'efectivo'),
(24, 22, '2025-05-31 02:08:39', 180000.00, 'efectivo'),
(25, 23, '2025-05-31 02:13:22', 290000.00, 'efectivo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias_producto`
--
ALTER TABLE `categorias_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indexes for table `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_id` (`compra_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `detalles_solicitud_material`
--
ALTER TABLE `detalles_solicitud_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_id` (`solicitud_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`);

--
-- Indexes for table `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto_imagen` (`producto_id`);

--
-- Indexes for table `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movimientos_material`
--
ALTER TABLE `movimientos_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`),
  ADD KEY `produccion_id` (`produccion_id`);

--
-- Indexes for table `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_id` (`factura_id`);

--
-- Indexes for table `producciones`
--
ALTER TABLE `producciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proyecto_id` (`proyecto_id`),
  ADD KEY `responsable_id` (`responsable_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_id` (`solicitud_id`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `solicitudes_proyecto`
--
ALTER TABLE `solicitudes_proyecto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `proyecto_id` (`proyecto_id`),
  ADD KEY `fk_servicio` (`servicio_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias_producto`
--
ALTER TABLE `categorias_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `detalles_compra`
--
ALTER TABLE `detalles_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `detalles_solicitud_material`
--
ALTER TABLE `detalles_solicitud_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `movimientos_material`
--
ALTER TABLE `movimientos_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `producciones`
--
ALTER TABLE `producciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `solicitudes_proyecto`
--
ALTER TABLE `solicitudes_proyecto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Constraints for table `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD CONSTRAINT `detalles_compra_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `detalles_compra_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`);

--
-- Constraints for table `detalles_solicitud_material`
--
ALTER TABLE `detalles_solicitud_material`
  ADD CONSTRAINT `detalles_solicitud_material_ibfk_1` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes_proyecto` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_solicitud_material_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `detalles_venta_ibfk_3` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Constraints for table `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);

--
-- Constraints for table `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  ADD CONSTRAINT `fk_producto_imagen` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `imagenes_producto_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Constraints for table `movimientos_material`
--
ALTER TABLE `movimientos_material`
  ADD CONSTRAINT `movimientos_material_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`),
  ADD CONSTRAINT `movimientos_material_ibfk_2` FOREIGN KEY (`produccion_id`) REFERENCES `producciones` (`id`);

--
-- Constraints for table `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`);

--
-- Constraints for table `producciones`
--
ALTER TABLE `producciones`
  ADD CONSTRAINT `producciones_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`),
  ADD CONSTRAINT `producciones_ibfk_2` FOREIGN KEY (`responsable_id`) REFERENCES `empleados` (`id`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes_proyecto` (`id`);

--
-- Constraints for table `solicitudes_proyecto`
--
ALTER TABLE `solicitudes_proyecto`
  ADD CONSTRAINT `fk_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitudes_proyecto_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `solicitudes_proyecto_ibfk_2` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`);

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
