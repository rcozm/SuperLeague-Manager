<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

function is_logged_in(): bool {
  return isset($_SESSION['user']);
}

function require_login(): void {
  if (!is_logged_in()) {
    $next = urlencode($_SERVER['REQUEST_URI'] ?? '/ass5/maintenance.php');
    header("Location: login.php?next={$next}");
    exit;
  }
}
