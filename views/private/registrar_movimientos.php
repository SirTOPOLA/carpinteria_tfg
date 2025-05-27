<?php


$errores = [];
$materiales = [];
$producciones = [];
$observaciones = '';

// Obtener materiales y producciones
try {
  $stmt = $pdo->query("SELECT id, nombre, stock_actual, stock_minimo FROM materiales ORDER BY nombre ASC");
  $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

  

  $sql = "SELECT 
                p.id,
                pr.nombre AS nombre_proyecto,
                e.nombre AS responsable
            FROM producciones p
            INNER JOIN proyectos pr ON p.proyecto_id = pr.id
            INNER JOIN empleados e ON p.responsable_id = e.id
            ORDER BY pr.nombre ASC";
  $stmt = $pdo->query($sql);
  $producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Obtener materiales ya comprados o disponebles en el almacen 

  $sql = 'SELECT 
                m.nombre AS nombre_material,
                dc.cantidad AS cantidad_material
                 FROM detalles_compra dc
                 LEFT JOIN materiales m ON dc.material_id = m.id 
                 ';

  $stmt = $pdo->query($sql);
  $material_comprado = $stmt->fetchAll(PDO::FETCH_ASSOC);
  /* print_r($material_comprado); */

} catch (PDOException $e) {
  $errores[] = "Error al cargar datos: " . $e->getMessage();
}


?>


<div id="content" class="container-fluid py-4">

  <div class="row justify-content-center">
    <div class="col-12 col-xl-11">
      <div class="card shadow rounded-4">
        <div class="card-header bg-dark text-white rounded-top-4 d-flex align-items-center">
          <i class="bi bi-arrow-left-right me-2 fs-4"></i>
          <h5 class="mb-0">Registrar Movimiento</h5>
        </div>

        <div class="card-body"> 

          <form method="POST" id="form" class="needs-validation" novalidate>

            <!-- Selección de producción -->
            <div class="mb-3">
              <label for="produccion_id" class="form-label">
                <i class="bi bi-hammer text-danger me-1"></i> Producción Asociada
                <span class="text-danger">*</span>
              </label>
              <select name="produccion_id" id="produccion_id" class="form-select" required>
                <option value="">Seleccione una producción</option>
                <?php foreach ($producciones as $prod): ?>
                  <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre_proyecto']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Contenedor dinámico de materiales -->
            <div id="materialesContainer"></div>

            <button type="button" class="btn btn-outline-success mb-3" id="btnAgregarMaterial">
              <i class="bi bi-plus-circle"></i> Agregar Material
            </button>

            <!-- Observaciones generales -->
            <div class="mb-3">
              <label for="observaciones" class="form-label">
                <i class="bi bi-chat-left-dots text-secondary me-1"></i> Observaciones generales
              </label>
              <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
              <a href="index.php?vista=movimientos" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save2"></i> Guardar Movimiento
              </button>
            </div>
          </form>

          <div id="mensaje"></div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Script para actualizar dinámicamente el stock -->


<script>

  
  
  document.addEventListener("DOMContentLoaded", () => {
  const stockMateriales = <?= json_encode($materiales) ?>;
  const materialesContainer = document.getElementById("materialesContainer");
  const btnAgregarMaterial = document.getElementById("btnAgregarMaterial");
  let grupoIndex = 0;

  const obtenerMaterialesDisponibles = (modoActual, indexActual) => {
    const seleccionados = new Set();

    document.querySelectorAll('.grupo-material').forEach((grupo, index) => {
      if (index !== indexActual) {
        const tipo = grupo.querySelector('[name="tipo[]"]').value;
        const material = grupo.querySelector('[name="material_id[]"]').value;
        if (tipo === 'entrada') seleccionados.add(material);
      }
    });

    return stockMateriales.filter(mat =>
      modoActual !== 'entrada' || !seleccionados.has(String(mat.id))
    );
  };

  const actualizarOpcionesMaterial = (select, tipoMovimiento, index) => {
    const materiales = obtenerMaterialesDisponibles(tipoMovimiento, index);
    select.innerHTML = `<option value="">Seleccione material</option>`;
    materiales.forEach(mat => {
      select.innerHTML += `<option value="${mat.id}">${mat.nombre}</option>`;
    });
  };

  const crearGrupoMaterial = () => {
    const index = grupoIndex++;
    const div = document.createElement("div");
    div.className = "row g-3 mb-3 border p-3 rounded bg-light grupo-material";

    div.innerHTML = `
      <div class="col-md-4">
        <label class="form-label">Tipo de Movimiento <span class="text-danger">*</span></label>
        <select name="tipo[]" class="form-select tipo" required>
          <option value="">Seleccione tipo</option>
          <option value="entrada">Entrada</option>
          <option value="salida">Salida</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Material <span class="text-danger">*</span></label>
        <select name="material_id[]" class="form-select material" required disabled>
          <option value="">Seleccione tipo primero</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Cantidad <span class="text-danger">*</span></label>
        <input type="number" name="cantidad[]" class="form-control cantidad" min="1" required disabled>
        <div class="form-text text-danger info-stock"></div>
      </div>

      <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-sm btnEliminarGrupo">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
    `;

    materialesContainer.appendChild(div);

    const tipoSel = div.querySelector(".tipo");
    const matSel = div.querySelector(".material");
    const cantidadInput = div.querySelector(".cantidad");
    const infoStock = div.querySelector(".info-stock");

    tipoSel.addEventListener("change", () => {
      matSel.disabled = false;
      actualizarOpcionesMaterial(matSel, tipoSel.value, index);
      cantidadInput.value = "";
      cantidadInput.disabled = true;
      infoStock.textContent = "";
    });

    matSel.addEventListener("change", () => {
      cantidadInput.disabled = false;
      const material = stockMateriales.find(m => m.id == matSel.value);
      const tipo = tipoSel.value;

      if (!material) return;

      if (tipo === "salida") {
        cantidadInput.max = material.stock_actual - material.stock_minimo;
        infoStock.textContent = `Stock disponible: ${material.stock_actual}, mínimo: ${material.stock_minimo}`;
      } else {
        cantidadInput.removeAttribute("max");
        infoStock.textContent = "";
      }
    });

    cantidadInput.addEventListener("input", () => {
      const tipo = tipoSel.value;
      const material = stockMateriales.find(m => m.id == matSel.value);
      if (!material) return;

      const val = parseInt(cantidadInput.value);
      if (tipo === "salida" && val > material.stock_actual - material.stock_minimo) {
        infoStock.textContent = "Cantidad excede el stock permitido.";
        cantidadInput.setCustomValidity("Cantidad excede límite");
      } else {
        infoStock.textContent = "";
        cantidadInput.setCustomValidity("");
      }
    });

    div.querySelector(".btnEliminarGrupo").addEventListener("click", () => {
      div.remove();
      actualizarTodosLosSelects();
    });
  };

  const actualizarTodosLosSelects = () => {
    document.querySelectorAll('.grupo-material').forEach((grupo, index) => {
      const tipo = grupo.querySelector('[name="tipo[]"]').value;
      const select = grupo.querySelector('[name="material_id[]"]');
      actualizarOpcionesMaterial(select, tipo, index);
    });
  };

  btnAgregarMaterial.addEventListener("click", () => {
    crearGrupoMaterial();
  });

  // Uno por defecto
  crearGrupoMaterial();

  // Manejo de envío
  const form = document.getElementById("formMovimiento");
  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    if (!form.checkValidity()) {
      form.classList.add("was-validated");
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch("guardar_movimiento_multiple.php", {
        method: "POST",
        body: formData
      });

      const data = await response.json();
      if (data.success) {
        alert("Movimiento registrado correctamente.");
        window.location.href = "index.php?vista=movimientos";
      } else {
        alert("Error: " + data.message);
      }

    } catch (err) {
      console.error(err);
      alert("Error en el servidor.");
    }
  });
});
</script>


<!-- 
<script>

  document.addEventListener("DOMContentLoaded", () => {
    const tipoSelect = document.getElementById("tipo");
    const materialSelect = document.getElementById("material_id");
    const cantidadContainer = document.getElementById("cantidadContainer");
    const cantidadSelect = document.getElementById("cantidad");
    const stockInfo = document.getElementById("stockInfo");
    const stockSpan = document.getElementById("stockActual");
    const mensajeError = document.getElementById("mensajeErrorAjax");
    const errorAjaxTexto = document.getElementById("errorAjaxTexto");

    let currentStock = 0;

    function evaluarMostrarCantidad() {
      const tipo = tipoSelect.value;
      const material = materialSelect.value;

      if (tipo && material) {
        cantidadContainer.style.display = "block";
        fetchStock(material);
      } else {
        cantidadContainer.style.display = "none";
        stockInfo.style.display = "none";
        mensajeError.style.display = "none";
      }
    }

    async function fetchStock(materialId) {

      try {
        const res = await fetch(`api/obtener_materiales.php?id=${materialId}`)
        const data = await res.json()

        if (data.success) {
          mensajeError.style.display = "none";
          currentStock = parseInt(data.stock) || 0;
          stockInfo.style.display = "block";
          stockSpan.textContent = currentStock;

          generarOpcionesCantidad(tipo);
        } else {
          cantidadContainer.style.display = "none";
          stockInfo.style.display = "none";
          errorAjaxTexto.textContent = data.message || "Error al obtener el stock.";
          mensajeError.style.display = "block";
          return;
        }
      } catch (err) {
        cantidadContainer.style.display = "none";
        stockInfo.style.display = "none";
        errorAjaxTexto.textContent = "Error al conectar con el servidor.";
        mensajeError.style.display = "block";
        // console.log(err)
      };
    }


    function generarOpcionesCantidad(tipo) {
      cantidadSelect.innerHTML = '<option value="">Seleccione una cantidad</option>';
      const limite = tipo === ('entrada' || 'salida') ? 100 : currentStock;

      for (let i = 1; i <= limite; i++) {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        cantidadSelect.appendChild(option);
      }

      if (tipo === 'salida' && currentStock <= 0) {
        const option = document.createElement("option");
        option.textContent = "Sin stock disponible";
        option.disabled = true;
        cantidadSelect.appendChild(option);
        cantidadSelect.disabled = true;
      } else {
        cantidadSelect.disabled = false;
      }
    }

    tipoSelect.addEventListener("change", evaluarMostrarCantidad);
    materialSelect.addEventListener("change", evaluarMostrarCantidad);
    /* --------------------------- */

    function cargarMaterialesPorTipo(tipo) {
      fetch(`api/obtener_materiales.php?tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            errorAjaxTexto.textContent = data.message || "Error al obtener materiales.";
            mensajeError.style.display = "block";
            return;
          }

          materialSelect.innerHTML = '<option value="">Seleccione un material</option>';
          data.materiales.forEach(mat => {
            const option = document.createElement("option");
            option.value = mat.id;
            option.textContent = mat.nombre;
            materialSelect.appendChild(option);
          });

          materialSelect.disabled = false;
        })
        .catch(() => {
          errorAjaxTexto.textContent = "Error al conectar con el servidor remoto.";
          mensajeError.style.display = "block";
        });
    }

    tipoSelect.addEventListener("change", () => {
      const tipo = tipoSelect.value;
      if (tipo) {
        cargarMaterialesPorTipo(tipo);
        cantidadContainer.style.display = "none";
        stockInfo.style.display = "none";
        materialSelect.innerHTML = '<option value="">Cargando...</option>';
        materialSelect.disabled = true;
      } else {
        materialSelect.innerHTML = '<option value="">Seleccione tipo de movimiento primero</option>';
        materialSelect.disabled = true;
        cantidadContainer.style.display = "none";
        stockInfo.style.display = "none";
      }
    });

  });


  /* -------------------- */



  document.getElementById('form').addEventListener('submit', async function (e) {
    e.preventDefault(); // Prevenir envío tradicional
    let mensaje = document.getElementById('mensaje');
    // Validación nativa de Bootstrap
    if (!this.checkValidity()) {
      this.classList.add('was-validated');
      return;
    }

    const form = e.target;
    const formData = new FormData(form);
    try {
      const res = await fetch('api/guardar_movimientos.php', {
        method: 'POST', body: formData
      })
      const data = await res.json(); // Esperamos JSON del backend
      if (data.success) {
        mensaje.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        setTimeout(() => {
          mensaje.style.opacity = 0;
          setTimeout(() => {
            mensaje.textContent = '';
            mensaje.style.opacity = 1;
            window.location.href = 'index.php?vista=movimientos'; // redirige si es exitoso

          }, 300); // espera a que se desvanezca
        }, 2000);

      } else {
        mensaje.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        setTimeout(() => {
          mensaje.textContent = '';
        }, 2000)

      }
    } catch (error) {
      mensaje.innerHTML = `<div class="alert alert-danger">${error}</div>`;
      setTimeout(() => {
        mensaje.textContent = '';
      }, 2000)
    };

  })




</script>

 -->