type T = Element | Element[] | null | HTMLElement | HTMLElement[] | undefined;

export const toggle = (el: T, cl: string = 'active') => {
  if (!el) return;
  if (Array.isArray(el)) {
    el.forEach((item) => toggle(item, cl));
  } else {
    el.classList.toggle(cl);
  }
};

export const add = (el: T, cl: string = 'active') => {
  if (!el) return;
  if (Array.isArray(el)) {
    el.forEach((item) => add(item, cl));
  } else {
    el.classList.add(cl);
  }
};

export const remove = (el: T, cl: string = 'active') => {
  if (!el) return;
  if (Array.isArray(el)) {
    el.forEach((item) => remove(item, cl));
  } else {
    el.classList.remove(cl);
  }
};

export const has = (el: HTMLElement, cl: string = '.active') => {
  return Boolean(el.closest(cl) && el?.closest(cl)?.matches(cl));
};

export const random = (min: number, max: number) => {
  return Math.round(Math.random() * (max - min + 1) + min);
};

export function g(element: string, cont = document, flag: boolean = false): any {
  const elements = Array.from(cont.querySelectorAll(element));
  if (!elements.length || !cont) return;

  if (elements.length === 1) {
    return flag ? elements : elements[0];
  } else {
    return elements;
  }
}

export const max = (arr: number[]) => arr.reduce((acc, num) => (acc > num ? acc : num));

export const upper = (text: string) => text[0].toUpperCase() + text.slice(1);

export const child = (str: any) => {
  if (!str) return;
  if (typeof str === 'string') {
    return Array.from(g(str)?.children);
  } else {
    return Array.from(str.children);
  }
};

export const scroll = (): number => {
  const scrollHeight = document.body.scrollHeight - window.innerHeight;
  const height = window.pageYOffset;
  const progress = Math.round((height / scrollHeight) * 100);
  return progress;
};

export const isSize = (num: number) => window.innerWidth <= num;

export const round = (num: number, del: number) => Math.round(num / del) * del;

export const getUrl = (url: string) => {
  const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\v=)([^#\\?]*).*/;
  const match = url.match(regExp);
  if (match && match[2].length === 11) {
    return match[2];
  }
};

export function duration(duration: string) {
  const match = duration.match(/PT(\d+H)?(\d+M)?(\d+S)?/);
  if (!match) return '';
  let hours = '',
    minutes = '',
    seconds = '';
  if (match[1]) {
    hours = match[1].replace('H', '').padStart(2, '0');
  }
  if (match[2]) {
    minutes = match[2].replace('M', '').padStart(2, '0');
  }
  if (match[3]) {
    seconds = match[3].replace('S', '').padStart(2, '0');
  }
  return `${hours ? hours + ':' : ''}${minutes !== '' ? minutes : '00'}:${seconds !== '' ? seconds : '00'}`;
}

export function splitArray(arr: any[]) {
  const mid = Math.ceil(arr.length / 2);
  const firstHalf = arr.slice(0, mid);
  const secondHalf = arr.slice(mid);
  return [firstHalf, secondHalf];
}

export function extractURL(cssURL: string) {
  const urlPattern = /url\(["']?([^"']+)["']?\)/;
  const match = cssURL.match(urlPattern);
  return match ? match[1] : '';
}

type breakpoint = 'phone' | 'tab-mid' | 'tab-big';

export function screen(breakpoint: breakpoint): boolean {
  if (breakpoint === 'phone') {
    return window.innerWidth >= 640;
  } else if (breakpoint === 'tab-mid') {
    return window.innerWidth >= 768;
  } else if (breakpoint === 'tab-big') {
    return window.innerWidth >= 1040;
  }
  return false;
}

export function formatNumber(number: number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

export function getVW(target: number, baseWidth: number): string {
  const vw = target / (baseWidth * 0.01);
  return `${vw}vw`;
}

// Specific functions
export function getD(target: number) {
  return getVW(target, 1536);
}

export function getXL(target: number) {
  return getVW(target, 1280);
}

export function getLG(target: number) {
  return getVW(target, 1024);
}

export function getMD(target: number) {
  return getVW(target, 768);
}

export function getSM(target: number) {
  return getVW(target, 640);
}

export function getXS(target: number) {
  return getVW(target, 480);
}

export const startScroll = () => {
  remove(document.body, 'overflow');
};

export const endScroll = () => {
  add(document.body, 'overflow');
};

export function shouldClampText(element: any, maxLines: number) {
  if (!element) return false;

  const computedStyle = window.getComputedStyle(element);
  const lineHeight = parseFloat(computedStyle.lineHeight) || parseFloat(computedStyle.fontSize) * 1.2;
  const maxHeight = lineHeight * maxLines;

  const prevWebkitLineClamp = element.style.webkitLineClamp;
  const prevLineClamp = element.style.lineClamp;
  const prevOverflow = element.style.overflow;
  const prevDisplay = element.style.display;
  const prevWebkitBoxOrient = element.style.webkitBoxOrient;

  element.style.webkitLineClamp = 'unset';
  element.style.lineClamp = 'unset';
  element.style.overflow = 'visible';
  element.style.display = 'block';
  element.style.webkitBoxOrient = 'unset';

  const fullHeight = element.scrollHeight;

  element.style.webkitLineClamp = prevWebkitLineClamp;
  element.style.lineClamp = prevLineClamp;
  element.style.overflow = prevOverflow;
  element.style.display = prevDisplay;
  element.style.webkitBoxOrient = prevWebkitBoxOrient;

  return fullHeight > maxHeight + 8;
}
