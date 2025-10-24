<x-forum.layouts.app>
    <div class="max-w-3xl mx-auto mt-8 bg-gray-900 p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-white">Crear nueva pregunta</h1>

        <form action="{{ route('question.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Título --}}
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Título</label>
                <input 
                    type="text" 
                    name="title" 
                    value="{{ old('title') }}"
                    class="w-full border border-gray-700 bg-gray-800 text-white rounded-md p-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Escribe un título descriptivo..."
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
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
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
                    placeholder="Describe tu duda o tema con detalle..."
                    required
                >{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botón --}}
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md font-semibold transition"
                >
                    Publicar pregunta
                </button>
            </div>
        </form>
    </div>
</x-forum.layouts.app>


