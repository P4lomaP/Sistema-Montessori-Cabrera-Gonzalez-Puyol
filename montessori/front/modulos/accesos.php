<section id="section-accesos" class="section-content hidden fade-in" data-permiso="usuarios_ver">
<div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-6 mb-6" data-permiso="usuarios_activar">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-extrabold text-slate-900">Registrar nuevo usuario</h3>
            <p class="text-xs text-slate-500">
                Cargue un nuevo usuario institucional. La cuenta quedará pendiente hasta ser activada por Dirección.
            </p>
        </div>

        <div class="badge badge-soft">
            <i class="fa-solid fa-user-plus"></i>
            Alta de usuario
        </div>
    </div>
        
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">DNI</label>
            <input id="registroDni" type="text" class="input-montessori" placeholder="Ej: 45970018">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Nombre</label>
            <input id="registroNombre" type="text" class="input-montessori" placeholder="Ej: Maximiliano">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Apellido</label>
            <input id="registroApellido" type="text" class="input-montessori" placeholder="Ej: Cabrera">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Correo electrónico</label>
            <input id="registroCorreo" type="email" class="input-montessori" placeholder="usuario@gmail.com">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Contraseña inicial</label>
            <input id="registroPassword" type="password" class="input-montessori" placeholder="Contraseña">
        </div>

    </div>
    

    <div class="mt-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <p class="text-xs text-slate-500">
            Luego de registrar, el usuario aparecerá en la lista de pendientes para ser activado.
        </p>

        <button onclick="registrarUsuarioDesdeAdmin()"
                class="h-11 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white text-sm font-bold transition-all">
            <i class="fa-solid fa-user-plus mr-2"></i>
            Registrar usuario
        </button>
    </div>
</div>
<div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden mb-6" data-permiso="usuarios_activar">
    <div class="p-5 border-b border-blue-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-slate-900">Usuarios registrados</h3>
            <p class="text-xs text-slate-500">
                Active o desactive cuentas sin eliminar la información del usuario.
            </p>
        </div>

        <button onclick="listarUsuariosAccesos()"
                class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
            Actualizar usuarios
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="text-left p-4">DNI</th>
                    <th class="text-left p-4">Usuario</th>
                    <th class="text-left p-4">Correo</th>
                    <th class="text-left p-4">Estado</th>
                    <th class="text-right p-4">Acción</th>
                </tr>
            </thead>
            <tbody id="tablaUsuariosAccesos" class="divide-y divide-slate-100">
                <tr>
                    <td colspan="5" class="tabla-vacia">Cargando usuarios...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden" data-permiso="usuarios_activar">
                        <div class="p-5 border-b border-blue-100 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900">Usuarios pendientes</h3>
                                <p class="text-xs text-slate-500">Cuentas que requieren activación institucional.</p>
                            </div>
                            <button onclick="listarPendientes()" class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
                                Actualizar
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                                    <tr>
                                        <th class="text-left p-4">DNI</th>
                                        <th class="text-left p-4">Nombre</th>
                                        <th class="text-right p-4">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaPendientes" class="divide-y divide-slate-100">
                                    <tr>
                                        <td colspan="3" class="tabla-vacia">Cargando...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden" data-permiso="usuarios_desbloquear">
                        <div class="p-5 border-b border-blue-100 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900">Solicitudes de desbloqueo</h3>
                                <p class="text-xs text-slate-500">Usuarios que cambiaron contraseña y esperan aprobación.</p>
                            </div>
                            <button onclick="listarPendientes()" class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
                                Actualizar
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                                    <tr>
                                        <th class="text-left p-4">DNI</th>
                                        <th class="text-left p-4">Usuario</th>
                                        <th class="text-right p-4">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaDesbloqueos" class="divide-y divide-slate-100">
                                    <tr>
                                        <td colspan="3" class="tabla-vacia">Cargando...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </section>
