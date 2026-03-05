@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card">
        <h2>Acessar Biblioteca</h2>
        
        @if ($errors->any())
            <div style="color: var(--danger); margin-bottom: 1rem; font-size: 0.9rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <input type="checkbox" name="remember" id="remember" style="width: auto; margin: 0;">
                <label for="remember" style="cursor: pointer;">Lembrar de mim</label>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%;">Entrar</button>
        </form>
        
        <p style="margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-secondary);">
            Novo por aqui? <a href="/registrar" style="color: var(--accent); text-decoration: none;">Crie sua conta</a>
        </p>
    </div>
</div>
@endsection