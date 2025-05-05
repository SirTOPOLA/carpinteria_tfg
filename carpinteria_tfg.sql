-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 05:25 PM
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
-- Table structure for table `categorias_material`
--

CREATE TABLE `categorias_material` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias_material`
--

INSERT INTO `categorias_material` (`id`, `nombre`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Maderas', 'Madera maciza, laminada y reciclada', '2025-04-19 11:50:44'),
(2, 'Tableros y derivados', 'MDF, Melamina, Triplay, Aglomerado, OSB', '2025-04-19 11:50:44'),
(3, 'Herrajes y accesorios', 'Bisagras, jaladeras, correderas, cerraduras, soportes', '2025-04-19 11:50:44'),
(4, 'Adhesivos y pegamentos', 'Cola blanca, pegamento de contacto, siliconas, resinas', '2025-04-19 11:50:44'),
(5, 'Acabados y pinturas', 'Barnices, selladores, lacas, tintes y aceites', '2025-04-19 11:50:44'),
(6, 'Abrasivos y lijas', 'Lijas de diversos granos, discos y esponjas abrasivas', '2025-04-19 11:50:44'),
(7, 'Material eléctrico', 'Tiras LED, conectores, interruptores para muebles', '2025-04-19 11:50:44'),
(8, 'Consumibles de maquinaria', 'Discos de corte, brocas, hojas de sierra, clavos y tornillos', '2025-04-19 11:50:44'),
(9, 'Empaque y embalaje', 'Cajas, plásticos protectores, cintas', '2025-04-19 11:50:44'),
(10, 'Otros materiales auxiliares', 'Molduras, espumas, plantillas, rellenos tapizados', '2025-04-19 11:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `categorias_producto`
--

CREATE TABLE `categorias_producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias_producto`
--

INSERT INTO `categorias_producto` (`id`, `nombre`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Muebles para el hogar', 'Incluye camas, mesas, sillas, closets y demás mobiliario para uso residencial.', '2025-04-19 10:50:45'),
(2, 'Muebles de oficina', 'Escritorios, archivadores, bibliotecas, estaciones de trabajo y sillas.', '2025-04-19 10:50:45'),
(3, 'Puertas y ventanas', 'Fabricación de puertas macizas, corredizas, batientes y ventanas de madera.', '2025-04-19 10:50:45'),
(4, 'Cocinas y alacenas', 'Gabinetes, muebles modulares y alacenas a medida.', '2025-04-19 10:50:45'),
(5, 'Closets y roperos', 'Sistemas de almacenamiento personalizados para dormitorios.', '2025-04-19 10:50:45'),
(6, 'Estanterías y repisas', 'Repisas decorativas, libreros y estantes funcionales.', '2025-04-19 10:50:45'),
(7, 'Cabeceras y respaldos', 'Diseños tapizados o en madera maciza para camas.', '2025-04-19 10:50:45'),
(8, 'Muebles de baño', 'Vanities, gabinetes y muebles auxiliares para baño.', '2025-04-19 10:50:45'),
(9, 'Paneles decorativos', 'Paneles tallados, revestimientos de pared y plafones de diseño.', '2025-04-19 10:50:45'),
(10, 'Juguetes y mobiliario infantil', 'Mesitas, cunas, sillas, estanterías y juguetes de madera.', '2025-04-19 10:50:45'),
(11, 'Muebles exteriores', 'Mesas de jardín, bancos, pérgolas y decks para exteriores.', '2025-04-19 10:50:45'),
(12, 'Accesorios y complementos', 'Bandejas, organizadores, cajones y piezas decorativas.', '2025-04-19 10:50:45'),
(13, 'Muebles personalizados', 'Diseños únicos hechos a pedido del cliente.', '2025-04-19 10:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `categorias_proyecto`
--

CREATE TABLE `categorias_proyecto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias_proyecto`
--

INSERT INTO `categorias_proyecto` (`id`, `nombre`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Muebles de hogar', 'Proyectos como closets, camas, mesas, sillas y repisas para uso doméstico.', '2025-04-19 10:50:44'),
(2, 'Muebles de oficina', 'Fabricación de escritorios, estanterías, archivos y divisiones para oficinas.', '2025-04-19 10:50:44'),
(3, 'Cocinas integrales', 'Diseño y elaboración de muebles de cocina a medida con acabados personalizados.', '2025-04-19 10:50:44'),
(4, 'Puertas y ventanas', 'Fabricación de puertas, marcos, ventanas de madera, corredizas o abatibles.', '2025-04-19 10:50:44'),
(5, 'Decoración interior', 'Elementos decorativos como paneles, molduras, zócalos y enchapes.', '2025-04-19 10:50:44'),
(6, 'Muebles comerciales', 'Mostradores, vitrinas, estanterías y mobiliario para tiendas y negocios.', '2025-04-19 10:50:44'),
(7, 'Proyectos a medida', 'Diseños personalizados según requerimientos específicos del cliente.', '2025-04-19 10:50:44'),
(8, 'Restauración de muebles', 'Servicios de reparación, restauración y barnizado de muebles antiguos.', '2025-04-19 10:50:44'),
(9, 'Terrazas y exteriores', 'Pergolas, muebles de terraza, cercos y estructuras de madera para exteriores.', '2025-04-19 10:50:44'),
(10, 'Closets empotrados', 'Diseño y construcción de closets integrados a la arquitectura del espacio.', '2025-04-19 10:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `direccion`, `telefono`, `correo`, `fecha_registro`) VALUES
(1, 'Juan Pérez', 'Av. Libertad 123, Lima', '987654321', 'juan.perez@gmail.com', '2025-04-19 10:50:43'),
(2, 'María García', 'Calle Falsa 456, Arequipa', '945672312', 'maria.garcia@hotmail.com', '2025-04-19 10:50:43'),
(3, 'Constructora Solidez', 'Jr. Ingeniería 345, Trujillo', '951234567', 'contacto@solidez.com', '2025-04-19 10:50:43'),
(4, 'Arquitectura Moderna SAC', 'Av. Reforma 789, Cusco', '968342115', 'ventas@moderna.com', '2025-04-19 10:50:43'),
(5, 'Carlos López', 'Mz. A Lt. 8, Chiclayo', '987001122', 'carlos.lopez@yahoo.com', '2025-04-19 10:50:43'),
(6, 'Muebles del Sur', 'Carretera Panamericana km 45, Tacna', '936745231', 'info@muebledelsur.pe', '2025-04-19 10:50:43'),
(7, 'Ana Torres', 'Urb. Primavera 88, Ica', '980123456', 'ana.torres@gmail.com', '2025-04-19 10:50:43'),
(8, 'Empresa Inmobiliaria Real', 'Av. Central 150, Piura', '972563478', 'inmobiliaria@real.pe', '2025-04-19 10:50:43'),
(9, 'Luis Fernández', 'Calle Colón 321, Huancayo', '989654321', 'luis.fernandez@hotmail.com', '2025-04-19 10:50:43'),
(10, 'Muebles y Estilo EIRL', 'Av. América Sur 101, Cajamarca', '965321478', 'ventas@mueblesyestilo.pe', '2025-04-19 10:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `moneda` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cotizaciones`
--

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `tipo` enum('cotizacion','proforma') DEFAULT 'cotizacion',
  `fecha_emision` date NOT NULL,
  `validez_dias` int(11) DEFAULT 7,
  `estado` enum('pendiente','aceptada','rechazada') DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT 0.00,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`, `descripcion`, `creado_en`) VALUES
(1, 'Departamento de Producción', 'Encargado de la fabricación de los productos de carpintería, bajo la supervisión de un jefe de Producción, que coordina a los Oficiales Carpinteros y Ayudantes de Taller.', '2025-05-01 13:08:37'),
(2, 'Departamento de Diseño y Proyectos', 'Responsable del desarrollo de propuestas personalizadas para los clientes, elaboración de planos, diseños en 3D y asesoramiento técnico.', '2025-05-01 13:09:19'),
(3, 'Departamento Comercial y Atención al Cliente', 'Encargado de la gestión de clientes, presupuestos, ventas, marketing y promoción de los productos y servicios.', '2025-05-01 13:09:48'),
(4, 'Departamento Administrativo y Financiero', 'Administra los recursos económicos, gestiona la contabilidad, las compras de materiales y el control de inventarios.', '2025-05-01 13:10:14');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_cotizacion_productos`
--

CREATE TABLE `detalle_cotizacion_productos` (
  `id` int(11) NOT NULL,
  `cotizacion_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_cotizacion_proyectos`
--

CREATE TABLE `detalle_cotizacion_proyectos` (
  `id` int(11) NOT NULL,
  `cotizacion_id` int(11) NOT NULL,
  `proyecto_id` int(11) NOT NULL,
  `descripcion_personalizada` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_cotizacion_servicios`
--

CREATE TABLE `detalle_cotizacion_servicios` (
  `id` int(11) NOT NULL,
  `cotizacion_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_orden_trabajo`
--

CREATE TABLE `detalle_orden_trabajo` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) NOT NULL,
  `proyecto_id` int(11) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `codigo` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `departamento_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `horario_trabajo` varchar(100) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellido`, `codigo`, `telefono`, `direccion`, `departamento_id`, `email`, `salario`, `horario_trabajo`, `fecha_ingreso`, `created_at`) VALUES
(1, 'martin', 'compe', '2015', '55120456', 'begoña 1', 4, 'mar@gmail.com', 20000.00, 'lunes -viernes', '2025-05-15', '2025-05-01 13:29:54'),
(2, 'lucas', 'mar', '222', '55120456', 'lamper', 1, 'mark@gmail.com', 50000.00, 'lunes -viernes', '2023-02-08', '2025-05-01 15:26:37');

-- --------------------------------------------------------

--
-- Table structure for table `imagenes_producto`
--

CREATE TABLE `imagenes_producto` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `ruta_imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materiales`
--

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `unidad_medida` varchar(50) DEFAULT NULL,
  `stock_actual` decimal(10,2) DEFAULT 0.00,
  `stock_minimo` decimal(10,2) DEFAULT 0.00,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materiales`
--

INSERT INTO `materiales` (`id`, `nombre`, `descripcion`, `categoria_id`, `unidad_medida`, `stock_actual`, `stock_minimo`, `fecha_creacion`) VALUES
(1, 'Madera Pino', 'Madera blanda usada en estructuras y muebles', 1, 'm³', 3.50, 1.00, '2025-04-19 11:50:44'),
(2, 'Madera Cedro', 'Madera resistente y decorativa para acabados', 1, 'm³', 2.00, 0.50, '2025-04-19 11:50:44'),
(3, 'Madera Nogal', 'Madera dura para muebles finos', 1, 'm³', 1.20, 0.30, '2025-04-19 11:50:44'),
(4, 'MDF 15mm', 'Tablero MDF de 15mm para mobiliario', 2, 'unidad', 50.00, 10.00, '2025-04-19 11:50:44'),
(5, 'Melamina Blanco 18mm', 'Tablero melamínico blanco 18mm', 2, 'unidad', 40.00, 10.00, '2025-04-19 11:50:44'),
(6, 'Triplay 9mm', 'Triplay de pino 9mm', 2, 'unidad', 30.00, 5.00, '2025-04-19 11:50:44'),
(7, 'Bisagra cazoleta 35mm', 'Bisagra para puertas de muebles', 3, 'paquete', 100.00, 20.00, '2025-04-19 11:50:44'),
(8, 'Corredera telescópica 40cm', 'Corredera metálica para cajones', 3, 'par', 80.00, 20.00, '2025-04-19 11:50:44'),
(9, 'Jaladera tipo barra', 'Jaladera metálica para mueble', 3, 'unidad', 120.00, 30.00, '2025-04-19 11:50:44'),
(10, 'Cola blanca 1L', 'Adhesivo PVA para madera', 4, 'litro', 25.00, 5.00, '2025-04-19 11:50:44'),
(11, 'Silicona transparente', 'Silicona multiuso para ensamblajes', 4, 'unidad', 40.00, 10.00, '2025-04-19 11:50:44'),
(12, 'Barniz Poliuretano', 'Barniz brillante para madera', 5, 'litro', 15.00, 3.00, '2025-04-19 11:50:44'),
(13, 'Tinte caoba', 'Tinte color caoba para acabados', 5, 'litro', 10.00, 2.00, '2025-04-19 11:50:44'),
(14, 'Lija grano 120', 'Lija fina para acabados', 6, 'paquete', 30.00, 5.00, '2025-04-19 11:50:44'),
(15, 'Disco abrasivo 115mm', 'Disco para esmeril angular', 6, 'unidad', 20.00, 5.00, '2025-04-19 11:50:44'),
(16, 'Tira LED 5m', 'Iluminación para muebles y vitrinas', 7, 'unidad', 15.00, 5.00, '2025-04-19 11:50:44'),
(17, 'Interruptor empotrado', 'Interruptor pequeño para muebles', 7, 'unidad', 25.00, 5.00, '2025-04-19 11:50:44'),
(18, 'Disco Sierra Circular 10\"', 'Disco de 80 dientes para corte fino', 8, 'unidad', 10.00, 2.00, '2025-04-19 11:50:44'),
(19, 'Clavos 1”', 'Clavos de acero para pistola neumática', 8, 'caja', 38.00, 10.00, '2025-04-19 11:50:44'),
(20, 'Caja cartón reforzada', 'Caja para empaque de muebles', 9, 'unidad', 40.00, 10.00, '2025-04-19 11:50:44'),
(21, 'Film plástico estirable', 'Protección contra polvo y humedad', 9, 'rollo', 15.00, 5.00, '2025-04-19 11:50:44'),
(22, 'Espuma para tapizado', 'Espuma de alta densidad para cojines', 10, 'm²', 20.00, 5.00, '2025-04-19 11:50:44'),
(23, 'Plantilla de corte', 'Plantilla para armado de piezas repetitivas', 10, 'unidad', 10.00, 2.00, '2025-04-19 11:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movimientos_inventario`
--

INSERT INTO `movimientos_inventario` (`id`, `material_id`, `tipo`, `cantidad`, `motivo`, `fecha`) VALUES
(1, 19, 'salida', 12.00, '', '2025-05-01 11:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `ordenes_trabajo`
--

CREATE TABLE `ordenes_trabajo` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `cotizacion_id` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `estado` enum('pendiente','en_produccion','terminado','entregado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `categoria_id`, `precio`, `fecha_creacion`) VALUES
(1, 'Mesa de comedor de roble', 'Mesa rectangular para 6 personas, madera de roble pulido.', 1, 850.00, '2025-04-19 10:50:45'),
(2, 'Silla tapizada clásica', 'Silla de comedor con respaldo alto y asiento acolchado.', 1, 180.00, '2025-04-19 10:50:45'),
(3, 'Escritorio ejecutivo', 'Escritorio de oficina en madera de cedro con cajoneras.', 2, 1200.00, '2025-04-19 10:50:45'),
(4, 'Archivador de 3 gavetas', 'Archivador vertical con cerradura metálica.', 2, 450.00, '2025-04-19 10:50:45'),
(5, 'Puerta maciza de caoba', 'Puerta de entrada de alta seguridad con barnizado premium.', 3, 980.00, '2025-04-19 10:50:45'),
(6, 'Ventana corrediza de pino', 'Ventana doble hoja con vidrio templado.', 3, 650.00, '2025-04-19 10:50:45'),
(7, 'Gabinete de cocina modular', 'Módulo superior de cocina con acabado brillante.', 4, 540.00, '2025-04-19 10:50:45'),
(8, 'Alacena de pared', 'Alacena compacta para cocina con tres compartimientos.', 4, 320.00, '2025-04-19 10:50:45'),
(9, 'Closet empotrado 3 puertas', 'Closet de madera MDF con división interna.', 5, 1100.00, '2025-04-19 10:50:45'),
(10, 'Ropero de 2 cuerpos', 'Ropero de madera natural con puertas corredizas.', 5, 950.00, '2025-04-19 10:50:45'),
(11, 'Repisas flotantes (juego de 3)', 'Juego de repisas minimalistas para sala.', 6, 270.00, '2025-04-19 10:50:45'),
(12, 'Estantería tipo biblioteca', 'Estantería de 5 niveles para libros y decoraciones.', 6, 590.00, '2025-04-19 10:50:45'),
(13, 'Respaldo acolchado para cama', 'Cabecera tapizada en lino con estructura de madera.', 7, 480.00, '2025-04-19 10:50:45'),
(14, 'Mueble de baño con espejo', 'Vanity con lavamanos, cajonera y espejo incorporado.', 8, 750.00, '2025-04-19 10:50:45'),
(15, 'Panel decorativo tallado', 'Panel artesanal en madera con diseño floral.', 9, 430.00, '2025-04-19 10:50:45'),
(16, 'Cuna para bebé con barandas', 'Cuna segura con barandas móviles y ruedas.', 10, 670.00, '2025-04-19 10:50:45'),
(17, 'Banco rústico para jardín', 'Banco de madera tratada para exteriores.', 11, 320.00, '2025-04-19 10:50:45'),
(18, 'Deck modular', 'Plataforma modular antideslizante para jardín.', 11, 880.00, '2025-04-19 10:50:45'),
(19, 'Organizador de escritorio', 'Caja de madera con divisiones para útiles.', 12, 95.00, '2025-04-19 10:50:45'),
(20, 'Bandeja decorativa de nogal', 'Bandeja rectangular para centro de mesa.', 12, 60.00, '2025-04-19 10:50:45'),
(21, 'Mesa de diseño exclusivo', 'Mesa personalizada según requerimientos del cliente.', 13, 1400.00, '2025-04-19 10:50:45'),
(22, 'Silla ergonómica artesanal', 'Diseño ergonómico hecho a medida.', 13, 890.00, '2025-04-19 10:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `telefono`, `correo`, `direccion`, `fecha_registro`) VALUES
(1, 'Maderas del Norte SAC', '987654321', 'ventas@maderasnorte.pe', 'Av. Los Álamos 123, Lima', '2025-04-19 10:50:43'),
(2, 'Proveedoras Andinas SRL', '945672312', 'contacto@andinas.com', 'Jr. Comercio 456, Cusco', '2025-04-19 10:50:43'),
(3, 'Maderera El Roble', '951234567', 'info@elroble.pe', 'Av. Forestal 789, Arequipa', '2025-04-19 10:50:43'),
(4, 'Distribuidora Carpintera S.A.C.', '968342115', 'ventas@carpintera.com', 'Calle Industrial 111, Trujillo', '2025-04-19 10:50:43'),
(5, 'Ferretería San José', '987001122', 'ferreteria@sanjose.com', 'Mz. D Lt. 5, Chiclayo', '2025-04-19 10:50:43'),
(6, 'Grupo Cedro y Nogal', '936745231', 'cedronogal@grupo.com', 'Carretera Central km 12, Huancayo', '2025-04-19 10:50:43'),
(7, 'Importadora de Maderas del Sur', '980123456', 'importaciones@maderassur.pe', 'Av. Panamericana Sur 301, Ica', '2025-04-19 10:50:43'),
(8, 'Industria Maderera Andina', '972563478', 'industria@andina.pe', 'Parque Industrial, Piura', '2025-04-19 10:50:43'),
(9, 'Maderas Selectas EIRL', '989654321', 'ventas@maderasselectas.pe', 'Av. Las Palmeras 321, Cajamarca', '2025-04-19 10:50:43'),
(10, 'Suministros Carpinteros SAC', '965321478', 'suministros@carpinteros.pe', 'Av. Los Talladores 555, Tacna', '2025-04-19 10:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `descripcion`, `categoria_id`, `fecha_creacion`) VALUES
(1, 'Closet empotrado de 3 cuerpos', 'Closet moderno de madera MDF con puertas corredizas y acabado en melamina blanca.', 10, '2025-04-19 10:50:45'),
(2, 'Escritorio en L para oficina', 'Diseño funcional con cajonera y espacio para CPU, acabado en tono wengué.', 2, '2025-04-19 10:50:45'),
(3, 'Cocina integral estilo minimalista', 'Muebles superiores e inferiores con superficie de granito, bisagras hidráulicas.', 3, '2025-04-19 10:50:45'),
(4, 'Puerta principal maciza de cedro', 'Puerta de entrada estilo rústico con barniz protector y detalles tallados.', 4, '2025-04-19 10:50:45'),
(5, 'Mueble para TV con repisas flotantes', 'Centro de entretenimiento en MDF con espacios abiertos y puertas ocultas.', 1, '2025-04-19 10:50:45'),
(6, 'Estantería modular para archivo', 'Sistema de estanterías ajustables para almacenamiento de documentos.', 2, '2025-04-19 10:50:45'),
(7, 'Mostrador para panadería', 'Mostrador frontal con vitrina de vidrio templado y estantes inferiores.', 6, '2025-04-19 10:50:45'),
(8, 'Mesa de comedor extensible', 'Mesa en madera sólida de 6 a 10 puestos con sistema deslizante.', 1, '2025-04-19 10:50:45'),
(9, 'Pergola de madera tratada', 'Estructura para terraza con techo de policarbonato y soporte reforzado.', 9, '2025-04-19 10:50:45'),
(10, 'Restauración de vitrina antigua', 'Pulido, barnizado y reemplazo de vidrio en vitrina de roble.', 8, '2025-04-19 10:50:45'),
(11, 'Panel decorativo con listones', 'Panel mural con diseño de listones verticales para sala o recepción.', 5, '2025-04-19 10:50:45'),
(12, 'Cama con cabecera tapizada', 'Diseño contemporáneo con almacenamiento inferior y cabecera forrada.', 1, '2025-04-19 10:50:45'),
(13, 'Mueble bajo de cocina', 'Gabinete inferior de cocina con cajones, puertas y espacio para horno.', 3, '2025-04-19 10:50:45'),
(14, 'Puerta corrediza tipo granero', 'Puerta rústica en roble con sistema de riel metálico expuesto.', 4, '2025-04-19 10:50:45'),
(15, 'Closet corredizo con espejo', 'Frente de espejo entero, interior con organizadores y zapatera.', 10, '2025-04-19 10:50:45'),
(16, 'División de ambientes con estantería', 'Estante multifuncional que sirve como división y almacenamiento.', 5, '2025-04-19 10:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `fecha_creacion`) VALUES
(1, 'Instalación de puertas', 'Montaje de puertas interiores o exteriores, incluye nivelación y herrajes.', 450.00, '2025-04-19 10:50:45'),
(2, 'Mantenimiento de muebles', 'Limpieza, pulido y restauración de muebles deteriorados por el uso o el tiempo.', 300.00, '2025-04-19 10:50:45'),
(3, 'Diseño de interiores en madera', 'Asesoría y elaboración de planos y propuestas en madera para ambientes residenciales o comerciales.', 700.00, '2025-04-19 10:50:45'),
(4, 'Lijado y barnizado de superficies', 'Proceso de preparación y acabado para renovar superficies de madera.', 250.00, '2025-04-19 10:50:45'),
(5, 'Restauración de muebles antiguos', 'Reparación estructural, sustitución de partes y acabado profesional de muebles antiguos.', 600.00, '2025-04-19 10:50:45'),
(6, 'Reparación de puertas y ventanas', 'Servicio para puertas y ventanas que no cierran bien, están flojas o dañadas.', 200.00, '2025-04-19 10:50:45'),
(7, 'Fabricación a medida de muebles', 'Creación de muebles personalizados según especificaciones del cliente.', 850.00, '2025-04-19 10:50:45'),
(8, 'Armado de muebles en sitio', 'Montaje profesional de muebles en la ubicación del cliente.', 180.00, '2025-04-19 10:50:45'),
(9, 'Asesoría para optimización de espacios', 'Servicio de consultoría para integrar mobiliario en espacios reducidos.', 350.00, '2025-04-19 10:50:45'),
(10, 'Cambio de bisagras y correderas', 'Reemplazo de mecanismos dañados o ruidosos en cajones y puertas.', 150.00, '2025-04-19 10:50:45'),
(11, 'Pintura y laqueado de muebles', 'Aplicación de pintura o laca en piezas nuevas o restauradas.', 300.00, '2025-04-19 10:50:45'),
(12, 'Instalación de closets empotrados', 'Colocación profesional de closets con nivelación y ajuste.', 500.00, '2025-04-19 10:50:45'),
(13, 'Corte y diseño en CNC', 'Cortes de precisión con máquina CNC para piezas decorativas o funcionales.', 700.00, '2025-04-19 10:50:45'),
(14, 'Servicio de medición en sitio', 'Visita técnica para toma de medidas y evaluación del lugar.', 100.00, '2025-04-19 10:50:45'),
(15, 'Tapizado de cabeceras y asientos', 'Servicio profesional de tapizado con espuma y telas de alta calidad.', 400.00, '2025-04-19 10:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `trabajadores`
--

CREATE TABLE `trabajadores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('administrador','vendedor','operario','diseñador') DEFAULT 'vendedor',
  `empleado_id` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol`, `empleado_id`, `activo`) VALUES
(2, '@gmail.com', '$2y$10$xwrPBsDgBXeNUSLKuAbWm.huO92FSteWTgAWjo41TDGDVgNufKyRq', 'operario', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `tipo_pago` enum('Efectivo','Transferencia','Tarjeta') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `fecha`, `total`, `tipo_pago`) VALUES
(1, 5, '2025-04-30 23:00:00', 540.00, 'Efectivo');

-- --------------------------------------------------------

--
-- Table structure for table `venta_detalle`
--

CREATE TABLE `venta_detalle` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `tipo_item` varchar(50) NOT NULL,
  `item_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venta_detalle`
--

INSERT INTO `venta_detalle` (`id`, `venta_id`, `tipo_item`, `item_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 'producto', 7, 1, 540.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias_material`
--
ALTER TABLE `categorias_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorias_producto`
--
ALTER TABLE `categorias_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorias_proyecto`
--
ALTER TABLE `categorias_proyecto`
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
-- Indexes for table `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indexes for table `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_id` (`compra_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `detalle_cotizacion_productos`
--
ALTER TABLE `detalle_cotizacion_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cotizacion_id` (`cotizacion_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indexes for table `detalle_cotizacion_proyectos`
--
ALTER TABLE `detalle_cotizacion_proyectos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cotizacion_id` (`cotizacion_id`),
  ADD KEY `proyecto_id` (`proyecto_id`);

--
-- Indexes for table `detalle_cotizacion_servicios`
--
ALTER TABLE `detalle_cotizacion_servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cotizacion_id` (`cotizacion_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indexes for table `detalle_orden_trabajo`
--
ALTER TABLE `detalle_orden_trabajo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_id` (`orden_id`),
  ADD KEY `proyecto_id` (`proyecto_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `departamento_id` (`departamento_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indexes for table `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `ordenes_trabajo`
--
ALTER TABLE `ordenes_trabajo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `cotizacion_id` (`cotizacion_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indexes for table `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_venta` (`venta_id`),
  ADD KEY `fk_item` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias_material`
--
ALTER TABLE `categorias_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categorias_producto`
--
ALTER TABLE `categorias_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categorias_proyecto`
--
ALTER TABLE `categorias_proyecto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cotizaciones`
--
ALTER TABLE `cotizaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_cotizacion_productos`
--
ALTER TABLE `detalle_cotizacion_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_cotizacion_proyectos`
--
ALTER TABLE `detalle_cotizacion_proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_cotizacion_servicios`
--
ALTER TABLE `detalle_cotizacion_servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_orden_trabajo`
--
ALTER TABLE `detalle_orden_trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ordenes_trabajo`
--
ALTER TABLE `ordenes_trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `venta_detalle`
--
ALTER TABLE `venta_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Constraints for table `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD CONSTRAINT `cotizaciones_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Constraints for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`);

--
-- Constraints for table `detalle_cotizacion_productos`
--
ALTER TABLE `detalle_cotizacion_productos`
  ADD CONSTRAINT `detalle_cotizacion_productos_ibfk_1` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_cotizacion_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Constraints for table `detalle_cotizacion_proyectos`
--
ALTER TABLE `detalle_cotizacion_proyectos`
  ADD CONSTRAINT `detalle_cotizacion_proyectos_ibfk_1` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_cotizacion_proyectos_ibfk_2` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`);

--
-- Constraints for table `detalle_cotizacion_servicios`
--
ALTER TABLE `detalle_cotizacion_servicios`
  ADD CONSTRAINT `detalle_cotizacion_servicios_ibfk_1` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_cotizacion_servicios_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Constraints for table `detalle_orden_trabajo`
--
ALTER TABLE `detalle_orden_trabajo`
  ADD CONSTRAINT `detalle_orden_trabajo_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_trabajo` (`id`),
  ADD CONSTRAINT `detalle_orden_trabajo_ibfk_2` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`),
  ADD CONSTRAINT `detalle_orden_trabajo_ibfk_3` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Constraints for table `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  ADD CONSTRAINT `imagenes_producto_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `materiales`
--
ALTER TABLE `materiales`
  ADD CONSTRAINT `materiales_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_material` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`);

--
-- Constraints for table `ordenes_trabajo`
--
ALTER TABLE `ordenes_trabajo`
  ADD CONSTRAINT `ordenes_trabajo_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ordenes_trabajo_ibfk_2` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_producto` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_proyecto` (`id`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Constraints for table `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
