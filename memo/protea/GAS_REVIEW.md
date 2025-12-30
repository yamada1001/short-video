# GASコードレビュー結果

**レビュー日**: 2025-12-30
**対象**: クライアントのProteaスプレッドシートGASコード（11ファイル）

---

## 📁 ファイル構成

```
├── appsscript.json          # プロジェクト設定
├── URLtoTitle.js            # URLからタイトル取得
├── generateTitles.js        # タイトル生成（12KB）★最重要
├── generateLead.js          # リード文生成（9.7KB）
├── generateMidashi.js       # 見出し生成（8.5KB）
├── generateHonbun.js        # 本文生成（9.3KB）★重要
├── regenerateHonbun.js      # 本文再生成（13KB）
├── generateKensaku.js       # 検索ワード生成（5.9KB）
├── rewrite.js               # リライト機能（9.3KB）★Claude API使用
├── getHtml.js               # HTML取得（1.1KB）
└── sheet.js                 # シート操作（507B）
```

---

## 🗂️ スプレッドシートデータ構造

### セル配置マップ

| セル範囲 | データ内容 | 個数 | 備考 |
|---------|-----------|------|------|
| `D5:D9` | 対象キーワード | 最大5個 | 複数キーワード対応 |
| `D12:D21` | 上位タイトル | 10個 | 競合記事タイトル |
| `D24:D38` | 共起語 | 15個 | 出現頻度の高い順 |
| `E24:E38` | 共起語の出現回数 | 15個 | 数値 |
| `D41:D50` | サジェストキーワード | 10個 | Google/Yahoo サジェスト |
| `D53:D67` | Yahoo知恵袋 質問 | 15個 | Q&A形式 |
| `E53:E67` | Yahoo知恵袋 回答 | 15個 | ベストアンサー |
| `D70:D74` | goo Q&A 質問 | 5個 | Q&A形式 |
| `E70:E74` | goo Q&A 回答 | 5個 | - |
| `D76` | 検索ニーズ | 1個 | ユーザーの検索意図 |
| `D80` | 生成タイトル | 1個 | GPT生成結果 |
| `D83` | 生成リード文 | 1個 | GPT生成結果 |
| `D85` | 生成見出し | 1個 | HTML形式（h2, h3, h4） |
| `D87` | 生成本文 | 1個 | HTML形式 |
| `D89` | リライト元記事 | 1個 | - |
| `D91〜` | リライト結果 | 複数 | h2タグごとに分割 |
| `G5:G9` | 競合記事本文 | 5個 | スクレイピング結果 |
| `H6:H9` | 競合記事URL | 4個 | - |

---

## 🤖 GPT API呼び出し仕様

### OpenAI API設定

```javascript
// APIキー取得
const apiKey = ScriptProperties.getProperty('SEOkey');

// エンドポイント
const apiUrl = 'https://api.openai.com/v1/chat/completions';

// リクエストヘッダー
const headers = {
  'Authorization': 'Bearer ' + apiKey,
  'Content-type': 'application/json',
  'X-Slack-No-Retry': 1
};

// リクエストボディ（generateTitles.js の場合）
{
  'model': 'gpt-4.1',           // ※実際は gpt-4-turbo の可能性
  'max_tokens': 4095,
  'temperature': 0.7,
  'response_format': {
    'type': 'json_object'       // JSON形式レスポンス
  },
  'messages': [
    {'role': 'system', 'content': "You are a helpful assistant."},
    {'role': 'user', 'content': prompt}
  ]
}
```

### パラメータ詳細

| パラメータ | generateTitles | generateHonbun | rewrite（Claude） |
|-----------|---------------|---------------|-------------------|
| **model** | gpt-4.1 | gpt-4.1 | claude-3-7-sonnet-20250219 |
| **max_tokens** | 4095 | 8192 | 8192 |
| **temperature** | 0.7 | 0.7 | 0 |
| **response_format** | json_object | なし | なし |

---

## 📝 プロンプト設計パターン

### 基本構造

```
<Role>
あなたは〇〇のスペシャリストです。

<Task>
△△を実行してください。

<優先度>
Must: 必須条件
Should: 推奨条件
Could: 可能であれば

<出力形式>
{具体的なフォーマット指定}
```

### generateTitles.js のプロンプト例

```javascript
var prompt1 = `
役割: あなたはSEOの特化したプロのSEOライター・ウェブマーケティングのスペシャリストです。

全体タスク: タスク1→タスク2の順にタスクを実行し、最終的な結果の出力形式に従って結果のみをを出力してください。

タスク1: キーワードに対して検索エンジンの検索結果のトップに表示される魅力的なタイトルを作成します。Must, Should, Couldの優先度に従って出来るだけ多くの条件を満たすタイトルを10個生成してください。

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

Could:
・出現回数の多い関連キーワードを含めること。

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
"""

タスク2: タスク1で生成したtitleのリストを修正します。あなたの強みを存分に発揮し、Must、Should、Couldの優先順位に従って修正してください。
（略）

最終的な結果の出力形式:以下のJSON形式を埋める形で作成し、先頭と末尾のjsonを取り除いて、JSONデータの本体のみ出力してください"
{
"prompt1": "タスク1の出力形式",
"prompt2": "タスク2の出力形式"
}
`;
```

**特徴**:
- ✅ 2段階プロンプト（生成→修正）
- ✅ Must/Should/Could の優先度明確化
- ✅ JSON形式で構造化された出力
- ✅ 競合データ（上位タイトル・共起語）を参照

---

## 🔄 記事生成フロー

```
1. generateTitles.js
   ├─ タスク1: タイトル10個生成（JSON）
   └─ タスク2: 1個選定・修正
   ↓ D80セルに出力

2. generateLead.js
   ├─ タイトル・キーワード・共起語を元にリード文生成
   └─ D83セルに出力

3. generateMidashi.js
   ├─ タイトル・リード文を元に見出し（h2/h3/h4）生成
   └─ D85セルに出力

4. generateHonbun.js
   ├─ タイトル・リード・見出しを元に本文生成（HTML形式）
   └─ D87セルに出力

5. rewrite.js（任意）
   ├─ Claude APIで人間らしくリライト
   ├─ h2タグごとに記事を分割して段階的に処理
   └─ D91〜に出力
```

---

## 🌐 Anthropic Claude API使用（rewrite.js）

```javascript
function sendMessageToAnthropic(conversationMessages) {
  var apiKey = PropertiesService.getScriptProperties().getProperty("ANTHROPIC_API_KEY");
  var url = "https://api.anthropic.com/v1/messages";

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
  return result.content[0].text;
}
```

**リライト処理**:
1. 記事全文をh2タグで分割（2見出しずつ）
2. 各パーツごとにClaude APIでリライト
3. 「人間らしい記事」の特徴を参考にAI感を消す
4. HTML形式でコードブロック（```）で出力

---

## 🎯 Webアプリ実装への示唆

### 必須機能

1. **Excelインポート** → PhpSpreadsheet
2. **GPT API統合**
   - OpenAI API（gpt-4-turbo）
   - Anthropic API（claude-3.5-sonnet）
3. **プロンプトテンプレート管理**
   - Must/Should/Could 優先度
   - 変数置換（{{keyword}}, {{cooccurrence}}, etc.）
4. **段階的記事生成**
   - タイトル → リード → 見出し → 本文
   - 各ステップで前のステップの結果を参照

### データベース設計の修正点

- ✅ 共起語は**個数不定**（15個程度を想定）
- ✅ Yahoo知恵袋は**15件**（仕様書では10件だった）
- ✅ goo Q&Aも**追加必要**（5件）
- ✅ 検索ニーズフィールド追加
- ✅ 競合記事本文は**5件**（仕様書通り）

### プロンプト設計のベストプラクティス

1. **Role（役割）を明確に**
2. **Task（タスク）を具体的に**
3. **Must/Should/Could で優先度を明示**
4. **出力形式を厳密に指定**（JSON or HTML）
5. **禁止事項を明記**
6. **段階的処理**（タスク1→タスク2）

---

## ✅ 次のステップ

1. [x] GASコードレビュー完了
2. [ ] 仕様書（specification.html）更新
   - Excelファイル構造を正確に
   - GPT API仕様を詳細化
   - プロンプトテンプレート機能を追加
3. [ ] データベーススキーマ修正
   - goo_qa テーブル追加
   - search_needs カラム追加
4. [ ] Webアプリ実装開始
