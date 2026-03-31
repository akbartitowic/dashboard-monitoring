<?php
// index.php
require_once 'config/db.php';
include 'includes/header.php';
?>

<div class="space-y-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-bold font-heading">Server Health</h1>
            <p class="text-zinc-400 mt-2">Real-time monitoring of your homelab environment</p>
        </div>
        <div class="text-right">
            <span id="last-update" class="text-xs font-mono text-zinc-500 uppercase tracking-widest">Last Update: Just Now</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- CPU Card -->
        <div class="glass p-6 rounded-2xl border border-accent hover:border-zinc-700 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-indigo-500/10 text-indigo-400 rounded-xl">
                    <i data-lucide="cpu" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-semibold text-emerald-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Live
                </span>
            </div>
            <h3 class="text-zinc-400 text-sm font-medium">CPU Usage</h3>
            <div class="flex items-baseline gap-2 mt-1">
                <span id="cpu-val" class="text-3xl font-bold font-heading">--</span>
                <span class="text-zinc-500 text-sm">%</span>
            </div>
            <div class="w-full bg-zinc-800 h-1.5 rounded-full mt-4 overflow-hidden">
                <div id="cpu-bar" class="bg-indigo-500 h-full transition-all duration-500" style="width: 0%"></div>
            </div>
        </div>

        <!-- RAM Card -->
        <div class="glass p-6 rounded-2xl border border-accent hover:border-zinc-700 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-purple-500/10 text-purple-400 rounded-xl">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-zinc-500">Volatile</span>
            </div>
            <h3 class="text-zinc-400 text-sm font-medium">RAM Usage</h3>
            <div class="flex items-baseline gap-2 mt-1">
                <span id="ram-val" class="text-3xl font-bold font-heading">--</span>
                <span class="text-zinc-500 text-sm">%</span>
            </div>
            <div class="w-full bg-zinc-800 h-1.5 rounded-full mt-4 overflow-hidden">
                <div id="ram-bar" class="bg-purple-500 h-full transition-all duration-500" style="width: 0%"></div>
            </div>
        </div>

        <!-- Temp Card -->
        <div class="glass p-6 rounded-2xl border border-accent hover:border-zinc-700 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-orange-500/10 text-orange-400 rounded-xl">
                    <i data-lucide="thermometer" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-zinc-500">Core</span>
            </div>
            <h3 class="text-zinc-400 text-sm font-medium">Temperature</h3>
            <div class="flex items-baseline gap-2 mt-1">
                <span id="temp-val" class="text-3xl font-bold font-heading">--</span>
                <span class="text-zinc-500 text-sm">°C</span>
            </div>
            <div class="w-full bg-zinc-800 h-1.5 rounded-full mt-4 overflow-hidden">
                <div id="temp-bar" class="bg-orange-500 h-full transition-all duration-500" style="width: 0%"></div>
            </div>
        </div>

        <!-- Projects Card -->
        <div class="glass p-6 rounded-2xl border border-accent hover:border-zinc-700 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-blue-500/10 text-blue-400 rounded-xl">
                    <i data-lucide="folder-kanban" class="w-6 h-6"></i>
                </div>
                <a href="projects.php" class="text-xs text-blue-400 hover:underline">Manage</a>
            </div>
            <h3 class="text-zinc-400 text-sm font-medium">Total Projects</h3>
            <div class="flex items-baseline gap-2 mt-1">
                <span id="projects-val" class="text-3xl font-bold font-heading">--</span>
                <span class="text-zinc-500 text-sm">Active</span>
            </div>
            <div class="mt-4 flex -space-x-2">
                <div class="w-8 h-8 rounded-full border-2 border-[#09090b] bg-zinc-800"></div>
                <div class="w-8 h-8 rounded-full border-2 border-[#09090b] bg-zinc-700"></div>
                <div class="w-8 h-8 rounded-full border-2 border-[#09090b] bg-zinc-600 flex items-center justify-center text-[10px]">+</div>
            </div>
        </div>
    </div>

    <!-- Active Projects Table -->
    <div class="glass rounded-2xl border border-accent overflow-hidden">
        <div class="p-6 border-b border-accent flex justify-between items-center">
            <h2 class="text-xl font-bold font-heading">Active Projects</h2>
            <button class="px-4 py-2 bg-white text-black rounded-lg text-sm font-semibold hover:bg-zinc-200 transition-colors">
                New Project
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-zinc-900/50 text-zinc-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">Name</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Domain</th>
                        <th class="px-6 py-4 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM projects ORDER BY last_update DESC LIMIT 5");
                    while ($row = $stmt->fetch()) {
                        $status_color = match($row['status']) {
                            'active' => 'text-emerald-400 bg-emerald-400/10',
                            'deploying' => 'text-blue-400 bg-blue-400/10 animate-pulse',
                            default => 'text-zinc-400 bg-zinc-400/10'
                        };
                    ?>
                    <tr class="hover:bg-zinc-800/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></div>
                                <span class="font-medium"><?= htmlspecialchars($row['name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $status_color ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-400 font-mono">
                            <?= htmlspecialchars($row['subdomain']) ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="vibe_coding.php?id=<?= $row['id'] ?>" class="text-xs font-semibold text-white/50 hover:text-white transition-colors flex items-center justify-end gap-2">
                                Vibe Code
                                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Simple Refresh Logic -->
<script>
async function updateStats() {
    try {
        const response = await fetch('api/stats.php');
        const data = await response.json();
        
        // Update values
        document.getElementById('cpu-val').innerText = data.cpu;
        document.getElementById('ram-val').innerText = data.ram;
        document.getElementById('temp-val').innerText = data.temp;
        document.getElementById('projects-val').innerText = data.projects;
        
        // Update bars
        document.getElementById('cpu-bar').style.width = data.cpu + '%';
        document.getElementById('ram-bar').style.width = data.ram + '%';
        document.getElementById('temp-bar').style.width = (data.temp / 100 * 100) + '%';
        
        document.getElementById('last-update').innerText = 'Last Update: ' + new Date().toLocaleTimeString();
    } catch (e) {
        console.error('Failed to fetch stats:', e);
    }
}

// Update every 3 seconds
setInterval(updateStats, 3000);
updateStats();
</script>

<?php include 'includes/footer.php'; ?>
