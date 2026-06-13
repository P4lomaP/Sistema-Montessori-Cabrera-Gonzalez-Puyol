<section id="section-asistencia" class="section-content hidden fade-in" data-permiso="asistencia_ver">
    
    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6 mb-6" data-permiso="asistencia_cargar">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Toma de Asistencia Diaria</h3>
                <p class="text-xs text-slate-500">
                    Registre la asistencia. El sistema calculará automáticamente el riesgo de abandono.
                </p>
            </div>
            <div class="flex gap-3">
                <input type="date" id="fechaAsistencia" class="input-montessori text-sm" value="<?php echo date('Y-m-d'); ?>">
                <select id="cursoAsistencia" class="input-montessori text-sm">
                    <option value="">Seleccione un curso...</option>
                </select>
                <button onclick="cargarPlanillaAsistencia()" class="h-10 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all">
                    Buscar
                </button>
            </div>
        </div>

        <div class="overflow-x-auto border border-slate-100 rounded-xl w-full">
            <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-100">
                    <tr>
                        <th class="p-4 font-bold whitespace-nowrap">DNI</th>
                        <th class="p-4 font-bold whitespace-nowrap">Alumno</th>
                        <th class="p-4 font-bold text-center whitespace-nowrap">Estado de Asistencia</th>
                        <th class="p-4 font-bold text-right whitespace-nowrap">Observación conductual</th>
                    </tr>
                </thead>
                <tbody id="tablaPlanillaAsistencia" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-400 text-xs font-semibold">
                            <i class="fa-solid fa-arrow-pointer mr-2"></i> Seleccione un curso y presione "Buscar" para cargar los alumnos.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-5 flex justify-end">
            <button onclick="confirmarGuardarAsistencia()" class="h-11 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar Asistencia
            </button>
        </div>
    </div>

    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden" data-permiso="asistencia_alertas">
        <div class="p-5 border-b border-blue-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">
                    Alertas de Riesgo de Abandono
                </h3>
                <p class="text-xs text-slate-500">Alumnos que superaron el umbral crítico de inasistencias.</p>
            </div>
            <button onclick="cargarAlertasRiesgo()" class="h-10 px-4 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold transition-all">
                Actualizar alertas
            </button>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase whitespace-nowrap">DNI</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase whitespace-nowrap">Alumno</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase text-center whitespace-nowrap">Estado</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase text-right whitespace-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaAlertasRiesgo" class="divide-y divide-slate-100">
                </tbody>
            </table>
        </div>
    </div>

</section>