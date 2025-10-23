<?php require_once "db.php";
$ok = $err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $full_name = trim($_POST['full_name'] ?? "");
        if ($full_name === "") throw new Exception("Full name is required.");
        $c = db();
        $stmt = $c->prepare("INSERT INTO person (full_name) VALUES (?)");
        $stmt->bind_param("s", $full_name);
        $stmt->execute();
        $ok = "Person inserted (id ".$c->insert_id.")";
    } catch (Throwable $e) { $err = $e->getMessage(); }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Add Person</title></head>
<body>
<h1>Add Person</h1>
<form method="post">
  <label>Full name <input name="full_name" required></label><br>
  <button type="submit">Insert Person</button>
</form>
<?php if ($ok) echo "<p style='color:green'>".esc($ok)."</p>"; ?>
<?php if ($err) echo "<p style='color:red'>".esc($err)."</p>"; ?>
<p><a href="maintenance.html">Back to maintenance</a></p>
</body></html>
