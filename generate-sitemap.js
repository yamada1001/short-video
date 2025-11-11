#!/usr/bin/env node

const fs = require('fs');
const path = require('path');

const baseUrl = 'https://yamada1001.github.io/short-video';
const currentDate = new Date().toISOString().split('T')[0];

// サイトマップに含めるページのリスト
const pages = [
    { url: '/index.html', priority: '1.0', changefreq: 'weekly' },
    { url: '/services.html', priority: '0.8', changefreq: 'weekly' },
    { url: '/privacy.html', priority: '0.3', changefreq: 'monthly' },
    { url: '/tokushoho.html', priority: '0.3', changefreq: 'monthly' }
];

// XMLサイトマップを生成
const sitemap = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${pages.map(page => `    <url>
        <loc>${baseUrl}${page.url}</loc>
        <lastmod>${currentDate}</lastmod>
        <changefreq>${page.changefreq}</changefreq>
        <priority>${page.priority}</priority>
    </url>`).join('\n')}
</urlset>`;

// sitemap.xmlファイルに書き込み
fs.writeFileSync(path.join(__dirname, 'sitemap.xml'), sitemap, 'utf8');
console.log('✅ sitemap.xml を生成しました');
