# Gemini API移行完了レポート

**日付**: 2025-12-19
**理由**: 無料運用のため、OpenAI API（有料）からGoogle Gemini API（無料枠）に移行

---

## 📊 移行概要

### 変更前（OpenAI API）
- **モデル**: GPT-3.5-turbo
- **料金**: 使用量課金（$0.0005/1K入力トークン、$0.0015/1K出力トークン）
- **月額コスト予測**: 100ユーザー×100回 = 約$50-100/月

### 変更後（Gemini API）
- **モデル**: Gemini 1.5 Flash
- **料金**: **完全無料**（1,500リクエスト/日まで）
- **月額コスト**: **¥0**

---

## ✅ 実装内容

### 1. Composer依存関係の追加
**ファイル**: `composer.json`

```json
"require": {
    "google/generative-ai-php": "^0.2"
}
```

### 2. Gemini API実装
**ファイル**: `api/gemini.php`（新規作成、150行）

- cURL経由でGemini APIを直接呼び出し
- キャッシュ機能実装（既存のSHA256ハッシュベース）
- エラーハンドリング（429: 無料枠超過、500: API障害）
- レスポンス形式の変換（Gemini形式 → 統一JSON）

**主要コード**:
```php
$apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/'
          . GEMINI_MODEL . ':generateContent?key=' . GEMINI_API_KEY;

$data = [
    'contents' => [
        ['parts' => [['text' => $prompt]]]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'maxOutputTokens' => 1000
    ]
];

// cURL実行、レスポンス取得
```

### 3. 設定ファイル更新
**ファイル**: `includes/config.php`

```php
// Gemini API設定（無料枠: 1,500リクエスト/日）
define('GEMINI_API_KEY', $_ENV['GEMINI_API_KEY']);
define('GEMINI_MODEL', $_ENV['GEMINI_MODEL'] ?? 'gemini-1.5-flash');
```

### 4. 環境変数テンプレート更新
**ファイル**: `.env.example`

```env
# Gemini API設定（推奨 - 無料枠: 1,500リクエスト/日）
# API Keyは https://aistudio.google.com/app/apikey から取得
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_MODEL=gemini-1.5-flash
```

### 5. エディタ型レッスンのエンドポイント変更
**ファイル**: `includes/lesson-types/editor.php`

```javascript
// 変更前
const response = await fetch(`${appUrl}/api/chatgpt.php`, {...});

// 変更後
const response = await fetch(`${appUrl}/api/gemini.php`, {...});
```

**レスポンス表示も更新**:
```javascript
<span>モデル: ${data.model || 'gemini-1.5-flash'}</span>
<span>トークン数: ${data.tokens_used || 0}</span>
```

---

## 🎯 Gemini APIの無料枠

### 制限
- **1日のリクエスト上限**: 1,500リクエスト（Gemini 1.5 Flash）
- **RPM（Requests Per Minute）**: 15リクエスト/分
- **TPM（Tokens Per Minute）**: 100万トークン/分

### 超過時の挙動
- **HTTPステータス**: 429 Resource Exhausted
- **エラーメッセージ**: "Gemini APIの無料枠（1,500リクエスト/日）を超過しました。明日0時（UTC）にリセットされます。"
- **課金**: されない（無料枠のみ）

### 使用量予測
- **想定ユーザー数**: 100人
- **1人あたり使用回数**: 100回/月
- **月間総リクエスト**: 10,000リクエスト
- **1日平均**: 約333リクエスト
- **無料枠との比較**: **1,500リクエスト/日 > 333リクエスト/日**
- **結論**: **約4.5倍の余裕あり**

---

## 💡 さらなるコスト削減策

### 1. キャッシュ機能（既に実装済み）
- 同じプロンプト = キャッシュから返す（API呼び出しなし）
- 推定削減率: **50-70%**

### 2. ユーザー制限（検討中）
- 無料ユーザー: 10回/日
- 登録ユーザー: 30回/日
- プレミアムユーザー: 100回/日

---

## 📦 デプロイ手順

### 1. Composerパッケージのインストール
```bash
cd chatgpt-learning-platform
composer install
```

### 2. 環境変数の設定
`.env`ファイルを作成し、Gemini API Keyを設定:

```env
GEMINI_API_KEY=your_actual_gemini_api_key_here
GEMINI_MODEL=gemini-1.5-flash
```

**API Keyの取得方法**:
1. https://aistudio.google.com/app/apikey にアクセス
2. 「Create API Key」をクリック
3. 生成されたキーをコピーして`.env`に貼り付け

### 3. データベースのマイグレーション
既存のテーブル構造は変更なし。`api_usage_logs`テーブルの`service`カラムに`'gemini'`が記録されるようになります。

### 4. 動作確認
1. エディタ型レッスンを開く
2. プロンプトを入力して「実行する」をクリック
3. Geminiの応答が表示されることを確認

---

## 🔧 トラブルシューティング

### エラー: "Gemini APIの実行に失敗しました"
**原因**: API Keyが無効、またはネットワークエラー

**解決策**:
1. `.env`のGEMINI_API_KEYが正しいか確認
2. https://aistudio.google.com/app/apikey でAPI Keyを再生成
3. Xserverからhttps://generativelanguage.googleapis.comにアクセスできるか確認

### エラー: "無料枠を超過しました"
**原因**: 1日1,500リクエストを超えた

**解決策**:
- 翌日0時（UTC）まで待つ
- キャッシュ機能が有効か確認（`prompt_cache`テーブル）
- ユーザーごとのAPI制限を強化

---

## 📈 今後の拡張案

### 1. Gemini 1.5 Proへのアップグレード
- 無料枠: 50リクエスト/日
- より高精度な応答
- 複雑なプロンプトに対応

### 2. ハイブリッド構成
- 基礎コース: Gemini Flash（無料）
- プレミアムコース: GPT-4（有料）
- ユーザーが選択可能

### 3. API使用量ダッシュボード
- 管理画面に使用量グラフを表示
- リアルタイムで残り枠を確認

---

## ✨ まとめ

| 項目 | 変更前（OpenAI） | 変更後（Gemini） |
|------|----------------|----------------|
| 月額コスト | $50-100 | **¥0** |
| 1日の制限 | なし（課金） | 1,500リクエスト |
| モデル | GPT-3.5-turbo | Gemini 1.5 Flash |
| レスポンス品質 | 高 | 高（同等） |
| 実装難易度 | 中 | 中 |

**結論**: Gemini APIへの移行により、**完全無料**で運用可能になりました。無料枠内で十分に運用でき、コスト面での優位性が大きいです。
