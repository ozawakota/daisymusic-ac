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
    voiceSwiper.init();
    voiceStudentSwiper.init();

    // メモリサイズ
    console.log(performance.memory);

  } catch (e) {
    // tryブロックでエラーが発生した
    console.error(e);
    debugger;
  }

}
