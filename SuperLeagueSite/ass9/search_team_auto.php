<?php
require_once __DIR__ . '/../ass5/db.php';
header('Content-Type: application/json; charset=utf-8');

$q = $_GET['term'] ?? '';
$q = trim($q);

if ($q === '') {
    echo json_encode([]);
    exit;
}

$conn = db();
$stmt = $conn->prepare("SELECT name FROM team WHERE name LIKE CONCAT('%', ?, '%') ORDER BY name LIMIT 10");
$stmt->bind_param('s', $q);
$stmt->execute();
$res = $stmt->get_result();

$teams = [];
while ($row = $res->fetch_assoc()) {
    $teams[] = $row['name'];
}

echo json_encode($teams, JSON_UNESCAPED_UNICODE);
