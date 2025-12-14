#!/usr/bin/env python3
"""
PDF to Images Converter for BNI Slide System V2
PDFファイルをページごとに画像に変換する

使用方法:
    python3 pdf_to_images.py <pdf_path> <output_dir>

例:
    python3 pdf_to_images.py presentation.pdf ./output
"""

import sys
import os

def convert_with_pymupdf(pdf_path, output_dir):
    """PyMuPDF (fitz) を使用してPDFを画像に変換"""
    try:
        import fitz  # PyMuPDF
    except ImportError:
        print("Error: PyMuPDF (fitz) が見つかりません", file=sys.stderr)
        print("インストール: pip install PyMuPDF", file=sys.stderr)
        return False

    try:
        # PDFを開く
        pdf_document = fitz.open(pdf_path)
        total_pages = len(pdf_document)

        print(f"PDF変換開始: {pdf_path}")
        print(f"総ページ数: {total_pages}")
        print(f"出力先: {output_dir}")
        print()

        # 各ページを画像に変換
        for page_num in range(total_pages):
            page = pdf_document[page_num]

            # 高解像度でレンダリング（DPI 150相当）
            zoom = 2.0  # 2倍ズーム = 約150 DPI
            mat = fitz.Matrix(zoom, zoom)
            pix = page.get_pixmap(matrix=mat)

            # PNG形式で保存
            output_path = os.path.join(output_dir, f'page_{page_num + 1:04d}.png')
            pix.save(output_path)

            print(f"保存: {output_path}")

        pdf_document.close()

        print()
        print(f"完了: {total_pages}ページを変換しました")
        return True

    except Exception as e:
        print(f"変換エラー: {e}", file=sys.stderr)
        return False


def convert_with_pdf2image(pdf_path, output_dir):
    """pdf2image を使用してPDFを画像に変換（フォールバック）"""
    try:
        from pdf2image import convert_from_path
    except ImportError:
        print("Error: pdf2image が見つかりません", file=sys.stderr)
        print("インストール: pip install pdf2image", file=sys.stderr)
        return False

    try:
        print(f"PDF変換開始 (pdf2image): {pdf_path}")
        print(f"出力先: {output_dir}")
        print()

        # PDFを画像に変換
        images = convert_from_path(pdf_path, dpi=150)

        print(f"変換されたページ数: {len(images)}")
        print()

        # 各ページを保存
        for i, image in enumerate(images, start=1):
            output_path = os.path.join(output_dir, f'page_{i:04d}.png')
            image.save(output_path, 'PNG')
            print(f"保存: {output_path}")

        print()
        print(f"完了: {len(images)}ページを変換しました")
        return True

    except Exception as e:
        print(f"変換エラー: {e}", file=sys.stderr)
        return False


def main():
    # 引数チェック
    if len(sys.argv) < 3:
        print("使用方法: python3 pdf_to_images.py <pdf_path> <output_dir>", file=sys.stderr)
        sys.exit(1)

    pdf_path = sys.argv[1]
    output_dir = sys.argv[2]

    # PDFファイル存在チェック
    if not os.path.exists(pdf_path):
        print(f"エラー: PDFファイルが見つかりません: {pdf_path}", file=sys.stderr)
        sys.exit(1)

    # 出力ディレクトリ作成
    os.makedirs(output_dir, exist_ok=True)

    # 変換実行（PyMuPDF優先、失敗したらpdf2image）
    success = convert_with_pymupdf(pdf_path, output_dir)

    if not success:
        print("\nPyMuPDFでの変換に失敗しました。pdf2imageを試します...\n", file=sys.stderr)
        success = convert_with_pdf2image(pdf_path, output_dir)

    if not success:
        print("\nPDF変換に失敗しました", file=sys.stderr)
        sys.exit(1)

    sys.exit(0)


if __name__ == '__main__':
    main()
