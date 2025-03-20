<?php
/**
 * Plugin Name: Brands Template
 * Description: Adds a shortcode [reviews_by_brand] to display reviews for each brand.
 * Version: 1.0
 * Author: Choctaw Websites
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Include the shortcode script
include_once plugin_dir_path(__FILE__) . 'sc_reviews_by_brand.php';

// Enqueue the CSS file
function enqueue_reviews_by_brand_styles() {
    wp_enqueue_style('reviews-by-brand-css', plugin_dir_url(__FILE__) . 'reviews_by_brand.css', [], '1.0');
}
add_action('wp_enqueue_scripts', 'enqueue_reviews_by_brand_styles');

// Add Help Link Under the Plugin Name Column
function brands_template_plugin_action_links($links) {
    $help_link = '<a href="' . esc_url(plugin_dir_url(__FILE__) . 'help.html') . '" target="_blank">Help</a>';
    array_push($links, $help_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'brands_template_plugin_action_links');
?>
