document.addEventListener('DOMContentLoaded', function () {
  const popupChat = document.querySelector('.popup-chat');
  const chatEl = document.querySelector('.chat-element');
  const heroEl = document.querySelector('.hero__element');

  if (heroEl) {
    if (popupChat) {
      popupChat.classList.add('popup-chat--hero');
    }
  }

  if (chatEl) {
    chatEl.addEventListener('click', (e) => {
      e.stopPropagation();

      if (!popupChat) return;

      if (popupChat.classList.contains('active') && activePopupBtn === chatEl) {
        popupChat.classList.remove('active');

        setTimeout(() => {
          popupChat.style.position = '';
          popupChat.style.top = '';
          popupChat.style.left = '';
        }, 200);

        activePopupBtn = null;
        return;
      }

      if (popupChat.classList.contains('active')) {
        popupChat.style.position = '';
        popupChat.style.top = '';
        popupChat.style.left = '';
      }
      popupChat.classList.add('active');

      activePopupBtn = chatEl;

      removeRetellFloatingContainers();
    });
  }

  if (heroEl) {
    heroEl.addEventListener('click', (e) => {
      e.stopPropagation();

      if (!popupChat) return;

      if (popupChat.classList.contains('active') && activePopupBtn === heroEl) {
        popupChat.classList.remove('active');

        setTimeout(() => {
          popupChat.style.position = '';
          popupChat.style.top = '';
          popupChat.style.left = '';
        }, 200);

        activePopupBtn = null;
        return;
      }

      if (popupChat.classList.contains('active')) {
        popupChat.style.position = '';
        popupChat.style.top = '';
        popupChat.style.left = '';
      }

      popupChat.classList.add('active');

      activePopupBtn = heroEl;

      removeRetellFloatingContainers();
    });
  }

  const popupButtons = document.querySelectorAll('.open-popup-chat');

  popupButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();

      const rect = btn.getBoundingClientRect();

      if (!popupChat) return;

      if (popupChat.classList.contains('active') && activePopupBtn === btn) {
        popupChat.classList.remove('active');

        setTimeout(() => {
          popupChat.style.position = '';
          popupChat.style.top = '';
          popupChat.style.left = '';
          popupChat.style.bottom = '';
          popupChat.style.width = '';
        }, 200);

        activePopupBtn = null;
        return;
      }

      if (btn.classList.contains('open-popup-chat--header')) {
        const isMobile = window.innerWidth < 768;

        popupChat.style.position = 'fixed';

        if (isMobile) {
          popupChat.style.top = 'auto';
          popupChat.style.left = '16px';
          popupChat.style.right = '16px';
          popupChat.style.bottom = 40 + 'px';
          popupChat.style.width = 'calc(100% - 32px)';
        } else {
          popupChat.style.top = rect.bottom + 10 + 'px';
          popupChat.style.left = rect.left + 'px';
          popupChat.style.right = '';
          popupChat.style.bottom = '';
          popupChat.style.width = '';
        }
      } else {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const scrollLeft = window.scrollX || document.documentElement.scrollLeft;

        popupChat.style.position = 'absolute';
        popupChat.style.top = rect.bottom + scrollTop + 10 + 'px';
        popupChat.style.left = rect.left + scrollLeft + 'px';
      }

      popupChat.classList.add('active');
      popupOpenScrollY = window.scrollY;

      activePopupBtn = btn;
    });
  });

  document.addEventListener('click', (e) => {
    if (popupChat && popupChat.classList.contains('active')) {
      if (!popupChat.contains(e.target) &&
        !(openChatBtn && openChatBtn.contains(e.target)) &&
        !(openCallbackBtn && openCallbackBtn.contains(e.target))) {

        popupChat.classList.remove('active');

        setTimeout(() => {
          popupChat.style.position = '';
          popupChat.style.top = '';
          popupChat.style.left = '';
          popupChat.style.bottom = '';
          popupChat.style.width = '';
        }, 200);

        activePopupBtn = null;
      }
    }
  });

  if (chatEl) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 400) {
        chatEl.classList.add('active');
      } else {
        chatEl.classList.remove('active');
      }
    });
  }

  if (heroEl) {
    const scrollTrigger = 300;
    let isFixed = false;

    window.addEventListener('scroll', () => {
      if (window.scrollY <= scrollTrigger || isFixed) return;

      const rect = heroEl.getBoundingClientRect();
      const distanceRight = window.innerWidth - rect.right;
      const distanceBottom = window.innerHeight - rect.bottom;

      heroEl.style.position = 'fixed';
      heroEl.style.right = distanceRight + 'px';
      heroEl.style.bottom = distanceBottom + 'px';
      heroEl.style.left = '';
      heroEl.style.top = '';
      heroEl.classList.add('fixed');

      setTimeout(() => {
        heroEl.style.transition = 'all 0.7s ease';
        heroEl.style.right = '20px';
        heroEl.style.bottom = '10px';
      }, 30);

      isFixed = true;

      if (popupChat) {
        popupChat.classList.remove('popup-chat--hero');
      }
    });
  }

  function removeRetellFloatingContainers() {
    document.querySelectorAll('div').forEach((div) => {
      const style = window.getComputedStyle(div);
      if (style.position === 'fixed' && style.bottom === '24px' && style.right === '24px' && style.zIndex === '999999') div.remove();
    });
  }

  function hideRetellFabInShadowOnce() {
    document.querySelectorAll('div').forEach((div) => {
      const style = window.getComputedStyle(div);
      if (style.position === 'fixed' && style.bottom === '24px' && style.right === '24px' && style.zIndex === '999999' && div.shadowRoot) {
        const shadow = div.shadowRoot;
        const fab = shadow.querySelector('.retell-fab');
        const chatWindow = shadow.querySelector('.retell-chat-window');
        if (fab) fab.style.display = 'none';
        if (chatWindow) chatWindow.style.display = 'flex';
      }
    });
  }

  function loadRetellWidget(config) {
    const container = document.getElementById('retell-container');
    if (!container) return;

    removeRetellFloatingContainers();
    container.innerHTML = '';

    const oldScript = document.getElementById('retell-widget');
    if (oldScript) oldScript.remove();

    const script = document.createElement('script');
    script.id = 'retell-widget';
    script.src = 'https://dashboard.retellai.com/retell-widget.js';

    Object.entries(config).forEach(([key, value]) => {
      script.setAttribute(key, value);
    });

    container.appendChild(script);

    setTimeout(() => {
      hideRetellFabInShadowOnce();
    }, 300);
  }

  const openChatBtn = document.getElementById('open-chat');
  if (openChatBtn) {
    openChatBtn.addEventListener('click', () => {
      if (popupChat) popupChat.classList.toggle('active');
      loadRetellWidget({
        'data-public-key': 'key_e9f62863219749bc3edee954b7c8',
        'data-agent-id': 'agent_bd1eb27cbf77ceaa3fbb0b28d8',
        'data-agent-version': 'V4',
        'data-title': 'Chat with AVA',
        'data-auto-open': 'true',
      });
    });
  }

  const openCallbackBtn = document.getElementById('open-callback');
  if (openCallbackBtn) {
    openCallbackBtn.addEventListener('click', () => {
      if (popupChat) popupChat.classList.toggle('active');
      loadRetellWidget({
        'data-public-key': 'key_e9f62863219749bc3edee954b7c8',
        'data-agent-id': 'agent_c5f635a249c5aaf4f14463ba8a',
        'data-widget': 'callback',
        'data-phone-number': '+14692779659',
        'data-title': 'Request a Call',
        'data-countries': 'US,CA,GB,ZA',
      });
    });
  }

  // Close chat
  document.addEventListener('click', (e) => {
    const floatingDivs = [];

    document.querySelectorAll('div').forEach((div) => {
      const style = window.getComputedStyle(div);

      if (
        style.position === 'fixed' &&
        style.bottom === '24px' &&
        style.right === '24px' &&
        style.zIndex === '999999'
      ) {
        floatingDivs.push(div);
      }
    });

    if (!floatingDivs.length) return;

    const path = e.composedPath();

    floatingDivs.forEach((div) => {
      const isInside = path.includes(div);

      if (!isInside) {
        div.remove();
      }
    });
  });
});
