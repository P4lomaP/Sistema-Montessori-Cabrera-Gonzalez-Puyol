<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ESTRATEGIA SEO: Palabras clave críticas para el posicionamiento orgánico en Google -->
    <meta name="description" content="Montessori Portal Institucional Web es la plataforma edtech argentina líder en modernización escolar. Un sistema de gestión escolar digital completo con control de asistencia digital en colegios, armario digital y biblioteca.">
    <meta name="keywords" content="sistema de gestión escolar digital, plataforma edtech argentina, software para escuelas privadas, control de asistencia digital colegios, armario digital institucional, software de gestión escolar, modernización educativa web">
    
    <title>Montessori Portal Institucional Web | Sistema de Gestión Escolar Digital</title>
    <!-- IMPORTANTE: los botones de acceso al sistema usan la ruta configurada en js/main.js -->
    
    <!-- Tailwind CSS desde CDN para agilidad en desarrollo -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts Premium: Plus Jakarta Sans (Tipografía moderna de software) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome para Iconografía Corporativa de Alta Fidelidad -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Configuración personalizada de Tailwind -->
    <script src="js/tailwind-config.js"></script>
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-white text-slate-900 antialiased selection:bg-blue-600 selection:text-white overflow-x-hidden dark:bg-brand-darkVoid dark:text-slate-100">

    <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100/80 dark:bg-brand-darkVoid/70 dark:border-slate-900/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <!-- Identidad de Marca -->
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="w-12 h-12 bg-brand-electric rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:rotate-6 transition-transform duration-300">
                        <i class="fa-solid fa-graduation-cap text-2xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500 tracking-tight">Montessori</span>
                        <span class="text-[9px] font-bold tracking-widest text-slate-400 dark:text-slate-500 uppercase -mt-1">Portal Institucional Web</span>
                    </div>
                </div>
                
                <!-- Links de Navegación Estilo Filtro Burbuja -->
                <div class="hidden lg:flex items-center gap-1 bg-slate-100/80 dark:bg-slate-900/50 p-1.5 rounded-full border border-slate-200/40 dark:border-slate-800/40 px-4">
                    <a href="#inicio" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-brand-electric dark:hover:text-white transition-colors px-4 py-2 rounded-full hover:bg-white dark:hover:bg-slate-800">Inicio</a>
                    <a href="#filosofia" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-brand-electric dark:hover:text-white transition-colors px-4 py-2 rounded-full hover:bg-white dark:hover:bg-slate-800">Filosofía</a>
                    <a href="#problemas" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-brand-electric dark:hover:text-white transition-colors px-4 py-2 rounded-full hover:bg-white dark:hover:bg-slate-800">Beneficios</a>
                    <a href="#modulos" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-brand-electric dark:hover:text-white transition-colors px-4 py-2 rounded-full hover:bg-white dark:hover:bg-slate-800">Módulos</a>
                    <a href="#comparativa" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-brand-electric dark:hover:text-white transition-colors px-4 py-2 rounded-full hover:bg-white dark:hover:bg-slate-800">Antes vs Después</a>
                    <a href="#faq" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-brand-electric dark:hover:text-white transition-colors px-4 py-2 rounded-full hover:bg-white dark:hover:bg-slate-800">FAQ</a>
                </div>

                <!-- Acciones Principales Interactivas -->
                <div class="flex items-center gap-4">
                    <!-- Switch Oscuro/Claro Mutante -->
                    <button id="theme-toggle" class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-amber-400 flex items-center justify-center border border-slate-200/50 dark:border-slate-800/50 hover:scale-105 transition-all" aria-label="Cambiar modo claro u oscuro">
                        <i id="theme-toggle-icon" class="fa-solid fa-moon text-lg"></i>
                    </button>
                    <a data-login-link href="login.html" class="hidden sm:inline-flex items-center justify-center px-5 h-12 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:border-brand-electric hover:text-brand-electric transition-all">
                        Iniciar sesión <i class="fa-solid fa-right-to-bracket text-[10px] ml-2"></i>
                    </a>
                    <a href="#contacto" class="btn-liquid-premium inline-flex items-center justify-center px-6 h-12 text-xs font-bold text-white rounded-xl shadow-md hover:shadow-cyber-glow transform hover:-translate-y-0.5 transition-all duration-300">
                        Solicitar Demo <i class="fa-solid fa-chevron-right text-[9px] ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. HERO PRINCIPAL -->
    <section id="inicio" class="relative overflow-hidden pt-16 pb-24 lg:pt-28 lg:pb-36 bg-white dark:bg-brand-darkVoid">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-br from-blue-600 to-cyan-500 rounded-full blur-[130px] -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-emerald-400/5 dark:bg-indigo-500/5 rounded-full blur-[140px] -z-10 pointer-events-none"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:5rem_5rem] opacity-30 dark:opacity-25 [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div class="lg:col-span-6 text-center lg:text-left">
                <span class="inline-flex items-center gap-2 py-1.5 px-3 rounded-full text-[11px] font-extrabold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30 mb-6 shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-gradient-to-br from-blue-600 to-cyan-500 animate-pulse"></span>
                    Montessori Portal Institucional Web
                </span>
                <h1 class="font-extrabold text-5xl sm:text-6xl text-slate-900 dark:text-white tracking-tight leading-[1.05] mb-6">
                    Transformamos la gestión educativa en una <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500 bg-clip-text text-transparent">experiencia digital inteligente</span>.
                </h1>
                <p class="text-base text-slate-500 dark:text-slate-400 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                    Plataforma integral para digitalizar procesos institucionales, optimizar recursos y centralizar la información educativa. Reducí el trabajo manual eliminando el caos de las planillas de papel de forma definitiva.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a data-login-link href="login.html" class="w-full sm:w-auto btn-liquid-premium inline-flex items-center justify-center px-8 h-14 text-xs font-bold text-white rounded-xl shadow-xl hover:shadow-cyber-glow transform hover:-translate-y-0.5 transition-all">
                        Ingresar al sistema <i class="fa-solid fa-right-to-bracket text-[10px] ml-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- Visual Hero Mockup -->
            <div class="lg:col-span-6 relative scroll-reveal">
                <div class="relative mx-auto max-w-[500px] lg:max-w-none bg-slate-900/5 dark:bg-white/5 rounded-[32px] p-2.5 backdrop-blur-md border border-slate-200/60 dark:border-slate-800/40 shadow-premium dark:shadow-dark-premium">
                    <div class="bg-white dark:bg-brand-darkCard rounded-[22px] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden aspect-[4/3] flex flex-col">
                        <div class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-5 py-3.5 flex items-center justify-between">
                            <div class="flex gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                            </div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono bg-white dark:bg-brand-darkVoid border border-slate-200 dark:border-slate-800 px-4 py-1 rounded-md">Montessori</div>
                            <div class="w-6"></div>
                        </div>
                        <div class="p-6 flex-1 bg-slate-50/50 dark:bg-brand-darkCard grid grid-cols-3 gap-4">
                            <div class="col-span-3 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 p-5 rounded-2xl text-white flex justify-between items-center shadow-md">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-blue-100">ESTADO DEL SERVIDOR</p>
                                    <h3 class="text-2xl font-bold mt-1">100% Sincronizado</h3>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><i class="fa-solid fa-chart-line"></i></div>
                            </div>
                            <div class="bg-white dark:bg-brand-darkVoid p-4 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Comedor</p>
                                <p class="text-base font-extrabold text-slate-800 dark:text-white mt-1">342 Raciones</p>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1 rounded-full mt-2"><div class="bg-blue-600 h-1 rounded-full w-[75%]"></div></div>
                            </div>
                            <div class="bg-white dark:bg-brand-darkVoid p-4 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Armario Digital</p>
                                <p class="text-base font-extrabold text-slate-800 dark:text-white mt-1">Sincronizado</p>
                                <span class="inline-block text-[8px] bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 font-bold px-1.5 py-0.5 rounded mt-2">Acceso Web Seguro</span>
                            </div>
                            <div class="bg-white dark:bg-brand-darkVoid p-4 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Biblioteca</p>
                                <p class="text-base font-extrabold text-slate-800 dark:text-white mt-1">0 Alertas</p>
                                <span class="inline-block text-[8px] bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold px-1.5 py-0.5 rounded mt-2">Al Día</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. IDENTIDAD CONCEPTUAL: Historia de Maria Montessori (CON RUTA LOCAL CONFIGURADA) -->
    <section id="filosofia" class="py-24 bg-brand-lightBg dark:bg-brand-darkCard/20 border-y border-slate-100 dark:border-slate-900/60 scroll-reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Foto local configurada para que agregues tu imagen descargada -->
                <div class="lg:col-span-4 flex justify-center">
                    <div class="relative group max-w-[280px]">
                        <div class="absolute inset-0 bg-brand-electric rounded-[32px] blur-xl opacity-20 group-hover:opacity-35 transition-opacity duration-300"></div>
                        <!-- RUTA LOCAL ACTUALIZADA -->
                        <img src="imagenes/maria-montessori.jpg" alt="Dra. Maria Montessori" class="rounded-[28px] border-4 border-white dark:border-brand-darkCard shadow-premium object-cover aspect-[3/4] w-full relative z-10 transition-transform duration-500 group-hover:scale-[1.03]">
                        <p class="text-center text-[10px] text-slate-400 dark:text-slate-500 mt-3 font-semibold font-mono">Dra. Maria Montessori (1870 - 1952)</p>
                    </div>
                </div>
                
                <!-- Texto Comercial Justificado -->
                <div class="lg:col-span-8 text-justify">
                    <span class="text-xs font-bold text-brand-electric dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-950/50 px-3 py-1 rounded-full">Fundamento Académico</span>
                    <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight mt-4 mb-6 text-left">
                        Inspirado en la autonomía y el orden del modelo educativo original
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                        La Dra. Maria Montessori revolucionó la pedagogía mundial al demostrar científicamente que un entorno adecuadamente preparado, limpio y perfectamente estructurado otorga a los individuos la capacidad de autogestionarse con libertad, responsabilidad y estructura. Ella creía firmemente que el orden externo facilita la claridad mental y el desarrollo armónico de cualquier actividad.
                    </p>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        Nuestro <strong>Montessori Portal Institucional Web</strong> traslada ese concepto histórico hacia la órbita de la administración escolar moderna: proveemos un entorno digital unificado para que el equipo de preceptoría, docencia y dirección centralice sus tareas operativas cotidianas en una plataforma sin fricciones. Automatizamos la burocracia analógica para liberar tiempo valioso, devolviendo el foco institucional a la verdadera excelencia pedagógica en el aula.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-left">
                        <div class="bg-white dark:bg-brand-darkVoid p-5 rounded-2xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm">
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1">Entorno Preparado Web</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Módulos interactivos limpios donde cada herramienta está en su lugar intuitivo, reduciendo errores humanos.</p>
                        </div>
                        <div class="bg-white dark:bg-brand-darkVoid p-5 rounded-2xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm">
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1">Auto-Gestión Absoluta</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Estadísticas, presentes y reportes que se calculan solos, garantizando la fluidez del establecimiento.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. PROBLEMAS QUE RESOLVEMOS -->
    <section id="problemas" class="py-24 bg-white dark:bg-brand-darkVoid scroll-reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-20">
                <span class="text-xs font-bold text-red-500 dark:text-red-400 uppercase tracking-widest bg-red-50 dark:bg-red-950/20 px-3 py-1 rounded-full">Dificultades Actuales</span>
                <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-4 tracking-tight">
                    La carga administrativa tradicional desgasta a las instituciones
                </h2>
                <p class="text-base text-slate-500 dark:text-slate-400 mt-3">Las ineficiencias analógicas representan horas de trabajo perdidas por mes.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="interactive-glow-card p-8 rounded-2xl bg-brand-lightBg dark:bg-brand-darkCard/40 border border-slate-200/60 dark:border-slate-800/60">
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/30 text-red-500 dark:text-red-400 flex items-center justify-center mb-6 z-10 relative"><i class="fa-solid fa-book-bookmark text-xl"></i></div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-3 z-10 relative">Libros físicos y registros en papel</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Depender de archivadores manuales expone a la institución a desgastes, pérdidas de datos cruciales y ocupación de espacio físico.</p>
                </div>
                <div class="interactive-glow-card p-8 rounded-2xl bg-brand-lightBg dark:bg-brand-darkCard/40 border border-slate-200/60 dark:border-slate-800/60">
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/30 text-red-500 dark:text-red-400 flex items-center justify-center mb-6 z-10 relative"><i class="fa-solid fa-clock-rotate-left text-xl"></i></div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-3 z-10 relative">Búsquedas e informes lentos</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Rastrear el legajo histórico de un alumno, circulares antiguas o actas de exámenes se convierte en un laberinto burocrático de minutos.</p>
                </div>
                <div class="interactive-glow-card p-8 rounded-2xl bg-brand-lightBg dark:bg-brand-darkCard/40 border border-slate-200/60 dark:border-slate-800/60">
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/30 text-red-500 dark:text-red-400 flex items-center justify-center mb-6 z-10 relative"><i class="fa-solid fa-copy text-xl"></i></div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-3 z-10 relative">Duplicación de tareas y errores</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Transcribir la información escolar repetidas veces entre múltiples cuadernos y planillas aisladas aumenta los errores operativos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. MÓDULOS DE EXCELENCIA -->
    <section id="modulos" class="py-24 bg-brand-lightBg dark:bg-brand-darkCard/10 scroll-reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-20">
                <span class="text-xs font-bold text-brand-electric dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-950/40 px-3 py-1 rounded-full">Infraestructura Completa</span>
                <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-4">Funcionalidades homologadas para el control de procesos</h2>
                <p class="text-base text-slate-500 dark:text-slate-400 mt-3">Herramientas nativas en la web alineadas con las demandas y el trabajo de secretaría y preceptoría.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Módulos Corporativos -->
                <div class="interactive-glow-card p-6 bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-5 z-10 relative"><i class="fa-solid fa-clipboard-user text-sm"></i></div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 z-10 relative">Módulo Asistencia</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Control de asistencia digital en colegios en tiempo real. Cargas directas desde entornos móviles sin planillas físicas de firmas.</p>
                    </div>
                </div>
                <div class="interactive-glow-card p-6 bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-5 z-10 relative"><i class="fa-solid fa-utensils text-sm"></i></div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 z-10 relative">Módulo Comedor</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Gestión operativa diaria de raciones solicitadas y estadísticas de consumo por turnos, facilitando el trabajo de cocina.</p>
                    </div>
                </div>
                <div class="interactive-glow-card p-6 bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-5 z-10 relative"><i class="fa-solid fa-box-open text-sm"></i></div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 z-10 relative">Armario Digital</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Resguardo digitalizado institucional de actas de examen, circulares de tutorías y documentación interna protegida bajo cifrado.</p>
                    </div>
                </div>
                <div class="interactive-glow-card p-6 bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-5 z-10 relative"><i class="fa-solid fa-book text-sm"></i></div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 z-10 relative">Módulo Biblioteca</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Administración del catálogo escolar físico, control automatizado de préstamos activos y envío de alertas automáticas de devoluciones.</p>
                    </div>
                </div>
                <div class="interactive-glow-card p-6 bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-5 z-10 relative"><i class="fa-solid fa-calendar-days text-sm"></i></div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 z-10 relative">Control de Horarios</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Grilla interactiva para organizar la carga horaria docente y asignación de aulas, previniendo superposiciones de forma inteligente.</p>
                    </div>
                </div>
                <div class="interactive-glow-card p-6 bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-5 z-10 relative"><i class="fa-solid fa-gauge text-sm"></i></div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 z-10 relative">Dashboard Macro</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Visualización centralizada de indicadores críticos, tasas de presentismo e informes gráficos en tiempo real para el equipo de dirección.</p>
                    </div>
                </div>
                <div class="interactive-glow-card p-6 bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-5 z-10 relative"><i class="fa-solid fa-users-gear text-sm"></i></div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 z-10 relative">Gestión de Usuarios</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed z-10 relative">Asignación de credenciales estructuradas de acceso y permisos adaptados por rol institucional (Admin, Preceptor, Docente).</p>
                    </div>
                </div>
                <div class="p-6 bg-slate-950 rounded-2xl text-white border border-slate-900 flex flex-col justify-between shadow-premium dark:shadow-dark-premium">
                    <div>
                        <h3 class="font-bold text-base mb-1">¿Buscás optimizar un proceso local?</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Nuestra plataforma se calibra según los marcos regulatorios normativos específicos requeridos por tu institución.</p>
                    </div>
                    <a href="#contacto" class="text-xs font-bold text-white bg-brand-electric py-3 px-4 rounded-xl text-center hover:bg-blue-700 transition-colors mt-4">Consultar Adaptación</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 6 y 7. COMPARATIVA ANTES VS DESPUÉS + CARRUSEL DE 7 FOTOS REALES -->
    <section id="comparativa" class="py-24 bg-white dark:bg-brand-darkVoid border-t border-slate-100 dark:border-slate-900/60 scroll-reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-20">
                <span class="text-xs font-bold text-brand-electric dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-950/40 px-3 py-1 rounded-full">El Salto de Paradigma</span>
                <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-4">Transformación radical en la dinámica escolar</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-20">
                <div class="bg-brand-lightBg dark:bg-brand-darkCard/30 p-8 rounded-2xl border border-slate-200/60 dark:border-slate-800/60 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-slate-400 text-white text-[9px] uppercase font-extrabold tracking-widest px-4 py-1.5 rounded-bl-xl">Modelo Analógico</div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-6 flex items-center gap-2.5"><i class="fa-solid fa-circle-xmark text-rose-500"></i> Gestión Tradicional</h3>
                    <ul class="space-y-4 text-xs text-slate-500 dark:text-slate-400">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-minus text-slate-300"></i> Dependencia absoluta de archivos de cartón y planillas de papel.</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-minus text-slate-300"></i> Demoras operativas diarias en el rastreo manual de expedientes antiguos.</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-minus text-slate-300"></i> Desconexión de información entre aulas y administración general.</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-minus text-slate-300"></i> Confección manual lenta de métricas propensa a fallas de escritura.</li>
                    </ul>
                </div>
                <div class="bg-white dark:bg-brand-darkCard p-8 rounded-2xl border-2 border-brand-electric shadow-premium dark:shadow-dark-premium relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-brand-electric text-white text-[9px] uppercase font-extrabold tracking-widest px-4 py-1.5 rounded-bl-xl">Portal Web</div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-6 flex items-center gap-2.5"><i class="fa-solid fa-circle-check text-emerald-500"></i> Ecosistema Integrado</h3>
                    <ul class="space-y-4 text-xs text-slate-700 dark:text-slate-300">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-brand-electric font-bold"></i> Resguardo 100% digitalizado en servidores seguros.</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-brand-electric font-bold"></i> Acceso instantáneo a cualquier documento desde el buscador inteligente.</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-brand-electric font-bold"></i> Base de datos unificada en tiempo real e interoperable entre roles.</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-brand-electric font-bold"></i> Confección automatizada de reportes estadísticos gerenciales.</li>
                    </ul>
                </div>
            </div>

            <!-- Carrusel Infinito con Tus 7 Fotos Reales -->
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center mb-8">Modernización Real del Portal</p>
            <div class="relative w-full flex items-center overflow-hidden py-4">
                <div class="marquee-track gap-6">
                    <!-- Foto 1 -->
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative group shadow-md shrink-0">
                        <img src="imagenes/imagen1.jpg" alt="Imagen 1" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs">
                            <span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 01</span>
                            Infraestructura Educativa Conectada
                        </div>
                    </div>
                    <!-- Foto 2 -->
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative group shadow-md shrink-0">
                        <img src="imagenes/imagen2.jpg" alt="Imagen 2" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs">
                            <span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 02</span>
                            Asistencia Móvil Docente
                        </div>
                    </div>
                    <!-- Foto 3 -->
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative group shadow-md shrink-0">
                        <img src="imagenes/imagen3.jpg" alt="Imagen 3" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs">
                            <span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 03</span>
                            Entorno Cero Papel
                        </div>
                    </div>
                    <!-- Foto 4 -->
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative group shadow-md shrink-0">
                        <img src="imagenes/imagen4.jpg" alt="Imagen 4" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs">
                            <span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 04</span>
                            Centralización Web Segura
                        </div>
                    </div>
                    <!-- Foto 5 -->
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative group shadow-md shrink-0">
                        <img src="imagenes/imagen5.jpg" alt="Imagen 5" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs">
                            <span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 05</span>
                            Automatización de Biblioteca
                        </div>
                    </div>
                    <!-- Foto 6 -->
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative group shadow-md shrink-0">
                        <img src="imagenes/imagen6.jpg" alt="Imagen 6" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs">
                            <span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 06</span>
                            Control Operativo de Comedor
                        </div>
                    </div>
                    <!-- Foto 7 -->
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative group shadow-md shrink-0">
                        <img src="imagenes/imagen7.jpg" alt="Imagen 7" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs">
                            <span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 07</span>
                            Optimización de Tiempos
                        </div>
                    </div>

                    <!-- CLONES PARA LOOP INFINITO -->
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative shrink-0"><img src="imagenes/imagen1.jpg" alt="Clone 1" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs"><span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 01</span>Infraestructura Conectada</div></div>
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative shrink-0"><img src="imagenes/imagen2.jpg" alt="Clone 2" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs"><span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 02</span>Asistencia Móvil Docente</div></div>
                    <div class="w-80 h-52 rounded-3xl overflow-hidden relative shrink-0"><img src="imagenes/imagen3.jpg" alt="Clone 3" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent p-6 flex flex-col justify-end text-white text-xs"><span class="text-blue-400 font-bold uppercase tracking-wider text-[9px] mb-1">Imagen 03</span>Reducción de Papel</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. SECCIÓN DE ESTADÍSTICAS DINÁMICAS -->
    <section class="py-20 bg-brand-lightBg dark:bg-brand-darkCard/20 scroll-reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <h3 class="font-extrabold text-5xl text-brand-electric dark:text-blue-400 tracking-tight"><span class="counter" data-target="95">0</span>%</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold mt-2">Menos consumo de papel</p>
                </div>
                <div>
                    <h3 class="font-extrabold text-5xl text-brand-electric tracking-tight"><span class="counter" data-target="80">0</span>%</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold mt-2">Menos tareas administrativas repetitivas</p>
                </div>
                <div>
                    <h3 class="font-extrabold text-5xl text-brand-electric tracking-tight"><span class="counter" data-target="100">0</span>%</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold mt-2">Información unificada en la web</p>
                </div>
                <div>
                    <h3 class="font-extrabold text-5xl text-brand-electric tracking-tight">24/7</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold mt-2">Acceso protegido multiplataforma</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. CÓMO FUNCIONA EL PORTAL (Timeline Desplegable Interactivo) -->
    <section class="py-24 bg-white dark:bg-brand-darkVoid scroll-reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-20">
                <span class="text-xs font-bold text-brand-electric dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-950/40 px-3 py-1 rounded-full">Despliegue Técnico</span>
                <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-4 tracking-tight">Fases de implementación interactiva</h2>
                <p class="text-base text-slate-500 dark:text-slate-400 mt-2">Hacé clic en cada etapa para descubrir las integraciones y flujos internos.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 max-w-5xl mx-auto">
                <!-- Fase 1 -->
                <div class="interactive-step p-5 bg-brand-lightBg dark:bg-brand-darkCard/40 rounded-2xl border border-slate-200/50 dark:border-slate-800/50 cursor-pointer hover:border-blue-500 group transition-all" onclick="toggleStepDetails(1)">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-950 text-brand-electric dark:text-blue-400 font-bold text-xs flex items-center justify-center mb-4 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">1</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1 flex justify-between items-center">Registro <i class="fa-solid fa-plus text-[10px] text-slate-400"></i></h4>
                    <p id="step-desc-1" class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed transition-all">Alta institucional del establecimiento en servidores seguros web.</p>
                </div>
                <!-- Fase 2 -->
                <div class="interactive-step p-5 bg-brand-lightBg dark:bg-brand-darkCard/40 rounded-2xl border border-slate-200/50 dark:border-slate-800/50 cursor-pointer hover:border-blue-500 group transition-all" onclick="toggleStepDetails(2)">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-950 text-brand-electric dark:text-blue-400 font-bold text-xs flex items-center justify-center mb-4 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">2</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1 flex justify-between items-center">Ajustes <i class="fa-solid fa-plus text-[10px] text-slate-400"></i></h4>
                    <p id="step-desc-2" class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed transition-all">Alta y asignación de perfiles, roles y cargas horarias de materias.</p>
                </div>
                <!-- Fase 3 -->
                <div class="interactive-step p-5 bg-brand-lightBg dark:bg-brand-darkCard/40 rounded-2xl border border-slate-200/50 dark:border-slate-800/50 cursor-pointer hover:border-blue-500 group transition-all" onclick="toggleStepDetails(3)">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-950 text-brand-electric dark:text-blue-400 font-bold text-xs flex items-center justify-center mb-4 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">3</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1 flex justify-between items-center">Carga <i class="fa-solid fa-plus text-[10px] text-slate-400"></i></h4>
                    <p id="step-desc-3" class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed transition-all">Migración de expedientes y actas viejas hacia el Armario Digital.</p>
                </div>
                <!-- Fase 4 -->
                <div class="interactive-step p-5 bg-brand-lightBg dark:bg-brand-darkCard/40 rounded-2xl border border-slate-200/50 dark:border-slate-800/40 cursor-pointer hover:border-blue-500 group transition-all" onclick="toggleStepDetails(4)">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-950 text-brand-electric dark:text-blue-400 font-bold text-xs flex items-center justify-center mb-4 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">4</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1 flex justify-between items-center">Rutina <i class="fa-solid fa-plus text-[10px] text-slate-400"></i></h4>
                    <p id="step-desc-4" class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed transition-all">Uso del portal para presentismo, biblioteca y raciones de cocina.</p>
                </div>
                <!-- Fase 5 -->
                <div class="interactive-step p-5 bg-slate-950 text-white rounded-2xl border border-slate-900 cursor-pointer hover:border-blue-500 group transition-all" onclick="toggleStepDetails(5)">
                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center mb-4 shadow-sm shadow-blue-500/20">5</div>
                    <h4 class="font-bold text-sm mb-1 flex justify-between items-center">Reporte <i class="fa-solid fa-plus text-[10px] text-slate-400"></i></h4>
                    <p id="step-desc-5" class="text-slate-400 text-[11px] leading-relaxed transition-all">Generación automática de balances estadísticos listos para secretaría.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. BENEFICIOS POR ROL -->
    <section class="py-24 bg-brand-lightBg dark:bg-brand-darkCard/10 border-y border-slate-100 dark:border-slate-900/60 scroll-reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-20">
                <span class="text-xs font-bold text-brand-electric dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-950/40 px-3 py-1 rounded-full">Beneficios Orientados</span>
                <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-4">Soluciones específicas para cada nivel del equipo</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-white dark:bg-brand-darkCard p-8 rounded-3xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-brand-electric dark:text-blue-400 flex items-center justify-center font-heading font-bold text-lg mb-6">01</div>
                    <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-3">Equipo de Dirección</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Obtenga una supervisión macro del estado de la institución. Reportes rápidos automáticos de presentismo escolar para agilizar la toma de decisiones estratégicas basadas en datos.</p>
                </div>
                <div class="bg-white dark:bg-brand-darkCard p-8 rounded-3xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-brand-electric dark:text-blue-400 flex items-center justify-center font-heading font-bold text-lg mb-6">02</div>
                    <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-3">Equipo de Docencia</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Reducción drástica de la carga de tareas burocráticas manuales. Registro ágil de asistencia digital por curso desde cualquier smartphone y acceso inmediato a las fichas de biblioteca.</p>
                </div>
                <div class="bg-white dark:bg-brand-darkCard p-8 rounded-3xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-brand-electric dark:text-blue-400 flex items-center justify-center font-heading font-bold text-lg mb-6">03</div>
                    <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-3">Equipo de Administración</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Eliminación definitiva de transcripciones repetitivas propensas a errores. Automatización y control en la organización de horarios institucionales, control de comedor y resguardo seguro.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 11. TESTIMONIOS CON ESTÉTICA MASONRY -->
    <section class="py-24 bg-white dark:bg-brand-darkVoid scroll-reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-20">
                <span class="text-xs font-bold text-brand-electric dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-950/40 px-3 py-1 rounded-full">Garantía de Confianza</span>
                <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-4">La validación de quienes transformaron sus escuelas</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="p-6 rounded-2xl bg-brand-lightBg dark:bg-brand-darkCard/40 border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <p class="text-slate-600 dark:text-slate-400 text-xs italic leading-relaxed">"La velocidad para confeccionar los balances de asistencia e informes mensuales cambió por completo desde que incorporamos el Portal Montessori Web. El ahorro en tiempo administrativo es extraordinario."</p>
                    <div class="flex items-center gap-3 mt-6">
                        <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">MA</div>
                        <div>
                            <h5 class="text-xs font-bold text-slate-900 dark:text-white">María Inés Almirón</h5>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Directora</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-brand-lightBg dark:bg-brand-darkCard/40 border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <p class="text-slate-600 dark:text-slate-400 text-xs italic leading-relaxed">"Cargar las asistencias de los alumnos en un solo clic desde la tablet en el aula y saber que secretaría ya tiene el dato consolidado elimina fricciones y papeleos diarios."</p>
                    <div class="flex items-center gap-3 mt-6">
                        <div class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs">RC</div>
                        <div>
                            <h5 class="text-xs font-bold text-slate-900 dark:text-white">Ricardo Céspedes</h5>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Docente</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-brand-lightBg dark:bg-brand-darkCard/40 border border-slate-200/60 dark:border-slate-800/60 flex flex-col justify-between">
                    <p class="text-slate-600 dark:text-slate-400 text-xs italic leading-relaxed">"El orden del Armario Digital y el control inteligente del stock de biblioteca nos quitó una pesada mochila de encima. Encontrar actas históricas ahora toma segundos."</p>
                    <div class="flex items-center gap-3 mt-6">
                        <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">EG</div>
                        <div>
                            <h5 class="text-xs font-bold text-slate-900 dark:text-white">Eugenia Gómez</h5>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Secretaria Administrativa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. FAQ ACORDEÓN -->
    <section id="faq" class="py-24 bg-brand-lightBg dark:bg-brand-darkCard/30 border-t border-slate-100 dark:border-slate-900/60 scroll-reveal">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-heading font-bold text-3xl text-slate-900 dark:text-white tracking-tight text-center mb-12">Preguntas frecuentes corporativas</h2>
            
            <div class="space-y-4">
                <div class="bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 overflow-hidden shadow-sm">
                    <button class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none toggle-faq group">
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-electric transition-colors">¿Se puede adaptar a diferentes niveles educativos e instituciones?</span>
                        <div class="w-7 h-7 rounded-full bg-slate-50 dark:bg-brand-darkVoid flex items-center justify-center text-slate-400 transition-transform duration-300"><i class="fa-solid fa-chevron-down text-[10px]"></i></div>
                    </button>
                    <div class="px-6 pb-5 text-xs text-slate-500 dark:text-slate-400 hidden leading-relaxed">Sí, absolutamente. El portal institucional web está diseñado bajo una arquitectura flexible y modular, lo que permite parametrizarlo con total precisión para cumplir con las exigencias y normativas específicas vigentes en tu región.</div>
                </div>
                <div class="bg-white dark:bg-brand-darkCard rounded-2xl border border-slate-200/60 dark:border-slate-800/60 overflow-hidden shadow-sm">
                    <button class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none toggle-faq group">
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-electric transition-colors">¿Es accesible desde celulares y requiere instalación física?</span>
                        <div class="w-7 h-7 rounded-full bg-slate-50 dark:bg-brand-darkVoid flex items-center justify-center text-slate-400 transition-transform duration-300"><i class="fa-solid fa-chevron-down text-[10px]"></i></div>
                    </button>
                    <div class="px-6 pb-5 text-xs text-slate-500 dark:text-slate-400 hidden leading-relaxed">No requiere ningún tipo de instalación local ni mantenimiento de servidores complejos en la escuela. Toda la plataforma es responsiva y accesible las 24 horas del día, los 7 días de la semana, desde computadoras, tablets o smartphones con conexión a internet.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 13. EMBUDO CTA FINAL -->
    <section id="contacto" class="py-24 bg-slate-950 text-white relative overflow-hidden rounded-[40px] mx-4 mb-8 shadow-2xl">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(37,99,235,0.15),transparent_40%)]"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div class="lg:col-span-6 text-center lg:text-left">
                <h2 class="font-heading font-bold text-4xl lg:text-5xl tracking-tight leading-tight mb-6">
                    ¿Listos para modernizar su institución educativa?
                </h2>
                <p class="text-slate-400 text-sm mb-8 max-w-md mx-auto lg:mx-0">
                    Completá tus datos institucionales para coordinar una reunión de relevamiento técnico personalizada y evaluar la integración del Montessori Portal Institucional Web en tu colegio.
                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-6 text-slate-500 text-xs font-semibold">
                    <div><i class="fa-solid fa-shield-halved text-brand-electric mr-1.5"></i> Datos Protegidos</div>
                    <div><i class="fa-solid fa-clock text-brand-electric mr-1.5"></i> Respuesta en menos de 24 horas</div>
                </div>
                <a data-login-link href="login.html" class="inline-flex items-center justify-center mt-8 px-7 h-12 text-xs font-bold text-white border border-white/20 rounded-xl hover:bg-white hover:text-slate-950 transition-all">
                    Ya tengo usuario: entrar al sistema <i class="fa-solid fa-arrow-right-to-bracket text-[10px] ml-2"></i>
                </a>
            </div>
            
            <div class="lg:col-span-6 bg-white rounded-3xl p-8 border border-slate-800/10 shadow-2xl text-slate-900 relative">
                <form action="#" method="POST" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Responsable Técnico/Directivo</label>
                            <input type="text" required placeholder="Ej: Sebastián Cabrera" class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-electric text-xs font-medium">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Establecimiento Escolar</label>
                            <input type="text" required placeholder="Ej: Colegio Provincial N° 1" class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-electric text-xs font-medium">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Correo Electrónico Oficial</label>
                            <input type="email" required placeholder="direccion@escuela.edu.ar" class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-electric text-xs font-medium">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Teléfono Celular de Contacto</label>
                            <input type="tel" required placeholder="+54 370 4123456" class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-electric text-xs font-medium">
                        </div>
                    </div>
                    <button type="submit" class="btn-liquid-premium w-full h-12 text-white font-bold text-xs rounded-xl shadow-lg transform hover:-translate-y-0.5 transition-all mt-2 uppercase tracking-wider">
                        Solicitar Implementación e Inicio de Demo
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- 14. FOOTER -->
    <footer class="bg-slate-950 text-slate-600 text-[11px] py-12 border-t border-slate-900/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-brand-electric rounded-md flex items-center justify-center text-white text-[10px]">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span class="font-heading font-bold text-slate-400 tracking-tight text-sm">Montessori</span>
            </div>
            <p>&copy; 2026 Montessori. Todos los derechos reservados. Desarrollo enfocado en el Montessori Portal Institucional Web.</p>
        </div>
    </footer>
    <!-- Interacciones de la landing -->
    <script src="js/landing.js"></script>
</body>
</html>