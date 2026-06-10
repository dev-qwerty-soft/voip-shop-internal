<?php

if (!defined('ABSPATH')) {
  exit();
}

add_action(
  'wp_enqueue_scripts',
  function () {
    if (class_exists('YITH_WAPO')) {
      add_filter(
        'script_loader_tag',
        function ($tag, $handle) {
          if (strpos($handle, 'yith') !== false || strpos($handle, 'wapo') !== false) {
            return $tag;
          }
          return $tag;
        },
        5,
        2
      );
    }
  },
  999
);

add_action(
  'wp_enqueue_scripts',
  function () {
    if (is_product()) {
      wp_enqueue_script('jquery');
    }
  },
  1
);

add_filter('yith_wapo_show_total_price', '__return_true');
add_filter('yith_wapo_enable_ajax_update', '__return_true');

add_action(
  'wp_footer',
  function () {
    if (!is_product()) {
      return;
    } ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(document).on('yith_wapo_after_calculate', function(e, data) {
                if (typeof data !== 'undefined' && typeof data.total !== 'undefined') {}
            });
        });
    </script>
<?php
  },
  999
);
