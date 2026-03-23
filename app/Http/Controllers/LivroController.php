<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $livros = Livro::when($search, function ($query) use ($search) {
            return $query->where('titulo', 'LIKE', "%{$search}%")
                ->orWhere('autor', 'LIKE', "%{$search}%");
        })
            ->with('genero')
            ->paginate(8)
            ->withQueryString();

        return view('home', compact('livros'));
    }

    public function show(Livro $livro)
    {
        $livro->load(['genero', 'avaliacoes.user']);

        $minhaAvaliacao = auth()->check()
            ? $livro->avaliacoes->firstWhere('user_id', auth()->id())
            : null;

        return view('livros.show', compact('livro', 'minhaAvaliacao'));
    }

    public function create()
    {
        $generos = Genero::all();

        return view('admin.livros.create', compact('generos'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo'          => 'required|string|max:255',
            'autor'           => 'required|string|max:255',
            'descricao'       => 'required|string',
            'estoque'         => 'required|integer|min:0',
            'genero_id'       => 'required|exists:generos,id',
            'capa'            => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'data_publicacao' => 'nullable|date',
        ], [
            'capa.max'        => 'A imagem da capa é muito pesada! O limite máximo é 2MB.',
            'capa.image'      => 'O arquivo enviado deve ser uma imagem válida.',
            'capa.required'   => 'Você precisa enviar uma imagem de capa.',
            'titulo.required' => 'O título é obrigatório.',
        ]);

        if ($request->hasFile('capa')) {
            $dados['capa'] = $request->file('capa')->store('capas', 'public');
        }

        Livro::create($dados);

        return redirect()->route('admin.dashboard')->with('success', 'Livro cadastrado com sucesso!');
    }

    public function edit(Livro $livro)
    {
        $generos = Genero::all();

        return view('admin.livros.edit', compact('livro', 'generos'));
    }

    public function update(Request $request, Livro $livro)
    {
        $dados = $request->validate([
            'titulo'          => 'required|string|max:255',
            'autor'           => 'required|string|max:255',
            'estoque'         => 'required|integer|min:0',
            'descricao'       => 'required|string',
            'genero_id'       => 'required|exists:generos,id',
            'data_publicacao' => 'nullable|date',
            'capa'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('capa')) {
            if ($livro->capa && Storage::disk('public')->exists($livro->capa)) {
                Storage::disk('public')->delete($livro->capa);
            }
            $dados['capa'] = $request->file('capa')->store('capas', 'public');
        }

        $livro->update($dados);

        return redirect()->route('livros.show', $livro->id)->with('success', 'Livro atualizado!');
    }

    public function destroy(Livro $livro)
    {
        if ($livro->capa && Storage::disk('public')->exists($livro->capa)) {
            Storage::disk('public')->delete($livro->capa);
        }

        $livro->delete();

        return redirect()->route('home')->with('success', 'Livro removido com sucesso!');
    }
}