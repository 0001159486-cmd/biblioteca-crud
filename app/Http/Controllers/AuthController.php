<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'cep'          => 'nullable|string|max:9',
            'rua'          => 'nullable|string|max:255',
            'numero'       => 'nullable|string|max:10',
            'complemento'  => 'nullable|string|max:255',
            'bairro'       => 'nullable|string|max:255',
            'cidade'       => 'nullable|string|max:255',
            'estado'       => 'nullable|string|max:2',
        ]);

        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'aluno',
            'cep'          => $request->cep,
            'rua'          => $request->rua,
            'numero'       => $request->numero,
            'complemento'  => $request->complemento,
            'bairro'       => $request->bairro,
            'cidade'       => $request->cidade,
            'estado'       => $request->estado,
        ]);

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso! Agora você pode entrar.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->withErrors(['email' => 'Dados não conferem.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ============================================================
    // PERFIL
    // ============================================================

    public function showPerfil()
    {
        return view('perfil', ['user' => Auth::user()]);
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|min:8|confirmed',
            'cep'         => 'nullable|string|max:9',
            'rua'         => 'nullable|string|max:255',
            'numero'      => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro'      => 'nullable|string|max:255',
            'cidade'      => 'nullable|string|max:255',
            'estado'      => 'nullable|string|max:2',
        ]);

        $user->name        = $request->name;
        $user->email       = $request->email;
        $user->cep         = $request->cep;
        $user->rua         = $request->rua;
        $user->numero      = $request->numero;
        $user->complemento = $request->complemento;
        $user->bairro      = $request->bairro;
        $user->cidade      = $request->cidade;
        $user->estado      = $request->estado;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}