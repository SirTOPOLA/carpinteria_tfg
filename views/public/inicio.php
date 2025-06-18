<?php
try { 


  $maxImagenes = 6;
$imagenes = [];
$basePath = "api/";
$maxIntentos = 20; // Límite de intentos para evitar bucles infinitos
$intentos = 0;

while (count($imagenes) < $maxImagenes && $intentos < $maxIntentos) {
    $stmt = $pdo->prepare("SELECT imagen 
                           FROM avances_produccion 
                           WHERE imagen IS NOT NULL AND TRIM(imagen) != '' 
                           ORDER BY RAND() 
                           LIMIT 1");
    $stmt->execute();
    $imagen = $stmt->fetchColumn();

    if ($imagen && file_exists($basePath . $imagen) && !in_array($imagen, $imagenes)) {
        $imagenes[] = $imagen;
    }

    $intentos++;
} 

} catch (PDOException $e) {
  $imagenes = [];
}
?>

<style>
/* Estilos modernos para la sección hero */
.hero-gradient {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  position: relative;
  overflow: hidden;
}

.hero-pattern {
  background-image: 
    radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 2px, transparent 2px),
    radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 2px, transparent 2px);
  background-size: 50px 50px;
}

.glass-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(15px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
}

.floating-animation {
  animation: floating 3s ease-in-out infinite;
}

@keyframes floating {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.btn-modern {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  background: linear-gradient(45deg, #ff6b6b, #ffd93d);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.btn-modern:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
  background: linear-gradient(45deg, #ff5252, #ffcc02);
}

.btn-outline-modern {
  background: transparent;
  border: 2px solid rgba(255, 255, 255, 0.8);
  color: white;
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}

.btn-outline-modern:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: white;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
}

.carousel-modern {
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.carousel-modern .carousel-item img {
  transition: transform 0.8s ease;
}

.carousel-modern:hover .carousel-item.active img {
  transform: scale(1.05);
}

.title-gradient {
  background: linear-gradient(45deg, #fff, #ffd700);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.fade-in-up {
  animation: fadeInUp 1s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.stagger-1 { animation-delay: 0.1s; }
.stagger-2 { animation-delay: 0.2s; }
.stagger-3 { animation-delay: 0.3s; }
.stagger-4 { animation-delay: 0.4s; }

/* Efectos de configuración */
.config-hero {
  background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
}

.config-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(15px);
  color: #2d3436;
}

@media (max-width: 768px) {
  .display-4 { font-size: 2.5rem; }
  .btn-lg { padding: 0.75rem 1.5rem; }
  .glass-card { margin: 1rem; padding: 2rem !important; }
}
</style>

<?php if (empty($usuario)): ?>
  <main class="min-vh-100 d-flex flex-column position-relative">
    <section class="flex-grow-1 d-flex align-items-center justify-content-center text-center position-relative overflow-hidden config-hero hero-pattern">
      
      <!-- Canvas de partículas mejorado -->
      <div id="tsparticles" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;"></div>
      
      <!-- Elementos decorativos flotantes -->
      <div class="position-absolute" style="top: 10%; left: 10%; z-index: 1;">
        <i class="bi bi-tools text-white opacity-25 floating-animation" style="font-size: 3rem; animation-delay: 0s;"></i>
      </div>
      <div class="position-absolute" style="top: 20%; right: 15%; z-index: 1;">
        <i class="bi bi-hammer text-white opacity-25 floating-animation" style="font-size: 2.5rem; animation-delay: 1s;"></i>
      </div>
      <div class="position-absolute" style="bottom: 15%; left: 20%; z-index: 1;">
        <i class="bi bi-tree-fill text-white opacity-25 floating-animation" style="font-size: 4rem; animation-delay: 2s;"></i>
      </div>

      <div class="container position-relative" style="z-index: 2;">
        <div class="p-5 rounded-4 config-card glass-card fade-in-up">
          <div class="mb-4 stagger-1 fade-in-up">
            <i class="bi bi-tree-fill text-primary" style="font-size: 4rem;"></i>
          </div>
          
          <h1 class="display-4 fw-bold text-uppercase mb-3 stagger-2 fade-in-up" style="color: #2d3436;">
            <?= htmlspecialchars($nombre_empresa ?? 'Sistema de Carpintería') ?>
          </h1>
          
          <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
              <p class="lead mb-4 stagger-3 fade-in-up" style="color: #636e72; font-size: 1.2rem;">
                <?= htmlspecialchars($mision ?? 'Configure su sistema de gestión de carpintería para comenzar a organizar sus proyectos, inventario y clientes de manera profesional.') ?>
              </p>
            </div>
          </div>

          <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center stagger-4 fade-in-up">
            <a href="configuracion.php" class="btn btn-modern btn-lg px-5 rounded-pill shadow-sm text-white fw-bold" role="button">
              <i class="bi bi-rocket-takeoff me-2"></i> Iniciar Configuración
            </a>
            <a href="#info" class="btn btn-outline-primary btn-lg px-4 rounded-pill" role="button">
              <i class="bi bi-info-circle me-2"></i> Más Información
            </a>
          </div>

          <!-- Características destacadas -->
          <div class="row mt-5 text-start">
            <div class="col-md-4 mb-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                <span class="fw-semibold">Gestión de Proyectos</span>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                <span class="fw-semibold">Control de Inventario</span>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                <span class="fw-semibold">Seguimiento de Clientes</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

<?php else: ?>
  <main class="min-vh-100 d-flex flex-column position-relative pt-4 mt-4">
    <section class="flex-grow-1 d-flex align-items-center justify-content-center text-center position-relative overflow-hidden hero-gradient hero-pattern pt4"
      style="background: linear-gradient(135deg, rgba(31, 41, 55, 0.9), rgba(75, 85, 99, 0.9)), url('<?= htmlspecialchars($heroRuta) ?>') center/cover no-repeat;">

      <!-- Elementos decorativos flotantes -->
      <div class="position-absolute" style="top: 10%; left: 5%; z-index: 1;">
        <i class="bi bi-gear-fill text-white opacity-20 floating-animation" style="font-size: 2rem; animation-delay: 0s;"></i>
      </div>
      <div class="position-absolute" style="top: 15%; right: 10%; z-index: 1;">
        <i class="bi bi-box-seam text-white opacity-20 floating-animation" style="font-size: 2.5rem; animation-delay: 1.5s;"></i>
      </div>
      <div class="position-absolute" style="bottom: 10%; right: 5%; z-index: 1;">
        <i class="bi bi-people-fill text-white opacity-20 floating-animation" style="font-size: 3rem; animation-delay: 2.5s;"></i>
      </div>

      <div class="container text-white position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            
            <div class="mb-4 fade-in-up stagger-1">
              <i class="bi bi-tree-fill text-warning floating-animation" style="font-size: 4rem;"></i>
            </div>

            <h1 class="display-3 fw-bold text-uppercase mb-4 title-gradient fade-in-up stagger-2">
              <?= htmlspecialchars($nombre_empresa ?? '') ?>
            </h1>
            
            <div class="row justify-content-center mb-5">
              <div class="col-lg-8">
                <p class="lead mb-0 fade-in-up stagger-3" style="font-size: 1.3rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                  <?= htmlspecialchars($mision ?? '') ?>
                </p>
              </div>
            </div>

            <div class="d-flex justify-content-center gap-4 flex-wrap mb-5 fade-in-up stagger-4">
              <a href="index.php?vista=producto" class="btn btn-modern btn-lg px-5 rounded-pill shadow-sm text-white fw-bold"
                aria-label="Ver Catálogo">
                <i class="bi bi-collection me-2"></i> Ver Catálogo
              </a>
              <a href="index.php?vista=contacto" class="btn btn-outline-modern btn-lg px-5 rounded-pill"
                aria-label="Hacer un pedido">
                <i class="bi bi-whatsapp me-2"></i> Hacer un pedido
              </a>
            </div>

            <?php if (!empty($imagenes)): ?>
              <div class="row justify-content-center fade-in-up stagger-4">
                <div class="col-lg-10">
                  <div id="heroCarousel" class="carousel slide carousel-modern shadow-lg" data-bs-ride="carousel" data-bs-interval="4000">
                    
                    <!-- Indicadores modernos -->
                    <div class="carousel-indicators">
                      <?php foreach ($imagenes as $index => $img): ?>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" 
                          <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?> 
                          aria-label="Slide <?= $index + 1 ?>">
                        </button>
                      <?php endforeach; ?>
                    </div>

                    <div class="carousel-inner rounded-4">
                      <?php foreach ($imagenes as $index => $img): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                          <div class="position-relative">
                            <img src="api/<?= htmlspecialchars($img) ?>" class="d-block w-100"
                              style="height: 500px; object-fit: cover;" alt="Proyecto <?= $index + 1 ?>" loading="lazy">
                            <div class="position-absolute bottom-0 start-0 end-0 bg-gradient-dark p-4">
                              <h5 class="text-white mb-2">Proyecto Destacado #<?= $index + 1 ?></h5>
                              <p class="text-white-50 mb-0 small">Carpintería de alta calidad</p>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>

                    <!-- Controles modernos -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                      <div class="bg-dark bg-opacity-50 rounded-circle p-2">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      </div>
                      <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                      <div class="bg-dark bg-opacity-50 rounded-circle p-2">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      </div>  
                      <span class="visually-hidden">Siguiente</span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Estadísticas o información adicional -->
              <div class="row mt-5 fade-in-up stagger-4">
                <div class="col-md-4 mb-3">
                  <div class="glass-card p-4 rounded-4 h-100">
                    <i class="bi bi-award-fill text-warning mb-3" style="font-size: 2.5rem;"></i>
                    <h4 class="fw-bold">Calidad Premium</h4>
                    <p class="mb-0 opacity-75">Materiales de primera calidad</p>
                  </div>
                </div>
                <div class="col-md-4 mb-3">  
                  <div class="glass-card p-4 rounded-4 h-100">
                    <i class="bi bi-clock-fill text-info mb-3" style="font-size: 2.5rem;"></i>
                    <h4 class="fw-bold">Entrega Puntual</h4>
                    <p class="mb-0 opacity-75">Cumplimos con los tiempos acordados</p> 
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="glass-card p-4 rounded-4 h-100">
                    <i class="bi bi-heart-fill text-danger mb-3" style="font-size: 2.5rem;"></i>
                    <h4 class="fw-bold">Trabajo Artesanal</h4>
                    <p class="mb-0 opacity-75">Cada pieza hecha con dedicación</p>
                  </div>
                </div>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </section>
  </main>
<?php endif; ?>

<script>
// Mejorar la experiencia con JavaScript adicional
document.addEventListener('DOMContentLoaded', function() {
  // Animación suave para los botones
  const buttons = document.querySelectorAll('.btn-modern, .btn-outline-modern');
  buttons.forEach(button => {
    button.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-3px) scale(1.02)';
    });
    button.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0) scale(1)';
    });
  });

  // Efecto parallax suave para elementos flotantes
  window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.floating-animation');
    
    parallaxElements.forEach((element, index) => {
      const speed = 0.5 + (index * 0.1);
      element.style.transform = `translateY(${scrolled * speed}px)`;
    });
  });

  // Lazy loading mejorado para imágenes del carousel
  const carouselImages = document.querySelectorAll('#heroCarousel img');
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.classList.add('fade-in');
        observer.unobserve(img);
      }
    });
  });

  carouselImages.forEach(img => imageObserver.observe(img));
});
</script>