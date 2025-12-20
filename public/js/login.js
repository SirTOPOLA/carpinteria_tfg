 
    // Mostrar / ocultar contraseña
    const toggle = document.getElementById('togglePass');
    const pass = document.getElementById('password');

    toggle.addEventListener('click', () => {
        if (pass.type === 'password') {
            pass.type = 'text';
            toggle.innerHTML = `<i class="bi bi-eye-slash-fill"></i>`;
        } else {
            pass.type = 'password';
            toggle.innerHTML = `<i class="bi bi-eye-fill"></i>`;
        }
    });

    // Validación Bootstrap
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', e => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
 
