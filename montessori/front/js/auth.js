/* =============================
   SESIÓN Y API
============================= */

function obtenerToken() {
    return localStorage.getItem('token_montessori');
}

function obtenerUsuario() {
    try {
        return JSON.parse(localStorage.getItem('usuario_montessori')) || null;
    } catch (e) {
        return null;
    }
}

function obtenerPermisos() {
    try {
        return JSON.parse(localStorage.getItem('permisos_montessori')) || [];
    } catch (e) {
        return [];
    }
}

function tienePermiso(permiso) {
    return obtenerPermisos().includes(permiso);
}

function protegerPagina() {
    if (!obtenerToken()) {
        window.location.href = 'modulos/auth/login.php';
    }
}

function cargarUsuarioActual() {
    const usuario = obtenerUsuario();

    if (usuario && document.getElementById('nombreUsuario')) {
        const nombreCompleto = `${usuario.nombre || ''} ${usuario.apellido || ''}`.trim();
        document.getElementById('nombreUsuario').textContent = nombreCompleto || 'Usuario';
    }
}

function mostrarPermisosEnPantalla() {
    const permisos = obtenerPermisos();
    const contenedor = document.getElementById('listaPermisosTexto');

    if (contenedor) {
        contenedor.textContent = permisos.length > 0 ? permisos.join(', ') : 'No hay permisos cargados.';
    }
}

function headersConToken() {
    return {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + obtenerToken()
    };
}

async function apiGet(endpoint) {
    return await fetch(API_BASE + endpoint, {
        method: 'GET',
        headers: headersConToken()
    });
}

async function apiPost(endpoint, datos = {}) {
    return await fetch(API_BASE + endpoint, {
        method: 'POST',
        headers: headersConToken(),
        body: JSON.stringify({
            ...datos,
            token: obtenerToken()
        })
    });
}

async function leerJSONSeguro(response) {
    const texto = await response.text();

    try {
        return JSON.parse(texto);
    } catch (e) {
        console.error('Respuesta inválida del servidor:', texto);
        throw new Error('Respuesta inválida del servidor.');
    }
}

function normalizarLista(data) {
    if (Array.isArray(data)) return data;
    if (Array.isArray(data.usuarios)) return data.usuarios;
    if (Array.isArray(data.perfiles)) return data.perfiles;
    if (Array.isArray(data.permisos)) return data.permisos;
    if (Array.isArray(data.pendientes)) return data.pendientes;
    if (Array.isArray(data.registros)) return data.registros;
    if (Array.isArray(data.data)) return data.data;
    return [];
}

function mostrarErrorBackend(data, titulo = 'Error') {
    Swal.fire({
        icon: 'error',
        title: titulo,
        text: data.mensaje || data.error || 'Ocurrió un error al procesar la solicitud.',
        confirmButtonColor: '#1d4ed8'
    });
}

function escaparHTML(valor) {
    if (valor === null || valor === undefined) return '';

    return String(valor)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

/* =============================
   LOGOUT
============================= */

async function cerrarSesion() {
    const confirmacion = await Swal.fire({
        icon: 'question',
        title: '¿Cerrar sesión?',
        text: 'Se cerrará tu sesión actual en el Sistema Montessori.',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        if (obtenerToken()) {
            await apiPost('logout.php', {});
        }
    } catch (error) {
        console.error(error);
    }

    localStorage.removeItem('token_montessori');
    localStorage.removeItem('usuario_montessori');
    localStorage.removeItem('permisos_montessori');
    localStorage.removeItem('dni_recuperacion');

    window.location.href = 'modulos/auth/login.php';
}
