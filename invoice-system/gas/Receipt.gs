/**
 * 領収書処理
 */

/**
 * 領収書作成ダイアログを表示
 */
function showCreateReceiptDialog() {
  const html = HtmlService.createHtmlOutputFromFile('CreateReceiptDialog')
    .setWidth(600)
    .setHeight(500);
  SpreadsheetApp.getUi().showModalDialog(html, '領収書作成');
}

/**
 * 領収書を作成
 */
function createReceipt(formData) {
  try {
    const sheet = getSheet(SHEET_NAMES.RECEIPTS);
    const docNumber = generateDocumentNumber('RECEIPT');
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
      '発行済み',                         // B: ステータス
      customer.id,                        // C: 取引先ID
      customer.name,                      // D: 取引先名
      formatDate(formData.issueDate),     // E: 発行日
      '',                                 // F: 支払期限（領収書では使用しない）
      formData.subject,                   // G: 件名
      stringifyLineItems(lineItems),      // H: 明細JSON
      subtotal,                           // I: 小計
      tax,                                // J: 消費税
      total,                              // K: 合計金額
      formData.notes || '',               // L: 備考
      formData.internalMemo || '',        // M: 社内メモ
      formData.sourceDocNumber || '',     // N: 変換元（請求書番号など）
      '',                                 // O: PDF URL
      now(),                              // P: 作成日
      now()                               // Q: 更新日
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `領収書 ${docNumber} を作成しました`,
      docNumber: docNumber
    };
  } catch (error) {
    return {
      success: false,
      message: `エラー: ${error.message}`
    };
  }
}
