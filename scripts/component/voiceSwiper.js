/**
 * Swiper スライダー実装
 *
 * 概要：新ソルフェージュ指導法講座ページの受講生の声スライダー
 * SWELLテーマで読み込まれているSwiper 7.0.6を使用
 */

const swiper = {
    init
}

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
  console.log('🎯 Swiper初期化開始');

  const swiperContainer = document.querySelector('.voice-swiper');

  if (!swiperContainer) {
    console.log('❌ .voice-swiper要素が見つかりません');
    return;
  }

  console.log('✅ .voice-swiper要素を発見:', swiperContainer);

  // Swiperが読み込まれるまで待機（最大5秒）
  let attempts = 0;
  const maxAttempts = 50;

  const initializeSwiper2 = () => {
    attempts++;

    if (typeof window.Swiper === 'undefined') {
      if (attempts >= maxAttempts) {
        console.error('❌ Swiperの読み込みタイムアウト（5秒経過）');
        return;
      }
      // Swiperがまだ読み込まれていない場合は少し待つ
      setTimeout(initializeSwiper2, 100);
      return;
    }

    console.log('✅ Swiper読み込み完了！初期化します');

    const swiperInstance = new window.Swiper('.voice-swiper', {
      // スライド設定
      slidesPerView: 1,
      spaceBetween: 20,

      // ループ設定
      loop: false,

      // 自動再生
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },

      // ナビゲーションボタン
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },

      // ページネーション（オプション）
      // pagination: {
      //   el: '.swiper-pagination',
      //   clickable: true,
      // },

      // レスポンシブ設定
      breakpoints: {
        // 768px以上
        768: {
          slidesPerView: 1.5,
          spaceBetween: 20,
        },
        // 1024px以上
        1024: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
      }
    });

    console.log('🎉 Swiperインスタンス作成完了:', swiperInstance);
  };

  initializeSwiper2();
}

export default swiper;
