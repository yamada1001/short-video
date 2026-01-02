function generateTitles() {
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


 var prompt1 = `
役割: あなたはSEOの特化したプロのSEOライター・ウェブマーケティングのスペシャリストです。SEOに最適化したウェブサイトタイトルの提案を得意としています。

全体タスク: タスク1→タスク2の順にタスクを実行し、最終的な結果の出力形式に従って結果のみをを出力してください。

タスク1: あなたの強みを存分に発揮し、キーワードに対して検索エンジンの検索結果のトップに表示される魅力的なタイトルを作成します。Must, Should, Couldの優先度に従って出来るだけ多くの条件を満たすタイトルを10個生成してください。

優先度(Must > >Should > Could):
Must:
・日本語として自然な表現にすること。
・合計で34文字以内にすること。
・"！" または "？" で前半と後半に区切ること。
・句読点なしで終わらせること。
・キーワードを全て含めること

Should: 
・前半にキーワードを用いてユーザーの課題提起か事象を簡潔にまとめること。
・後半は検索ニーズから類推されるユーザーの具体的な課題に対応する内容にすること。
・キーワードを検索するユーザーの誰もがクリックしてしまうような内容にすること。
・例のタイトルとは異なり独創性溢れた表現を優先すること。

Could: 
・出現回数の多い関連キーワードを含めること。
・ウェブサイトの内容が簡単に類推できるようにすること。

キーワード: ${KW}
上位キーワード(出現回数):  ${DictKW}

関連キーワード:  ${Sugglist.join('\n')}
検索ニーズ: "${Needs} "

例: 
タイトル: ${Title.join('\n')}


タスク1の出力形式:
"""
{
"title": ["タイトル１","タイトル2","",""],
}
"""’’’


タスク2: タスク1で生成したtitleのリストを修正します。あなたの強みを存分に発揮し、Must、Should、Couldの優先順位に従って修正してください。


優先順位: Must>>Should>>Could
Must:
・必要な助詞を自然な形で挿入してください。


Should:
・日常会話で使用される平易な単語を使用してください。
・「その」や「この」などの指示語は削除してください
・「！」や「？」などの感嘆符の直前の文字が「〜ず」の場合は「〜ない」に変換してください
・文中の「〜ず」は「〜なくて」に変換してください


Could
・文の種類（疑問文、感嘆文、平叙文など）に合わせて、動詞の活用と助動詞の組み合わせが自然になるように修正してください。
・口語的な表現と文語的な表現が混在しないように注意してください。
・主語と述語の対応を自然にしてください


タスク2の出力形式: 以下のJSON形式を埋める形で作成し、先頭と末尾のjsonを取り除いて、JSONデータの本体のみ出力してください"
{
"タスク1のtitleの各要素": "修正後タイトル",
"タスク1のtitleの各要素": "修正後タイトル"
}


最終的な結果の出力形式:以下のJSON形式を埋める形で作成し、先頭と末尾のjsonを取り除いて、JSONデータの本体のみ出力してください"
{
"prompt1": "タスク1の出力形式",
"prompt2": "タスク2の出力形式"
}
`;
  console.log(prompt1)


 //スクリプトプロパティに設定したOpenAIのAPIキーを取得 
 const apiKey = ScriptProperties.getProperty('SEOkey');
 //ChatGPTのAPIのエンドポイントを設定
 const apiUrl = 'https://api.openai.com/v1/chat/completions';
 //ChatGPTに投げるメッセージを設定
 const messages1 = [
   {'role': 'system', 'content': "You are a helpful assistant."},
   {'role': 'user', 'content': prompt1},
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
     'max_tokens' : 4095,
     'temperature' : 0.7,
     'response_format': {
       'type': 'json_object',
       },
     'messages': messages1})
 };


 //OpenAIのChatGPTにAPIリクエストを送り、結果を変数に格納
 var response = JSON.parse(UrlFetchApp.fetch(apiUrl, options1).getContentText());
 console.log(response);


 var generatedText = response.choices[0].message;


 // generatedTextをJSON形式に変換
 // var jsonGeneratedText = JSON.stringify(generatedText);
 // console.log(jsonGeneratedText);
  // var outputCell = sheet.getRange("D66"); // 生成されたテキストを出力するセルの範囲を指定
 // outputCell.setValue(generatedText);
 console.log(generatedText);
  var content1 = generatedText.content;
 console.log(content1);
 var titles = Object.values(JSON.parse(content1).prompt2);
 console.log(titles);

 var prompt2 = `
役割:
あなたはSEOの特化したウェブマーケティングのコンサルタントです。SEOに関連するあらゆるコンテンツに対してフィードバックをすることを得意としています。

タスク:
あなたの強みを存分に発揮し、提案された候補タイトルに対して検索エンジンの検索結果のトップに表示される魅力的なタイトルを選定し出力形式に従って出力してください。
タイトルは、優先度のうち、Mustは全て満たす必要があり、Should・Couldは可能な限り多くの条件を満たしていることが望ましいです。

プロセス:
1. キーワードと検索ニーズから、検索ユーザーのペルソナと検索の具体的なユースケースを理解する。
2. タイトルリストから実際にSEO順位の高いタイトルの傾向を理解する。
3. 1.2.をもとに、候補タイトルから最適なタイトルを選定する。
4. 最後に助詞を中心に、日本語として流暢かつ読み手に読みやすいかを確認し、修正結果を出力する。

出力形式：
選定されたタイトルのみを単文で出力

優先度(Must > >Should > Could):
Must:
・日本語として表現が自然なものを選定すること。
・合計で35文字以内にすること。
・"！" または "？" で前半と後半に区切ること。
・句読点なしで終わらせること。
・キーワードを全て含めること

Should:
・前半にキーワードを用いてユーザーの課題提起か事象を簡潔にまとめること。
・後半は検索ニーズから類推されるユーザーの具体的な課題に対応する内容にすること。
・キーワードを検索するユーザーの誰もがクリックしてしまうような内容にすること。
・例のタイトルとは異なり独創性溢れた表現を優先すること。

Could:
・出現回数の多い上位キーワードを含めること。
・ウェブサイトの内容が簡単に類推できるようにすること。

キーワード：
${KW}

上位キーワード(出現回数)：
${DictKW}

関連キーワード：
${Sugglist.join('\n')}

検索ニーズ:
"${Needs} "

タイトルリスト:
${Title.join('\n')};

候補タイトル:
${titles.join('\n')}`;
  console.log(prompt2);


 //ChatGPTに投げるメッセージを設定
 const messages2 = [
   {'role': 'system', 'content': "You are a helpful assistant."},
   {'role': 'user', 'content': prompt2},
 ];
  //オプションの設定(モデルやトークン上限、プロンプト)
 const options2 = {
   'muteHttpExceptions' : true,
   'headers': headers,
   'method': 'POST',
   'payload': JSON.stringify({
     'model': 'gpt-4.1',
     'max_tokens' : 4095,
     'temperature' : 0.7,
     // 'response_format':{ "type": "json_object" },
     'messages': messages2})
 };


 //OpenAIのChatGPTにAPIリクエストを送り、結果を変数に格納
 var response2 = JSON.parse(UrlFetchApp.fetch(apiUrl, options2).getContentText());
 console.log(response2);


 var generatedTitle = response2.choices[0].message;


 // generatedTextをJSON形式に変換
 // var jsonGeneratedText = JSON.stringify(generatedText);
 // console.log(jsonGeneratedText);
  var title = generatedTitle.content;
 var outputCell = sheet.getRange("D80"); // 生成されたテキストを出力するセルの範囲を指定
 outputCell.setValue(title);
 console.log(title);
}
