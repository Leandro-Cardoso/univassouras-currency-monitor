<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Monitor de Moedas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-6">
    <nav class="max-w-6xl mx-auto flex justify-between items-center bg-white p-4 rounded-lg shadow-sm mb-6">
        <span class="font-bold text-lg text-gray-700">🪙 Monitor de Câmbio | <span class="font-normal text-sm text-gray-500">Logado como: {{ Auth::user()->name }}</span></span>
        <a href="/logout" class="text-red-500 hover:underline font-medium">Sair</a>
    </nav>

    <main class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Nova Consulta</h2>
                <form action="/dashboard/search" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Escolha o Par de Câmbio</label>
                        <select name="pair" class="w-full p-3 border rounded-lg bg-gray-50 font-medium text-gray-700">
                            <option value="USD-BRL">Dólar Americano (USD) ➡️ Real (BRL)</option>
                            <option value="EUR-BRL">Euro (EUR) ➡️ Real (BRL)</option>
                            <option value="BTC-BRL">Bitcoin (BTC) ➡️ Real (BRL)</option>
                            <option value="GBP-BRL">Libra Esterlina (GBP) ➡️ Real (BRL)</option>
                            <option value="USD-EUR">Dólar Americano (USD) ➡️ Euro (EUR)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                        Consultar Cotação
                    </button>
                </form>
            </div>

            @if($last_search)
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-6 rounded-lg shadow-md">
                    <span class="text-xs font-bold uppercase bg-blue-500/40 px-2 py-1 rounded">Resultado Atual</span>
                    <h3 class="text-lg font-bold mt-2">{{ $last_search['name'] }}</h3>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <p class="text-blue-200 text-xs">Compra (Bid)</p>
                            <p class="text-2xl font-black">R$ {{ number_format($last_search['bid'], 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-xs">Venda (Ask)</p>
                            <p class="text-2xl font-black">R$ {{ number_format($last_search['ask'], 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/20 text-xs flex justify-between">
                        <span>Variação: <b class="{{ $last_search['pctChange'] >= 0 ? 'text-green-300' : 'text-red-300' }}">{{ $last_search['pctChange'] }}%</b></span>
                        <span>Fator: {{ $last_search['from'] }}/{{ $last_search['to'] }}</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Histórico de Pesquisas (Auditoria)</h2>
                
                @if(session('sucesso')) <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">{{ session('sucesso') }}</div> @endif
                @if(session('erro')) <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">{{ session('erro') }}</div> @endif

                @if($history->isEmpty())
                    <p class="text-gray-500 text-center py-8">Nenhuma consulta realizada por esta conta ainda.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b text-gray-600 font-bold">
                                    <th class="p-3">De</th>
                                    <th class="p-3">Para</th>
                                    <th class="p-3">Compra (Bid)</th>
                                    <th class="p-3">Venda (Ask)</th>
                                    <th class="p-3">Data Cotação (API)</th>
                                    <th class="p-3">Data Registro</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @foreach($history as $reg)
                                    <tr class="hover:bg-gray-50/80">
                                        <td class="p-3 font-bold text-blue-600">{{ $reg->from_currency }}</td>
                                        <td class="p-3 font-bold text-green-600">{{ $reg->to_currency }}</td>
                                        <td class="p-3">R$ {{ number_format($reg->bid_value, 4, ',', '.') }}</td>
                                        <td class="p-3">R$ {{ number_format($reg->ask_value, 4, ',', '.') }}</td>
                                        <td class="p-3 text-xs text-gray-500">{{ date('d/m/Y H:i:s', strtotime($reg->consulted_at)) }}</td>
                                        <td class="p-3 text-xs text-gray-500 font-medium">{{ date('d/m/Y H:i:s', strtotime($reg->created_at)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>