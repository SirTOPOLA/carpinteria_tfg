-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 10:31 AM
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
-- Database: `carpinteria`
--

-- --------------------------------------------------------

--
-- Table structure for table `avances_produccion`
--

CREATE TABLE `avances_produccion` (
  `id` int(11) NOT NULL,
  `produccion_id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `email` varchar(255) DEFAULT NULL,
  `codigo_acceso` varchar(100) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `codigo`, `telefono`, `direccion`, `email`, `codigo_acceso`, `creado_en`) VALUES
(1, 'Rufina Batapa', '000121323', '555908967', 'lampert', 'rufina@gmail.com', 'RUBA25001', '2025-06-01 16:11:31'),
(2, 'Marieta manga', '1445785245641', '55120456', 'banapa', 'la@gmail.com', 'MAMA25002', '2025-06-03 14:27:32'),
(3, 'lucas moreno', '0001454788', '222001122', 'lamper', 'lucas@gmail.com', 'LUMO25003', '2025-06-03 14:27:44'),
(4, 'carmina', '0014578', '222547895', 'begoña 1', '', 'CACA25004', '2025-06-06 07:04:27');

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `codigo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compras`
--

INSERT INTO `compras` (`id`, `proveedor_id`, `fecha`, `total`, `codigo`) VALUES
(1, 1, '2025-05-31', 115000.00, '#247'),
(2, 1, '2025-06-05', 435000.00, '#SIXBOKU-20250606-0001');

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
  `nif` varchar(10) DEFAULT NULL,
  `moneda` varchar(10) DEFAULT NULL,
  `imagen` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `mision` text DEFAULT NULL,
  `historia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre_empresa`, `direccion`, `telefono`, `correo`, `logo`, `iva`, `nif`, `moneda`, `imagen`, `vision`, `mision`, `historia`) VALUES
(1, 'CARPINTERIA SIXBOKU SL', 'PERES MERCAMAR', '551718822', 'sixboku@carpinteria.net', 'uploads/configuracion/logo_1748791116.jpg', 15.00, NULL, 'XAF', 'uploads/configuracion/imagen_1748791116.jpg', 'fgs', 'gfhd', 'sfgs');

-- --------------------------------------------------------

--
-- Table structure for table `detalles_compra`
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
-- Dumping data for table `detalles_compra`
--

INSERT INTO `detalles_compra` (`id`, `compra_id`, `material_id`, `cantidad`, `precio_unitario`, `stock`) VALUES
(4, 1, 6, 10, 3500.00, 0),
(5, 1, 4, 10, 5000.00, 0),
(6, 1, 5, 20, 1500.00, 0),
(7, 2, 1, 50, 2000.00, 50),
(8, 2, 2, 70, 3500.00, 70),
(9, 2, 6, 5, 4000.00, 5),
(10, 2, 4, 10, 7000.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `detalles_pedido_material`
--

CREATE TABLE `detalles_pedido_material` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalles_pedido_material`
--

INSERT INTO `detalles_pedido_material` (`id`, `pedido_id`, `material_id`, `cantidad`) VALUES
(7, 5, 6, 5),
(8, 5, 5, 4),
(13, 8, 6, 2),
(14, 8, 5, 3),
(15, 9, 5, 2),
(16, 9, 4, 9),
(17, 9, 1, 5),
(18, 9, 6, 2);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalles_venta`
--

INSERT INTO `detalles_venta` (`id`, `venta_id`, `tipo`, `producto_id`, `servicio_id`, `cantidad`, `precio_unitario`, `descuento`, `subtotal`) VALUES
(1, 1, 'servicio', NULL, 1, 5, 1500.00, 0.00, 7500.00),
(2, 2, 'servicio', NULL, 2, 2, 2000.00, 0.00, 4000.00),
(3, 3, 'servicio', NULL, 4, 2, 15000.00, 0.00, 30000.00),
(4, 3, 'servicio', NULL, 2, 2, 2000.00, 0.00, 4000.00),
(5, 3, 'servicio', NULL, 3, 2, 5000.00, 0.00, 10000.00),
(6, 4, 'producto', 1, NULL, 1, 12000.00, 0.00, 12000.00),
(8, 7, 'producto', NULL, NULL, 1, 22000.00, 0.00, 22000.00),
(9, 8, 'producto', 5, NULL, 1, 48500.00, 0.00, 48500.00),
(10, 9, 'producto', 6, NULL, 1, 41500.00, 0.00, 41500.00),
(11, 10, 'producto', 1, NULL, 1, 12000.00, 0.00, 12000.00),
(12, 11, 'producto', 7, NULL, 1, 134000.00, 0.00, 134000.00);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellido`, `fecha_nacimiento`, `codigo`, `genero`, `telefono`, `direccion`, `email`, `horario_trabajo`, `fecha_ingreso`, `creado_en`, `salario`) VALUES
(2, 'Jesus Crispín', 'TOPOLÁ BOÑAHO', '1997-06-30', '000175362', 'M', '551718822', 'Ela Nguema (Bisinga)', 'sirtopola@gmail.com', 'lunes - viernes', '2025-05-26', '2025-06-01 15:19:11', NULL),
(3, 'Bienvenido', 'Sipepe', '2000-06-14', '000144578', 'M', '555477895', 'bata', '', 'lunes - Sabado', '2017-06-07', '2025-06-06 06:50:09', NULL),
(4, 'Merin', 'Compe  Buale', '2008-05-06', '00080910', 'M', '555154578', 'begoña 1', 'martin@gmail.com', 'lunes - viernes', '2020-06-10', '2025-06-06 06:53:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `estados`
--

CREATE TABLE `estados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `entidad` enum('produccion','proyecto','pedido','venta','factura') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estados`
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
-- Table structure for table `facturas`
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
-- Dumping data for table `facturas`
--

INSERT INTO `facturas` (`id`, `venta_id`, `fecha_emision`, `monto_total`, `saldo_pendiente`, `estado_id`) VALUES
(1, 2, '2025-06-01', 4000.00, 0.00, 2),
(2, 1, '2025-06-02', 7500.00, 0.00, 2),
(3, 3, '2025-06-02', 44000.00, 3998.00, 1),
(4, 4, '2025-06-03', 12000.00, 0.00, 2),
(5, 7, '2025-06-04', 22000.00, 0.00, 2),
(6, 8, '2025-06-05', 48500.00, 0.00, 2),
(7, 9, '2025-06-05', 41500.00, 0.00, 2),
(8, 10, '2025-06-06', 12000.00, 0.00, 2),
(9, 11, '2025-06-06', 134000.00, 80000.00, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materiales`
--

INSERT INTO `materiales` (`id`, `nombre`, `descripcion`, `unidad_medida`, `stock_actual`, `stock_minimo`, `creado_en`) VALUES
(1, 'Madera Pino', 'Madera blanda y económica para estructuras y muebles básicos.', 'metro cúbico', 50, 5, '2025-06-01 16:30:42'),
(2, 'Madera Cedro', 'Madera resistente y aromática, ideal para muebles finos.', 'metro cúbico', 70, 5, '2025-06-01 16:31:16'),
(3, 'Madera MDF', 'Tablero de fibra de densidad media, ideal para interiores.', 'hoja', 0, 5, '2025-06-01 16:31:55'),
(4, 'Cola de carpintero', 'Adhesivo blanco para unión de madera.', 'litro', 20, 2, '2025-06-01 16:32:31'),
(5, 'Tornillos para madera 100mm', 'Tornillos galvanizados para fijaciones pequeñas.', 'kg', 16, 5, '2025-06-01 16:34:06'),
(6, 'Barniz transparente', 'Barniz protector con acabado brillante o mate.', 'litro', 7, 2, '2025-06-01 16:35:32');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movimientos_material`
--

INSERT INTO `movimientos_material` (`id`, `material_id`, `tipo_movimiento`, `cantidad`, `fecha`, `motivo`, `produccion_id`) VALUES
(1, 6, 'salida', 5, '2025-06-05 04:10:02', 'salida', 2),
(2, 5, 'salida', 4, '2025-06-05 04:10:14', 'salida', 2),
(3, 6, 'salida', 2, '2025-06-05 04:38:10', 'barnizado', 1),
(4, 6, 'entrada', 1, '2025-06-05 04:41:49', 'montaje', 1),
(5, 6, 'salida', 2, '2025-06-05 07:56:07', 'salida', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
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
-- Dumping data for table `pagos`
--

INSERT INTO `pagos` (`id`, `factura_id`, `monto_pagado`, `fecha_pago`, `metodo_pago`, `observaciones`) VALUES
(1, 1, 2000.00, '2025-06-01', 'efectivo', 'pagará el resto la semana entrante'),
(2, 1, 2000.00, '2025-06-01', 'efectivo', 'pagará el resto la semana entrante'),
(3, 1, 1500.00, '2025-06-01', 'efectivo', 'pagar proxima semana'),
(4, 1, 500.00, '2025-06-02', 'efectivo', 'paga completada'),
(5, 2, 5000.00, '2025-06-02', 'efectivo', 'pagará tras la entrega'),
(6, 2, 2500.00, '2025-06-02', 'efectivo', 'Pagado'),
(7, 3, 2.00, '2025-06-02', 'efectivo', 'pagaré'),
(8, 3, 40000.00, '2025-06-02', 'efectivo', 'a deber'),
(9, 3, 40000.00, '2025-06-02', 'efectivo', 'a deber'),
(10, 4, 12000.00, '2025-06-03', 'efectivo', 'factura sin costes.'),
(11, 5, 11000.00, '2025-06-04', 'efectivo', 'pagara el resto en la entrega'),
(12, 5, 11000.00, '2025-06-04', 'efectivo', 'pagara el resto en la entrega'),
(13, 6, 30000.00, '2025-06-05', 'efectivo', ''),
(14, 6, 18500.00, '2025-06-05', 'efectivo', ''),
(15, 7, 22000.00, '2025-06-05', '', ''),
(16, 7, 19500.00, '2025-06-05', 'efectivo', ''),
(17, 8, 12000.00, '2025-06-06', 'efectivo', ''),
(18, 9, 54000.00, '2025-06-06', 'efectivo', 'paga el resto alfinal');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `proyecto` varchar(100) NOT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_entrega` int(11) NOT NULL,
  `precio_obra` decimal(10,2) DEFAULT 0.00,
  `estimacion_total` decimal(10,2) DEFAULT NULL,
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_id`, `proyecto`, `servicio_id`, `descripcion`, `fecha_solicitud`, `fecha_entrega`, `precio_obra`, `estimacion_total`, `estado_id`) VALUES
(5, 3, 'aparador de pared', NULL, 'un aparador moderno de 2x3 para un salon mediano', '2025-06-05', 20, 25000.00, 48500.00, 11),
(8, 2, 'Mesita de salón', NULL, 'un mueble elegante', '2025-06-05', 25, 30000.00, 41500.00, 11),
(9, 4, 'aparador moderno', NULL, 'una replica moderna para salon grande', '2025-06-06', 10, 50000.00, 134000.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `producciones`
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
-- Dumping data for table `producciones`
--

INSERT INTO `producciones` (`id`, `solicitud_id`, `responsable_id`, `fecha_inicio`, `fecha_fin`, `estado_id`, `created_at`) VALUES
(1, NULL, 2, '2025-06-09', '2025-06-05', 9, '2025-06-04 14:22:13'),
(2, 5, 2, '2025-06-16', '2025-06-05', 9, '2025-06-04 23:06:00'),
(3, NULL, 2, '2025-06-09', '2025-06-05', 9, '2025-06-05 07:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
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
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `imagen`, `precio_unitario`, `stock`) VALUES
(1, 'aparador moderno', 'un elgante articulo', 'uploads/productos/img_683d2a31893d7.jpg', 12000.00, 0),
(5, 'aparador de pared', 'un aparador moderno de 2x3 para un salon mediano', 'producto_68413437b40868.73728009.jpeg', 48500.00, 0),
(6, 'Mesita de salón', 'un mueble elegante', NULL, 41500.00, 0),
(7, 'aparador moderno', 'una replica moderna para salon grande', NULL, 134000.00, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `contacto`, `telefono`, `email`, `direccion`) VALUES
(1, 'Lucichat SL', 'Sulamita', '22501254', NULL, 'Bisinga');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(6, 'Administrador'),
(7, 'Vendedor'),
(8, 'Diseñador'),
(9, 'empleado'),
(10, 'Operario');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio_base`, `unidad`, `activo`, `creado_en`) VALUES
(1, 'Corte de madera', 'Corte de madera con maquinaria especializada según medidas requeridas.', 1500.00, 'por corte', 1, '2025-06-01 16:17:59'),
(2, 'Cepillado de madera', 'Alisado y nivelado de tablas o piezas de madera', 2000.00, 'por metro', 1, '2025-06-01 16:18:39'),
(3, 'Barnizado', 'Aplicación de barniz protector y decorativo sobre superficies de madera.', 5000.00, 'por metro cuadrado', 1, '2025-06-01 16:19:25'),
(4, 'Revestimiento en madera', 'Colocación de paneles o láminas decorativas de madera.', 15000.00, 'por metro cuadrado', 1, '2025-06-01 16:20:58');

-- --------------------------------------------------------

--
-- Table structure for table `tareas_produccion`
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

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `empleado_id`, `username`, `password`, `imagen`, `rol_id`, `activo`) VALUES
(1, 2, 'admin', '$2y$10$3vdW2OU0E56D4mjPYrrsQu3oc4qmDYv4VoCh9EM6PIGoBrD4YpkjG', 'uploads/usuarios/usuario_1748791171.jpg', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
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
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `nombre_cliente`, `dni_cliente`, `direccion_cliente`, `fecha`, `total`, `metodo_pago`) VALUES
(1, 1, '', '', '', '2025-06-01 17:16:25', 7500.00, 'efectivo'),
(2, NULL, 'Mandela', '000145478', 'Sampaca', '2025-06-01 17:19:19', 4000.00, 'efectivo'),
(3, NULL, 'Felipe Raso', '0001405478', 'lampert', '2025-06-02 03:09:49', 44000.00, 'efectivo'),
(4, NULL, 'Felipe', '0014578', 'los angeles', '2025-06-03 10:58:39', 12000.00, 'efectivo'),
(7, 2, NULL, NULL, NULL, '2025-06-04 12:24:01', 22000.00, 'efectivo'),
(8, 3, NULL, NULL, NULL, '2025-06-04 23:03:23', 48500.00, 'efectivo'),
(9, 2, NULL, NULL, NULL, '2025-06-05 07:54:23', 41500.00, 'efectivo'),
(10, NULL, 'carmina', ' 0014578', 'begoña 1', '2025-06-06 06:59:18', 12000.00, 'efectivo'),
(11, 4, NULL, NULL, NULL, '2025-06-06 07:10:01', 134000.00, 'efectivo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avances_produccion`
--
ALTER TABLE `avances_produccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produccion_id` (`produccion_id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

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
-- Indexes for table `detalles_pedido_material`
--
ALTER TABLE `detalles_pedido_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
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
-- Indexes for table `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `estado_id` (`estado_id`);

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
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indexes for table `producciones`
--
ALTER TABLE `producciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_id` (`solicitud_id`),
  ADD KEY `responsable_id` (`responsable_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
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
-- Indexes for table `tareas_produccion`
--
ALTER TABLE `tareas_produccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produccion_id` (`produccion_id`),
  ADD KEY `responsable_id` (`responsable_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `empleado_id` (`empleado_id`),
  ADD KEY `rol_id` (`rol_id`);

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
-- AUTO_INCREMENT for table `avances_produccion`
--
ALTER TABLE `avances_produccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detalles_compra`
--
ALTER TABLE `detalles_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `detalles_pedido_material`
--
ALTER TABLE `detalles_pedido_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `movimientos_material`
--
ALTER TABLE `movimientos_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `producciones`
--
ALTER TABLE `producciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tareas_produccion`
--
ALTER TABLE `tareas_produccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avances_produccion`
--
ALTER TABLE `avances_produccion`
  ADD CONSTRAINT `avances_produccion_ibfk_1` FOREIGN KEY (`produccion_id`) REFERENCES `producciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD CONSTRAINT `detalles_compra_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_compra_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `detalles_pedido_material`
--
ALTER TABLE `detalles_pedido_material`
  ADD CONSTRAINT `detalles_pedido_material_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_pedido_material_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_venta_ibfk_3` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `movimientos_material`
--
ALTER TABLE `movimientos_material`
  ADD CONSTRAINT `movimientos_material_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_material_ibfk_2` FOREIGN KEY (`produccion_id`) REFERENCES `producciones` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `producciones`
--
ALTER TABLE `producciones`
  ADD CONSTRAINT `producciones_ibfk_1` FOREIGN KEY (`solicitud_id`) REFERENCES `pedidos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `producciones_ibfk_2` FOREIGN KEY (`responsable_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `producciones_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tareas_produccion`
--
ALTER TABLE `tareas_produccion`
  ADD CONSTRAINT `tareas_produccion_ibfk_1` FOREIGN KEY (`produccion_id`) REFERENCES `producciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tareas_produccion_ibfk_2` FOREIGN KEY (`responsable_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tareas_produccion_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
