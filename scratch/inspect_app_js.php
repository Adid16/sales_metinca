<?php
$content = file_get_contents('public/assets/compiled/js/app.js');
$pos = strpos($content, 'isElementInViewport(z)');
if ($pos !== false) {
    echo substr($content, $pos - 50, 300) . "\n";
}
