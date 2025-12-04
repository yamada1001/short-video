/**
 * BNI Slide System - Google Apps Script
 * Google Slides自動生成スクリプト
 */

/**
 * メイン関数：スライドを生成
 */
function generateBNISlides() {
  // サンプルデータ（実際はスプレッドシートから取得）
  const data = getSampleData();
  const stats = calculateStats(data);

  // 新しいプレゼンテーションを作成
  const presentation = SlidesApp.create('BNI週次レポート - ' + Utilities.formatDate(new Date(), 'Asia/Tokyo', 'yyyy年MM月dd日'));
  const presentationId = presentation.getId();

  Logger.log('プレゼンテーションID: ' + presentationId);
  Logger.log('URL: https://docs.google.com/presentation/d/' + presentationId);

  // スライドを削除（デフォルトの空白スライドを削除）
  const slides = presentation.getSlides();
  if (slides.length > 0) {
    slides[0].remove();
  }

  // スライドを生成
  createTitleSlide(presentation, stats);
  createSummarySlide(presentation, stats);
  createVisitorListSlides(presentation, data);
  createReferralBreakdownSlide(presentation, stats);
  createMemberContributionSlides(presentation, stats);
  createReferralDetailSlides(presentation, data);
  createActivitySummarySlide(presentation, stats, data);
  createThankYouSlide(presentation);

  // URLを返す
  return presentation.getUrl();
}

/**
 * Slide 1: タイトルスライド
 */
function createTitleSlide(presentation, stats) {
  const slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
  const pageElements = slide.getPageElements();

  // 背景色を白に設定
  const background = slide.getBackground();
  background.setSolidFill('#FFFFFF');

  // タイトル
  const titleBox = slide.insertTextBox('BNI週次レポート', 50, 150, 600, 80);
  const titleText = titleBox.getText();
  titleText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(48)
    .setBold(true)
    .setForegroundColor('#CF2030');
  titleText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);

  // 日付
  const today = Utilities.formatDate(new Date(), 'Asia/Tokyo', 'yyyy年M月d日');
  const dateBox = slide.insertTextBox(today, 50, 250, 600, 50);
  const dateText = dateBox.getText();
  dateText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(24)
    .setForegroundColor('#666666');
  dateText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);

  // ブランディング
  const brandBox = slide.insertTextBox('Givers Gain® | BNI Slide System', 50, 400, 600, 40);
  const brandText = brandBox.getText();
  brandText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(14)
    .setForegroundColor('#999999');
  brandText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);
}

/**
 * Slide 2: 今週のサマリー（横1列バッジスタイル）
 */
function createSummarySlide(presentation, stats) {
  const slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
  const background = slide.getBackground();
  background.setSolidFill('#FFFFFF');

  // タイトル
  const titleBox = slide.insertTextBox('今週のサマリー', 50, 30, 600, 50);
  const titleText = titleBox.getText();
  titleText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(32)
    .setBold(true)
    .setForegroundColor('#CF2030');
  titleText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);

  // バッジを横1列に配置
  const badges = [
    { icon: '👥', number: stats.total_visitors, label: 'ビジター紹介' },
    { icon: '💰', number: '¥' + formatNumber(stats.total_referral_amount), label: 'リファーラル金額' },
    { icon: '✓', number: stats.total_attendance, label: '出席者数' },
    { icon: '🤝', number: stats.total_one_to_one, label: '121実施数' }
  ];

  const badgeWidth = 140;
  const badgeHeight = 80;
  const gap = 20;
  const startX = 50;
  const startY = 150;

  badges.forEach((badge, index) => {
    const x = startX + (badgeWidth + gap) * index;

    // バッジ背景（角丸四角形）
    const shape = slide.insertShape(SlidesApp.ShapeType.ROUND_RECTANGLE, x, startY, badgeWidth, badgeHeight);
    shape.getFill().setSolidFill('#F8F9FA');
    shape.getBorder().setTransparent();

    // アイコン
    const iconBox = slide.insertTextBox(badge.icon, x + 10, startY + 10, 30, 30);
    iconBox.getText().getTextStyle().setFontSize(24);

    // 数値
    const numberBox = slide.insertTextBox(String(badge.number), x + 10, startY + 35, badgeWidth - 20, 25);
    const numberText = numberBox.getText();
    numberText.getTextStyle()
      .setFontFamily('Inter')
      .setFontSize(20)
      .setBold(true)
      .setForegroundColor('#CF2030');

    // ラベル
    const labelBox = slide.insertTextBox(badge.label, x + 10, startY + 55, badgeWidth - 20, 20);
    const labelText = labelBox.getText();
    labelText.getTextStyle()
      .setFontFamily('Noto Sans JP')
      .setFontSize(10)
      .setForegroundColor('#666666');
  });
}

/**
 * Slide 3: ビジター紹介一覧（5件/ページ）
 */
function createVisitorListSlides(presentation, data) {
  const visitorsWithData = data.filter(row => row.visitor_name);
  const itemsPerPage = 5;
  const totalPages = Math.ceil(visitorsWithData.length / itemsPerPage);

  for (let page = 0; page < totalPages; page++) {
    const start = page * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = visitorsWithData.slice(start, end);

    const slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
    const background = slide.getBackground();
    background.setSolidFill('#FFFFFF');

    // タイトル
    const title = totalPages > 1 ? `ビジター紹介一覧 (${page + 1}/${totalPages})` : 'ビジター紹介一覧';
    const titleBox = slide.insertTextBox(title, 50, 30, 600, 40);
    const titleText = titleBox.getText();
    titleText.getTextStyle()
      .setFontFamily('Noto Sans JP')
      .setFontSize(28)
      .setBold(true)
      .setForegroundColor('#CF2030');

    // テーブル
    const table = slide.insertTable(pageData.length + 1, 4, 50, 90, 600, 300);

    // ヘッダー
    const headers = ['紹介者', 'ビジター名', '業種', '紹介日'];
    headers.forEach((header, colIndex) => {
      const cell = table.getCell(0, colIndex);
      cell.getText().setText(header);
      cell.getText().getTextStyle()
        .setFontFamily('Noto Sans JP')
        .setFontSize(11)
        .setBold(true)
        .setForegroundColor('#FFFFFF');
      cell.getFill().setSolidFill('#CF2030');
    });

    // データ行
    pageData.forEach((row, rowIndex) => {
      const rowData = [
        row.introducer_name,
        row.visitor_name,
        row.visitor_industry || '-',
        row.introduction_date
      ];

      rowData.forEach((data, colIndex) => {
        const cell = table.getCell(rowIndex + 1, colIndex);
        cell.getText().setText(data);
        cell.getText().getTextStyle()
          .setFontFamily('Noto Sans JP')
          .setFontSize(10)
          .setForegroundColor('#333333');

        // 偶数行に背景色
        if ((rowIndex + 1) % 2 === 0) {
          cell.getFill().setSolidFill('#F8F9FA');
        }
      });
    });
  }
}

/**
 * Slide 4: リファーラル金額内訳
 */
function createReferralBreakdownSlide(presentation, stats) {
  const slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
  const background = slide.getBackground();
  background.setSolidFill('#FFFFFF');

  // タイトル
  const titleBox = slide.insertTextBox('リファーラル金額内訳', 50, 30, 600, 40);
  const titleText = titleBox.getText();
  titleText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(28)
    .setBold(true)
    .setForegroundColor('#CF2030');
  titleText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);

  // 総額ボックス
  const totalBox = slide.insertShape(SlidesApp.ShapeType.ROUND_RECTANGLE, 150, 90, 400, 60);
  totalBox.getFill().setSolidFill('#FFF5F5');
  totalBox.getBorder().getLineFill().setSolidFill('#CF2030');
  totalBox.getBorder().setWeight(2);

  const totalText = totalBox.getText();
  totalText.setText('総額: ¥' + formatNumber(stats.total_referral_amount));
  totalText.getTextStyle()
    .setFontFamily('Inter')
    .setFontSize(24)
    .setBold(true)
    .setForegroundColor('#27AE60');
  totalText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);

  // カテゴリ別内訳（プログレスバー）
  let yPos = 180;
  Object.entries(stats.categories).forEach(([category, amount]) => {
    const percentage = stats.total_referral_amount > 0
      ? ((amount / stats.total_referral_amount) * 100).toFixed(1)
      : 0;

    // カテゴリ名と金額
    const labelBox = slide.insertTextBox(category, 100, yPos, 250, 20);
    labelBox.getText().getTextStyle()
      .setFontFamily('Noto Sans JP')
      .setFontSize(11)
      .setBold(true)
      .setForegroundColor('#333333');

    const amountBox = slide.insertTextBox('¥' + formatNumber(amount), 400, yPos, 200, 20);
    const amountText = amountBox.getText();
    amountText.getTextStyle()
      .setFontFamily('Inter')
      .setFontSize(11)
      .setBold(true)
      .setForegroundColor('#27AE60');
    amountText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.END);

    // プログレスバー背景
    const barBg = slide.insertShape(SlidesApp.ShapeType.ROUND_RECTANGLE, 100, yPos + 25, 500, 15);
    barBg.getFill().setSolidFill('#E9ECEF');
    barBg.getBorder().setTransparent();

    // プログレスバー
    const barWidth = 500 * (percentage / 100);
    if (barWidth > 0) {
      const bar = slide.insertShape(SlidesApp.ShapeType.ROUND_RECTANGLE, 100, yPos + 25, barWidth, 15);
      bar.getFill().setSolidFill('#CF2030');
      bar.getBorder().setTransparent();

      // パーセンテージ表示
      if (barWidth > 50) {
        const percentBox = slide.insertTextBox(percentage + '%', 100, yPos + 25, barWidth, 15);
        percentBox.getText().getTextStyle()
          .setFontSize(9)
          .setBold(true)
          .setForegroundColor('#FFFFFF');
        percentBox.getText().getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);
      }
    }

    yPos += 50;
  });
}

/**
 * Slide 5: メンバー別貢献度（6件/ページ、3列×2行）
 */
function createMemberContributionSlides(presentation, stats) {
  const memberEntries = Object.entries(stats.members);
  const itemsPerPage = 6;
  const totalPages = Math.ceil(memberEntries.length / itemsPerPage);

  for (let page = 0; page < totalPages; page++) {
    const start = page * itemsPerPage;
    const end = start + itemsPerPage;
    const pageMembers = memberEntries.slice(start, end);

    const slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
    const background = slide.getBackground();
    background.setSolidFill('#FFFFFF');

    // タイトル
    const title = totalPages > 1 ? `メンバー別貢献度 (${page + 1}/${totalPages})` : 'メンバー別貢献度';
    const titleBox = slide.insertTextBox(title, 50, 30, 600, 40);
    const titleText = titleBox.getText();
    titleText.getTextStyle()
      .setFontFamily('Noto Sans JP')
      .setFontSize(28)
      .setBold(true)
      .setForegroundColor('#CF2030');

    // メンバーカードを3列×2行で配置
    const cardWidth = 180;
    const cardHeight = 90;
    const gapX = 20;
    const gapY = 20;
    const startX = 60;
    const startY = 100;

    pageMembers.forEach(([member, memberStats], index) => {
      const col = index % 3;
      const row = Math.floor(index / 3);
      const x = startX + (cardWidth + gapX) * col;
      const y = startY + (cardHeight + gapY) * row;

      // カード背景
      const card = slide.insertShape(SlidesApp.ShapeType.ROUND_RECTANGLE, x, y, cardWidth, cardHeight);
      card.getFill().setSolidFill('#F8F9FA');
      card.getBorder().getLineFill().setSolidFill('#CF2030');
      card.getBorder().setWeight(1);

      // メンバー名
      const nameBox = slide.insertTextBox(member, x + 10, y + 10, cardWidth - 20, 25);
      nameBox.getText().getTextStyle()
        .setFontFamily('Noto Sans JP')
        .setFontSize(12)
        .setBold(true)
        .setForegroundColor('#CF2030');

      // 統計情報
      const statsText = `ビジター: ${memberStats.visitors}名\nリファーラル: ¥${formatNumber(memberStats.referral_amount)}`;
      const statsBox = slide.insertTextBox(statsText, x + 10, y + 40, cardWidth - 20, 40);
      statsBox.getText().getTextStyle()
        .setFontFamily('Noto Sans JP')
        .setFontSize(10)
        .setForegroundColor('#666666');
    });
  }
}

/**
 * Slide 6: リファーラル詳細（5件/ページ）
 */
function createReferralDetailSlides(presentation, data) {
  const itemsPerPage = 5;
  const totalPages = Math.ceil(data.length / itemsPerPage);

  for (let page = 0; page < totalPages; page++) {
    const start = page * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = data.slice(start, end);

    const slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
    const background = slide.getBackground();
    background.setSolidFill('#FFFFFF');

    // タイトル
    const title = totalPages > 1 ? `リファーラル詳細 (${page + 1}/${totalPages})` : 'リファーラル詳細';
    const titleBox = slide.insertTextBox(title, 50, 30, 600, 40);
    const titleText = titleBox.getText();
    titleText.getTextStyle()
      .setFontFamily('Noto Sans JP')
      .setFontSize(28)
      .setBold(true)
      .setForegroundColor('#CF2030');

    // テーブル
    const table = slide.insertTable(pageData.length + 1, 4, 50, 90, 600, 300);

    // ヘッダー
    const headers = ['案件名', '金額', 'カテゴリ', '提供者'];
    headers.forEach((header, colIndex) => {
      const cell = table.getCell(0, colIndex);
      cell.getText().setText(header);
      cell.getText().getTextStyle()
        .setFontFamily('Noto Sans JP')
        .setFontSize(11)
        .setBold(true)
        .setForegroundColor('#FFFFFF');
      cell.getFill().setSolidFill('#CF2030');
    });

    // データ行
    pageData.forEach((row, rowIndex) => {
      const rowData = [
        row.project_name,
        '¥' + formatNumber(row.referral_amount),
        row.category,
        row.referral_provider || '-'
      ];

      rowData.forEach((data, colIndex) => {
        const cell = table.getCell(rowIndex + 1, colIndex);
        cell.getText().setText(data);
        cell.getText().getTextStyle()
          .setFontFamily('Noto Sans JP')
          .setFontSize(10)
          .setForegroundColor('#333333');

        // 金額の列は緑色
        if (colIndex === 1) {
          cell.getText().getTextStyle().setForegroundColor('#27AE60').setBold(true);
        }

        // 偶数行に背景色
        if ((rowIndex + 1) % 2 === 0) {
          cell.getFill().setSolidFill('#F8F9FA');
        }
      });
    });
  }
}

/**
 * Slide 7: アクティビティサマリー
 */
function createActivitySummarySlide(presentation, stats, data) {
  const slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
  const background = slide.getBackground();
  background.setSolidFill('#FFFFFF');

  // タイトル
  const titleBox = slide.insertTextBox('アクティビティサマリー', 50, 30, 600, 50);
  const titleText = titleBox.getText();
  titleText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(32)
    .setBold(true)
    .setForegroundColor('#CF2030');
  titleText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);

  // バッジを横1列に配置
  const badges = [
    { icon: '📝', number: stats.total_thanks_slips, label: 'サンクスリップ' },
    { icon: '🤝', number: stats.total_one_to_one, label: '121実施数' },
    { icon: '✓', number: stats.total_attendance, label: '出席者数' },
    { icon: '👥', number: data.length, label: '回答者数' }
  ];

  const badgeWidth = 140;
  const badgeHeight = 80;
  const gap = 20;
  const startX = 50;
  const startY = 150;

  badges.forEach((badge, index) => {
    const x = startX + (badgeWidth + gap) * index;

    // バッジ背景（角丸四角形）
    const shape = slide.insertShape(SlidesApp.ShapeType.ROUND_RECTANGLE, x, startY, badgeWidth, badgeHeight);
    shape.getFill().setSolidFill('#F8F9FA');
    shape.getBorder().setTransparent();

    // アイコン
    const iconBox = slide.insertTextBox(badge.icon, x + 10, startY + 10, 30, 30);
    iconBox.getText().getTextStyle().setFontSize(24);

    // 数値
    const numberBox = slide.insertTextBox(String(badge.number), x + 10, startY + 35, badgeWidth - 20, 25);
    const numberText = numberBox.getText();
    numberText.getTextStyle()
      .setFontFamily('Inter')
      .setFontSize(20)
      .setBold(true)
      .setForegroundColor('#CF2030');

    // ラベル
    const labelBox = slide.insertTextBox(badge.label, x + 10, startY + 55, badgeWidth - 20, 20);
    const labelText = labelBox.getText();
    labelText.getTextStyle()
      .setFontFamily('Noto Sans JP')
      .setFontSize(10)
      .setForegroundColor('#666666');
  });
}

/**
 * Slide 8: ありがとうございました
 */
function createThankYouSlide(presentation) {
  const slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
  const background = slide.getBackground();
  background.setSolidFill('#FFFFFF');

  // タイトル
  const titleBox = slide.insertTextBox('ありがとうございました', 50, 150, 600, 80);
  const titleText = titleBox.getText();
  titleText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(48)
    .setBold(true)
    .setForegroundColor('#CF2030');
  titleText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);

  // サブタイトル
  const subtitleBox = slide.insertTextBox('来週もよろしくお願いします', 50, 250, 600, 50);
  const subtitleText = subtitleBox.getText();
  subtitleText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(24)
    .setForegroundColor('#666666');
  subtitleText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);

  // ブランディング
  const brandBox = slide.insertTextBox('Givers Gain®', 50, 400, 600, 40);
  const brandText = brandBox.getText();
  brandText.getTextStyle()
    .setFontFamily('Noto Sans JP')
    .setFontSize(14)
    .setForegroundColor('#999999');
  brandText.getParagraphStyle().setParagraphAlignment(SlidesApp.ParagraphAlignment.CENTER);
}

/**
 * サンプルデータを取得（実際はスプレッドシートから取得）
 */
function getSampleData() {
  return [
    {
      introducer_name: '山田太郎',
      visitor_name: '鈴木商事 鈴木様',
      visitor_industry: '製造業',
      introduction_date: '2024-12-02',
      project_name: '新工場設備導入案件',
      referral_amount: 5000000,
      category: '成約',
      referral_provider: '佐藤花子',
      thanks_slips: 3,
      one_to_one: 2,
      attendance: '出席'
    },
    {
      introducer_name: '佐藤花子',
      visitor_name: '田中建設 田中様',
      visitor_industry: '建設業',
      introduction_date: '2024-12-03',
      project_name: 'オフィス改装工事',
      referral_amount: 3500000,
      category: '成約',
      referral_provider: '山田太郎',
      thanks_slips: 2,
      one_to_one: 1,
      attendance: '出席'
    },
    {
      introducer_name: '鈴木一郎',
      visitor_name: '高橋ITシステムズ 高橋様',
      visitor_industry: 'IT・通信',
      introduction_date: '2024-12-02',
      project_name: '基幹システム刷新',
      referral_amount: 8000000,
      category: '商談中',
      referral_provider: '田中美咲',
      thanks_slips: 4,
      one_to_one: 3,
      attendance: '出席'
    },
    {
      introducer_name: '田中美咲',
      visitor_name: '伊藤物流 伊藤様',
      visitor_industry: '運輸・物流',
      introduction_date: '2024-12-04',
      project_name: '倉庫管理システム導入',
      referral_amount: 2500000,
      category: '成約',
      referral_provider: '鈴木一郎',
      thanks_slips: 2,
      one_to_one: 2,
      attendance: '出席'
    },
    {
      introducer_name: '高橋健太',
      visitor_name: '渡辺デザイン 渡辺様',
      visitor_industry: 'デザイン・広告',
      introduction_date: '2024-12-01',
      project_name: 'ブランディング支援',
      referral_amount: 1200000,
      category: '商談中',
      referral_provider: '山田太郎',
      thanks_slips: 1,
      one_to_one: 1,
      attendance: '出席'
    },
    {
      introducer_name: '山田太郎',
      visitor_name: '小林飲食 小林様',
      visitor_industry: '飲食業',
      introduction_date: '2024-12-03',
      project_name: '新店舗内装デザイン',
      referral_amount: 800000,
      category: '見込み',
      referral_provider: '',
      thanks_slips: 3,
      one_to_one: 1,
      attendance: '出席'
    },
    {
      introducer_name: '佐藤花子',
      visitor_name: '加藤不動産 加藤様',
      visitor_industry: '不動産',
      introduction_date: '2024-12-02',
      project_name: 'オフィス移転コンサル',
      referral_amount: 1500000,
      category: '成約',
      referral_provider: '高橋健太',
      thanks_slips: 2,
      one_to_one: 2,
      attendance: '出席'
    },
    {
      introducer_name: '鈴木一郎',
      visitor_name: '',
      visitor_industry: '',
      introduction_date: '',
      project_name: 'Web広告運用支援',
      referral_amount: 600000,
      category: '成約',
      referral_provider: '佐藤花子',
      thanks_slips: 1,
      one_to_one: 1,
      attendance: '出席'
    }
  ];
}

/**
 * 統計を計算
 */
function calculateStats(data) {
  const stats = {
    total_referral_amount: 0,
    total_visitors: 0,
    total_attendance: 0,
    total_thanks_slips: 0,
    total_one_to_one: 0,
    categories: {},
    members: {}
  };

  data.forEach(row => {
    // Total referral amount
    stats.total_referral_amount += row.referral_amount;

    // Total visitors
    if (row.visitor_name) {
      stats.total_visitors++;
    }

    // Attendance
    if (row.attendance === '出席') {
      stats.total_attendance++;
    }

    // Thanks slips
    stats.total_thanks_slips += row.thanks_slips;

    // One-to-one
    stats.total_one_to_one += row.one_to_one;

    // Categories
    if (row.category) {
      if (!stats.categories[row.category]) {
        stats.categories[row.category] = 0;
      }
      stats.categories[row.category] += row.referral_amount;
    }

    // Members
    if (row.introducer_name) {
      if (!stats.members[row.introducer_name]) {
        stats.members[row.introducer_name] = {
          visitors: 0,
          referral_amount: 0
        };
      }
      stats.members[row.introducer_name].visitors++;
      stats.members[row.introducer_name].referral_amount += row.referral_amount;
    }
  });

  return stats;
}

/**
 * 数値をカンマ区切りにフォーマット
 */
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
