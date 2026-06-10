document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.why-we__item');
    const itemsData = [];

    items.forEach(function (item) {
        const textEl = item.querySelector('.why-we__item-text');
        const moreBtn = item.querySelector('.why-we__item-more');

        if (!textEl || !moreBtn) return;

        if (window.innerWidth <= 768) {
            moreBtn.style.display = 'none';
            return;
        }

        const lineHeight = parseFloat(getComputedStyle(textEl).lineHeight);
        const maxHeight = lineHeight * 4;

        if (textEl.scrollHeight <= maxHeight + 2) {
            moreBtn.style.display = 'none';
            return;
        }

        const fullHTML = textEl.innerHTML;
        const words = textEl.innerText.split(' ');
        let truncated = '';

        for (let i = 0; i < words.length; i++) {
            textEl.innerText = truncated + words[i] + '...';

            if (textEl.scrollHeight > lineHeight * 3 + 2) {
                textEl.innerText = truncated + words[i] + '...';
                break;
            }

            truncated += words[i] + ' ';
        }

        const truncatedHTML = textEl.innerHTML;

        // Вимірюємо повну висоту картки з розкритим текстом
        textEl.innerHTML = fullHTML;
        const expandedCardHeight = item.scrollHeight;
        textEl.innerHTML = truncatedHTML;

        itemsData.push({ item, textEl, moreBtn, fullHTML, truncatedHTML, lineHeight, expandedCardHeight });
    });

    const maxCardHeight = Math.max(...itemsData.map(d => d.expandedCardHeight));

    itemsData.forEach(function ({ item, textEl, moreBtn, fullHTML, truncatedHTML, lineHeight }) {

        moreBtn.addEventListener('click', function () {
            const isExpanded = moreBtn.classList.contains('opened');

            if (!isExpanded) {
                const collapsedHeight = textEl.offsetHeight;
                textEl.innerHTML = fullHTML;
                const expandedHeight = textEl.scrollHeight;

                textEl.style.height = collapsedHeight + 'px';
                textEl.style.overflow = 'hidden';
                textEl.style.transition = 'height 0.3s ease';

                requestAnimationFrame(() => {
                    textEl.style.height = expandedHeight + 'px';
                });

                textEl.addEventListener('transitionend', function () {
                    textEl.style.height = '';
                    textEl.style.overflow = '';
                    textEl.style.transition = '';
                }, { once: true });

                item.style.transition = 'min-height 0.3s ease, padding-bottom 0.3s ease';
                item.style.minHeight = maxCardHeight + 'px';
                item.style.height = 'auto';
                item.style.paddingBottom = '48px';

                moreBtn.classList.add('opened');
                moreBtn.querySelector('span')
                    ? moreBtn.querySelector('span').textContent = 'less'
                    : moreBtn.childNodes[0].textContent = 'less\n';

            } else {
                const expandedHeight = textEl.offsetHeight;
                textEl.style.height = expandedHeight + 'px';
                textEl.style.overflow = 'hidden';
                textEl.style.transition = 'height 0.3s ease';

                requestAnimationFrame(() => {
                    textEl.style.height = (lineHeight * 3.2) + 'px';
                });

                textEl.addEventListener('transitionend', function () {
                    textEl.innerHTML = truncatedHTML;
                    textEl.style.height = '';
                    textEl.style.overflow = '';
                    textEl.style.transition = '';
                }, { once: true });

                item.style.transition = 'min-height 0.3s ease, padding-bottom 0.3s ease';
                item.style.minHeight = '';
                item.style.paddingBottom = '';
                item.style.height = '';

                moreBtn.classList.remove('opened');
                moreBtn.querySelector('span')
                    ? moreBtn.querySelector('span').textContent = 'more'
                    : moreBtn.childNodes[0].textContent = 'more\n';
            }
        });
    });
});