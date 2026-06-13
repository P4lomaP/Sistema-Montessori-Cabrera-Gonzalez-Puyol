/*
  Interacciones de la landing Montessori.
  Cambiá LOGIN_URL si el archivo de inicio de sesión está en otra ruta.
*/
const LOGIN_URL = "/Sistema-Montessori-Cabrera-Gonzalez-Puyol/montessori/front/modulos/auth/login.php";
document.querySelectorAll("[data-login-link]").forEach((link) => {
    link.setAttribute("href", LOGIN_URL);
});

// 1. SWITCH INTEGRADO DE MODO OSCURO / MODO CLARO HÍBRIDO
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');

        themeToggleBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                themeToggleIcon.classList.replace('fa-moon', 'fa-sun');
                themeToggleIcon.classList.add('text-amber-400');
            } else {
                themeToggleIcon.classList.replace('fa-sun', 'fa-moon');
                themeToggleIcon.classList.remove('text-amber-400');
            }
        });

        // 2. Acordeón Desplegable para Preguntas FAQ
        document.querySelectorAll('.toggle-faq').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const icon = button.querySelector('.fa-chevron-down');
                content.classList.toggle('hidden');
                icon.parentElement.classList.toggle('rotate-180');
            });
        });

        // 3. Control de Animación de Contadores de Métricas de Venta
        const counters = document.querySelectorAll('.counter');
        const speed = 140; 

        const triggerCounters = () => {
            counters.forEach(counter => {
                const animate = () => {
                    const targetValue = +counter.getAttribute('data-target');
                    const currentValue = +counter.innerText;
                    const increment = targetValue / speed;
                    if (currentValue < targetValue) {
                        counter.innerText = Math.ceil(currentValue + increment);
                        setTimeout(animate, 12);
                    } else {
                        counter.innerText = targetValue;
                    }
                }
                animate();
            });
        }

        // 4. Mecanismo de Revelación Blur por Scroll mediante Intersection Observer AUTOMÁTICO REPARADO
        const observerOptions = { threshold: 0.05, rootMargin: "0px 0px -40px 0px" };
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    if (entry.target.querySelector('.counter')) {
                        triggerCounters();
                    }
                }
            });
        }, observerOptions);

        // Captura y observa todos los elementos marcados para revelarse de corrido
        document.querySelectorAll('.scroll-reveal').forEach(el => revealObserver.observe(el));
        document.querySelectorAll('.interactive-glow-card').forEach(el => revealObserver.observe(el));

        // 5. Rastrear Coordenadas del Mouse para Efecto Aura Premium
        const updateCardGlow = (e, card) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        };

        document.querySelectorAll('.interactive-glow-card').forEach(card => {
            card.addEventListener('mousemove', (e) => updateCardGlow(e, card));
        });

        // 6. Timeline Interactivo Desplegable (Cerrar/Abrir sub-pasos)
        function toggleStepDetails(stepNum) {
            const desc = document.getElementById(`step-desc-${stepNum}`);
            desc.classList.toggle('hidden');
        }
