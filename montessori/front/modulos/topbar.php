<header class="h-20 bg-white/80 backdrop-blur-xl border-b border-blue-100 sticky top-0 z-30 flex items-center justify-between px-4 lg:px-8">

    <div class="flex items-center">
        <button id="btnMenuMobile" class="lg:hidden text-slate-500 hover:text-blue-600 transition-colors text-xl mr-4">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div>
            <h2 id="tituloSeccion" class="text-xl font-extrabold text-slate-900">Dashboard</h2>
            <p class="text-xs hidden sm:block text-slate-500">Sistema institucional digital</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button id="btnToggleTema" class="h-10 w-10 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-500 transition-colors" title="Cambiar tema">
            <i id="iconoTema" class="fa-solid fa-moon text-xl"></i>
        </button>

        <div class="hidden md:flex flex-col items-end text-right">
            <span id="nombreUsuario" class="text-sm font-bold text-slate-900 block">Usuario</span>
            <span class="text-[10px] uppercase font-bold text-slate-500">Sesión activa</span>
        </div>

        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/20 flex-shrink-0">
            <i class="fa-solid fa-user"></i>
        </div>
    
    </div>

</header>