#!/usr/bin/env python3
"""
PDF to Images Converter
PDFの各ページを画像に変換して分析用に保存
"""

import sys
import os
from pdf2image import convert_from_path
from PIL import Image

def convert_pdf_to_images(pdf_path, output_dir, start_page=1, end_page=None, dpi=150):
    """
    PDFを画像に変換
    """
    # 出力ディレクトリを作成
    os.makedirs(output_dir, exist_ok=True)

    print(f'PDF変換開始: {pdf_path}')
    print(f'ページ範囲: {start_page} 〜 {end_page if end_page else "最後まで"}')
    print(f'DPI: {dpi}')
    print(f'出力先: {output_dir}')
    print()

    try:
        # PDFを画像に変換
        images = convert_from_path(
            pdf_path,
            dpi=dpi,
            first_page=start_page,
            last_page=end_page
        )

        print(f'変換されたページ数: {len(images)}')
        print()

        # 各ページを保存
        for i, image in enumerate(images, start=start_page):
            output_path = os.path.join(output_dir, f'page_{i:04d}.png')
            image.save(output_path, 'PNG')
            print(f'保存: {output_path}')

            # サムネイルも作成 (幅800px)
            thumbnail = image.copy()
            thumbnail.thumbnail((800, 600), Image.Resampling.LANCZOS)
            thumbnail_path = os.path.join(output_dir, f'thumb_{i:04d}.png')
            thumbnail.save(thumbnail_path, 'PNG')

        print()
        print(f'✅ 完了: {len(images)}ページを変換しました')

    except Exception as e:
        print(f'❌ エラー: {e}', file=sys.stderr)
        sys.exit(1)

if __name__ == '__main__':
    # コマンドライン引数をチェック
    if len(sys.argv) >= 4:
        pdf_path = sys.argv[1]
        output_dir = sys.argv[2]
        start_page = int(sys.argv[3])
        end_page = int(sys.argv[4]) if len(sys.argv) >= 5 else None
        dpi = int(sys.argv[5]) if len(sys.argv) >= 6 else 150

        convert_pdf_to_images(pdf_path, output_dir, start_page, end_page, dpi)
    else:
        # PDFパス
        pdf_path = '/Users/yamadaren/Downloads/本番用スライド.pdf'

        # 出力ディレクトリ
        output_dir = '/Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/pdf_analysis'

        # まずサンプルページを抽出 (1-50ページ)
        print('=' * 60)
        print('サンプルページ抽出 (1-50ページ)')
        print('=' * 60)
        print()

        convert_pdf_to_images(pdf_path, output_dir, start_page=1, end_page=50, dpi=150)
