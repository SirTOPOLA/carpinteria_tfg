<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$rol = $_SESSION['usuario']['rol']; // 'administrador', 'vendedor', etc.
?>



<div id="content" class="container-fluid px-4">

    <div class="container mt-4">
        <div class="row" id="dashboardCards">
            <!-- Las tarjetas se cargan por JS -->
        </div>
    </div>





</div>



<script>
 
 async function mostrarResumen() {
    try {
        const res = await fetch('api/dashboard.php');
        const data = await res.json();

        if (data.success) {
            const contenedor = document.getElementById('dashboardCards');
            contenedor.innerHTML = '';

            data.data.forEach(card => {
                const cardHTML = `
                      <div class="col-md-6 col-xl-3 mb-4">
  <div class="card h-100 border rounded-3 bg-white shadow-sm position-relative overflow-hidden"
       style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
    <div class="card-body p-4 d-flex align-items-center gap-3">
      <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center shadow"
           style="
             width: 50px; 
             height: 50px; 
             background: linear-gradient(135deg, #4f46e5, #3b82f6); /* azul brillante degradado */
             box-shadow: 0 4px 10px rgba(59, 130, 246, 0.5);
             transition: background 0.3s ease;
           ">
        <i class="bi ${card.icono} fs-4 text-white"></i>
      </div>
      <div class="flex-grow-1">
        <p class="text-secondary text-uppercase fw-medium small mb-1" style="letter-spacing: 0.05em;">
          ${card.titulo}
        </p>
        <h3 class="fw-semibold text-dark mb-0">${card.total}</h3>
      </div>
    </div>
  </div>
</div>



                                `;
                contenedor.innerHTML += cardHTML;
            });
        } else {
            contenedor.innerHTML = `<div class="alert alert-warning">No hay datos para mostrar.</div>`;
        }
    } catch (error) {
        console.error("Error al cargar el resumen:", error);
        document.getElementById('dashboardCards').innerHTML = `<div class="alert alert-danger">Error al cargar los datos.</div>`;
    }
}

mostrarResumen()
</script>