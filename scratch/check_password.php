<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=metinca_db2', 'root', '');
$stmt = $pdo->query("SELECT id, name, email, password FROM users WHERE email = 'ahm@customer.com'");
$user = $stmt->fetch();
print_r($user);
echo "Check password 'password': " . (password_verify('password', $user['password']) ? 'MATCH' : 'NO') . "\n";
echo "Check password 'admin123': " . (password_verify('admin123', $user['password']) ? 'MATCH' : 'NO') . "\n";
