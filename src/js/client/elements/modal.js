const PHONE_COUNTRIES = [
  { name: 'Afghanistan', dial: '+93', flag: '🇦🇫', code: 'AF' },
  { name: 'Albania', dial: '+355', flag: '🇦🇱', code: 'AL' },
  { name: 'Algeria', dial: '+213', flag: '🇩🇿', code: 'DZ' },
  { name: 'Andorra', dial: '+376', flag: '🇦🇩', code: 'AD' },
  { name: 'Angola', dial: '+244', flag: '🇦🇴', code: 'AO' },
  { name: 'Argentina', dial: '+54', flag: '🇦🇷', code: 'AR' },
  { name: 'Armenia', dial: '+374', flag: '🇦🇲', code: 'AM' },
  { name: 'Australia', dial: '+61', flag: '🇦🇺', code: 'AU' },
  { name: 'Austria', dial: '+43', flag: '🇦🇹', code: 'AT' },
  { name: 'Azerbaijan', dial: '+994', flag: '🇦🇿', code: 'AZ' },
  { name: 'Bahamas', dial: '+1242', flag: '🇧🇸', code: 'BS' },
  { name: 'Bahrain', dial: '+973', flag: '🇧🇭', code: 'BH' },
  { name: 'Bangladesh', dial: '+880', flag: '🇧🇩', code: 'BD' },
  { name: 'Belarus', dial: '+375', flag: '🇧🇾', code: 'BY' },
  { name: 'Belgium', dial: '+32', flag: '🇧🇪', code: 'BE' },
  { name: 'Belize', dial: '+501', flag: '🇧🇿', code: 'BZ' },
  { name: 'Benin', dial: '+229', flag: '🇧🇯', code: 'BJ' },
  { name: 'Bhutan', dial: '+975', flag: '🇧🇹', code: 'BT' },
  { name: 'Bolivia', dial: '+591', flag: '🇧🇴', code: 'BO' },
  { name: 'Bosnia and Herzegovina', dial: '+387', flag: '🇧🇦', code: 'BA' },
  { name: 'Botswana', dial: '+267', flag: '🇧🇼', code: 'BW' },
  { name: 'Brazil', dial: '+55', flag: '🇧🇷', code: 'BR' },
  { name: 'Brunei', dial: '+673', flag: '🇧🇳', code: 'BN' },
  { name: 'Bulgaria', dial: '+359', flag: '🇧🇬', code: 'BG' },
  { name: 'Burkina Faso', dial: '+226', flag: '🇧🇫', code: 'BF' },
  { name: 'Burundi', dial: '+257', flag: '🇧🇮', code: 'BI' },
  { name: 'Cambodia', dial: '+855', flag: '🇰🇭', code: 'KH' },
  { name: 'Cameroon', dial: '+237', flag: '🇨🇲', code: 'CM' },
  { name: 'Canada', dial: '+1', flag: '🇨🇦', code: 'CA' },
  { name: 'Cape Verde', dial: '+238', flag: '🇨🇻', code: 'CV' },
  { name: 'Central African Republic', dial: '+236', flag: '🇨🇫', code: 'CF' },
  { name: 'Chad', dial: '+235', flag: '🇹🇩', code: 'TD' },
  { name: 'Chile', dial: '+56', flag: '🇨🇱', code: 'CL' },
  { name: 'China', dial: '+86', flag: '🇨🇳', code: 'CN' },
  { name: 'Colombia', dial: '+57', flag: '🇨🇴', code: 'CO' },
  { name: 'Comoros', dial: '+269', flag: '🇰🇲', code: 'KM' },
  { name: 'Congo', dial: '+242', flag: '🇨🇬', code: 'CG' },
  { name: 'Costa Rica', dial: '+506', flag: '🇨🇷', code: 'CR' },
  { name: 'Croatia', dial: '+385', flag: '🇭🇷', code: 'HR' },
  { name: 'Cuba', dial: '+53', flag: '🇨🇺', code: 'CU' },
  { name: 'Cyprus', dial: '+357', flag: '🇨🇾', code: 'CY' },
  { name: 'Czech Republic', dial: '+420', flag: '🇨🇿', code: 'CZ' },
  { name: 'Denmark', dial: '+45', flag: '🇩🇰', code: 'DK' },
  { name: 'Djibouti', dial: '+253', flag: '🇩🇯', code: 'DJ' },
  { name: 'Dominican Republic', dial: '+1809', flag: '🇩🇴', code: 'DO' },
  { name: 'DR Congo', dial: '+243', flag: '🇨🇩', code: 'CD' },
  { name: 'Ecuador', dial: '+593', flag: '🇪🇨', code: 'EC' },
  { name: 'Egypt', dial: '+20', flag: '🇪🇬', code: 'EG' },
  { name: 'El Salvador', dial: '+503', flag: '🇸🇻', code: 'SV' },
  { name: 'Eritrea', dial: '+291', flag: '🇪🇷', code: 'ER' },
  { name: 'Estonia', dial: '+372', flag: '🇪🇪', code: 'EE' },
  { name: 'Eswatini', dial: '+268', flag: '🇸🇿', code: 'SZ' },
  { name: 'Ethiopia', dial: '+251', flag: '🇪🇹', code: 'ET' },
  { name: 'Fiji', dial: '+679', flag: '🇫🇯', code: 'FJ' },
  { name: 'Finland', dial: '+358', flag: '🇫🇮', code: 'FI' },
  { name: 'France', dial: '+33', flag: '🇫🇷', code: 'FR' },
  { name: 'Gabon', dial: '+241', flag: '🇬🇦', code: 'GA' },
  { name: 'Gambia', dial: '+220', flag: '🇬🇲', code: 'GM' },
  { name: 'Georgia', dial: '+995', flag: '🇬🇪', code: 'GE' },
  { name: 'Germany', dial: '+49', flag: '🇩🇪', code: 'DE' },
  { name: 'Ghana', dial: '+233', flag: '🇬🇭', code: 'GH' },
  { name: 'Greece', dial: '+30', flag: '🇬🇷', code: 'GR' },
  { name: 'Guatemala', dial: '+502', flag: '🇬🇹', code: 'GT' },
  { name: 'Guinea', dial: '+224', flag: '🇬🇳', code: 'GN' },
  { name: 'Guinea-Bissau', dial: '+245', flag: '🇬🇼', code: 'GW' },
  { name: 'Guyana', dial: '+592', flag: '🇬🇾', code: 'GY' },
  { name: 'Haiti', dial: '+509', flag: '🇭🇹', code: 'HT' },
  { name: 'Honduras', dial: '+504', flag: '🇭🇳', code: 'HN' },
  { name: 'Hungary', dial: '+36', flag: '🇭🇺', code: 'HU' },
  { name: 'Iceland', dial: '+354', flag: '🇮🇸', code: 'IS' },
  { name: 'India', dial: '+91', flag: '🇮🇳', code: 'IN' },
  { name: 'Indonesia', dial: '+62', flag: '🇮🇩', code: 'ID' },
  { name: 'Iran', dial: '+98', flag: '🇮🇷', code: 'IR' },
  { name: 'Iraq', dial: '+964', flag: '🇮🇶', code: 'IQ' },
  { name: 'Ireland', dial: '+353', flag: '🇮🇪', code: 'IE' },
  { name: 'Israel', dial: '+972', flag: '🇮🇱', code: 'IL' },
  { name: 'Italy', dial: '+39', flag: '🇮🇹', code: 'IT' },
  { name: 'Jamaica', dial: '+1876', flag: '🇯🇲', code: 'JM' },
  { name: 'Japan', dial: '+81', flag: '🇯🇵', code: 'JP' },
  { name: 'Jordan', dial: '+962', flag: '🇯🇴', code: 'JO' },
  { name: 'Kazakhstan', dial: '+7', flag: '🇰🇿', code: 'KZ' },
  { name: 'Kenya', dial: '+254', flag: '🇰🇪', code: 'KE' },
  { name: 'Kosovo', dial: '+383', flag: '🇽🇰', code: 'XK' },
  { name: 'Kuwait', dial: '+965', flag: '🇰🇼', code: 'KW' },
  { name: 'Kyrgyzstan', dial: '+996', flag: '🇰🇬', code: 'KG' },
  { name: 'Laos', dial: '+856', flag: '🇱🇦', code: 'LA' },
  { name: 'Latvia', dial: '+371', flag: '🇱🇻', code: 'LV' },
  { name: 'Lebanon', dial: '+961', flag: '🇱🇧', code: 'LB' },
  { name: 'Lesotho', dial: '+266', flag: '🇱🇸', code: 'LS' },
  { name: 'Liberia', dial: '+231', flag: '🇱🇷', code: 'LR' },
  { name: 'Libya', dial: '+218', flag: '🇱🇾', code: 'LY' },
  { name: 'Liechtenstein', dial: '+423', flag: '🇱🇮', code: 'LI' },
  { name: 'Lithuania', dial: '+370', flag: '🇱🇹', code: 'LT' },
  { name: 'Luxembourg', dial: '+352', flag: '🇱🇺', code: 'LU' },
  { name: 'Madagascar', dial: '+261', flag: '🇲🇬', code: 'MG' },
  { name: 'Malawi', dial: '+265', flag: '🇲🇼', code: 'MW' },
  { name: 'Malaysia', dial: '+60', flag: '🇲🇾', code: 'MY' },
  { name: 'Maldives', dial: '+960', flag: '🇲🇻', code: 'MV' },
  { name: 'Mali', dial: '+223', flag: '🇲🇱', code: 'ML' },
  { name: 'Malta', dial: '+356', flag: '🇲🇹', code: 'MT' },
  { name: 'Mauritania', dial: '+222', flag: '🇲🇷', code: 'MR' },
  { name: 'Mauritius', dial: '+230', flag: '🇲🇺', code: 'MU' },
  { name: 'Mexico', dial: '+52', flag: '🇲🇽', code: 'MX' },
  { name: 'Moldova', dial: '+373', flag: '🇲🇩', code: 'MD' },
  { name: 'Monaco', dial: '+377', flag: '🇲🇨', code: 'MC' },
  { name: 'Mongolia', dial: '+976', flag: '🇲🇳', code: 'MN' },
  { name: 'Montenegro', dial: '+382', flag: '🇲🇪', code: 'ME' },
  { name: 'Morocco', dial: '+212', flag: '🇲🇦', code: 'MA' },
  { name: 'Mozambique', dial: '+258', flag: '🇲🇿', code: 'MZ' },
  { name: 'Myanmar', dial: '+95', flag: '🇲🇲', code: 'MM' },
  { name: 'Namibia', dial: '+264', flag: '🇳🇦', code: 'NA' },
  { name: 'Nepal', dial: '+977', flag: '🇳🇵', code: 'NP' },
  { name: 'Netherlands', dial: '+31', flag: '🇳🇱', code: 'NL' },
  { name: 'New Zealand', dial: '+64', flag: '🇳🇿', code: 'NZ' },
  { name: 'Nicaragua', dial: '+505', flag: '🇳🇮', code: 'NI' },
  { name: 'Niger', dial: '+227', flag: '🇳🇪', code: 'NE' },
  { name: 'Nigeria', dial: '+234', flag: '🇳🇬', code: 'NG' },
  { name: 'North Korea', dial: '+850', flag: '🇰🇵', code: 'KP' },
  { name: 'North Macedonia', dial: '+389', flag: '🇲🇰', code: 'MK' },
  { name: 'Norway', dial: '+47', flag: '🇳🇴', code: 'NO' },
  { name: 'Oman', dial: '+968', flag: '🇴🇲', code: 'OM' },
  { name: 'Pakistan', dial: '+92', flag: '🇵🇰', code: 'PK' },
  { name: 'Palestine', dial: '+970', flag: '🇵🇸', code: 'PS' },
  { name: 'Panama', dial: '+507', flag: '🇵🇦', code: 'PA' },
  { name: 'Papua New Guinea', dial: '+675', flag: '🇵🇬', code: 'PG' },
  { name: 'Paraguay', dial: '+595', flag: '🇵🇾', code: 'PY' },
  { name: 'Peru', dial: '+51', flag: '🇵🇪', code: 'PE' },
  { name: 'Philippines', dial: '+63', flag: '🇵🇭', code: 'PH' },
  { name: 'Poland', dial: '+48', flag: '🇵🇱', code: 'PL' },
  { name: 'Portugal', dial: '+351', flag: '🇵🇹', code: 'PT' },
  { name: 'Qatar', dial: '+974', flag: '🇶🇦', code: 'QA' },
  { name: 'Romania', dial: '+40', flag: '🇷🇴', code: 'RO' },
  { name: 'Russia', dial: '+7', flag: '🇷🇺', code: 'RU' },
  { name: 'Rwanda', dial: '+250', flag: '🇷🇼', code: 'RW' },
  { name: 'San Marino', dial: '+378', flag: '🇸🇲', code: 'SM' },
  { name: 'Saudi Arabia', dial: '+966', flag: '🇸🇦', code: 'SA' },
  { name: 'Senegal', dial: '+221', flag: '🇸🇳', code: 'SN' },
  { name: 'Serbia', dial: '+381', flag: '🇷🇸', code: 'RS' },
  { name: 'Sierra Leone', dial: '+232', flag: '🇸🇱', code: 'SL' },
  { name: 'Singapore', dial: '+65', flag: '🇸🇬', code: 'SG' },
  { name: 'Slovakia', dial: '+421', flag: '🇸🇰', code: 'SK' },
  { name: 'Slovenia', dial: '+386', flag: '🇸🇮', code: 'SI' },
  { name: 'Somalia', dial: '+252', flag: '🇸🇴', code: 'SO' },
  { name: 'South Africa', dial: '+27', flag: '🇿🇦', code: 'ZA' },
  { name: 'South Korea', dial: '+82', flag: '🇰🇷', code: 'KR' },
  { name: 'South Sudan', dial: '+211', flag: '🇸🇸', code: 'SS' },
  { name: 'Spain', dial: '+34', flag: '🇪🇸', code: 'ES' },
  { name: 'Sri Lanka', dial: '+94', flag: '🇱🇰', code: 'LK' },
  { name: 'Sudan', dial: '+249', flag: '🇸🇩', code: 'SD' },
  { name: 'Sweden', dial: '+46', flag: '🇸🇪', code: 'SE' },
  { name: 'Switzerland', dial: '+41', flag: '🇨🇭', code: 'CH' },
  { name: 'Syria', dial: '+963', flag: '🇸🇾', code: 'SY' },
  { name: 'Taiwan', dial: '+886', flag: '🇹🇼', code: 'TW' },
  { name: 'Tajikistan', dial: '+992', flag: '🇹🇯', code: 'TJ' },
  { name: 'Tanzania', dial: '+255', flag: '🇹🇿', code: 'TZ' },
  { name: 'Thailand', dial: '+66', flag: '🇹🇭', code: 'TH' },
  { name: 'Timor-Leste', dial: '+670', flag: '🇹🇱', code: 'TL' },
  { name: 'Togo', dial: '+228', flag: '🇹🇬', code: 'TG' },
  { name: 'Trinidad and Tobago', dial: '+1868', flag: '🇹🇹', code: 'TT' },
  { name: 'Tunisia', dial: '+216', flag: '🇹🇳', code: 'TN' },
  { name: 'Turkey', dial: '+90', flag: '🇹🇷', code: 'TR' },
  { name: 'Turkmenistan', dial: '+993', flag: '🇹🇲', code: 'TM' },
  { name: 'Uganda', dial: '+256', flag: '🇺🇬', code: 'UG' },
  { name: 'Ukraine', dial: '+380', flag: '🇺🇦', code: 'UA' },
  { name: 'United Arab Emirates', dial: '+971', flag: '🇦🇪', code: 'AE' },
  { name: 'United Kingdom', dial: '+44', flag: '🇬🇧', code: 'GB' },
  { name: 'United States', dial: '+1', flag: '🇺🇸', code: 'US' },
  { name: 'Uruguay', dial: '+598', flag: '🇺🇾', code: 'UY' },
  { name: 'Uzbekistan', dial: '+998', flag: '🇺🇿', code: 'UZ' },
  { name: 'Venezuela', dial: '+58', flag: '🇻🇪', code: 'VE' },
  { name: 'Vietnam', dial: '+84', flag: '🇻🇳', code: 'VN' },
  { name: 'Yemen', dial: '+967', flag: '🇾🇪', code: 'YE' },
  { name: 'Zambia', dial: '+260', flag: '🇿🇲', code: 'ZM' },
  { name: 'Zimbabwe', dial: '+263', flag: '🇿🇼', code: 'ZW' },
];

class PhoneSelect {
  constructor(root) {
    this.root = root;
    this.trigger = root.querySelector('.phone-select__trigger');
    this.dropdown = root.querySelector('.phone-select__dropdown');
    this.searchInput = root.querySelector('.phone-select__search');
    this.list = root.querySelector('.phone-select__list');
    this.hidden = root.querySelector('.phone-select__hidden');
    this.flagEl = root.querySelector('.phone-select__flag');
    this.codeEl = root.querySelector('.phone-select__code');
    this.isOpen = false;
    this.highlightedIndex = -1;
    this._selectedCode = 'US';

    this._renderList(PHONE_COUNTRIES);
    this._bindEvents();
  }

  _renderList(countries) {
    if (!countries.length) {
      this.list.innerHTML = `<li class="phone-select__empty">No results</li>`;
      return;
    }

    this.list.innerHTML = countries
      .map(
        (c, i) => `
      <li
        class="phone-select__item${this.hidden.value === c.dial && this._selectedCode === c.code ? ' is-selected' : ''}"
        role="option"
        data-dial="${c.dial}"
        data-flag="${c.flag}"
        data-code="${c.code}"
        data-index="${i}"
        aria-selected="${this.hidden.value === c.dial && this._selectedCode === c.code}"
      >
        <span class="phone-select__item-flag">${c.flag}</span>
        <span class="phone-select__item-name">${c.name}</span>
        <span class="phone-select__item-dial">${c.dial}</span>
      </li>
    `,
      )
      .join('');

    this.filteredCountries = countries;
    this.highlightedIndex = -1;
  }

  _select(dial, flag, code) {
    this.hidden.value = dial;
    this.flagEl.textContent = flag;
    this.codeEl.textContent = dial;
    this._selectedCode = code;

    this.list.querySelectorAll('.phone-select__item').forEach((el) => {
      el.classList.toggle('is-selected', el.dataset.dial === dial && el.dataset.code === code);
    });

    this.root.dispatchEvent(new CustomEvent('phone-select:change', { detail: { dial, flag, code } }));
    this._close();
  }

  _open() {
    this.isOpen = true;
    this.root.classList.add('active');
    this.trigger.setAttribute('aria-expanded', 'true');
    this.searchInput.value = '';
    this._renderList(PHONE_COUNTRIES);
    requestAnimationFrame(() => this.searchInput.focus());
  }

  _close() {
    this.isOpen = false;
    this.root.classList.remove('active');
    this.trigger.setAttribute('aria-expanded', 'false');
    this.highlightedIndex = -1;
  }

  _toggle() {
    this.isOpen ? this._close() : this._open();
  }

  _setHighlight(index) {
    const items = this.list.querySelectorAll('.phone-select__item');
    if (!items.length) return;

    if (this.highlightedIndex >= 0 && items[this.highlightedIndex]) {
      items[this.highlightedIndex].classList.remove('is-highlighted');
    }

    this.highlightedIndex = Math.max(0, Math.min(index, items.length - 1));
    const target = items[this.highlightedIndex];
    target.classList.add('is-highlighted');
    target.scrollIntoView({ block: 'nearest' });
  }

  _bindEvents() {
    this.trigger.addEventListener('click', () => this._toggle());

    this.list.addEventListener('click', (e) => {
      const item = e.target.closest('.phone-select__item');
      if (!item) return;
      this._select(item.dataset.dial, item.dataset.flag, item.dataset.code);
    });

    this.searchInput.addEventListener('input', () => {
      const q = this.searchInput.value.trim().toLowerCase();
      const filtered = q ? PHONE_COUNTRIES.filter((c) => c.name.toLowerCase().includes(q) || c.dial.includes(q) || c.code.toLowerCase().includes(q)) : PHONE_COUNTRIES;
      this._renderList(filtered);
    });

    this.searchInput.addEventListener('keydown', (e) => {
      const items = this.list.querySelectorAll('.phone-select__item');

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        this._setHighlight(this.highlightedIndex + 1);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        this._setHighlight(this.highlightedIndex - 1);
      } else if (e.key === 'Enter') {
        e.preventDefault();
        const target = items[this.highlightedIndex];
        if (target) this._select(target.dataset.dial, target.dataset.flag, target.dataset.code);
      } else if (e.key === 'Escape') {
        this._close();
        this.trigger.focus();
      }
    });

    this.trigger.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this._toggle();
      } else if (e.key === 'Escape') {
        this._close();
      }
    });

    document.addEventListener('click', (e) => {
      if (this.isOpen && !this.root.contains(e.target)) this._close();
    });
  }
}

class PhoneInput {
  constructor(selectRoot, phoneInput) {
    this.phoneInput = phoneInput;
    this.phoneSelect = selectRoot._phoneSelectInstance;
    this.prefix = this.phoneSelect.hidden.value;

    this._init();
    this._bindEvents();
  }

  _init() {
    this.phoneInput.value = this.prefix + ' ';
    this._moveCursorToEnd();
  }

  _moveCursorToEnd() {
    const len = this.phoneInput.value.length;
    this.phoneInput.setSelectionRange(len, len);
  }

  _updatePrefix(newPrefix) {
    const old = this.prefix;
    const current = this.phoneInput.value;
    const number = current.startsWith(old) ? current.slice(old.length).trimStart() : '';
    this.prefix = newPrefix;
    this.phoneInput.value = newPrefix + (number ? ' ' + number : ' ');
    this._moveCursorToEnd();
  }

  _bindEvents() {
    this.phoneInput.addEventListener('keydown', (e) => {
      const pos = this.phoneInput.selectionStart;
      const guard = this.prefix.length + 1;

      if ((e.key === 'Backspace' || e.key === 'Delete') && pos <= guard) {
        e.preventDefault();
      }
      if (e.key === 'ArrowLeft' && pos <= guard) {
        e.preventDefault();
      }
    });

    this.phoneInput.addEventListener('click', () => {
      const guard = this.prefix.length + 1;
      if (this.phoneInput.selectionStart < guard) {
        this._moveCursorToEnd();
      }
    });

    this.phoneInput.addEventListener('input', () => {
      if (!this.phoneInput.value.startsWith(this.prefix)) {
        this.phoneInput.value = this.prefix + ' ';
        this._moveCursorToEnd();
      }
    });

    this.phoneInput.addEventListener('paste', (e) => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
      const current = this.phoneInput.value;
      const after = current.slice(this.prefix.length + 1).replace(/\D/g, '');
      this.phoneInput.value = this.prefix + ' ' + after + pasted;
      this._moveCursorToEnd();
    });

    this.phoneSelect.root.addEventListener('phone-select:change', (e) => {
      this._updatePrefix(e.detail.dial);
    });
  }
}

export const initPhoneSelect = () => {
  document.querySelectorAll('.phone-select').forEach((el) => {
    const instance = new PhoneSelect(el);
    el._phoneSelectInstance = instance;
    const phoneInput = el.closest('.support-popup__phone')?.querySelector('.support-popup__input--phone');
    if (phoneInput) new PhoneInput(el, phoneInput);
  });
};

export const SupportPopup = (() => {
  const popup = document.getElementById('supportPopup');
  const overlay = popup?.querySelector('.support-popup__overlay');
  const closeBtn = popup?.querySelector('.support-popup__close');
  const cancelBtn = document.querySelectorAll('.support-popup__btn--cancel');
  const PARAM = 'open-support';

  function open() {
    if (!popup) return;
    popup.classList.add('active');
    const url = new URL(window.location.href);
    url.searchParams.set(PARAM, 'true');
    history.pushState(null, '', url.toString());
  }

  function close() {
    if (!popup) return;
    popup.classList.remove('active');
    popup.classList.remove('error');
    popup.classList.remove('success');
    const url = new URL(window.location.href);
    url.searchParams.delete(PARAM);
    history.pushState(null, '', url.toString());
    const fields = popup?.querySelectorAll('.support-popup__input:not(.support-popup__input--phone), .support-popup__input--phone, .support-popup__textarea');
    if (fields) fields.forEach((el) => (el.value = ''));
  }

  function bindTriggers() {
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('a[href*="#open-support"]');
      if (!trigger) return;
      e.preventDefault();
      open();
    });

    overlay?.addEventListener('click', close);
    closeBtn?.addEventListener('click', close);
    cancelBtn?.forEach((btn) => btn.addEventListener('click', close));

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && popup?.classList.contains('active')) close();
    });

    window.addEventListener('popstate', () => {
      const params = new URLSearchParams(window.location.search);
      params.get(PARAM) === 'true' ? open() : close();
    });
  }

  function checkOnLoad() {
    const params = new URLSearchParams(window.location.search);
    if (params.get(PARAM) === 'true') open();
  }

  function init() {
    if (!popup) return;
    bindTriggers();
    checkOnLoad();
    const form = document.querySelector('.support-popup__form');
    const fields = form?.querySelectorAll('.support-popup__input:not(.support-popup__input--phone), .support-popup__input--phone, .support-popup__textarea');

    if (form && fields) {
      const check = () => {
        const code = form?.querySelector('.phone-select__code').textContent;
        const filled = [...fields].every((f) => f.value.trim().replace(code, '').length > 0);
        form.classList.toggle('is-filled', filled);
      };

      fields.forEach((f) => f.addEventListener('input', check));
    }
  }

  return { init, open, close };
})();

export const SupportFormSend = (() => {
  const form = document.querySelector('.support-popup__form');
  const sendBtn = document.querySelector('.support-popup__btn--send');
  const popup = document.getElementById('supportPopup');

  const STATE = {
    idle: 'idle',
    loading: 'loading',
    success: 'success',
    error: 'error',
  };

  let current = STATE.idle;

  function setState(state) {
    current = state;
    form?.classList.remove('is-loading', 'is-success', 'is-error');

    if (state === STATE.loading) {
      form.classList.add('is-loading');
      sendBtn.disabled = true;
      sendBtn.textContent = 'Sending...';
    }

    if (state === STATE.error) {
      form.classList.add('is-error');
      sendBtn.disabled = false;
      sendBtn.textContent = 'Send';
    }

    if (state === STATE.idle) {
      sendBtn.disabled = false;
      sendBtn.textContent = 'Send';
      clearMessage();
    }
  }

  function showMessage(type, text) {
    clearMessage();
    const popup = document.querySelector('.support-popup');
    popup.classList.remove('error');
    popup.classList.remove('success');
    popup.classList.add(type);
    document.querySelector('.status-title').textContent = text;
  }

  function clearMessage() {
    popup?.querySelectorAll('.support-popup__message').forEach((el) => el.remove());
  }

  function resetForm() {
    form?.querySelectorAll('input:not([type="hidden"]), textarea').forEach((el) => {
      if (!el.classList.contains('phone-select__search')) el.value = '';
    });
    form?.classList.remove('is-filled');

    const phoneInput = form?.querySelector('.support-popup__input--phone');
    const hidden = form?.querySelector('.phone-select__hidden');
    if (phoneInput && hidden) phoneInput.value = hidden.value + ' ';
  }

  async function send() {
    if (current === STATE.loading) return;

    clearMessage();

    setState(STATE.loading);

    try {
      const formData = new FormData(form);
      formData.append('nonce', window.supportForm?.nonce);
      formData.append('action', 'support_form');

      const res = await fetch(window.supportForm?.url, {
        method: 'POST',
        body: formData,
      });

      const json = await res.json();

      if (json.success) {
        setState(STATE.success);
        sendBtn.disabled = false;
        sendBtn.textContent = 'Send';
        showMessage('success', json.data?.message);
        resetForm();
      } else {
        setState(STATE.error);
        showMessage('error', json.data?.message ?? 'Something went wrong. Please try again.');
      }
    } catch {
      setState(STATE.error);
      showMessage('error', 'Network error. Please check your connection.');
    }
  }

  function init() {
    if (!form || !sendBtn) return;
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      send();
    });
  }

  return { init };
})();
