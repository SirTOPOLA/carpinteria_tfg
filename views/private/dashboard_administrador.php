<?php

// Consulta 1: Materiales más utilizados
$sql1 = "SELECT m.nombre, SUM(mm.cantidad) AS total_usado
         FROM movimientos_material mm
         JOIN materiales m ON mm.material_id = m.id
         WHERE mm.tipo_movimiento = 'salida'
         GROUP BY mm.material_id
         ORDER BY total_usado DESC
         LIMIT 10";
$stmt1 = $pdo->query($sql1);
$materiales_usados = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Consulta 2: Stock actual
$sql2 = "SELECT nombre, stock_actual FROM materiales ORDER BY stock_actual DESC LIMIT 10";
$stmt2 = $pdo->query($sql2);
$stock_actual = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Consulta 3: Alertas de stock bajo
$sql3 = "SELECT nombre, stock_actual, stock_minimo FROM materiales WHERE stock_actual < stock_minimo";
$stmt3 = $pdo->query($sql3);
$stock_bajo = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="content">
  <div class="container-fluid">

    <div class="row mb-4">
      <div class="col-xl-3 col-lg-6 mb-3">
        <div class="stat-card animate__animated animate__fadeInUp">
          <div class="row align-items-center">
            <div class="col-8">
              <p class="stat-number" id="ventas-mes">CFA 45,280</p>
              <p class="stat-label">Ventas del Mes</p>
              <small class="text-light">
                <i class="fas fa-arrow-up me-1"></i> <span id="variacion"></span>
              </small>
            </div>
            <div class="col-4">
              <i class="fas fa-chart-line stat-icon"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-lg-6 mb-3">
        <div class="stat-card animate__animated animate__fadeInUp"
          style="animation-delay: 0.1s; background: linear-gradient(135deg, var(--success-color), #20c997);">
          <div class="row align-items-center">
            <div class="col-8">
              <p class="stat-number" id="pedidos-activos">23</p>
              <p class="stat-label">Pedidos Activos</p>
              <small class="text-light">
                <i class="fas fa-clock me-1"></i>5 próximos a vencer
              </small>
            </div>
            <div class="col-4">
              <i class="fas fa-clipboard-list stat-icon"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-lg-6 mb-3">
        <div class="stat-card animate__animated animate__fadeInUp"
          style="animation-delay: 0.2s; background: linear-gradient(135deg, var(--info-color), #6f42c1);">
          <div class="row align-items-center">
            <div class="col-8">
              <p class="stat-number" id="producciones">8</p>
              <p class="stat-label">En Producción</p>
              <small class="text-light">
                <i class="fas fa-tools me-1"></i><span id="promedioProduccion">Promedio 85% completado</span>
              </small>
            </div>
            <div class="col-4">
              <i class="fas fa-cogs stat-icon"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-lg-6 mb-3">
        <div class="stat-card animate__animated animate__fadeInUp"
          style="animation-delay: 0.3s; background: linear-gradient(135deg, var(--warning-color), #fd7e14);">
          <div class="row align-items-center">
            <div class="col-8">
              <p class="stat-number" id="alertas">12</p>
              <p class="stat-label">Alertas Activas</p>
              <small class="text-light">
                <i class="fas fa-exclamation-triangle me-1"></i>Stock bajo y vencimientos
              </small>
            </div>
            <div class="col-4">
              <i class="fas fa-bell stat-icon"></i>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div class="row">
      <div class="col-lg-8 mb-4">
        <div class="card animate__animated animate__fadeInLeft">
          <div class="card-header bg-transparent">
            <h5 class="section-title mb-0">
              <i class="fas fa-industry me-2"></i>
              Estado de Producciones Activas
            </h5>
          </div>
          <div class="card-body">
            <div id="contenedor-producciones" class="row">

            </div>

            <div class="text-center mt-3">
              <a href="index.php?vista=producciones" class="quick-action-btn">Ver Todas las Producciones</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 mb-4">
        <div class="card animate__animated animate__fadeInRight">
          <div class="card-header bg-transparent">
            <h5 class="section-title mb-0">
              <i class="fas fa-users me-2"></i>
              Rendimiento del Equipo
            </h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <div id="rendimientoEquipo" class="row">

              </div>
              <div class="text-center mt-3">
                <a href="index.php?vista=empleados" class="quick-action-btn">Ver Todos los Empleados</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="row mb-4">
      <div class="col-lg-6">
        <div class="card animate__animated animate__fadeInUp">
          <div class="card-header bg-transparent">
            <h5 class="section-title mb-0">
              <i class="fas fa-chart-bar me-2"></i>
              Resumen Financiero
            </h5>
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-4">
                <div class="mb-3">
                  <h4 id="ingresosMes" class="text-success mb-1">CFA 0.00</h4>
                  <small class="text-muted">Ingresos Mes</small>
                </div>
              </div>

              <div class="col-4">
                <div class="mb-3">
                  <h4 id="gastosMes" class="text-danger mb-1">$0.00</h4>
                  <small class="text-muted">compras Mes</small>
                </div>
              </div>
              <div class="col-4">
                <div class="mb-3">
                  <h4 id="gananciaMes" class="text-primary mb-1">CFA 0.00</h4>
                  <small class="text-muted">Ganancia</small>
                </div>
              </div>
            </div>

            <hr>

            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span>Facturas Pagadas</span>
                <span id="facturasPagadas" class="text-success">CFA 0.00</span>

              </div>
              <div class="progress-custom">
                <div id="progress-bar-facturasPagadas" class="progress-bar-custom bg-success" style="height: 8px;">
                </div>
              </div>
            </div>

            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span>Facturas Pendientes</span>
                <span id="facturasPendientes" class="text-warning">CFA 0.00</span>
              </div>
              <div class="progress-custom">
                <div id="progress-bar-facturasPendientes" class="progress-bar-custom bg-warning" style="height: 8px;">
                </div>
              </div>
            </div>

            <div class="text-center mt-3">
              <a href="#" class="quick-action-btn">Ver Reporte Completo</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-4">
        <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
          <div class="card-header bg-transparent">
            <h5 class="section-title mb-0">
              <i class="fas fa-star me-2"></i>
              Top Clientes del Mes
            </h5>
          </div>
          <div class="card-body">
            <div id="clienteDelMes" class=""></div>

            <div class="text-center mt-3">
              <a href="index.php?vista=clientes" class="quick-action-btn">Ver Todos los Clientes</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ***************   Materiales         ******************** -->
    <div class="row mb-4 ">
      <!-- Gráfico: Materiales más usados -->
      <div class="col-md-6">
        <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">

          <div class="card-header bg-transparent">
            <h5 class="section-title mb-0">
              <i class="fas fa-warehouse me-2"></i>
              Materiales Más Utilizados
            </h5>
          </div>
          <div class="card-body">
            <canvas id="graficoMasUsados"></canvas>
          </div>
        </div>
      </div>

      <!-- Gráfico: Stock actual -->
      <div class="col-md-6">
        <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
          <div class="card-header bg-transparent">
            <h5 class="section-title mb-0">
              <i class="fas fa-warehouse me-2"></i>
              Nivel de Stock Actual
            </h5>
          </div>
          <div class="card-body">
            <canvas id="graficoStockActual"></canvas>
          </div>
        </div>
      </div>
    </div>
    <!-- ***************   BENEFICIOS POR PRODUCCION         ******************** -->
    <div class="row card p-3 shadow-sm rounded-4 bg-white border-0">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="filtroMes" class="form-label">Filtrar por mes:</label>
          <input type="month" id="filtroMes" class="form-control" />
        </div>
        <div class="col-md-4">
          <label class="form-label d-block">Ordenar:</label>
          <button id="ordenarBeneficio" class="btn btn-outline-primary w-100">
            <i class="bi bi-sort-down"></i> Beneficio más alto
          </button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center" id="tablaBeneficios">
          <thead class="table-light">
            <tr>
              <th>Proyecto</th>
              <th>Fecha Inicio</th>
              <th>Estimación</th>
              <th>Materiales</th>
              <th>Beneficio</th>
              <th>% Margen</th>
            </tr>
          </thead>
          <tbody id="bodyBeneficios"></tbody>
        </table>
      </div>
    </div>




    <?php if (count($stock_bajo) > 0): ?>
      <div class="row mb-4">
        <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
          <div class="card-header bg-transparent">
            <h5 class="section-title mb-0">
              <i class="fas fa-exclamation-triangle me-2"></i>
              Alertas Críticas
            </h5>
          </div>
          <div class="card-body">
            <!-- Alertas de stock bajo -->
            <div class="alert alert-danger mt-4 shadow-sm rounded-3">
              <h5 class="mb-3"><i class="bi bi-exclamation-triangle-fill"></i> ¡Materiales con Stock Bajo!</h5>
              <ul class="mb-0">
                <?php foreach ($stock_bajo as $item): ?>
                  <li><strong><?= htmlspecialchars($item['nombre']) ?></strong>: <?= $item['stock_actual'] ?> unidades
                    (mínimo: <?= $item['stock_minimo'] ?>)</li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>


  // Datos PHP → JS
  const materialesUsados = <?= json_encode($materiales_usados) ?>;
  const stockActual = <?= json_encode($stock_actual) ?>;

  // Gráfico 1: Materiales más utilizados
  new Chart(document.getElementById('graficoMasUsados'), {
    type: 'bar',
    data: {
      labels: materialesUsados.map(item => item.nombre),
      datasets: [{
        label: 'Cantidad usada',
        data: materialesUsados.map(item => item.total_usado),
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Gráfico 2: Stock actual
  new Chart(document.getElementById('graficoStockActual'), {
    type: 'bar',
    data: {
      labels: stockActual.map(item => item.nombre),
      datasets: [{
        label: 'Stock actual',
        data: stockActual.map(item => item.stock_actual),
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      scales: {
        x: { beginAtZero: true }
      }
    }
  });





  document.addEventListener("DOMContentLoaded", () => {
    fetch('api/dashboard_data.php')
      .then(res => res.json())
      .then(data => {
        // Ventas
        const monto = parseFloat(data.ventas_mes).toLocaleString('es-ES', { currency: 'XAF', style: 'currency' });
        const variacion = parseFloat(data.variacion_ventas);
        const signo = variacion >= 0 ? '+' : '-';
        const variacionFormateada = `${signo}${Math.abs(variacion).toLocaleString('es-ES', { minimumFractionDigits: 1 })}% vs mes anterir`;

        document.getElementById("variacion").textContent = variacionFormateada;
        document.getElementById("ventas-mes").textContent = monto;

        // Pedidos activos
        document.getElementById("pedidos-activos").textContent = data.pedidos_activos;
        document.querySelector("#pedidos-activos").parentNode.querySelector("small").innerHTML =
          `<i class="fas fa-clock me-1"></i>${data.pedidos_proximos_vencer} próximos a vencer`;

        // Producción

        // document.getElementById("promedioProduccion").textContent = data.producciones;

        document.getElementById("producciones").textContent = data.producciones;
        document.querySelector("#producciones").parentNode.querySelector("small").innerHTML =
          `<i class="fas fa-tools me-1"></i>Promedio ${data.producciones_promedio}% completado`;

        // Alertas
        document.getElementById("alertas").textContent = data.alertas_stock;
        document.querySelector("#alertas").parentNode.querySelector("small").innerHTML =
          `<i class="fas fa-exclamation-triangle me-1"></i>Stock bajo y vencimientos`;

        console.log(data.rendimiento_equipo)

        data.producciones_activas.forEach(p => {
          const icono = seleccionarIcono(p.pedido_desc); // función opcional
          const bg = seleccionarColor(p.porcentaje); // ej: verde si > 80
          document.querySelector('#contenedor-producciones').innerHTML += `
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center mb-2">
                  <div class="me-3">
                    <div class="rounded-circle ${bg} d-flex align-items-center justify-content-center"
                      style="width: 50px; height: 50px;">
                      <i class="fas ${icono} text-white"></i>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="mb-1">${p.pedido_desc} - ${obtenerIniciales(p.responsable_nombre)}</h6>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar-custom ${bg}" style="width: ${p.porcentaje}%;"></div>
                    </div>
                    <small class="text-muted">${p.porcentaje}% - ${p.avance_desc}</small>
                  </div>
                </div>
              </div>
            `;
        });
        const equipoContainer = document.getElementById('rendimientoEquipo');
        equipoContainer.innerHTML = '';

        data.rendimiento_equipo.forEach((emp, index) => {
          const badgeColor = emp.rendimiento >= 95 ? 'success' :
            emp.rendimiento >= 85 ? 'primary' :
              emp.rendimiento >= 60 ? 'warning' : 'danger';

          const accordionItem = `
                <div class="accordion-item border rounded mb-3 shadow-sm">
                  <h2 class="accordion-header" id="heading${index}">
                    <button class="accordion-button collapsed d-flex align-items-center gap-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}" aria-expanded="false" aria-controls="collapse${index}">
                      <img src="api/${emp.imagen}" class="rounded-circle" alt="Avatar" style="width: 45px; height: 45px; object-fit: cover;">
                      <div class="flex-grow-1">
                        <h6 class="mb-0">${emp.nombre_completo}</h6>
                        <small class="text-muted">${emp.rol}</small>
                      </div>
                      <span class="badge bg-${badgeColor}">${emp.rendimiento}%</span>
                    </button>
                  </h2>
                  <div id="collapse${index}" class="accordion-collapse collapse" aria-labelledby="heading${index}" data-bs-parent="#rendimientoEquipo">
                    <div class="accordion-body">
                      <div class="mb-2">
                        <label class="form-label mb-1">Progreso de tareas</label>
                        <div class="progress" style="height: 8px;">
                          <div class="progress-bar bg-${badgeColor}" role="progressbar" style="width: ${emp.rendimiento}%;" aria-valuenow="${emp.rendimiento}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                      <div>
                        <small class="text-muted">Avance producción: <strong>${emp.produccion}%</strong></small>
                      </div>
                    </div>
                  </div>
                </div>
              `;
          equipoContainer.insertAdjacentHTML('beforeend', accordionItem);
        });



        /* --- resumen financiero ------ */
        const resumen = data.resumen_financiero;
        document.querySelector('#ingresosMes').textContent = `CFA ${parseFloat(resumen.ingresos_mes).toLocaleString()}`;
        document.querySelector('#gastosMes').textContent = `CFA ${parseFloat(resumen.gastos_mes).toLocaleString()}`;
        document.querySelector('#gananciaMes').textContent = `CFA ${parseFloat(resumen.ganancia).toLocaleString()}`;
        document.querySelector('#facturasPagadas').textContent = `CFA ${parseFloat(resumen.facturas_pagadas).toLocaleString()}`;
        document.querySelector('#facturasPendientes').textContent = `CFA ${parseFloat(resumen.facturas_pendientes).toLocaleString()}`;



        const totalFacturado = parseFloat(resumen.facturas_pagadas) + parseFloat(resumen.facturas_pendientes);
        const porcentajePagado = totalFacturado > 0 ? (resumen.facturas_pagadas / totalFacturado * 100) : 0;
        const porcentajePendiente = 100 - porcentajePagado;

        const barraPagadas = document.getElementById('progress-bar-facturasPagadas');
        const barraPendientes = document.getElementById('progress-bar-facturasPendientes');

        barraPagadas.style.width = `${porcentajePagado}%`;
        barraPendientes.style.width = `${porcentajePendiente}%`;


        /* document.querySelector('.progress-bar-custom.bg-success').style.width = `${porcentajePagado}% ${seleccionarColor(porcentajePagado)}`;
        document.querySelector('.progress-bar-custom.bg-warning').style.width = `${porcentajePendiente}% ${seleccionarColor(porcentajePendiente)}`;
 */
        /* cliente del mes */
        const topClientesContainer = document.querySelector('#clienteDelMes');

        const bgColors = [
          'rgba(139,69,19,0.05)',
          'rgba(139,69,19,0.03)',
          'rgba(139,69,19,0.02)'
        ];

        const circleClasses = ['bg-warning', 'bg-secondary', 'bg-info'];
        let html = '';
        data.clientes_mes.forEach((cliente, i) => {
          const bgColor = bgColors[i % 3];
          const circleClass = circleClasses[i] || 'bg-secondary';
          const pedidosText = cliente.cantidad_ventas + (cliente.cantidad_ventas > 1 ? ' pedidos completados' : ' pedido completado');
          const totalFormatted = Number(cliente.total_gastado).toLocaleString('es-ES', { style: 'currency', currency: 'CFA' });

          html += `
        <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: ${bgColor};">
          <div class="d-flex align-items-center">
            <div class="rounded-circle ${circleClass} d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
              <span class="text-white fw-bold">${i + 1}</span>
            </div>
            <div>
              <h6 class="mb-0">${cliente.nombre_cliente}</h6>
              <small class="text-muted">${pedidosText}</small>
            </div>
          </div>
          <div>
            <span class="h5 text-success mb-0">${totalFormatted}</span>
          </div>
        </div>
      `;
        });

        topClientesContainer.innerHTML = html;


        /* ****** Grafico de beneficios por produccion ******* */

        let produccionesOriginales = data.beneficios_produccion;
        let produccionesFiltradas = [...produccionesOriginales];
        let ordenDescendente = true;

        const tbody = document.getElementById('bodyBeneficios');
        const filtroMes = document.getElementById('filtroMes');
        const ordenarBtn = document.getElementById('ordenarBeneficio');

        // Renderizar tabla
        function renderTabla(lista) {
          tbody.innerHTML = '';
          lista.forEach(p => {
            const estimacion = parseFloat(p.estimacion_total);
            const materiales = parseFloat(p.costo_materiales);
            const beneficio = parseFloat(p.beneficio_estimado);
            const margen = estimacion > 0 ? ((beneficio / estimacion) * 100).toFixed(1) : '0';

            const fila = document.createElement('tr');
            fila.innerHTML = `
            <td class="text-start"><i class="bi bi-briefcase-fill text-primary me-1"></i> ${p.proyecto}</td>
            <td>${p.fecha_inicio || '-'}</td>
            <td><span class="text-info">CFA ${estimacion.toLocaleString()}</span></td>
            <td><span class="text-warning">CFA ${materiales.toLocaleString()}</span></td>
            <td><span class="text-success fw-bold">CFA ${beneficio.toLocaleString()}</span></td>
            <td>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-success" role="progressbar" 
                    style="width: ${margen}%;" 
                    aria-valuenow="${margen}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <small>${margen}%</small>
            </td>
          `;
            tbody.appendChild(fila);
          });
        }

        // Filtrar por mes
        filtroMes.addEventListener('change', () => {
          const mesSeleccionado = filtroMes.value; // YYYY-MM
          produccionesFiltradas = produccionesOriginales.filter(p => {
            return p.fecha_inicio && p.fecha_inicio.startsWith(mesSeleccionado);
          });
          renderTabla(produccionesFiltradas);
        });

        // Ordenar por beneficio
        ordenarBtn.addEventListener('click', () => {
          produccionesFiltradas.sort((a, b) => {
            const beneficioA = parseFloat(a.beneficio_estimado);
            const beneficioB = parseFloat(b.beneficio_estimado);
            return ordenDescendente ? beneficioB - beneficioA : beneficioA - beneficioB;
          });
          ordenDescendente = !ordenDescendente;
          renderTabla(produccionesFiltradas);
        });

        // Render inicial
        renderTabla(produccionesFiltradas);

      });






  });





  
  function seleccionarColor(porcentaje) {
    if (porcentaje >= 80) return 'bg-success';
    if (porcentaje >= 50) return 'bg-warning';
    if (porcentaje >= 20) return 'bg-info';
    return 'bg-secondary';
  }

  function seleccionarIcono(desc) {
    desc = desc.toLowerCase();
    if (desc.includes('mesa')) return 'fa-chair';
    if (desc.includes('estantería')) return 'fa-couch';
    if (desc.includes('puerta')) return 'fa-door-open';
    if (desc.includes('dormitorio')) return 'fa-bed';
    return 'fa-tools';
  }

  function obtenerIniciales(nombre) {
    const partes = nombre.trim().split(' ');
    if (partes.length === 1) return partes[0];
    return partes[0] + ' ' + partes[1][0] + '.';
  }


</script>