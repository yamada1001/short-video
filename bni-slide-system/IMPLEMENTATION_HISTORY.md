# BNI Slide System - 実装履歴

## 📋 実装完了済み機能（2025年12月5日〜6日）

### 1. 自動保存機能（LocalStorage）✅

**実装日**: 2025-12-05

**概要**:
ユーザーがアンケートを入力中にブラウザを閉じてしまった場合でも、入力内容を復元できる自動保存機能を実装しました。

**技術仕様**:
- LocalStorageを使用したブラウザ側の下書き保存
- 3秒間の入力停止後に自動保存（デバウンス処理）
- ユーザーごとに保存キーを分離（`bni_survey_autosave_[メールアドレス]`）
- 動的フィールド（ビジター、リファーラル）の数も保存・復元

**主な変更ファイル**:
- `index.php` - 自動保存JavaScript実装

**主な機能**:
```javascript
// 3秒間の入力停止後に自動保存
const AUTOSAVE_INTERVAL = 3000;

// フォームデータを収集
function collectFormData() { ... }

// LocalStorageに保存
function saveToLocalStorage() { ... }

// ページ読み込み時に下書き復元バナーを表示
function checkAndRestoreDraft() { ... }
```

**ユーザーフィードバック**: "完璧です。めちゃめちゃ良いですね！"

---

### 2. 簡易ダッシュボード ✅

**実装日**: 2025-12-05

**概要**:
ログイン後のトップページに週次・月次のサマリー情報を表示するダッシュボードを実装しました。当初はindex.phpに統合されていましたが、ユーザー要望により独立したページに分離しました。

**技術仕様**:
- 独立したdashboard.phpページ
- APIエンドポイント（api_dashboard_stats.php）からデータ取得
- 4つのカード表示：
  1. 今週の提出状況（個人）
  2. 今週のチーム統計
  3. あなたの今週の統計
  4. あなたの今月の統計

**主な変更ファイル**:
- `dashboard.php` - ダッシュボードページ（新規作成）
- `api_dashboard_stats.php` - ダッシュボードAPI（新規作成）
- `index.php`, `my-data.php`, `profile.php`, `manual.php` - ナビゲーション更新

**API レスポンス例**:
```json
{
  "this_week": {
    "user": {
      "submitted": true,
      "attendance": "出席",
      "visitor_count": 2,
      "referral_amount": 150000,
      "thanks_slips": 3,
      "one_to_one": 1
    },
    "team": {
      "total_members": 50,
      "visitor_count": 25,
      "referral_amount": 5000000
    }
  },
  "this_month": {
    "user": {
      "visitor_count": 8,
      "referral_amount": 600000,
      "attendance_count": 4
    }
  }
}
```

**ユーザーフィードバック**: "ダッシュボードOK"

---

### 3. CSV/Excelエクスポート機能 ✅

**実装日**: 2025-12-05

**概要**:
管理者が週次データをCSVファイルとしてダウンロードできる機能を実装しました。当初はPhpSpreadsheetを使用したExcel出力を試みましたが、サーバー環境の制約により、Excel互換のUTF-8 BOM付きCSVに変更しました。

**技術仕様**:
- UTF-8 BOM（`\xEF\xBB\xBF`）付きCSV出力
- Excelで文字化けせずに開ける
- 管理者権限チェック
- ファイル名: `BNI_Weekly_Data_[週].csv`

**主な変更ファイル**:
- `api_export_csv.php` - CSVエクスポートAPI（新規作成）
- `assets/js/edit.js` - エクスポートボタンのイベント処理
- `api_load.php` - 週ファイル名をレスポンスに追加

**実装のポイント**:
```php
// UTF-8 BOM for Excel compatibility
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="BNI_Weekly_Data_' . $week . '.csv"');
echo "\xEF\xBB\xBF"; // UTF-8 BOM

// CSV出力
$output = fopen('php://output', 'w');
while (($row = fgetcsv($handle)) !== false) {
    fputcsv($output, $row);
}
```

**課題と解決**:
- **課題1**: PhpSpreadsheetの構文エラー（`use`文の不適切な使用）
- **解決1**: PhpSpreadsheetを削除し、シンプルなCSV出力に変更
- **課題2**: サーバーキャッシュによりエラーが残る
- **解決2**: ファイル名を`api_export_excel.php`から`api_export_csv.php`に変更
- **課題3**: 週パラメータが`undefined`になる
- **解決3**: api_load.phpに`week`フィールドを追加
- **課題4**: 管理者権限エラー
- **解決4**: members.jsonに`role: admin`を追加

**ユーザーフィードバック**: "csvデータ確認できました。OKです。"

---

### 4. パスワードリセット機能 ✅

**実装日**: 2025-12-05

**概要**:
メール送信機能を使用したパスワードリセット機能を実装しました。セキュアなトークン生成と24時間の有効期限を設定しています。

**技術仕様**:
- トークン: `bin2hex(random_bytes(16))` = 32文字のランダム文字列
- 有効期限: 24時間
- members.jsonにトークンと有効期限を保存
- メール送信: PHP mail()関数

**主な変更ファイル**:
- `forgot-password.php` - パスワードリセット申請ページ（新規作成）
- `api_send_reset_email.php` - リセットメール送信API（新規作成）
- `reset-password.php` - パスワード再設定ページ（新規作成）
- `api_reset_password.php` - パスワード更新API（新規作成）
- `login.php` - 「パスワードを忘れた方」リンク追加
- `data/members.json` - yamada@yojitu.com ユーザー追加

**フロー**:
1. ユーザーがメールアドレスを入力
2. システムが32文字のトークンを生成し、members.jsonに保存
3. リセットリンク（トークン付き）をメール送信
4. ユーザーがリンクをクリック
5. トークンと有効期限を検証
6. 新しいパスワードを設定（bcryptハッシュ化）
7. トークンを削除

**重要な修正**:
- **課題**: パスワードリセット後にログインできない
- **原因**: session_auth.phpは`password_hash`キーを参照するが、api_reset_password.phpは`password`キーに保存していた
- **解決**: 全ファイルで`password_hash`キーに統一

**セキュリティ対策**:
- パスワードはbcryptでハッシュ化
- トークンは暗号学的に安全な乱数生成
- 24時間の有効期限
- 使用後はトークンを削除

**ユーザーフィードバック**: "おっ！ログインできました！ありがとうございます🙇"

---

### 5. 監査ログ機能 ✅

**実装日**: 2025-12-05

**概要**:
全てのデータ変更操作（作成・更新・削除）を記録する監査ログ機能を実装しました。誰が、いつ、何をしたかを追跡できます。

**技術仕様**:
- JSON形式でログを保存（`data/audit_log.json`）
- 最大1000件のログを保持（古いログは自動削除）
- 記録内容: タイムスタンプ、アクション、対象、ユーザー情報、データ、IPアドレス、User Agent

**主な変更ファイル**:
- `includes/audit_logger.php` - 監査ログライブラリ（新規作成）
- `admin/audit_log.php` - 監査ログ閲覧ページ（新規作成）
- `api_save.php` - 新規アンケート提出時のログ記録
- `api_update_my_data.php` - ユーザー自己編集時のログ記録
- `api_update.php` - 管理者一括編集時のログ記録
- `admin/sitemap.php` - 監査ログへのリンク追加

**ログ記録関数**:
```php
writeAuditLog(
    $action,      // 'create', 'update', 'delete'
    $target,      // 'survey_data', 'user', etc.
    $data,        // 変更内容（配列）
    $userEmail,   // 操作ユーザーのメール
    $userName     // 操作ユーザーの名前
);
```

**ログエントリ構造**:
```json
{
  "id": "log_67520e8f12345.67890123",
  "timestamp": "2025-12-05 18:30:00",
  "action": "create",
  "target": "survey_data",
  "user_email": "yamada@yojitu.com",
  "user_name": "山田",
  "data": {
    "input_date": "2025-12-05",
    "attendance": "出席",
    "visitor_count": 2,
    "referral_count": 1
  },
  "ip_address": "123.45.67.89",
  "user_agent": "Mozilla/5.0..."
}
```

**管理画面機能**:
- ページネーション（50件/ページ）
- アクション別の色分け（作成=緑、更新=黄、削除=赤）
- 統計情報表示

**ユーザーフィードバック**: "OKです。ログみれました。"

---

### 6. 週次リマインダーメール機能 ✅

**実装日**: 2025-12-05〜06

**概要**:
3段階のリマインダーメールを自動送信する機能を実装しました。未回答者へのリマインドと、回答済みユーザーへの更新確認を行います。

**技術仕様**:
- cron実行用のPHPスクリプト
- 3段階のリマインド:
  1. **金曜日 19:00** - 未回答者に初回リマインド
  2. **水曜日 20:00** - まだ未回答の方に2回目リマインド
  3. **木曜日 20:00** - **回答済みの方**に更新情報確認

**主な変更ファイル**:
- `cron_send_reminder.php` - リマインダー送信スクリプト（新規作成）
- `test_send_reminder.php` - テスト送信スクリプト（新規作成）
- `CRON_SETUP.md` - Xserver cron設定ガイド（新規作成）
- `logs/.gitkeep` - ログディレクトリ作成
- `.gitignore` - ログファイルを除外

**cron設定**:
```bash
# 金曜日 19:00
0 19 * * 5 /usr/bin/php /path/to/cron_send_reminder.php friday >> /path/to/logs/reminder.log 2>&1

# 水曜日 20:00
0 20 * * 3 /usr/bin/php /path/to/cron_send_reminder.php wednesday >> /path/to/logs/reminder.log 2>&1

# 木曜日 20:00
0 20 * * 4 /usr/bin/php /path/to/cron_send_reminder.php thursday >> /path/to/logs/reminder.log 2>&1
```

**メール内容**:

**金曜日（初回リマインド）**:
```
件名: 【BNI】今週のアンケート提出のお願い
内容: 今週（2025年12月5日週）のアンケートをまだご提出いただいておりません。
      お手数ですが、下記URLよりご回答をお願いいたします。
```

**水曜日（2回目リマインド）**:
```
件名: 【BNI】アンケート提出のお願い（再送）
内容: 今週（2025年12月5日週）のアンケートをまだご提出いただいておりません。
      明日のミーティングまでに、ご回答をお願いいたします。
```

**木曜日（更新確認）**:
```
件名: 【BNI】更新情報はありませんか？
内容: 今週（2025年12月5日週）のアンケートをご提出いただき、ありがとうございます。
      その後、新たなリファーラルやビジターの追加など、更新すべき情報はございませんでしょうか？
      もし追加情報がございましたら、マイデータページよりいつでも更新が可能です。
      ※明日のミーティング当日の朝5時までに更新いただければスライドに反映されます。
```

**対象者の選定ロジック**:
```php
// 金曜日・水曜日: 未回答者
if (!in_array($emailLower, $submittedMembers)) {
    $targetMembers[] = ['email' => $email, 'name' => $user['name']];
}

// 木曜日: 回答済み者
if (in_array($emailLower, $submittedMembers)) {
    $targetMembers[] = ['email' => $email, 'name' => $user['name']];
}
```

**実装のポイント**:
- 対象週の判定にgetTargetFriday()を使用（金曜5時〜金曜5時の週定義）
- 提出済みメンバーはCSVファイルから抽出
- 送信間隔を0.5秒空けてサーバー負荷を軽減
- ログファイルに実行履歴を記録

**ユーザーフィードバック**: "OK.メール届きました。問題ありません！"

---

## 🚧 現在進行中の実装

### 8. SQLiteデータベース移行

**開始日**: 2025-12-06

**概要**:
現在のCSVファイルベースのデータ管理から、SQLiteデータベースへ移行します。50人以上のメンバーがいるため、パフォーマンスとデータ整合性の向上が期待できます。

**移行の理由**:
- メンバー数が50人を超え、CSVファイルの読み書きが非効率になってきた
- 複雑なクエリ（集計、フィルタリング）が困難
- データの整合性チェックが難しい
- 同時書き込みの競合リスク

**技術仕様**:
- SQLite3データベース（`data/bni_system.db`）
- 正規化されたテーブル設計:
  - `users` - ユーザー/メンバー情報
  - `survey_data` - 週次アンケートデータ（メイン）
  - `visitors` - ビジター情報（survey_dataと1対多）
  - `referrals` - リファーラル情報（survey_dataと1対多）
  - `audit_logs` - 監査ログ（JSONからSQLiteへ）

**完了済み**:
- ✅ `database/schema.sql` - テーブル定義作成

**今後の作業**:
1. データベース初期化スクリプト作成（`database/init_db.php`）
2. CSV→SQLite移行スクリプト作成（`database/migrate_csv_to_sqlite.php`）
3. データベース接続ライブラリ作成（`includes/db.php`）
4. 全APIファイルの書き換え（8〜10ファイル）:
   - api_save.php
   - api_load.php
   - api_update.php
   - api_update_my_data.php
   - api_dashboard_stats.php
   - api_load_my_data.php
   - cron_send_reminder.php
   - その他
5. テスト・検証
6. 本番デプロイ

**推定作業時間**: 8〜10時間

**ロールバック計画**:
- Gitで全ての変更を管理
- 問題があれば、すぐに以前のバージョンに戻せる
- CSVファイルは移行後も保持（バックアップ）

**ユーザー要望**:
> 「多分中途半端にやるより、一気に全部進めたほうが良いと思います。ただ、現状かなり複雑な構造のため、すぐ戻さないと悪い可能性もあるので、再度この時点で、コミットとプッシュが問題なくされているかを確認後、これまでの作業内容をMDファイルに追記して、一気に作業を進めてください。最悪gitで管理してるので、バージョンを戻します。」

---

## ⏭️ スキップした実装

### 7. 2段階認証（Google Authenticator対応）

**スキップ理由**:
ユーザー判断により、現在のシステムには不要と判断されました。

**ユーザーコメント**:
> 「私２段階認証、よくわかってないのですが、これ実装したほうがいいんですかね？？」
>
> （説明後）
>
> 「OKです。ではSQLの方やっていきましょうか？」

**判断理由**:
- 内部システムであり、外部からのアクセスリスクは低い
- ユーザーの利便性を優先
- 必要になった場合は後から追加可能

---

## 📝 その他の細かい改善

### メッセージ表示位置の変更
- 「すでに回答済みです」などのメッセージを、送信ボタンの上に表示するように変更
- ファイル: `index.php`

### 管理者権限チェックの追加
- 全ての管理者ページにセッション認証とロールチェックを追加
- ファイル: `admin/edit.php`, `admin/slide.php`, `admin/users.php`, `admin/sitemap.php`

### データ整合性の改善
- 空のビジター名をカウントしないように修正
- リファーラル金額0円のメンバーを非表示
- ファイル: `assets/js/svg-slide-generator.js`

### テストデータの生成
- 5週分、44レコードの仮想データを生成
- ファイル: `generate_sample_data.php`, `data/*.csv`

---

## 🔄 Git管理状況

**最終プッシュ**: 2025-12-06
**最終コミット**: `Feature: SQLite移行準備 - データベーススキーマ作成`

**コミット履歴** (最近10件):
1. SQLite移行準備 - スキーマ作成
2. 週次リマインダー機能実装
3. 監査ログ機能実装
4. パスワードリセット機能実装
5. CSVエクスポート機能実装
6. ダッシュボード分離
7. 自動保存機能実装
8. （その他の過去のコミット...）

---

## 📚 ドキュメント

### 作成済みドキュメント
- `README.md` - システム概要
- `MANUAL.md` - 利用マニュアル
- `WORK_LOG_2025-12-05.md` - 作業ログ（日次）
- `CRON_SETUP.md` - cron設定ガイド
- `IMPLEMENTATION_HISTORY.md` - 本ファイル

---

## 🎯 次のステップ

1. **SQLite移行を一気に完了させる**
   - データベース初期化
   - CSVデータ移行
   - 全APIファイルの書き換え
   - テスト・検証

2. **本番デプロイ**
   - Xserverへのアップロード
   - 動作確認

3. **ロールバック準備**
   - 問題があればGitで即座に戻せる体制

---

**作成日**: 2025-12-06
**最終更新**: 2025-12-06
