//C:\laragon\www\PichangaYa\pichangaya\resources\js\app.js
import { driver } from "driver.js";
import "driver.js/dist/driver.css";
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {

    // ============================================================
    // 1. LÓGICA DE MODO OSCURO (SEGÚN ROL)
    // ============================================================
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    const themeToggleBtn = document.getElementById('theme-toggle');

    // Verificamos si el modo oscuro está prohibido (clase 'light' añadida en app.blade.php)
    const isDarkModeForbidden = document.documentElement.classList.contains('light');

    if (isDarkModeForbidden) {
        if (themeToggleBtn) themeToggleBtn.style.display = 'none';
        document.documentElement.classList.remove('dark');
    } else if (themeToggleBtn) {
        // Solo si el usuario tiene permitido el modo oscuro
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
    // 2. SEGURIDAD Y TUTORIAL (TU CÓDIGO ORIGINAL)
    // ============================================================
    if (window.usuarioLogueado !== true || !window.usuarioId) {
        return;
    }

    const path = window.location.pathname;
    const storageKeyGeneral = `tutorial_dashboard_user_${window.usuarioId}`;
    const storageKeyDetalles = `tutorial_detalles_user_${window.usuarioId}`;

    // --- ESCENARIO A: DASHBOARD ---
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
                }
                // ... puedes seguir pegando el resto de tus pasos aquí ...
            ]
        });

        if (!yaVioGeneral) {
            driverGeneral.drive();
            localStorage.setItem(storageKeyGeneral, 'true');
        }
    }

    // --- BOTÓN DE REPLAY (TU CÓDIGO ORIGINAL) ---
    const crearBotonAyuda = () => {
        if (document.getElementById('btn-replay-tutorial')) return;
        const boton = document.createElement('button');
        boton.id = 'btn-replay-tutorial';
        boton.innerHTML = '❔';
        boton.title = 'Ver Guía de Ayuda';

        Object.assign(boton.style, {
            position: 'fixed',
            bottom: '15px',
            right: '15px',
            backgroundColor: '#4f46e5',
            color: 'white',
            border: 'none',
            borderRadius: '50%',
            width: '25px',
            height: '25px',
            fontSize: '12px',
            zIndex: '9999',
            cursor: 'pointer',
            opacity: '0.7'
        });

        boton.onclick = () => {
            localStorage.removeItem(storageKeyGeneral);
            localStorage.removeItem(storageKeyDetalles);
            window.location.reload();
        };
        document.body.appendChild(boton);
    };

    crearBotonAyuda();
});