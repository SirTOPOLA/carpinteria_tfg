<?php
// footer.php — Componente reutilizable para todo el sistema
?>

<footer class="footer mt-auto py-3 bg-dark text-white">
    <div class="container text-center">
        <div class="d-flex flex-column align-items-center gap-1">
            <span class="small">
                <i class="bi bi-c-circle"></i> 
                <?php echo date('Y'); ?> — Todos los derechos reservados
            </span>

            <span class="small text-secondary">
                Sistema de Gestión de Carpintería
            </span>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="<?=urlsite?>public/js/bootstrap.bundle.min.js"></script>

</body>
</html>
