#!/usr/bin/env python3
"""
スライドPHPファイルからPNG画像を生成するスクリプト
Usage: python3 generate_slide_image.py <slide_file> <page_number> [date]
Example: python3 generate_slide_image.py seating.php 7 2025-12-20
"""

import sys
import os
import subprocess
from pathlib import Path

def generate_slide_image(slide_file, page_number, date=None):
    """
    スライドPHPファイルからPNG画像を生成

    Args:
        slide_file: スライドファイル名（例: seating.php）
        page_number: ページ番号（例: 7 → slide_007.png）
        date: 対象日（省略可）
    """
    # パス設定
    script_dir = Path(__file__).parent
    slides_dir = script_dir.parent / 'slides'
    output_dir = script_dir.parent.parent.parent / 'assets' / 'images' / 'slides' / 'production'

    # 出力ディレクトリ作成
    output_dir.mkdir(parents=True, exist_ok=True)

    # URLを構築（本番環境）
    slide_url = f"https://yojitu.com/bni-slide-system/slides_v2/slides/{slide_file}"
    if date:
        slide_url += f"?date={date}"

    # 出力ファイル名（3桁ゼロパディング）
    output_file = output_dir / f"slide_{str(page_number).zfill(3)}.png"

    # Puppeteer/Playwright用のNode.jsスクリプトを実行
    node_script = script_dir / 'capture_slide.js'

    try:
        result = subprocess.run(
            ['node', str(node_script), slide_url, str(output_file)],
            capture_output=True,
            text=True,
            timeout=30
        )

        if result.returncode == 0:
            print(f"✓ 画像生成成功: {output_file}")
            return True
        else:
            print(f"✗ 画像生成失敗: {result.stderr}")
            return False
    except subprocess.TimeoutExpired:
        print("✗ タイムアウト: 画像生成に30秒以上かかりました")
        return False
    except Exception as e:
        print(f"✗ エラー: {str(e)}")
        return False

if __name__ == '__main__':
    if len(sys.argv) < 3:
        print("Usage: python3 generate_slide_image.py <slide_file> <page_number> [date]")
        print("Example: python3 generate_slide_image.py seating.php 7 2025-12-20")
        sys.exit(1)

    slide_file = sys.argv[1]
    page_number = int(sys.argv[2])
    date = sys.argv[3] if len(sys.argv) > 3 else None

    success = generate_slide_image(slide_file, page_number, date)
    sys.exit(0 if success else 1)
