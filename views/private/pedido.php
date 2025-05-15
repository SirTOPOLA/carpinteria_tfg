



<main class="pt-5 min-vh-100 d-flex flex-column bg-body-tertiary">

 <!-- Hero Seccional -->
  <section class="py-5 text-center text-white position-relative overflow-hidden"
    style="background: linear-gradient(to right, #1f2937cc, #4b5563cc), url('<?= htmlspecialchars($heroRuta) ?>') center/cover no-repeat;">
    <div class="container">
        <h1 class="display-4 fw-bold">Pedido Personalizado</h1>
        <p class="lead">Solicita muebles hechos a tu medida con calidad y pasión artesanal.</p>
    </div>
</section> 

  <!-- Formulario de pedido personalizado -->
  <section class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="border rounded-4 shadow p-4 bg-white">
          <h2 class="text-center mb-4 text-dark fw-semibold">
            <i class="bi bi-clipboard-check me-2"></i>Formulario de Pedido
          </h2>
          
          <form action="guardar_pedido_personalizado.php" method="POST" enctype="multipart/form-data" novalidate>

            <!-- Nombre -->
            <div class="mb-3">
              <label for="nombre" class="form-label fw-semibold">
                <i class="bi bi-person-fill me-2 text-primary"></i>Nombre Completo
              </label>
              <input type="text" class="form-control rounded-pill" id="nombre" name="nombre" required>
            </div>

            <!-- Código -->
            <div class="mb-3">
              <label for="codigo" class="form-label fw-semibold">
                <i class="bi bi-hash me-2 text-secondary"></i>Código del Cliente
              </label>
              <input type="text" class="form-control rounded-pill" id="codigo" name="codigo" required>
            </div>

            <!-- Teléfono -->
            <div class="mb-3">
              <label for="telefono" class="form-label fw-semibold">
                <i class="bi bi-telephone-fill me-2 text-success"></i>Teléfono
              </label>
              <input type="tel" class="form-control rounded-pill" id="telefono" name="telefono">
            </div>

            <!-- Dirección -->
            <div class="mb-3">
              <label for="direccion" class="form-label fw-semibold">
                <i class="bi bi-geo-alt-fill me-2 text-danger"></i>Dirección
              </label>
              <input type="text" class="form-control rounded-pill" id="direccion" name="direccion">
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">
                <i class="bi bi-envelope-fill me-2 text-warning"></i>Correo Electrónico
              </label>
              <input type="email" class="form-control rounded-pill" id="email" name="email">
            </div>

            <!-- Descripción del pedido -->
            <div class="mb-3">
              <label for="descripcion" class="form-label fw-semibold">
                <i class="bi bi-card-text me-2 text-info"></i>Descripción del Pedido
              </label>
              <textarea class="form-control rounded-4" id="descripcion" name="descripcion" rows="5" required></textarea>
            </div>

            <!-- Imagen de referencia -->
            <div class="mb-4">
              <label for="imagen" class="form-label fw-semibold">
                <i class="bi bi-image-fill me-2 text-muted"></i>Imagen de Referencia (opcional)
              </label>
              <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
            </div>

            <!-- Botón enviar -->
            <div class="text-center">
              <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                <i class="bi bi-send-fill me-2"></i>Enviar Pedido
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </section>

</main>
