<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal | Montessori</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" type="image/png" href="assets/favicon.png">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=12">

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
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen overflow-x-hidden">

<div class="flex min-h-screen">

    <?php include 'modulos/sidebar.php'; ?>

    <main class="flex-1 lg:ml-72 min-h-screen overflow-x-hidden max-w-full">

        <?php include 'modulos/topbar.php'; ?>

        <div class="p-6 lg:p-8 space-y-8">

            <?php include 'modulos/dashboard.php'; ?>

            <?php include 'modulos/accesos.php'; ?>

            <?php include 'modulos/perfiles.php'; ?>

            <?php include 'modulos/asistencia.php'; ?>

            <?php include 'modulos/comedor.php'; ?>

            <?php include 'modulos/armario.php'; ?>

            <?php include 'modulos/biblioteca.php'; ?>

            <?php include 'modulos/horarios.php'; ?>

            <?php include 'modulos/actividades.php'; ?>

            <?php include 'modulos/sin_permisos.php'; ?>

        </div>

    </main>

</div>

    <script src="https://cdn.jsdelivr.net/npm/darkreader@4.9.86/darkreader.min.js"></script>
    <script>
        const btnTema = document.getElementById('btnToggleTema');
        const iconoTema = document.getElementById('iconoTema');

        const configDarkReader = {
            brightness: 105,
            contrast: 110,  
            sepia: 0,
            darkSchemeBackgroundColor: '#0f172a', 
            darkSchemeTextColor: '#f8fafc'        
        };

        function activarModoOscuro() {
            DarkReader.enable(configDarkReader);
            if(iconoTema) {
                iconoTema.classList.remove('fa-moon');
                iconoTema.classList.add('fa-sun');
                iconoTema.style.transform = "rotate(360deg)"; 
            }
            localStorage.setItem('temaMontessori', 'oscuro');
        }

        function activarModoClaro() {
            DarkReader.disable();
            if(iconoTema) {
                iconoTema.classList.remove('fa-sun');
                iconoTema.classList.add('fa-moon');
                iconoTema.style.transform = "rotate(0deg)"; 
            }
            localStorage.setItem('temaMontessori', 'claro');
        }

        window.addEventListener('DOMContentLoaded', () => {
            DarkReader.disable(); 

            if (localStorage.getItem('temaMontessori') === 'oscuro') {
                activarModoOscuro();
            } else {
                activarModoClaro();
            }
        });

        if(btnTema) {
            btnTema.addEventListener('click', () => {
                if (localStorage.getItem('temaMontessori') === 'oscuro') {
                    activarModoClaro();
                } else {
                    activarModoOscuro();
                }
            });
        }
    </script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="js/config.js?v=1"></script>
<script src="js/auth.js?v=1"></script>
<script src="js/dashboard.js?v=6"></script>
<script src="js/main.js?v=2"></script>
<script src="js/accesos.js?v=1"></script>
<script src="js/perfiles.js?v=1"></script>
<script src="js/permisos.js?v=1"></script>
<script src="js/usuarios_perfiles.js?v=1"></script>
<script src="js/asistencia.js?v=1"></script>
<script src="js/comedor.js?v=1"></script>

<link rel="stylesheet" href="css/style.css?v=90">

</body>
</html>