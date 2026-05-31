<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Monitor de Moedas - Entrar</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl bg-white p-8 rounded-xl shadow-md w-full mx-4">
        
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Já tenho conta</h2>
            
            @if($errors->has('login')) 
                <p class="text-red-500 text-sm mb-2 font-medium">{{ $errors->first('login') }}</p> 
            @endif
            
            <form action="/login" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-600 mb-1">E-mail</label>
                    <input type="email" name="email" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Senha</label>
                    <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded transition">
                    Entrar no Monitor
                </button>
            </form>
        </div>

        <div class="border-t md:border-t-0 md:border-l pt-6 md:pt-0 md:pl-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Criar Nova Conta</h2>
            <form action="/register" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nome Completo</label>
                    <input type="text" name="name" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">E-mail válido</label>
                    <input type="email" name="email" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Escolha uma Senha</label>
                    <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded transition">
                    Cadastrar e Entrar
                </button>
            </form>
        </div>

    </div>
</body>
</html>