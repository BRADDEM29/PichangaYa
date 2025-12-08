import { driver } from "driver.js";
import "driver.js/dist/driver.css";
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {

    // 1. SEGURIDAD
    if (window.usuarioLogueado !== true || !window.usuarioId) {
        return;
    }

    const path = window.location.pathname;
    const storageKeyGeneral = `tutorial_dashboard_user_${window.usuarioId}`;
    const storageKeyDetalles = `tutorial_detalles_user_${window.usuarioId}`;

    // ============================================================
    // ESCENARIO A: DASHBOARD
    // ============================================================
    if (path === '/dashboard' || path === '/' || path === '/home') {

        const yaVioGeneral = localStorage.getItem(storageKeyGeneral);

        const driverGeneral = driver({
            showProgress: true,
            animate: true,
            allowClose: false,
            nextBtnText: 'Siguiente ➡',
            prevBtnText: '⬅ Atrás',
            doneBtnText: 'Entendido',
            steps: [
                {
                    element: '#tour-logo',
                    popover: { title: 'Panel Principal', description: 'Aquí inicia todo.' }
                },
                {
                    element: '#tour-mis-reservas',
                    popover: { title: 'Tu Historial', description: 'En este menú verás todas tus reservaciones pasadas.' }
                },
                {
                    element: '#tour-detalles',
                    popover: {
                        title: 'El Siguiente Paso',
                        description: 'Para reservar, haz clic en "Ver Detalles" en cualquier cancha. ¡Inténtalo!'
                    }
                }
            ],
            onDestroyed: () => {
                localStorage.setItem(storageKeyGeneral, 'true');
                mostrarBotonReplay(() => driverGeneral.drive());
            }
        });

        if (!yaVioGeneral) {
            Swal.fire({
                title: '¡Bienvenido!',
                text: "¿Quieres un tour rápido?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    driverGeneral.drive();
                } else {
                    localStorage.setItem(storageKeyGeneral, 'true');
                    mostrarBotonReplay(() => driverGeneral.drive());
                }
            });
        } else {
            mostrarBotonReplay(() => driverGeneral.drive());
        }
    }

    // ============================================================
    // ESCENARIO B: DETALLES
    // ============================================================
    if (path.includes('/canchas/')) {

        const yaVioDetalles = localStorage.getItem(storageKeyDetalles);

        const driverDetalles = driver({
            showProgress: true,
            animate: true,
            doneBtnText: 'Finalizar',
            steps: [
                {
                    element: '#tour-calendario',
                    popover: {
                        title: '¡Reserva Aquí!',
                        description: 'Selecciona la fecha y hora en este calendario para asegurar tu partido.'
                    }
                },
                {
                    element: 'body',
                    popover: {
                        title: '¿Dudas?',
                        description: 'Si necesitas ayuda extra, contáctanos por WhatsApp.',
                        align: 'center'
                    }
                }
            ],
            onDestroyed: () => {
                localStorage.setItem(storageKeyDetalles, 'true');
                mostrarBotonReplay(() => driverDetalles.drive());
            }
        });

        if (!yaVioDetalles) {
            driverDetalles.drive();
        } else {
            mostrarBotonReplay(() => driverDetalles.drive());
        }
    }

    // ============================================================
    // BOTÓN REPLAY (VERSIÓN MINI)
    // ============================================================
    function mostrarBotonReplay(onClickFunction) {
        let boton = document.getElementById('btn-replay-tutorial');

        if (!boton) {
            boton = document.createElement('button');
            boton.id = 'btn-replay-tutorial';
            boton.innerHTML = '❔'; // Solo el icono para que sea muy pequeño
            boton.title = 'Ver Guía de Ayuda'; // Tooltip al pasar el mouse

            Object.assign(boton.style, {
                position: 'fixed',
                bottom: '15px',      // Más pegado abajo
                right: '15px',       // Más pegado a la derecha
                backgroundColor: '#4f46e5',
                color: 'white',
                border: 'none',
                borderRadius: '50%', // Redondo perfecto
                width: '25px',       // Ancho fijo pequeño
                height: '25px',      // Alto fijo pequeño
                fontSize: '12px',    // Letra pequeña
                lineHeight: '25px',  // Centrado vertical
                textAlign: 'center', // Centrado horizontal
                zIndex: '9999',
                cursor: 'pointer',
                boxShadow: '0 2px 4px rgba(0,0,0,0.2)',
                opacity: '0.7',      // Un poco transparente
                transition: 'all 0.3s'
            });

            // Efecto Hover (se vuelve opaco y crece un pelín)
            boton.onmouseover = () => { boton.style.opacity = '1'; boton.style.transform = 'scale(1.1)'; };
            boton.onmouseout = () => { boton.style.opacity = '0.7'; boton.style.transform = 'scale(1)'; };

            document.body.appendChild(boton);
        }

        boton.onclick = onClickFunction;
    }
});