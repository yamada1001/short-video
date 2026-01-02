function unhideAllSheets() {
  var spreadsheet = SpreadsheetApp.getActiveSpreadsheet();
  var sheets = spreadsheet.getSheets();

  for (var i = 0; i < sheets.length; i++) {
    var sheet = sheets[i];
    if (sheet.isSheetHidden()) {
      sheet.showSheet();
      Logger.log('シート「' + sheet.getName() + '」を再表示しました。');
    }
  }
  // 完了メッセージを出すこともできます
  SpreadsheetApp.getUi().alert("非表示のシートをすべて再表示しました。");
}
