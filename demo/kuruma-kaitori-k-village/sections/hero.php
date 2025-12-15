<!-- Hero Section - Tailwind CSS + Alpine.js - One Screen Optimized -->
<section class="tw-relative tw-h-screen tw-flex tw-items-center tw-overflow-hidden" x-data="{}">
    <!-- Background Image -->
    <div class="tw-absolute tw-inset-0 tw-z-0">
        <img
            src="https://lh3.googleusercontent.com/gps-cs-s/AG0ilSwfJZeJbu5T37bt6g5WaOeuar19YAoFXV3SZU_eY9POZ3g4r5WiA4VFoVfdg_4luqLbtd4LAiwPdi2COmKsJeujocyo2-fp6_rglWjZf3j3n9U-fuTt5BiufPBc5t3mm6cupxqMljma65EJ=s680-w680-h510-rw"
            alt="くるま買取ケイヴィレッジ 店舗外観"
            class="tw-w-full tw-h-full tw-object-cover tw-brightness-[0.35]"
            loading="eager"
        >
        <!-- Gradient Overlay -->
        <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-br tw-from-primary/70 tw-via-secondary/60 tw-to-primary/50 tw-mix-blend-multiply"></div>
    </div>

    <!-- Content Container -->
    <div class="container tw-relative tw-z-10 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-h-full tw-flex tw-items-center">
        <div class="tw-grid lg:tw-grid-cols-[1.2fr_0.8fr] tw-gap-8 lg:tw-gap-12 tw-items-center tw-w-full">

            <!-- Left Column - Text Content -->
            <div class="tw-text-white tw-space-y-6"
                 x-data="{ show: false }"
                 x-init="setTimeout(() => show = true, 100)"
                 :class="show ? 'tw-opacity-100 tw-translate-y-0' : 'tw-opacity-0 tw-translate-y-8'"
                 class="tw-transition-all tw-duration-1000 tw-ease-out">

                <!-- Badge -->
                <div class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-white/20 tw-backdrop-blur-md tw-rounded-full tw-border tw-border-white/30">
                    <i class="fa-solid fa-award tw-text-yellow-400"></i>
                    <span class="tw-text-sm tw-font-bold">大分市で30年以上の実績</span>
                </div>

                <!-- Title -->
                <div>
                    <h1 class="tw-text-3xl md:tw-text-5xl lg:tw-text-6xl tw-font-black tw-leading-tight">
                        <span class="tw-block tw-text-xl md:tw-text-2xl tw-font-medium tw-mb-2 tw-tracking-wide">愛車を高く売るなら</span>
                        <span class="tw-block tw-bg-gradient-to-r tw-from-white tw-to-white/90 tw-bg-clip-text tw-text-transparent tw-drop-shadow-2xl">
                            ケイヴィレッジ
                        </span>
                    </h1>
                </div>

                <!-- Lead Text -->
                <p class="tw-text-lg md:tw-text-xl tw-leading-relaxed">
                    <span class="tw-text-4xl md:tw-text-5xl tw-font-black tw-text-yellow-400 tw-drop-shadow-[0_0_20px_rgba(255,215,0,0.5)]">90秒</span>
                    <span>でカンタン無料査定</span>
                </p>

                <!-- CTA Buttons - 最優先 -->
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-gap-4">
                    <a href="<?php echo url('kaitori'); ?>"
                       class="tw-group tw-inline-flex tw-items-center tw-justify-center tw-gap-3 tw-px-10 tw-py-6 tw-bg-gradient-to-br tw-from-primary tw-to-orange-600 tw-rounded-2xl tw-shadow-2xl hover:tw-shadow-primary/50 tw-transition-all tw-duration-300 hover:tw-scale-110 hover:tw--translate-y-2">
                        <i class="fa-solid fa-clipboard-check tw-text-4xl"></i>
                        <div class="tw-text-left">
                            <div class="tw-text-2xl tw-font-black">無料査定</div>
                            <div class="tw-text-sm tw-opacity-90">90秒で完了</div>
                        </div>
                    </a>

                    <a href="tel:<?php echo PHONE_LINK; ?>"
                       class="tw-group tw-inline-flex tw-items-center tw-justify-center tw-gap-3 tw-px-8 tw-py-6 tw-bg-white/20 tw-backdrop-blur-md tw-rounded-2xl tw-border-2 tw-border-white/30 tw-shadow-xl hover:tw-bg-white/30 tw-transition-all tw-duration-300 hover:tw-scale-105">
                        <i class="fa-solid fa-phone tw-text-3xl tw-animate-pulse"></i>
                        <div class="tw-text-left">
                            <div class="tw-text-xl tw-font-black"><?php echo PHONE; ?></div>
                            <div class="tw-text-xs tw-opacity-90"><?php echo BUSINESS_HOURS; ?></div>
                        </div>
                    </a>
                </div>

                <!-- Mini Stats - シンプルに -->
                <div class="tw-flex tw-gap-6 tw-text-sm">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa-solid fa-circle-check tw-text-yellow-400"></i>
                        <span>30年+の実績</span>
                    </div>
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa-solid fa-circle-check tw-text-yellow-400"></i>
                        <span>即日買取OK</span>
                    </div>
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa-solid fa-circle-check tw-text-yellow-400"></i>
                        <span>出張査定無料</span>
                    </div>
                </div>
            </div>

            <!-- Right Column - YouTube Video (Compact) -->
            <div class="tw-hidden lg:tw-block tw-relative"
                 x-data="{ show: false }"
                 x-init="setTimeout(() => show = true, 400)"
                 :class="show ? 'tw-opacity-100 tw-translate-x-0' : 'tw-opacity-0 tw-translate-x-8'"
                 class="tw-transition-all tw-duration-1000 tw-ease-out">

                <!-- Video Wrapper -->
                <div class="tw-relative tw-aspect-video tw-rounded-2xl tw-overflow-hidden tw-shadow-2xl tw-border-3 tw-border-white/30 tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw-shadow-primary/50">
                    <iframe
                        src="https://www.youtube.com/embed/kCmPeHw6xAU?si=VIlE1_Mqx6KsR817"
                        title="くるま買取ケイヴィレッジ 店舗紹介"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen
                        loading="lazy"
                        class="tw-absolute tw-inset-0 tw-w-full tw-h-full">
                    </iframe>
                </div>

                <!-- Video Badge -->
                <div class="tw-absolute tw-bottom-3 tw-left-3 tw-inline-flex tw-items-center tw-gap-2 tw-px-3 tw-py-1.5 tw-bg-black/80 tw-backdrop-blur-md tw-rounded-full tw-border tw-border-white/20">
                    <i class="fa-solid fa-play-circle tw-text-red-600"></i>
                    <span class="tw-text-xs tw-font-bold tw-text-white">店舗紹介</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="tw-absolute tw-bottom-8 tw-left-1/2 tw--translate-x-1/2 tw-z-10 tw-animate-bounce">
        <i class="fa-solid fa-chevron-down tw-text-white/60 tw-text-2xl"></i>
    </div>

    <!-- Wave Decoration -->
    <div class="tw-absolute tw-bottom-0 tw-left-0 tw-w-full tw-z-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80" preserveAspectRatio="none" class="tw-w-full tw-h-12 md:tw-h-16">
            <path d="M0,48 C240,64 480,64 720,48 C960,32 1200,32 1440,48 L1440,80 L0,80 Z" fill="white"/>
        </svg>
    </div>
</section>
