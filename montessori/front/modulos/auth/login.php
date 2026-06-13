<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Montessori</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        .reveal-scale {
            opacity: 0;
            transform: scale(0.97) translateY(12px);
            animation: revealScale 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        @keyframes revealScale {
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-shake { animation: shake 0.4s ease-in-out; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-2px); }
        }
        .hero-overlay {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.92) 0%, rgba(6, 182, 212, 0.82) 100%);
            background-size: cover;
        }
        .panel-blob {
            background: radial-gradient(circle, rgba(34, 211, 238, 0.35) 0%, rgba(0, 0, 0, 0) 70%);
        }
        .soft-float { animation: softFloat 2.8s ease-in-out infinite; }
        @keyframes softFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
    </style>
</head>

<body class="bg-gradient-to-b from-white via-blue-50/20 to-white min-h-screen flex items-center justify-center font-sans antialiased text-slate-900 overflow-x-hidden">

<div class="w-full min-h-screen flex flex-col md:flex-row">

    <div class="w-full md:w-[45%] hero-overlay p-8 md:p-16 flex flex-col justify-between text-white relative overflow-hidden shadow-2xl">

        <div class="absolute -top-20 -left-20 w-96 h-96 panel-blob pointer-events-none rounded-full filter blur-3xl opacity-70"></div>

        <div class="flex items-center gap-3 reveal-scale relative z-10">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/30 shadow-lg">
                <i class="fa-solid fa-graduation-cap text-xl text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-white">Montessori</h1>
                <p class="text-[10px] text-cyan-100 tracking-widest uppercase font-bold">Portal Educativo</p>
            </div>
        </div>

        <div class="space-y-6 my-auto max-w-md reveal-scale relative z-10" style="animation-delay: 0.1s;">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md text-white rounded-full text-xs font-semibold border border-white/20 shadow-sm">
                <span>🔒</span> Entorno de Red Seguro
            </div>

            <h2 class="text-4xl font-extrabold leading-tight md:text-5xl tracking-tight drop-shadow-md">
                Conectando la <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-200 via-white to-cyan-100 font-black">
                    educación
                </span>
            </h2>

            <p class="text-sm text-cyan-50/90 leading-relaxed font-medium drop-shadow-sm">
                Acceso exclusivo para personal directivo, administrativo y docente homologado.
            </p>
        </div>

        <a href="../../landing.php" class="relative mt-8 md:mt-0 md:absolute md:bottom-8 md:left-8 flex items-center gap-2 text-white/70 hover:text-white text-sm font-semibold transition-colors group z-20 w-fit">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </div>
            Volver al inicio
        </a>

    </div>

    <div class="w-full md:w-[55%] flex items-center justify-center p-6 md:p-16 relative">

        <div class="w-full max-w-md bg-white border border-blue-100/60 rounded-2xl p-8 md:p-10 shadow-2xl shadow-blue-500/5 relative overflow-hidden reveal-scale">

            <div id="view-login" class="space-y-5">

                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl flex items-center justify-center mb-4 shadow-md text-white soft-float">
                        <i class="fa-solid fa-lock text-lg"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Iniciar sesión</h3>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-0.5">Usuario (DNI)</label>
                    <input type="text" id="login-dni" placeholder="Ingresa tu documento" class="w-full h-12 px-4 rounded-xl border-2 border-slate-200 focus:border-blue-500 outline-none text-sm font-medium transition-all duration-300 focus:shadow-lg focus:shadow-blue-500/10">
                    <p id="error-dni" class="text-xs text-red-500 font-medium mt-1.5 hidden"><i class="fa-solid fa-circle-exclamation mr-1"></i> El DNI es obligatorio</p>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5 ml-0.5">
                        <label class="block text-xs font-semibold text-slate-700">Contraseña</label>
                        <button type="button" onclick="olvidoContrasenaIniciador()" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">¿Olvidaste tu contraseña?</button>
                    </div>
                    <div class="relative">
                        <input type="password" id="login-password" placeholder="••••••••" class="w-full h-12 pl-4 pr-12 rounded-xl border-2 border-slate-200 focus:border-blue-500 outline-none text-sm font-medium transition-all duration-300 focus:shadow-lg focus:shadow-blue-500/10">
                        <button type="button" id="toggle-pass" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-600 transition-colors"><i class="fa-regular fa-eye text-sm"></i></button>
                    </div>
                    <p id="error-password" class="text-xs text-red-500 font-medium mt-1.5 hidden"><i class="fa-solid fa-circle-exclamation mr-1"></i> La contraseña es obligatoria</p>
                </div>

                <button id="btn-ingresar" class="w-full h-12 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-500/20 mt-2 flex items-center justify-center gap-2 transition-all duration-300 hover:scale-[1.02]">
                    <span>Ingresar al Portal</span>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>

            <div id="view-ask-email" class="hidden space-y-5 animate-[revealScale_0.4s_ease-out]">
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl flex items-center justify-center mb-4 text-white shadow-lg shadow-blue-500/20 soft-float">
                        <i class="fa-solid fa-envelope-circle-check text-lg"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Código enviado</h3>
                </div>
                <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-4 text-xs text-slate-700 leading-relaxed space-y-2 text-center">
                    <p class="font-medium">Enviamos el código de recuperación al correo registrado:</p>
                    <p id="txt-email-ofuscado" class="font-bold text-sm text-blue-900 bg-white border border-blue-100 rounded-lg py-1">correo@institucional.com</p>
                </div>
                <button id="btn-continuar-a-pin" class="w-full h-12 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl flex items-center justify-center gap-2 transition-all duration-300 hover:scale-[1.02]">
                    <span>Ingresar mi PIN</span>
                    <i class="fa-solid fa-key text-xs"></i>
                </button>
            </div>

            <div id="view-unlock" class="hidden space-y-5 animate-[revealScale_0.4s_ease-out]">
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl flex items-center justify-center mb-4 text-white shadow-lg shadow-blue-500/20 soft-float">
                        <i class="fa-solid fa-lock text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">Validar Código PIN</h3>
                    <p class="text-xs text-slate-500 mt-2">Ingrese el código de 6 dígitos enviado a su correo.</p>
                </div>
                <div>
                    <input type="text" id="unlock-pin" placeholder="000000" maxlength="6" class="w-full h-14 text-center tracking-[0.8em] text-xl font-bold rounded-xl border-2 border-blue-300 bg-blue-50/10 text-slate-800 outline-none focus:border-blue-500 transition-all duration-300 focus:scale-[1.02] focus:shadow-lg focus:shadow-blue-500/10">
                    <p id="error-pin" class="text-xs text-red-500 font-medium mt-2 text-center hidden"><i class="fa-solid fa-circle-exclamation"></i> PIN de 6 dígitos requerido</p>
                </div>
                <button id="btn-verificar-pin" class="w-full h-12 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 transition-all duration-300 hover:scale-[1.02]">
                    <span>Verificar Código</span>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>

            <div id="view-new-password" class="hidden space-y-5 animate-[revealScale_0.4s_ease-out]">
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl flex items-center justify-center mb-4 text-white shadow-lg shadow-blue-500/20 soft-float">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">Nueva Contraseña</h3>
                    <p class="text-xs text-slate-500 mt-2">Cree una nueva contraseña segura para recuperar el acceso.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nueva Contraseña</label>
                    <input type="password" id="new-pass-1" placeholder="Mínimo 6 caracteres" class="w-full h-11 px-4 rounded-xl border-2 border-slate-200 focus:border-blue-500 outline-none text-sm font-medium transition-all duration-300 focus:shadow-lg focus:shadow-blue-500/10">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Confirmar Contraseña</label>
                    <input type="password" id="new-pass-2" placeholder="Repite la contraseña" class="w-full h-11 px-4 rounded-xl border-2 border-slate-200 focus:border-blue-500 outline-none text-sm font-medium transition-all duration-300 focus:shadow-lg focus:shadow-blue-500/10">
                    <p id="error-pass-match" class="text-xs text-red-500 font-medium mt-1.5 hidden"><i class="fa-solid fa-circle-exclamation"></i> Las contraseñas no coinciden o son muy cortas.</p>
                </div>
                <button id="btn-guardar-clave" class="w-full h-12 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-500/30 flex items-center justify-center gap-2">
                    <span>Actualizar contraseña</span>
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                </button>
            </div>

            <div id="view-success-unlock" class="hidden text-center py-6 flex flex-col items-center justify-center animate-[revealScale_0.4s_ease-out]">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 border border-blue-100 shadow-inner soft-float">
                    <i class="fa-solid fa-clipboard-check text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Cambio registrado</h3>
                <p class="text-xs text-slate-500 my-5 px-2">Su contraseña ha sido actualizada. La cuenta fue enviada a Dirección y está a la espera de ser desbloqueada.</p>
                <button onclick="window.location.reload();" class="w-full h-11 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl shadow-md transition-all duration-300 hover:scale-[1.02]">
                    Volver al inicio
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    const API_URL = '/Sistema-Montessori-Cabrera-Gonzalez-Puyol/montessori/back/api/gestion_perfiles_accesos/';

    const btnLogin = document.getElementById('btn-ingresar');
    const dniInp = document.getElementById('login-dni');
    const passInp = document.getElementById('login-password');
    const pinInp = document.getElementById('unlock-pin');
    const btnPin = document.getElementById('btn-verificar-pin');
    const btnGoToPin = document.getElementById('btn-continuar-a-pin');
    const btnGuardarClave = document.getElementById('btn-guardar-clave');

    document.getElementById('toggle-pass').addEventListener('click', function () {
        const isPass = passInp.type === 'password';
        passInp.type = isPass ? 'text' : 'password';
        this.innerHTML = isPass ? '<i class="fa-regular fa-eye-slash text-sm"></i>' : '<i class="fa-regular fa-eye text-sm"></i>';
    });

    dniInp.addEventListener('input', e => { e.target.value = e.target.value.replace(/[^0-9]/g, ''); });
    pinInp.addEventListener('input', e => { e.target.value = e.target.value.replace(/[^0-9]/g, ''); });

    function clearErrors() {
        [dniInp, passInp, pinInp, document.getElementById('new-pass-1'), document.getElementById('new-pass-2')].forEach(i => {
            i.classList.remove('border-red-500', 'animate-shake');
        });
        document.querySelectorAll('[id^="error-"]').forEach(p => { p.classList.add('hidden'); });
    }

    function mostrarVista(idVista) {
        ['view-login', 'view-ask-email', 'view-unlock', 'view-new-password', 'view-success-unlock'].forEach(id => {
            document.getElementById(id).classList.add('hidden');
        });
        document.getElementById(idVista).classList.remove('hidden');
    }

    async function leerJSONSeguro(response) {
        const texto = await response.text();
        try {
            return JSON.parse(texto);
        } catch (error) {
            console.error('Respuesta no válida del servidor:', texto);
            throw new Error('El servidor devolvió una respuesta inválida.');
        }
    }

    btnLogin.addEventListener('click', async () => {
        clearErrors();
        let error = false;

        if (!dniInp.value.trim()) {
            dniInp.classList.add('border-red-500', 'animate-shake');
            document.getElementById('error-dni').classList.remove('hidden');
            error = true;
        }

        if (!passInp.value.trim()) {
            passInp.classList.add('border-red-500', 'animate-shake');
            document.getElementById('error-password').classList.remove('hidden');
            error = true;
        }

        if (error) return;

        btnLogin.disabled = true;
        btnLogin.innerHTML = `<i class="fa-solid fa-spinner animate-spin text-sm text-white"></i><span class="text-white">Verificando...</span>`;

        try {
            const response = await fetch(API_URL + 'login.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ dni: dniInp.value.trim(), password: passInp.value.trim() })
            });
            const res = await leerJSONSeguro(response);

            if (response.status === 200) {
                localStorage.setItem('token_montessori', res.token);
                localStorage.setItem('usuario_montessori', JSON.stringify({
                    id: res.usuario.id, nombre: res.usuario.nombre + ' ' + res.usuario.apellido, rol_nombre: 'Personal'
                }));
                localStorage.setItem('permisos_montessori', JSON.stringify(res.usuario.permisos || []));
                window.location.href = '../../index.php';
            } else if (response.status === 403) {
                if (res.requiere_pin === true) {
                    Swal.fire({ icon: 'warning', title: 'Cuenta bloqueada', text: 'Se ha excedido el límite de intentos. Iniciando recuperación...', confirmButtonColor: '#1d4ed8', timer: 2000, showConfirmButton: false });
                    setTimeout(() => { dispararDespachoDePinAutomatico(); }, 2000);
                } else {
                    Swal.fire({ icon: 'info', title: 'Cuenta en revisión', text: res.mensaje, confirmButtonColor: '#1d4ed8' });
                }
            } else {
                Swal.fire({ icon: 'error', title: 'Acceso denegado', text: res.mensaje || 'DNI o contraseña incorrectos.', confirmButtonColor: '#1d4ed8' });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'No se pudo conectar con el servidor.', confirmButtonColor: '#1d4ed8' });
        } finally {
            btnLogin.disabled = false;
            btnLogin.innerHTML = `<span class="text-white">Ingresar al Portal</span><i class="fa-solid fa-chevron-right text-xs text-white"></i>`;
        }
    });

    function olvidoContrasenaIniciador() {
        clearErrors();
        if (!dniInp.value.trim()) {
            dniInp.classList.add('border-red-500', 'animate-shake');
            document.getElementById('error-dni').classList.remove('hidden');
            Swal.fire({ icon: 'info', title: 'Ingrese su DNI', text: 'Coloque su DNI en el campo usuario para buscar su correo registrado.', confirmButtonColor: '#1d4ed8' });
            return;
        }
        dispararDespachoDePinAutomatico();
    }

    async function dispararDespachoDePinAutomatico() {
        const dni = dniInp.value.trim();
        if (!dni) { Swal.fire({ icon: 'info', title: 'DNI requerido', text: 'Ingrese su DNI para generar el PIN.', confirmButtonColor: '#1d4ed8' }); return; }

        Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const response = await fetch(API_URL + 'generar_pin.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ dni: dni })
            });
            const res = await leerJSONSeguro(response);
            Swal.close();

            if (response.status === 200) {
                localStorage.setItem('dni_recuperacion', dni);
                document.getElementById('txt-email-ofuscado').innerText = res.correo_destino || 'Correo registrado';
                mostrarVista('view-ask-email');
            } else {
                Swal.fire({ icon: 'error', title: 'Aviso', text: (res.mensaje || 'No se pudo generar el PIN.') + (res.error ? '\\n\\nDetalle: ' + res.error : ''), confirmButtonColor: '#1d4ed8' });
            }
        } catch (error) {
            Swal.close();
            console.error(error);
            Swal.fire({ icon: 'error', title: 'Fallo de red', text: 'Error al contactar con generar_pin.php. Ver consola.', confirmButtonColor: '#1d4ed8' });
        }
    }

    btnGoToPin.addEventListener('click', () => { mostrarVista('view-unlock'); });

    btnPin.addEventListener('click', async () => {
        clearErrors();
        if (pinInp.value.trim().length < 6) {
            pinInp.classList.add('border-red-500', 'animate-shake');
            document.getElementById('error-pin').classList.remove('hidden');
            return;
        }
        const dni = dniInp.value.trim() || localStorage.getItem('dni_recuperacion');
        if (!dni) { Swal.fire({ icon: 'error', title: 'DNI no encontrado', text: 'Debe iniciar nuevamente la recuperación.', confirmButtonColor: '#1d4ed8' }); mostrarVista('view-login'); return; }

        btnPin.disabled = true;
        btnPin.innerHTML = `<i class="fa-solid fa-spinner animate-spin text-sm text-white"></i><span class="text-white">Verificando...</span>`;

        try {
            const response = await fetch(API_URL + 'validar_pin.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ dni: dni, pin: pinInp.value.trim() })
            });
            const res = await leerJSONSeguro(response);

            if (res.status === 'ok') {
                mostrarVista('view-new-password');
            } else {
                Swal.fire({ icon: 'error', title: 'Código inválido', text: res.mensaje || 'El PIN ingresado no es correcto.', confirmButtonColor: '#1d4ed8' });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Error de red al validar el PIN.', confirmButtonColor: '#1d4ed8' });
        } finally {
            btnPin.disabled = false;
            btnPin.innerHTML = `<span class="text-white">Verificar Código</span><i class="fa-solid fa-chevron-right text-xs text-white"></i>`;
        }
    });

    btnGuardarClave.addEventListener('click', async () => {
        clearErrors();
        const p1 = document.getElementById('new-pass-1');
        const p2 = document.getElementById('new-pass-2');
        const errDiv = document.getElementById('error-pass-match');

        if (!p1.value.trim() || p1.value.trim().length < 6 || p1.value !== p2.value) {
            p1.classList.add('border-red-500', 'animate-shake');
            p2.classList.add('border-red-500', 'animate-shake');
            errDiv.classList.remove('hidden');
            return;
        }

        const dni = dniInp.value.trim() || localStorage.getItem('dni_recuperacion');
        if (!dni) { Swal.fire({ icon: 'error', title: 'DNI no encontrado', text: 'Debe iniciar nuevamente la recuperación.', confirmButtonColor: '#1d4ed8' }); mostrarVista('view-login'); return; }

        btnGuardarClave.disabled = true;
        btnGuardarClave.innerHTML = `<i class="fa-solid fa-spinner animate-spin text-sm text-white"></i><span class="text-white">Actualizando...</span>`;

        try {
            const response = await fetch(API_URL + 'cambiar_clave_pin.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ dni: dni, pin: pinInp.value.trim(), nueva_clave: p1.value.trim() })
            });
            const res = await leerJSONSeguro(response);

            if (response.status === 200) {
                localStorage.removeItem('dni_recuperacion');
                mostrarVista('view-success-unlock');
            } else {
                Swal.fire({ icon: 'error', title: 'Fallo de operación', text: res.mensaje || 'No se pudo cambiar la contraseña.', confirmButtonColor: '#1d4ed8' });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un problema de red.', confirmButtonColor: '#1d4ed8' });
        } finally {
            btnGuardarClave.disabled = false;
            btnGuardarClave.innerHTML = `<span class="text-white">Actualizar contraseña</span><i class="fa-solid fa-floppy-disk text-xs text-white"></i>`;
        }
    });
</script>

</body>
</html>