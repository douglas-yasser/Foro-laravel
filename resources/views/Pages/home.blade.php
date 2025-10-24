<x-forum.layouts.app>
    <div class="max-w-5xl mx-auto mt-8">

        {{-- Encabezado y botón Preguntar --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Foro de Preguntas</h1>

            @auth
                <a href="{{ route('question.create') }}" 
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md font-semibold text-sm transition">
                    + Preguntar
                </a>
            @else
                <a href="{{ route('login') }}" 
                    class="text-sm font-semibold text-blue-400 hover:text-blue-300">
                    Inicia sesión para preguntar →
                </a>
            @endauth
        </div>

        {{-- Mensaje flash de éxito --}}
        @if (session('success'))
            <div class="bg-green-700 border border-green-600 text-white p-3 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Lista de preguntas --}}
        <div class="space-y-4">
            @forelse($questions as $question)
                <div class="bg-gray-900 p-5 rounded-lg shadow-md hover:shadow-lg transition">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('question.show', $question) }}" class="text-lg font-semibold text-blue-400 hover:text-blue-300">
                            {{ $question->title }}
                        </a>
                        <span class="text-xs text-gray-400 bg-gray-800 px-2 py-1 rounded">
                            {{ $question->category->name }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-300 mt-2 line-clamp-2">
                        {{ Str::limit($question->content, 180) }}
                    </p>

                    <div class="flex justify-between items-center mt-3 text-xs text-gray-500">
                        <div>
                            <span class="font-semibold text-gray-300">{{ $question->user->name }}</span>
                            • {{ $question->created_at->diffForHumans() }}
                        </div>

                        <a href="{{ route('question.show', $question) }}" 
                            class="text-blue-400 hover:underline">
                            Ver más →
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-gray-800 p-6 text-center rounded-lg text-gray-400">
                    No hay preguntas todavía.
                    @auth
                        <a href="{{ route('question.create') }}" class="text-blue-400 hover:underline">¡Haz la primera!</a>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-400 hover:underline">Inicia sesión para crear una.</a>
                    @endauth
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $questions->links() }}
        </div>

    </div>
</x-forum.layouts.app>


