<section id="section-dashboard" class="section-content fade-in" data-permiso="dashboard_ver">

    <div class="dashboard-hero-clean mb-8">

        <div>

            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight">
                Bienvenido/a al Sistema Montessori
            </h2>

            <p class="text-sm text-slate-600 mt-2 max-w-3xl leading-relaxed">
                Resumen general de la institución, con información útil según los permisos del usuario.
            </p>

            <div class="flex flex-wrap gap-3 mt-5">
                <div class="dashboard-mini-info">
                    <span>Usuario</span>
                    <strong id="dashNombreUsuario">--</strong>
                </div>

                <div class="dashboard-mini-info">
                    <span>Estado</span>
                    <strong id="dashEstadoSesion">Sesión activa</strong>
                </div>

                <div class="dashboard-mini-info">
                    <span>Permisos</span>
                    <strong id="dashCantidadPermisosUsuario">--</strong>
                </div>
            </div>
        </div>

        <div class="dashboard-access-box">
            <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wide mb-3">
                Accesos rápidos
            </p>

            <div class="grid grid-cols-2 gap-3">

                <button onclick="mostrarSeccion('asistencia')"
                        data-permiso="asistencia_ver"
                        class="dash-access dash-access-blue">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Asistencia</span>
                </button>

                <button onclick="mostrarSeccion('comedor')"
                        data-permiso="comedor_ver_totales"
                        class="dash-access dash-access-green">
                    <i class="fa-solid fa-utensils"></i>
                    <span>Comedor</span>
                </button>

                <button onclick="mostrarSeccion('perfiles')"
                        data-permiso="perfiles_ver"
                        class="dash-access dash-access-cyan">
                    <i class="fa-solid fa-id-card-clip"></i>
                    <span>Perfiles</span>
                </button>

                <button onclick="mostrarSeccion('horarios')"
                        data-permiso="horarios_ver"
                        class="dash-access dash-access-amber">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Horarios</span>
                </button>

            </div>

            <div class="mt-4">
                <a href="../back/api/dashboard/exportar_sigef.php" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-3 px-4 rounded-xl shadow-md transition-all flex items-center justify-between group cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-file-export text-emerald-400"></i>
                        <span>Exportar Reporte SIGEF</span>
                    </div>
                    <i class="fa-solid fa-download text-slate-400 group-hover:text-white transition-colors"></i>
                </a>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <div class="dashboard-stat-card">
            <div>
                <p>Usuarios activos</p>
                <h3 id="dashUsuariosActivos">--</h3>
                <span>Registrados en el sistema</span>
            </div>
            <i class="fa-solid fa-users bg-blue-50 text-blue-600"></i>
        </div>

        <div class="dashboard-stat-card" data-permiso="perfiles_ver">
            <div>
                <p>Perfiles</p>
                <h3 id="dashPerfilesActivos">--</h3>
                <span>Roles institucionales</span>
            </div>
            <i class="fa-solid fa-id-card-clip bg-cyan-50 text-cyan-600"></i>
        </div>

        <div class="dashboard-stat-card" data-permiso="asistencia_ver">
            <div>
                <p>Asistencia hoy</p>
                <h3 id="dashAsistenciaHoy">--</h3>
                <span>Presentes registrados</span>
            </div>
            <i class="fa-solid fa-user-check bg-emerald-50 text-emerald-600"></i>
        </div>

        <div class="dashboard-stat-card" data-permiso="comedor_ver_totales">
            <div>
                <p>Raciones comedor</p>
                <h3 id="dashRacionesComedor">--</h3>
                <span>Previstas para hoy</span>
            </div>
            <i class="fa-solid fa-utensils bg-amber-50 text-amber-600"></i>
        </div>

    </div>

    <div class="grid grid-cols-1 2xl:grid-cols-2 gap-6 mb-8">

        <div class="dashboard-panel" data-permiso="asistencia_ver">
            <div class="dashboard-panel-header">
                <div>
                    <h3>Asistencia del día</h3>
                    <p>Presentes y ausentes registrados.</p>
                </div>
                <div class="dashboard-panel-icon bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>

            <div class="dashboard-chart-box dashboard-chart-donut">
                <canvas id="graficoAsistencia"></canvas>
            </div>
        </div>

        <div class="dashboard-panel" data-permiso="comedor_ver_totales">
            <div class="dashboard-panel-header">
                <div>
                    <h3>Inventario del comedor</h3>
                    <p>Stock actual de los principales insumos.</p>
                </div>
                <div class="dashboard-panel-icon bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>

            <div class="dashboard-chart-box">
                <canvas id="graficoInventario"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="dashboard-panel xl:col-span-2">
            <div class="dashboard-panel-header">
                <div>
                    <h3>Alertas importantes</h3>
                    <p>Situaciones que requieren atención institucional.</p>
                </div>
                <div class="dashboard-panel-icon bg-rose-50 text-rose-600">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>

            <!-- Contenedor dinámico (Seba borra lo que hay acá adentro) -->
            <div id="dashAlertas" class="space-y-3 mb-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                    Cargando alertas del sistema...
                </div>
            </div>
            
            <!-- Alerta de Carga Pendiente (Afuera del contenedor dinámico para que no se borre) -->
            <div class="flex items-start gap-3 p-3 bg-orange-50 border border-orange-100 rounded-lg">
                <i class="fa-solid fa-triangle-exclamation text-orange-500 mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-orange-800">Carga Pendiente</p>
                    <p class="text-xs text-orange-600">Falta registrar la asistencia de 2do Año División B.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-panel" data-permiso="comedor_ver_totales">
            <div class="dashboard-panel-header">
                <div>
                    <h3>Comedor</h3>
                    <p>Resumen del día.</p>
                </div>
                <div class="dashboard-panel-icon bg-orange-50 text-orange-600">
                    <i class="fa-solid fa-bowl-food"></i>
                </div>
            </div>

            <div class="space-y-3">
                <div class="dashboard-info-box">
                    <span>Menú del día</span>
                    <strong id="dashMenuDia">--</strong>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="dashboard-number-box bg-blue-50 text-blue-700">
                        <strong id="dashStockBajo">--</strong>
                        <span>Stock bajo</span>
                    </div>

                    <div class="dashboard-number-box bg-cyan-50 text-cyan-700">
                        <strong id="dashUltimosIngresos">--</strong>
                        <span>Ingresos hoy</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Panel de Auditoría (Sin data-permiso para que se vea siempre por ahora) -->
    <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm dashboard-panel mt-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-800"><i class="fa-solid fa-clock-rotate-left text-blue-500 mr-2"></i> Últimos Movimientos</h3>
            <span class="text-xs font-semibold px-2 py-1 bg-slate-100 text-slate-600 rounded-md">Hoy</span>
        </div>
        <div class="space-y-4">
            <div class="flex items-center gap-3 text-sm">
                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-user-check text-xs"></i></div>
                <div class="flex-1"><p class="text-slate-700 font-semibold">Carga de asistencia</p><p class="text-xs text-slate-500">María Gómez - Hace 10 min</p></div>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-box-open text-xs"></i></div>
                <div class="flex-1"><p class="text-slate-700 font-semibold">Ingreso de mercadería</p><p class="text-xs text-slate-500">Juan Pérez - Hace 45 min</p></div>
            </div>
        </div>
    </div>

</section>