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

        <!-- Running Apps Card -->
        <div class="glass p-6 rounded-2xl border border-accent hover:border-zinc-700 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl">
                    <i data-lucide="layout-grid" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-semibold text-emerald-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Live
                </span>
            </div>
            <h3 class="text-zinc-400 text-sm font-medium">Running Apps</h3>
            <div class="flex items-baseline gap-2 mt-1">
                <span id="apps-val" class="text-3xl font-bold font-heading">--</span>
                <span class="text-zinc-500 text-sm">Active</span>
            </div>
            <div class="w-full bg-zinc-800 h-1.5 rounded-full mt-4 overflow-hidden">
                <div id="apps-bar" class="bg-emerald-500 h-full transition-all duration-500" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Active Applications Table -->
    <div class="glass rounded-2xl border border-accent overflow-hidden">
        <div class="p-6 border-b border-accent flex justify-between items-center">
            <h2 class="text-xl font-bold font-heading">Active Applications</h2>
            <div class="flex items-center gap-2">
                <span class="text-xs text-zinc-500">Live Updates</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="apps-table">
                <thead>
                    <tr class="bg-zinc-900/50 text-zinc-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">App Name</th>
                        <th class="px-6 py-4 font-medium">CPU %</th>
                        <th class="px-6 py-4 font-medium">RAM %</th>
                        <th class="px-6 py-4 font-medium">Uptime</th>
                        <th class="px-6 py-4 font-medium text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50" id="apps-list">
                    <!-- Dynamic Content -->
                    <tr class="animate-pulse">
                        <td colspan="4" class="px-6 py-12 text-center text-zinc-500">Loading applications...</td>
                    </tr>
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
        if (document.getElementById('apps-val')) {
            document.getElementById('apps-val').innerText = data.running_apps;
        }
        if (document.getElementById('apps-bar')) {
            const percentage = Math.min((data.running_apps / 30) * 100, 100);
            document.getElementById('apps-bar').style.width = percentage + '%';
        }
        
        // Update bars
        document.getElementById('cpu-bar').style.width = data.cpu + '%';
        document.getElementById('ram-bar').style.width = data.ram + '%';
        document.getElementById('temp-bar').style.width = (data.temp / 100 * 100) + '%';
        
        document.getElementById('last-update').innerText = 'Last Update: ' + new Date().toLocaleTimeString();
    } catch (e) {
        console.error('Failed to fetch stats:', e);
    }
}

async function updateAppsList() {
    try {
        const response = await fetch('api/apps.php');
        const apps = await response.json();
        const list = document.getElementById('apps-list');
        
        if (!list) return;

        if (apps.length === 0) {
            list.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-zinc-500">No active applications found.</td></tr>`;
            return;
        }

        list.innerHTML = apps.map(app => `
            <tr class="hover:bg-zinc-800/30 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></div>
                        <span class="font-medium text-sm text-zinc-100">${app.name}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-xs font-mono">
                    <span class="${parseFloat(app.cpu) > 10 ? 'text-orange-400' : 'text-zinc-400'}">${app.cpu}%</span>
                </td>
                <td class="px-6 py-4 text-xs font-mono">
                    <span class="${parseFloat(app.mem) > 5 ? 'text-purple-400' : 'text-zinc-400'}">${app.mem}%</span>
                </td>
                <td class="px-6 py-4 text-xs text-zinc-500">
                    ${app.uptime}
                </td>
                <td class="px-6 py-4 text-right">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold text-emerald-400 bg-emerald-400/10 uppercase tracking-tighter">
                        Active
                    </span>
                </td>
            </tr>
        `).join('');
    } catch (e) {
        console.error('Failed to fetch apps:', e);
    }
}

// Update stats every 3 seconds
setInterval(updateStats, 3000);
updateStats();

// Update apps every 10 seconds
setInterval(updateAppsList, 10000);
updateAppsList();
</script>

<?php include 'includes/footer.php'; ?>
