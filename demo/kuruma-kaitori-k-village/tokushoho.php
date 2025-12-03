<?php
/**
 * Specified Commercial Transaction Act Page (特定商取引法ページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/includes/functions.php';

// ヘッダー読み込み
$page = 'tokushoho';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Tokushoho Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">
            <i class="fa-solid fa-scale-balanced"></i>
            特定商取引法に基づく表記
        </h1>
        <p class="page-hero__lead">
            法令に基づく販売事業者情報
        </p>
    </div>
</section>

<!-- Tokushoho Content Section -->
<section class="section policy-section">
    <div class="container">
        <div class="policy-content">

            <div class="policy-intro">
                <p>
                    特定商取引法に基づき、以下のとおり表示いたします。
                </p>
            </div>

            <table class="tokushoho-table">
                <tbody>
                    <tr>
                        <th>販売業者</th>
                        <td><?php echo COMPANY_NAME; ?></td>
                    </tr>
                    <tr>
                        <th>古物商許可番号</th>
                        <td><?php echo LICENSE_NUMBER; ?></td>
                    </tr>
                    <tr>
                        <th>所在地</th>
                        <td>
                            <?php echo POSTAL_CODE; ?><br>
                            <?php echo ADDRESS; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td>
                            <a href="tel:<?php echo TEL; ?>"><?php echo TEL_DISPLAY; ?></a>
                        </td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td>
                            <a href="mailto:<?php echo EMAIL; ?>"><?php echo EMAIL; ?></a>
                        </td>
                    </tr>
                    <tr>
                        <th>営業時間</th>
                        <td>
                            平日: 9:00〜18:00<br>
                            土曜: 9:00〜17:00<br>
                            定休日: 日曜・祝日
                        </td>
                    </tr>
                    <tr>
                        <th>販売価格</th>
                        <td>
                            各車両・サービスの詳細ページに記載しております。<br>
                            価格は全て税込表示となります。
                        </td>
                    </tr>
                    <tr>
                        <th>代金の支払方法</th>
                        <td>
                            <ul>
                                <li>現金一括払い</li>
                                <li>銀行振込</li>
                                <li>各種オートローン</li>
                                <li>クレジットカード（一部サービスのみ）</li>
                            </ul>
                            ※詳細は店頭またはお問い合わせにてご確認ください。
                        </td>
                    </tr>
                    <tr>
                        <th>代金の支払時期</th>
                        <td>
                            <strong>【車両購入の場合】</strong><br>
                            契約時に手付金、納車時に残金をお支払いいただきます。<br>
                            オートローンをご利用の場合は、ローン会社との契約に基づきます。<br><br>

                            <strong>【車検・整備・板金の場合】</strong><br>
                            作業完了後、お車お引き取り時にお支払いいただきます。
                        </td>
                    </tr>
                    <tr>
                        <th>商品の引渡時期</th>
                        <td>
                            <strong>【新車販売】</strong><br>
                            ご契約後、メーカーの生産状況により1〜3ヶ月程度<br><br>

                            <strong>【中古車販売】</strong><br>
                            ご契約後、整備・名義変更等を経て、1〜2週間程度<br><br>

                            <strong>【車検・整備】</strong><br>
                            作業内容により異なりますが、通常1〜3日程度<br><br>

                            ※詳細は個別にご案内いたします。
                        </td>
                    </tr>
                    <tr>
                        <th>返品・キャンセルについて</th>
                        <td>
                            <strong>【車両購入の場合】</strong><br>
                            契約締結後のお客様都合による返品・キャンセルは原則としてお受けできません。<br>
                            ただし、車両に重大な瑕疵があった場合は、契約不適合責任に基づき対応いたします。<br><br>

                            <strong>【車検・整備・板金の場合】</strong><br>
                            作業開始前であればキャンセル可能です。<br>
                            作業開始後のキャンセルは、既に発生した費用をご負担いただきます。<br><br>

                            詳細は契約書面をご確認いただくか、お問い合わせください。
                        </td>
                    </tr>
                    <tr>
                        <th>不良品の対応</th>
                        <td>
                            商品に瑕疵がある場合、または説明と著しく異なる場合は、
                            速やかに当店までご連絡ください。<br>
                            状況を確認の上、修理、交換、返金等の対応をさせていただきます。<br>
                            ただし、中古車の性質上、経年劣化による不具合は対象外となる場合があります。
                        </td>
                    </tr>
                    <tr>
                        <th>保証について</th>
                        <td>
                            <strong>【新車】</strong><br>
                            メーカー保証が適用されます。<br><br>

                            <strong>【中古車】</strong><br>
                            車両により保証内容が異なります。詳細は各車両ページまたは店頭でご確認ください。<br>
                            有償保証プランもご用意しております。<br><br>

                            <strong>【車検・整備】</strong><br>
                            作業内容に応じた保証を提供いたします。詳細はお見積り時にご説明いたします。
                        </td>
                    </tr>
                    <tr>
                        <th>免許・資格</th>
                        <td>
                            古物商許可番号: <?php echo LICENSE_NUMBER; ?><br>
                            （大分県公安委員会）
                        </td>
                    </tr>
                    <tr>
                        <th>個人情報の取り扱い</th>
                        <td>
                            お客様からお預かりした個人情報は、当店の<a href="<?php echo url('privacy'); ?>">プライバシーポリシー</a>に従って適切に管理いたします。
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="policy-footer">
                <p>
                    ご不明な点がございましたら、お気軽に<a href="<?php echo url('contact'); ?>">お問い合わせ</a>ください。
                </p>
            </div>

        </div>
    </div>
</section>

<!-- CTA Contact Section -->
<?php require_once __DIR__ . '/sections/cta-contact.php'; ?>

<?php
// フッター読み込み
require_once __DIR__ . '/includes/footer.php';
?>
