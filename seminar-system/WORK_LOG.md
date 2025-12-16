# セミナー管理システム - 作業進捗ログ

## 📅 最終更新日時
**2025-12-16**

---

## 🎯 現在の状況

### プロジェクトステータス
- **フェーズ**: 仕様策定完了
- **進捗率**: 5%（仕様書作成済み）
- **次のステップ**: フェーズ1（基盤構築）開始待ち

### 完了済みタスク
- ✅ 要件定義（AskUserQuestion形式で実施）
- ✅ SPECIFICATION.md 作成
- ✅ Xserver対応設定追加
- ✅ WORK_LOG.md 作成

### 進行中タスク
- なし（実装開始待ち）

### 次に実施すること
1. ディレクトリ構造作成
2. composer.json作成
3. .env.example作成
4. .htaccess作成
5. Database.phpクラス作成

---

## 📋 実装フェーズ詳細

### フェーズ1: 基盤構築（1週間）

#### タスク一覧
- [ ] ディレクトリ構造作成
  - [ ] public/
  - [ ] public/admin/
  - [ ] public/assets/css/
  - [ ] public/assets/js/
  - [ ] src/
  - [ ] config/
  - [ ] database/
  - [ ] uploads/seminars/
  - [ ] logs/
  - [ ] cron/

- [ ] 設定ファイル作成
  - [ ] composer.json
  - [ ] .env.example
  - [ ] .htaccess
  - [ ] .gitignore
  - [ ] config/config.php

- [ ] 基本クラス実装
  - [ ] src/Database.php（PDO接続）
  - [ ] src/Logger.php（ログ記録）
  - [ ] src/helpers.php（ヘルパー関数）

- [ ] データベースセットアップ
  - [ ] database/schema.sql（テーブル定義）
  - [ ] database/seeds.sql（初期データ）
  - [ ] DB作成スクリプト実行

- [ ] Square APIクライアント
  - [ ] src/SquareClient.php（Payment Links作成）
  - [ ] Webhook署名検証実装

#### 進捗状況
- **開始日**: 未定
- **完了予定**: 未定
- **進捗率**: 0%

---

### フェーズ2: セミナー・参加者管理（1週間）

#### タスク一覧
- [ ] セミナー管理機能
  - [ ] src/Seminar.php
  - [ ] public/admin/seminars.php（一覧・作成・編集・削除）
  - [ ] PDFアップロード機能

- [ ] 申込フォーム
  - [ ] public/index.php（セミナー一覧表示）
  - [ ] 申込フォーム実装
  - [ ] トークン生成（欠席用・QRコード用）

- [ ] 参加者管理機能
  - [ ] src/Attendee.php
  - [ ] public/admin/attendees.php（一覧・検索・フィルター）
  - [ ] ステータス変更機能
  - [ ] CSVエクスポート

- [ ] 支払いページ
  - [ ] public/payment.php（参加者選択）
  - [ ] 繰越クレジット表示
  - [ ] Square Payment Link作成

- [ ] Webhook受信
  - [ ] public/webhook.php（決済完了通知）
  - [ ] ステータス自動更新（paid）

#### 進捗状況
- **開始日**: 未定
- **完了予定**: 未定
- **進捗率**: 0%

---

### フェーズ3: アンケート機能（1週間）

#### タスク一覧
- [ ] アンケート管理
  - [ ] src/Survey.php
  - [ ] public/admin/surveys.php（質問管理）
  - [ ] 質問タイプ対応（text/radio/checkbox）

- [ ] 申込時アンケート
  - [ ] public/index.php に組み込み
  - [ ] 回答保存処理

- [ ] セミナー後アンケート
  - [ ] public/survey.php（トークン認証）
  - [ ] 回答保存処理

- [ ] 回答結果表示
  - [ ] public/admin/survey-results.php
  - [ ] グラフ表示（Chart.js）

#### 進捗状況
- **開始日**: 未定
- **完了予定**: 未定
- **進捗率**: 0%

---

### フェーズ4: メール機能（1週間）

#### タスク一覧
- [ ] PHPMailer導入
  - [ ] composer require phpmailer/phpmailer
  - [ ] src/MailSender.php

- [ ] 申込完了メール
  - [ ] テンプレート作成
  - [ ] 欠席リンク生成
  - [ ] QRコードリンク生成

- [ ] リマインドメール
  - [ ] cron/send-reminder-mail.php
  - [ ] 前日自動送信処理
  - [ ] Cron設定

- [ ] サンクスメール
  - [ ] cron/send-thanks-mail.php
  - [ ] PDF添付処理
  - [ ] アンケートリンク生成
  - [ ] セミナー終了後自動送信

- [ ] 個別メール送信
  - [ ] public/admin/send-mail.php
  - [ ] 参加者選択機能

- [ ] メール設定画面
  - [ ] public/admin/emails.php
  - [ ] テンプレート編集
  - [ ] 送信者名設定

#### 進捗状況
- **開始日**: 未定
- **完了予定**: 未定
- **進捗率**: 0%

---

### フェーズ5: QRコード・欠席機能（1週間）

#### タスク一覧
- [ ] QRコード生成
  - [ ] src/QRCodeGenerator.php
  - [ ] public/checkin.php（QRコード表示）
  - [ ] qrcode.js 導入

- [ ] QRスキャンチェックイン
  - [ ] public/admin/checkin-scan.php
  - [ ] カメラアクセス実装
  - [ ] トークン検証
  - [ ] ステータス更新（attended）

- [ ] 欠席フォーム
  - [ ] public/cancel.php（トークン認証）
  - [ ] 欠席理由入力
  - [ ] ステータス更新（absent）
  - [ ] クレジット計算

- [ ] 繰越クレジット管理
  - [ ] クレジット表示ロジック
  - [ ] クレジット適用ロジック
  - [ ] 管理画面での確認機能

#### 進捗状況
- **開始日**: 未定
- **完了予定**: 未定
- **進捗率**: 0%

---

### フェーズ6: テスト・本番移行（1週間）

#### タスク一覧
- [ ] テスト
  - [ ] 申込フロー全体テスト
  - [ ] 決済フローテスト（Sandbox）
  - [ ] メール送信テスト
  - [ ] QRコードテスト
  - [ ] 欠席・クレジットテスト

- [ ] セキュリティチェック
  - [ ] SQLインジェクション対策確認
  - [ ] XSS対策確認
  - [ ] CSRF対策確認
  - [ ] ファイルアップロード脆弱性確認
  - [ ] .envアクセス制限確認

- [ ] 本番環境デプロイ
  - [ ] Xserverへアップロード
  - [ ] ファイルパーミッション設定
  - [ ] .htaccess設定
  - [ ] DB作成・インポート
  - [ ] .env設定（本番API鍵）
  - [ ] Cron設定

- [ ] 動作確認
  - [ ] 本番環境での全機能テスト
  - [ ] メール送信確認
  - [ ] 決済テスト（Production）

- [ ] ドキュメント作成
  - [ ] README.md
  - [ ] 管理者マニュアル
  - [ ] デプロイ手順書

#### 進捗状況
- **開始日**: 未定
- **完了予定**: 未定
- **進捗率**: 0%

---

## 🐛 問題・課題

### 現在の問題
なし

### 解決済み問題
- ✅ BNI特定の記述を削除してシンプルなセミナーシステムに変更
- ✅ PHP 8.3 → 8.1 に変更（Xserver対応）
- ✅ Xserver用 .htaccess設定を追加

---

## 📝 メモ・注意事項

### Xserver特有の注意点
1. **PHP 8.1のみ対応**（8.3は使えない）
2. **ファイルパーミッション**
   - ディレクトリ: 705
   - PHPファイル: 604
   - 書き込み可能ディレクトリ: 707
   - .env: 600
3. **外部CSSが403エラーになる可能性**
   - BNIシステムで発生した問題
   - 対策: インラインCSSまたはパーミッション調整
4. **Composer実行**
   - SSH接続でcomposer install実行
   - vendorディレクトリは.gitignore

### セキュリティ要件
- トークンは64文字ランダム（bin2hex(random_bytes(32))）
- Webhook署名検証必須（HMAC-SHA256）
- .envファイルへのアクセス禁止
- SQLインジェクション対策（PDOプリペアドステートメント）
- XSS対策（htmlspecialchars()）

### デザイン方針
- 無印良品スタイル（ミニマルデザイン）
- Noto Sans JP フォント
- シンプルな色使い（白・グレー・アクセントカラー）
- 余白を多めに

---

## 🔄 変更履歴

### 2025-12-16
- 仕様書作成完了（SPECIFICATION.md）
- Xserver対応設定追加
- WORK_LOG.md 作成
- システム名を「セミナー管理システム」に変更（BNI削除）

---

## 💡 次回セッション開始時の確認事項

このファイルを読み込んで、以下を確認してください：

1. **現在のフェーズ**: 上記「プロジェクトステータス」を確認
2. **次にやること**: 「次に実施すること」リストを確認
3. **問題があるか**: 「問題・課題」セクションを確認
4. **前回の作業内容**: 「変更履歴」を確認

**このログファイルは実装の各段階で必ず更新してください。**
