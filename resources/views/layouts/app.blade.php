<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Helpdesk - Dashboard')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
        }
        
        .gradient-border {
            position: relative;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background: rgba(30, 41, 59, 0.5);
        }

        .ai-chat-panel {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .ai-chat-panel.active {
            transform: translateX(0);
        }

        .chat-message {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .typing-indicator span {
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        .hide-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .hide-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .hide-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.2);
            border-radius: 10px;
        }
        
        .hide-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.3);
        }
        
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.2) transparent;
        }

        .sidebar-tooltip {
            position: absolute;
            left: 100%;
            margin-left: 15px;
            padding: 8px 12px;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 100;
        }

        .sidebar-btn-wrapper:hover .sidebar-tooltip {
            opacity: 1;
        }

        .sidebar-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #334155;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-slate-900 text-white">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 overflow-auto hide-scrollbar">
            <!-- Header -->
            @include('layouts.partials.header')

            <!-- Page Content -->
            @yield('content')
        </main>

        <!-- AI Chat Panel -->
        @include('layouts.partials.ai-chat')
    </div>

    @stack('scripts')
    
    <script>
        // CSRF Token Setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // AI Chat Panel Toggle
        const aiChatToggle = document.getElementById('aiChatToggle');
        const aiChatPanel = document.getElementById('aiChatPanel');
        const closeChatPanel = document.getElementById('closeChatPanel');
        const chatInput = document.getElementById('chatInput');
        const sendMessage = document.getElementById('sendMessage');
        const chatMessages = document.getElementById('chatMessages');

        aiChatToggle.addEventListener('click', () => {
            aiChatPanel.classList.add('active');
        });

        closeChatPanel.addEventListener('click', () => {
            aiChatPanel.classList.remove('active');
        });

        // Quick Actions
        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const actionText = this.querySelector('p').textContent;
                chatInput.value = actionText;
                chatInput.focus();
            });
        });

        // Send Message Function
        function sendChatMessage() {
            const message = chatInput.value.trim();
            if (message === '') return;

            const userMessageHTML = `
                <div class="chat-message flex items-start space-x-3 flex-row-reverse">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-semibold">{{ strtoupper(substr(auth()->user()->name ?? 'SR', 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 text-right">
                        <div class="bg-blue-500 rounded-2xl rounded-tr-none p-3 inline-block">
                            <p class="text-sm text-white">${message}</p>
                        </div>
                        <span class="text-xs text-slate-500 mt-1 block">Baru saja</span>
                    </div>
                </div>
            `;
            chatMessages.insertAdjacentHTML('beforeend', userMessageHTML);
            chatInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;

            const typingHTML = `
                <div id="typingIndicator" class="chat-message flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-800/50 rounded-2xl rounded-tl-none p-3">
                            <div class="typing-indicator flex space-x-1">
                                <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                                <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                                <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            chatMessages.insertAdjacentHTML('beforeend', typingHTML);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // TODO: Integrate with AI API
            setTimeout(() => {
                document.getElementById('typingIndicator').remove();
                
                const aiResponseHTML = `
                    <div class="chat-message flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <div class="bg-slate-800/50 rounded-2xl rounded-tl-none p-3">
                                <p class="text-sm">Baik, saya sedang memproses permintaan Anda. Mohon tunggu sebentar...</p>
                            </div>
                            <span class="text-xs text-slate-500 mt-1 block">Baru saja</span>
                        </div>
                    </div>
                `;
                chatMessages.insertAdjacentHTML('beforeend', aiResponseHTML);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 1500);
        }

        sendMessage.addEventListener('click', sendChatMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendChatMessage();
            }
        });

        // Sidebar navigation
        document.querySelectorAll('.sidebar-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-btn').forEach(b => {
                    b.classList.remove('active', 'bg-blue-500', 'text-white');
                    b.classList.add('text-slate-400');
                });
                this.classList.add('active', 'bg-blue-500', 'text-white');
                this.classList.remove('text-slate-400');
            });
        });
    </script>
</body>
</html>