<?php
/**
 * Plugin Name: Fixed Header
 * Description: Keeps the full-sized header fixed at the top of the page while scrolling.
 * Version: 1.1
 * Author: Choctaw Websites
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue JavaScript and CSS files
function fixed_header_enqueue_assets() {
    // Enqueue JavaScript file
    wp_enqueue_script(
        'fixed-header-js',
        plugin_dir_url(__FILE__) . 'fixed-header.js',
        array(),
        null,
        true
    );

    // Enqueue CSS file
    wp_enqueue_style(
        'fixed-header-css',
        plugin_dir_url(__FILE__) . 'fixed-header.css',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'fixed_header_enqueue_assets');

/**
 * Add a Help link to the plugin settings page
 */
function fixed_header_add_help_link($links) {
    $help_url = get_site_url() . '/wp-content/plugins/fixed-header/help.html'; // Update with actual help file URL
    $help_link = '<a href="' . esc_url($help_url) . '" target="_blank">Help</a>';
    array_push($links, $help_link);
    return $links;
}

// Hook the Help link into the plugin settings page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'fixed_header_add_help_link');
