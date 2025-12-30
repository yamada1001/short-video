# Protea Webアプリ

SEO記事作成支援システム - Pythonスクレイピング結果（Excel）を管理し、GPT APIで記事を自動生成するWebアプリケーション

## システム概要

- Excelファイル（6シート構造）をアップロードして一元管理
- キーワード管理・進捗管理
- GPT/Claude APIを使った記事自動生成
- WYSIWYG記事編集・プレビュー機能

## 技術スタック

- **バックエンド**: PHP 7.4+, SQLite（開発）/ MySQL（本番）
- **フロントエンド**: HTML/CSS/JavaScript, Chart.js, TinyMCE
- **ライブラリ**: PhpSpreadsheet, Guzzle
- **API**: OpenAI GPT API, Anthropic Claude API

## セットアップ（開発環境）

### 1. 依存関係インストール

```bash
cd protea-app
composer install
```

### 2. 環境変数設定

```bash
cp config/.env.example config/.env
# .envファイルを編集（開発時はUSE_MOCK_API=trueのまま）
```

### 3. データベース初期化

```bash
php database/init_db.php
```

### 4. ローカルサーバー起動

```bash
php -S localhost:8000 -t public
```

ブラウザで http://localhost:8000 にアクセス

## ディレクトリ構造

```
protea-app/
├── public/              # 公開ディレクトリ
│   ├── index.php       # キーワード一覧
│   ├── upload.php      # Excelアップロード
│   ├── detail.php      # キーワード詳細
│   ├── generate.php    # 記事生成
│   ├── edit.php        # 記事編集
│   └── dashboard.php   # ダッシュボード
├── src/                # PHPクラス
│   ├── Database.php    # DB接続
│   ├── ExcelParser.php # Excelパーサー
│   └── ...
├── config/             # 設定ファイル
├── database/           # SQLスキーマ
├── assets/             # CSS/JS
└── storage/            # アップロードファイル・ログ
```

## 使い方

1. **Excelアップロード**: Pythonスクレイピング結果をアップロード
2. **キーワード管理**: 一覧表示・検索・フィルタ・詳細表示
3. **記事生成**: GPT APIで記事タイトル・本文を自動生成
4. **記事編集**: WYSIWYGエディタで編集・保存
5. **ダッシュボード**: 進捗確認・統計表示

## 本番環境デプロイ

詳細は `USER_GUIDE.md` を参照してください。

## ライセンス

Proprietary - YOJITU.COM
