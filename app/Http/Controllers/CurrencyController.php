<?php

namespace App\Http\Controllers;

use App\Models\CurrencyHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CurrencyController extends Controller
{
    public function index()
    {
        if (!Auth::check()) return redirect('/login');

        // Busca o histórico do usuário logado do mais recente para o mais antigo
        $historico = CurrencyHistory::where('user_id', Auth::id())
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return view('dashboard', ['history' => $historico, 'last_search' => null]);
    }

    public function consultar(Request $request)
    {
        if (!Auth::check()) return redirect('/login');

        $request->validate([
            'pair' => 'required|string' // Ex: "USD-BRL" ou "EUR-BRL"
        ]);

        list($from, $to) = explode('-', $request->pair);

        $lastSearch = null;

        try {
            // Consulta a AwesomeAPI em tempo real
            $response = Http::timeout(10)->get("https://economia.awesomeapi.com.br/last/{$request->pair}");

            if ($response->successful()) {
                // A API responde com uma chave dinâmica ex: "USDBRL"
                $key = $from . $to;
                $data = $response->json()[$key];

                $lastSearch = [
                    'from' => $from,
                    'to' => $to,
                    'name' => $data['name'],
                    'bid' => $data['bid'],
                    'ask' => $data['ask'],
                    'pctChange' => $data['pctChange']
                ];

                // Grava automaticamente na tabela de histórico para auditoria
                CurrencyHistory::create([
                    'user_id' => Auth::id(),
                    'from_currency' => $from,
                    'to_currency' => $to,
                    'bid_value' => $data['bid'],
                    'ask_value' => $data['ask'],
                    'consulted_at' => Carbon::parse($data['create_date']),
                ]);

                session()->now('sucesso', 'Consulta realizada e registrada no histórico!');
            } else {
                session()->now('erro', 'Par de moedas não suportado ou API indisponível.');
            }
        } catch (\Exception $e) {
            session()->now('erro', 'Falha ao conectar com o serviço de cotações.');
        }

        // Recarrega o histórico atualizado
        $historico = CurrencyHistory::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('dashboard', ['history' => $historico, 'last_search' => $lastSearch]);
    }
}
