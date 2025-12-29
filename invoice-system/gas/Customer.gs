/**
 * 取引先管理
 */

/**
 * 取引先追加ダイアログを表示
 */
function showAddCustomerDialog() {
  const html = HtmlService.createHtmlOutputFromFile('AddCustomerDialog')
    .setWidth(500)
    .setHeight(450);
  SpreadsheetApp.getUi().showModalDialog(html, '取引先を追加');
}

/**
 * 取引先を追加
 */
function addCustomer(formData) {
  try {
    const sheet = getSheet(SHEET_NAMES.CUSTOMERS);
    const lastRow = sheet.getLastRow();

    // 取引先IDを自動生成（C001, C002...）
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

    // 新しい行を追加
    const newRow = [
      newId,                              // A: 取引先ID
      formData.name,                      // B: 取引先名
      formData.postalCode || '',          // C: 郵便番号
      formData.address || '',             // D: 住所
      formData.contactPerson || '',       // E: 担当者名
      formData.phone || '',               // F: 電話番号
      formData.email || '',               // G: メールアドレス
      formData.notes || '',               // H: 備考
      now(),                              // I: 作成日
      now()                               // J: 更新日
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
