-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-06-2025 a las 16:58:14
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
  `imagen` text NOT NULL,
  porcentaje int DEFAULT 0,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avances_produccion`
--

INSERT INTO `avances_produccion` (`id`, `produccion_id`, `descripcion`, `imagen`, `fecha`) VALUES
(1, 8, 'mod', 'img_6842faf428d2e7.70904481_istockphoto-628110806-1024x1024.jpg', '2025-06-06 14:28:04'),
(2, 6, 'un modelo trivial', 'uploads/produccion/img_6842fb4533e8a5.70162564_istockphoto-628110806-1024x1024.jpg', '2025-06-06 14:29:25');

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
(1, 'CARPINTERIA SIXBOKU SL', 'PERES MERCAMAR', '551718822', 'sixboku@carpinteria.net', 'uploads/configuracion/logo_1748791116.jpg', 15.00, NULL, 'XAF', 'uploads/configuracion/imagen_1748791116.jpg', 'fgs', 'gfhd', 'sfgs');

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
(33, 17, 6, 7);

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
  `entidad` enum('produccion','proyecto','pedido','venta','factura', 'tareas') NOT NULL
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
(11, 'entregado', 'pedido');

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
(1, 'Madera Pino', 'Madera blanda y económica para estructuras y muebles básicos.', 'metro cúbico', 140, 5, '2025-06-01 16:30:42'),
(2, 'Madera Cedro', 'Madera resistente y aromática, ideal para muebles finos.', 'metro cúbico', 116, 5, '2025-06-01 16:31:16'),
(3, 'Madera MDF', 'Tablero de fibra de densidad media, ideal para interiores.', 'hoja', 100, 5, '2025-06-01 16:31:55'),
(4, 'Cola de carpintero', 'Adhesivo blanco para unión de madera.', 'litro', 15, 2, '2025-06-01 16:32:31'),
(5, 'Tornillos para madera 100mm', 'Tornillos galvanizados para fijaciones pequeñas.', 'kg', 16, 5, '2025-06-01 16:34:06'),
(6, 'Barniz transparente', 'Barniz protector con acabado brillante o mate.', 'litro', 3, 2, '2025-06-01 16:35:32');

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
(19, 4, 'salida', 5, '2025-06-06 14:16:15', 'vmov', 8);

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
(11, 4, 'aparador moderno', 1, NULL, 'un elgante articulo', '2025-06-06', 20, 80000.00, 140000.00, 0.00, 4),
(12, 4, 'aparador moderno', 1, NULL, 'un elgante articulo', '2025-06-06', 2, 15000.00, 71000.00, 50000.00, 6),
(13, 2, 'aparador moderno', 1, NULL, 'un elgante articulo', '2025-06-06', 0, 15000.00, 28500.00, 10000.00, 5),
(14, 3, 'aparador moderno', 1, 2, 'un elgante articulo', '2025-06-06', 15, 45000.00, 61500.00, 50000.00, 5),
(16, 1, 'aparador moderno', 0, NULL, 'un elgante articulo', '2025-06-06', 15, 30000.00, 58000.00, 0.00, 4),
(17, 1, 'aparador moderno', 2, NULL, 'un elgante articulo', '2025-06-06', 15, 30000.00, 58000.00, 29000.00, 5);

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
(7, 17, 4, '2025-06-08', '2025-06-23', 7, '2025-06-06 14:11:21'),
(8, 12, 3, '2025-06-11', '2025-06-13', 7, '2025-06-06 14:15:59');

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
(1, 'aparador moderno', 'un elgante articulo', 'uploads/productos/img_683d2a31893d7.jpg', 120000.00, 0);

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
(4, 'Revestimiento en madera', 'Colocación de paneles o láminas decorativas de madera.', 15000.00, 'por metro cuadrado', 1, '2025-06-01 16:20:58');

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


CREATE TABLE detalles_produccion (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  produccion_id INT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NOT NULL,
  descripcion TEXT,
  cantidad INT DEFAULT 1,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id),
  FOREIGN KEY (producto_id) REFERENCES productos(id)
);


-- Volcado de datos para la tabla `tareas_produccion`
--

INSERT INTO `tareas_produccion` (`id`, `produccion_id`, `descripcion`, `responsable_id`, `estado_id`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 6, 'realiza dos manos de barnizado para el corte 4x8', 3, 8, '2025-06-06', '2025-06-06'),
(2, 8, 'haga un corte en la madera de 4x7', 4, 8, '2025-06-06', '2025-06-06');

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
(1, 2, 'admin', '$2y$10$3vdW2OU0E56D4mjPYrrsQu3oc4qmDYv4VoCh9EM6PIGoBrD4YpkjG', 'uploads/usuarios/usuario_1748791171.jpg', 6, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `movimientos_material`
--
ALTER TABLE `movimientos_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `producciones`
--
ALTER TABLE `producciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tareas_produccion`
--
ALTER TABLE `tareas_produccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
