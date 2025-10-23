<?php require_once "db.php";
$ok = $err = ""; $players = []; $teams = [];
try {
    $c = db();
    $players = $c->query("
      SELECT pl.person_id, pe.full_name, t.name AS team_name
      FROM player pl
      JOIN person pe ON pe.person_id = pl.person_id
      LEFT JOIN team t ON t.team_id = pl.team_id
      ORDER BY pe.full_name")->fetch_all(MYSQLI_ASSOC);
    $teams   = $c->query("SELECT team_id, name FROM team ORDER BY name")->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $e) { $err = $e->getMessage(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $person_id = intval($_POST['person_id'] ?? 0);
        $team_id   = intval($_POST['team_id'] ?? 0);
        if ($person_id<=0 || $team_id<=0) throw new Exception("Choose both player and team.");
        $c = db();
        $stmt = $c->prepare("UPDATE player SET team_id=? WHERE person_id=?");
        $stmt->bind_param("ii", $team_id, $person_id);
        $stmt->execute();
        $ok = "Player reassigned.";
    } catch (Throwable $e) { $err = $e->getMessage(); }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Link Player to Team</title></head>
<body>
<h1>Link Player → Team</h1>
<form method="post">
  <label>Player
    <select name="person_id" required>
      <option value="">-- choose --</option>
      <?php foreach ($players as $p): ?>
        <option value="<?=esc($p['person_id'])?>">
          <?=esc($p['full_name'])?> <?=($p['team_name'] ? " (now: ".esc($p['team_name']).")" : "")?>
        </option>
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
  <button type="submit">Save Link</button>
</form>
<?php if ($ok) echo "<p style='color:green'>".esc($ok)."</p>"; ?>
<?php if ($err) echo "<p style='color:red'>".esc($err)."</p>"; ?>
<p><a href="maintenance.html">Back to maintenance</a></p>
</body></html>
