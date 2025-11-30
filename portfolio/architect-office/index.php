<?php
$page_title = "Home";
$page_description = "東京を拠点とする建築設計事務所。住宅から商業施設まで、空間に新たな価値を創造します。";
include('includes/header.php');
?>

<!-- Hero Section -->
<section id="top" class="lHero">
    <div class="lHero__image">
        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80" alt="Featured Project">
    </div>
    <div class="lHero__content">
        <h1 class="lHero__title">
            Creating Spaces<br>
            That Inspire
        </h1>
        <p class="lHero__subtitle">
            空間に、新しい価値を。<br>
            建築を通じて、人々の暮らしを豊かにします。
        </p>
    </div>
    <div class="lHero__scroll">
        <div class="lHero__scroll_line"></div>
        <span class="lHero__scroll_text">Scroll</span>
    </div>
</section>

<!-- About Section -->
<section id="about" class="lAbout">
    <div class="container">
        <div class="lAbout__grid">
            <div class="lAbout__image">
                <img src="https://images.unsplash.com/photo-1600585154363-67eb9e2e2099?w=800&q=80" alt="About Us">
            </div>
            <div class="lAbout__content">
                <div class="lAbout__label">About Us</div>
                <h2 class="lAbout__title">
                    建築を通じて、<br>
                    未来を創造する。
                </h2>
                <p class="lAbout__text">
                    私たちは、建築という手段を通じて、人々の暮らしに新しい価値を提供します。<br><br>

                    住宅、商業施設、公共施設まで、幅広いプロジェクトに携わり、クライアントの想いを形にしてきました。美しさと機能性を両立させ、時代を超えて愛される建築を目指しています。<br><br>

                    一つひとつのプロジェクトに真摯に向き合い、最高の空間を創り上げることが、私たちの使命です。
                </p>
                <div class="lAbout__link">
                    <a href="#contact" class="cButton cButton--primary">
                        Contact Us
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section id="projects" class="lProjects">
    <div class="container">
        <div class="lProjects__header">
            <div class="lProjects__label">Projects</div>
            <h2 class="lProjects__title">施工事例</h2>
        </div>

        <div class="lProjects__filter">
            <button class="lProjects__filter_button active" data-filter="all">All</button>
            <button class="lProjects__filter_button" data-filter="residential">Residential</button>
            <button class="lProjects__filter_button" data-filter="commercial">Commercial</button>
            <button class="lProjects__filter_button" data-filter="public">Public</button>
        </div>

        <div class="lProjects__grid">
            <!-- Project 1 -->
            <div class="lProjects__item" data-category="residential">
                <a href="#" class="lProjects__item_link">
                    <div class="lProjects__item_image">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80" alt="Forest House">
                    </div>
                    <div class="lProjects__item_content">
                        <h3 class="lProjects__item_title">Forest House</h3>
                        <p class="lProjects__item_location">神奈川県 / 住宅</p>
                    </div>
                </a>
            </div>

            <!-- Project 2 -->
            <div class="lProjects__item" data-category="commercial">
                <a href="#" class="lProjects__item_link">
                    <div class="lProjects__item_image">
                        <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800&q=80" alt="Modern Office">
                    </div>
                    <div class="lProjects__item_content">
                        <h3 class="lProjects__item_title">Modern Office</h3>
                        <p class="lProjects__item_location">東京都 / オフィス</p>
                    </div>
                </a>
            </div>

            <!-- Project 3 -->
            <div class="lProjects__item" data-category="residential">
                <a href="#" class="lProjects__item_link">
                    <div class="lProjects__item_image">
                        <img src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&q=80" alt="Light House">
                    </div>
                    <div class="lProjects__item_content">
                        <h3 class="lProjects__item_title">Light House</h3>
                        <p class="lProjects__item_location">東京都 / 住宅</p>
                    </div>
                </a>
            </div>

            <!-- Project 4 -->
            <div class="lProjects__item" data-category="public">
                <a href="#" class="lProjects__item_link">
                    <div class="lProjects__item_image">
                        <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=800&q=80" alt="Community Center">
                    </div>
                    <div class="lProjects__item_content">
                        <h3 class="lProjects__item_title">Community Center</h3>
                        <p class="lProjects__item_location">千葉県 / 公共施設</p>
                    </div>
                </a>
            </div>

            <!-- Project 5 -->
            <div class="lProjects__item" data-category="commercial">
                <a href="#" class="lProjects__item_link">
                    <div class="lProjects__item_image">
                        <img src="https://images.unsplash.com/photo-1600607686527-6fb886090705?w=800&q=80" alt="Cafe & Restaurant">
                    </div>
                    <div class="lProjects__item_content">
                        <h3 class="lProjects__item_title">Cafe & Restaurant</h3>
                        <p class="lProjects__item_location">東京都 / 商業施設</p>
                    </div>
                </a>
            </div>

            <!-- Project 6 -->
            <div class="lProjects__item" data-category="residential">
                <a href="#" class="lProjects__item_link">
                    <div class="lProjects__item_image">
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80" alt="Urban Residence">
                    </div>
                    <div class="lProjects__item_content">
                        <h3 class="lProjects__item_title">Urban Residence</h3>
                        <p class="lProjects__item_location">東京都 / 住宅</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section id="team" class="lTeam">
    <div class="container">
        <div class="lTeam__header">
            <div class="lTeam__label">Team</div>
            <h2 class="lTeam__title">私たちについて</h2>
        </div>

        <div class="lTeam__grid">
            <!-- Member 1 -->
            <div class="lTeam__member">
                <div class="lTeam__member_image">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=600&q=80" alt="代表取締役 / 一級建築士">
                </div>
                <div class="lTeam__member_info">
                    <h3 class="lTeam__member_name">山田 太郎</h3>
                    <p class="lTeam__member_role">代表取締役 / 一級建築士</p>
                    <p class="lTeam__member_bio">
                        東京大学建築学科卒業後、大手設計事務所を経て2015年に独立。人と自然が調和する建築を追求しています。
                    </p>
                </div>
            </div>

            <!-- Member 2 -->
            <div class="lTeam__member">
                <div class="lTeam__member_image">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&q=80" alt="パートナー / 一級建築士">
                </div>
                <div class="lTeam__member_info">
                    <h3 class="lTeam__member_name">佐藤 花子</h3>
                    <p class="lTeam__member_role">パートナー / 一級建築士</p>
                    <p class="lTeam__member_bio">
                        早稲田大学建築学科卒業。商業施設の設計を得意とし、機能性とデザイン性を両立させた空間づくりを心がけています。
                    </p>
                </div>
            </div>

            <!-- Member 3 -->
            <div class="lTeam__member">
                <div class="lTeam__member_image">
                    <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&q=80" alt="シニアアーキテクト">
                </div>
                <div class="lTeam__member_info">
                    <h3 class="lTeam__member_name">鈴木 一郎</h3>
                    <p class="lTeam__member_role">シニアアーキテクト</p>
                    <p class="lTeam__member_bio">
                        京都大学建築学科卒業。木造建築の技術を活かした、伝統と現代が融合する建築デザインを追求しています。
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="lContact">
    <div class="container">
        <div class="lContact__content">
            <div class="lContact__label">Contact</div>
            <h2 class="lContact__title">
                まずはお気軽に<br>
                ご相談ください
            </h2>
            <p class="lContact__text">
                住宅の新築、リノベーション、商業施設の設計など、<br class="md">
                どんなご相談でもお気軽にお問い合わせください。<br>
                経験豊富な建築士が、丁寧にご対応いたします。
            </p>

            <div class="lContact__buttons">
                <a href="tel:0312345678" class="cButton cButton--outline">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 16.92V19.92C22.0011 20.1985 21.9441 20.4742 21.8325 20.7293C21.7209 20.9845 21.5573 21.2136 21.3521 21.4019C21.1468 21.5901 20.9046 21.7335 20.6407 21.8227C20.3769 21.9119 20.0974 21.9451 19.82 21.92C16.7428 21.5856 13.787 20.5341 11.19 18.85C8.77382 17.3147 6.72533 15.2662 5.18999 12.85C3.49997 10.2412 2.44824 7.27097 2.11999 4.18C2.095 3.90347 2.12787 3.62476 2.21649 3.36162C2.30512 3.09849 2.44756 2.85669 2.63476 2.65162C2.82196 2.44655 3.0498 2.28271 3.30379 2.17052C3.55777 2.05833 3.83233 2.00026 4.10999 2H7.10999C7.5953 1.99522 8.06579 2.16708 8.43376 2.48353C8.80173 2.79999 9.04207 3.23945 9.10999 3.72C9.23662 4.68007 9.47144 5.62273 9.80999 6.53C9.94454 6.88792 9.97366 7.27691 9.8939 7.65088C9.81415 8.02485 9.62886 8.36811 9.35999 8.64L8.08999 9.91C9.51355 12.4135 11.5864 14.4864 14.09 15.91L15.36 14.64C15.6319 14.3711 15.9751 14.1858 16.3491 14.1061C16.7231 14.0263 17.1121 14.0555 17.47 14.19C18.3773 14.5286 19.3199 14.7634 20.28 14.89C20.7658 14.9585 21.2094 15.2032 21.5265 15.5775C21.8437 15.9518 22.0122 16.4296 22 16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    03-1234-5678
                </a>

                <a href="mailto:info@studio-architects.jp" class="cButton cButton--outline">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Email
                </a>
            </div>

            <div class="lContact__info">
                <p>
                    <strong>受付時間:</strong> 平日 9:00〜18:00<br>
                    <strong>定休日:</strong> 土日祝日
                </p>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>
