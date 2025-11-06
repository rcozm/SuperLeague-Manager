<?php
require_once __DIR__ . '/auth.php';
require_login(); 
require_once "db.php";
$ok = $err = ""; $persons = []; $teams = [];
try {
    $c = db();
    $persons = $c->query("SELECT person_id, full_name FROM person ORDER BY full_name")->fetch_all(MYSQLI_ASSOC);
    $teams   = $c->query("SELECT team_id, name FROM team ORDER BY name")->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $e) { $err = $e->getMessage(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $person_id = intval($_POST['person_id'] ?? 0);
        $team_id   = intval($_POST['team_id'] ?? 0);
        $position  = trim($_POST['position'] ?? "");
        $shirt     = intval($_POST['shirt_number'] ?? 0);
        if ($person_id<=0 || $team_id<=0 || $position==="" || $shirt<=0) throw new Exception("All fields required.");
        $c = db();
        $stmt = $c->prepare("INSERT INTO player (person_id, team_id, position, shirt_number) VALUES (?,?,?,?)");
        $stmt->bind_param("iisi", $person_id, $team_id, $position, $shirt);
        $stmt->execute();
        $ok = "Player inserted for person_id $person_id.";
    } catch (Throwable $e) { $err = $e->getMessage(); }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Add Player</title></head>
<body>
<h1>Add Player</h1>
<form method="post">
  <label>Person
    <select name="person_id" required>
      <option value="">-- choose --</option>
      <?php foreach ($persons as $p): ?>
        <option value="<?=esc($p['person_id'])?>"><?=esc($p['full_name'])?> (id <?=esc($p['person_id'])?>)</option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>Team
    <select name="team_id" required>
      <option value="">-- choose --</option>
      <?php foreach ($teams as $t): ?>
        <option value="<?=esc($t['team_id'])?>"><?=esc($t['name'])?></option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>Position <input name="position" required placeholder="FW / MF / DF / GK"></label><br>
  <label>Shirt number <input type="number" name="shirt_number" required></label><br>
  <button type="submit">Insert Player</button>
</form>
<?php if ($ok) echo "<p style='color:green'>".esc($ok)."</p>"; ?>
<?php if ($err) echo "<p style='color:red'>".esc($err)."</p>"; ?>
<p><a href="maintenance.php">Back to maintenance</a></p>
</body></html>
