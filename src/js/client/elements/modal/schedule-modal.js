(function () {
    const SCHEDULE_MODAL_KEY = 'schedule_modal_shown';
    const overlay = document.getElementById('scheduleModal');
    const anchor = document.getElementById('schedule-anchor');

    if (!overlay || !anchor) return;

    let shown = sessionStorage.getItem(SCHEDULE_MODAL_KEY) === '1';

    function scheduleModalShow() {
        if (shown) return;
        shown = true;
        sessionStorage.setItem(SCHEDULE_MODAL_KEY, '1');
        overlay.classList.add('schedule-modal--visible');
    }

    function scheduleModalClose() {
        overlay.classList.remove('schedule-modal--visible');
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                scheduleModalShow();
                observer.disconnect();
            }
        });
    }, {
        threshold: 0.3
    });

    observer.observe(anchor);

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) scheduleModalClose();
    });

    document.getElementById('scheduleModalClose')
        ?.addEventListener('click', scheduleModalClose);

    document.getElementById('scheduleModalAccept')
        ?.addEventListener('click', scheduleModalClose);
})();