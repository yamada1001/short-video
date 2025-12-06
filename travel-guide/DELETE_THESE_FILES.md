# 🗑️ サーバーから削除すべきファイル

以下のファイルはセキュリティ上、サーバーから削除してください。

## 削除対象ファイル（5つ）

### travel-guide 直下

```
/home/xs545151/yojitu.com/public_html/travel-guide/debug.php
/home/xs545151/yojitu.com/public_html/travel-guide/diagnose.php
/home/xs545151/yojitu.com/public_html/travel-guide/enable-auth.php
/home/xs545151/yojitu.com/public_html/travel-guide/generate-htpasswd.php
/home/xs545151/yojitu.com/public_html/travel-guide/test.php
```

## 削除方法

### Xserver ファイルマネージャーで削除

1. Xserverのファイルマネージャーにログイン
2. `/home/xs545151/yojitu.com/public_html/travel-guide/` に移動
3. 上記5つのファイルを選択して削除

### SSH で削除（SSH接続可能な場合）

```bash
cd /home/xs545151/yojitu.com/public_html/travel-guide
rm debug.php diagnose.php enable-auth.php generate-htpasswd.php test.php
```

## 削除確認

削除後、以下のURLにアクセスして404エラーが表示されることを確認：

```
https://yojitu.com/travel-guide/debug.php
https://yojitu.com/travel-guide/diagnose.php
https://yojitu.com/travel-guide/enable-auth.php
https://yojitu.com/travel-guide/generate-htpasswd.php
https://yojitu.com/travel-guide/test.php
```

## 削除完了後

このファイル（DELETE_THESE_FILES.md）も削除してOKです。

---

**注意**: `.htaccess` と `.htpasswd` は削除しないでください！
