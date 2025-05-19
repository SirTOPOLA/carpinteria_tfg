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
            <div class="col-lg-8 mt-4">

                <form id="formContacto" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-semibold">Nombre <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-primary text-white"><i
                                    class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3"
                                maxlength="100" placeholder="Nombre completo">
                            <div class="invalid-feedback">Por favor ingresa tu nombre (mín. 3 caracteres).</div>
                        </div>
                    </div>

                    <!-- Código -->
                    <div class="mb-4">
                        <label for="codigo" class="form-label fw-semibold">DIP <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-primary text-white"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" class="form-control" id="codigo" name="codigo" required maxlength="20"
                                placeholder="Código identificador">
                            <div class="invalid-feedback">Por favor ingresa un código válido (máx. 20 caracteres).</div>
                        </div>
                    </div>

                    <!-- Email -->
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

                    <!-- Teléfono -->
                    <div class="mb-4">
                        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i
                                    class="bi bi-telephone-fill"></i></span>
                            <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="20"
                                placeholder="Número de contacto">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-4">
                        <label for="direccion" class="form-label fw-semibold">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i
                                    class="bi bi-geo-alt-fill"></i></span>
                            <input type="text" class="form-control" id="direccion" name="direccion" maxlength="100"
                                placeholder="Dirección completa">
                        </div>
                    </div>
                    <!-- Descripción -->
                    <div class="mb-4">
                        <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i
                                    class="bi bi-chat-left-text-fill"></i></span>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" maxlength="500"
                                placeholder="Cuéntanos en qué podemos ayudarte..."></textarea>
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            <i class="bi bi-send-fill me-2"></i>Enviar
                        </button>
                    </div>

                </form>
                <!-- Botón de WhatsApp flotante (activador del formulario) -->
                <a href="#" id="btnWhatsapp" class="btn btn-success shadow-lg rounded-circle"
                    style="position: fixed; bottom: 20px; right: 20px; z-index: 1050; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;"
                    aria-label="WhatsApp">
                    <i class="bi bi-whatsapp" style="font-size: 28px;"></i>
                </a>


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
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade" title="Mapa de Carpintería en Pérez Mercamar"></iframe>


        </div>
    </section>

</main>


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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("btnWhatsappFlotante").addEventListener("click", function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Deseas escribir por WhatsApp?',
            text: 'Primero completa el formulario. Luego se abrirá WhatsApp automáticamente.',
            icon: 'info',
            confirmButtonText: 'Ir al formulario',
            cancelButtonText: 'Cancelar',
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("formContacto").scrollIntoView({ behavior: "smooth" });
                setTimeout(() => {
                    document.getElementById("nombre").focus();
                }, 800);
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("formContacto");

        form.addEventListener("submit", async function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            e.preventDefault(); // Detenemos el envío

            const formData = new FormData(form);

            try {
                const res = await fetch("api/guardar_contacto.php", {
                    method: "POST",
                    body: formData
                });

                const data = await res.json();

                if (!res.ok) {
                    if (res.status === 422 && data.errores) {
                        // Mostrar errores en el formulario
                        let mensajeErrores = '';
                        for (const campo in data.errores) {
                            mensajeErrores += `${data.errores[campo]}\n`;
                        }
                        Swal.fire("Errores de validación", mensajeErrores, "warning");
                    } else {
                        throw new Error("Error en la solicitud");
                    }
                    return;
                }

                // Éxito - Continuar con WhatsApp
                const nombre = formData.get("nombre") || "Sin nombre";
                const codigo = formData.get("codigo") || "Sin código";
                const telefono = formData.get("telefono") || "No especificado";
                const direccion = formData.get("direccion") || "No especificada";
                const email = formData.get("email") || "No especificado";
                const descripcion = formData.get("descripcion") || "Sin descripción";

                const mensaje = `Hola, quiero registrarme. Mis datos son:%0A` +
                    `👤 Nombre: ${nombre}%0A` +
                    `🔢 Código: ${codigo}%0A` +
                    `📞 Teléfono: ${telefono}%0A` +
                    `📍 Dirección: ${direccion}%0A` +
                    `📧 Email: ${email}%0A` +
                    `📝 Descripción: ${descripcion}`;

                const numero = <?= htmlspecialchars($telefono)?> ; // <-- Reemplaza con tu número real
                const url = `https://wa.me/${numero}?text=${mensaje}`;
                window.open(url, "_blank");
                form.reset(); // Limpia los campos
                form.classList.remove('was-validated'); // Quita la clase de validación

            } catch (err) {
                console.error("Error en el envío:", err);
                Swal.fire("Error", "No se pudo enviar el formulario. Intenta de nuevo.", "error");
            }
        });
    });
</script>