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
      <h2 class="section-title">📂 ディレクトリ構成</h2>
      <div class="code-block"><pre>design-final/
├── index.html              # トップページ（デザイン完成版）
├── about/                  # 会社紹介
├── services/               # サービスページ
│   ├── personal/          # 個人向け
│   └── corporate/         # 法人向け
├── why-us/                # 選ばれる理由
├── voice/                 # お客様の声
├── staff/                 # スタッフ紹介
├── company/               # 会社情報
├── news/                  # お知らせ
├── faq/                   # よくあるご質問
├── contact/               # お問い合わせ
└── assets/                # 静的ファイル
    ├── css/
    ├── js/
    ├── images/
    └── fonts/</pre></div>
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
