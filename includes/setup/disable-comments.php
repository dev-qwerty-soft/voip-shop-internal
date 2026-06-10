<?php

function completely_disable_comments() {
  foreach (get_post_types() as $post_type) {
    if (post_type_supports($post_type, 'comments')) {
      remove_post_type_support($post_type, 'comments');
      remove_post_type_support($post_type, 'trackbacks');
    }
  }

  remove_menu_page('edit-comments.php');
  remove_submenu_page('options-general.php', 'options-discussion.php');

  if (get_current_screen()) {
    if (is_admin() && get_current_screen()->id === 'edit-comments') {
      wp_redirect(admin_url());
      exit();
    }
  }

  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

  add_filter('comments_open', '__return_false', 20, 2);
  add_filter('pings_open', '__return_false', 20, 2);
  add_filter('comments_array', '__return_empty_array', 10, 2);
}
add_action('admin_init', 'completely_disable_comments');

function remove_adminbar_comments() {
  if (is_admin_bar_showing()) {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  }
}
add_action('init', 'remove_adminbar_comments');
