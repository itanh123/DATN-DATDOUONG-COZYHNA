<!-- AI Chatbot Floating Widget -->
<div id="ai-chat-widget" class="fixed bottom-6 right-6 z-50 font-sans">
    <!-- Chat Toggle Button -->
    <button id="ai-chat-toggle" onclick="toggleAiChat()" 
        class="relative group flex items-center gap-2 bg-gradient-to-r from-amber-600 via-primary to-emerald-600 text-white px-4 py-3 rounded-full shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
        <div class="relative flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl animate-pulse">auto_awesome</span>
            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
        </div>
        <span class="font-bold text-sm tracking-wide hidden md:inline">AI Trợ Lý CozyHNA</span>
    </button>

    <!-- Chat Modal Window -->
    <div id="ai-chat-window" 
        class="hidden fixed bottom-24 right-4 md:right-6 w-[92vw] md:w-[380px] h-[520px] max-h-[80vh] bg-surface-container-lowest/95 backdrop-blur-md rounded-3xl shadow-2xl border border-outline-variant/30 flex flex-col overflow-hidden transition-all duration-300 transform scale-95 opacity-0">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary via-amber-700 to-emerald-700 p-4 text-white flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm border border-white/30">
                    <span class="material-symbols-outlined text-amber-200 text-xl">smart_toy</span>
                </div>
                <div>
                    <h3 class="font-bold text-base leading-tight">AI Trợ Lý CozyHNA</h3>
                    <p class="text-xs text-amber-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-ping"></span>
                        Trực tuyến • Tư vấn & VietQR
                    </p>
                </div>
            </div>
            <button onclick="toggleAiChat()" class="text-white/80 hover:text-white p-1 rounded-full hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Messages Body -->
        <div id="ai-chat-messages" class="flex-grow p-4 overflow-y-auto space-y-3 bg-surface-container-low/40 text-sm">
            <!-- Welcome Message -->
            <div class="flex items-start gap-2 max-w-[85%]">
                <div class="w-7 h-7 rounded-full bg-primary text-white flex items-center justify-center text-xs flex-shrink-0 mt-1">AI</div>
                <div class="bg-surface-container-lowest p-3 rounded-2xl rounded-tl-none border border-outline-variant/20 shadow-sm text-on-surface">
                    <p class="font-semibold text-primary mb-1">👋 Xin chào!</p>
                    <p>Tôi là <b>AI Trợ Lý CozyHNA</b>. Bạn cần tư vấn đồ uống ngon, hướng dẫn chuyển khoản <b>VietQR</b> hay kiểm tra đơn hàng?</p>
                </div>
            </div>

            <!-- Quick Action Chips -->
            <div class="flex flex-wrap gap-1.5 pt-1" id="ai-quick-chips">
                <button onclick="sendQuickMessage('Tư vấn món ngon hot hôm nay')" class="text-xs bg-primary/10 text-primary border border-primary/20 px-2.5 py-1.5 rounded-full hover:bg-primary hover:text-white transition-colors">
                    ☕ Tư vấn món HOT
                </button>
                <button onclick="sendQuickMessage('Hướng dẫn chuyển khoản VietQR')" class="text-xs bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 px-2.5 py-1.5 rounded-full hover:bg-emerald-600 hover:text-white transition-colors">
                    💳 Hướng dẫn VietQR
                </button>
                <button onclick="sendQuickMessage('Kiểm tra đơn hàng của tôi')" class="text-xs bg-amber-500/10 text-amber-700 border border-amber-500/20 px-2.5 py-1.5 rounded-full hover:bg-amber-600 hover:text-white transition-colors">
                    📦 Kiểm tra đơn hàng
                </button>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="ai-typing" class="hidden px-4 py-2 text-xs text-on-surface-variant flex items-center gap-2 bg-surface-container-low/40">
            <span class="w-2 h-2 bg-primary rounded-full animate-bounce"></span>
            <span class="w-2 h-2 bg-primary rounded-full animate-bounce delay-100"></span>
            <span class="w-2 h-2 bg-primary rounded-full animate-bounce delay-200"></span>
            <span>AI đang soạn tin trả lời...</span>
        </div>

        <!-- Input Form -->
        <form id="ai-chat-form" onsubmit="handleAiSubmit(event)" class="p-3 bg-surface-container-lowest border-t border-outline-variant/20 flex items-center gap-2">
            <input type="text" id="ai-user-input" placeholder="Hỏi AI về thực đơn, VietQR, đơn hàng..." required
                class="flex-grow px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 focus:outline-none focus:border-primary text-sm text-on-surface transition-all"/>
            <button type="submit" class="bg-primary text-white p-2.5 rounded-xl hover:bg-primary-container hover:text-on-primary-container active:scale-95 transition-all">
                <span class="material-symbols-outlined text-lg">send</span>
            </button>
        </form>
    </div>
</div>

<script>
    let aiSessionId = localStorage.getItem('ai_chat_session_id') || null;

    function toggleAiChat() {
        const win = document.getElementById('ai-chat-window');
        if (win.classList.contains('hidden')) {
            win.classList.remove('hidden');
            setTimeout(() => {
                win.classList.remove('scale-95', 'opacity-0');
                win.classList.add('scale-100', 'opacity-100');
            }, 10);
        } else {
            win.classList.remove('scale-100', 'opacity-100');
            win.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                win.classList.add('hidden');
            }, 200);
        }
    }

    function sendQuickMessage(msg) {
        document.getElementById('ai-user-input').value = msg;
        handleAiSubmit(new Event('submit'));
    }

    async function handleAiSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('ai-user-input');
        const msg = input.value.trim();
        if (!msg) return;

        appendChatMessage('user', msg);
        input.value = '';

        document.getElementById('ai-typing').classList.remove('hidden');
        scrollAiBottom();

        try {
            const response = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: msg,
                    session_id: aiSessionId
                })
            });

            const data = await response.json();
            document.getElementById('ai-typing').classList.add('hidden');

            if (data.session_id) {
                aiSessionId = data.session_id;
                localStorage.setItem('ai_chat_session_id', aiSessionId);
            }

            if (data.text) {
                appendChatMessage('assistant', data.text);
            } else {
                appendChatMessage('assistant', 'Rất tiếc, AI tạm thời chưa thể phản hồi. Bạn vui lòng thử lại sau.');
            }
        } catch (err) {
            document.getElementById('ai-typing').classList.add('hidden');
            appendChatMessage('assistant', 'Có lỗi kết nối. Bạn vui lòng kiểm tra mạng và thử lại!');
        }
    }

    function appendChatMessage(role, text) {
        const container = document.getElementById('ai-chat-messages');
        const isUser = role === 'user';

        // Convert simple markdown bold & newlines to HTML
        let formattedText = text
            .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>')
            .replace(/\*(.*?)\*/g, '<i>$1</i>')
            .replace(/`(.*?)`/g, '<code class="bg-amber-100 px-1 py-0.5 rounded text-amber-900 font-mono text-xs">$1</code>')
            .replace(/\n/g, '<br/>');

        const wrapper = document.createElement('div');
        wrapper.className = isUser ? 'flex justify-end pt-1' : 'flex items-start gap-2 max-w-[88%] pt-1';

        if (isUser) {
            wrapper.innerHTML = `
                <div class="bg-primary text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-sm max-w-[85%]">
                    ${formattedText}
                </div>
            `;
        } else {
            wrapper.innerHTML = `
                <div class="w-7 h-7 rounded-full bg-primary text-white flex items-center justify-center text-xs flex-shrink-0 mt-1">AI</div>
                <div class="bg-surface-container-lowest p-3 rounded-2xl rounded-tl-none border border-outline-variant/20 shadow-sm text-on-surface leading-relaxed">
                    ${formattedText}
                </div>
            `;
        }

        container.appendChild(wrapper);
        scrollAiBottom();
    }

    function scrollAiBottom() {
        const container = document.getElementById('ai-chat-messages');
        container.scrollTop = container.scrollHeight;
    }
</script>
