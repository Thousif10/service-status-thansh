<?php
// index.php
// Developed by THOUSIF K

// Load services.json
$services = json_decode(file_get_contents("services.json"), true);

// Function to get service status
function get_status($service) {
    $cmd = "systemctl is-active " . escapeshellcmd($service) . " 2>/dev/null";
    $status = trim(shell_exec($cmd));
    return $status ?: "unknown";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Service Management Dashboard</title>
<style>
    body { font-family: Arial, sans-serif; background:#f7f8fc; margin:0; }
    header { background:#222; color:#fff; padding:1rem; font-size:1.5rem; }
    nav { background:#333; color:#fff; padding:1rem; width:200px; height:100vh; float:left; }
    nav a { display:block; color:#fff; margin:10px 0; text-decoration:none; }
    main { margin-left:210px; padding:1rem; }
    table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    th, td { border:1px solid #ddd; padding:10px; text-align:center; }
    th { background:#eee; }
    .running { color:green; font-weight:bold; }
    .stopped { color:red; font-weight:bold; }
    button { padding:5px 10px; margin:2px; border:none; border-radius:4px; cursor:pointer; }
    .start { background:#28a745; color:#fff; }
    .stop { background:#dc3545; color:#fff; }
    .restart { background:#ffc107; color:#000; }
    .logs { background:#007bff; color:#fff; }
</style>
</head>
<body>

<header>Service Management</header>

<nav>
  <a href="#">Dashboard</a>
  <a href="#">Add</a>
  <a href="#">Manage</a>
  <a href="#">Service Details</a>
</nav>

<main>
  <h2>Services</h2>
  <table>
    <tr>
      <th>Service Name</th>
      <th>Display Service</th>
      <th>Description</th>
      <th>Status</th>
      <th>Enable</th>
      <th>Action</th>
    </tr>
    <?php foreach ($services as $unit => $s): 
        $status = get_status($unit);
        $status_class = ($status === "active") ? "running" : "stopped";
    ?>
    <tr>
      <td><?= htmlspecialchars($unit) ?></td>
      <td><?= htmlspecialchars($s['display']) ?></td>
      <td><?= htmlspecialchars($s['desc']) ?></td>
      <td class="<?= $status_class ?>"><?= $status ?></td>
      <td>
        <form method="post" action="toggle_service.php" style="display:inline;">
          <input type="hidden" name="service" value="<?= $unit ?>">
          <button type="submit" class="start">Enable</button>
        </form>
      </td>
      <td>
        <form method="post" action="control_service.php" style="display:inline;">
          <input type="hidden" name="service" value="<?= $unit ?>">
          <button name="action" value="start" class="start">Start</button>
          <button name="action" value="stop" class="stop">Stop</button>
          <button name="action" value="restart" class="restart">Restart</button>
        </form>
        <form method="get" action="get_logs.php" style="display:inline;">
          <input type="hidden" name="service" value="<?= $unit ?>">
          <button class="logs">Logs</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</main>

</body>
</html>
