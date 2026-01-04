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

# ローカルサーバーのベースURL（ポート8000で起動）
BASE_URL = "http://localhost:8000/mockup"

# 全31ページのURL
urls = [
    ("01_top", f"{BASE_URL}/index.html"),
    ("02_about", f"{BASE_URL}/about/index.html"),
    ("03_services_personal", f"{BASE_URL}/services/personal/index.html"),
    ("04_life_planning", f"{BASE_URL}/services/personal/life-planning/index.html"),
    ("05_insurance", f"{BASE_URL}/services/personal/insurance/index.html"),
    ("06_housing_loan", f"{BASE_URL}/services/personal/housing-loan/index.html"),
    ("07_inheritance", f"{BASE_URL}/services/personal/inheritance/index.html"),
    ("08_investment", f"{BASE_URL}/services/personal/investment/index.html"),
    ("09_services_corporate", f"{BASE_URL}/services/corporate/index.html"),
    ("10_financial_consulting", f"{BASE_URL}/services/corporate/financial-consulting/index.html"),
    ("11_retirement", f"{BASE_URL}/services/corporate/retirement/index.html"),
    ("12_succession", f"{BASE_URL}/services/corporate/succession/index.html"),
    ("13_stock", f"{BASE_URL}/services/corporate/stock/index.html"),
    ("14_why_us", f"{BASE_URL}/why-us/index.html"),
    ("15_voice", f"{BASE_URL}/voice/index.html"),
    ("16_staff", f"{BASE_URL}/staff/index.html"),
    ("17_company", f"{BASE_URL}/company/index.html"),
    ("18_privacy", f"{BASE_URL}/company/privacy/index.html"),
    ("19_solicitation", f"{BASE_URL}/company/solicitation/index.html"),
    ("20_customer_oriented", f"{BASE_URL}/company/customer-oriented/index.html"),
    ("21_news", f"{BASE_URL}/news/index.html"),
    ("22_news_detail", f"{BASE_URL}/news/detail.html"),
    ("23_seminar", f"{BASE_URL}/news/seminar/index.html"),
    ("24_seminar_detail", f"{BASE_URL}/news/seminar/detail.html"),
    ("25_staff_blog", f"{BASE_URL}/news/staff-blog/index.html"),
    ("26_staff_blog_detail", f"{BASE_URL}/news/staff-blog/detail.html"),
    ("27_staff_blog_by_staff", f"{BASE_URL}/news/staff-blog/by-staff/index.html"),
    ("28_staff_blog_by_category", f"{BASE_URL}/news/staff-blog/by-category/index.html"),
    ("29_faq", f"{BASE_URL}/faq/index.html"),
    ("30_contact", f"{BASE_URL}/contact/index.html"),
    ("31_thanks", f"{BASE_URL}/contact/thanks.html"),
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
    time.sleep(5)  # ページ読み込み待機を長めに

    # ページ全体の高さを取得（複数回試行）
    total_height = driver.execute_script("return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight, document.body.offsetHeight, document.documentElement.offsetHeight, document.body.clientHeight, document.documentElement.clientHeight)")

    # 最小高さを保証
    if total_height < 800:
        total_height = 3000  # デフォルトの高さ

    driver.set_window_size(1440, total_height)
    time.sleep(3)  # レンダリング待機

    # スクリーンショット保存
    screenshot_path = output_dir / f"{filename}.png"
    driver.save_screenshot(str(screenshot_path))

    # ファイルサイズを確認
    file_size = screenshot_path.stat().st_size
    print(f"✅ 保存完了: {screenshot_path} ({file_size:,} bytes)")

    if file_size < 10000:
        print(f"⚠️  警告: ファイルサイズが小さすぎます ({file_size} bytes)")

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
