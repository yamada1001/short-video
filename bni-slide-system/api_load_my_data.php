<?php
/**
 * BNI Slide System - Load My Data API
 * ログインユーザー自身のアンケートデータを読み込む
 */

header('Content-Type: application/json; charset=utf-8');

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Load date helper functions
require_once __DIR__ . '/includes/date_helper.php';

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
