@extends('layouts.app')

@section('content')
<div class="admin-wrapper">
    <div class="glass-card-admin">
        <div class="form-header">
            <h2>Novo Título na Biblioteca</h2>
            <p>Preencha os dados abaixo para cadastrar o livro no sistema.</p>
        </div>

        <form action="{{ route('livros.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="titulo">Título do Livro</label>
                <input type="text" name="titulo" id="titulo" placeholder="Ex: O Código Limpo" required>
            </div>

            <div class="form-group">
                <label for="autor">Autor</label>
                <input type="text" name="autor" id="autor" placeholder="Nome do autor" required>
            </div>

            <div class="form-row">
                <div class="form-group flex-1">
                    <label for="estoque">Estoque Inicial</label>
                    <input type="number" name="estoque" id="estoque" min="0" value="1" required>
                </div>
                <div class="form-group flex-1">
                    <label for="genero_id">Gênero</label>
                    <select name="genero_id" id="genero_id" required>
                        <option value="" disabled selected>Selecione...</option>
                        @foreach($generos as $genero)
                            <option value="{{ $genero->id }}">{{ $genero->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="data_publicacao">Data de Publicação</label>
                <input type="date" name="data_publicacao" id="data_publicacao">
            </div>

            <div class="form-group">
                <label for="capa">Capa (SOMENTE ATÉ 2MB) </label>
                <input type="file" name="capa" id="capa" accept="image/*" class="file-input" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição / Sinopse</label>
                <textarea name="descricao" id="descricao" rows="4" placeholder="Breve resumo da obra..." required></textarea>
            </div>

            <div class="form-footer">
                <a href="{{ route('home') }}" class="btn-cancelar">CANCELAR</a>
                <button type="submit" class="btn-salvar">CADASTRAR</button>
            </div>
        </form>
    </div>
</div>

<style>

    
</style>
@endsection