#!/usr/bin/env python3
"""
ブログ記事ファイルの検証スクリプト

このスクリプトは blog/data/ ディレクトリ内のすべての article-*-full.html ファイルを検証し、
不要なHTML構造タグが含まれていないかチェックします。

検証項目:
- <!DOCTYPE>, <html>, <head>, <title>, <body> タグの有無
- <article> タグの有無
- <header class="article-header"> セクションの有無

正しい形式:
- 純粋なコンテンツ（<section>, <p>, <h2> など）から始まること
- 構造化データ（<script type="application/ld+json">）は含まれていてもOK
"""

import re
import glob
import sys
from pathlib import Path

# 検出すべき不要なタグのパターン
INVALID_PATTERNS = [
    (r'<!DOCTYPE', '<!DOCTYPE html> declaration'),
    (r'<html[\s>]', '<html> tag'),
    (r'<head[\s>]', '<head> tag'),
    (r'<title[\s>]', '<title> tag'),
    (r'<body[\s>]', '<body> tag'),
    (r'<article[\s>]', '<article> tag'),
    (r'<header\s+class=["\']article-header["\']', '<header class="article-header"> tag'),
]

def validate_article(filepath):
    """記事ファイルを検証"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    errors = []

    # 各パターンをチェック
    for pattern, description in INVALID_PATTERNS:
        if re.search(pattern, content, re.IGNORECASE):
            errors.append(f"  ❌ Found {description}")

    # ファイルが正しいコンテンツから始まっているかチェック
    first_tag = re.match(r'\s*<(\w+)', content)
    if first_tag:
        tag_name = first_tag.group(1).lower()
        if tag_name not in ['section', 'p', 'h1', 'h2', 'div', 'ul', 'ol']:
            errors.append(f"  ⚠️  File starts with <{tag_name}> tag (expected content tags like <section>, <p>, <h2>)")

    return errors

def main():
    """メイン処理"""
    print("=" * 60)
    print("ブログ記事ファイル検証")
    print("=" * 60)
    print()

    # blog/data/article-*-full.html を検索
    article_files = sorted(glob.glob('blog/data/article-*-full.html'))

    if not article_files:
        print("❌ No article files found in blog/data/")
        return 1

    print(f"Found {len(article_files)} article files")
    print()

    total_errors = 0
    problematic_files = []

    for filepath in article_files:
        filename = Path(filepath).name
        errors = validate_article(filepath)

        if errors:
            print(f"❌ {filename}")
            for error in errors:
                print(error)
            print()
            total_errors += len(errors)
            problematic_files.append(filename)
        else:
            print(f"✅ {filename}")

    print()
    print("=" * 60)
    print("検証結果サマリー")
    print("=" * 60)
    print(f"Total files checked: {len(article_files)}")
    print(f"Files with issues: {len(problematic_files)}")
    print(f"Total errors: {total_errors}")

    if problematic_files:
        print()
        print("問題のあるファイル:")
        for filename in problematic_files:
            print(f"  - {filename}")
        print()
        print("修正方法:")
        print("  1. 記事ファイルから不要なHTML構造タグを削除してください")
        print("  2. 純粋なコンテンツ（<section>, <p>, <h2> など）のみを含めてください")
        print("  3. 構造化データ（<script type='application/ld+json'>）は残してOKです")
        return 1
    else:
        print()
        print("✅ すべての記事ファイルが正しい形式です！")
        return 0

if __name__ == '__main__':
    sys.exit(main())
