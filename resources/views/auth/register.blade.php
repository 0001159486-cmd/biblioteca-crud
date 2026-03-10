@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card">
        <h2>Criar Conta</h2>
        
        <form action="/registrar" method="POST">
            @csrf

            <div class="input-group">
                <label>Nome:</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="input-group">
                <label>E-mail:</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="input-group">
                <label>Senha:</label>
                <input type="password" name="password" placeholder="Mínimo 8 caracteres" required>
            </div>

            <div class="input-group">
                <label>Confirme a Senha:</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn-primary">Criar Conta</button>
        </form>
        
        <p class="footer-link">Já tem uma conta? <a href="/login" style="color: var(--accent); text-decoration: none;" >Entre aqui</a></p>
    </div>
</div>
@endsection