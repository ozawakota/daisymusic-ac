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
  $.dataBgSp = INode.qsAll('[data-bg-sp]');
  console.log($.dataBg);
  console.log($.dataBgSp);
  
  
   _bindEvents();
}


function _bindEvents() {
 // data-bg属性を持つ要素に対して背景画像のURLを設定
  if (!$.dataBg.length) return;
    $.dataBg.forEach(el => {
    const bgUrl = el.getAttribute('data-bg');
    el.style.setProperty('--bg-url', `url('${bgUrl}')`);
  });
  if (!$.dataBgSp.length) return;
    $.dataBgSp.forEach(els => {
    const bgUrlSp = els.getAttribute('data-bg-sp');
    els.style.setProperty('--bg-url-sp', `url('${bgUrlSp}')`);
  });


}


export default bgSrc;
