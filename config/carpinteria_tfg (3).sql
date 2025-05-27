-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 11:33 AM
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
(10, 'Carolina Moche', '000014725', '555211223', 'Timbabe', '2025-05-17 04:02:49', 'carolina@gmail.com', 'CAT25010');

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `codigo` VARCHAR(100) NOT NULL
  `fecha` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `compras`
--

INSERT INTO `compras` (`id`, `proveedor_id`, `fecha`, `total`) VALUES
(9, 2, NULL, 36000.00),
(10, 1, NULL, 25000.00),
(11, 1, NULL, 15000.00),
(12, 2, NULL, 52500.00),
(13, 1, NULL, 67500.00),
(14, 1, NULL, 27500.00),
(15, 1, NULL, 100000.00),
(16, 2, NULL, 40000.00),
(17, 1, NULL, 90000.00),
(18, 1, NULL, 30000.00),
(19, 2, NULL, 18000.00),
(20, 2, '2025-05-05', 3400.00),
(21, 1, '0000-00-00', 2000.00),
(22, 2, '2025-05-08', 2400.00),
(23, 1, '2025-05-10', 42000.00),
(24, 1, '2025-05-10', 53800.00),
(25, 2, '2025-05-10', 99000.00);

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
(1, 'Carpinteria SIXBOKU', 'Malabo-perez mercamar', '+240551718822', 'carpinteria@sixboku.com', 'uploads/configuracion/logo_1747446242.jpg', 12.00, 'CFA', 'uploads/configuracion/imagen_1747446242.jpg', 'Ser una referencia en el sector de la carpintería \r\nen Guinea Ecuatorial, destacando por la calidad, \r\nla creatividad y el trato cercano.\r\n\r\n', 'Crear muebles y soluciones de carpintería de alta calidad, \r\ncombinando tradición artesanal con innovación, para mejorar los \r\nespacios de nuestros clientes.\r\n', 'Nacimos como un pequeño taller en Ela-Nguema, fruto del esfuerzo \r\ny la pasión de nuestro fundador. Con el tiempo y gracias a la confianza \r\nde nuestros clientes, nos consolidamos en el Barrio Pérez, donde hoy \r\ntrabajamos con maquinaria moderna y un equipo profesional comprometido \r\ncon la excelencia.');

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
(1, 9, 1, 24, 1500.00, 0),
(4, 12, 1, 35, 1500.00, 0),
(5, 13, 1, 45, 1500.00, 0),
(7, 14, 1, 5, 1500.00, 0),
(12, 19, 5, 12, 1500.00, 0),
(17, 24, 1, 2, 1500.00, 0),
(19, 24, 5, 20, 2000.00, 0),
(20, 25, 6, 25, 1800.00, 0),
(21, 25, 8, 45, 1200.00, 0);

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
  `descuento` DECIMAL(10,2) DEFAULT 0,
  `subtotal` decimal(10,2) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(12, 'Sir', 'topola', '2001-06-07', '000175362', 'M', '240551718822', 'bisinga', 'admin@gmail.com', 'lunes -viernes 8:00 a.m - 18:00 p.m', '2013-06-12', '2025-05-17 01:48:10', NULL);

-- --------------------------------------------------------

 
-- --------------------------------------------------------

--
-- Table structure for table `imagenes_producto`
--

CREATE TABLE `imagenes_producto` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `ruta_imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'clavos', 'clavos de 10 cm', 'kg', 84, 1, '2025-05-07 03:50:45'),
(5, 'tablon', 'madera dura', 'metros', 62, 2, '2025-05-09 23:00:32'),
(6, 'lima', 'util', 'cm2', 25, 2, '2025-05-10 00:57:52'),
(7, 'brocha', 'modelo chino', 'cm', 5, 2, '2025-05-10 00:58:40'),
(8, 'correa', 'necesario', 'mm', 45, 2, '2025-05-10 00:59:28'),
(9, 'lino', 'mola', 'pm', 0, 5, '2025-05-13 20:37:03'),
(10, 'cebolla', 'mola', 'm', 0, 5, '2025-05-13 20:47:56'),
(11, 'chapa', 'moderna', 'mm', 0, 2, '2025-05-13 22:24:28'),
(12, 'morera', 'mola', 'cm', 0, 5, '2025-05-13 22:25:20'),
(13, 'mazda', 'moderna', '0', 0, 2, '2025-05-13 22:25:50'),
(14, 'cepillos', 'mola', 'cm', 0, 2, '2025-05-13 22:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `movimientos_material`
--

CREATE TABLE `movimientos_material` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `tipo_movimiento` enum('pendiente','entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `motivo` text DEFAULT NULL,
  `produccion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

 
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
(1, 'local Marat', 'malu', '551415236', 'tienda@gmail.com', 'bisinga'),
(2, 'Madera Zolart', 'maluma', '222311440', 'zolart@gmail.com', 'bisinga'),
(3, 'tienda amusa', 'amusa', '44525832', 'amusa@prueba.com', 'malabo');

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
(1, 'closet', 'moderno y liviano', 'pendiente', '2025-05-05', '2025-05-20', '2025-05-07 02:25:24'),
(2, 'Mesa de salón', 'una mesa, con costal', 'pendiente', '2025-05-12', '2025-05-20', '2025-05-10 03:20:13');

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
(1, 'Administrador'),
(2, 'Vendedor'),
(3, 'Diseñador'),
(4, 'Operario');

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
(1, 'Montaje de cocinas integrales', 'Montaje y ajuste de módulos de cocina personalizados.', 90000.00, 'por proyecto', 1, '2025-05-17 04:07:27'),
(2, 'Montaje de cocinas integrales', 'Montaje y ajuste de módulos de cocina personalizados.', 90000.00, 'por proyecto', 0, '2025-05-17 04:07:38'),
(3, 'Montaje de cocinas integrales', 'Montaje y ajuste de módulos de cocina personalizados.', 90000.00, 'por proyecto', 0, '2025-05-17 04:07:39'),
(4, 'Montaje de cocinas integrales', 'Montaje y ajuste de módulos de cocina personalizados.', 90000.00, 'por proyecto', 0, '2025-05-17 04:07:39'),
(5, 'Montaje de cocinas integrales', 'Montaje y ajuste de módulos de cocina personalizados.', 90000.00, 'por proyecto', 0, '2025-05-17 04:08:10');

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
(16, 'admin@prueba.com', '$2y$10$CXuUdb/DwfinSQyNIsT2a.SyoWsHCcWObG26HuaMF2YKP1ir.f8eS', 1, 12, 1, 'uploads/usuarios/usuario_1747446687.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
   `metodo_pago` enum('efectivo','tarjeta','transferencia') DEFAULT 'efectivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

 
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
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `detalles_compra`
--
ALTER TABLE `detalles_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

 
--
-- AUTO_INCREMENT for table `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `movimientos_material`
--
ALTER TABLE `movimientos_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

 
--
-- AUTO_INCREMENT for table `producciones`
--
ALTER TABLE `producciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `solicitudes_proyecto`
--
ALTER TABLE `solicitudes_proyecto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
