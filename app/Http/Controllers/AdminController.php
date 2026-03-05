<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $alugueis = Emprestimo::with(['user', 'livro'])->get();

        $totalAtivos = $alugueis->count();

        $totalAtrasados = $alugueis->filter(function ($item) {
            return $item->data_devolucao && $item->data_devolucao->isPast();
        })->count();

        $maisAlugado = Livro::withCount('emprestimos')
            ->orderBy('emprestimos_count', 'desc')
            ->first();

        return view('admin.dashboard', [
            'alugueis' => $alugueis,
            'totalAtivos' => $totalAtivos,
            'totalAtrasados' => $totalAtrasados,
            'maisAlugado' => $maisAlugado,
        ]);
    }

    public function concluirEmprestimo(Emprestimo $emprestimo)
    {
        $emprestimo->delete();

        return redirect()->back()->with('success', 'Empréstimo concluído e removido do sistema.');
    }

    public function users()
    {
        $usuarios = User::paginate(10);

        return view('admin.users', compact('usuarios'));
    }

    public function editUser(User $user)
    {
        return view('admin.users_edit', compact('user'));
    }

    public function destroyUser(User $user)
    {
        if ($user->emprestimos()->count() > 0) {
            return redirect()->route('admin.users')->with('error', 'O usuário possui empréstimos ativos e não pode ser removido.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuário removido com sucesso!');
    }

    public function updateUser(Request $request, User $user)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:aluno,admin',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $dados['name'];
        $user->email = $dados['email'];
        $user->role = $dados['role'];

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Dados atualizados com sucesso.');
    }
}
