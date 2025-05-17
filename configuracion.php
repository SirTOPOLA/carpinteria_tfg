<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Configuración Inicial</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .step {
      display: none;
      opacity: 0;
      transition: opacity 0.6s ease, transform 0.6s ease;
      transform: translateY(20px);
    }

    .step.active {
      display: block;
      opacity: 1;
      transform: translateY(0);
    }

    .step-indicator {
      display: flex;
      justify-content: center;
      margin-bottom: 2rem;
      gap: 1rem;
    }

    .step-indicator span {
      width: 40px;
      height: 40px;
      line-height: 40px;
      background: #dee2e6;
      border-radius: 50%;
      text-align: center;
      font-weight: bold;
      color: #495057;
      transition: background 0.3s, color 0.3s;
    }

    .step-indicator span.active {
      background: #0d6efd;
      color: white;
    }

    #indicador-pasos .badge {
      font-size: 1rem;
      padding: 0.6rem 1.2rem;
      margin: 0 0.5rem;
      transition: all 0.3s ease;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div id="indicador-pasos" class="step-indicator text-center mb-4">
      <span id="step1-indicator" class="step-circle active"><i class="bi bi-building"></i> 1</span>
      <span id="step2-indicator" class="step-circle"><i class="bi bi-person-vcard"></i> 2</span>
      <span id="step3-indicator" class="step-circle"><i class="bi bi-person-lock"></i> 3</span>
    </div>
    <div id="alertas" class="mt-2"></div>
    <!-- Paso 1: Configuración empresa -->
    <div class="step active" id="step1">
      <div class="card shadow p-4 ">
        <h3 class="text-primary mb-4"><i class="bi bi-building"></i> Configurar Empresa</h3>
        <form id="empresaForm" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold "><i class="bi bi-bank2"></i> Nombre Empresa</label>
              <input type="text" name="nombre_empresa" class="form-control " required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold "><i class="bi bi-geo-alt"></i> Dirección</label>
              <input type="text" name="direccion" class="form-control " required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold "><i class="bi bi-telephone"></i> Teléfono</label>
              <input type="text" name="telefono" class="form-control " required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold "><i class="bi bi-envelope"></i> Correo</label>
              <input type="email" name="correo" class="form-control " required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold "><i class="bi bi-percent"></i> IVA (%)</label>
              <input type="number" step="0.01" min="0" max="100" name="iva" class="form-control " required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold "><i class="bi bi-currency-dollar"></i> Moneda</label>
              <input type="text" name="moneda" class="form-control " placeholder="Ej. USD, EUR" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold "><i class="bi bi-image"></i> Logo (pequeño)</label>
              <input type="file" name="logo" class="form-control " accept="image/*">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold "><i class="bi bi-image-fill"></i> Imagen
                representativa</label>
              <input type="file" name="imagen" class="form-control " accept="image/*">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold "><i class="bi bi-journal-text"></i> Misión</label>
              <textarea name="mision" rows="3" class="form-control " placeholder="Describe la misión de la empresa..."
                required></textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold "><i class="bi bi-journal-text"></i> Visión</label>
              <textarea name="vision" rows="3" class="form-control " placeholder="Describe la visión de la empresa..."
                required></textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold "><i class="bi bi-journal-text"></i> Historia</label>
              <textarea name="historia" rows="4" class="form-control "
                placeholder="Escribe la historia de la empresa..." required></textarea>
            </div>
            <div class="col-12 text-center mt-4">
              <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-check-circle"></i> Guardar y continuar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>


    <!-- Paso 2: Registro primer empleado -->
    <div class="step" id="step2">
      <div class="card shadow p-4">
        <h3 class="text-success mb-3"><i class="bi bi-person-vcard"></i> Registrar Primer Empleado</h3>
        <form id="empleadoForm">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-person"></i> Nombre</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-person"></i> Apellido</label>
              <input type="text" name="apellido" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-calendar3"></i> Fecha de Nacimiento</label>
              <input type="date" name="fecha_nacimiento" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-hash"></i> Código</label>
              <input type="text" name="codigo" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-gender-ambiguous"></i> Género</label>
              <select name="genero" class="form-select" required>
                <option value="">Seleccione</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                <option value="O">Otro</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-telephone"></i> Teléfono</label>
              <input type="text" name="telefono" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-geo"></i> Dirección</label>
              <input type="text" name="direccion" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
              <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-clock-history"></i> Horario de Trabajo</label>
              <input type="text" name="horario_trabajo" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-calendar-check"></i> Fecha de Ingreso</label>
              <input type="date" name="fecha_ingreso" class="form-control" required>
            </div>
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success "><i class="bi bi-check-circle"></i> Guardar y
                continuar</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Paso 3: Registro de usuario -->
    <div class="step" id="step3">
      <div class="card shadow p-4">
        <h3 class=" mb-3"><i class="bi bi-person-lock"></i> Crear Usuario del Empleado</h3>
        <form id="usuarioForm" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-person-circle"></i> Usuario</label>
              <input type="text" name="usuario" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-shield-lock"></i> Contraseña</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-image-fill"></i> Foto</label>
              <input type="file" name="imagen" class="form-control" accept="image/*">
            </div>
            <input type="hidden" name="rol" value="1">
            <div class="col-md-12">
              <label class="form-label"><i class="bi bi-person-vcard"></i> Empleado vinculado</label>
              <select name="empleado_id" id="empleado_id" class="form-select" required>
                <option value="">Cargando...</option>
              </select>
            </div>
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success  "><i class="bi bi-person-check"></i> Registrar y
                acceder</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const alertas = document.getElementById('alertas');
      const pasos = {
        1: document.getElementById("step1"),
        2: document.getElementById("step2"),
        3: document.getElementById("step3"),
      };

      const indicadores = {
        1: document.getElementById("step1-indicator"),
        2: document.getElementById("step2-indicator"),
        3: document.getElementById("step3-indicator"),
      };

      let empleadoIdCreado = null;

      function mostrarPaso(pasoActual) {
        for (let paso in pasos) {
          pasos[paso].classList.remove("active");
          indicadores[paso].classList.remove("active");
        }
        pasos[pasoActual].classList.add("active");
        indicadores[pasoActual].classList.add("active");
      }

      // Paso 1: Guardar empresa
      document.getElementById("empresaForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
          const res = await fetch("api/guardar_empresa.php", {
            method: "POST",
            body: formData,
          });

          const data = await res.json();

          if (data.status) {
            alert("Empresa registrada correctamente.");
            mostrarPaso(2);
          } else {

            alert("Error al registrar empresa: " + data.message);

          }
        } catch (err) {
          alert("Error de red al registrar empresa.");
          console.error(err);
        }
      });

      // Paso 2: Guardar empleado
      document.getElementById("empleadoForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
          const res = await fetch("api/guardar_empleado.php", {
            method: "POST",
            body: formData,
          });

          const data = await res.json();

          if (data.status && data.empleado_id) {
            alert("Empleado registrado correctamente.");
            empleadoIdCreado = data.empleado_id;

            // Agregar la opción al select
            const select = document.getElementById("empleado_id");
            select.innerHTML = `<option value="${empleadoIdCreado}" selected>Empleado creado (ID: ${empleadoIdCreado})</option>`;

            mostrarPaso(3);
          } else {
            const mensajes = Array.isArray(data.message) ? data.message : [data.message];
            mensajes.forEach(msg => {
              const alerta = document.createElement('div');
              alerta.className = 'alert alert-danger alert-dismissible fade show';
              alerta.setAttribute('role', 'alert');
              alerta.innerHTML = `
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                `;
              alertas.appendChild(alerta);
            });
            alert("Error al registrar empleado: " + data.message);
          }
        } catch (err) {
          alert("Error de red al registrar empleado.");
          console.error(err);
        }
      });

      // Paso 3: Guardar usuario
      document.getElementById("usuarioForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
          const res = await fetch("api/guardar_usuario.php", {
            method: "POST",
            body: formData,
          });

          const data = await res.json();

          if (data.status) {
            alert("Usuario inicial creado correctamente. ¡Bienvenido al sistema!");
            window.location.href = "login.php";
          } else {
            const mensajes = Array.isArray(data.message) ? data.message : [data.message];
            mensajes.forEach(msg => {
              const alerta = document.createElement('div');
              alerta.className = 'alert alert-danger alert-dismissible fade show';
              alerta.setAttribute('role', 'alert');
              alerta.innerHTML = `
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                `;
              alertas.appendChild(alerta);
            });
            console.log("Error al registrar usuario: " + data.mensaje + ' ' + data.data)
            alert("Error al registrar usuario: " + data.mensaje);
          }
        } catch (err) {
          alert("Error de red al registrar usuario.");
          console.error(err);
        }
      });
    });
  </script>

</body>

</html>