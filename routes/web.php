<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;

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
    Route::delete('/emprestimos/{emprestimo}/concluir', [AdminController::class, 'concluirEmprestimo'])->name('admin.emprestimos.concluir');
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
});

Route::get('/livros/{livro}', [LivroController::class, 'show'])->name('livros.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [LivroController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/livros/{livro}/alugar', [EmprestimoController::class, 'store'])->name('livros.alugar');
    Route::get('/sucess', function () {
        return view('livros.sucess');
    })->name('livros.sucess');
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');
});
