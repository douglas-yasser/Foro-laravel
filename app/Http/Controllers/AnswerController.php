<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnswerController extends Controller implements HasMiddleware
{
    // Nuevo método estático para middleware en Laravel 12
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['store']),
        ];
    }
 
    public function store(Request $request, Question $question)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1900',
        ]);
 
        // Crear la respuesta asociada al usuario logueado
        $answer = $question->answers()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);
 
        return redirect()
            ->route('question.show', $question)
            ->with('success', '¡Respuesta publicada exitosamente!');
    }

    public function destroy($id)
    {
        $answer = \App\Models\Answer::findOrFail($id);
        
        // Verificar que el usuario sea el dueño de la respuesta
        if ($answer->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar esta respuesta.');
        }

        $questionId = $answer->question_id;
        $answer->delete();

        return redirect()
            ->route('question.show', $questionId)
            ->with('success', '¡Respuesta eliminada exitosamente!');
    }
}



