<?php
require_once __DIR__ . '/auth.php';
require_login(); 
require_once "db.php";
$ok = $err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name'] ?? "");
        $city = trim($_POST['city'] ?? "");
        if ($name === "" || $city === "") throw new Exception("Name and City are required.");
        $c = db();
        $stmt = $c->prepare("INSERT INTO team (name, city) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $city);
        $stmt->execute();
        $ok = "Team inserted (id ".$c->insert_id.").";
    } catch (Throwable $e) { $err = $e->getMessage(); }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Add Team</title></head>
<body>
<h1>Add Team</h1>
<form method="post">
  <label>Name <input name="name" required></label><br>
  <label>City <input name="city" required></label><br>
  <button type="submit">Insert Team</button>
</form>
<?php if ($ok) echo "<p style='color:green'>".esc($ok)."</p>"; ?>
<?php if ($err) echo "<p style='color:red'>".esc($err)."</p>"; ?>
<p><a href="maintenance.php">Back to maintenance</a></p>
</body></html>
