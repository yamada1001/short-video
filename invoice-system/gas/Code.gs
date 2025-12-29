/**
 * GAS版 請求書・見積書システム
 * freee形式準拠
 *
 * 【機能】
 * - 見積書・納品書・請求書・領収書の作成
 * - 見積書→納品書/請求書、納品書→請求書への変換
 * - 合算請求書作成
 * - PDF自動生成（Google Docs → PDF）
 * - Gmail連携メール送信
 */

/**
 * スプレッドシート起動時に実行（カスタムメニュー追加）
 */
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

/**
 * シート名定数
 */
const SHEET_NAMES = {
  SETTINGS: '設定',
  CUSTOMERS: '取引先マスタ',
  QUOTES: '見積書',
  DELIVERIES: '納品書',
  INVOICES: '請求書',
  RECEIPTS: '領収書',
  ITEMS: '品目マスタ'
};

/**
 * 書類種別定数
 */
const DOC_TYPES = {
  QUOTE: { prefix: 'Q', name: '見積書' },
  DELIVERY: { prefix: 'D', name: '納品書' },
  INVOICE: { prefix: 'I', name: '請求書' },
  RECEIPT: { prefix: 'R', name: '領収書' }
};
