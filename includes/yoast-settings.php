<?php

add_filter('wpseo_breadcrumb_separator', 'custom_yoast_breadcrumb_separator');
function custom_yoast_breadcrumb_separator($separator) {
  $svg = '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M5 2.99982L8.035 6.03482L5 9.06982" stroke="#A3A3A3" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>';

  return $svg;
}

add_filter('wpseo_breadcrumb_single_link', 'custom_home_icon_in_breadcrumbs', 10, 2);
function custom_home_icon_in_breadcrumbs($link_output, $link) {
  if ((isset($link['id']) && $link['id'] === 'home') || (isset($link['url']) && $link['url'] === home_url('/'))) {
    $svg = '
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M7.765 3.1168L3.7225 6.2668C3.0475 6.7918 2.5 7.9093 2.5 8.7568V14.3143C2.5 16.0543 3.9175 17.4793 5.6575 17.4793H14.3425C16.0825 17.4793 17.5 16.0543 17.5 14.3218V8.8618C17.5 7.9543 16.8925 6.7918 16.15 6.2743L11.515 3.0268C10.465 2.2918 8.7775 2.3293 7.765 3.1168Z" stroke="#8B8A93" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M10 14.4795V12.2295" stroke="#8B8A93" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ';

    $link_output = str_replace('</a>', '</a> ' . $svg, $link_output);
  }

  return $link_output;
}
