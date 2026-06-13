<aside class="w-72 bg-white border-r border-blue-100 hidden lg:flex flex-col fixed left-0 top-0 h-screen z-40">

        <div class="p-6 border-b border-blue-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-900">Montessori</h1>
                    <p class="text-[10px] uppercase tracking-widest text-blue-600 font-bold">Portal Institucional</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

            <button class="sidebar-link active w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="dashboard"
                    data-permiso="dashboard_ver">
                <i class="fa-solid fa-chart-line text-blue-600 w-5"></i>
                Dashboard
            </button>

            <button class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="accesos"
                    data-permiso="usuarios_ver">
                <i class="fa-solid fa-users-gear text-blue-600 w-5"></i>
                Gestión de Accesos
            </button>

            <button class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="perfiles"
                    data-permiso="perfiles_ver">
                <i class="fa-solid fa-id-card-clip text-blue-600 w-5"></i>
                Perfiles y Permisos
            </button>

            <button class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="asistencia"
                    data-permiso="asistencia_ver">
                <i class="fa-solid fa-user-check text-blue-600 w-5"></i>
                Asistencia
            </button>

            <button class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="comedor"
                    data-permiso="comedor_ver">
                <i class="fa-solid fa-utensils text-blue-600 w-5"></i>
                Comedor
            </button>

            <button class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="armario"
                    data-permiso="documentos_ver">
                <i class="fa-solid fa-folder-open text-blue-600 w-5"></i>
                Armario Digital
            </button>

            <button class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="biblioteca"
                    data-permiso="biblioteca_ver">
                <i class="fa-solid fa-book text-blue-600 w-5"></i>
                Biblioteca
            </button>

            <button class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="horarios"
                    data-permiso="horarios_ver">
                <i class="fa-solid fa-calendar-days text-blue-600 w-5"></i>
                Horarios
            </button>

            <button class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-blue-50 transition-all"
                    data-section="actividades"
                    data-permiso="actividades_ver">
                <i class="fa-solid fa-calendar-check text-blue-600 w-5"></i>
                Actividades
            </button>

        </nav>

        <div class="p-4 border-t border-blue-100">
            <button id="btnLogout" class="w-full h-11 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold flex items-center justify-center gap-2 transition-all">
                <i class="fa-solid fa-right-from-bracket"></i>
                Cerrar sesión
            </button>
        </div>

    </aside>
