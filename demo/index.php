<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Demo Site - YOJITU.COM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans JP', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 60px;
        }

        h1 {
            color: #ffffff;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
            font-weight: 400;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .project-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .project-status {
            display: inline-block;
            padding: 6px 14px;
            background: #10b981;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .project-status.in-progress {
            background: #f59e0b;
        }

        .project-status.completed {
            background: #6366f1;
        }

        h2 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .project-meta {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .project-meta span {
            margin-right: 16px;
        }

        .project-description {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .project-link {
            display: inline-block;
            padding: 12px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .project-link:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .empty-state {
            background: #ffffff;
            border-radius: 16px;
            padding: 80px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 24px;
        }

        .empty-state h3 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
        }

        footer {
            text-align: center;
            margin-top: 60px;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .footer-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 36px;
            }

            .projects-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .project-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🚀 Demo Site</h1>
            <p class="subtitle">クライアント様向けプレビューサイト一覧</p>
        </header>

        <div class="projects-grid">
            <?php
            // demo/ ディレクトリ内のプロジェクトを取得
            $projects = [];
            $dir = __DIR__;

            if ($handle = opendir($dir)) {
                while (false !== ($entry = readdir($handle))) {
                    // _template、隠しファイル、カレントディレクトリを除外
                    if ($entry != "." && $entry != ".." && $entry != "_template" && substr($entry, 0, 1) != "." && is_dir($dir . '/' . $entry)) {
                        $projectPath = $dir . '/' . $entry;
                        $clientInfoPath = $projectPath . '/CLIENT_INFO.md';

                        // プロジェクト情報を収集
                        $project = [
                            'slug' => $entry,
                            'name' => ucwords(str_replace('-', ' ', $entry)),
                            'status' => 'in-progress',
                            'created' => date('Y-m-d', filectime($projectPath)),
                            'description' => 'クライアント様向けデモサイト'
                        ];

                        // CLIENT_INFO.md が存在する場合、情報を抽出
                        if (file_exists($clientInfoPath)) {
                            $content = file_get_contents($clientInfoPath);

                            // 屋号を抽出
                            if (preg_match('/\*\*屋号\*\*:\s*(.+)/', $content, $matches)) {
                                $project['name'] = trim($matches[1]);
                            }

                            // 事業内容を抽出
                            if (preg_match('/\*\*事業内容\*\*:\s*(.+?)(?=\n-|\n\n|$)/s', $content, $matches)) {
                                $businessContent = trim($matches[1]);
                                $businessLines = array_filter(array_map('trim', explode("\n", $businessContent)));
                                if (!empty($businessLines)) {
                                    $project['description'] = implode('、', array_slice($businessLines, 0, 3));
                                }
                            }

                            // ステータスを抽出
                            if (preg_match('/\*\*ステータス\*\*:\s*(.+)/', $content, $matches)) {
                                $status = trim($matches[1]);
                                if (strpos($status, '完成') !== false) {
                                    $project['status'] = 'completed';
                                } elseif (strpos($status, '制作中') !== false) {
                                    $project['status'] = 'in-progress';
                                } else {
                                    $project['status'] = 'planning';
                                }
                            }
                        }

                        $projects[] = $project;
                    }
                }
                closedir($handle);
            }

            // プロジェクトを作成日でソート（新しい順）
            usort($projects, function($a, $b) {
                return strcmp($b['created'], $a['created']);
            });

            // プロジェクト表示
            if (empty($projects)) {
                echo '<div class="empty-state">';
                echo '<div class="empty-state-icon">📭</div>';
                echo '<h3>プロジェクトがまだありません</h3>';
                echo '<p>新しい案件を追加すると、ここに表示されます。</p>';
                echo '</div>';
            } else {
                foreach ($projects as $project) {
                    $statusLabel = '制作準備中';
                    $statusClass = 'planning';

                    if ($project['status'] === 'in-progress') {
                        $statusLabel = '制作中';
                        $statusClass = 'in-progress';
                    } elseif ($project['status'] === 'completed') {
                        $statusLabel = '完成';
                        $statusClass = 'completed';
                    }

                    echo '<div class="project-card">';
                    echo '<span class="project-status ' . $statusClass . '">' . $statusLabel . '</span>';
                    echo '<h2>' . htmlspecialchars($project['name']) . '</h2>';
                    echo '<div class="project-meta">';
                    echo '<span>📅 作成日: ' . $project['created'] . '</span>';
                    echo '</div>';
                    echo '<p class="project-description">' . htmlspecialchars($project['description']) . '</p>';
                    echo '<a href="' . $project['slug'] . '/" class="project-link">プレビューを見る →</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>

        <footer>
            <p class="footer-text">
                Powered by <a href="https://www.yojitu.com" class="footer-link" target="_blank">YOJITU.COM</a>
            </p>
        </footer>
    </div>
</body>
</html>
