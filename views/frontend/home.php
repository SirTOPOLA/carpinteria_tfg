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
    <section class="hero position-relative">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">


            <?php
            // $obras debe venir desde el controlador (MVC)
            // Ejemplo: $obras = [ ['imagen'=>'obra1.jpg'], ... ];
            $obras = [
                ['imagen' => 'public/img/img-1.jpg'],
                ['imagen' => 'public/img/img-2.jpeg'],

            ];

            ?>


            <div class="carousel-inner">
                <?php if (!empty($obras)): ?>
                    <div class="carousel-inner">
                        <?php foreach ($obras as $index => $obra): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <div class="carousel-bg" style="background-image: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)), 
                                        url('<?= urlsite . htmlspecialchars($obra['imagen']) ?>');">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="carousel-item active">
                        <div class="carousel-bg"
                            style="background-image: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)), url('<?= urlsite ?> public/img/img-2.jpeg');">
                        </div>
                    </div>
                <?php endif; ?>
            </div>



            <!-- Controles -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>


        <!-- Contenido superpuesto -->
        <div class="hero-content position-absolute top-50 start-50 translate-middle text-center text-white container">
            <span class="badge badge-trust mb-3">
                <i class="bi bi-award"></i> Más de 15 años creando muebles únicos
            </span>
            <h1 class="display-4 fw-bold mt-3">Muebles a medida con alma artesanal</h1>
            <p class="lead mt-3">Diseñamos y fabricamos piezas únicas en madera, cuidando cada detalle.</p>
            <div class="mt-4">
                <a href="#contacto" class="btn btn-lg cta me-2">
                    <i class="bi bi-chat-dots"></i> Solicitar presupuesto
                </a>
                <a href="#proyectos" class="btn btn-lg btn-outline-light">
                    <i class="bi bi-images"></i> Ver trabajos
                </a>
            </div>
        </div>
    </section>


    <!-- Principios de Cialdini: Autoridad / Prueba Social / Escasez -->
    <section class="py-5" id="servicios">
        <div class="container">
            <div class="row text-center mb-4">
                <div class="col">
                    <h2 class="fw-bold">¿Por qué elegirnos?</h2>
                    <p class="text-muted">Razones que nos convierten en tu mejor opción</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <h5 class="mt-3">Autoridad</h5>
                        <p>Profesionales certificados en carpintería artesanal y diseño en madera.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-people feature-icon"></i>
                        <h5 class="mt-3">Prueba social</h5>
                        <p>Más de 300 clientes satisfechos avalan nuestro trabajo.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="bi bi-hourglass-split feature-icon"></i>
                        <h5 class="mt-3">Escasez</h5>
                        <p>Aceptamos cupos limitados por mes para garantizar máxima calidad.</p>
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