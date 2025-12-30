/**
 * GAS版 請求書・見積書システム（freee形式準拠）
 * フルバージョン - 全機能実装版
 *
 * 【実装機能】
 * ✅ 見積書・納品書・請求書・領収書の作成（リッチダイアログUI）
 * ✅ 見積書→納品書/請求書、納品書→請求書への変換
 * ✅ 合算請求書作成（複数の見積書・納品書から）
 * ✅ 複数明細の動的追加・削除
 * ✅ リアルタイム金額計算（小計・消費税・合計）
 * ✅ 取引先管理
 * ✅ 初期セットアップ（シート自動作成）
 * ✅ PDF自動生成（Google Docs テンプレート）
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
    .addItem('🔧 初期セットアップ（初回のみ）', 'setupSheets')
    .addItem('🧪 テストデータを挿入', 'insertTestData')
    .addSeparator()
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
    .addItem('📥 PDFを生成', 'showGeneratePDFDialog')
    .addItem('🗑️ PDFシートを削除', 'showDeletePDFSheetsDialog')
    .addSeparator()
    .addItem('取引先を追加', 'showAddCustomerDialog')
    .addToUi();
}

// ============================================
// 初期セットアップ
// ============================================

function setupSheets() {
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  const ui = SpreadsheetApp.getUi();

  try {
    // 設定シート
    let settingsSheet = ss.getSheetByName(SHEET_NAMES.SETTINGS);
    if (!settingsSheet) {
      settingsSheet = ss.insertSheet(SHEET_NAMES.SETTINGS);
      settingsSheet.getRange('A1:B1').setValues([['項目名', '値']]);
      settingsSheet.getRange('A2:A14').setValues([
        ['会社名'], ['郵便番号'], ['住所'], ['電話番号'], ['FAX番号'],
        ['メールアドレス'], ['登録番号（インボイス）'], ['振込先銀行名'],
        ['振込先支店名'], ['振込先口座種別'], ['振込先口座番号'],
        ['振込先口座名義'], ['印鑑画像URL']
      ]);
      settingsSheet.getRange('A1:B14').setFontWeight('bold');
    }

    // 取引先マスタシート
    let customersSheet = ss.getSheetByName(SHEET_NAMES.CUSTOMERS);
    if (!customersSheet) {
      customersSheet = ss.insertSheet(SHEET_NAMES.CUSTOMERS);
      customersSheet.getRange('A1:J1').setValues([[
        '取引先ID', '取引先名', '郵便番号', '住所', '担当者名',
        '電話番号', 'メールアドレス', '備考', '作成日', '更新日'
      ]]);
      customersSheet.getRange('A1:J1').setFontWeight('bold').setBackground('#E5DDD5');
    }

    // 見積書・納品書・請求書・領収書シート
    const docSheets = [
      SHEET_NAMES.QUOTES, SHEET_NAMES.DELIVERIES,
      SHEET_NAMES.INVOICES, SHEET_NAMES.RECEIPTS
    ];

    docSheets.forEach(sheetName => {
      let sheet = ss.getSheetByName(sheetName);
      if (!sheet) {
        sheet = ss.insertSheet(sheetName);
        sheet.getRange('A1:Q1').setValues([[
          '書類番号', 'ステータス', '取引先ID', '取引先名', '発行日', '支払期限',
          '件名', '明細JSON', '小計', '消費税', '合計金額', '備考',
          '社内メモ', '変換元', 'PDF URL', '作成日', '更新日'
        ]]);
        sheet.getRange('A1:Q1').setFontWeight('bold').setBackground('#E5DDD5');
      }
    });

    // 品目マスタシート
    let itemsSheet = ss.getSheetByName(SHEET_NAMES.ITEMS);
    if (!itemsSheet) {
      itemsSheet = ss.insertSheet(SHEET_NAMES.ITEMS);
      itemsSheet.getRange('A1:D1').setValues([['品目名', '単価', '単位', '備考']]);
      itemsSheet.getRange('A1:D1').setFontWeight('bold').setBackground('#E5DDD5');
    }

    // Sheet1を削除
    const sheet1 = ss.getSheetByName('Sheet1');
    if (sheet1 && ss.getSheets().length > 1) {
      ss.deleteSheet(sheet1);
    }

    ui.alert(
      '✅ セットアップ完了',
      '全7シートを作成しました。\n\n「設定」シートに自社情報を入力してください。',
      ui.ButtonSet.OK
    );
  } catch (error) {
    ui.alert('エラー', `セットアップエラー：${error.message}`, ui.ButtonSet.OK);
  }
}

// ============================================
// ユーティリティ関数
// ============================================

function getSpreadsheet() {
  return SpreadsheetApp.getActiveSpreadsheet();
}

function getSheet(sheetName) {
  const sheet = getSpreadsheet().getSheetByName(sheetName);
  if (!sheet) throw new Error(`シート「${sheetName}」が見つかりません`);
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
  const map = {
    QUOTE: SHEET_NAMES.QUOTES,
    DELIVERY: SHEET_NAMES.DELIVERIES,
    INVOICE: SHEET_NAMES.INVOICES,
    RECEIPT: SHEET_NAMES.RECEIPTS
  };
  return map[docType];
}

function getCustomerList() {
  const sheet = getSheet(SHEET_NAMES.CUSTOMERS);
  const lastRow = sheet.getLastRow();
  if (lastRow < 2) return [];

  const data = sheet.getRange(2, 1, lastRow - 1, 2).getValues();
  return data.map(row => ({ id: row[0], name: row[1] }))
    .filter(c => c.id && c.name);
}

function getCustomerById(customerId) {
  const sheet = getSheet(SHEET_NAMES.CUSTOMERS);
  const lastRow = sheet.getLastRow();
  if (lastRow < 2) return null;

  const data = sheet.getRange(2, 1, lastRow - 1, 10).getValues();
  const row = data.find(r => r[0] === customerId);
  if (!row) return null;

  return {
    id: row[0], name: row[1], postalCode: row[2], address: row[3],
    contactPerson: row[4], phone: row[5], email: row[6], notes: row[7]
  };
}

function getCompanyInfo() {
  const sheet = getSheet(SHEET_NAMES.SETTINGS);
  const data = sheet.getRange('B2:B14').getValues();
  return {
    name: data[0][0], postalCode: data[1][0], address: data[2][0],
    phone: data[3][0], fax: data[4][0], email: data[5][0],
    registrationNumber: data[6][0], bankName: data[7][0],
    branchName: data[8][0], accountType: data[9][0],
    accountNumber: data[10][0], accountHolder: data[11][0],
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
// 見積書作成
// ============================================

function showCreateQuoteDialog() {
  const html = HtmlService.createHtmlOutput(getCreateDocumentDialogHTML('QUOTE'))
    .setWidth(700)
    .setHeight(600);
  SpreadsheetApp.getUi().showModalDialog(html, '見積書作成');
}

function createQuote(formData) {
  return createDocument('QUOTE', formData);
}

function getQuoteList(customerId = null) {
  return getDocumentList('QUOTE', customerId);
}

function getQuoteData(docNumber) {
  return getDocumentData('QUOTE', docNumber);
}

// ============================================
// 納品書作成
// ============================================

function showCreateDeliveryDialog() {
  const html = HtmlService.createHtmlOutput(getCreateDocumentDialogHTML('DELIVERY'))
    .setWidth(700)
    .setHeight(600);
  SpreadsheetApp.getUi().showModalDialog(html, '納品書作成');
}

function createDelivery(formData) {
  return createDocument('DELIVERY', formData);
}

function getDeliveryList(customerId = null) {
  return getDocumentList('DELIVERY', customerId);
}

function getDeliveryData(docNumber) {
  return getDocumentData('DELIVERY', docNumber);
}

// ============================================
// 請求書作成
// ============================================

function showCreateInvoiceDialog() {
  const html = HtmlService.createHtmlOutput(getCreateDocumentDialogHTML('INVOICE'))
    .setWidth(700)
    .setHeight(650);
  SpreadsheetApp.getUi().showModalDialog(html, '請求書作成');
}

function createInvoice(formData) {
  return createDocument('INVOICE', formData);
}

function getInvoiceList(customerId = null) {
  return getDocumentList('INVOICE', customerId);
}

// ============================================
// 領収書作成
// ============================================

function showCreateReceiptDialog() {
  const html = HtmlService.createHtmlOutput(getCreateDocumentDialogHTML('RECEIPT'))
    .setWidth(700)
    .setHeight(600);
  SpreadsheetApp.getUi().showModalDialog(html, '領収書作成');
}

function createReceipt(formData) {
  return createDocument('RECEIPT', formData);
}

// ============================================
// 書類作成共通処理
// ============================================

function createDocument(docType, formData) {
  try {
    const sheet = getSheet(getSheetNameByDocType(docType));
    const docNumber = generateDocumentNumber(docType);
    const customer = getCustomerById(formData.customerId);

    if (!customer) throw new Error('取引先が見つかりません');

    const lineItems = formData.lineItems;
    const { subtotal, tax, total } = calculateAmounts(lineItems);

    const newRow = [
      docNumber, '作成中', customer.id, customer.name,
      formatDate(formData.issueDate),
      formatDate(formData.dueDate || ''),
      formData.subject,
      stringifyLineItems(lineItems),
      subtotal, tax, total,
      formData.notes || '',
      formData.internalMemo || '',
      formData.sourceDocNumber || '',
      '', now(), now()
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `${DOC_TYPES[docType].name} ${docNumber} を作成しました（金額: ¥${total.toLocaleString()}）`,
      docNumber: docNumber
    };
  } catch (error) {
    return { success: false, message: `エラー: ${error.message}` };
  }
}

function getDocumentList(docType, customerId = null) {
  const sheet = getSheet(getSheetNameByDocType(docType));
  const lastRow = sheet.getLastRow();
  if (lastRow < 2) return [];

  const data = sheet.getRange(2, 1, lastRow - 1, 17).getValues();
  let docs = data.map(row => ({
    docNumber: row[0], status: row[1], customerId: row[2],
    customerName: row[3], issueDate: formatDate(row[4]),
    dueDate: formatDate(row[5]), subject: row[6], total: row[10]
  })).filter(d => d.docNumber);

  if (customerId) {
    docs = docs.filter(d => d.customerId === customerId);
  }

  return docs;
}

function getDocumentData(docType, docNumber) {
  const sheet = getSheet(getSheetNameByDocType(docType));
  const lastRow = sheet.getLastRow();
  if (lastRow < 2) return null;

  const data = sheet.getRange(2, 1, lastRow - 1, 17).getValues();
  const row = data.find(r => r[0] === docNumber);
  if (!row) return null;

  return {
    docNumber: row[0], status: row[1], customerId: row[2],
    customerName: row[3], issueDate: row[4], dueDate: row[5],
    subject: row[6], lineItems: parseLineItems(row[7]),
    subtotal: row[8], tax: row[9], total: row[10],
    notes: row[11], internalMemo: row[12]
  };
}

// ============================================
// 書類変換処理
// ============================================

function showConvertQuoteToDeliveryDialog() {
  const html = HtmlService.createHtmlOutput(getConversionDialogHTML('QUOTE', 'DELIVERY'))
    .setWidth(600)
    .setHeight(500);
  SpreadsheetApp.getUi().showModalDialog(html, '見積書 → 納品書に変換');
}

function showConvertQuoteToInvoiceDialog() {
  const html = HtmlService.createHtmlOutput(getConversionDialogHTML('QUOTE', 'INVOICE'))
    .setWidth(600)
    .setHeight(550);
  SpreadsheetApp.getUi().showModalDialog(html, '見積書 → 請求書に変換');
}

function showConvertDeliveryToInvoiceDialog() {
  const html = HtmlService.createHtmlOutput(getConversionDialogHTML('DELIVERY', 'INVOICE'))
    .setWidth(600)
    .setHeight(550);
  SpreadsheetApp.getUi().showModalDialog(html, '納品書 → 請求書に変換');
}

function convertDocument(sourceDocNumber, targetDocType, formData) {
  try {
    const sourceDocType = sourceDocNumber.startsWith('Q-') ? 'QUOTE' :
                          sourceDocNumber.startsWith('D-') ? 'DELIVERY' : null;

    if (!sourceDocType) throw new Error('不正な書類番号です');

    const sourceData = getDocumentData(sourceDocType, sourceDocNumber);
    if (!sourceData) throw new Error('元の書類が見つかりません');

    const sheet = getSheet(getSheetNameByDocType(targetDocType));
    const newDocNumber = generateDocumentNumber(targetDocType);

    const newRow = [
      newDocNumber, '作成中', sourceData.customerId, sourceData.customerName,
      formatDate(formData.issueDate || now()),
      formatDate(formData.dueDate || ''),
      formData.subject || sourceData.subject,
      stringifyLineItems(sourceData.lineItems),
      sourceData.subtotal, sourceData.tax, sourceData.total,
      formData.notes || sourceData.notes,
      formData.internalMemo || '',
      sourceDocNumber, '', now(), now()
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `${DOC_TYPES[sourceDocType].name} ${sourceDocNumber} から${DOC_TYPES[targetDocType].name} ${newDocNumber} を作成しました`,
      docNumber: newDocNumber
    };
  } catch (error) {
    return { success: false, message: `エラー: ${error.message}` };
  }
}

// ============================================
// 合算請求書作成
// ============================================

function showCombineInvoiceDialog() {
  const html = HtmlService.createHtmlOutput(getCombineInvoiceDialogHTML())
    .setWidth(700)
    .setHeight(600);
  SpreadsheetApp.getUi().showModalDialog(html, '合算請求書を作成');
}

function getDocumentsForCombine(customerId) {
  const quotes = getQuoteList(customerId);
  const deliveries = getDeliveryList(customerId);

  const documents = [
    ...quotes.map(q => ({ ...q, docType: '見積書', docTypeCode: 'QUOTE' })),
    ...deliveries.map(d => ({ ...d, docType: '納品書', docTypeCode: 'DELIVERY' }))
  ];

  documents.sort((a, b) => new Date(a.issueDate) - new Date(b.issueDate));
  return documents;
}

function createCombinedInvoice(formData) {
  try {
    const customerId = formData.customerId;
    const selectedDocNumbers = formData.selectedDocNumbers;
    const customer = getCustomerById(customerId);

    if (!customer) throw new Error('取引先が見つかりません');
    if (!selectedDocNumbers || selectedDocNumbers.length === 0) {
      throw new Error('合算する書類を選択してください');
    }

    const combinedLineItems = [];
    const sourceDocNumbers = [];

    selectedDocNumbers.forEach(docNumber => {
      let docData = null;
      if (docNumber.startsWith('Q-')) docData = getQuoteData(docNumber);
      else if (docNumber.startsWith('D-')) docData = getDeliveryData(docNumber);

      if (docData) {
        combinedLineItems.push(...docData.lineItems);
        sourceDocNumbers.push(docNumber);
      }
    });

    if (combinedLineItems.length === 0) throw new Error('明細が取得できませんでした');

    const { subtotal, tax, total } = calculateAmounts(combinedLineItems);
    const sheet = getSheet(SHEET_NAMES.INVOICES);
    const invoiceDocNumber = generateDocumentNumber('INVOICE');

    const newRow = [
      invoiceDocNumber, '作成中', customerId, customer.name,
      formatDate(formData.issueDate || now()),
      formatDate(formData.dueDate),
      formData.subject || '合算請求書',
      stringifyLineItems(combinedLineItems),
      subtotal, tax, total,
      formData.notes || `合算元: ${sourceDocNumbers.join(', ')}`,
      formData.internalMemo || '',
      sourceDocNumbers.join(', '),
      '', now(), now()
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `${sourceDocNumbers.length}件の書類から合算請求書 ${invoiceDocNumber} を作成しました`,
      docNumber: invoiceDocNumber
    };
  } catch (error) {
    return { success: false, message: `エラー: ${error.message}` };
  }
}

// ============================================
// 取引先追加
// ============================================

function showAddCustomerDialog() {
  const html = HtmlService.createHtmlOutput(getAddCustomerDialogHTML())
    .setWidth(600)
    .setHeight(500);
  SpreadsheetApp.getUi().showModalDialog(html, '取引先を追加');
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
      newId, formData.name, formData.postalCode || '',
      formData.address || '', formData.contactPerson || '',
      formData.phone || '', formData.email || '',
      formData.notes || '', now(), now()
    ];

    sheet.appendRow(newRow);

    return {
      success: true,
      message: `取引先 ${newId}: ${formData.name} を追加しました`,
      customerId: newId
    };
  } catch (error) {
    return { success: false, message: `エラー: ${error.message}` };
  }
}

// ============================================
// HTMLダイアログテンプレート
// ============================================

function getCreateDocumentDialogHTML(docType) {
  const docTypeName = DOC_TYPES[docType].name;
  const showDueDate = (docType === 'INVOICE');

  return `
<!DOCTYPE html>
<html>
<head>
  <base target="_top">
  <style>
    body { font-family: 'Noto Sans JP', Arial, sans-serif; padding: 20px; background: #F5F3F0; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: 600; color: #4A4A4A; }
    input, select, textarea { width: 100%; padding: 8px; border: 1px solid #E5DDD5; border-radius: 2px; font-size: 14px; box-sizing: border-box; }
    textarea { min-height: 60px; resize: vertical; }
    .line-items { margin: 20px 0; border: 1px solid #E5DDD5; padding: 15px; background: white; border-radius: 2px; }
    .line-item { display: grid; grid-template-columns: 3fr 1fr 1fr 1fr 30px; gap: 10px; margin-bottom: 10px; align-items: end; }
    .btn { background: #8B7355; color: white; border: none; padding: 10px 20px; border-radius: 2px; cursor: pointer; font-size: 14px; margin-right: 10px; }
    .btn:hover { background: #6B5335; }
    .btn-secondary { background: #E5DDD5; color: #4A4A4A; }
    .btn-add { background: #4CAF50; padding: 8px 15px; font-size: 13px; }
    .remove-btn { background: #f44336; color: white; border: none; width: 25px; height: 25px; border-radius: 2px; cursor: pointer; }
    .total-section { text-align: right; margin-top: 15px; padding-top: 15px; border-top: 2px solid #E5DDD5; font-weight: 600; }
    #message { margin-top: 15px; padding: 10px; border-radius: 2px; display: none; }
    .success { background: #E8F5E9; color: #2E7D32; border: 1px solid #4CAF50; }
    .error { background: #FFEBEE; color: #C62828; border: 1px solid #F44336; }
  </style>
</head>
<body>
  <form id="docForm">
    <div class="form-group">
      <label>取引先 *</label>
      <select id="customerId" required><option value="">選択してください</option></select>
    </div>
    <div class="form-group">
      <label>発行日 *</label>
      <input type="date" id="issueDate" required>
    </div>
    ${showDueDate ? '<div class="form-group"><label>支払期限 *</label><input type="date" id="dueDate" required></div>' : ''}
    <div class="form-group">
      <label>件名 *</label>
      <input type="text" id="subject" placeholder="例: 〇〇工事費用" required>
    </div>
    <div class="line-items">
      <label>明細 *</label>
      <div id="lineItemsContainer">
        <div class="line-item">
          <input type="text" placeholder="品目" class="item-name" required>
          <input type="number" placeholder="数量" class="item-qty" value="1" min="1" required>
          <input type="number" placeholder="単価" class="item-price" min="0" required>
          <input type="number" placeholder="金額" class="item-amount" readonly>
          <button type="button" class="remove-btn" onclick="removeLineItem(this)">×</button>
        </div>
      </div>
      <button type="button" class="btn btn-add" onclick="addLineItem()">+ 明細を追加</button>
      <div class="total-section">
        <div>小計: ¥<span id="subtotal">0</span></div>
        <div>消費税(10%): ¥<span id="tax">0</span></div>
        <div style="font-size: 18px; color: #8B7355; margin-top: 10px;">合計: ¥<span id="total">0</span></div>
      </div>
    </div>
    <div class="form-group">
      <label>備考</label>
      <textarea id="notes" placeholder="納期など"></textarea>
    </div>
    <div class="form-group">
      <label>社内メモ（PDF非表示）</label>
      <textarea id="internalMemo"></textarea>
    </div>
    <div style="margin-top: 20px;">
      <button type="submit" class="btn">${docTypeName}を作成</button>
      <button type="button" class="btn btn-secondary" onclick="google.script.host.close()">キャンセル</button>
    </div>
    <div id="message"></div>
  </form>
  <script>
    google.script.run.withSuccessHandler(function(customers) {
      const select = document.getElementById('customerId');
      customers.forEach(c => {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = c.name;
        select.appendChild(option);
      });
    }).getCustomerList();

    document.getElementById('issueDate').valueAsDate = new Date();
    ${showDueDate ? "const d = new Date(); d.setDate(d.getDate() + 30); document.getElementById('dueDate').valueAsDate = d;" : ""}

    function addLineItem() {
      const container = document.getElementById('lineItemsContainer');
      const newItem = document.createElement('div');
      newItem.className = 'line-item';
      newItem.innerHTML = '<input type="text" placeholder="品目" class="item-name" required><input type="number" placeholder="数量" class="item-qty" value="1" min="1" required><input type="number" placeholder="単価" class="item-price" min="0" required><input type="number" placeholder="金額" class="item-amount" readonly><button type="button" class="remove-btn" onclick="removeLineItem(this)">×</button>';
      container.appendChild(newItem);
      attachCalculateListeners(newItem);
    }

    function removeLineItem(btn) {
      if (document.querySelectorAll('.line-item').length > 1) {
        btn.closest('.line-item').remove();
        calculateTotal();
      } else {
        alert('最低1つの明細が必要です');
      }
    }

    function calculateLineAmount(lineItem) {
      const qty = parseFloat(lineItem.querySelector('.item-qty').value) || 0;
      const price = parseFloat(lineItem.querySelector('.item-price').value) || 0;
      lineItem.querySelector('.item-amount').value = qty * price;
      calculateTotal();
    }

    function calculateTotal() {
      let subtotal = 0;
      document.querySelectorAll('.line-item').forEach(item => {
        subtotal += parseFloat(item.querySelector('.item-amount').value) || 0;
      });
      const tax = Math.floor(subtotal * 0.1);
      const total = subtotal + tax;
      document.getElementById('subtotal').textContent = subtotal.toLocaleString();
      document.getElementById('tax').textContent = tax.toLocaleString();
      document.getElementById('total').textContent = total.toLocaleString();
    }

    function attachCalculateListeners(lineItem) {
      lineItem.querySelector('.item-qty').addEventListener('input', () => calculateLineAmount(lineItem));
      lineItem.querySelector('.item-price').addEventListener('input', () => calculateLineAmount(lineItem));
    }

    document.querySelectorAll('.line-item').forEach(attachCalculateListeners);

    document.getElementById('docForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const lineItems = [];
      document.querySelectorAll('.line-item').forEach(item => {
        const itemName = item.querySelector('.item-name').value;
        const quantity = parseFloat(item.querySelector('.item-qty').value);
        const unitPrice = parseFloat(item.querySelector('.item-price').value);
        const amount = parseFloat(item.querySelector('.item-amount').value);
        if (itemName && quantity && unitPrice >= 0) {
          lineItems.push({ itemName, quantity, unitPrice, amount });
        }
      });

      if (lineItems.length === 0) {
        alert('明細を入力してください');
        return;
      }

      const formData = {
        customerId: document.getElementById('customerId').value,
        issueDate: document.getElementById('issueDate').value,
        ${showDueDate ? "dueDate: document.getElementById('dueDate').value," : ""}
        subject: document.getElementById('subject').value,
        lineItems: lineItems,
        notes: document.getElementById('notes').value,
        internalMemo: document.getElementById('internalMemo').value
      };

      google.script.run
        .withSuccessHandler(function(result) {
          const messageDiv = document.getElementById('message');
          if (result.success) {
            messageDiv.className = 'success';
            messageDiv.textContent = result.message;
            messageDiv.style.display = 'block';
            setTimeout(() => google.script.host.close(), 2000);
          } else {
            messageDiv.className = 'error';
            messageDiv.textContent = result.message;
            messageDiv.style.display = 'block';
          }
        })
        .withFailureHandler(function(error) {
          const messageDiv = document.getElementById('message');
          messageDiv.className = 'error';
          messageDiv.textContent = 'エラー: ' + error.message;
          messageDiv.style.display = 'block';
        })
        .create${docType[0] + docType.slice(1).toLowerCase()}(formData);
    });
  </script>
</body>
</html>
  `.trim();
}

function getConversionDialogHTML(sourceType, targetType) {
  const sourceTypeName = DOC_TYPES[sourceType].name;
  const targetTypeName = DOC_TYPES[targetType].name;
  const showDueDate = (targetType === 'INVOICE');

  return `
<!DOCTYPE html>
<html>
<head>
  <base target="_top">
  <style>
    body { font-family: 'Noto Sans JP', Arial, sans-serif; padding: 20px; background: #F5F3F0; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: 600; color: #4A4A4A; }
    input, select, textarea { width: 100%; padding: 8px; border: 1px solid #E5DDD5; border-radius: 2px; font-size: 14px; box-sizing: border-box; }
    .btn { background: #8B7355; color: white; border: none; padding: 10px 20px; border-radius: 2px; cursor: pointer; margin-right: 10px; }
    .btn-secondary { background: #E5DDD5; color: #4A4A4A; }
    #message { margin-top: 15px; padding: 10px; border-radius: 2px; display: none; }
    .success { background: #E8F5E9; color: #2E7D32; }
    .error { background: #FFEBEE; color: #C62828; }
  </style>
</head>
<body>
  <form id="convertForm">
    <div class="form-group">
      <label>${sourceTypeName}番号 *</label>
      <select id="sourceDocNumber" required><option value="">選択してください</option></select>
    </div>
    <div class="form-group">
      <label>発行日</label>
      <input type="date" id="issueDate">
    </div>
    ${showDueDate ? '<div class="form-group"><label>支払期限 *</label><input type="date" id="dueDate" required></div>' : ''}
    <div class="form-group">
      <label>件名（変更する場合）</label>
      <input type="text" id="subject">
    </div>
    <div class="form-group">
      <label>備考</label>
      <textarea id="notes"></textarea>
    </div>
    <div>
      <button type="submit" class="btn">変換して${targetTypeName}を作成</button>
      <button type="button" class="btn btn-secondary" onclick="google.script.host.close()">キャンセル</button>
    </div>
    <div id="message"></div>
  </form>
  <script>
    google.script.run.withSuccessHandler(function(docs) {
      const select = document.getElementById('sourceDocNumber');
      docs.forEach(d => {
        const option = document.createElement('option');
        option.value = d.docNumber;
        option.textContent = d.docNumber + ' - ' + d.customerName + ' (¥' + d.total.toLocaleString() + ')';
        select.appendChild(option);
      });
    }).get${sourceType[0] + sourceType.slice(1).toLowerCase()}List();

    document.getElementById('issueDate').valueAsDate = new Date();
    ${showDueDate ? "const d = new Date(); d.setDate(d.getDate() + 30); document.getElementById('dueDate').valueAsDate = d;" : ""}

    document.getElementById('convertForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = {
        issueDate: document.getElementById('issueDate').value,
        ${showDueDate ? "dueDate: document.getElementById('dueDate').value," : ""}
        subject: document.getElementById('subject').value,
        notes: document.getElementById('notes').value
      };

      google.script.run
        .withSuccessHandler(function(result) {
          const messageDiv = document.getElementById('message');
          messageDiv.className = result.success ? 'success' : 'error';
          messageDiv.textContent = result.message;
          messageDiv.style.display = 'block';
          if (result.success) setTimeout(() => google.script.host.close(), 2000);
        })
        .convertDocument(document.getElementById('sourceDocNumber').value, '${targetType}', formData);
    });
  </script>
</body>
</html>
  `.trim();
}

function getCombineInvoiceDialogHTML() {
  return `
<!DOCTYPE html>
<html>
<head>
  <base target="_top">
  <style>
    body { font-family: 'Noto Sans JP', Arial, sans-serif; padding: 20px; background: #F5F3F0; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: 600; color: #4A4A4A; }
    input, select { width: 100%; padding: 8px; border: 1px solid #E5DDD5; border-radius: 2px; font-size: 14px; box-sizing: border-box; }
    .doc-list { max-height: 250px; overflow-y: auto; border: 1px solid #E5DDD5; padding: 10px; background: white; }
    .doc-item { padding: 8px; margin: 5px 0; background: #f9f9f9; border-radius: 2px; }
    .btn { background: #8B7355; color: white; border: none; padding: 10px 20px; border-radius: 2px; cursor: pointer; margin-right: 10px; }
    .btn-secondary { background: #E5DDD5; color: #4A4A4A; }
    #message { margin-top: 15px; padding: 10px; border-radius: 2px; display: none; }
    .success { background: #E8F5E9; color: #2E7D32; }
    .error { background: #FFEBEE; color: #C62828; }
  </style>
</head>
<body>
  <form id="combineForm">
    <div class="form-group">
      <label>取引先 *</label>
      <select id="customerId" required onchange="loadDocuments()"><option value="">選択してください</option></select>
    </div>
    <div class="form-group">
      <label>合算する書類を選択 *</label>
      <div class="doc-list" id="docList">取引先を選択してください</div>
    </div>
    <div class="form-group">
      <label>発行日 *</label>
      <input type="date" id="issueDate" required>
    </div>
    <div class="form-group">
      <label>支払期限 *</label>
      <input type="date" id="dueDate" required>
    </div>
    <div class="form-group">
      <label>件名</label>
      <input type="text" id="subject" value="合算請求書">
    </div>
    <div>
      <button type="submit" class="btn">合算請求書を作成</button>
      <button type="button" class="btn btn-secondary" onclick="google.script.host.close()">キャンセル</button>
    </div>
    <div id="message"></div>
  </form>
  <script>
    google.script.run.withSuccessHandler(function(customers) {
      const select = document.getElementById('customerId');
      customers.forEach(c => {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = c.name;
        select.appendChild(option);
      });
    }).getCustomerList();

    document.getElementById('issueDate').valueAsDate = new Date();
    const d = new Date(); d.setDate(d.getDate() + 30);
    document.getElementById('dueDate').valueAsDate = d;

    function loadDocuments() {
      const customerId = document.getElementById('customerId').value;
      if (!customerId) return;

      google.script.run.withSuccessHandler(function(docs) {
        const docList = document.getElementById('docList');
        if (docs.length === 0) {
          docList.innerHTML = '<p>合算可能な書類がありません</p>';
          return;
        }
        docList.innerHTML = docs.map(d =>
          '<div class="doc-item"><label><input type="checkbox" name="doc" value="' + d.docNumber + '"> ' +
          d.docNumber + ' - ' + d.docType + ' (' + d.subject + ') ¥' + d.total.toLocaleString() + '</label></div>'
        ).join('');
      }).getDocumentsForCombine(customerId);
    }

    document.getElementById('combineForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const selectedDocs = Array.from(document.querySelectorAll('input[name="doc"]:checked')).map(cb => cb.value);
      if (selectedDocs.length === 0) {
        alert('合算する書類を選択してください');
        return;
      }

      const formData = {
        customerId: document.getElementById('customerId').value,
        selectedDocNumbers: selectedDocs,
        issueDate: document.getElementById('issueDate').value,
        dueDate: document.getElementById('dueDate').value,
        subject: document.getElementById('subject').value
      };

      google.script.run
        .withSuccessHandler(function(result) {
          const messageDiv = document.getElementById('message');
          messageDiv.className = result.success ? 'success' : 'error';
          messageDiv.textContent = result.message;
          messageDiv.style.display = 'block';
          if (result.success) setTimeout(() => google.script.host.close(), 2000);
        })
        .createCombinedInvoice(formData);
    });
  </script>
</body>
</html>
  `.trim();
}

function getAddCustomerDialogHTML() {
  return `
<!DOCTYPE html>
<html>
<head>
  <base target="_top">
  <style>
    body { font-family: 'Noto Sans JP', Arial, sans-serif; padding: 20px; background: #F5F3F0; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: 600; color: #4A4A4A; }
    input { width: 100%; padding: 8px; border: 1px solid #E5DDD5; border-radius: 2px; font-size: 14px; box-sizing: border-box; }
    .btn { background: #8B7355; color: white; border: none; padding: 10px 20px; border-radius: 2px; cursor: pointer; margin-right: 10px; }
    .btn-secondary { background: #E5DDD5; color: #4A4A4A; }
    #message { margin-top: 15px; padding: 10px; border-radius: 2px; display: none; }
    .success { background: #E8F5E9; color: #2E7D32; }
    .error { background: #FFEBEE; color: #C62828; }
  </style>
</head>
<body>
  <form id="customerForm">
    <div class="form-group">
      <label>取引先名 *</label>
      <input type="text" id="name" placeholder="例: 株式会社〇〇" required>
    </div>
    <div class="form-group">
      <label>郵便番号</label>
      <input type="text" id="postalCode" placeholder="例: 870-0000">
    </div>
    <div class="form-group">
      <label>住所</label>
      <input type="text" id="address" placeholder="例: 大分県大分市〇〇 1-2-3">
    </div>
    <div class="form-group">
      <label>担当者名</label>
      <input type="text" id="contactPerson" placeholder="例: 山田太郎">
    </div>
    <div class="form-group">
      <label>電話番号</label>
      <input type="text" id="phone" placeholder="例: 097-XXX-XXXX">
    </div>
    <div class="form-group">
      <label>メールアドレス</label>
      <input type="email" id="email" placeholder="例: contact@example.com">
    </div>
    <div>
      <button type="submit" class="btn">取引先を追加</button>
      <button type="button" class="btn btn-secondary" onclick="google.script.host.close()">キャンセル</button>
    </div>
    <div id="message"></div>
  </form>
  <script>
    document.getElementById('customerForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = {
        name: document.getElementById('name').value,
        postalCode: document.getElementById('postalCode').value,
        address: document.getElementById('address').value,
        contactPerson: document.getElementById('contactPerson').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value
      };

      google.script.run
        .withSuccessHandler(function(result) {
          const messageDiv = document.getElementById('message');
          messageDiv.className = result.success ? 'success' : 'error';
          messageDiv.textContent = result.message;
          messageDiv.style.display = 'block';
          if (result.success) setTimeout(() => google.script.host.close(), 2000);
        })
        .addCustomer(formData);
    });
  </script>
</body>
</html>
  `.trim();
}

// ============================================
// PDF生成処理
// ============================================

/**
 * PDF生成ダイアログを表示
 */
function showGeneratePDFDialog() {
  const html = HtmlService.createHtmlOutput(getGeneratePDFDialogHTML())
    .setWidth(600)
    .setHeight(400);
  SpreadsheetApp.getUi().showModalDialog(html, 'PDFを生成');
}

/**
 * PDFを生成（新方式: シート作成 → PDF エクスポート）
 */
function generatePDF(docNumber, docType) {
  try {
    const ss = getSpreadsheet();

    // 書類データを取得
    const docData = getDocumentData(docType, docNumber);
    if (!docData) {
      return { success: false, message: '書類データが見つかりません' };
    }

    // 自社情報を取得
    const companyInfo = getCompanyInfo();

    // 取引先情報を取得
    const customer = getCustomerById(docData.customerId);

    // 新しいシートを作成
    const sheetName = `PDF_${docNumber}`;

    // 既存のシートがあれば削除
    const existingSheet = ss.getSheetByName(sheetName);
    if (existingSheet) {
      ss.deleteSheet(existingSheet);
    }

    const pdfSheet = ss.insertSheet(sheetName);

    // シートをフォーマット（freee風レイアウト）
    formatPDFSheet(pdfSheet, docType, docData, companyInfo, customer);

    // PDF エクスポートURLを生成
    const spreadsheetId = ss.getId();
    const sheetId = pdfSheet.getSheetId();
    const pdfUrl = `https://docs.google.com/spreadsheets/d/${spreadsheetId}/export?format=pdf&gid=${sheetId}&portrait=true&size=A4&fitw=true`;

    // スプレッドシートにPDF URLを記録
    updatePDFUrl(docNumber, docType, pdfUrl);

    return {
      success: true,
      message: `PDF生成完了！\n\nシート「${sheetName}」を作成しました。\n下記リンクからPDFダウンロードできます。`,
      pdfUrl: pdfUrl,
      sheetName: sheetName,
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
 * PDFシートをフォーマット（freee風レイアウト）
 */
function formatPDFSheet(sheet, docType, docData, companyInfo, customer) {
  // シート幅を調整
  sheet.setColumnWidth(1, 500);
  sheet.setColumnWidth(2, 150);
  sheet.setColumnWidth(3, 100);
  sheet.setColumnWidth(4, 100);
  sheet.setColumnWidth(5, 120);

  let row = 1;

  // ===== ヘッダー =====
  sheet.getRange(row, 1, 1, 5).merge();
  sheet.getRange(row, 1).setValue(DOC_TYPES[docType].name);
  sheet.getRange(row, 1).setFontSize(24).setFontWeight('bold').setHorizontalAlignment('center');
  sheet.setRowHeight(row, 50);
  row += 2;

  // ===== 取引先情報 =====
  sheet.getRange(row, 1).setValue(`${customer.name} 御中`);
  sheet.getRange(row, 1).setFontSize(14).setFontWeight('bold');
  row++;

  if (customer.postalCode || customer.address) {
    sheet.getRange(row, 1).setValue(`〒${customer.postalCode || ''} ${customer.address || ''}`);
    sheet.getRange(row, 1).setFontSize(10);
    row++;
  }

  if (customer.contactPerson) {
    sheet.getRange(row, 1).setValue(`担当: ${customer.contactPerson}`);
    sheet.getRange(row, 1).setFontSize(10);
    row++;
  }
  row++;

  // ===== 書類情報 =====
  sheet.getRange(row, 1).setValue('書類番号:');
  sheet.getRange(row, 2).setValue(docData.docNumber);
  row++;

  sheet.getRange(row, 1).setValue('発行日:');
  sheet.getRange(row, 2).setValue(formatDate(docData.issueDate));
  row++;

  if (docData.dueDate) {
    sheet.getRange(row, 1).setValue('支払期限:');
    sheet.getRange(row, 2).setValue(formatDate(docData.dueDate));
    row++;
  }
  row++;

  // ===== 件名 =====
  sheet.getRange(row, 1).setValue(`件名: ${docData.subject}`);
  sheet.getRange(row, 1).setFontSize(12).setFontWeight('bold');
  row += 2;

  // ===== 明細テーブル =====
  const headerRow = row;
  sheet.getRange(headerRow, 1).setValue('品目');
  sheet.getRange(headerRow, 3).setValue('数量');
  sheet.getRange(headerRow, 4).setValue('単価');
  sheet.getRange(headerRow, 5).setValue('金額');

  // ヘッダー行のスタイル
  sheet.getRange(headerRow, 1, 1, 5).setBackground('#E5DDD5').setFontWeight('bold').setHorizontalAlignment('center');
  row++;

  // 明細行
  docData.lineItems.forEach(item => {
    sheet.getRange(row, 1, 1, 2).merge();
    sheet.getRange(row, 1).setValue(item.itemName);
    sheet.getRange(row, 3).setValue(item.quantity);
    sheet.getRange(row, 4).setValue(item.unitPrice).setNumberFormat('#,##0');
    sheet.getRange(row, 5).setValue(item.amount).setNumberFormat('#,##0');

    // 右寄せ
    sheet.getRange(row, 3, 1, 3).setHorizontalAlignment('right');
    row++;
  });

  row++;

  // ===== 合計 =====
  sheet.getRange(row, 4).setValue('小計:');
  sheet.getRange(row, 5).setValue(docData.subtotal).setNumberFormat('#,##0');
  sheet.getRange(row, 4, 1, 2).setFontWeight('bold').setHorizontalAlignment('right');
  row++;

  sheet.getRange(row, 4).setValue('消費税(10%):');
  sheet.getRange(row, 5).setValue(docData.tax).setNumberFormat('#,##0');
  sheet.getRange(row, 4, 1, 2).setFontWeight('bold').setHorizontalAlignment('right');
  row++;

  sheet.getRange(row, 4).setValue('合計金額:');
  sheet.getRange(row, 5).setValue(docData.total).setNumberFormat('#,##0');
  sheet.getRange(row, 4, 1, 2).setFontSize(14).setFontWeight('bold').setHorizontalAlignment('right').setBackground('#FFF9E6');
  row += 2;

  // ===== 備考 =====
  if (docData.notes) {
    sheet.getRange(row, 1).setValue(`備考: ${docData.notes}`);
    sheet.getRange(row, 1).setFontSize(10);
    row += 2;
  }

  // ===== 発行元情報 =====
  sheet.getRange(row, 1, 1, 5).merge();
  sheet.getRange(row, 1).setValue('━'.repeat(60));
  row++;

  sheet.getRange(row, 1).setValue('発行元');
  sheet.getRange(row, 1).setFontWeight('bold');
  row++;

  sheet.getRange(row, 1).setValue(companyInfo.name || '');
  sheet.getRange(row, 1).setFontSize(12).setFontWeight('bold');
  row++;

  sheet.getRange(row, 1).setValue(`〒${companyInfo.postalCode || ''} ${companyInfo.address || ''}`);
  sheet.getRange(row, 1).setFontSize(10);
  row++;

  sheet.getRange(row, 1).setValue(`TEL: ${companyInfo.phone || ''} / Email: ${companyInfo.email || ''}`);
  sheet.getRange(row, 1).setFontSize(10);
  row++;

  if (companyInfo.registrationNumber) {
    sheet.getRange(row, 1).setValue(`登録番号: ${companyInfo.registrationNumber}`);
    sheet.getRange(row, 1).setFontSize(10);
    row++;
  }

  row++;

  // ===== 振込先情報 =====
  if (companyInfo.bankName) {
    sheet.getRange(row, 1).setValue('【お振込先】');
    sheet.getRange(row, 1).setFontWeight('bold');
    row++;

    sheet.getRange(row, 1).setValue(`${companyInfo.bankName || ''} ${companyInfo.branchName || ''} ${companyInfo.accountType || ''} ${companyInfo.accountNumber || ''}`);
    sheet.getRange(row, 1).setFontSize(10);
    row++;

    sheet.getRange(row, 1).setValue(`${companyInfo.accountHolder || ''}`);
    sheet.getRange(row, 1).setFontSize(10);
    row++;
  }

  // 枠線を追加
  const dataRange = sheet.getRange(1, 1, row - 1, 5);
  dataRange.setBorder(true, true, true, true, true, true, '#000000', SpreadsheetApp.BorderStyle.SOLID);
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
  if (!amount && amount !== 0) return '¥0';
  return '¥' + Number(amount).toLocaleString('ja-JP');
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
 * PDF生成ダイアログHTML
 */
function getGeneratePDFDialogHTML() {
  return `
<!DOCTYPE html>
<html>
<head>
  <base target="_top">
  <style>
    body { font-family: 'Noto Sans JP', Arial, sans-serif; padding: 20px; background: #F5F3F0; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: 600; color: #4A4A4A; }
    select { width: 100%; padding: 8px; border: 1px solid #E5DDD5; border-radius: 2px; font-size: 14px; box-sizing: border-box; }
    .btn { background: #8B7355; color: white; border: none; padding: 10px 20px; border-radius: 2px; cursor: pointer; margin-right: 10px; }
    .btn:hover { background: #6B5335; }
    .btn-secondary { background: #E5DDD5; color: #4A4A4A; }
    #message { margin-top: 15px; padding: 10px; border-radius: 2px; display: none; }
    .success { background: #E8F5E9; color: #2E7D32; border: 1px solid #4CAF50; }
    .error { background: #FFEBEE; color: #C62828; border: 1px solid #F44336; }
    .info { background: #E3F2FD; color: #1565C0; border: 1px solid #2196F3; padding: 10px; border-radius: 2px; margin-bottom: 15px; font-size: 13px; }
  </style>
</head>
<body>
  <div class="info">
    📌 PDFシートを作成して、PDFとしてダウンロードできます。<br>
    生成されたシートは「PDF_書類番号」という名前で保存されます。<br>
    不要なシートは「🗑️ PDFシートを削除」メニューから削除できます。
  </div>

  <form id="pdfForm">
    <div class="form-group">
      <label>書類種別 *</label>
      <select id="docType" required onchange="loadDocuments()">
        <option value="">選択してください</option>
        <option value="QUOTE">見積書</option>
        <option value="DELIVERY">納品書</option>
        <option value="INVOICE">請求書</option>
        <option value="RECEIPT">領収書</option>
      </select>
    </div>

    <div class="form-group">
      <label>書類番号 *</label>
      <select id="docNumber" required>
        <option value="">まず書類種別を選択してください</option>
      </select>
    </div>

    <div>
      <button type="submit" class="btn">PDF生成</button>
      <button type="button" class="btn btn-secondary" onclick="google.script.host.close()">キャンセル</button>
    </div>

    <div id="message"></div>
  </form>

  <script>
    function loadDocuments() {
      const docType = document.getElementById('docType').value;
      if (!docType) return;

      const docNumberSelect = document.getElementById('docNumber');
      docNumberSelect.innerHTML = '<option value="">読み込み中...</option>';

      google.script.run
        .withSuccessHandler(function(docs) {
          docNumberSelect.innerHTML = '<option value="">選択してください</option>';
          docs.forEach(d => {
            const option = document.createElement('option');
            option.value = d.docNumber;
            option.textContent = d.docNumber + ' - ' + d.customerName + ' (¥' + d.total.toLocaleString() + ')';
            docNumberSelect.appendChild(option);
          });
        })
        .getDocumentList(docType);
    }

    document.getElementById('pdfForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const docNumber = document.getElementById('docNumber').value;
      const docType = document.getElementById('docType').value;

      if (!docNumber || !docType) {
        alert('書類種別と書類番号を選択してください');
        return;
      }

      const messageDiv = document.getElementById('message');
      messageDiv.textContent = 'PDF生成中...';
      messageDiv.className = 'info';
      messageDiv.style.display = 'block';

      google.script.run
        .withSuccessHandler(function(result) {
          messageDiv.className = result.success ? 'success' : 'error';
          messageDiv.innerHTML = result.message;
          if (result.pdfUrl) {
            messageDiv.innerHTML += '<br><a href="' + result.pdfUrl + '" target="_blank">PDFを開く</a>';
          }
          messageDiv.style.display = 'block';
        })
        .withFailureHandler(function(error) {
          messageDiv.className = 'error';
          messageDiv.textContent = 'エラー: ' + error.message;
          messageDiv.style.display = 'block';
        })
        .generatePDF(docNumber, docType);
    });
  </script>
</body>
</html>
  `.trim();
}

// ============================================
// テストデータ挿入
// ============================================

/**
 * テストデータを挿入（開発・テスト用）
 */
function insertTestData() {
  const ui = SpreadsheetApp.getUi();
  const response = ui.alert(
    'テストデータ挿入',
    'テストデータ（自社情報・取引先3件・見積書2件・請求書1件）を挿入しますか？',
    ui.ButtonSet.OK_CANCEL
  );

  if (response !== ui.Button.OK) return;

  try {
    const ss = getSpreadsheet();

    // 1. 自社情報を挿入
    const settingsSheet = getSheet(SHEET_NAMES.SETTINGS);
    settingsSheet.getRange('B2:B14').setValues([
      ['株式会社YOJITU'],
      ['870-0123'],
      ['大分県大分市中央町1-2-3 YOJITUビル5F'],
      ['097-123-4567'],
      ['097-123-4568'],
      ['info@yojitu.com'],
      ['T1234567890123'],
      ['大分銀行'],
      ['中央支店'],
      ['普通'],
      ['1234567'],
      ['カ）ヨジツ'],
      [''] // 印鑑画像URL
    ]);

    // 2. 取引先3件を挿入
    const customersSheet = getSheet(SHEET_NAMES.CUSTOMERS);
    const testCustomers = [
      ['C001', '株式会社ABC建設', '870-0001', '大分県大分市府内町1-1-1', '山田太郎', '097-111-1111', 'yamada@abc-const.co.jp', '定期取引先', now(), now()],
      ['C002', '有限会社XYZ商事', '870-0002', '大分県大分市荷揚町2-2-2', '佐藤花子', '097-222-2222', 'sato@xyz-trade.co.jp', '新規取引先', now(), now()],
      ['C003', '合同会社テクノロジー', '870-0003', '大分県大分市都町3-3-3', '鈴木一郎', '097-333-3333', 'suzuki@tech.co.jp', 'VIP顧客', now(), now()]
    ];
    testCustomers.forEach(customer => customersSheet.appendRow(customer));

    // 3. 見積書2件を挿入
    const quotesSheet = getSheet(SHEET_NAMES.QUOTES);
    const today = new Date();
    const dateStr = Utilities.formatDate(today, 'Asia/Tokyo', 'yyyyMMdd');

    const quote1LineItems = [
      { itemName: 'Webサイト制作', quantity: 1, unitPrice: 500000, amount: 500000 },
      { itemName: 'SEO対策', quantity: 1, unitPrice: 100000, amount: 100000 }
    ];
    const quote1Amounts = calculateAmounts(quote1LineItems);
    quotesSheet.appendRow([
      `Q-${dateStr}-001`, '作成中', 'C001', '株式会社ABC建設',
      formatDate(today), '',
      'コーポレートサイト制作のお見積り',
      stringifyLineItems(quote1LineItems),
      quote1Amounts.subtotal, quote1Amounts.tax, quote1Amounts.total,
      '納期: 2ヶ月', '初回取引', '', '', now(), now()
    ]);

    const quote2LineItems = [
      { itemName: 'LP制作', quantity: 1, unitPrice: 300000, amount: 300000 },
      { itemName: '広告運用', quantity: 3, unitPrice: 50000, amount: 150000 }
    ];
    const quote2Amounts = calculateAmounts(quote2LineItems);
    quotesSheet.appendRow([
      `Q-${dateStr}-002`, '作成中', 'C002', '有限会社XYZ商事',
      formatDate(today), '',
      'ランディングページ制作＋広告運用',
      stringifyLineItems(quote2LineItems),
      quote2Amounts.subtotal, quote2Amounts.tax, quote2Amounts.total,
      '納期: 1.5ヶ月', '', '', '', now(), now()
    ]);

    // 4. 請求書1件を挿入
    const invoicesSheet = getSheet(SHEET_NAMES.INVOICES);
    const dueDate = new Date();
    dueDate.setDate(dueDate.getDate() + 30);

    const invoice1LineItems = [
      { itemName: 'システム開発', quantity: 1, unitPrice: 800000, amount: 800000 },
      { itemName: '保守サポート（3ヶ月）', quantity: 3, unitPrice: 50000, amount: 150000 }
    ];
    const invoice1Amounts = calculateAmounts(invoice1LineItems);
    invoicesSheet.appendRow([
      `I-${dateStr}-001`, '作成中', 'C003', '合同会社テクノロジー',
      formatDate(today), formatDate(dueDate),
      '業務システム開発＋保守サポート',
      stringifyLineItems(invoice1LineItems),
      invoice1Amounts.subtotal, invoice1Amounts.tax, invoice1Amounts.total,
      '支払い期限: 月末締め翌月末払い', 'VIP顧客につき優先対応', '', '', now(), now()
    ]);

    ui.alert(
      '✅ テストデータ挿入完了',
      '以下のデータを挿入しました：\n\n' +
      '• 自社情報（株式会社YOJITU）\n' +
      '• 取引先3件（ABC建設、XYZ商事、テクノロジー）\n' +
      '• 見積書2件（Q-' + dateStr + '-001, Q-' + dateStr + '-002）\n' +
      '• 請求書1件（I-' + dateStr + '-001）\n\n' +
      'すぐに動作確認ができます！',
      ui.ButtonSet.OK
    );

  } catch (error) {
    ui.alert('エラー', `テストデータ挿入エラー：${error.message}`, ui.ButtonSet.OK);
  }
}

// ============================================
// PDFシート削除機能
// ============================================

/**
 * PDFシート削除ダイアログを表示
 */
function showDeletePDFSheetsDialog() {
  const html = HtmlService.createHtmlOutput(getDeletePDFSheetsDialogHTML())
    .setWidth(600)
    .setHeight(500);
  SpreadsheetApp.getUi().showModalDialog(html, 'PDFシートを削除');
}

/**
 * PDFシート一覧を取得
 */
function getPDFSheets() {
  const ss = getSpreadsheet();
  const sheets = ss.getSheets();
  const pdfSheets = [];

  sheets.forEach(sheet => {
    const name = sheet.getName();
    if (name.startsWith('PDF_')) {
      pdfSheets.push({
        name: name,
        sheetId: sheet.getSheetId()
      });
    }
  });

  return pdfSheets;
}

/**
 * 選択されたPDFシートを削除
 */
function deletePDFSheets(sheetNames) {
  try {
    if (!sheetNames || sheetNames.length === 0) {
      return { success: false, message: '削除するシートを選択してください' };
    }

    const ss = getSpreadsheet();
    let deletedCount = 0;

    sheetNames.forEach(sheetName => {
      const sheet = ss.getSheetByName(sheetName);
      if (sheet) {
        ss.deleteSheet(sheet);
        deletedCount++;
      }
    });

    return {
      success: true,
      message: `${deletedCount}件のPDFシートを削除しました`
    };

  } catch (error) {
    return {
      success: false,
      message: `削除エラー: ${error.message}`
    };
  }
}

/**
 * PDFシート削除ダイアログHTML
 */
function getDeletePDFSheetsDialogHTML() {
  return `
<!DOCTYPE html>
<html>
<head>
  <base target="_top">
  <style>
    body { font-family: 'Noto Sans JP', Arial, sans-serif; padding: 20px; background: #F5F3F0; }
    .info { background: #E3F2FD; color: #1565C0; border: 1px solid #2196F3; padding: 10px; border-radius: 2px; margin-bottom: 15px; font-size: 13px; }
    .sheet-list { max-height: 300px; overflow-y: auto; border: 1px solid #E5DDD5; padding: 10px; background: white; margin: 15px 0; }
    .sheet-item { padding: 8px; margin: 5px 0; background: #f9f9f9; border-radius: 2px; display: flex; align-items: center; }
    .sheet-item:hover { background: #f0f0f0; }
    .sheet-item input[type="checkbox"] { margin-right: 10px; width: 18px; height: 18px; cursor: pointer; }
    .sheet-item label { flex: 1; cursor: pointer; user-select: none; }
    .btn { background: #8B7355; color: white; border: none; padding: 10px 20px; border-radius: 2px; cursor: pointer; margin-right: 10px; font-size: 14px; }
    .btn:hover { background: #6B5335; }
    .btn-danger { background: #f44336; }
    .btn-danger:hover { background: #d32f2f; }
    .btn-secondary { background: #E5DDD5; color: #4A4A4A; }
    .btn-secondary:hover { background: #D5CDB5; }
    #message { margin-top: 15px; padding: 10px; border-radius: 2px; display: none; }
    .success { background: #E8F5E9; color: #2E7D32; border: 1px solid #4CAF50; }
    .error { background: #FFEBEE; color: #C62828; border: 1px solid #F44336; }
    .select-all { margin-bottom: 10px; padding: 8px; background: #FFF9E6; border-radius: 2px; }
    .select-all input { margin-right: 8px; }
  </style>
</head>
<body>
  <div class="info">
    📌 生成されたPDFシート（PDF_で始まるシート）を削除できます。<br>
    削除したいシートをチェックして「削除」ボタンをクリックしてください。
  </div>

  <div class="select-all">
    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this.checked)">
    <label for="selectAll">すべて選択</label>
  </div>

  <div class="sheet-list" id="sheetList">
    <p style="text-align: center; color: #999;">読み込み中...</p>
  </div>

  <div>
    <button type="button" class="btn btn-danger" onclick="deleteSelectedSheets()">🗑️ 選択したシートを削除</button>
    <button type="button" class="btn btn-secondary" onclick="google.script.host.close()">キャンセル</button>
  </div>

  <div id="message"></div>

  <script>
    // PDFシート一覧を読み込み
    google.script.run
      .withSuccessHandler(function(pdfSheets) {
        const sheetList = document.getElementById('sheetList');

        if (pdfSheets.length === 0) {
          sheetList.innerHTML = '<p style="text-align: center; color: #999;">PDFシートがありません</p>';
          return;
        }

        sheetList.innerHTML = pdfSheets.map(sheet => 
          '<div class="sheet-item">' +
          '<input type="checkbox" name="sheet" value="' + sheet.name + '" id="' + sheet.name + '">' +
          '<label for="' + sheet.name + '">' + sheet.name + '</label>' +
          '</div>'
        ).join('');
      })
      .getPDFSheets();

    // すべて選択/解除
    function toggleSelectAll(checked) {
      document.querySelectorAll('input[name="sheet"]').forEach(cb => {
        cb.checked = checked;
      });
    }

    // 選択したシートを削除
    function deleteSelectedSheets() {
      const selectedSheets = Array.from(document.querySelectorAll('input[name="sheet"]:checked'))
        .map(cb => cb.value);

      if (selectedSheets.length === 0) {
        alert('削除するシートを選択してください');
        return;
      }

      const confirmed = confirm(selectedSheets.length + '件のシートを削除しますか？\\n\\n削除されたシートは元に戻せません。');
      if (!confirmed) return;

      const messageDiv = document.getElementById('message');
      messageDiv.textContent = '削除中...';
      messageDiv.className = 'info';
      messageDiv.style.display = 'block';

      google.script.run
        .withSuccessHandler(function(result) {
          messageDiv.className = result.success ? 'success' : 'error';
          messageDiv.textContent = result.message;
          messageDiv.style.display = 'block';

          if (result.success) {
            // 一覧を再読み込み
            setTimeout(function() { location.reload(); }, 1500);
          }
        })
        .withFailureHandler(function(error) {
          messageDiv.className = 'error';
          messageDiv.textContent = 'エラー: ' + error.message;
          messageDiv.style.display = 'block';
        })
        .deletePDFSheets(selectedSheets);
    }
  </script>
</body>
</html>
  `.trim();
}
