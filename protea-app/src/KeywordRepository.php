<?php
namespace Protea;

use PDO;

/**
 * キーワードDB操作クラス
 */
class KeywordRepository
{
    private $pdo;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->pdo = $db->getPDO();
    }

    /**
     * Excelデータを一括登録（トランザクション）
     */
    public function importExcelData($data, $filename)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. キーワード登録
            $keywordId = $this->insertKeyword($data['keyword'], $filename);

            // 2. ブログ記事登録
            foreach ($data['blog_articles'] as $article) {
                $this->insertBlogArticle($keywordId, $article);
            }

            // 3. 共起語登録
            foreach ($data['cooccurrence_words'] as $word) {
                $this->insertCooccurrenceWord($keywordId, $word);
            }

            // 4. サジェスト登録
            foreach ($data['suggestions'] as $suggestion) {
                $this->insertSuggestion($keywordId, $suggestion);
            }

            // 5. 競合記事本文登録
            foreach ($data['article_contents'] as $content) {
                $this->insertArticleContent($keywordId, $content);
            }

            // 6. Yahoo知恵袋登録
            foreach ($data['yahoo_qa'] as $qa) {
                $this->insertYahooQA($keywordId, $qa);
            }

            // 7. goo Q&A登録
            foreach ($data['goo_qa'] as $qa) {
                $this->insertGooQA($keywordId, $qa);
            }

            $this->pdo->commit();

            return [
                'success' => true,
                'keyword_id' => $keywordId,
                'counts' => [
                    'blog_articles' => count($data['blog_articles']),
                    'cooccurrence_words' => count($data['cooccurrence_words']),
                    'suggestions' => count($data['suggestions']),
                    'article_contents' => count($data['article_contents']),
                    'yahoo_qa' => count($data['yahoo_qa']),
                    'goo_qa' => count($data['goo_qa']),
                ],
            ];

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * キーワード登録
     */
    private function insertKeyword($keyword, $filename)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO keywords (keyword, status, excel_filename, uploaded_at)
            VALUES (:keyword, 'スクレイピング完了', :filename, datetime('now'))
        ");

        $stmt->execute([
            'keyword' => $keyword,
            'filename' => $filename,
        ]);

        return $this->pdo->lastInsertId();
    }

    /**
     * ブログ記事登録
     */
    private function insertBlogArticle($keywordId, $data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO blog_articles (keyword_id, url, title)
            VALUES (:keyword_id, :url, :title)
        ");

        $stmt->execute([
            'keyword_id' => $keywordId,
            'url' => $data['url'],
            'title' => $data['title'],
        ]);
    }

    /**
     * 共起語登録
     */
    private function insertCooccurrenceWord($keywordId, $data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO cooccurrence_words (keyword_id, word, count)
            VALUES (:keyword_id, :word, :count)
        ");

        $stmt->execute([
            'keyword_id' => $keywordId,
            'word' => $data['word'],
            'count' => $data['count'],
        ]);
    }

    /**
     * サジェスト登録
     */
    private function insertSuggestion($keywordId, $data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO suggestions (keyword_id, query, suggestion)
            VALUES (:keyword_id, :query, :suggestion)
        ");

        $stmt->execute([
            'keyword_id' => $keywordId,
            'query' => $data['query'],
            'suggestion' => $data['suggestion'],
        ]);
    }

    /**
     * 競合記事本文登録
     */
    private function insertArticleContent($keywordId, $data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO article_contents (keyword_id, url, title, content, word_count)
            VALUES (:keyword_id, :url, :title, :content, :word_count)
        ");

        $stmt->execute([
            'keyword_id' => $keywordId,
            'url' => $data['url'],
            'title' => $data['title'],
            'content' => $data['content'],
            'word_count' => $data['word_count'],
        ]);
    }

    /**
     * Yahoo知恵袋登録
     */
    private function insertYahooQA($keywordId, $data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO yahoo_qa (keyword_id, url, question, best_answer)
            VALUES (:keyword_id, :url, :question, :best_answer)
        ");

        $stmt->execute([
            'keyword_id' => $keywordId,
            'url' => $data['url'],
            'question' => $data['question'],
            'best_answer' => $data['best_answer'],
        ]);
    }

    /**
     * goo Q&A登録
     */
    private function insertGooQA($keywordId, $data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO goo_qa (keyword_id, url, question, best_answer)
            VALUES (:keyword_id, :url, :question, :best_answer)
        ");

        $stmt->execute([
            'keyword_id' => $keywordId,
            'url' => $data['url'],
            'question' => $data['question'],
            'best_answer' => $data['best_answer'],
        ]);
    }

    /**
     * キーワード一覧取得
     */
    public function getKeywords($params = [])
    {
        $where = [];
        $bindings = [];

        // 検索条件
        if (!empty($params['keyword'])) {
            $where[] = "keyword LIKE :keyword";
            $bindings['keyword'] = '%' . $params['keyword'] . '%';
        }

        if (!empty($params['status'])) {
            $where[] = "status = :status";
            $bindings['status'] = $params['status'];
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // ページネーション
        $page = intval($params['page'] ?? 1);
        $perPage = intval($params['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        // 総件数取得
        $countSql = "SELECT COUNT(*) FROM keywords {$whereSql}";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($bindings);
        $total = $stmt->fetchColumn();

        // データ取得
        $sql = "
            SELECT * FROM keywords
            {$whereSql}
            ORDER BY updated_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    /**
     * キーワード詳細取得
     */
    public function getKeywordById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM keywords WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * 関連データ取得
     */
    public function getRelatedData($keywordId)
    {
        return [
            'blog_articles' => $this->getBlogArticles($keywordId),
            'cooccurrence_words' => $this->getCooccurrenceWords($keywordId),
            'suggestions' => $this->getSuggestions($keywordId),
            'article_contents' => $this->getArticleContents($keywordId),
            'yahoo_qa' => $this->getYahooQA($keywordId),
            'goo_qa' => $this->getGooQA($keywordId),
        ];
    }

    private function getBlogArticles($keywordId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM blog_articles WHERE keyword_id = :id ORDER BY id");
        $stmt->execute(['id' => $keywordId]);
        return $stmt->fetchAll();
    }

    private function getCooccurrenceWords($keywordId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cooccurrence_words WHERE keyword_id = :id ORDER BY count DESC");
        $stmt->execute(['id' => $keywordId]);
        return $stmt->fetchAll();
    }

    private function getSuggestions($keywordId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suggestions WHERE keyword_id = :id ORDER BY id");
        $stmt->execute(['id' => $keywordId]);
        return $stmt->fetchAll();
    }

    private function getArticleContents($keywordId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM article_contents WHERE keyword_id = :id ORDER BY id");
        $stmt->execute(['id' => $keywordId]);
        return $stmt->fetchAll();
    }

    private function getYahooQA($keywordId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM yahoo_qa WHERE keyword_id = :id ORDER BY id");
        $stmt->execute(['id' => $keywordId]);
        return $stmt->fetchAll();
    }

    private function getGooQA($keywordId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM goo_qa WHERE keyword_id = :id ORDER BY id");
        $stmt->execute(['id' => $keywordId]);
        return $stmt->fetchAll();
    }
}
