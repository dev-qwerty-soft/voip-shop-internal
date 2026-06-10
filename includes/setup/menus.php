<?php
register_nav_menus([
  'header-menu' => esc_html('Header Menu'),
  'footer-menu' => esc_html('Footer Menu Main'),
  'footer-menu-login' => esc_html('Footer Menu Log In'),
  'footer-menu-support' => esc_html('Footer Menu Support'),
  'footer-menu-aside' => esc_html('Footer aside Menu'),
]);

add_filter('wp_nav_menu_items', function ($items, $args) {
  if ($args->theme_location !== 'header-menu') return $items;

  // Отримуємо dropdown html
  ob_start();
  $solutions_group = get_field('solutions_dropdown_menu', 'options');
  $solutions_group_links = isset($solutions_group['solutions_dropdown_menu_links'])
    ? $solutions_group['solutions_dropdown_menu_links']
    : [];

  if ($solutions_group_links && is_array($solutions_group_links) && count($solutions_group_links) > 0):
?>
    <div class="header-menu-dropdown header-menu-dropdown--solutions">
      <ul class="header-menu-dropdown__links">
        <?php foreach ($solutions_group_links as $link) {
          $text = isset($link['text']) ? $link['text'] : '';
          $link_obj = isset($link['link']) ? $link['link'] : '';
          $target_link = isset($link['target']) ? $link['target'] : '';
          $url_link = isset($link_obj['url']) ? $link_obj['url'] : '';
          $icon = isset($link['icon']) ? $link['icon'] : '';
          $icon_url = isset($icon['url']) ? $icon['url'] : '';
          $img = displaySvg($icon_url);

          echo "<li class='header-menu-dropdown__links-item--center'>
                        <a target='$target_link' href='$url_link'>
                            <div class='icon'>$img</div>
                            <div class='text text--title'>
                                <span>$text</span>
                            </div>
                        </a>
                    </li>";
        } ?>
      </ul>
    </div>
<?php
  endif;
  $dropdown = ob_get_clean();

  $items = preg_replace(
    '/(<li[^>]*>\s*<a[^>]*href=["\']#solutions["\'][^>]*>.*?<\/a>)(\s*<\/li>)/s',
    '$1' . $dropdown . '$2',
    $items
  );

  return $items;
}, 10, 2);
