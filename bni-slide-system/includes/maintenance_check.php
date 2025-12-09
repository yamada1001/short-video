<?php
/**
 * BNI Slide System - Maintenance Mode Check
 * メンテナンスモードチェック処理
 */

// Load maintenance configuration
require_once __DIR__ . '/../config/maintenance.php';

/**
 * Check if system is in maintenance mode
 * If in maintenance mode and user is not allowed, redirect to maintenance page
 */
function checkMaintenanceMode() {
    // メンテナンスモードが無効な場合はスキップ
    if (!MAINTENANCE_MODE) {
        return;
    }

    // セッション開始（まだ開始していない場合）
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // ログインユーザーのメールアドレスを取得
    $userEmail = $_SESSION['user_email'] ?? null;

    // 管理者リストに含まれている場合はアクセス許可
    if ($userEmail && in_array($userEmail, MAINTENANCE_ALLOWED_EMAILS)) {
        return;
    }

    // メンテナンスページへリダイレクト
    $maintenancePage = '/bni-slide-system/maintenance.php';
    $currentPage = $_SERVER['PHP_SELF'] ?? '';

    // 既にメンテナンスページにいる場合はリダイレクトしない
    if (strpos($currentPage, 'maintenance.php') !== false) {
        return;
    }

    // メンテナンスページへリダイレクト
    header('Location: ' . $maintenancePage);
    exit;
}

// 自動実行（このファイルをrequireするだけでチェックが走る）
checkMaintenanceMode();
