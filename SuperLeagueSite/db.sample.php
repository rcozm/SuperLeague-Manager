<?php
$DB_HOST = "10.60.36.1";
$DB_USER = "rcozmolici";
$DB_PASS = "<<<CHANGE_ME>>>";
$DB_NAME = "db_rcozmolici";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db() {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset("utf8mb4");
    return $conn;
}

function esc($s) { return htmlspecialchars($s ?? "", ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); }
