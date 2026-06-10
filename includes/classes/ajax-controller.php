<?php

if (!defined('ABSPATH')) {
  exit();
}

class AjaxController {
  public function __construct() {
    add_action('wp_ajax_theme_ajax', [$this, 'handle_ajax_request']);
    add_action('wp_ajax_nopriv_theme_ajax', [$this, 'handle_ajax_request']);

    add_action('wp_ajax_load_more_posts', [$this, 'load_more_posts']);
    add_action('wp_ajax_nopriv_load_more_posts', [$this, 'load_more_posts']);
  }

  public function handle_ajax_request() {
    // Rate limiting check
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $key = 'ajax_rate_' . md5($ip);
    $requests = get_transient($key) ?: 0;

    if ($requests >= 10) {
      wp_send_json_error(['message' => 'Too many requests']);
    }

    set_transient($key, $requests + 1, MINUTE_IN_SECONDS);

    if (!wp_verify_nonce($_POST['nonce'], 'theme_nonce')) {
      wp_die('Security check failed');
    }

    $action_type = sanitize_text_field($_POST['action_type'] ?? '');

    switch ($action_type) {
      case 'contact_form':
        $this->handle_contact_form();
        break;
      case 'newsletter':
        $this->handle_newsletter();
        break;
      default:
        wp_send_json_error(['message' => 'Unknown action']);
    }
  }

  private function handle_contact_form() {
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
      wp_send_json_error(['message' => 'All fields are required']);
    }

    if (!is_email($email)) {
      wp_send_json_error(['message' => 'Invalid email address']);
    }

    $subject = 'New Contact Form Submission';
    $body = "Name: {$name}\nEmail: {$email}\nMessage: {$message}";
    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    $sent = wp_mail(get_option('admin_email'), $subject, $body, $headers);

    if ($sent) {
      wp_send_json_success(['message' => 'Message sent successfully']);
    } else {
      wp_send_json_error(['message' => 'Failed to send message']);
    }
  }

  private function handle_newsletter() {
    $email = sanitize_email($_POST['email'] ?? '');

    if (empty($email) || !is_email($email)) {
      wp_send_json_error(['message' => 'Valid email is required']);
    }

    wp_send_json_success(['message' => 'Successfully subscribed to newsletter']);
  }

  public function load_more_posts() {
    if (!wp_verify_nonce($_POST['nonce'], 'theme_nonce')) {
      wp_die('Security check failed');
    }

    $page = intval($_POST['page'] ?? 1);
    $posts_per_page = intval($_POST['posts_per_page'] ?? get_option('posts_per_page'));

    $args = [
      'post_type' => 'post',
      'post_status' => 'publish',
      'posts_per_page' => $posts_per_page,
      'paged' => $page,
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
      ob_start();
      while ($query->have_posts()) {
        $query->the_post();
        get_template_part('template-parts/content', 'card');
      }
      $content = ob_get_clean();
      wp_reset_postdata();

      wp_send_json_success([
        'content' => $content,
        'has_more' => $page < $query->max_num_pages,
      ]);
    } else {
      wp_send_json_error(['message' => 'No more posts']);
    }
  }
}
