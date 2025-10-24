<nav class="flex items-center justify-between h-16">

    {{-- Logo --}}
    <div>
        <a href="{{ route('home') }}">
            <x-forum.logo />
        </a>
    </div>

    {{-- Enlaces principales --}}
    <div class="flex gap-4">
        <a href="{{ route('home') }}" class="text-sm font-semibold hover:underline">Foro</a>
        <a href="#" class="text-sm font-semibold hover:underline">Blog</a>

        @auth
            {{-- Mostrar botón "Preguntar" solo si está logueado --}}
            <a href="{{ route('question.create') }}" 
               class="text-sm font-semibold text-white bg-blue-600 px-3 py-1 rounded hover:bg-blue-500 transition">
               Preguntar
            </a>
        @endauth
    </div>

    {{-- Autenticación --}}
    <div class="flex items-center gap-3">
        @auth
            {{-- Nombre del usuario logueado --}}
            <span class="text-sm text-gray-200">Hola, {{ auth()->user()->name }}</span>

            {{-- Cerrar sesión --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-red-400 hover:text-red-300">
                    Cerrar sesión &rarr;
                </button>
            </form>
        @else
            {{-- Invitado: opciones de login y registro --}}
            <a href="{{ route('login') }}" class="text-sm font-semibold hover:underline">Login &rarr;</a>
            <a href="{{ route('register') }}" class="text-sm font-semibold hover:underline">Register</a>
        @endauth
    </div>

</nav>
