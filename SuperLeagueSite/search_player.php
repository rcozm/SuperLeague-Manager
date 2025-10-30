<?php
require_once __DIR__ . '/db.php';
$c = db();
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
?>
<!doctype html>
<meta charset="utf-8">
<title>Search Player</title>
<h1>Search Player by Name</h1>

<form method="get">
  Player name contains:
  <input type="text" name="q" value="<?= esc($q) ?>">
  <button type="submit">Search</button>
</form>

<p><a href="/~rcozmolici/">Back to site</a></p>
<?php if ($q !== ''): ?>
  <p><a href="search_player.php">New search</a></p>
<?php endif; ?>

<?php
if ($q !== '') {
  $sql = "
    SELECT pl.person_id, pe.full_name
    FROM player pl
    JOIN person pe ON pe.person_id = pl.person_id
    WHERE pe.full_name LIKE CONCAT('%', ?, '%')
    ORDER BY pe.full_name
    LIMIT 50
  ";

  $st = $c->prepare($sql);
  $st->bind_param('s', $q);
  $st->execute();
  $rs = $st->get_result();

  if ($rs->num_rows === 0) {
    echo '<p><em>No players found.</em></p>';
  } else {
    echo '<table border="1" cellspacing="0" cellpadding="4">';
    echo '<tr><th>Name</th></tr>';
    while ($r = $rs->fetch_assoc()) {
      $name = esc($r['full_name']);
      $id   = (int)$r['person_id'];
      echo "<tr><td><a href='player_detail.php?id=$id'>$name</a></td></tr>";
    }
    echo '</table>';
  }
}
?>