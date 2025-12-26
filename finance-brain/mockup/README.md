# Finance Brain モックアップサイト

株式会社ファイナンスブレーンのウェブサイトリニューアル用モックアップです。

## 📂 ページ一覧

### メインページ
- **トップページ**: `index.html`
- **会社概要**: `company/index.html`
- **お問い合わせ**: `contact/index.html`
- **スタッフブログ**: `news/staff-blog/index.html`

### サービスページ（個人向け）
1. **ライフプランニング**: `services/personal/life-planning/index.html`
2. **保険の見直し・ご相談**: `services/personal/insurance/index.html`
3. **住宅ローンのご相談**: `services/personal/housing-loan/index.html`
4. **相続に関するご相談**: `services/personal/inheritance/index.html`
5. **投資信託・資産運用**: `services/personal/investment/index.html`

### サービスページ（法人向け）
6. **財務コンサルティング**: `services/corporate/financial-consulting/index.html`
7. **退職金コンサルティング**: `services/corporate/retirement/index.html`
8. **事業承継対策**: `services/corporate/succession/index.html`
9. **自社株対策**: `services/corporate/stock/index.html`

## 🎨 デザイン仕様

### カラーパレット
- **プライマリー**: #5767bf（青）
- **セカンダリー**: #ff8c42（オレンジ）
- **テキスト**: #333333
- **背景**: #f5f7fa

### フォント
- **メインフォント**: Noto Sans JP

### レスポンシブ
- **SP**: 〜767px
- **タブレット**: 768px〜1024px
- **PC**: 1025px〜

## ⚙️ 機能

### JavaScript
- タブ切り替え（サービスページ）
- ハンバーガーメニュー（SP）
- スムーズスクロール
- フォームバリデーション
- Intersection Observer アニメーション

### CSS
- モダンなカードデザイン
- グラデーション背景
- ホバーアニメーション
- シャドウ効果

## 📱 確認方法

ローカル環境で確認する場合:

```bash
# このディレクトリで任意のHTTPサーバーを起動
cd finance-brain/mockup
python3 -m http.server 8000
# ブラウザで http://localhost:8000 を開く
```

または、ブラウザでファイルを直接開く:
```
finance-brain/mockup/index.html
```

## 📝 メモ

### 現在の状態
- ✅ トップページ完成
- ✅ サービスページテンプレート作成（9ページ）
- ✅ 会社概要ページ完成
- ✅ お問い合わせページ完成
- ✅ スタッフブログページ完成

### 次のステップ
- [ ] サービスページのコンテンツをカスタマイズ（現在はライフプランニングのテンプレート）
- [ ] 画像・アイコンの追加
- [ ] Google Map埋め込み
- [ ] SEOメタタグの最適化
- [ ] WordPress移行

## 🔗 リンク

- [元サイト](https://finance-brain.co.jp/)
- [設計書](../index.html)
- [ディレクトリマップ](../index.html#directory-structure)
