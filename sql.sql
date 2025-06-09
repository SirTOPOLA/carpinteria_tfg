-- CLIENTES
CREATE TABLE clientes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  codigo VARCHAR(20) UNIQUE NOT NULL,
  telefono VARCHAR(20),
  direccion VARCHAR(100),
  email VARCHAR(255),
  codigo_acceso VARCHAR(100),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- EMPLEADOS
CREATE TABLE empleados (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  codigo VARCHAR(20) UNIQUE NOT NULL,
  genero CHAR(1) NOT NULL,
  telefono VARCHAR(20),
  direccion VARCHAR(100),
  email VARCHAR(100),
  horario_trabajo VARCHAR(100),
  fecha_ingreso DATE,
  salario DECIMAL(10,2),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- USUARIOS
CREATE TABLE usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT UNSIGNED NOT NULL,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  imagen TEXT,
  rol ENUM('administrador', 'operario') NOT NULL,
  activo TINYINT(1) DEFAULT 1,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id)
);

-- PROVEEDORES
CREATE TABLE proveedores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  contacto VARCHAR(100),
  telefono VARCHAR(20),
  email VARCHAR(100),
  direccion VARCHAR(255)
);

-- COMPRAS
CREATE TABLE compras (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  proveedor_id INT UNSIGNED NOT NULL,
  fecha DATE NOT NULL,
  total DECIMAL(10,2),
  codigo VARCHAR(100) UNIQUE NOT NULL,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
);

-- MATERIALES
CREATE TABLE materiales (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  unidad_medida VARCHAR(50),
  stock_actual INT DEFAULT 0,
  stock_minimo INT DEFAULT 0,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DETALLES DE COMPRA
CREATE TABLE detalles_compra (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  compra_id INT UNSIGNED NOT NULL,
  material_id INT UNSIGNED NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (compra_id) REFERENCES compras(id),
  FOREIGN KEY (material_id) REFERENCES materiales(id)
);

-- INVENTARIOS (movimientos de materiales)
CREATE TABLE inventarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  material_id INT UNSIGNED NOT NULL,
  tipo_movimiento ENUM('entrada','salida') NOT NULL,
  cantidad INT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  motivo TEXT,
  produccion_id INT UNSIGNED,
  FOREIGN KEY (material_id) REFERENCES materiales(id),
  FOREIGN KEY (produccion_id) REFERENCES producciones(id)
);

-- PEDIDOS
CREATE TABLE pedidos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT UNSIGNED NOT NULL,
  proyecto VARCHAR(100) NOT NULL,
  piezas INT DEFAULT 1,
  descripcion TEXT,
  fecha_solicitud DATE NOT NULL,
  fecha_entrega DATE NOT NULL,
  precio_obra DECIMAL(10,2) DEFAULT 0.00,
  estimacion_total DECIMAL(10,2),
  adelanto DECIMAL(10,2) DEFAULT 0.00,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

 

-- PRODUCCIONES
CREATE TABLE producciones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT UNSIGNED, 
  fecha_inicio DATE,
  fecha_fin DATE, 
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id), 
  FOREIGN KEY (estado_id) REFERENCES estados_produccion(id)
);

-- PRODUCTOS
CREATE TABLE productos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  imagen TEXT,
  precio_unitario DECIMAL(10,2),
  stock INT DEFAULT 0
);

-- DETALLES DE PRODUCCIÓN (PRODUCTOS A FABRICAR EN UNA PRODUCCIÓN)
CREATE TABLE detalles_produccion (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  produccion_id INT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NOT NULL,
  descripcion TEXT,
  cantidad INT DEFAULT 1,
  FOREIGN KEY (produccion_id) REFERENCES producciones(id),
  FOREIGN KEY (producto_id) REFERENCES productos(id)
);

 

-- VENTAS
CREATE TABLE ventas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT UNSIGNED NOT NULL,
  usuario_id INT UNSIGNED NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2),
  metodo_pago VARCHAR(50),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
  FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- DETALLES DE VENTA
CREATE TABLE detalles_venta (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  venta_id INT UNSIGNED NOT NULL, 
  producto_id INT UNSIGNED, 
  cantidad INT DEFAULT 1,
  precio_unitario DECIMAL(10,2) NOT NULL,
  descuento DECIMAL(10,2) DEFAULT 0.00,
  subtotal DECIMAL(10,2),
  FOREIGN KEY (venta_id) REFERENCES ventas(id),
  FOREIGN KEY (producto_id) REFERENCES productos(id) 
);
