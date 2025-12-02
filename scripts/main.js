import {   utils, config, INode } from "#/helper";
import bgSrc from "./component/bgSrc";
import voiceSwiper from "./component/voiceSwiper";
import voiceStudentSwiper from "./component/voiceStudentSwiper";

window.debug = enableDebugMode(1);

// デバッグモード：1, 非デバッグモード：0
function enableDebugMode(debug) {
  return debug && import.meta.env.DEV;
}

export async function init() {
  try {

    bgSrc.init();
    
    // ページに応じてSwiperを初期化
    const isNewSolfegeLesson = document.querySelector('[data-page="solfege-seminar"]');
    const isPianoLesson = document.querySelector('[data-page="piano-lesson"]');
    
    if (isNewSolfegeLesson) {
      // console.log('新ソルフェージュページ: voiceSwiperを初期化');
      voiceSwiper.init();
    } else if (isPianoLesson) {
      // console.log('ピアノレッスンページ: voiceStudentSwiperを初期化');
      voiceStudentSwiper.init();
    } else {
      // console.log('対象ページではありません');
    }

    // メモリサイズ
    console.log(performance.memory);

  } catch (e) {
    // tryブロックでエラーが発生した
    console.error(e);
    debugger;
  }

}
