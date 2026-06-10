<div class="support-popup" id="supportPopup" aria-modal="true" role="dialog" aria-labelledby="supportPopupTitle">
  <div class="support-popup__overlay"></div>
  <div class="support-popup__wrapper">
    <div class="support-popup__inner">
      <button class="support-popup__close" type="button" aria-label="<?php esc_attr_e('Close Modal', 'voip-theme'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
          <path d="M1 1L17 17M17 1L1 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </button>
      <h2 class="support-popup__title" id="supportPopupTitle">
        <?= get_field('support_title', 'option') ?>
      </h2>
      <p class="support-popup__desc">
        <?= get_field('support_description', 'option') ?>
      </p>
      <form action="#" method="post" class="support-popup__form">
        <div class="support-popup__grid">
          <div class="support-popup__field">
            <label class="support-popup__label" for="supportName">
              <?php esc_html_e('Name', 'voip-theme'); ?>
            </label>
            <input
              class="support-popup__input"
              type="text"
              id="supportName"
              name="support_name"
              placeholder="<?php esc_attr_e('Enter here', 'voip-theme'); ?>"
              autocomplete="name"
              required
            >
          </div>
          <div class="support-popup__field">
            <label class="support-popup__label" for="supportCompany">
              <?php esc_html_e('Company name', 'voip-theme'); ?>
            </label>
            <input
              class="support-popup__input"
              type="text"
              id="supportCompany"
              name="support_company"
              placeholder="<?php esc_attr_e('Enter here', 'voip-theme'); ?>"
              autocomplete="organization"
              required
            >
          </div>
          <div class="support-popup__field">
            <label class="support-popup__label" for="supportPhone">
              <?php esc_html_e('Phone Number', 'voip-theme'); ?>
            </label>
            <div class="support-popup__phone">
              <div class="phone-select" id="phoneSelect">
                <input type="hidden" name="support_phone_code" class="phone-select__hidden" value="+1">
                <button
                  type="button"
                  class="phone-select__trigger"
                  aria-haspopup="listbox"
                  aria-expanded="false"
                  aria-label="<?php esc_attr_e('Select country code', 'voip-theme'); ?>"
                >
                  <span class="phone-select__flag">🇺🇸</span>
                  <span class="phone-select__code">+1</span>
                  <svg class="phone-select__arrow" width="10" height="6" viewBox="0 0 10 6" fill="none">
                    <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
                <div class="phone-select__dropdown" role="listbox">
                  <div class="phone-select__search-wrap">
                    <svg class="phone-select__search-icon" width="14" height="14" viewBox="0 0 14 14" fill="none">
                      <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.3"/>
                      <path d="M10 10L13 13" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    <input
                      type="text"
                      class="phone-select__search"
                      placeholder="<?php esc_attr_e('Search country...', 'voip-theme'); ?>"
                      autocomplete="off"
                    >
                  </div>
                  <ul class="phone-select__list"></ul>
                </div>
              </div>
              <input
                class="support-popup__input support-popup__input--phone"
                type="tel"
                id="supportPhone"
                name="support_phone"
                placeholder="___-___-____"
                autocomplete="tel"
                required
              >
            </div>
          </div>
          <div class="support-popup__field">
            <label class="support-popup__label" for="supportEmail">
              <?php esc_html_e('Email adress', 'voip-theme'); ?>
            </label>
            <input
              class="support-popup__input"
              type="email"
              id="supportEmail"
              name="support_email"
              placeholder="<?php esc_attr_e('Enter here', 'voip-theme'); ?>"
              autocomplete="email"
              required
            >
          </div>
          <div class="support-popup__field support-popup__field--full">
            <label class="support-popup__label" for="supportDescription">
              <?php esc_html_e('Description', 'voip-theme'); ?>
            </label>
            <textarea
              class="support-popup__textarea"
              id="supportDescription"
              name="support_description"
              placeholder="<?php esc_attr_e('Enter here', 'voip-theme'); ?>"
              rows="4"
              autocomplete="off"
              required
            ></textarea>
          </div>
        </div>
        <div class="support-popup__actions">
          <button class="btn support-popup__btn--send" type="submit">
            <?php esc_html_e('Send', 'voip-theme'); ?>
          </button>
          <button class="btn support-popup__btn--cancel btn--light" type="button" id="supportCancel">
            <?php esc_html_e('Cancel', 'voip-theme'); ?>
          </button>
        </div>
      </form>
    </div>
    <div class="support-popup__inner--result">
      <div class="icon-status icon-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
          <rect width="48" height="48" rx="24" fill="#2CFF56"/>
          <path d="M30 21L22 29L18 25" stroke="#2F3E46" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="icon-status icon-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="1190" height="1190" viewBox="0 0 1190 1190" fill="none">
          <g clip-path="url(#clip0_244_8)">
            <path d="M1190 595C1190 266.391 923.609 0 595 0C266.391 0 0 266.391 0 595C0 923.609 266.391 1190 595 1190C923.609 1190 1190 923.609 1190 595Z" fill="#FF1900"/>
            <path d="M395 395L795 795M795 395L395 795" stroke="#2F3E46" stroke-width="66.6667" stroke-linecap="round" stroke-linejoin="round"/>
          </g>
          <defs>
            <clipPath id="clip0_244_8">
              <rect width="1190" height="1190" fill="white"/>
            </clipPath>
          </defs>
        </svg>
      </div>
      <span class="status-title">Ticket sent</span>
      <p><?= get_field('support_success_message', 'option') ?></p>
      <button class="btn support-popup__btn--cancel btn--light">Got It</button>
    </div>
  </div>
</div>