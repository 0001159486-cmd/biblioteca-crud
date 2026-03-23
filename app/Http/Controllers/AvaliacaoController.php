<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController extends Controller
{
    public function store(Request $request, Livro $livro)
    {
        $request->validate([
            'nota'       => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ], [
            'nota.required' => 'Selecione uma nota de 1 a 5.',
            'nota.min'      => 'A nota mínima é 1.',
            'nota.max'      => 'A nota máxima é 5.',
        ]);

        // Verifica se já avaliou
        $jaAvaliou = Avaliacao::where('user_id', Auth::id())
            ->where('livro_id', $livro->id)
            ->exists();

        if ($jaAvaliou) {
            return back()->with('error', 'Você já avaliou este livro.');
        }

        Avaliacao::create([
            'user_id'    => Auth::id(),
            'livro_id'   => $livro->id,
            'nota'       => $request->nota,
            'comentario' => $request->comentario,
        ]);

        return back()->with('success', 'Avaliação enviada com sucesso!');
    }

    public function destroy(Livro $livro)
    {
        Avaliacao::where('user_id', Auth::id())
            ->where('livro_id', $livro->id)
            ->delete();

        return back()->with('success', 'Avaliação removida.');
    }
}