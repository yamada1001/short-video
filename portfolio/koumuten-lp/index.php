<?php
$page_title = "トップページ";
$page_description = "大阪で創業50年。想いをかたちに。家族が笑顔になる、あたたかい家づくりを心がけています。注文住宅、リフォーム、リノベーションまで、安心してお任せください。";
include('includes/header.php');
?>

<!-- メインビジュアル -->
<section class="lHero">
    <div class="lHero__bg">
        <div class="lHero__bg_image" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=90');"></div>
        <div class="lHero__bg_overlay"></div>
    </div>

    <div class="lHero__inner">
        <div class="lHero__content">
            <h1 class="lHero__title">
                <span class="lHero__title_main">想いを、かたちに。</span>
                <span class="lHero__title_sub">家族が笑顔になる、あたたかい家づくり。</span>
            </h1>

            <p class="lHero__text">
                大阪で創業50年。<br class="sp">お客様一人ひとりに寄り添った、<br>
                世界でたったひとつの「我が家」をつくりあげます。
            </p>

            <div class="lHero__buttons">
                <a href="#contact" class="lHero__button lHero__button--primary">
                    <span class="lHero__button_text">無料相談はこちら</span>
                    <span class="lHero__button_icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
                <a href="#works" class="lHero__button lHero__button--secondary">
                    <span class="lHero__button_text">施工事例を見る</span>
                </a>
            </div>
        </div>

        <div class="lHero__scroll">
            <div class="lHero__scroll_text">SCROLL</div>
            <div class="lHero__scroll_line"></div>
        </div>
    </div>

    <!-- 装飾用ヘキサゴン -->
    <div class="lHero__decoration">
        <div class="lHero__decoration_hexagon lHero__decoration_hexagon--1"></div>
        <div class="lHero__decoration_hexagon lHero__decoration_hexagon--2"></div>
        <div class="lHero__decoration_hexagon lHero__decoration_hexagon--3"></div>
    </div>
</section>

<!-- 想いセクション -->
<section id="about" class="lAbout">
    <div class="lAbout__inner">
        <div class="lAbout__header">
            <div class="cHeading">
                <div class="cHeading__en">PHILOSOPHY</div>
                <h2 class="cHeading__ja">想い</h2>
            </div>
        </div>

        <div class="lAbout__content">
            <div class="lAbout__text">
                <div class="lAbout__lead">
                    木のぬくもりを感じる、あたたかい家づくり。<br>
                    家族が笑顔で集まる、心地よい空間。
                </div>
                <p class="lAbout__paragraph">
                    お客様一人ひとりの「理想の暮らし」に耳を傾けながら、<br>
                    世界でたったひとつの「我が家」をつくりあげます。
                </p>
                <p class="lAbout__paragraph">
                    創業50年、大阪でたくさんのご家族と共に歩んできた私たち。<br>
                    確かな技術と、暮らしへの深い想いで、<br>
                    あなたの夢を実現する家づくりをお手伝いします。
                </p>
            </div>

            <div class="lAbout__image">
                <div class="lAbout__image_wrapper">
                    <img src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&q=80" alt="木のぬくもりを感じる空間" loading="lazy">
                </div>
            </div>
        </div>

        <div class="lAbout__features">
            <div class="lAbout__feature">
                <div class="lAbout__feature_number">01</div>
                <h3 class="lAbout__feature_title">自然素材へのこだわり</h3>
                <p class="lAbout__feature_text">
                    無垢材や漆喰など、厳選した自然素材を使用。家族の健康と、長く住み続けられる家づくりを大切にしています。
                </p>
            </div>

            <div class="lAbout__feature">
                <div class="lAbout__feature_number">02</div>
                <h3 class="lAbout__feature_title">職人の確かな技術</h3>
                <p class="lAbout__feature_text">
                    創業以来培ってきた伝統の技術と、最新の工法を融合。細部までこだわり抜いた、質の高い家づくりを実現します。
                </p>
            </div>

            <div class="lAbout__feature">
                <div class="lAbout__feature_number">03</div>
                <h3 class="lAbout__feature_title">一生涯のパートナー</h3>
                <p class="lAbout__feature_text">
                    家づくりは完成がゴールではありません。定期的なメンテナンスやリフォームまで、一生涯お付き合いさせていただきます。
                </p>
            </div>
        </div>
    </div>

    <!-- 装飾 -->
    <div class="lAbout__decoration">
        <div class="lAbout__decoration_hexagon lAbout__decoration_hexagon--1"></div>
        <div class="lAbout__decoration_hexagon lAbout__decoration_hexagon--2"></div>
    </div>
</section>

<!-- 施工事例セクション -->
<section id="works" class="lWorks">
    <div class="lWorks__inner">
        <div class="lWorks__header">
            <div class="cHeading cHeading--center">
                <div class="cHeading__en">WORKS</div>
                <h2 class="cHeading__ja">施工事例</h2>
            </div>
        </div>

        <div class="lWorks__grid">
            <!-- 事例1 -->
            <article class="lWorks__item">
                <a href="#" class="lWorks__item_link">
                    <div class="lWorks__item_image">
                        <img src="https://images.unsplash.com/photo-1600607687644-c7171b42498b?w=800&q=80" alt="木のぬくもりを感じる北欧スタイルの家" loading="lazy">
                        <div class="lWorks__item_overlay">
                            <span class="lWorks__item_more">詳しく見る</span>
                        </div>
                    </div>
                    <div class="lWorks__item_content">
                        <h3 class="lWorks__item_title">木のぬくもりを感じる、北欧スタイルの家</h3>
                        <div class="lWorks__item_meta">
                            <span class="lWorks__item_location">大阪市北区</span>
                            <span class="lWorks__item_type">新築注文住宅</span>
                        </div>
                    </div>
                </a>
            </article>

            <!-- 事例2 -->
            <article class="lWorks__item">
                <a href="#" class="lWorks__item_link">
                    <div class="lWorks__item_image">
                        <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800&q=80" alt="家族が集まる、広々リビングの家" loading="lazy">
                        <div class="lWorks__item_overlay">
                            <span class="lWorks__item_more">詳しく見る</span>
                        </div>
                    </div>
                    <div class="lWorks__item_content">
                        <h3 class="lWorks__item_title">家族が集まる、広々リビングの家</h3>
                        <div class="lWorks__item_meta">
                            <span class="lWorks__item_location">大阪市中央区</span>
                            <span class="lWorks__item_type">リノベーション</span>
                        </div>
                    </div>
                </a>
            </article>

            <!-- 事例3 -->
            <article class="lWorks__item">
                <a href="#" class="lWorks__item_link">
                    <div class="lWorks__item_image">
                        <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=800&q=80" alt="自然光が注ぐ、開放的な二階建て" loading="lazy">
                        <div class="lWorks__item_overlay">
                            <span class="lWorks__item_more">詳しく見る</span>
                        </div>
                    </div>
                    <div class="lWorks__item_content">
                        <h3 class="lWorks__item_title">自然光が注ぐ、開放的な二階建て</h3>
                        <div class="lWorks__item_meta">
                            <span class="lWorks__item_location">大阪府吹田市</span>
                            <span class="lWorks__item_type">新築注文住宅</span>
                        </div>
                    </div>
                </a>
            </article>

            <!-- 事例4 -->
            <article class="lWorks__item">
                <a href="#" class="lWorks__item_link">
                    <div class="lWorks__item_image">
                        <img src="https://images.unsplash.com/photo-1600607686527-6fb886090705?w=800&q=80" alt="伝統と現代が調和する和モダンの家" loading="lazy">
                        <div class="lWorks__item_overlay">
                            <span class="lWorks__item_more">詳しく見る</span>
                        </div>
                    </div>
                    <div class="lWorks__item_content">
                        <h3 class="lWorks__item_title">伝統と現代が調和する和モダンの家</h3>
                        <div class="lWorks__item_meta">
                            <span class="lWorks__item_location">大阪市天王寺区</span>
                            <span class="lWorks__item_type">リフォーム</span>
                        </div>
                    </div>
                </a>
            </article>

            <!-- 事例5 -->
            <article class="lWorks__item">
                <a href="#" class="lWorks__item_link">
                    <div class="lWorks__item_image">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&q=80" alt="中庭のある平屋の家" loading="lazy">
                        <div class="lWorks__item_overlay">
                            <span class="lWorks__item_more">詳しく見る</span>
                        </div>
                    </div>
                    <div class="lWorks__item_content">
                        <h3 class="lWorks__item_title">中庭のある平屋の家</h3>
                        <div class="lWorks__item_meta">
                            <span class="lWorks__item_location">大阪府豊中市</span>
                            <span class="lWorks__item_type">新築注文住宅</span>
                        </div>
                    </div>
                </a>
            </article>

            <!-- 事例6 -->
            <article class="lWorks__item">
                <a href="#" class="lWorks__item_link">
                    <div class="lWorks__item_image">
                        <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800&q=80" alt="スキップフロアで空間を楽しむ家" loading="lazy">
                        <div class="lWorks__item_overlay">
                            <span class="lWorks__item_more">詳しく見る</span>
                        </div>
                    </div>
                    <div class="lWorks__item_content">
                        <h3 class="lWorks__item_title">スキップフロアで空間を楽しむ家</h3>
                        <div class="lWorks__item_meta">
                            <span class="lWorks__item_location">大阪市西区</span>
                            <span class="lWorks__item_type">新築注文住宅</span>
                        </div>
                    </div>
                </a>
            </article>
        </div>

        <div class="lWorks__more">
            <a href="#" class="cButton cButton--outline">
                <span class="cButton__text">施工事例をもっと見る</span>
                <span class="cButton__icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>

<!-- お客様の声セクション -->
<section id="voice" class="lVoice">
    <div class="lVoice__inner">
        <div class="lVoice__header">
            <div class="cHeading cHeading--center">
                <div class="cHeading__en">VOICE</div>
                <h2 class="cHeading__ja">お客様の声</h2>
            </div>
        </div>

        <div class="lVoice__slider">
            <!-- 声1 -->
            <div class="lVoice__item">
                <div class="lVoice__item_content">
                    <div class="lVoice__item_quote">
                        <p class="lVoice__item_text">
                            初めての家づくりで不安でしたが、担当の方が丁寧に説明してくださり、安心してお任せできました。想像以上の仕上がりに、家族全員大満足です！毎日家に帰るのが楽しみになりました。
                        </p>
                    </div>
                    <div class="lVoice__item_meta">
                        <div class="lVoice__item_name">K様ご家族</div>
                        <div class="lVoice__item_info">大阪市北区 / 新築注文住宅</div>
                    </div>
                </div>
                <div class="lVoice__item_image">
                    <img src="https://images.unsplash.com/photo-1600607687644-c7171b42498b?w=600&q=80" alt="K様邸" loading="lazy">
                </div>
            </div>

            <!-- 声2 -->
            <div class="lVoice__item">
                <div class="lVoice__item_content">
                    <div class="lVoice__item_quote">
                        <p class="lVoice__item_text">
                            古い家をリノベーションしていただきました。昔の良さを残しながら、現代的な快適さも加えていただき、大変満足しています。職人さんの技術の高さに感動しました。
                        </p>
                    </div>
                    <div class="lVoice__item_meta">
                        <div class="lVoice__item_name">M様ご夫婦</div>
                        <div class="lVoice__item_info">大阪市中央区 / リノベーション</div>
                    </div>
                </div>
                <div class="lVoice__item_image">
                    <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=600&q=80" alt="M様邸" loading="lazy">
                </div>
            </div>

            <!-- 声3 -->
            <div class="lVoice__item">
                <div class="lVoice__item_content">
                    <div class="lVoice__item_quote">
                        <p class="lVoice__item_text">
                            細部までこだわりを実現していただきました。無垢材の床や漆喰の壁など、自然素材をふんだんに使った家は、本当に居心地が良いです。アフターフォローも手厚く、安心です。
                        </p>
                    </div>
                    <div class="lVoice__item_meta">
                        <div class="lVoice__item_name">S様</div>
                        <div class="lVoice__item_info">大阪府吹田市 / 新築注文住宅</div>
                    </div>
                </div>
                <div class="lVoice__item_image">
                    <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=600&q=80" alt="S様邸" loading="lazy">
                </div>
            </div>
        </div>
    </div>

    <!-- 装飾 -->
    <div class="lVoice__decoration">
        <div class="lVoice__decoration_hexagon lVoice__decoration_hexagon--1"></div>
    </div>
</section>

<!-- 家づくりの流れセクション -->
<section id="flow" class="lFlow">
    <div class="lFlow__inner">
        <div class="lFlow__header">
            <div class="cHeading cHeading--center">
                <div class="cHeading__en">FLOW</div>
                <h2 class="cHeading__ja">家づくりの流れ</h2>
            </div>
        </div>

        <div class="lFlow__steps">
            <!-- ステップ1 -->
            <div class="lFlow__step">
                <div class="lFlow__step_number">STEP 01</div>
                <h3 class="lFlow__step_title">無料相談・ヒアリング</h3>
                <p class="lFlow__step_text">
                    まずはお気軽にご相談ください。理想の暮らし、ご予算、スケジュールなど、じっくりお話を伺います。
                </p>
            </div>

            <!-- ステップ2 -->
            <div class="lFlow__step">
                <div class="lFlow__step_number">STEP 02</div>
                <h3 class="lFlow__step_title">プランニング・お見積もり</h3>
                <p class="lFlow__step_text">
                    ヒアリング内容をもとに、プランと概算お見積もりをご提案。納得いくまで何度でもご相談いただけます。
                </p>
            </div>

            <!-- ステップ3 -->
            <div class="lFlow__step">
                <div class="lFlow__step_number">STEP 03</div>
                <h3 class="lFlow__step_title">ご契約</h3>
                <p class="lFlow__step_text">
                    プラン・お見積もりにご納得いただけましたら、ご契約となります。詳細な設計図面の作成に入ります。
                </p>
            </div>

            <!-- ステップ4 -->
            <div class="lFlow__step">
                <div class="lFlow__step_number">STEP 04</div>
                <h3 class="lFlow__step_title">詳細設計・仕様決定</h3>
                <p class="lFlow__step_text">
                    設備や素材など、細部まで一緒に決めていきます。ショールーム見学なども行います。
                </p>
            </div>

            <!-- ステップ5 -->
            <div class="lFlow__step">
                <div class="lFlow__step_number">STEP 05</div>
                <h3 class="lFlow__step_title">着工・施工</h3>
                <p class="lFlow__step_text">
                    いよいよ着工です。定期的に現場をご確認いただき、進捗をご報告します。
                </p>
            </div>

            <!-- ステップ6 -->
            <div class="lFlow__step">
                <div class="lFlow__step_number">STEP 06</div>
                <h3 class="lFlow__step_title">完成・お引き渡し</h3>
                <p class="lFlow__step_text">
                    最終検査を行い、お引き渡しとなります。アフターフォローも万全ですので、ご安心ください。
                </p>
            </div>
        </div>

        <div class="lFlow__cta">
            <div class="lFlow__cta_text">まずはお気軽にご相談ください</div>
            <a href="#contact" class="cButton cButton--primary">
                <span class="cButton__text">無料相談はこちら</span>
                <span class="cButton__icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>

<!-- お問い合わせセクション -->
<section id="contact" class="lContact">
    <div class="lContact__inner">
        <div class="lContact__header">
            <div class="cHeading cHeading--center cHeading--white">
                <div class="cHeading__en">CONTACT</div>
                <h2 class="cHeading__ja">お問い合わせ</h2>
            </div>
        </div>

        <div class="lContact__content">
            <div class="lContact__methods">
                <!-- 電話 -->
                <div class="lContact__method">
                    <div class="lContact__method_icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 16.92V19.92C22.0011 20.1985 21.9441 20.4742 21.8325 20.7293C21.7209 20.9845 21.5573 21.2136 21.3521 21.4019C21.1468 21.5901 20.9046 21.7335 20.6407 21.8227C20.3769 21.9119 20.0974 21.9451 19.82 21.92C16.7428 21.5856 13.787 20.5341 11.19 18.85C8.77382 17.3147 6.72533 15.2662 5.18999 12.85C3.49997 10.2412 2.44824 7.27097 2.11999 4.18C2.095 3.90347 2.12787 3.62476 2.21649 3.36162C2.30512 3.09849 2.44756 2.85669 2.63476 2.65162C2.82196 2.44655 3.0498 2.28271 3.30379 2.17052C3.55777 2.05833 3.83233 2.00026 4.10999 2H7.10999C7.5953 1.99522 8.06579 2.16708 8.43376 2.48353C8.80173 2.79999 9.04207 3.23945 9.10999 3.72C9.23662 4.68007 9.47144 5.62273 9.80999 6.53C9.94454 6.88792 9.97366 7.27691 9.8939 7.65088C9.81415 8.02485 9.62886 8.36811 9.35999 8.64L8.08999 9.91C9.51355 12.4135 11.5864 14.4864 14.09 15.91L15.36 14.64C15.6319 14.3711 15.9751 14.1858 16.3491 14.1061C16.7231 14.0263 17.1121 14.0555 17.47 14.19C18.3773 14.5286 19.3199 14.7634 20.28 14.89C20.7658 14.9585 21.2094 15.2032 21.5265 15.5775C21.8437 15.9518 22.0122 16.4296 22 16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="lContact__method_title">お電話でのお問い合わせ</h3>
                    <div class="lContact__method_tel">
                        <a href="tel:0612345678">06-1234-5678</a>
                    </div>
                    <div class="lContact__method_time">
                        受付時間：9:00〜18:00（水曜定休）
                    </div>
                </div>

                <!-- メール -->
                <div class="lContact__method">
                    <div class="lContact__method_icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="lContact__method_title">メールでのお問い合わせ</h3>
                    <div class="lContact__method_note">
                        24時間受付中<br>
                        2営業日以内にご返信いたします
                    </div>
                    <a href="mailto:info@yamada-koumuten.jp" class="cButton cButton--white">
                        <span class="cButton__text">メールフォームへ</span>
                        <span class="cButton__icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <div class="lContact__note">
                ※ご相談・お見積もりは無料です。お気軽にお問い合わせください。
            </div>
        </div>
    </div>

    <!-- 装飾 -->
    <div class="lContact__decoration">
        <div class="lContact__decoration_hexagon lContact__decoration_hexagon--1"></div>
        <div class="lContact__decoration_hexagon lContact__decoration_hexagon--2"></div>
    </div>
</section>

<?php include('includes/footer.php'); ?>
