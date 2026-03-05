<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Digital</title>
    {{-- simplesmente parou de funcionar usei fonte local no lugar
    
	<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet"> --}}

<link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
<body>
    <header>
        <nav class="navbar">
            @auth
            <div class="logo"><a href="/home">BIBLIOTECA</a></div>
            @else
            <div class="logo"><a href="/">BIBLIOTECA</a></div>
            @endauth
            <div class="search-wrapper">
    <form action="{{ route('home') }}" method="GET">
        <div class="search-box">
            <input type="text" name="search" placeholder="O que você procura?" value="{{ request('search') }}">
            <button type="submit">🔍</button>
        </div>
    </form>
</div>
@if(Auth::check() && Auth::user()->role === 'admin')
    <div class="admin-toolbar">
        <a href="{{ route('livros.create') }}" class="btn-admin-action">
            <span class="icon">+</span>
            <span class="text">ADICIONAR LIVRO</span>
        </a>
    </div>
@endif
            <ul class="nav-links">
                @auth
                    <li>
    <a href="/perfil" class="user-profile-link">
        <div class="profile-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <span>Olá, {{ Auth::user()->name }}</span>
    </a>
</li>
                @else
                    <li><a href="/login">Entrar</a></li>
                    <li><a href="/registrar">Registrar</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main class="container">
        @yield('content') </main>

{{-- Sweetalert, achei legal e joguei aqui --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const config = {
            background: '#121212',
            color: '#fff',
            confirmButtonColor: '#e1b12c',
        };

        @if(session('success'))
            Swal.fire({
                ...config,
                icon: 'success',
                title: 'SUCESSO',
                text: "{{ session('success') }}",
                iconColor: '#22c55e'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                ...config,
                icon: 'error',
                title: 'OPERAÇÃO NEGADA',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
                iconColor: '#ef4444'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                ...config,
                icon: 'warning',
                title: 'VERIFIQUE OS CAMPOS',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#f59e0b',
                iconColor: '#f59e0b'
            });
        @endif
    });
</script>

</body>
</html>