<?php require "views/layouts/header.php"; ?>

<div class="container-fluid py-4">



    <!-- ======================= Navegación principal ======================= -->
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-hammer"></i> Carpintería SIXBOKU
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#proyectos">Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonios">Testimonios</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-outline-primary ms-lg-3"
                            href="<?= urlsite ?>?page=login">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero: Carrusel dinámico desde BD -->
    <!-- ======================= HERO ======================= -->

    <section class="hero">

<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">

    <div class="carousel-inner">

        <?php if (!empty($obras)): ?>

            <?php foreach ($obras as $index => $obra): ?>

                <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">

                    <img src="<?= urlsite . $obra['imagen'] ?>" 
                         class="d-block w-100"
                         style="height:90vh; object-fit:cover;">

                    <div class="carousel-caption d-flex flex-column justify-content-center h-100">

                        <h1 class="display-4 fw-bold">
                            Muebles a medida con alma artesanal
                        </h1>

                        <p class="lead">
                            Diseñamos y fabricamos piezas únicas en madera
                        </p>

                        <div class="mt-3">

                            <a href="#contacto" class="btn btn-lg btn-primary me-2">
                                Solicitar presupuesto
                            </a>

                            <a href="#proyectos" class="btn btn-lg btn-outline-light">
                                Ver trabajos
                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <!-- CAROUSEL SOLO TEXTO SI NO HAY OBRAS -->

            <div class="carousel-item active">

                <div class="d-flex align-items-center justify-content-center text-center text-white"
                     style="height:90vh; background:#222;">

                    <div>

                        <h1 class="display-4 fw-bold">
                            Creamos muebles únicos
                        </h1>

                        <p class="lead">
                            Diseño, calidad y tradición artesanal en cada pieza
                        </p>

                        <a href="#contacto" class="btn btn-lg btn-primary mt-3">
                            Solicitar información
                        </a>

                    </div>

                </div>

            </div>

            <div class="carousel-item">

                <div class="d-flex align-items-center justify-content-center text-center text-white"
                     style="height:90vh; background:#333;">

                    <div>

                        <h1 class="display-4 fw-bold">
                            Fabricación a medida
                        </h1>

                        <p class="lead">
                            Adaptamos cada diseño a tu espacio y necesidades
                        </p>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>

</section>
    <!-- Principios de Cialdini: Autoridad / Prueba Social / Escasez -->
    <!-- ======================= Servicios ======================= -->

    <section class="py-5 bg-light" id="servicios">
        <div class="container">

            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="fw-bold">Nuestros Servicios</h2>
                    <p class="text-muted">Diseñamos y fabricamos muebles de madera a medida</p>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-door-open display-5 text-primary"></i>
                        <h5 class="mt-3">Puertas y Ventanas</h5>
                        <p>Fabricación de puertas y ventanas de madera con acabados duraderos y elegantes.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-house-door display-5 text-primary"></i>
                        <h5 class="mt-3">Muebles a Medida</h5>
                        <p>Armarios, camas, mesas, cocinas y muebles personalizados según tu espacio.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-tools display-5 text-primary"></i>
                        <h5 class="mt-3">Restauración</h5>
                        <p>Restauramos muebles antiguos devolviendo su belleza y funcionalidad.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-grid-3x3-gap display-5 text-primary"></i>
                        <h5 class="mt-3">Cocinas de Madera</h5>
                        <p>Diseño e instalación de cocinas modernas o clásicas en madera.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-columns-gap display-5 text-primary"></i>
                        <h5 class="mt-3">Closets</h5>
                        <p>Closets y armarios empotrados optimizados para tu espacio.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-palette display-5 text-primary"></i>
                        <h5 class="mt-3">Diseño Personalizado</h5>
                        <p>Convertimos tus ideas en piezas únicas adaptadas a tu estilo.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section class="bg-light py-5" id="testimonios">
        <div class="container">
            <h2 class="text-center fw-bold mb-4">Lo que dicen nuestros clientes</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm p-4">
                        <p>“Un trabajo impecable, madera de primera y diseño espectacular.”</p>
                        <small class="fw-bold">— María G.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm p-4">
                        <p>“Cumplieron plazos y superaron nuestras expectativas.”</p>
                        <small class="fw-bold">— Carlos R.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm p-4">
                        <p>“Cada mueble cuenta una historia, se nota la pasión.”</p>
                        <small class="fw-bold">— Ana L.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= Proyectos ======================= -->
    <section class="py-5" id="proyectos">
        <div class="container">

            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="fw-bold">Nuestros Proyectos</h2>
                    <p class="text-muted">Algunos de nuestros trabajos recientes</p>
                </div>
            </div>



            <div class="row g-4">

                <?php foreach ($proyectos as $p): ?>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">

                            <img src="<?= urlsite . $p['imagen'] ?>" class="card-img-top"
                                style="height:250px;object-fit:cover;">

                            <div class="card-body text-center">
                                <h6 class="fw-bold">
                                    <?= htmlspecialchars($p['titulo']) ?>
                                </h6>
                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-5 text-center">
        <div class="container">
            <h2 class="fw-bold">¿Listo para crear algo único?</h2>
            <p class="text-muted">Agenda hoy tu presupuesto sin compromiso</p>
            <a href="#contacto" class="btn btn-lg cta">
                <i class="bi bi-envelope"></i> Contáctanos ahora
            </a>
        </div>
    </section>




    <?php require "views/layouts/footer.php"; ?>