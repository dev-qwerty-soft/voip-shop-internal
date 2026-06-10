<?php

class ACFSettings {
  public function __construct() {
    if (!function_exists('get_field')) {
      return;
    }

    add_filter('acf/settings/save_json', [$this, 'save_json_point']);
    add_filter('acf/settings/load_json', [$this, 'load_json_point']);
  }

  public function save_json_point($path) {
    return get_stylesheet_directory() . '/acf-json';
  }

  public function load_json_point($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
  }
}
