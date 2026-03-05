@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="titulo">Dashboard Administrativo</h1>
    
    {{-- Cards de Estatísticas --}}
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Mais Alugado (Semana)</span>
            <span class="stat-value">{{ $maisAlugado->titulo ?? 'Nenhum' }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Empréstimos Ativos</span>
            <span class="stat-value">{{ $totalAtivos }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Atrasados</span>
            <span class="stat-value" style="color: #ef4444;">{{ $totalAtrasados }}</span>
        </div>
    </div>

    {{-- Tabela de Gerenciamento --}}
    <div class="glass-card" style="max-width: 97%; margin-top: 30px; padding: 20px;">
        <h3 style="color: #e1b12c; margin-bottom: 20px;">Empréstimos em Aberto</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Livro</th>
                    <th>Data Aluguel</th>
                    <th>Vencimento</th>
                    <th>Status</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alugueis as $aluguel)
                <tr>
                    <td>{{ $aluguel->user->name ?? 'N/A' }}</td>
                    <td>{{ $aluguel->livro->titulo ?? 'N/A' }}</td>
                    <td>{{ $aluguel->data_saida ? $aluguel->data_saida->format('d/m/Y') : '-' }}</td>
                    <td>{{ $aluguel->data_devolucao ? $aluguel->data_devolucao->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($aluguel->data_devolucao && $aluguel->data_devolucao->isPast())
                            <span class="status-badge atrasado">ATRASADO</span>
                        @else
                            <span class="status-badge em-dia">EM DIA</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <form action="{{ route('admin.emprestimos.concluir', $aluguel->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin btn-edit" style="height: 28px; padding: 0 12px; font-size: 0.6rem;">
                                CONCLUIR
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection