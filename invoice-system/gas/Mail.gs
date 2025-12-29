/**
 * メール送信処理（Gmail連携）
 */

/**
 * 請求書をメール送信
 */
function sendInvoiceByEmail(docNumber, docType) {
  try {
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

    // 取引先情報を取得
    const customer = getCustomerById(docData.customerId);

    if (!customer || !customer.email) {
      throw new Error('取引先のメールアドレスが登録されていません');
    }

    // 自社情報を取得
    const companyInfo = getCompanyInfo();

    // PDFを取得（未生成なら生成）
    let pdfUrl = docData.pdfUrl;
    if (!pdfUrl) {
      const result = generatePDF(docNumber, docType);
      if (!result.success) {
        throw new Error('PDF生成に失敗しました');
      }
      pdfUrl = result.pdfUrl;
    }

    // PDFファイルを取得
    const pdfFileId = pdfUrl.match(/\/d\/([a-zA-Z0-9_-]+)/)[1];
    const pdfFile = DriveApp.getFileById(pdfFileId);
    const pdfBlob = pdfFile.getBlob();

    // メール本文を作成
    const subject = `【${DOC_TYPES[docType].name}】${docData.subject}`;
    const body = createEmailBody(docType, docData, customer, companyInfo);

    // メール送信
    GmailApp.sendEmail(
      customer.email,
      subject,
      body,
      {
        attachments: [pdfBlob],
        name: companyInfo.name
      }
    );

    // ステータスを「送付済み」に更新
    updateDocumentStatus(docNumber, docType, '送付済み');

    return {
      success: true,
      message: `${customer.name}（${customer.email}）にメールを送信しました`
    };

  } catch (error) {
    Logger.log(`メール送信エラー: ${error.message}`);
    return {
      success: false,
      message: `メール送信エラー: ${error.message}`
    };
  }
}

/**
 * メール本文を作成
 */
function createEmailBody(docType, docData, customer, companyInfo) {
  const docTypeName = DOC_TYPES[docType].name;

  let body = `${customer.name} 御中\n\n`;
  body += `いつもお世話になっております。\n`;
  body += `${companyInfo.name}でございます。\n\n`;
  body += `${docTypeName}をお送りいたします。\n`;
  body += `ご確認のほど、よろしくお願いいたします。\n\n`;

  body += `─────────────────────\n`;
  body += `【${docTypeName}内容】\n`;
  body += `番号: ${docData.docNumber}\n`;
  body += `件名: ${docData.subject}\n`;
  body += `発行日: ${formatDate(docData.issueDate)}\n`;

  if (docType === 'INVOICE' && docData.dueDate) {
    body += `お支払期限: ${formatDate(docData.dueDate)}\n`;
  }

  body += `金額: ¥${docData.total.toLocaleString('ja-JP')}（税込）\n`;
  body += `─────────────────────\n\n`;

  if (docType === 'INVOICE') {
    body += `【お振込先】\n`;
    body += `${companyInfo.bankName} ${companyInfo.branchName}\n`;
    body += `${companyInfo.accountType} ${companyInfo.accountNumber}\n`;
    body += `${companyInfo.accountHolder}\n\n`;
  }

  if (docData.notes) {
    body += `【備考】\n`;
    body += `${docData.notes}\n\n`;
  }

  body += `─────────────────────\n`;
  body += `${companyInfo.name}\n`;
  body += `〒${companyInfo.postalCode}\n`;
  body += `${companyInfo.address}\n`;
  body += `TEL: ${companyInfo.phone}\n`;
  body += `Email: ${companyInfo.email}\n`;
  body += `─────────────────────\n`;

  return body;
}

/**
 * 書類ステータスを更新
 */
function updateDocumentStatus(docNumber, docType, newStatus) {
  const sheetName = getSheetNameByDocType(docType);
  const sheet = getSheet(sheetName);
  const lastRow = sheet.getLastRow();

  if (lastRow < 2) return;

  const data = sheet.getRange(2, 1, lastRow - 1, 1).getValues();
  const rowIndex = data.findIndex(row => row[0] === docNumber);

  if (rowIndex >= 0) {
    sheet.getRange(rowIndex + 2, 2).setValue(newStatus); // B列にステータスを記録
    sheet.getRange(rowIndex + 2, 17).setValue(now());    // Q列に更新日を記録
  }
}
