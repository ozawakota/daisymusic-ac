import {   utils, config, INode } from "#/helper";

window.debug = enableDebugMode(1);

// デバッグモード：1, 非デバッグモード：0
function enableDebugMode(debug) {
  return debug && import.meta.env.DEV;
}

export async function init() {
  try {

    // メモリサイズ
    console.log(performance.memory);

  } catch (e) {
    // tryブロックでエラーが発生した
    console.error(e);
    debugger;
  }

}
