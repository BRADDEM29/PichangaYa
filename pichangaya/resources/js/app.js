import { driver } from "driver.js";
import "driver.js/dist/driver.css";
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {

    // 1. SEGURIDAD Y VALIDACIÓN DE USUARIO
    if (window.usuarioLogueado !== true || !window.usuarioId) {
        return; // Si es invitado, no hacemos nada
    }

    // Creamos una "llave" única para este usuario específico
    // Ej: tutorial_visto_user_5 (Así si entra el user 6, le saldrá de nuevo)
    const storageKey = `tutorial_visto_user_${window.usuarioId}`;
    const yaVioTutorial = localStorage.getItem(storageKey);

    // 2. CONFIGURACIÓN DEL TOUR (Sin PDF)
    const driverObj = driver({
        showProgress: true,
        animate: true,
        allowClose: false,
        nextBtnText: 'Siguiente ➡',
        prevBtnText: '⬅ Atrás',
        doneBtnText: '¡A Jugar!',
        steps: [
            {
                element: '#tour-logo',
                popover: { title: 'Panel de Control', description: 'Estás en tu zona de mando.' }
            },
            {
                element: '#tour-detalles',
                popover: { title: 'Analiza antes de elegir', description: 'Mira fotos y precios aquí.' }
            },
            {
                element: '#tour-reservas',
                popover: { title: '¡Reserva Ya!', description: 'Usa el calendario para asegurar tu partido.' }
            },
            {
                element: '#tour-mis-canchas',
                popover: { title: 'Tu Historial', description: 'Aquí verás tus reservaciones pasadas.' }
            }
        ],
        onDestroyed: () => {
            // Al terminar, marcamos como visto Y mostramos el botón pequeño
            localStorage.setItem(storageKey, 'true');
            mostrarBotonReplay();
        }
    });

    // 3. LÓGICA DE INICIO
    if (!yaVioTutorial) {
        // --- CASO A: PRIMERA VEZ QUE ENTRA ---
        Swal.fire({
            title: '¡Bienvenido!',
            html: '<span style="color: #4a5568;">¿Quieres una guía rápida de la plataforma?</span>',
            icon: 'info',
            background: 'rgba(255, 255, 255, 0.9)',
            backdrop: `rgba(0,0,0,0.5)`,
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#718096',
            confirmButtonText: 'Sí, guíame',
            cancelButtonText: 'No, gracias'
        }).then((result) => {
            if (result.isConfirmed) {
                driverObj.drive();
            } else {
                // Si dice que no, guardamos que ya decidió y mostramos el botón pequeño por si se arrepiente
                localStorage.setItem(storageKey, 'true');
                mostrarBotonReplay();
            }
        });
    } else {
        // --- CASO B: YA LO VIO ANTES ---
        // Directamente mostramos el botón pequeño en la esquina
        mostrarBotonReplay();
    }

    // 4. FUNCIÓN PARA CREAR EL BOTÓN PEQUEÑO
    function mostrarBotonReplay() {
        // Evitar crear el botón si ya existe
        if (document.getElementById('btn-replay-tutorial')) return;

        const boton = document.createElement('button');
        boton.id = 'btn-replay-tutorial';
        boton.innerHTML = '❔ Guía';

        // Estilos CSS directos para el botón (Pequeño, esquina inferior derecha)
        Object.assign(boton.style, {
            position: 'fixed',
            bottom: '20px',
            right: '20px',
            backgroundColor: '#4f46e5', // Color Indigo
            color: 'white',
            border: 'none',
            borderRadius: '20px',
            padding: '5px 12px',
            fontSize: '12px',
            fontWeight: 'bold',
            boxShadow: '0 2px 5px rgba(0,0,0,0.3)',
            cursor: 'pointer',
            zIndex: '9999',
            opacity: '0.8',
            transition: 'opacity 0.3s'
        });

        // Efecto hover
        boton.onmouseover = () => boton.style.opacity = '1';
        boton.onmouseout = () => boton.style.opacity = '0.8';

        // Acción al hacer clic: Reiniciar el tour
        boton.onclick = () => {
            driverObj.drive();
        };

        document.body.appendChild(boton);
    }
});