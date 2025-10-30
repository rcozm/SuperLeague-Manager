<?php
require_once __DIR__ . '/../ass5/db.php';
$conn = db();

$team_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($team_id <= 0) {
  http_response_code(400);
  exit('Invalid team id.');
}

$sqlTeam = "SELECT team_id, name, city
            FROM team
            WHERE team_id = ?";
$stmt = $conn->prepare($sqlTeam);
$stmt->bind_param('i', $team_id);
$stmt->execute();
$team = $stmt->get_result()->fetch_assoc();

if (!$team) {
  http_response_code(404);
  exit('Team not found.');
}

$sqlHomeVenue = "SELECT v.venue_id, v.name, v.city, COUNT(*) AS uses_cnt
                 FROM `match` m
                 JOIN venue v ON v.venue_id = m.venue_id
                 WHERE m.home_team_id = ?
                 GROUP BY v.venue_id, v.name, v.city
                 ORDER BY uses_cnt DESC
                 LIMIT 1";
$stmt = $conn->prepare($sqlHomeVenue);
$stmt->bind_param('i', $team_id);
$stmt->execute();
$favoriteVenue = $stmt->get_result()->fetch_assoc();

$sqlMatches = "SELECT m.match_id, m.date_time, m.round_no,
                      th.team_id AS home_id, th.name AS home_name,
                      ta.team_id AS away_id, ta.name AS away_name,
                      COALESCE(m.home_goals, 0) AS home_goals,
                      COALESCE(m.away_goals, 0) AS away_goals,
                      v.name AS venue_name, v.city AS venue_city
               FROM `match` m
               JOIN team th ON th.team_id = m.home_team_id
               JOIN team ta ON ta.team_id = m.away_team_id
               LEFT JOIN venue v ON v.venue_id = m.venue_id
               WHERE m.home_team_id = ? OR m.away_team_id = ?
               ORDER BY m.date_time DESC
               LIMIT 10";
$stmt = $conn->prepare($sqlMatches);
$stmt->bind_param('ii', $team_id, $team_id);
$stmt->execute();
$matches = $stmt->get_result();
?>
<!doctype html>
<meta charset="utf-8">
<title>Team Detail</title>

<h1>Team Detail</h1>

<h2><?= esc($team['name']) ?></h2>
<p><strong>City:</strong> <?= esc($team['city']) ?></p>

<?php if ($favoriteVenue): ?>
  <p><strong>Typical home venue:</strong>
     <?= esc($favoriteVenue['name']) ?> (<?= esc($favoriteVenue['city']) ?>)</p>
<?php endif; ?>

<h3>Recent Matches</h3>
<?php if ($matches->num_rows === 0): ?>
  <p>No matches found for this team.</p>
<?php else: ?>
  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <th>Date/Time</th>
      <th>Round</th>
      <th>Home</th>
      <th>Away</th>
      <th>Score</th>
      <th>Venue</th>
    </tr>
    <?php while ($m = $matches->fetch_assoc()): ?>
      <tr>
        <td><?= esc($m['date_time']) ?></td>
        <td><?= esc($m['round_no']) ?></td>
        <td><?= esc($m['home_name']) ?></td>
        <td><?= esc($m['away_name']) ?></td>
        <td><?= esc($m['home_goals']) ?> : <?= esc($m['away_goals']) ?></td>
        <td><?= esc($m['venue_name']) ?><?= $m['venue_city'] ? ' — '.esc($m['venue_city']) : '' ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php endif; ?>

<p><a href="search_team.php">Back to team search</a></p>
<p><a href="/~rcozmolici/">Back to site</a></p>