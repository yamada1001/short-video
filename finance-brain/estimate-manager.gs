/**
 * ファイナンスブレーン 見積書管理 GAS
 *
 * Googleスプレッドシート1枚で見積書データを管理します。
 * ページ数が減る可能性を考慮し、単価を高めに設定しています。
 *
 * シート構成:
 * - 上部: 基本情報（顧客名、作成日、有効期限）
 * - 中部: 見積もり項目一覧
 * - 下部: 合計金額
 */

/**
 * メニューを追加
 */
function onOpen() {
  const ui = SpreadsheetApp.getUi();
  ui.createMenu('見積書管理')
    .addItem('見積書テンプレート作成', 'createTemplate')
    .addItem('合計金額を再計算', 'recalculateTotal')
    .addToUi();
}

/**
 * 合計金額を再計算
 */
function recalculateTotal() {
  const sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();

  // 見積もり項目の開始行（基本情報の後）
  const itemStartRow = 10;
  const data = sheet.getDataRange().getValues();

  let grandTotal = 0;

  // 10行目から最後まで金額（F列）を集計
  for (let i = itemStartRow - 1; i < data.length; i++) {
    const amount = data[i][5]; // F列（金額）
    if (typeof amount === 'number' && amount > 0) {
      grandTotal += amount;
    }

    // 「合計（税抜）」行に到達したら終了
    if (data[i][0] === '合計（税抜）') {
      break;
    }
  }

  // 合計金額を探して更新
  for (let i = 0; i < data.length; i++) {
    if (data[i][0] === '合計（税抜）') {
      sheet.getRange(i + 1, 6).setValue(grandTotal);

      // 消費税計算
      const tax = Math.floor(grandTotal * 0.1);
      sheet.getRange(i + 2, 6).setValue(tax);

      // 税込合計
      const totalWithTax = grandTotal + tax;
      sheet.getRange(i + 3, 6).setValue(totalWithTax);

      break;
    }
  }

  SpreadsheetApp.getUi().alert(
    '再計算完了',
    `合計金額を再計算しました。\n\n合計（税抜）: ¥${grandTotal.toLocaleString()}\n消費税: ¥${Math.floor(grandTotal * 0.1).toLocaleString()}\n合計（税込）: ¥${(grandTotal + Math.floor(grandTotal * 0.1)).toLocaleString()}`,
    SpreadsheetApp.getUi().ButtonSet.OK
  );
}

/**
 * 見積書テンプレートを作成（1枚のシートに全て）
 */
function createTemplate() {
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  let sheet = ss.getActiveSheet();

  // シートをクリア
  sheet.clear();

  // === 基本情報 ===
  sheet.getRange('A1').setValue('ファイナンスブレーン様 Webサイトリニューアル お見積書');
  sheet.getRange('A1').setFontSize(16).setFontWeight('bold');

  sheet.getRange('A3:B6').setValues([
    ['顧客名', '株式会社ファイナンスブレーン 様'],
    ['作成日', new Date()],
    ['有効期限', new Date(Date.now() + 90 * 24 * 60 * 60 * 1000)],
    ['', '']
  ]);
  sheet.getRange('A3:A6').setFontWeight('bold');

  // === 見積もり項目ヘッダー ===
  sheet.getRange('A9:F9').setValues([
    ['セクション', '項目名', '説明', '数量', '単価', '金額']
  ]);
  sheet.getRange('A9:F9').setFontWeight('bold').setBackground('#5767bf').setFontColor('#ffffff');

  // === 見積もり項目 ===
  const items = [
    // 1. 基本設計・企画
    ['1. 基本設計・企画', '要件定義・サイト設計', 'お客様のご要望をまとめ、サイト全体の構成を決定します', 1, 150000, 150000],

    // 2. デザイン制作
    ['2. デザイン制作', 'トップページデザイン', '1ページ', 1, 150000, 150000],
    ['', 'サービスページデザイン', '個人向け5種類 + 法人向け1種類', 6, 60000, 360000],
    ['', 'サービス詳細ページデザイン', 'ライフプランニング3種類 + 保険2種類', 5, 35000, 175000],
    ['', 'その他ページデザイン', '会社概要、FAQ、お問い合わせ、スタッフ紹介等', 15, 25000, 375000],

    // 3. コーディング・実装
    ['3. コーディング・実装', 'HTML/CSS/JavaScriptコーディング', '27ページ分のコーディング（レスポンシブ対応込み）', 27, 25000, 675000],
    ['', 'お問い合わせフォーム実装', '入力チェック機能、自動返信メール設定', 1, 70000, 70000],
    ['', 'SEO基本設定', 'タイトルタグ、メタディスクリプション、構造化データ設定', 1, 50000, 50000],

    // 4. その他費用
    ['4. その他費用', '動作テスト・品質確認', '各種ブラウザ・デバイスでの動作確認', 1, 80000, 80000],
    ['', '公開作業・サーバー設定', 'サーバーへのアップロード、DNS設定、SSL証明書設定', 1, 50000, 50000],
    ['', '運用サポート（3ヶ月）', '公開後3ヶ月間の軽微な修正対応', 1, 100000, 100000],
  ];

  sheet.getRange(10, 1, items.length, 6).setValues(items);

  // === 合計行 ===
  const totalRow = 10 + items.length + 1;
  sheet.getRange(`A${totalRow}:F${totalRow + 2}`).setValues([
    ['合計（税抜）', '', '', '', '', 2235000],
    ['消費税（10%）', '', '', '', '', 223500],
    ['合計（税込）', '', '', '', '', 2458500]
  ]);

  sheet.getRange(`A${totalRow}:A${totalRow + 2}`).setFontWeight('bold');
  sheet.getRange(`A${totalRow}:F${totalRow}`).setBackground('#f5f7fa');
  sheet.getRange(`A${totalRow + 2}:F${totalRow + 2}`).setBackground('#5767bf').setFontColor('#ffffff').setFontWeight('bold');

  // === 列幅調整 ===
  sheet.setColumnWidth(1, 180); // セクション
  sheet.setColumnWidth(2, 250); // 項目名
  sheet.setColumnWidth(3, 350); // 説明
  sheet.setColumnWidth(4, 80);  // 数量
  sheet.setColumnWidth(5, 100); // 単価
  sheet.setColumnWidth(6, 120); // 金額

  // === 数値フォーマット ===
  sheet.getRange(10, 4, items.length + 3, 1).setNumberFormat('#,##0'); // 数量
  sheet.getRange(10, 5, items.length + 3, 1).setNumberFormat('¥#,##0'); // 単価
  sheet.getRange(10, 6, items.length + 3, 1).setNumberFormat('¥#,##0'); // 金額

  // === 罫線 ===
  const dataRange = sheet.getRange(9, 1, items.length + 4, 6);
  dataRange.setBorder(true, true, true, true, true, true);

  // === 日付フォーマット ===
  sheet.getRange('B4').setNumberFormat('yyyy"年"m"月"d"日"');
  sheet.getRange('B5').setNumberFormat('yyyy"年"m"月"d"日"');

  // === 注意事項 ===
  const noteRow = totalRow + 4;
  sheet.getRange(`A${noteRow}`).setValue('注意事項');
  sheet.getRange(`A${noteRow}`).setFontWeight('bold').setFontSize(12);

  sheet.getRange(`A${noteRow + 1}:F${noteRow + 3}`).setValues([
    ['・上記金額は税抜価格です。消費税（10%）を加算した金額が税込価格となります。', '', '', '', '', ''],
    ['・ページ数が減る可能性を考慮し、単価を高めに設定しています。', '', '', '', '', ''],
    ['・金額や項目を変更した場合は「見積書管理 > 合計金額を再計算」を実行してください。', '', '', '', '', '']
  ]);
  sheet.getRange(`A${noteRow + 1}:A${noteRow + 3}`).setFontSize(10);

  // 自動計算式を設定
  setFormulas();

  SpreadsheetApp.getUi().alert(
    'テンプレート作成完了',
    '見積書テンプレートを作成しました！\n\n各項目を編集したら「見積書管理 > 合計金額を再計算」を実行してください。\n\n※ページ数が減る可能性を考慮し、単価を高めに設定しています。',
    SpreadsheetApp.getUi().ButtonSet.OK
  );
}

/**
 * F列（金額）の自動計算式を設定
 */
function setFormulas() {
  const sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();

  // 10行目から30行目までF列に数式を設定（D列×E列）
  for (let i = 10; i <= 30; i++) {
    const cellValue = sheet.getRange(`A${i}`).getValue();

    // 合計行に到達したら終了
    if (cellValue === '合計（税抜）') {
      break;
    }

    sheet.getRange(`F${i}`).setFormula(`=IF(AND(D${i}<>"", E${i}<>""), D${i}*E${i}, "")`);
  }

  SpreadsheetApp.getUi().alert(
    '数式設定完了',
    'F列（金額）に自動計算式を設定しました。\n数量または単価を変更すると、金額が自動的に計算されます。',
    SpreadsheetApp.getUi().ButtonSet.OK
  );
}
