<section id="section-biblioteca" class="section-content hidden fade-in">
    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Biblioteca Escolar</h3>
                <p class="text-xs text-slate-500">Gestión de catálogo, préstamos y devoluciones.</p>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <input type="text" placeholder="Buscar por título o autor..." class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 outline-none">
                <button class="h-10 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all whitespace-nowrap shadow-md">
                    <i class="fa-solid fa-book-medical mr-2"></i> Nuevo Préstamo
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 overflow-x-auto border border-slate-100 rounded-xl">
                <table class="w-full text-left text-sm border-collapse min-w-[500px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs uppercase">
                            <th class="p-3 font-bold">Título / Autor</th>
                            <th class="p-3 font-bold text-center">Código</th>
                            <th class="p-3 font-bold text-center">Estado</th>
                            <th class="p-3 font-bold text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">
                                <p class="font-bold text-slate-800">El Principito</p>
                                <p class="text-[11px] text-slate-500">Antoine de Saint-Exupéry</p>
                            </td>
                            <td class="p-3 text-center font-mono text-xs text-slate-500">LIT-001</td>
                            <td class="p-3 text-center"><span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-[10px] font-bold uppercase">Disponible</span></td>
                            <td class="p-3 text-right"><button class="text-blue-600 font-bold text-xs hover:underline">Prestar</button></td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">
                                <p class="font-bold text-slate-800">Manual de Cs. Naturales 4</p>
                                <p class="text-[11px] text-slate-500">Editorial Santillana</p>
                            </td>
                            <td class="p-3 text-center font-mono text-xs text-slate-500">MAN-042</td>
                            <td class="p-3 text-center"><span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-md text-[10px] font-bold uppercase">Prestado</span></td>
                            <td class="p-3 text-right"><button class="text-slate-400 cursor-not-allowed font-bold text-xs">Prestar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-rose-50 border border-rose-100 rounded-xl p-5">
                <h4 class="font-extrabold text-rose-800 text-sm mb-4"><i class="fa-solid fa-clock text-rose-500 mr-2"></i> Devoluciones Atrasadas</h4>
                <div class="bg-white rounded-lg p-3 shadow-sm border border-rose-100 mb-3">
                    <p class="font-bold text-slate-800 text-xs">Cuentos de la Selva</p>
                    <p class="text-[10px] text-slate-500 mt-1">Prestado a: <span class="font-bold">Prof. Laura Pérez</span></p>
                    <p class="text-[10px] text-rose-600 font-bold mt-1">Venció hace 2 días</p>
                    <button class="mt-2 w-full h-7 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-700 text-[10px] font-bold transition-colors">Enviar Notificación</button>
                </div>
            </div>
        </div>
    </div>
</section>