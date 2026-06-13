let chartAsistencia = null;
let chartInventario = null;
let chartPermisos = null;

const DASHBOARD_API_BASE = (typeof API_BASE !== 'undefined')
    ? API_BASE.replace('gestion_perfiles_accesos/', 'dashboard/')
    : '/Sistema-Montessori-Cabrera-Gonzalez-Puyol/montessori/back/api/dashboard/';

/* ==============================================================
   HELPERS
============================================================== */

function dashboardHeaders() {
    const token = typeof obtenerToken === 'function' ? obtenerToken() : '';

    return {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    };
}

async function dashboardGet(endpoint) {
    return fetch(DASHBOARD_API_BASE + endpoint, {
        method: 'GET',
        headers: dashboardHeaders()
    });
}

async function leerJSONDashboard(response) {
    try {
        return await response.json();
    } catch (error) {
        return {
            mensaje: 'Respuesta inválida del servidor.'
        };
    }
}

function setDashboardText(id, valor) {
    const elemento = document.getElementById(id);

    if (elemento) {
        elemento.textContent = valor;
    }
}

function escaparDashboard(valor) {
    if (typeof escaparHTML === 'function') {
        return escaparHTML(valor);
    }

    return String(valor ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function obtenerNombreUsuarioDashboard() {
    if (typeof obtenerUsuario !== 'function') return 'Usuario';

    const usuario = obtenerUsuario();

    if (!usuario) return 'Usuario';

    const nombreCompleto = `${usuario.nombre || ''} ${usuario.apellido || ''}`.trim();

    return nombreCompleto || usuario.nombre_usuario || usuario.usuario || 'Usuario';
}

/* ==============================================================
   CARGA PRINCIPAL
============================================================== */

async function cargarDashboard() {
    cargarDatosLocalesDashboard();

    try {
        const response = await dashboardGet('obtener_dashboard.php');
        const data = await leerJSONDashboard(response);

        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo cargar el dashboard.');
        }

        pintarDashboard(data);
        pintarGraficosDashboard(data);

    } catch (error) {
        console.error(error);

        pintarAlertasDashboard([
            {
                tipo: 'error',
                texto: 'No se pudieron cargar los datos completos del dashboard. Verificá el endpoint obtener_dashboard.php.'
            }
        ]);
    }
}

/* ==============================================================
   DATOS LOCALES DEL USUARIO
============================================================== */

function cargarDatosLocalesDashboard() {
    const permisos = typeof obtenerPermisos === 'function' ? obtenerPermisos() : [];
    const usuario = obtenerNombreUsuarioDashboard();

    setDashboardText('dashNombreUsuario', usuario);
    setDashboardText('dashEstadoSesion', 'Sesión activa');
    setDashboardText('dashCantidadPermisosUsuario', permisos.length + ' permiso/s');

    setDashboardText(
        'dashPermisosTexto',
        permisos.length > 0 ? permisos.join(', ') : 'No hay permisos cargados.'
    );

    const permisosViejo = document.getElementById('listaPermisosTexto');

    if (permisosViejo) {
        permisosViejo.textContent = permisos.length > 0 ? permisos.join(', ') : 'No hay permisos cargados.';
    }
}

/* ==============================================================
   PINTAR DASHBOARD
============================================================== */

function pintarDashboard(data) {
    const usuariosActivos = data.usuarios_activos ?? '--';
    const pendientes = data.pendientes ?? 0;
    const perfilesActivos = data.perfiles_activos ?? '--';
    const permisosActivos = data.permisos_activos ?? '--';

    setDashboardText('dashUsuariosActivos', usuariosActivos);
    setDashboardText('dashPerfilesActivos', perfilesActivos);

    setDashboardText('contadorUsuarios', usuariosActivos);
    setDashboardText('contadorPendientes', pendientes);
    setDashboardText('contadorPerfiles', perfilesActivos);
    setDashboardText('contadorPermisos', permisosActivos);

    const asistencia = data.asistencia || {};

    setDashboardText('dashPresentes', asistencia.presentes ?? 0);
    setDashboardText('dashAusentes', asistencia.ausentes ?? 0);
    setDashboardText('dashAlertasAsistencia', asistencia.alertas ?? 0);
    setDashboardText('dashAsistenciaHoy', asistencia.presentes ?? 0);

    const comedor = data.comedor || {};

    setDashboardText('dashRacionesComedor', comedor.raciones ?? 0);
    setDashboardText('dashStockBajo', comedor.stock_bajo ?? 0);
    setDashboardText('dashUltimosIngresos', comedor.ultimos_ingresos ?? 0);

    if (comedor.menu_texto) {
        setDashboardText('dashMenuDia', comedor.menu_texto);
    } else if (comedor.menu) {
        const partesMenu = [
            comedor.menu.desayuno ? `Desayuno: ${comedor.menu.desayuno}` : '',
            comedor.menu.almuerzo ? `Almuerzo: ${comedor.menu.almuerzo}` : '',
            comedor.menu.merienda ? `Merienda: ${comedor.menu.merienda}` : ''
        ].filter(Boolean);

        setDashboardText('dashMenuDia', partesMenu.length > 0 ? partesMenu.join(' | ') : 'Sin menú cargado');
    } else {
        setDashboardText('dashMenuDia', 'Sin menú cargado');
    }

    pintarAlertasDashboard(data.alertas || []);
}

/* ==============================================================
   ALERTAS
============================================================== */

function pintarAlertasDashboard(alertas) {
    const contenedor = document.getElementById('dashAlertas');

    if (!contenedor) return;

    if (!Array.isArray(alertas) || alertas.length === 0) {
        contenedor.innerHTML = `
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-800 font-semibold">
                <i class="fa-solid fa-circle-check mr-2"></i>
                No hay alertas importantes por el momento.
            </div>
        `;
        return;
    }

    contenedor.innerHTML = alertas.map(alerta => {
        const tipo = alerta.tipo || 'info';
        const texto = alerta.texto || alerta.mensaje || alerta;

        let clases = 'border-blue-100 bg-blue-50 text-blue-800';
        let icono = 'fa-circle-info';

        if (tipo === 'warning') {
            clases = 'border-amber-100 bg-amber-50 text-amber-800';
            icono = 'fa-triangle-exclamation';
        }

        if (tipo === 'error') {
            clases = 'border-rose-100 bg-rose-50 text-rose-800';
            icono = 'fa-circle-exclamation';
        }

        if (tipo === 'success') {
            clases = 'border-emerald-100 bg-emerald-50 text-emerald-800';
            icono = 'fa-circle-check';
        }

        return `
            <div class="rounded-2xl border ${clases} p-4 text-sm font-semibold">
                <i class="fa-solid ${icono} mr-2"></i>
                ${escaparDashboard(texto)}
            </div>
        `;
    }).join('');
}
function pintarGraficosDashboard(data) {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js no está cargado.');
        return;
    }

    pintarGraficoAsistencia(data);
    pintarGraficoInventario(data);
    pintarGraficoPermisos(data);
}

function pintarGraficoAsistencia(data) {
    const canvas = document.getElementById('graficoAsistencia');
    if (!canvas) return;

    const asistencia = data.asistencia || {};

    const presentes = Number(asistencia.presentes || 0);
    const ausentes = Number(asistencia.ausentes || 0);

    let labels = ['Presentes', 'Ausentes'];
    let valores = [presentes, ausentes];
    let colores = [
        'rgba(16, 185, 129, 0.85)',
        'rgba(244, 63, 94, 0.85)'
    ];
    let bordes = [
        'rgba(16, 185, 129, 1)',
        'rgba(244, 63, 94, 1)'
    ];

    if (presentes === 0 && ausentes === 0) {
        labels = ['Sin registros'];
        valores = [1];
        colores = ['rgba(226, 232, 240, 0.95)'];
        bordes = ['rgba(203, 213, 225, 1)'];
    }

    if (chartAsistencia) {
        chartAsistencia.destroy();
    }

    chartAsistencia = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: valores,
                backgroundColor: colores,
                borderColor: bordes,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (presentes === 0 && ausentes === 0) {
                                return 'Sin asistencia cargada hoy';
                            }

                            return `${context.label}: ${context.raw}`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 900
            }
        }
    });
}

function pintarGraficoInventario(data) {
    const canvas = document.getElementById('graficoInventario');
    if (!canvas) return;

    const inventario = data.graficos?.inventario || [];

    const inventarioLimitado = inventario.slice(0, 6);

const labels = inventarioLimitado.length > 0
    ? inventarioLimitado.map(item => String(item.insumo).slice(0, 14))
    : ['Sin datos'];

const valores = inventarioLimitado.length > 0
    ? inventarioLimitado.map(item => Number(item.cantidad || 0))
    : [0];

    if (chartInventario) {
        chartInventario.destroy();
    }

    chartInventario = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Stock',
                data: valores,
                backgroundColor: 'rgba(37, 99, 235, 0.75)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 2,
                borderRadius: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            animation: {
                duration: 900,
                easing: 'easeOutQuart'
            }
        }
    });
}

function pintarGraficoPermisos(data) {
    const canvas = document.getElementById('graficoPermisos');
    if (!canvas) return;

    const permisos = data.graficos?.permisos_por_modulo || [];

    const labels = permisos.length > 0
        ? permisos.map(item => item.modulo)
        : ['Sin datos'];

    const valores = permisos.length > 0
        ? permisos.map(item => Number(item.total || 0))
        : [0];

    if (chartPermisos) {
        chartPermisos.destroy();
    }

    chartPermisos = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Permisos',
                data: valores,
                backgroundColor: 'rgba(14, 165, 233, 0.75)',
                borderColor: 'rgba(14, 165, 233, 1)',
                borderWidth: 2,
                borderRadius: 12
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            animation: {
                duration: 900,
                easing: 'easeOutQuart'
            }
        }
    });
}