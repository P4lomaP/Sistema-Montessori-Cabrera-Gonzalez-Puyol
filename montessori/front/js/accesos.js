/* =============================
   USUARIOS PENDIENTES / DESBLOQUEOS
============================= */

let usuariosAccesosCargados = [];

async function listarPendientes() {
    try {
        const response = await apiGet('listar_pendientes.php');
        const data = await leerJSONSeguro(response);

        if (!response.ok) {
            cargarTablaPendientes([]);
            cargarTablaDesbloqueos([]);
            return;
        }

        const usuarios = normalizarLista(data);

        cargarTablaPendientes(usuarios);
        cargarTablaDesbloqueos(usuarios);

        const contador = document.getElementById('contadorPendientes');
        if (contador) contador.textContent = usuarios.length;

    } catch (error) {
        console.error(error);
        cargarTablaPendientes([]);
        cargarTablaDesbloqueos([]);
    }
}

function cargarTablaPendientes(usuarios) {
    const tbody = document.getElementById('tablaPendientes');
    if (!tbody) return;

    const pendientesActivacion = usuarios.filter(u => String(u.estado_activo) === '0' || u.tipo === 'activacion');

    if (pendientesActivacion.length === 0) {
        tbody.innerHTML = `<tr><td colspan="3" class="tabla-vacia">No hay usuarios pendientes.</td></tr>`;
        return;
    }

    tbody.innerHTML = pendientesActivacion.map(u => `
        <tr>
            <td class="p-4 font-semibold text-slate-700">${escaparHTML(u.dni || '-')}</td>
            <td class="p-4 text-slate-600">${escaparHTML((u.nombre || '') + ' ' + (u.apellido || ''))}</td>
            <td class="p-4 text-right">
                <button onclick="activarUsuario(${u.id_usuario})"
                        class="h-9 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold">
                    Activar
                </button>
            </td>
        </tr>
    `).join('');
}

function cargarTablaDesbloqueos(usuarios) {
    const tbody = document.getElementById('tablaDesbloqueos');
    if (!tbody) return;

    const pendientesDesbloqueo = usuarios.filter(u => u.estado_desbloqueo === 'pendiente_aprobacion' || u.tipo === 'desbloqueo');

    if (pendientesDesbloqueo.length === 0) {
        tbody.innerHTML = `<tr><td colspan="3" class="tabla-vacia">No hay solicitudes de desbloqueo.</td></tr>`;
        return;
    }

    tbody.innerHTML = pendientesDesbloqueo.map(u => `
        <tr>
            <td class="p-4 font-semibold text-slate-700">${escaparHTML(u.dni || '-')}</td>
            <td class="p-4 text-slate-600">${escaparHTML((u.nombre || '') + ' ' + (u.apellido || ''))}</td>
            <td class="p-4 text-right">
                <button onclick="aprobarDesbloqueo(${u.id_usuario})"
                        class="h-9 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold">
                    Desbloquear
                </button>
            </td>
        </tr>
    `).join('');
}

async function activarUsuario(idUsuario) {
    const confirmacion = await Swal.fire({
        icon: 'warning',
        title: '¿Activar usuario?',
        text: 'Esta acción habilitará el acceso del usuario al sistema.',
        showCancelButton: true,
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('activar_usuario.php', { id_usuario: idUsuario });
        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Usuario activado',
                text: data.mensaje || 'La cuenta fue activada correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await listarPendientes();
            await listarUsuariosAccesos();

            if (typeof cargarUsuariosYPerfiles === 'function') {
                await cargarUsuariosYPerfiles();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo activar');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

async function aprobarDesbloqueo(idUsuario) {
    const confirmacion = await Swal.fire({
        icon: 'warning',
        title: '¿Desbloquear cuenta?',
        text: 'Esta acción permitirá que el usuario vuelva a iniciar sesión.',
        showCancelButton: true,
        confirmButtonText: 'Sí, desbloquear',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('aprobar_desbloqueo.php', { id_usuario: idUsuario });
        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Cuenta desbloqueada',
                text: data.mensaje || 'El usuario ya puede iniciar sesión nuevamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await listarPendientes();
            await listarUsuariosAccesos();

            if (typeof cargarUsuariosYPerfiles === 'function') {
                await cargarUsuariosYPerfiles();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo desbloquear');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   REGISTRAR USUARIO DESDE ADMIN
============================= */

async function registrarUsuarioDesdeAdmin() {
    const dni = document.getElementById('registroDni').value.trim();
    const nombre = document.getElementById('registroNombre').value.trim();
    const apellido = document.getElementById('registroApellido').value.trim();
    const correo = document.getElementById('registroCorreo').value.trim();
    const password = document.getElementById('registroPassword').value.trim();

    if (!dni || !nombre || !apellido || !correo || !password) {
        Swal.fire({
            icon: 'info',
            title: 'Campos incompletos',
            text: 'Complete DNI, nombre, apellido, correo y contraseña inicial.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    if (!/^[0-9]{7,8}$/.test(dni)) {
        Swal.fire({
            icon: 'warning',
            title: 'DNI inválido',
            text: 'El DNI debe tener solo números y entre 7 y 8 dígitos.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    if (!validarTextoSoloLetras(nombre)) {
        Swal.fire({
            icon: 'warning',
            title: 'Nombre inválido',
            text: 'El nombre debe contener solo letras y tener al menos 2 caracteres.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    if (!validarTextoSoloLetras(apellido)) {
        Swal.fire({
            icon: 'warning',
            title: 'Apellido inválido',
            text: 'El apellido debe contener solo letras y tener al menos 2 caracteres.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    if (!validarCorreoSimple(correo)) {
        Swal.fire({
            icon: 'warning',
            title: 'Correo inválido',
            text: 'Ingrese un correo electrónico válido. Ejemplo: usuario@gmail.com',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    if (password.length < 6) {
        Swal.fire({
            icon: 'warning',
            title: 'Contraseña muy corta',
            text: 'La contraseña inicial debe tener al menos 6 caracteres.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: '¿Registrar usuario?',
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
                        Se registrará el siguiente usuario:
                    </p>
                    <p style="font-size:15px; margin:8px 0 0 0; color:#0f172a;">
                        <b>${escaparHTML(nombre)} ${escaparHTML(apellido)}</b><br>
                        DNI: <b>${escaparHTML(dni)}</b><br>
                        Correo: <b>${escaparHTML(correo)}</b>
                    </p>
                </div>

                <p style="font-size:12px; color:#64748b; margin:0;">
                    La cuenta quedará pendiente de activación hasta que sea habilitada por el administrador.
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Sí, registrar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('registro.php', {
            dni: dni,
            nombre: nombre,
            apellido: apellido,
            correo: correo,
            password: password
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Usuario registrado',
                text: data.mensaje || 'El usuario fue registrado y quedó pendiente de activación.',
                confirmButtonColor: '#1d4ed8'
            });

            limpiarFormularioRegistroAdmin();

            await listarPendientes();
            await listarUsuariosAccesos();

            if (typeof cargarUsuariosYPerfiles === 'function') {
                await cargarUsuariosYPerfiles();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo registrar');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para registrar el usuario.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   VALIDACIONES AUXILIARES
============================= */

function validarCorreoSimple(correo) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(correo);
}

function validarTextoSoloLetras(texto) {
    return /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]{2,60}$/.test(texto);
}

function limpiarFormularioRegistroAdmin() {
    document.getElementById('registroDni').value = '';
    document.getElementById('registroNombre').value = '';
    document.getElementById('registroApellido').value = '';
    document.getElementById('registroCorreo').value = '';
    document.getElementById('registroPassword').value = '';
}

/* =============================
   LISTAR USUARIOS REGISTRADOS
============================= */

async function listarUsuariosAccesos() {
    const tbody = document.getElementById('tablaUsuariosAccesos');
    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="5" class="tabla-vacia">Cargando usuarios...</td>
        </tr>
    `;

    try {
        const response = await apiGet('listar_usuarios.php');
        const data = await leerJSONSeguro(response);

        if (!response.ok) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="tabla-vacia">
                        ${data.mensaje || 'No se pudieron cargar los usuarios.'}
                    </td>
                </tr>
            `;
            return;
        }

        const usuarios = normalizarLista(data);
        usuariosAccesosCargados = usuarios;

        if (usuarios.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="tabla-vacia">No hay usuarios registrados.</td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = usuarios.map(u => {
            const activo = String(u.estado_activo) === '1';

            let estadoHTML = '';

            if (u.estado_cuenta === 'bloqueada') {
                estadoHTML = `<span class="badge bg-red-50 text-red-600 border-red-100">Bloqueada</span>`;
            } else if (u.estado_cuenta === 'pendiente_desbloqueo') {
                estadoHTML = `<span class="badge bg-amber-50 text-amber-600 border-amber-100">Pendiente desbloqueo</span>`;
            } else if (activo) {
                estadoHTML = `<span class="badge">Habilitada</span>`;
            } else {
                estadoHTML = `<span class="badge badge-soft">Deshabilitada</span>`;
            }

            return `
                <tr>
                    <td class="p-4 font-semibold text-slate-700">
                        ${escaparHTML(u.dni || '-')}
                    </td>

                    <td class="p-4 text-slate-600">
                        <div class="font-bold text-slate-800">
                            ${escaparHTML((u.nombre || '') + ' ' + (u.apellido || ''))}
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">
                            ${escaparHTML(u.perfiles || 'Sin perfiles asignados')}
                        </div>
                    </td>

                    <td class="p-4 text-slate-600">
                        ${escaparHTML(u.correo || '-')}
                    </td>

                    <td class="p-4">
                        ${estadoHTML}
                    </td>

                    <td class="p-4 text-right">
                        <div class="flex flex-wrap justify-end gap-2">

                            <button onclick="editarUsuarioRegistrado(${u.id_usuario})"
                                    class="h-9 px-4 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold">
                                <i class="fa-solid fa-pen-to-square mr-1"></i>
                                Editar
                            </button>

                            ${
                                activo
                                ? `
                                    <button onclick="cambiarEstadoUsuario(${u.id_usuario}, 0)"
                                            class="h-9 px-4 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 text-xs font-bold">
                                        Deshabilitar
                                    </button>
                                `
                                : `
                                    <button onclick="cambiarEstadoUsuario(${u.id_usuario}, 1)"
                                            class="h-9 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold">
                                        Habilitar
                                    </button>
                                `
                            }
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

    } catch (error) {
        console.error(error);

        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="tabla-vacia">Error al cargar usuarios.</td>
            </tr>
        `;
    }
}

/* =============================
   EDITAR USUARIO REGISTRADO
============================= */

async function editarUsuarioRegistrado(idUsuario) {
    const usuario = usuariosAccesosCargados.find(u => String(u.id_usuario) === String(idUsuario));

    if (!usuario) {
        Swal.fire({
            icon: 'error',
            title: 'Usuario no encontrado',
            text: 'No se pudo encontrar la información del usuario seleccionado.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const resultado = await Swal.fire({
        title: '',
        width: 620,
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif; padding:4px 2px;">
                <div style="
                    display:flex;
                    align-items:center;
                    gap:12px;
                    padding:14px 16px;
                    border-radius:20px;
                    background:linear-gradient(135deg, #eff6ff, #ffffff);
                    border:1px solid #dbeafe;
                    margin-bottom:18px;
                ">
                    <div style="
                        width:46px;
                        height:46px;
                        border-radius:16px;
                        background:#2563eb;
                        color:white;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                    ">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>

                    <div>
                        <h2 style="margin:0; font-size:20px; font-weight:800; color:#0f172a;">
                            Editar usuario
                        </h2>
                        <p style="margin:4px 0 0 0; font-size:12px; color:#64748b;">
                            Modifique los datos registrados de la cuenta seleccionada.
                        </p>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                    <div>
                        <label style="font-size:12px; font-weight:800; color:#475569;">DNI</label>
                        <input id="swalUsuarioDni"
                               class="swal2-input"
                               maxlength="8"
                               minlength="7"
                               inputmode="numeric"
                               style="width:100%; margin:6px 0 0 0; height:44px; border-radius:14px; border:1px solid #cbd5e1; font-size:14px;"
                               value="${escaparHTML(usuario.dni || '')}"
                               placeholder="Ej: 45970018">
                    </div>

                    <div>
                        <label style="font-size:12px; font-weight:800; color:#475569;">Correo electrónico</label>
                        <input id="swalUsuarioCorreo"
                               class="swal2-input"
                               style="width:100%; margin:6px 0 0 0; height:44px; border-radius:14px; border:1px solid #cbd5e1; font-size:14px;"
                               value="${escaparHTML(usuario.correo || '')}"
                               placeholder="Ej: usuario@gmail.com">
                    </div>

                    <div>
                        <label style="font-size:12px; font-weight:800; color:#475569;">Nombre</label>
                        <input id="swalUsuarioNombre"
                               class="swal2-input"
                               maxlength="60"
                               style="width:100%; margin:6px 0 0 0; height:44px; border-radius:14px; border:1px solid #cbd5e1; font-size:14px;"
                               value="${escaparHTML(usuario.nombre || '')}"
                               placeholder="Ej: Maximiliano">
                    </div>

                    <div>
                        <label style="font-size:12px; font-weight:800; color:#475569;">Apellido</label>
                        <input id="swalUsuarioApellido"
                               class="swal2-input"
                               maxlength="60"
                               style="width:100%; margin:6px 0 0 0; height:44px; border-radius:14px; border:1px solid #cbd5e1; font-size:14px;"
                               value="${escaparHTML(usuario.apellido || '')}"
                               placeholder="Ej: Cabrera">
                    </div>
                </div>

                <div style="
                    margin-top:16px;
                    padding:12px 14px;
                    border-radius:16px;
                    background:#f8fafc;
                    border:1px solid #e2e8f0;
                    color:#64748b;
                    font-size:12px;
                    line-height:1.5;
                ">
                    <b style="color:#334155;">Validaciones:</b>
                    DNI solo números, nombre y apellido solo letras, y correo con formato válido.
                    La contraseña no se modifica desde esta ventana.
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-floppy-disk"></i> Guardar cambios',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
        didOpen: () => {
            const dniInput = document.getElementById('swalUsuarioDni');
            const nombreInput = document.getElementById('swalUsuarioNombre');
            const apellidoInput = document.getElementById('swalUsuarioApellido');

            dniInput.addEventListener('input', () => {
                dniInput.value = dniInput.value.replace(/\D/g, '');
            });

            nombreInput.addEventListener('input', () => {
                nombreInput.value = nombreInput.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
            });

            apellidoInput.addEventListener('input', () => {
                apellidoInput.value = apellidoInput.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
            });
        },
        preConfirm: () => {
            const dni = document.getElementById('swalUsuarioDni').value.trim();
            const nombre = document.getElementById('swalUsuarioNombre').value.trim();
            const apellido = document.getElementById('swalUsuarioApellido').value.trim();
            const correo = document.getElementById('swalUsuarioCorreo').value.trim();

            if (!/^[0-9]{7,8}$/.test(dni)) {
                Swal.showValidationMessage('El DNI debe tener solo números y entre 7 y 8 dígitos.');
                return false;
            }

            if (!validarTextoSoloLetras(nombre)) {
                Swal.showValidationMessage('El nombre debe contener solo letras y tener al menos 2 caracteres.');
                return false;
            }

            if (!validarTextoSoloLetras(apellido)) {
                Swal.showValidationMessage('El apellido debe contener solo letras y tener al menos 2 caracteres.');
                return false;
            }

            if (!validarCorreoSimple(correo)) {
                Swal.showValidationMessage('Ingrese un correo electrónico válido. Ejemplo: usuario@gmail.com');
                return false;
            }

            return { dni, nombre, apellido, correo };
        }
    });

    if (!resultado.isConfirmed) return;

    try {
        const response = await apiPost('editar_usuario.php', {
            id_usuario: idUsuario,
            dni: resultado.value.dni,
            nombre: resultado.value.nombre,
            apellido: resultado.value.apellido,
            correo: resultado.value.correo
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Usuario actualizado',
                text: data.mensaje || 'Los datos del usuario fueron actualizados correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await listarUsuariosAccesos();
            await listarPendientes();

            if (typeof cargarUsuariosYPerfiles === 'function') {
                await cargarUsuariosYPerfiles();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo editar el usuario');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor para editar el usuario.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   ACTIVAR / DESHABILITAR CUENTA
============================= */

async function cambiarEstadoUsuario(idUsuario, nuevoEstado) {
    const accion = nuevoEstado === 1 ? 'habilitar' : 'deshabilitar';

    const confirmacion = await Swal.fire({
        icon: nuevoEstado === 1 ? 'question' : 'warning',
        title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} cuenta?`,
        text: nuevoEstado === 1
            ? 'El usuario podrá ingresar al sistema.'
            : 'El usuario no podrá ingresar al sistema hasta que se reactive su cuenta.',
        showCancelButton: true,
        confirmButtonText: `Sí, ${accion}`,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: nuevoEstado === 1 ? '#1d4ed8' : '#dc2626',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await apiPost('cambiar_estado_usuario.php', {
            id_usuario: idUsuario,
            estado_activo: nuevoEstado
        });

        const data = await leerJSONSeguro(response);

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: nuevoEstado === 1 ? 'Cuenta habilitada' : 'Cuenta deshabilitada',
                text: data.mensaje || 'Estado actualizado correctamente.',
                confirmButtonColor: '#1d4ed8'
            });

            await listarUsuariosAccesos();

            if (typeof listarPendientes === 'function') {
                await listarPendientes();
            }

            if (typeof cargarUsuariosYPerfiles === 'function') {
                await cargarUsuariosYPerfiles();
            }

        } else {
            mostrarErrorBackend(data, 'No se pudo actualizar la cuenta');
        }

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

/* =============================
   CARGA AUTOMÁTICA
============================= */

window.addEventListener('load', () => {
    const tablaUsuariosAccesos = document.getElementById('tablaUsuariosAccesos');

    if (tablaUsuariosAccesos && typeof listarUsuariosAccesos === 'function') {
        listarUsuariosAccesos();
    }
});