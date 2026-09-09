<?php
$file = 'public/assets/compiled/js/app.js';
$content = file_get_contents($file);

$target = 'isElementInViewport(z){var s=z.getBoundingClientRect();return s.top>=0&&s.left>=0&&s.bottom<=(window.innerHeight||document.documentElement.clientHeight)&&s.right<=(window.innerWidth||document.documentElement.clientWidth)}forceElementVisibility(z){this.isElementInViewport(z)||z.scrollIntoView(!1)}';

$replacement = 'isElementInViewport(z){if(!z)return!1;var s=z.getBoundingClientRect();return s.top>=0&&s.left>=0&&s.bottom<=(window.innerHeight||document.documentElement.clientHeight)&&s.right<=(window.innerWidth||document.documentElement.clientWidth)}forceElementVisibility(z){if(!z)return;this.isElementInViewport(z)||z.scrollIntoView(!1)}';

if (strpos($content, $target) !== false) {
    $content = str_replace($target, $replacement, $content);
    file_put_contents($file, $content);
    echo "[SUCCESS] Patched public/assets/compiled/js/app.js\n";
} else {
    echo "[FAIL] Target not found in app.js\n";
}
