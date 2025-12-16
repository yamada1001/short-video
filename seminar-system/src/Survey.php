<?php
namespace Seminar;

/**
 * アンケート管理クラス
 */
class Survey {
    /**
     * 質問取得
     *
     * @param string $surveyType 'registration' or 'post_seminar'
     * @param int|null $seminarId セミナーID（NULLの場合は共通質問）
     * @return array
     */
    public static function getQuestions(string $surveyType, ?int $seminarId = null): array {
        $db = Database::getInstance();

        $sql = "SELECT * FROM survey_questions
                WHERE survey_type = ?
                AND (seminar_id IS NULL OR seminar_id = ?)
                ORDER BY order_index ASC";

        return $db->query($sql, [$surveyType, $seminarId]);
    }

    /**
     * ID指定で質問取得
     *
     * @param int $id
     * @return array|null
     */
    public static function getQuestionById(int $id): ?array {
        $db = Database::getInstance();

        $sql = "SELECT * FROM survey_questions WHERE id = ?";
        return $db->fetch($sql, [$id]);
    }

    /**
     * 質問作成
     *
     * @param array $data
     * @return int 作成された質問ID
     */
    public static function createQuestion(array $data): int {
        $db = Database::getInstance();

        $sql = "INSERT INTO survey_questions (
            seminar_id, survey_type, question_text, question_type,
            options, is_required, order_index
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $options = null;
        if (!empty($data['options']) && is_array($data['options'])) {
            $options = json_encode($data['options'], JSON_UNESCAPED_UNICODE);
        }

        $params = [
            $data['seminar_id'] ?? null,
            $data['survey_type'],
            $data['question_text'],
            $data['question_type'],
            $options,
            $data['is_required'] ?? 0,
            $data['order_index'] ?? 0
        ];

        $db->execute($sql, $params);
        $questionId = (int)$db->lastInsertId();

        Logger::info('Survey question created', [
            'question_id' => $questionId,
            'survey_type' => $data['survey_type']
        ]);

        return $questionId;
    }

    /**
     * 質問更新
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateQuestion(int $id, array $data): bool {
        $db = Database::getInstance();

        $sql = "UPDATE survey_questions SET
                seminar_id = ?,
                survey_type = ?,
                question_text = ?,
                question_type = ?,
                options = ?,
                is_required = ?,
                order_index = ?
                WHERE id = ?";

        $options = null;
        if (!empty($data['options']) && is_array($data['options'])) {
            $options = json_encode($data['options'], JSON_UNESCAPED_UNICODE);
        }

        $params = [
            $data['seminar_id'] ?? null,
            $data['survey_type'],
            $data['question_text'],
            $data['question_type'],
            $options,
            $data['is_required'] ?? 0,
            $data['order_index'] ?? 0,
            $id
        ];

        return $db->execute($sql, $params) > 0;
    }

    /**
     * 質問削除
     *
     * @param int $id
     * @return bool
     */
    public static function deleteQuestion(int $id): bool {
        $db = Database::getInstance();

        $sql = "DELETE FROM survey_questions WHERE id = ?";
        $result = $db->execute($sql, [$id]) > 0;

        Logger::info('Survey question deleted', ['question_id' => $id]);

        return $result;
    }

    /**
     * 回答保存
     *
     * @param int $attendeeId
     * @param int $questionId
     * @param mixed $answer
     * @return int 作成された回答ID
     */
    public static function saveAnswer(int $attendeeId, int $questionId, $answer): int {
        $db = Database::getInstance();

        // 配列の場合はJSON化
        if (is_array($answer)) {
            $answer = json_encode($answer, JSON_UNESCAPED_UNICODE);
        }

        $sql = "INSERT INTO survey_answers (attendee_id, question_id, answer_text)
                VALUES (?, ?, ?)";

        $db->execute($sql, [$attendeeId, $questionId, $answer]);
        return (int)$db->lastInsertId();
    }

    /**
     * 複数の回答を一括保存
     *
     * @param int $attendeeId
     * @param array $answers [question_id => answer_text]
     * @return bool
     */
    public static function saveAnswers(int $attendeeId, array $answers): bool {
        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            foreach ($answers as $questionId => $answer) {
                self::saveAnswer($attendeeId, $questionId, $answer);
            }

            $db->commit();

            Logger::info('Survey answers saved', [
                'attendee_id' => $attendeeId,
                'count' => count($answers)
            ]);

            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            Logger::error('Failed to save survey answers', [
                'attendee_id' => $attendeeId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * 参加者の回答取得
     *
     * @param int $attendeeId
     * @return array
     */
    public static function getAnswersByAttendee(int $attendeeId): array {
        $db = Database::getInstance();

        $sql = "SELECT sa.*, sq.question_text, sq.question_type, sq.survey_type
                FROM survey_answers sa
                LEFT JOIN survey_questions sq ON sa.question_id = sq.id
                WHERE sa.attendee_id = ?
                ORDER BY sq.survey_type, sq.order_index";

        return $db->query($sql, [$attendeeId]);
    }

    /**
     * 質問に対する全回答取得
     *
     * @param int $questionId
     * @return array
     */
    public static function getAnswersByQuestion(int $questionId): array {
        $db = Database::getInstance();

        $sql = "SELECT sa.*, a.name, a.email
                FROM survey_answers sa
                LEFT JOIN attendees a ON sa.attendee_id = a.id
                WHERE sa.question_id = ?
                ORDER BY sa.created_at DESC";

        return $db->query($sql, [$questionId]);
    }

    /**
     * アンケート回答済みかチェック
     *
     * @param int $attendeeId
     * @param string $surveyType
     * @return bool
     */
    public static function hasAnswered(int $attendeeId, string $surveyType): bool {
        $db = Database::getInstance();

        $sql = "SELECT COUNT(*) as count
                FROM survey_answers sa
                LEFT JOIN survey_questions sq ON sa.question_id = sq.id
                WHERE sa.attendee_id = ? AND sq.survey_type = ?";

        $result = $db->fetch($sql, [$attendeeId, $surveyType]);

        return (int)($result['count'] ?? 0) > 0;
    }

    /**
     * セミナーの回答統計取得（選択肢型質問用）
     *
     * @param int $seminarId
     * @param string $surveyType
     * @return array
     */
    public static function getStatistics(int $seminarId, string $surveyType): array {
        $db = Database::getInstance();

        // セミナーの全参加者取得
        $attendees = Attendee::getAll($seminarId);
        $attendeeIds = pluck($attendees, 'id');

        if (empty($attendeeIds)) {
            return [];
        }

        // 質問取得
        $questions = self::getQuestions($surveyType, $seminarId);
        $stats = [];

        foreach ($questions as $question) {
            // radio/checkbox質問のみ統計を取る
            if (!in_array($question['question_type'], ['radio', 'checkbox'])) {
                continue;
            }

            // この質問への全回答取得
            $answers = self::getAnswersByQuestion($question['id']);

            // 回答をカウント
            $counts = [];
            foreach ($answers as $answer) {
                $answerText = $answer['answer_text'];

                // JSONの場合はデコード
                if (isJson($answerText)) {
                    $answerArray = json_decode($answerText, true);
                    foreach ($answerArray as $item) {
                        $counts[$item] = ($counts[$item] ?? 0) + 1;
                    }
                } else {
                    $counts[$answerText] = ($counts[$answerText] ?? 0) + 1;
                }
            }

            $stats[$question['id']] = [
                'question' => $question,
                'counts' => $counts,
                'total' => count($answers)
            ];
        }

        return $stats;
    }
}
