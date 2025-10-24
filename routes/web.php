<?php   

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

// Página principal
Route::get('/', [PageController::class, 'index'])->name('home');

// Mostrar una pregunta (pública)
Route::get('/question/{question}', [QuestionController::class, 'show'])->name('question.show');

// Rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    // Preguntas
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('question.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('question.store');
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('question.edit');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    
    // Respuestas
    Route::post('/answers/{question}', [AnswerController::class, 'store'])->name('answers.store');
    
    // Dashboard
    // En routes/web.php
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');
    
    // Configuración de perfil (Livewire)
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Autenticación (login, registro, etc.)
require __DIR__.'/auth.php';