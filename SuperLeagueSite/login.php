<?php
require_once __DIR__ . '/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$next = isset($_GET['next']) ? $_GET['next'] : 'maintenance.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $next = $_POST['next'] ?? 'maintenance.php';
  $u = trim($_POST['username'] ?? '');
  $p = $_POST['password'] ?? '';

  if ($u === '' || $p === '') {
    $error = 'Please enter username and password.';
  } else {
    $c = db();
    $st = $c->prepare('SELECT password_hash FROM user_account WHERE username = ?');
    $st->bind_param('s', $u);
    $st->execute();
    $rs = $st->get_result();
    $row = $rs->fetch_assoc();

    if ($row && password_verify($p, $row['password_hash'])) {
      session_regenerate_id(true);
      $_SESSION['user'] = $u;
      header("Location: {$next}");
      exit;
    } else {
      $error = 'Invalid credentials.';
    }
  }
}
?>
<!doctype html>
<meta charset="utf-8">
<title>Login</title>
<h1>Maintenance Login</h1>

<?php if ($error): ?>
  <p style="color:#b00;"><b><?= htmlspecialchars($error, ENT_QUOTES) ?></b></p>
<?php endif; ?>

<form method="post" action="login.php">
  <input type="hidden" name="next" value="<?= htmlspecialchars($next, ENT_QUOTES) ?>">
  <p>Username: <input type="text" name="username" autofocus></p>
  <p>Password: <input type="password" name="password"></p>
  <p><button type="submit">Login</button></p>
</form>

<p><a href="../index.html">Back to site</a></p>
