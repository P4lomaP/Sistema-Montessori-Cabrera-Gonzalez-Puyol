const COMEDOR_API_BASE = (typeof API_BASE !== 'undefined')
    ? API_BASE.replace('gestion_perfiles_accesos/', 'comedor/')
    : '/Sistema-Montessori-Cabrera-Gonzalez-Puyol/montessori/back/api/comedor/';

function comedorHeaders() {
    const token = typeof obtenerToken === 'function' ? obtenerToken() : '';

    return {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    };
}

async function comedorGet(endpoint) {
    return fetch(COMEDOR_API_BASE + endpoint, {
        method: 'GET',
        headers: comedorHeaders()
    });
}

async function comedorPost(endpoint, body) {
    return fetch(COMEDOR_API_BASE + endpoint, {
        method: 'POST',
        headers: comedorHeaders(),
        body: JSON.stringify(body)
    });
}

async function leerJSONComedor(response) {
    try {
        return await response.json();
    } catch (error) {
        return {
            mensaje: 'La respuesta del servidor no tiene formato JSON válido.'
        };
    }
}

function escaparComedor(valor) {
    if (typeof escaparHTML === 'function') {
        return escaparHTML(valor);
    }

    return String(valor ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function formatearCantidadComedor(valor) {
    const numero = Number(valor || 0);

    return numero.toLocaleString('es-AR', {
        minimumFractionDigits: numero % 1 === 0 ? 0 : 2,
        maximumFractionDigits: 2
    });
}

function claseEstadoInventario(estado) {
    const estadoNormalizado = String(estado || '').toLowerCase();

    if (estadoNormalizado.includes('bajo')) {
        return 'bg-rose-100 text-rose-700';
    }

    if (estadoNormalizado.includes('medio')) {
        return 'bg-amber-100 text-amber-700';
    }

    if (estadoNormalizado.includes('óptimo') || estadoNormalizado.includes('optimo')) {
        return 'bg-emerald-100 text-emerald-700';
    }

    return 'bg-slate-100 text-slate-700';
}

function cambiarTabComedor(tab) {
    document.querySelectorAll('.tab-comedor-panel').forEach(p => p.classList.add('hidden'));

    const claseInactiva = "px-5 py-2.5 rounded-xl text-sm font-bold bg-white text-slate-500 hover:bg-slate-50 border border-slate-200 transition-all whitespace-nowrap";

    const btnDiaria = document.getElementById('btn-tab-diaria');
    const btnHistorial = document.getElementById('btn-tab-historial');
    const btnInventario = document.getElementById('btn-tab-inventario');

    if (btnDiaria) btnDiaria.className = claseInactiva;
    if (btnHistorial) btnHistorial.className = claseInactiva;
    if (btnInventario) btnInventario.className = claseInactiva;

    const panel = document.getElementById('tab-' + tab);
    const boton = document.getElementById('btn-tab-' + tab);

    if (panel) panel.classList.remove('hidden');

    if (boton) {
        boton.className = "px-5 py-2.5 rounded-xl text-sm font-bold bg-slate-900 text-white shadow-md transition-all whitespace-nowrap";
    }

    if (tab === 'diaria') cargarDatosComedor();
    if (tab === 'historial') cargarHistorialComedor();
    if (tab === 'inventario') cargarInventarioComedor();
}

async function cargarDatosComedor() {
    const tbody = document.getElementById('tablaComensales');
    const contadorRaciones = document.getElementById('contadorRaciones');
    const textoMenuDia = document.getElementById('textoMenuDia');

    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="4" class="p-4 text-center text-slate-400 font-semibold">
                Cargando datos del comedor...
            </td>
        </tr>
    `;

    try {
        const response = await comedorGet('obtener_totales_cocina.php');
        const data = await leerJSONComedor(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudieron cargar los datos del comedor.');
        }

        const raciones = data.raciones || data.total_raciones || data.contador_raciones || 0;
        const menu = data.menu || {};
        const comensales = data.comensales || data.alumnos || [];

        if (contadorRaciones) {
            contadorRaciones.textContent = raciones;
        }

        if (textoMenuDia) {
            textoMenuDia.innerHTML = `
                <div class="text-sm font-semibold text-slate-700 mt-2 space-y-1">
                    <p>
                        <i class="fa-solid fa-mug-hot text-amber-600 w-5"></i>
                        <span class="font-normal">${escaparComedor(menu.desayuno || 'Sin desayuno cargado')}</span>
                    </p>
                    <p>
                        <i class="fa-solid fa-utensils text-blue-600 w-5"></i>
                        <span class="font-normal">${escaparComedor(menu.almuerzo || 'Sin almuerzo cargado')}</span>
                    </p>
                    <p>
                        <i class="fa-solid fa-cookie-bite text-emerald-600 w-5"></i>
                        <span class="font-normal">${escaparComedor(menu.merienda || 'Sin merienda cargada')}</span>
                    </p>
                </div>
            `;
        }

        if (!Array.isArray(comensales) || comensales.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="p-4 text-center text-slate-400 font-semibold">
                        No hay comensales registrados para hoy.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = comensales.map(c => {
            const idMatricula = c.id_matricula || c.matriculas_id_matricula || c.id || '';
            const nombre = c.nombre_completo || `${c.apellido || ''}, ${c.nombre || ''}`.trim();
            const curso = c.curso || c.nombre_curso || 'Sin curso';
            const alerta = c.restriccion_alimentaria || c.alerta || 'Ninguna';
            const checked = String(c.asiste ?? c.presente ?? 1) === '1' ? 'checked' : '';

            const alertaHtml = alerta === 'Ninguna'
                ? `<span class="text-slate-400 text-xs font-bold">-</span>`
                : `<span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-md text-[10px] font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                        ${escaparComedor(alerta)}
                   </span>`;

            return `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-bold text-slate-800">${escaparComedor(nombre)}</td>

                    <td class="p-4 text-slate-600 text-xs font-semibold">
                        ${escaparComedor(curso)}
                    </td>

                    <td class="p-4 text-center">
                        ${alertaHtml}
                    </td>

                    <td class="p-4 text-right">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   class="sr-only peer check-comensal"
                                   data-id-matricula="${escaparComedor(idMatricula)}"
                                   ${checked}>

                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </td>
                </tr>
            `;
        }).join('');

    } catch (error) {
        console.error(error);

        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="p-4 text-center text-red-500 font-semibold">
                    No se pudieron cargar los datos del comedor.
                </td>
            </tr>
        `;
    }
}

async function abrirModalMenu() {
    const { value: formValues } = await Swal.fire({
        title: 'Actualizar Menú del Día',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <label style="font-size:12px; font-weight:800; color:#475569;">☕ Desayuno</label>
                <input id="swal-desayuno" class="swal2-input" style="width:100%; height:40px; margin:5px 0 15px 0; border-radius:10px; font-size:14px;" placeholder="Ej: Té con galletitas...">

                <label style="font-size:12px; font-weight:800; color:#475569;">🍲 Almuerzo</label>
                <input id="swal-almuerzo" class="swal2-input" style="width:100%; height:40px; margin:5px 0 15px 0; border-radius:10px; font-size:14px;" placeholder="Ej: Fideos con tuco...">

                <label style="font-size:12px; font-weight:800; color:#475569;">🥛 Merienda</label>
                <input id="swal-merienda" class="swal2-input" style="width:100%; height:40px; margin:5px 0 5px 0; border-radius:10px; font-size:14px;" placeholder="Ej: Mate cocido...">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar Menú Completo',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        preConfirm: () => {
            const desayuno = document.getElementById('swal-desayuno').value.trim();
            const almuerzo = document.getElementById('swal-almuerzo').value.trim();
            const merienda = document.getElementById('swal-merienda').value.trim();

            if (desayuno === '' && almuerzo === '' && merienda === '') {
                Swal.showValidationMessage('Debe completar al menos una comida para guardar.');
                return false;
            }

            return {
                desayuno: desayuno || 'Sin asignar',
                almuerzo: almuerzo || 'Sin asignar',
                merienda: merienda || 'Sin asignar'
            };
        }
    });

    if (!formValues) return;

    try {
        const response = await comedorPost('gestionar_menu.php', formValues);
        const data = await leerJSONComedor(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo guardar el menú.');
        }

        await cargarDatosComedor();

        Swal.fire({
            icon: 'success',
            title: 'Menú actualizado',
            text: data.mensaje || 'Las comidas del día han sido programadas.',
            confirmButtonColor: '#1d4ed8'
        });

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo guardar el menú.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

async function solicitarExcepcion() {
    const { value: formValues } = await Swal.fire({
        title: 'Cargar Excepción',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <p style="font-size:12px; color:#64748b; margin-bottom:15px;">
                    Ingrese el DNI y Nombre del alumno que no figura en el padrón.
                </p>

                <input id="swal-dni" type="number" class="swal2-input" style="width:100%; height:44px; margin:5px 0; border-radius:14px; font-size:14px;" placeholder="DNI sin puntos">

                <input id="swal-nombre" type="text" class="swal2-input" style="width:100%; height:44px; margin:5px 0; border-radius:14px; font-size:14px;" placeholder="Apellido, Nombre">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Verificar y Añadir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        preConfirm: () => {
            const dni = document.getElementById('swal-dni').value.trim();
            const nombre = document.getElementById('swal-nombre').value.trim();

            if (!dni || !nombre) {
                Swal.showValidationMessage('Debe completar ambos campos.');
                return false;
            }

            return { dni, nombre };
        }
    });

    if (!formValues) return;

    try {
        const response = await comedorPost('solicitar_excepcion.php', formValues);
        const data = await leerJSONComedor(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo cargar la excepción.');
        }

        await cargarDatosComedor();

        Swal.fire({
            icon: 'success',
            title: 'Excepción cargada',
            text: data.mensaje || 'Se sumó al padrón temporal de hoy.',
            confirmButtonColor: '#1d4ed8'
        });

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo cargar la excepción.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

async function guardarComensales() {
    const checks = Array.from(document.querySelectorAll('.check-comensal'));

    const comensales = checks.map(check => ({
        id_matricula: check.getAttribute('data-id-matricula'),
        asiste: check.checked ? 1 : 0
    })).filter(c => c.id_matricula);

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: '¿Guardar padrón?',
        text: 'Se registrarán las raciones consumidas y las ausencias del día.',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Revisar',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await comedorPost('guardar_comensales.php', {
            comensales: comensales
        });

        const data = await leerJSONComedor(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo guardar el padrón.');
        }

        Swal.fire({
            icon: 'success',
            title: 'Padrón Guardado',
            text: data.mensaje || 'La asistencia al comedor ha sido registrada.',
            confirmButtonColor: '#10b981'
        });

        await cargarDatosComedor();

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo guardar el padrón.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

async function finalizarServicio() {
    const inputSobrantes = document.getElementById('racionesSobrantes');
    const sobrantes = inputSobrantes ? inputSobrantes.value.trim() : '';

    if (!sobrantes) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Ingrese la cantidad de raciones sobrantes.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    const confirmacion = await Swal.fire({
        icon: 'warning',
        title: '¿Cerrar cocina?',
        text: `Se registrarán ${sobrantes} sobrantes. Esto bloquea el padrón por el resto del día.`,
        showCancelButton: true,
        confirmButtonText: 'Sí, finalizar servicio',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0f172a',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const response = await comedorPost('finalizar_servicio.php', {
            raciones_sobrantes: Number(sobrantes)
        });

        const data = await leerJSONComedor(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo finalizar el servicio.');
        }

        Swal.fire({
            icon: 'success',
            title: 'Servicio Finalizado',
            text: data.mensaje || 'El reporte diario de cocina se cerró con éxito.',
            confirmButtonColor: '#0f172a'
        });

        if (inputSobrantes) inputSobrantes.value = '';

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo finalizar el servicio.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}

function exportarSigef() {
    window.open(COMEDOR_API_BASE + 'exportar_sigef.php', '_blank');
}

async function cargarHistorialComedor() {
    const tbody = document.getElementById('tablaHistorialComedor');
    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="4" class="p-4 text-center text-slate-400 font-semibold">
                Cargando historial...
            </td>
        </tr>
    `;

    try {
        const response = await comedorGet('obtener_historial.php');
        const data = await leerJSONComedor(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo cargar el historial.');
        }

        const historial = data.historial || data.registros || [];

        if (!Array.isArray(historial) || historial.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="p-4 text-center text-slate-400 font-semibold">
                        No hay registros históricos disponibles.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = historial.map(h => {
            const fecha = h.fecha || h.dia || '-';
            const servidas = h.raciones_servidas || h.servidas || h.total_servidas || 0;
            const sobrantes = h.raciones_sobrantes || h.sobrantes || 0;
            const menu = h.menu || h.almuerzo || h.descripcion_menu || '-';

            return `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-bold text-slate-800">
                        ${escaparComedor(fecha)}
                    </td>

                    <td class="p-4 text-center font-semibold text-emerald-600">
                        ${escaparComedor(servidas)}
                    </td>

                    <td class="p-4 text-center font-semibold text-rose-600">
                        ${escaparComedor(sobrantes)}
                    </td>

                    <td class="p-4 text-right text-slate-600 font-medium">
                        ${escaparComedor(menu)}
                    </td>
                </tr>
            `;
        }).join('');

    } catch (error) {
        console.error(error);

        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="p-4 text-center text-red-500 font-semibold">
                    No se pudo cargar el historial del comedor.
                </td>
            </tr>
        `;
    }
}

/* ==============================================================
   6. INVENTARIO REAL
============================================================== */

async function cargarInventarioComedor() {
    const tbody = document.getElementById('tablaInventario');
    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="4" class="p-4 text-center text-slate-400 font-semibold">
                Cargando inventario...
            </td>
        </tr>
    `;

    try {
        const response = await comedorGet('obtener_inventario.php');
        const data = await leerJSONComedor(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo cargar el inventario.');
        }

        const inventario = data.inventario || data.items || data.data || [];

        if (!Array.isArray(inventario) || inventario.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="p-4 text-center text-slate-400 font-semibold">
                        No hay insumos registrados.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = inventario.map(item => {
            const insumo = item.insumo || item.nombre || 'Sin nombre';
            const cantidad = item.cantidad_total ?? item.cantidad ?? item.stock ?? 0;
            const unidad = item.unidad || 'Unidades';
            const estado = item.estado || calcularEstadoInventario(cantidad);

            return `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-bold text-slate-800">
                        ${escaparComedor(insumo)}
                    </td>

                    <td class="p-4 text-center font-semibold text-slate-700">
                        ${formatearCantidadComedor(cantidad)}
                    </td>

                    <td class="p-4 text-center text-slate-500 text-xs uppercase">
                        ${escaparComedor(unidad)}
                    </td>

                    <td class="p-4 text-right">
                        <span class="px-2 py-1 ${claseEstadoInventario(estado)} rounded-md text-[10px] font-bold uppercase">
                            ${escaparComedor(estado)}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');

    } catch (error) {
        console.error(error);

        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="p-4 text-center text-red-500 font-semibold">
                    No se pudo cargar el inventario. Verificá el endpoint obtener_inventario.php.
                </td>
            </tr>
        `;
    }
}

function calcularEstadoInventario(cantidad) {
    const numero = Number(cantidad || 0);

    if (numero <= 5) return 'Stock Bajo';
    if (numero <= 15) return 'Stock Medio';
    return 'Óptimo';
}

async function registrarIngresoInventario() {
    const { value: formValues } = await Swal.fire({
        title: 'Ingreso de Mercadería',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <label style="font-size:12px; font-weight:800; color:#475569;">Insumo</label>
                <input id="swal-insumo"
                       class="swal2-input"
                       style="width:100%; height:40px; margin:5px 0 15px 0; border-radius:10px; font-size:14px;"
                       placeholder="Ej: Fideos Tirabuzón">

                <div style="display:flex; gap:10px;">
                    <div style="flex:1;">
                        <label style="font-size:12px; font-weight:800; color:#475569;">Cantidad</label>
                        <input id="swal-cantidad"
                               type="number"
                               min="0.01"
                               step="0.01"
                               class="swal2-input"
                               style="width:100%; height:40px; margin:5px 0 15px 0; border-radius:10px; font-size:14px;"
                               placeholder="Ej: 10">
                    </div>

                    <div style="flex:1;">
                        <label style="font-size:12px; font-weight:800; color:#475569;">Unidad</label>
                        <select id="swal-unidad"
                                class="swal2-input"
                                style="width:100%; height:40px; margin:5px 0 15px 0; border-radius:10px; font-size:14px; padding:0 10px;">
                            <option value="Kilos">Kilos</option>
                            <option value="Litros">Litros</option>
                            <option value="Unidades">Unidades</option>
                        </select>
                    </div>
                </div>

                <label style="font-size:12px; font-weight:800; color:#475569;">N.º de comprobante</label>
                <input id="swal-comprobante"
                       class="swal2-input"
                       style="width:100%; height:40px; margin:5px 0 0 0; border-radius:10px; font-size:14px;"
                       placeholder="Opcional">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar Ingreso',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        preConfirm: () => {
            const insumo = document.getElementById('swal-insumo').value.trim();
            const cantidad = document.getElementById('swal-cantidad').value;
            const unidad = document.getElementById('swal-unidad').value;
            const numeroComprobante = document.getElementById('swal-comprobante').value.trim();

            if (!insumo || !cantidad || !unidad) {
                Swal.showValidationMessage('Complete insumo, cantidad y unidad.');
                return false;
            }

            if (Number(cantidad) <= 0) {
                Swal.showValidationMessage('La cantidad debe ser mayor a cero.');
                return false;
            }

            return {
                insumo: insumo,
                cantidad: Number(cantidad),
                unidad: unidad,
                numero_comprobante: numeroComprobante || null
            };
        }
    });

    if (!formValues) return;

    try {
        const response = await comedorPost('registrar_ingreso_inventario.php', formValues);
        const data = await leerJSONComedor(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo registrar el ingreso.');
        }

        Swal.fire({
            icon: 'success',
            title: 'Inventario actualizado',
            text: data.mensaje || 'El ingreso de mercadería fue registrado.',
            confirmButtonColor: '#2563eb'
        });

        await cargarInventarioComedor();

    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo registrar el ingreso de inventario.',
            confirmButtonColor: '#1d4ed8'
        });
    }
}