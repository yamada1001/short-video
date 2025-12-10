# ユーザー管理ガイド - 二度とミスを起こさないために

**作成日**: 2025年12月10日
**目的**: ユーザー登録時のミスを防ぎ、確実にログインできるようにする

---

## 📌 重要な前提知識

### 1. データベースはGitで同期されない

**最重要ポイント**:
```
❌ データベース（bni_system.db）はGitでプッシュしても本番環境に反映されません
✅ データベースの中身は、本番環境で直接スクリプトを実行して更新する必要があります
```

**理由**:
- データベースファイル（`data/bni_system.db`）は `.gitignore` で除外されている
- ローカル環境と本番環境は別々のデータベースを持つ
- Gitで管理されるのは「ファイル」のみ、「データベースの中身」は管理されない

---

### 2. ローカル環境と本番環境の違い

| 項目 | ローカル環境 | 本番環境 |
|------|------------|----------|
| データベースファイル | `/Users/yamadaren/.../data/bni_system.db` | `/home/xs545151/.../data/bni_system.db` |
| 同期方法 | Gitでコード同期 | **別々のデータベース（同期されない）** |
| ユーザーデータ | テスト用データ | 本番用データ |

---

## ✅ 正しいユーザー登録手順

### ステップ1: ユーザー作成スクリプトを作成

**絶対にやってはいけないこと**:
```bash
❌ sqlite3コマンドで直接INSERT
❌ phpコマンドでパスワードハッシュを生成してから手動でINSERT
```

**理由**: 特殊文字を含むパスワードがエスケープされず、ハッシュが正しく生成されない

**正しい方法**: PHPスクリプトを作成する

```php
<?php
require_once __DIR__ . '/includes/db.php';

$email = 'user@example.com';
$name = 'ユーザー名';
$password = 'パスワード'; // 特殊文字含んでもOK
$role = 'admin'; // または 'member'

try {
    $db = getDbConnection();

    // パスワードハッシュを生成
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // ユーザーを作成
    $query = "INSERT INTO users (email, name, password_hash, role, is_active, created_at)
              VALUES (:email, :name, :password_hash, :role, 1, datetime('now', 'localtime'))";

    dbExecute($db, $query, [
        ':email' => $email,
        ':name' => $name,
        ':password_hash' => $passwordHash,
        ':role' => $role
    ]);

    // 認証テスト
    $user = dbQueryOne($db, "SELECT password_hash FROM users WHERE email = :email",
                       [':email' => $email]);

    if (password_verify($password, $user['password_hash'])) {
        echo "✅ 認証成功！ログインできます！\n";
    } else {
        echo "❌ 認証失敗\n";
    }

    dbClose($db);
} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
}
```

---

### ステップ2: 本番環境で実行

**方法1: ブラウザから実行（簡単・推奨）**

1. スクリプトをGitでプッシュ
   ```bash
   git add create_user.php
   git commit -m "Add: ユーザー作成スクリプト"
   git push
   ```

2. ブラウザでアクセス
   ```
   https://yojitu.com/bni-slide-system/create_user.php
   ```

3. 画面に「✅ 認証成功！ログインできます！」が表示されればOK

---

**方法2: SSH経由で実行（セキュア）**

```bash
# SSHでXserverに接続
ssh xs545151@xs545151.xsrv.jp

# ディレクトリに移動
cd /home/xs545151/yojitu.com/public_html/bni-slide-system

# スクリプトを実行
php create_user.php
```

---

### ステップ3: 必ず動作確認

**チェックリスト**:
- [ ] スクリプトが「✅ 認証成功！ログインできます！」を表示した
- [ ] ログインページでログインできる
- [ ] マイデータページが表示される
- [ ] エラーが出ない

**ログインURL**:
```
https://yojitu.com/bni-slide-system/login.php
```

---

### ステップ4: セキュリティ - スクリプトを削除

**重要**: テスト完了後は必ずスクリプトを削除してください

```bash
# Xserverで実行
rm /home/xs545151/yojitu.com/public_html/bni-slide-system/create_user.php
```

**または、ファイルマネージャーで削除**

---

## ⚠️ 過去のミスと教訓

### ミス1: ローカル環境でのみユーザー作成

**何が起こったか**:
- ローカル環境のSQLiteでユーザーを作成
- Gitでプッシュした
- 本番環境のデータベースには反映されなかった
- ログインできなかった

**教訓**:
```
✅ ユーザー作成は本番環境で直接スクリプトを実行する
```

---

### ミス2: 特殊文字を含むパスワードのハッシュ生成失敗

**何が起こったか**:
- パスワード: `~F/=rZ!-nAy5`
- シェルコマンドでパスワードハッシュを生成
- 特殊文字がエスケープされず、正しいハッシュが生成されなかった
- ログインできなかった

**教訓**:
```
✅ PHPスクリプトで password_hash() を使う
❌ シェルコマンドやsqlite3コマンドで直接パスワードを扱わない
```

---

## 📝 標準ユーザー作成テンプレート

このスクリプトをコピーして使用してください：

```php
<?php
/**
 * ユーザー作成スクリプト
 * 使用方法:
 * 1. このファイルを create_new_user.php として保存
 * 2. Git でプッシュ
 * 3. https://yojitu.com/bni-slide-system/create_new_user.php にアクセス
 * 4. 完了後、ファイルを削除
 */

require_once __DIR__ . '/includes/db.php';

// ===== ここを編集 =====
$email = 'user@example.com';
$name = 'ユーザー名';
$password = 'パスワード';
$role = 'admin'; // 'admin' または 'member'
// =====================

echo "=== ユーザー作成 ===\n\n";

try {
    $db = getDbConnection();

    // 既存ユーザーチェック
    echo "1. ユーザーの存在確認...\n";
    $existingUser = dbQueryOne($db, "SELECT id FROM users WHERE email = :email",
                                [':email' => $email]);

    if ($existingUser) {
        echo "   ⚠️  ユーザーは既に存在します（ID: {$existingUser['id']}）\n";
        echo "   パスワードを更新します...\n\n";

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        dbExecute($db, "UPDATE users SET password_hash = :password_hash WHERE email = :email",
                  [':password_hash' => $passwordHash, ':email' => $email]);

        echo "   ✅ パスワード更新完了\n\n";
    } else {
        echo "   ℹ️  新規ユーザーを作成します\n\n";

        echo "2. ユーザー作成中...\n";
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (email, name, password_hash, role, is_active, created_at)
                  VALUES (:email, :name, :password_hash, :role, 1, datetime('now', 'localtime'))";

        dbExecute($db, $query, [
            ':email' => $email,
            ':name' => $name,
            ':password_hash' => $passwordHash,
            ':role' => $role
        ]);

        echo "   ✅ ユーザー作成完了\n\n";
    }

    // 認証テスト
    echo "3. パスワード認証テスト...\n";
    $user = dbQueryOne($db, "SELECT password_hash FROM users WHERE email = :email",
                       [':email' => $email]);

    if (password_verify($password, $user['password_hash'])) {
        echo "   ✅ 認証成功！ログインできます！\n\n";
    } else {
        echo "   ❌ 認証失敗\n\n";
    }

    dbClose($db);

    echo "=== 完了 ===\n\n";
    echo "ログイン情報:\n";
    echo "URL: https://yojitu.com/bni-slide-system/login.php\n";
    echo "Email: {$email}\n";
    echo "Password: {$password}\n\n";
    echo "⚠️ このファイルは必ず削除してください！\n";

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
    if (isset($db)) dbClose($db);
}
```

---

## 🔐 セキュリティベストプラクティス

### 1. ユーザー作成スクリプトは一時的なもの

```
✅ 実行後は必ず削除する
❌ 本番環境に残さない
```

### 2. パスワードの取り扱い

```
✅ PHPスクリプト内で password_hash() を使う
✅ 特殊文字を含むパスワードも問題なし
❌ コマンドラインでパスワードを直接扱わない
```

### 3. 実行確認

```
✅ 必ずログインテストを実行
✅ エラーログを確認
✅ 本番環境で動作確認
```

---

## 📞 トラブルシューティング

### 問題: ログインできない

**確認事項**:
1. スクリプトが「✅ 認証成功！」を表示したか？
2. 本番環境で実行したか？（ローカルではなく）
3. メールアドレスは正しいか？
4. パスワードは正しいか？（コピペ推奨）

**解決方法**:
- ユーザー作成スクリプトを再実行
- 既存ユーザーの場合、パスワードが更新される

---

### 問題: スクリプト実行時にエラー

**よくあるエラー**:

1. **"Table users not found"**
   - 原因: データベースが初期化されていない
   - 解決: `database/schema.sql` を実行

2. **"Permission denied"**
   - 原因: データベースファイルの権限がない
   - 解決: `chmod 666 data/bni_system.db`

3. **"Null offset on array"**
   - 原因: ユーザーが見つからない
   - 解決: INSERT文が成功したか確認

---

## ✅ チェックリスト

新しいユーザーを作成する際、以下を必ず確認してください：

### 作成前
- [ ] ユーザー作成スクリプトを準備した
- [ ] メールアドレス、名前、パスワード、権限を設定した
- [ ] 特殊文字を含むパスワードでもPHPスクリプトで処理する

### 実行中
- [ ] Gitでプッシュした
- [ ] **本番環境で**スクリプトを実行した
- [ ] 「✅ 認証成功！ログインできます！」が表示された

### 実行後
- [ ] ログインページでログインできた
- [ ] マイデータページが表示された
- [ ] エラーが出ない
- [ ] **ユーザー作成スクリプトを削除した**

---

## 📚 関連ドキュメント

- [WORK_SESSION_2025-12-10_FINAL_TESTING.md](WORK_SESSION_2025-12-10_FINAL_TESTING.md) - 今回のミスの詳細
- [WORK_SESSION_2025-12-09_PITCH_VIEWER.md](WORK_SESSION_2025-12-09_PITCH_VIEWER.md) - 前回セッション
- [IMPLEMENTATION_PLAN.md](IMPLEMENTATION_PLAN.md) - システム全体の実装計画

---

**最終更新**: 2025年12月10日
**作成者**: Claude Code
**レビュー**: 余日様

---

_このガイドを守れば、二度とユーザー登録のミスは起こりません。_
