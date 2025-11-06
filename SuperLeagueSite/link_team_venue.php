<?php
require_once __DIR__ . '/auth.php';
require_login(); 
require_once "db.php";
$ok = $err = ""; $teams = []; $venues = [];

try {
    $c = db();
    $teams  = $c->query("SELECT team_id, name FROM team ORDER BY name")->fetch_all(MYSQLI_ASSOC);
    $venues = $c->query("SELECT venue_id, name, city FROM venue ORDER BY name")->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $e) { $err = $e->getMessage(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $team_id  = intval($_POST['team_id'] ?? 0);
        $venue_id = intval($_POST['venue_id'] ?? 0);
        if ($team_id <= 0 || $venue_id <= 0) throw new Exception("Choose both team and venue.");
        $c = db();
        $stmt = $c->prepare("UPDATE team SET venue_id=? WHERE team_id=?");
        $stmt->bind_param("ii", $venue_id, $team_id);
        $stmt->execute();
        $ok = "Linked team to venue successfully.";
    } catch (Throwable $e) { $err = $e->getMessage(); }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Link Team to Venue</title></head>
<body>
<h1>Link Team → Venue</h1>
<form method="post">
  <label>Team
    <select name="team_id" required>
      <option value="">-- choose team --</option>
      <?php foreach ($teams as $t): ?>
        <option value="<?=esc($t['team_id'])?>"><?=esc($t['name'])?></option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>Venue
    <select name="venue_id" required>
      <option value="">-- choose venue --</option>
      <?php foreach ($venues as $v): ?>
        <option value="<?=esc($v['venue_id'])?>"><?=esc($v['name'])?> (<?=esc($v['city'])?>)</option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <button type="submit">Save Link</button>
</form>
<?php if ($ok) echo "<p style='color:green'>".esc($ok)."</p>"; ?>
<?php if ($err) echo "<p style='color:red'>".esc($err)."</p>"; ?>
<p><a href="maintenance.php">Back to maintenance</a></p>
</body></html>
