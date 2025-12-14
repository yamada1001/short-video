/**
 * Puppeteer を使用してスライドをPNG画像としてキャプチャ
 * Usage: node capture_slide.js <url> <output_path>
 */

const puppeteer = require('puppeteer');

async function captureSlide(url, outputPath) {
    let browser;
    try {
        browser = await puppeteer.launch({
            headless: 'new',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--disable-gpu'
            ],
            executablePath: puppeteer.executablePath()
        });

        const page = await puppeteer.newPage();

        // フルスクリーンサイズ（1920x1080）に設定
        await page.setViewport({
            width: 1920,
            height: 1080,
            deviceScaleFactor: 1
        });

        // スライドページにアクセス
        await page.goto(url, {
            waitUntil: 'networkidle0',
            timeout: 30000
        });

        // フォントとCSSが完全にロードされるまで待機
        await page.waitForTimeout(2000);

        // スクリーンショットを撮影
        await page.screenshot({
            path: outputPath,
            fullPage: false,
            type: 'png'
        });

        console.log(`✓ Screenshot saved: ${outputPath}`);
        return true;

    } catch (error) {
        console.error(`✗ Error capturing slide: ${error.message}`);
        return false;
    } finally {
        if (browser) {
            await browser.close();
        }
    }
}

// コマンドライン引数から取得
const url = process.argv[2];
const outputPath = process.argv[3];

if (!url || !outputPath) {
    console.error('Usage: node capture_slide.js <url> <output_path>');
    process.exit(1);
}

captureSlide(url, outputPath)
    .then(success => process.exit(success ? 0 : 1))
    .catch(err => {
        console.error(err);
        process.exit(1);
    });
