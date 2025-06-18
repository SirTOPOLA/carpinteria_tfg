-- CLIENTES
CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  telefono VARCHAR(20),
  direccion VARCHAR(100),
  email VARCHAR(255),
  codigo_acceso VARCHAR(100),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PROVEEDORES
CREATE TABLE proveedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  contacto VARCHAR(100),
  telefono VARCHAR(20),
  email VARCHAR(100),
  direccion VARCHAR(255)
);

-- MATERIALES
CREATE TABLE materiales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  unidad_medida VARCHAR(50),
  stock_actual INT DEFAULT 0,
  stock_minimo INT DEFAULT 0,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ESTADOS
CREATE TABLE estados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  entidad ENUM('produccion','proyecto','pedido','venta','factura','tareas') NOT NULL
);

-- EMPLEADOS
CREATE TABLE empleados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  genero CHAR(1) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  direccion VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  horario_trabajo VARCHAR(100) NOT NULL,
  fecha_ingreso DATE,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  salario DECIMAL(10,2)
);

-- ROLES
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

-- USUARIOS
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  imagen TEXT,
  rol_id INT NOT NULL,
  activo TINYINT(1) DEFAULT 1,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (rol_id) REFERENCES roles(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

-- SERVICIOS
CREATE TABLE servicios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio_base DECIMAL(10,2),
  unidad VARCHAR(50),
  activo TINYINT(1) DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PRODUCTOS
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  descripcion TEXT,
  imagen TEXT,
  precio_unitario DECIMAL(10,2),
  stock INT DEFAULT 0
);

-- CONFIGURACIÓN
CREATE TABLE configuracion (
  id INT AUTO_INCREMENT PRIMARY KEY,
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

-- COMPRAS
CREATE TABLE compras (
  id INT AUTO_INCREMENT PRIMARY KEY,
  proveedor_id INT NOT NULL,
  fecha DATE,
  total DECIMAL(10,2),
  codigo VARCHAR(100) NOT NULL,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
    ON DELETE CASCADE ON UPDATE CASCADE
);

-- DETALLES COMPRA
CREATE TABLE detalles_compra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  compra_id INT NOT NULL,
  material_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  FOREIGN KEY (compra_id) REFERENCES compras(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (material_id) REFERENCES materiales(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

-- PEDIDOS
 
CREATE TABLE pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  proyecto VARCHAR(100) NOT NULL, 
  descripcion TEXT,
  fecha_solicitud DATE NOT NULL,
  fecha_entrega DATE NOT NULL,
  precio_obra DECIMAL(10,2) DEFAULT 0.00,
  estimacion_total DECIMAL(10,2), 
  estado_id INT NOT NULL,
  requiere_factura TINYINT(1) DEFAULT 1,
  facturado TINYINT(1) DEFAULT 0,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON DELETE CASCADE ON UPDATE CASCADE, 
  FOREIGN KEY (estado_id) REFERENCES estados(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

-- DETALLES PEDIDO MATERIAL
CREATE TABLE detalles_pedido_material (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  material_id INT NOT NULL,
  cantidad INT NOT NULL,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (material_id) REFERENCES materiales(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);


CREATE TABLE detalle_pedido_producto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2),
  desde_stock TINYINT(1) DEFAULT 1,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE detalle_pedido_servicio (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  servicio_id INT NOT NULL,
  cantidad INT DEFAULT 1,
  precio_unitario DECIMAL(10,2),
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);


-- PRODUCCIONES
CREATE TABLE producciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  solicitud_id INT,
  responsable_id INT,
  fecha_inicio DATE,
  fecha_fin DATE,
  estado_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (solicitud_id) REFERENCES pedidos(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (responsable_id) REFERENCES empleados(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

-- DETALLES PRODUCCIÓN
CREATE TABLE detalles_produccion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produccion_id INT NOT NULL,
  producto_id INT NOT NULL,
  descripcion TEXT,
  cantidad INT DEFAULT 1,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

-- TAREAS PRODUCCIÓN
CREATE TABLE tareas_produccion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produccion_id INT NOT NULL,
  descripcion TEXT NOT NULL,
  responsable_id INT,
  estado_id INT NOT NULL,
  fecha_inicio DATE,
  fecha_fin DATE,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (responsable_id) REFERENCES empleados(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

-- AVANCES PRODUCCIÓN
CREATE TABLE avances_produccion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produccion_id INT NOT NULL,
  descripcion TEXT,
  imagen TEXT NOT NULL,
  porcentaje INT DEFAULT 0,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id)
    ON DELETE CASCADE ON UPDATE CASCADE
);

-- MOVIMIENTOS MATERIAL
CREATE TABLE movimientos_material (
  id INT AUTO_INCREMENT PRIMARY KEY,
  material_id INT NOT NULL,
  tipo_movimiento ENUM('entrada','salida') NOT NULL,
  cantidad INT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  motivo TEXT,
  produccion_id INT,
  FOREIGN KEY (material_id) REFERENCES materiales(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id)
    ON DELETE SET NULL ON UPDATE CASCADE
);

-- VENTAS 
CREATE TABLE ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT, 
  pedido_id INT,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2),
  metodo_pago VARCHAR(50),
  observaciones TEXT, -- posi existen entregas parciales
  estado_id INT NOT NULL, --  ENUM('pendiente','pagada','anulada') DEFAULT 'pendiente',
  tipo_venta ENUM('adelanto','completa','parcial') DEFAULT 'completa',
  FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON DELETE SET NULL ON UPDATE CASCADE
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

 


CREATE TABLE detalles_venta_producto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad INT DEFAULT 1,
  precio_unitario DECIMAL(10,2) NOT NULL,
  descuento DECIMAL(10,2) DEFAULT 0.00,
  subtotal DECIMAL(10,2),
  FOREIGN KEY (venta_id) REFERENCES ventas(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE detalles_venta_servicio (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  servicio_id INT NOT NULL,
  cantidad INT DEFAULT 1,
  precio_unitario DECIMAL(10,2) NOT NULL,
  descuento DECIMAL(10,2) DEFAULT 0.00,
  subtotal DECIMAL(10,2),
  FOREIGN KEY (venta_id) REFERENCES ventas(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    ON DELETE SET NULL ON UPDATE CASCADE
);
 

 
CREATE TABLE facturas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  pedido_id INT,
  numero_factura VARCHAR(20) UNIQUE,
  fecha_emision DATE NOT NULL,
  fecha_vencimiento DATE,
  monto_total DECIMAL(10,2) NOT NULL,
  saldo_pendiente DECIMAL(10,2) DEFAULT 0.00,
  iva DECIMAL(10,2) DEFAULT 0.00,
  estado_id INT NOT NULL,
  FOREIGN KEY (venta_id) REFERENCES ventas(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (estado_id) REFERENCES estados(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);



-- PAGOS
CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  factura_id INT NOT NULL,
  monto_pagado DECIMAL(10,2) NOT NULL,
  fecha_pago DATE NOT NULL,
  metodo_pago VARCHAR(50),
  observaciones TEXT,
  usuario_id INT,
  FOREIGN KEY (factura_id) REFERENCES facturas(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON DELETE SET NULL ON UPDATE CASCADE
);


