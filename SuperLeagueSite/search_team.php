<?php
require_once __DIR__ . '/../ass5/db.php';
$conn = db();
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Search Team</title>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
</head>
<body>
  <h1>Search Team by Name</h1>

  <form method="get">
    Team name contains: <input name="q" value="<?= esc($q) ?>" id="teamsearch">
    <button type="submit">Search</button>
  </form>

  <?php
  if ($q !== '') {
      $sql = "SELECT team_id, name, city FROM team WHERE name LIKE CONCAT('%', ?, '%') ORDER BY name";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('s', $q);
      $stmt->execute();
      $res = $stmt->get_result();

      if ($res->num_rows === 0) {
          echo "<p>No teams found.</p>";
      } else {
          echo "<table border='1' cellpadding='4' cellspacing='0'><tr><th>Name</th><th>City</th></tr>";
          while ($r = $res->fetch_assoc()) {
              $link = "team_detail.php?id=" . (int)$r['team_id'];
              echo "<tr><td><a href='$link'>" . esc($r['name']) . "</a></td><td>" . esc($r['city']) . "</td></tr>";
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
      $("#teamsearch").autocomplete({
        source: "../ass9/search_team_auto.php",
        minLength: 1
      });
    });
  </script>
</body>
</html>