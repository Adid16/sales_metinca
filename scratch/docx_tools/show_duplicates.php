<?php

$text = file(__DIR__ . '/capstone_full_text.txt', FILE_IGNORE_NEW_LINES);

echo "=== DUPLICATE WORDS FOUND ===\n";
foreach ($text as $lineNo => $line) {
    if (preg_match_all('/\b(\w{3,})\s+\1\b/iu', $line, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            echo "Line " . ($lineNo + 1) . ": '{$m[0]}' -> " . mb_substr($line, 0, 100) . "...\n";
        }
    }
}
