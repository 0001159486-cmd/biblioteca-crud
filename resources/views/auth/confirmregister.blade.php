@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card" style="text-align: center;">
        
        <div class="checkmark-container" style="margin-bottom: 20px;">
            <img src="{{ asset('img/check.gif') }}" alt="Sucesso" style="width: 100px; height: 100px;">
        </div>

        <div class="success-message">
            <h2>Registro Concluído!</h2>
            <p>Sua conta foi criada com sucesso. Agora você já pode acessar o acervo da biblioteca.</p>
        </div>

    </div>
</div>
@endsection