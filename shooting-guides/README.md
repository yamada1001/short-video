# Shooting Guides

撮影ガイド専用ディレクトリ

## 概要

クライアント向けの撮影ガイドを管理するディレクトリです。Basic認証とnoindexで保護されており、外部からのアクセスを制限しています。

## ディレクトリ構造

```
shooting-guides/
├── .htaccess                      # Basic認証設定・noindex
├── .htpasswd                      # 認証情報（暗号化）
├── index.php                      # ディレクトリアクセス防止
├── README.md                      # このファイル
└── tune-stay-kyoto/               # TUNE STAY KYOTO撮影ガイド
    ├── index.php                  # メインファイル
    └── fonts/                     # LINE Seed JPフォント
        ├── LINESeedJP_OTF_Rg.otf
        ├── LINESeedJP_OTF_Bd.otf
        └── LINESeedJP_OTF_Th.otf
```

## セキュリティ

### Basic認証

- **ユーザー名**: `yojitu`
- **パスワード**: `shooting2024`
- **適用範囲**: `shooting-guides/` ディレクトリ配下すべて

### noindex設定

検索エンジンにインデックスされないよう以下を設定：

- metaタグ: `<meta name="robots" content="noindex, nofollow">`
- HTTPヘッダー: `X-Robots-Tag: noindex, nofollow`

## アクセス方法

### URL

```
https://yojitu.com/shooting-guides/tune-stay-kyoto/
```

### 認証情報

Basic認証のダイアログが表示されます：

- ユーザー名: `yojitu`
- パスワード: `shooting2024`

## プロジェクト一覧

### TUNE STAY KYOTO

**作成日**: 2025年12月6日
**URL**: https://yojitu.com/shooting-guides/tune-stay-kyoto/

#### 概要

京都のライフスタイルホテル「TUNE STAY KYOTO」のInstagram撮影ガイド。

#### 機能

- **撮影カットリスト**: 12カテゴリー、100+項目のチェックリスト
- **LocalStorage連動**: チェック状態を保存
- **スクロール連動TOC**: PC版（右サイドバー）
- **フローティング目次**: SP版（右下ボタン）
- **レスポンシブデザイン**: PC・タブレット・SP対応
- **GTM連携**: アクセス解析対応
- **LINE Seed JPフォント**: 全体で統一されたフォントスタイル

#### 内容

1. 基本情報（運営会社、代表者情報含む）
2. SNS情報
3. 施設構成
4. 客室タイプ
5. Instagram撮影スポット
6. 撮影カットリスト（マスト項目）
7. アメニティ・設備
8. 食事・ドリンク
9. 3つのコンテンツ体験
10. 周辺撮影スポット
11. 料金目安
12. レビュー・評価
13. 撮影時の注意事項
14. 参考ブログ記事・メディア
15. まとめ

## 技術仕様

### フロントエンド

- **HTML/CSS/JavaScript**: バニラJS（フレームワーク不使用）
- **フォント**: LINE Seed JP（ローカルフォント）
- **アイコン**: Font Awesome 6.5.1
- **レスポンシブ**: モバイルファースト設計

### バックエンド

- **言語**: PHP
- **認証**: Apache Basic認証（.htaccess）
- **キャッシュ制御**: no-cache設定

### デザイン

- **カラースキーム**: 白ベース、モノトーン
- **メインカラー**: #2c3e50（ダークグレー）
- **フォントサイズ（SP）**: 16px（ベース）
- **左右padding（SP）**: 8%

## 開発メモ

### フォントサイズ調整の経緯

当初、デバイスピクセル比による分岐を試みましたが、以下の理由でシンプルな設計に戻しました：

1. **PC版表示モードの誤認**: Pixel 8で「PC版表示」がONになっていたため、SP版スタイルが適用されていないと誤解
2. **メディアクエリのネスト問題**: CSSではメディアクエリのネスト不可
3. **複雑性の回避**: デバイス別分岐は保守性を下げる

**結論**: 16pxベース・8%左右paddingのシンプルな設計で統一

### キャッシュ制御

以下のメタタグでブラウザキャッシュを無効化：

```html
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
```

## 更新履歴

### 2025-12-06

- ✅ プロジェクト作成
- ✅ Basic認証・noindex設定
- ✅ HTML→PHP変換
- ✅ GTMタグ追加
- ✅ フォントファイル配置（LINE Seed JP）
- ✅ 運営会社情報追加（代表者名、リンク）
- ✅ GINコンセプト詳細説明追加
- ✅ SP版フォントサイズ調整
- ✅ フローティング目次実装（SP版）
- ✅ 左右padding調整（8%）
- ✅ キャッシュ制御追加
- ✅ README作成

## メンテナンス

### 新しい撮影ガイドの追加

1. `shooting-guides/` 配下に新しいディレクトリを作成
2. `index.php` を作成（GTM・noindex・フォント設定を含む）
3. 必要に応じて画像・フォントなどのアセットを配置
4. Basic認証は `.htaccess` で自動適用される

### 認証情報の変更

`.htpasswd` を再生成：

```bash
htpasswd -c /path/to/.htpasswd yojitu
```

### フォントの追加・変更

1. フォントファイルを `fonts/` ディレクトリに配置
2. `@font-face` で読み込み
3. 相対パス `./fonts/` を使用

## 注意事項

- `.htpasswd` はGitで管理されているが、パスワードは暗号化されている
- 本番環境の `.htaccess` パスは `/home/xs545151/yojitu.com/public_html/shooting-guides/.htpasswd`
- デバッグファイル（`test.php`, `server-info.php`）は確認後削除すること
- フォントファイルはライセンスを確認してから使用すること

## トラブルシューティング

### 500 Internal Server Error

- `.htaccess` の `AuthUserFile` パスを確認
- `server-info.php` で正しいパスを確認してから修正

### フォントが表示されない

- フォントファイルのパスを確認（相対パス `./fonts/`）
- ブラウザのキャッシュをクリア
- ファイルのパーミッション確認（644）

### スタイルが反映されない

- ブラウザのキャッシュをクリア（Ctrl+Shift+R）
- PC版表示モードがOFFか確認（SP閲覧時）
- Developer Toolsでメディアクエリの適用状況を確認

## 参考リンク

- [TUNE STAY KYOTO 公式サイト](https://www.tune-stay.com/)
- [株式会社ティーエーティー](https://www.tat-group.co.jp/)
- [新都市企画株式会社](https://www.n-up.co.jp/)
- [PIECE GROUP](https://piecehotel.com/)

---

**作成者**: Claude Code
**最終更新**: 2025年12月6日
