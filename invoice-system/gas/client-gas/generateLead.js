function generateLead() {
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


 var prompt = `
役割:
あなたはSEOの特化したプロのSEOライター・ウェブマーケティングのスペシャリストです。SEOに最適化した記事のコンテンツ提案を得意としています。


全体のタスク:
タスク１、タスク２の順に実行し、出力形式に従って最終的なアウトプットを出力してください


出力形式：
以下のJSON形式を埋める形で作成し、先頭と末尾のjsonを取り除いて、JSONデータの本体のみ出力してください"
{
"original": "タスク1の出力",
"revised": "タスク2の出力"
}"


タスク1：’’’
あなたの強みを存分に発揮し、タイトルに対して検索エンジンの検索結果のトップに表示される魅力的なコンテンツを提案します。コンテンツの骨格となるようにサマリーを作成してください。




要件:
- タイトルと出力が対応関係にあること。
- まとめて1つの文章として生成する。
- 構成の要素ごとに1行改行する。
- ユーザーが抱える悩みや課題を最初に問いかけること。
- ですます調の口語を用いながら検索ユーザーが記事を閲覧したくなる誘導を含める。
- 記事を読むことで得られるメリットや解決方法を簡潔に示すこと。
- 不自然な日本語にならないようにする(日本語だけでなく、文章・言い回しも含めて)こと。
- 「読めば〜解消します」という結びにしない。（例：「〇〇が気になっている人は、ぜひ参考にしてください。」などにすること。
- 「本記事では」という言い回しを使用せず、「今回の記事では」や「この記事では」といった言い回しをすること。


プロセス:
1. キーワードと検索ニーズから検索ユーザーのペルソナを立てる。
2. タイトルリストを元に、頻出の記事コンテンツを理解する。
3. QAリストの内容も含めるように、タイトルに沿ったサマリーを生成する。


構成:
1. 構築したペルソナに対して、どのような”課題”、”悩み”を持っているのかを2行程度で問いかける
2. 記事の概要として、ユーザーが解決できる課題を2行程度で提示する。
3. タイトルに沿うように、ウェブサイトに記載される具体的なコンテンツをサマリーとして箇条書きを用いながら150文字程度で生成する。


タイトル:
${Genetitle}


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


例: """
冬なのになぜか脇汗が止まらない、こんな悩みを抱えていませんか？
夏に脇汗で悩む人は非常に多いですが、実は冬の脇汗が止まらなくて悩みを抱えている方、結構多いんです。
冬は厚着をしていることもあり、ニオイが籠もってしまうという悩みも。。。


でも夏と違って冬に出てくる脇汗って暑くもないのになぜ出てくるのか分かりませんよね？
この記事を読めば冬の脇汗で悩んでいるあなたが脇汗で悩まないようになれるように分かりやすく解説しています。


具体的には冬の脇汗は以下の方法で治すことが可能です。
・汗取りパッド、汗取りインナーを使用する
・脇汗を止めるクリームを使用する
・脇汗を抑えるツボを押す


上記の方法で冬に出てしまう脇汗を簡単に止めることが可能です。
以下で詳しく解説しているので、是非参考にしてくださいね。
"""’’’


タスク2: ’’’タスク1で生成したブログのサマリーを自然な日本語に修正します。あなたの強みを存分に発揮し、Must、Shouldの優先順位に従って修正してください。


優先順位：
Must:
・意味が通る範囲で、主語は省略してください。
・接続詞と指示語を適切に使用してわかりやすい文章にしてください
・使用する単語は日常会話で使われるような平易なものにしてください。


Should:
・文の種類（疑問文、感嘆文、平叙文など）に合わせて、動詞の活用と助動詞の組み合わせが自然になるように修正してください。
・読み手が読みやすいように少しくだけた文章にしてください。
・繰り返し使われる単語や同じ意味の文章は、削除することで文章を簡潔にしてください。
’’’
`;


 console.log(prompt);
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


 // generatedTextをJSON形式に変換
 // var jsonGeneratedText = JSON.stringify(generatedText);
 // console.log(jsonGeneratedText);
  var outputCell = sheet.getRange("D83"); // 生成されたテキストを出力するセルの範囲を指定
 var content = generatedText.content;
 console.log(content);


 outputCell.setValue(JSON.parse(content).revised);
 console.log(JSON.parse(content).revised);
}
