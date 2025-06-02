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
 
-- ===============================
-- CONFIGURACIÓN GLOBAL
-- ===============================
CREATE TABLE configuracion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_empresa VARCHAR(100),
  logo_empresa VARCHAR(255),
  direccion VARCHAR(255),
  telefono VARCHAR(20),
  correo VARCHAR(100),
  dni VARCHAR(20),
  porcentaje_iva DECIMAL(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- CLIENTES
-- ===============================
CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  telefono VARCHAR(20),
  direccion VARCHAR(100),
  email VARCHAR(255),
  codigo_acceso VARCHAR(100),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- EMPLEADOS
-- ===============================
CREATE TABLE empleados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  genero VARCHAR(1) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  direccion VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  horario_trabajo VARCHAR(100) NOT NULL,
  fecha_ingreso DATE,
  salario DECIMAL(10,2),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- ESTADOS GENERALES
-- ===============================
CREATE TABLE estados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE roles (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
empleado_id INT NOT NULL,
username VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
rol_id INT NOT NULL,
activo TINYINT,
CONSTRAINT fk_usuarios_empleado FOREIGN KEY (empleado_id)
REFERENCES empleados(id)
ON DELETE CASCADE
ON UPDATE CASCADE,
CONSTRAINT fk_usuarios_rol FOREIGN KEY (rol_id)
REFERENCES roles(id)
ON DELETE RESTRICT
ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE permisos (
id INT AUTO_INCREMENT PRIMARY KEY,
rol_id INT NOT NULL,
modulo VARCHAR(100) NOT NULL,
puede_ver BOOLEAN DEFAULT FALSE,
puede_editar BOOLEAN DEFAULT FALSE,
puede_eliminar BOOLEAN DEFAULT FALSE,
CONSTRAINT fk_permisos_rol FOREIGN KEY (rol_id)
REFERENCES roles(id)
ON DELETE CASCADE
ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- ===============================
-- SERVICIOS
-- ===============================
CREATE TABLE servicios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio_base DECIMAL(10,2),
  unidad VARCHAR(50),
  activo TINYINT(1) DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- MATERIALES
-- ===============================
CREATE TABLE materiales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  unidad_medida VARCHAR(50),
  stock_actual INT DEFAULT 0,
  stock_minimo INT DEFAULT 0,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE proveedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) DEFAULT NULL,
  contacto VARCHAR(100) DEFAULT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  direccion VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE compras (
  id INT AUTO_INCREMENT PRIMARY KEY,
  proveedor_id INT NOT NULL,
  fecha DATE DEFAULT NULL,
  total DECIMAL(10,2) DEFAULT NULL,
  codigo VARCHAR(100) NOT NULL,
  CONSTRAINT fk_compras_proveedor FOREIGN KEY (proveedor_id)
    REFERENCES proveedores(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE detalles_compra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  compra_id INT NOT NULL,
  material_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  CONSTRAINT fk_detallescompra_compra FOREIGN KEY (compra_id)
    REFERENCES compras(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_detallescompra_material FOREIGN KEY (material_id)
    REFERENCES materiales(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- SOLICITUDES DE PROYECTO
-- ===============================
CREATE TABLE pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  proyecto VARCHAR(100) NOT NULL,
  servicio_id INT,
  descripcion TEXT,
  fecha_solicitud DATE NOT NULL,
  fecha_entrega DATE NOT NULL,
  precio_obra DECIMAL(10,2) DEFAULT 0.00,
  estimacion_total DECIMAL(10,2),
  estado_id INT NOT NULL,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- DETALLES DE MATERIALES SOLICITADOS
-- ===============================
CREATE TABLE detalles_solicitud_material (
  id INT AUTO_INCREMENT PRIMARY KEY,
  solicitud_id INT NOT NULL,
  material_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED,
  FOREIGN KEY (solicitud_id) REFERENCES solicitudes_proyecto(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (material_id) REFERENCES materiales(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- PRODUCCIONES
-- ===============================
CREATE TABLE producciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  solicitud_id INT,
  responsable_id INT,
  fecha_inicio DATE,
  fecha_fin DATE,
  estado_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (solicitud_id) REFERENCES solicitudes_proyecto(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (responsable_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- TAREAS DE PRODUCCIÓN
-- ===============================
CREATE TABLE tareas_produccion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produccion_id INT NOT NULL,
  descripcion TEXT NOT NULL,
  responsable_id INT,
  estado_id INT NOT NULL,
  fecha_inicio DATE,
  fecha_fin DATE,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (responsable_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- MOVIMIENTOS DE MATERIALES
-- ===============================
CREATE TABLE movimientos_material (
  id INT AUTO_INCREMENT PRIMARY KEY,
  material_id INT NOT NULL,
  tipo_movimiento ENUM('entrada','salida') NOT NULL,
  cantidad INT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  motivo TEXT,
  produccion_id INT,
  FOREIGN KEY (material_id) REFERENCES materiales(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- PRODUCTOS
-- ===============================
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  descripcion TEXT,
  precio_unitario DECIMAL(10,2),
  stock INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- VENTAS
-- ===============================
 
CREATE TABLE ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT, -- puede ser NULL si no se desea registrar
  nombre_cliente VARCHAR(100), -- opcional si cliente_id está presente
  dni_cliente VARCHAR(20),     -- opcional si cliente_id está presente
  direccion_cliente VARCHAR(255),
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2),
  metodo_pago VARCHAR(50),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- DETALLES DE VENTA
-- ===============================
CREATE TABLE detalles_venta (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  tipo ENUM('producto','servicio') NOT NULL,
  producto_id INT,
  servicio_id INT,
  cantidad INT DEFAULT 1,
  precio_unitario DECIMAL(10,2) NOT NULL,
  descuento DECIMAL(10,2) DEFAULT 0.00,
  subtotal DECIMAL(10,2),
  FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- FACTURAS
-- ===============================
CREATE TABLE facturas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  fecha_emision DATE NOT NULL,
  monto_total DECIMAL(10,2) NOT NULL,
  saldo_pendiente DECIMAL(10,2) DEFAULT 0.00,
  estado_id INT NOT NULL,
  FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===============================
-- PAGOS
-- ===============================
CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  factura_id INT NOT NULL,
  monto_pagado DECIMAL(10,2) NOT NULL,
  fecha_pago DATE NOT NULL,
  metodo_pago VARCHAR(50),
  referencia_pago VARCHAR(100),
  observaciones TEXT,
  FOREIGN KEY (factura_id) REFERENCES facturas(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

