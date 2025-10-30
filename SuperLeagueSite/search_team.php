<?php
require_once __DIR__ . '/../ass5/db.php';
$conn = db();

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

?>
<!doctype html>
<meta charset="utf-8">
<title>Search Team</title>
<h1>Search Team by Name</h1>

<form method="get">
  Team name contains: <input name="q" value="<?= esc($q) ?>">
  <button type="submit">Search</button>
</form>

<?php
if ($q !== '') {
  $sql = "SELECT team_id, name, city
          FROM team
          WHERE name LIKE CONCAT('%', ?, '%')
          ORDER BY name";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $q);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 0) {
    echo "<p>No teams found.</p>";
  } else {
    echo '<table border="1" cellpadding="4" cellspacing="0"><tr><th>Name</th><th>City</th></tr>';
    while ($r = $res->fetch_assoc()) {
      $link = 'team_detail.php?id=' . (int)$r['team_id'];
      echo '<tr>';
      echo '<td><a href="' . $link . '">' . esc($r['name']) . '</a></td>';
      echo '<td>' . esc($r['city']) . '</td>';
      echo '</tr>';
    }
    echo '</table>';
  }
}
?>
<p><a href="/~rcozmolici/">Back to site</a></p>