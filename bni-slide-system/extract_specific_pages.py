#!/usr/bin/env python3
"""
本番用PDFから必要なページのみを抽出して確認用に画像化
"""
import sys
from pathlib import Path
from pdf2image import convert_from_path

# 必要なページ番号のリスト
REQUIRED_PAGES = [
    2,      # 出欠確認
    42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59,  # ビジター紹介後
    62,     # コアバリュー
    65, 66, 67, 68, 69, 70, 71,  # BNIの目的
    73,     # シェアストーリー
    111, 113,  # 更新メンバー
    182,    # ビジター様自己紹介
    198, 199, 200,  # ビジネスブレイクアウト
    203, 204, 205, 206,  # バイスプレジデント統計情報
    211,    # 募集カテゴリアンケート
    213,    # 一般規定
    228,    # リファーラルと推薦発表
    230,    # ビジター様本日の一言感想
    246, 247, 248, 249, 250, 251, 252, 253,  # リファーラル信頼度の確認
    303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 315, 316, 317, 318, 319, 320, 321, 322, 323, 324, 325, 326, 327, 328  # 終礼
]

def main():
    pdf_path = Path('/Users/yamadaren/Downloads/本番用スライド.pdf')
    output_dir = Path('/Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/pdf_analysis/required_pages')
    output_dir.mkdir(parents=True, exist_ok=True)

    print(f"PDF読み込み中: {pdf_path}")
    print(f"必要なページ数: {len(REQUIRED_PAGES)}")

    # PDFから特定ページのみ抽出
    for page_num in REQUIRED_PAGES:
        print(f"ページ {page_num} を抽出中...")
        images = convert_from_path(
            pdf_path,
            first_page=page_num,
            last_page=page_num,
            dpi=150  # 確認用なので150dpiで十分
        )

        if images:
            output_path = output_dir / f'page_{page_num:03d}.png'
            images[0].save(output_path, 'PNG')
            print(f"  保存完了: {output_path}")

    print(f"\n✅ 全{len(REQUIRED_PAGES)}ページの抽出完了")
    print(f"出力先: {output_dir}")

if __name__ == '__main__':
    main()
