<?php
$content = file_get_contents('public/assets/compiled/js/app.js');
$pos = strpos($content, 'isElementInViewport');
if ($pos !== false) {
    echo "Found at offset: $pos\n";
    echo substr($content, max(0, $pos - 150), 300) . "\n";
} else {
    echo "Not found in app.js\n";
    // Check for getBoundingClientRect
    $pos = strpos($content, 'getBoundingClientRect');
    echo "First getBoundingClientRect at: $pos\n";
    echo substr($content, max(0, $pos - 100), 200) . "\n";
}
