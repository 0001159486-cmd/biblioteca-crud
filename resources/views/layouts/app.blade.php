<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Digital</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Aplica o tema salvo ANTES de renderizar, evita flash de tema errado --}}
    <script>
        (function() {
            if (localStorage.getItem('tema') === 'claro') {
                document.documentElement.classList.add('light-mode-pre');
            }
        })();
    </script>
    <style>
        html.light-mode-pre body { background-color: #f0ebe0; }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            @auth
            <div class="logo"><a href="/home">Biblioteca</a></div>
            @else
            <div class="logo"><a href="/">Biblioteca</a></div>
            @endauth

            {{-- Barra de busca: só aparece para usuários logados --}}
            @auth
            <div class="search-wrapper">
                <form action="{{ route('home') }}" method="GET">
                    <div class="search-box">
                        <input type="text" name="search" placeholder="O que você procura?" value="{{ request('search') }}">
                        <button type="submit">🔍</button>
                    </div>
                </form>
            </div>
            @endauth

            <ul class="nav-links">
                @auth
                    {{-- Perfil do usuário --}}
                    <li>
                        <a href="/perfil" class="user-profile-link">
                            <div class="profile-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>Olá, {{ Auth::user()->name }}</span>
                        </a>
                    </li>

                    {{-- Botão admin: só aparece para admins, após o nome --}}
                    @if(Auth::user()->role === 'admin')
                    <li>
                        <a href="{{ route('livros.create') }}" class="btn-admin-action">
                            <span class="icon">+</span>
                            <span class="text">ADICIONAR LIVRO</span>
                        </a>
                    </li>
                    @endif
                @else
                    <li><a href="/login">Entrar</a></li>
                    <li><a href="/registrar">Registrar</a></li>
                @endauth

                {{-- BOTÃO TOGGLE DE TEMA — sempre por último --}}
                <li>
                    <button class="theme-toggle-btn" id="theme-toggle" onclick="toggleTema()" title="Alternar tema">
                        <span class="toggle-icon" id="toggle-icon">☀️</span>
                        <span id="toggle-label">CLARO</span>
                    </button>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container">
        @yield('content')
    </main>

    {{-- Sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ============================================================
        // SISTEMA DE TEMA
        // ============================================================
        function aplicarTema(tema) {
            const body = document.body;
            const icon = document.getElementById('toggle-icon');
            const label = document.getElementById('toggle-label');

            if (tema === 'claro') {
                body.classList.add('light-mode');
                icon.textContent = '🌙';
                label.textContent = 'ESCURO';
            } else {
                body.classList.remove('light-mode');
                icon.textContent = '☀️';
                label.textContent = 'CLARO';
            }
        }

        function toggleTema() {
            const temaAtual = localStorage.getItem('tema') || 'escuro';
            const novoTema = temaAtual === 'escuro' ? 'claro' : 'escuro';
            localStorage.setItem('tema', novoTema);
            aplicarTema(novoTema);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const temaSalvo = localStorage.getItem('tema') || 'escuro';
            aplicarTema(temaSalvo);
            document.documentElement.classList.remove('light-mode-pre');
        });

        // ============================================================
        // SWEETALERT — Adapta as cores ao tema atual
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const temaAtual = localStorage.getItem('tema') || 'escuro';
            const config = temaAtual === 'claro'
                ? { background: '#faf6ef', color: '#1a1a1a', confirmButtonColor: '#b8860b' }
                : { background: '#121212', color: '#fff', confirmButtonColor: '#e1b12c' };

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

        // ============================================================
        // CHAT GEMINI — só funciona quando logado, mas o HTML
        // só é renderizado para usuários autenticados (ver abaixo)
        // ============================================================
        function toggleChat() {
            const chatWindow = document.getElementById('chat-window');
            if (!chatWindow) return;
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

    {{-- Chat: só aparece para usuários logados --}}
    @auth
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
    @endauth

</body>
</html>