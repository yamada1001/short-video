# 旅行の栞システム

プライベート用の旅行計画・栞管理システムです。

## 📁 ディレクトリ構造

```
travel-guide/
├── .htaccess              # Basic認証設定（後で追加）
├── .htpasswd              # 認証用パスワードファイル（後で追加）
├── index.php              # トップページ（旅行先一覧）
├── test.php               # サーバー環境確認用（開発用）
├── README.md              # このファイル
│
├── assets/                # 共通リソース
│   ├── css/
│   │   ├── common.css    # 共通スタイル
│   │   └── guide.css     # 栞ページ用スタイル
│   ├── js/
│   │   ├── common.js     # 共通JavaScript
│   │   └── guide.js      # 栞ページ用JS（チェックボックス・目次機能）
│   └── images/
│       └── common/       # 共通画像
│
├── includes/              # 共通PHPファイル
│   ├── config.php        # 設定ファイル
│   ├── header.php        # 共通ヘッダー（noindex・GTM含む）
│   └── footer.php        # 共通フッター
│
└── kyoto/                 # 京都旅行（2025年12月7-9日）
    ├── index.php         # 京都旅行トップ（旅程概要）
    ├── assets/
    │   └── images/       # 京都旅行用画像
    ├── days/
    │   ├── day1.php     # 12/7（土）今日 - 動ける場合
    │   ├── day2.php     # 12/8（日）明日 - フル稼働
    │   └── day3.php     # 12/9（月）明後日 - 午前のみ
    └── info/             # 追加情報（将来的に使用）
```

## 🎨 デザイン仕様

- **フォント**: LINE Seed JP（font-weight: 100）
- **カラー**: 白ベース、シンプルなデザイン
- **アクセントカラー**: #4A90E2（青）
- **レスポンシブ**: PC・スマホ完全対応

## ✨ 主な機能

### 1. チェックボックス機能
- 各スポットに訪問済みチェックボックス
- localStorage に自動保存
- ページをリロードしても保持

### 2. 目次機能
- **PC**: 右サイドバーにフローティング目次
  - スクロールに連動してアクティブ化
  - 目次内もスクロール（見出しが常に見える）
- **SP**: フローティングボタン
  - タップでモーダル目次が開く
  - 背景クリックまたは×で閉じる

### 3. Google Maps リンク
- 各スポットにGoogle Mapsリンクを配置
- 公式サイトや画像検索リンクも追加

### 4. 統計情報
- 訪問予定スポット数
- 達成率（チェック済み / 全体）
- 開始時刻など

## 🔧 セットアップ

### 1. サーバー環境確認

```
https://yojitu.com/travel-guide/test.php
```

にアクセスして、すべてのファイルが正しく配置されているか確認してください。

### 2. 動作確認

京都旅行トップページにアクセス:

```
https://yojitu.com/travel-guide/kyoto/index.php
```

### 3. Basic認証の設定（後から）

#### .htpasswd の生成

```bash
cd /path/to/travel-guide
htpasswd -c .htpasswd ユーザー名
```

パスワードを入力してください。

#### .htaccess の作成

`travel-guide/.htaccess` を作成:

```apache
AuthType Basic
AuthName "Private Travel Guide"
AuthUserFile /absolute/path/to/.htpasswd
Require valid-user

# noindexはPHPで設定済み（header.phpに記載）
```

**注意**: `AuthUserFile` は絶対パスで指定してください。

## 📊 京都旅行の内容

### 全19スポット制覇プラン

- **12/7（土）**: 3〜4スポット
  - イノダコーヒ、伏見稲荷大社、鴨川沿い散歩、夕食
- **12/8（日）**: 12スポット
  - 午前: 嵐山（竹林の小径、天龍寺、渡月橋、キモノフォレスト）
  - 午後: 東山（蹴上インクライン、南禅寺、永観堂、京都国立近代美術館、円山公園）
  - 夕方: 四条・河原町（梅園、錦市場、今西軒）
- **12/9（月）**: 3スポット
  - 五重塔（東寺）、東寺餅、おみやげ小路

## 🚀 今後の拡張

新しい旅行先を追加する場合:

1. `includes/config.php` の `$destinations` に追加
2. 同じディレクトリ構造で旅行先フォルダを作成（例: `osaka/`）
3. 同じパターンでファイルを作成

## 🔒 セキュリティ

- **noindex**: 全ページに `<meta name="robots" content="noindex, nofollow">` 設定済み
- **Basic認証**: 後から設定予定
- **プライベート**: 完全非公開での運用を想定

## 📝 メモ

- GTM（Google Tag Manager）設定済み（ID: GTM-T7NGQDC2）
- LINE Seed JP フォントをGoogle Fontsから読み込み
- Font Awesome 6.5.1 を使用

---

**開発者メモ**: test.php は本番環境では削除してください。
