<?php
/**
 * Plugin Name: Brand Archive Page
 * Description: Adds a shortcode [brand_listing] to display all brands in a single-column list.
 * Version: 1.0
 * Author: Choctaw Websites
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Include the shortcode script
include_once plugin_dir_path(__FILE__) . 'brand-listing.php';

// Enqueue the CSS file
function enqueue_brand_listing_styles() {
    wp_enqueue_style('brand-listing-css', plugin_dir_url(__FILE__) . 'brand-listing.css', [], '1.0');
}
add_action('wp_enqueue_scripts', 'enqueue_brand_listing_styles');

// Add Help Link Under the Plugin Name Column
function brand_archive_plugin_action_links($links) {
    $help_link = '<a href="' . esc_url(plugin_dir_url(__FILE__) . 'help.html') . '" target="_blank">Help</a>';
    array_push($links, $help_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'brand_archive_plugin_action_links');
?>
