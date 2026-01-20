<x-app-layout>
    {{-- CSS de la Librería (CDN) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.css" />

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-700">
                
                <h2 class="text-3xl font-bold text-white text-center mb-6 uppercase tracking-widest">{{ $tournament->name }}</h2>
                
                {{-- Contenedor donde la librería dibujará el gráfico --}}
                <div id="bracket-gfx" class="flex justify-center overflow-x-auto py-4"></div>

            </div>
        </div>
    </div>

    {{-- SCRIPTS (jQuery + Library) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.js"></script>

    <script>
        $(function() {
            // Aquí recibimos los datos que envía el controlador nuevo
            var data = @json($bracketData);

            $('#bracket-gfx').bracket({
                init: data,
                skipConsolationRound: true, // No mostrar 3er puesto
                teamWidth: 140,
                scoreWidth: 30,
                matchMargin: 50,
                roundMargin: 60,
                decorator: {
                    edit: function() {}, 
                    render: function(container, data, score, state) {
                        switch(state) {
                            case "empty-bye":
                                container.append("BYE") // Texto para los pases directos
                                return;
                            case "empty-tbd":
                                container.append("Esperando...")
                                return;
                            case "entry-no-score":
                            case "entry-default-win":
                            case "entry-complete":
                                container.append(data)
                                return;
                        }
                    }
                }
            });
        });
    </script>
    
    <style>
        /* Estilos oscuros para que combine con tu tema */
        .jQBracket .team {
            background-color: #1f2937 !important; /* Gris oscuro */
            color: #f3f4f6 !important; /* Texto claro */
            border: 1px solid #374151 !important;
            font-weight: bold;
        }
        .jQBracket .team.winner {
            background-color: #065f46 !important; /* Verde oscuro */
            color: #fff !important;
            border-color: #10b981 !important;
        }
        .jQBracket .score {
            background-color: #111827 !important;
            color: #fbbf24 !important; /* Amarillo */
        }
        .jQBracket .connector {
            border-color: #6b7280 !important; /* Líneas grises */
        }
        .jQBracket .bubble {
            background-color: #374151 !important;
            color: white !important;
        }
    </style>
</x-app-layout>