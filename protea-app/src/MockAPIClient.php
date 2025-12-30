<?php
namespace Protea;

/**
 * モックAPIクライアント（開発用）
 * 実際のGPT/Claude APIを使わずに、ダミーレスポンスを返す
 */
class MockAPIClient
{
    /**
     * タイトル生成（モック）
     */
    public function generateTitle($keyword, $competitors, $cooccurrence)
    {
        $titles = [
            "{$keyword}で悩む方へ！解決策を徹底解説",
            "{$keyword}の真実とは？専門家が語る",
            "【完全版】{$keyword}の全てを解説します",
            "{$keyword}について知っておくべき5つのこと",
            "{$keyword}を成功させる秘訣！実践ガイド",
        ];

        return $titles[array_rand($titles)];
    }

    /**
     * リード文生成（モック）
     */
    public function generateLead($keyword, $title)
    {
        return "{$keyword}について、多くの方が悩んでいらっしゃるのではないでしょうか。" .
               "この記事では、{$keyword}の基本から応用まで、分かりやすく解説していきます。" .
               "実践的な方法や具体的な事例も交えながら、あなたの課題解決をサポートします。";
    }

    /**
     * 見出し生成（モック）
     */
    public function generateHeadings($keyword, $title, $lead)
    {
        return [
            ['level' => 'h2', 'text' => "{$keyword}とは？基礎知識を解説"],
            ['level' => 'h3', 'text' => "定義と概要"],
            ['level' => 'h3', 'text' => "重要性について"],
            ['level' => 'h2', 'text' => "{$keyword}の具体的な方法"],
            ['level' => 'h3', 'text' => "ステップ1: 準備段階"],
            ['level' => 'h3', 'text' => "ステップ2: 実践方法"],
            ['level' => 'h3', 'text' => "ステップ3: 効果測定"],
            ['level' => 'h2', 'text' => "よくある質問とトラブルシューティング"],
            ['level' => 'h3', 'text' => "Q1: {$keyword}で失敗しないためには？"],
            ['level' => 'h3', 'text' => "Q2: 初心者でもできますか？"],
            ['level' => 'h2', 'text' => "まとめ"],
        ];
    }

    /**
     * 本文生成（モック）
     */
    public function generateContent($keyword, $title, $lead, $headings, $yahooQA)
    {
        $html = "<h1>{$title}</h1>\n\n";
        $html .= "<div class=\"lead\">{$lead}</div>\n\n";

        foreach ($headings as $heading) {
            $html .= "<{$heading['level']}>{$heading['text']}</{$heading['level']}>\n\n";

            // モック本文
            $html .= "<p>ここでは、{$heading['text']}について詳しく解説します。" .
                     "{$keyword}に関する重要なポイントを押さえながら、" .
                     "実践的な内容をお伝えしていきます。</p>\n\n";

            $html .= "<p>具体的には、以下のような点が重要です：</p>\n\n";
            $html .= "<ul>\n";
            $html .= "<li>ポイント1: 基本的な考え方を理解する</li>\n";
            $html .= "<li>ポイント2: 実践的なテクニックを身につける</li>\n";
            $html .= "<li>ポイント3: 継続的に改善していく</li>\n";
            $html .= "</ul>\n\n";
        }

        // Yahoo知恵袋からの回答を含める
        if (!empty($yahooQA)) {
            $html .= "<h2>実際の悩みと解決策</h2>\n\n";
            $html .= "<p>Yahoo知恵袋で寄せられた実際の質問と回答をご紹介します。</p>\n\n";

            foreach (array_slice($yahooQA, 0, 3) as $qa) {
                $html .= "<div class=\"qa-block\">\n";
                $html .= "<h4>質問</h4>\n";
                $html .= "<p>" . htmlspecialchars(mb_substr($qa['question'], 0, 200)) . "...</p>\n";
                $html .= "<h4>回答</h4>\n";
                $html .= "<p>" . htmlspecialchars(mb_substr($qa['best_answer'], 0, 300)) . "...</p>\n";
                $html .= "</div>\n\n";
            }
        }

        return $html;
    }

    /**
     * フル記事生成（全ステップ）
     */
    public function generateFullArticle($keywordData)
    {
        // 1. タイトル生成
        $title = $this->generateTitle(
            $keywordData['keyword'],
            $keywordData['competitors'],
            $keywordData['cooccurrence']
        );

        // 2. リード文生成
        $lead = $this->generateLead($keywordData['keyword'], $title);

        // 3. 見出し生成
        $headings = $this->generateHeadings($keywordData['keyword'], $title, $lead);

        // 4. 本文生成
        $content = $this->generateContent(
            $keywordData['keyword'],
            $title,
            $lead,
            $headings,
            $keywordData['yahoo_qa']
        );

        return [
            'title' => $title,
            'content' => $content,
            'word_count' => mb_strlen(strip_tags($content)),
            'model' => 'mock-gpt-4-turbo',
        ];
    }
}
