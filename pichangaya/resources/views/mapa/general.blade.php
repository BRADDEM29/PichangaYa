{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\mapa\general.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <hgroup class="flex items-center gap-3 transition-colors duration-300">
            {{-- Icono Mapa SVG --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-indigo-600 dark:text-indigo-400" aria-hidden="true">
                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
            </svg>
            <h1 class="font-extrabold text-xl md:text-2xl text-gray-800 dark:text-white leading-tight tracking-tight">
                Mapa de Canchas
            </h1>
        </hgroup>
    </x-slot>

    {{-- MAIN: Contenido Principal --}}
    {{-- Agregamos pb-10 para asegurar espacio final para el scroll --}}
    <main class="py-4 sm:py-6 lg:py-8 pb-12 bg-gray-50 dark:bg-gray-900">
        <section class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            
            {{-- ARTICLE: Tarjeta contenedora --}}
            {{-- Agregamos mb-8 para empujar el footer hacia abajo visualmente --}}
            <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl rounded-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 transition-colors duration-300 mb-8">
                <section class="p-2 sm:p-4 md:p-6">
                    
                    {{-- FIGURE: Semántica para el Mapa --}}
                    <figure aria-label="Mapa interactivo de ubicación de canchas" class="relative w-full">
                        
                        {{-- MAPA: Altura ajustada a 55vh en móvil para que no sea tan invasiva --}}
                        <section id="map" class="w-full h-[55vh] sm:h-[65vh] md:h-[75vh] rounded-lg sm:rounded-xl shadow-inner z-0 border border-gray-200 dark:border-gray-600 block"></section>
                        
                        <figcaption class="sr-only">Mapa mostrando marcadores de todas las canchas registradas en el sistema.</figcaption>
                    </figure>

                </section>
            </article>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="relative z-50 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 w-full block">
        <x-footer />
    </footer>

    {{-- Script de Google Maps con la API KEY inyectada --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>

    <script>
        // Función asíncrona para cargar el mapa y procesar el SVG
        async function initMap() {
            const cusco = { lat: -13.5319, lng: -71.9675 };
            const canchas = @json($canchas ?? []); 
            const isDarkMode = document.documentElement.classList.contains('dark');

            // 1. ESTILOS DEL MAPA
            const darkMapStyle = [
                { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
                { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
                { featureType: "administrative.locality", elementType: "labels.text.fill", stylers: [{ color: "#d59563" }] },
                { featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] },
                { featureType: "poi", elementType: "labels.text.fill", stylers: [{ color: "#d59563" }] },
                { featureType: "poi.park", elementType: "geometry", stylers: [{ color: "#263c3f" }] },
                { featureType: "poi.park", elementType: "labels.text.fill", stylers: [{ color: "#6b9a76" }] },
                { featureType: "road", elementType: "geometry", stylers: [{ color: "#38414e" }] },
                { featureType: "road", elementType: "geometry.stroke", stylers: [{ color: "#212a37" }] },
                { featureType: "road", elementType: "labels.text.fill", stylers: [{ color: "#9ca5b3" }] },
                { featureType: "road.highway", elementType: "geometry", stylers: [{ color: "#746855" }] },
                { featureType: "road.highway", elementType: "geometry.stroke", stylers: [{ color: "#1f2835" }] },
                { featureType: "road.highway", elementType: "labels.text.fill", stylers: [{ color: "#f3d19c" }] },
                { featureType: "water", elementType: "geometry", stylers: [{ color: "#17263c" }] },
                { featureType: "water", elementType: "labels.text.fill", stylers: [{ color: "#515c6d" }] },
                { featureType: "water", elementType: "labels.text.stroke", stylers: [{ color: "#17263c" }] }
            ];

            const lightMapStyle = [
                { featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] }
            ];

            // 2. INICIALIZAR EL MAPA
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: canchas.length > 0 ? { lat: parseFloat(canchas[0].lat), lng: parseFloat(canchas[0].lng) } : cusco,
                styles: isDarkMode ? darkMapStyle : lightMapStyle,
                mapTypeControl: false,
                streetViewControl: true,
                fullscreenControl: true,
                gestureHandling: 'cooperative', 
            });

            // 3. PROCESAMIENTO DEL PIN SVG (Blanco en Dark Mode)
            const pinUrl = "{{ asset('images/pin.svg') }}";
            let finalIconUrl = pinUrl;

            if (isDarkMode) {
                try {
                    const response = await fetch(pinUrl);
                    let svgText = await response.text();

                    if (svgText.includes('<svg')) {
                        const styleInjection = ` style="filter: brightness(0) invert(1);" `;
                        svgText = svgText.replace('<svg', '<svg' + styleInjection);
                        finalIconUrl = 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgText);
                    }
                } catch (error) {
                    console.error("Error transformando el PIN:", error);
                }
            }

            const customIcon = {
                url: finalIconUrl, 
                scaledSize: new google.maps.Size(42, 42),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(21, 42)
            };

            const infoWindow = new google.maps.InfoWindow();

            // 4. CREAR MARCADORES
            if (Array.isArray(canchas)) {
                canchas.forEach((cancha) => {
                    if(!cancha.lat || !cancha.lng) return;

                    const marker = new google.maps.Marker({
                        position: { lat: parseFloat(cancha.lat), lng: parseFloat(cancha.lng) },
                        map: map,
                        title: cancha.name,
                        icon: customIcon,
                        animation: google.maps.Animation.DROP
                    });

                    // Icono de ubicación (SVG Inline)
                    const locationIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 inline-block mr-1 text-gray-400"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg>`;

                    // HTML SEMÁNTICO dentro del Popup (Cero Divs)
                    const contentString = `
                        <article class="p-1 max-w-[240px] text-center font-sans">
                            <figure class="relative w-full h-28 mb-2 overflow-hidden rounded-lg shadow-md group">
                                <img src="${cancha.image}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="${cancha.name}">
                                
                                <span class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent block" aria-hidden="true"></span>
                                
                                <figcaption class="absolute bottom-1 left-2 text-white text-xs font-bold text-left drop-shadow-md">
                                    ${cancha.name}
                                </figcaption>
                            </figure>
                            
                            <p class="text-xs text-gray-500 mb-2 flex items-center justify-center">
                                ${locationIcon} ${cancha.district}
                            </p>
                            
                            <section class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2 mb-2 border border-gray-100">
                                 <span class="text-xs text-gray-400 font-bold uppercase">Tarifa</span>
                                 <data value="${cancha.price}" class="text-indigo-600 font-extrabold text-sm">S/ ${cancha.price}</data>
                            </section>

                            <footer class="mt-2">
                                <a href="${cancha.url}" class="block w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white text-[11px] font-bold py-2 px-4 rounded-md transition-all shadow-md transform hover:-translate-y-0.5 uppercase tracking-wider">
                                    Reservar Ahora
                                </a>
                            </footer>
                        </article>
                    `;

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
        }
    </script>
</x-app-layout>