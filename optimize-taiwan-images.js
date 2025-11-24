const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const inputDir = 'assets/images/blog/taiwan';
const tempDir = 'assets/images/blog/taiwan-temp';

// 一時ディレクトリ作成
if (!fs.existsSync(tempDir)) {
  fs.mkdirSync(tempDir, { recursive: true });
}

async function optimizeImages() {
  const files = fs.readdirSync(inputDir)
    .filter(file => /\.(jpg|jpeg|JPG|JPEG)$/i.test(file));

  console.log(`Found ${files.length} images to optimize\n`);

  for (const file of files) {
    const inputPath = path.join(inputDir, file);
    const outputPath = path.join(tempDir, file.toLowerCase());

    try {
      // 画像情報取得
      const metadata = await sharp(inputPath).metadata();

      // EXIF Orientationを正しく処理してリサイズ＆圧縮
      await sharp(inputPath)
        .rotate() // EXIF Orientationに基づいて自動回転（これが重要）
        .resize(1200, 1200, {
          withoutEnlargement: true,
          fit: 'inside' // アスペクト比を保持
        })
        .jpeg({
          quality: 95, // 高品質を保つ
          mozjpeg: true
        })
        .toFile(outputPath);

      const inputStats = fs.statSync(inputPath);
      const outputStats = fs.statSync(outputPath);
      const reduction = ((1 - outputStats.size / inputStats.size) * 100).toFixed(1);

      // 最適化後のサイズ確認
      const optimizedMetadata = await sharp(outputPath).metadata();

      console.log(`✅ ${file}`);
      console.log(`   ${(inputStats.size / 1024 / 1024).toFixed(2)}MB → ${(outputStats.size / 1024 / 1024).toFixed(2)}MB (${reduction}% 削減)`);
      console.log(`   元: ${metadata.width}x${metadata.height}px → 最適化後: ${optimizedMetadata.width}x${optimizedMetadata.height}px\n`);
    } catch (error) {
      console.error(`❌ ${file}: ${error.message}`);
    }
  }

  console.log('✨ 最適化完了！一時フォルダに保存されました');
}

optimizeImages().catch(console.error);
