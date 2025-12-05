<?php
/**
 * BNI Slide System - Update My Data API
 * ユーザー自身のデータ更新API
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

try {
    // Get POST data
    $csvFile = $_POST['csv_file'] ?? '';
    $inputDate = $_POST['input_date'] ?? '';
    $attendance = $_POST['attendance'] ?? '';
    $thanksSlips = intval($_POST['thanks_slips'] ?? 0);
    $oneToOneCount = intval($_POST['one_to_one_count'] ?? 0);
    $comments = $_POST['comments'] ?? '';

    // Validate required fields
    if (empty($csvFile)) {
        throw new Exception('CSVファイルが指定されていません');
    }

    // Get visitors data
    $visitors = [];
    if (!empty($_POST['visitor_name'])) {
        foreach ($_POST['visitor_name'] as $index => $name) {
            $name = trim($name);
            $company = trim($_POST['visitor_company'][$index] ?? '');
            $industry = trim($_POST['visitor_industry'][$index] ?? '');

            // Only add visitor if name is provided
            if (!empty($name)) {
                $visitors[] = [
                    'name' => $name,
                    'company' => $company,
                    'industry' => $industry
                ];
            }
        }
    }

    // Get referrals data
    $referrals = [];
    if (!empty($_POST['referral_name'])) {
        foreach ($_POST['referral_name'] as $index => $name) {
            $name = trim($name);
            $amount = intval($_POST['referral_amount'][$index] ?? 0);
            $category = trim($_POST['referral_category'][$index] ?? '');
            $provider = trim($_POST['referral_provider'][$index] ?? '');

            // Only add referral if name is provided
            if (!empty($name)) {
                $referrals[] = [
                    'name' => $name,
                    'amount' => $amount,
                    'category' => $category,
                    'provider' => $provider
                ];
            }
        }
    }

    // CSV file path
    $csvPath = __DIR__ . '/data/' . basename($csvFile) . '.csv';

    if (!file_exists($csvPath)) {
        throw new Exception('データファイルが見つかりません');
    }

    // Read existing CSV data
    $allRows = [];
    $header = [];

    if (($handle = fopen($csvPath, 'r')) !== false) {
        $header = fgetcsv($handle);

        // Read all rows except user's rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= count($header)) {
                $rowData = array_combine($header, $row);

                // Skip rows belonging to current user
                if ($rowData['メールアドレス'] !== $currentUser['email'] ||
                    $rowData['入力日'] !== $inputDate) {
                    $allRows[] = $row;
                }
            }
        }
        fclose($handle);
    }

    // Prepare new rows for current user
    $newRows = [];
    $timestamp = date('Y-m-d H:i:s');

    // If no visitors and no referrals, create one row with base data
    if (empty($visitors) && empty($referrals)) {
        $newRows[] = [
            'timestamp' => $timestamp,
            '入力日' => $inputDate,
            '紹介者名' => $currentUser['name'],
            'メールアドレス' => $currentUser['email'],
            '出席状況' => $attendance,
            'ビジター名' => '',
            'ビジター会社名' => '',
            'ビジター業種' => '',
            '案件名' => '-',
            'リファーラル金額' => 0,
            'カテゴリ' => '',
            'リファーラル提供者' => '',
            'サンクスリップ数' => $thanksSlips,
            'ワンツーワン数' => $oneToOneCount,
            'アクティビティ' => '',
            'コメント' => $comments
        ];
    } else {
        // Calculate total rows needed
        $maxRows = max(count($visitors), count($referrals));

        for ($i = 0; $i < $maxRows; $i++) {
            $visitor = $visitors[$i] ?? ['name' => '', 'company' => '', 'industry' => ''];
            $referral = $referrals[$i] ?? ['name' => '-', 'amount' => 0, 'category' => '', 'provider' => ''];

            // Ensure referral has default values if not set
            if (empty($referral['name'])) {
                $referral['name'] = '-';
            }

            $newRows[] = [
                'timestamp' => $timestamp,
                '入力日' => $inputDate,
                '紹介者名' => $currentUser['name'],
                'メールアドレス' => $currentUser['email'],
                '出席状況' => $attendance,
                'ビジター名' => $visitor['name'],
                'ビジター会社名' => $visitor['company'],
                'ビジター業種' => $visitor['industry'],
                '案件名' => $referral['name'],
                'リファーラル金額' => $referral['amount'],
                'カテゴリ' => $referral['category'],
                'リファーラル提供者' => $referral['provider'],
                'サンクスリップ数' => $thanksSlips,
                'ワンツーワン数' => $oneToOneCount,
                'アクティビティ' => '',
                'コメント' => $comments
            ];
        }
    }

    // Write updated CSV
    if (($handle = fopen($csvPath, 'w')) !== false) {
        // Write header
        fputcsv($handle, $header);

        // Write all other rows
        foreach ($allRows as $row) {
            fputcsv($handle, $row);
        }

        // Write user's new rows
        foreach ($newRows as $rowData) {
            $row = [];
            foreach ($header as $col) {
                $row[] = $rowData[$col] ?? '';
            }
            fputcsv($handle, $row);
        }

        fclose($handle);
    } else {
        throw new Exception('CSVファイルの書き込みに失敗しました');
    }

    echo json_encode([
        'success' => true,
        'message' => 'データを更新しました！'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
