<div id="aiChatPanel" class="ai-chat-panel fixed right-0 top-0 h-full w-96 bg-slate-950 border-l border-slate-800 shadow-2xl z-50 flex flex-col">
    <!-- Chat Header -->
    <div class="gradient-border p-4 flex items-center justify-between border-b border-slate-800">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                <i class="fas fa-robot text-white"></i>
            </div>
            <div>
                <h3 class="font-semibold">AI Assistant</h3>
                <div class="flex items-center space-x-1">
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                    <span class="text-xs text-slate-400">Online</span>
                </div>
            </div>
        </div>
        <button id="closeChatPanel" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
            <i class="fas fa-times text-slate-400 hover:text-white"></i>
        </button>
    </div>

    <!-- Quick Actions -->
    <div class="p-4 border-b border-slate-800">
        <p class="text-xs text-slate-400 mb-3">Quick Actions</p>
        <div class="grid grid-cols-2 gap-2">
            <button class="quick-action-btn p-3 bg-slate-800/50 hover:bg-slate-800 rounded-lg text-left transition-colors">
                <i class="fas fa-search text-purple-500 text-sm mb-1"></i>
                <p class="text-xs font-medium">Cari Tiket</p>
            </button>
            <button class="quick-action-btn p-3 bg-slate-800/50 hover:bg-slate-800 rounded-lg text-left transition-colors">
                <i class="fas fa-lightbulb text-yellow-500 text-sm mb-1"></i>
                <p class="text-xs font-medium">Saran Solusi</p>
            </button>
            <button class="quick-action-btn p-3 bg-slate-800/50 hover:bg-slate-800 rounded-lg text-left transition-colors">
                <i class="fas fa-chart-bar text-blue-500 text-sm mb-1"></i>
                <p class="text-xs font-medium">Analisis Data</p>
            </button>
            <button class="quick-action-btn p-3 bg-slate-800/50 hover:bg-slate-800 rounded-lg text-left transition-colors">
                <i class="fas fa-brain text-pink-500 text-sm mb-1"></i>
                <p class="text-xs font-medium">Prediksi Trend</p>
            </button>
        </div>
    </div>

    <!-- Chat Messages -->
    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4 hide-scrollbar">
        <!-- Welcome Message -->
        <div class="chat-message flex items-start space-x-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-white text-xs"></i>
            </div>
            <div class="flex-1">
                <div class="bg-slate-800/50 rounded-2xl rounded-tl-none p-3">
                    <p class="text-sm">Halo! Saya AI Assistant Helpdesk. Saya bisa membantu Anda:</p>
                    <ul class="text-xs text-slate-400 mt-2 space-y-1">
                        <li>• Mencari dan menganalisis tiket</li>
                        <li>• Memberikan solusi otomatis</li>
                        <li>• Memprediksi permasalahan</li>
                        <li>• Membuat laporan instan</li>
                    </ul>
                </div>
                <span class="text-xs text-slate-500 mt-1 block">Baru saja</span>
            </div>
        </div>
    </div>

    <!-- Chat Input -->
    <div class="p-4 border-t border-slate-800">
        <div class="flex items-center space-x-2">
            <button class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-paperclip text-slate-400"></i>
            </button>
            <input 
                type="text" 
                id="chatInput"
                placeholder="Ketik pesan atau pertanyaan..." 
                class="flex-1 bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
            >
            <button id="sendMessage" class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-lg transition-colors">
                <i class="fas fa-paper-plane text-white"></i>
            </button>
        </div>
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center space-x-2">
                <button class="text-xs text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-microphone mr-1"></i>Voice
                </button>
                <button class="text-xs text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-image mr-1"></i>Image
                </button>
            </div>
            <span class="text-xs text-slate-500">AI Helpdesk</span>
        </div>
    </div>
</div>

<script>
document.getElementById("sendMessage").addEventListener("click", sendAIMessage);
document.getElementById("chatInput").addEventListener("keypress", function(e){
    if (e.key === "Enter") sendAIMessage();
});

function addUserMessage(msg) {
    const box = document.getElementById("chatMessages");
    box.innerHTML += `
        <div class="flex justify-end mb-2">
            <div class="bg-purple-600 text-white p-3 rounded-xl max-w-xs text-sm">${msg}</div>
        </div>`;
    box.scrollTop = box.scrollHeight;
}

function addAIMessage(msg) {
    const box = document.getElementById("chatMessages");
    box.innerHTML += `
        <div class="flex items-start space-x-3 mb-2">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                <i class="fas fa-robot text-white text-xs"></i>
            </div>
            <div class="bg-slate-800/50 p-3 rounded-xl text-sm max-w-xs">${msg}</div>
        </div>`;
    box.scrollTop = box.scrollHeight;
}

async function sendAIMessage() {
    let msg = document.getElementById("chatInput").value;
    if (!msg.trim()) return;

    addUserMessage(msg);
    document.getElementById("chatInput").value = "";

    addAIMessage("⏳ Sedang mengetik...");

    let response = await fetch("/api/ai-chat", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ message: msg })
    });

    let data = await response.json();

// remove "typing"
let box = document.getElementById("chatMessages");
box.lastElementChild.remove();

if (data.success) {

    // CEK INTENT DARI AI
    if (data.intent === "export_excel") {
        window.location.href = "/api/ai/export-excel";
        addAIMessage("📄 Sedang membuat file Excel…");
        return;
    }

    if (data.intent === "analisis_user") {
        addAIMessage("📊 Sebentar, saya menganalisis user...");
        // panggil endpoint analisis
        let res = await fetch("/api/ai/analisis-user");
        let result = await res.json();
        addAIMessage(result.text);
        return;
    }

    if (data.intent === "analisis_tiket") {
        let res = await fetch("/api/ai/analisis-tiket");
        let result = await res.json();
        addAIMessage(result.text);
        return;
    }

    // Normal Reply
    addAIMessage(data.reply);

} else {
    addAIMessage("❗ Error: " + data.error);
}
}
</script>
