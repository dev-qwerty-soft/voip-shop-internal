<?php

class Custom_Taxonomy {
  private $taxonomy;
  private $post_type;
  private $name;

  public function __construct($taxonomy, $post_type, $name) {
    $this->post_type = $post_type;
    $this->taxonomy = $taxonomy;
    $this->name = $name;
    add_action('init', [$this, 'register_taxonomy']);
  }

  public function register_taxonomy() {
    $labels = [
      'name' => _x($this->name . ' category', 'taxonomy general name'),
      'singular_name' => _x($this->name . ' category', 'taxonomy singular name'),
      'search_items' => __('Search ' . $this->name),
      'all_items' => __('All ' . $this->name . ' categories'),
      'parent_item' => __('Parent ' . strtolower($this->name) . ' category'),
      'parent_item_colon' => __('Parent ' . $this->name . ':'),
      'edit_item' => __('Edit ' . $this->name . ' category'),
      'update_item' => __('Update ' . $this->name . ' category'),
      'add_new_item' => __('Add new ' . strtolower($this->name) . ' category'),
      'new_item_name' => __('New ' . strtolower($this->name) . ' category name'),
      'menu_name' => __($this->name . ' category'),
    ];

    $args = [
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'query_var' => true,
      'rewrite' => ['slug' => $this->taxonomy],
    ];

    register_taxonomy($this->taxonomy, [$this->post_type], $args);
  }
}

// new Custom_Taxonomy('resource-category', 'resource', 'Local resource');
