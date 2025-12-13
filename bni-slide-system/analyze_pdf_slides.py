#!/usr/bin/env python3
"""
PDF Slide Analyzer
全309ページのスライドを分析し、スライドタイプを分類する
"""

import fitz  # PyMuPDF
import os
import json
from collections import Counter
import re

def analyze_page(page, page_num):
    """ページを分析してメタデータを抽出"""

    # テキスト抽出
    text = page.get_text()
    text_length = len(text.strip())

    # テキストの特徴を分析
    has_title = False
    has_list = False
    has_table = False
    has_ranking = False
    has_video = False

    # タイトルスライドの検出
    if any(keyword in text for keyword in ['チャプター', '定例会', 'プレジ', '朝礼']):
        has_title = True

    # リストの検出
    if text.count('・') > 3 or text.count('①') > 0 or text.count('②') > 0:
        has_list = True

    # ランキングの検出
    if any(keyword in text for keyword in ['ランキング', '1位', '2位', '3位', 'リファーラル金額', 'ビジター']):
        has_ranking = True

    # 動画スライドの検出
    if any(keyword in text for keyword in ['動画', 'YouTube', '再生']):
        has_video = True

    # テーブルの検出（簡易版）
    lines = text.split('\n')
    if len(lines) > 10:
        has_table = True

    # 主要なキーワードを抽出
    keywords = []
    for line in lines[:5]:  # 最初の5行から
        clean_line = line.strip()
        if clean_line and len(clean_line) > 3:
            keywords.append(clean_line[:50])  # 最大50文字

    return {
        'page_num': page_num,
        'text_length': text_length,
        'has_title': has_title,
        'has_list': has_list,
        'has_table': has_table,
        'has_ranking': has_ranking,
        'has_video': has_video,
        'keywords': keywords[:3],  # 最大3つ
        'text_preview': text[:200].replace('\n', ' ')  # 最初の200文字
    }

def classify_slide_type(metadata):
    """メタデータからスライドタイプを分類"""

    text_preview = metadata['text_preview'].lower()
    keywords_text = ' '.join(metadata['keywords']).lower()

    # 分類ルール
    if metadata['has_ranking']:
        return 'ranking'
    elif metadata['has_video']:
        return 'video'
    elif metadata['has_title']:
        return 'title'
    elif metadata['text_length'] < 50:
        return 'image_only'
    elif metadata['has_list']:
        return 'list'
    elif metadata['has_table']:
        return 'table'
    elif 'メンバー' in keywords_text and '30秒' in keywords_text:
        return 'member_pitch'
    elif any(word in keywords_text for word in ['委員会', 'チーム', 'メンバーシップ']):
        return 'committee'
    elif metadata['text_length'] > 500:
        return 'content_heavy'
    else:
        return 'standard'

def main():
    pdf_path = '/Users/yamadaren/Downloads/25_12_12_57回宗麟_定例会.ppt  .pdf'
    output_json = '/Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/pdf_analysis/analysis_result.json'

    print('📊 PDF Slide Analyzer')
    print('=' * 60)

    # PDFを開く
    doc = fitz.open(pdf_path)
    total_pages = len(doc)
    print(f'Total pages: {total_pages}\n')

    # 全ページを分析
    results = []
    slide_types = Counter()

    for i in range(total_pages):
        page = doc[i]
        metadata = analyze_page(page, i + 1)
        slide_type = classify_slide_type(metadata)

        metadata['slide_type'] = slide_type
        results.append(metadata)
        slide_types[slide_type] += 1

        # Progress
        if (i + 1) % 50 == 0:
            print(f'Analyzed: {i+1}/{total_pages} pages...')

    doc.close()

    # 結果を保存
    output_data = {
        'total_pages': total_pages,
        'slide_type_counts': dict(slide_types),
        'pages': results
    }

    with open(output_json, 'w', encoding='utf-8') as f:
        json.dump(output_data, f, ensure_ascii=False, indent=2)

    print(f'\n✅ Analysis complete!')
    print(f'Results saved to: {output_json}\n')

    # 統計情報を表示
    print('📈 Slide Type Statistics:')
    print('=' * 60)
    for slide_type, count in sorted(slide_types.items(), key=lambda x: x[1], reverse=True):
        percentage = count / total_pages * 100
        print(f'{slide_type:20s}: {count:3d} pages ({percentage:5.1f}%)')

    print('\n' + '=' * 60)

    # サンプルページを表示
    print('\n🔍 Sample Pages by Type:')
    print('=' * 60)
    for slide_type in slide_types.keys():
        sample = next((r for r in results if r['slide_type'] == slide_type), None)
        if sample:
            print(f'\n{slide_type.upper()}:')
            print(f'  Page: {sample["page_num"]}')
            print(f'  Preview: {sample["text_preview"][:100]}...')

if __name__ == '__main__':
    main()
