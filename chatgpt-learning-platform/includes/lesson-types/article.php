<?php
/**
 * Article Lesson Type Display
 *
 * Displays article-style lesson content with:
 * - Markdown-like syntax parsing (headings, paragraphs, code blocks)
 * - Auto-generated table of contents from h2/h3 headings
 * - Copy-able prompt boxes
 * - References section
 * - Mobile-first responsive design
 * - Reading time display
 * - Learning objectives
 *
 * Expected JSON structure in $content:
 * {
 *   "title": "Lesson Title",
 *   "reading_time": 15,
 *   "learning_objectives": ["objective 1", "objective 2"],
 *   "content": "Markdown-style content with ## headings",
 *   "references": [
 *     {"title": "Site name", "url": "https://...", "description": "..."}
 *   ],
 *   "prompts": [
 *     {"title": "Prompt title", "content": "Prompt text", "use_case": "Usage example"}
 *   ]
 * }
 */

// Ensure $content is an array and has required fields
if (!is_array($content)) {
    $content = json_decode($content, true) ?? [];
}

$title = $content['title'] ?? 'Article Lesson';
$reading_time = $content['reading_time'] ?? 10;
$learning_objectives = $content['learning_objectives'] ?? [];
$article_content = $content['content'] ?? '';
$references = $content['references'] ?? [];
$prompts = $content['prompts'] ?? [];

// Parse markdown-like content and extract headings
$parsed_content = parse_article_markdown($article_content);
$html_content = $parsed_content['html'];
$headings = $parsed_content['headings'];
?>

<div class="article-lesson-container">
    <!-- Header Section -->
    <header class="article-header">
        <div class="article-header-content">
            <h1 class="article-title"><?php echo esc_html($title); ?></h1>

            <!-- Meta Information -->
            <div class="article-meta">
                <span class="reading-time">
                    <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Reading time: <?php echo esc_html($reading_time); ?> min
                </span>
            </div>
        </div>
    </header>

    <div class="article-main-content">
        <!-- Sidebar (Mobile: below header, Desktop: fixed left) -->
        <aside class="article-sidebar">
            <!-- Learning Objectives -->
            <?php if (!empty($learning_objectives)): ?>
            <div class="learning-objectives-box">
                <h3 class="sidebar-title">Learning Objectives</h3>
                <ul class="objectives-list">
                    <?php foreach ($learning_objectives as $objective): ?>
                    <li><?php echo esc_html($objective); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Table of Contents -->
            <?php if (!empty($headings)): ?>
            <nav class="table-of-contents">
                <h3 class="sidebar-title">Table of Contents</h3>
                <ul class="toc-list">
                    <?php foreach ($headings as $heading): ?>
                    <li class="toc-item toc-level-<?php echo intval($heading['level']); ?>">
                        <a href="#<?php echo esc_attr($heading['id']); ?>" class="toc-link">
                            <?php echo esc_html($heading['text']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </aside>

        <!-- Main Article Content -->
        <main class="article-content">
            <!-- Article Body -->
            <div class="article-body">
                <?php echo $html_content; // Already safely processed in parse function ?>
            </div>

            <!-- Prompts Section -->
            <?php if (!empty($prompts)): ?>
            <section class="prompts-section">
                <h2 class="section-title">ChatGPT Prompts</h2>
                <div class="prompts-grid">
                    <?php foreach ($prompts as $index => $prompt): ?>
                    <div class="prompt-card">
                        <div class="prompt-header">
                            <h4 class="prompt-title"><?php echo esc_html($prompt['title'] ?? 'Prompt ' . ($index + 1)); ?></h4>
                            <button class="copy-button" data-prompt-id="prompt-<?php echo $index; ?>" title="Copy prompt to clipboard">
                                <svg class="copy-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                </svg>
                                <span class="copy-text">Copy</span>
                            </button>
                        </div>

                        <div class="prompt-content" id="prompt-<?php echo $index; ?>">
                            <?php echo esc_html($prompt['content'] ?? ''); ?>
                        </div>

                        <?php if (!empty($prompt['use_case'])): ?>
                        <div class="prompt-use-case">
                            <strong>Use case:</strong> <?php echo esc_html($prompt['use_case']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- References Section -->
            <?php if (!empty($references)): ?>
            <section class="references-section">
                <h2 class="section-title">References</h2>
                <ul class="references-list">
                    <?php foreach ($references as $reference): ?>
                    <li class="reference-item">
                        <a href="<?php echo esc_url($reference['url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer" class="reference-link">
                            <strong><?php echo esc_html($reference['title'] ?? 'Reference'); ?></strong>
                        </a>
                        <?php if (!empty($reference['description'])): ?>
                        <p class="reference-description"><?php echo esc_html($reference['description']); ?></p>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Styles -->
<style>
    /* ========================================
       Article Lesson - Mobile-First Responsive
       ======================================== */

    .article-lesson-container {
        max-width: 1400px;
        margin: 0 auto;
        background-color: #fff;
    }

    /* Header Section */
    .article-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 2rem 1.5rem;
        background-color: #f9fafb;
    }

    .article-header-content {
        max-width: 1000px;
        margin: 0 auto;
    }

    .article-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 1rem 0;
        line-height: 1.3;
    }

    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .reading-time,
    .article-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .icon {
        color: #9ca3af;
    }

    /* Main Layout */
    .article-main-content {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        padding: 2rem 1.5rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Sidebar */
    .article-sidebar {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        order: -1;
    }

    .sidebar-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 1rem 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Learning Objectives */
    .learning-objectives-box {
        background-color: #ecf0ff;
        border-left: 4px solid #3b82f6;
        padding: 1.5rem;
        border-radius: 0.375rem;
    }

    .objectives-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .objectives-list li {
        padding: 0.5rem 0;
        padding-left: 1.5rem;
        position: relative;
        color: #374151;
        font-size: 0.9375rem;
        line-height: 1.5;
    }

    .objectives-list li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #3b82f6;
        font-weight: bold;
    }

    /* Table of Contents */
    .table-of-contents {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 1.5rem;
        border-radius: 0.375rem;
        max-height: 400px;
        overflow-y: auto;
    }

    .toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .toc-item {
        margin: 0;
    }

    .toc-item.toc-level-2 {
        margin-bottom: 0.75rem;
    }

    .toc-item.toc-level-3 {
        margin-bottom: 0.5rem;
        padding-left: 1.5rem;
    }

    .toc-link {
        font-size: 0.875rem;
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s;
        line-height: 1.4;
        display: block;
        padding: 0.25rem 0;
    }

    .toc-link:hover {
        color: #1e40af;
        text-decoration: underline;
    }

    /* Article Content */
    .article-content {
        min-width: 0;
    }

    .article-body {
        line-height: 1.75;
        color: #374151;
        font-size: 1rem;
        word-break: break-word;
    }

    /* Markdown Converted Content */
    .article-body h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 2.5rem 0 1rem 0;
        padding-top: 1rem;
        border-top: 2px solid #e5e7eb;
        scroll-margin-top: 80px;
    }

    .article-body h2:first-child {
        border-top: none;
        padding-top: 0;
        margin-top: 0;
    }

    .article-body h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin: 1.75rem 0 0.75rem 0;
        scroll-margin-top: 80px;
    }

    .article-body p {
        margin: 0 0 1rem 0;
    }

    .article-body p:last-child {
        margin-bottom: 0;
    }

    .article-body ul,
    .article-body ol {
        margin: 1rem 0;
        padding-left: 1.75rem;
    }

    .article-body ul li,
    .article-body ol li {
        margin: 0.5rem 0;
        color: #374151;
    }

    /* Code Blocks */
    .code-block-wrapper {
        background-color: #1f2937;
        border-radius: 0.375rem;
        margin: 1.5rem 0;
        overflow: hidden;
        border: 1px solid #374151;
    }

    .code-block-header {
        background-color: #111827;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        color: #9ca3af;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .code-block-copy-btn {
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .code-block-copy-btn:hover {
        color: #fff;
    }

    .code-block-content {
        padding: 1rem;
        overflow-x: auto;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #e5e7eb;
    }

    /* Prompts Section */
    .prompts-section {
        margin-top: 3rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 1.5rem 0;
        border-top: 2px solid #e5e7eb;
        padding-top: 1rem;
    }

    .prompts-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .prompt-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
        background-color: #fafafa;
        transition: box-shadow 0.2s, border-color 0.2s;
    }

    .prompt-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .prompt-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .prompt-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
        flex: 1;
    }

    .copy-button {
        background-color: #fff;
        border: 1px solid #d1d5db;
        color: #6b7280;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        white-space: nowrap;
        font-weight: 500;
    }

    .copy-button:hover {
        background-color: #f9fafb;
        border-color: #9ca3af;
        color: #374151;
    }

    .copy-button:active {
        background-color: #f3f4f6;
    }

    .copy-button.copied {
        background-color: #dcfce7;
        border-color: #86efac;
        color: #166534;
    }

    .copy-icon {
        width: 16px;
        height: 16px;
    }

    .prompt-content {
        background-color: #fff;
        padding: 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.9375rem;
        line-height: 1.6;
        color: #1f2937;
        white-space: pre-wrap;
        word-wrap: break-word;
        margin-bottom: 1rem;
    }

    .prompt-use-case {
        font-size: 0.875rem;
        color: #6b7280;
        padding: 0.75rem;
        background-color: #f9fafb;
        border-radius: 0.375rem;
    }

    .prompt-use-case strong {
        color: #374151;
    }

    /* References Section */
    .references-section {
        margin-top: 3rem;
    }

    .references-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .reference-item {
        padding: 1rem;
        border-left: 3px solid #e5e7eb;
        margin-bottom: 1rem;
        transition: border-color 0.2s, background-color 0.2s;
    }

    .reference-item:hover {
        border-left-color: #3b82f6;
        background-color: #f9fafb;
    }

    .reference-link {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s;
    }

    .reference-link:hover {
        color: #1e40af;
        text-decoration: underline;
    }

    .reference-link strong {
        font-weight: 600;
    }

    .reference-description {
        margin: 0.5rem 0 0 0;
        font-size: 0.9375rem;
        color: #6b7280;
        line-height: 1.5;
    }

    /* ========================================
       Tablet: Sidebar Layout
       ======================================== */

    @media (min-width: 768px) {
        .article-header {
            padding: 3rem 2rem;
        }

        .article-header-content {
            max-width: 1200px;
        }

        .article-title {
            font-size: 2.25rem;
        }

        .article-main-content {
            grid-template-columns: 280px 1fr;
            gap: 2.5rem;
            padding: 2.5rem 2rem;
        }

        .article-sidebar {
            order: 0;
            position: sticky;
            top: 20px;
            height: fit-content;
            max-height: calc(100vh - 40px);
        }

        .prompts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* ========================================
       Desktop: Wider Layout
       ======================================== */

    @media (min-width: 1024px) {
        .article-main-content {
            grid-template-columns: 320px 1fr;
            gap: 3rem;
        }

        .article-title {
            font-size: 2.5rem;
        }

        .prompts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1280px) {
        .article-header-content,
        .article-main-content {
            max-width: 1200px;
        }

        .prompts-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* ========================================
       Print Styles
       ======================================== */

    @media print {
        .article-sidebar {
            display: none;
        }

        .copy-button {
            display: none;
        }

        .code-block-header {
            display: none;
        }

        .article-main-content {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Script: Copy to Clipboard Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Copy prompt functionality
        const copyButtons = document.querySelectorAll('.copy-button');
        copyButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const promptId = this.getAttribute('data-prompt-id');
                const promptElement = document.getElementById(promptId);
                const promptText = promptElement.textContent;

                // Copy to clipboard
                navigator.clipboard.writeText(promptText).then(() => {
                    // Visual feedback
                    const originalText = this.querySelector('.copy-text').textContent;
                    this.classList.add('copied');
                    this.querySelector('.copy-text').textContent = 'Copied!';

                    setTimeout(() => {
                        this.classList.remove('copied');
                        this.querySelector('.copy-text').textContent = originalText;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    alert('Failed to copy prompt. Please try again.');
                });
            });
        });

        // Code block copy functionality
        const codeBlockCopyButtons = document.querySelectorAll('.code-block-copy-btn');
        codeBlockCopyButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const codeBlock = this.closest('.code-block-wrapper').querySelector('.code-block-content');
                const codeText = codeBlock.textContent;

                navigator.clipboard.writeText(codeText).then(() => {
                    const originalText = this.textContent;
                    this.textContent = 'Copied!';

                    setTimeout(() => {
                        this.textContent = originalText;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            });
        });
    });
</script>

<?php
/**
 * Parse article content with markdown-like syntax
 *
 * Converts markdown-like syntax to HTML:
 * - ## Text -> h2 heading
 * - ### Text -> h3 heading
 * - ``` code ``` -> code block
 * - Blank lines -> paragraphs
 *
 * @param string $content Raw markdown-like content
 * @return array Array with 'html' and 'headings' keys
 */
function parse_article_markdown($content) {
    $lines = explode("\n", $content);
    $html = '';
    $headings = [];
    $in_code_block = false;
    $code_block_content = '';
    $code_block_index = 0;
    $current_paragraph = '';

    foreach ($lines as $line) {
        // Check for code block markers
        if (strpos(trim($line), '```') === 0) {
            if ($in_code_block) {
                // Ending code block
                $in_code_block = false;
                $html .= render_code_block($code_block_content, $code_block_index);
                $code_block_index++;
                $code_block_content = '';
            } else {
                // Starting code block - flush any pending paragraph
                if (!empty($current_paragraph)) {
                    $html .= '<p>' . wp_kses_post($current_paragraph) . '</p>';
                    $current_paragraph = '';
                }
                $in_code_block = true;
            }
            continue;
        }

        // If in code block, accumulate lines
        if ($in_code_block) {
            $code_block_content .= $line . "\n";
            continue;
        }

        // Check for h2 headings
        if (strpos(trim($line), '##') === 0 && strpos(trim($line), '###') !== 0) {
            // Flush pending paragraph
            if (!empty($current_paragraph)) {
                $html .= '<p>' . wp_kses_post($current_paragraph) . '</p>';
                $current_paragraph = '';
            }

            $heading_text = trim(substr(trim($line), 2));
            $heading_id = sanitize_title_with_dashes($heading_text);
            $html .= '<h2 id="' . esc_attr($heading_id) . '">' . esc_html($heading_text) . '</h2>';

            $headings[] = [
                'level' => 2,
                'text' => $heading_text,
                'id' => $heading_id,
            ];
            continue;
        }

        // Check for h3 headings
        if (strpos(trim($line), '###') === 0) {
            // Flush pending paragraph
            if (!empty($current_paragraph)) {
                $html .= '<p>' . wp_kses_post($current_paragraph) . '</p>';
                $current_paragraph = '';
            }

            $heading_text = trim(substr(trim($line), 3));
            $heading_id = sanitize_title_with_dashes($heading_text);
            $html .= '<h3 id="' . esc_attr($heading_id) . '">' . esc_html($heading_text) . '</h3>';

            $headings[] = [
                'level' => 3,
                'text' => $heading_text,
                'id' => $heading_id,
            ];
            continue;
        }

        // Check for blank lines
        if (trim($line) === '') {
            if (!empty($current_paragraph)) {
                $html .= '<p>' . wp_kses_post($current_paragraph) . '</p>';
                $current_paragraph = '';
            }
            continue;
        }

        // Accumulate paragraph text
        if (!empty($current_paragraph)) {
            $current_paragraph .= ' ';
        }
        $current_paragraph .= trim($line);
    }

    // Flush any remaining paragraph
    if (!empty($current_paragraph)) {
        $html .= '<p>' . wp_kses_post($current_paragraph) . '</p>';
    }

    // Handle unclosed code block
    if ($in_code_block && !empty($code_block_content)) {
        $html .= render_code_block($code_block_content, $code_block_index);
    }

    return [
        'html' => $html,
        'headings' => $headings,
    ];
}

/**
 * Render a code block with copy button
 *
 * @param string $content Code block content
 * @param int $index Code block index
 * @return string HTML for code block
 */
function render_code_block($content, $index) {
    $content = rtrim($content, "\n");
    $content = esc_html($content);

    return '<div class="code-block-wrapper">'
        . '<div class="code-block-header">'
        . '<span>Code</span>'
        . '<button class="code-block-copy-btn" data-code-index="' . $index . '">Copy Code</button>'
        . '</div>'
        . '<div class="code-block-content">' . $content . '</div>'
        . '</div>';
}

/**
 * WordPress-compatible title sanitization
 * Converts text to URL-safe slug
 *
 * @param string $title Text to sanitize
 * @return string Sanitized title
 */
function sanitize_title_with_dashes($title) {
    // Convert to lowercase
    $title = strtolower($title);

    // Replace spaces and special characters with dashes
    $title = preg_replace('/[^\w\s-]/u', '', $title);
    $title = preg_replace('/[\s_-]+/', '-', $title);
    $title = preg_replace('/^-+|-+$/', '', $title);

    return $title;
}

?>
