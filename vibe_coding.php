<?php
// vibe_coding.php
require_once 'config/db.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: projects.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: projects.php');
    exit;
}

include 'includes/header.php';
?>

<div class="h-screen flex flex-col -mt-8 -ml-8 -mr-8 overflow-hidden bg-[#0a0a0c]">
    <!-- Workspace Header -->
    <header class="h-16 flex items-center justify-between px-8 bg-zinc-900 border-b border-white/5">
        <div class="flex items-center gap-6">
            <a href="projects.php" class="p-2 hover:bg-zinc-800 rounded-lg transition-colors text-zinc-400">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="h-8 w-[1px] bg-white/10"></div>
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                <h2 class="text-lg font-bold font-heading"><?= htmlspecialchars($project['name']) ?></h2>
                <span class="text-xs font-mono text-zinc-500 bg-white/5 px-2 py-0.5 rounded uppercase">vibe-space v1.0</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-3 py-1.5 bg-indigo-600/10 text-indigo-400 rounded-lg border border-indigo-500/20 text-xs font-semibold">
                <i data-lucide="github" class="w-3.5 h-3.5"></i>
                Synced: main
            </div>
            <button id="deploy-btn" class="px-6 py-2 bg-white text-black rounded-lg font-bold hover:bg-zinc-200 transition-all text-sm flex items-center gap-2">
                Deploy
                <i data-lucide="play" class="w-4 h-4 fill-black"></i>
            </button>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
        <!-- Vibe Chat / Editor Interface -->
        <main class="flex-1 flex flex-col p-8 bg-[#09090b] relative overflow-y-auto">
            <div class="max-w-4xl mx-auto w-full space-y-10">
                <div class="space-y-4">
                    <h2 class="text-3xl font-bold font-heading text-zinc-200">What's the vibe today?</h2>
                    <p class="text-zinc-500 text-lg">Describe the changes you want to apply to <span class="text-zinc-300 font-medium">/<?= basename($project['local_path']) ?></span></p>
                </div>

                <!-- Command / Prompt Input -->
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                    <textarea id="vibe-input" rows="4" 
                        placeholder="e.g., Add a dark mode toggle to the login page and center the hero section..." 
                        class="relative w-full bg-zinc-900 border border-white/10 rounded-2xl p-6 text-lg text-zinc-200 placeholder:text-zinc-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 leading-relaxed"></textarea>
                    
                    <div class="absolute right-4 bottom-4 flex items-center gap-2">
                        <span class="text-[10px] font-mono text-zinc-500 uppercase tracking-widest mr-4">Press ⌘+Enter to Vibe</span>
                        <button class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all flex items-center gap-2 shadow-lg">
                            Apply Vibe
                            <i data-lucide="zap" class="w-4 h-4 fill-white"></i>
                        </button>
                    </div>
                </div>

                <!-- Simulation/History Feed -->
                <div class="space-y-6 pt-10">
                    <h4 class="text-xs font-bold text-zinc-600 uppercase tracking-[0.2em] flex items-center gap-2">
                        <i data-lucide="history" class="w-3 h-3"></i>
                        Vibe History
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="p-6 bg-zinc-900/50 border border-white/5 rounded-2xl flex gap-6 group hover:bg-zinc-800/50 transition-colors">
                            <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-xl border border-emerald-500/20 h-fit">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div class="flex-1 space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-bold text-zinc-200 uppercase text-xs tracking-wider">Applied Fix</span>
                                    <span class="text-xs font-mono text-zinc-600">2h ago</span>
                                </div>
                                <p class="text-zinc-400">Fixed the sidebar overlapping on mobile views. Added responsive breakpoints to the layout.php file.</p>
                                <div class="flex items-center gap-4 pt-2">
                                    <span class="text-xs font-mono text-emerald-500"><i data-lucide="file-code" class="w-3 h-3 inline mr-1"></i> layout.php</span>
                                    <span class="text-xs font-mono text-emerald-500"><i data-lucide="git-commit" class="w-3 h-3 inline mr-1"></i> commit: a3f9b2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Right Side: Terminal / Logs -->
        <aside class="w-[420px] bg-black border-l border-white/5 p-6 flex flex-col font-mono text-sm">
            <div class="flex items-center justify-between mb-6 text-zinc-500">
                <span class="flex items-center gap-2 uppercase tracking-widest text-[10px] font-bold">
                    <i data-lucide="terminal" class="w-3.5 h-3.5"></i>
                    Terminal Output
                </span>
                <span class="text-[10px] text-emerald-500 font-bold">online</span>
            </div>

            <div id="terminal-output" class="flex-1 space-y-1.5 overflow-y-auto scrollbar-none text-zinc-300 leading-relaxed">
                <div class="text-zinc-500">Initializing workspace...</div>
                <div class="text-zinc-500">CD <?= htmlspecialchars($project['local_path']) ?></div>
                <div class="text-emerald-400 font-bold">➜ [SUCCESS] Ready for input.</div>
                <div class="text-zinc-600 pt-4 cursor-default">Waiting for vibe coding session to start...</div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-white/10 space-y-4">
                <h4 class="text-[10px] font-bold text-zinc-600 uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                    System Health
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between text-xs">
                        <span class="text-zinc-500 uppercase tracking-widest">CPU LOAD</span>
                        <span class="text-zinc-200">12.4%</span>
                    </div>
                    <div class="w-full bg-zinc-900 h-1 rounded-full overflow-hidden">
                        <div class="bg-indigo-500 h-full w-[12.4%]"></div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
// Mocking deployment logic
document.getElementById('deploy-btn').addEventListener('click', function() {
    const term = document.getElementById('terminal-output');
    this.disabled = true;
    this.innerText = 'Deploying...';
    
    term.innerHTML += `<div class="text-blue-400 mt-4">➜ Starting deployment...</div>`;
    term.innerHTML += `<div class="text-zinc-400 font-mono">GIT PULL origin main...</div>`;
    
    setTimeout(() => {
        term.innerHTML += `<div class="text-zinc-400 font-mono">COMPOSER INSTALL --quiet...</div>`;
        term.scrollTop = term.scrollHeight;
    }, 1000);
    
    setTimeout(() => {
        term.innerHTML += `<div class="text-emerald-400 font-bold mt-2">➜ [DONE] Build success. Re-mapping subdomains.</div>`;
        this.disabled = false;
        this.innerHTML = 'Deploy <i data-lucide="play" class="w-4 h-4 fill-black"></i>';
        lucide.createIcons();
        term.scrollTop = term.scrollHeight;
    }, 3000);
});
</script>

<?php include 'includes/footer.php'; ?>
