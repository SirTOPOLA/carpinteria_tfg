<main class="py-3 min-vh-100 d-flex flex-column bg-body-tertiary">
<!-- Canvas de partículas -->
<div id="tsparticles" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;"></div>

  <!-- Hero Seccional -->
  <section class="py-5 text-center text-white position-relative overflow-hidden"
    style="background: linear-gradient(to right, #1f2937cc, #4b5563cc), url('<?= htmlspecialchars($heroRuta) ?>') center/cover no-repeat;">
    <div class="container">
      <h1 class="display-4 fw-bold text-uppercase">Sobre Nosotros</h1>
      <p class="lead"><?= htmlspecialchars($vision) ?></p>
    </div>
  </section>

  <!-- Historia -->
  <section class="container py-5">
    <h2 class="text-center mb-4 text-dark fw-semibold text-uppercase">
      <i class="bi bi-journal-text me-2 text-secondary"></i>Nuestra Historia
    </h2>
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <p class="mb-3">
        <?= htmlspecialchars($historia) ?>
        </p>
       <!--  <p>
          En cada proyecto aplicamos técnicas tradicionales combinadas con diseño contemporáneo, priorizando la calidad
          y sostenibilidad. Nuestra misión es dejar una huella significativa en los hogares de quienes confían en
          nosotros.
        </p> -->
      </div>
    </div>
  </section>

  <!-- Misión y Visión -->
  <section class="bg-light py-5">
    <div class="container">
      <div class="row g-4 text-center">
        <div class="col-md-6">
          <div class="border rounded-4 bg-white shadow-sm p-4 h-100">
            <i class="bi bi-bullseye display-5 text-warning mb-3"></i>
            <h4 class="fw-semibold text-uppercase">Misión</h4>
            <p class="mb-0"><?= htmlspecialchars($mision) ?></p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="border rounded-4 bg-white shadow-sm p-4 h-100">
            <i class="bi bi-eye display-5 text-primary mb-3"></i>
            <h4 class="fw-semibold text-uppercase">Visión</h4>
            <p class="mb-0"><?= htmlspecialchars($vision) ?>.</p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Nuestro equipo -->
  <section class="container py-5">
    <h2 class="text-center mb-4 text-dark fw-semibold">
      <i class="bi bi-people-fill me-2"></i>Conoce a Nuestro Equipo
    </h2>
    <div class="row justify-content-center g-4">
      <?php if (!empty($equipo)): ?>
        <?php foreach ($equipo as $miembro): ?>
          <div class="col-6 col-md-4 col-lg-3 text-center">
            <img src="api/<?= htmlspecialchars($miembro['perfil']) ?>" class="rounded-circle shadow"
              alt="<?= htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']) ?>" width="120" height="120"
              style="object-fit: cover;">
            <h6 class="mt-3 mb-0 fw-bold">
              <?= htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']) ?>
            </h6>
            <small class="text-muted">
        <?php   $rol = $miembro['rol'];
              $genero = $miembro['genero'];

              $rol_genero = match (strtolower($rol)) {
                'diseñador' => ($genero == 'F') ? 'Diseñadora' : 'Diseñador',
                'operario' => ($genero == 'F') ? 'Operaria' : 'Operario',
                'vendedor' => ($genero == 'F') ? 'Vendedora' : 'Vendedor',
                default => $rol
              };
              ?>

              <?php   echo  htmlspecialchars( $rol_genero ) ?>
            </small>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p class="text-muted">No hay miembros del equipo disponibles actualmente.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

</main>