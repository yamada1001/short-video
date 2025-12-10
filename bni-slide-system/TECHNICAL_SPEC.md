# BNIスライドシステム 技術仕様書

## 🏗️ アーキテクチャ概要

### 現在のシステム構成
```
bni-slide-system/
├── index.php              # アンケート入力画面
├── admin/
│   ├── slide.php          # スライド表示画面
│   └── edit.php           # データ編集画面
├── data/
│   ├── *.csv              # 週次データ（YYYY-MM-DD.csv）
│   └── members.json       # メンバー情報
├── assets/
│   ├── js/
│   │   ├── slide.js       # スライド制御
│   │   └── svg-slide-generator.js  # スライド生成
│   └── css/
│       └── slide.css      # スライドスタイル
└── api_*.php              # 各種API
```

### 新規追加ファイル構成
```
data/
├── slide_config.json      # スライド設定（新規）
├── birthdays.json         # 誕生日情報（新規）
└── speaker_rotation.json  # スピーカーローテーション（新規）

uploads/
└── members/               # メンバー写真（新規）
    ├── yamada.jpg
    └── tanaka.jpg

admin/
├── slide_config.php       # スライド設定管理画面（新規）
└── member_photos.php      # メンバー写真管理画面（新規）

api_slide_config.php       # スライド設定API（新規）
```

---

## 📋 データ構造詳細

### 1. `data/members.json` 拡張版
```json
[
  {
    "id": 1,
    "name": "山田太郎",
    "email": "yamada@example.com",
    "company": "株式会社サンプル",
    "industry": "経営コンサル",
    "industry_icon": "briefcase",
    "photo": "uploads/members/yamada.jpg",
    "birthday": "05-15",
    "joined_date": "2024-10-31",
    "team": "A",
    "role": "メンバー",
    "phone": "090-1234-5678",
    "position": "代表取締役",
    "pitch_time": 33
  }
]
```

### 2. `data/slide_config.json`（新規）
```json
{
  "president": {
    "name": "高橋",
    "message": "本日も宜しくお願いします"
  },
  "secretary": {
    "name": "蔦山佳子",
    "message": "会費の納入をお願いします"
  },
  "director": {
    "name": "木津裕充",
    "message": "次回の研修についてお知らせします"
  },
  "visitor_hosts": [
    {
      "name": "野口浩乃",
      "company": "株式会社くるみ不動産",
      "industry": "不動産売買",
      "photo": "uploads/members/noguchi.jpg"
    },
    {
      "name": "吉田惇哉",
      "company": "ジブラルタ生命保険株式会社",
      "industry": "生命保険",
      "photo": "uploads/members/yoshida.jpg"
    },
    {
      "name": "渡邊真造",
      "company": "ヤルモグループ",
      "industry": "冠婚葬祭",
      "photo": "uploads/members/watanabe.jpg"
    }
  ],
  "orientation_facilitator": {
    "name": "穴井佑一",
    "title": "BNI大分リージョン リージョナルディレクター",
    "photo": "uploads/members/anai.jpg"
  },
  "networking_education": {
    "presenter": "蔦山佳子",
    "role": "書記兼会計",
    "category": "マヤ暦",
    "photo": "uploads/members/tsutayama.jpg"
  },
  "chapter_motto": "Keep growing 宗麟\n〜貢献の絆で未来を創る〜",
  "values": [
    "前進し続ける・やり続ける",
    "全員が成長し続け大分を大きくする"
  ]
}
```

### 3. `data/speaker_rotation.json`（新規）
```json
[
  {
    "date": "11/7",
    "presenter": "吉岡恭介",
    "industry": "高齢者コンシェルジュ",
    "target": "高齢者の方々に関わる仕事\n医療福祉系に関わっている方",
    "status": "past"
  },
  {
    "date": "11/14",
    "presenter": "野口浩乃",
    "industry": "不動産売買",
    "target": "高齢者施設関係者\n高齢者ビジネスをされている方\n医療、介護、保育・こども園、動物病院等を営んでいて新築・移転・新規開設を考えている方",
    "status": "past"
  },
  {
    "date": "11/21",
    "presenter": "早野大介",
    "industry": "便利屋",
    "target": "豊かな未来を次世代に残していきたい方\n集客を求めている方\n投資家、コンサルティング業、便利屋",
    "status": "past"
  },
  {
    "date": "11/28",
    "presenter": "花本昭彦",
    "industry": "住宅塗装",
    "target": "不動産屋、アパートオーナー、ハウスメーカー\n戸建てにお住まいの方",
    "status": "current"
  },
  {
    "date": "12/5",
    "presenter": "佐藤公重",
    "industry": "相続コーディネーター",
    "target": "子供が県外で、ご自身は大分県内で一人暮らししている70代の方",
    "status": "future"
  }
]
```

---

## 🎨 スライド生成ロジック詳細

### スライド順序（完全版）
```javascript
// svg-slide-generator.js の構造

async function generateSVGSlides(data, stats, slideDate, pitchPresenter, config) {
  let slides = '';

  // 1. タイトル ✅既存
  slides += generateTitleSlide(slideDate);

  // 2. オープニングセクション 🆕
  slides += generateAttendanceCheckSlide(config);
  slides += generateBusinessCardSeatingSlide(config);
  slides += generatePresidentMessageSlide(config);
  slides += generateGoodAndNewSlide();

  // 3. メインプレゼン導入 🆕
  slides += generateMainPresentationIntroSlide(config);
  slides += generateStartDashPresenSlide();

  // 4. サマリー ✅既存
  slides += generateSummarySlide(stats);

  // 5. ビジター紹介 ✅既存
  slides += generateVisitorListSlide(data);
  slides += generateVisitorSelfIntroSlides(data);

  // 6. リファーラル発表テンプレート 🆕
  slides += generateReferralTemplateSlide();

  // 7. 新入会メンバー 🆕
  slides += generateNewMemberSlide(config);

  // 8. 月間チャンピオン 🆕
  slides += generateMonthlyChampionsSlide(stats);

  // 9. ハッピーバースデー 🆕
  slides += generateBirthdaySlide(config);

  // 10. 過去最多記録 🆕
  slides += generateRecordAnnouncementSlide(stats);

  // 11. 書記兼会計より 🆕
  slides += generateSecretaryMessageSlide(config);

  // 12. ディレクターより 🆕
  slides += generateDirectorMessageSlide(config);

  // 13. ビジターホスト 🆕
  slides += generateVisitorHostsSlide(config);

  // 14. Welcome to BNI 🆕
  slides += generateWelcomeToBNISlide();

  // 15. BNI理念 🆕
  slides += generateBNICoreValuesSlide();
  slides += generateGiversGainSlide();

  // 16. ネットワーキング学習 🆕
  slides += generateNetworkingEducationSlide(config);

  // 17. 60秒ピッチ（全員分） 🆕最重要
  slides += generateAllMemberPitchSlides(config);

  // 18. ビジター自己紹介テンプレート 🆕
  slides += generateVisitorSelfIntroTemplateSlide();

  // 19. ピッチ資料 ✅既存
  slides += generatePitchFileSlide(pitchPresenter);

  // 20. リファーラル金額内訳 ✅既存
  slides += generateReferralBreakdownSlide(stats);

  // 21. メンバー別貢献度 ✅既存
  slides += generateMemberContributionsSlide(data);

  // 22. リファーラル詳細 ✅既存
  slides += generateReferralDetailsSlide(data);

  // 23. 活動サマリー ✅既存
  slides += generateActivitySummarySlide(stats);

  // 24. スピーカーローテーション 🆕
  slides += generateSpeakerRotationSlide(config);

  // 25. 「良かった点」 🆕
  slides += generateFeedbackSlide();

  // 26. ビジターオリエンテーション 🆕
  slides += generateOrientationSlide(config);

  // 27. 各コーディネーターより 🆕
  slides += generateCoordinatorMessageSlide();

  // 28. クロージング 🆕
  slides += generateClosingSlide(config);

  // 29. Thank You ✅既存
  slides += generateThankYouSlide();

  return slides;
}
```

---

## 🔧 重要機能の実装詳細

### 1. 60秒ピッチスライド生成
```javascript
function generateAllMemberPitchSlides(config) {
  const members = config.members || [];
  let slides = '';

  members.forEach((member, index) => {
    const photoUrl = member.photo || 'assets/images/default-avatar.png';
    const industryIcon = getIndustryIcon(member.industry_icon);
    const pitchTime = member.pitch_time || 30;

    slides += `
      <section class="member-pitch-slide" data-member="${escapeHtml(member.name)}">
        <div class="pitch-layout">
          <div class="member-photo-container">
            <img src="${photoUrl}" alt="${escapeHtml(member.name)}" class="member-photo">
          </div>
          <div class="member-info">
            <div class="industry-badge">
              <i class="fas fa-${industryIcon}"></i>
              <span>${escapeHtml(member.industry)}</span>
            </div>
            <h2 class="member-name">${escapeHtml(member.name)}</h2>
            <p class="member-company">${escapeHtml(member.company)}</p>
            <p class="member-position">(${escapeHtml(member.position || '')})</p>
          </div>
          <div class="pitch-timer" data-time="${pitchTime}">
            <<00:${String(pitchTime).padStart(2, '0')}>>
          </div>
        </div>
      </section>
    `;
  });

  return slides;
}
```

### 2. 名刺交換席次表の生成
```javascript
function generateBusinessCardSeatingSlide(config) {
  const teams = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

  return `
    <section class="seating-chart-slide">
      <div class="seating-header">
        <div class="left-label">ポーティアム</div>
        <h2>名刺交換時</h2>
        <div class="right-label">山本 花田 佳子</div>
      </div>
      <div class="seating-grid">
        ${teams.map(team => generateTeamCircle(team, config)).join('')}
        <div class="center-circle">
          <span>H</span>
        </div>
      </div>
      <div class="screen-label">スクリーン</div>
    </section>
  `;
}

function generateTeamCircle(team, config) {
  const members = config.members.filter(m => m.team === team);
  const memberNames = members.map(m => m.name.split(' ')[1] || m.name).join('\n');

  return `
    <div class="team-circle team-${team}">
      <div class="team-label">${team}</div>
      <div class="team-members">${memberNames}</div>
    </div>
  `;
}
```

### 3. 月間チャンピオン計算
```javascript
function calculateMonthlyChampions(data, stats) {
  // 過去1ヶ月のデータから集計
  const visitorChampion = findTopVisitorIntroducer(data);
  const oneToOneChampion = findTopOneToOne(data);
  const ceuChampion = findTopCEU(data);

  return {
    visitor: visitorChampion,
    oneToOne: oneToOneChampion,
    ceu: ceuChampion
  };
}
```

---

## 🎯 実装ロードマップ

### Week 1: データ基盤整備
- [ ] `data/slide_config.json` 作成
- [ ] `data/members.json` 拡張
- [ ] メンバー写真アップロード機能
- [ ] 設定管理画面UI作成

### Week 2: 最重要機能実装
- [ ] 60秒ピッチスライド生成
- [ ] 名刺交換席次表
- [ ] オープニングセクション

### Week 3: データ表示機能
- [ ] 月間チャンピオン計算・表示
- [ ] ハッピーバースデー
- [ ] スピーカーローテーション

### Week 4: 静的コンテンツ
- [ ] BNI理念スライド
- [ ] リファーラル発表テンプレート
- [ ] その他固定スライド

### Week 5: テスト・調整
- [ ] 全機能統合テスト
- [ ] パフォーマンス最適化
- [ ] ドキュメント整備

---

## 🚀 次のアクション

1. ✅ このドキュメントを確認
2. データ準備を開始
3. 段階的実装を開始
