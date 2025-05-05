DROP DATABASE IF EXISTS carpinteria_tfg;
CREATE DATABASE IF NOT EXISTS carpinteria_tfg DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE carpinteria_tfg;

-- 1. MÓDULO DE ROLES Y EMPLEADOS
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    genero VARCHAR(1) NOT NULL,
    salario DECIMAL(10,2),
    telefono VARCHAR(20) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    horario_trabajo VARCHAR(100) NOT NULL,
    fecha_ingreso DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    empleado_id INT UNIQUE,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 2. CLIENTES
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(100),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. SERVICIOS
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_base DECIMAL(10,2),
    unidad VARCHAR(50),
    activo BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. CATEGORÍAS Y PRODUCTOS
CREATE TABLE categorias_producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- 6.1 SOLICITUDES (antes de productos)
CREATE TABLE solicitudes_proyecto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    descripcion TEXT,
    fecha_solicitud DATE NOT NULL,
    estado ENUM('pendiente', 'cotizado', 'aprobado', 'rechazado', 'en_produccion', 'finalizado') DEFAULT 'pendiente',
    estimacion_total DECIMAL(10,2) DEFAULT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    categoria_id INT,
    precio_unitario DECIMAL(10,2),
    stock INT DEFAULT 0,
    solicitud_id INT DEFAULT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias_producto(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (solicitud_id) REFERENCES solicitudes_proyecto(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE imagenes_producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT,
    ruta_imagen VARCHAR(255),
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. MATERIALES
CREATE TABLE materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    unidad_medida VARCHAR(50),
    stock_actual DECIMAL(10,2) DEFAULT 0,
    stock_minimo DECIMAL(10,2) DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 6.2 PROYECTOS
CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    solicitud_id INT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('en diseño', 'en producción', 'finalizado') DEFAULT 'en diseño',
    fecha_inicio DATE,
    fecha_entrega DATE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (solicitud_id) REFERENCES solicitudes_proyecto(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

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

CREATE TABLE movimientos_material (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    motivo TEXT,
    produccion_id INT,
    FOREIGN KEY (material_id) REFERENCES materiales(id),
    FOREIGN KEY (produccion_id) REFERENCES producciones(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. VENTAS
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    metodo_pago VARCHAR(50),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE detalles_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    tipo ENUM('producto', 'servicio') NOT NULL,
    producto_id INT DEFAULT NULL,
    servicio_id INT DEFAULT NULL,
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 8. FACTURAS Y PAGOS
CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT,
    fecha_emision DATE,
    monto_total DECIMAL(10,2),
    estado ENUM('pendiente', 'pagada') DEFAULT 'pendiente',
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    factura_id INT,
    monto_pagado DECIMAL(10,2),
    fecha_pago DATE,
    metodo_pago VARCHAR(50),
    FOREIGN KEY (factura_id) REFERENCES facturas(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 9. COMPRAS Y PROVEEDORES
CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proveedor_id INT,
    fecha DATE,
    total DECIMAL(10,2),
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE detalles_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT,
    material_id INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (compra_id) REFERENCES compras(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materiales(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 10. CONFIGURACIÓN GENERAL
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
