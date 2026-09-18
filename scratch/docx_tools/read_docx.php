<?php

/**
 * DOCX Reader & Extractor Tool for Capstone Project Document
 * 
 * Location: scratch/docx_tools/read_docx.php
 * Output:   scratch/docx_tools/capstone_full_text.txt
 * 
 * You can safely delete this whole 'docx_tools' folder whenever you are done!
 */

$docFile = __DIR__ . '/../../LAPORAN CAPSTONE PROJECT Adi Dwi Nugroho.docx';
$outputFile = __DIR__ . '/capstone_full_text.txt';

if (!file_exists($docFile)) {
    echo "[Error] File not found: $docFile\n";
    exit(1);
}

$zip = new ZipArchive();
if ($zip->open($docFile) === true) {
    $xmlContent = $zip->getFromName('word/document.xml');
    $zip->close();
    
    if (!$xmlContent) {
        echo "[Error] Could not read word/document.xml inside docx.\n";
        exit(1);
    }
    
    $dom = new DOMDocument();
    $dom->loadXML($xmlContent, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
    
    $paragraphs = $dom->getElementsByTagName('p');
    $text = [];
    foreach ($paragraphs as $p) {
        $pText = trim($p->textContent);
        if ($pText !== '') {
            $text[] = $pText;
        }
    }
    
    file_put_contents($outputFile, implode("\n", $text));
    
    echo "=========================================================\n";
    echo "  DOCX EXTRACTION SUCCESSFUL\n";
    echo "=========================================================\n";
    echo "Source:      " . basename($docFile) . "\n";
    echo "Paragraphs:  " . count($text) . " lines\n";
    echo "Saved to:    " . $outputFile . " (" . number_format(filesize($outputFile)) . " bytes)\n";
    echo "=========================================================\n";
} else {
    echo "[Error] Failed to open zip archive from docx file.\n";
}
