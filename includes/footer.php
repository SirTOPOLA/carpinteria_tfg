


 

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

 <script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebarMobile = document.getElementById('sidebarMobile');
  const sidebarOverlay = document.getElementById('sidebarOverlay');
  const closeBtn = document.getElementById('closeSidebar');

  toggleBtn.addEventListener('click', () => {
    sidebarMobile.classList.add('mobile-show');
    sidebarOverlay.style.display = 'block';
  });

  closeBtn.addEventListener('click', () => {
    sidebarMobile.classList.remove('mobile-show');
    sidebarOverlay.style.display = 'none';
  });

  sidebarOverlay.addEventListener('click', () => {
    sidebarMobile.classList.remove('mobile-show');
    sidebarOverlay.style.display = 'none';
  });
</script>
</body>
</html>

