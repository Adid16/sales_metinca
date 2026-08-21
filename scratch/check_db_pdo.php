<?php
$host = '127.0.0.1';
$db   = 'metinca_db2';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== DAFTAR USER & PO DI DATABASE ===\n\n";

    $stmt = $pdo->query("
        SELECT po.id as po_id, po.po_no, po.status as po_status, u.id as user_id, u.name as customer_name, u.email as customer_email, u.role
        FROM purchase_orders po
        JOIN users u ON po.customer_id = u.id
        ORDER BY po.id DESC
    ");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo "PO ID: {$row['po_id']} | PO No: {$row['po_no']} | Status: {$row['po_status']}\n";
        echo "Customer Name: {$row['customer_name']} | Email: {$row['customer_email']} | Role: {$row['role']}\n";
        echo "---------------------------------------------------------\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
