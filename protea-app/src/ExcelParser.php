<?php
namespace Protea;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Excelファイルパーサークラス
 * 6シート構造（ブログ記事、共起語、サジェスト、URL本文、Yahoo知恵袋、goo Q&A）をパース
 */
class ExcelParser
{
    private $spreadsheet;
    private $keyword;

    /**
     * Excelファイルを読み込み
     */
    public function load($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("ファイルが存在しません: {$filePath}");
        }

        $this->spreadsheet = IOFactory::load($filePath);

        // ファイル名からキーワードを抽出（例: 開業医_患者_来ない.xlsx → 開業医 患者 来ない）
        $filename = basename($filePath, '.xlsx');
        $this->keyword = str_replace('_', ' ', $filename);

        return $this;
    }

    /**
     * キーワードを取得
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * シート1: ブログ記事（10件）
     * 列構成: URL, タイトル
     */
    public function parseBlogArticles()
    {
        $sheet = $this->getSheetByName('ブログ記事');
        if (!$sheet) {
            throw new \Exception("シート「ブログ記事」が見つかりません");
        }

        $data = [];
        // ヘッダー行をスキップして2行目から11行目まで（10件）
        for ($row = 2; $row <= 11; $row++) {
            $url = $sheet->getCell("A{$row}")->getValue();
            $title = $sheet->getCell("B{$row}")->getValue();

            if (!empty($url)) {
                $data[] = [
                    'url' => $url,
                    'title' => $title ?: '',
                ];
            }
        }

        return $data;
    }

    /**
     * シート2: 共起語（可変、15件程度）
     * 列構成: 共起語, 出現回数
     */
    public function parseCooccurrenceWords()
    {
        $sheet = $this->getSheetByName('共起語');
        if (!$sheet) {
            throw new \Exception("シート「共起語」が見つかりません");
        }

        $data = [];
        $row = 2; // ヘッダーをスキップ
        $maxRows = 100; // 無限ループ防止

        while ($row < $maxRows) {
            $word = $sheet->getCell("A{$row}")->getValue();
            $count = $sheet->getCell("B{$row}")->getValue();

            if (empty($word)) {
                break; // 空行で終了
            }

            $data[] = [
                'word' => $word,
                'count' => intval($count ?: 0),
            ];
            $row++;
        }

        return $data;
    }

    /**
     * シート3: サジェスト（4件）
     * 列構成: クエリ, サジェスト
     */
    public function parseSuggestions()
    {
        $sheet = $this->getSheetByName('サジェスト');
        if (!$sheet) {
            throw new \Exception("シート「サジェスト」が見つかりません");
        }

        $data = [];
        // ヘッダー行をスキップして2行目から5行目まで（4件）
        for ($row = 2; $row <= 5; $row++) {
            $query = $sheet->getCell("A{$row}")->getValue();
            $suggestion = $sheet->getCell("B{$row}")->getValue();

            if (!empty($query) || !empty($suggestion)) {
                $data[] = [
                    'query' => $query ?: '',
                    'suggestion' => $suggestion ?: '',
                ];
            }
        }

        return $data;
    }

    /**
     * シート4: URL本文（5件）
     * 列構成: URL, タイトル, 本文
     */
    public function parseArticleContents()
    {
        $sheet = $this->getSheetByName('URL本文');
        if (!$sheet) {
            throw new \Exception("シート「URL本文」が見つかりません");
        }

        $data = [];
        // ヘッダー行をスキップして2行目から6行目まで（5件）
        for ($row = 2; $row <= 6; $row++) {
            $url = $sheet->getCell("A{$row}")->getValue();
            $title = $sheet->getCell("B{$row}")->getValue();
            $content = $sheet->getCell("C{$row}")->getValue();

            if (!empty($url)) {
                $data[] = [
                    'url' => $url,
                    'title' => $title ?: '',
                    'content' => $content ?: '',
                    'word_count' => mb_strlen($content ?: ''),
                ];
            }
        }

        return $data;
    }

    /**
     * シート5: Yahoo知恵袋（15件）
     * 列構成: URL, 質問, ベストアンサー
     */
    public function parseYahooQA()
    {
        $sheet = $this->getSheetByName('Yahoo知恵袋');
        if (!$sheet) {
            throw new \Exception("シート「Yahoo知恵袋」が見つかりません");
        }

        $data = [];
        // ヘッダー行をスキップして2行目から16行目まで（15件）
        for ($row = 2; $row <= 16; $row++) {
            $url = $sheet->getCell("A{$row}")->getValue();
            $question = $sheet->getCell("B{$row}")->getValue();
            $answer = $sheet->getCell("C{$row}")->getValue();

            if (!empty($url) || !empty($question)) {
                $data[] = [
                    'url' => $url ?: '',
                    'question' => $question ?: '',
                    'best_answer' => $answer ?: '',
                ];
            }
        }

        return $data;
    }

    /**
     * シート6: goo Q&A（5件）
     * 列構成: URL, 質問, ベストアンサー
     */
    public function parseGooQA()
    {
        $sheet = $this->getSheetByName('goo Q&A');
        if (!$sheet) {
            // goo Q&Aシートがない場合は空配列を返す（後方互換性）
            return [];
        }

        $data = [];
        // ヘッダー行をスキップして2行目から6行目まで（5件）
        for ($row = 2; $row <= 6; $row++) {
            $url = $sheet->getCell("A{$row}")->getValue();
            $question = $sheet->getCell("B{$row}")->getValue();
            $answer = $sheet->getCell("C{$row}")->getValue();

            if (!empty($url) || !empty($question)) {
                $data[] = [
                    'url' => $url ?: '',
                    'question' => $question ?: '',
                    'best_answer' => $answer ?: '',
                ];
            }
        }

        return $data;
    }

    /**
     * 全データをパースして返す
     */
    public function parseAll()
    {
        return [
            'keyword' => $this->keyword,
            'blog_articles' => $this->parseBlogArticles(),
            'cooccurrence_words' => $this->parseCooccurrenceWords(),
            'suggestions' => $this->parseSuggestions(),
            'article_contents' => $this->parseArticleContents(),
            'yahoo_qa' => $this->parseYahooQA(),
            'goo_qa' => $this->parseGooQA(),
        ];
    }

    /**
     * シート名でシートを取得（部分一致対応）
     */
    private function getSheetByName($name)
    {
        try {
            return $this->spreadsheet->getSheetByName($name);
        } catch (\Exception $e) {
            // シート名の部分一致を試行
            foreach ($this->spreadsheet->getSheetNames() as $sheetName) {
                if (strpos($sheetName, $name) !== false) {
                    return $this->spreadsheet->getSheetByName($sheetName);
                }
            }
            return null;
        }
    }
}
