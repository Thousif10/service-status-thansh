<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method not allowed");
}

$service = $_POST['service'] ?? '';
$action  = $_POST['action'] ?? '';

if ($service === '' || $action === '') {
    http_response_code(400);
    exit("service and action are required");
}

// Validate service Exists in DB (allowlist)
$stmt = $conn->prepare("SELECT service FROM services WHERE service = ? LIMIT 1");
$stmt->bind_param("s", $service);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    http_response_code(400);
    exit("Invalid service");
}
$stmt->close();
$conn->close();

// Whitelist actions
$allowed = ['start','stop','restart','status','enable','disable'];
if (!in_array($action, $allowed, true)) {
    http_response_code(400);
    exit("Invalid action");
}

// Map action to systemctl
$sysAction = $action;
if ($action === 'enable' || $action === 'disable') {
    $cmd = "sudo /bin/systemctl $action " . escapeshellarg($service) . " 2>&1";
} elseif ($action === 'status') {
    $cmd = "sudo /bin/systemctl status " . escapeshellarg($service) . " --no-pager 2>&1";
} else {
    $cmd = "sudo /bin/systemctl $sysAction " . escapeshellarg($service) . " 2>&1";
}

$output = shell_exec($cmd);
echo "Command: $cmd\n\n$output";
