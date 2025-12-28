#!/usr/bin/env python3
"""
全31ページを自動スクリーンショット（Figma移行用）
"""
import time
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from pathlib import Path

# スクリーンショット保存先
output_dir = Path("figma_screenshots")
output_dir.mkdir(exist_ok=True)

# 全31ページのURL
urls = [
    ("01_top", "https://www.yojitu.com/finance-brain/mockup/index.html"),
    ("02_about", "https://www.yojitu.com/finance-brain/mockup/about/index.html"),
    ("03_services_personal", "https://www.yojitu.com/finance-brain/mockup/services/personal/index.html"),
    ("04_life_planning", "https://www.yojitu.com/finance-brain/mockup/services/personal/life-planning/index.html"),
    ("05_insurance", "https://www.yojitu.com/finance-brain/mockup/services/personal/insurance/index.html"),
    ("06_housing_loan", "https://www.yojitu.com/finance-brain/mockup/services/personal/housing-loan/index.html"),
    ("07_inheritance", "https://www.yojitu.com/finance-brain/mockup/services/personal/inheritance/index.html"),
    ("08_investment", "https://www.yojitu.com/finance-brain/mockup/services/personal/investment/index.html"),
    ("09_services_corporate", "https://www.yojitu.com/finance-brain/mockup/services/corporate/index.html"),
    ("10_financial_consulting", "https://www.yojitu.com/finance-brain/mockup/services/corporate/financial-consulting/index.html"),
    ("11_retirement", "https://www.yojitu.com/finance-brain/mockup/services/corporate/retirement/index.html"),
    ("12_succession", "https://www.yojitu.com/finance-brain/mockup/services/corporate/succession/index.html"),
    ("13_stock", "https://www.yojitu.com/finance-brain/mockup/services/corporate/stock/index.html"),
    ("14_why_us", "https://www.yojitu.com/finance-brain/mockup/why-us/index.html"),
    ("15_voice", "https://www.yojitu.com/finance-brain/mockup/voice/index.html"),
    ("16_staff", "https://www.yojitu.com/finance-brain/mockup/staff/index.html"),
    ("17_company", "https://www.yojitu.com/finance-brain/mockup/company/index.html"),
    ("18_privacy", "https://www.yojitu.com/finance-brain/mockup/company/privacy/index.html"),
    ("19_solicitation", "https://www.yojitu.com/finance-brain/mockup/company/solicitation/index.html"),
    ("20_customer_oriented", "https://www.yojitu.com/finance-brain/mockup/company/customer-oriented/index.html"),
    ("21_news", "https://www.yojitu.com/finance-brain/mockup/news/index.html"),
    ("22_news_detail", "https://www.yojitu.com/finance-brain/mockup/news/detail.html"),
    ("23_seminar", "https://www.yojitu.com/finance-brain/mockup/news/seminar/index.html"),
    ("24_seminar_detail", "https://www.yojitu.com/finance-brain/mockup/news/seminar/detail.html"),
    ("25_staff_blog", "https://www.yojitu.com/finance-brain/mockup/news/staff-blog/index.html"),
    ("26_staff_blog_detail", "https://www.yojitu.com/finance-brain/mockup/news/staff-blog/detail.html"),
    ("27_staff_blog_by_staff", "https://www.yojitu.com/finance-brain/mockup/news/staff-blog/by-staff/index.html"),
    ("28_staff_blog_by_category", "https://www.yojitu.com/finance-brain/mockup/news/staff-blog/by-category/index.html"),
    ("29_faq", "https://www.yojitu.com/finance-brain/mockup/faq/index.html"),
    ("30_contact", "https://www.yojitu.com/finance-brain/mockup/contact/index.html"),
    ("31_thanks", "https://www.yojitu.com/finance-brain/mockup/contact/thanks.html"),
]

def setup_driver():
    """Chromeドライバーのセットアップ"""
    chrome_options = Options()
    chrome_options.add_argument("--headless")  # ヘッドレスモード
    chrome_options.add_argument("--window-size=1440,900")  # デスクトップサイズ
    chrome_options.add_argument("--disable-gpu")
    chrome_options.add_argument("--no-sandbox")

    driver = webdriver.Chrome(options=chrome_options)
    return driver

def take_full_page_screenshot(driver, url, filename):
    """フルページスクリーンショット"""
    print(f"📸 {filename}...")

    driver.get(url)
    time.sleep(2)  # ページ読み込み待機

    # ページ全体の高さを取得
    total_height = driver.execute_script("return document.body.scrollHeight")
    driver.set_window_size(1440, total_height)
    time.sleep(1)

    # スクリーンショット保存
    screenshot_path = output_dir / f"{filename}.png"
    driver.save_screenshot(str(screenshot_path))
    print(f"✅ 保存完了: {screenshot_path}")

def main():
    print("🚀 全31ページのスクリーンショットを開始します...")
    print(f"📁 保存先: {output_dir.absolute()}\n")

    driver = setup_driver()

    try:
        for i, (name, url) in enumerate(urls, 1):
            print(f"[{i}/31] {name}")
            take_full_page_screenshot(driver, url, name)
    finally:
        driver.quit()

    print("\n🎉 完了！")
    print(f"📂 {output_dir.absolute()} に31枚のスクリーンショットが保存されました")
    print("\n次のステップ:")
    print("1. Figmaを開く")
    print("2. figma_screenshots フォルダから全画像を選択")
    print("3. Figmaにドラッグ&ドロップ")

if __name__ == "__main__":
    main()
