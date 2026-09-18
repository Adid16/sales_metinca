<?php

$text = file(__DIR__ . '/capstone_full_text.txt', FILE_IGNORE_NEW_LINES);

echo "========================================================\n";
echo "1. AUDIT KATA BERULANG / TYPO DUPLIKAT (DUPLICATE WORDS)\n";
echo "========================================================\n";
foreach ($text as $lineNo => $line) {
    if (preg_match('/\b(\w{3,})\s+\1\b/iu', $line, $matches)) {
        // Skip some common false positives or check
        echo "Line " . ($lineNo + 1) . ": Duplikat kata '{$matches[1]}' -> \"$line\"\n";
    }
}

echo "\n========================================================\n";
echo "2. AUDIT NOMOR BAB & SUB-BAB (NUMBERING & SUBSECTION HIERARCHY)\n";
echo "========================================================\n";
$headings = [];
foreach ($text as $lineNo => $line) {
    $trimmed = trim($line);
    if (preg_match('/^(BAB\s+[IVXLCDM]+|[0-9]+\.[0-9]+(\.[0-9]+)?(\.[0-9]+)?)\s*(.*)$/i', $trimmed, $m)) {
        $headings[] = ['line' => $lineNo + 1, 'num' => $m[1], 'title' => $m[4]];
    }
}

foreach ($headings as $h) {
    // Show headings
    if (str_starts_with($h['num'], 'BAB') || strlen($h['num']) <= 5) {
        echo "Line {$h['line']}: {$h['num']} {$h['title']}\n";
    }
}

echo "\n========================================================\n";
echo "3. AUDIT NAMA PERUSAHAAN & VARIASI PENULISAN\n";
echo "========================================================\n";
$companyVariants = [];
foreach ($text as $lineNo => $line) {
    if (preg_match_all('/(PT\.?\s+Metinca[^\.\,\;\:\n]*)/i', $line, $matches)) {
        foreach ($matches[0] as $match) {
            $companyVariants[trim($match)][] = $lineNo + 1;
        }
    }
}
foreach ($companyVariants as $variant => $lines) {
    echo "- \"$variant\" (" . count($lines) . " kali, contoh baris: " . implode(', ', array_slice($lines, 0, 5)) . ")\n";
}

echo "\n========================================================\n";
echo "4. AUDIT PENULISAN GELAR & NAMA DOSEN / PENGUJI / PEMBIMBING\n";
echo "========================================================\n";
$names = ['Endang Ayu', 'Bella Ayu', 'Eka Yuni', 'Ade Supriatna', 'Adi Dwi'];
foreach ($names as $name) {
    echo "--- Search: $name ---\n";
    foreach ($text as $lineNo => $line) {
        if (stripos($line, $name) !== false) {
            echo "Line " . ($lineNo + 1) . ": " . trim($line) . "\n";
        }
    }
}
