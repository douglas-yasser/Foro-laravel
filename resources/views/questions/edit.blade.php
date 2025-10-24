<x-forum.layouts.app>
    <div class="max-w-3xl mx-auto mt-8 bg-gray-900 p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-white">Editar pregunta</h1>

        <form action="{{ route('question.update', $question) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Título --}}
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Título</label>
                <input 
                    type="text" 
                    name="title" 
                    value="{{ old('title', $question->title) }}"
                    class="w-full border border-gray-700 bg-gray-800 text-white rounded-md p-2 focus:ring-2 focus:ring-blue-500"
                    required
                >
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Categoría --}}
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Categoría</label>
                <select 
                    name="category_id" 
                    class="w-full border border-gray-700 bg-gray-800 text-white rounded-md p-2 focus:ring-2 focus:ring-blue-500"
                    required
                >
                    <option value="">-- Selecciona una categoría --</option>
                    @foreach($categories as $category)
                        <option 
                            value="{{ $category->id }}" 
                            {{ old('category_id', $question->category_id) == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Contenido --}}
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Contenido</label>
                <textarea 
                    name="content" 
                    rows="6"
                    class="w-full border border-gray-700 bg-gray-800 text-white rounded-md p-2 focus:ring-2 focus:ring-blue-500"
                    required
                >{{ old('content', $question->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('question.show', $question) }}" class="text-gray-400 hover:text-gray-200 text-sm">
                    ← Volver
                </a>
                <button 
                    type="submit" 
                    class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-md font-semibold transition"
                >
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</x-forum.layouts.app>
