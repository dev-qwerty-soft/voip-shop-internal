<?php
$footer_desc = get_field('footer__desc', 'options');
$facebook = get_field('facebook', 'options');
$instagram = get_field('instagram', 'options');
$youtube = get_field('youtube', 'options');
$twitter = get_field('twitter', 'options');
$current_url = $_SERVER['REQUEST_URI'];
$help_center_link = get_field('help_center_link', 'options');
$contact_support_link = get_field('contact_support_link', 'options');
$open_support_link = get_field('support_ticket_link', 'options');
?>
<footer class="footer">
  <div class="container">
    <div class="footer__top">
      <div class="footer__top-l">
        <div class="footer__logo">
          <?php if (function_exists('get_field')) {
            $footer_logo = get_field('footer_logo', 'option');
            if ($footer_logo) { ?>
              <img width="100" height="20" src="<?= esc_url($footer_logo['url']) ?>" alt="<?= esc_attr($footer_logo['alt']) ?>" draggable="false">
          <?php } else {
              if (has_custom_logo()) {
                the_custom_logo();
              }
            }
          } else {
            if (has_custom_logo()) {
              the_custom_logo();
            }
          } ?>
        </div>
        <?php if ($footer_desc) {
          echo '<div class="footer__desc">' . $footer_desc . '</div>';
        } ?>
        <div class="footer__social">
          <?php
          if ($facebook): ?>
            <a target="_blank" rel="noopener noreferrer" aria-label="Facebook link" href="<?= $facebook ?>">
              <?= displaySvg('src/svg/ic_fb.svg') ?>
            </a>
          <?php endif;
          if ($instagram): ?>
            <a target="_blank" rel="noopener noreferrer" aria-label="Instagram link" href="<?= $instagram ?>">
              <?= displaySvg('src/svg/ic_inst.svg') ?>
            </a>
          <?php endif;
          if ($youtube): ?>
            <a target="_blank" rel="noopener noreferrer" aria-label="Youtube link" href="<?= $youtube ?>">
              <?= displaySvg('src/svg/ic_youtube.svg') ?>
            </a>
          <?php endif;
          if ($twitter): ?>
            <a target="_blank" rel="noopener noreferrer" aria-label="Twitter link" href="<?= $twitter ?>">
              <?= displaySvg('src/svg/ic_twitter.svg') ?>
            </a>
          <?php endif;
          ?>
        </div>
      </div>
      <div class="footer__top-m">
        <?php if (has_nav_menu('footer-menu')) : ?>
          <div class="footer__menu">
            <span class="footer__menu-title">MENU</span>
            <nav>
              <?php wp_nav_menu([
                'theme_location' => 'footer-menu',
                'container' => false,
                'fallback_cb' => '__return_empty_string',
              ]); ?>
            </nav>
          </div>
        <?php endif; ?>

        <?php if (has_nav_menu('footer-menu-login')) : ?>
          <div class="footer__menu">
            <span class="footer__menu-title">LOG IN</span>
            <nav>
              <?php wp_nav_menu([
                'theme_location' => 'footer-menu-login',
                'container' => false,
                'fallback_cb' => '__return_empty_string',
              ]); ?>
            </nav>
          </div>
        <?php endif; ?>

        <?php if (has_nav_menu('footer-menu-support')) : ?>
          <div class="footer__menu big">
            <span class="footer__menu-title">SUPPORT</span>
            <nav>
              <?php wp_nav_menu([
                'theme_location' => 'footer-menu-support',
                'container' => false,
                'fallback_cb' => '__return_empty_string',
              ]); ?>
            </nav>
          </div>
        <?php endif; ?>
      </div>
      <div class="footer__top-r">
        <?= do_shortcode('[forminator_form id="85"]') ?>
        <?php if ($help_center_link || $contact_support_link || $open_support_link):
          $cards = array_filter([
            $help_center_link,
            $contact_support_link,
            $open_support_link
          ]);

          $cards_count = count($cards); ?>
          <ul class="footer__cards <?= $cards_count === 2 ? 'footer__cards--two' : '' ?>">
            <?php if ($help_center_link): ?>
              <li>
                <a href="<?= esc_url($help_center_link['url']) ?>" target="<?= esc_attr($help_center_link['target'] ? $help_center_link['target'] : '_self') ?>">
                  <svg class="icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_1491_645)">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M9 17.25C4.44353 17.25 0.75 13.5565 0.75 9C0.75 4.44353 4.44353 0.75 9 0.75C13.5565 0.75 17.25 4.44353 17.25 9C17.25 13.5565 13.5565 17.25 9 17.25ZM6.525 7.17015H7.84995C7.89532 6.47137 8.36392 6.02588 9.09075 6.02588C9.8019 6.02588 10.2754 6.45983 10.2754 7.05713C10.2754 7.61565 10.0387 7.92007 9.33908 8.34412C8.5611 8.80035 8.23358 9.30772 8.28473 10.1426L8.2905 10.5427H9.59895V10.2161C9.59895 9.65175 9.80767 9.35805 10.546 8.92987C11.3133 8.47283 11.7423 7.86975 11.7423 7.0068C11.7415 5.766 10.7086 4.875 9.16335 4.875C7.49025 4.875 6.57037 5.8452 6.525 7.17015ZM8.96122 13.1935C9.41827 13.1935 9.7788 12.8387 9.7788 12.3932C9.7788 11.9477 9.41827 11.5971 8.96205 11.5971C8.50418 11.5971 8.13787 11.9469 8.13787 12.3924C8.13787 12.8379 8.50417 13.1935 8.96122 13.1935Z" fill="#D4D6E2" />
                    </g>
                    <defs>
                      <clipPath id="clip0_1491_645">
                        <rect width="18" height="18" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                  <span><?= isset($help_center_link['title']) ? $help_center_link['title'] : '' ?></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if ($contact_support_link): ?>
              <li>
                <a href="<?= esc_url($contact_support_link['url']) ?>" target="<?= esc_attr($contact_support_link['target'] ? $contact_support_link['target'] : '_self') ?>">
                  <svg class="icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.8557 13.1485C15.2974 12.586 13.9453 11.7651 13.2893 11.4343C12.435 11.004 12.3647 10.9688 11.6932 11.4677C11.2453 11.8006 10.9475 12.098 10.4233 11.9862C9.89915 11.8745 8.76008 11.2441 7.7627 10.2499C6.76532 9.25566 6.0984 8.08355 5.98626 7.56113C5.87411 7.03871 6.17645 6.74445 6.50622 6.29551C6.97098 5.66269 6.93583 5.55723 6.53856 4.70293C6.22883 4.03848 5.38403 2.69902 4.81942 2.14355C4.21544 1.54695 4.21544 1.65242 3.82626 1.81414C3.5094 1.94742 3.20544 2.10947 2.91817 2.29824C2.35567 2.67195 2.04348 2.98238 1.82516 3.44891C1.60684 3.91543 1.50876 5.00914 2.63622 7.05734C3.76368 9.10555 4.55469 10.1529 6.19192 11.7855C7.82915 13.4182 9.08809 14.296 10.9285 15.3282C13.2052 16.6033 14.0785 16.3548 14.5465 16.1368C15.0144 15.9188 15.3262 15.6095 15.7006 15.047C15.8899 14.7602 16.0523 14.4565 16.1858 14.1399C16.3479 13.7521 16.4533 13.7521 15.8557 13.1485Z" fill="#D4D6E2" />
                  </svg>
                  <span><?= isset($contact_support_link['title']) ? $contact_support_link['title'] : '' ?></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if ($open_support_link): ?>
              <li>
                <a href="<?= esc_url($open_support_link['url']) ?>" target="<?= esc_attr($open_support_link['target'] ? $contact_support_link['target'] : '_self') ?>">
                  <svg class="icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.5 8.0625C16.8075 8.0625 17.0625 7.8075 17.0625 7.5V6.75C17.0625 3.4425 16.0575 2.4375 12.75 2.4375H8.0625V4.125C8.0625 4.4325 7.8075 4.6875 7.5 4.6875C7.1925 4.6875 6.9375 4.4325 6.9375 4.125V2.4375H5.25C1.9425 2.4375 0.9375 3.4425 0.9375 6.75V7.125C0.9375 7.4325 1.1925 7.6875 1.5 7.6875C2.22 7.6875 2.8125 8.28 2.8125 9C2.8125 9.72 2.22 10.3125 1.5 10.3125C1.1925 10.3125 0.9375 10.5675 0.9375 10.875V11.25C0.9375 14.5575 1.9425 15.5625 5.25 15.5625H6.9375V13.875C6.9375 13.5675 7.1925 13.3125 7.5 13.3125C7.8075 13.3125 8.0625 13.5675 8.0625 13.875V15.5625H12.75C16.0575 15.5625 17.0625 14.5575 17.0625 11.25C17.0625 10.9425 16.8075 10.6875 16.5 10.6875C15.78 10.6875 15.1875 10.095 15.1875 9.375C15.1875 8.655 15.78 8.0625 16.5 8.0625ZM8.0625 10.6275C8.0625 10.935 7.8075 11.19 7.5 11.19C7.1925 11.19 6.9375 10.935 6.9375 10.6275V7.3725C6.9375 7.065 7.1925 6.81 7.5 6.81C7.8075 6.81 8.0625 7.065 8.0625 7.3725V10.6275Z" fill="white" />
                  </svg>
                  <svg class="chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 2.93L8.035 5.965L5 9" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span><?= isset($open_support_link['title']) ? $open_support_link['title'] : '' ?></span>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
    <?= displaySvg('src/svg/footer-line.svg') ?>
    <div class="footer__bottom">
      <div class="footer__copy">
        <?php printf(esc_html__('© %s VOIPx3. All Rights Reserved', 'voip-theme'), date('Y')); ?>
      </div>
      <nav class="footer__menu-aside">
        <?php wp_nav_menu([
          'theme_location' => 'footer-menu-aside',
          'container' => false,
          'menu_class' => 'footer__menu-aside',
          'fallback_cb' => function () {
            echo '<ul class="footer__bottom-menu">';
            echo '<li><a href="' . home_url('/privacy') . '">Privacy Policy</a></li>';
            echo '<li><a href="' . home_url('/terms') . '">Terms of Use</a></li>';
            echo '</ul>';
          },
        ]); ?>
      </nav>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
<div id="retell-container"></div>
<div class="popup-chat">
  <button id="open-chat" aria-label="Open chat" type="button">
    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" clip-rule="evenodd" d="M9 1.5C9.67575 1.5 10.3305 1.59 10.9537 1.758C10.5644 1.96246 10.2435 2.27654 10.0307 2.66142C9.81793 3.04629 9.7226 3.48508 9.75652 3.92355C9.79043 4.36202 9.95209 4.78093 10.2215 5.12851C10.491 5.47609 10.8563 5.73709 11.2725 5.87925L11.556 5.976C11.6646 6.01301 11.7633 6.07445 11.8444 6.15558C11.9256 6.23672 11.987 6.33539 12.024 6.444L12.1215 6.7275C12.2639 7.14325 12.5249 7.50819 12.8723 7.77729C13.2198 8.04639 13.6384 8.20785 14.0765 8.24175C14.5147 8.27565 14.9532 8.1805 15.3379 7.96805C15.7225 7.7556 16.0366 7.43515 16.2413 7.04625C16.4131 7.68328 16.5001 8.3402 16.5 9C16.5 13.1422 13.1422 16.5 9 16.5H3C2.60218 16.5 2.22064 16.342 1.93934 16.0607C1.65804 15.7794 1.5 15.3978 1.5 15V9C1.5 4.85775 4.85775 1.5 9 1.5ZM9 10.5H6.75C6.55109 10.5 6.36032 10.579 6.21967 10.7197C6.07902 10.8603 6 11.0511 6 11.25C6 11.4489 6.07902 11.6397 6.21967 11.7803C6.36032 11.921 6.55109 12 6.75 12H9C9.19891 12 9.38968 11.921 9.53033 11.7803C9.67098 11.6397 9.75 11.4489 9.75 11.25C9.75 11.0511 9.67098 10.8603 9.53033 10.7197C9.38968 10.579 9.19891 10.5 9 10.5ZM11.25 7.5H6.75C6.55109 7.5 6.36032 7.57902 6.21967 7.71967C6.07902 7.86032 6 8.05109 6 8.25C6 8.44891 6.07902 8.63968 6.21967 8.78033C6.36032 8.92098 6.55109 9 6.75 9H11.25C11.4489 9 11.6397 8.92098 11.7803 8.78033C11.921 8.63968 12 8.44891 12 8.25C12 8.05109 11.921 7.86032 11.7803 7.71967C11.6397 7.57902 11.4489 7.5 11.25 7.5ZM14.25 0.75C14.4064 0.750062 14.559 0.799046 14.6862 0.890092C14.8134 0.981139 14.909 1.10969 14.9595 1.25775L15.057 1.54125C15.282 2.2005 15.7995 2.71875 16.4595 2.94375L16.7422 3.0405C16.8901 3.09118 17.0185 3.18681 17.1094 3.31402C17.2003 3.44123 17.2491 3.59366 17.2491 3.75C17.2491 3.90634 17.2003 4.05877 17.1094 4.18598C17.0185 4.31319 16.8901 4.40882 16.7422 4.4595L16.4587 4.557C15.7995 4.782 15.2813 5.2995 15.0563 5.9595L14.9595 6.24225C14.9088 6.39015 14.8132 6.51851 14.686 6.60939C14.5588 6.70027 14.4063 6.74913 14.25 6.74913C14.0937 6.74913 13.9412 6.70027 13.814 6.60939C13.6868 6.51851 13.5912 6.39015 13.5405 6.24225L13.443 5.95875C13.3319 5.63336 13.1478 5.33774 12.9046 5.09462C12.6615 4.8515 12.3659 4.66732 12.0405 4.55625L11.7578 4.4595C11.6099 4.40882 11.4815 4.31319 11.3906 4.18598C11.2997 4.05877 11.2509 3.90634 11.2509 3.75C11.2509 3.59366 11.2997 3.44123 11.3906 3.31402C11.4815 3.18681 11.6099 3.09118 11.7578 3.0405L12.0413 2.943C12.7005 2.718 13.2187 2.2005 13.4437 1.5405L13.5405 1.25775C13.591 1.10969 13.6866 0.981139 13.8138 0.890092C13.941 0.799046 14.0936 0.750062 14.25 0.75Z" fill="#D4D6E2" />
    </svg>
    <span>Chat with AVA</span>
  </button>
  <button id="open-callback" aria-label="Request a Call" type="button">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
      <path d="M15.8557 13.1484C15.2974 12.5859 13.9453 11.765 13.2893 11.4342C12.435 11.0039 12.3647 10.9688 11.6932 11.4676C11.2453 11.8005 10.9475 12.098 10.4233 11.9862C9.89915 11.8744 8.76008 11.244 7.7627 10.2498C6.76532 9.25559 6.0984 8.08348 5.98626 7.56106C5.87411 7.03863 6.17645 6.74438 6.50622 6.29543C6.97098 5.66262 6.93583 5.55715 6.53856 4.70285C6.22883 4.0384 5.38403 2.69895 4.81942 2.14348C4.21544 1.54688 4.21544 1.65234 3.82626 1.81406C3.5094 1.94734 3.20544 2.10939 2.91817 2.29816C2.35567 2.67188 2.04348 2.98231 1.82516 3.44883C1.60684 3.91535 1.50876 5.00906 2.63622 7.05727C3.76368 9.10547 4.55469 10.1528 6.19192 11.7854C7.82915 13.4181 9.08809 14.2959 10.9285 15.3281C13.2052 16.6032 14.0785 16.3547 14.5465 16.1367C15.0144 15.9188 15.3262 15.6094 15.7006 15.0469C15.8899 14.7601 16.0523 14.4565 16.1858 14.1398C16.3479 13.7521 16.4533 13.7521 15.8557 13.1484Z" fill="white" />
    </svg>
    <span>Get a call from us</span>
  </button>
</div>
<?php if ($current_url !== '/' && $current_url !== '/about-page/'): ?>
  <div class="chat-element">
    <?= displaySvg('src/svg/hero-el.svg') ?>
  </div>
<?php endif; ?>
<?php 
get_template_part('template-blocks/support-modal');
get_template_part('template-blocks/schedule-modal'); 
?>
</body>

</html>