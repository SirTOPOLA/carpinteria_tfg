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
fetch('api/dashboard.php')
    .then(res => res.json())
    .then(data => {
        console.log("Tarjetas:", data);

        const contenedor = document.getElementById('dashboardCards');
        contenedor.innerHTML = '';

        data.forEach(card => {
            const cardHTML = `
                <div class="col-md-4 mb-3">
                    <div class="card shadow rounded">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi ${card.icono} fs-1 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">${card.titulo}</h6>
                                <h4 class="fw-bold">${card.total}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            contenedor.innerHTML += cardHTML;
        });
    });
</script>


