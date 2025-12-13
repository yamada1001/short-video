# プロジェクト整理・リファクタリング提案

## 現状の問題点

1. **ルートディレクトリに100個以上のファイルが散在**
2. **MDファイルが40個以上（作業ログ、仕様書、レポート等）**
3. **テスト・デバッグ用PHPファイルが本番環境に存在**
4. **管理画面が分散（admin/配下だが、機能ごとにバラバラ）**
5. **APIファイルが30個以上ルートに散在**

## 提案する整理案

### 1. ディレクトリ構造の再編成

```
bni-slide-system/
├── admin/                    # 管理画面（統合UI）
│   ├── index.php            # 管理ダッシュボード（全機能へのリンク）
│   ├── data-entry/          # データ入力系
│   │   ├── survey.php       # アンケート入力
│   │   ├── visitors.php     # ビジター管理
│   │   ├── networking.php   # ネットワーキング学習
│   │   ├── referrals.php    # リファーラル管理
│   │   └── seating.php      # 座席表管理
│   ├── content/             # コンテンツ管理系
│   │   ├── pitch.php        # ピッチ管理
│   │   ├── education.php    # 教育コンテンツ
│   │   └── ranking.php      # 月間ランキング
│   ├── system/              # システム管理系
│   │   ├── users.php        # ユーザー管理
│   │   ├── audit.php        # 監査ログ
│   │   └── sitemap.php      # サイトマップ
│   └── slide.php            # スライド表示
├── api/                     # 全APIファイルを集約
│   ├── survey/
│   ├── visitors/
│   ├── networking/
│   ├── referrals/
│   └── auth/
├── includes/                # 共通PHP
├── assets/                  # CSS/JS/画像
├── data/                    # データファイル
├── database/                # DB・マイグレーション
├── uploads/                 # アップロードファイル
├── docs/                    # ドキュメント（全MDファイル）
│   ├── archive/            # 古い作業ログ
│   ├── specs/              # 仕様書
│   └── guides/             # ガイド
├── scripts/                 # ツール・スクリプト
│   ├── test/               # テストファイル
│   ├── debug/              # デバッグツール
│   └── migration/          # マイグレーション
├── index.php                # ダッシュボード
├── login.php
└── README.md
```

### 2. 削除してもよいファイル（要確認）

**テスト・デバッグファイル:**
- `test_getTargetFriday.php`
- `test_login.php`
- `test_send_reminder.php`
- `debug-check-user.php`
- `debug-members.php`
- `check_database.php`
- `create_pitch_test_data.php`
- `generate_sample_data.php`
- `generate_test_data.php`
- `extract_pdf_page.py`
- `pdf_to_images.py`
- `analyze_pdf_slides.py`

**古いワンタイムスクリプト:**
- `create_yano_user.php`
- `fix_yano_password.php`
- `delete-user.php`
- `setup_production.php`

**重複・古いMDファイル:**
- `WORK_LOG_2024-*.md` → docs/archive/
- `SESSION_STATUS_*.md` → docs/archive/
- `INCIDENT_REPORT_*.md` → docs/archive/
- `PROGRESS_TRACKING.md`（重複）
- `PROPOSAL_SIMPLE.md`（重複）

### 3. 管理画面UIの統合提案

**現在の問題:**
- 各機能が独立したページで管理しにくい
- どこに何があるか分かりにくい

**提案: 統合ダッシュボード**

```
┌─────────────────────────────────────────┐
│ BNI Slide System - 管理画面             │
├─────────────────────────────────────────┤
│ データ入力                               │
│  □ アンケートデータ入力                  │
│  □ ビジター・ご紹介管理                  │
│  □ ネットワーキング学習コーナー          │
│  □ リファーラル管理                      │
│  □ 座席表設定                            │
├─────────────────────────────────────────┤
│ コンテンツ管理                           │
│  □ ピッチ管理                            │
│  □ 教育コンテンツ                        │
│  □ 月間ランキング                        │
├─────────────────────────────────────────┤
│ 表示・確認                               │
│  □ スライド表示                          │
│  □ マイデータ                            │
├─────────────────────────────────────────┤
│ システム管理（管理者のみ）               │
│  □ ユーザー管理                          │
│  □ 監査ログ                              │
│  □ サイトマップ                          │
└─────────────────────────────────────────┘
```

### 4. 実装ステップ

**Phase 1: ドキュメント整理（影響なし）**
1. `docs/` ディレクトリ作成
2. 全MDファイルを移動・整理
3. 古いファイルをアーカイブ

**Phase 2: テスト・デバッグファイル整理**
1. `scripts/test/` と `scripts/debug/` 作成
2. 本番環境で不要なファイルを移動
3. `.gitignore` で除外

**Phase 3: API整理**
1. `api/` ディレクトリ作成
2. 機能別にサブディレクトリ作成
3. APIファイルを移動（後方互換性のためリダイレクト設置）

**Phase 4: 管理画面UI統合**
1. `admin/index.php` 統合ダッシュボード作成
2. 既存ページを機能別ディレクトリに整理
3. ナビゲーション統一

**Phase 5: .gitignore 更新**
```
# Test & Debug
scripts/test/
scripts/debug/
test_*.php
debug-*.php

# Temporary files
*.tmp
*.bak
.DS_Store

# Archives
docs/archive/
```

## メリット

1. **見通しが良くなる**: 機能ごとに整理され、どこに何があるか明確
2. **保守性向上**: 関連ファイルが近くに配置される
3. **セキュリティ向上**: テストファイルが本番環境から分離
4. **UX改善**: 統合ダッシュボードで全機能に簡単アクセス
5. **ドキュメント管理**: MDファイルが整理され、必要な情報を探しやすい

## 注意点

- 後方互換性のため、既存URLへのリダイレクトが必要
- GitHub Actionsのデプロイ設定も更新必要
- 段階的に実施し、各Phaseでテスト

## 実施タイミング

今すぐ実施可能ですが、以下の順序を推奨:

1. まず **Phase 1（ドキュメント整理）** のみ実施 → 影響なし
2. ネットワーキング学習PDF機能を完成させる
3. Phase 2〜4を順次実施
