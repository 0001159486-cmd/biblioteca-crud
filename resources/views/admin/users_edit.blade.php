@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card">
        <h2 class="form-title">Editar Usuário</h2>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-row">
                <div class="form-group flex-1">
                    <label>Nível de Acesso</label>
                    <select name="role" class="select-dark">
                        <option value="aluno" {{ $user->role === 'aluno' ? 'selected' : '' }}>ALUNO / LEITOR</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>ADMINISTRADOR</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group flex-1">
                    <label>Nova Senha (opcional)</label>
                    <input type="password" name="password" placeholder="Deixe vazio para manter">
                </div>
                <div class="form-group flex-1">
                    <label>Confirmar Senha</label>
                    <input type="password" name="password_confirmation" placeholder="Repita a senha">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-salvar">SALVAR ALTERAÇÕES</button>
                <a href="{{ route('admin.users') }}" class="btn-cancelar">CANCELAR</a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-title {
        color: var(--accent);
        margin-bottom: 25px;
        text-align: center;
        font-family: var(--font-titles);
    }

    .form-row {
        display: flex;
        gap: 15px;
    }

    .flex-1 {
        flex: 1;
    }

    .select-dark {
        width: 100%;
        background: #1a1a1a;
        border: 1px solid #333;
        color: #eee;
        padding: 12px;
        border-radius: 4px;
        outline: none;
        cursor: pointer;
    }
</style>
@endsection