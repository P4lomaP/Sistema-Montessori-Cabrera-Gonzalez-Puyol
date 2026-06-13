async function cargarOpcionesCursos() {
    const select = document.getElementById('cursoAsistencia');
    if (!select) return;

    try {
        const response = await fetch('../back/api/asistencias_seguimiento/obtener_cursos.php');
        const result = await response.json();

        if (result.status === 'ok') {
            select.innerHTML = '<option value="">Seleccione un curso...</option>';
            result.data.forEach(curso => {
                const option = document.createElement('option');
                option.value = curso.id_curso;
                option.textContent = `${curso.anio_grado}ro ${curso.division} - ${curso.turno}`;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error("Error al cargar la lista de cursos:", error);
    }
}

async function cargarPlanillaAsistencia() {
    const curso = document.getElementById('cursoAsistencia').value;
    const tbody = document.getElementById('tablaPlanillaAsistencia');

    if (!curso) {
        Swal.fire({
            icon: 'info',
            title: 'Campos incompletos',
            text: 'Por favor, seleccione un curso para cargar la planilla.',
            confirmButtonColor: '#1d4ed8'
        });
        return;
    }

    tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-slate-400 text-xs font-semibold"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Cargando base de datos...</td></tr>`;

    try {
        const response = await fetch(`../back/api/asistencias_seguimiento/obtener_alumnos_curso.php?id_curso=${curso}`);
        const result = await response.json();

        if (result.status === 'error') throw new Error(result.mensaje);

        const alumnos = result.data;

        if (!alumnos || alumnos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-amber-500 font-semibold text-sm"><i class="fa-solid fa-folder-open mr-2"></i> No hay alumnos registrados en este curso.</td></tr>`;
            return;
        }

        tbody.innerHTML = alumnos.map(alumno => {
            const id = alumno.id_matricula || alumno.id_alumno;
            const nombreCompleto = alumno.nombre_completo || `${alumno.apellido}, ${alumno.nombre}`;
            
            return `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-semibold text-slate-700">${alumno.dni || '-'}</td>
                    <td class="p-4 font-bold text-slate-800">${nombreCompleto}</td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="estado_${id}" value="Presente" checked class="text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4">
                                <span class="text-xs text-slate-600 font-semibold">Presente</span>
                            </label>
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="estado_${id}" value="Ausente" class="text-red-600 focus:ring-red-500 cursor-pointer w-4 h-4">
                                <span class="text-xs text-slate-600 font-semibold">Ausente</span>
                            </label>
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="estado_${id}" value="Justificado" class="text-amber-500 focus:ring-amber-500 cursor-pointer w-4 h-4">
                                <span class="text-xs text-slate-600 font-semibold">Justificado</span>
                            </label>
                        </div>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="verHistorialAsistencia(${id}, '${nombreCompleto}')" class="h-8 w-8 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-400 hover:text-blue-600 transition-colors" title="Ver historial">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </button>
                            <button onclick="verLegajo(${id}, '${nombreCompleto}')" class="h-8 w-8 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-400 hover:text-purple-600 transition-colors" title="Ver legajo e incidentes">
                                <i class="fa-solid fa-folder-open"></i>
                            </button>
                            <button onclick="abrirModalIncidente(${id}, '${nombreCompleto}')" class="h-8 px-3 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-[11px] font-bold shadow-sm hover:text-amber-600 transition-colors">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i> Incidente
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

    } catch (error) {
        console.error(error);
        tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-red-500 text-xs font-semibold">Error al cargar los alumnos del curso.</td></tr>`;
    }
}

async function confirmarGuardarAsistencia() {
    const curso = document.getElementById('cursoAsistencia').value;
    if (!curso) {
        Swal.fire({ icon: 'warning', title: 'Atención', text: 'Debe buscar un curso primero.' });
        return;
    }

    const radiosSeleccionados = document.querySelectorAll('input[type="radio"]:checked');
    if (radiosSeleccionados.length === 0) {
        Swal.fire({ icon: 'warning', title: 'Planilla vacía', text: 'No hay asistencia para registrar.' });
        return;
    }

    const asistencias = Array.from(radiosSeleccionados).map(radio => ({
        id_matricula: radio.name.split('_')[1],
        estado: radio.value
    }));

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: '¿Guardar asistencia?',
        text: 'Se registrarán los estados seleccionados y se actualizará el Motor de Riesgo.',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Revisar',
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    });

    if (confirmacion.isConfirmed) {
        try {
            const response = await fetch('../back/api/asistencias_seguimiento/guardar_asistencia.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_curso: curso, registros: asistencias })
            });
            const result = await response.json();

            if (result.status === 'error') throw new Error(result.mensaje);

            Swal.fire({ icon: 'success', title: 'Planilla guardada', text: 'La asistencia ha sido registrada.', confirmButtonColor: '#1d4ed8' });
            
            // Recargamos el motor de riesgo automáticamente
            cargarAlertasRiesgo();

        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error', text: error.message || 'No se pudo guardar la asistencia.' });
        }
    }
}

async function cargarAlertasRiesgo() {
    const tbody = document.getElementById('tablaAlertasRiesgo');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-slate-400 text-xs font-semibold"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Analizando inasistencias...</td></tr>`;

    try {
        const response = await fetch('../back/api/asistencias_seguimiento/motor_riesgo.php');
        const result = await response.json();

        if (result.status === 'error') throw new Error(result.mensaje);

        const alertas = result.data || [];

        if (alertas.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-emerald-500 text-xs font-semibold"><i class="fa-solid fa-check-circle mr-2"></i> No hay alumnos en riesgo de abandono escolar.</td></tr>`;
            return;
        }

        tbody.innerHTML = alertas.map(alerta => {
            const nombreCompleto = alerta.nombre_completo || `${alerta.apellido}, ${alerta.nombre}`;
            const id = alerta.id_alumno || alerta.id_matricula;
            const color = alerta.color || 'amber'; // El Back-End puede mandar 'rose', 'amber' u 'orange'

            return `
                <tr class="hover:bg-slate-50">
                    <td class="p-4 font-bold text-slate-800">${nombreCompleto}</td>
                    <td class="p-4 text-center font-semibold text-slate-700">${alerta.faltas_acumuladas}</td>
                    <td class="p-4 text-center">
                        <span class="px-2 py-1 bg-${color}-100 text-${color}-700 rounded-md text-[10px] font-bold uppercase tracking-wider">${alerta.nivel_riesgo}</span>
                    </td>
                    <td class="p-4 text-right">
                        <button onclick="abrirModalIntervencion(${id}, '${nombreCompleto}')" class="h-8 px-3 rounded-lg bg-blue-50 border border-blue-200 hover:bg-blue-100 text-blue-700 text-[11px] font-bold shadow-sm transition-colors">
                            <i class="fa-solid fa-hand-holding-medical mr-1"></i> Intervenir
                        </button>
                    </td>
                </tr>
            `;
        }).join('');

    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-red-500 text-xs font-semibold">Error al conectar con el motor de riesgo.</td></tr>`;
    }
}

async function verHistorialAsistencia(idAlumno, nombreAlumno) {
    Swal.fire({ title: 'Buscando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch(`../back/api/asistencias_seguimiento/obtener_historial_asistencia.php?id=${idAlumno}`);
        const result = await res.json();
        
        let htmlTabla = '<p style="font-size:13px; color:#64748b;">No hay registros previos.</p>';
        if (result.status === 'ok' && result.data.length > 0) {
            htmlTabla = `
                <table style="width: 100%; font-size: 13px; text-align: left;">
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 5px;">Fecha</th><th style="padding: 5px;">Estado</th>
                    </tr>
                    ${result.data.map(h => `
                        <tr>
                            <td style="padding: 5px;">${h.fecha}</td>
                            <td style="padding: 5px; color: ${h.estado === 'Ausente' ? '#dc2626' : '#2563eb'}; font-weight: bold;">${h.estado}</td>
                        </tr>
                    `).join('')}
                </table>`;
        }

        Swal.fire({
            title: 'Historial de Asistencia',
            html: `<div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                    <p style="font-size:14px; color:#475569; margin-bottom:15px;">Alumno: <b>${nombreAlumno}</b></p>
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px;">${htmlTabla}</div>
                   </div>`,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#1d4ed8'
        });
    } catch (e) { Swal.fire('Error', 'No se pudo cargar el historial', 'error'); }
}

async function verLegajo(idAlumno, nombreAlumno) {
    Swal.fire({ title: 'Buscando legajo...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch(`../back/api/asistencias_seguimiento/listar_incidente_alumno.php?id=${idAlumno}`);
        const result = await res.json();

        let htmlIncidentes = '<p style="font-size:13px; color:#64748b;">El legajo está limpio. No hay incidentes.</p>';
        if (result.status === 'ok' && result.data.length > 0) {
            htmlIncidentes = result.data.map(inc => `
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 12px; margin-bottom: 8px;">
                    <span style="font-size: 10px; font-weight: bold; color: #d97706; text-transform: uppercase;">Incidente ${inc.gravedad} - ${inc.fecha || 'Reciente'}</span>
                    <p style="font-size: 13px; color: #78350f; margin: 4px 0 0 0;">${inc.observacion}</p>
                </div>
            `).join('');
        }

        Swal.fire({
            title: 'Legajo de Conducta',
            html: `<div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                    <p style="font-size:14px; color:#475569; margin-bottom:15px;">Alumno: <b>${nombreAlumno}</b></p>
                    <div style="max-height: 250px; overflow-y: auto;">${htmlIncidentes}</div>
                   </div>`,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#1d4ed8'
        });
    } catch (e) { Swal.fire('Error', 'No se pudo cargar el legajo', 'error'); }
}

async function abrirModalIncidente(idAlumno, nombreAlumno) {
    const { value: formValues } = await Swal.fire({
        title: 'Registrar Incidente',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <p style="font-size:14px; color:#475569; margin-bottom:15px;">Alumno: <b>${nombreAlumno}</b></p>
                <label style="font-size:12px; font-weight:800; color:#475569;">Gravedad</label>
                <select id="swal-gravedad" class="swal2-input" style="width:100%; height:44px; margin:5px 0 15px 0; border-radius:14px; font-size:14px;">
                    <option value="Leve">Leve</option><option value="Moderada">Moderada</option><option value="Grave">Grave</option>
                </select>
                <label style="font-size:12px; font-weight:800; color:#475569;">Observación</label>
                <textarea id="swal-observacion" class="swal2-textarea" style="width:100%; margin:5px 0 0 0; border-radius:14px; font-size:14px;" placeholder="Detalle el comportamiento..."></textarea>
            </div>
        `,
        showCancelButton: true, confirmButtonText: 'Guardar Incidente', confirmButtonColor: '#1d4ed8',
        preConfirm: () => {
            const gravedad = document.getElementById('swal-gravedad').value;
            const observacion = document.getElementById('swal-observacion').value;
            if (!observacion) Swal.showValidationMessage('Debe escribir una observación.');
            return { id_alumno: idAlumno, gravedad, observacion };
        }
    });

    if (formValues) {
        try {
            await fetch('../back/api/asistencias_seguimiento/registrar_incidente.php', { method: 'POST', body: JSON.stringify(formValues) });
            Swal.fire({ icon: 'success', title: 'Incidente registrado', text: 'Guardado en el legajo.', confirmButtonColor: '#1d4ed8' });
        } catch (e) { Swal.fire('Error', 'No se pudo guardar', 'error'); }
    }
}

async function abrirModalIntervencion(idAlumno, nombreAlumno) {
    const { value: formValues } = await Swal.fire({
        title: 'Protocolo de Intervención',
        html: `
            <div style="text-align:left; font-family:'Plus Jakarta Sans', sans-serif;">
                <p style="font-size:14px; color:#475569; margin-bottom:15px;">Alumno en riesgo: <b>${nombreAlumno}</b></p>
                <label style="font-size:12px; font-weight:800; color:#475569;">Tipo de Acción</label>
                <select id="swal-tipo-int" class="swal2-input" style="width:100%; height:44px; margin:5px 0 15px 0; border-radius:14px; font-size:14px;">
                    <option value="Citacion a Tutor">Citación a Tutor</option>
                    <option value="Derivacion a Gabinete">Derivación a Gabinete Pedagógico</option>
                </select>
                <label style="font-size:12px; font-weight:800; color:#475569;">Detalle</label>
                <textarea id="swal-detalle-int" class="swal2-textarea" style="width:100%; margin:5px 0 0 0; border-radius:14px; font-size:14px;" placeholder="Motivo..."></textarea>
            </div>
        `,
        showCancelButton: true, confirmButtonText: 'Registrar Acción', confirmButtonColor: '#1d4ed8',
        preConfirm: () => {
            const tipo = document.getElementById('swal-tipo-int').value;
            const descripcion = document.getElementById('swal-detalle-int').value;
            if (!descripcion) Swal.showValidationMessage('Debe detallar la intervención.');
            return { id_alumno: idAlumno, tipo, descripcion };
        }
    });

    if (formValues) {
        try {
            await fetch('../back/api/asistencias_seguimiento/registrar_intervencion.php', { method: 'POST', body: JSON.stringify(formValues) });
            Swal.fire({ icon: 'success', title: 'Intervención registrada', text: 'Asentada en el historial.', confirmButtonColor: '#1d4ed8' });
        } catch (e) { Swal.fire('Error', 'No se pudo guardar', 'error'); }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    cargarOpcionesCursos();
    cargarAlertasRiesgo();
});