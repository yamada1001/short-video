/**
 * 請求書処理
 */

/**
 * 請求書作成ダイアログを表示
 */
function showCreateInvoiceDialog() {
  const html = HtmlService.createHtmlOutputFromFile('CreateInvoiceDialog')
    .setWidth(600)
    .setHeight(550);
  SpreadsheetApp.getUi().showModalDialog(html, '請求書作成');
}

/**
 * 請求書を作成
 */
function createInvoice(formData) {
  try {
    const sheet = getSheet(SHEET_NAMES.INVOICES);
    const docNumber = generateDocumentNumber('INVOICE');
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
      formatDate(formData.dueDate),       // F: 支払期限
      formData.subject,                   // G: 件名
      stringifyLineItems(lineItems),      // H: 明細JSON
      subtotal,                           // I: 小計
      tax,                                // J: 消費税
      total,                              // K: 合計金額
      formData.notes || '',               // L: 備考
      formData.internalMemo || '',        // M: 社内メモ
      formData.sourceDocNumber || '',     // N: 変換元
      '',                                 // O: PDF URL
      now(),                              // P: 作成日
      now()                               // Q: 更新日
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `請求書 ${docNumber} を作成しました`,
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
 * 請求書一覧を取得
 */
function getInvoiceList(customerId = null) {
  const sheet = getSheet(SHEET_NAMES.INVOICES);
  const lastRow = sheet.getLastRow();

  if (lastRow < 2) {
    return [];
  }

  const data = sheet.getRange(2, 1, lastRow - 1, 17).getValues();
  let invoices = data.map(row => ({
    docNumber: row[0],
    status: row[1],
    customerId: row[2],
    customerName: row[3],
    issueDate: formatDate(row[4]),
    dueDate: formatDate(row[5]),
    subject: row[6],
    total: row[10]
  })).filter(inv => inv.docNumber);

  // 取引先IDでフィルタ
  if (customerId) {
    invoices = invoices.filter(inv => inv.customerId === customerId);
  }

  return invoices;
}
