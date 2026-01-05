// C:\laragon\www\PichangaYa\pichangaya\resources\js\app.js
import { driver } from "driver.js";
import "driver.js/dist/driver.css";
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {

    // ============================================================
    // 1. MODO OSCURO (Standard)
    // ============================================================
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    const themeToggleBtn = document.getElementById('theme-toggle');
    const isDarkModeForbidden = document.documentElement.classList.contains('light');

    if (isDarkModeForbidden) {
        if (themeToggleBtn) themeToggleBtn.style.display = 'none';
        document.documentElement.classList.remove('dark');
    } else if (themeToggleBtn) {
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            if (themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
        } else {
            if (themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function () {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    }

    // ============================================================
    // 2. TUTORIAL: CONFIGURACIÓN ACTUALIZADA (Sin Sección Dueños)
    // ============================================================

    if (window.location.pathname === '/' || window.location.pathname === '') {

        const driverHome = driver({
            showProgress: true,
            animate: true,
            allowClose: true,
            nextBtnText: 'Siguiente ➡',
            prevBtnText: '⬅ Atrás',
            doneBtnText: '¡Listo!',
            steps: [
                {
                    element: '#hero-title',
                    popover: {
                        title: '¡Bienvenido a PichangaYa!',
                        description: 'Tu pichanga está a solo unos clics. Permítenos mostrarte cómo sacarle el máximo provecho a la plataforma.',
                        side: "bottom"
                    }
                },

                {
                    element: '#search-form',
                    popover: {
                        title: 'Buscador Inteligente',
                        description: 'Busca por nombre de complejo o usa los filtros rápidos.',
                        side: "bottom"
                    }
                },
                {
                    element: '#search-filters',
                    popover: {
                        title: 'Filtros de Precisión',
                        description: 'Encuentra canchas en tu distrito favorito (Wanchaq, San Sebastián, etc.) y filtra por el deporte que quieras jugar.',
                        side: "bottom"
                    }
                },
                ...(document.getElementById('tour-notificaciones') ? [{
                    element: '#tour-notificaciones',
                    popover: {
                        title: 'Notificaciones',
                        description: 'Aquí te avisaremos cuando tu reserva sea confirmada o si hay algún cambio importante.',
                        side: "bottom",
                        align: "end"
                    }
                }] : []),
                ...(document.getElementById('featured-section') ? [{
                    element: '#featured-section',
                    popover: {
                        title: 'Las Mejores Canchas',
                        description: 'Estas son las canchas destacadas por su excelente servicio y popularidad en Cusco.',
                        side: "top"
                    }
                }] : []),
                {
                    element: '#canchas-grid',
                    popover: {
                        title: 'Disponibilidad Total',
                        description: 'Explora nuestra lista completa de locales deportivos.',
                        side: "top"
                    }
                },
                {
                    element: 'article:first-of-type',
                    popover: {
                        title: 'Detalle de la Cancha',
                        description: 'Mira fotos reales, precios actualizados y los servicios incluidos (Grass, Techo, Wifi).',
                        side: "right"
                    }
                },
                // PASO: Favoritos
                ...(document.querySelector('.btn-favorito-tour') ? [{
                    element: '.btn-favorito-tour',
                    popover: {
                        title: 'Tus Favoritos',
                        description: 'Guarda las canchas que más te gustan para encontrarlas más rápido la próxima vez.',
                        side: "top"
                    }
                }] : []),
                {
                    element: 'article:first-of-type a',
                    popover: {
                        title: 'Reserva al Instante',
                        description: 'Haz clic para ver el calendario de horas libres y separar tu turno de inmediato.',
                        side: "top"
                    }
                },
                {
                    element: '#about-section',
                    popover: {
                        title: 'Seguridad y Confianza',
                        description: 'Somos una plataforma 100% cusqueña. Trabajamos para que tu única preocupación sea meter goles.',
                        side: "top"
                    }
                },
                {
                    element: 'footer',
                    popover: {
                        title: 'Soporte 24/7',
                        description: '¿Necesitas ayuda técnica? Contáctanos por WhatsApp desde el enlace en el pie de página.',
                        side: "top"
                    }
                },
                // PASO FINAL: Botón Flotante
                {
                    element: '#btn-ayuda-home',
                    popover: {
                        title: 'Ayuda y Soporte',
                        description: 'Si olvidas algo, puedes volver a ver este tutorial o contactarnos por WhatsApp desde este menú desplegable.',
                        side: "left"
                    }
                }
            ]
        });

        // 🟢 EXPOSICIÓN GLOBAL PARA ALPINE.JS
        window.driverHome = driverHome;

        // Auto-inicio solo la primera vez
        if (!localStorage.getItem('tutorial_welcome_completo')) {
            setTimeout(() => {
                driverHome.drive();
                localStorage.setItem('tutorial_welcome_completo', 'true');
            }, 1500);
        }
    }
});