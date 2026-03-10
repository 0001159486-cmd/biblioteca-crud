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
            return back()->with('error', 'Sem estoque.');
        }

        $dataDevolucao = now()->addDays(7);

        DB::transaction(function () use ($livro, $dataDevolucao) {
            Emprestimo::create([
                'user_id' => auth()->id(),
                'livro_id' => $livro->id,
                'data_saida' => now(),
                'data_devolucao' => $dataDevolucao,
            ]);

            $livro->decrement('estoque');
        });

        return redirect()->route('livros.sucess')->with('data_entrega', $dataDevolucao->format('d/m/Y'));
    }

    protected $casts = [
        'data_aluguel' => 'datetime',
        'data_vencimento' => 'datetime',
        'data_devolucao' => 'datetime',
    ];
}
