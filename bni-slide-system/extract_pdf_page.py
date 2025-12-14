#!/usr/bin/env python3
"""
PDF Page Extractor
指定したページを画像として抽出
"""
import sys
import os
from pdf2image import convert_from_path

def extract_page(pdf_path, page_num, output_path):
    """1ページだけを抽出"""
    try:
        images = convert_from_path(
            pdf_path,
            dpi=150,
            first_page=page_num,
            last_page=page_num
        )
        
        if images:
            images[0].save(output_path, 'PNG')
            print(f'✅ Page {page_num} extracted to {output_path}')
            return True
        else:
            print(f'❌ Failed to extract page {page_num}')
            return False
    except Exception as e:
        print(f'❌ Error: {e}')
        return False

if __name__ == '__main__':
    if len(sys.argv) < 3:
        print('Usage: python3 extract_pdf_page.py <page_number> <output_path>')
        sys.exit(1)
    
    pdf_path = '/Users/yamadaren/Downloads/25_12_12_57回宗麟_定例会.ppt  .pdf'
    page_num = int(sys.argv[1])
    output_path = sys.argv[2]
    
    extract_page(pdf_path, page_num, output_path)
