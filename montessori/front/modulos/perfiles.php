<section id="section-perfiles" class="section-content hidden fade-in" data-permiso="perfiles_ver">

    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 mb-6">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">Gestión de perfiles y permisos</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Administre perfiles funcionales, permisos por módulo y asignaciones múltiples a usuarios.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <button class="tab-perfiles active px-4 py-3 rounded-xl text-sm font-bold bg-slate-100 text-slate-600 hover:bg-blue-50 transition-all"
                        data-tab-perfiles="tab-perfiles-listado"
                        onclick="mostrarTabPerfiles('tab-perfiles-listado')">
                    <i class="fa-solid fa-id-card-clip mr-2 text-blue-600"></i>
                    Perfiles
                </button>

                <button class="tab-perfiles px-4 py-3 rounded-xl text-sm font-bold bg-slate-100 text-slate-600 hover:bg-blue-50 transition-all"
                        data-tab-perfiles="tab-permisos-listado"
                        data-permiso="permisos_asignar"
                        onclick="mostrarTabPerfiles('tab-permisos-listado')">
                    <i class="fa-solid fa-key mr-2 text-blue-600"></i>
                    Permisos
                </button>

                <button class="tab-perfiles px-4 py-3 rounded-xl text-sm font-bold bg-slate-100 text-slate-600 hover:bg-blue-50 transition-all"
                        data-tab-perfiles="tab-configurar-perfil"
                        data-permiso="permisos_asignar"
                        onclick="mostrarTabPerfiles('tab-configurar-perfil')">
                    <i class="fa-solid fa-sliders mr-2 text-blue-600"></i>
                    Configurar perfil
                </button>

                <button class="tab-perfiles px-4 py-3 rounded-xl text-sm font-bold bg-slate-100 text-slate-600 hover:bg-blue-50 transition-all"
                        data-tab-perfiles="tab-usuarios-perfiles"
                        data-permiso="perfiles_asignar"
                        onclick="mostrarTabPerfiles('tab-usuarios-perfiles')">
                    <i class="fa-solid fa-user-gear mr-2 text-blue-600"></i>
                    Usuarios y perfiles
                </button>
            </div>
        </div>
    </div>

    <!-- TAB 1: PERFILES -->
    <div id="tab-perfiles-listado" class="tab-perfiles-content fade-in">

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6" data-permiso="perfiles_crear">
                <h3 class="text-lg font-extrabold text-slate-900">Crear nuevo perfil</h3>
                <p class="text-xs text-slate-500 mb-5">
                    Cree perfiles funcionales según las acciones que podrá realizar cada usuario.
                    Por ejemplo: Gestión institucional completa, Carga de asistencia o Consulta documental.
                </p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Nombre del perfil</label>
                        <input id="nombrePerfil"
                               type="text"
                               class="input-montessori"
                               placeholder="Ej: Gestión de asistencia">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Descripción</label>
                        <input id="descripcionPerfil"
                               type="text"
                               class="input-montessori"
                               placeholder="Ej: Permite consultar y cargar asistencia diaria">
                    </div>

                    <button onclick="crearPerfil()"
                            class="w-full h-11 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white text-sm font-bold transition-all">
                        Crear perfil
                    </button>
                </div>
            </div>

            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-extrabold text-slate-900">Resumen de perfiles</h3>
                <p class="text-xs text-slate-500 mb-5">
                    Cada perfil agrupa permisos. Un usuario puede tener más de un perfil.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100">
                        <p class="text-xs font-bold text-blue-700 uppercase">Total perfiles</p>
                        <h4 id="resumenTotalPerfiles" class="text-3xl font-extrabold text-blue-950 mt-1">--</h4>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <p class="text-xs font-bold text-slate-500 uppercase">Permisos cargados</p>
                        <h4 id="resumenTotalPermisos" class="text-3xl font-extrabold text-slate-900 mt-1">--</h4>
                    </div>
                </div>

                <div class="mt-5 bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-800 leading-relaxed">
                    Un perfil puede reutilizar permisos que ya existen en otros perfiles.
                    También puede recibir permisos nuevos creados desde la pestaña Permisos.
                </div>
            </div>

        </div>

        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden mt-6">
            <div class="p-5 border-b border-blue-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Perfiles existentes</h3>
                    <p class="text-xs text-slate-500">Usuarios y permisos asociados a cada perfil.</p>
                </div>

                <button onclick="listarPerfiles()"
                        class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
                    Actualizar
                </button>
            </div>

            <div id="listaPerfiles" class="p-5 grid grid-cols-1 xl:grid-cols-2 gap-4">
                <p class="text-sm text-slate-400">Cargando...</p>
            </div>
        </div>

    </div>

    <!-- TAB 2: PERMISOS -->
    <!-- TAB 2: PERMISOS -->
<div id="tab-permisos-listado" class="tab-perfiles-content hidden fade-in" data-permiso="permisos_asignar">

    <div class="permisos-hero">
        <div>
            <span class="permisos-kicker">
                <i class="fa-solid fa-key"></i>
                Administración de permisos
            </span>

            <h3>Permisos del sistema</h3>

            <p>
                Gestione qué acciones pueden realizar los usuarios dentro de cada módulo institucional.
                Los permisos se asignan luego a los perfiles.
            </p>
        </div>

        <button onclick="listarPermisos()" class="permisos-btn-refresh">
            <i class="fa-solid fa-rotate-right"></i>
            Actualizar
        </button>
    </div>

    <div class="permisos-resumen-grid">
        <div class="permiso-resumen-card">
            <div class="permiso-resumen-icon">
                <i class="fa-solid fa-key"></i>
            </div>
            <div>
                <span>Total permisos</span>
                <strong id="resumenPermisosTotal">--</strong>
            </div>
        </div>

        <div class="permiso-resumen-card">
            <div class="permiso-resumen-icon">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <span>Módulos del sistema</span>
                <strong id="resumenModulosTotal">--</strong>
            </div>
        </div>

        <div class="permiso-resumen-card">
            <div class="permiso-resumen-icon">
                <i class="fa-solid fa-id-card-clip"></i>
            </div>
            <div>
                <span>Perfiles asociados</span>
                <strong id="resumenPerfilesAsociados">--</strong>
            </div>
        </div>

        <div class="permiso-resumen-card">
            <div class="permiso-resumen-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <span>Usuarios alcanzados</span>
                <strong id="resumenUsuariosAlcanzados">--</strong>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- CREAR NUEVO PERMISO -->
        <div class="xl:col-span-1 bg-white border border-blue-100 rounded-2xl shadow-sm p-6">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Crear permiso</h3>
                    <p class="text-xs text-slate-500">
                        Seleccione un módulo existente y agregue una acción.
                    </p>
                </div>

                <div class="h-11 w-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-plus"></i>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Módulo existente</label>
                    <select id="selectModuloPermiso" class="input-montessori">
                        <option value="">Seleccione un módulo</option>
                        <option value="dashboard">Dashboard</option>
                        <option value="usuarios">Usuarios y accesos</option>
                        <option value="perfiles">Perfiles</option>
                        <option value="permisos">Permisos</option>
                        <option value="asistencia">Asistencia</option>
                        <option value="comedor">Comedor</option>
                        <option value="armario">Armario</option>
                        <option value="biblioteca">Biblioteca</option>
                        <option value="horarios">Horarios</option>
                        <option value="documentos">Documentos</option>
                        <option value="actividades">Actividades</option> </select>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Acción / permiso</label>
                    <input id="nuevaAccionPermiso"
                           type="text"
                           maxlength="40"
                           class="input-montessori"
                           placeholder="Ej: ver, cargar, editar">
                </div>

                <div class="permiso-ayuda">
                    <i class="fa-solid fa-circle-info"></i>
                    <p>
                        Ejemplo: si selecciona <b>Asistencia</b> y escribe <b>cargar</b>,
                        se creará <b>asistencia_cargar</b>.
                    </p>
                </div>

                <button onclick="crearPermiso()" class="permisos-btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Crear permiso
                </button>
            </div>
        </div>

        <!-- LISTADO -->
        <div class="xl:col-span-2 bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="permisos-toolbar">
                <div>
                    <h3>Permisos existentes</h3>
                    <p id="mensajeFiltroPermisos">
                        Listado compacto de permisos por módulo y acción.
                    </p>
                </div>

                <div class="permisos-filtros">
                    <select id="filtroModuloPermisos"
                            onchange="filtrarListaPermisos()"
                            class="input-montessori">
                        <option value="">Todos los módulos</option>
                    </select>

                    <input id="buscarPermisoListado"
                           oninput="filtrarListaPermisos()"
                           type="text"
                           class="input-montessori"
                           placeholder="Buscar permiso...">
                </div>
            </div>

            <div id="listaPermisos" class="p-5">
                <div class="permisos-loading">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Cargando permisos...</span>
                </div>
            </div>
        </div>

    </div>

</div>
    <!-- TAB 3: CONFIGURAR PERFIL -->
    <div id="tab-configurar-perfil" class="tab-perfiles-content hidden fade-in" data-permiso="permisos_asignar">

        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6">

            <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Configurar permisos del perfil</h3>
                    <p class="text-xs text-slate-500">
                        Seleccione un perfil y marque qué módulos y acciones podrá utilizar.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 w-full xl:w-[720px]">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Perfil a configurar</label>
                        <select id="selectPerfilConfig" class="input-montessori" onchange="seleccionarPerfilConfig()">
                            <option value="">Seleccione un perfil</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Buscar permiso</label>
                        <input id="buscarPermisoConfig"
                               oninput="filtrarPermisosConfig()"
                               type="text"
                               class="input-montessori"
                               placeholder="Ej: asistencia, ver, comedor...">
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white text-blue-600 flex items-center justify-center border border-blue-100">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-950 text-sm">Configuración de permisos</h4>
                        <p class="text-xs text-blue-800 leading-relaxed mt-1">
                            Los permisos se agrupan por módulos existentes del sistema.
                            Puede crear nuevas acciones para esos módulos desde la pestaña Permisos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-5">
                <button onclick="marcarTodosPermisos()" class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold">
                    Marcar todos
                </button>

                <button onclick="desmarcarTodosPermisos()" class="h-10 px-4 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold">
                    Desmarcar todos
                </button>

                <button onclick="marcarSoloLectura()" class="h-10 px-4 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold">
                    Solo lectura
                </button>
            </div>

            <div id="matrizPermisos" class="grid grid-cols-1 xl:grid-cols-2 gap-5"></div>

            <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <p id="resumenPermisosSeleccionados" class="text-xs text-slate-500">
                    Seleccione un perfil para configurar sus permisos.
                </p>

                <button onclick="guardarPermisosPerfil()"
                        class="h-12 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Guardar permisos del perfil
                </button>
            </div>

        </div>

    </div>

    <!-- TAB 4: USUARIOS Y PERFILES -->
    <div id="tab-usuarios-perfiles" class="tab-perfiles-content hidden fade-in" data-permiso="perfiles_asignar">

        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Asignar perfiles a usuarios</h3>
                    <p class="text-xs text-slate-500">
                        Un usuario puede tener más de un perfil. Puede agregar o quitar perfiles de forma dinámica.
                    </p>
                </div>

                <button onclick="cargarUsuariosYPerfiles()"
                        class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
                    Actualizar listas
                </button>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

                <div class="xl:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Usuario</label>
                    <select id="selectUsuarioPerfil"
                            class="input-montessori"
                            onchange="cargarPerfilesDelUsuarioSeleccionado()">
                        <option value="">Seleccione usuario</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Perfil a agregar</label>
                    <select id="selectPerfilUsuario" class="input-montessori">
                        <option value="">Seleccione perfil</option>
                    </select>
                </div>

            </div>

            <div class="mt-4">
                <button onclick="asignarPerfilUsuario()"
                        class="h-11 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold">
                    Asignar perfil al usuario
                </button>
            </div>

        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">

            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div>
                        <h4 class="font-extrabold text-slate-900">Perfiles actuales del usuario</h4>
                        <p id="textoUsuarioSeleccionado" class="text-xs text-slate-500 mt-1">
                            Seleccione un usuario para ver sus perfiles.
                        </p>
                    </div>
                    <span id="contadorPerfilesUsuario" class="badge badge-soft">0 perfiles</span>
                </div>

                <div id="perfilesActualesUsuario" class="flex flex-wrap gap-2">
                    <p class="text-sm text-slate-400">Sin usuario seleccionado.</p>
                </div>
            </div>

            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6">
                <h4 class="font-extrabold text-slate-900">Permisos finales heredados</h4>
                <p class="text-xs text-slate-500 mt-1 mb-4">
                    Es la suma de permisos de todos los perfiles asignados al usuario.
                </p>

                <p id="permisosFinalesUsuario"
                   class="text-xs text-slate-600 break-words leading-relaxed bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    Seleccione un usuario para ver la suma de permisos de todos sus perfiles.
                </p>
            </div>

        </div>

    </div>

</section>