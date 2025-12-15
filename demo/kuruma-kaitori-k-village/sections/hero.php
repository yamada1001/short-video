<!-- Hero Section - Tailwind CSS + Alpine.js -->
<section class="tw-relative tw-min-h-screen tw-flex tw-items-center tw-overflow-hidden" x-data="{ stats: [
    { number: '30年+', label: '買取実績', delay: '0s' },
    { number: '98%', label: '顧客満足度', delay: '0.2s' },
    { number: '即日', label: '現金買取', delay: '0.4s' }
] }">
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
    <div class="container tw-relative tw-z-10 tw-px-4 sm:tw-px-6 lg:tw-px-8">
        <div class="tw-grid lg:tw-grid-cols-2 tw-gap-12 lg:tw-gap-16 tw-items-center">

            <!-- Left Column - Text Content -->
            <div class="tw-text-white tw-space-y-8"
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
                <div class="tw-space-y-2">
                    <h1 class="tw-text-4xl md:tw-text-5xl lg:tw-text-6xl tw-font-black tw-leading-tight">
                        <span class="tw-block tw-text-2xl md:tw-text-3xl tw-font-medium tw-mb-2 tw-tracking-wide">愛車を高く売るなら</span>
                        <span class="tw-block tw-bg-gradient-to-r tw-from-white tw-to-white/90 tw-bg-clip-text tw-text-transparent tw-drop-shadow-2xl">
                            ケイヴィレッジ
                        </span>
                    </h1>
                </div>

                <!-- Lead Text -->
                <p class="tw-text-xl md:tw-text-2xl tw-leading-relaxed">
                    <span class="tw-text-5xl tw-font-black tw-text-yellow-400 tw-drop-shadow-[0_0_20px_rgba(255,215,0,0.5)]">90秒</span>
                    <span class="tw-text-lg">でカンタン無料査定</span><br>
                    <span class="tw-text-base tw-text-white/90">買取・販売・車検・整備まで、すべてお任せください</span>
                </p>

                <!-- CTA Buttons -->
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-gap-4">
                    <a href="<?php echo url('kaitori'); ?>"
                       class="tw-group tw-inline-flex tw-flex-col tw-items-start tw-px-8 tw-py-5 tw-bg-gradient-to-br tw-from-primary tw-to-orange-600 tw-rounded-2xl tw-shadow-2xl hover:tw-shadow-primary/50 tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw--translate-y-1">
                        <i class="fa-solid fa-dollar-sign tw-text-3xl tw-mb-2"></i>
                        <span class="tw-text-xl tw-font-black">無料査定はこちら</span>
                        <span class="tw-text-sm tw-opacity-90">90秒で完了</span>
                    </a>

                    <a href="tel:<?php echo PHONE_LINK; ?>"
                       class="tw-group tw-inline-flex tw-flex-col tw-items-start tw-px-8 tw-py-5 tw-bg-white/20 tw-backdrop-blur-md tw-rounded-2xl tw-border-2 tw-border-white/30 tw-shadow-xl hover:tw-bg-white/30 tw-transition-all tw-duration-300 hover:tw-scale-105">
                        <i class="fa-solid fa-phone tw-text-3xl tw-mb-2 tw-animate-pulse"></i>
                        <span class="tw-text-xl tw-font-black"><?php echo PHONE; ?></span>
                        <span class="tw-text-sm tw-opacity-90">受付時間: <?php echo BUSINESS_HOURS; ?></span>
                    </a>
                </div>

                <!-- Stats Cards -->
                <div class="tw-grid tw-grid-cols-3 tw-gap-4">
                    <template x-for="(stat, index) in stats" :key="index">
                        <div class="tw-bg-white/15 tw-backdrop-blur-lg tw-border-2 tw-border-white/30 tw-rounded-2xl tw-p-4 tw-text-center tw-transition-all tw-duration-500 hover:tw-bg-white/25 hover:tw-scale-110 hover:tw--translate-y-2 hover:tw-shadow-2xl"
                             x-data="{ show: false }"
                             x-init="setTimeout(() => show = true, 1000 + (index * 200))"
                             :style="`transition-delay: ${stat.delay}`"
                             :class="show ? 'tw-opacity-100 tw-scale-100' : 'tw-opacity-0 tw-scale-75'">
                            <div class="tw-text-3xl md:tw-text-4xl tw-font-black tw-text-yellow-400 tw-drop-shadow-[0_0_20px_rgba(255,215,0,0.5)] tw-mb-1" x-text="stat.number"></div>
                            <div class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-white/90" x-text="stat.label"></div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Column - YouTube Video -->
            <div class="tw-relative"
                 x-data="{ show: false }"
                 x-init="setTimeout(() => show = true, 600)"
                 :class="show ? 'tw-opacity-100 tw-translate-x-0' : 'tw-opacity-0 tw-translate-x-8'"
                 class="tw-transition-all tw-duration-1000 tw-ease-out">

                <!-- Video Wrapper -->
                <div class="tw-relative tw-aspect-video tw-rounded-3xl tw-overflow-hidden tw-shadow-2xl tw-border-4 tw-border-white/30 tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw-shadow-primary/50">
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
                <div class="tw-absolute tw-bottom-4 tw-left-4 tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-black/80 tw-backdrop-blur-md tw-rounded-full tw-border tw-border-white/20">
                    <i class="fa-solid fa-play-circle tw-text-red-600 tw-text-lg"></i>
                    <span class="tw-text-sm tw-font-bold tw-text-white">店舗紹介動画</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Decoration -->
    <div class="tw-absolute tw-bottom-0 tw-left-0 tw-w-full tw-z-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none" class="tw-w-full tw-h-16 md:tw-h-24">
            <path d="M0,64 C240,96 480,96 720,64 C960,32 1200,32 1440,64 L1440,120 L0,120 Z" fill="white"/>
        </svg>
    </div>
</section>
