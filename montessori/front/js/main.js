document.addEventListener('DOMContentLoaded', () => {
    protegerPagina();
    cargarUsuarioActual();
    mostrarPermisosEnPantalla();

    /*
        Primero aplicamos permisos visuales.
        Esto elimina del HTML lo que el usuario no puede ver.
    */
    aplicarPermisos();

    /*
        Después inicializamos navegación y pestañas.
    */
    inicializarNavegacion();
    inicializarTabsPerfiles();

    /*
        Cargar dashboard inicial.
    */
    if (tienePermiso('dashboard_ver')) {
        if (typeof cargarDashboard === 'function') {
            cargarDashboard();
        }
    }

    /*
        Cargas iniciales según permisos.
        Se usan typeof para evitar errores si algún archivo JS todavía no cargó.
    */
    if (tienePermiso('usuarios_ver')) {
        if (typeof listarPendientes === 'function') {
            listarPendientes();
        }

        if (typeof listarUsuariosAccesos === 'function') {
            listarUsuariosAccesos();
        }
    }

    if (tienePermiso('perfiles_ver')) {
        if (typeof listarPerfiles === 'function') {
            listarPerfiles();
        }
    }

    if (tienePermiso('permisos_asignar')) {
        if (typeof listarPermisos === 'function') {
            listarPermisos();
        }
    }

    if (tienePermiso('perfiles_asignar')) {
        if (typeof cargarUsuariosYPerfiles === 'function') {
            cargarUsuariosYPerfiles();
        }
    }

    const btnLogout = document.getElementById('btnLogout');

    if (btnLogout) {
        btnLogout.addEventListener('click', cerrarSesion);
    }
});

/* =============================
   MENÚ MOBILE
============================= */

document.addEventListener('DOMContentLoaded', () => {
    const btnMenuMobile = document.getElementById('btnMenuMobile');
    const sidebar = document.querySelector('aside');

    if (btnMenuMobile && sidebar) {
        const fondoOscuro = document.createElement('div');
        fondoOscuro.className = 'fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity';
        document.body.appendChild(fondoOscuro);

        const cerrarMenuMobile = () => {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('flex', 'shadow-2xl');
            fondoOscuro.classList.add('hidden');
        };

        btnMenuMobile.addEventListener('click', () => {
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex', 'shadow-2xl');
                fondoOscuro.classList.remove('hidden');
            } else {
                cerrarMenuMobile();
            }
        });

        fondoOscuro.addEventListener('click', cerrarMenuMobile);

        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', cerrarMenuMobile);
        });
    }
});

/* =============================
   PERMISOS VISUALES
============================= */

function aplicarPermisos() {
    const permisos = obtenerPermisos();

    document.querySelectorAll('[data-permiso]').forEach(elemento => {
        const permisoNecesario = elemento.getAttribute('data-permiso');

        if (!permisos.includes(permisoNecesario)) {
            elemento.remove();
        }
    });

    const primerLink = document.querySelector('.sidebar-link');

    if (!primerLink) {
        document.querySelectorAll('.section-content').forEach(sec => sec.classList.add('hidden'));

        const sinPermisos = document.getElementById('section-sin-permisos');

        if (sinPermisos) {
            sinPermisos.classList.remove('hidden');
        }

        return;
    }

    cambiarSeccion(primerLink.getAttribute('data-section'));
}

/* =============================
   NAVEGACIÓN PRINCIPAL
============================= */

function inicializarNavegacion() {
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', () => {
            cambiarSeccion(link.getAttribute('data-section'));
        });
    });
}

function cambiarSeccion(nombre) {
    document.querySelectorAll('.section-content').forEach(sec => {
        sec.classList.add('hidden');
    });

    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.classList.remove('active');
    });

    const section = document.getElementById('section-' + nombre);
    const link = document.querySelector(`.sidebar-link[data-section="${nombre}"]`);

    if (section) {
        section.classList.remove('hidden');
    }

    if (link) {
        link.classList.add('active');
    }

    const titulos = {
        dashboard: 'Dashboard',
        accesos: 'Gestión de Accesos',
        perfiles: 'Perfiles y Permisos',
        asistencia: 'Asistencia',
        comedor: 'Comedor',
        armario: 'Armario Digital',
        biblioteca: 'Biblioteca',
        horarios: 'Horarios',
        actividades: 'Actividades'
    };

    const tituloSeccion = document.getElementById('tituloSeccion');

    if (tituloSeccion) {
        tituloSeccion.textContent = titulos[nombre] || 'Panel';
    }

    /*
        CARGAS ESPECÍFICAS POR SECCIÓN
        Esto evita que una tabla quede clavada en "Cargando..."
        cuando el usuario entra a una sección.
    */

    if (nombre === 'dashboard') {
        if (typeof cargarDashboard === 'function') {
            cargarDashboard();
        }
    }

    if (nombre === 'accesos') {
        if (typeof listarPendientes === 'function') {
            listarPendientes();
        }

        if (typeof listarUsuariosAccesos === 'function') {
            listarUsuariosAccesos();
        }
    }

    if (nombre === 'perfiles') {
        if (typeof listarPerfiles === 'function') {
            listarPerfiles();
        }

        if (typeof listarPermisos === 'function') {
            listarPermisos();
        }

        if (typeof cargarUsuariosYPerfiles === 'function') {
            cargarUsuariosYPerfiles();
        }
    }

    if (nombre === 'comedor') {
        if (typeof cargarDatosComedor === 'function') {
            cargarDatosComedor();
        }
    }

    if (nombre === 'asistencia') {
        if (typeof cargarDatosAsistencia === 'function') {
            cargarDatosAsistencia();
        }
    }
}

/* =============================
   FUNCIÓN PARA BOTONES INTERNOS
============================= */

function mostrarSeccion(nombre) {
    cambiarSeccion(nombre);
}

/* =============================
   TABS PERFILES
============================= */

function inicializarTabsPerfiles() {
    const primeraTabVisible = document.querySelector('.tab-perfiles');

    if (primeraTabVisible) {
        mostrarTabPerfiles(primeraTabVisible.getAttribute('data-tab-perfiles'));
    }
}

function mostrarTabPerfiles(idTab) {
    document.querySelectorAll('.tab-perfiles-content').forEach(tab => {
        tab.classList.add('hidden');
    });

    document.querySelectorAll('.tab-perfiles').forEach(btn => {
        btn.classList.remove('active');
    });

    const tab = document.getElementById(idTab);
    const btn = document.querySelector(`.tab-perfiles[data-tab-perfiles="${idTab}"]`);

    if (tab) {
        tab.classList.remove('hidden');
    }

    if (btn) {
        btn.classList.add('active');
    }

    /*
        Cargas específicas por pestaña interna de Perfiles y Permisos.
    */

    if (idTab === 'tab-perfiles-listado') {
        if (typeof listarPerfiles === 'function') {
            listarPerfiles();
        }
    }

    if (idTab === 'tab-permisos-listado') {
        if (typeof listarPermisos === 'function') {
            listarPermisos();
        }
    }

    if (idTab === 'tab-configurar-perfil') {
        if (typeof listarPermisos === 'function') {
            listarPermisos();
        }

        if (typeof listarPerfiles === 'function') {
            listarPerfiles();
        }
    }

    if (idTab === 'tab-usuarios-perfiles') {
        if (typeof cargarUsuariosYPerfiles === 'function') {
            cargarUsuariosYPerfiles();
        }
    }
}

/* =============================
   DESBLOQUEO MANUAL DEL COMEDOR
============================= */
async function desbloquearComedorManual() {
    const confirmacion = await Swal.fire({
        icon: 'warning',
        title: '¿Forzar desbloqueo?',
        html: `
            <p style="font-size: 14px; color: #475569;">
                Esta acción habilitará la carga de comensales fuera del horario límite establecido.
            </p>
            <p style="font-size: 13px; color: #dc2626; margin-top: 10px; font-weight: bold;">
                El evento quedará registrado en la auditoría bajo su nombre de usuario.
            </p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Sí, desbloquear',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (confirmacion.isConfirmed) {
        Swal.fire({
            icon: 'success',
            title: 'Servicio Desbloqueado',
            text: 'Se ha registrado la apertura manual. Ya puede cargar raciones y excepciones adicionales.',
            confirmButtonColor: '#1d4ed8'
        });
        // Acá se haría el fetch a la API (desbloquear_servicio.php)
    }
}


/* =============================
   INACTIVIDAD Y CIERRE DE SESIÓN SEGURO (RNF-02)
============================= */
let tiempoInactividad;

function reiniciarTemporizador() {
    clearTimeout(tiempoInactividad);
    // 30 minutos = 30 * 60 * 1000 milisegundos
    tiempoInactividad = setTimeout(() => {
        Swal.fire({
            icon: 'warning',
            title: 'Sesión expirada',
            text: 'Por motivos de seguridad, su sesión se ha cerrado tras 30 minutos de inactividad.',
            confirmButtonColor: '#1d4ed8',
            allowOutsideClick: false
        }).then(() => {
            if (typeof cerrarSesion === 'function') {
                cerrarSesion();
            } else {
                window.location.href = 'landing.php';
            }
        });
    }, 30 * 60 * 1000); 
}

// Reseteamos el reloj cada vez que el usuario mueve el mouse o toca una tecla
window.onload = reiniciarTemporizador;
document.onmousemove = reiniciarTemporizador;
document.onkeypress = reiniciarTemporizador;
document.ontouchstart = reiniciarTemporizador;