/**
 * 合算請求書作成（freee準拠）
 */

/**
 * 合算請求書作成ダイアログを表示
 */
function showCombineInvoiceDialog() {
  const html = HtmlService.createHtmlOutputFromFile('CombineInvoiceDialog')
    .setWidth(600)
    .setHeight(550);
  SpreadsheetApp.getUi().showModalDialog(html, '合算請求書を作成');
}

/**
 * 指定取引先の見積書・納品書一覧を取得
 */
function getDocumentsForCombine(customerId) {
  const quotes = getQuoteList(customerId);
  const deliveries = getDeliveryList(customerId);

  const documents = [
    ...quotes.map(q => ({
      ...q,
      docType: '見積書',
      docTypeCode: 'QUOTE'
    })),
    ...deliveries.map(d => ({
      ...d,
      docType: '納品書',
      docTypeCode: 'DELIVERY'
    }))
  ];

  // 発行日でソート
  documents.sort((a, b) => new Date(a.issueDate) - new Date(b.issueDate));

  return documents;
}

/**
 * 複数の書類から合算請求書を作成
 */
function createCombinedInvoice(formData) {
  try {
    const customerId = formData.customerId;
    const selectedDocNumbers = formData.selectedDocNumbers; // ['Q-...', 'D-...', ...]
    const customer = getCustomerById(customerId);

    if (!customer) {
      throw new Error('取引先が見つかりません');
    }

    if (!selectedDocNumbers || selectedDocNumbers.length === 0) {
      throw new Error('合算する書類を選択してください');
    }

    // 選択された書類のデータを取得
    const combinedLineItems = [];
    const sourceDocNumbers = [];

    selectedDocNumbers.forEach(docNumber => {
      let docData = null;

      if (docNumber.startsWith('Q-')) {
        docData = getQuoteData(docNumber);
      } else if (docNumber.startsWith('D-')) {
        docData = getDeliveryData(docNumber);
      }

      if (docData) {
        combinedLineItems.push(...docData.lineItems);
        sourceDocNumbers.push(docNumber);
      }
    });

    if (combinedLineItems.length === 0) {
      throw new Error('明細が取得できませんでした');
    }

    // 金額を再計算
    const { subtotal, tax, total } = calculateAmounts(combinedLineItems);

    // 合算請求書を作成
    const sheet = getSheet(SHEET_NAMES.INVOICES);
    const invoiceDocNumber = generateDocumentNumber('INVOICE');

    const newRow = [
      invoiceDocNumber,                       // A: 書類番号
      '作成中',                               // B: ステータス
      customerId,                             // C: 取引先ID
      customer.name,                          // D: 取引先名
      formatDate(formData.issueDate || now()), // E: 発行日
      formatDate(formData.dueDate),           // F: 支払期限
      formData.subject || '合算請求書',       // G: 件名
      stringifyLineItems(combinedLineItems),  // H: 明細JSON
      subtotal,                               // I: 小計
      tax,                                    // J: 消費税
      total,                                  // K: 合計金額
      formData.notes || `合算元: ${sourceDocNumbers.join(', ')}`, // L: 備考
      formData.internalMemo || '',            // M: 社内メモ
      sourceDocNumbers.join(', '),            // N: 変換元
      '',                                     // O: PDF URL
      now(),                                  // P: 作成日
      now()                                   // Q: 更新日
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `${sourceDocNumbers.length}件の書類から合算請求書 ${invoiceDocNumber} を作成しました`,
      docNumber: invoiceDocNumber,
      sourceCount: sourceDocNumbers.length
    };
  } catch (error) {
    return {
      success: false,
      message: `エラー: ${error.message}`
    };
  }
}
