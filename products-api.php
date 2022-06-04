<?php
/*
Plugin Name: Products API
Plugin URI: https://github.com/Subas-roy/products-api
Description: Make powerfull & secure endpoint for your products and get access all of your products simultaneously.
Version: 1.0.0
Author: Subas Roy
Author URI: https://github.com/Subas-roy/
License: GPLv2 or later
Text Domain: productsapi
*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing? You silly human!.' );

  add_action('admin_init', 'api_settings');

  function api_settings() {
    // settings section
    add_settings_section('add_settings_sec', 'API Form','settings_sec_text','api_options');
    function settings_sec_text() {
      echo 'There is an example api for posts <code>http://yourdomain/wp-json/wp/v2/posts</code>';
    }

    // namespaces
    register_setting('add_settings_sec','namespace-text');
    add_settings_field('namespace-text','Namespaces','namespace_form','api_options','add_settings_sec');
    // endpoint
    register_setting('add_settings_sec','endpoint-text');
    add_settings_field('endpoint-text','Endpoint','endpoint_form','api_options','add_settings_sec');
  }

  function namespace_form() {
    echo '<input type="text" name="namespace-text" class="regular-text" value="'.get_option('namespace-text').'" />';
    echo '<p class="description" id="tagline-description">example: /wp/v1</p>';
  }

  function endpoint_form() {
    echo '<input type="text" name="endpoint-text" class="regular-text" value="'.get_option('endpoint-text').'" />';
    echo '<p class="description" id="tagline-description">example: /products</p>';
  }

  add_action('admin_menu', 'add_menu_option');
  function add_menu_option() {
    add_menu_page('Products API','Products API','manage_options','api_options','api_options_format');
  }

  function api_options_format() { ?>
    <h1>Product API Settings</h1>
    <form action="options.php" method="POST">
      <?php do_settings_sections('api_options'); ?>
      <?php settings_fields('add_settings_sec'); ?>
      <?php submit_button(); ?>
    </form>
  <?php }

  add_action('rest_api_init', 'productsJson');
  function productsJson() {
    $namespace = get_option('namespace-text');
    $endpoint = get_option('endpoint-text');
    register_rest_route($namespace, $endpoint, array(
      'methods' => 'GET',
      'callback' => 'get_products',
    ));
  }

  // Get recent products
  function get_products( $params ) {

    // gets the global $wpdb variable
    global $wpdb;
    header('Content-Type:application/json');
    header('dataType:json');
    $results = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_type = 'product'");
    return $results;

  }

  

