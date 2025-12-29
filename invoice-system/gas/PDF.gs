/**
 * PDF生成処理（Google Docs API）
 */

/**
 * Google DocsテンプレートからPDFを生成
 *
 * 【事前準備】
 * 1. Google Docsで請求書テンプレートを作成
 * 2. テンプレート内に{{変数}}でプレースホルダーを配置
 *    例: {{会社名}}, {{請求書番号}}, {{発行日}} など
 * 3. テンプレートIDを取得して、下記のTEMPLATE_IDを設定
 */

// Google DocsテンプレートID（各書類種別ごとに設定）
const TEMPLATE_IDS = {
  QUOTE: 'YOUR_QUOTE_TEMPLATE_ID',      // 見積書テンプレート
  DELIVERY: 'YOUR_DELIVERY_TEMPLATE_ID', // 納品書テンプレート
  INVOICE: 'YOUR_INVOICE_TEMPLATE_ID',   // 請求書テンプレート
  RECEIPT: 'YOUR_RECEIPT_TEMPLATE_ID'    // 領収書テンプレート
};

/**
 * PDFを生成
 */
function generatePDF(docNumber, docType) {
  try {
    // テンプレートIDを取得
    const templateId = TEMPLATE_IDS[docType];
    if (!templateId || templateId.startsWith('YOUR_')) {
      throw new Error('テンプレートIDが設定されていません');
    }

    // 書類データを取得
    let docData = null;
    switch(docType) {
      case 'QUOTE':
        docData = getQuoteData(docNumber);
        break;
      case 'DELIVERY':
        docData = getDeliveryData(docNumber);
        break;
      case 'INVOICE':
        docData = getInvoiceData(docNumber);
        break;
      case 'RECEIPT':
        docData = getReceiptData(docNumber);
        break;
    }

    if (!docData) {
      throw new Error('書類データが見つかりません');
    }

    // 自社情報を取得
    const companyInfo = getCompanyInfo();

    // 取引先情報を取得
    const customer = getCustomerById(docData.customerId);

    // テンプレートをコピー
    const templateDoc = DriveApp.getFileById(templateId);
    const copyDoc = templateDoc.makeCopy(`${docNumber}_temp`);
    const copyDocId = copyDoc.getId();

    // Google Docsを開いて変数を置換
    const doc = DocumentApp.openById(copyDocId);
    const body = doc.getBody();

    // 明細テーブルを作成
    const lineItemsTable = createLineItemsTable(docData.lineItems);

    // 置換マップ
    const replacements = {
      '{{書類種別}}': DOC_TYPES[docType].name,
      '{{書類番号}}': docNumber,
      '{{発行日}}': formatDate(docData.issueDate),
      '{{支払期限}}': docData.dueDate ? formatDate(docData.dueDate) : '',
      '{{取引先名}}': customer.name,
      '{{取引先郵便番号}}': customer.postalCode || '',
      '{{取引先住所}}': customer.address || '',
      '{{取引先担当者}}': customer.contactPerson || '',
      '{{件名}}': docData.subject,
      '{{明細}}': lineItemsTable,
      '{{小計}}': formatCurrency(docData.subtotal),
      '{{消費税}}': formatCurrency(docData.tax),
      '{{合計金額}}': formatCurrency(docData.total),
      '{{備考}}': docData.notes || '',
      '{{会社名}}': companyInfo.name,
      '{{会社郵便番号}}': companyInfo.postalCode,
      '{{会社住所}}': companyInfo.address,
      '{{会社電話}}': companyInfo.phone,
      '{{会社メール}}': companyInfo.email,
      '{{登録番号}}': companyInfo.registrationNumber || '',
      '{{銀行名}}': companyInfo.bankName || '',
      '{{支店名}}': companyInfo.branchName || '',
      '{{口座種別}}': companyInfo.accountType || '',
      '{{口座番号}}': companyInfo.accountNumber || '',
      '{{口座名義}}': companyInfo.accountHolder || ''
    };

    // テキストを置換
    for (const [key, value] of Object.entries(replacements)) {
      body.replaceText(key, value);
    }

    doc.saveAndClose();

    // PDFに変換
    const pdfBlob = copyDoc.getAs('application/pdf');
    pdfBlob.setName(`${docNumber}.pdf`);

    // Google Driveに保存
    const folder = getOrCreatePDFFolder(docType);
    const pdfFile = folder.createFile(pdfBlob);
    const pdfUrl = pdfFile.getUrl();

    // 一時ファイルを削除
    DriveApp.getFileById(copyDocId).setTrashed(true);

    // スプレッドシートにPDF URLを記録
    updatePDFUrl(docNumber, docType, pdfUrl);

    return {
      success: true,
      pdfUrl: pdfUrl,
      fileName: `${docNumber}.pdf`
    };

  } catch (error) {
    Logger.log(`PDF生成エラー: ${error.message}`);
    return {
      success: false,
      message: `PDF生成エラー: ${error.message}`
    };
  }
}

/**
 * 明細テーブルをテキスト形式で作成
 */
function createLineItemsTable(lineItems) {
  if (!lineItems || lineItems.length === 0) {
    return '明細なし';
  }

  let table = '品目\t数量\t単価\t金額\n';
  table += '─'.repeat(50) + '\n';

  lineItems.forEach(item => {
    table += `${item.itemName}\t${item.quantity}\t${formatCurrency(item.unitPrice)}\t${formatCurrency(item.amount)}\n`;
  });

  return table;
}

/**
 * 金額をフォーマット（カンマ区切り）
 */
function formatCurrency(amount) {
  if (!amount) return '¥0';
  return '¥' + amount.toLocaleString('ja-JP');
}

/**
 * PDF保存用フォルダを取得（なければ作成）
 */
function getOrCreatePDFFolder(docType) {
  const folderName = `${DOC_TYPES[docType].name}PDF`;
  const folders = DriveApp.getFoldersByName(folderName);

  if (folders.hasNext()) {
    return folders.next();
  } else {
    return DriveApp.createFolder(folderName);
  }
}

/**
 * スプレッドシートにPDF URLを記録
 */
function updatePDFUrl(docNumber, docType, pdfUrl) {
  const sheetName = getSheetNameByDocType(docType);
  const sheet = getSheet(sheetName);
  const lastRow = sheet.getLastRow();

  if (lastRow < 2) return;

  const data = sheet.getRange(2, 1, lastRow - 1, 1).getValues();
  const rowIndex = data.findIndex(row => row[0] === docNumber);

  if (rowIndex >= 0) {
    sheet.getRange(rowIndex + 2, 15).setValue(pdfUrl); // O列にPDF URLを記録
  }
}

/**
 * 請求書データを取得（PDF生成用）
 */
function getInvoiceData(docNumber) {
  const sheet = getSheet(SHEET_NAMES.INVOICES);
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
    dueDate: row[5],
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
 * 領収書データを取得（PDF生成用）
 */
function getReceiptData(docNumber) {
  const sheet = getSheet(SHEET_NAMES.RECEIPTS);
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
