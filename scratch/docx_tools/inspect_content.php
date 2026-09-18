<?php

$text = file(__DIR__ . '/capstone_full_text.txt', FILE_IGNORE_NEW_LINES);

function extractBodySection($text, $startLine, $numLines = 40) {
    $out = [];
    for ($i = $startLine; $i < min($startLine + $numLines, count($text)); $i++) {
        $out[] = ($i + 1) . ": " . $text[$i];
    }
    return implode("\n", $out);
}

// Bab 1 starts around line 334
echo "=== BAB I LATAR BELAKANG (Line 334) ===\n";
echo extractBodySection($text, 334, 25) . "\n\n";

echo "=== BAB I RUMUSAN & TUJUAN (Line 339) ===\n";
echo extractBodySection($text, 339, 20) . "\n\n";

// Bab 4 starts around line 413
echo "=== BAB IV ANALISA SISTEM (Line 509) ===\n";
echo extractBodySection($text, 509, 25) . "\n\n";

// Bab 6 implementasi starts around line 2980
echo "=== BAB VI IMPLEMENTASI SISTEM (Line 2980) ===\n";
echo extractBodySection($text, 2980, 30) . "\n\n";

// Bab 7 starts around line 3315
echo "=== BAB VII KESIMPULAN (Line 3315) ===\n";
echo extractBodySection($text, 3315, 25) . "\n\n";
