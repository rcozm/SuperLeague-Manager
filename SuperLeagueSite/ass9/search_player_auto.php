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
$stmt = $conn->prepare("SELECT pe.full_name
                        FROM player pl
                        JOIN person pe ON pe.person_id = pl.person_id
                        WHERE pe.full_name LIKE CONCAT('%', ?, '%')
                        ORDER BY pe.full_name
                        LIMIT 10");
$stmt->bind_param('s', $q);
$stmt->execute();
$res = $stmt->get_result();

$players = [];
while ($row = $res->fetch_assoc()) {
    $players[] = $row['full_name'];
}

echo json_encode($players, JSON_UNESCAPED_UNICODE);
