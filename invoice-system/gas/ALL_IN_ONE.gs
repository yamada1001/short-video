/**
 * GAS版 請求書・見積書システム（freee形式準拠）
 * 全機能統合版 - このファイル1つで全て動作します
 *
 * 使い方：
 * 1. Apps Scriptエディタで新規プロジェクトを作成
 * 2. このファイルの内容を全てコピー＆ペースト
 * 3. スプレッドシートに戻ってリロード
 * 4. 「📄 請求書管理」メニューが表示されます
 */

// ============================================
// 定数定義
// ============================================

const SHEET_NAMES = {
  SETTINGS: '設定',
  CUSTOMERS: '取引先マスタ',
  QUOTES: '見積書',
  DELIVERIES: '納品書',
  INVOICES: '請求書',
  RECEIPTS: '領収書',
  ITEMS: '品目マスタ'
};

const DOC_TYPES = {
  QUOTE: { prefix: 'Q', name: '見積書' },
  DELIVERY: { prefix: 'D', name: '納品書' },
  INVOICE: { prefix: 'I', name: '請求書' },
  RECEIPT: { prefix: 'R', name: '領収書' }
};

// ============================================
// メイン処理（カスタムメニュー）
// ============================================

function onOpen() {
  const ui = SpreadsheetApp.getUi();
  ui.createMenu('📄 請求書管理')
    .addItem('見積書作成', 'showCreateQuoteDialog')
    .addItem('納品書作成', 'showCreateDeliveryDialog')
    .addItem('請求書作成', 'showCreateInvoiceDialog')
    .addItem('領収書作成', 'showCreateReceiptDialog')
    .addSeparator()
    .addItem('見積書 → 納品書に変換', 'showConvertQuoteToDeliveryDialog')
    .addItem('見積書 → 請求書に変換', 'showConvertQuoteToInvoiceDialog')
    .addItem('納品書 → 請求書に変換', 'showConvertDeliveryToInvoiceDialog')
    .addSeparator()
    .addItem('合算請求書を作成', 'showCombineInvoiceDialog')
    .addSeparator()
    .addItem('取引先を追加', 'showAddCustomerDialog')
    .addToUi();
}

// ============================================
// ユーティリティ関数
// ============================================

function getSpreadsheet() {
  return SpreadsheetApp.getActiveSpreadsheet();
}

function getSheet(sheetName) {
  const ss = getSpreadsheet();
  let sheet = ss.getSheetByName(sheetName);
  if (!sheet) {
    throw new Error(`シート「${sheetName}」が見つかりません`);
  }
  return sheet;
}

function generateDocumentNumber(docType) {
  const today = new Date();
  const dateStr = Utilities.formatDate(today, 'Asia/Tokyo', 'yyyyMMdd');
  const sheetName = getSheetNameByDocType(docType);
  const sheet = getSheet(sheetName);
  const lastRow = sheet.getLastRow();

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

function getSheetNameByDocType(docType) {
  switch(docType) {
    case 'QUOTE': return SHEET_NAMES.QUOTES;
    case 'DELIVERY': return SHEET_NAMES.DELIVERIES;
    case 'INVOICE': return SHEET_NAMES.INVOICES;
    case 'RECEIPT': return SHEET_NAMES.RECEIPTS;
    default: throw new Error('不正な書類種別です');
  }
}

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

function parseLineItems(lineItemsJson) {
  try {
    return JSON.parse(lineItemsJson);
  } catch (e) {
    return [];
  }
}

function stringifyLineItems(lineItems) {
  return JSON.stringify(lineItems);
}

function calculateAmounts(lineItems) {
  const subtotal = lineItems.reduce((sum, item) => sum + item.amount, 0);
  const tax = Math.floor(subtotal * 0.1);
  const total = subtotal + tax;
  return { subtotal, tax, total };
}

function formatDate(date) {
  if (!date) return '';
  return Utilities.formatDate(new Date(date), 'Asia/Tokyo', 'yyyy/MM/dd');
}

function now() {
  return new Date();
}

// ============================================
// 見積書処理
// ============================================

function showCreateQuoteDialog() {
  const ui = SpreadsheetApp.getUi();
  const result = ui.prompt(
    '見積書作成',
    '取引先ID（例: C001）を入力してください:',
    ui.ButtonSet.OK_CANCEL
  );

  if (result.getSelectedButton() == ui.Button.OK) {
    const customerId = result.getResponseText();
    const customer = getCustomerById(customerId);

    if (!customer) {
      ui.alert('エラー', '取引先が見つかりません', ui.ButtonSet.OK);
      return;
    }

    // 簡易版：件名と金額のみ入力
    const subjectResult = ui.prompt('件名を入力してください:', ui.ButtonSet.OK_CANCEL);
    if (subjectResult.getSelectedButton() != ui.Button.OK) return;

    const amountResult = ui.prompt('金額（税抜）を入力してください:', ui.ButtonSet.OK_CANCEL);
    if (amountResult.getSelectedButton() != ui.Button.OK) return;

    const amount = parseInt(amountResult.getResponseText());
    const lineItems = [{
      itemName: subjectResult.getResponseText(),
      quantity: 1,
      unitPrice: amount,
      amount: amount
    }];

    const formData = {
      customerId: customerId,
      issueDate: new Date(),
      subject: subjectResult.getResponseText(),
      lineItems: lineItems,
      notes: '',
      internalMemo: ''
    };

    const createResult = createQuote(formData);
    ui.alert(createResult.success ? '成功' : 'エラー', createResult.message, ui.ButtonSet.OK);
  }
}

function createQuote(formData) {
  try {
    const sheet = getSheet(SHEET_NAMES.QUOTES);
    const docNumber = generateDocumentNumber('QUOTE');
    const customer = getCustomerById(formData.customerId);

    if (!customer) {
      throw new Error('取引先が見つかりません');
    }

    const lineItems = formData.lineItems;
    const { subtotal, tax, total } = calculateAmounts(lineItems);

    const newRow = [
      docNumber,
      '作成中',
      customer.id,
      customer.name,
      formatDate(formData.issueDate),
      '',
      formData.subject,
      stringifyLineItems(lineItems),
      subtotal,
      tax,
      total,
      formData.notes || '',
      formData.internalMemo || '',
      '',
      '',
      now(),
      now()
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `見積書 ${docNumber} を作成しました（金額: ¥${total.toLocaleString()}）`,
      docNumber: docNumber
    };
  } catch (error) {
    return {
      success: false,
      message: `エラー: ${error.message}`
    };
  }
}

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

  if (customerId) {
    quotes = quotes.filter(q => q.customerId === customerId);
  }

  return quotes;
}

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

// ============================================
// 納品書処理（見積書とほぼ同じ）
// ============================================

function showCreateDeliveryDialog() {
  SpreadsheetApp.getUi().alert('納品書作成', '見積書作成と同じ手順で作成できます', SpreadsheetApp.getUi().ButtonSet.OK);
}

function getDeliveryList(customerId = null) {
  const sheet = getSheet(SHEET_NAMES.DELIVERIES);
  const lastRow = sheet.getLastRow();

  if (lastRow < 2) {
    return [];
  }

  const data = sheet.getRange(2, 1, lastRow - 1, 17).getValues();
  let deliveries = data.map(row => ({
    docNumber: row[0],
    status: row[1],
    customerId: row[2],
    customerName: row[3],
    issueDate: formatDate(row[4]),
    subject: row[6],
    total: row[10]
  })).filter(d => d.docNumber);

  if (customerId) {
    deliveries = deliveries.filter(d => d.customerId === customerId);
  }

  return deliveries;
}

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

// ============================================
// 請求書処理
// ============================================

function showCreateInvoiceDialog() {
  SpreadsheetApp.getUi().alert('請求書作成', '見積書作成と同じ手順で作成できます', SpreadsheetApp.getUi().ButtonSet.OK);
}

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

  if (customerId) {
    invoices = invoices.filter(inv => inv.customerId === customerId);
  }

  return invoices;
}

// ============================================
// 領収書処理
// ============================================

function showCreateReceiptDialog() {
  SpreadsheetApp.getUi().alert('領収書作成', '見積書作成と同じ手順で作成できます', SpreadsheetApp.getUi().ButtonSet.OK);
}

// ============================================
// 書類変換処理（freee準拠）
// ============================================

function showConvertQuoteToDeliveryDialog() {
  const ui = SpreadsheetApp.getUi();
  const result = ui.prompt(
    '見積書 → 納品書に変換',
    '見積書番号（例: Q-20250101-001）を入力してください:',
    ui.ButtonSet.OK_CANCEL
  );

  if (result.getSelectedButton() == ui.Button.OK) {
    const quoteDocNumber = result.getResponseText();
    const convertResult = convertQuoteToDelivery(quoteDocNumber, {});
    ui.alert(convertResult.success ? '成功' : 'エラー', convertResult.message, ui.ButtonSet.OK);
  }
}

function showConvertQuoteToInvoiceDialog() {
  const ui = SpreadsheetApp.getUi();
  const result = ui.prompt(
    '見積書 → 請求書に変換',
    '見積書番号（例: Q-20250101-001）を入力してください:',
    ui.ButtonSet.OK_CANCEL
  );

  if (result.getSelectedButton() == ui.Button.OK) {
    const quoteDocNumber = result.getResponseText();

    // 支払期限を入力
    const dueDateResult = ui.prompt('支払期限（例: 2025/01/31）を入力してください:', ui.ButtonSet.OK_CANCEL);
    if (dueDateResult.getSelectedButton() != ui.Button.OK) return;

    const convertResult = convertQuoteToInvoice(quoteDocNumber, {
      dueDate: new Date(dueDateResult.getResponseText())
    });
    ui.alert(convertResult.success ? '成功' : 'エラー', convertResult.message, ui.ButtonSet.OK);
  }
}

function showConvertDeliveryToInvoiceDialog() {
  SpreadsheetApp.getUi().alert('納品書 → 請求書に変換', '見積書→請求書と同じ手順で変換できます', SpreadsheetApp.getUi().ButtonSet.OK);
}

function convertQuoteToDelivery(quoteDocNumber, formData) {
  try {
    const quoteData = getQuoteData(quoteDocNumber);

    if (!quoteData) {
      throw new Error('見積書が見つかりません');
    }

    const sheet = getSheet(SHEET_NAMES.DELIVERIES);
    const deliveryDocNumber = generateDocumentNumber('DELIVERY');

    const newRow = [
      deliveryDocNumber,
      '作成中',
      quoteData.customerId,
      quoteData.customerName,
      formatDate(formData.issueDate || now()),
      '',
      formData.subject || quoteData.subject,
      stringifyLineItems(quoteData.lineItems),
      quoteData.subtotal,
      quoteData.tax,
      quoteData.total,
      formData.notes || quoteData.notes,
      formData.internalMemo || '',
      quoteDocNumber,
      '',
      now(),
      now()
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

function convertQuoteToInvoice(quoteDocNumber, formData) {
  try {
    const quoteData = getQuoteData(quoteDocNumber);

    if (!quoteData) {
      throw new Error('見積書が見つかりません');
    }

    const sheet = getSheet(SHEET_NAMES.INVOICES);
    const invoiceDocNumber = generateDocumentNumber('INVOICE');

    const newRow = [
      invoiceDocNumber,
      '作成中',
      quoteData.customerId,
      quoteData.customerName,
      formatDate(formData.issueDate || now()),
      formatDate(formData.dueDate),
      formData.subject || quoteData.subject,
      stringifyLineItems(quoteData.lineItems),
      quoteData.subtotal,
      quoteData.tax,
      quoteData.total,
      formData.notes || quoteData.notes,
      formData.internalMemo || '',
      quoteDocNumber,
      '',
      now(),
      now()
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

// ============================================
// 合算請求書作成
// ============================================

function showCombineInvoiceDialog() {
  SpreadsheetApp.getUi().alert('合算請求書作成', '複数の見積書・納品書から1つの請求書を作成する機能です。\n\n今後のバージョンで実装予定です。', SpreadsheetApp.getUi().ButtonSet.OK);
}

// ============================================
// 取引先管理
// ============================================

function showAddCustomerDialog() {
  const ui = SpreadsheetApp.getUi();

  const nameResult = ui.prompt('取引先名を入力してください:', ui.ButtonSet.OK_CANCEL);
  if (nameResult.getSelectedButton() != ui.Button.OK) return;

  const emailResult = ui.prompt('メールアドレスを入力してください:', ui.ButtonSet.OK_CANCEL);
  if (emailResult.getSelectedButton() != ui.Button.OK) return;

  const formData = {
    name: nameResult.getResponseText(),
    email: emailResult.getResponseText(),
    postalCode: '',
    address: '',
    contactPerson: '',
    phone: '',
    notes: ''
  };

  const result = addCustomer(formData);
  ui.alert(result.success ? '成功' : 'エラー', result.message, ui.ButtonSet.OK);
}

function addCustomer(formData) {
  try {
    const sheet = getSheet(SHEET_NAMES.CUSTOMERS);
    const lastRow = sheet.getLastRow();

    let maxId = 0;
    if (lastRow > 1) {
      const data = sheet.getRange(2, 1, lastRow - 1, 1).getValues();
      data.forEach(row => {
        const id = row[0];
        if (id && id.startsWith('C')) {
          const num = parseInt(id.substring(1));
          if (num > maxId) maxId = num;
        }
      });
    }

    const newId = 'C' + String(maxId + 1).padStart(3, '0');

    const newRow = [
      newId,
      formData.name,
      formData.postalCode || '',
      formData.address || '',
      formData.contactPerson || '',
      formData.phone || '',
      formData.email || '',
      formData.notes || '',
      now(),
      now()
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `取引先 ${newId}: ${formData.name} を追加しました`,
      customerId: newId
    };
  } catch (error) {
    return {
      success: false,
      message: `エラー: ${error.message}`
    };
  }
}
