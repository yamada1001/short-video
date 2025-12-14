# SNSアカウントURL修正作業レポート

## 作業概要

提案資料ページ（`index.php`）に掲載されているSNSアカウントのURLにリンク切れがあったため、全アカウントを再確認し、正しいURLに修正しました。

## 作業日時

2025-12-09

## 修正内容

### 第1回修正（コミット: ae2b8e1）

#### TikTok URL修正
- `@onlyyouhome` → `@only_you_home`
- `@lakia_umeda` → `@lakia.umeda`
- `@kenchobi` → `@kenchobi_house`

#### YouTube URL修正
- `@yukkurifudosan` → `@YukkuriFudosan`（大文字化）
- `@fudosan-gmen` → `@gmentakishima`
- `@mofmof-investor` → `channel/UCsWTZ4nYODCwlE8rdv7DZzA`（チャンネルID）
- `@uraken-fudosan` → `@urakenfudosan`

### 第2回修正（コミット: a223ee6）

ユーザーからのフィードバックにより、以下のTikTokアカウントにリンク切れがあることが判明し、再修正を実施。

#### 修正1: LAKIA不動産
- **問題**: `@lakia.umeda`（梅田店）が非公開アカウントになっており、アクセス不可
- **修正前**: `https://www.tiktok.com/@lakia.umeda`
- **修正後**: `https://www.tiktok.com/@lakia.company`
- **理由**: 梅田店アカウントが非公開のため、公開されている本社公式アカウントに変更
- **アカウント名変更**: 「LAKIA不動産 大阪梅田店」→「LAKIA不動産」

#### 修正2: スタイリー不動産（Stylee）
- **問題**: `@kenchobi_house` が存在しないアカウント
- **修正前**: `https://www.tiktok.com/@kenchobi_house`
- **修正後**: `https://www.tiktok.com/@stylee.estate`
- **理由**: けんちょび氏が運営する「スタイリー不動産」の正式なTikTokアカウントが `@stylee.estate` であることが判明
- **アカウント名変更**: 「Kenchobi」→「スタイリー不動産（Stylee）」
- **説明文変更**: 「TikTok経由で20件以上の契約・100件以上の問い合わせ獲得」→「5ヶ月で23件の成約、オシャレな物件紹介」

## 最終確認済みURL一覧

### Instagram 採用アカウント（4件）

| アカウント名 | ハンドル | URL | ステータス |
|------------|---------|-----|----------|
| 三井不動産リアルティグループ | @mfrealty.recruit | https://www.instagram.com/mfrealty.recruit/ | ✓ 確認済み |
| 野村不動産ソリューションズ | @nomura_solutions.recruit | https://www.instagram.com/nomura_solutions.recruit/ | ✓ 確認済み |
| 近鉄不動産 | @kintechu_recruit | https://www.instagram.com/kintechu_recruit/ | ✓ 確認済み |
| コスギ不動産HD | @kosugi_recruit | https://www.instagram.com/kosugi_recruit/ | ✓ 確認済み |

### Instagram 売買・物件紹介アカウント（5件）

| アカウント名 | ハンドル | URL | ステータス |
|------------|---------|-----|----------|
| アイズルーム (I's Room) | @is_room_ | https://www.instagram.com/is_room_/ | ✓ 確認済み |
| グッドルーム (goodroom) | @goodroom_jp | https://www.instagram.com/goodroom_jp/ | ✓ 確認済み |
| 東京R不動産 | @tokyo__r | https://www.instagram.com/tokyo__r/ | ✓ 確認済み |
| Simple NAIKEN | @simplenaiken | https://www.instagram.com/simplenaiken/ | ✓ 確認済み |
| 明日の不動産 | @asunofudosan | https://www.instagram.com/asunofudosan/ | ✓ 確認済み |

### TikTok アカウント（4件）

| アカウント名 | ハンドル | URL | ステータス |
|------------|---------|-----|----------|
| ないけんぼーいず | @naikenboys | https://www.tiktok.com/@naikenboys | ✓ 確認済み |
| ONLY YOU HOME | @only_you_home | https://www.tiktok.com/@only_you_home | ✓ 確認済み |
| LAKIA不動産 | @lakia.company | https://www.tiktok.com/@lakia.company | ✓ 確認済み |
| スタイリー不動産（Stylee） | @stylee.estate | https://www.tiktok.com/@stylee.estate | ✓ 確認済み |

### YouTube アカウント（5件）

| アカウント名 | ハンドル/チャンネルID | URL | ステータス |
|------------|-------------------|-----|----------|
| ゆっくり不動産 | @YukkuriFudosan | https://www.youtube.com/@YukkuriFudosan | ✓ 確認済み |
| 不動産Gメン滝島 | @gmentakishima | https://www.youtube.com/@gmentakishima | ✓ 確認済み |
| 不動産投資の楽待 | @rakumachi | https://www.youtube.com/@rakumachi | ✓ 確認済み |
| もふもふ不動産 | UCsWTZ4nYODCwlE8rdv7DZzA | https://www.youtube.com/channel/UCsWTZ4nYODCwlE8rdv7DZzA | ✓ 確認済み |
| ウラケン不動産 | @urakenfudosan | https://www.youtube.com/@urakenfudosan | ✓ 確認済み |

## 調査方法

各アカウントについて、以下の方法でWeb検索を実施し、正確なURLを特定しました。

### 検索クエリ例
- Instagram: `"アカウント名" Instagram @`
- TikTok: `"アカウント名" TikTok アカウント`
- YouTube: `"チャンネル名" YouTube @` または `"チャンネル名" YouTube channel`

### 確認事項
1. アカウントが実在するか
2. アカウントが公開されているか
3. 正しいハンドル/チャンネルIDであるか
4. リンクが正常にアクセス可能か

## 注意事項

### もふもふ不動産（YouTube）
- @ハンドルではなく、チャンネルID（`UCsWTZ4nYODCwlE8rdv7DZzA`）を使用
- 理由: 公式な@ハンドルが確認できなかったため、確実にアクセスできるチャンネルIDを使用

### LAKIA不動産（TikTok）
- 梅田店アカウント（@lakia.umeda）は非公開のため使用不可
- 代わりに本社公式アカウント（@lakia.company）を使用

### スタイリー不動産（TikTok）
- 「Kenchobi」はけんちょび氏の個人名であり、正式なアカウント名は「スタイリー不動産（Stylee）」
- 正式なハンドルは @stylee.estate

## Git コミット履歴

### コミット1: ae2b8e1
```
Fix: すべてのSNSアカウントURLを修正

## 修正内容

### TikTok URL修正
- @onlyyouhome → @only_you_home
- @lakia_umeda → @lakia.umeda
- @kenchobi → @kenchobi_house

### YouTube URL修正
- @yukkurifudosan → @YukkuriFudosan（大文字化）
- @fudosan-gmen → @gmentakishima
- @mofmof-investor → channel ID (UCsWTZ4nYODCwlE8rdv7DZzA)
- @uraken-fudosan → @urakenfudosan
```

### コミット2: a223ee6
```
Fix: TikTok アカウントURL修正（リンク切れ対応）

## 修正内容

### 修正したTikTokアカウント

1. **LAKIA不動産**
   - 変更前: @lakia.umeda（非公開アカウントのためアクセス不可）
   - 変更後: @lakia.company（本社公式アカウント）
   - 説明: 梅田店アカウントが非公開のため、公開されている本社アカウントに変更

2. **Kenchobi → スタイリー不動産（Stylee）**
   - 変更前: @kenchobi_house（存在しないアカウント）
   - 変更後: @stylee.estate（正式アカウント）
   - 説明: けんちょび氏が運営する「スタイリー不動産」の正式なTikTokアカウント
```

## まとめ

- **総確認アカウント数**: 18件（Instagram: 9件、TikTok: 4件、YouTube: 5件）
- **修正回数**: 2回
- **最終結果**: 全18アカウントのURLが正常にアクセス可能であることを確認

すべてのSNSアカウントURLを個別にWeb検索で再確認し、正しいURLに修正しました。
