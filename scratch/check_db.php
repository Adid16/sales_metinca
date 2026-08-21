<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=metinca_db2', 'root', '');
$stmt = $pdo->query("SELECT id, purchase_order_id, po_no, item, qty, unit_price FROM purchase_order_internals");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
