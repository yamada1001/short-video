#!/bin/bash
# ======================================================
# 本番環境バックアップスクリプト
# ======================================================
# 用途: Xserverの本番環境から全ファイルをローカルにバックアップ
# 実行頻度: 月1回推奨（重要な変更前は必ず実行）
# 実行方法: chmod +x backup-production.sh && ./backup-production.sh
# ======================================================

# 設定
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_ROOT="$HOME/Backups/yojitu.com"
BACKUP_DIR="$BACKUP_ROOT/$DATE"

# FTP認証情報（環境変数から取得）
FTP_SERVER="${FTP_SERVER:-ftp.xserver.jp}"  # Xserverのデフォルト
FTP_USER="${FTP_USER}"
FTP_PASS="${FTP_PASS}"
REMOTE_DIR="/yojitu.com/public_html"

echo "======================================"
echo "本番環境バックアップ開始"
echo "======================================"
echo "日時: $(date '+%Y-%m-%d %H:%M:%S')"
echo "保存先: $BACKUP_DIR"
echo ""

# バックアップディレクトリ作成
mkdir -p "$BACKUP_DIR"

# FTP認証情報チェック
if [ -z "$FTP_USER" ] || [ -z "$FTP_PASS" ]; then
    echo "❌ エラー: FTP認証情報が設定されていません"
    echo ""
    echo "以下の環境変数を設定してください："
    echo "  export FTP_USER='あなたのFTPユーザー名'"
    echo "  export FTP_PASS='あなたのFTPパスワード'"
    echo ""
    echo "または、.envファイルを作成してください："
    echo "  echo 'FTP_USER=あなたのFTPユーザー名' > .env"
    echo "  echo 'FTP_PASS=あなたのFTPパスワード' >> .env"
    echo "  source .env"
    exit 1
fi

# lftpがインストールされているか確認
if ! command -v lftp &> /dev/null; then
    echo "❌ エラー: lftpがインストールされていません"
    echo ""
    echo "Homebrewでインストールしてください："
    echo "  brew install lftp"
    exit 1
fi

echo "📥 バックアップ中..."
echo ""

# lftpでバックアップ実行
lftp -c "
set ftp:ssl-allow no
open -u $FTP_USER,$FTP_PASS $FTP_SERVER
mirror --verbose --only-newer --parallel=10 $REMOTE_DIR $BACKUP_DIR
bye
"

if [ $? -eq 0 ]; then
    echo ""
    echo "======================================"
    echo "✅ バックアップ完了"
    echo "======================================"
    echo "保存先: $BACKUP_DIR"

    # バックアップサイズ表示
    BACKUP_SIZE=$(du -sh "$BACKUP_DIR" | cut -f1)
    echo "サイズ: $BACKUP_SIZE"

    # 古いバックアップを削除（30日以上前）
    echo ""
    echo "🗑️  古いバックアップを削除中（30日以上前）..."
    find "$BACKUP_ROOT" -maxdepth 1 -type d -mtime +30 -exec rm -rf {} \;

    # 残りのバックアップ数表示
    BACKUP_COUNT=$(find "$BACKUP_ROOT" -maxdepth 1 -type d | wc -l)
    echo "現在のバックアップ数: $((BACKUP_COUNT - 1))個"

    echo ""
    echo "======================================"
else
    echo ""
    echo "======================================"
    echo "❌ バックアップ失敗"
    echo "======================================"
    echo "FTP認証情報またはサーバー接続を確認してください"
    exit 1
fi
