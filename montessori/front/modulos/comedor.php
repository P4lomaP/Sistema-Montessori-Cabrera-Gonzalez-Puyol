<section id="section-comedor" class="section-content hidden fade-in" data-permiso="comedor_ver">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-200 pb-4">
        <div class="flex items-center gap-2 overflow-x-auto">
            <button onclick="cambiarTabComedor('diaria')" id="btn-tab-diaria" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-slate-900 text-white shadow-md transition-all whitespace-nowrap">Gestión Diaria</button>
            <button onclick="cambiarTabComedor('historial')" id="btn-tab-historial" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-white text-slate-500 hover:bg-slate-50 border border-slate-200 transition-all whitespace-nowrap">Auditoría e Historial</button>
            <button onclick="cambiarTabComedor('inventario')" id="btn-tab-inventario" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-white text-slate-500 hover:bg-slate-50 border border-slate-200 transition-all whitespace-nowrap">Control de Inventario</button>
        </div>
        
        <button data-permiso="comedor_aprobar_excepciones" onclick="desbloquearComedorManual()" class="h-10 px-4 rounded-xl bg-red-50 border border-red-200 hover:bg-red-100 text-red-700 text-xs font-bold transition-all shadow-sm flex items-center whitespace-nowrap">
            <i class="fa-solid fa-unlock-keyhole mr-2"></i> Forzar Desbloqueo
        </button>
    </div>

    <div id="tab-diaria" class="tab-comedor-panel">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 flex items-center justify-between" data-permiso="comedor_ver_totales">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Raciones a Preparar</p>
                    <h3 id="contadorRaciones" class="text-3xl font-extrabold text-blue-600 mt-1">--</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-utensils"></i>
                </div>
            </div>

            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 flex items-center justify-between md:col-span-2" data-permiso="comedor_gestionar_menu">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Menú del Día</p>
                    <div id="textoMenuDia" class="text-lg font-extrabold text-slate-900 mt-1">Cargando menú...</div>
                </div>
                <button onclick="abrirModalMenu()" class="h-10 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    Cambiar Menú
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <div class="xl:col-span-2 bg-white border border-blue-100 rounded-2xl shadow-sm p-6" data-permiso="comedor_cargar_comensales">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Control de Comensales</h3>
                        <p class="text-xs text-slate-500">Registre el ingreso al comedor escolar.</p>
                    </div>
                    <div class="flex gap-2">
                        <input type="text" id="buscadorComensal" placeholder="Buscar alumno..." class="input-montessori text-sm w-full sm:w-64">
                    </div>
                </div>

                <div class="overflow-x-auto w-full border border-slate-100 rounded-xl">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase whitespace-nowrap">Alumno</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase whitespace-nowrap">Curso</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase whitespace-nowrap text-center">Alertas Nutricionales</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase whitespace-nowrap text-right">Confirmar Plato</th>
                            </tr>
                        </thead>
                        <tbody id="tablaComensales" class="divide-y divide-slate-100 text-sm">
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400 font-semibold">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i> Cargando padrón...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-5 flex justify-end gap-3">
                    <button onclick="solicitarExcepcion()" class="h-11 px-4 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-bold transition-all" data-permiso="comedor_aprobar_excepciones">
                        <i class="fa-solid fa-plus mr-2"></i> Excepción
                    </button>
                    <button onclick="guardarComensales()" class="h-11 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition-all">
                        <i class="fa-solid fa-check-double mr-2"></i> Guardar Lista
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6" data-permiso="comedor_registrar_sobrantes">
                    <h3 class="text-md font-extrabold text-slate-900 mb-1">Cierre de Cocina</h3>
                    <p class="text-xs text-slate-500 mb-5">Registre sobrantes y cierre el servicio.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Raciones Sobrantes</label>
                            <input type="number" id="racionesSobrantes" placeholder="Ej: 12" class="input-montessori w-full text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Motivo / Observación</label>
                            <textarea id="obsSobrantes" rows="2" class="input-montessori w-full text-sm" placeholder="Ausencia por lluvia..."></textarea>
                        </div>
                        <button onclick="finalizarServicio()" class="w-full h-11 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold transition-all">
                            Finalizar Servicio del Día
                        </button>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 border border-blue-100 rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="fa-solid fa-file-export"></i>
                        </div>
                        <div>
                            <h3 class="text-md font-extrabold text-slate-900">Exportar SIGEF</h3>
                        </div>
                    </div>
                    <button onclick="exportarSigef()" class="w-full h-10 rounded-xl bg-white border border-blue-200 hover:border-blue-400 hover:text-blue-700 text-slate-700 text-xs font-bold transition-all shadow-sm">
                        Descargar Reporte (.csv)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-historial" class="tab-comedor-panel hidden">
        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Historial de Consumo</h3>
                    <p class="text-xs text-slate-500">Auditoría de raciones y sobrantes de días anteriores.</p>
                </div>
                <div class="flex gap-2">
                    <input type="month" class="input-montessori text-sm">
                    <button class="h-10 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">Filtrar</button>
                </div>
            </div>
            <div class="overflow-x-auto w-full border border-slate-100 rounded-xl">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase">Fecha</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-center">Raciones Servidas</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-center">Sobrantes</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-right">Menú Principal</th>
                        </tr>
                    </thead>
                    <tbody id="tablaHistorialComedor" class="divide-y divide-slate-100 text-sm">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="tab-inventario" class="tab-comedor-panel hidden">
        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Control de Inventario</h3>
                    <p class="text-xs text-slate-500">Stock actual de insumos en cocina.</p>
                </div>
                <button onclick="registrarIngresoInventario()" class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-md">
                    <i class="fa-solid fa-truck-ramp-box mr-2"></i> Registrar Ingreso
                </button>
            </div>
            <div class="overflow-x-auto w-full border border-slate-100 rounded-xl">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase">Insumo</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-center">Stock Actual</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-center">Unidad</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="tablaInventario" class="divide-y divide-slate-100 text-sm">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

</section>