<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class PageController extends Controller
{
    public function index()
    {
        // 🔹 Usamos paginate() en lugar de get()
        $questions = Question::with(['category', 'user'])
            ->latest() // ordena de la más reciente a la más antigua
            ->paginate(10); // 👈 muestra 10 por página

        return view('pages.home', [
            'questions' => $questions,
        ]);
    }
}

