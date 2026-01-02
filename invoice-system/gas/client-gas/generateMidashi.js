function generateMidashi() {
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
 Sugglist.push(((i+1) + ". " + Sugg[i]));
 }
 console.log(Sugglist.join('\n'));
  //QA（yahoo）を抽出し、辞書型にする
 var Qyahoo = [];
 var values = sheet.getRange("D53:D67").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Qyahoo.push(values[i][0]);
   }
 }
 console.log(Qyahoo);


 var Ayahoo = [];
 var values = sheet.getRange("E53:E67").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Ayahoo.push(values[i][0]);
   }
 }
 console.log(Ayahoo);
  var DictQAyahoo = {};
 for(var i=0; i < Qyahoo.length; i++){
   DictQAyahoo[Qyahoo[i]] = Ayahoo[i];
 }
 console.log(DictQAyahoo)


 //QA（goo）を抽出し、辞書型にする
 var Qgoo = [];
 var values = sheet.getRange("D70:D74").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Qgoo.push(values[i][0]);
   }
 }
 console.log(Qgoo);


 var Agoo = [];
 var values = sheet.getRange("E70:E74").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Agoo.push(values[i][0]);
   }
 }
 console.log(Agoo);
  var DictQAgoo = {};
 for(var i=0; i < Qgoo.length; i++){
   DictQAgoo[Qgoo[i]] = Agoo[i];
 }
 console.log(DictQAgoo);


 //検索ニーズ抽出
 var Needs = [];
 var values = sheet.getRange("D76").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Needs.push(values[i][0]);
   }
 }
 console.log(Needs);


 // タイトル候補抽出
 var Genetitle = [];
 var values = sheet.getRange("D80").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Genetitle.push(values[i][0]);
   }
 }
 console.log(Genetitle);


 // リード文抽出
 var Genelead = [];
 var values = sheet.getRange("D83").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Genelead.push(values[i][0]);
   }
 }
 console.log(Genelead);

 // 本文抽出
 var Honbun = [];
 var values = sheet.getRange("G5:G9").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Honbun.push( (i+1) + "." + values[i][0]);
   }
 }
 console.log(Honbun.join('\n'));


 var prompt =
`役割:
あなたはSEOの特化したプロのSEOライター・ウェブマーケティングのスペシャリストです。SEOに最適化した記事のコンテンツ提案を得意としています。


タスク:
あなたの強みを存分に発揮し、タイトルに対して検索エンジンの検索結果のトップに表示される魅力的なコンテンツを提案します。記事のHTML形式の見出しを{出力形式}に従って構成してください。


要件:
- h2タグとh3タグを用いること
- h2タグには必ずキーワード・関連キーワードに含まれる単語を不自然な日本語にならないように1つ以上含めること。
- h2タグは4〜6つにする。
- h3タグは2つ以上、7つ以下で、h2タグの内容を元にユーザーのニーズを解決できる最適な個数を入れる。
- h4は基本的に使用しない。必要な場合のみh4タグ以下を用いること。
- まとめの段落内にはh3以下は使用しない
- リスト形式は全てHeadingタグを用いる。
- 記事が全体で3000文字程度になるようにHeadingタグ数を逆算し構成に反映させる。
- htmlの本文だけを出力すること


構成:
- サマリー}}の内容に沿った見出し構成にすること。
- {{サマリー}}に基づき、記事が構造的に整理されるようにh2タグを構築する。
- 記事が論理的にステップを踏むようにh2タグ以下を決定する。
- 最後に必ず「まとめ」の見出しを用意すること。
- 最下層のHeadingタグから2-3文の本文が作成できる粒度まで、Headingタグを分解し最下層のHeadingタグを生成する。


プロセス:
1. キーワードと検索ニーズから検索ユーザーのペルソナを立てる。
2. タイトル・サマリーから記事の構成を立案する。
3. h2タグ・h3タグや必要に応じてh4タグ以下を用いて具体的な見出し構成を出力するが、基本的にh4タグは使用しない。


タイトル:
${Genetitle}


キーワード：
${KW}


サマリー:"""
${Genelead}"""


上位キーワード(出現回数)：
${DictKW}


関連キーワード：
${Sugglist.join('\n')}


検索ニーズ:
"${Needs} "


タイトルリスト:
${Title.join('\n')};


出力形式：
<h2>スマホの電源が入らない時の初期対応</h2>
   <h3>電源が入らない主な原因</h3>
   <h3>基本的な応急処置</h3>
       <h4>バッテリーのチェック</h4>
       <h4>充電器とケーブルの確認</h4>
       <h4>リセット方法</h4>


<h2>Androidスマホの対処法</h2>
   <h3>起動しない原因とその対処法</h3>
       <h4>ソフトウェアの問題</h4>
       <h4>ハードウェアの故障</h4>
   <h3>充電ができない場合のチェックポイント</h3>
       <h4>充電ポートの清掃</h4>
       <h4>別の充電器を試す</h4>`;


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
     // 'response_format':{ "type": "json_object" },
     'messages': messages})
 };


 //OpenAIのChatGPTにAPIリクエストを送り、結果を変数に格納
 var response = JSON.parse(UrlFetchApp.fetch(apiUrl, options).getContentText());
 console.log(response);


 var generatedText = response.choices[0].message;
 console.log(generatedText);


 // generatedTextをJSON形式に変換
 // var jsonGeneratedText = JSON.stringify(generatedText);
 // console.log(jsonGeneratedText);
  var outputCell = sheet.getRange("D85"); // 生成されたテキストを出力するセルの範囲を指定
 var content = generatedText.content;
 outputCell.setValue(content.replace(/```html/g,"").replace(/```/g,""));
 console.log(content);
}
