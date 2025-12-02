//breakpoint
let mqPointTab = window.matchMedia("(min-width: 768px)"),
    mqPointTab2 = window.matchMedia("(min-width: 980px)"),
    mqPointPC = window.matchMedia("(max-width: 980px)");

// 100vh設定
function setHeight() {
    let vh = document.documentElement.clientHeight * 0.01;
    document.documentElement.style.setProperty("--vh", vh + "px");
}
window.addEventListener("DOMContentLoaded", setHeight);
window.addEventListener("resize", setHeight);
// 100vw設定
function setWidth() {
    let vw = document.documentElement.clientWidth * 0.01;
    document.documentElement.style.setProperty("--vw", vw + "px");
}
window.addEventListener("DOMContentLoaded", setWidth);
window.addEventListener("resize", setWidth);

// //heroslide
if ($(".js-heroSlide").length) {
    let autoPlaySpeed = 4500;
    let speed = 2500;
    $(".js-heroSlide").on(
        "beforeChange",
        function (event, slick, currentSlide, nextSlide) {
            $(".slick-slide .js-kvimg", this).eq(nextSlide).addClass("a-kvimg");
            $(".slick-slide .js-kvimg", this)
                .eq(currentSlide)
                .addClass("a-kvimg--prev");
        }
    );
    $(".js-heroSlide").on(
        "afterChange",
        function (event, slick, currentSlide, nextSlide) {
            $(".a-kvimg--prev", this).removeClass("a-kvimg--prev a-kvimg");
        }
    );

    //ページロードに合わせて起動遅延
    $(window).on("load", function () {
        setTimeout(function () {
            $(".js-heroSlide").on("init", function () {
                $('.slick-slide[data-slick-index="0"] .js-kvimg').addClass(
                    "a-kvimg"
                );
            });
            $(".js-heroSlide").slick({
                infinite: true,
                fade: true,
                dots: false,
                autoplay: true,
                autoplaySpeed: autoPlaySpeed,
                focusOnSelect: true,
                pauseOnFocus: false,
                pauseOnHover: false,
                pauseOnDotsHover: false,
                swipeToSlide: true,
                arrows: false,
                speed: speed,
                cssEase: "ease",
                lazyLoad: "ondemand",
            });
        }, 50);
    });
}

// //shopslide
if ($(".js-shopSlide").length) {
    let autoPlaySpeed = 3200;
    let speed = 1400;
    $(function () {
        $(".js-shopSlide").slick({
            infinite: true,
            fade: true,
            dots: false,
            autoplay: true,
            autoplaySpeed: autoPlaySpeed,
            focusOnSelect: false,
            pauseOnFocus: false,
            pauseOnHover: false,
            pauseOnDotsHover: false,
            swipeToSlide: true,
            arrows: false,
            speed: speed,
            cssEase: "ease",
            lazyLoad: "ondemand",
        });
    });
}
//experience slide
if ($(".js-expSLide").length) {
    let autoPlaySpeed = 4000;
    let speed = 1400;
    $(function () {
        $(".js-expSLide").slick({
            infinite: true,
            fade: false,
            dots: false,
            autoplay: true,
            autoplaySpeed: autoPlaySpeed,
            focusOnSelect: false,
            pauseOnFocus: false,
            pauseOnHover: false,
            pauseOnDotsHover: false,
            swipeToSlide: true,
            arrows: true,
            prevArrow: '<button class="p-front-exp__Arrow prev-arrow">',
            nextArrow: '<button class="p-front-exp__Arrow next-arrow">',
            speed: speed,
            cssEase: "ease",
            lazyLoad: "ondemand",
        });
    });
}

//ナビゲーション固定
if (!mqPointPC.matches) {
    window.addEventListener("DOMContentLoaded", () => {
        let nav = document.getElementById("js-fixNav");
        let navOffsetTop = nav.getBoundingClientRect().top + window.scrollY;
        let offsetMargin = 40;

        window.addEventListener("scroll", () => {
            if (window.scrollY + offsetMargin >= navOffsetTop) {
                nav.classList.add("-fixed");
            } else {
                nav.classList.remove("-fixed");
            }
        });
    });
}
//ページ内リンクスクロール
const smoothScrollTrigger = document.querySelectorAll('a[href*="#"]');
for (let i = 0; i < smoothScrollTrigger.length; i++) {
    smoothScrollTrigger[i].addEventListener("click", (e) => {
        e.preventDefault();
        let href = smoothScrollTrigger[i].getAttribute("href");
        let targetElement = document.getElementById(href.replace("#", ""));
        const rect = targetElement.getBoundingClientRect().top;
        const offset = window.pageYOffset;
        const gap = 0;
        const target = rect + offset - gap;
        window.scrollTo({
            top: target,
            behavior: "smooth",
        });
    });
}

//headermenu
let HeaderMenuBtn = document.querySelector(".js-menuBtn");
let HeaderMenu = document.querySelector(".js-menu");
let MenuLinks = document.querySelectorAll(".js-menuLink");

function menuToggle() {
    HeaderMenuBtn.classList.toggle("--open");
    HeaderMenu.classList.toggle("--open");
}
HeaderMenuBtn.addEventListener("click", function () {
    menuToggle();
});

MenuLinks.forEach(function (menulink) {
    menulink.addEventListener("click", function () {
        menuToggle();
    });
});

function triggerLoadMask(source) {
    console.log("triggerLoadMask called from: " + source);
    $(".js-load").addClass("loaded");
    setTimeout(() => {
        $(".js-loadItem").addClass("loaded");
    }, 900);
}

$(window).on("load", function () {
    triggerLoadMask("load");
});

window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        triggerLoadMask("pageshow");
    }
});

//fade animation
$(function () {
    if ($(".js-fade").length) {
        let fade = $(".js-fade");
        $(window).on("scroll", function () {
            scrollfade();
        });
        // //ページ読み込み時に実行
        // $(window).on("load", function () {
        //     scrollfade();
        // });
        function scrollfade() {
            // var winW = $(window).width();
            // var devW = 819;
            let scroll = $(window).scrollTop();
            let windowHeight = $(window).height();
            let gap;
            if (mqPointPC.matches) {
                gap = 100;
            } else {
                gap = 200;
            }

            //   if (devW <= winW) {
            fade.each(function () {
                let triggerTop = $(this).offset().top;

                if (scroll > triggerTop - windowHeight + gap) {
                    $(this).addClass("a-fade");
                }
            });
            // }
        }
    }
});

//フロアマップ
document.addEventListener("DOMContentLoaded", () => {
    const floorSec = document.querySelector("#floor");
    const shops = document.querySelectorAll(".js-shop");
    const markers = document.querySelectorAll(".js-marker");
    if (mqPointTab2.matches) {
        let offset = 300;

        function resetMarkers() {
            markers.forEach((marker) => marker.classList.remove("is-active"));
        }

        function checkMarkers() {
            let secRect = floorSec.getBoundingClientRect();
            if (secRect.bottom < 0 || secRect.top > window.innerHeight) {
                return;
                //セクションが画面外なら処理しない
            }
            let matched = false;
            shops.forEach((shop) => {
                const rect = shop.getBoundingClientRect();
                const distFromBottom = window.innerHeight - rect.top;

                if (
                    !matched &&
                    distFromBottom >= offset &&
                    distFromBottom <= offset + shop.offsetHeight
                ) {
                    //店舗要素がoffsetを超えて、画面内に収まっているとき
                    let shopId = shop.dataset.shopId;
                    resetMarkers();
                    let marker = document.getElementById(`marker-${shopId}`);
                    if (marker) {
                        marker.classList.add("is-active");
                    }
                    matched = true;
                }
            });
            if (!matched) resetMarkers();
        }

        window.addEventListener("scroll", checkMarkers);
        //リサイズで表示位置が変わる可能性があるため
        window.addEventListener("resize", checkMarkers);
    }
});
