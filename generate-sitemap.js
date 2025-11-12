#!/usr/bin/env node

const fs = require('fs');
const path = require('path');

const baseUrl = 'https://yojitu.com';
const currentDate = new Date().toISOString().split('T')[0];

// ページの優先度と更新頻度を定義
const pageConfig = {
    'index.html': { priority: '1.0', changefreq: 'weekly' },
    'services.html': { priority: '0.8', changefreq: 'weekly' },
    'privacy.html': { priority: '0.3', changefreq: 'monthly' },
    'tokushoho.html': { priority: '0.3', changefreq: 'monthly' }
};

// デフォルトの設定
const defaultConfig = { priority: '0.5', changefreq: 'monthly' };

// カレントディレクトリからHTMLファイルを自動検出
const htmlFiles = fs.readdirSync(__dirname)
    .filter(file => file.endsWith('.html'))
    .map(file => {
        const config = pageConfig[file] || defaultConfig;
        const stats = fs.statSync(path.join(__dirname, file));
        const lastmod = stats.mtime.toISOString().split('T')[0];

        return {
            url: `/${file}`,
            lastmod: lastmod,
            priority: config.priority,
            changefreq: config.changefreq
        };
    })
    // 優先度順にソート
    .sort((a, b) => parseFloat(b.priority) - parseFloat(a.priority));

// XMLサイトマップを生成
const sitemap = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${htmlFiles.map(page => `    <url>
        <loc>${baseUrl}${page.url}</loc>
        <lastmod>${page.lastmod}</lastmod>
        <changefreq>${page.changefreq}</changefreq>
        <priority>${page.priority}</priority>
    </url>`).join('\n')}
</urlset>`;

// sitemap.xmlファイルに書き込み
fs.writeFileSync(path.join(__dirname, 'sitemap.xml'), sitemap, 'utf8');
console.log(`✅ sitemap.xml を生成しました (${htmlFiles.length}ページ)`);
console.log('📄 検出されたページ:');
htmlFiles.forEach(page => {
    console.log(`   - ${page.url} (priority: ${page.priority})`);
});
