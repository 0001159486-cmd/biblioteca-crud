<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Livro;
use Illuminate\Support\Facades\DB;

class EmprestimoController extends Controller
{
    public function store(Livro $livro)
{
    if ($livro->estoque <= 0) {
        return back()->with('error', 'Este livro está esgotado no momento.');
    }

    $userId = auth()->id();

    $pendente = Emprestimo::where('user_id', $userId)->exists();

if ($pendente) {
    return back()->with('error', 'Você já possui um empréstimo ativo.');
}

    $prazoEntrega = now()->addDays(7);

    DB::transaction(function () use ($livro, $userId, $prazoEntrega) {
        Emprestimo::create([
            'user_id' => $userId,
            'livro_id' => $livro->id,
            'data_saida' => now(),
            'data_devolucao' => $prazoEntrega,
        ]);

        $livro->decrement('estoque');
    });

    return redirect()->route('home')->with([
        'success' => 'Empréstimo realizado!',
        'data_entrega' => $prazoEntrega->format('d/m/Y')
    ]);
}

public function concluir($id)
{
    $emprestimo = Emprestimo::findOrFail($id);

    DB::transaction(function () use ($emprestimo) {
        $emprestimo->livro->increment('estoque');
        $emprestimo->delete();
    });

    return back()->with('success', 'Livro devolvido e estoque atualizado.');
}
}
