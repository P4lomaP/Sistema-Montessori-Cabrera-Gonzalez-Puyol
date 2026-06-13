/* =============================
   PERMISOS
============================= */

const MODULOS_PERMITIDOS = [
    'dashboard',
    'usuarios',
    'perfiles',
    'permisos',
    'asistencia',
    'comedor',
    'armario',
    'biblioteca',
    'horarios',
    'documentos',
    'actividades'
];

const NOMBRES_MODULOS = {
    dashboard: 'Dashboard',
    usuarios: 'Usuarios y accesos',
    perfiles: 'Perfiles',
    permisos: 'Permisos',
    asistencia: 'Asistencia',
    comedor: 'Comedor',
    armario: 'Armario',
    biblioteca: 'Biblioteca',
    horarios: 'Horarios',
    documentos: 'Documentos',
    actividades: 'Actividades'
};

const ICONOS_MODULOS = {
    dashboard: 'fa-chart-line',
    usuarios: 'fa-users-gear',
    perfiles: 'fa-id-card-clip',
    permisos: 'fa-key',
    asistencia: 'fa-user-check',
    comedor: 'fa-utensils',
    armario: 'fa-folder-open',
    biblioteca: 'fa-book',
    horarios: 'fa-calendar-days',
    documentos: 'fa-file-lines',
    actividades: 'fa-calendar-check'
};

/* =============================
   LISTAR PERMISOS
============================= */

async function listarPermisos() {
    const listaPermisos = document.getElementById('listaPermisos');
    const matrizPermisos = document.getElementById('matrizPermisos');

    if (listaPermisos) {
        listaPermisos.innerHTML = `
            <div class="permisos-loading">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <span>Cargando permisos existentes...</span>
            </div>
        `;
    }

    if (matrizPermisos) {
        matrizPermisos.innerHTML = `
            <div class="text-sm text-slate-400">
                Cargando permisos...
            </div>
        `;
    }

    try {
        const response = await apiGet('listar_permisos.php');
        const data = await leerJSONSeguro(response);

        if (!response.ok) {
            const mensaje = data.mensaje || 'No se pudieron cargar los permisos.';

            if (listaPermisos) {
                listaPermisos.innerHTML = `
                    <div class="permisos-empty permisos-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <p>${escaparHTML(mensaje)}</p>
                    </div>
                `;
            }

            if (matrizPermisos) {
                matrizPermisos.innerHTML = `
                    <p class="text-sm text-red-500">${escaparHTML(mensaje)}</p>
                `;
            }

            return;
        }

        const permisos = normalizarLista(data);
        permisosCargados = permisos;

        actualizarContadoresPermisos(permisos);
        cargarFiltroModulosPermisos(permisos);
        renderizarListaPermisos(permisos);
        renderizarMatrizPermisos(permisos);

        if (typeof actualizarResumenPermisos === 'function') {
            actualizarResumenPermisos();
        }

    } catch (error) {
        console.error(error);

        if (listaPermisos) {
            listaPermisos.innerHTML = `
                <div class="permisos-empty permisos-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p>Error al cargar los permisos existentes.</p>
                </div>
            `;
        }

        if (matrizPermisos) {
            matrizPermisos.innerHTML = `
                <p class="text-sm text-red-500">
                    Error al cargar la matriz de permisos.
                </p>
            `;
        }
    }
}

/* =============================
   CONTADORES
============================= */

function actualizarContadoresPermisos(permisos) {
    const totalPermisos = permisos.length;
    const totalModulos = [...new Set(permisos.map(p => p.modulo).filter(Boolean))].length;
    const totalPerfilesAsociados = permisos.reduce((acc, p) => acc + Number(p.cantidad_perfiles || 0), 0);
    const totalUsuariosAlcanzados = permisos.reduce((acc, p) => acc + Number(p.cantidad_usuarios || 0), 0);

    const resumenPermisosTotal = document.getElementById('resumenPermisosTotal');
    const resumenTotalPermisos = document.getElementById('resumenTotalPermisos');
    const resumenModulosTotal = document.getElementById('resumenModulosTotal');
    const resumenPerfilesAsociados = document.getElementById('resumenPerfilesAsociados');
    const resumenUsuariosAlcanzados = document.getElementById('resumenUsuariosAlcanzados');

    if (resumenPermisosTotal) resumenPermisosTotal.textContent = totalPermisos;
    if (resumenTotalPermisos) resumenTotalPermisos.textContent = totalPermisos;
    if (resumenModulosTotal) resumenModulosTotal.textContent = totalModulos;
    if (resumenPerfilesAsociados) resumenPerfilesAsociados.textContent = totalPerfilesAsociados;
    if (resumenUsuariosAlcanzados) resumenUsuariosAlcanzados.textContent = totalUsuariosAlcanzados;
}

/* =============================
   FILTROS
============================= */

function cargarFiltroModulosPermisos(permisos) {
    const select = document.getElementById('filtroModuloPermisos');
    if (!select) return;

    const valorActual = select.value;

    const modulos = [...new Set(permisos.map(p => p.modulo).filter(Boolean))];

    select.innerHTML = `<option value="">Todos los módulos</option>`;

    modulos.forEach(modulo => {
        const nombreModulo = NOMBRES_MODULOS[modulo] || capitalizarTexto(modulo);

        select.innerHTML += `
            <option value="${escaparHTML(modulo)}">
                ${escaparHTML(nombreModulo)}
            </option>
        `;
    });

    select.value = valorActual;
}

function filtrarListaPermisos() {
    const input = document.getElementById('buscarPermisoListado');
    const filtroTexto = input ? input.value.trim().toLowerCase() : '';

    const selectModulo = document.getElementById('filtroModuloPermisos');
    const filtroModulo = selectModulo ? selectModulo.value : '';

    document.querySelectorAll('.fila-permiso').forEach(fila => {
        const texto = fila.getAttribute('data-busqueda') || '';
        const modulo = fila.getAttribute('data-modulo') || '';

        const coincideTexto = !filtroTexto || texto.includes(filtroTexto);
        const coincideModulo = !filtroModulo || modulo === filtroModulo;

        fila.style.display = coincideTexto && coincideModulo ? '' : 'none';
    });

    actualizarMensajeFiltroPermisos();
}

function actualizarMensajeFiltroPermisos() {
    const mensaje = document.getElementById('mensajeFiltroPermisos');
    if (!mensaje) return;

    const filas = Array.from(document.querySelectorAll('.fila-permiso'));
    const visibles = filas.filter(f => f.style.display !== 'none').length;

    if (filas.length === 0) {
        mensaje.textContent = 'No hay permisos registrados.';
        return;
    }

    mensaje.textContent = `Mostrando ${visibles} de ${filas.length} permiso/s.`;
}

/* =============================
   PERMISOS EXISTENTES - TABLA COMPACTA
============================= */

function renderizarListaPermisos(permisos) {
    const contenedor = document.getElementById('listaPermisos');
    if (!contenedor) return;

    if (!permisos || permisos.length === 0) {
        contenedor.innerHTML = `
            <div class="permisos-empty">
                <i class="fa-solid fa-key"></i>
                <h4>No hay permisos registrados</h4>
                <p>Cuando cree permisos, aparecerán organizados en este listado.</p>
            </div>
        `;
        return;
    }

    contenedor.innerHTML = `
        <div class="permisos-table-wrapper">
            <table class="permisos-table">
                <thead>
                    <tr>
                        <th>Permiso</th>
                        <th>Módulo</th>
                        <th>Acción</th>
                        <th class="text-center">Perfiles</th>
                        <th class="text-center">Usuarios</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    ${permisos.map(p => {
                        const idPermiso = p.id_permiso || p.id || '';
                        const modulo = p.modulo || 'general';
                        const accion = p.accion || '';
                        const nombreModulo = NOMBRES_MODULOS[modulo] || capitalizarTexto(modulo);
                        const nombrePermiso = p.nombre_permiso || `${modulo}_${accion}`;
                        const icono = ICONOS_MODULOS[modulo] || 'fa-folder';

                        const cantidadPerfiles = Number(p.cantidad_perfiles || 0);
                        const cantidadUsuarios = Number(p.cantidad_usuarios || 0);

                        const textoBusqueda = `${nombrePermiso} ${modulo} ${accion} ${nombreModulo}`.toLowerCase();

                        return `
                            <tr class="fila-permiso"
                                data-modulo="${escaparHTML(modulo)}"
                                data-busqueda="${escaparHTML(textoBusqueda)}">

                                <td>
                                    <div class="permiso-nombre">
                                        <div class="permiso-icono">
                                            <i class="fa-solid ${escaparHTML(icono)}"></i>
                                        </div>

                                        <div>
                                            <strong>${escaparHTML(formatearNombrePermiso(modulo, accion))}</strong>
                                            <span>${escaparHTML(nombrePermiso)}</span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="permiso-badge-modulo">
                                        ${escaparHTML(nombreModulo)}
                                    </span>
                                </td>

                                <td>
                                    <span class="permiso-accion">
                                        ${escaparHTML(capitalizarTexto(accion))}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="permiso-numero">
                                        ${cantidadPerfiles}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="permiso-numero">
                                        ${cantidadUsuarios}
                                    </span>
                                </td>

                                <td class="text-center">
                                    ${
                                        cantidadPerfiles === 0
                                        ? `
                                            <button onclick='eliminarPermiso(${Number(idPermiso)}, ${JSON.stringify(nombrePermiso)})'
                                                    class="permiso-btn-eliminar"
                                                    title="Eliminar permiso">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        `
                                        : `
                                            <button disabled
                                                    class="permiso-btn-bloqueado"
                                                    title="No se puede eliminar porque está asignado a perfiles">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        `
                                    }
                                </td>

                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        </div>
    `;

    actualizarMensajeFiltroPermisos();
}

function formatearNombrePermiso(modulo, accion) {
    const nombreModulo = NOMBRES_MODULOS[modulo] || capitalizarTexto(modulo);
    const accionLimpia = capitalizarTexto(String(accion || '').replaceAll('_', ' '));

    return `${accionLimpia} ${nombreModulo}`;
}

/* =============================
   MATRIZ DE PERMISOS PARA CONFIGURAR PERFIL
============================= */

function renderizarMatrizPermisos(permisos) {
    const contenedor = document.getElementById('matrizPermisos');
    if (!contenedor) return;

    if (!permisos || permisos.length === 0) {
        contenedor.innerHTML = `
            <p class="text-sm text-slate-400">
                No hay permisos disponibles para configurar.
            </p>
        `;
        return;
    }

    const permisosPorModulo = agruparPermisosPorModulo(permisos);

    contenedor.innerHTML = Object.keys(permisosPorModulo).map(modulo => {
        const nombreModulo = NOMBRES_MODULOS[modulo] || capitalizarTexto(modulo);
        const listaPermisos = permisosPorModulo[modulo];
        const icono = ICONOS_MODULOS[modulo] || 'fa-folder';

        return `
            <div class="modulo-permisos-card rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
                 data-modulo="${escaparHTML(modulo)}">

                <div class="flex items-center justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="permiso-icono">
                            <i class="fa-solid ${escaparHTML(icono)}"></i>
                        </div>

                        <div>
                            <h4 class="font-extrabold text-slate-900">
                                ${escaparHTML(nombreModulo)}
                            </h4>

                            <p class="text-xs text-slate-400">
                                ${listaPermisos.length} permiso/s disponible/s
                            </p>
                        </div>
                    </div>

                    <span class="badge badge-soft">
                        ${escaparHTML(modulo)}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    ${listaPermisos.map(permiso => {
                        const idPermiso = permiso.id_permiso || permiso.id || '';
                        const accion = permiso.accion || '';
                        const valor = `${modulo}_${accion}`;

                        return `
                            <label class="permiso-item-config flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-100 px-3 py-2 cursor-pointer transition-all"
                                   data-busqueda="${escaparHTML((valor + ' ' + accion + ' ' + modulo + ' ' + nombreModulo).toLowerCase())}">

                                <input type="checkbox"
                                       class="permiso-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                       value="${escaparHTML(valor)}"
                                       data-id-permiso="${escaparHTML(idPermiso)}"
                                       data-modulo="${escaparHTML(modulo)}"
                                       data-accion="${escaparHTML(accion)}"
                                       onchange="actualizarResumenPermisos()">

                                <span class="text-xs font-semibold text-slate-700">
                                    ${escaparHTML(capitalizarTexto(accion))}
                                </span>
                            </label>
                        `;
                    }).join('')}
                </div>
            </div>
        `;
    }).join('');
}

function agruparPermisosPorModulo(permisos) {
    const agrupados = {};

    permisos.forEach(permiso => {
        const modulo = permiso.modulo || 'general';

        if (!agrupados[modulo]) {
            agrupados[modulo] = [];
        }

        agrupados[modulo].push(permiso);
    });

    return agrupados;
}

function filtrarPermisosConfig() {
    const input = document.getElementById('buscarPermisoConfig');
    const filtro = input ? input.value.trim().toLowerCase() : '';

    document.querySelectorAll('.modulo-permisos-card').forEach(card => {
        let tieneVisible = false;

        card.querySelectorAll('.permiso-item-config').forEach(item => {
            const texto = item.getAttribute('data-busqueda') || '';

            if (!filtro || texto.includes(filtro)) {
                item.style.display = '';
                tieneVisible = true;
            } else {
                item.style.display = 'none';
            }
        });

        card.style.display = tieneVisible ? '' : 'none';
    });
}

function marcarTodosPermisos() {
    document.querySelectorAll('.permiso-checkbox').forEach(check => {
        const item = check.closest('.permiso-item-config');

        if (!item || item.style.display !== 'none') {
            check.checked = true;
        }
    });

    if (typeof actualizarResumenPermisos === 'function') {
        actualizarResumenPermisos();
    }
}

function desmarcarTodosPermisos() {
    document.querySelectorAll('.permiso-checkbox').forEach(check => {
        check.checked = false;
    });

    if (typeof actualizarResumenPermisos === 'function') {
        actualizarResumenPermisos();
    }
}

function marcarSoloLectura() {
    document.querySelectorAll('.permiso-checkbox').forEach(check => {
        const accion = check.getAttribute('data-accion') || '';

        check.checked = ['ver', 'consultar', 'listar'].includes(accion);
    });

    if (typeof actualizarResumenPermisos === 'function') {
        actualizarResumenPermisos();
    }
}

/* =============================
   CREAR PERMISO
============================= */

async function crearPermiso() {
    const selectModulo = document.getElementById('selectModuloPermiso');
    const inputAccion = document.getElementById('nuevaAccionPermiso');

    if (!selectModulo || !inputAccion) {
        Swal.fire({
            icon: 'error',
            title: 'Formulario no encontrado',
            text: 'No se encontró el formulario para crear permisos.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const modulo = selectModulo.value.trim();
    const accionOriginal = inputAccion.value.trim();

    if (!modulo || !accionOriginal) {
        Swal.fire({
            icon: 'info',
            title: 'Campos incompletos',
            text: 'Seleccione un módulo existente y escriba la acción del permiso.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    if (!MODULOS_PERMITIDOS.includes(modulo)) {
        Swal.fire({
            icon: 'warning',
            title: 'Módulo inválido',
            text: 'Debe seleccionar un módulo existente del sistema.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s_]{2,40}$/.test(accionOriginal)) {
        Swal.fire({
            icon: 'warning',
            title: 'Acción inválida',
            text: 'La acción debe contener solo letras, espacios o guiones bajos.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const accionNormalizada = normalizarAccionPermiso(accionOriginal);

    if (!accionNormalizada) {
        Swal.fire({
            icon: 'warning',
            title: 'Acción inválida',
            text: 'No se pudo generar una acción válida para el permiso.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const permisoFinal = `${modulo}_${accionNormalizada}`;
    const nombreModulo = NOMBRES_MODULOS[modulo] || modulo;

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: '¿Crear permiso?',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <div style="
                    padding:14px 16px;
                    border-radius:18px;
                    background:#eff6ff;
                    border:1px solid #dbeafe;
                    margin-bottom:14px;
                ">
                    <p style="font-size:14px; color:#475569; margin:0;">
                        Se creará el siguiente permiso:
                    </p>

                    <p style="font-size:16px; margin:10px 0 0 0; color:#0f172a;">
                        <b>${escaparHTML(permisoFinal)}</b>
                    </p>

                    <p style="font-size:12px; margin:8px 0 0 0; color:#64748b;">
                        Módulo: <b>${escaparHTML(nombreModulo)}</b><br>
                        Acción: <b>${escaparHTML(accionNormalizada)}</b>
                    </p>
                </div>

                <p style="font-size:12px; color:#64748b; margin:0;">
                    Este permiso luego podrá asignarse a uno o varios perfiles.
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Sí, crear',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('crear_permiso.php', {
            modulo: modulo,
            accion: accionNormalizada
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Permiso creado',
                text: data.mensaje || 'El permiso fue creado correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            selectModulo.value = '';
            inputAccion.value = '';

            await listarPermisos();

            if (typeof listarPerfiles === 'function') {
                await listarPerfiles();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo crear el permiso');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para crear el permiso.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   ELIMINAR PERMISO
============================= */

async function eliminarPermiso(idPermiso, nombrePermiso) {
    const confirmacion = await Swal.fire({
        icon: 'warning',
        title: '¿Eliminar permiso?',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <div style="
                    padding:16px;
                    border-radius:18px;
                    background:#fef2f2;
                    border:1px solid #fecaca;
                    margin-bottom:14px;
                ">
                    <p style="font-size:14px; color:#475569; margin:0;">
                        Se eliminará del listado el permiso:
                    </p>

                    <p style="font-size:17px; margin:10px 0 0 0; color:#0f172a;">
                        <b>${escaparHTML(nombrePermiso)}</b>
                    </p>
                </div>

                <p style="font-size:12px; color:#64748b; margin:0;">
                    Esta eliminación es lógica: el permiso no se borra físicamente de la base de datos,
                    solo quedará inactivo y dejará de mostrarse en el sistema.
                </p>

                <p style="font-size:12px; color:#dc2626; margin-top:10px;">
                    Solo se puede eliminar un permiso si no está asignado a ningún perfil.
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('eliminar_permiso.php', {
            id_permiso: idPermiso
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Permiso eliminado',
                text: data.mensaje || 'El permiso fue eliminado correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await listarPermisos();

            if (typeof listarPerfiles === 'function') {
                await listarPerfiles();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo eliminar el permiso');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para eliminar el permiso.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   HELPERS
============================= */

function normalizarAccionPermiso(accion) {
    return String(accion)
        .trim()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/ñ/g, 'n')
        .replace(/Ñ/g, 'n')
        .replace(/\s+/g, '_')
        .replace(/_+/g, '_')
        .replace(/[^a-zA-Z_]/g, '')
        .toLowerCase();
}

function capitalizarTexto(texto) {
    if (!texto) return '';

    return String(texto)
        .charAt(0)
        .toUpperCase() + String(texto).slice(1);
}