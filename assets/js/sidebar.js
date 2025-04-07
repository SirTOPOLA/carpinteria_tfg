document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
  
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('d-none');
      sidebar.classList.toggle('d-block');
      sidebar.classList.toggle('position-fixed');
      sidebar.classList.toggle('w-100');
      sidebar.classList.toggle('bg-dark');
      sidebar.classList.toggle('p-3');
    });
  });
  