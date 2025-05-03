SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Módulo de Roles
-- --------------------------------------------------------
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE -- Ej: administrador, vendedor, operario, etc.
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Módulo de Empleados
-- --------------------------------------------------------
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    email VARCHAR(100) UNIQUE NOT NULL,
    salario DECIMAL(10,2) DEFAULT 0.00,
    horario_trabajo_id INT, -- relación a tabla de horarios normalizada (opcional)
    fecha_ingreso DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de Horarios de Trabajo (opcional)
CREATE TABLE horarios_trabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL -- Ej: "Lunes a Viernes 9-18h"
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Módulo de Usuarios
-- --------------------------------------------------------
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    empleado_id INT UNIQUE, -- cada empleado puede tener solo un usuario
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Módulo de Categorías de Materiales
-- --------------------------------------------------------
CREATE TABLE categorias_materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT DEFAULT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla categorias_materiales
INSERT INTO categorias_materiales (id, nombre, descripcion, fecha_creacion) VALUES
(1, 'Maderas', 'Madera maciza, laminada y reciclada', '2025-04-11 10:59:27'),
(2, 'Tableros y derivados', 'MDF, Melamina, Triplay, Aglomerado, OSB', '2025-04-11 10:59:27'),
(3, 'Herrajes y accesorios', 'Bisagras, jaladeras, correderas, cerraduras, soportes', '2025-04-11 10:59:27'),
(4, 'Adhesivos y pegamentos', 'Cola blanca, pegamento de contacto, siliconas, resinas', '2025-04-11 10:59:27'),
(5, 'Acabados y pinturas', 'Barnices, selladores, lacas, tintes y aceites', '2025-04-11 10:59:27'),
(6, 'Abrasivos y lijas', 'Lijas de diversos granos, discos y esponjas abrasivas', '2025-04-11 10:59:27'),
(7, 'Material eléctrico', 'Tiras LED, conectores, interruptores para muebles', '2025-04-11 10:59:27'),
(8, 'Consumibles de maquinaria', 'Discos de corte, brocas, hojas de sierra, clavos y tornillos', '2025-04-11 10:59:27'),
(9, 'Empaque y embalaje', 'Cajas, plásticos protectores, cintas', '2025-04-11 10:59:27'),
(10, 'Otros materiales auxiliares', 'Molduras, espumas, plantillas, rellenos tapizados', '2025-04-11 10:59:27');

-- --------------------------------------------------------
-- Módulo de Categorías de Productos
-- --------------------------------------------------------
CREATE TABLE categorias_producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT DEFAULT NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla categorias_producto
INSERT INTO categorias_producto (id, nombre, descripcion, fecha_creacion) VALUES
(1, 'Muebles para el hogar', 'Incluye camas, mesas, sillas, closets y demás mobiliario para uso residencial.', '2025-04-11 09:51:16'),
(2, 'Muebles de oficina', 'Escritorios, archivadores, bibliotecas, estaciones de trabajo y sillas.', '2025-04-11 09:51:16'),
(3, 'Puertas y ventanas', 'Fabricación de puertas macizas, corredizas, batientes y ventanas de madera.', '2025-04-11 09:51:16'),
(4, 'Cocinas y alacenas', 'Gabinetes, muebles modulares y alacenas a medida.', '2025-04-11 09:51:16'),
(5, 'Closets y roperos', 'Sistemas de almacenamiento personalizados para dormitorios.', '2025-04-11 09:51:16'),
(6, 'Estanterías y repisas', 'Repisas decorativas, libreros y estantes funcionales.', '2025-04-11 09:51:16'),
(7, 'Cabeceras y respaldos', 'Diseños tapizados o en madera maciza para camas.', '2025-04-11 09:51:16'),
(8, 'Muebles de baño', 'Vanities, gabinetes y muebles auxiliares para baño.', '2025-04-11 09:51:16'),
(9, 'Paneles decorativos', 'Paneles tallados, revestimientos de pared y plafones de diseño.', '2025-04-11 09:51:16'),
(10, 'Juguetes y mobiliario infantil', 'Mesitas, cunas, sillas, estanterías y juguetes de madera.', '2025-04-11 09:51:16'),
(11, 'Muebles exteriores', 'Mesas de jardín, bancos, pérgolas y decks para exteriores.', '2025-04-11 09:51:16'),
(12, 'Accesorios y complementos', 'Bandejas, organizadores, cajones y piezas decorativas.', '2025-04-11 09:51:16'),
(13, 'Muebles personalizados', 'Diseños únicos hechos a pedido del cliente.', '2025-04-11 09:51:16');

-- --------------------------------------------------------
-- Módulo de Producción
-- --------------------------------------------------------
CREATE TABLE producciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT,
    fecha_inicio DATE,
    fecha_fin DATE,
    estado ENUM('pendiente', 'en proceso', 'terminado') DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE procesos_produccion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produccion_id INT NOT NULL,
    etapa VARCHAR(100),
    fecha_inicio DATE,
    fecha_fin DATE,
    responsable_id INT,
    FOREIGN KEY (produccion_id) REFERENCES producciones(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (responsable_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Módulo de Clientes
-- --------------------------------------------------------
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255) DEFAULT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    correo VARCHAR(100) DEFAULT NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Módulo de Compras
-- --------------------------------------------------------
CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proveedor_id INT NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    total DECIMAL(10,2) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE detalles_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT,
    producto_id INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (compra_id) REFERENCES compras(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Módulo de Configuración
-- --------------------------------------------------------
CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(100) DEFAULT NULL,
    direccion VARCHAR(255) DEFAULT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    correo VARCHAR(100) DEFAULT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    iva DECIMAL(5,2) DEFAULT NULL,
    moneda VARCHAR(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
