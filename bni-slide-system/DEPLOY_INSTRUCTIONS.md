# 本番環境デプロイ手順（Xserver）

## 1. サーバーにSSH接続

```bash
ssh xs545151@yojitu.com
```

## 2. プロジェクトディレクトリに移動

```bash
cd /home/xs545151/yojitu.com/public_html/bni-slide-system
```

## 3. 最新コードをPull

```bash
git pull origin main
```

## 4. データベース移行を実行

```bash
php database/migrate_all.php --force
```

確認プロンプトで `y` を入力してEnter

## 5. ファイル権限を確認

```bash
chmod 666 data/bni_system.db
chmod 777 data/
```

## 6. 動作確認

以下のURLにアクセスして確認：

- ログイン: https://yojitu.com/bni-slide-system/login.php
- スライド表示: https://yojitu.com/bni-slide-system/admin/slide.php

---

## トラブルシューティング

### エラー: "no such column: is_active"

→ データベースが古い。手順4を再実行してください。

### エラー: "unable to open database file"

→ ファイル権限の問題。以下を実行：
```bash
chmod 666 data/bni_system.db
chmod 777 data/
```

### スライドが表示されない

1. データが正しく移行されたか確認：
```bash
sqlite3 data/bni_system.db "SELECT COUNT(*) FROM survey_data;"
```

2. エラーログ確認：
```bash
tail -50 logs/error.log
```

---

**作成日**: 2025-12-06
