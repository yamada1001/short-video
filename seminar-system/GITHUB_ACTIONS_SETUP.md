# GitHub Actions 自動デプロイ設定手順

mainブランチにpushするだけでXserverに自動デプロイされる仕組みです。

## 📋 事前準備

- [ ] GitHubリポジトリ作成済み
- [ ] Xserver SSH接続情報確認済み
- [ ] SSH秘密鍵取得済み

---

## 🔑 ステップ1: SSH鍵の準備

### XserverでSSH鍵を生成（まだの場合）

1. Xserverサーバーパネルにログイン
2. 「SSH設定」を開く
3. 「SSH設定」タブで「ONにする」
4. 「公開鍵認証用鍵ペアの生成」タブ
5. パスフレーズを設定して「確認画面へ進む」
6. 秘密鍵ファイル（`xserver_key.key`）をダウンロード

### 既存のSSH鍵を使う場合

ローカルの秘密鍵ファイル（`~/.ssh/id_rsa` など）の内容をコピー:

```bash
cat ~/.ssh/id_rsa
```

---

## 🔧 ステップ2: GitHub Secretsの設定

### 1. GitHubリポジトリを開く

```
https://github.com/yamadaren/yojitu.com
```

### 2. Settings → Secrets and variables → Actions

### 3. 以下のSecretを追加

#### `XSERVER_SSH_KEY` （必須）

- 「New repository secret」をクリック
- Name: `XSERVER_SSH_KEY`
- Secret: SSH秘密鍵の内容を貼り付け

```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAABlwAAAAdzc2gtcn
...（秘密鍵の中身全体）...
-----END OPENSSH PRIVATE KEY-----
```

#### `XSERVER_HOST` （必須）

- Name: `XSERVER_HOST`
- Secret: `sv12345.xserver.jp` （あなたのサーバー番号に変更）

#### `XSERVER_USER` （必須）

- Name: `XSERVER_USER`
- Secret: `xs545151` （あなたのサーバーIDに変更）

#### `XSERVER_PATH` （必須）

- Name: `XSERVER_PATH`
- Secret: `~/yojitu.com/public_html/seminar-system`

---

## ✅ ステップ3: 動作確認

### 1. ワークフローファイルを確認

```bash
# ローカルリポジトリで確認
cat .github/workflows/deploy-seminar-system.yml
```

### 2. GitHubにpush

```bash
git add .github/workflows/deploy-seminar-system.yml
git commit -m "Add: GitHub Actions自動デプロイ設定"
git push origin main
```

### 3. GitHub Actionsで確認

1. GitHubリポジトリの「Actions」タブを開く
2. 「Deploy Seminar System to Xserver」ワークフローをクリック
3. 実行中のジョブを確認
4. ✅ 緑色のチェックマークが表示されれば成功

---

## 🚀 使い方

### 自動デプロイ（Push時）

```bash
# seminar-systemディレクトリ内のファイルを編集
nano seminar-system/public/index.php

# コミット
git add seminar-system/
git commit -m "Update: セミナーフォーム改善"

# Push → 自動的にXserverにデプロイされる
git push origin main
```

### 手動デプロイ

1. GitHubリポジトリの「Actions」タブ
2. 「Deploy Seminar System to Xserver」を選択
3. 「Run workflow」→「Run workflow」

---

## 📝 デプロイされるファイル

### デプロイされる
- `seminar-system/` 内の全ファイル

### デプロイされない（除外）
- `.git/`
- `.github/`
- `node_modules/`
- `vendor/`（サーバー側で再生成）
- `.env`（手動で設定）
- `logs/*.log`（ログファイル）
- `uploads/**/*.pdf`（アップロード済みPDF）

---

## 🔄 デプロイ時の自動処理

1. **ファイル同期**: rsyncでXserverに転送
2. **Composer実行**: `composer install --no-dev`
3. **パーミッション設定**: 自動的に正しい権限を設定
4. **ディレクトリ作成**: `logs/`, `uploads/seminars/` を作成

---

## 🐛 トラブルシューティング

### デプロイが失敗する

#### SSH接続エラー

**エラー**: `Permission denied (publickey)`

**原因**: SSH鍵が正しく設定されていない

**解決方法**:
1. GitHub Secretsの`XSERVER_SSH_KEY`を確認
2. 秘密鍵の内容が完全にコピーされているか確認
3. `-----BEGIN` から `-----END` まで全て含まれているか確認

#### rsyncエラー

**エラー**: `rsync: command not found`

**原因**: rsyncがインストールされていない（通常はインストール済み）

**解決方法**:
```bash
# Xserverにrsyncがあるか確認
ssh xs545151@sv12345.xserver.jp "which rsync"
```

#### Composer実行エラー

**エラー**: `composer: command not found`

**原因**: Composerがインストールされていない

**解決方法**:
```bash
# SSH接続してComposerインストール
ssh xs545151@sv12345.xserver.jp
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### .envファイルが存在しない

**警告**: `.env file does not exist. Please create it manually.`

**解決方法**:
```bash
# SSH接続
ssh xs545151@sv12345.xserver.jp
cd ~/yojitu.com/public_html/seminar-system

# .env作成
cp .env.example .env
nano .env
# 必要な設定を記入
```

---

## 🔒 セキュリティ

### 注意点

1. **秘密鍵は絶対にコミットしない**
   - `.gitignore`で除外されています
   - GitHub Secretsのみに保存

2. **`.env`は自動デプロイされない**
   - サーバー側で手動作成
   - 機密情報を含むため

3. **SSH鍵のパーミッション**
   - GitHub Actionsが自動的に設定

---

## 📊 デプロイログの確認

### GitHubで確認

1. リポジトリの「Actions」タブ
2. 最新のワークフロー実行を選択
3. 「deploy」ジョブをクリック
4. 各ステップの詳細ログを確認

### Xserverで確認

```bash
# SSH接続
ssh xs545151@sv12345.xserver.jp
cd ~/yojitu.com/public_html/seminar-system

# ログ確認
tail -n 50 logs/app.log
```

---

## 🎯 ワークフローのカスタマイズ

### デプロイブランチを変更

`.github/workflows/deploy-seminar-system.yml`:

```yaml
on:
  push:
    branches:
      - production  # mainからproductionに変更
```

### デプロイタイミングを変更

```yaml
on:
  push:
    branches:
      - main
    paths:
      - 'seminar-system/**'  # このディレクトリのみ
  workflow_dispatch:  # 手動実行も可能
```

### Slack通知を追加

```yaml
- name: Send Slack notification
  if: always()
  uses: 8398a7/action-slack@v3
  with:
    status: ${{ job.status }}
    webhook_url: ${{ secrets.SLACK_WEBHOOK }}
```

---

## ✅ デプロイチェックリスト

### 初回デプロイ前

- [ ] GitHub Secretsを4つ全て設定
- [ ] SSH接続テスト成功
- [ ] `.env`ファイルをサーバーに手動作成
- [ ] データベース作成済み
- [ ] Square Webhook設定済み

### 毎回デプロイ後

- [ ] GitHub Actionsで緑色のチェックマーク確認
- [ ] サイトにアクセスしてエラーがないか確認
- [ ] ログファイル確認（`logs/app.log`）

---

## 🚀 まとめ

GitHub Actionsを使えば：

1. **自動デプロイ**: `git push`するだけ
2. **安全**: 秘密鍵はGitHub Secretsで管理
3. **高速**: rsyncで差分のみ転送
4. **確実**: パーミッション自動設定

これで開発がさらにスムーズになります！🎉
