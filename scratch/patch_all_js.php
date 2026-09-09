<?php
function searchDir($dir, $pattern) {
    $results = [];
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            $results = array_merge($results, searchDir($path, $pattern));
        } else if (preg_match('/\.js$/', $file)) {
            $content = file_get_contents($path);
            if (strpos($content, $pattern) !== false) {
                $results[] = $path;
            }
        }
    }
    return $results;
}

echo "Searching for isElementInViewport in public/...\n";
$matches = searchDir('public', 'isElementInViewport');
foreach ($matches as $m) {
    echo "Found in: $m\n";
    $content = file_get_contents($m);
    // Check if it has the null check
    if (strpos($content, 'if(!z)return!1;') !== false || strpos($content, 'if (!el) return false;') !== false) {
        echo "  -> ALREADY PATCHED with null check!\n";
    } else {
        echo "  -> NEEDS PATCHING!\n";
        $content = str_replace(
            'isElementInViewport(z){var s=z.getBoundingClientRect()',
            'isElementInViewport(z){if(!z)return!1;var s=z.getBoundingClientRect()',
            $content
        );
        $content = str_replace(
            'isElementInViewport(el) {var rect = el.getBoundingClientRect()',
            'isElementInViewport(el) {if(!el)return false;var rect = el.getBoundingClientRect()',
            $content
        );
        $content = str_replace(
            'forceElementVisibility(z){this.isElementInViewport(z)',
            'forceElementVisibility(z){if(!z)return;this.isElementInViewport(z)',
            $content
        );
        $content = str_replace(
            'forceElementVisibility(el) {if (!this.isElementInViewport(el)',
            'forceElementVisibility(el) {if(!el)return;if (!this.isElementInViewport(el)',
            $content
        );
        file_put_contents($m, $content);
        echo "  -> APPLIED PATCH TO $m\n";
    }
}
