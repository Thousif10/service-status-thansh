<?php
if (!isset($_POST['service']) || !isset($_POST['action'])) {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

$service = escapeshellcmd($_POST['service']);
$action = escapeshellcmd($_POST['action']);

// Only allow specific actions
$allowed = ['start', 'stop', 'restart', 'status'];
if (!in_array($action, $allowed)) {
    http_response_code(400);
    echo "Invalid action";
    exit;
}

$output = shell_exec("sudo systemctl $action $service 2>&1");
echo nl2br($output);
