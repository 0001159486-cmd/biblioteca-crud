@extends('layouts.app')
@section('content')
<div class="vitrine-livros">
    @foreach($livros as $livro)
        <div class="livro-card">
            <div class="capa-container">
                <img src="{{ asset('storage/' . $livro->capa) }}" alt="Capa de {{ $livro->titulo }}">
            </div>
            
            <div class="livro-info">
                <div class="meta">
                    <small>{{ $livro->genero?->nome }}</small>
                    <h3>{{ $livro->titulo }}</h3>
                    <p>Por: {{ $livro->autor }}</p>
                </div>
                
                <div class="footer-card">
                    @if($livro->estoque > 0)
                        <span class="status disponivel">Disponível: {{ $livro->estoque }}</span>
                    @else
                        <span class="status esgotado">Indisponível</span>
                    @endif

                    <a href="{{ route('livros.show', $livro->id) }}" class="btn-primary">Ver Detalhes</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="pagination-container">
    {{ $livros->links() }}
</div>
@endsection