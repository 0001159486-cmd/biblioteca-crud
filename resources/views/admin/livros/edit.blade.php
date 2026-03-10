@extends('layouts.app')

@section('content')
<div class="auth-wrapper" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="glass-card" style="width: 100%; max-width: 500px; padding: 30px;">
        <h2 style="color: #e1b12c; margin-bottom: 25px; text-align: center;">Editar Livro</h2>

        <form action="{{ route('livros.update', $livro->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Título</label>
                <input type="text" name="titulo" value="{{ $livro->titulo }}" required>
            </div>

            <div class="form-group">
                <label>Autor</label>
                <input type="text" name="autor" value="{{ $livro->autor }}" required>
            </div>

            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Estoque</label>
                    <input type="number" name="estoque" value="{{ $livro->estoque }}" min="0" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Data de Publicação</label>
                    <input type="date" name="data_publicacao" value="{{ $livro->data_publicacao }}">
                </div>
            </div>

            <div class="form-group">
                <label>Descrição</label>
                <textarea name="descricao" rows="4" required>{{ $livro->descricao }}</textarea>
            </div>

            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn-salvar">SALVAR</button>
                <a href="{{ route('livros.show', $livro->id) }}" class="btn-cancelar">CANCELAR</a>
            </div>
        </form>
    </div>
</div>

@endsection