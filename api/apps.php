<?php
// api/apps.php
header('Content-Type: application/json');

function get_running_apps_list() {
    // Get detailed application info
    // We filter for some common patterns and avoid system/background only if needed
    $output = shell_exec("lsappinfo list");
    if (!$output) return [];

    $apps = [];
    $lines = explode("\n", $output);
    $current_app = null;

    // Get usage stats from ps for mapping
    $ps_output = shell_exec("ps -Ao pcpu,pmem,pid");
    $usage_map = [];
    if ($ps_output) {
        $ps_lines = explode("\n", trim($ps_output));
        foreach ($ps_lines as $ps_line) {
            $parts = preg_split('/\s+/', trim($ps_line));
            if (count($parts) >= 3 && is_numeric($parts[2])) {
                $usage_map[$parts[2]] = [
                    'cpu' => $parts[0],
                    'mem' => $parts[1]
                ];
            }
        }
    }

    foreach ($lines as $line) {
        // App name and ASN line: 1) "Finder" ASN:0x0-0x11011:
        if (preg_match('/^\d+\)\s+"([^"]+)"\s+ASN:([^:]+):/', $line, $matches)) {
            if ($current_app) {
                if (isset($current_app['path']) && strpos($current_app['path'], '.app') !== false) {
                    $apps[] = $current_app;
                }
            }
            $current_app = [
                'name' => $matches[1],
                'asn' => $matches[2],
                'bundle_id' => 'Unknown',
                'uptime' => 'N/A',
                'path' => '',
                'pid' => null,
                'cpu' => '0.0',
                'mem' => '0.0'
            ];
        }

        // PID: pid = 585
        if (preg_match('/pid\s*=\s*(\d+)/', $line, $matches)) {
            if ($current_app) {
                $pid = $matches[1];
                $current_app['pid'] = $pid;
                if (isset($usage_map[$pid])) {
                    $current_app['cpu'] = $usage_map[$pid]['cpu'];
                    $current_app['mem'] = $usage_map[$pid]['mem'];
                }
            }
        }

        // Bundle ID: bundleID="com.apple.finder"
        if (preg_match('/bundleID="([^"]+)"/', $line, $matches)) {
            if ($current_app) $current_app['bundle_id'] = $matches[1];
        }

        // Bundle path: bundle path="/System/Library/CoreServices/Finder.app"
        if (preg_match('/bundle path="([^"]+)"/', $line, $matches)) {
            if ($current_app) $current_app['path'] = $matches[1];
        }

        // Uptime/Checkin Time: checkin time = 2026/04/01 11:24:49 ( 32 minutes, 1.87 seconds ago )
        if (preg_match('/checkin time = .*\( (.*) \)/', $line, $matches)) {
            if ($current_app) $current_app['uptime'] = $matches[1];
        }
    }

    // Add last app
    if ($current_app && isset($current_app['path']) && strpos($current_app['path'], '.app') !== false) {
        $apps[] = $current_app;
    }

    // Secondary Filter: Only show apps in /Applications or /System/Library/CoreServices
    // to avoid thousands of small background helper components
    $filtered_apps = array_filter($apps, function($app) {
        $path = $app['path'];
        return (strpos($path, '/Applications/') !== false || strpos($path, '/System/Library/CoreServices/') !== false) 
                && strpos($path, 'Helper') === false;
    });

    return array_values($filtered_apps);
}

// Support for killing an app (if pid was captured - we'll add it in the parse if needed)
// For now, just listing.
echo json_encode(get_running_apps_list());
?>
