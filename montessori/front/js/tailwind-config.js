/*
  Configuración personalizada de Tailwind para Montessori.
  Este archivo debe cargarse junto al CDN de Tailwind.
*/
tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            electric: '#2563eb',
                            cyberBlue: '#3b82f6',
                            lightBg: '#f8fafc',
                            darkVoid: '#070a13',
                            darkCard: '#0f1424'
                        }
                    },
                    boxShadow: {
                        'premium': '0 25px 60px -15px rgba(37, 99, 235, 0.08)',
                        'cyber-glow': '0 0 40px rgba(37, 99, 235, 0.25)',
                        'dark-premium': '0 25px 60px -15px rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        }
