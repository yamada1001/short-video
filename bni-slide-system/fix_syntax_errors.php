<?php
/**
 * CRUD files syntax error fix script
 *
 * Fixes the pattern:
 *   $stmt->execute();
 *   if ($result) {
 *       echo json_encode(['success' => true]);
 *
 *   break;
 *
 * To:
 *   $result = $stmt->execute();
 *   if ($result) {
 *       echo json_encode(['success' => true]);
 *   } else {
 *       echo json_encode(['success' => false, 'error' => '操作に失敗しました']);
 *   }
 *   break;
 */

$files = [
    'slides_v2/api/member_pitch_crud.php',
    'slides_v2/api/renewal_crud.php',
    'slides_v2/api/share_story_crud.php',
    'slides_v2/api/start_dash_crud.php',
    'slides_v2/api/substitutes_crud.php',
    'slides_v2/api/weekly_no1_crud.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "⚠ File not found: $file\n";
        continue;
    }

    echo "Processing: $file\n";
    $content = file_get_contents($file);

    // Pattern 1: Fix missing $result assignment and closing brace
    // Match: ->execute();\n\n        if ($result) {\n            echo json_encode(['success' => true]);\n        \n        break;
    $pattern1 = '/->execute\(\);(\s+)if \(\$result\) \{(\s+)echo json_encode\(\[\'success\' => true\]\);(\s+)break;/';
    $replacement1 = '->execute();$1$result = true;$1if ($result) {$2echo json_encode([\'success\' => true]);$2} else {$2    echo json_encode([\'success\' => false, \'error\' => \'操作に失敗しました\']);$2}$2break;';

    // Better approach: use preg_replace_callback to handle this more carefully
    $lines = explode("\n", $content);
    $modified = false;

    for ($i = 0; $i < count($lines); $i++) {
        // Look for pattern: $stmt->execute();
        if (preg_match('/\$stmt->execute\(\);/', $lines[$i])) {
            // Check if next non-empty line has "if ($result)"
            $j = $i + 1;
            while ($j < count($lines) && trim($lines[$j]) === '') {
                $j++;
            }

            if ($j < count($lines) && preg_match('/if \(\$result\)/', $lines[$j])) {
                // Check if the following lines match the broken pattern
                $k = $j + 1;
                while ($k < count($lines) && trim($lines[$k]) === '') {
                    $k++;
                }

                // Look for echo json_encode(['success' => true]);
                if ($k < count($lines) && preg_match('/echo json_encode\(\[\'success\' => true\]\);/', $lines[$k])) {
                    // Check if next line is just whitespace then break;
                    $m = $k + 1;
                    while ($m < count($lines) && trim($lines[$m]) === '') {
                        $m++;
                    }

                    if ($m < count($lines) && preg_match('/^\s*break;\s*$/', $lines[$m])) {
                        // Found the broken pattern! Fix it
                        echo "  Found broken pattern at line " . ($i + 1) . "\n";

                        // Change $stmt->execute(); to $result = $stmt->execute();
                        $lines[$i] = preg_replace('/\$stmt->execute\(\);/', '$result = $stmt->execute();', $lines[$i]);

                        // Add closing brace and else block before break
                        $indent = str_repeat(' ', strlen($lines[$m]) - strlen(ltrim($lines[$m])));
                        $lines[$k] .= "\n" . $indent . "} else {\n" . $indent . "    echo json_encode(['success' => false, 'error' => '操作に失敗しました']);\n" . $indent . "}";

                        $modified = true;
                    }
                }
            }
        }
    }

    if ($modified) {
        $newContent = implode("\n", $lines);
        file_put_contents($file, $newContent);
        echo "  ✓ Fixed\n";
    } else {
        echo "  - No changes needed\n";
    }
}

echo "\nDone!\n";
