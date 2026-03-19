@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card" style="max-width: 520px;">
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

            {{-- CEP --}}
            <div class="input-group" style="position: relative;">
                <label>CEP:</label>
                <input
                    type="text"
                    name="cep"
                    id="cep"
                    value="{{ old('cep') }}"
                    placeholder="00000-000"
                    maxlength="9"
                    oninput="mascararCep(this)"
                    onblur="buscarCep(this.value)"
                    autocomplete="off"
                >
                <span id="cep-status" style="
                    position: absolute;
                    right: 12px;
                    bottom: 10px;
                    font-size: 0.75rem;
                    color: var(--accent);
                    display: none;
                ">Buscando...</span>
            </div>

            {{-- Rua + Número --}}
            <div style="display: flex; gap: 16px;">
                <div class="input-group" style="flex: 2;">
                    <label>Rua:</label>
                    <input type="text" name="rua" id="rua" value="{{ old('rua') }}" placeholder="Preenchido automaticamente">
                </div>
                <div class="input-group" style="flex: 1;">
                    <label>Número:</label>
                    <input type="text" name="numero" id="numero" value="{{ old('numero') }}" placeholder="Ex: 123">
                </div>
            </div>

            {{-- Complemento --}}
            <div class="input-group">
                <label>Complemento: <span style="font-size:0.75rem; opacity:0.5;">(opcional)</span></label>
                <input type="text" name="complemento" id="complemento" value="{{ old('complemento') }}" placeholder="Apto, Bloco, Casa...">
            </div>

            {{-- Bairro + Cidade --}}
            <div style="display: flex; gap: 16px;">
                <div class="input-group" style="flex: 1;">
                    <label>Bairro:</label>
                    <input type="text" name="bairro" id="bairro" value="{{ old('bairro') }}" placeholder="Preenchido automaticamente">
                </div>
                <div class="input-group" style="flex: 1;">
                    <label>Cidade:</label>
                    <input type="text" name="cidade" id="cidade" value="{{ old('cidade') }}" placeholder="Preenchido automaticamente">
                </div>
            </div>

            {{-- Estado --}}
            <div class="input-group" style="max-width: 100px;">
                <label>Estado:</label>
                <input type="text" name="estado" id="estado" value="{{ old('estado') }}" placeholder="UF" maxlength="2" style="text-transform: uppercase;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 8px;">Criar Conta</button>
        </form>

        <p style="margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-secondary);">
            Já tem uma conta?
            <a href="/login" style="color: var(--accent); text-decoration: none;">Entre aqui</a>
        </p>
    </div>
</div>

<script>
    function mascararCep(input) {
        let v = input.value.replace(/\D/g, '');
        if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
        input.value = v;
    }

    function buscarCep(cep) {
        const limpo = cep.replace(/\D/g, '');
        if (limpo.length !== 8) return;

        const status = document.getElementById('cep-status');
        const campoCep = document.getElementById('cep');
        status.style.display = 'inline';
        campoCep.style.borderColor = '';

        fetch('https://viacep.com.br/ws/' + limpo + '/json/')
            .then(function(res) { return res.json(); })
            .then(function(dados) {
                status.style.display = 'none';

                if (dados.erro) {
                    campoCep.style.borderColor = '#ef4444';
                    limparEndereco();
                    return;
                }

                campoCep.style.borderColor = '#22c55e';
                document.getElementById('rua').value         = dados.logradouro || '';
                document.getElementById('bairro').value      = dados.bairro     || '';
                document.getElementById('cidade').value      = dados.localidade  || '';
                document.getElementById('estado').value      = dados.uf          || '';

                // Foca no número após preencher
                document.getElementById('numero').focus();
            })
            .catch(function() {
                status.style.display = 'none';
                campoCep.style.borderColor = '#ef4444';
            });
    }

    function limparEndereco() {
        document.getElementById('rua').value        = '';
        document.getElementById('bairro').value     = '';
        document.getElementById('cidade').value     = '';
        document.getElementById('estado').value     = '';
    }
</script>
@endsection