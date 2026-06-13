/* =============================
   PERFILES
============================= */

async function listarPerfiles() {
    const contenedor = document.getElementById('listaPerfiles');
    if (!contenedor) return;

    try {
        const response = await apiGet('listar_perfiles.php');
        const data = await leerJSONSeguro(response);

        if (!response.ok) {
            contenedor.innerHTML = `<p class="text-sm text-red-500">${data.mensaje || 'No se pudieron cargar los perfiles.'}</p>`;
            return;
        }

        const perfiles = normalizarLista(data);
        perfilesCargados = perfiles;

        cargarPerfilesEnSelect(perfiles);
        cargarPerfilesEnSelectAsignacion(perfiles);

        const contador = document.getElementById('contadorPerfiles');
        const resumenTotalPerfiles = document.getElementById('resumenTotalPerfiles');

        if (contador) contador.textContent = perfiles.length;
        if (resumenTotalPerfiles) resumenTotalPerfiles.textContent = perfiles.length;

        if (perfiles.length === 0) {
            contenedor.innerHTML = '<p class="text-sm text-slate-400">No hay perfiles registrados.</p>';
            return;
        }

        contenedor.innerHTML = perfiles.map(p => {
            const idPerfil = p.id_perfil || p.id;
            const nombre = p.nombre || p.nombre_perfil || 'Perfil';
            const descripcion = p.descripcion || 'Sin descripción';
            const cantidadUsuarios = p.cantidad_usuarios || 0;
            const cantidadPermisos = p.cantidad_permisos || contarPermisosTexto(p.permisos);
            const permisosTexto = p.permisos || 'Sin permisos asignados';

            const puedeEliminar = Number(idPerfil) !== 1 && Number(cantidadUsuarios) === 0;

            return `
                <div class="w-full text-left p-4 rounded-xl border border-blue-100 bg-blue-50/40 hover:bg-blue-50 transition-all">
                    
                    <div onclick="seleccionarPerfilDesdeCard('${idPerfil}')" class="cursor-pointer">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-slate-900">${escaparHTML(nombre)}</h4>
                                <p class="text-xs text-slate-500 mt-1">${escaparHTML(descripcion)}</p>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <span class="badge">${cantidadUsuarios} usuario/s</span>
                                <span class="badge badge-soft">${cantidadPermisos} permiso/s</span>
                            </div>
                        </div>

                        <div class="mt-3 bg-white/70 border border-blue-100 rounded-xl p-3">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1">Permisos asignados</p>
                            <p class="text-xs text-slate-600 break-words">${escaparHTML(permisosTexto)}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap justify-end gap-2">

                        <button onclick="editarPerfil(${idPerfil})"
                                class="h-9 px-4 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold transition-all">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>
                            Editar
                        </button>

                        <button onclick="seleccionarPerfilDesdeCard('${idPerfil}')"
                                class="h-9 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
                            <i class="fa-solid fa-sliders mr-1"></i>
                            Configurar
                        </button>

                        ${
                            puedeEliminar
                            ? `
                                <button onclick='eliminarPerfil(${idPerfil}, ${JSON.stringify(nombre)})'
                                        class="h-9 px-4 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 text-xs font-bold transition-all">
                                    <i class="fa-solid fa-trash mr-1"></i>
                                    Eliminar
                                </button>
                            `
                            : `
                                <button disabled
                                        title="No se puede eliminar si tiene usuarios asignados o es el perfil principal"
                                        class="h-9 px-4 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-bold cursor-not-allowed">
                                    <i class="fa-solid fa-lock mr-1"></i>
                                    No eliminable
                                </button>
                            `
                        }
                    </div>
                </div>
            `;
        }).join('');

    } catch (error) {
        console.error(error);
        contenedor.innerHTML = '<p class="text-sm text-red-500">No se pudieron cargar los perfiles.</p>';
    }
}

function contarPermisosTexto(permisosTexto) {
    if (!permisosTexto || permisosTexto === 'Sin permisos asignados') return 0;

    return String(permisosTexto)
        .split(',')
        .map(p => p.trim())
        .filter(Boolean)
        .length;
}

function cargarPerfilesEnSelect(perfiles) {
    const select = document.getElementById('selectPerfilConfig');
    if (!select) return;

    const valorActual = select.value;

    select.innerHTML = '<option value="">Seleccione un perfil</option>';

    perfiles.forEach(p => {
        const idPerfil = p.id_perfil || p.id;
        const nombre = p.nombre || p.nombre_perfil || 'Perfil';

        select.innerHTML += `<option value="${idPerfil}">${escaparHTML(nombre)}</option>`;
    });

    if (valorActual) {
        select.value = valorActual;
    }
}

function cargarPerfilesEnSelectAsignacion(perfiles) {
    const select = document.getElementById('selectPerfilUsuario');
    if (!select) return;

    const valorActual = select.value;

    select.innerHTML = '<option value="">Seleccione perfil</option>';

    perfiles.forEach(p => {
        const idPerfil = p.id_perfil || p.id;
        const nombre = p.nombre || p.nombre_perfil || 'Perfil';
        const cantidadUsuarios = p.cantidad_usuarios || 0;

        select.innerHTML += `
            <option value="${idPerfil}">
                ${escaparHTML(nombre)} (${cantidadUsuarios} usuario/s)
            </option>
        `;
    });

    if (valorActual) {
        select.value = valorActual;
    }
}

function seleccionarPerfilDesdeCard(idPerfil) {
    mostrarTabPerfiles('tab-configurar-perfil');

    setTimeout(() => {
        const select = document.getElementById('selectPerfilConfig');
        if (!select) return;

        select.value = idPerfil;
        seleccionarPerfilConfig();

        const bloquePermisos = document.getElementById('matrizPermisos');

        if (bloquePermisos) {
            bloquePermisos.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }, 150);
}

function seleccionarPerfilConfig() {
    limpiarChecksPermisos();

    const select = document.getElementById('selectPerfilConfig');
    const idPerfil = select ? select.value : '';

    if (!idPerfil) {
        actualizarResumenPermisos();
        return;
    }

    const perfil = perfilesCargados.find(p => String(p.id_perfil || p.id) === String(idPerfil));

    if (perfil && perfil.permisos) {
        marcarPermisosDelPerfil(perfil.permisos);
    }

    actualizarResumenPermisos();
}

function marcarPermisosDelPerfil(permisosTexto) {
    if (!permisosTexto || permisosTexto === 'Sin permisos asignados') return;

    const permisosArray = String(permisosTexto)
        .split(',')
        .map(p => p.trim())
        .filter(p => p.length > 0);

    document.querySelectorAll('.permiso-checkbox').forEach(check => {
        if (permisosArray.includes(check.value)) {
            check.checked = true;
        }
    });
}

function limpiarChecksPermisos() {
    document.querySelectorAll('.permiso-checkbox').forEach(check => {
        check.checked = false;
    });
}

function actualizarResumenPermisos() {
    const resumen = document.getElementById('resumenPermisosSeleccionados');
    const select = document.getElementById('selectPerfilConfig');
    const idPerfil = select ? select.value : '';
    const seleccionados = obtenerPermisosMarcados();

    if (!resumen) return;

    if (!idPerfil) {
        resumen.textContent = 'Seleccione un perfil para configurar sus permisos.';
        return;
    }

    if (seleccionados.length === 0) {
        resumen.textContent = 'No hay permisos seleccionados para este perfil.';
        return;
    }

    resumen.textContent = `Permisos seleccionados: ${seleccionados.length}`;
}

function obtenerPermisosMarcados() {
    const checks = document.querySelectorAll('.permiso-checkbox:checked');

    return Array.from(checks).map(check => ({
        modulo: check.getAttribute('data-modulo'),
        accion: check.getAttribute('data-accion')
    }));
}

/* =============================
   CREAR PERFIL
============================= */

async function crearPerfil() {
    const nombre = document.getElementById('nombrePerfil').value.trim();
    const descripcion = document.getElementById('descripcionPerfil').value.trim();

    if (!nombre || !descripcion) {
        Swal.fire({
            icon: 'info',
            title: 'Campos incompletos',
            text: 'Ingrese nombre y descripción del perfil.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: '¿Crear perfil?',
        text: `Se creará el perfil "${nombre}".`,
        showCancelButton: true,
        confirmButtonText: 'Sí, crear',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('crear_perfil.php', {
            nombre_perfil: nombre,
            nombre: nombre,
            descripcion: descripcion
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Perfil creado',
                text: data.mensaje || 'El perfil fue creado correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            document.getElementById('nombrePerfil').value = '';
            document.getElementById('descripcionPerfil').value = '';

            await listarPerfiles();

        } else {
            mostrarErrorBackend(data, 'No se pudo crear el perfil');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para crear el perfil.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   EDITAR PERFIL
============================= */

async function editarPerfil(idPerfil) {
    const perfil = perfilesCargados.find(p => String(p.id_perfil || p.id) === String(idPerfil));

    if (!perfil) {
        Swal.fire({
            icon: 'error',
            title: 'Perfil no encontrado',
            text: 'No se pudo encontrar la información del perfil seleccionado.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const nombreActual = perfil.nombre || perfil.nombre_perfil || '';
    const descripcionActual = perfil.descripcion || '';

    const resultado = await Swal.fire({
        title: 'Editar perfil',
        html: `
            <div style="text-align:left;">
                <label style="font-size:12px; font-weight:700; color:#475569;">Nombre del perfil</label>
                <input id="swalNombrePerfil"
                       class="swal2-input"
                       style="width:90%; margin:8px auto 16px auto;"
                       value="${escaparHTML(nombreActual)}"
                       placeholder="Ej: Gestión institucional completa">

                <label style="font-size:12px; font-weight:700; color:#475569;">Descripción</label>
                <textarea id="swalDescripcionPerfil"
                          class="swal2-textarea"
                          style="width:90%; margin:8px auto;"
                          placeholder="Ej: Acceso completo a todos los módulos">${escaparHTML(descripcionActual)}</textarea>

                <p style="font-size:12px; color:#64748b; margin-top:12px;">
                    Recomendación: use nombres funcionales, por ejemplo “Carga de asistencia”, “Consulta documental” o “Gestión institucional completa”.
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar cambios',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
        preConfirm: () => {
            const nombre = document.getElementById('swalNombrePerfil').value.trim();
            const descripcion = document.getElementById('swalDescripcionPerfil').value.trim();

            if (!nombre || !descripcion) {
                Swal.showValidationMessage('Debe completar nombre y descripción.');
                return false;
            }

            if (nombre.length < 3) {
                Swal.showValidationMessage('El nombre debe tener al menos 3 caracteres.');
                return false;
            }

            return {
                nombre: nombre,
                descripcion: descripcion
            };
        }
    });

    if (!resultado.isConfirmed) return;

    try {
        const response = await apiPost('editar_perfil.php', {
            id_perfil: idPerfil,
            nombre_perfil: resultado.value.nombre,
            descripcion: resultado.value.descripcion
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Perfil actualizado',
                text: data.mensaje || 'El perfil fue actualizado correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await listarPerfiles();

            if (typeof cargarUsuariosYPerfiles === 'function') {
                await cargarUsuariosYPerfiles();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo editar el perfil');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para editar el perfil.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   GUARDAR PERMISOS DEL PERFIL
============================= */

async function guardarPermisosPerfil() {
    const select = document.getElementById('selectPerfilConfig');
    const idPerfil = select ? select.value : '';

    if (!idPerfil) {
        Swal.fire({
            icon: 'info',
            title: 'Seleccione un perfil',
            text: 'Primero debe seleccionar el perfil que desea configurar.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const permisos = obtenerPermisosMarcados();
    const perfilTexto = select.options[select.selectedIndex].text;

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: '¿Guardar permisos?',
        text: `Se actualizarán los permisos del perfil "${perfilTexto}".`,
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('guardar_permisos_perfil.php', {
            id_perfil: idPerfil,
            permisos: permisos
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Permisos guardados',
                text: data.mensaje || 'Los permisos del perfil fueron actualizados correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await listarPerfiles();

            if (typeof listarPermisos === 'function') {
                await listarPermisos();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudieron guardar los permisos');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para guardar permisos.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   ELIMINAR PERFIL
============================= */

async function eliminarPerfil(idPerfil, nombrePerfil) {
    const confirmacion = await Swal.fire({
        icon: 'warning',
        title: '¿Eliminar perfil?',
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
                        Se eliminará del listado el perfil:
                    </p>

                    <p style="font-size:17px; margin:10px 0 0 0; color:#0f172a;">
                        <b>${escaparHTML(nombrePerfil)}</b>
                    </p>
                </div>

                <p style="font-size:12px; color:#64748b; margin:0;">
                    Esta eliminación es lógica: el perfil no se borra físicamente de la base de datos,
                    solo quedará inactivo y dejará de mostrarse en el sistema.
                </p>

                <p style="font-size:12px; color:#dc2626; margin-top:10px;">
                    Solo se puede eliminar un perfil si no tiene usuarios asignados.
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
        const response = await apiPost('eliminar_perfil.php', {
            id_perfil: idPerfil
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Perfil eliminado',
                text: data.mensaje || 'El perfil fue eliminado correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await listarPerfiles();

            if (typeof listarPermisos === 'function') {
                await listarPermisos();
            }

            if (typeof cargarUsuariosYPerfiles === 'function') {
                await cargarUsuariosYPerfiles();
            }

            const selectPerfilConfig = document.getElementById('selectPerfilConfig');

            if (selectPerfilConfig) {
                selectPerfilConfig.value = '';
            }

            limpiarChecksPermisos();
            actualizarResumenPermisos();

        } else {
            mostrarErrorBackend(data, 'No se pudo eliminar el perfil');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para eliminar el perfil.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}