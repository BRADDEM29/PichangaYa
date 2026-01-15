{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\mapa\general.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            {{-- Icono Mapa SVG (Reemplaza al emoji 📍) --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-indigo-600">
                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
            </svg>
            Mapa de Canchas Disponibles
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg relative">
                
                {{-- Contenedor del Mapa --}}
                <div id="map" class="w-full h-[600px] rounded-lg z-0"></div>

            </div>
        </div>
    </div>

    {{-- Script de Google Maps --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMap" async defer></script>

    <script>
        function initMap() {
            // 1. Configuración inicial
            const cusco = { lat: -13.5319, lng: -71.9675 };
            const canchas = @json($canchas); 

            // Verificar en consola si llegan los datos
            console.log("Canchas cargadas:", canchas);

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: canchas.length > 0 ? { lat: canchas[0].lat, lng: canchas[0].lng } : cusco,
                styles: [ 
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });

            // 2. CONFIGURACIÓN DEL PIN PERSONALIZADO
            // Asegúrate de que tu archivo esté en: public/images/pin.svg
            const pinUrl = "{{ asset('images/pin.svg') }}";
            console.log("Buscando pin en:", pinUrl); // Mira la consola para ver si la ruta es correcta

            const customIcon = {
                url: pinUrl, 
                scaledSize: new google.maps.Size(40, 40), // Ajusta el tamaño aquí si tu SVG es muy grande
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(20, 40) // Punta del pin (Centro, Abajo)
            };

            const infoWindow = new google.maps.InfoWindow();

            // 3. Crear Marcadores
            canchas.forEach((cancha) => {
                // Validación extra para evitar errores si falta lat/lng
                if(!cancha.lat || !cancha.lng) return;

                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(cancha.lat), lng: parseFloat(cancha.lng) },
                    map: map,
                    title: cancha.name,
                    icon: customIcon, // Aquí aplicamos tu SVG
                    animation: google.maps.Animation.DROP
                });

                // 4. Contenido del Popup (Sin Emojis, con SVG incrustado)
                // Usamos un SVG pequeño de ubicación gris dentro del string HTML
                const locationIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 inline-block mr-1"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg>`;

                const contentString = `
                    <div class="p-2 max-w-xs text-center">
                        <img src="${cancha.image}" class="w-full h-24 object-cover rounded-md mb-2 shadow-sm" alt="${cancha.name}">
                        <h3 class="font-bold text-md text-gray-800 leading-tight">${cancha.name}</h3>
                        <p class="text-sm text-gray-500 mb-2 flex items-center justify-center mt-1">
                            ${locationIcon} ${cancha.district}
                        </p>
                        <p class="text-green-600 font-bold text-lg mb-3">S/ ${cancha.price} <span class="text-xs text-gray-400 font-normal">/hora</span></p>
                        <a href="${cancha.url}" class="block w-full bg-indigo-600 text-white text-xs font-bold py-2 px-4 rounded shadow hover:bg-indigo-700 transition uppercase tracking-wide">
                            Ver Detalles
                        </a>
                    </div>
                `;

                // Evento Click en el marcador
                marker.addListener("click", () => {
                    infoWindow.setContent(contentString);
                    infoWindow.open({
                        anchor: marker,
                        map,
                        shouldFocus: false,
                    });
                });
            });
        }
    </script>
</x-app-layout>