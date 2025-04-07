

DROP DATABASE carpinteria_tfg;

CREATE DATABASE IF NOT EXISTS carpinteria_tfg;
USE carpinteria_tfg;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE 
 
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id) 
);
 
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO clientes (nombre, direccion, telefono, correo) VALUES
('Juan Pérez', 'Av. Libertad 123, Lima', '987654321', 'juan.perez@gmail.com'),
('María García', 'Calle Falsa 456, Arequipa', '945672312', 'maria.garcia@hotmail.com'),
('Constructora Solidez', 'Jr. Ingeniería 345, Trujillo', '951234567', 'contacto@solidez.com'),
('Arquitectura Moderna SAC', 'Av. Reforma 789, Cusco', '968342115', 'ventas@moderna.com'),
('Carlos López', 'Mz. A Lt. 8, Chiclayo', '987001122', 'carlos.lopez@yahoo.com'),
('Muebles del Sur', 'Carretera Panamericana km 45, Tacna', '936745231', 'info@muebledelsur.pe'),
('Ana Torres', 'Urb. Primavera 88, Ica', '980123456', 'ana.torres@gmail.com'),
('Empresa Inmobiliaria Real', 'Av. Central 150, Piura', '972563478', 'inmobiliaria@real.pe'),
('Luis Fernández', 'Calle Colón 321, Huancayo', '989654321', 'luis.fernandez@hotmail.com'),
('Muebles y Estilo EIRL', 'Av. América Sur 101, Cajamarca', '965321478', 'ventas@mueblesyestilo.pe');



CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL, 
    telefono VARCHAR(20),
    correo VARCHAR(100),
    direccion VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO proveedores (nombre, telefono, correo, direccion) VALUES
('Maderas del Norte SAC', '987654321', 'ventas@maderasnorte.pe', 'Av. Los Álamos 123, Lima'),
('Proveedoras Andinas SRL', '945672312', 'contacto@andinas.com', 'Jr. Comercio 456, Cusco'),
('Maderera El Roble', '951234567', 'info@elroble.pe', 'Av. Forestal 789, Arequipa'),
('Distribuidora Carpintera S.A.C.', '968342115', 'ventas@carpintera.com', 'Calle Industrial 111, Trujillo'),
('Ferretería San José', '987001122', 'ferreteria@sanjose.com', 'Mz. D Lt. 5, Chiclayo'),
('Grupo Cedro y Nogal', '936745231', 'cedronogal@grupo.com', 'Carretera Central km 12, Huancayo'),
('Importadora de Maderas del Sur', '980123456', 'importaciones@maderassur.pe', 'Av. Panamericana Sur 301, Ica'),
('Industria Maderera Andina', '972563478', 'industria@andina.pe', 'Parque Industrial, Piura'),
('Maderas Selectas EIRL', '989654321', 'ventas@maderasselectas.pe', 'Av. Las Palmeras 321, Cajamarca'),
('Suministros Carpinteros SAC', '965321478', 'suministros@carpinteros.pe', 'Av. Los Talladores 555, Tacna');

 
 
CREATE TABLE categorias_materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);
 
INSERT INTO categorias_material (nombre, descripcion, fecha_creacion) VALUES
('Maderas', 'Madera maciza, laminada y reciclada', NOW()),
('Tableros y derivados', 'MDF, Melamina, Triplay, Aglomerado, OSB', NOW()),
('Herrajes y accesorios', 'Bisagras, jaladeras, correderas, cerraduras, soportes', NOW()),
('Adhesivos y pegamentos', 'Cola blanca, pegamento de contacto, siliconas, resinas', NOW()),
('Acabados y pinturas', 'Barnices, selladores, lacas, tintes y aceites', NOW()),
('Abrasivos y lijas', 'Lijas de diversos granos, discos y esponjas abrasivas', NOW()),
('Material eléctrico', 'Tiras LED, conectores, interruptores para muebles', NOW()),
('Consumibles de maquinaria', 'Discos de corte, brocas, hojas de sierra, clavos y tornillos', NOW()),
('Empaque y embalaje', 'Cajas, plásticos protectores, cintas', NOW()),
('Otros materiales auxiliares', 'Molduras, espumas, plantillas, rellenos tapizados', NOW());


 
CREATE TABLE materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    categoria_id INT NOT NULL,
    unidad_medida VARCHAR(50),
    stock_actual DECIMAL(10, 2) DEFAULT 0,
    stock_minimo DECIMAL(10, 2) DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias_materiales(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);
  
-- MADERAS (categoria_id = 1)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Madera Pino', 'Madera blanda usada en estructuras y muebles', 1, 'm³', 3.5, 1.0, NOW()),
('Madera Cedro', 'Madera resistente y decorativa para acabados', 1, 'm³', 2.0, 0.5, NOW()),
('Madera Nogal', 'Madera dura para muebles finos', 1, 'm³', 1.2, 0.3, NOW());

-- TABLEROS Y DERIVADOS (categoria_id = 2)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('MDF 15mm', 'Tablero MDF de 15mm para mobiliario', 2, 'unidad', 50, 10, NOW()),
('Melamina Blanco 18mm', 'Tablero melamínico blanco 18mm', 2, 'unidad', 40, 10, NOW()),
('Triplay 9mm', 'Triplay de pino 9mm', 2, 'unidad', 30, 5, NOW());

-- HERRAJES Y ACCESORIOS (categoria_id = 3)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Bisagra cazoleta 35mm', 'Bisagra para puertas de muebles', 3, 'paquete', 100, 20, NOW()),
('Corredera telescópica 40cm', 'Corredera metálica para cajones', 3, 'par', 80, 20, NOW()),
('Jaladera tipo barra', 'Jaladera metálica para mueble', 3, 'unidad', 120, 30, NOW());

-- ADHESIVOS Y PEGAMENTOS (categoria_id = 4)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Cola blanca 1L', 'Adhesivo PVA para madera', 4, 'litro', 25, 5, NOW()),
('Silicona transparente', 'Silicona multiuso para ensamblajes', 4, 'unidad', 40, 10, NOW());

-- ACABADOS Y PINTURAS (categoria_id = 5)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Barniz Poliuretano', 'Barniz brillante para madera', 5, 'litro', 15, 3, NOW()),
('Tinte caoba', 'Tinte color caoba para acabados', 5, 'litro', 10, 2, NOW());

-- ABRASIVOS Y LIJAS (categoria_id = 6)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Lija grano 120', 'Lija fina para acabados', 6, 'paquete', 30, 5, NOW()),
('Disco abrasivo 115mm', 'Disco para esmeril angular', 6, 'unidad', 20, 5, NOW());

-- MATERIAL ELÉCTRICO (categoria_id = 7)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Tira LED 5m', 'Iluminación para muebles y vitrinas', 7, 'unidad', 15, 5, NOW()),
('Interruptor empotrado', 'Interruptor pequeño para muebles', 7, 'unidad', 25, 5, NOW());

-- CONSUMIBLES DE MAQUINARIA (categoria_id = 8)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Disco Sierra Circular 10"', 'Disco de 80 dientes para corte fino', 8, 'unidad', 10, 2, NOW()),
('Clavos 1”', 'Clavos de acero para pistola neumática', 8, 'caja', 50, 10, NOW());

-- EMPAQUE Y EMBALAJE (categoria_id = 9)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Caja cartón reforzada', 'Caja para empaque de muebles', 9, 'unidad', 40, 10, NOW()),
('Film plástico estirable', 'Protección contra polvo y humedad', 9, 'rollo', 15, 5, NOW());

-- OTROS MATERIALES AUXILIARES (categoria_id = 10)
INSERT INTO materiales (nombre, descripcion, categoria_id, unidad_medida, stock_actual, stock_minimo, fecha_creacion) VALUES
('Espuma para tapizado', 'Espuma de alta densidad para cojines', 10, 'm²', 20, 5, NOW()),
('Plantilla de corte', 'Plantilla para armado de piezas repetitivas', 10, 'unidad', 10, 2, NOW());



CREATE TABLE movimientos_inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    tipo ENUM('entrada', 'salida') NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    motivo VARCHAR(255),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materiales(id)
);

 
CREATE TABLE categorias_proyecto  (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categorias_proyecto  (nombre, descripcion) VALUES
('Muebles de hogar', 'Proyectos como closets, camas, mesas, sillas y repisas para uso doméstico.'),
('Muebles de oficina', 'Fabricación de escritorios, estanterías, archivos y divisiones para oficinas.'),
('Cocinas integrales', 'Diseño y elaboración de muebles de cocina a medida con acabados personalizados.'),
('Puertas y ventanas', 'Fabricación de puertas, marcos, ventanas de madera, corredizas o abatibles.'),
('Decoración interior', 'Elementos decorativos como paneles, molduras, zócalos y enchapes.'),
('Muebles comerciales', 'Mostradores, vitrinas, estanterías y mobiliario para tiendas y negocios.'),
('Proyectos a medida', 'Diseños personalizados según requerimientos específicos del cliente.'),
('Restauración de muebles', 'Servicios de reparación, restauración y barnizado de muebles antiguos.'),
('Terrazas y exteriores', 'Pergolas, muebles de terraza, cercos y estructuras de madera para exteriores.'),
('Closets empotrados', 'Diseño y construcción de closets integrados a la arquitectura del espacio.');


CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    categoria_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias_proyecto_carpinteria(id)
);

INSERT INTO proyectos (nombre, descripcion, categoria_id) VALUES
('Closet empotrado de 3 cuerpos', 'Closet moderno de madera MDF con puertas corredizas y acabado en melamina blanca.', 10),
('Escritorio en L para oficina', 'Diseño funcional con cajonera y espacio para CPU, acabado en tono wengué.', 2),
('Cocina integral estilo minimalista', 'Muebles superiores e inferiores con superficie de granito, bisagras hidráulicas.', 3),
('Puerta principal maciza de cedro', 'Puerta de entrada estilo rústico con barniz protector y detalles tallados.', 4),
('Mueble para TV con repisas flotantes', 'Centro de entretenimiento en MDF con espacios abiertos y puertas ocultas.', 1),
('Estantería modular para archivo', 'Sistema de estanterías ajustables para almacenamiento de documentos.', 2),
('Mostrador para panadería', 'Mostrador frontal con vitrina de vidrio templado y estantes inferiores.', 6),
('Mesa de comedor extensible', 'Mesa en madera sólida de 6 a 10 puestos con sistema deslizante.', 1),
('Pergola de madera tratada', 'Estructura para terraza con techo de policarbonato y soporte reforzado.', 9),
('Restauración de vitrina antigua', 'Pulido, barnizado y reemplazo de vidrio en vitrina de roble.', 8),
('Panel decorativo con listones', 'Panel mural con diseño de listones verticales para sala o recepción.', 5),
('Cama con cabecera tapizada', 'Diseño contemporáneo con almacenamiento inferior y cabecera forrada.', 1),
('Mueble bajo de cocina', 'Gabinete inferior de cocina con cajones, puertas y espacio para horno.', 3),
('Puerta corrediza tipo granero', 'Puerta rústica en roble con sistema de riel metálico expuesto.', 4),
('Closet corredizo con espejo', 'Frente de espejo entero, interior con organizadores y zapatera.', 10),
('División de ambientes con estantería', 'Estante multifuncional que sirve como división y almacenamiento.', 5);

 
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO servicios (nombre, descripcion, precio) VALUES
('Instalación de puertas', 'Montaje de puertas interiores o exteriores, incluye nivelación y herrajes.', 450.00),
('Mantenimiento de muebles', 'Limpieza, pulido y restauración de muebles deteriorados por el uso o el tiempo.', 300.00),
('Diseño de interiores en madera', 'Asesoría y elaboración de planos y propuestas en madera para ambientes residenciales o comerciales.', 700.00),
('Lijado y barnizado de superficies', 'Proceso de preparación y acabado para renovar superficies de madera.', 250.00),
('Restauración de muebles antiguos', 'Reparación estructural, sustitución de partes y acabado profesional de muebles antiguos.', 600.00),
('Reparación de puertas y ventanas', 'Servicio para puertas y ventanas que no cierran bien, están flojas o dañadas.', 200.00),
('Fabricación a medida de muebles', 'Creación de muebles personalizados según especificaciones del cliente.', 850.00),
('Armado de muebles en sitio', 'Montaje profesional de muebles en la ubicación del cliente.', 180.00),
('Asesoría para optimización de espacios', 'Servicio de consultoría para integrar mobiliario en espacios reducidos.', 350.00),
('Cambio de bisagras y correderas', 'Reemplazo de mecanismos dañados o ruidosos en cajones y puertas.', 150.00),
('Pintura y laqueado de muebles', 'Aplicación de pintura o laca en piezas nuevas o restauradas.', 300.00),
('Instalación de closets empotrados', 'Colocación profesional de closets con nivelación y ajuste.', 500.00),
('Corte y diseño en CNC', 'Cortes de precisión con máquina CNC para piezas decorativas o funcionales.', 700.00),
('Servicio de medición en sitio', 'Visita técnica para toma de medidas y evaluación del lugar.', 100.00),
('Tapizado de cabeceras y asientos', 'Servicio profesional de tapizado con espuma y telas de alta calidad.', 400.00);


CREATE TABLE categorias_producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categorias_producto (nombre, descripcion) VALUES
('Muebles para el hogar', 'Incluye camas, mesas, sillas, closets y demás mobiliario para uso residencial.'),
('Muebles de oficina', 'Escritorios, archivadores, bibliotecas, estaciones de trabajo y sillas.'),
('Puertas y ventanas', 'Fabricación de puertas macizas, corredizas, batientes y ventanas de madera.'),
('Cocinas y alacenas', 'Gabinetes, muebles modulares y alacenas a medida.'),
('Closets y roperos', 'Sistemas de almacenamiento personalizados para dormitorios.'),
('Estanterías y repisas', 'Repisas decorativas, libreros y estantes funcionales.'),
('Cabeceras y respaldos', 'Diseños tapizados o en madera maciza para camas.'),
('Muebles de baño', 'Vanities, gabinetes y muebles auxiliares para baño.'),
('Paneles decorativos', 'Paneles tallados, revestimientos de pared y plafones de diseño.'),
('Juguetes y mobiliario infantil', 'Mesitas, cunas, sillas, estanterías y juguetes de madera.'),
('Muebles exteriores', 'Mesas de jardín, bancos, pérgolas y decks para exteriores.'),
('Accesorios y complementos', 'Bandejas, organizadores, cajones y piezas decorativas.'),
('Muebles personalizados', 'Diseños únicos hechos a pedido del cliente.');

 
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  categoria_id INT,
  precio DECIMAL(10,2) NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (categoria_id) REFERENCES categorias_producto(id)
    ON DELETE SET NULL ON UPDATE CASCADE
);
 
INSERT INTO productos (nombre, descripcion, categoria_id, precio) VALUES
('Mesa de comedor de roble', 'Mesa rectangular para 6 personas, madera de roble pulido.', 1, 850.00),
('Silla tapizada clásica', 'Silla de comedor con respaldo alto y asiento acolchado.', 1, 180.00),
('Escritorio ejecutivo', 'Escritorio de oficina en madera de cedro con cajoneras.', 2, 1200.00),
('Archivador de 3 gavetas', 'Archivador vertical con cerradura metálica.', 2, 450.00),
('Puerta maciza de caoba', 'Puerta de entrada de alta seguridad con barnizado premium.', 3, 980.00),
('Ventana corrediza de pino', 'Ventana doble hoja con vidrio templado.', 3, 650.00),
('Gabinete de cocina modular', 'Módulo superior de cocina con acabado brillante.', 4, 540.00),
('Alacena de pared', 'Alacena compacta para cocina con tres compartimientos.', 4, 320.00),
('Closet empotrado 3 puertas', 'Closet de madera MDF con división interna.', 5, 1100.00),
('Ropero de 2 cuerpos', 'Ropero de madera natural con puertas corredizas.', 5, 950.00),
('Repisas flotantes (juego de 3)', 'Juego de repisas minimalistas para sala.', 6, 270.00),
('Estantería tipo biblioteca', 'Estantería de 5 niveles para libros y decoraciones.', 6, 590.00),
('Respaldo acolchado para cama', 'Cabecera tapizada en lino con estructura de madera.', 7, 480.00),
('Mueble de baño con espejo', 'Vanity con lavamanos, cajonera y espejo incorporado.', 8, 750.00),
('Panel decorativo tallado', 'Panel artesanal en madera con diseño floral.', 9, 430.00),
('Cuna para bebé con barandas', 'Cuna segura con barandas móviles y ruedas.', 10, 670.00),
('Banco rústico para jardín', 'Banco de madera tratada para exteriores.', 11, 320.00),
('Deck modular', 'Plataforma modular antideslizante para jardín.', 11, 880.00),
('Organizador de escritorio', 'Caja de madera con divisiones para útiles.', 12, 95.00),
('Bandeja decorativa de nogal', 'Bandeja rectangular para centro de mesa.', 12, 60.00),
('Mesa de diseño exclusivo', 'Mesa personalizada según requerimientos del cliente.', 13, 1400.00),
('Silla ergonómica artesanal', 'Diseño ergonómico hecho a medida.', 13, 890.00);


CREATE TABLE imagenes_producto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT,
  ruta_imagen VARCHAR(255),
  FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON DELETE CASCADE ON UPDATE CASCADE
);
 
CREATE TABLE cotizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE detalle_cotizacion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cotizacion_id INT,
  tipo INT,   
  cantidad INT,
  precio_unitario DECIMAL(10,2),
  FOREIGN KEY (cotizacion_id) REFERENCES cotizaciones(id)
    ON DELETE CASCADE ON UPDATE CASCADE
);

 

CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    tipo_pago ENUM('Efectivo', 'Transferencia','Tarjeta'),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) 
);
 
 
CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proveedor_id INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
);

CREATE TABLE detalle_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT NOT NULL,
    material_id INT NOT NULL,
    cantidad DECIMAL(10,2),
    precio_unitario DECIMAL(10,2),
    subtotal DECIMAL(10,2),
    FOREIGN KEY (compra_id) REFERENCES compras(id),
    FOREIGN KEY (material_id) REFERENCES materiales(id)
);

-- ⚙️ CONFIGURACIÓN Y LOGS
CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(100),
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    logo VARCHAR(255),
    iva DECIMAL(5,2),
    moneda VARCHAR(10)
);

CREATE TABLE log_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    accion TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

 

-- ⚙️ MÓDULO: ÓRDENES DE TRABAJO
CREATE TABLE trabajadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    especialidad VARCHAR(100)
);

CREATE TABLE ordenes_trabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    cotizacion_id INT,
    fecha_inicio DATE,
    fecha_entrega DATE,
    estado ENUM('pendiente', 'en_produccion', 'terminado', 'entregado') DEFAULT 'pendiente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (cotizacion_id) REFERENCES cotizaciones(id)
);

CREATE TABLE detalle_orden_trabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orden_id INT NOT NULL,
    proyecto_id INT,
    servicio_id INT,
    cantidad INT NOT NULL,
    FOREIGN KEY (orden_id) REFERENCES ordenes_trabajo(id),
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id),
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);
