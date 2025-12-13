<?php
/**
 * BNI Slide System - Date Helper Functions
 * 日付関連の共通処理
 *
 * このファイルは新形式（YYYY-MM-DD）と旧形式（YYYY-MM-W）の両方に対応し、
 * すべてのAPIで一貫した日付処理を提供します。
 */

/**
 * ファイル名から日付情報をパース
 *
 * @param string $filename ファイル名（拡張子なし、例: "2025-11-07" または "2024-11-3"）
 * @return array ['success' => bool, 'date' => DateTime, 'format' => 'new'|'old', 'label' => string, 'error' => string]
 */
function parseFilenameToDate($filename) {
    $parts = explode('-', $filename);

    if (count($parts) !== 3) {
        return [
            'success' => false,
            'error' => 'Invalid filename format'
        ];
    }

    // Try to parse as date first (YYYY-MM-DD format)
    try {
        $date = new DateTime($filename);

        // Get day of week name
        $dayOfWeek = $date->format('w'); // 0=Sun, 5=Fri
        $dayNames = ['日', '月', '火', '水', '木', '金', '土'];
        $dayName = $dayNames[$dayOfWeek];

        $label = $date->format('Y年n月j日') . '（' . $dayName . '）';

        return [
            'success' => true,
            'date' => $date,
            'format' => 'new',
            'label' => $label
        ];
    } catch (Exception $e) {
        // If parsing fails, try old format: YYYY-MM-W
        if (strlen($parts[2]) <= 2 && intval($parts[2]) <= 5) {
            $year = intval($parts[0]);
            $month = intval($parts[1]);
            $weekInMonth = intval($parts[2]);

            try {
                $fridayDate = calculateFridayDate($year, $month, $weekInMonth);
                $label = $parts[0] . '年' . $parts[1] . '月第' . $parts[2] . '週';

                return [
                    'success' => true,
                    'date' => $fridayDate,
                    'format' => 'old',
                    'label' => $label
                ];
            } catch (Exception $ex) {
                return [
                    'success' => false,
                    'error' => 'Failed to calculate Friday date: ' . $ex->getMessage()
                ];
            }
        }

        return [
            'success' => false,
            'error' => 'Invalid date format: ' . $e->getMessage()
        ];
    }
}

/**
 * Calculate Friday date for given year, month, and week number (旧形式用)
 *
 * @param int $year 年
 * @param int $month 月
 * @param int $weekInMonth 月内の週番号（1-5）
 * @return DateTime
 */
function calculateFridayDate($year, $month, $weekInMonth) {
    // Find the Nth Friday of the month
    $date = new DateTime("$year-$month-01");

    // Find first Friday of the month
    $dayOfWeek = intval($date->format('w')); // 0=Sunday, 5=Friday

    if ($dayOfWeek <= 5) {
        // If month starts on or before Friday, go to first Friday
        $daysToFriday = 5 - $dayOfWeek;
    } else {
        // If month starts on Saturday/Sunday, go to next Friday
        $daysToFriday = (5 - $dayOfWeek + 7) % 7;
    }

    if ($daysToFriday > 0) {
        $date->modify("+$daysToFriday days");
    }

    // Now add (weekInMonth - 1) weeks
    if ($weekInMonth > 1) {
        $date->modify("+" . ($weekInMonth - 1) . " weeks");
    }

    return $date;
}

/**
 * 週のラベルを取得（マイデータ画面用）
 *
 * @param string $filename ファイル名（拡張子なし）
 * @return string 週のラベル（例: "2025年11月7日（金）"）
 */
function getWeekLabel($filename) {
    $result = parseFilenameToDate($filename);

    if ($result['success']) {
        return $result['label'];
    }

    // フォールバック：ファイル名をそのまま返す
    return $filename;
}

/**
 * 現在のタイムスタンプから対象の金曜日を取得（新規データ保存用）
 * 週の定義：金曜5:00 AM ~ 次の金曜5:00 AM
 *
 * @param string $timestamp タイムスタンプ（Y-m-d H:i:s形式）
 * @return string 金曜日の日付（Y-m-d形式）
 */
function getTargetFriday($timestamp) {
    $dt = new DateTime($timestamp);
    $dayOfWeek = intval($dt->format('w')); // 0=Sunday, 5=Friday
    $hour = intval($dt->format('H'));

    // Friday 0:00-4:59 → This Friday (today)
    if ($dayOfWeek === 5 && $hour < 5) {
        return $dt->format('Y-m-d');
    }

    // Friday 5:00 onwards → Next Friday
    if ($dayOfWeek === 5 && $hour >= 5) {
        $dt->modify('+7 days');
        return $dt->format('Y-m-d');
    }

    // For any other day (Sat, Sun, Mon, Tue, Wed, Thu):
    // Find the PREVIOUS Friday (this is the week's start date)
    // Sat(6) → -1 day, Sun(0) → -2 days, Mon(1) → -3 days, ..., Thu(4) → -6 days
    if ($dayOfWeek === 0) {
        // Sunday → go back 2 days to Friday
        $dt->modify('-2 days');
    } else {
        // Other days: calculate days back to Friday
        $daysToSubtract = $dayOfWeek - 5;
        if ($daysToSubtract < 0) {
            $daysToSubtract += 7;
        }
        $dt->modify("-$daysToSubtract days");
    }

    return $dt->format('Y-m-d');
}
