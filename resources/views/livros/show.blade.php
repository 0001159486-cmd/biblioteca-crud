@extends('layouts.app')

@section('content')
<div class="livro-container">
    <div class="col-esquerda">
        <div class="capa-box">
            <img src="{{ Storage::url($livro->capa) }}" alt="{{ $livro->titulo }}">
        </div>

        <div class="acao-box">
            @if($livro->estoque > 0)
                <form action="{{ route('livros.alugar', $livro->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-alugar">ALUGAR</button>
                </form>
            @else
                <button class="btn-esgotado" disabled>SEM ESTOQUE</button>
            @endif
        </div>
    </div>

    <div class="col-direita" style="position: relative;">
        @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="admin-actions-top">
                <a href="{{ route('livros.edit', $livro->id) }}" class="btn-admin btn-edit">EDITAR</a>
                
                <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" onsubmit="return confirm('Deletar este livro?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-admin btn-delete">EXCLUIR</button>
                </form>
            </div>
        @endif

        <h1 class="titulo">{{ $livro->titulo }}</h1>
        <p class="autor">por <span>{{ $livro->autor }}</span></p>
        <span class="badge">Estoque atual: {{ $livro->estoque }}</span>
        
        <div class="meta-info">
            <span class="badge">{{ $livro->genero->nome ?? 'Geral' }}</span>
            <span class="data">Publicado em: {{ \Carbon\Carbon::parse($livro->data_publicacao)->format('Y') }}</span>
        </div>

        <hr class="divisor">

        <div class="descricao-box">
            <h3>Descrição</h3>
            <p>{{ $livro->descricao }}</p>
        </div>
    </div>
</div>
@endsection