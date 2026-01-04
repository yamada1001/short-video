/**
 * ファイナンスブレーン 見積書管理 GAS
 *
 * Googleスプレッドシートで見積書データを管理します。
 * ページ数が減る可能性を考慮し、単価を高めに設定しています。
 *
 * スプレッドシート構成:
 * - シート「基本情報」: 顧客名、作成日、有効期限など
 * - シート「基本設計・企画」: 基本設計・企画費用の項目
 * - シート「デザイン制作」: デザイン制作費用の項目
 * - シート「コーディング・実装」: コーディング・実装費用の項目
 * - シート「その他費用」: その他の費用
 * - シート「集計」: 自動集計シート
 */

/**
 * メニューを追加
 */
function onOpen() {
  const ui = SpreadsheetApp.getUi();
  ui.createMenu('見積書管理')
    .addItem('テンプレート作成', 'createTemplate')
    .addItem('合計金額を再計算', 'recalculateTotal')
    .addToUi();
}

/**
 * 合計金額を再計算
 */
function recalculateTotal() {
  const ss = SpreadsheetApp.getActiveSpreadsheet();

  // 各セクションの合計を計算
  const sections = ['基本設計・企画', 'デザイン制作', 'コーディング・実装', 'その他費用'];
  let grandTotal = 0;
  const sectionTotals = {};

  sections.forEach(sectionName => {
    const sheet = ss.getSheetByName(sectionName);
    if (!sheet) return;

    const data = sheet.getDataRange().getValues();
    let sectionTotal = 0;

    // 3行目から（2行目はヘッダー）
    for (let i = 2; i < data.length; i++) {
      const amount = data[i][4]; // E列（金額）
      if (typeof amount === 'number' && amount > 0) {
        sectionTotal += amount;
      } else if (!amount) {
        break; // 空行で終了
      }
    }

    sectionTotals[sectionName] = sectionTotal;
    grandTotal += sectionTotal;
  });

  // 集計シートを更新
  let summarySheet = ss.getSheetByName('集計');
  if (!summarySheet) {
    summarySheet = ss.insertSheet('集計');
  }

  summarySheet.clear();
  summarySheet.getRange('A1:C1').setValues([
    ['セクション', '小計（税抜）', '備考']
  ]);
  summarySheet.getRange('A1:C1').setFontWeight('bold').setBackground('#5767bf').setFontColor('#ffffff');

  let row = 2;
  sections.forEach(sectionName => {
    summarySheet.getRange(`A${row}:C${row}`).setValues([
      [sectionName, sectionTotals[sectionName], '']
    ]);
    row++;
  });

  // 合計行
  summarySheet.getRange(`A${row}:C${row}`).setValues([
    ['合計（税抜）', grandTotal, '']
  ]);
  summarySheet.getRange(`A${row}:C${row}`).setFontWeight('bold').setBackground('#f5f7fa');
  row++;

  // 消費税
  const tax = Math.floor(grandTotal * 0.1);
  summarySheet.getRange(`A${row}:C${row}`).setValues([
    ['消費税（10%）', tax, '']
  ]);
  row++;

  // 税込合計
  const totalWithTax = grandTotal + tax;
  summarySheet.getRange(`A${row}:C${row}`).setValues([
    ['合計（税込）', totalWithTax, '']
  ]);
  summarySheet.getRange(`A${row}:C${row}`).setFontWeight('bold').setBackground('#5767bf').setFontColor('#ffffff');

  // 列幅調整
  summarySheet.setColumnWidth(1, 200);
  summarySheet.setColumnWidth(2, 150);
  summarySheet.setColumnWidth(3, 300);

  // 数値フォーマット
  summarySheet.getRange(2, 2, row - 1, 1).setNumberFormat('¥#,##0');

  SpreadsheetApp.getUi().alert(
    '再計算完了',
    `合計金額を再計算しました。\n\n合計（税抜）: ¥${grandTotal.toLocaleString()}\n消費税: ¥${tax.toLocaleString()}\n合計（税込）: ¥${totalWithTax.toLocaleString()}\n\n「集計」シートで詳細を確認できます。`,
    SpreadsheetApp.getUi().ButtonSet.OK
  );
}

/**
 * スプレッドシートのテンプレートを作成
 */
function createTemplate() {
  const ss = SpreadsheetApp.getActiveSpreadsheet();

  // 1. 基本情報シート
  let sheet = ss.getSheetByName('基本情報');
  if (!sheet) {
    sheet = ss.insertSheet('基本情報');
  }
  sheet.clear();
  sheet.getRange('A1:B6').setValues([
    ['項目', '内容'],
    ['顧客名', '株式会社ファイナンスブレーン 様'],
    ['作成日', new Date()],
    ['有効期限', new Date(Date.now() + 90 * 24 * 60 * 60 * 1000)],
    ['', ''],
    ['注意', '合計金額は「見積書管理 > 合計金額を再計算」で更新されます'],
  ]);
  sheet.getRange('A1:B1').setFontWeight('bold').setBackground('#5767bf').setFontColor('#ffffff');
  sheet.setColumnWidth(1, 150);
  sheet.setColumnWidth(2, 400);

  // 2. 基本設計・企画シート
  sheet = ss.getSheetByName('基本設計・企画');
  if (!sheet) {
    sheet = ss.insertSheet('基本設計・企画');
  }
  sheet.clear();
  sheet.getRange('A1:E1').setValues([
    ['1. 基本設計・企画費用', 'サイト全体の設計図を作ります。ページ数に関わらず、しっかりとした設計が必要です。', '', '', ''],
  ]);
  sheet.getRange('A2:E2').setValues([
    ['項目名', '説明', '数量', '単価', '金額'],
  ]);
  sheet.getRange('A3:E3').setValues([
    ['要件定義・サイト設計', 'お客様のご要望をまとめ、サイト全体の構成を決定します', 1, 150000, 150000],
  ]);
  sheet.getRange('A2:E2').setFontWeight('bold').setBackground('#f5f7fa');
  sheet.setColumnWidth(1, 250);
  sheet.setColumnWidth(2, 350);
  sheet.getRange('C3:E100').setNumberFormat('#,##0');

  // 3. デザイン制作シート
  sheet = ss.getSheetByName('デザイン制作');
  if (!sheet) {
    sheet = ss.insertSheet('デザイン制作');
  }
  sheet.clear();
  sheet.getRange('A1:E1').setValues([
    ['2. デザイン制作費用', 'ページ数が減る可能性を考慮し、単価を高めに設定しています。質の高いデザインを提供します。', '', '', ''],
  ]);
  sheet.getRange('A2:E2').setValues([
    ['項目名', '説明', '数量', '単価', '金額'],
  ]);
  sheet.getRange('A3:E6').setValues([
    ['トップページデザイン', '1ページ', 1, 150000, 150000],
    ['サービスページデザイン', '個人向け5種類 + 法人向け1種類', 6, 60000, 360000],
    ['サービス詳細ページデザイン', 'ライフプランニング3種類 + 保険2種類', 5, 35000, 175000],
    ['その他ページデザイン', '会社概要、FAQ、お問い合わせ、スタッフ紹介等', 15, 25000, 375000],
  ]);
  sheet.getRange('A2:E2').setFontWeight('bold').setBackground('#f5f7fa');
  sheet.setColumnWidth(1, 250);
  sheet.setColumnWidth(2, 350);
  sheet.getRange('C3:E100').setNumberFormat('#,##0');

  // 4. コーディング・実装シート
  sheet = ss.getSheetByName('コーディング・実装');
  if (!sheet) {
    sheet = ss.insertSheet('コーディング・実装');
  }
  sheet.clear();
  sheet.getRange('A1:E1').setValues([
    ['3. コーディング・実装費用', 'デザインを実際に動くWebサイトにします。ページ単価を上げて品質を確保します。', '', '', ''],
  ]);
  sheet.getRange('A2:E2').setValues([
    ['項目名', '説明', '数量', '単価', '金額'],
  ]);
  sheet.getRange('A3:E5').setValues([
    ['HTML/CSS/JavaScriptコーディング', '27ページ分のコーディング（レスポンシブ対応込み）', 27, 25000, 675000],
    ['お問い合わせフォーム実装', '入力チェック機能、自動返信メール設定', 1, 70000, 70000],
    ['SEO基本設定', 'タイトルタグ、メタディスクリプション、構造化データ設定', 1, 50000, 50000],
  ]);
  sheet.getRange('A2:E2').setFontWeight('bold').setBackground('#f5f7fa');
  sheet.setColumnWidth(1, 250);
  sheet.setColumnWidth(2, 350);
  sheet.getRange('C3:E100').setNumberFormat('#,##0');

  // 5. その他費用シート
  sheet = ss.getSheetByName('その他費用');
  if (!sheet) {
    sheet = ss.insertSheet('その他費用');
  }
  sheet.clear();
  sheet.getRange('A1:E1').setValues([
    ['4. その他費用', 'テスト、公開準備、サポートなどの費用', '', '', ''],
  ]);
  sheet.getRange('A2:E2').setValues([
    ['項目名', '説明', '数量', '単価', '金額'],
  ]);
  sheet.getRange('A3:E5').setValues([
    ['動作テスト・品質確認', '各種ブラウザ・デバイスでの動作確認', 1, 80000, 80000],
    ['公開作業・サーバー設定', 'サーバーへのアップロード、DNS設定、SSL証明書設定', 1, 50000, 50000],
    ['運用サポート（3ヶ月）', '公開後3ヶ月間の軽微な修正対応', 1, 100000, 100000],
  ]);
  sheet.getRange('A2:E2').setFontWeight('bold').setBackground('#f5f7fa');
  sheet.setColumnWidth(1, 250);
  sheet.setColumnWidth(2, 350);
  sheet.getRange('C3:E100').setNumberFormat('#,##0');

  // 集計シートを作成
  recalculateTotal();

  SpreadsheetApp.getUi().alert(
    'テンプレート作成完了',
    'スプレッドシートのテンプレートを作成しました！\n\n各シートを編集したら「見積書管理 > 合計金額を再計算」を実行してください。\n\n※ページ数が減る可能性を考慮し、単価を高めに設定しています。',
    SpreadsheetApp.getUi().ButtonSet.OK
  );
}

/**
 * E列（金額）の自動計算式を設定
 */
function setFormulas() {
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  const sections = ['基本設計・企画', 'デザイン制作', 'コーディング・実装', 'その他費用'];

  sections.forEach(sectionName => {
    const sheet = ss.getSheetByName(sectionName);
    if (!sheet) return;

    // 3行目から20行目までE列に数式を設定（C列×D列）
    for (let i = 3; i <= 20; i++) {
      sheet.getRange(`E${i}`).setFormula(`=IF(AND(C${i}<>"", D${i}<>""), C${i}*D${i}, "")`);
    }
  });

  SpreadsheetApp.getUi().alert('数式設定完了', 'E列（金額）に自動計算式を設定しました。\n数量または単価を変更すると、金額が自動的に計算されます。', SpreadsheetApp.getUi().ButtonSet.OK);
}
