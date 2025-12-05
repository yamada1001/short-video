# BNI Slide System - Cron設定ガイド

週次リマインダーメールを自動送信するためのcron設定手順です。

## 📅 実行スケジュール

| タイミング | 曜日 | 時刻 | 内容 |
|----------|------|------|------|
| 1回目 | 金曜日 | 19:00 | 未回答者に初回リマインド |
| 2回目 | 水曜日 | 20:00 | まだ未回答の方に2回目リマインド |
| 3回目 | 木曜日 | 20:00 | **回答済みの方**に更新情報確認 |

## 🛠️ Xserverでのcron設定手順

### 1. サーバーパネルにログイン

https://www.xserver.ne.jp/ からサーバーパネルにログイン

### 2. cron設定を開く

サーバーパネル → 「cron設定」をクリック

### 3. cron設定を追加

「cron設定追加」タブをクリックし、以下の3つを追加してください。

---

#### ✅ 金曜日19時（初回リマインド）

- **コマンド**:
  ```bash
  /usr/bin/php /home/xs545151/yojitu.com/public_html/bni-slide-system/cron_send_reminder.php friday >> /home/xs545151/yojitu.com/public_html/bni-slide-system/logs/reminder.log 2>&1
  ```

- **実行時間**:
  - 分: `0`
  - 時: `19`
  - 日: `*`
  - 月: `*`
  - 曜日: `5` (金曜日)

- **コメント**: `BNI週次リマインド1回目（金曜19時）`

---

#### ✅ 水曜日20時（2回目リマインド）

- **コマンド**:
  ```bash
  /usr/bin/php /home/xs545151/yojitu.com/public_html/bni-slide-system/cron_send_reminder.php wednesday >> /home/xs545151/yojitu.com/public_html/bni-slide-system/logs/reminder.log 2>&1
  ```

- **実行時間**:
  - 分: `0`
  - 時: `20`
  - 日: `*`
  - 月: `*`
  - 曜日: `3` (水曜日)

- **コメント**: `BNI週次リマインド2回目（水曜20時）`

---

#### ✅ 木曜日20時（最終リマインド）

- **コマンド**:
  ```bash
  /usr/bin/php /home/xs545151/yojitu.com/public_html/bni-slide-system/cron_send_reminder.php thursday >> /home/xs545151/yojitu.com/public_html/bni-slide-system/logs/reminder.log 2>&1
  ```

- **実行時間**:
  - 分: `0`
  - 時: `20`
  - 日: `*`
  - 月: `*`
  - 曜日: `4` (木曜日)

- **コメント**: `BNI週次リマインド3回目（木曜20時）`

---

### 4. ログディレクトリを作成

SSHまたはFTPで以下のディレクトリを作成してください：

```bash
mkdir -p /home/xs545151/yojitu.com/public_html/bni-slide-system/logs
chmod 755 /home/xs545151/yojitu.com/public_html/bni-slide-system/logs
```

## 🧪 テスト実行

cron設定前に、コマンドラインから手動実行してテストできます：

### SSH接続

```bash
ssh xs545151@xs545151.xsrv.jp
```

### 手動テスト実行

```bash
# 金曜日リマインド
php /home/xs545151/yojitu.com/public_html/bni-slide-system/cron_send_reminder.php friday

# 水曜日リマインド
php /home/xs545151/yojitu.com/public_html/bni-slide-system/cron_send_reminder.php wednesday

# 木曜日リマインド
php /home/xs545151/yojitu.com/public_html/bni-slide-system/cron_send_reminder.php thursday
```

## 📊 ログ確認

実行ログは以下のファイルに記録されます：

```bash
tail -f /home/xs545151/yojitu.com/public_html/bni-slide-system/logs/reminder.log
```

## ⚠️ 注意事項

1. **メール送信制限**
   - Xserverには1時間あたりのメール送信数制限があります
   - メンバー数が多い場合は、スクリプト内の送信間隔を調整してください

2. **タイムゾーン**
   - サーバー時刻（JST）を基準に実行されます
   - 実行時刻がずれる場合は、サーバーのタイムゾーン設定を確認してください

3. **週の判定**
   - 金曜日の5:00 AMを週の境界としています
   - 金曜日5:00以降のリマインドは、次の金曜日の週が対象になります

## 🔧 トラブルシューティング

### メールが送信されない場合

1. **PHPパスの確認**
   ```bash
   which php
   # /usr/bin/php が表示されるか確認
   ```

2. **スクリプトの実行権限確認**
   ```bash
   ls -la /home/xs545151/yojitu.com/public_html/bni-slide-system/cron_send_reminder.php
   ```

3. **ログファイルの確認**
   ```bash
   cat /home/xs545151/yojitu.com/public_html/bni-slide-system/logs/reminder.log
   ```

### cron実行が動かない場合

- サーバーパネルの「cron設定」で設定が有効になっているか確認
- コマンドパスが正しいか確認
- ログディレクトリが存在し、書き込み権限があるか確認

## 📝 メール内容のカスタマイズ

メール内容を変更したい場合は、`cron_send_reminder.php` の `getReminderMailContent()` 関数を編集してください。

---

設定完了後、次の実行タイミングでリマインダーメールが自動送信されます。
