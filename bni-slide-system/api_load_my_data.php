<?php
/**
 * BNI Slide System - Load My Data API
 * ログインユーザー自身のアンケートデータを読み込む
 */

header('Content-Type: application/json; charset=utf-8');

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Get current user info
$currentUser = getCurrentUserInfo();

if (!$currentUser) {
    echo json_encode([
        'success' => false,
        'message' => 'ログインが必要です'
    ]);
    exit;
}

$userName = $currentUser['name'];
$userEmail = $currentUser['email'];

try {
    $dataDir = __DIR__ . '/data';
    $csvFiles = glob($dataDir . '/*.csv');

    if (!$csvFiles) {
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => 'データがありません'
        ]);
        exit;
    }

    $myData = [];

    foreach ($csvFiles as $csvFile) {
        $filename = basename($csvFile, '.csv');

        // Skip backup files
        if (strpos($filename, 'backup') !== false) {
            continue;
        }

        // Read CSV file
        if (($handle = fopen($csvFile, 'r')) !== false) {
            // Read header
            $header = fgetcsv($handle);

            if (!$header) {
                fclose($handle);
                continue;
            }

            // Read all rows
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < count($header)) {
                    continue;
                }

                // Create associative array
                $rowData = array_combine($header, $row);

                // Skip if array_combine failed
                if ($rowData === false) {
                    continue;
                }

                // Check if required fields exist
                if (!isset($rowData['メールアドレス']) || !isset($rowData['紹介者名'])) {
                    continue;
                }

                // Check if this row belongs to current user
                if ($rowData['メールアドレス'] === $userEmail || $rowData['紹介者名'] === $userName) {
                    // Add week information
                    $rowData['週'] = getWeekLabel($filename);
                    $rowData['CSVファイル'] = $filename;
                    $myData[] = $rowData;
                }
            }

            fclose($handle);
        }
    }

    // Sort by date (newest first)
    usort($myData, function($a, $b) {
        return strcmp($b['入力日'], $a['入力日']);
    });

    echo json_encode([
        'success' => true,
        'data' => $myData,
        'user' => [
            'name' => $userName,
            'email' => $userEmail
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

/**
 * Get week label from filename
 */
function getWeekLabel($filename) {
    $parts = explode('-', $filename);

    if (count($parts) === 3) {
        // Check if it's new format (YYYY-MM-DD) or old format (YYYY-MM-W)
        if (strlen($parts[2]) === 2 && intval($parts[2]) <= 12) {
            // Old format: YYYY-MM-W
            return $parts[0] . '年' . $parts[1] . '月第' . $parts[2] . '週';
        } else {
            // New format: YYYY-MM-DD
            $date = new DateTime($filename);
            return $date->format('Y年n月j日') . '週';
        }
    }

    return $filename;
}
