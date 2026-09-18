<?php

$text = file(__DIR__ . '/capstone_full_text.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

function findSection($text, $keyword, $length = 30) {
    echo "=== SEARCH: $keyword ===\n";
    foreach ($text as $idx => $line) {
        if (stripos($line, $keyword) !== false) {
            echo "Line $idx: $line\n";
            for ($j = 1; $j <= $length && ($idx + $j) < count($text); $j++) {
                echo "  + " . $text[$idx + $j] . "\n";
            }
            echo "----------------------------------------\n";
            break;
        }
    }
}

// Check Batasan Masalah
findSection($text, "1.4 Batasan Masalah", 20);

// Check Roles / Aktor
findSection($text, "4.2.1 Use Case Diagram", 25);

// Check Status Tracking
findSection($text, "multi-stage tracking", 15);

// Check Database / Tabel
findSection($text, "5.3 Perancangan Basis Data", 25);

// Check Pengujian Fungsional
findSection($text, "6.4 Pengujian Fungsional Sistem", 25);

// Check Kesimpulan
findSection($text, "BAB VII", 20);
if (!str_contains(implode("\n", $text), "BAB VII")) {
    findSection($text, "PENUTUP", 20);
}
