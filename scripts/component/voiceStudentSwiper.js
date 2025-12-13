/**
 * Swiper スライダー実装
 *
 * 概要：ピアノレッスンページの受講生の声スライダー
 * SWELLテーマで読み込まれているSwiper 7.0.6を使用
 */

const student_swiper = {
  init
};

function init() {
  // DOMとSwiperライブラリが読み込まれた後に初期化
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', _initSwiper);
  } else {
    // DOMが既に読み込まれている場合は即座に実行
    _initSwiper();
  }
}

/**
 * Swiperの初期化
 * SWELLテーマのグローバルSwiperオブジェクトを使用
 */
function _initSwiper() {
  // console.log('🎯 Swiper初期化開始');

  const swiperContainer = document.querySelector('.s-voice-swiper');

  if (!swiperContainer) {
    console.log('❌ .s-voice-swiper要素が見つかりません');
    return;
  }


  // Swiperが読み込まれるまで待機（最大5秒）
  let attempts = 0;
  const maxAttempts = 50;

  const initializeSwiper2 = () => {
    attempts++;

    if (typeof window.Swiper === 'undefined') {
      if (attempts >= maxAttempts) {
        // console.log('⚠️ SWELLのSwiperが見つからないため、CDNから読み込みます');
        loadSwiperFromCDN();
        return;
      }
      // Swiperがまだ読み込まれていない場合は少し待つ
      setTimeout(initializeSwiper2, 100);
      return;
    }

    // console.log('✅ Swiper読み込み完了！初期化します');
    createSwiperInstance();
  };

  initializeSwiper2();
}

/**
 * CDNからSwiperを読み込む
 */
function loadSwiperFromCDN() {
  // console.log('📦 CDNからSwiper読み込み開始');
  
  // CSS読み込み
  const cssLink = document.createElement('link');
  cssLink.rel = 'stylesheet';
  cssLink.href = 'https://unpkg.com/swiper@7.4.1/swiper-bundle.min.css';
  document.head.appendChild(cssLink);
  
  // JS読み込み
  const script = document.createElement('script');
  script.src = 'https://unpkg.com/swiper@7.4.1/swiper-bundle.min.js';
  script.onload = () => {
    // console.log('✅ CDNからSwiper読み込み完了');
    createSwiperInstance();
  };
  script.onerror = () => {
    console.error('❌ CDNからのSwiper読み込み失敗');
  };
  document.head.appendChild(script);
}

/**
 * Swiperインスタンス作成
 */
function createSwiperInstance() {
  // console.log('🚀 Swiperインスタンス作成開始');
  
  const swiperInstance = new window.Swiper('.s-voice-swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: false,
    autoplay: false,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      768: {
        slidesPerView: 1.5,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
    }
  });
  
  // console.log('🎉 Swiperインスタンス作成完了:', swiperInstance);
}

export default student_swiper;