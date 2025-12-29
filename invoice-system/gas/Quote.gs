/**
 * 見積書処理
 */

/**
 * 見積書作成ダイアログを表示
 */
function showCreateQuoteDialog() {
  const html = HtmlService.createHtmlOutputFromFile('CreateQuoteDialog')
    .setWidth(600)
    .setHeight(500);
  SpreadsheetApp.getUi().showModalDialog(html, '見積書作成');
}

/**
 * 見積書を作成
 */
function createQuote(formData) {
  try {
    const sheet = getSheet(SHEET_NAMES.QUOTES);
    const docNumber = generateDocumentNumber('QUOTE');
    const customer = getCustomerById(formData.customerId);

    if (!customer) {
      throw new Error('取引先が見つかりません');
    }

    // 明細を解析
    const lineItems = formData.lineItems;
    const { subtotal, tax, total } = calculateAmounts(lineItems);

    // 新しい行を追加
    const newRow = [
      docNumber,                          // A: 書類番号
      '作成中',                           // B: ステータス
      customer.id,                        // C: 取引先ID
      customer.name,                      // D: 取引先名
      formatDate(formData.issueDate),     // E: 発行日
      '',                                 // F: 支払期限（見積書では使用しない）
      formData.subject,                   // G: 件名
      stringifyLineItems(lineItems),      // H: 明細JSON
      subtotal,                           // I: 小計
      tax,                                // J: 消費税
      total,                              // K: 合計金額
      formData.notes || '',               // L: 備考
      formData.internalMemo || '',        // M: 社内メモ
      '',                                 // N: 変換元
      '',                                 // O: PDF URL
      now(),                              // P: 作成日
      now()                               // Q: 更新日
    ];

    sheet.appendRow(newRow);

    // PDF生成（非同期）
    // generatePDF(docNumber, 'QUOTE');

    return {
      success: true,
      message: `見積書 ${docNumber} を作成しました`,
      docNumber: docNumber
    };
  } catch (error) {
    return {
      success: false,
      message: `エラー: ${error.message}`
    };
  }
}

/**
 * 見積書一覧を取得
 */
function getQuoteList(customerId = null) {
  const sheet = getSheet(SHEET_NAMES.QUOTES);
  const lastRow = sheet.getLastRow();

  if (lastRow < 2) {
    return [];
  }

  const data = sheet.getRange(2, 1, lastRow - 1, 17).getValues();
  let quotes = data.map(row => ({
    docNumber: row[0],
    status: row[1],
    customerId: row[2],
    customerName: row[3],
    issueDate: formatDate(row[4]),
    subject: row[6],
    total: row[10]
  })).filter(q => q.docNumber);

  // 取引先IDでフィルタ
  if (customerId) {
    quotes = quotes.filter(q => q.customerId === customerId);
  }

  return quotes;
}

/**
 * 見積書データを取得
 */
function getQuoteData(docNumber) {
  const sheet = getSheet(SHEET_NAMES.QUOTES);
  const lastRow = sheet.getLastRow();

  if (lastRow < 2) {
    return null;
  }

  const data = sheet.getRange(2, 1, lastRow - 1, 17).getValues();
  const row = data.find(r => r[0] === docNumber);

  if (!row) {
    return null;
  }

  return {
    docNumber: row[0],
    status: row[1],
    customerId: row[2],
    customerName: row[3],
    issueDate: row[4],
    subject: row[6],
    lineItems: parseLineItems(row[7]),
    subtotal: row[8],
    tax: row[9],
    total: row[10],
    notes: row[11],
    internalMemo: row[12]
  };
}
