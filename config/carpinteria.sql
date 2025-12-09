-- Ajustes generales
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Roles (seguridad)
CREATE TABLE IF NOT EXISTS roles (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL,
  descripcion VARCHAR(255),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Empleados
CREATE TABLE IF NOT EXISTS empleados (
  id_empleado INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100),
  documento VARCHAR(50),
  telefono VARCHAR(50),
  correo VARCHAR(100),
  direccion VARCHAR(255),
  rol_laboral VARCHAR(100),
  salario_base DECIMAL(13,2) DEFAULT 0.00,
  fecha_contratacion DATE,
  activo TINYINT(1) DEFAULT 1,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuarios (para autenticación)
CREATE TABLE IF NOT EXISTS usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  id_empleado INT NULL,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  ultimo_login TIMESTAMP NULL,
  activo TINYINT(1) DEFAULT 1,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Clientes
CREATE TABLE IF NOT EXISTS clientes (
  id_cliente INT AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('natural','empresa') DEFAULT 'natural',
  nombre VARCHAR(120) NOT NULL,
  apellido VARCHAR(120),
  razon_social VARCHAR(200),
  identificacion VARCHAR(100),
  telefono VARCHAR(80),
  correo VARCHAR(120),
  direccion VARCHAR(255),
  observaciones TEXT,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Proveedores
CREATE TABLE IF NOT EXISTS proveedores (
  id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  contacto VARCHAR(120),
  telefono VARCHAR(80),
  correo VARCHAR(120),
  direccion VARCHAR(255),
  condiciones_pago VARCHAR(100),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categorías de materiales
CREATE TABLE IF NOT EXISTS categorias_materiales (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  descripcion VARCHAR(255),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Materiales (inventario)
CREATE TABLE IF NOT EXISTS materiales (
  id_material INT AUTO_INCREMENT PRIMARY KEY,
  id_categoria INT NULL,
  codigo VARCHAR(80) UNIQUE,
  nombre VARCHAR(200) NOT NULL,
  descripcion TEXT,
  unidad_medida VARCHAR(30) NOT NULL, -- ej. kg, mt, unidad, litro
  stock_minimo DECIMAL(13,3) DEFAULT 0.000,
  stock_actual DECIMAL(13,3) DEFAULT 0.000,
  precio_promedio DECIMAL(13,4) DEFAULT 0.0000,
  activo TINYINT(1) DEFAULT 1,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_categoria) REFERENCES categorias_materiales(id_categoria) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Relación materiales <-> proveedores (precios, código proveedor)
CREATE TABLE IF NOT EXISTS materiales_proveedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_material INT NOT NULL,
  id_proveedor INT NOT NULL,
  codigo_proveedor VARCHAR(120),
  precio_unitario DECIMAL(13,4) DEFAULT 0.0000,
  fecha_ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_material) REFERENCES materiales(id_material) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Movimientos de inventario (entradas, salidas, ajustes)
CREATE TABLE IF NOT EXISTS inventario_movimientos (
  id_movimiento BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_material INT NOT NULL,
  tipo_mov ENUM('entrada','salida','ajuste') NOT NULL,
  referencia_tipo VARCHAR(60), -- ej. 'compra', 'proyecto', 'ajuste'
  referencia_id INT NULL, -- id de la orden/compra/proyecto dependiendo del tipo
  cantidad DECIMAL(13,3) NOT NULL,
  costo_unitario DECIMAL(13,4) DEFAULT 0.0000,
  subtotal DECIMAL(15,4) GENERATED ALWAYS AS (cantidad * costo_unitario) VIRTUAL,
  observaciones TEXT,
  id_usuario INT NULL,
  fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_material) REFERENCES materiales(id_material) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Productos terminados (muebles estándar)
CREATE TABLE IF NOT EXISTS productos (
  id_producto INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(80) UNIQUE,
  nombre VARCHAR(200) NOT NULL,
  descripcion TEXT,
  precio_venta DECIMAL(13,2) DEFAULT 0.00,
  costo_estandar DECIMAL(13,2) DEFAULT 0.00,
  activo TINYINT(1) DEFAULT 1,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Stock de productos (opcional)
CREATE TABLE IF NOT EXISTS productos_stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_producto INT NOT NULL,
  cantidad INT DEFAULT 0,
  ubicacion VARCHAR(120),
  fecha_ultimo_mov TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Imágenes (materiales/productos/proyectos)
CREATE TABLE IF NOT EXISTS archivos (
  id_archivo BIGINT AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('material','producto','proyecto','diseno','otro') DEFAULT 'otro',
  referencia_id INT NULL,
  nombre_original VARCHAR(255),
  ruta VARCHAR(512) NOT NULL,
  descripcion VARCHAR(255),
  fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Servicios (que ofreces a terceros o al cliente final)
CREATE TABLE IF NOT EXISTS servicios (
  id_servicio INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  descripcion TEXT,
  precio_base DECIMAL(13,2) DEFAULT 0.00,
  duracion_estimada_min INT DEFAULT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estados de proyecto
CREATE TABLE IF NOT EXISTS proyectos_estados (
  id_estado INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  descripcion VARCHAR(255),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Proyectos / encargos
CREATE TABLE IF NOT EXISTS proyectos (
  id_proyecto BIGINT AUTO_INCREMENT PRIMARY KEY,
  codigo_proyecto VARCHAR(80) UNIQUE,
  id_cliente INT NULL,
  nombre_proyecto VARCHAR(200) NOT NULL,
  descripcion TEXT,
  tipo ENUM('mueble','servicio','mixto') DEFAULT 'mueble',
  id_estado INT DEFAULT NULL,
  fecha_inicio DATE,
  fecha_estimacion DATE,
  fecha_entrega DATE,
  total_estimado DECIMAL(15,2) DEFAULT 0.00,
  total_real DECIMAL(15,2) DEFAULT 0.00,
  observaciones TEXT,
  id_usuario_creo INT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_estado) REFERENCES proyectos_estados(id_estado) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario_creo) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Diseños / archivos de proyecto
CREATE TABLE IF NOT EXISTS proyectos_disenos (
  id_diseno BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_proyecto BIGINT NOT NULL,
  nombre VARCHAR(200),
  descripcion TEXT,
  id_archivo BIGINT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_archivo) REFERENCES archivos(id_archivo) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Materiales asociados a un proyecto (consumo)
CREATE TABLE IF NOT EXISTS proyectos_materiales (
  id_proyecto_material BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_proyecto BIGINT NOT NULL,
  id_material INT NOT NULL,
  cantidad DECIMAL(13,3) NOT NULL,
  unidad_medida VARCHAR(30),
  costo_unitario DECIMAL(13,4) DEFAULT 0.0000,
  subtotal DECIMAL(15,4) GENERATED ALWAYS AS (cantidad * costo_unitario) VIRTUAL,
  id_movimiento_inventario BIGINT NULL, -- referencia al movimiento si aplica
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_material) REFERENCES materiales(id_material) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (id_movimiento_inventario) REFERENCES inventario_movimientos(id_movimiento) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mano de obra asignada por proyecto
CREATE TABLE IF NOT EXISTS proyectos_mano_obra (
  id_mano_obra BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_proyecto BIGINT NOT NULL,
  id_empleado INT NOT NULL,
  horas_trabajadas DECIMAL(8,2) DEFAULT 0.00,
  costo_hora DECIMAL(13,4) DEFAULT 0.0000,
  subtotal DECIMAL(15,4) GENERATED ALWAYS AS (horas_trabajadas * costo_hora) VIRTUAL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Imágenes / fotos específicas de proyectos
CREATE TABLE IF NOT EXISTS proyectos_imagenes (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_proyecto BIGINT NOT NULL,
  id_archivo BIGINT NOT NULL,
  descripcion VARCHAR(255),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_archivo) REFERENCES archivos(id_archivo) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pagos / abonos por proyecto
CREATE TABLE IF NOT EXISTS proyectos_pagos (
  id_pago BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_proyecto BIGINT NOT NULL,
  monto DECIMAL(15,2) NOT NULL,
  tipo_pago ENUM('anticipo','parcial','final','otros') DEFAULT 'parcial',
  metodo_pago VARCHAR(60),
  referencia VARCHAR(120),
  id_usuario INT NULL,
  fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Facturas de proyectos
CREATE TABLE IF NOT EXISTS proyectos_facturas (
  id_factura BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_proyecto BIGINT NOT NULL,
  numero_factura VARCHAR(120) UNIQUE,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  subtotal DECIMAL(15,2) DEFAULT 0.00,
  impuestos DECIMAL(15,2) DEFAULT 0.00,
  total DECIMAL(15,2) DEFAULT 0.00,
  id_usuario_emitio INT NULL,
  observaciones TEXT,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario_emitio) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Órdenes de compra
CREATE TABLE IF NOT EXISTS ordenes_compra (
  id_orden BIGINT AUTO_INCREMENT PRIMARY KEY,
  codigo_orden VARCHAR(120) UNIQUE,
  id_proveedor INT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('pendiente','recibido','cancelado') DEFAULT 'pendiente',
  total DECIMAL(15,2) DEFAULT 0.00,
  id_usuario_creo INT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario_creo) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Detalle de ordenes de compra
CREATE TABLE IF NOT EXISTS ordenes_compra_detalle (
  id_detalle BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_orden BIGINT NOT NULL,
  id_material INT NOT NULL,
  cantidad DECIMAL(13,3) NOT NULL,
  costo_unitario DECIMAL(13,4) DEFAULT 0.0000,
  subtotal DECIMAL(15,4) GENERATED ALWAYS AS (cantidad * costo_unitario) VIRTUAL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_orden) REFERENCES ordenes_compra(id_orden) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_material) REFERENCES materiales(id_material) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pagos de compras / proveedores
CREATE TABLE IF NOT EXISTS compras_pagos (
  id_pago BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_orden BIGINT NOT NULL,
  monto DECIMAL(15,2) NOT NULL,
  metodo_pago VARCHAR(60),
  fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_usuario INT NULL,
  FOREIGN KEY (id_orden) REFERENCES ordenes_compra(id_orden) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Gastos operativos (combustible, transporte, mantenimiento...)
CREATE TABLE IF NOT EXISTS gastos_operativos (
  id_gasto BIGINT AUTO_INCREMENT PRIMARY KEY,
  descripcion VARCHAR(255),
  monto DECIMAL(15,2) NOT NULL,
  tipo_gasto VARCHAR(100),
  id_usuario_registro INT NULL,
  fecha_gasto TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario_registro) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ventas / facturación general (además de proyectos)
CREATE TABLE IF NOT EXISTS ventas (
  id_venta BIGINT AUTO_INCREMENT PRIMARY KEY,
  codigo_venta VARCHAR(120) UNIQUE,
  id_cliente INT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('pendiente','pagada','anulada') DEFAULT 'pendiente',
  subtotal DECIMAL(15,2) DEFAULT 0.00,
  impuestos DECIMAL(15,2) DEFAULT 0.00,
  total DECIMAL(15,2) DEFAULT 0.00,
  id_usuario_creo INT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario_creo) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Detalle ventas (productos / servicios)
CREATE TABLE IF NOT EXISTS ventas_detalles (
  id_detalle BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_venta BIGINT NOT NULL,
  tipo_item ENUM('producto','servicio','proyecto') DEFAULT 'producto',
  id_producto INT NULL,
  id_servicio INT NULL,
  id_proyecto BIGINT NULL,
  descripcion VARCHAR(255),
  cantidad DECIMAL(13,3) DEFAULT 1.000,
  precio_unitario DECIMAL(15,4) DEFAULT 0.0000,
  subtotal DECIMAL(18,4) GENERATED ALWAYS AS (cantidad * precio_unitario) VIRTUAL,
  FOREIGN KEY (id_venta) REFERENCES ventas(id_venta) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pagos de ventas
CREATE TABLE IF NOT EXISTS ventas_pagos (
  id_pago BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_venta BIGINT NOT NULL,
  monto DECIMAL(15,2) NOT NULL,
  metodo_pago VARCHAR(60),
  referencia VARCHAR(120),
  fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_usuario INT NULL,
  FOREIGN KEY (id_venta) REFERENCES ventas(id_venta) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cotizaciones
CREATE TABLE IF NOT EXISTS cotizaciones (
  id_cotizacion BIGINT AUTO_INCREMENT PRIMARY KEY,
  codigo_cotizacion VARCHAR(120) UNIQUE,
  id_cliente INT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('borrador','enviada','aceptada','rechazada') DEFAULT 'borrador',
  subtotal DECIMAL(15,2) DEFAULT 0.00,
  impuestos DECIMAL(15,2) DEFAULT 0.00,
  total DECIMAL(15,2) DEFAULT 0.00,
  id_usuario_creo INT NULL,
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario_creo) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cotizaciones_detalles (
  id_detalle BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_cotizacion BIGINT NOT NULL,
  tipo_item ENUM('producto','servicio','proyecto') DEFAULT 'producto',
  id_producto INT NULL,
  id_servicio INT NULL,
  id_proyecto BIGINT NULL,
  descripcion VARCHAR(255),
  cantidad DECIMAL(13,3) DEFAULT 1.000,
  precio_unitario DECIMAL(15,4) DEFAULT 0.0000,
  subtotal DECIMAL(18,4) GENERATED ALWAYS AS (cantidad * precio_unitario) VIRTUAL,
  FOREIGN KEY (id_cotizacion) REFERENCES cotizaciones(id_cotizacion) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Órdenes de servicio (otros talleres que solicitan trabajos)
CREATE TABLE IF NOT EXISTS ordenes_servicio (
  id_orden_servicio BIGINT AUTO_INCREMENT PRIMARY KEY,
  codigo_orden VARCHAR(120) UNIQUE,
  id_cliente INT NULL, -- puede ser otra carpintería o cliente
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('pendiente','en_proceso','finalizado','entregado','cancelado') DEFAULT 'pendiente',
  total DECIMAL(15,2) DEFAULT 0.00,
  id_usuario_creo INT NULL,
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario_creo) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ordenes_servicio_detalle (
  id_detalle BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_orden_servicio BIGINT NOT NULL,
  id_servicio INT NOT NULL,
  descripcion VARCHAR(255),
  cantidad DECIMAL(13,3) DEFAULT 1.000,
  precio_unitario DECIMAL(15,4) DEFAULT 0.0000,
  subtotal DECIMAL(18,4) GENERATED ALWAYS AS (cantidad * precio_unitario) VIRTUAL,
  FOREIGN KEY (id_orden_servicio) REFERENCES ordenes_servicio(id_orden_servicio) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pagos de órdenes de servicio
CREATE TABLE IF NOT EXISTS servicios_pagos (
  id_pago BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_orden_servicio BIGINT NOT NULL,
  monto DECIMAL(15,2) NOT NULL,
  metodo_pago VARCHAR(60),
  fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_usuario INT NULL,
  FOREIGN KEY (id_orden_servicio) REFERENCES ordenes_servicio(id_orden_servicio) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Caja diaria
CREATE TABLE IF NOT EXISTS caja_diaria (
  id_caja BIGINT AUTO_INCREMENT PRIMARY KEY,
  fecha DATE NOT NULL,
  saldo_inicial DECIMAL(15,2) DEFAULT 0.00,
  saldo_final DECIMAL(15,2) DEFAULT 0.00,
  id_usuario_apertura INT NULL,
  id_usuario_cierre INT NULL,
  fecha_apertura TIMESTAMP NULL,
  fecha_cierre TIMESTAMP NULL,
  UNIQUE KEY uq_caja_fecha (fecha),
  FOREIGN KEY (id_usuario_apertura) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario_cierre) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Movimientos de caja
CREATE TABLE IF NOT EXISTS caja_movimientos (
  id_movimiento BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_caja BIGINT NOT NULL,
  tipo ENUM('ingreso','egreso') NOT NULL,
  monto DECIMAL(15,2) NOT NULL,
  descripcion VARCHAR(255),
  referencia_tipo VARCHAR(80), -- ej. 'venta','compra','gasto','proyecto'
  referencia_id BIGINT NULL,
  id_usuario_registro INT NULL,
  fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_caja) REFERENCES caja_diaria(id_caja) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario_registro) REFERENCES usuarios(id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- índices básicos y chequeo final
CREATE INDEX idx_material_nombre ON materiales(nombre);
CREATE INDEX idx_producto_nombre ON productos(nombre);
CREATE INDEX idx_proyecto_codigo ON proyectos(codigo_proyecto);
CREATE INDEX idx_ordencompra_codigo ON ordenes_compra(codigo_orden);
CREATE INDEX idx_vent_codigo ON ventas(codigo_venta);

SET FOREIGN_KEY_CHECKS = 1;
