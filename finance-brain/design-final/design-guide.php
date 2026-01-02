<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Design Final - デザイン完成版ディレクトリ | ファイナンスブレーン</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    .design-guide-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      background: #fff;
    }
    .design-guide-header {
      text-align: center;
      margin-bottom: 3rem;
      padding-bottom: 2rem;
      border-bottom: 3px solid #5767bf;
    }
    .design-guide-title {
      font-size: 2rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 0.5rem;
    }
    .design-guide-subtitle {
      font-size: 1rem;
      color: #666;
      line-height: 1.6;
    }
    .section {
      margin-bottom: 3rem;
    }
    .section-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 1.5rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid #5767bf;
    }
    .color-palette {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .color-card {
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .color-swatch {
      height: 100px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.9rem;
    }
    .color-info {
      padding: 1rem;
      background: #f5f7fa;
    }
    .color-name {
      font-weight: 700;
      color: #333;
      margin-bottom: 0.25rem;
    }
    .color-code {
      font-family: 'Courier New', monospace;
      color: #666;
      font-size: 0.9rem;
    }
    .color-usage {
      font-size: 0.85rem;
      color: #666;
      margin-top: 0.5rem;
    }
    .code-block {
      background: #f5f7fa;
      border: 1px solid #d0d8e0;
      border-radius: 8px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      font-family: 'Courier New', monospace;
      font-size: 0.9rem;
      overflow-x: auto;
    }
    .info-box {
      background: #fff9f5;
      border-left: 4px solid #ff8c42;
      padding: 1rem 1.5rem;
      margin-bottom: 1.5rem;
      font-size: 0.95rem;
      line-height: 1.6;
    }
    .grid-2 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
    }
    .note-box {
      background: #f5f7fa;
      border: 1px solid #d0d8e0;
      border-radius: 8px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .note-box h3 {
      font-weight: 700;
      color: #333;
      margin-bottom: 1rem;
      font-size: 1.1rem;
    }
    .note-box ul {
      margin: 0.5rem 0 0 1.5rem;
      padding: 0;
    }
    .note-box ul li {
      margin-bottom: 0.5rem;
    }
    .checklist {
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .checklist li {
      padding-left: 1.5rem;
      margin-bottom: 0.5rem;
      position: relative;
    }
    .checklist li:before {
      content: '□';
      position: absolute;
      left: 0;
      color: #5767bf;
      font-weight: 700;
    }
    .link-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
    }
    .link-card {
      background: #f5f7fa;
      border: 1px solid #d0d8e0;
      border-radius: 8px;
      padding: 1rem;
      text-align: center;
      transition: all 0.3s ease;
    }
    .link-card:hover {
      background: #5767bf;
      border-color: #5767bf;
    }
    .link-card:hover a {
      color: #fff;
    }
    .link-card a {
      color: #5767bf;
      text-decoration: none;
      font-weight: 600;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.5rem;
    }
    table th,
    table td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid #e0e0e0;
    }
    table th {
      background: #f5f7fa;
      font-weight: 600;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="design-guide-container">
    <!-- ヘッダー -->
    <div class="design-guide-header">
      <h1 class="design-guide-title">Design Final - デザイン完成版ディレクトリ</h1>
      <p class="design-guide-subtitle">
        このディレクトリは、モックアップを基に実際のデザイン・コーディングを進めるための作業用ディレクトリです。
      </p>
    </div>

    <!-- ディレクトリ構成 -->
    <div class="section">
      <h2 class="section-title">📂 ディレクトリ構成（全32ページ）</h2>
      <div class="info-box">
        <strong>📌 仕様書の「推奨ディレクトリ構成（開発用）」に基づく完全版</strong>
        <p style="margin-top: 0.5rem; line-height: 1.8;">
          以下は、仕様書で定義された32ページすべてのディレクトリ構成です。<br>
          この構成に従って、design-final/ディレクトリ内でHTMLファイルを作成してください。
        </p>
      </div>
      <div class="code-block"><pre>design-final/
│
├── <strong>index.html</strong>                           # 1. トップページ
│
├── <strong>about/</strong>                                 # 会社紹介
│   └── index.html                         # 2. ファイナンスブレーンとは（理念・沿革含む）
│
├── <strong>services/</strong>                              # サービス
│   │
│   ├── <strong>personal/</strong>                         # 個人のお客様向けサービス
│   │   ├── index.html                     # 3. 個人向けサービス一覧
│   │   │
│   │   ├── <strong>life-planning/</strong>               # ライフプランニング
│   │   │   ├── index.html                 # 4. ライフプランニングTOP
│   │   │   ├── housing.html               # 5. 住宅購入資金
│   │   │   ├── education.html             # 6. 教育資金
│   │   │   └── retirement.html            # 7. 老後資金
│   │   │
│   │   ├── <strong>insurance/</strong>                  # 保険の見直し・ご相談
│   │   │   ├── index.html                 # 8. 保険TOP
│   │   │   ├── life-insurance.html        # 9. 生命保険
│   │   │   └── general-insurance.html     # 10. 損害保険
│   │   │
│   │   ├── <strong>housing-loan/</strong>               # 住宅ローンのご相談
│   │   │   └── index.html                 # 11. 住宅ローン（選び方・借り換え等を統合）
│   │   │
│   │   ├── <strong>inheritance/</strong>                # 相続に関するご相談
│   │   │   └── index.html                 # 12. 相続対策（相続対策・税務等を統合）
│   │   │
│   │   └── <strong>investment/</strong>                 # 投資信託・資産運用
│   │       └── index.html                 # 13. 投資信託（NISA等を統合）
│   │
│   └── <strong>corporate/</strong>                       # 法人のお客様向けサービス
│       └── index.html                     # 14. 法人向けサービス一覧（財務コンサル・退職金・事業承継・自社株対策を統合）
│
├── <strong>why-us/</strong>                                # 選ばれる理由
│   └── index.html                         # 15. 選ばれる理由
│
├── <strong>voice/</strong>                                 # お客様の声
│   └── index.html                         # 16. お客様の声（個人・法人の声を統合）
│
├── <strong>staff/</strong>                                 # スタッフ紹介
│   └── index.html                         # 17. スタッフ紹介
│
├── <strong>company/</strong>                               # 会社情報
│   ├── index.html                         # 18. 会社概要（アクセス・地図含む）
│   ├── privacy.html                       # 19. 個人情報保護方針
│   └── solicitation.html                  # 20. 勧誘方針
│
├── <strong>news/</strong>                                  # お知らせ
│   ├── index.html                         # 21. 新着情報一覧
│   ├── detail.html                        # 22. お知らせ詳細テンプレート
│   │
│   ├── <strong>seminar/</strong>                         # セミナー・イベント
│   │   ├── index.html                     # 23. セミナー一覧
│   │   └── detail.html                    # 24. セミナー詳細テンプレート
│   │
│   └── <strong>staff-blog/</strong>                     # スタッフブログ
│       ├── index.html                     # 25. スタッフブログ一覧
│       ├── detail.html                    # 26. ブログ記事テンプレート
│       ├── by-staff/                      # スタッフ別アーカイブ
│       │   └── index.html                 # 27. スタッフ別アーカイブ
│       └── by-category/                   # カテゴリ別アーカイブ
│           └── index.html                 # 28. カテゴリ別アーカイブ
│
├── <strong>faq/</strong>                                   # よくあるご質問
│   └── index.html                         # 29. FAQ（全カテゴリ統合）
│
├── <strong>contact/</strong>                               # お問い合わせ
│   ├── index.html                         # 30. お問い合わせフォーム（種別選択で無料相談予約も対応、LINE相談ボタンも含む）
│   └── thanks.html                        # 31. 送信完了ページ
│
└── <strong>assets/</strong>                                # 静的ファイル
    ├── <strong>css/</strong>                               # CSSファイル
    │   ├── reset.css                      # リセットCSS
    │   ├── variables.css                  # CSS変数（カラーパレット等）
    │   ├── common.css                     # 共通スタイル
    │   ├── layout.css                     # レイアウト
    │   ├── components.css                 # コンポーネント（ボタン、カード等）
    │   └── pages/                         # ページ別CSS
    │       ├── front-page.css             # トップページ
    │       ├── services.css               # サービスページ
    │       ├── about.css                  # 会社紹介
    │       └── contact.css                # お問い合わせ
    │
    ├── <strong>js/</strong>                                # JavaScriptファイル
    │   ├── common.js                      # 共通スクリプト
    │   ├── scroll-animation.js            # スクロールアニメーション
    │   └── form-validation.js             # フォームバリデーション
    │
    ├── <strong>images/</strong>                            # 画像ファイル
    │   ├── logo/                          # ロゴ
    │   ├── hero/                          # ヒーロー画像
    │   ├── services/                      # サービス画像
    │   ├── staff/                         # スタッフ写真
    │   └── icons/                         # アイコン（SVG推奨）
    │
    └── <strong>fonts/</strong>                             # フォントファイル
        └── NotoSansJP/                    # Noto Sans JP（woff2形式）

<strong>📊 ページ数カウント:</strong>
- 合計: <strong>31ページ</strong>（index.phpは除外、assetsは静的ファイルなのでカウントせず）
- 個人向けサービス: 10ページ
- 法人向けサービス: 1ページ
- その他コンテンツページ: 11ページ
- お知らせ関連: 7ページ
- お問い合わせ: 2ページ

<strong>⚠️ 注意:</strong> 仕様書では「32ページ」と記載されていますが、上記構成では31ページです。
おそらく、company/の下にもう1ページ（例: 特定商取引法表記など）があるか、
または404.htmlなどのエラーページがカウントされている可能性があります。</pre></div>

      <div class="info-box" style="margin-top: 1.5rem;">
        <strong>💡 制作時の優先順位</strong>
        <p style="margin-top: 0.5rem; line-height: 1.8;">
          すべてのページを一度に作る必要はありません。以下の順序で進めることを推奨します：
        </p>
        <ol style="margin: 0.5rem 0 0 1.5rem; line-height: 1.8;">
          <li><strong>フェーズ1:</strong> トップページ（index.html） + 共通パーツ（ヘッダー、フッター）</li>
          <li><strong>フェーズ2:</strong> 個人向けサービス主要ページ（life-planning, insurance, housing-loan, inheritance, investment の各TOP）</li>
          <li><strong>フェーズ3:</strong> その他重要ページ（about, why-us, voice, contact）</li>
          <li><strong>フェーズ4:</strong> 法人向けサービス、会社情報、FAQ</li>
          <li><strong>フェーズ5:</strong> お知らせ・ブログ関連（テンプレート）</li>
          <li><strong>フェーズ6:</strong> 詳細ページ（housing.html, education.html等）</li>
        </ol>
      </div>
    </div>

    <!-- カラーパレット -->
    <div class="section">
      <h2 class="section-title">🎨 カラーパレット</h2>

      <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem;">プライマリーカラー（メイン）</h3>
      <div class="color-palette">
        <div class="color-card">
          <div class="color-swatch" style="background-color: #5767bf; color: #fff;">#5767bf</div>
          <div class="color-info">
            <div class="color-name">濃いブルー</div>
            <div class="color-code">#5767bf</div>
            <div class="color-usage">ヘッダー、メインナビ、見出し、CTA背景</div>
          </div>
        </div>
      </div>

      <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; margin-top: 2rem;">セカンダリーカラー</h3>
      <div class="color-palette">
        <div class="color-card">
          <div class="color-swatch" style="background-color: #3a4a8f; color: #fff;">#3a4a8f</div>
          <div class="color-info">
            <div class="color-name">深いネイビー</div>
            <div class="color-code">#3a4a8f</div>
            <div class="color-usage">グラデーション用</div>
          </div>
        </div>
        <div class="color-card">
          <div class="color-swatch" style="background-color: #a0b3e0; color: #333;">#a0b3e0</div>
          <div class="color-info">
            <div class="color-name">淡いブルー</div>
            <div class="color-code">#a0b3e0</div>
            <div class="color-usage">背景・装飾用</div>
          </div>
        </div>
      </div>

      <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; margin-top: 2rem;">アクセントカラー</h3>
      <div class="color-palette">
        <div class="color-card">
          <div class="color-swatch" style="background-color: #ff8c42; color: #fff;">#ff8c42</div>
          <div class="color-info">
            <div class="color-name">オレンジ</div>
            <div class="color-code">#ff8c42</div>
            <div class="color-usage">ボタン、お問い合わせリンク、強調</div>
          </div>
        </div>
      </div>

      <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; margin-top: 2rem;">背景カラー</h3>
      <div class="color-palette">
        <div class="color-card">
          <div class="color-swatch" style="background-color: #ffffff; color: #333; border: 1px solid #e0e0e0;">#ffffff</div>
          <div class="color-info">
            <div class="color-name">白</div>
            <div class="color-code">#ffffff</div>
            <div class="color-usage">メイン背景</div>
          </div>
        </div>
        <div class="color-card">
          <div class="color-swatch" style="background-color: #f5f7fa; color: #333;">#f5f7fa</div>
          <div class="color-info">
            <div class="color-name">オフホワイト</div>
            <div class="color-code">#f5f7fa</div>
            <div class="color-usage">セクション背景</div>
          </div>
        </div>
        <div class="color-card">
          <div class="color-swatch" style="background-color: #fafbfc; color: #333;">#fafbfc</div>
          <div class="color-info">
            <div class="color-name">ライトグレー</div>
            <div class="color-code">#fafbfc</div>
            <div class="color-usage">交互背景</div>
          </div>
        </div>
      </div>

      <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; margin-top: 2rem;">テキストカラー</h3>
      <div class="color-palette">
        <div class="color-card">
          <div class="color-swatch" style="background-color: #333333; color: #fff;">#333333</div>
          <div class="color-info">
            <div class="color-name">ダークグレー</div>
            <div class="color-code">#333333</div>
            <div class="color-usage">本文テキスト</div>
          </div>
        </div>
        <div class="color-card">
          <div class="color-swatch" style="background-color: #666666; color: #fff;">#666666</div>
          <div class="color-info">
            <div class="color-name">ミディアムグレー</div>
            <div class="color-code">#666666</div>
            <div class="color-usage">補足テキスト</div>
          </div>
        </div>
        <div class="color-card">
          <div class="color-swatch" style="background-color: #999999; color: #fff;">#999999</div>
          <div class="color-info">
            <div class="color-name">ライトグレー</div>
            <div class="color-code">#999999</div>
            <div class="color-usage">キャプション</div>
          </div>
        </div>
      </div>
    </div>

    <!-- フォント -->
    <div class="section">
      <h2 class="section-title">📝 フォント</h2>

      <div class="note-box">
        <h3>日本語フォント</h3>
        <div class="code-block"><pre>'Noto Sans JP', 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif</pre></div>
      </div>

      <div class="note-box">
        <h3>英数字フォント</h3>
        <div class="code-block"><pre>'Roboto', 'Arial', sans-serif</pre></div>
      </div>

      <div class="note-box">
        <h3>フォントサイズ</h3>
        <table>
          <thead>
            <tr>
              <th>用途</th>
              <th>サイズ</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>本文</td>
              <td>16px (1rem)</td>
            </tr>
            <tr>
              <td>見出し1</td>
              <td>32px (2rem)</td>
            </tr>
            <tr>
              <td>見出し2</td>
              <td>28px (1.75rem)</td>
            </tr>
            <tr>
              <td>見出し3</td>
              <td>24px (1.5rem)</td>
            </tr>
            <tr>
              <td>見出し4</td>
              <td>20px (1.25rem)</td>
            </tr>
            <tr>
              <td>小テキスト</td>
              <td>14px (0.875rem)</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="note-box">
        <h3>行間</h3>
        <ul>
          <li><strong>本文:</strong> 1.7</li>
          <li><strong>見出し:</strong> 1.4</li>
        </ul>
      </div>
    </div>

    <!-- レスポンシブブレークポイント -->
    <div class="section">
      <h2 class="section-title">📱 レスポンシブブレークポイント</h2>
      <div class="code-block"><pre>/* SP（スマートフォン） */
@media (max-width: 767px) { }

/* タブレット */
@media (min-width: 768px) and (max-width: 1024px) { }

/* PC */
@media (min-width: 1025px) { }</pre></div>
    </div>

    <!-- デザインスタイル -->
    <div class="section">
      <h2 class="section-title">✨ デザインスタイル</h2>

      <div class="grid-2">
        <div class="note-box">
          <h3>1. ミニマル・モダン</h3>
          <ul>
            <li>豊富な余白（ホワイトスペース）</li>
            <li>グリッドベースレイアウト</li>
            <li>シンプルなタイポグラフィ</li>
            <li>控えめなアニメーション</li>
          </ul>
        </div>

        <div class="note-box">
          <h3>2. 信頼感の演出</h3>
          <ul>
            <li>専門資格バッジの表示</li>
            <li>実績数値の可視化</li>
            <li>お客様の声・事例紹介</li>
            <li>スタッフ顔写真の掲載</li>
          </ul>
        </div>

        <div class="note-box">
          <h3>3. 親しみやすさ</h3>
          <ul>
            <li>柔らかいイラスト・アイコン</li>
            <li>平易な言葉での説明</li>
            <li>FAQの充実</li>
            <li>LINE相談ボタンの目立つ配置</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- 2026年デザイントレンド -->
    <div class="section">
      <h2 class="section-title">🚀 2026年デザイントレンド（採用方針）</h2>

      <div class="info-box">
        <strong>📌 このプロジェクトのデザイン方針</strong>
        <p style="margin-top: 0.5rem; line-height: 1.8;">
          金融サービスサイトとして「<strong>保守的・信頼重視</strong>」を基本としつつ、2026年のWebデザイントレンドを<strong>ほんのり</strong>取り入れます。<br>
          「モダンすぎて軽薄に見える」「派手すぎて信頼感が損なわれる」を避け、「クリーンで洗練されているが、しっかりとした安心感がある」デザインを目指します。
        </p>
      </div>

      <div class="note-box">
        <h3>トレンド1: ソフトグラデーション（控えめに採用）</h3>
        <p style="margin-bottom: 1rem; color: #666; line-height: 1.7;">
          <strong>何をするか：</strong> ヘッダー背景やボタンに、同系色の微妙なグラデーションを適用します。<br>
          <strong>なぜやるか：</strong> フラットデザインから一歩進んだ、立体感と奥行きを演出できます。<br>
          <strong>注意点：</strong> 派手なネオンカラーグラデーションは使いません。あくまで「よく見ると微妙にグラデーションかかってる」程度。
        </p>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">✅ 使用例（DO）</h4>
        <div class="code-block"><pre>/* ヘッダー背景 */
background: linear-gradient(135deg, #5767bf 0%, #6b7ac7 100%);
/* 説明: 同じブルー系の中で、少しだけ明るくする程度 */

/* プライマリーボタン */
background: linear-gradient(180deg, #5767bf 0%, #4a5ab3 50%, #3a4a8f 100%);
/* 説明: 上から下に向かって少し濃くなる。深みが出る */

/* セクション背景（超控えめ） */
background: linear-gradient(to bottom, #f5f7fa 0%, #fafbfc 100%);
/* 説明: ほぼ同じ色。よく見ないとわからない程度 */</pre></div>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0; color: #d9534f;">❌ NG例（DON'T）</h4>
        <div class="code-block"><pre>/* 派手すぎるネオンカラー */
background: linear-gradient(45deg, #ff006e, #8338ec, #3a86ff);
/* 理由: 金融サイトに不向き。軽薄に見える */

/* 角度が急すぎる */
background: linear-gradient(90deg, #5767bf 0%, #ff8c42 100%);
/* 理由: 色が対比しすぎて目がチカチカする */

/* グラデーションが複雑すぎる */
background: linear-gradient(to right, red 0%, orange 20%, yellow 40%, green 60%, blue 80%, purple 100%);
/* 理由: 虹色は論外。信頼感ゼロ */</pre></div>
      </div>

      <div class="note-box">
        <h3>トレンド2: グラスモーフィズム（アクセントのみ採用）</h3>
        <p style="margin-bottom: 1rem; color: #666; line-height: 1.7;">
          <strong>何をするか：</strong> 半透明のカード要素に背景ぼかし効果（backdrop-filter: blur）を適用し、ガラスのような質感を出します。<br>
          <strong>なぜやるか：</strong> 2026年のトレンドで、洗練された印象を与えます。<br>
          <strong>注意点：</strong> <span style="color: #d9534f; font-weight: 600;">視認性が下がるため、全体には使わない。ヒーローセクションの装飾カードなど、アクセント程度にとどめる。</span>
        </p>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">✅ 使用例（DO）</h4>
        <div class="code-block"><pre>/* ヒーローセクションの装飾カード（アクセント） */
.hero-glass-card {
  background: rgba(255, 255, 255, 0.1);  /* 半透明の白 */
  backdrop-filter: blur(10px);           /* ぼかし効果 */
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
  padding: 2rem;
}
/* 説明: 背景がほんのり透けて見える。おしゃれ */

/* モーダルの背景オーバーレイ */
.modal-overlay {
  background: rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(5px);
}
/* 説明: ポップアップ表示時の背景ぼかし。モダン */</pre></div>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0; color: #d9534f;">❌ NG例（DON'T）</h4>
        <div class="code-block"><pre>/* 本文テキストエリアに使う */
.main-content {
  background: rgba(255, 255, 255, 0.5);
  backdrop-filter: blur(20px);
}
/* 理由: 文字が読みにくくなる。UX最悪 */

/* ヘッダー全体に使う */
.header {
  background: rgba(87, 103, 191, 0.3);
  backdrop-filter: blur(15px);
}
/* 理由: ナビゲーションが見づらい。実用性ゼロ */</pre></div>
      </div>

      <div class="note-box">
        <h3>トレンド3: スクロールアニメーション（控えめに採用）</h3>
        <p style="margin-bottom: 1rem; color: #666; line-height: 1.7;">
          <strong>何をするか：</strong> ページスクロールに応じて、要素がふわっとフェードイン・スライドインします。<br>
          <strong>なぜやるか：</strong> モダンな印象を与え、ユーザーの視線を自然に誘導できます。<br>
          <strong>注意点：</strong> <span style="color: #d9534f; font-weight: 600;">派手すぎる動きは避ける。「気づいたら表示されてた」程度が理想。</span>
        </p>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">✅ 使用例（DO）</h4>
        <div class="code-block"><pre>/* フェードイン（opacity 0 → 1） */
.fade-in {
  opacity: 0;
  transform: translateY(20px);  /* 20px下から */
  transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-in.is-visible {
  opacity: 1;
  transform: translateY(0);
}
/* 説明: スクロールしたら、ふわっと浮き上がるように表示 */

/* スライドイン（左から） */
.slide-in-left {
  opacity: 0;
  transform: translateX(-30px);
  transition: opacity 0.5s ease, transform 0.5s ease;
}
.slide-in-left.is-visible {
  opacity: 1;
  transform: translateX(0);
}
/* 説明: 左からスーッと入ってくる。目立ちすぎない */</pre></div>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0; color: #d9534f;">❌ NG例（DON'T）</h4>
        <div class="code-block"><pre>/* 回転しながら登場 */
.rotate-in {
  transform: rotate(360deg) scale(0);
  transition: transform 2s ease;
}
/* 理由: クルクル回るのは派手すぎ。金融サイトに不向き */

/* バウンド（跳ねる） */
@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-50px); }
}
/* 理由: 跳ねるのはカジュアルすぎる。子供向けサイトじゃない */

/* 画面全体が揺れる */
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-10px); }
  75% { transform: translateX(10px); }
}
/* 理由: 地震みたい。不快 */</pre></div>
      </div>

      <div class="note-box">
        <h3>トレンド4: パフォーマンス最適化（必須で採用）</h3>
        <p style="margin-bottom: 1rem; color: #666; line-height: 1.7;">
          <strong>何をするか：</strong> 画像のWebP化、Critical CSSのインライン化、遅延読み込みなど。<br>
          <strong>なぜやるか：</strong> GoogleのCore Web Vitalsに対応し、SEOとUXを向上させます。<br>
          <strong>注意点：</strong> <span style="color: #5767bf; font-weight: 600;">これは「トレンド」というより「必須」。2026年現在、遅いサイトは検索順位が下がります。</span>
        </p>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">✅ 実装項目（すべて実施）</h4>
        <ul style="margin: 0.5rem 0 0 1.5rem;">
          <li><strong>画像最適化:</strong> WebP形式、width/height属性指定、lazy loading</li>
          <li><strong>フォント最適化:</strong> font-display: swap、プリロード</li>
          <li><strong>CSS最適化:</strong> Critical CSSをインライン化、それ以外は非同期読み込み</li>
          <li><strong>JavaScript最適化:</strong> defer/async、不要なライブラリは削除</li>
          <li><strong>キャッシュ:</strong> .htaccessでブラウザキャッシュ設定</li>
        </ul>

        <div class="code-block" style="margin-top: 1rem;"><pre><!-- 画像の最適化例 -->
<img
  src="image.webp"
  alt="説明文"
  width="800"
  height="600"
  loading="lazy"
>

<!-- フォントのプリロード -->
<link rel="preload" href="fonts/NotoSansJP-Regular.woff2" as="font" type="font/woff2" crossorigin>

<!-- CSSの非同期読み込み -->
<link rel="preload" href="style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="style.css"></noscript></pre></div>
      </div>
    </div>

    <!-- 詳細実装ガイドライン -->
    <div class="section">
      <h2 class="section-title">📐 詳細実装ガイドライン</h2>

      <div class="note-box">
        <h3>1. レイアウト・余白の基本ルール</h3>
        <p style="margin-bottom: 1rem; color: #666; line-height: 1.7;">
          <strong>原則：</strong> 余白は「8の倍数」で統一します（8px, 16px, 24px, 32px, 40px...）。<br>
          これにより、視覚的な統一感が生まれ、デザインが整って見えます。
        </p>

        <table style="margin-top: 1rem;">
          <thead>
            <tr>
              <th>用途</th>
              <th>値</th>
              <th>具体例</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>セクション間の余白</td>
              <td>80px（PC）<br>60px（SP）</td>
              <td>各セクションのpadding-top/bottom</td>
            </tr>
            <tr>
              <td>見出しと本文の間</td>
              <td>24px</td>
              <td>h2要素とp要素の間</td>
            </tr>
            <tr>
              <td>カード内の余白</td>
              <td>32px（PC）<br>24px（SP）</td>
              <td>.card { padding: 32px; }</td>
            </tr>
            <tr>
              <td>ボタン内の余白</td>
              <td>16px 32px</td>
              <td>.btn { padding: 16px 32px; }</td>
            </tr>
            <tr>
              <td>テキスト行間</td>
              <td>1.7（本文）<br>1.4（見出し）</td>
              <td>line-height</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="note-box">
        <h3>2. タイポグラフィの詳細ルール</h3>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">見出しの使い方</h4>
        <table>
          <thead>
            <tr>
              <th>見出し</th>
              <th>サイズ</th>
              <th>用途</th>
              <th>フォントウェイト</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>h1</td>
              <td>48px（PC）<br>32px（SP）</td>
              <td>ページタイトル（各ページ1つのみ）</td>
              <td>700（Bold）</td>
            </tr>
            <tr>
              <td>h2</td>
              <td>32px（PC）<br>28px（SP）</td>
              <td>セクション見出し</td>
              <td>700（Bold）</td>
            </tr>
            <tr>
              <td>h3</td>
              <td>24px（PC）<br>20px（SP）</td>
              <td>サブセクション見出し</td>
              <td>600（SemiBold）</td>
            </tr>
            <tr>
              <td>h4</td>
              <td>20px</td>
              <td>カード内見出し</td>
              <td>600（SemiBold）</td>
            </tr>
          </tbody>
        </table>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">本文テキストの使い方</h4>
        <table>
          <thead>
            <tr>
              <th>要素</th>
              <th>サイズ</th>
              <th>色</th>
              <th>用途</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>通常テキスト</td>
              <td>16px</td>
              <td>#333</td>
              <td>メインコンテンツ</td>
            </tr>
            <tr>
              <td>リード文</td>
              <td>18px</td>
              <td>#333</td>
              <td>セクション冒頭の説明文</td>
            </tr>
            <tr>
              <td>補足テキスト</td>
              <td>14px</td>
              <td>#666</td>
              <td>注釈、キャプション</td>
            </tr>
            <tr>
              <td>小テキスト</td>
              <td>12px</td>
              <td>#999</td>
              <td>フッター、コピーライト</td>
            </tr>
          </tbody>
        </table>

        <div class="info-box" style="margin-top: 1rem;">
          <strong>💡 読みやすさのポイント</strong>
          <ul style="margin-top: 0.5rem;">
            <li>1行の文字数は<strong>全角35〜45文字</strong>が理想（PC）</li>
            <li>それ以上長い場合は、max-widthで幅を制限する</li>
            <li>行間（line-height）は最低でも<strong>1.6以上</strong>、本文は1.7推奨</li>
            <li>文字色と背景色のコントラスト比は<strong>4.5:1以上</strong>（WCAG AA準拠）</li>
          </ul>
        </div>
      </div>

      <div class="note-box">
        <h3>3. ボタンの詳細デザイン仕様</h3>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1rem 0 0.5rem 0;">プライマリーボタン（最も重要なCTA）</h4>
        <div class="code-block"><pre>.btn-primary {
  /* 基本スタイル */
  background: linear-gradient(180deg, #5767bf 0%, #4a5ab3 100%);
  color: #ffffff;
  font-size: 16px;
  font-weight: 600;
  padding: 16px 32px;
  border: none;
  border-radius: 8px;
  cursor: pointer;

  /* シャドウ */
  box-shadow: 0 4px 12px rgba(87, 103, 191, 0.3);

  /* トランジション */
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: linear-gradient(180deg, #4a5ab3 0%, #3a4a8f 100%);
  transform: translateY(-2px);  /* 2px浮く */
  box-shadow: 0 6px 16px rgba(87, 103, 191, 0.4);
}

.btn-primary:active {
  transform: translateY(0);  /* 押したら元に戻る */
  box-shadow: 0 2px 8px rgba(87, 103, 191, 0.3);
}</pre></div>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">セカンダリーボタン（2番目に重要なCTA）</h4>
        <div class="code-block"><pre>.btn-secondary {
  background: linear-gradient(180deg, #ff8c42 0%, #e67e22 100%);
  color: #ffffff;
  font-size: 16px;
  font-weight: 600;
  padding: 16px 32px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(255, 140, 66, 0.3);
  transition: all 0.3s ease;
}

.btn-secondary:hover {
  background: linear-gradient(180deg, #e67e22 0%, #d35400 100%);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(255, 140, 66, 0.4);
}</pre></div>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">アウトラインボタン（目立たせたくないCTA）</h4>
        <div class="code-block"><pre>.btn-outline {
  background: transparent;
  color: #5767bf;
  font-size: 16px;
  font-weight: 600;
  padding: 16px 32px;
  border: 2px solid #5767bf;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-outline:hover {
  background: #5767bf;
  color: #ffffff;
  transform: translateY(-2px);
}</pre></div>

        <div class="info-box" style="margin-top: 1rem;">
          <strong>💡 ボタン配置のルール</strong>
          <ul style="margin-top: 0.5rem;">
            <li>1画面に<strong>プライマリーボタンは1つまで</strong>（複数あると迷う）</li>
            <li>ボタン同士の間隔は<strong>16px以上</strong>空ける</li>
            <li>テキストボタンは最小でも<strong>44x44px</strong>のタップ領域を確保（スマホ対応）</li>
            <li>ローディング中は<strong>disabled状態</strong>にして二重送信を防ぐ</li>
          </ul>
        </div>
      </div>

      <div class="note-box">
        <h3>4. カードコンポーネントの詳細仕様</h3>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1rem 0 0.5rem 0;">基本カード</h4>
        <div class="code-block"><pre>.card {
  /* 基本スタイル */
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;  /* 少し丸めで柔らかい印象 */
  padding: 32px;

  /* シャドウ（控えめ） */
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);

  /* トランジション */
  transition: all 0.3s ease;
}

.card:hover {
  /* ホバー時: 少し浮く */
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  border-color: #5767bf;  /* ボーダーも変わる */
}</pre></div>

        <h4 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.5rem 0;">クリック可能カード（リンクカード）</h4>
        <div class="code-block"><pre>.card-link {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  cursor: pointer;  /* カーソルがポインターになる */
  text-decoration: none;
  display: block;
}

.card-link:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  border-color: #5767bf;
  background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
}

/* カード内の矢印アイコン（ホバー時に右に動く） */
.card-link .arrow-icon {
  transition: transform 0.3s ease;
}

.card-link:hover .arrow-icon {
  transform: translateX(4px);  /* 右に4px移動 */
}</pre></div>
      </div>
    </div>

    <!-- 参考サイト詳細分析 BuySell Technologies -->
    <div class="section">
      <h2 class="section-title">📘 参考サイト詳細分析：BuySell Technologies 採用サイト</h2>

      <div class="info-box" style="background: #e3f2fd; border-left-color: #2750df;">
        <strong>🎯 このサイトを参考にする理由</strong>
        <p style="margin-top: 0.5rem; line-height: 1.8;">
          株式会社BuySell Technologiesの新卒採用サイトは、<strong style="color: #2750df;">青をベースカラーとした超モダンなデザイン</strong>を実現しています。<br>
          金融・信頼性が求められる業界でありながら、洗練された先進的なUIを提供する優れた事例です。
        </p>
      </div>

      <h3 style="font-size: 1.3rem; font-weight: 600; margin: 2rem 0 1rem; color: #2750df;">🎨 カラーシステム</h3>

      <div class="note-box" style="background: #f8f9fa;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">メインカラー</h4>
        <div class="color-palette">
          <div class="color-card">
            <div class="color-swatch" style="background-color: #2750df; color: #fff;">#2750df</div>
            <div class="color-info">
              <div class="color-name">Blue（メイン）</div>
              <div class="color-code">#2750df</div>
              <div class="color-usage">ブランドカラー、ボタン、アイコン</div>
            </div>
          </div>
          <div class="color-card">
            <div class="color-swatch" style="background-color: #183aae; color: #fff;">#183aae</div>
            <div class="color-info">
              <div class="color-name">Dark Blue</div>
              <div class="color-code">#183aae</div>
              <div class="color-usage">ホバー、強調、見出し</div>
            </div>
          </div>
          <div class="color-card">
            <div class="color-swatch" style="background-color: #cfe2ff; color: #333;">#cfe2ff</div>
            <div class="color-info">
              <div class="color-name">Light Blue</div>
              <div class="color-code">#cfe2ff</div>
              <div class="color-usage">背景、淡い装飾</div>
            </div>
          </div>
        </div>
      </div>

      <div class="note-box" style="background: #f8f9fa; margin-top: 1.5rem;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">グラデーション（重要！）</h4>
        <p style="margin-bottom: 1rem; line-height: 1.7;">このサイトの<strong>モダンさの秘密は、複数のグラデーション</strong>を巧みに使い分けていることです。</p>

        <div class="code-block"><pre>/* Gradient 1: メインボタン・テキストグラデーション */
background: linear-gradient(90deg, #2750df 0%, #183aae 100%);

/* Gradient 2: 背景グラデーション（複雑） */
background: linear-gradient(90deg,
  #2750df 0%,
  #5071e2 30.29%,
  #3c5fd7 80.29%,
  #375ad5 100%
);

/* Gradient 3: 淡い背景 */
background: linear-gradient(90deg,
  #ebe8fd 0%,
  #e4f2fe 50%,
  #e1e3fd 100%
);

/* Gradient 4: さらに淡い背景 */
background: linear-gradient(90deg,
  #e9f0fe 0%,
  #f7f9fd 50.48%,
  #dee7fe 100%
);

/* Gradient 5: 斜めグラデーション */
background: linear-gradient(114deg,
  #dfd9ff 0%,
  #c8e6ff 39.4%,
  #cddeff 84.03%,
  #ced1f5 98.75%
);

/* Gradient 6: ホバーエフェクト用 */
background: linear-gradient(93deg,
  #afd9ff 20.13%,
  #bdb9ff 81.55%
), #2750df;</pre></div>
      </div>

      <h3 style="font-size: 1.3rem; font-weight: 600; margin: 2rem 0 1rem; color: #2750df;">✍️ タイポグラフィ</h3>

      <div class="note-box" style="background: #f8f9fa;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">フォントファミリー</h4>
        <div class="code-block"><pre>/* 英文見出し用 - 可変フォント */
font-family: 'ClashDisplay-Variable', var(--base-font);
font-weight: 200～700（可変）
font-variation-settings: "wght" 500;

/* 日本語本文用 */
font-family: 'Noto Sans JP', sans-serif;
font-weight: 400～900

/* 日本語見出し・強調用 */
font-family: 'Noto Serif JP', serif;
font-weight: 200～900

/* 補助フォント */
font-family: 'Switzer-Variable';
font-weight: 100～900（可変）</pre></div>

        <div class="info-box" style="margin-top: 1rem;">
          <strong>⚠️ 重要ポイント</strong>
          <ul style="margin-top: 0.5rem; padding-left: 1.5rem; line-height: 1.8;">
            <li><strong>英文は必ずClashDisplay-Variable</strong>を使用（モダンで高級感）</li>
            <li><strong>可変フォント（Variable Font）</strong>でウェイト調整が滑らか</li>
            <li><strong>font-feature-settings: "palt"</strong> でプロポーショナルメトリクス有効化</li>
            <li><strong>letter-spacing</strong> を細かく調整（英文: -0.01em, 和文: 0.04em）</li>
          </ul>
        </div>
      </div>

      <h3 style="font-size: 1.3rem; font-weight: 600; margin: 2rem 0 1rem; color: #2750df;">🏗️ レイアウトシステム</h3>

      <div class="note-box" style="background: #f8f9fa;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">CSS Custom Properties（CSS変数）</h4>
        <div class="code-block"><pre>:root {
  /* スケーリングシステム */
  --viewport-width: 100vw;
  --window-width: tan(atan2(var(--viewport-width), 1px));
  --mw: max(1440px, 90rem);
  --max: tan(atan2(var(--mw), 1px));
  --scale: max(1, var(--window-width) / var(--max));
  --px: calc(1px * var(--scale));
  --rem: calc(1rem * var(--scale));

  /* スペーシング（clampで流動的） */
  --grid-gutter: clamp(20 * var(--px), (var(--window-width) * .0188 + 12.958) * var(--px), 40 * var(--px));
  --inline-space-md: clamp(10 * var(--px), (var(--window-width) * .00939 + 6.479) * var(--px), 20 * var(--px));
  --inline-space-lg: clamp(20 * var(--px), (var(--window-width) * .0188 + 12.958) * var(--px), 40 * var(--px));
  --inline-space-xl: clamp(30 * var(--px), (var(--window-width) * .047 + 12.394) * var(--px), 80 * var(--px));

  /* フォントサイズ（clampで流動的） */
  --fz-root: clamp(.9375 * var(--rem), (var(--window-width) * .0000587 + .916) * var(--rem), 1 * var(--rem));
  --fz-3xlg: clamp(1.5 * var(--rem), (var(--window-width) * .00047 + 1.324) * var(--rem), 2 * var(--rem));
  --fz-2xlg: clamp(1.375 * var(--rem), (var(--window-width) * .000353 + 1.243) * var(--rem), 1.75 * var(--rem));
  --fz-xlg: clamp(1.25 * var(--rem), (var(--window-width) * .000235 + 1.162) * var(--rem), 1.5 * var(--rem));

  /* アニメーション */
  --duration: 1s;
}</pre></div>

        <div class="info-box" style="margin-top: 1rem;">
          <strong>🎯 このシステムの強み</strong>
          <ul style="margin-top: 0.5rem; padding-left: 1.5rem; line-height: 1.8;">
            <li><strong>clamp()</strong>による流動的なレスポンシブ（メディアクエリ不要）</li>
            <li><strong>数学関数</strong>を使った高度なスケーリング</li>
            <li><strong>8pxの倍数</strong>を基準にした統一感</li>
            <li><strong>calc()演算</strong>で柔軟な計算</li>
          </ul>
        </div>
      </div>

      <div class="note-box" style="background: #f8f9fa; margin-top: 1.5rem;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">CSS Grid + Container Queries</h4>
        <div class="code-block"><pre>/* Container Queriesを使用 */
.container {
  container-type: inline-size;
  display: grid;
  grid-template-columns: 25.0666666667cqw 1fr;
  gap: var(--grid-gutter);
}

@container (min-width: max(550px, 34.375rem)) {
  .parent-box {
    grid-auto-flow: column;
  }
}</pre></div>

        <div class="info-box" style="margin-top: 1rem;">
          <strong>💡 Container Queriesとは</strong>
          <p style="margin-top: 0.5rem; line-height: 1.8;">
            通常のメディアクエリは<strong>ビューポート</strong>を基準にしますが、<br>
            Container Queriesは<strong>親要素のサイズ</strong>を基準にできる次世代の技術です。<br>
            <strong>cqw（container query width）</strong>単位で柔軟なレイアウトが可能！
          </p>
        </div>
      </div>

      <h3 style="font-size: 1.3rem; font-weight: 600; margin: 2rem 0 1rem; color: #2750df;">🎭 アニメーション・インタラクション</h3>

      <div class="note-box" style="background: #f8f9fa;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">ホバーエフェクト</h4>
        <div class="code-block"><pre>/* カードホバー */
.card {
  position: relative;
  overflow: hidden;
  background-color: var(--white);
  border-radius: calc(10 * var(--px));
  transition: background-color calc(var(--duration) * .5) cubic-bezier(.23, 1, .32, 1);
}

.card:before {
  content: "";
  position: absolute;
  inset: 0;
  width: calc(100% - calc(10 * var(--px)));
  height: calc(100% - calc(10 * var(--px)));
  margin: auto;
  background: var(--gradient-6);
  border-radius: calc(10 * var(--px));
  opacity: 0;
  scale: 1;
  transition: calc(var(--duration) * .5) cubic-bezier(.23, 1, .32, 1);
  transition-property: opacity, width, height, scale;
}

.card:hover {
  background-color: transparent;
  transition-delay: .48s;
}

.card:hover:before {
  width: 100%;
  height: 100%;
  opacity: 1;
  scale: 1.01;
}

.card:hover img {
  transform: scale(1.1);
}

/* 矢印アニメーション（2つのSVGを重ねる） */
.arrow svg {
  position: absolute;
  width: 100%;
  transition: translate calc(var(--duration) * .4) cubic-bezier(.23, 1, .32, 1);
}

.arrow svg:first-child {
  translate: 0 0;
}

.arrow svg:last-child {
  translate: calc((100% + calc(5 * var(--px))) * -1) 0;
}

.card:hover .arrow svg:first-child {
  translate: 100% 0;
}

.card:hover .arrow svg:last-child {
  translate: 0 0;
}</pre></div>

        <div class="info-box" style="margin-top: 1rem;">
          <strong>✨ このアニメーションの工夫</strong>
          <ul style="margin-top: 0.5rem; padding-left: 1.5rem; line-height: 1.8;">
            <li><strong>cubic-bezier(.23, 1, .32, 1)</strong>のイージング（滑らかで自然）</li>
            <li><strong>transition-delay</strong>で段階的なアニメーション</li>
            <li><strong>2つのSVG矢印</strong>を重ねてスライドイン効果</li>
            <li><strong>:before疑似要素</strong>でグラデーション背景をオーバーレイ</li>
            <li><strong>scale(1.01)</strong>の微妙な拡大で立体感</li>
          </ul>
        </div>
      </div>

      <h3 style="font-size: 1.3rem; font-weight: 600; margin: 2rem 0 1rem; color: #2750df;">🖼️ SVGアイコンシステム</h3>

      <div class="note-box" style="background: #f8f9fa;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">SVG Spriteパターン</h4>
        <div class="code-block"><pre>&lt;!-- SVG Symbol定義（1回だけ定義） --&gt;
&lt;svg width="1em" height="1em" aria-hidden="true"&gt;
  &lt;symbol id="ai:local:common/arrow-forward" viewBox="0 0 10 10"&gt;
    &lt;path fill="currentColor" d="m9.425 4.612.388.389-.388.389-3.437 3.435-.777-.777L7.708 5.55H0v-1.1h7.707l-2.496-2.5.777-.778z"/&gt;
  &lt;/symbol&gt;
&lt;/svg&gt;

&lt;!-- 使い回し（何度でも） --&gt;
&lt;svg width="1em" height="1em" class="icon"&gt;
  &lt;use href="#ai:local:common/arrow-forward"&gt;&lt;/use&gt;
&lt;/svg&gt;</pre></div>

        <div class="info-box" style="margin-top: 1rem;">
          <strong>🎯 SVG Spriteの利点</strong>
          <ul style="margin-top: 0.5rem; padding-left: 1.5rem; line-height: 1.8;">
            <li><strong>再利用性が高い</strong>：1つ定義すれば何度でも使える</li>
            <li><strong>キャッシュ効率</strong>：外部ファイルより高速</li>
            <li><strong>fill="currentColor"</strong>でテキスト色に連動</li>
            <li><strong>width="1em"</strong>でフォントサイズに比例</li>
            <li><strong>絵文字は一切使わない</strong>（プロフェッショナル）</li>
          </ul>
        </div>
      </div>

      <h3 style="font-size: 1.3rem; font-weight: 600; margin: 2rem 0 1rem; color: #2750df;">📋 Finance Brainへの適用方針</h3>

      <div class="note-box" style="background: #fff3cd; border-left-color: #ff8c42;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">採用するポイント ✅</h4>
        <ul style="padding-left: 1.5rem; line-height: 1.8;">
          <li><strong>カラー</strong>：#5767bf を #2750df のように使用、グラデーション多用</li>
          <li><strong>フォント</strong>：ClashDisplay-Variable（英）+ Noto Sans JP（日）の組み合わせ</li>
          <li><strong>SVGアイコン</strong>：SVG Spriteパターンで統一（絵文字廃止）</li>
          <li><strong>レイアウト</strong>：CSS Grid + Container Queries</li>
          <li><strong>アニメーション</strong>：cubic-bezier(.23, 1, .32, 1)、矢印スライド、画像scale(1.1)</li>
          <li><strong>スペーシング</strong>：clamp()による流動的サイズ、8pxの倍数</li>
        </ul>
      </div>

      <div class="note-box" style="background: #ffe9e9; border-left-color: #d9534f; margin-top: 1.5rem;">
        <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">調整が必要なポイント ⚠️</h4>
        <ul style="padding-left: 1.5rem; line-height: 1.8;">
          <li><strong>グラデーションの使用量</strong>：やや控えめに（金融サイトとして）</li>
          <li><strong>アニメーション速度</strong>：少し遅めに（落ち着いた印象）</li>
          <li><strong>明度</strong>：やや明るめに（Finance Brainは親しみやすさ重視）</li>
          <li><strong>文字サイズ</strong>：やや大きめに（高齢者にも配慮）</li>
        </ul>
      </div>
    </div>

    <!-- 参考サイト（2026年トレンド） -->
    <div class="section">
      <h2 class="section-title">🌐 参考サイト（2026年モダンデザイン）</h2>

      <div class="info-box">
        <strong>📌 これらのサイトから学ぶポイント</strong>
        <p style="margin-top: 0.5rem; line-height: 1.8;">
          以下のサイトは「2026年のモダンWebデザイン」の参考例です。<br>
          ただし、<strong style="color: #d9534f;">そのまま真似るのではなく</strong>、「保守的・信頼重視」の金融サイトに落とし込む必要があります。
        </p>
      </div>

      <div class="note-box">
        <h3>海外の金融・Fintech系サイト</h3>
        <table>
          <thead>
            <tr>
              <th>サイト名</th>
              <th>URL</th>
              <th>参考にするポイント</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Stripe</strong></td>
              <td>stripe.com</td>
              <td>微妙なグラデーション、余白の取り方、アニメーション（控えめ）</td>
            </tr>
            <tr>
              <td><strong>Plaid</strong></td>
              <td>plaid.com</td>
              <td>グラスモーフィズム（アクセント程度）、タイポグラフィ</td>
            </tr>
            <tr>
              <td><strong>Revolut</strong></td>
              <td>revolut.com</td>
              <td>カラフルだがビジネスライク、カードデザイン</td>
            </tr>
            <tr>
              <td><strong>Wise</strong></td>
              <td>wise.com</td>
              <td>シンプル、クリーン、信頼感</td>
            </tr>
          </tbody>
        </table>

        <div class="info-box" style="margin-top: 1rem;">
          <strong>⚠️ 注意点</strong><br>
          これらは「攻めたデザイン」です。ファイナンスブレーンはもっと<strong>保守的</strong>にします。<br>
          「雰囲気」だけ参考にして、派手な要素は採用しません。
        </div>
      </div>

      <div class="note-box">
        <h3>国内の金融・保険サイト</h3>
        <table>
          <thead>
            <tr>
              <th>サイト名</th>
              <th>業種</th>
              <th>参考にするポイント</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>マネーフォワード</strong></td>
              <td>家計簿・会計</td>
              <td>クリーンなデザイン、余白、微妙なグラデーション</td>
            </tr>
            <tr>
              <td><strong>freee</strong></td>
              <td>会計ソフト</td>
              <td>親しみやすいイラスト、分かりやすいUI</td>
            </tr>
            <tr>
              <td><strong>ソニー生命</strong></td>
              <td>生命保険</td>
              <td>信頼感、落ち着いた配色、写真の使い方</td>
            </tr>
            <tr>
              <td><strong>プルデンシャル生命</strong></td>
              <td>生命保険</td>
              <td>プロフェッショナル感、余白の取り方</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="note-box">
        <h3>避けるべき参考サイト（金融には不向き）</h3>
        <ul style="margin: 0.5rem 0 0 1.5rem;">
          <li><strong>Linear.app:</strong> 先進的すぎる。ダークモード基調は金融に不向き</li>
          <li><strong>Apple.com:</strong> ミニマルすぎる。情報量が少なすぎて金融サイトに不向き</li>
          <li><strong>Awwwards受賞サイト:</strong> デザイン重視すぎて実用性が低い</li>
          <li><strong>ゲーム系サイト:</strong> 派手すぎて信頼感ゼロ</li>
        </ul>
      </div>
    </div>

    <!-- デザインの禁止事項（絶対にやってはいけないこと） -->
    <div class="section">
      <h2 class="section-title">🚫 デザインの禁止事項（絶対NG）</h2>

      <div class="note-box" style="border-left: 4px solid #d9534f;">
        <h3 style="color: #d9534f;">1. 派手すぎる色・グラデーション</h3>
        <div class="code-block"><pre>/* ❌ 絶対にやってはいけない例 */
background: linear-gradient(45deg, #ff0080, #7928ca, #0070f3);
background: radial-gradient(circle, #ff006e, #8338ec);
color: #00ff00;  /* 蛍光グリーン */
color: #ff00ff;  /* マゼンタ */

/* ✅ これならOK */
background: linear-gradient(135deg, #5767bf 0%, #6b7ac7 100%);
color: #333;  /* 落ち着いたグレー */</pre></div>
        <p style="margin-top: 1rem; color: #666; line-height: 1.7;">
          <strong>理由:</strong> 金融サービスは「信頼」が命。派手な色は軽薄に見え、顧客の不安を煽ります。
        </p>
      </div>

      <div class="note-box" style="border-left: 4px solid #d9534f;">
        <h3 style="color: #d9534f;">2. 過度なアニメーション</h3>
        <div class="code-block"><pre>/* ❌ 絶対にやってはいけない例 */
@keyframes crazy-spin {
  0% { transform: rotate(0deg) scale(1); }
  100% { transform: rotate(720deg) scale(2); }
}
.element {
  animation: crazy-spin 2s infinite;  /* グルグル回り続ける */
}

/* ❌ バウンド（跳ねる） */
@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-50px); }
}

/* ✅ これならOK */
.fade-in {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-in.is-visible {
  opacity: 1;
  transform: translateY(0);
}</pre></div>
        <p style="margin-top: 1rem; color: #666; line-height: 1.7;">
          <strong>理由:</strong> 過度なアニメーションは気が散り、プロフェッショナル感が損なわれます。
        </p>
      </div>

      <div class="note-box" style="border-left: 4px solid #d9534f;">
        <h3 style="color: #d9534f;">3. 読みにくいテキスト</h3>
        <div class="code-block"><pre>/* ❌ 絶対にやってはいけない例 */
.text {
  font-size: 10px;  /* 小さすぎて読めない */
  color: #ccc;      /* 背景が白だとコントラスト不足 */
  line-height: 1.0; /* 詰まりすぎ */
  letter-spacing: 10px;  /* 開きすぎ */
  font-weight: 100;  /* 細すぎて読めない */
}

/* ✅ これならOK */
.text {
  font-size: 16px;
  color: #333;
  line-height: 1.7;
  letter-spacing: normal;
  font-weight: 400;
}</pre></div>
        <p style="margin-top: 1rem; color: #666; line-height: 1.7;">
          <strong>理由:</strong> 金融サービスは高齢者も利用します。読みやすさは最優先事項です。
        </p>
      </div>

      <div class="note-box" style="border-left: 4px solid #d9534f;">
        <h3 style="color: #d9534f;">4. 重すぎる画像・動画</h3>
        <ul style="margin: 0.5rem 0 0 1.5rem;">
          <li>❌ 5MB以上のJPEG画像（圧縮しろ）</li>
          <li>❌ 全画面自動再生動画（モバイルで死ぬ）</li>
          <li>❌ GIFアニメ多用（容量が重い）</li>
          <li>❌ 非圧縮PNG（WebPにしろ）</li>
        </ul>
        <p style="margin-top: 1rem; color: #666; line-height: 1.7;">
          <strong>理由:</strong> ページが遅いとSEOで不利。ユーザーは3秒待たずに離脱します。
        </p>
      </div>

      <div class="note-box" style="border-left: 4px solid #d9534f;">
        <h3 style="color: #d9534f;">5. スマホ対応していないデザイン</h3>
        <ul style="margin: 0.5rem 0 0 1.5rem;">
          <li>❌ 固定幅レイアウト（width: 1200px固定）</li>
          <li>❌ ボタンが小さすぎてタップできない（44x44px未満）</li>
          <li>❌ 横スクロール発生</li>
          <li>❌ テキストが小さすぎて読めない（12px未満）</li>
        </ul>
        <p style="margin-top: 1rem; color: #666; line-height: 1.7;">
          <strong>理由:</strong> 2026年現在、Web閲覧の70%以上はスマホ。レスポンシブ対応は必須です。
        </p>
      </div>
    </div>

    <!-- 実装チェックリスト -->
    <div class="section">
      <h2 class="section-title">✅ 実装チェックリスト</h2>

      <div class="info-box">
        <strong>📌 コーディング前に必ず確認</strong>
        <p style="margin-top: 0.5rem; line-height: 1.8;">
          以下のチェックリストを使って、デザインガイドラインに沿っているか確認してください。<br>
          1つでも「いいえ」があれば、修正してから次に進みましょう。
        </p>
      </div>

      <div class="note-box">
        <h3>カラー・タイポグラフィ</h3>
        <ul class="checklist">
          <li>カラーパレット以外の色を使っていないか？</li>
          <li>グラデーションは控えめか？（派手すぎないか）</li>
          <li>本文のフォントサイズは16px以上か？</li>
          <li>行間（line-height）は1.6以上か？</li>
          <li>テキストと背景のコントラスト比は4.5:1以上か？</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>レイアウト・余白</h3>
        <ul class="checklist">
          <li>余白は8の倍数（8px, 16px, 24px...）で統一しているか？</li>
          <li>セクション間の余白は十分か？（PC: 80px, SP: 60px）</li>
          <li>1行の文字数は35〜45文字以内か？（PC）</li>
          <li>カード内の余白は適切か？（PC: 32px, SP: 24px）</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>ボタン・リンク</h3>
        <ul class="checklist">
          <li>1画面にプライマリーボタンは1つだけか？</li>
          <li>ボタンのタップ領域は44x44px以上か？（スマホ対応）</li>
          <li>ホバー時の動きは控えめか？（2-4px程度）</li>
          <li>ローディング中はdisabled状態にしているか？</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>アニメーション</h3>
        <ul class="checklist">
          <li>アニメーションは控えめか？（派手すぎないか）</li>
          <li>トランジション時間は0.3〜0.6秒以内か？</li>
          <li>回転・バウンドなど派手な動きは使っていないか？</li>
          <li>アニメーションは減速運動（ease）を使っているか？</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>パフォーマンス</h3>
        <ul class="checklist">
          <li>画像はWebP形式にしているか？</li>
          <li>画像にwidth/height属性を指定しているか？</li>
          <li>画像にloading="lazy"を指定しているか？</li>
          <li>フォントはfont-display: swapにしているか？</li>
          <li>不要なJavaScriptライブラリは削除したか？</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>レスポンシブ対応</h3>
        <ul class="checklist">
          <li>スマホで横スクロールが発生していないか？</li>
          <li>スマホでボタンが小さすぎないか？（44x44px以上）</li>
          <li>スマホでテキストが読みやすいか？（16px以上）</li>
          <li>タブレットでもレイアウトが崩れていないか？</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>アクセシビリティ</h3>
        <ul class="checklist">
          <li>altテキストは全画像に設定しているか？</li>
          <li>フォームのlabelは適切に設定しているか？</li>
          <li>キーボードだけで操作できるか？</li>
          <li>リンクテキストは「こちら」ではなく具体的か？</li>
        </ul>
      </div>
    </div>

    <!-- UIコンポーネント -->
    <div class="section">
      <h2 class="section-title">🎛️ UIコンポーネント（旧情報・参考）</h2>

      <div class="note-box">
        <h3>ボタン</h3>
        <ul>
          <li><strong>プライマリーボタン:</strong> 背景 #5767bf, テキスト #ffffff, ホバー時 #3a4a8f</li>
          <li><strong>セカンダリーボタン:</strong> 背景 #ff8c42, テキスト #ffffff, ホバー時 #e67e22</li>
          <li><strong>アウトラインボタン:</strong> ボーダー #5767bf, テキスト #5767bf, ホバー時背景 #5767bf</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>カード</h3>
        <ul>
          <li><strong>背景:</strong> #ffffff</li>
          <li><strong>ボーダー:</strong> 1px solid #e0e0e0</li>
          <li><strong>ボックスシャドウ:</strong> 0 2px 8px rgba(0, 0, 0, 0.05)</li>
          <li><strong>ホバー時:</strong> 0 4px 12px rgba(0, 0, 0, 0.1)</li>
          <li><strong>角丸:</strong> 8px</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>セクション</h3>
        <ul>
          <li><strong>パディング:</strong> 80px 0（PC）, 60px 0（SP）</li>
          <li><strong>交互背景:</strong> #f5f7fa と #ffffff</li>
        </ul>
      </div>
    </div>

    <!-- アニメーション -->
    <div class="section">
      <h2 class="section-title">🎬 アニメーション</h2>

      <div class="note-box">
        <h3>トランジション</h3>
        <ul>
          <li><strong>標準:</strong> all 0.3s ease</li>
          <li><strong>ホバー:</strong> transform 0.2s ease</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>使用可能なアニメーション</h3>
        <ul>
          <li>フェードイン</li>
          <li>スライドイン（上から・下から・左から・右から）</li>
          <li>スケール（拡大・縮小）</li>
        </ul>
      </div>
    </div>

    <!-- 制作進捗 -->
    <div class="section">
      <h2 class="section-title">📋 制作進捗</h2>

      <div class="note-box">
        <h3>完了済み</h3>
        <ul class="checklist">
          <li>なし（これから作成開始）</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>進行中</h3>
        <ul class="checklist">
          <li>トップページ</li>
          <li>個人向けサービスページ</li>
          <li>法人向けサービスページ</li>
          <li>その他ページ</li>
        </ul>
      </div>

      <div class="note-box">
        <h3>保留中</h3>
        <ul class="checklist">
          <li>WordPress化</li>
        </ul>
      </div>
    </div>

    <!-- 参考リンク -->
    <div class="section">
      <h2 class="section-title">🔗 参考リンク</h2>
      <div class="link-grid">
        <div class="link-card">
          <a href="../index.html" target="_blank">仕様書</a>
        </div>
        <div class="link-card">
          <a href="../estimate.html" target="_blank">お見積書</a>
        </div>
        <div class="link-card">
          <a href="../mockup/index.html" target="_blank">モックアップ</a>
        </div>
        <div class="link-card">
          <a href="../index.html#color-proposals" target="_blank">カラースキーム案</a>
        </div>
        <div class="link-card">
          <a href="../index.html#design-direction" target="_blank">デザイン方向性</a>
        </div>
      </div>
    </div>

    <!-- 注意事項 -->
    <div class="section">
      <div class="info-box">
        <strong>⚠️ 注意事項</strong>
        <ul style="margin-top: 0.5rem;">
          <li>モックアップディレクトリ (<code>mockup/</code>) は参考用として残します</li>
          <li>このディレクトリで実際のデザイン・コーディングを進めます</li>
          <li>完成後、WordPress化の準備を行います</li>
          <li>デザイントンマナから外れないように注意してください</li>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>
