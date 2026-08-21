<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=metinca_db2', 'root', '');
$stmt = $pdo->query("SELECT po.id, po.po_no, u.id as customer_id, u.name as customer_name, u.email as customer_email, u.role FROM purchase_orders po JOIN users u ON po.customer_id = u.id");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

$stmt2 = $pdo->query("SELECT id, name, email, role FROM users WHERE role = 'customer'");
echo "\n--- ALL CUSTOMER USERS ---\n";
print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
