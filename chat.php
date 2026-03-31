<?php
// chat.php
require_once 'config/db.php';
include 'includes/header.php';

// Fetch agents from DB
$agents = $pdo->query("SELECT * FROM agents")->fetchAll();
?>

<div class="flex flex-col h-[calc(100vh-8rem)] max-w-5xl mx-auto">
    <!-- Chat Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold font-heading">AI Agent Hub</h1>
            <p class="text-zinc-400">Interact with your local or remote LLM agents</p>
        </div>
        <div class="flex items-center gap-4">
            <select id="agent-selector" class="bg-zinc-900 border border-accent rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <?php foreach ($agents as $agent): ?>
                    <option value="<?= $agent['id'] ?>"><?= htmlspecialchars($agent['name']) ?> (<?= htmlspecialchars($agent['model_name']) ?>)</option>
                <?php endforeach; ?>
            </select>
            <button class="p-2 hover:bg-zinc-800 rounded-lg transition-colors text-zinc-400">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Chat Messages Container -->
    <div id="chat-container" class="flex-1 glass border border-accent rounded-2xl p-6 overflow-y-auto space-y-6 mb-4 scrollbar-thin scrollbar-thumb-zinc-700">
        <!-- AI Welcome Message -->
        <div class="flex gap-4">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shrink-0">
                <i data-lucide="sparkles" class="w-4 h-4 text-white"></i>
            </div>
            <div class="space-y-2 max-w-[80%]">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Gemini Pro • Now</div>
                <div class="bg-zinc-800/50 p-4 rounded-2xl rounded-tl-none border border-zinc-700/50 leading-relaxed">
                    Hello! I am your Homelab assistant. How can I help you with your server status or project deployments today?
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Input Area -->
    <div class="relative">
        <textarea id="chat-input" rows="1" placeholder="Type your message here..." 
            class="w-full glass border border-accent rounded-2xl px-6 py-4 pr-32 focus:ring-2 focus:ring-indigo-500 outline-none resize-none min-h-[64px] max-h-48 leading-relaxed"></textarea>
        
        <div class="absolute right-4 bottom-4 flex items-center gap-2">
            <button id="send-btn" class="px-6 py-2 bg-white text-black rounded-xl font-bold hover:bg-zinc-200 transition-all flex items-center gap-2">
                Send
                <i data-lucide="send" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>

<script>
const chatBox = document.getElementById('chat-container');
const input = document.getElementById('chat-input');
const sendBtn = document.getElementById('send-btn');

function appendMessage(role, text, name = 'You') {
    const isUser = role === 'user';
    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    
    const html = `
        <div class="flex gap-4 ${isUser ? 'flex-row-reverse' : ''}">
            <div class="w-8 h-8 rounded-lg ${isUser ? 'bg-zinc-700' : 'bg-indigo-600'} flex items-center justify-center shrink-0">
                <i data-lucide="${isUser ? 'user' : 'sparkles'}" class="w-4 h-4 text-white"></i>
            </div>
            <div class="space-y-2 max-w-[80%] ${isUser ? 'text-right' : ''}">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">${name} • ${time}</div>
                <div class="${isUser ? 'bg-indigo-600/20 border-indigo-500/20' : 'bg-zinc-800/50 border-zinc-700/50'} p-4 rounded-2xl ${isUser ? 'rounded-tr-none' : 'rounded-tl-none'} border leading-relaxed">
                    ${text}
                </div>
            </div>
        </div>
    `;
    
    chatBox.insertAdjacentHTML('beforeend', html);
    lucide.createIcons();
    chatBox.scrollTop = chatBox.scrollHeight;
}

sendBtn.addEventListener('click', () => {
    const msg = input.value.trim();
    if(!msg) return;
    
    appendMessage('user', msg);
    input.value = '';
    
    // Simulate AI typing
    const typingHtml = `
        <div id="typing" class="flex gap-4">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shrink-0">
                <i data-lucide="sparkles" class="w-4 h-4 text-white"></i>
            </div>
            <div class="space-y-2">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">AI is typing...</div>
                <div class="bg-zinc-800/50 p-4 rounded-2xl rounded-tl-none border border-zinc-700/50 flex gap-1">
                    <span class="w-1.5 h-1.5 bg-zinc-500 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-zinc-500 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1.5 h-1.5 bg-zinc-500 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                </div>
            </div>
        </div>
    `;
    chatBox.insertAdjacentHTML('beforeend', typingHtml);
    chatBox.scrollTop = chatBox.scrollHeight;
    
    // Simulate Response
    setTimeout(() => {
        document.getElementById('typing').remove();
        appendMessage('assistant', "I've analyzed your project 'Dashboard Project'. The latest push was 2 hours ago. Everything seems to be running smoothly on the server.", 'Gemini Pro');
    }, 1500);
});

// Auto-expand textarea
input.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});
</script>

<?php include 'includes/footer.php'; ?>
