<?php
/**
 * ログアウト処理
 */
require_once __DIR__ . '/../includes/config.php';

// セッション破棄
session_destroy();

// ログインページへリダイレクト
header('Location: ' . APP_URL . '/login.php');
exit;
