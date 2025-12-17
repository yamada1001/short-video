# Cron設定手順

セミナー管理システムの自動メール送信機能（リマインダー・サンクスメール）を有効にするため、Cronの設定を行います。

## 📋 Cronスクリプト一覧

| スクリプト | 説明 | 実行タイミング（推奨） |
|---|---|---|
| `cron/send-reminders.php` | リマインダーメール送信<br>（明日開催のセミナー参加者向け） | 毎日 18:00 |
| `cron/send-thanks.php` | サンクスメール送信<br>（今日終了したセミナー出席者向け） | 毎日 22:00 |

---

## 🛠️ Xserverでの設定方法

### 1. サーバーパネルにログイン

[Xserverサーバーパネル](https://www.xserver.ne.jp/)にログイン

### 2. Cron設定画面を開く

- 「Cron設定」をクリック
- 対象ドメイン（yojitu.com）を選択

### 3. Cronジョブを追加

#### リマインダーメール（毎日18時）

```
分: 0
時: 18
日: *
月: *
曜日: *
コマンド: /usr/bin/php /home/xs545151/yojitu.com/public_html/seminar-system/cron/send-reminders.php
```

#### サンクスメール（毎日22時）

```
分: 0
時: 22
日: *
月: *
曜日: *
コマンド: /usr/bin/php /home/xs545151/yojitu.com/public_html/seminar-system/cron/send-thanks.php
```

---

## 🔍 動作確認

### 手動実行でテスト

SSHでサーバーに接続し、手動でスクリプトを実行して動作確認します。

```bash
# リマインダーメール送信テスト
cd /home/xs545151/yojitu.com/public_html/seminar-system
php cron/send-reminders.php

# サンクスメール送信テスト
php cron/send-thanks.php
```

### 実行権限の付与（必要な場合）

```bash
chmod +x cron/send-reminders.php
chmod +x cron/send-thanks.php
```

---

## 📝 ログ確認

Cronスクリプトの実行ログは以下に記録されます：

```
logs/app.log
```

エラーが発生した場合はこのログファイルを確認してください。

### ログの確認方法（SSH）

```bash
# 最新100行を表示
tail -n 100 /home/xs545151/yojitu.com/public_html/seminar-system/logs/app.log

# リアルタイムで監視
tail -f /home/xs545151/yojitu.com/public_html/seminar-system/logs/app.log

# Cron関連のログのみ抽出
grep '\[Cron\]' /home/xs545151/yojitu.com/public_html/seminar-system/logs/app.log
```

---

## ⚙️ カスタマイズ

### 実行時刻の変更

実行時刻を変更する場合は、Cron設定の「時」「分」を編集してください。

**例: リマインダーメールを17時30分に変更**

```
分: 30
時: 17
日: *
月: *
曜日: *
```

### リマインダー送信タイミングの変更

デフォルトでは「明日開催」のセミナーにリマインダーを送信しますが、これを変更する場合は `cron/send-reminders.php` のSQLクエリを編集してください。

**例: 2日前に送信する場合**

```php
// 変更前
AND DATE(s.start_datetime) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)

// 変更後
AND DATE(s.start_datetime) = DATE_ADD(CURDATE(), INTERVAL 2 DAY)
```

---

## 🐛 トラブルシューティング

### メールが送信されない

1. **SMTP設定を確認**
   - `.env` ファイルの `SMTP_HOST`, `SMTP_USERNAME`, `SMTP_PASSWORD` が正しいか確認

2. **ログを確認**
   ```bash
   tail -n 50 logs/app.log
   ```

3. **手動実行でエラーメッセージを確認**
   ```bash
   php cron/send-reminders.php
   ```

### Cronが実行されない

1. **Cron設定を確認**
   - サーバーパネルの「Cron設定」でジョブが正しく登録されているか確認

2. **ファイルパスを確認**
   - コマンド欄のパスが正しいか確認
   - `/home/xs545151/yojitu.com/public_html/seminar-system/cron/...`

3. **PHPパスを確認**
   - Xserverでは通常 `/usr/bin/php` ですが、環境によっては異なる場合があります
   ```bash
   which php
   ```

### 重複送信が発生する

`email_logs` テーブルで送信履歴を管理しており、同じメールを2回送信しない仕組みになっています。

万が一重複送信が発生する場合は、以下のSQLで送信履歴を確認してください：

```sql
SELECT * FROM email_logs
WHERE attendee_id = [参加者ID]
ORDER BY sent_at DESC;
```

---

## 📧 SMTP設定例

### Gmail（推奨）

1. Googleアカウントで「2段階認証」を有効化
2. 「アプリパスワード」を生成
3. `.env` に設定

```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@yojitu.com
MAIL_FROM_NAME=セミナー運営事務局
```

### Xserver メールサーバー

```env
SMTP_HOST=sv12345.xserver.jp
SMTP_PORT=587
SMTP_USERNAME=noreply@yojitu.com
SMTP_PASSWORD=your-mail-password
MAIL_FROM_ADDRESS=noreply@yojitu.com
MAIL_FROM_NAME=セミナー運営事務局
```

---

## ✅ 確認チェックリスト

- [ ] Cronジョブが登録されている
- [ ] `.env` にSMTP設定が記載されている
- [ ] 手動実行でメールが送信される
- [ ] ログファイルにエラーがない
- [ ] メール受信を確認できた

---

## 📞 サポート

問題が解決しない場合は、以下の情報とともにお問い合わせください：

- エラーログ（`logs/app.log` の該当部分）
- Cron設定のスクリーンショット
- `.env` の設定内容（パスワードは伏せてください）
