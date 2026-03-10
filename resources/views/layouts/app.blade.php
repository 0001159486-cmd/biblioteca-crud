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
            <div class="logo"><a href="/home">Biblioteca</a></div>
            @else
            <div class="logo"><a href="/">Biblioteca</a></div>
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

    function toggleChat() {
    const chatWindow = document.getElementById('chat-window');
    const display = chatWindow.style.display;
    chatWindow.style.display = (display === 'none' || display === '') ? 'flex' : 'none';
}

function handleKeyPress(e) {
    if (e.key === 'Enter') sendToGemini();
}

async function sendToGemini() {
    const input = document.getElementById('chat-input');
    const container = document.getElementById('chat-messages');
    const texto = input.value.trim();

    if (!texto) return;

    container.innerHTML += `<div class="msg user">${texto}</div>`;
    input.value = '';
    container.scrollTop = container.scrollHeight;

    const loadingId = 'loading-' + Date.now();
    container.innerHTML += `<div class="msg ai" id="${loadingId}">...</div>`;

    try {
        const response = await fetch("{{ route('gemini.chat') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ pergunta: texto })
        });

        const data = await response.json();
        document.getElementById(loadingId).innerText = data.resposta;
    } catch (error) {
        document.getElementById(loadingId).innerText = "Erro ao conectar com o bibliotecário.";
    }
    container.scrollTop = container.scrollHeight;
}
</script>

<div id="chat-circle" class="chat-button" onclick="toggleChat()">
    <span class="icon">💬</span>
</div>

<div id="chat-window" class="chat-container" style="display: none;">
    <div id="chat-messages" class="chat-content">
        <div class="msg ai">Olá! Pergunte-me sobre os livros do nosso acervo.</div>
    </div>
    <div class="chat-footer">
        <input type="text" id="chat-input" placeholder="Qual livro você busca?" onkeypress="handleKeyPress(event)">
        <button onclick="sendToGemini()" class="send-btn">➔</button>
    </div>
</div>

</body>
</html>
