function generateHonbun() {
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

  // 見出し抽出
 var Midashi = [];
 var values = sheet.getRange("D85").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Midashi.push(values[i][0]);
   }
 }
 var strMidashi = String(Midashi)
 console.log(strMidashi);

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
あなたの強みを存分に発揮し、検索エンジンの検索結果のトップに表示される魅力的なコンテンツを提案します。{タイトル}・{サマリー}・{見出し}を元に見出しごとに本文を生成し、HTML形式の記事を完成させてください。

要件:
- 記事の構成は{見出し}の全てのhtmlタグを使用して、変化させないこと。
- コメントなしのhtmlだけを出力
- タグに従ってインデントを行うこと
- 各タグの下には必ず<p></p>を出力すること。
- <h2>タグから<h3>タグへの移行など、タグの階層が<hn>タグから<hn+1>タグに下がる際には、タグ間に最低一文を加えること。
- <h4>タグは基本使用せず、構成的にどうしても必要な場合のみ使用すること。
- 本文とタグ（上位タグ含む）で整合性のある内容にすること。
- 本文には日本語の文章表現として適切な範囲内で{{キーワード}}・{{関連キーワード}}内の単語を不自然な日本語にならないよう適度に含めること。過度な詰め込み・文脈がおかしくなる記載やキーワード・関連キーワードをそのまま入れるのはNG(例：「スマホ バッテリー交換」をそのまま差し込むのはNG)
- 冷たい文章にならないよう温かみのある文章にすること。
- 不自然な外来語は使用しないこと。(不自然な表現がないか納品前に自己チェックしてから出力)
- 「本章」や「この章」といった言い回しを使用しないこと。加えて段落の冒頭で「ここでは〜を解説します」「ここからは〜について説明します」など同じ表現が繰り返されないように注意すること。
- リスト(ulやol)は必要以上に多用せずに、使用する際は<h2>がポイントやコツ・方法の場合「導入段落→箇条書きサマリー→橋渡し段落」の形での設置を検討すること。
- リスト(ulやol)はを作るときは見出し（h3）の文言を活かして順序を守ること。
- 列挙性が低いテーマではリスト(ulやol)を無理に入れないこと。
- 指示代名詞を使いすぎていないか確認すること。
- 読者によって文章の解釈が変わらないか確認すること。
- 同一の意味を示す単語については、以下の優先順位を採用すること。
  - キーワード>>関連キーワード>>より可読性の高い単語

出力形式："""
<h2>セクションのタイトル</h2>
  <p>この段落はセクションの説明です。</p>
  <h3>サブセクションのタイトル</h3>
    <p>この段落はセクションの説明です。</p>
    <h4>サブサブセクションのタイトル</h4>
      <p>この段落はセクションの説明です。</p>"""
    

禁止:
- 指示語
- 冗長表現
- メッセージの出力

構成: 
- {要件}を満たすことを最優先とする。
- {要件}を満たした場合は、箇条書き・表形式の導入など可読性を高める表現の導入を実行する。

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

見出し:
${strMidashi}
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
     'max_tokens' : 8192,
     'temperature' : 0.7,
     // 'response_format':{ "type": "json_object" },
     'messages': messages})
 };

 //OpenAIのChatGPTにAPIリクエストを送り、結果を変数に格納
 var response = JSON.parse(UrlFetchApp.fetch(apiUrl, options).getContentText());
 console.log("response: ", response);


 var generatedText = response.choices[0].message;
 console.log(generatedText);

  var outputCell = sheet.getRange("D87"); // 生成されたテキストを出力するセルの範囲を指定
 var content = generatedText.content;
 outputCell.setValue(content);
 console.log(content);
}