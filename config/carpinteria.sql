

-- --------------------------------------------------------
-- Base de datos: `carpinteria`
-- --------------------------------------------------------

-- Crear la base de datos si no existe y usarla
CREATE DATABASE IF NOT EXISTS carpinteria DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE carpinteria;

-- --------------------------------------------------------
-- MÓDULO DE EMPLEADOS Y ROLES
 
-- --------------------------------------------------------
 
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE -- Ej: administrador, vendedor, operario, etc.
) ENGINE=InnoDB;

-- Tabla: empleados
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

-- (opcional) Tabla: horarios_trabajo
CREATE TABLE horarios_trabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL -- Ej: "Lunes a Viernes 9-18h"
) ENGINE=InnoDB;

-- Tabla: usuarios del sistema (gestión de acceso)
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
-- MÓDULO DE CLIENTES Y CRM
-- --------------------------------------------------------

-- Tabla: clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,                   -- ID único para el cliente
    nombre VARCHAR(100) NOT NULL,                         -- Nombre del cliente
    email VARCHAR(100) UNIQUE,                            -- Email único del cliente
    telefono VARCHAR(20),                                 -- Teléfono del cliente
    direccion VARCHAR(255),                               -- Dirección del cliente
    fecha_registro DATE,                                  -- Fecha en que el cliente fue registrado
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP        -- Fecha de creación (registro)
) ENGINE=InnoDB;

-- Tabla: crm_actividades (actividades de clientes)
CREATE TABLE crm_actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para cada actividad
    cliente_id INT,                                       -- Relación con la tabla clientes
    tipo_actividad VARCHAR(100),                          -- Tipo de actividad (Ej: llamada, reunión)
    descripcion TEXT,                                     -- Descripción de la actividad
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,            -- Fecha de la actividad
    responsable_id INT,                                   -- Relación con la tabla empleados (quién realizó la actividad)
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (responsable_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- MÓDULO DE PROYECTOS Y PRODUCCIÓN
-- --------------------------------------------------------

-- Tabla: proyectos
CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,                   -- ID único para el proyecto
    cliente_id INT,                                       -- Relación con la tabla clientes
    nombre VARCHAR(100) NOT NULL,                         -- Nombre del proyecto
    descripcion TEXT,                                     -- Descripción detallada del proyecto
    estado ENUM('en diseño', 'en producción', 'finalizado') DEFAULT 'en diseño', -- Estado del proyecto
    fecha_inicio DATE,                                    -- Fecha de inicio del proyecto
    fecha_entrega DATE,                                   -- Fecha estimada de entrega
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,       -- Fecha de creación del proyecto
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: producciones
CREATE TABLE producciones (
    id INT AUTO_INCREMENT PRIMARY KEY,                   -- ID único para cada producción
    proyecto_id INT,                                      -- Relación con la tabla proyectos
    fecha_inicio DATE,                                    -- Fecha de inicio de la producción
    fecha_fin DATE,                                       -- Fecha de fin de la producción
    estado ENUM('pendiente', 'en proceso', 'terminado') DEFAULT 'pendiente', -- Estado de la producción
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,       -- Fecha de creación de la producción
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: procesos_produccion (etapas de producción)
CREATE TABLE procesos_produccion (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para cada proceso
    produccion_id INT NOT NULL,                           -- Relación con la tabla producciones
    etapa VARCHAR(100),                                   -- Etapa del proceso (Ej: corte, ensamblaje)
    fecha_inicio DATE,                                    -- Fecha de inicio de la etapa
    fecha_fin DATE,                                       -- Fecha de fin de la etapa
    responsable_id INT,                                   -- Relación con la tabla empleados (quién está encargado)
    FOREIGN KEY (produccion_id) REFERENCES producciones(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (responsable_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- MÓDULO DE PRODUCTOS Y VENTAS
-- --------------------------------------------------------

-- Tabla: categorias_producto
CREATE TABLE categorias_producto (
    id INT AUTO_INCREMENT PRIMARY KEY,                   -- ID único para la categoría
    nombre VARCHAR(100) NOT NULL                          -- Nombre de la categoría (Ej: madera, herramienta)
) ENGINE=InnoDB;

-- Tabla: productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,                   -- ID único para el producto
    nombre VARCHAR(100),                                  -- Nombre del producto
    descripcion TEXT,                                     -- Descripción detallada del producto
    categoria_id INT,                                     -- Relación con la tabla categorias_producto
    precio_unitario DECIMAL(10,2),                        -- Precio unitario del producto
    stock INT DEFAULT 0,                                  -- Cantidad disponible en inventario
    FOREIGN KEY (categoria_id) REFERENCES categorias_producto(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: ventas
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para cada venta
    cliente_id INT,                                       -- Relación con la tabla clientes
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,            -- Fecha de la venta
    total DECIMAL(10,2),                                  -- Total de la venta
    metodo_pago VARCHAR(50),                              -- Método de pago (Ej: tarjeta, efectivo)
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: detalles_venta (detalles de los productos vendidos)
CREATE TABLE detalles_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para cada detalle de venta
    venta_id INT NOT NULL,                                -- Relación con la tabla ventas
    tipo ENUM('producto', 'servicio') NOT NULL,           -- Tipo de detalle (producto o servicio)
    producto_id INT DEFAULT NULL,                         -- Relación con la tabla productos (si es un producto)
    servicio_id INT DEFAULT NULL,                         -- Relación con la tabla servicios (si es un servicio)
    cantidad INT DEFAULT 1,                               -- Cantidad vendida
    precio_unitario DECIMAL(10,2) NOT NULL,               -- Precio unitario
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED, -- Subtotal generado automáticamente
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- MÓDULO DE MATERIALES
-- --------------------------------------------------------

-- Tabla: materiales
CREATE TABLE materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,                   -- ID único para cada material
    nombre VARCHAR(100) NOT NULL,                         -- Nombre del material
    descripcion TEXT,                                     -- Descripción del material
    unidad_medida VARCHAR(50),                            -- Unidad de medida (Ej: kg, m2, litros, unidades)
    stock_actual DECIMAL(10,2) DEFAULT 0,                 -- Stock disponible actualmente
    stock_minimo DECIMAL(10,2) DEFAULT 0,                 -- Stock mínimo permitido
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP        -- Fecha de creación
) ENGINE=InnoDB;

-- Tabla: movimientos_material
CREATE TABLE movimientos_material (
    id INT AUTO_INCREMENT PRIMARY KEY,                   -- ID único para cada movimiento de material
    material_id INT NOT NULL,                             -- Relación con la tabla materiales
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,  -- Tipo de movimiento (entrada o salida)
    cantidad DECIMAL(10,2) NOT NULL,                      -- Cantidad movida
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,            -- Fecha del movimiento
    motivo TEXT,                                          -- Motivo del movimiento
    FOREIGN KEY (material_id) REFERENCES materiales(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- MÓDULO DE SERVICIOS
-- --------------------------------------------------------

-- Tabla: servicios
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para cada servicio
    nombre VARCHAR(100) NOT NULL,                          -- Nombre del servicio
    descripcion TEXT,                                     -- Descripción detallada del servicio
    precio_base DECIMAL(10,2),                            -- Precio base del servicio
    unidad VARCHAR(50),                                   -- Unidad de medida (Ej: por hora, por servicio)
    activo BOOLEAN DEFAULT TRUE,                          -- Estado del servicio (activo o inactivo)
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP    -- Fecha de creación
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- OTRAS TABLAS DE VENTAS Y COMPRAS
-- --------------------------------------------------------

-- Tabla: facturas
CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para la factura
    venta_id INT,                                         -- Relación con la tabla ventas
    fecha_emision DATE,                                   -- Fecha de emisión de la factura
    monto_total DECIMAL(10,2),                            -- Monto total de la factura
    estado ENUM('pendiente', 'pagada') DEFAULT 'pendiente', -- Estado de la factura
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: pagos
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para el pago
    factura_id INT,                                       -- Relación con la tabla facturas
    monto_pagado DECIMAL(10,2),                           -- Monto pagado
    fecha_pago DATE,                                      -- Fecha del pago
    metodo_pago VARCHAR(50),                              -- Método de pago (Ej: tarjeta, efectivo)
    FOREIGN KEY (factura_id) REFERENCES facturas(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: proveedores
CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para cada proveedor
    nombre VARCHAR(100),                                  -- Nombre del proveedor
    contacto VARCHAR(100),                                -- Persona de contacto
    telefono VARCHAR(20),                                 -- Teléfono del proveedor
    email VARCHAR(100),                                   -- Email del proveedor
    direccion VARCHAR(255)                                -- Dirección del proveedor
) ENGINE=InnoDB;

-- Tabla: compras
CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para cada compra
    proveedor_id INT,                                     -- Relación con la tabla proveedores
    fecha DATE,                                           -- Fecha de la compra
    total DECIMAL(10,2),                                  -- Total de la compra
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: detalles_compra
CREATE TABLE detalles_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,                    -- ID único para cada detalle de compra
    compra_id INT,                                        -- Relación con la tabla compras
    producto_id INT,                                      -- Relación con la tabla productos
    cantidad INT,                                         -- Cantidad de productos
    precio_unitario DECIMAL(10,2),                        -- Precio unitario del producto
    FOREIGN KEY (compra_id) REFERENCES compras(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;
