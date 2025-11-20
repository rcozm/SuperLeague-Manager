<?php
require_once __DIR__ . '/../ass5/db.php';
$c = db();
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Search Player</title>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
</head>
<body>
  <h1>Search Player by Name</h1>

  <form method="get">
    Player name contains: <input name="q" value="<?= esc($q) ?>" id="playersearch">
    <button type="submit">Search</button>
  </form>

  <?php
  if ($q !== '') {
      $sql = "SELECT pl.person_id, pe.full_name
              FROM player pl
              JOIN person pe ON pe.person_id = pl.person_id
              WHERE pe.full_name LIKE CONCAT('%', ?, '%')
              ORDER BY pe.full_name
              LIMIT 50";
      $st = $c->prepare($sql);
      $st->bind_param('s', $q);
      $st->execute();
      $rs = $st->get_result();

      if ($rs->num_rows === 0) {
          echo "<p><em>No players found.</em></p>";
      } else {
          echo "<table border='1' cellspacing='0' cellpadding='4'><tr><th>Name</th></tr>";
          while ($r = $rs->fetch_assoc()) {
              $id = (int)$r['person_id'];
              $name = esc($r['full_name']);
              echo "<tr><td><a href='player_detail.php?id=$id'>$name</a></td></tr>";
          }
          echo "</table>";
      }
  }
  ?>

  <p><a href="../index.html">Back to site</a></p>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
  <script>
    $(function() {
      $("#playersearch").autocomplete({
        source: "../ass9/search_player_auto.php",
        minLength: 1
      });
    });
  </script>
</body>
</html>