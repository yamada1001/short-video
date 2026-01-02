function regenerateHonbun() {
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


 var JKWn = [];
 var values = sheet.getRange("E24:E38").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     JKWn.push(values[i][0]);
   }
 }
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


 var Ayahoo = [];
 var values = sheet.getRange("E53:E67").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Ayahoo.push(values[i][0]);
   }
 }
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
 var Agoo = [];
 var values = sheet.getRange("E70:E74").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Agoo.push(values[i][0]);
   }
 }
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
 console.log(Midashi);

 // 本文例抽出
 var Honbun = [];
 var values = sheet.getRange("G5:G9").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Honbun.push( (i+1) + "." + values[i][0]);
   }
 }
 //console.log(Honbun.join('\n'));

 //本文抽出
 var Honbun1 = [];
 var values = sheet.getRange("D87").getValues();
 for (var i = 0; i < values.length; i++) {
   if (values[i][0]) { // 値が存在する場合
     Honbun1.push(values[i][0]);
   }
 }
 console.log(Honbun1);

 const str = String(Honbun1);

 // 正規表現を使って全ての<h2>タグを見つける
 var h2Matches = str.match(/<h2>/g) || [];
 var totalH2 = h2Matches.length;
 
 // 後半部分を開始する<h2>タグの位置を計算する
 var startSecondHalfIndex = Math.ceil(totalH2 / 2);
 
 // 指定された番号の<h2>タグを見つけるために正規表現を使用する
 var regex = new RegExp("(<h2>)", "g");
 var current = 0;
 var splitPosition = 0;
 var match;
  
 // 正規表現のexecメソッドを使用して、指定された<h2>タグの位置を特定する
 while ((match = regex.exec(str)) !== null) {
  current++;
  if (current === startSecondHalfIndex) {
    splitPosition = match.index;
    break;
  }
 }
  
  // 分割位置でHTMLを2つに分ける
  var firstPart = str.substring(0, splitPosition);
  var secondPart = str.substring(splitPosition);
  console.log(firstPart);
  console.log(secondPart);

  const strMidashi = String(Midashi);
  // 正規表現のexecメソッドを使用して、指定された<h2>タグの位置を特定する
  var regex = new RegExp("(<h2>)", "g");
  var current2 = 0;
  var splitPosition2= 0;
  var match2;
  while ((match2 = regex.exec(strMidashi)) !== null) {
    current2++;
    if (current2 === startSecondHalfIndex) {
      splitPosition2 = match2.index;
      break;
    }
  }
  
  // 分割位置でHTMLを2つに分ける
 var firstMidashi = strMidashi.substring(0, splitPosition2);
 var secondMidashi = strMidashi.substring(splitPosition2);

 console.log(firstMidashi);
 console.log(secondMidashi);



 var prompt =
`役割:
あなたはSEOの特化したプロのSEOライター・ウェブマーケティングのスペシャリストです。SEOに最適化した記事のコンテンツ提案を得意としています。

タスク:
{本文候補}の<p></p>の文章量を{例}を参照して増やし、3000字程度の記事を完成させてください。

要件:
- htmlだけを出力してください。
- 記事の構成は{見出し}の全てのhtmlタグを使用して、変化させないこと。
- タグに従ってインデントを行ってください
- 最下層のタグの下の<p></p>の文章量を自然な範囲で充実させてください。
- **<h2>直下の文章（導入文）のルール**:
  - 日本語で150文字〜200文字程度に抑えること。
  - その章で何を学ぶかを簡潔に伝え、<h3>セクションへ読者を誘導してください。
  - <h2>直下で詳細な解説を完結させないでください。
- **<h3>・<h4>内の文章のルール**:
  - ここが解説の本体です。1見出しあたり300〜350文字程度を目安に詳しく記述してください。
  - <h3>内に<h4>がある場合は、<h3>直下は150文字程度の導入とし、<h4>で詳細を記述してください。
- 箇条書き（ul/ol）や表（table）は、原則として<h3>以下の詳細解説部分で使用してください。
- 本文には日本語の文章表現として適切な範囲内で{{キーワード}}・{{関連キーワード}}内の単語を不自然な日本語にならないよう適度に含めること。過度な詰め込み・文脈がおかしくなる記載やキーワード・関連キーワードをそのまま入れるのはNG(例：「スマホ バッテリー交換」をそのまま差し込むのはNG)
- <h2>はあくまで、その段落の概要を簡潔に伝える役割です。
- <h2>直下には必要であれば<h3>への橋渡し(「ここでは〜〜〜〜解説していきます。」や「ここからは〜〜〜〜紹介していきます。」など)をつけること。
- <h3>や<h4>内にて詳細を解説していきます。
- 冷たい文章にならないよう温かみのある文章にすること。
- 不自然な外来語は使用しないこと。(不自然な表現がないか納品前に自己チェックしてから出力)
- 「本章」や「この章」といった言い回しを使用しないこと。加えて段落の冒頭で「ここでは〜を解説します」「ここからは〜について説明します」など同じ表現が繰り返されないように注意すること。
- リスト(ulやol)を使用する際は<h2>がポイントやコツ・方法の場合「導入段落→箇条書きサマリー→橋渡し段落」の形での設置を検討すること。
- リスト(ulやol)はを作るときは見出し（h3）の文言を活かして順序を守ること。
- 列挙性が低いテーマではリスト(ulやol)を無理に入れないこと。
- 指示代名詞を使いすぎていないか確認すること。
- 読者によって文章の解釈が変わらないか確認すること。
- 同一の意味を示す単語については、以下の優先順位を採用すること。
  - キーワード>>関連キーワード>>より可読性の高い単語


禁止:
- 指示語
- 冗長表現
- メッセージの出力

構成: 
- {要件}を満たすことを最優先とする。
- {要件}を満たした場合は、箇条書き・表形式の導入など可読性を高める表現の導入を実行する。

出力形式："""
<h2>セクションのタイトル</h2>
  <p>ここには150-200文字程度の導入文を記述。h3へ繋げます。必要に応じて<ul>等を使用。</p>
  <h3>サブセクションのタイトル</h3>
    <p>ここには詳細な解説を300~350文字ほどで記述。</p>
    <h4>サブサブセクションのタイトル</h4>
      <p>この段落はセクションの説明です。</p>"""

見出し:
${firstMidashi}

キーワード：
${KW}

関連キーワード：
${Sugglist.join('\n')}

本文候補：
${firstPart}

例：
${Honbun.join('\n')};
`;


 //console.log(prompt)


 //スクリプトプロパティに設定したOpenAIのAPIキーを取得 
 const apiKey = ScriptProperties.getProperty('SEOkey');
 //ChatGPTのAPIのエンドポイントを設定
 const apiUrl = 'https://api.openai.com/v1/chat/completions';
 //ChatGPTに投げるメッセージを設定
 const messages1 = [
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
 const options1 = {
   'muteHttpExceptions' : true,
   'headers': headers,
   'method': 'POST',
   'payload': JSON.stringify({
     'model': 'gpt-4.1',
     'max_tokens' : 8192,
     'temperature' : 0.7,
     //'response_format':{ "type": "json_object" },
     'messages': messages1})
 };


 //OpenAIのChatGPTにAPIリクエストを送り、結果を変数に格納
 console.log("start generating fitst half.");
 var response1 = JSON.parse(UrlFetchApp.fetch(apiUrl, options1).getContentText());
 console.log(response1);

 var generatedText1 = response1.choices[0].message;
 console.log(generatedText1);

 var content1 = generatedText1.content;
 console.log(content1);
 console.log(typeof(content1));
 var content1str = JSON.stringify(content1);

 var prompt2 =
`タスク：
本文候補２に対して先ほどと同様の処理をしてください

見出し:
${secondMidashi}

キーワード：
${KW}

関連キーワード：
${Sugglist.join('\n')}

本文候補2：
${secondPart}

例：
${Honbun.join('\n')};
`;

 //console.log(prompt2)

 const messages2 = [
   {'role': 'system', 'content': "You are a helpful assistant."},
   {'role': 'user', 'content': prompt},
   {'role':'assistant','content':content1},
   {'role': 'user', 'content': prompt2},
 ];

 const options2 = {
   'muteHttpExceptions' : true,
   'headers': headers,
   'method': 'POST',
   'payload': JSON.stringify({
     'model': 'gpt-4.1',
     'max_tokens' : 8192,
     'temperature' : 0.7,
     //'response_format':{ "type": "json_object" },
     'messages': messages2})
 };

 var response2 = JSON.parse(UrlFetchApp.fetch(apiUrl, options2).getContentText());
 console.log(response2);

 var generatedText2 = response2.choices[0].message;
 console.log(generatedText2);
 
 var outputCell = sheet.getRange("D89"); // 生成されたテキストを出力するセルの範囲を指定
 var content2 = generatedText2.content;
 var finaloutput = content1 + '\n' + content2;
// var finaloutput = output.replace(/```/g, "").replace(/html/g, "");
 outputCell.setValue(finaloutput);
 console.log(finaloutput);
}
