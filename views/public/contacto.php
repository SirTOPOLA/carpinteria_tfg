<main class="  min-vh-100 d-flex flex-column bg-body-tertiary">


    <!-- Hero Seccional -->
    <section class="hero-contacto py-5 text-center text-white"
        style="background: linear-gradient(to right, rgba(31,41,55,0.8), rgba(75,85,99,0.8)), url('<?= htmlspecialchars($heroRuta) ?>') center/cover no-repeat;">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="bi bi-envelope-paper-fill me-2"></i>Contacto</h1>
            <p class="lead">Estamos aquí para ayudarte. Envíanos tus consultas y responderemos pronto.</p>
        </div>
    </section>

    <!-- Formulario de Contacto -->
    <section class="container py-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <form id="formContacto" method="POST" action="procesar_contacto.php" class="needs-validation" novalidate
                    enctype="multipart/form-data">

                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-semibold">Nombre <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-primary text-white"><i
                                    class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3"
                                maxlength="100" placeholder="Tu nombre completo">
                            <div class="invalid-feedback">Por favor ingresa tu nombre (mín. 3 caracteres).</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Email <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-primary text-white"><i
                                    class="bi bi-envelope-fill"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required
                                placeholder="ejemplo@correo.com" maxlength="255">
                            <div class="invalid-feedback">Por favor ingresa un email válido.</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i
                                    class="bi bi-telephone-fill"></i></span>
                            <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="20"
                                placeholder="Número de contacto">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="mensaje" class="form-label fw-semibold">Consulta / Mensaje <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required minlength="10"
                            maxlength="2000" placeholder="Escribe tu consulta aquí..."></textarea>
                        <div class="invalid-feedback">Por favor ingresa un mensaje válido (mín. 10 caracteres).</div>
                    </div>
<!-- 
                     -->

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            <i class="bi bi-send-fill me-2"></i>Enviar Consulta
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </section>

    <!-- Mapa -->
    <section class="container pb-5">
        <h2 class="text-center mb-4 fw-semibold text-dark"><i class="bi bi-geo-alt-fill me-2 text-primary"></i>Nuestra
            Ubicación</h2>
        <div class="ratio ratio-16x9 rounded-4 shadow">
            <iframe
  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3941.419013030712!2d8.774311815142819!3d3.750838198785769!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc11a7ac63bc2d3%3A0x4e89e1a96e0a3f70!2sBarrio%20Perez%20Mercamar%2C%20Malabo!5e0!3m2!1ses!2ses!4v1652035000000!5m2!1ses!2ses"
  width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa de Carpintería en Pérez Mercamar"></iframe>


        </div>
    </section>

</main>

<!-- Botón flotante WhatsApp -->
<!-- <a href="https://wa.me/<?= htmlspecialchars($telefono) ?>?text=Hola,%20quiero%20hacer%20una%20consulta%20sobre%20sus%20productos"
    class="btn btn-success shadow-lg rounded-circle"
    style="position: fixed; bottom: 20px; right: 20px; z-index: 1050; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;"
    target="_blank" aria-label="WhatsApp">
    <i class="bi bi-whatsapp" style="font-size: 28px;"></i>
</a> -->
<a href="https://wa.me/240222247194?text=Hola,%20quiero%20hacer%20una%20consulta%20sobre%20sus%20productos"
    class="btn btn-success shadow-lg rounded-circle"
    style="position: fixed; bottom: 20px; right: 20px; z-index: 1050; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;"
    target="_blank" aria-label="WhatsApp">
    <i class="bi bi-whatsapp" style="font-size: 28px;"></i>
</a>

<!-- Validación Bootstrap 5 -->
<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>