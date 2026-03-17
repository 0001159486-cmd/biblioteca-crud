@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-card" style="text-align: center;">

        <div class="checkmark-container" style="margin-bottom: 20px;">
            <img src="{{ asset('img/check.png') }}" alt="Sucesso" style="width: 100px; height: 100px;">
        </div>

<div class="success-message">
    <h2>Concluído!</h2>
    @if(session('data_entrega'))
        <p>Devolva este livro até: <strong>{{ session('data_entrega') }}</strong></p>
    @endif
</div>

    </div>
</div>
@endsection
