-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 04:16 PM
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
(14, 'Lucas Maroto', '0001213023', '222544778', 'lampert', '2025-05-26 23:49:07', 'lucas@gmail.com', 'LUMA25014');

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
(1, 'CARPINTERIA SIXBOKU SL', 'PERES MERCAMAR', '551718822', 'sixboku@carpinteria.net', 'uploads/configuracion/logo_1748076937.jpg', 25.00, 'XAF', 'uploads/configuracion/imagen_1748076937.jpg', 'Ser una referencia en el sector de la carpintería \r\nen Guinea Ecuatorial, destacando por la calidad, \r\nla creatividad y el trato cercano.', 'Crear muebles y soluciones de carpintería de alta calidad, \r\ncombinando tradición artesanal con innovación, para mejorar los \r\nespacios de nuestros clientes.', 'Nacimos como un pequeño taller en Ela-Nguema, fruto del esfuerzo \r\ny la pasión de nuestro fundador. Con el tiempo y gracias a la confianza \r\nde nuestros clientes, nos consolidamos en el Barrio Pérez, donde hoy \r\ntrabajamos con maquinaria moderna y un equipo profesional comprometido \r\ncon la excelencia.');

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
(7, 4, 'servicio', NULL, 11, 1, 5000.00, 0.00, 5000.00),
(8, 5, 'servicio', NULL, 11, 2, 5000.00, 0.00, 10000.00),
(9, 6, 'producto', 11, NULL, 1, 2000.00, 0.00, 2000.00),
(12, 8, 'producto', 6, NULL, 3, 2000.00, 0.00, 6000.00),
(21, 13, 'producto', 9, NULL, 1, 622.00, 0.00, 622.00),
(22, 14, 'servicio', NULL, 16, 1, 200.00, 0.00, 200.00),
(23, 15, 'producto', 11, NULL, 1, 2000.00, 0.00, 2000.00),
(24, 15, 'producto', 7, NULL, 1, 2000.00, 0.00, 2000.00),
(25, 15, 'producto', 12, NULL, 1, 200.00, 0.00, 200.00),
(26, 16, 'servicio', NULL, 19, 1, 1500.00, 0.00, 1500.00),
(27, 16, 'servicio', NULL, 12, 1, 3000.00, 0.00, 3000.00),
(28, 16, 'servicio', NULL, 11, 1, 5000.00, 0.00, 5000.00);

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
(23, 'Lisa', 'Marquez', '2000-06-10', '000121323', 'F', '551718822', 'lampert', 'lisa@gmail.com', 'lunes - viernes', '2023-12-02', '2025-05-24 09:11:11', NULL);

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
(7, 7, 'uploads/productos/img_6832a1f364950.png'),
(8, 8, 'uploads/productos/img_6832a228d6ce4.png'),
(9, 9, 'uploads/productos/img_6832a24ed5770.png'),
(10, 10, 'uploads/productos/img_6832d212959ba.png'),
(11, 11, 'uploads/productos/img_6832d439d8546.png'),
(12, 12, 'uploads/productos/img_6832d47927f11.png'),
(13, 13, 'uploads/productos/img_6832ff62dc35d.png');

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
(16, 'Madera Pino', 'Tablas de pino cepillado de 2x4 pulgadas', 'unidad', 305, 10, '2025-05-24 09:30:34'),
(17, 'Clavos 80', 'Clavos de 2 pulgadas para estructuras', 'kg', 5, 5, '2025-05-24 09:31:27'),
(18, 'Tornillos para madera 1.5 pulgadas', 'Tornillos de 1.5 pulgadas con cabeza estrella', 'caja', 15, 5, '2025-05-24 09:32:09'),
(19, 'Pegamento de carpintero', 'Adhesivo vinílico blanco de uso general', 'litros', 80, 2, '2025-05-24 09:33:02'),
(20, 'Lija grano 120', 'Lija para madera grano fino 120', 'hoja', 20, 20, '2025-05-24 09:40:01'),
(21, 'tapa porros', 'un material', 'mml', 25, 12, '2025-05-25 13:37:12'),
(22, 'clavos de 100', 'clavos útiles para grapado', 'kg', 135, 5, '2025-05-25 13:53:50'),
(23, 'clavo 50', 'necesarios', 'kg', 20, 5, '2025-05-25 20:56:09');

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
(5, 4, 22, '2025-05-26', '2025-05-31', 'en proceso', '2025-05-26 23:10:27');

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
(5, 'Lucas', 'thyfdfdsds', 2000.00, 5, NULL),
(6, 'Lucas', 'thyfdfdsds', 2000.00, 5, NULL),
(7, 'Cepillado de madera', 'lhjhgfhggki', 2000.00, 6, NULL),
(8, 'Lucas', 'i87ydrtiy78', 2333.00, 56, NULL),
(9, 'closet', 'kiutyyugi', 622.00, 6, NULL),
(10, 'Armado de muebles', 'MSJDUHFIUFIUGICUEFEFEFW', 2500.00, 5, NULL),
(11, 'closet moderno', 'gthfdff', 2000.00, 3, NULL),
(12, 'closets modernos caribe', 'rgdsdgfhh', 200.00, 2, NULL),
(13, 'corte recto', 'kjhkdsfgaf', 2000.00, 20, NULL);

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
(9, 'pinteria', 'manuel', '551718822', 'manuel@prueba.com', 'sampaka');

-- --------------------------------------------------------

--
-- Table structure for table `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('pendiente','en diseño','en producción','finalizado') DEFAULT 'pendiente',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `descripcion`, `estado`, `fecha_inicio`, `fecha_entrega`, `creado_en`) VALUES
(3, 'lijado de mueble', 'mejsuiufhdoifjuuvfg', 'pendiente', '2025-05-26', '2025-05-31', '2025-05-26 22:03:21'),
(4, 'marcos francés', 'marcos tipicos de tamanio grande', 'pendiente', '2025-05-26', '2025-06-03', '2025-05-26 22:05:52');

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
(18, 'Marieta', 'dfyhjyku', -200.00, 'unidad', 1, '2025-05-25 01:41:49'),
(19, 'past', 'fgfhsdf', 1500.00, 'corte', 0, '2025-05-25 11:21:48'),
(20, 'gdadf', 'rthgafgh', 2000.00, 'unidad', 1, '2025-05-25 11:25:12'),
(21, 'refinado', 'msdfioiahdifpdg', 1500.00, 'm2', 0, '2025-05-25 11:26:05'),
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
  `estado` enum('pendiente','cotizado','aprobado','rechazado','en_produccion','finalizado') DEFAULT 'pendiente',
  `estimacion_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(24, 'vendedor@prueba.com', '$2y$10$CZV9YtOpR3VNJzLawU1zYusFPQ5Qs2a44IDSmqCuDAXs/yv/iwsf.', 26, 23, 1, 'uploads/usuarios/usuario_1748077972.jpg');

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
(4, 13, '2025-05-25 23:33:32', 5000.00, 'efectivo'),
(5, 13, '2025-05-26 00:08:17', 10000.00, 'efectivo'),
(6, 13, '2025-05-26 00:32:29', 2000.00, 'efectivo'),
(8, 13, '2025-05-26 20:52:50', 6000.00, 'efectivo'),
(13, 13, '2025-05-26 21:04:15', 622.00, 'efectivo'),
(14, 13, '2025-05-26 21:04:32', 200.00, 'efectivo'),
(15, 13, '2025-05-26 21:05:00', 4200.00, 'efectivo'),
(16, 13, '2025-05-26 21:05:32', 9500.00, 'efectivo');

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
  ADD KEY `producto_id` (`producto_id`);

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
  ADD KEY `proyecto_id` (`proyecto_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
-- AUTO_INCREMENT for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `movimientos_material`
--
ALTER TABLE `movimientos_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `producciones`
--
ALTER TABLE `producciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
