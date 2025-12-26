<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO METADATA --}}
    <title>PichangaYa | Reserva Canchas de Fútbol, Vóley y más en Cusco</title>
    <meta name="description" content="Reserva canchas deportivas en Cusco al instante. Fútbol, Vóley, Básquet. Encuentra canchas con Grass Sintético, Techo, Wifi y Estacionamiento.">

    {{-- DARK MODE SCRIPT --}}
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-950 dark:text-gray-100">

    @include('navigation-menu')

    {{-- ZONA SUPERIOR: HERO + CARRUSEL + OLA AL FINAL --}}
    <div class="relative bg-gray-900 text-white overflow-hidden">
        
        {{-- 1. HERO & SEARCH --}}
        <div class="relative pb-16">
            <div class="absolute inset-0">
                <img src="{{ asset('images/balon2.webp') }}" class="w-full h-full object-cover opacity-40" alt="Fútbol Cusco">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
            </div>

            <header class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-8 text-center z-10">
                {{-- ID: hero-title (Para el tutorial) --}}
                <h1 id="hero-title" class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 drop-shadow-xl">
                    Encuentra tu cancha en <span class="text-green-400">Cusco</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto font-medium">
                    Fútbol, Vóley, Básquet y más. Reserva al instante.
                </p>

                {{-- ID: search-form (Para el tutorial) --}}
                <div id="search-form" class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-2xl max-w-4xl mx-auto text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-4">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre de la cancha..." 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-green-500 focus:border-green-500 placeholder-gray-400">
                        </div>
                        <div class="md:col-span-3">
                            <select name="district_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg">
                                <option value="">Todo Cusco</option>
                                @isset($districts)
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <select name="sport_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg">
                                <option value="">Todos los Deportes</option>
                                @isset($sports)
                                    @foreach($sports as $sport)
                                        <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                            {{ $sport->icon }} {{ $sport->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg transition duration-200 shadow-md">
                                🔍 Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </header>
        </div>

        {{-- 2. CARRUSEL DESTACADOS --}}
        @if(isset($featuredCanchas) && $featuredCanchas->isNotEmpty() && !request('search'))
            {{-- ID: featured-section (Para el tutorial) --}}
            <div id="featured-section" class="relative pb-28 z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-3xl">⭐</span>
                        <h3 class="text-2xl font-black text-white uppercase tracking-wide">Mejores Canchas</h3>
                    </div>
                    <x-carousel :items="$featuredCanchas" />
                </div>
            </div>
        @endif

        {{-- 🌊 LA OLA --}}
        <div class="absolute bottom-[-1px] left-0 right-0 w-full overflow-hidden leading-[0] rotate-180">
            <svg class="relative block w-[calc(100%+1.3px)] h-[70px] md:h-[120px]" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-gray-50 dark:fill-gray-950"></path>
            </svg>
        </div>
    </div>

    {{-- 3. LISTADO DE CANCHAS --}}
    {{-- ID: canchas-grid (Para el tutorial) --}}
    <section id="canchas-grid" class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 border-l-4 border-green-600 pl-4">
            Explora Todas las Canchas
        </h2>

        @if($canchas->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                <p class="text-gray-500 dark:text-gray-400">No encontramos canchas con esos filtros. 😢</p>
                <a href="{{ route('home') }}" class="text-green-600 hover:underline mt-4 inline-block font-bold">Ver todas</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($canchas as $cancha)
                    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full group">
                        
                        <div class="relative h-56 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" 
                                     alt="{{ $cancha->name }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="flex items-center justify-center h-full text-4xl">🏟️</div>
                            @endif
                            
                            <div class="absolute top-4 right-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-3 py-1 rounded-lg shadow-sm text-sm font-bold text-green-700">
                                S/ {{ number_format($cancha->price_per_hour, 2) }}
                            </div>
                        </div>

                        <div class="p-5 flex-grow flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $cancha->name }}</h3>
                            
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] font-bold px-2 py-1 rounded">
                                    📍 {{ $cancha->district->name ?? 'Cusco' }}
                                </span>
                                @foreach($cancha->sports as $sport)
                                    <span class="bg-green-600 text-white text-[10px] font-bold px-2 py-1 rounded">
                                        {{ $sport->icon }} {{ $sport->name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach ($cancha->services as $service)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/50">
                                        {{ $service->icon }} {{ $service->name }}
                                    </span>
                                @endforeach
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ $cancha->description }}
                            </p>
                            
                            <div class="mt-auto">
                                <a href="{{ route('canchas.show', $cancha) }}" 
                                   class="block w-full py-2.5 bg-green-600 text-white text-center rounded-lg font-bold hover:bg-green-700 transition">
                                    Ver Disponibilidad
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- 4. SECCIÓN ¿QUIÉNES SOMOS? --}}
    {{-- ID: about-section (Para el tutorial) --}}
    <section id="about-section" class="py-20 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <div class="relative">
                    <span class="text-green-600 font-bold uppercase tracking-widest text-sm mb-2 block">Conócenos</span>
                    <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-8">¿Quiénes somos?</h2>
                    <div class="space-y-6 text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                        <p>
                            Somos la plataforma #1 en Cusco diseñada para simplificar la vida de los deportistas. Nuestra misión es conectar la pasión por el deporte con la tecnología, ofreciendo una forma rápida y segura de reservar espacios deportivos.
                        </p>
                        <p>
                            Ya sea fútbol, vóley o básquet, trabajamos con los mejores complejos para garantizar que tu única preocupación sea dar lo mejor en la cancha.
                        </p>
                    </div>
                    <div class="mt-10 grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                            <span class="bg-green-100 text-green-600 p-1 rounded-full">✓</span> Reservas al instante
                        </div>
                        <div class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                            <span class="bg-green-100 text-green-600 p-1 rounded-full">✓</span> Pagos seguros
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-4 bg-green-500/10 rounded-3xl transform rotate-3 transition group-hover:rotate-0"></div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('images/balon2.webp') }}" alt="PichangaYa Cusco" class="w-full h-[400px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl hidden md:block">
                        <div class="text-3xl font-black text-green-600">100%</div>
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Cusqueño</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-300 pt-16 pb-8 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="space-y-4">
                    <h4 class="text-2xl font-black italic text-gray-900 dark:text-white">
                        ⚽ Pichanga<span class="text-green-500">Ya</span>
                    </h4>
                    <p class="text-sm opacity-80">Encuentra y reserva en las mejores canchas de Cusco.</p>
                    
                    <div class="flex items-center gap-4 pt-4">
                        <a href="https://wa.me/51940766968" target="_blank" class="text-gray-400 hover:text-green-500 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <a href="mailto:soporte@pichangaya.com" class="text-gray-400 hover:text-red-500 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 8.138h-18.745l5.479-8.133zm9.208-1.259l4.616-3.741v9.462l-4.616-5.721z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Explorar</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('home') }}" class="hover:text-green-500 transition">🔍 Buscar Canchas</a></li>
                        <li><a href="{{ route('register-pitch') }}" class="text-green-600 dark:text-green-400 font-bold hover:underline">🏟️ Registra tu Cancha</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-green-500 transition">¿Quiénes somos?</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Ayuda</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('faq') }}" class="hover:text-green-500 transition">Preguntas Frecuentes</a></li>
                        <li><a href="{{ route('contact.index') }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Atención Inmediata</a></li>
                        <li><a href="{{ route('suggestions.index') }}" class="hover:text-green-500 transition">Enviar Sugerencia</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Legal</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('terms.show') }}" class="hover:text-green-500 transition">Términos y condiciones</a></li>
                        <li><a href="{{ route('policy.show') }}" class="hover:text-green-500 transition">Política de privacidad</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-800 pt-8 text-center text-xs opacity-60">
                &copy; {{ date('Y') }} PichangaYa Cusco.
            </div>
        </div>
    </footer>

    {{-- Componente de Tarjeta Flotante --}}
    <x-urgent-booking-card />
</body>
</html>