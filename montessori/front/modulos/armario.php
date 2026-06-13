<section id="section-armario" class="section-content hidden fade-in">
    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Armario Digital</h3>
                <p class="text-xs text-slate-500">Repositorio institucional para actas, circulares y documentos.</p>
            </div>
            <button class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-md">
                <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Subir Documento
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-colors">
                <div class="text-3xl text-blue-500"><i class="fa-solid fa-folder-open"></i></div>
                <div><h4 class="font-bold text-slate-800 text-sm">Circulares</h4><p class="text-xs text-slate-500">12 archivos</p></div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-colors">
                <div class="text-3xl text-amber-500"><i class="fa-solid fa-folder-closed"></i></div>
                <div><h4 class="font-bold text-slate-800 text-sm">Actas de Reunión</h4><p class="text-xs text-slate-500">45 archivos</p></div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-colors">
                <div class="text-3xl text-emerald-500"><i class="fa-solid fa-folder-closed"></i></div>
                <div><h4 class="font-bold text-slate-800 text-sm">Directivas</h4><p class="text-xs text-slate-500">8 archivos</p></div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-colors">
                <div class="text-3xl text-purple-500"><i class="fa-solid fa-folder-closed"></i></div>
                <div><h4 class="font-bold text-slate-800 text-sm">Libro de Visitas</h4><p class="text-xs text-slate-500">Registro digital</p></div>
            </div>
        </div>

        <h4 class="font-bold text-slate-700 text-sm mb-3">Documentos Recientes</h4>
        <div class="overflow-x-auto border border-slate-100 rounded-xl">
            <table class="w-full text-left text-sm border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs uppercase">
                        <th class="p-3 font-bold">Título del Documento</th>
                        <th class="p-3 font-bold text-center">Categoría</th>
                        <th class="p-3 font-bold text-center">Fecha</th>
                        <th class="p-3 font-bold text-center">Estado de Lectura</th>
                        <th class="p-3 font-bold text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 font-semibold text-slate-800"><i class="fa-solid fa-file-pdf text-red-500 mr-2"></i> Circular N° 05/26 - Acto Patrio</td>
                        <td class="p-3 text-center"><span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-[10px] font-bold uppercase">Circulares</span></td>
                        <td class="p-3 text-center text-slate-600 text-xs">11/06/2026</td>
                        <td class="p-3 text-center"><span class="text-xs font-bold text-emerald-600">Leído (24/44)</span></td>
                        <td class="p-3 text-right"><button class="text-blue-600 hover:text-blue-800 font-bold text-xs">Ver detalle</button></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 font-semibold text-slate-800"><i class="fa-solid fa-file-word text-blue-500 mr-2"></i> Acta de Reunión Docente</td>
                        <td class="p-3 text-center"><span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-md text-[10px] font-bold uppercase">Actas</span></td>
                        <td class="p-3 text-center text-slate-600 text-xs">10/06/2026</td>
                        <td class="p-3 text-center"><span class="text-xs font-bold text-slate-400">-</span></td>
                        <td class="p-3 text-right"><button class="text-blue-600 hover:text-blue-800 font-bold text-xs">Descargar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>