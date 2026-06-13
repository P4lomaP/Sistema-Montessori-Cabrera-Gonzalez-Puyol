/* =============================
   USUARIOS Y MULTIPERFIL
============================= */

async function listarUsuarios() {
    try {
        const response = await apiGet('listar_usuarios.php');
        const data = await leerJSONSeguro(response);

        if (!response.ok) return [];

        if (Array.isArray(data)) return data;
        if (Array.isArray(data.usuarios)) return data.usuarios;
        if (Array.isArray(data.data)) return data.data;

        return [];
    } catch (error) {
        console.error(error);
        return [];
    }
}

async function cargarUsuariosYPerfiles() {
    usuariosCargados = await listarUsuarios();

    if (perfilesCargados.length === 0) {
        await listarPerfiles();
    }

    cargarUsuariosEnSelect(usuariosCargados);
    cargarPerfilesEnSelectAsignacion(perfilesCargados);

    const contador = document.getElementById('contadorUsuarios');

    if (contador) {
        contador.textContent = usuariosCargados.length;
    }
}

function cargarUsuariosEnSelect(usuarios) {
    const select = document.getElementById('selectUsuarioPerfil');
    if (!select) return;

    const valorActual = select.value;

    select.innerHTML = '<option value="">Seleccione usuario</option>';

    usuarios.forEach(u => {
        const idUsuario = u.id_usuario;
        const nombreCompleto = `${u.nombre || ''} ${u.apellido || ''}`.trim();
        const dni = u.dni || '-';
        const estado = String(u.estado_activo) === '1' ? 'Activo' : 'Inactivo';

        select.innerHTML += `
            <option value="${idUsuario}">
                ${escaparHTML(nombreCompleto)} - DNI ${escaparHTML(dni)} (${estado})
            </option>
        `;
    });

    if (valorActual) {
        select.value = valorActual;
    }
}

async function cargarPerfilesDelUsuarioSeleccionado() {
    const select = document.getElementById('selectUsuarioPerfil');
    const idUsuario = select ? select.value : '';

    if (!idUsuario) {
        renderPerfilesUsuario(null, [], []);
        return;
    }

    try {
        const response = await apiPost('listar_perfiles_usuario.php', {
            id_usuario: idUsuario
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            ultimoUsuarioSeleccionado = data.usuario || null;
            renderPerfilesUsuario(
                data.usuario || null,
                data.perfiles || [],
                data.permisos_finales || []
            );
        } else {
            mostrarErrorBackend(data, 'No se pudieron cargar los perfiles del usuario');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para cargar los perfiles del usuario.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

function renderPerfilesUsuario(usuario, perfiles, permisosFinales) {
    const textoUsuario = document.getElementById('textoUsuarioSeleccionado');
    const contenedor = document.getElementById('perfilesActualesUsuario');
    const contador = document.getElementById('contadorPerfilesUsuario');
    const permisosTexto = document.getElementById('permisosFinalesUsuario');

    if (!textoUsuario || !contenedor || !contador || !permisosTexto) return;

    if (!usuario) {
        textoUsuario.textContent = 'Seleccione un usuario para ver sus perfiles.';
        contenedor.innerHTML = '<p class="text-sm text-slate-400">Sin usuario seleccionado.</p>';
        contador.textContent = '0 perfiles';
        permisosTexto.textContent = 'Seleccione un usuario para ver la suma de permisos de todos sus perfiles.';
        return;
    }

    const nombreUsuario = `${usuario.nombre || ''} ${usuario.apellido || ''}`.trim();

    textoUsuario.textContent = `${nombreUsuario} - DNI ${usuario.dni || '-'}`;
    contador.textContent = `${perfiles.length} perfil/es`;

    if (perfiles.length === 0) {
        contenedor.innerHTML = '<p class="text-sm text-slate-400">Este usuario no tiene perfiles asignados.</p>';
    } else {
        contenedor.innerHTML = perfiles.map(p => {
            const idPerfil = p.id_perfil || p.id;
            const nombrePerfil = p.nombre || p.nombre_perfil || 'Perfil';

            return `
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-50 text-blue-700 border border-blue-100 text-xs font-bold">
                    ${escaparHTML(nombrePerfil)}

                    <button onclick="quitarPerfilUsuario(${usuario.id_usuario}, ${idPerfil})"
                            title="Quitar perfil"
                            class="w-5 h-5 rounded-full bg-white border border-blue-200 hover:bg-red-50 hover:text-red-600 flex items-center justify-center">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    </button>
                </span>
            `;
        }).join('');
    }

    permisosTexto.textContent = permisosFinales.length > 0
        ? permisosFinales.join(', ')
        : 'El usuario no tiene permisos heredados.';
}

async function asignarPerfilUsuario() {
    const selectUsuario = document.getElementById('selectUsuarioPerfil');
    const selectPerfil = document.getElementById('selectPerfilUsuario');

    const idUsuario = selectUsuario ? selectUsuario.value : '';
    const idPerfil = selectPerfil ? selectPerfil.value : '';

    if (!idUsuario || !idPerfil) {
        Swal.fire({
            icon: 'info',
            title: 'Campos incompletos',
            text: 'Seleccione un usuario y un perfil.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const usuarioTexto = selectUsuario.options[selectUsuario.selectedIndex].text;
    const perfilTexto = selectPerfil.options[selectPerfil.selectedIndex].text;

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: '¿Asignar perfil?',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <p style="font-size:14px; color:#475569;">
                    Se asignará el perfil:
                </p>

                <p style="font-size:15px; margin-top:8px;">
                    <b>${escaparHTML(perfilTexto)}</b>
                </p>

                <p style="font-size:14px; color:#475569; margin-top:14px;">
                    Al usuario:
                </p>

                <p style="font-size:15px; margin-top:8px;">
                    <b>${escaparHTML(usuarioTexto)}</b>
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Sí, asignar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('asignar_perfil_usuario.php', {
            id_usuario: idUsuario,
            id_perfil: idPerfil
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Perfil asignado',
                text: data.mensaje || 'El perfil fue asignado al usuario correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            selectPerfil.value = '';

            await cargarPerfilesDelUsuarioSeleccionado();

            if (typeof listarPerfiles === 'function') {
                await listarPerfiles();
            }

            if (typeof listarPermisos === 'function') {
                await listarPermisos();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo asignar el perfil');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para asignar el perfil.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

async function quitarPerfilUsuario(idUsuario, idPerfil) {
    const confirmacion = await Swal.fire({
        icon: 'warning',
        title: '¿Quitar perfil?',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <p style="font-size:14px; color:#475569; margin:0;">
                    Se quitará este perfil al usuario seleccionado.
                </p>

                <p style="font-size:12px; color:#64748b; margin-top:10px;">
                    Esto no elimina el perfil del sistema. Solo deja de estar asignado a este usuario.
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Sí, quitar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('quitar_perfil_usuario.php', {
            id_usuario: idUsuario,
            id_perfil: idPerfil
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Perfil quitado',
                text: data.mensaje || 'El perfil fue quitado correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await cargarPerfilesDelUsuarioSeleccionado();

            if (typeof listarPerfiles === 'function') {
                await listarPerfiles();
            }

            if (typeof listarPermisos === 'function') {
                await listarPermisos();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo quitar el perfil');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para quitar el perfil.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}