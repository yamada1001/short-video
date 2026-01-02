/**
 * 指定された m (分割パーツ数) に応じて、システムプロンプト内の <Steps> タグの内容を返す
 * m = 1 の場合はステップ1～3のみ、
 * m > 1 の場合は、ステップ1～3と最終ステップ（m+2）を示し、中間は「…（中略）…」と記載します。
 */
function buildStepsForSystemPrompt(m) {
  if (m <= 1) {
    return "<Steps>\n" +
      "1. 2つの記事を比較して{HumanMade}の人間らしい記事の特徴を5個列挙してください。\n" +
      "2. 続けて、{Written}のAIで生成されている可能性が高いと判定される理由を5個列挙してください。\n" +
      "3. その後、列挙した人間らしい記事の特徴とAIで生成されている可能性が高い理由を参考に、{Guideline}を元に{OutputFormat}に沿って、headingタグは変更せず冒頭2つのh2タグ以下の内容をリライトしてください。\n" +
      "</Steps>";
  } else {
    return "<Steps>\n" +
      "1. 2つの記事を比較して{HumanMade}の人間らしい記事の特徴を5個列挙してください。\n" +
      "2. 続けて、{Written}のAIで生成されている可能性が高いと判定される理由を5個列挙してください。\n" +
      "3. その後、列挙した人間らしい記事の特徴とAIで生成されている可能性が高い理由を参考に、{Guideline}を元に{OutputFormat}に沿って、headingタグは変更せず冒頭2つのh2タグ以下の内容をリライトしてください。\n" +
      "…（中略）…\n" +
      (m + 2) + ". 同様の条件に従って、続きの2つのh2タグ以下{tags}で指定された内容を、前の内容と上手く繋がるようにリライトしてください。\n" +
      "</Steps>";
  }
}

/**
 * システムプロンプト全体（Role, Task, Description, Guideline, OutputFormat, Steps など）を返す関数
 * @param {number} m 分割パーツ数（＝splitArticleIntoParts() の戻り値の件数）を指定
 */
function buildSystemPrompt(m) {
  var prompt =
    "<Role>\n" +
    "あなたはWebメディアの編集者として、ライターが納品した記事をチェックするスペシャリストです。\n" +
    "過去にライターとして執筆も行っており、記事の作成や校正に定評があります。\n" +
    "</Role>\n" +
    "<Task>\n" +
    "ライターが納品した記事が生成AIによって生成された可能性が高いと指摘されています。\n" +
    "ライターが納品した記事を、システムプロンプト内の{Steps}に沿ってステップバイステップでチェックし、{OutputFormat}に従ってリライトしてください。\n" +
    "</Task>\n" +
    "<Description>\n" +
    "- {HumanMade}は全文人間の手で執筆された記事です。\n" +
    "- 一方、{Written}はAIで生成されたとされる記事です。\n" +
    "</Description>\n" +
    "<Guideline>\n" +
    "- 何かしらのスタンスを持ち、そのスタンスに沿った見解を含めること。\n" +
    "- 類似した構成や表現が連続しないよう、バリエーション豊かな表現を心がけること。\n" +
    "- 英語由来のカタカナ語を必要以上に多用しないこと。\n" +
    "- 文章のつながりや接続詞を積極的に用い、読みやすい文章にすること。\n" +
    "- 高度すぎる単語は使わず、ですます調で丁寧な表現を用いること。\n" +
    "- 箇条書きを極力避け、ナラティブな文章とすること。\n" +
    "</Guideline>\n" +
    "<OutputFromat>\n" +
    "- リライト後の記事は情報量を落とさず、文字数を維持し、全体で5000文字以上となること。\n" +
    "- リライトした記事は必ずコードブロック（```）で囲むこと。\n" +
    "- html形式を採用し、元の記事のheadingタグ（h2, h3, h4, …）はそのまま踏襲すること。\n" +
    "- 必要に応じて<p>タグの文章を追加してもよい。\n" +
    "</OutputFromat>\n" +
    buildStepsForSystemPrompt(m);
  return prompt;
}

/**
 * セル A2 に格納された記事全文から、<h2> タグを目安に記事を「2見出しずつ」に分割して配列で返す関数
 * ※ 導入文（<h2>以外の部分）がある場合は、最初のパーツに付加します。
 */
function splitArticleIntoParts(articleText) {
  var parts = [];
  var segments = articleText.split(/(?=<h2>)/gi);
  if (segments.length > 1 && segments[0].trim().indexOf("<h2>") !== 0) {
    segments[1] = segments[0] + segments[1];
    segments.shift();
  }
  for (var i = 0; i < segments.length; i += 2) {
    var part = segments[i];
    if (i + 1 < segments.length) {
      part += segments[i + 1];
    }
    parts.push(part);
  }
  return parts;
}

/**
 * Anthropic Messages API では、システムプロンプトはトップレベルの system パラメータとして設定し、
 * messages 配列にはユーザーメッセージのみを含む必要があります。
 * この関数では conversationMessages 配列から role が "system" のメッセージを抽出し、
 * 残りを userMessages として payload に設定します。
 */
function sendMessageToAnthropic(conversationMessages) {
  var apiKey = PropertiesService.getScriptProperties().getProperty("ANTHROPIC_API_KEY");
  if (!apiKey) {
    throw new Error("Anthropic APIキーが設定されていません。スクリプトプロパティにANTHROPIC_API_KEYを設定してください。");
  }
  
  var url = "https://api.anthropic.com/v1/messages"; // エンドポイントは /v1/messages
  
  // system プロンプトと user メッセージを分離
  var systemPrompt = "";
  var userMessages = [];
  conversationMessages.forEach(function(msg) {
    if (msg.role === "system") {
      systemPrompt = msg.content;
    } else {
      userMessages.push(msg);
    }
  });
  
  var payload = {
    model: "claude-3-7-sonnet-20250219",
    temperature: 0,
    max_tokens: 8192,
    system: systemPrompt,
    messages: userMessages
  };
  
  var options = {
    method: "post",
    contentType: "application/json",
    headers: {
      "x-api-key": apiKey,
      "anthropic-version": "2023-06-01"
    },
    payload: JSON.stringify(payload),
    muteHttpExceptions: true
  };
  
  var response = UrlFetchApp.fetch(url, options);
  var result = JSON.parse(response.getContentText());
  //　console.log("sendMessageToAnthropic raw response: " + JSON.stringify(result));
  return result.content[0].text;
}

/**
 * 指定されたテキストから、```html で囲まれた部分のみを抽出して返す関数
 */
function extractHTMLBlock(text) {
  var regex = /```(?:\w+\s*)?([\s\S]*?)```/;
  var match = text.match(regex);
  if (match && match[1]) {
    return match[1].trim();
  }
  return ""; // 該当部分がない場合は空文字を返す
}

/**
 * メイン処理：
 * ① セル A2 の記事全文を取得
 * ② 全文を対象に初回 API 呼び出し（全文リライト結果から、```html で囲まれた部分を抽出してセル B2 に出力）
 * ③ その後、記事全文を <h2> タグを目安に m 個に分割し、
 *     分割結果の 2パーツ目以降について、各パーツに対して「【ステップ X】」の指示付きで API へリライト依頼を投げる（X = インデックス＋3）
 *     → 各応答から ```html で囲まれた部分のみを抽出してシートに出力します。
 */
function processMultiTurnConversation() {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  
  // セル A2 から記事全文を取得
  var fullArticle = sheet.getRange("D89").getValue();
  
  // 記事全文から分割パーツを作成
  var parts = splitArticleIntoParts(fullArticle);
  var m = parts.length;
  console.log("記事分割数 m = " + m);
  
  // ① 初回：全文そのままでリライト依頼
  var conversationFull = [];
  conversationFull.push({
    role: "system",
    content: buildSystemPrompt(m)
  });
  conversationFull.push({
    role: "user",
    content: fullArticle
  });
  
  var responseFull = sendMessageToAnthropic(conversationFull);
  console.log("全文に対する応答:\n" + responseFull);
  // 抽出した HTML 部分をセル A5 に出力
  var htmlResponseFull = extractHTMLBlock(responseFull);
  sheet.getRange("D91").setValue(htmlResponseFull);
  sheet.getRange("E91").setValue(responseFull);
  
  // ② 2パーツ目以降：各パーツごとに「【ステップ X】」の指示付きでリライト依頼
  for (var i = 1; i < m; i++) {
    var stepNumber = i + 3;
    var conversationPart = [];
    conversationPart.push({
      role: "system",
      content: buildSystemPrompt(m)
    });
    
    var userPrompt = "【ステップ " + stepNumber + "】 以下は記事の続き部分です。前の内容と上手く繋がるようにリライトしてください。\n" + parts[i];
    conversationPart.push({
      role: "user",
      content: userPrompt
    });

    
    var responsePart = sendMessageToAnthropic(conversationPart);
    // 抽出した HTML 部分をセル A(5+i) に出力
    var htmlResponsePart = extractHTMLBlock(responsePart);
    console.log("パーツ " + (i+1) + " に対する応答:\n" + htmlResponsePart);
    sheet.getRange(91 + i, 4).setValue(htmlResponsePart);
    sheet.getRange(91 + i, 5).setValue(responsePart);
    
    Utilities.sleep(2000);
  }
}
