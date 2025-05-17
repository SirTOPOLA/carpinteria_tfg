




  <!-- Incluye tsparticles justo antes del cierre de body -->
  <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.11.1/tsparticles.bundle.min.js"></script>
  <script>
    tsParticles.load("tsparticles", {
      fpsLimit: 60,
      background: { color: "transparent" },
      particles: {
        color: { value: "#6fffff" },
        links: {
          color: "#3fffff",
          distance: 120,
          enable: true,
          opacity: 0.3,
          width: 1,
        },
        move: {
          enable: true,
          speed: 1.2,
          direction: "none",
          random: false,
          straight: false,
          outModes: "out",
        },
        number: {
          value: 50,
          density: { enable: true, area: 800 }
        },
        opacity: {
          value: 0.6,
          random: true,
          anim: { enable: true, speed: 1, opacity_min: 0.2, sync: false }
        },
        shape: { type: "circle" },
        size: {
          value: { min: 1, max: 3 },
          random: true,
          anim: { enable: false }
        }
      },
      detectRetina: true,
    });
  </script>
 
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
