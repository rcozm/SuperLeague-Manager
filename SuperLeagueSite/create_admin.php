<?php
require_once __DIR__ . '/db.php';

$username = 'admin';
$plain = 'Spiderman1-'; 

$hash = password_hash($plain, PASSWORD_BCRYPT);

$c = db();
$st = $c->prepare('INSERT INTO user_account (username, password_hash) VALUES (?, ?)
                   ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)');
$st->bind_param('ss', $username, $hash);
$st->execute();

echo "Admin user created/updated.\nUsername: {$username}\nPassword: {$plain}\n";
