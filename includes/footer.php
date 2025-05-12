


 

 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById("toggleSidebar").addEventListener("click", function () {
      document.getElementById("sidebar").classList.toggle("collapsed");
      document.getElementById("content").classList.toggle("collapsed");
    });
  </script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');

    toggleBtn.addEventListener('click', function () {
      sidebar.classList.toggle('show');
    });

    // Opcional: ocultar sidebar al hacer clic fuera en móvil
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 767.98 && sidebar.classList.contains('show')) {
        if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
          sidebar.classList.remove('show');
        }
      }
    });
  });
</script> 
</body>
</html>

