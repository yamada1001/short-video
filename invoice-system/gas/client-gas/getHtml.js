function getHtml() {
  // スプレッドシートとシートを取得
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();

  // H5からH9までの各セルのURLを処理
  for (var row = 6; row <= 9; row++) {
    // URLを読み込む
    var url = sheet.getRange('H' + row).getValue();
    if (url !== '') { // URLが空でない場合のみ処理を行う
      // try {
        // URLからHTMLを取得
        var response = UrlFetchApp.fetch(url);
        var html = response.getContentText();
        console.log(html)

        var document = XmlService.parse(html);
        var root = document.getRootElement();

        removeElementsByClass(root, 'head');
        removeElementsByClass(root, 'footer');
        removeElementsByClass(root, 'sidebar');
        console.log(root)

        // 取得したHTMLを左隣のセルに出力
        sheet.getRange('G' + row).setValue(root);
      // } catch (e) {
      //   // エラーが発生した場合、エラーメッセージを出力
      //   sheet.getRange('G' + row).setValue('Error fetching URL: ' + e.toString());
      // }
     }
  }
}