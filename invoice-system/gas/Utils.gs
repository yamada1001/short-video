/**
 * ユーティリティ関数
 */

/**
 * アクティブなスプレッドシートを取得
 */
function getSpreadsheet() {
  return SpreadsheetApp.getActiveSpreadsheet();
}

/**
 * 指定名のシートを取得
 */
function getSheet(sheetName) {
  const ss = getSpreadsheet();
  let sheet = ss.getSheetByName(sheetName);

  if (!sheet) {
    throw new Error(`シート「${sheetName}」が見つかりません`);
  }

  return sheet;
}

/**
 * 書類番号を自動生成
 * 例: Q-20250101-001（見積書）
 */
function generateDocumentNumber(docType) {
  const today = new Date();
  const dateStr = Utilities.formatDate(today, 'Asia/Tokyo', 'yyyyMMdd');
  const sheetName = getSheetNameByDocType(docType);
  const sheet = getSheet(sheetName);
  const lastRow = sheet.getLastRow();

  // 今日の日付で既に発行された番号を取得
  let maxSeq = 0;
  if (lastRow > 1) {
    const data = sheet.getRange(2, 1, lastRow - 1, 1).getValues();
    const todayPrefix = `${DOC_TYPES[docType].prefix}-${dateStr}-`;

    data.forEach(row => {
      const docNum = row[0];
      if (docNum && docNum.startsWith(todayPrefix)) {
        const seq = parseInt(docNum.split('-')[2]);
        if (seq > maxSeq) maxSeq = seq;
      }
    });
  }

  const newSeq = String(maxSeq + 1).padStart(3, '0');
  return `${DOC_TYPES[docType].prefix}-${dateStr}-${newSeq}`;
}

/**
 * 書類種別からシート名を取得
 */
function getSheetNameByDocType(docType) {
  switch(docType) {
    case 'QUOTE': return SHEET_NAMES.QUOTES;
    case 'DELIVERY': return SHEET_NAMES.DELIVERIES;
    case 'INVOICE': return SHEET_NAMES.INVOICES;
    case 'RECEIPT': return SHEET_NAMES.RECEIPTS;
    default: throw new Error('不正な書類種別です');
  }
}

/**
 * 取引先一覧を取得（プルダウン用）
 */
function getCustomerList() {
  const sheet = getSheet(SHEET_NAMES.CUSTOMERS);
  const lastRow = sheet.getLastRow();

  if (lastRow < 2) {
    return [];
  }

  const data = sheet.getRange(2, 1, lastRow - 1, 2).getValues();
  return data.map(row => ({
    id: row[0],
    name: row[1]
  })).filter(customer => customer.id && customer.name);
}

/**
 * 取引先情報を取得
 */
function getCustomerById(customerId) {
  const sheet = getSheet(SHEET_NAMES.CUSTOMERS);
  const lastRow = sheet.getLastRow();

  if (lastRow < 2) {
    return null;
  }

  const data = sheet.getRange(2, 1, lastRow - 1, 10).getValues();
  const row = data.find(r => r[0] === customerId);

  if (!row) {
    return null;
  }

  return {
    id: row[0],
    name: row[1],
    postalCode: row[2],
    address: row[3],
    contactPerson: row[4],
    phone: row[5],
    email: row[6],
    notes: row[7]
  };
}

/**
 * 自社情報を取定」シートから取得
 */
function getCompanyInfo() {
  const sheet = getSheet(SHEET_NAMES.SETTINGS);
  const data = sheet.getRange('B2:B14').getValues();

  return {
    name: data[0][0],
    postalCode: data[1][0],
    address: data[2][0],
    phone: data[3][0],
    fax: data[4][0],
    email: data[5][0],
    registrationNumber: data[6][0],
    bankName: data[7][0],
    branchName: data[8][0],
    accountType: data[9][0],
    accountNumber: data[10][0],
    accountHolder: data[11][0],
    stampImageUrl: data[12][0]
  };
}

/**
 * 明細JSONを解析
 */
function parseLineItems(lineItemsJson) {
  try {
    return JSON.parse(lineItemsJson);
  } catch (e) {
    return [];
  }
}

/**
 * 明細JSONを生成
 */
function stringifyLineItems(lineItems) {
  return JSON.stringify(lineItems);
}

/**
 * 小計・消費税・合計を計算
 */
function calculateAmounts(lineItems) {
  const subtotal = lineItems.reduce((sum, item) => sum + item.amount, 0);
  const tax = Math.floor(subtotal * 0.1);
  const total = subtotal + tax;

  return { subtotal, tax, total };
}

/**
 * 日付を YYYY/MM/DD 形式にフォーマット
 */
function formatDate(date) {
  if (!date) return '';
  return Utilities.formatDate(new Date(date), 'Asia/Tokyo', 'yyyy/MM/dd');
}

/**
 * 現在日時を取得
 */
function now() {
  return new Date();
}
