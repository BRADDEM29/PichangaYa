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

    // VERIFICACIÓN ESTRICTA DE RUTA: Solo funciona en el inicio
    if (window.location.pathname === '/' || window.location.pathname === '') {

        const driverHome = driver({
            showProgress: true,
            animate: true,
            allowClose: true,
            nextBtnText: 'Siguiente ➡',
            prevBtnText: '⬅ Atrás',
            doneBtnText: '¡Entendido!',
            steps: [
                // 1. Bienvenida
                {
                    element: '#hero-title',
                    popover: {
                        title: '👋 Bienvenido a PichangaYa',
                        description: 'La plataforma #1 en Cusco para reservar tus partidos al instante.',
                        side: "bottom"
                    }
                },
                // 2. Buscador
                {
                    element: '#search-form',
                    popover: {
                        title: '🔍 El Buscador Inteligente',
                        description: 'Aquí empieza todo. Escribe el nombre de la cancha, o filtra por Distrito (ej. Wanchaq) y Deporte.',
                        side: "bottom"
                    }
                },
                // 3. Notificaciones (Nuevo paso agregado)
                // Se agrega condicionalmente solo si el elemento existe (usuario logueado)
                ...(document.getElementById('tour-notificaciones') ? [{
                    element: '#tour-notificaciones',
                    popover: {
                        title: '🔔 Centro de Notificaciones',
                        description: '¡Atento aquí! Recibirás avisos sobre tus reservas confirmadas, pagos pendientes o cancelaciones.',
                        side: "bottom",
                        align: "end"
                    }
                }] : []),
                // 4. Carrusel
                ...(document.getElementById('featured-section') ? [{
                    element: '#featured-section',
                    popover: {
                        title: '⭐ Canchas Destacadas',
                        description: 'Aquí mostramos las canchas mejor valoradas y más populares del momento.',
                        side: "top"
                    }
                }] : []),
                // 5. Grid de Canchas
                {
                    element: '#canchas-grid',
                    popover: {
                        title: '📋 Listado de Canchas',
                        description: 'Aquí verás todas las canchas disponibles según tu búsqueda.',
                        side: "top"
                    }
                },
                // 6. Tarjeta Individual
                {
                    element: 'article:first-of-type',
                    popover: {
                        title: 'ℹ️ Información de la Cancha',
                        description: 'Cada tarjeta te muestra: Foto, Precio por hora, Ubicación y Servicios disponibles.',
                        side: "right"
                    }
                },
                // 7. Botón Reservar
                {
                    element: 'article:first-of-type a',
                    popover: {
                        title: '📅 Ver Disponibilidad',
                        description: 'Dale clic a este botón verde para ver los horarios libres y reservar tu partido.',
                        side: "top"
                    }
                },
                // 8. Nosotros
                {
                    element: '#about-section',
                    popover: {
                        title: '🤝 Nosotros',
                        description: 'Conoce más sobre nuestra misión y por qué somos la opción más segura en Cusco.',
                        side: "top"
                    }
                },
                // 9. Footer
                {
                    element: 'footer',
                    popover: {
                        title: '📞 Ayuda y Soporte',
                        description: '¿Tienes dudas? Aquí abajo encuentras nuestro WhatsApp, Correo y términos legales.',
                        side: "top"
                    }
                }
            ]
        });

        // --- CREACIÓN DEL BOTÓN DE AYUDA FLOTANTE ---
        const crearBotonAyudaHome = () => {
            if (document.getElementById('btn-ayuda-home')) return;

            const boton = document.createElement('button');
            boton.id = 'btn-ayuda-home';
            boton.innerHTML = '❔';
            boton.title = 'Ver Tutorial';

            // Estilos del botón
            Object.assign(boton.style, {
                position: 'fixed',
                bottom: '30px',
                right: '30px',
                backgroundColor: '#16a34a', // Verde corporativo
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
                boxShadow: '0 4px 15px rgba(22, 163, 74, 0.4)', // Sombra verde
                transition: 'all 0.3s ease'
            });

            // Hover
            boton.onmouseover = () => {
                boton.style.transform = 'scale(1.1) rotate(10deg)';
                boton.style.backgroundColor = '#15803d'; // Verde más oscuro
            };
            boton.onmouseout = () => {
                boton.style.transform = 'scale(1) rotate(0deg)';
                boton.style.backgroundColor = '#16a34a';
            };

            // Click
            boton.onclick = () => driverHome.drive();

            document.body.appendChild(boton);
        };

        // Ejecutar creación
        crearBotonAyudaHome();

        // Auto-inicio (Opcional: solo si no lo ha visto)
        if (!localStorage.getItem('tutorial_welcome_completo')) {
            setTimeout(() => {
                driverHome.drive();
                localStorage.setItem('tutorial_welcome_completo', 'true');
            }, 1500);
        }
    }
});