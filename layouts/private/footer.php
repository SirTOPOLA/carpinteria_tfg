</div>






<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Validación personalizada Bootstrap 5
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        document.getElementById("toggleSidebar").addEventListener("click", function () {
            if (window.innerWidth <= 767.98) {
                document.getElementById("sidebar").classList.toggle("show");
                document.getElementById("sidebar").classList.remove("collapsed");
                document.getElementById("content").classList.remove("collapsed");
                document.getElementById("navbar").classList.remove("collapsed");


            } else {
                document.getElementById("sidebar").classList.toggle("collapsed");
                document.getElementById("content").classList.toggle("collapsed");
                document.getElementById("navbar").classList.toggle("collapsed");

            }
        });

    });
</script>



<script>
    /*   document.addEventListener("DOMContentLoaded", () => {
          const btn = document.getElementById("btnNotificaciones");
       
          const contador = document.getElementById("contadorNotificaciones");
  
  
          function cargarLogs() {
              fetch("api/obtener_logs.php")
                  .then(res => res.json())
                  .then(data => {
                      if (data.success && data.mensajes.length > 0) {
                          let html = `<div class="container">`;
  
                          data.mensajes.slice(0, 10).forEach((msg, index) => {
                              const campos = msg.split(" | ").reduce((acc, parte) => {
                                  const [clave, valor] = parte.split(": ");
                                  if (clave && valor) acc[clave.trim()] = valor.trim();
                                  return acc;
                              }, {});
  
                              html += `
                          <div class="card mb-3 border-primary">
                              <div class="card-body">
                                  <h5 class="card-title"><strong>Nombre:</strong> ${campos["Nombre"] || "Sin nombre"}</h5>
                                  <p class="card-text">
                                      <strong>Código:</strong> ${campos["Código"] || ""}<br>
                                      <strong>Teléfono:</strong> ${campos["Teléfono"] || ""}<br>
                                      <strong>Dirección:</strong> ${campos["Dirección"] || ""}<br>
                                      <strong>Email:</strong> ${campos["Email"] || ""}<br>
                                      <strong>Descripción:</strong> ${campos["Descripción"] || ""}
                                  </p>
                                  <div class="d-flex justify-content-end">
                                      <button class="btn btn-success me-2 btnLeer" data-index="${index}">Marcar como leído</button>
                                      <button class="btn btn-danger btnEliminar" data-index="${index}">Eliminar</button>
                                  </div>
                              </div>
                          </div>`;
                          });
  
                          html += `</div>`;
  
                          Swal.fire({
                              title: "📋 Notificaciones recientes",
                              html: html,
                              width: 700,
                              showConfirmButton: false,
                              customClass: { popup: 'text-start' },
                              didOpen: () => {
                                  // Marcar como leído
                                  document.querySelectorAll(".btnLeer").forEach(btn => {
                                      btn.addEventListener("click", e => {
                                          const idx = btn.dataset.index;
                                          const msg = data.mensajes[idx];
  
                                          // Convertir mensaje plano a objeto
                                          const campos = msg.split(" | ").reduce((acc, parte) => {
                                              const [clave, valor] = parte.split(": ");
                                              if (clave && valor) acc[clave.trim().toLowerCase()] = valor.trim();
                                              return acc;
                                          }, {});
  
                                          // Preparar objeto limpio para localStorage
                                          const cliente = {
                                              nombre: campos["nombre"] || "",
                                              correo: campos["email"] || "",
                                              telefono: campos["teléfono"] || campos["telefono"] || "",
                                              codigo: campos["código"] || campos["codigo"] || "",
                                              direccion: campos["dirección"] || campos["direccion"] || ""
                                          };
  
                                          // Guardar en localStorage
                                          localStorage.setItem("clienteTemporal", JSON.stringify(cliente));
  
                                          // Redirigir
                                          window.location.href = "index.php?vista=registrar_clientes";
                                      });
  
                                  });
  
                                  // Eliminar
                                  document.querySelectorAll(".btnEliminar").forEach(btn => {
                                      btn.addEventListener("click", e => {
                                          const idx = btn.dataset.index;
                                          fetch("api/eliminar_logs.php", {
                                              method: "POST",
                                              headers: { "Content-Type": "application/json" },
                                              body: JSON.stringify({ index: idx })
                                          })
                                              .then(res => res.json())
                                              .then(resp => {
                                                  if (resp.success) {
                                                      Swal.fire("Eliminado", "La notificación fue eliminada", "success");
                                                      cargarLogs(); // Recargar la lista
                                                  }
                                              });
                                      });
                                  });
                              }
                          });
                      } else {
                          Swal.fire("Sin notificaciones", "No hay registros en logs.txt", "info");
                      }
                  });
          }
  
          btn.addEventListener("click", () => {
              cargarLogs();
          });
  
          // Contador de notificaciones
          fetch("api/obtener_logs.php")
              .then(res => res.json())
              .then(data => {
                  if (data.success && data.mensajes.length > 0) {
                      contador.textContent = data.mensajes.length;
                      contador.classList.add("badge", "bg-danger", "ms-1");
                  } else {
                      contador.style.display = "none";
                  }
              });
      });
  
   */
    function cerrarSession() {
        document.getElementById('cerrarSession').addEventListener('click', () => {
            if (confirm('¿Estás seguro de que deseas cerrar la sesión?')) {
                // console.log('mola')
                // Redirecciona o ejecuta la lógica de cierre
                window.location.href = 'logout.php'; // Cambia a tu ruta real
            }
        });
    }

    cerrarSession();


    // notificationManager.js
    class NotificationManager {
        constructor() {
            this.apiBase = 'api/obtener_logs.php';
            this.currentFilter = {
                status: '',
                priority: '',
                search: '',
                dateFrom: '',
                dateTo: ''
            };
            this.notifications = [];
            this.stats = {};

            this.init();
            this.apiTel = 'api/obtener_telefono.php';
        }

        async init() {
            await this.loadStats();
            await this.loadNotifications();
            this.setupEventListeners();
            this.updateCounters();

            // Auto-refresh cada 30 segundos
            setInterval(() => {
                this.loadStats();
                this.updateCounters();
            }, 30000);
        }

        setupEventListeners() {
            // Botón principal de notificaciones
            const totalNotificacion = document.getElementById("totalNotificacion");
            const btnNotificaciones = document.getElementById("btnNotificaciones");
            if (btnNotificaciones) {
                btnNotificaciones.addEventListener("click", () => this.showNotificationModal());
            }

            // Cerrar sesión
            this.setupLogoutHandler();
        }

        async loadNotifications(limit = 20) {
            try {
                const params = new URLSearchParams({
                    action: 'get_all',
                    limit: limit
                });

                const response = await fetch(`${this.apiBase}?${params}`);
                const data = await response.json();

                if (data.success) {
                    this.notifications = data.notifications;
                    return data;
                } else {
                    throw new Error(data.message || 'Error al cargar notificaciones');
                }
            } catch (error) {
                console.error('Error cargando notificaciones:', error);
                this.showError('Error al cargar las notificaciones');
                return { success: false, notifications: [] };
            }
        }

        async loadStats() {
            try {
                const response = await fetch(`${this.apiBase}?action=get_stats`);
                const data = await response.json();

                if (data.success) {
                    this.stats = data.stats;
                    return data.stats;
                }
            } catch (error) {
                console.error('Error cargando estadísticas:', error);
            }
            return {};
        }

        async searchNotifications() {
            try {
                const params = new URLSearchParams({
                    action: 'search',
                    q: this.currentFilter.search,
                    status: this.currentFilter.status,
                    priority: this.currentFilter.priority,
                    date_from: this.currentFilter.dateFrom,
                    date_to: this.currentFilter.dateTo
                });

                const response = await fetch(`${this.apiBase}?${params}`);
                const data = await response.json();

                if (data.success) {
                    this.notifications = data.notifications;
                    this.updateNotificationsList();
                }
            } catch (error) {
                console.error('Error en búsqueda:', error);
            }
        }

        updateCounters() {
            const contador = document.getElementById("contadorNotificaciones");
            if (contador && this.stats.unread) {
                contador.textContent = this.stats.unread;
                contador.className = "badge bg-danger ms-1";
                contador.style.display = "inline";
            } else if (contador) {
                contador.style.display = "none";
            }
        }

        showNotificationModal() {
            const html = this.generateNotificationHTML();

            Swal.fire({
                title: `
            <div class="container-fluid px-2">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <span class="fw-semibold fs-5">📋 Gestión de Notificaciones</span>
                    </div>
                    <div class="col-12 col-md-6 d-flex flex-wrap justify-content-md-end gap-2">
                        ${this.generateStatsHTML()}
                    </div>
                </div>
            </div>
        `,
                html: `<div class="container-fluid">${html}</div>`,
                width: '95%',
                maxWidth: '1200px',
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'text-start',
                    title: 'w-100 p-0'
                },
                didOpen: () => {
                    this.setupModalEventListeners();
                }
            });
        }
generateStatsHTML() {
    return `
        <span class="badge bg-primary">Total: ${this.stats.total || 0}</span>
        <span class="badge bg-warning text-dark">No leídas: ${this.stats.unread || 0}</span>
        <span class="badge bg-info text-dark">Hoy: ${this.stats.today || 0}</span>
        <span class="badge bg-success">Esta semana: ${this.stats.this_week || 0}</span>
    `;
}


        generateNotificationHTML() {
            return `
            <div class="container-fluid">
                <!-- Filtros y búsqueda -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" 
                                               id="searchInput" placeholder="Buscar por nombre, descripción...">
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-select form-select-sm" id="statusFilter">
                                            <option value="">Todos</option>
                                            <option value="unread">No leídos</option>
                                            <option value="read">Leídos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-select form-select-sm" id="priorityFilter">
                                            <option value="">Todas las prioridades</option>
                                            <option value="high">Alta</option>
                                            <option value="medium">Media</option>
                                            <option value="normal">Normal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" class="form-control form-control-sm" id="dateFromFilter">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" class="form-control form-control-sm" id="dateToFilter">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary" id="btnSearch">🔍 Buscar</button>
                                            <button type="button" class="btn btn-outline-secondary" id="btnClearFilters">🗑️ Limpiar</button>
                                            <button type="button" class="btn btn-outline-info" id="btnRefresh">🔄 Actualizar</button>
                                            <button type="button" class="btn btn-outline-warning" id="btnArchive">📦 Archivar antiguos</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de notificaciones -->
                <div id="notificationsList">
                    ${this.generateNotificationsList()}
                </div>
            </div>
        `;
        }

        generateNotificationsList() {
            if (!this.notifications.length) {
                return '<div class="text-center p-4"><h5>No hay notificaciones</h5></div>';
            }

            return this.notifications.map((notification, index) => {
                const priorityColors = {
                    high: 'border-danger',
                    medium: 'border-warning',
                    normal: 'border-primary'
                };

                const priorityBadges = {
                    high: '<span class="badge bg-danger">Alta prioridad</span>',
                    medium: '<span class="badge bg-warning">Prioridad media</span>',
                    normal: '<span class="badge bg-primary">Prioridad normal</span>'
                };

                const statusBadge = notification.processed
                    ? '<span class="badge bg-success">✓ Procesado</span>'
                    : '<span class="badge bg-secondary">⏳ Pendiente</span>';

                return `
                <div class="card mb-2 ${priorityColors[notification.priority]} ${!notification.processed ? 'shadow-sm' : ''}">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-1">
                                        <strong>${notification.name}</strong>
                                        ${priorityBadges[notification.priority]}
                                        ${statusBadge}
                                    </h6>
                                    <small class="text-muted">${this.formatDate(notification.date)}</small>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                        <small><strong>Código:</strong> ${notification.code}</small><br>
                                        <small><strong>Teléfono:</strong> ${notification.phone}</small>
                                    </div>
                                    <div class="col-sm-6">
                                        <small><strong>Email:</strong> ${notification.email}</small><br>
                                        <small><strong>Dirección:</strong> ${notification.address}</small>
                                    </div>
                                </div>
                                
                                <div class="mt-2">
                                    <small><strong>Descripción:</strong></small>
                                    <p class="mb-0 small">${notification.description}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="d-flex flex-column gap-1">
                                    ${!notification.processed ? `
                                        <button class="btn btn-success btn-sm btnProcesar" 
                                                data-id="${notification.id}" 
                                                data-notification='${JSON.stringify(notification).replace(/'/g, "&apos;")}'>
                                            ✓ Procesar cliente
                                        </button>
                                    ` : `
                                        <button class="btn btn-outline-success btn-sm" disabled>
                                            ✓ Ya procesado
                                        </button>
                                    `}
                                    
                                    <button class="btn btn-info btn-sm btnWhatsApp" 
                                            data-notification='${JSON.stringify(notification).replace(/'/g, "&apos;")}'>
                                        💬 WhatsApp
                                    </button>
                                    
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    `;
            }).join('');
        }
        /* <button class="btn btn-danger btn-sm btnEliminar" 
                data-index="${notification.index}">
            🗑️ Eliminar
        </button> */

        setupModalEventListeners() {
            // Botones de filtro
            document.getElementById('btnSearch')?.addEventListener('click', () => {
                this.updateFiltersFromForm();
                this.searchNotifications();
            });

            document.getElementById('btnClearFilters')?.addEventListener('click', () => {
                this.clearFilters();
            });

            document.getElementById('btnRefresh')?.addEventListener('click', async () => {
                await this.loadNotifications();
                await this.loadStats();
                this.updateNotificationsList();
                this.updateCounters();
            });

            document.getElementById('btnArchive')?.addEventListener('click', () => {
                this.archiveOldNotifications();
            });

            // Búsqueda en tiempo real
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.currentFilter.search = e.target.value;
                        this.searchNotifications();
                    }, 500);
                });
            }

            // Botones de acción en notificaciones
            this.setupNotificationActions();
        }

        setupNotificationActions() {
            // Procesar cliente
            document.querySelectorAll('.btnProcesar').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const notificationId = btn.dataset.id;
                    const notification = JSON.parse(btn.dataset.notification);

                    // Marcar como procesado
                    await this.markAsProcessed(notificationId);

                    // Preparar datos para localStorage
                    const clienteData = {
                        nombre: notification.name,
                        correo: notification.email,
                        telefono: notification.phone,
                        codigo: notification.code,
                        direccion: notification.address
                    };

                    // Guardar y redirigir
                    localStorage.setItem("clienteTemporal", JSON.stringify(clienteData));

                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente procesado',
                        text: 'Redirigiendo al registro de clientes...',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "index.php?vista=registrar_clientes";
                    });
                });
            });

            // WhatsApp
            document.querySelectorAll('.btnWhatsApp').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const notification = JSON.parse(btn.dataset.notification);
                    this.openWhatsApp(notification);
                });
            });

            // Eliminar
            document.querySelectorAll('.btnEliminar').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const index = btn.dataset.index;

                    const result = await Swal.fire({
                        title: '¿Eliminar notificación?',
                        text: 'Esta acción no se puede deshacer',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    });

                    if (result.isConfirmed) {
                        await this.deleteNotification(index);
                    }
                });
            });
        }

        async markAsProcessed(notificationId) {
            try {
                const response = await fetch(`${this.apiBase}?action=mark_processed`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: notificationId })
                });

                const data = await response.json();

                if (data.success) {
                    // Actualizar estado local
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification) {
                        notification.processed = true;
                    }
                    await this.loadStats();
                    this.updateCounters();
                }

                return data;
            } catch (error) {
                console.error('Error marcando como procesado:', error);
                this.showError('Error al procesar la notificación');
            }
        }

        async deleteNotification(index) {
            try {
                const response = await fetch(`${this.apiBase}?action=delete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ index: parseInt(index) })
                });

                const data = await response.json();

                if (data.success) {
                    await this.loadNotifications();
                    await this.loadStats();
                    this.updateNotificationsList();
                    this.updateCounters();

                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'La notificación fue eliminada correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    this.showError(data.message || 'Error al eliminar la notificación');
                }
            } catch (error) {
                console.error('Error eliminando notificación:', error);
                this.showError('Error al eliminar la notificación');
            }
        }

        async archiveOldNotifications(days = 30) {
            try {
                const result = await Swal.fire({
                    title: 'Archivar notificaciones antiguas',
                    text: `¿Archivar notificaciones de más de ${days} días?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, archivar',
                    cancelButtonText: 'Cancelar'
                });

                if (result.isConfirmed) {
                    const response = await fetch(`${this.apiBase}?action=archive_old&days=${days}`);
                    const data = await response.json();

                    if (data.success) {
                        await this.loadNotifications();
                        await this.loadStats();
                        this.updateNotificationsList();
                        this.updateCounters();

                        Swal.fire({
                            icon: 'success',
                            title: 'Archivado completo',
                            text: `${data.archived_count} notificaciones fueron archivadas`,
                            timer: 3000
                        });
                    } else {
                        this.showError(data.message || 'Error al archivar notificaciones');
                    }
                }
            } catch (error) {
                console.error('Error archivando notificaciones:', error);
                this.showError('Error al archivar notificaciones');
            }
        }

        async openWhatsApp(notification) {
            try {
                const response = await fetch(`${this.apiTel}`);
                const data = await response.json();

                const numero = data.telefono || '';
                if (!numero) {
                    console.error('No se encontró un número de teléfono válido.');
                    alert('No se pudo abrir WhatsApp porque no hay número disponible.');
                    return;
                }

                let mensaje = `Hola, me interesa el producto/servicio con código ${notification.code}.%0A`;
                mensaje += `Nombre: ${notification.name}%0A`;
                if (notification.phone) mensaje += `Teléfono: ${notification.phone}%0A`;
                if (notification.address) mensaje += `Dirección: ${notification.address}%0A`;
                if (notification.email) mensaje += `Email: ${notification.email}%0A`;
                mensaje += `Descripción: ${notification.description}`;

                const url = `https://wa.me/${encodeURIComponent(numero)}?text=${encodeURIComponent(mensaje)}`;
                window.open(url, '_blank');
            } catch (error) {
                console.error('Error al abrir WhatsApp:', error);
                alert('Ha ocurrido un error al intentar abrir WhatsApp.');
            }
        }


        updateFiltersFromForm() {
            this.currentFilter.search = document.getElementById('searchInput')?.value || '';
            this.currentFilter.status = document.getElementById('statusFilter')?.value || '';
            this.currentFilter.priority = document.getElementById('priorityFilter')?.value || '';
            this.currentFilter.dateFrom = document.getElementById('dateFromFilter')?.value || '';
            this.currentFilter.dateTo = document.getElementById('dateToFilter')?.value || '';
        }

        clearFilters() {
            this.currentFilter = {
                status: '',
                priority: '',
                search: '',
                dateFrom: '',
                dateTo: ''
            };

            // Limpiar formulario
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('priorityFilter').value = '';
            document.getElementById('dateFromFilter').value = '';
            document.getElementById('dateToFilter').value = '';

            // Recargar todas las notificaciones
            this.loadNotifications().then(() => {
                this.updateNotificationsList();
            });
        }

        updateNotificationsList() {
            const container = document.getElementById('notificationsList');
            if (container) {
                container.innerHTML = this.generateNotificationsList();
                this.setupNotificationActions();
            }
        }

        formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInHours = (now - date) / (1000 * 60 * 60);

            if (diffInHours < 1) {
                const diffInMinutes = Math.floor((now - date) / (1000 * 60));
                return `Hace ${diffInMinutes} minuto${diffInMinutes !== 1 ? 's' : ''}`;
            } else if (diffInHours < 24) {
                const hours = Math.floor(diffInHours);
                return `Hace ${hours} hora${hours !== 1 ? 's' : ''}`;
            } else if (diffInHours < 48) {
                return 'Ayer ' + date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            } else {
                return date.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }

        setupLogoutHandler() {
            const cerrarSessionBtn = document.getElementById('cerrarSession');
            if (cerrarSessionBtn) {
                cerrarSessionBtn.addEventListener('click', (e) => {
                    e.preventDefault();

                    Swal.fire({
                        title: '¿Cerrar sesión?',
                        text: '¿Estás seguro de que deseas cerrar la sesión?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, cerrar sesión',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'logout.php';
                        }
                    });
                });
            }
        }

        showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 4000,
                showConfirmButton: true
            });
        }

        showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        }
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener("DOMContentLoaded", () => {
        window.notificationManager = new NotificationManager();
    });

    // Exportar para uso global si es necesario
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = NotificationManager;
    }




</script>





















</body>

</html>