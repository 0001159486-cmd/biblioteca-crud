<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/livros/{livro}/edit', [LivroController::class, 'edit'])->name('livros.edit');
    Route::put('/livros/{livro}', [LivroController::class, 'update'])->name('livros.update');
    Route::get('/livros/create', [LivroController::class, 'create'])->name('livros.create');
    Route::post('/livros', [LivroController::class, 'store'])->name('livros.store');
    Route::delete('/livros/{livro}', [LivroController::class, 'destroy'])->name('livros.destroy');
    Route::get('/usuarios', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/usuarios/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/usuarios/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/usuarios/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::delete('/admin/emprestimos/{id}/concluir', [EmprestimoController::class, 'concluir'])->name('admin.emprestimos.concluir');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/registrar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registrar', [AuthController::class, 'storeRegister']);
    Route::get('/confirmregister', function () {
        return view('auth.confirmregister');
    })->name('confirm.register');

    // Recuperação de senha
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link de recuperação enviado para seu e-mail!')
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Senha redefinida com sucesso!')
            : back()->withErrors(['email' => [__($status)]]);
    })->name('password.update');
});

Route::get('/livros/{livro}', [LivroController::class, 'show'])->name('livros.show');

Route::middleware(['auth'])->post('/gemini/chat', [GeminiController::class, 'perguntar'])->name('gemini.chat');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [LivroController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/gemini/chat', [GeminiController::class, 'perguntar'])->name('gemini.chat');
    Route::post('/livros/{livro}/alugar', [EmprestimoController::class, 'store'])->name('livros.alugar');
    Route::get('/sucess', function () {
        return view('livros.sucess');
    })->name('livros.sucess');

    // Perfil
    Route::get('/perfil', [AuthController::class, 'showPerfil'])->name('perfil');
    Route::put('/perfil', [AuthController::class, 'updatePerfil'])->name('perfil.update');

    // Avaliações
    Route::post('/livros/{livro}/avaliar', [AvaliacaoController::class, 'store'])->name('livros.avaliar');
    Route::delete('/livros/{livro}/avaliar', [AvaliacaoController::class, 'destroy'])->name('livros.avaliar.destroy');
});