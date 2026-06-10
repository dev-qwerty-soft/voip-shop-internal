<?php

class Custom_Post_Type {
  private $post_type;
  private $singular_name;
  private $plural_name;
  private $args;

  public function __construct($post_type, $singular_name, $plural_name, $args = [], $has_archive = true) {
    $this->post_type = $post_type;
    $this->singular_name = $singular_name;
    $this->plural_name = $plural_name;
    $this->args = $args;
    $this->has_archive = $has_archive;

    add_action('init', [$this, 'register_post_type']);
  }

  public function register_post_type() {
    $labels = [
      'name' => $this->plural_name,
      'singular_name' => $this->singular_name,
      'menu_name' => $this->plural_name,
      'name_admin_bar' => $this->singular_name,
      'add_new' => 'Add ' . $this->singular_name,
      'add_new_item' => 'Add new ' . $this->singular_name,
      'edit_item' => 'Edit ' . $this->singular_name,
      'new_item' => 'New ' . $this->singular_name,
      'view_item' => 'View ' . $this->singular_name,
      'search_items' => 'Search ' . $this->plural_name,
      'not_found' => 'Not found',
      'not_found_in_trash' => 'Not found in trash',
      'all_items' => 'All ' . $this->plural_name,
      'archives' => 'Archives ' . $this->singular_name,
      'insert_into_item' => 'Insert into ' . $this->singular_name,
      'uploaded_to_this_item' => 'Uploaded to this ' . $this->singular_name,
      'filter_items_list' => 'Filter list of ' . $this->plural_name,
      'items_list_navigation' => 'List navigation of ' . $this->plural_name,
      'items_list' => 'List of ' . $this->plural_name,
    ];

    $default_args = [
      'label' => $this->plural_name,
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'show_in_rest' => true,
      'query_var' => true,
      'rewrite' => ['slug' => $this->post_type],
      'capability_type' => 'post',
      'has_archive' => $this->has_archive,
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt'],
    ];

    $args = array_merge($default_args, $this->args);
    register_post_type($this->post_type, $args);
  }
}

// new Custom_Post_Type(
//   'resource',
//   'Local resource',
//   'Local resources',
//   [
//     'menu_icon' => 'dashicons-format-aside',
//     'supports' => ['title', 'author', 'editor'],
//   ],
//   false
// );
