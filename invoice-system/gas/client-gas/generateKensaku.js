function generateKensaku() {
 //var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("（編集用）タイトル・リード文・見出し生成");
 var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();


 //対象キーワード抽出
 var KW = [];
 var values = sheet.getRange("D5:D9").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     KW.push(values[i][0]);
   }
 }
 console.log(KW);


 //上位タイトル抽出
 var Title = [];
 var values = sheet.getRange("D12:D21").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Title.push("-" + values[i][0]);
   }
 }
 console.log(Title);


 //上位出現キーワード抽出と回数を抽出する
 var JKW = [];
 var values = sheet.getRange("D24:D38").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     JKW.push(values[i][0]);
   }
 }
 console.log(JKW);


 var JKWn = [];
 var values = sheet.getRange("E24:E38").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     JKWn.push(values[i][0]);
   }
 }
 console.log(JKWn);
  var DictKW = [];
 for(var i=0; i < JKW.length; i++){
   DictKW.push(JKW[i] + "(" + JKWn[i] + ")");
 }
 console.log(DictKW)




 //サジェストキーワード抽出
 var  Sugg= [];
 var values = sheet.getRange("D41:D50").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Sugg.push(values[i][0]);
   }
 }
 console.log(Sugg);
 var Sugglist = [];
 for(var i = 0; i < Sugg.length; i++){
 Sugglist.push(((i+1) + ". " + Sugg[i].replace(/[ 　]/g, ", ")));
 }
 console.log(Sugglist.join('\n'));
 
 // QAYahooを抽出
 var QAY = [];
 var values = sheet.getRange("D53:E67").getValues();
 for (let i = 0; i < values.length; i++) {
   const q = values[i][0];
   const a = values[i][1];


   if (q && a) {
     QAY += `Q.${q}\n→${a}\n`;
   }
 }
 console.log(QAY);


 var prompt = `
役割:
あなたはSEOの特化したプロのSEOライター・ウェブマーケティングのスペシャリストです。SEOに最適化した記事作成に不可欠なペルソナのニーズ把握を得意としています。

タスク:
あなたの強みを存分に発揮し、SEO上位タイトルやQAリストから、キーワードの検索ユーザーの検索ニーズを特定してください。

変数説明:
- プロセス: タスクを実行するためのステップバイステップの実行プロセス
- キーワード: ユーザーが検索するキーワード
- 上位タイトル: キーワードに対する現状のSEO上位10件のWebタイトル。上から順にSEO順位が高い。
- 上位キーワード(出現回数): 上位タイトルに出現回数が多いキーワード。()の中の数字は出現回数。
- サジェストキーワード: 検索窓にキーワードを入力した際にキーワードに続けてサジェストキーワード。キ キーワードに追加される単語はより具体的なニーズを潜在的に示している。
- QAリスト: Yahoo!知恵袋や、教えてGoo!などの掲示板にキーワードに関連して投稿された質問と回答のリスト。

プロセス:
1. ペルソナ生成: 上位タイトル・QAリスト・サジェストキーワードから、キーワードで検索するペルソナを3つ生成する。
2. ニーズ生成: 各ペルソナに対して、キーワードに関連する上位3件のニーズ・課題・悩みを生成する。
3. 検索ニーズ生成: 1.2.で生成した合計9件のニーズ・課題・悩みをもとに、SEO効果が高い上位3件のニーズをナラティブな文章で200文字にまとめた検索ニーズを生成する。特定のペルソナには触れず、キーワードで検索したユーザーが取得したい情報としてニーズを羅列する。

キーワード：
${KW}

上位キーワード(出現回数)：
${DictKW}

サジェストキーワード：
${Sugglist.join('\n')}

上位タイトル:
${Title.join('\n')}

QAリスト:
${QAY}

出力形式：
以下のJSON形式を埋める形で作成し、先頭と末尾のjsonを取り除いて、JSONデータの本体のみ出力してください:"""
{
 "persona": "生成されたペルソナ"
 "needs": "生成されたニーズ"
 "kensaku": "生成された検索ニーズ"
}"""
`;

 console.log(prompt)


 //スクリプトプロパティに設定したOpenAIのAPIキーを取得 
 const apiKey = ScriptProperties.getProperty('SEOkey');
 //ChatGPTのAPIのエンドポイントを設定
 const apiUrl = 'https://api.openai.com/v1/chat/completions';
 //ChatGPTに投げるメッセージを設定
 const messages = [
   {'role': 'system', 'content': "You are a helpful assistant."},
   {'role': 'user', 'content': prompt},
 ];
 //OpenAIのAPIリクエストに必要なヘッダー情報を設定
 const headers = {
   'Authorization':'Bearer '+ apiKey,
   'Content-type': 'application/json',
   'X-Slack-No-Retry': 1
 };
 //オプションの設定(モデルやトークン上限、プロンプト)
 const options = {
   'muteHttpExceptions' : true,
   'headers': headers,
   'method': 'POST',
   'payload': JSON.stringify({
     'model': 'gpt-4.1',
     'max_tokens' : 4095,
     'temperature' : 0.7,
     'response_format':{ "type": "json_object" },
     'messages': messages})
 };


 //OpenAIのChatGPTにAPIリクエストを送り、結果を変数に格納
 var response = JSON.parse(UrlFetchApp.fetch(apiUrl, options).getContentText());
 console.log(response);

 var generatedText = response.choices[0].message;
 console.log(generatedText);

  var content = generatedText.content;
 console.log(content);
 var kensaku = JSON.parse(content).kensaku;
 console.log(kensaku);

 var outputCell = sheet.getRange("D76"); // 生成されたテキストを出力するセルの範囲を指定
 outputCell.setValue(kensaku);
}
