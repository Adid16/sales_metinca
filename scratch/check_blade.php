<?php
$content = file_get_contents('c:/laragon/www/sales_metinca/resources/views/purchase-orders/index.blade.php');
$lines = explode("\n", $content);

$stack = [];
foreach ($lines as $num => $line) {
    $lineNum = $num + 1;
    // Match blade directives
    preg_match_all('/@(if|elseif|else|endif|foreach|endforeach|forelse|endforelse|section|endsection|push|endpush|php|endphp)\b/', $line, $matches);
    foreach ($matches[1] as $tag) {
        if (in_array($tag, ['if', 'foreach', 'forelse', 'section', 'push'])) {
            $stack[] = ['tag' => $tag, 'line' => $lineNum];
        } elseif ($tag === 'endif') {
            $last = end($stack);
            if ($last && $last['tag'] === 'if') {
                array_pop($stack);
            } else {
                echo "Unmatched @endif at line $lineNum. Expected matching for: " . json_encode($last) . "\n";
            }
        } elseif ($tag === 'endforeach') {
            $last = end($stack);
            if ($last && $last['tag'] === 'foreach') {
                array_pop($stack);
            } else {
                echo "Unmatched @endforeach at line $lineNum. Expected matching for: " . json_encode($last) . "\n";
            }
        } elseif ($tag === 'endforelse') {
            $last = end($stack);
            if ($last && $last['tag'] === 'forelse') {
                array_pop($stack);
            } else {
                echo "Unmatched @endforelse at line $lineNum. Expected matching for: " . json_encode($last) . "\n";
            }
        } elseif ($tag === 'endsection') {
            $last = end($stack);
            if ($last && $last['tag'] === 'section') {
                array_pop($stack);
            } else {
                echo "Unmatched @endsection at line $lineNum. Expected matching for: " . json_encode($last) . "\n";
            }
        } elseif ($tag === 'endpush') {
            $last = end($stack);
            if ($last && $last['tag'] === 'push') {
                array_pop($stack);
            } else {
                echo "Unmatched @endpush at line $lineNum. Expected matching for: " . json_encode($last) . "\n";
            }
        }
    }
}

echo "Remaining unclosed directives in stack:\n";
print_r($stack);
