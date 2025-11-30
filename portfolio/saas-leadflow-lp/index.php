<?php
// LeadFlow - Awwwards-style Ultra Rich LP
http_response_code(200);

// メタデータ
$page_title = 'LeadFlow - Next Generation CRM Platform';
$page_description = 'Experience the future of sales management. A showcase of cutting-edge web technology and interaction design.';
$gtm_id = 'GTM-T7NGQDC2';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- Google Tag Manager -->
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    </script>
    <script defer src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($gtm_id); ?>"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- External Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js"></script>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Loading Screen -->
    <div class="loader" id="loader">
        <div class="loader__content">
            <div class="loader__logo">LeadFlow</div>
            <div class="loader__progress">
                <div class="loader__progress-bar" id="loaderProgress"></div>
            </div>
            <div class="loader__text">Loading Experience...</div>
        </div>
    </div>

    <!-- Custom Cursor -->
    <div class="cursor" id="cursor"></div>
    <div class="cursor-follower" id="cursorFollower"></div>

    <!-- Three.js Background Canvas -->
    <canvas id="webgl"></canvas>

    <!-- Main Content -->
    <div class="main-wrapper" id="mainWrapper">

        <!-- Navigation -->
        <nav class="nav">
            <div class="nav__logo">LeadFlow</div>
            <div class="nav__links">
                <a href="#features" class="nav__link">Features</a>
                <a href="#demo" class="nav__link">Demo</a>
                <a href="#pricing" class="nav__link">Pricing</a>
                <a href="#contact" class="nav__link nav__link--cta">Get Started</a>
            </div>
            <button class="nav__burger" id="navBurger">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>

        <!-- Hero Section -->
        <section class="section hero" id="hero">
            <div class="hero__content">
                <h1 class="hero__title">
                    <span class="hero__title-line">Transform</span>
                    <span class="hero__title-line">Your Sales</span>
                    <span class="hero__title-line hero__title-line--gradient">Performance</span>
                </h1>
                <p class="hero__subtitle">
                    Next-generation CRM platform powered by AI.<br>
                    Built for teams that demand excellence.
                </p>
                <div class="hero__cta">
                    <button class="btn btn--primary">
                        <span>Start Free Trial</span>
                        <svg width="20" height="20" viewBox="0 0 20 20">
                            <path d="M7 3L14 10L7 17" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                    </button>
                    <button class="btn btn--secondary">Watch Demo</button>
                </div>
                <div class="hero__scroll">
                    <span>Scroll to explore</span>
                    <div class="hero__scroll-icon"></div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="section stats" id="stats">
            <div class="stats__grid">
                <div class="stat-item">
                    <div class="stat-item__number" data-count="10000">0</div>
                    <div class="stat-item__label">Active Users</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item__number" data-count="98">0</div>
                    <div class="stat-item__label">Satisfaction Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item__number" data-count="45">0</div>
                    <div class="stat-item__label">Countries</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item__number" data-count="24">0</div>
                    <div class="stat-item__label">7 Support</div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="section features" id="features">
            <div class="section-header">
                <h2 class="section-title">Powerful Features</h2>
                <p class="section-subtitle">Everything you need to scale your sales</p>
            </div>
            <div class="features__grid">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48">
                            <rect width="48" height="48" rx="12" fill="url(#grad1)"/>
                            <path d="M16 24L22 30L32 20" stroke="white" stroke-width="3" fill="none"/>
                            <defs>
                                <linearGradient id="grad1" x1="0" y1="0" x2="48" y2="48">
                                    <stop offset="0%" stop-color="#667eea"/>
                                    <stop offset="100%" stop-color="#764ba2"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">AI-Powered Insights</h3>
                    <p class="feature-card__desc">Machine learning algorithms predict customer behavior and optimize your sales pipeline automatically.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48">
                            <rect width="48" height="48" rx="12" fill="url(#grad2)"/>
                            <circle cx="24" cy="24" r="8" stroke="white" stroke-width="3" fill="none"/>
                            <defs>
                                <linearGradient id="grad2" x1="0" y1="0" x2="48" y2="48">
                                    <stop offset="0%" stop-color="#f093fb"/>
                                    <stop offset="100%" stop-color="#f5576c"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">Real-time Analytics</h3>
                    <p class="feature-card__desc">Monitor your team's performance with beautiful dashboards and instant notifications.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48">
                            <rect width="48" height="48" rx="12" fill="url(#grad3)"/>
                            <path d="M14 24H34M24 14V34" stroke="white" stroke-width="3"/>
                            <defs>
                                <linearGradient id="grad3" x1="0" y1="0" x2="48" y2="48">
                                    <stop offset="0%" stop-color="#4facfe"/>
                                    <stop offset="100%" stop-color="#00f2fe"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">Seamless Integration</h3>
                    <p class="feature-card__desc">Connect with 1000+ apps and tools you already use. API-first architecture for developers.</p>
                </div>
            </div>
        </section>

        <!-- Demo Section -->
        <section class="section demo" id="demo">
            <div class="demo__content">
                <div class="demo__text">
                    <h2 class="demo__title">See it in action</h2>
                    <p class="demo__description">
                        Experience the power of LeadFlow with our interactive demo.
                        No signup required.
                    </p>
                    <button class="btn btn--primary">Launch Demo</button>
                </div>
                <div class="demo__visual">
                    <div class="demo__screen">
                        <!-- Placeholder for demo visual -->
                        <div class="demo__screen-inner"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="section pricing" id="pricing">
            <div class="section-header">
                <h2 class="section-title">Simple Pricing</h2>
                <p class="section-subtitle">Choose the plan that fits your team</p>
            </div>
            <div class="pricing__grid">
                <div class="pricing-card">
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">Starter</h3>
                        <div class="pricing-card__price">
                            <span class="price-currency">¥</span>
                            <span class="price-amount">9,800</span>
                            <span class="price-period">/month</span>
                        </div>
                    </div>
                    <ul class="pricing-card__features">
                        <li>Up to 5 users</li>
                        <li>Basic CRM features</li>
                        <li>Email support</li>
                        <li>1GB storage</li>
                    </ul>
                    <button class="btn btn--outline">Get Started</button>
                </div>
                <div class="pricing-card pricing-card--featured">
                    <div class="pricing-card__badge">Popular</div>
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">Professional</h3>
                        <div class="pricing-card__price">
                            <span class="price-currency">¥</span>
                            <span class="price-amount">24,800</span>
                            <span class="price-period">/month</span>
                        </div>
                    </div>
                    <ul class="pricing-card__features">
                        <li>Up to 20 users</li>
                        <li>Advanced analytics</li>
                        <li>Priority support</li>
                        <li>50GB storage</li>
                        <li>API access</li>
                    </ul>
                    <button class="btn btn--primary">Get Started</button>
                </div>
                <div class="pricing-card">
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">Enterprise</h3>
                        <div class="pricing-card__price">
                            <span class="price-amount">Custom</span>
                        </div>
                    </div>
                    <ul class="pricing-card__features">
                        <li>Unlimited users</li>
                        <li>Custom integrations</li>
                        <li>Dedicated support</li>
                        <li>Unlimited storage</li>
                        <li>SLA guarantee</li>
                    </ul>
                    <button class="btn btn--outline">Contact Sales</button>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section cta" id="contact">
            <div class="cta__content">
                <h2 class="cta__title">Ready to transform your sales?</h2>
                <p class="cta__subtitle">Start your 14-day free trial. No credit card required.</p>
                <form class="cta__form">
                    <input type="email" class="cta__input" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn--primary">Start Free Trial</button>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer__content">
                <div class="footer__brand">
                    <div class="footer__logo">LeadFlow</div>
                    <p class="footer__tagline">Next-generation CRM platform</p>
                </div>
                <div class="footer__links">
                    <div class="footer__column">
                        <h4>Product</h4>
                        <a href="#features">Features</a>
                        <a href="#pricing">Pricing</a>
                        <a href="#demo">Demo</a>
                    </div>
                    <div class="footer__column">
                        <h4>Company</h4>
                        <a href="#">About</a>
                        <a href="#">Blog</a>
                        <a href="#">Careers</a>
                    </div>
                    <div class="footer__column">
                        <h4>Support</h4>
                        <a href="#">Help Center</a>
                        <a href="#">Contact</a>
                        <a href="#">API Docs</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2024 LeadFlow. All rights reserved.</p>
            </div>
        </footer>

    </div>

    <script src="assets/js/awwwards.js"></script>
</body>
</html>
