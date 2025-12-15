# BNI Payment System - 更新・メンテナンスガイド

## 目次
1. [日常的な管理作業](#日常的な管理作業)
2. [メンバー管理](#メンバー管理)
3. [支払いデータの確認](#支払いデータの確認)
4. [データベース管理](#データベース管理)
5. [システム更新](#システム更新)
6. [バックアップとリストア](#バックアップとリストア)
7. [トラブルシューティング](#トラブルシューティング)
8. [定期メンテナンス](#定期メンテナンス)

---

## 日常的な管理作業

### 毎週火曜日の作業

#### 1. 支払い状況確認

**朝（会議前）**:
1. 管理者ダッシュボードにアクセス
2. 支払い済みメンバーを確認
3. 未払いメンバーにリマインド送信（任意）

**昼（会議後）**:
1. 再度ダッシュボードで最終確認
2. CSVエクスポートでデータ保存
3. 会計ソフトへインポート（任意）

#### 2. CSVデータの保存

```
管理者ダッシュボード → 「CSVエクスポート」ボタン → ファイル保存
```

**推奨保存先**:
```
/BNI支払いデータ/
  └── 2025/
      ├── 2025-01-07_支払いデータ.csv
      ├── 2025-01-14_支払いデータ.csv
      └── 2025-01-21_支払いデータ.csv
```

---

## メンバー管理

### メンバーの追加

#### ケース1: 新規入会メンバー

**手順**:
1. https://yojitu.com/bni-payment-system/public/admin/members.php にアクセス
2. 「+ 新規メンバー追加」ボタンをクリック
3. 以下を入力:
   - **名前**: フルネーム（例: 佐藤美咲）
   - **メールアドレス**: 連絡先メールアドレス
   - **ステータス**: 「有効」を選択
4. 「保存」をクリック

**確認**:
- メンバー一覧に新しいメンバーが表示される
- メンバー支払いページのドロップダウンに名前が表示される

---

### メンバーの編集

#### ケース1: メールアドレス変更

**手順**:
1. メンバー管理画面でメンバーを探す
2. 「編集」ボタンをクリック
3. メールアドレスを修正
4. 「保存」をクリック

#### ケース2: 名前の訂正

**手順**:
1. メンバー管理画面でメンバーを探す
2. 「編集」ボタンをクリック
3. 名前を修正
4. 「保存」をクリック

**注意**: 過去の支払い記録にも新しい名前が反映されます。

---

### メンバーの削除 vs 無効化

#### 無効化（推奨）

**使用ケース**:
- 一時的な休会
- 退会（記録を保持したい）
- 長期欠席

**メリット**:
- 過去の支払い記録が保持される
- 統計データに影響しない
- 必要に応じて再有効化できる

**手順**:
1. メンバー管理画面で該当メンバーを編集
2. ステータスを「無効」に変更
3. 「保存」をクリック

**結果**:
- 支払いページのドロップダウンに表示されなくなる
- ダッシュボードの統計から除外される
- 過去の支払い記録は残る

---

#### 削除（非推奨）

**使用ケース**:
- テストアカウントの削除
- 誤って追加したメンバーの削除

**注意**: 削除すると過去の支払い記録も全て削除されます（復元不可）

**手順**:
1. メンバー管理画面で該当メンバーの「削除」ボタンをクリック
2. 確認ダイアログで「OK」をクリック

**結果**:
- メンバー情報が完全に削除される
- 過去の支払い記録も全て削除される
- **復元不可**

---

## 支払いデータの確認

### ダッシュボードでの確認

#### 統計カード

- **総メンバー数**: 有効なメンバーの総数
- **支払い済み**: 今週の会費を支払ったメンバー数
- **未払い**: まだ支払っていないメンバー数
- **合計金額**: 今週の総入金額

#### 支払い済みメンバー一覧

- メンバー名
- 支払い日時
- 緑のチェックマーク ✓

---

### データベースでの直接確認

#### SSH接続

```bash
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp
```

#### 今週の支払い確認

```sql
mysql -u xs545151_bnipay -p xs545151_bnipayment

-- 今週の支払い一覧
SELECT
    m.name,
    p.amount,
    p.week_of,
    p.paid_at
FROM payments p
JOIN members m ON p.member_id = m.id
WHERE p.week_of = DATE(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))
ORDER BY p.paid_at DESC;
```

#### 特定メンバーの支払い履歴

```sql
-- 山田太郎さんの全支払い履歴
SELECT
    week_of,
    amount,
    paid_at
FROM payments
WHERE member_id = (SELECT id FROM members WHERE name = '山田太郎')
ORDER BY week_of DESC;
```

#### 未払いメンバー確認

```sql
-- 今週未払いのメンバー一覧
SELECT
    m.id,
    m.name,
    m.email
FROM members m
WHERE m.active = 1
AND m.id NOT IN (
    SELECT member_id
    FROM payments
    WHERE week_of = DATE(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))
);
```

---

## データベース管理

### バックアップ

#### 毎週のバックアップ（推奨）

```bash
# SSH接続
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp

# バックアップディレクトリ作成
mkdir -p ~/backups/bni-payment-system

# バックアップ実行
mysqldump -u xs545151_bnipay -p xs545151_bnipayment > ~/backups/bni-payment-system/backup_$(date +%Y%m%d).sql

# 確認
ls -lh ~/backups/bni-payment-system/
```

#### 自動バックアップ（cron設定）

```bash
# cron編集
crontab -e

# 毎週月曜日 0時にバックアップ
0 0 * * 1 mysqldump -u xs545151_bnipay -p'8I]qcFPNNk?O' xs545151_bnipayment > ~/backups/bni-payment-system/backup_$(date +\%Y\%m\%d).sql
```

**注意**: パスワードに特殊文字が含まれる場合、シングルクォートで囲んでください。

---

### リストア（復元）

```bash
# SSH接続
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp

# バックアップファイルを確認
ls -lh ~/backups/bni-payment-system/

# リストア実行（注意: 既存データは上書きされます）
mysql -u xs545151_bnipay -p xs545151_bnipayment < ~/backups/bni-payment-system/backup_20251215.sql
```

---

### 古いバックアップの削除

```bash
# 30日以上前のバックアップを削除
find ~/backups/bni-payment-system/ -name "backup_*.sql" -mtime +30 -delete

# 削除前に確認
find ~/backups/bni-payment-system/ -name "backup_*.sql" -mtime +30 -ls
```

---

## システム更新

### コードの更新

#### ケース1: GitHub経由での更新

```bash
# SSH接続
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp

# プロジェクトディレクトリへ移動
cd ~/yojitu.com/public_html/bni-payment-system

# 最新コードを取得
git pull origin main

# Composer依存パッケージ更新
php8.3 composer.phar update --no-dev --optimize-autoloader
```

#### ケース2: 個別ファイルの更新

```bash
# ローカルからサーバーへアップロード（SCP）
scp -i ~/.ssh/xs545151.key -P 10022 \
  /path/to/local/file.php \
  xs545151@xs545151.xsrv.jp:~/yojitu.com/public_html/bni-payment-system/path/to/file.php
```

---

### .envファイルの更新

#### Square本番環境への切り替え

```bash
# SSH接続
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp

# .envファイル編集
cd ~/yojitu.com/public_html/bni-payment-system
vi .env

# 以下を変更:
# SQUARE_ENVIRONMENT=sandbox → production
# SQUARE_ACCESS_TOKEN=本番用トークン
# SQUARE_APPLICATION_ID=本番用ID
# SQUARE_LOCATION_ID=本番用ロケーションID
# SQUARE_WEBHOOK_SIGNATURE_KEY=本番用署名キー
```

または sed コマンドで一括変更:

```bash
sed -i 's|SQUARE_ENVIRONMENT=sandbox|SQUARE_ENVIRONMENT=production|' .env
sed -i 's|SQUARE_ACCESS_TOKEN=.*|SQUARE_ACCESS_TOKEN=本番用トークン|' .env
# ...以下同様
```

---

### Composer依存パッケージの更新

```bash
# SSH接続
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp

cd ~/yojitu.com/public_html/bni-payment-system

# パッケージ更新
php8.3 composer.phar update --no-dev

# セキュリティアップデートのみ
php8.3 composer.phar update --no-dev --with-dependencies square/square
```

**注意**: 更新前に必ずバックアップを取ってください。

---

## バックアップとリストア

### 完全バックアップ（データベース + ファイル）

```bash
# SSH接続
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp

# バックアップディレクトリ作成
mkdir -p ~/backups/bni-payment-system/$(date +%Y%m%d)

# データベースバックアップ
mysqldump -u xs545151_bnipay -p xs545151_bnipayment \
  > ~/backups/bni-payment-system/$(date +%Y%m%d)/database.sql

# ファイルバックアップ
tar -czf ~/backups/bni-payment-system/$(date +%Y%m%d)/files.tar.gz \
  -C ~/yojitu.com/public_html \
  bni-payment-system

# 確認
ls -lh ~/backups/bni-payment-system/$(date +%Y%m%d)/
```

---

### ローカルへのバックアップダウンロード

```bash
# ローカルマシンで実行
scp -i ~/.ssh/xs545151.key -P 10022 -r \
  xs545151@xs545151.xsrv.jp:~/backups/bni-payment-system/20251215 \
  ~/Desktop/BNI_Backup_20251215/
```

---

## トラブルシューティング

### 問題: 支払いが記録されない

#### 診断手順

1. **Square Dashboardで確認**
   - https://squareup.com/dashboard にログイン
   - Payments → 該当の決済が記録されているか確認

2. **Webhookログ確認**
```bash
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp
tail -f ~/yojitu.com/logs/bni-payment-system/webhook-*.log
```

3. **データベース確認**
```sql
mysql -u xs545151_bnipay -p xs545151_bnipayment

-- 最新5件の支払い記録
SELECT * FROM payments ORDER BY created_at DESC LIMIT 5;
```

4. **Webhook署名キー確認**
```bash
cat ~/yojitu.com/public_html/bni-payment-system/.env | grep SQUARE_WEBHOOK_SIGNATURE_KEY
```

Square Developer Dashboardの署名キーと一致するか確認。

---

### 問題: メンバーが支払いページに表示されない

#### 診断手順

1. **メンバーのステータス確認**
```sql
mysql -u xs545151_bnipay -p xs545151_bnipayment

SELECT id, name, email, active FROM members WHERE name = 'メンバー名';
```

`active` が `0` の場合、無効化されています。

2. **有効化**
```sql
UPDATE members SET active = 1 WHERE name = 'メンバー名';
```

または管理画面から編集。

---

### 問題: CSVエクスポートが動作しない

#### 診断手順

1. **PHPエラーログ確認**
```bash
tail -f ~/yojitu.com/logs/error_log
```

2. **パーミッション確認**
```bash
ls -la ~/yojitu.com/public_html/bni-payment-system/public/admin/export.php
chmod 644 ~/yojitu.com/public_html/bni-payment-system/public/admin/export.php
```

---

## 定期メンテナンス

### 毎週

- [ ] 支払い状況確認
- [ ] CSVエクスポート
- [ ] 未払いメンバーへリマインド（任意）

### 毎月

- [ ] データベースバックアップ
- [ ] ログファイル確認
- [ ] 古いログ削除（30日以上前）

### 四半期ごと

- [ ] Square API Credentials更新確認
- [ ] Composer依存パッケージ更新
- [ ] セキュリティアップデート確認
- [ ] Basic認証パスワード変更

### 年1回

- [ ] 完全バックアップ（データベース + ファイル）
- [ ] バックアップのローカル保存
- [ ] Square本番環境への移行（Sandboxから）
- [ ] システム全体の見直し

---

## ログ管理

### ログファイルの場所

```
~/yojitu.com/logs/bni-payment-system/
  ├── app-2025-12-15.log       # アプリケーションログ
  └── webhook-2025-12-15.log   # Webhookログ
```

### ログ確認

```bash
# リアルタイムログ監視
tail -f ~/yojitu.com/logs/bni-payment-system/app-*.log

# エラーのみ表示
grep ERROR ~/yojitu.com/logs/bni-payment-system/app-*.log

# 今日のWebhookイベント
grep "Webhook" ~/yojitu.com/logs/bni-payment-system/webhook-$(date +%Y-%m-%d).log
```

### ログローテーション（古いログ削除）

```bash
# 30日以上前のログを削除
find ~/yojitu.com/logs/bni-payment-system/ -name "*.log" -mtime +30 -delete

# 削除前に確認
find ~/yojitu.com/logs/bni-payment-system/ -name "*.log" -mtime +30 -ls
```

---

## Square設定の更新

### Webhookエンドポイント変更

1. Square Developer Dashboard にログイン
2. Applications → BNI Payment System → Webhooks
3. Endpoint URL: `https://yojitu.com/bni-payment-system/public/webhook.php`
4. Events: `payment.created`, `payment.updated` を選択
5. 「Save」をクリック
6. Signature Key をコピー
7. `.env` ファイルの `SQUARE_WEBHOOK_SIGNATURE_KEY` を更新

```bash
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp
cd ~/yojitu.com/public_html/bni-payment-system
sed -i 's|SQUARE_WEBHOOK_SIGNATURE_KEY=.*|SQUARE_WEBHOOK_SIGNATURE_KEY=新しいキー|' .env
```

---

## セキュリティメンテナンス

### Basic認証パスワード変更

```bash
# SSH接続
ssh -i ~/.ssh/xs545151.key -p 10022 xs545151@xs545151.xsrv.jp

# パスワード変更
cd ~/yojitu.com/public_html/bni-payment-system/public/admin
htpasswd -c .htpasswd admin
# 新しいパスワード入力

# パーミッション確認
chmod 600 .htpasswd
```

### .envファイルの保護確認

```bash
# .envファイルがWeb経由でアクセスできないか確認
curl https://yojitu.com/bni-payment-system/.env
# 「403 Forbidden」が返ってくることを確認
```

---

## サポート

### 技術サポート

- システム開発者に連絡
- GitHub Issues: （リポジトリURL）

### Square サポート

- Square Help Center: https://squareup.com/help
- Square Developer Forums: https://developer.squareup.com/forums

### Xserver サポート

- サーバーパネル: https://www.xserver.ne.jp/login_server.php
- サポート: https://support.xserver.ne.jp/

---

**最終更新**: 2025年12月15日
**バージョン**: 1.0.0
