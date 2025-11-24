<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Helpdesk System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }
        
        .gradient-border {
            position: relative;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-radius: 1.5rem;
            padding: 2px;
        }
        
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1.5rem;
            padding: 2px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #06b6d4);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }
        
        .gradient-border-inner {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-radius: 1.4rem;
            position: relative;
            z-index: 1;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">

    <div class="w-full max-w-md fade-in">
        
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-500/20 border border-blue-500/30 mb-4">
                <i class="fas fa-headset text-3xl text-blue-500"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">Helpdesk System</h1>
        </div>

        <!-- Login Card -->
        <div class="gradient-border">
            <div class="gradient-border-inner p-8">
                
                <h2 class="text-xl font-bold text-white mb-2">Login</h2>
                <p class="text-slate-400 text-sm mb-6">Masukkan kredensial Anda untuk melanjutkan</p>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl flex items-start space-x-3">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-red-400 text-sm font-medium">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input 
                                type="email" 
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-500 transition-all text-sm"
                                placeholder="nama@example.com"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input 
                                type="password" 
                                name="password"
                                id="password"
                                class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-11 pr-12 py-3 text-white placeholder-slate-500 transition-all text-sm"
                                placeholder="••••••••"
                                required
                            >
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors"
                                title="Tampilkan password"
                            >
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 text-slate-300 cursor-pointer group">
                            <input 
                                type="checkbox" 
                                name="remember"
                                class="w-4 h-4 rounded border-slate-600 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 bg-slate-800"
                            >
                            <span class="text-sm group-hover:text-white transition-colors">Ingat Saya</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit"
                        class="w-full btn-login text-white font-semibold py-3 rounded-xl flex items-center justify-center space-x-2 shadow-lg shadow-blue-500/25"
                    >
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                </form>

            </div>
        </div>

       

    </div>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add loading state on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');
        });

        // Auto-hide error message after 5 seconds
        const errorAlert = document.querySelector('[class*="bg-red-500"]');
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.transition = 'opacity 0.5s ease';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500);
            }, 5000);
        }
    </script>

</body>
</html>