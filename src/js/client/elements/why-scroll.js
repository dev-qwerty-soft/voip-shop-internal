(function () {
    const wrapper = document.getElementById('whySticky');
    if (!wrapper) return;

    const items = wrapper.querySelectorAll('.why-list__item');
    const slides = wrapper.querySelectorAll('.why-list__images .img-slide');
    const list = wrapper.querySelector('.why-list__list');

    if (!items.length || !list) return;

    const TOTAL = items.length;
    const isMobile = () => window.innerWidth <= 768;

    let currentIndex = -1;
    let ticking = false;

    items.forEach(item => item.classList.remove('active'));

    const collapsedHeights = Array.from(items).map(item => {
        const head = item.querySelector('.why-list__item-head');
        return head ? head.offsetHeight : item.offsetHeight;
    });

    const bodyHeights = Array.from(items).map(item => {
        const body = item.querySelector('.why-list__item-body');
        return body ? body.scrollHeight : 0;
    });

    console.log('collapsedHeights:', collapsedHeights);
    console.log('bodyHeights:', bodyHeights);

    function calcMarginBottom(index) {
        const gap = parseFloat(getComputedStyle(list).gap) || 0;
        const containerHeight = list.parentElement.offsetHeight;

        let heightsAbove = 0;
        for (let i = 0; i < index; i++) {
            heightsAbove += collapsedHeights[i];
        }

        const activeHeight = collapsedHeights[index];

        let heightsBelow = 0;
        for (let i = index + 1; i < TOTAL; i++) {
            heightsBelow += collapsedHeights[i];
        }

        const allGaps = gap * (TOTAL - 1);
        const margin = containerHeight - heightsAbove - activeHeight - heightsBelow - allGaps;

        return Math.max(0, margin);
    }

    function updateSpacing(index) {
        if (isMobile()) {
            items.forEach(item => item.style.marginBottom = '');
            return;
        }

        const marginBottom = calcMarginBottom(index);

        items.forEach((item, i) => {
            item.style.transition = 'margin-bottom 0.6s cubic-bezier(.4, 0, .2, 1)';
            item.style.marginBottom = i === index ? `${marginBottom}px` : '';
        });
    }

    function activate(index) {
        if (index === currentIndex) return;
        currentIndex = index;

        updateSpacing(index);

        items.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });

        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    }

    function onScroll() {
        if (ticking) return;
        ticking = true;

        requestAnimationFrame(() => {
            const rect = wrapper.getBoundingClientRect();
            const total = wrapper.offsetHeight - window.innerHeight;

            if (total <= 0) {
                activate(0);
                ticking = false;
                return;
            }

            const scrolled = -rect.top;
            const progress = Math.max(0, Math.min(1, scrolled / total));

            const raw = progress * TOTAL;
            const index = Math.min(TOTAL - 1, Math.floor(raw));

            activate(index);

            const itemProgress = raw - index;

            items.forEach((item, i) => {
                const bar = item.querySelector('.feature-progress');
                if (!bar) return;

                if (i < index) {
                    bar.style.height = '100%';
                } else if (i === index) {
                    bar.style.height = (itemProgress * 100) + '%';
                } else {
                    bar.style.height = '0%';
                }
            });

            ticking = false;
        });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', () => {
        items.forEach(item => item.classList.remove('active'));

        collapsedHeights.forEach((_, i) => {
            const head = items[i].querySelector('.why-list__item-head');
            collapsedHeights[i] = head ? head.offsetHeight : items[i].offsetHeight;
            const body = items[i].querySelector('.why-list__item-body');
            bodyHeights[i] = body ? body.scrollHeight : 0;
        });

        currentIndex = -1;
        onScroll();
    });

    requestAnimationFrame(() => {
        onScroll();
    });
})();