<?php
$host = '127.0.0.1';
$db   = 'metinca_db2';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== FIXING CONTRACT AND POI STATUSES IN DATABASE ===\n";
    
    // 1. Update contracts with 'done' where POI is production -> status = 'production'
    $affected1 = $pdo->exec("UPDATE contracts SET status = 'production' WHERE status = 'done'");
    echo "Updated $affected1 contracts from 'done' to 'production'.\n";

    // 2. Update contract CTR-2026-AHM-002 (CID 12) from 'contract' to 'approved' (4 approvers present)
    $affected2 = $pdo->exec("UPDATE contracts SET status = 'approved' WHERE id = 12 OR (sales_approver IS NOT NULL AND quality_approver IS NOT NULL AND ppc_approver IS NOT NULL AND dev_engineering_approver IS NOT NULL AND status IN ('contract', 'review'))");
    echo "Updated $affected2 contracts with 4 approvals to 'approved'.\n";

    // 3. Update POI 17 from 'contract' to 'approved'
    $affected3 = $pdo->exec("UPDATE purchase_order_internals SET status = 'approved' WHERE status = 'contract'");
    echo "Updated $affected3 PO internals from 'contract' to 'approved'.\n";

    echo "\n=== VERIFYING UPDATED STATUSES ===\n";
    foreach (['contracts', 'purchase_orders', 'purchase_order_internals'] as $table) {
        $stmt = $pdo->query("SELECT status, count(*) as cnt FROM $table GROUP BY status");
        echo "Table: $table\n";
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            echo "  Status: '{$row['status']}' => {$row['cnt']}\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
