/**
 * メニュー制御ファイル
 *
 * 概要：メニューの開閉処理
 */
import { INode, config, utils } from "#/helper";

const bgSrc = {
    init
}
const $ = {};

function init() {
  $.dataBg = INode.qsAll('[data-bg]');

   _bindEvents();
}


function _bindEvents() {
 // data-bg属性を持つ要素に対して背景画像のURLを設定
  if (!$.dataBg.length) return;
  $.dataBg.forEach(el => {
   const bgUrl = el.getAttribute('data-bg');
   el.style.setProperty('--bg-url', `url('${bgUrl}')`);
 });


}


export default bgSrc;
