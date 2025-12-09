# dataディレクトリについて

このディレクトリには、本番環境のデータファイルが含まれています。

## ⚠️ 重要なファイル（Gitで管理しない）

以下のファイルは本番データのため、Gitで管理されていません：

- `bni_system.db` - SQLiteデータベース（本番データ）
- `bni_system.db-shm` - SQLite WALモード一時ファイル
- `bni_system.db-wal` - SQLite WALモード一時ファイル
- `members.json` - ユーザー情報（本番パスワード含む）
- `*.csv` - 週次アンケートデータ（旧形式）

## 🔧 初回セットアップ手順

### 1. members.jsonの作成

```bash
cp members.json.sample members.json
```

### 2. members.jsonの編集

`members.json` を開いて、実際のユーザー情報と本番パスワードを設定してください。

**サンプルファイルのパスワード**: すべて `password` になっています。

パスワードハッシュの生成方法：
```bash
php -r "echo password_hash('あなたのパスワード', PASSWORD_DEFAULT);"
```

### 3. データベースの初期化

```bash
cd ..
php database/init_production_db.php
```

このスクリプトは以下を実行します：
- データベーススキーマ（テーブル）の作成
- `members.json` からユーザーを `users` テーブルにインポート

## 📁 ファイル説明

| ファイル | 説明 | Git管理 |
|---------|------|---------|
| `members.json.sample` | ユーザー情報のテンプレート | ✅ Yes |
| `members.json` | 実際のユーザー情報（本番） | ❌ No |
| `bni_system.db` | SQLiteデータベース（本番） | ❌ No |
| `pitch/` | ピッチファイル保存ディレクトリ | ❌ No |
| `*.csv` | 週次データ（旧形式、互換性のため残存） | ❌ No |

## 🔒 セキュリティ

- **絶対に** `members.json` や `bni_system.db` をGitにコミットしないでください
- パスワードハッシュは外部に漏らさないでください
- `.gitignore` の設定を変更しないでください
