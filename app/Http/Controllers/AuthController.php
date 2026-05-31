<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Realiza o cadastro do usuário no MariaDB
    public function register(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:4'
        ]);

        // Criptografa a senha nativamente antes de salvar
        $dados['password'] = Hash::make($dados['password']);
        
        // Cria o usuário na tabela 'users'
        $user = User::create($dados);

        // Loga o usuário automaticamente após criar a conta
        Auth::login($user);

        return redirect('/dashboard');
    }

    // Realiza o Login na Sessão
    public function login(Request $request)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Tenta autenticar contra a tabela 'users' do banco
        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        // Se errar, volta com a mensagem de erro
        return back()->withErrors(['login' => 'E-mail ou senha incorretos.']);
    }

    // Desloga o usuário e limpa os arquivos de sessão
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
