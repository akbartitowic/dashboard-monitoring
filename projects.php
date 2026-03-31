<?php
// projects.php
require_once 'config/db.php';
include 'includes/header.php';

// Fetch all projects
$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
?>

<div class="space-y-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-bold font-heading">Project Space</h1>
            <p class="text-zinc-400 mt-2">Manage your homelab projects and microservices</p>
        </div>
        <button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all flex items-center gap-2 shadow-[0_0_15px_rgba(99,102,241,0.3)]">
            <i data-lucide="plus" class="w-5 h-5"></i>
            New Project
        </button>
    </div>

    <!-- Multi-Column Project Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($projects as $project): 
            $status_class = match($project['status']) {
                'active' => 'bg-emerald-500',
                'deploying' => 'bg-blue-500 animate-pulse',
                'error' => 'bg-red-500',
                default => 'bg-zinc-500'
            };
        ?>
        <div class="glass p-6 rounded-2xl border border-accent hover:border-indigo-500/50 transition-all group flex flex-col h-full">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-zinc-800 flex items-center justify-center text-indigo-400">
                        <i data-lucide="folder" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full <?= $status_class ?>"></span>
                    <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider"><?= $project['status'] ?></span>
                </div>
            </div>

            <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-400 transition-colors"><?= htmlspecialchars($project['name']) ?></h3>
            <p class="text-zinc-500 text-sm flex-1 mb-6"><?= htmlspecialchars($project['description']) ?></p>

            <div class="space-y-4 pt-4 border-t border-accent">
                <div class="flex justify-between items-center text-xs text-zinc-500">
                    <span class="flex items-center gap-1.5 font-mono">
                        <i data-lucide="link" class="w-3 h-3"></i>
                        <?= htmlspecialchars($project['subdomain'] ?? 'no-subdomain') ?>
                    </span>
                    <span class="font-mono"><?= date('M d, Y', strtotime($project['last_update'])) ?></span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="vibe_coding.php?id=<?= $project['id'] ?>" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 rounded-xl text-sm font-semibold transition-all">
                        <i data-lucide="zap" class="w-4 h-4 text-orange-400"></i>
                        Vibe Code
                    </a>
                    <button class="flex items-center justify-center gap-2 px-4 py-2.5 bg-accent hover:bg-zinc-800 rounded-xl text-sm font-semibold transition-all">
                        <i data-lucide="terminal" class="w-4 h-4 text-emerald-400"></i>
                        Logs
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
