<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="fixed top-0 left-0 h-screen w-64 glass z-40 border-r border-accent p-4 py-8">
    <div class="flex items-center gap-3 px-4 mb-10">
        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[#09090b]">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        </div>
        <h2 class="text-xl font-bold font-heading tracking-tight">Homelab Agent</h2>
    </div>

    <nav class="space-y-1">
        <a href="index.php" class="flex items-center gap-3 px-4 py-2.5 rounded-md transition-colors <?= ($current_page == 'index.php') ? 'sidebar-item-active' : 'text-zinc-400 hover:text-white' ?>">
            <i data-lucide="home" class="w-4 h-4"></i>
            <span class="text-sm font-medium">Dashboard</span>
        </a>
    </nav>

    <div class="absolute bottom-8 left-0 w-full px-4 pt-10">
        <div class="bg-accent/50 p-4 rounded-xl border border-accent">
            <h4 class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">System Status</h4>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-medium text-emerald-500">All systems operational</span>
            </div>
        </div>
    </div>
</aside>
