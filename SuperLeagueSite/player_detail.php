<?php
require_once __DIR__ . '/db.php';
$c  = db();
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
  echo '<p><b>Error:</b> No valid player ID supplied.</p>';
  echo '<p><a href="search_player.php">Back to player search</a></p>';
  echo '<p><a href="/~rcozmolici/">Back to site</a></p>';
  exit;
}

$sql = "
  SELECT
    pe.full_name,
    pl.position,
    pl.shirt_number,
    t.name  AS team_name,
    t.city  AS team_city
  FROM player pl
  JOIN person pe ON pe.person_id = pl.person_id
  LEFT JOIN team t ON t.team_id = pl.team_id
  WHERE pl.person_id = ?
";
$st = $c->prepare($sql);
$st->bind_param('i', $id);
$st->execute();
$player = $st->get_result()->fetch_assoc();
?>
<!doctype html>
<meta charset="utf-8">
<title>Player Detail</title>
<h1>Player Detail</h1>

<?php if (!$player): ?>
  <p>Player not found.</p>
<?php else: ?>
  <p><b>Name:</b> <?= esc($player['full_name']) ?></p>
  <p><b>Position:</b> <?= esc($player['position'] ?? '') ?></p>
  <p><b>Shirt #:</b> <?= esc($player['shirt_number'] ?? '') ?></p>
  <?php if (!empty($player['team_name'])): ?>
    <p><b>Team:</b> <?= esc($player['team_name']) ?><?= $player['team_city'] ? ' — ' . esc($player['team_city']) : '' ?></p>
  <?php endif; ?>
<?php endif; ?>

<p><a href="search_player.php">Back to player search</a></p>
<p><a href="/~rcozmolici/">Back to site</a></p>