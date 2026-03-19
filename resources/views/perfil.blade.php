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

        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn-primary" style="margin-bottom: 15px; display: flex; text-decoration: none;">
                Alugueis
            </a>
            <a href="{{ route('admin.users') }}" class="btn-primary" style="margin-bottom: 15px; display: flex; text-decoration: none;">
                Gerenciar Usuários
            </a>
        @endif

        {{-- BOTÃO EDITAR PERFIL --}}
        <button
            type="button"
            class="btn-primary"
            style="margin-bottom: 15px; width: 100%; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); box-shadow: 0 4px 15px rgba(37,99,235,0.3);"
            onclick="toggleFormEdicao()"
            id="btn-editar"
        >
            ✏️ Editar Perfil
        </button>

        {{-- FORMULÁRIO DE EDIÇÃO (oculto por padrão) --}}
        <div id="form-edicao" style="display: none;">
            <hr class="divider">
            <h3 style="text-align: left; font-size: 1rem; margin-bottom: 20px;">Editar Dados</h3>

            <form action="{{ route('perfil.update') }}" method="POST" style="text-align: left;">
                @csrf
                @method('PUT')

                <div class="input-group">
                    <label>Nome:</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                </div>

                <div class="input-group">
                    <label>E-mail:</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                </div>

                <div class="input-group">
                    <label>Nova Senha: <span style="font-size:0.75rem; opacity:0.5;">(deixe em branco para não alterar)</span></label>
                    <input type="password" name="password" placeholder="Mínimo 8 caracteres">
                </div>

                <div class="input-group">
                    <label>Confirme a Nova Senha:</label>
                    <input type="password" name="password_confirmation">
                </div>

                <hr class="divider">
                <h3 style="text-align: left; font-size: 1rem; margin-bottom: 20px;">Endereço</h3>

                {{-- CEP --}}
                <div class="input-group" style="position: relative;">
                    <label>CEP:</label>
                    <input
                        type="text"
                        name="cep"
                        id="cep"
                        value="{{ old('cep', Auth::user()->cep) }}"
                        placeholder="00000-000"
                        maxlength="9"
                        oninput="mascararCep(this)"
                        onblur="buscarCep(this.value)"
                        autocomplete="off"
                    >
                    <span id="cep-status" style="position:absolute; right:12px; bottom:10px; font-size:0.75rem; color:var(--accent); display:none;">Buscando...</span>
                </div>

                {{-- Rua + Número --}}
                <div style="display: flex; gap: 16px;">
                    <div class="input-group" style="flex: 2;">
                        <label>Rua:</label>
                        <input type="text" name="rua" id="rua" value="{{ old('rua', Auth::user()->rua) }}" placeholder="Preenchido automaticamente">
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Número:</label>
                        <input type="text" name="numero" id="numero" value="{{ old('numero', Auth::user()->numero) }}" placeholder="Ex: 123">
                    </div>
                </div>

                {{-- Complemento --}}
                <div class="input-group">
                    <label>Complemento: <span style="font-size:0.75rem; opacity:0.5;">(opcional)</span></label>
                    <input type="text" name="complemento" id="complemento" value="{{ old('complemento', Auth::user()->complemento) }}" placeholder="Apto, Bloco, Casa...">
                </div>

                {{-- Bairro + Cidade --}}
                <div style="display: flex; gap: 16px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Bairro:</label>
                        <input type="text" name="bairro" id="bairro" value="{{ old('bairro', Auth::user()->bairro) }}" placeholder="Preenchido automaticamente">
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Cidade:</label>
                        <input type="text" name="cidade" id="cidade" value="{{ old('cidade', Auth::user()->cidade) }}" placeholder="Preenchido automaticamente">
                    </div>
                </div>

                {{-- Estado --}}
                <div class="input-group" style="max-width: 100px;">
                    <label>Estado:</label>
                    <input type="text" name="estado" id="estado" value="{{ old('estado', Auth::user()->estado) }}" placeholder="UF" maxlength="2" style="text-transform: uppercase;">
                </div>

                {{-- Botões --}}
                <div style="display: flex; gap: 12px; margin-top: 8px;">
                    <button type="submit" class="btn-primary" style="flex: 2;">Salvar</button>
                    <button type="button" class="btn-cancel" style="flex: 1;" onclick="toggleFormEdicao()">Cancelar</button>
                </div>
            </form>
        </div>

        <hr class="divider">

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout-big">Sair da Conta</button>
        </form>
    </div>
</div>

<script>
    function toggleFormEdicao() {
        var form = document.getElementById('form-edicao');
        var btn  = document.getElementById('btn-editar');
        var visivel = form.style.display !== 'none';
        form.style.display = visivel ? 'none' : 'block';
        btn.textContent    = visivel ? '✏️ Editar Perfil' : '✖ Fechar Edição';
    }

    // Abre o formulário automaticamente se houver erros de validação
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            toggleFormEdicao();
        });
    @endif

    function mascararCep(input) {
        let v = input.value.replace(/\D/g, '');
        if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
        input.value = v;
    }

    function buscarCep(cep) {
        const limpo = cep.replace(/\D/g, '');
        if (limpo.length !== 8) return;

        const status   = document.getElementById('cep-status');
        const campoCep = document.getElementById('cep');
        status.style.display = 'inline';
        campoCep.style.borderColor = '';

        fetch('https://viacep.com.br/ws/' + limpo + '/json/')
            .then(function(res) { return res.json(); })
            .then(function(dados) {
                status.style.display = 'none';
                if (dados.erro) {
                    campoCep.style.borderColor = '#ef4444';
                    return;
                }
                campoCep.style.borderColor = '#22c55e';
                document.getElementById('rua').value    = dados.logradouro || '';
                document.getElementById('bairro').value = dados.bairro     || '';
                document.getElementById('cidade').value = dados.localidade  || '';
                document.getElementById('estado').value = dados.uf          || '';
                document.getElementById('numero').focus();
            })
            .catch(function() {
                status.style.display = 'none';
                document.getElementById('cep').style.borderColor = '#ef4444';
            });
    }
</script>
@endsection