/**
 * 書類変換処理（freee準拠）
 */

/**
 * 見積書 → 納品書に変換ダイアログを表示
 */
function showConvertQuoteToDeliveryDialog() {
  const html = HtmlService.createHtmlOutputFromFile('ConvertQuoteToDeliveryDialog')
    .setWidth(500)
    .setHeight(400);
  SpreadsheetApp.getUi().showModalDialog(html, '見積書 → 納品書に変換');
}

/**
 * 見積書 → 請求書に変換ダイアログを表示
 */
function showConvertQuoteToInvoiceDialog() {
  const html = HtmlService.createHtmlOutputFromFile('ConvertQuoteToInvoiceDialog')
    .setWidth(500)
    .setHeight(450);
  SpreadsheetApp.getUi().showModalDialog(html, '見積書 → 請求書に変換');
}

/**
 * 納品書 → 請求書に変換ダイアログを表示
 */
function showConvertDeliveryToInvoiceDialog() {
  const html = HtmlService.createHtmlOutputFromFile('ConvertDeliveryToInvoiceDialog')
    .setWidth(500)
    .setHeight(450);
  SpreadsheetApp.getUi().showModalDialog(html, '納品書 → 請求書に変換');
}

/**
 * 見積書 → 納品書に変換
 */
function convertQuoteToDelivery(quoteDocNumber, formData) {
  try {
    const quoteData = getQuoteData(quoteDocNumber);

    if (!quoteData) {
      throw new Error('見積書が見つかりません');
    }

    const sheet = getSheet(SHEET_NAMES.DELIVERIES);
    const deliveryDocNumber = generateDocumentNumber('DELIVERY');

    // 納品書を作成（見積書のデータをコピー）
    const newRow = [
      deliveryDocNumber,                      // A: 書類番号
      '作成中',                               // B: ステータス
      quoteData.customerId,                   // C: 取引先ID
      quoteData.customerName,                 // D: 取引先名
      formatDate(formData.issueDate || now()), // E: 発行日
      '',                                     // F: 支払期限（納品書では使用しない）
      formData.subject || quoteData.subject,  // G: 件名
      stringifyLineItems(quoteData.lineItems), // H: 明細JSON
      quoteData.subtotal,                     // I: 小計
      quoteData.tax,                          // J: 消費税
      quoteData.total,                        // K: 合計金額
      formData.notes || quoteData.notes,      // L: 備考
      formData.internalMemo || '',            // M: 社内メモ
      quoteDocNumber,                         // N: 変換元
      '',                                     // O: PDF URL
      now(),                                  // P: 作成日
      now()                                   // Q: 更新日
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `見積書 ${quoteDocNumber} から納品書 ${deliveryDocNumber} を作成しました`,
      docNumber: deliveryDocNumber
    };
  } catch (error) {
    return {
      success: false,
      message: `エラー: ${error.message}`
    };
  }
}

/**
 * 見積書 → 請求書に変換
 */
function convertQuoteToInvoice(quoteDocNumber, formData) {
  try {
    const quoteData = getQuoteData(quoteDocNumber);

    if (!quoteData) {
      throw new Error('見積書が見つかりません');
    }

    const sheet = getSheet(SHEET_NAMES.INVOICES);
    const invoiceDocNumber = generateDocumentNumber('INVOICE');

    // 請求書を作成（見積書のデータをコピー）
    const newRow = [
      invoiceDocNumber,                       // A: 書類番号
      '作成中',                               // B: ステータス
      quoteData.customerId,                   // C: 取引先ID
      quoteData.customerName,                 // D: 取引先名
      formatDate(formData.issueDate || now()), // E: 発行日
      formatDate(formData.dueDate),           // F: 支払期限
      formData.subject || quoteData.subject,  // G: 件名
      stringifyLineItems(quoteData.lineItems), // H: 明細JSON
      quoteData.subtotal,                     // I: 小計
      quoteData.tax,                          // J: 消費税
      quoteData.total,                        // K: 合計金額
      formData.notes || quoteData.notes,      // L: 備考
      formData.internalMemo || '',            // M: 社内メモ
      quoteDocNumber,                         // N: 変換元
      '',                                     // O: PDF URL
      now(),                                  // P: 作成日
      now()                                   // Q: 更新日
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `見積書 ${quoteDocNumber} から請求書 ${invoiceDocNumber} を作成しました`,
      docNumber: invoiceDocNumber
    };
  } catch (error) {
    return {
      success: false,
      message: `エラー: ${error.message}`
    };
  }
}

/**
 * 納品書データを取得
 */
function getDeliveryData(docNumber) {
  const sheet = getSheet(SHEET_NAMES.DELIVERIES);
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

/**
 * 納品書 → 請求書に変換
 */
function convertDeliveryToInvoice(deliveryDocNumber, formData) {
  try {
    const deliveryData = getDeliveryData(deliveryDocNumber);

    if (!deliveryData) {
      throw new Error('納品書が見つかりません');
    }

    const sheet = getSheet(SHEET_NAMES.INVOICES);
    const invoiceDocNumber = generateDocumentNumber('INVOICE');

    // 請求書を作成（納品書のデータをコピー）
    const newRow = [
      invoiceDocNumber,                       // A: 書類番号
      '作成中',                               // B: ステータス
      deliveryData.customerId,                // C: 取引先ID
      deliveryData.customerName,              // D: 取引先名
      formatDate(formData.issueDate || now()), // E: 発行日
      formatDate(formData.dueDate),           // F: 支払期限
      formData.subject || deliveryData.subject, // G: 件名
      stringifyLineItems(deliveryData.lineItems), // H: 明細JSON
      deliveryData.subtotal,                  // I: 小計
      deliveryData.tax,                       // J: 消費税
      deliveryData.total,                     // K: 合計金額
      formData.notes || deliveryData.notes,   // L: 備考
      formData.internalMemo || '',            // M: 社内メモ
      deliveryDocNumber,                      // N: 変換元
      '',                                     // O: PDF URL
      now(),                                  // P: 作成日
      now()                                   // Q: 更新日
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `納品書 ${deliveryDocNumber} から請求書 ${invoiceDocNumber} を作成しました`,
      docNumber: invoiceDocNumber
    };
  } catch (error) {
    return {
      success: false,
      message: `エラー: ${error.message}`
    };
  }
}
