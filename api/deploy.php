<?php
// deploy.php
// GitHub Webhook Handler for Auto-Updates

require_once 'config/db.php';

// Post Data from GitHub
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// [OPTIONAL] Verify signature here with your secret
// if (!$signature || !verify_signature($payload, $signature)) { die('Unauthorized'); }

$data = json_decode($payload, true);
if (!$data) {
    die('Invalid payload');
}

$repo_name = $data['repository']['full_name'] ?? '';
$branch = str_replace('refs/heads/', '', $data['ref'] ?? '');

if ($repo_name && $branch === 'main') {
    // Find project in DB
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE github_repo LIKE ?");
    $stmt->execute(['%' . $repo_name . '%']);
    $project = $stmt->fetch();

    if ($project) {
        $path = $project['local_path'];
        
        // Update status to deploying
        $upd = $pdo->prepare("UPDATE projects SET status = 'deploying' WHERE id = ?");
        $upd->execute([$project['id']]);

        // Run Deployment
        $output = [];
        $cmd = "cd " . escapeshellarg($path) . " && git pull origin main 2>&1";
        exec($cmd, $output);
        
        // Log output (you can store this in system_logs if needed)
        $log_content = implode("\n", $output);
        
        // Final Update
        $final_status = (strpos($log_content, 'error') === false) ? 'active' : 'error';
        $upd = $pdo->prepare("UPDATE projects SET status = ?, last_update = CURRENT_TIMESTAMP WHERE id = ?");
        $upd->execute([$final_status, $project['id']]);

        http_response_code(200);
        echo "Deployment complete for $repo_name on branch $branch";
    } else {
        die('Project not found in dashboard database');
    }
} else {
    die('Only main branch push triggers deployment');
}

function verify_signature($payload, $signature) {
    $secret = 'YOUR_SECRET_HERE'; // Add this to your project settings
    $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);
    return hash_equals($hash, $signature);
}
?>
