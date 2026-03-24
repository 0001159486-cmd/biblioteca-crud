@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card">
        <h2>Recuperar Senha</h2>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
            Digite seu e-mail e enviaremos um link para redefinir sua senha.
        </p>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="input-group">
                <label>E-mail:</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%;">Enviar Link</button>
        </form>

        <p style="margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-secondary);">
            Lembrou a senha? <a href="/login" style="color: var(--accent); text-decoration: none;">Voltar ao login</a>
        </p>
    </div>
</div>
@endsection