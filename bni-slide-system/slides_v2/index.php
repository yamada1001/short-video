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
        .reveal .slides section {
            height: 100%;
            width: 100%;
        }

        .reveal .slides section img {
            max-width: 100%;
            max-height: 100%;
            margin: 0;
            border: none;
            box-shadow: none;
            background: none;
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
                echo "<section>";
                echo "<img src=\"{$imagePath}\" alt=\"Slide {$i}\" />";
                echo "</section>\n";
            }
            ?>
        </div>
    </div>

    <!-- Reveal.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.5.0/reveal.min.js"></script>
    <script>
        Reveal.initialize({
            controls: true,
            progress: true,
            center: true,
            hash: true,
            width: 1920,
            height: 1080,
            margin: 0,
            minScale: 0.2,
            maxScale: 2.0
        });
    </script>
</body>
</html>
