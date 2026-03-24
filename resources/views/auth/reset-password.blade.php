@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card">
        <h2>Nova Senha</h2>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
            Digite e confirme sua nova senha abaixo.
        </p>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <label>E-mail:</label>
                <input type="email" name="email" value="{{ old('email', request('email')) }}" required>
            </div>

            <div class="input-group">
                <label>Nova Senha:</label>
                <input type="password" name="password" placeholder="Mínimo 8 caracteres" required>
            </div>

            <div class="input-group">
                <label>Confirme a Nova Senha:</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%;">Redefinir Senha</button>
        </form>
    </div>
</div>
@endsection