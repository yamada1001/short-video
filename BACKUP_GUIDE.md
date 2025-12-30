# バックアップガイド

本番環境（www.yojitu.com）のバックアップ方法を説明します。

## 📌 重要性

前回のインシデントのような全ファイル削除が起きた場合、バックアップがあれば**数分で復旧可能**です。

**推奨頻度:** 月1回 + 重要な変更前

---

## 🚀 初回セットアップ（1回だけ）

### 1. lftpのインストール

```bash
brew install lftp
```

### 2. FTP認証情報の設定

**方法A: 環境変数（推奨）**

`~/.zshrc` または `~/.bash_profile` に追加：

```bash
export FTP_USER='xs545151'  # XserverのFTPユーザー名
export FTP_PASS='your_password'  # XserverのFTPパスワード
```

設定後、ターミナル再起動または：

```bash
source ~/.zshrc
```

**方法B: .envファイル**

プロジェクトルートに `.env` ファイルを作成：

```bash
cd /Users/yamadaren/Movies/claude-code-projects/yojitu.com
echo 'FTP_USER=xs545151' > .env
echo 'FTP_PASS=your_password' >> .env
```

**注意:** `.env` ファイルは `.gitignore` に追加してください（機密情報のため）。

---

## 💾 バックアップ実行方法

### 1. スクリプト実行

```bash
cd /Users/yamadaren/Movies/claude-code-projects/yojitu.com
./backup-production.sh
```

### 2. バックアップ先

```
~/Backups/yojitu.com/
├── 20250101_120000/  # 2025年1月1日 12:00のバックアップ
├── 20250201_140000/  # 2025年2月1日 14:00のバックアップ
└── 20250301_160000/  # 2025年3月1日 16:00のバックアップ
```

**自動削除:** 30日以上前のバックアップは自動削除されます。

---

## 🔄 復旧方法（緊急時）

バックアップから復旧する場合：

### 方法1: GitHub経由（推奨）

1. 最新のバックアップディレクトリを確認
2. 必要なファイルをGitリポジトリにコピー
3. `git add` → `git commit` → `git push`
4. GitHub Actionsが自動デプロイ

### 方法2: FTPで直接アップロード

1. FileZilla/WinSCPで接続
2. バックアップディレクトリから必要なファイルをアップロード

---

## 📋 バックアップ運用ルール

### 必ずバックアップを取るべきタイミング

- [ ] 月初（月1回の定期バックアップ）
- [ ] GitHub Actionsのワークフロー変更前
- [ ] 大量のファイル削除前
- [ ] 本番環境に大きな変更を加える前

### バックアップ確認

定期的にバックアップが正常に取れているか確認：

```bash
ls -lh ~/Backups/yojitu.com/
```

---

## ⚠️ トラブルシューティング

### エラー: lftpがインストールされていません

```bash
brew install lftp
```

### エラー: FTP認証情報が設定されていません

環境変数 `FTP_USER` と `FTP_PASS` を設定してください（上記参照）。

### エラー: FTP接続失敗

- XserverのFTPユーザー名/パスワードを確認
- Xserverコンパネで「FTPアカウント」設定を確認
- サーバーアドレスが `ftp.xserver.jp` で正しいか確認

---

## 📊 バックアップサイズの目安

- 平均: 500MB〜2GB
- 所要時間: 5〜15分（回線速度による）

---

## 🔒 セキュリティ注意事項

- `.env` ファイルは**絶対にGitにコミットしない**
- FTPパスワードは他人に教えない
- バックアップディレクトリは定期的に外付けHDD等にも保存することを推奨
