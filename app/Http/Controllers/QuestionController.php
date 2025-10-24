<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Muestra la lista de preguntas (página principal del foro)
     */
    public function index()
    {
        $questions = Question::with(['user', 'category'])
            ->latest()
            ->paginate(20);

        return view('pages.home', compact('questions'));
    }

    /**
     * Muestra una pregunta en detalle
     */
    public function show(Question $question)
    {
        $question->load(['user', 'category', 'answers.user']);
        return view('questions.show', compact('question'));
    }

    /**
     * Formulario para crear una nueva pregunta
     */
    public function create()
    {
        $categories = Category::all();
        return view('questions.create', compact('categories'));
    }

    /**
     * Guarda una nueva pregunta en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1900',
            'category_id' => 'required|exists:categories,id',
        ]);

        Question::create([
            'title'       => $request->title,
            'content'     => $request->content,
            'category_id' => $request->category_id,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', 'Pregunta publicada correctamente.');
    }

    /**
     * Formulario para editar una pregunta existente
     */
    public function edit(Question $question)
    {
        // Verificar que el usuario actual sea el autor
        if ($question->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta pregunta.');
        }

        $categories = Category::all();
        return view('questions.edit', compact('question', 'categories'));
    }

    /**
     * Actualiza una pregunta existente
     */
    public function update(Request $request, Question $question)
    {
        if ($question->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para actualizar esta pregunta.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1900',
            'category_id' => 'required|exists:categories,id',
        ]);

        $question->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('question.show', $question)
            ->with('success', 'Pregunta actualizada correctamente.');
    }

    /**
     * Elimina una pregunta
     */
    public function destroy(Question $question)
    {
        if ($question->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta pregunta.');
        }

        $question->delete();

        return redirect()->route('home')
            ->with('success', 'Pregunta eliminada correctamente.');
    }
}






