-- Script SQL actualizado y normalizado con claves foráneas y ON DELETE/UPDATE coherentes

-- Tabla de configuración general
CREATE TABLE configuracion (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre_empresa VARCHAR(100),
  direccion VARCHAR(255),
  telefono VARCHAR(20),
  correo VARCHAR(100),
  logo VARCHAR(255),
  iva DECIMAL(5,2),
  nif VARCHAR(10),
  moneda VARCHAR(10),
  imagen TEXT,
  vision TEXT,
  mision TEXT,
  historia TEXT
);

-- Estados del sistema (para pedidos, usuarios, etc.)
 
CREATE TABLE estados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL, 
  entidad ENUM('produccion', 'proyecto', 'pedido', 'venta', 'factura') NOT NULL
);


-- Roles del sistema
CREATE TABLE roles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL
);

-- Empleados
CREATE TABLE empleados (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  codigo VARCHAR(20) NOT NULL,
  genero VARCHAR(1) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  direccion VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  horario_trabajo VARCHAR(100) NOT NULL,
  fecha_ingreso DATE,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  salario DECIMAL(10,2)
);

-- Usuarios
CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  empleado_id INT NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  imagen text DEFAULT NULL,
  rol_id INT NOT NULL,
 `activo` tinyint(1) DEFAULT 1,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  
);

-- Servicios
CREATE TABLE servicios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio_base DECIMAL(10,2),
  unidad VARCHAR(50),
  activo TINYINT(1) DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Productos
CREATE TABLE productos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100),
  descripcion TEXT,
  imagen TEXT,
  precio_unitario DECIMAL(10,2),
  stock INT DEFAULT 0
);

-- Materiales
CREATE TABLE materiales (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  unidad_medida VARCHAR(50),
  stock_actual INT DEFAULT 0,
  stock_minimo INT DEFAULT 0,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Proveedores
CREATE TABLE proveedores (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100),
  contacto VARCHAR(100),
  telefono VARCHAR(20),
  email VARCHAR(100),
  direccion VARCHAR(255)
);

-- Compras
CREATE TABLE compras (
  id INT PRIMARY KEY AUTO_INCREMENT,
  proveedor_id INT NOT NULL,
  fecha DATE,
  total DECIMAL(10,2),
  codigo VARCHAR(100) NOT NULL,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Detalles de compra
CREATE TABLE detalles_compra (
  id INT PRIMARY KEY AUTO_INCREMENT,
  compra_id INT NOT NULL,
  material_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  FOREIGN KEY (compra_id) REFERENCES compras(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (material_id) REFERENCES materiales(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Clientes
CREATE TABLE clientes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  telefono VARCHAR(20),
  direccion VARCHAR(100),
  email VARCHAR(255),
  codigo_acceso VARCHAR(100),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pedidos
CREATE TABLE pedidos (
  id INT PRIMARY KEY AUTO_INCREMENT,
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
);

-- Detalles de pedido de material
CREATE TABLE detalles_pedido_material (
  id INT PRIMARY KEY AUTO_INCREMENT,
  pedido_id INT NOT NULL,
  material_id INT NOT NULL,
  cantidad INT NOT NULL,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (material_id) REFERENCES materiales(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Producciones
CREATE TABLE producciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  solicitud_id INT,
  responsable_id INT,
  fecha_inicio DATE,
  fecha_fin DATE,
  estado_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (solicitud_id) REFERENCES pedidos(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (responsable_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tareas de producción
CREATE TABLE tareas_produccion (
  id INT PRIMARY KEY AUTO_INCREMENT,
  produccion_id INT NOT NULL,
  descripcion TEXT NOT NULL,
  responsable_id INT,
  estado_id INT NOT NULL,
  fecha_inicio DATE,
  fecha_fin DATE,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (responsable_id) REFERENCES empleados(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Movimientos de materiales
CREATE TABLE movimientos_material (
  id INT PRIMARY KEY AUTO_INCREMENT,
  material_id INT NOT NULL,
  tipo_movimiento ENUM('entrada','salida') NOT NULL,
  cantidad INT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  motivo TEXT,
  produccion_id INT,
  FOREIGN KEY (material_id) REFERENCES materiales(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id) ON DELETE SET NULL ON UPDATE CASCADE
);
CREATE TABLE avances_produccion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produccion_id INT NOT NULL,
  descripcion TEXT,
  imagen TEXT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
  FOREIGN KEY (produccion_id) REFERENCES producciones(id) ON DELETE CASCADE ON UPDATE CASCADE
   );

-- Ventas
CREATE TABLE ventas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  cliente_id INT,
  nombre_cliente VARCHAR(100),
  dni_cliente VARCHAR(20),
  direccion_cliente VARCHAR(255),
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2),
  metodo_pago VARCHAR(50),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Detalles de venta
CREATE TABLE detalles_venta (
  id INT PRIMARY KEY AUTO_INCREMENT,
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
);

-- Facturas
CREATE TABLE facturas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  venta_id INT NOT NULL,
  fecha_emision DATE NOT NULL,
  monto_total DECIMAL(10,2) NOT NULL,
  saldo_pendiente DECIMAL(10,2) DEFAULT 0.00,
  estado_id INT NOT NULL,
  FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Pagos
CREATE TABLE pagos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  factura_id INT NOT NULL,
  monto_pagado DECIMAL(10,2) NOT NULL,
  fecha_pago DATE NOT NULL,
  metodo_pago VARCHAR(50),
  observaciones TEXT,
  FOREIGN KEY (factura_id) REFERENCES facturas(id) ON DELETE CASCADE ON UPDATE CASCADE
);
