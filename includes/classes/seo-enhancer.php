<?php

if (!defined('ABSPATH')) {
  exit();
}

class SEOEnhancer {
  public function __construct() {
    add_action('wp_head', [$this, 'add_open_graph_tags']);
    add_action('wp_head', [$this, 'add_schema_markup']);
    add_action('wp_head', [$this, 'add_twitter_cards']);
  }

  public function add_open_graph_tags() {
    if (is_singular()) {
      global $post;
      $title = get_the_title();
      $description = wp_trim_words(get_the_excerpt() ?: $post->post_content, 20);
      $image = get_the_post_thumbnail_url($post->ID, 'large');
      $url = get_permalink();

      echo "<meta property='og:title' content='" . esc_attr($title) . "'>\n";
      echo "<meta property='og:description' content='" . esc_attr($description) . "'>\n";
      echo "<meta property='og:url' content='" . esc_url($url) . "'>\n";
      echo "<meta property='og:type' content='article'>\n";

      if ($image) {
        echo "<meta property='og:image' content='" . esc_url($image) . "'>\n";
      }
    }
  }

  public function add_schema_markup() {
    if (is_singular('post')) {
      $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'author' => [
          '@type' => 'Person',
          'name' => get_the_author(),
        ],
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
      ];

      echo "<script type='application/ld+json'>" . json_encode($schema) . "</script>\n";
    }
  }

  public function add_twitter_cards() {
    echo "<meta name='twitter:card' content='summary_large_image'>\n";
    if (is_singular()) {
      echo "<meta name='twitter:title' content='" . esc_attr(get_the_title()) . "'>\n";
    }
  }
}
