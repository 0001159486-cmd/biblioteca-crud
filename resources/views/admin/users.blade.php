@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="titulo">Gerenciamento de Usuários</h1>

    <div class="glass-card" style="max-width: 97%; margin-top: 30px; padding: 20px;">
        <h3 style="color: #e1b12c; margin-bottom: 20px;">Usuários Cadastrados</h3>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nome / E-mail</th>
                    <th>Nível</th>
                    <th>Endereço</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $user)
                <tr>
                    <td>
                        <div style="display: flex; flex-direction: column;">
                            <span style="color: #fff; font-weight: bold;">{{ $user->name }}</span>
                            <span style="font-size: 0.8rem; color: #888;">{{ $user->email }}</span>
                        </div>
                    </td>

                    <td>
                        <span class="status-badge {{ $user->role === 'admin' ? 'em-dia' : '' }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>

                    <td>
                        @if($user->cep)
                            <button
                                class="btn-ver-endereco"
                                onclick="toggleEndereco('endereco-{{ $user->id }}')"
                            >
                                📍 Ver endereço
                            </button>

                            <div id="endereco-{{ $user->id }}" class="endereco-box" style="display: none;">
                                <div class="endereco-linha">
                                    <span class="endereco-label">CEP</span>
                                    <span>{{ $user->cep }}</span>
                                </div>
                                <div class="endereco-linha">
                                    <span class="endereco-label">Rua</span>
                                    <span>{{ $user->rua }}{{ $user->numero ? ', ' . $user->numero : '' }}</span>
                                </div>
                                @if($user->complemento)
                                <div class="endereco-linha">
                                    <span class="endereco-label">Complemento</span>
                                    <span>{{ $user->complemento }}</span>
                                </div>
                                @endif
                                <div class="endereco-linha">
                                    <span class="endereco-label">Bairro</span>
                                    <span>{{ $user->bairro }}</span>
                                </div>
                                <div class="endereco-linha">
                                    <span class="endereco-label">Cidade / UF</span>
                                    <span>{{ $user->cidade }} - {{ $user->estado }}</span>
                                </div>
                            </div>
                        @else
                            <span style="font-size: 0.8rem; color: #555;">Não informado</span>
                        @endif
                    </td>

                    <td>
                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-admin btn-edit">EDITAR</a>

                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-admin btn-delete" onclick="confirmDelete({{ $user->id }})">EXCLUIR</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>

<style>
.btn-ver-endereco {
    background: transparent !important;
    border: 1px solid rgba(225, 177, 44, 0.3) !important;
    color: #e1b12c !important;
    font-size: 0.7rem !important;
    padding: 4px 10px !important;
    border-radius: 4px !important;
    cursor: pointer;
    height: auto !important;
    transform: none !important;
    box-shadow: none !important;
    letter-spacing: 0.5px;
    transition: all 0.2s;
    white-space: nowrap;
}

.btn-ver-endereco:hover {
    background: rgba(225, 177, 44, 0.1) !important;
    border-color: #e1b12c !important;
    transform: none !important;
}

.endereco-box {
    margin-top: 10px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 6px;
    padding: 10px 12px;
    min-width: 220px;
}

.endereco-linha {
    display: flex;
    gap: 8px;
    font-size: 0.78rem;
    padding: 3px 0;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    color: #ccc;
}

.endereco-linha:last-child {
    border-bottom: none;
}

.endereco-label {
    color: #e1b12c;
    font-weight: 700;
    min-width: 80px;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Modo claro */
body.light-mode .endereco-box {
    background: rgba(36, 52, 71, 0.05);
    border-color: rgba(36, 52, 71, 0.12);
}

body.light-mode .endereco-linha {
    color: #1a2a3a;
    border-bottom-color: rgba(36, 52, 71, 0.08);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleEndereco(id) {
    var box = document.getElementById(id);
    var visivel = box.style.display !== 'none';
    box.style.display = visivel ? 'none' : 'block';
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Deletar usuário?',
        text: "Esta ação é irreversível!",
        icon: 'warning',
        showCancelButton: true,
        background: '#121212',
        color: '#fff',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#444',
        confirmButtonText: 'Sim, deletar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection