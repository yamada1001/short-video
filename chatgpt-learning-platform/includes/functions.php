<?php
/**
 * Gemini AI学習プラットフォーム - 共通関数
 *
 * アプリケーション全体で使用される共通関数
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * ユーザー情報を取得
 */
function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $sql = "SELECT * FROM users WHERE id = ?";
    return db()->fetchOne($sql, [$_SESSION['user_id']]);
}

/**
 * ログインチェック
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        redirect(APP_URL . '/login.php');
    }
}

/**
 * 管理者チェック
 */
function requireAdmin() {
    requireLogin();
    $user = getCurrentUser();
    if ($user['email'] !== 'admin@example.com') { // 仮実装
        redirect(APP_URL . '/dashboard.php');
    }
}

/**
 * コースアクセス権チェック
 */
function canAccessCourse($courseId) {
    // 全てのコースにアクセス可能
    return true;
}

/**
 * 1日のAPI使用回数チェック
 */
function checkApiLimit() {
    $user = getCurrentUser();
    if (!$user) return false;

    // API制限なし（常にtrueを返す）
    return true;
}

/**
 * API使用量を記録
 */
function logApiUsage($endpoint, $tokensUsed, $costUsd) {
    $user = getCurrentUser();
    if (!$user) return false;

    $sql = "INSERT INTO api_usage (user_id, endpoint, tokens_used, cost_usd)
            VALUES (?, ?, ?, ?)";
    return db()->execute($sql, [$user['id'], $endpoint, $tokensUsed, $costUsd]);
}

/**
 * プロンプトキャッシュを取得
 */
function getCachedPrompt($promptText, $model) {
    $hash = hash('sha256', $promptText . $model);

    $sql = "SELECT response_text FROM prompt_cache
            WHERE prompt_hash = ? AND model = ?";
    $result = db()->fetchOne($sql, [$hash, $model]);

    if ($result) {
        // ヒットカウントと最終使用日を更新
        $updateSql = "UPDATE prompt_cache
                     SET hit_count = hit_count + 1, last_used_at = NOW()
                     WHERE prompt_hash = ?";
        db()->execute($updateSql, [$hash]);

        return $result['response_text'];
    }

    return null;
}

/**
 * プロンプトキャッシュを保存
 */
function saveCachedPrompt($promptText, $responseText, $model) {
    $hash = hash('sha256', $promptText . $model);

    $sql = "INSERT INTO prompt_cache (prompt_hash, prompt_text, response_text, model)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            response_text = VALUES(response_text),
            last_used_at = NOW()";

    return db()->execute($sql, [$hash, $promptText, $responseText, $model]);
}

/**
 * 進捗を更新
 */
function updateProgress($lessonId, $status = 'completed') {
    $user = getCurrentUser();
    if (!$user) return false;

    $sql = "INSERT INTO user_progress (user_id, lesson_id, status, completed_at)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            status = VALUES(status),
            completed_at = VALUES(completed_at)";

    $completedAt = ($status === 'completed') ? date('Y-m-d H:i:s') : null;

    $result = db()->execute($sql, [$user['id'], $lessonId, $status, $completedAt]);

    // レッスン完了時のゲーミフィケーション処理
    // エラーが発生しても進捗更新自体は成功させる
    if ($result && $status === 'completed') {
        try {
            // ポイント付与
            $points = calculateLessonPoints($lessonId, $user['id']);
            awardPoints($user['id'], $points, "レッスン完了", 'lesson', $lessonId);

            // ストリーク更新
            updateStreak($user['id']);

            // バッジチェック
            checkAndAwardBadges($user['id']);
        } catch (Exception $e) {
            // ゲーミフィケーション処理のエラーはログに記録するだけ
            error_log('Gamification error in updateProgress: ' . $e->getMessage());
            // 進捗更新自体は成功しているのでtrueを返す
        }
    }

    return $result;
}

/**
 * コースの進捗率を取得
 */
function getCourseProgress($courseId) {
    $user = getCurrentUser();
    if (!$user) return 0;

    // コースの総レッスン数を取得
    $totalSql = "SELECT COUNT(*) as total FROM lessons WHERE course_id = ?";
    $totalResult = db()->fetchOne($totalSql, [$courseId]);
    $total = $totalResult['total'] ?? 0;

    if ($total === 0) return 0;

    // 完了したレッスン数を取得
    $completedSql = "SELECT COUNT(*) as completed FROM user_progress up
                     JOIN lessons l ON up.lesson_id = l.id
                     WHERE l.course_id = ? AND up.user_id = ? AND up.status = 'completed'";
    $completedResult = db()->fetchOne($completedSql, [$courseId, $user['id']]);
    $completed = $completedResult['completed'] ?? 0;

    return round(($completed / $total) * 100);
}

/**
 * メール送信（PHPMailer）
 */
function sendEmail($to, $subject, $body) {
    // 配信停止済みユーザーのチェック
    $userSql = "SELECT id, email_unsubscribed FROM users WHERE email = ?";
    $user = db()->fetchOne($userSql, [$to]);

    // ユーザーが配信停止している場合は送信しない
    if ($user && $user['email_unsubscribed']) {
        error_log("Email not sent to {$to}: User has unsubscribed");
        return false;
    }

    // 配信停止リンクを追加（ユーザーが存在する場合）
    if ($user) {
        $userId = $user['id'];
        $unsubscribeToken = hash_hmac('sha256', $userId, UNSUBSCRIBE_SECRET);
        $unsubscribeUrl = APP_URL . '/unsubscribe.php?user=' . $userId . '&token=' . $unsubscribeToken;

        // メール本文にフッターを追加
        $footer = <<<HTML
<div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; text-align: center;">
    <p style="margin: 8px 0;">このメールに心当たりがない場合は、無視してください。</p>
    <p style="margin: 8px 0;">
        <a href="{$unsubscribeUrl}" style="color: #9ca3af; text-decoration: underline;">メール配信を停止する</a>
    </p>
    <p style="margin: 8px 0;">© 2025 Gemini AI学習プラットフォーム</p>
</div>
HTML;
        $body = $body . $footer;
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // SMTP設定
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;
        $mail->Timeout = 5; // タイムアウトを5秒に設定（デフォルトは30秒以上）

        // 送信者・受信者設定
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        $mail->addAddress($to);

        // 文字コード設定
        $mail->CharSet = 'UTF-8';

        // メール内容
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mail error: ' . $mail->ErrorInfo);
        return false;
    }
}

/**
 * パスワード再発行トークン生成
 */
function generatePasswordResetToken($userId) {
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $sql = "INSERT INTO password_reset_tokens (user_id, token, expires_at)
            VALUES (?, ?, ?)";

    if (db()->execute($sql, [$userId, $token, $expiresAt])) {
        return $token;
    }

    return false;
}

/**
 * パスワード再発行トークン検証
 */
function verifyPasswordResetToken($token) {
    $sql = "SELECT * FROM password_reset_tokens
            WHERE token = ? AND used = 0 AND expires_at > NOW()";

    return db()->fetchOne($sql, [$token]);
}

/**
 * パスワード再発行トークンを使用済みにする
 */
function markTokenAsUsed($token) {
    $sql = "UPDATE password_reset_tokens SET used = 1 WHERE token = ?";
    return db()->execute($sql, [$token]);
}
// ============================================
// ゲーミフィケーション機能
// ============================================

/**
 * ポイント付与
 * 
 * @param int $userId ユーザーID
 * @param int $points ポイント数（マイナス可）
 * @param string $reason 理由
 * @param string $relatedType 関連タイプ (lesson, quiz, course, badge, streak, other)
 * @param int|null $relatedId 関連ID
 * @return bool 成功/失敗
 */
function awardPoints($userId, $points, $reason, $relatedType = 'other', $relatedId = null) {
    try {
        // ポイント履歴を記録
        $sql = "INSERT INTO user_points (user_id, points, reason, related_type, related_id)
                VALUES (?, ?, ?, ?, ?)";
        
        if (!db()->execute($sql, [$userId, $points, $reason, $relatedType, $relatedId])) {
            return false;
        }
        
        // ユーザーの総ポイントを更新
        $updateSql = "UPDATE users 
                     SET total_points = total_points + ? 
                     WHERE id = ?";
        
        if (!db()->execute($updateSql, [$points, $userId])) {
            return false;
        }
        
        // 総ポイントを取得してレベルを計算・更新
        $userSql = "SELECT total_points FROM users WHERE id = ?";
        $user = db()->fetchOne($userSql, [$userId]);
        
        if ($user) {
            $newLevel = calculateLevel($user['total_points']);
            $levelSql = "UPDATE users SET level = ? WHERE id = ?";
            db()->execute($levelSql, [$newLevel, $userId]);
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("ポイント付与エラー: " . $e->getMessage());
        return false;
    }
}

/**
 * レベル計算
 * 
 * ポイント数からレベルを計算
 * レベル1: 0-99pt
 * レベル2: 100-299pt
 * レベル3: 300-599pt
 * レベル4: 600-999pt
 * レベル5: 1000-1499pt
 * 以降、500pt毎に1レベルアップ
 * 
 * @param int $totalPoints 総ポイント
 * @return int レベル
 */
function calculateLevel($totalPoints) {
    if ($totalPoints < 100) return 1;
    if ($totalPoints < 300) return 2;
    if ($totalPoints < 600) return 3;
    if ($totalPoints < 1000) return 4;
    if ($totalPoints < 1500) return 5;
    
    // レベル6以降は500pt毎に1レベルアップ
    return 5 + floor(($totalPoints - 1500) / 500) + 1;
}

/**
 * レッスン完了時のポイント計算
 * 
 * 基本: 10pt
 * ボーナス:
 * - 初回完了: +5pt
 * - スライド形式: +0pt
 * - クイズ形式: +5pt
 * 
 * @param int $lessonId レッスンID
 * @param int $userId ユーザーID
 * @return int ポイント数
 */
function calculateLessonPoints($lessonId, $userId) {
    $basePoints = 10;
    $bonusPoints = 0;
    
    // レッスン情報を取得
    $lessonSql = "SELECT lesson_type FROM lessons WHERE id = ?";
    $lesson = db()->fetchOne($lessonSql, [$lessonId]);
    
    if (!$lesson) {
        return $basePoints;
    }
    
    // クイズ形式はボーナス
    if ($lesson['lesson_type'] === 'quiz') {
        $bonusPoints += 5;
    }
    
    // 初回完了チェック
    $progressSql = "SELECT COUNT(*) as count FROM user_progress 
                   WHERE user_id = ? AND lesson_id = ? AND status = 'completed'";
    $progress = db()->fetchOne($progressSql, [$userId, $lessonId]);
    
    // 初回完了（まだcompletedが記録されていない）
    if ($progress && $progress['count'] == 0) {
        $bonusPoints += 5;
    }
    
    return $basePoints + $bonusPoints;
}

/**
 * ストリーク更新
 * 
 * 今日の学習活動を記録し、連続学習日数を更新
 * 
 * @param int $userId ユーザーID
 * @return bool 成功/失敗
 */
function updateStreak($userId) {
    try {
        $today = date('Y-m-d');

        // 今日の記録があるかチェック
        $checkSql = "SELECT COUNT(*) as count FROM user_streaks
                    WHERE user_id = ? AND activity_date = ?";
        $existing = db()->fetchOne($checkSql, [$userId, $today]);

        // クエリが失敗した場合は早期リターン
        if ($existing === false) {
            error_log('updateStreak: user_streaks table query failed for user ' . $userId);
            return false;
        }

        // 今日の記録がなければ追加
        if ($existing['count'] == 0) {
            $insertSql = "INSERT INTO user_streaks (user_id, activity_date) VALUES (?, ?)";
            db()->execute($insertSql, [$userId, $today]);
        }
        
        // 連続日数を計算
        $currentStreak = calculateCurrentStreak($userId);
        
        // usersテーブルを更新
        $updateSql = "UPDATE users 
                     SET current_streak = ?,
                         longest_streak = GREATEST(longest_streak, ?)
                     WHERE id = ?";
        
        return db()->execute($updateSql, [$currentStreak, $currentStreak, $userId]);
        
    } catch (Exception $e) {
        error_log("ストリーク更新エラー: " . $e->getMessage());
        return false;
    }
}

/**
 * 現在の連続学習日数を計算
 * 
 * @param int $userId ユーザーID
 * @return int 連続日数
 */
function calculateCurrentStreak($userId) {
    $sql = "SELECT activity_date FROM user_streaks
            WHERE user_id = ?
            ORDER BY activity_date DESC";

    $records = db()->fetchAll($sql, [$userId]);

    // クエリが失敗した場合は0を返す
    if ($records === false || empty($records)) {
        return 0;
    }
    
    $streak = 0;
    $expectedDate = date('Y-m-d');
    
    foreach ($records as $record) {
        if ($record['activity_date'] === $expectedDate) {
            $streak++;
            // 前日を期待する日付に設定
            $expectedDate = date('Y-m-d', strtotime($expectedDate . ' -1 day'));
        } else {
            // 連続が途切れた
            break;
        }
    }
    
    return $streak;
}

/**
 * バッジ獲得チェック
 * 
 * ユーザーが獲得条件を満たすバッジをチェックし、自動付与
 * 
 * @param int $userId ユーザーID
 * @return array 新規獲得したバッジのID配列
 */
function checkAndAwardBadges($userId) {
    $newBadges = [];

    try {
        // 未獲得のバッジ一覧を取得
        $badgesSql = "SELECT * FROM gamification_badges
                     WHERE id NOT IN (
                         SELECT badge_id FROM user_badges WHERE user_id = ?
                     )
                     ORDER BY display_order";

        $badges = db()->fetchAll($badgesSql, [$userId]);

        // クエリが失敗した場合は早期リターン
        if ($badges === false) {
            error_log('checkAndAwardBadges: gamification_badges table query failed for user ' . $userId);
            return [];
        }

        foreach ($badges as $badge) {
            $condition = json_decode($badge['required_condition'], true);
            
            if (isBadgeConditionMet($userId, $condition)) {
                // バッジ付与
                $insertSql = "INSERT INTO user_badges (user_id, badge_id) VALUES (?, ?)";
                if (db()->execute($insertSql, [$userId, $badge['id']])) {
                    $newBadges[] = $badge['id'];
                    
                    // ボーナスポイント付与
                    if ($badge['points_reward'] > 0) {
                        awardPoints(
                            $userId, 
                            $badge['points_reward'], 
                            "バッジ獲得: {$badge['name']}", 
                            'badge', 
                            $badge['id']
                        );
                    }
                }
            }
        }
        
        return $newBadges;
        
    } catch (Exception $e) {
        error_log("バッジチェックエラー: " . $e->getMessage());
        return [];
    }
}

/**
 * バッジ獲得条件をチェック
 * 
 * @param int $userId ユーザーID
 * @param array $condition 条件（JSON）
 * @return bool 条件を満たすか
 */
function isBadgeConditionMet($userId, $condition) {
    $type = $condition['type'] ?? '';
    $count = $condition['count'] ?? 0;
    
    switch ($type) {
        case 'lesson_complete':
            $sql = "SELECT COUNT(*) as count FROM user_progress 
                   WHERE user_id = ? AND status = 'completed'";
            $result = db()->fetchOne($sql, [$userId]);
            return ($result['count'] ?? 0) >= $count;
            
        case 'course_complete':
            if ($count === 'all') {
                // 全コース完了チェック（実装が複雑なため簡易版）
                $totalCoursesSql = "SELECT COUNT(*) as count FROM courses";
                $totalCourses = db()->fetchOne($totalCoursesSql);
                
                $completedCoursesSql = "SELECT COUNT(DISTINCT l.course_id) as count 
                                       FROM user_progress up
                                       JOIN lessons l ON up.lesson_id = l.id
                                       WHERE up.user_id = ? AND up.status = 'completed'";
                $completedCourses = db()->fetchOne($completedCoursesSql, [$userId]);
                
                return ($completedCourses['count'] ?? 0) >= ($totalCourses['count'] ?? 1);
            } else {
                // N個のコース完了チェック（簡易版: 全レッスン完了したコースをカウント）
                return false; // TODO: 正確な実装
            }
            
        case 'quiz_perfect':
            $sql = "SELECT COUNT(*) as count FROM quiz_results 
                   WHERE user_id = ? AND score = 100";
            $result = db()->fetchOne($sql, [$userId]);
            return ($result['count'] ?? 0) >= $count;
            
        case 'streak':
            $userSql = "SELECT current_streak FROM users WHERE id = ?";
            $user = db()->fetchOne($userSql, [$userId]);
            return ($user['current_streak'] ?? 0) >= $count;
            
        case 'user_register':
            // 登録済みなら常にtrue
            return true;
            
        case 'profile_complete':
            // プロフィール完成チェック（簡易版）
            $userSql = "SELECT * FROM users WHERE id = ?";
            $user = db()->fetchOne($userSql, [$userId]);
            return !empty($user['email']) && !empty($user['name']);
            
        case 'all_complete':
            // 全条件達成（簡易版: 他の全バッジ獲得）
            $totalBadgesSql = "SELECT COUNT(*) as count FROM gamification_badges 
                              WHERE badge_key != 'ai_master'";
            $totalBadges = db()->fetchOne($totalBadgesSql);
            
            $userBadgesSql = "SELECT COUNT(*) as count FROM user_badges 
                             WHERE user_id = ?";
            $userBadges = db()->fetchOne($userBadgesSql, [$userId]);
            
            return ($userBadges['count'] ?? 0) >= ($totalBadges['count'] ?? 1);
            
        default:
            return false;
    }
}

// ============================================
// 目的別学習教材表示機能
// ============================================

/**
 * アンケート結果に基づいてコースを推薦
 * 
 * @param int $userId ユーザーID
 * @return array 推薦コースのID配列
 */
function getRecommendedCourses($userId) {
    try {
        // アンケート回答を取得
        $sql = "SELECT sq.question_key, usr.answer_value
               FROM user_survey_responses usr
               JOIN survey_questions sq ON usr.question_id = sq.id
               WHERE usr.user_id = ?";
        
        $responses = db()->fetchAll($sql, [$userId]);
        
        if (empty($responses)) {
            // 未回答の場合は全コースを返す（順番順）
            return getAllCourseIds();
        }
        
        // 回答を連想配列に変換
        $answers = [];
        foreach ($responses as $response) {
            $answers[$response['question_key']] = $response['answer_value'];
        }
        
        // 推薦スコアを計算
        $courseScores = calculateCourseScores($answers);
        
        // スコア順にソート
        arsort($courseScores);
        
        // 上位3つのコースIDを返す
        return array_slice(array_keys($courseScores), 0, 3);
        
    } catch (Exception $e) {
        error_log("推薦コース取得エラー: " . $e->getMessage());
        return getAllCourseIds();
    }
}

/**
 * 全コースIDを取得
 * 
 * @return array コースID配列
 */
function getAllCourseIds() {
    $sql = "SELECT id FROM courses ORDER BY order_num";
    $courses = db()->fetchAll($sql);
    return array_column($courses, 'id');
}

/**
 * コース推薦スコアを計算
 * 
 * @param array $answers アンケート回答
 * @return array コースID => スコアの連想配列
 */
function calculateCourseScores($answers) {
    // 簡易的な推薦ロジック
    // 興味分野に基づいてコースをマッピング
    
    $courseMapping = [
        1 => [ // 初めてのプロンプトエンジニアリング
            'keywords' => ['対話型AI', 'ビジネスAI'],
            'base_score' => 100
        ],
        // 将来的に他のコースも追加
    ];
    
    $scores = [];
    
    // 全コースにベーススコアを設定
    foreach ($courseMapping as $courseId => $config) {
        $scores[$courseId] = $config['base_score'];
    }
    
    // 興味分野に基づいてスコア加算
    if (isset($answers['interest_areas'])) {
        $interestAreas = json_decode($answers['interest_areas'], true) ?: [];
        
        foreach ($courseMapping as $courseId => $config) {
            foreach ($config['keywords'] as $keyword) {
                if (in_array($keyword, $interestAreas)) {
                    $scores[$courseId] += 50;
                }
            }
        }
    }
    
    // 学習目的に基づいてスコア加算
    if (isset($answers['learning_goal'])) {
        $learningGoals = json_decode($answers['learning_goal'], true) ?: [];
        
        // 業務効率化・キャリアアップを選んだ人には全コースを推薦
        if (in_array('業務効率化・生産性向上', $learningGoals) || 
            in_array('キャリアアップ・スキルアップ', $learningGoals)) {
            foreach ($scores as $courseId => $score) {
                $scores[$courseId] += 30;
            }
        }
    }
    
    return $scores;
}

/**
 * ウェルカムメール送信
 *
 * 新規登録ユーザーにウェルカムメールを送信
 *
 * @param string $email メールアドレス
 * @param string $name ユーザー名
 * @return bool 成功/失敗
 */
function sendWelcomeEmail($email, $name) {
    $subject = '【AI活用学習プラットフォーム】ご登録ありがとうございます';

    $body = <<<HTML
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Noto Sans JP', 'Hiragino Sans', 'Meiryo', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF5252 100%);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .button {
            display: inline-block;
            background: #FF6B6B;
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 48px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 18px;
            margin: 20px 0;
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
            transition: all 0.3s ease;
        }
        .button:hover {
            background: #FF5252;
            box-shadow: 0 6px 16px rgba(255, 107, 107, 0.5);
            transform: translateY(-2px);
        }
        .features {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        .features h3 {
            margin-top: 0;
            color: #FF6B6B;
        }
        .features ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .features li {
            padding: 8px 0;
            padding-left: 24px;
            position: relative;
        }
        .features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #FF6B6B;
            font-weight: bold;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AI活用学習プラットフォーム</h1>
        </div>
        <div class="content">
            <p class="greeting">{$name} 様</p>

            <div class="message">
                <p>この度は、AI活用学習プラットフォームにご登録いただき、誠にありがとうございます。</p>
                <p>ChatGPTをはじめとする生成AIを使った仕事の進め方を、わかりやすく学べるコースをご用意しています。</p>
            </div>

            <div style="text-align: center; margin: 40px 0;">
                <a href="https://yojitu.com/chatgpt-learning-platform/dashboard.php" class="button">今すぐ学習を始める →</a>
            </div>

            <div class="features">
                <h3>このプラットフォームでできること</h3>
                <ul>
                    <li>AIに上手に質問する方法を学べます</li>
                    <li>実際に使える例文（プロンプト）がたくさん見られます</li>
                    <li>クイズで楽しく理解度をチェックできます</li>
                    <li>仕事ですぐに使えるテクニックが身につきます</li>
                </ul>
            </div>

            <div class="message">
                <p>まずは「初めてのプロンプトエンジニアリング」コースから始めてみましょう。</p>
                <p>わからないことがあれば、各レッスンページの「わからないことがあれば質問」ボタンから、いつでもお気軽にご質問ください。</p>
            </div>

            <p>それでは、楽しい学習をお楽しみください！</p>
        </div>
        <div class="footer">
            <p>AI活用学習プラットフォーム 運営チーム</p>
            <p><a href="https://yojitu.com/chatgpt-learning-platform" style="color: #6c757d;">https://yojitu.com/chatgpt-learning-platform</a></p>
        </div>
    </div>
</body>
</html>
HTML;

    try {
        return sendEmail($email, $subject, $body);
    } catch (Exception $e) {
        error_log('Welcome email error: ' . $e->getMessage());
        // メール送信失敗しても登録処理は継続
        return false;
    }
}
