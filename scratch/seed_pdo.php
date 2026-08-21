<?php

$host = '127.0.0.1';
$db   = 'metinca_db2';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // 1. Disable FK checks & Truncate Tables
     $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
     $pdo->exec("TRUNCATE TABLE contract_requirements");
     $pdo->exec("TRUNCATE TABLE contracts");
     $pdo->exec("TRUNCATE TABLE purchase_order_internals");
     $pdo->exec("TRUNCATE TABLE purchase_orders");
     $pdo->exec("TRUNCATE TABLE quotation_items");
     $pdo->exec("TRUNCATE TABLE quotations");
     $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

     // 2. Ambil ID Customer
     $stmt = $pdo->query("SELECT id FROM users WHERE role = 'customer' LIMIT 1");
     $cust = $stmt->fetch();
     $customerId = $cust ? $cust['id'] : 1;

     $now = date('Y-m-d H:i:s');
     $deliv1 = date('Y-m-d', strtotime('+10 days'));
     $deliv2 = date('Y-m-d', strtotime('+14 days'));
     $deliv3 = date('Y-m-d', strtotime('+21 days'));

     // 3. Insert Quotation #1 (Single Item PO)
     $stmtQ1 = $pdo->prepare("INSERT INTO quotations (customer_id, quotation_no, date_expired, material, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
     $stmtQ1->execute([$customerId, 'QT-2026-07-0001', date('Y-m-d', strtotime('+30 days')), 'Bronze Alloy B12', 'accepted', $now, $now]);
     $q1Id = $pdo->lastInsertId();

     $stmtQI = $pdo->prepare("INSERT INTO quotation_items (quotation_id, item, qty, price, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
     $stmtQI->execute([$q1Id, 'Custom Turbine Runner Bronze', 15, 1250000, $now, $now]);

     // Insert PO External #1 (Single Item PO: PO-2026-07-001)
     $stmtPO = $pdo->prepare("INSERT INTO purchase_orders (customer_id, quotation_id, po_no, delivery_request, attachment, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
     $stmtPO->execute([$customerId, $q1Id, 'PO-2026-07-001', $deliv1, '1784693775_quotation-QT-2026-07-0002.pdf', 'sent', $now, $now]);

     // 4. Insert Quotation #2 (Multi Item PO - 2 Items)
     $stmtQ2 = $pdo->prepare("INSERT INTO quotations (customer_id, quotation_no, date_expired, material, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
     $stmtQ2->execute([$customerId, 'QT-2026-07-0002', date('Y-m-d', strtotime('+30 days')), 'Cast Iron & Steel S45C', 'accepted', $now, $now]);
     $q2Id = $pdo->lastInsertId();

     $stmtQI->execute([$q2Id, 'Impeller Pump Cast Iron D200', 50, 350000, $now, $now]);
     $stmtQI->execute([$q2Id, 'Shaft Coupling Steel S45C', 100, 175000, $now, $now]);

     // Insert PO External #2 (PO-2026-07-002)
     $stmtPO->execute([$customerId, $q2Id, 'PO-2026-07-002', $deliv2, '1784693775_quotation-QT-2026-07-0002.pdf', 'sent', $now, $now]);

     // 5. Insert Quotation #3 (Multi Item PO - 3 Items)
     $stmtQ3 = $pdo->prepare("INSERT INTO quotations (customer_id, quotation_no, date_expired, material, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
     $stmtQ3->execute([$customerId, 'QT-2026-07-0003', date('Y-m-d', strtotime('+30 days')), 'Stainless Steel SS316', 'accepted', $now, $now]);
     $q3Id = $pdo->lastInsertId();

     $stmtQI->execute([$q3Id, 'Housing Valve SS316', 20, 850000, $now, $now]);
     $stmtQI->execute([$q3Id, 'Flange Disc 4 Inch SS316', 40, 420000, $now, $now]);
     $stmtQI->execute([$q3Id, 'Bracket Mounting Heavy Duty', 80, 210000, $now, $now]);

     // Insert PO External #3 (PO-2026-07-003)
     $stmtPO->execute([$customerId, $q3Id, 'PO-2026-07-003', $deliv3, '1784694257_quotation-QT-2026-07-0008.pdf', 'sent', $now, $now]);

     echo "SUCCESS: DATABASE RESET & FRESH SINGLE + MULTI ITEM PO DATA SEEDED SUCCESSFULLY!\n";
} catch (PDOException $e) {
     echo "DB ERROR: " . $e->getMessage() . "\n";
}
