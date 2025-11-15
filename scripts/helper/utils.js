/**
 * 汎用関数
 *
 *
 */
import { INode, config } from "#/helper";
import { detect as detectBrowser } from "detect-browser";

class Utils {
  constructor() {
    this.browser = detectBrowser();
  }

  scrollToTop() {
    return window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  isTouchDevices() {
    return Boolean("ontouchstart" in window || (window.DocumentTouch && document instanceof DocumentTouch));
  }

  isMobile(scope = window.innerWidth) {
    return scope < config.breakpoint;
  }

  isSafari() {
    return this.browser.name === "safari";
  }

  isIOS() {
    const userAgent = navigator.userAgent;

    if (userAgent.match(/iPhone/i) || userAgent.match(/iPad/i)) {
      return true;
    } else {
      return false;
    }
  }

  leaveClassName(target, classname) {
    [...target].forEach( (anchor) => {
      anchor.classList.remove(classname);
    });
  }

  toggle(target, classname) {
    target.classList.toggle(classname);
  }

  changeImgPath(data, before, after) {
    if (data && data.getAttribute) {
      let imgPath = data.getAttribute('src');
      let changePath = imgPath.replace(before, after);
      data.setAttribute('src', changePath);
    }
  }
}

const utils = new Utils();
export { utils };
