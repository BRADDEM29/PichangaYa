//C:\laragon\www\PichangaYa\pichangaya\resources\js\app.js
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
    // 2. TUTORIAL: SOLO EN HOME ("/")
    // ============================================================

    if (window.location.pathname === '/' || window.location.pathname === '') {

        const driverHome = driver({
            showProgress: true,
            animate: true,
            allowClose: true,
            nextBtnText: 'Siguiente ➡',
            prevBtnText: '⬅ Atrás',
            doneBtnText: '¡Listo, a jugar!',
            steps: [
                {
                    element: '#hero-title',
                    popover: {
                        title: '👋 ¡Bienvenido a PichangaYa!',
                        description: 'Tu pichanga está a solo unos clics. Permítenos mostrarte cómo sacarle el máximo provecho a la plataforma.',
                        side: "bottom"
                    }
                },
                // PASO NUEVO: Modo Oscuro
                ...(document.getElementById('theme-toggle') ? [{
                    element: '#theme-toggle',
                    popover: {
                        title: '🌙 Modo Noche',
                        description: '¿Prefieres un estilo oscuro? Cambia el aspecto de la web aquí para mayor comodidad visual.',
                        side: "bottom"
                    }
                }] : []),
                {
                    element: '#search-form',
                    popover: {
                        title: '🔍 Buscador Inteligente',
                        description: 'Busca por nombre de complejo o usa los filtros rápidos.',
                        side: "bottom"
                    }
                },
                // PASO NUEVO: Filtros Detallados
                {
                    element: '#search-filters',
                    popover: {
                        title: '📍 Filtros de Precisión',
                        description: 'Encuentra canchas en tu distrito favorito (Wanchaq, San Sebastián, etc.) y filtra por el deporte que quieras jugar.',
                        side: "bottom"
                    }
                },
                ...(document.getElementById('tour-notificaciones') ? [{
                    element: '#tour-notificaciones',
                    popover: {
                        title: '🔔 Notificaciones',
                        description: 'Aquí te avisaremos cuando tu reserva sea confirmada o si hay algún cambio importante.',
                        side: "bottom",
                        align: "end"
                    }
                }] : []),
                ...(document.getElementById('featured-section') ? [{
                    element: '#featured-section',
                    popover: {
                        title: '⭐ Las Mejores Canchas',
                        description: 'Estas son las canchas destacadas por su excelente servicio y popularidad en Cusco.',
                        side: "top"
                    }
                }] : []),
                {
                    element: '#canchas-grid',
                    popover: {
                        title: '📋 Disponibilidad Total',
                        description: 'Explora nuestra lista completa de locales deportivos.',
                        side: "top"
                    }
                },
                {
                    element: 'article:first-of-type',
                    popover: {
                        title: 'ℹ️ Detalle de la Cancha',
                        description: 'Mira fotos reales, precios actualizados y los servicios incluidos (Grass, Techo, Wifi).',
                        side: "right"
                    }
                },
                // PASO NUEVO: Favoritos
                ...(document.querySelector('.btn-favorito-tour') ? [{
                    element: '.btn-favorito-tour',
                    popover: {
                        title: '❤️ Tus Favoritos',
                        description: 'Guarda las canchas que más te gustan para encontrarlas más rápido la próxima vez.',
                        side: "top"
                    }
                }] : []),
                {
                    element: 'article:first-of-type a',
                    popover: {
                        title: '📅 Reserva al Instante',
                        description: 'Haz clic para ver el calendario de horas libres y separar tu turno de inmediato.',
                        side: "top"
                    }
                },
                // PASO NUEVO: Sección Dueños
                ...(document.getElementById('cta-owner-tour') ? [{
                    element: '#cta-owner-tour',
                    popover: {
                        title: '🏟️ ¿Eres dueño de una cancha?',
                        description: 'Únete a nuestra red y empieza a recibir reservas automáticas hoy mismo.',
                        side: "top"
                    }
                }] : []),
                {
                    element: '#about-section',
                    popover: {
                        title: '🤝 Seguridad y Confianza',
                        description: 'Somos una plataforma 100% cusqueña. Trabajamos para que tu única preocupación sea meter goles.',
                        side: "top"
                    }
                },
                {
                    element: 'footer',
                    popover: {
                        title: '📞 Soporte 24/7',
                        description: '¿Necesitas ayuda técnica? Contáctanos por WhatsApp desde el enlace en el pie de página.',
                        side: "top"
                    }
                },
                // PASO FINAL: El mismo botón
                {
                    element: '#btn-ayuda-home',
                    popover: {
                        title: '❓ Ayuda siempre disponible',
                        description: 'Si olvidas algo, puedes volver a ver este tutorial haciendo clic en este botón verde.',
                        side: "left"
                    }
                }
            ]
        });

        const crearBotonAyudaHome = () => {
            if (document.getElementById('btn-ayuda-home')) return;

            const boton = document.createElement('button');
            boton.id = 'btn-ayuda-home';
            boton.innerHTML = '❓';
            boton.title = 'Ver Tutorial';

            Object.assign(boton.style, {
                position: 'fixed',
                bottom: '30px',
                right: '30px',
                backgroundColor: '#16a34a',
                color: 'white',
                border: '3px solid white',
                borderRadius: '50%',
                width: '60px',
                height: '60px',
                fontSize: '28px',
                zIndex: '9999',
                cursor: 'pointer',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                boxShadow: '0 4px 15px rgba(22, 163, 74, 0.4)',
                transition: 'all 0.3s ease'
            });

            boton.onmouseover = () => {
                boton.style.transform = 'scale(1.1) rotate(10deg)';
                boton.style.backgroundColor = '#15803d';
            };
            boton.onmouseout = () => {
                boton.style.transform = 'scale(1) rotate(0deg)';
                boton.style.backgroundColor = '#16a34a';
            };

            boton.onclick = () => driverHome.drive();

            document.body.appendChild(boton);
        };

        crearBotonAyudaHome();

        if (!localStorage.getItem('tutorial_welcome_completo')) {
            setTimeout(() => {
                driverHome.drive();
                localStorage.setItem('tutorial_welcome_completo', 'true');
            }, 1500);
        }
    }
});