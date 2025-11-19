<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-6 rounded-xl shadow">
        <h2 class="text-2xl font-bold text-center mb-6">Login Sistem</h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Email</label>
                <input type="email" name="email"
                    class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Password</label>
                <input type="password" name="password"
                    class="w-full border rounded p-2" required>
            </div>

            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Login
            </button>
        </form>
    </div>

</body>
</html>
