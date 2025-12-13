<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNI Slide System V2</title>

    <!-- Reveal.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.5.0/reveal.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.5.0/theme/black.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }

        .reveal .slides {
            width: 100% !important;
            height: 100% !important;
        }

        .reveal .slides section {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            top: 0 !important;
            left: 0 !important;
        }

        .reveal .slides section img {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
            object-fit: contain;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* ページ番号表示 */
        .slide-number-display {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #000;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 24px;
            font-weight: bold;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        /* Reveal.jsのデフォルトコントロールを非表示 */
        .reveal .controls {
            display: none;
        }

        .reveal .progress {
            display: none;
        }
    </style>
</head>
<body>
    <div class="reveal">
        <div class="slides">
            <?php
            // 309枚の画像をスライドとして表示
            for ($i = 1; $i <= 309; $i++) {
                $slideNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                $imagePath = "../assets/images/slides/production/slide_{$slideNumber}.png";
                echo "<section data-slide-number=\"{$i}\">";
                echo "<img src=\"{$imagePath}\" alt=\"Slide {$i}\" />";
                echo "</section>\n";
            }
            ?>
        </div>
    </div>

    <!-- ページ番号表示 -->
    <div class="slide-number-display" id="slideNumber">1 / 309</div>

    <!-- Reveal.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.5.0/reveal.min.js"></script>
    <script>
        Reveal.initialize({
            width: '100%',
            height: '100%',
            margin: 0,
            minScale: 1,
            maxScale: 1,
            controls: false,
            progress: false,
            center: false,
            hash: true,
            transition: 'slide',
            backgroundTransition: 'none'
        });

        // ページ番号更新
        Reveal.on('slidechanged', event => {
            const slideNumber = event.indexh + 1;
            document.getElementById('slideNumber').textContent = slideNumber + ' / 309';
        });

        // 初期表示時にフルスクリーンモードを提案
        document.addEventListener('DOMContentLoaded', () => {
            // Fキーでフルスクリーン切り替え
            document.addEventListener('keydown', (e) => {
                if (e.key === 'f' || e.key === 'F') {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                    } else {
                        document.exitFullscreen();
                    }
                }
            });
        });
    </script>
</body>
</html>
