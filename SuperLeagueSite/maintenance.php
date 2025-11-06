<?php
require_once __DIR__ . '/auth.php';
?>
<!doctype html>
<meta charset="utf-8">
<title>Maintenance</title>

<h1>SuperLeague — Maintenance</h1>

<?php if (!is_logged_in()): ?>
  <p><b>Authorized access only.</b></p>
  <form method="post" action="login.php">
    <input type="hidden" name="next" value="maintenance.php">
    <p>Username: <input type="text" name="username" autofocus></p>
    <p>Password: <input type="password" name="password"></p>
    <p><button type="submit">Login</button></p>
  </form>
  <p><a href="../index.html">Back to site</a></p>
  <?php exit; ?>
<?php endif; ?>

<p>Signed in as <b><?= htmlspecialchars($_SESSION['user'], ENT_QUOTES) ?></b> —
   <a href="logout.php">Log out</a></p>

<ul>
  <li><strong>Entities (4)</strong>
    <ul>
      <li><a href="add_team.php">Add Team</a></li>
      <li><a href="add_person.php">Add Person</a></li>
      <li><a href="add_player.php">Add Player</a></li>
      <li><a href="add_venue.php">Add Venue</a></li>
    </ul>
  </li>
  <li><strong>Relationships (2)</strong>
    <ul>
      <li><a href="link_player_team.php">Link Player → Team</a></li>
      <li><a href="link_team_venue.php">Link Team → Venue</a></li>
    </ul>
  </li>
</ul>

<p><a href="../index.html">Back to site</a></p>
