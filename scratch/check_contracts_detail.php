<?php
$host = '127.0.0.1';
$db   = 'metinca_db2';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== COLUMNS IN CONTRACTS TABLE ===\n";
    $stmt = $pdo->query("DESCRIBE contracts");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo "{$col['Field']} ({$col['Type']})\n";
    }

    echo "\n=== ALL CONTRACT RECORDS ===\n";
    $stmt2 = $pdo->query("SELECT * FROM contracts");
    print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
