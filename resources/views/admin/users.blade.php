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



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
    })
}
</script>
@endsection