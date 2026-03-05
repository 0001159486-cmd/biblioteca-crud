@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card profile-card">
        <div class="profile-avatar-big">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>

        <div class="profile-info">
            <h2>{{ Auth::user()->name }}</h2>
            <p>{{ Auth::user()->email }}</p>
            <span class="badge" style="background: rgba(225, 177, 44, 0.1); color: #e1b12c; border: 1px solid #e1b12c;">
                {{ strtoupper(Auth::user()->role) }}
            </span>
        </div>

        <hr class="divider">

        {{-- BOTÃO DO DASHBOARD --}}
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn-primary" style="margin-bottom: 15px; display: flex; text-decoration: none;">
                Alugueis
            </a>

            <a href="{{ route('admin.users') }}" class="btn-primary" style="margin-bottom: 15px; display: flex; text-decoration: none;">
                Gerenciar Usuários
            </a>
        @endif

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout-big">
                Sair da Conta
            </button>
        </form>
    </div>
</div>
@endsection