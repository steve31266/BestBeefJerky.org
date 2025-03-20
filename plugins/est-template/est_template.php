<?php
/**
 * Plugin Name: EST Template
 * Description: Adds custom functionality for the "est" custom post type, including Google Maps, title modification, and reviews display.
 * Version: 1.7
 * Author: Choctaw Websites
 */

defined('ABSPATH') || exit;

// Include the shortcode and title modification scripts
include_once plugin_dir_path(__FILE__) . 'sc_est_map.php';
include_once plugin_dir_path(__FILE__) . 'modify_est_title.php';
include_once plugin_dir_path(__FILE__) . 'sc_reviews_by_est.php';

// Enqueue JavaScript and CSS
function est_template_enqueue_assets() {
    if (is_singular('est')) { // Only load on "est" post type
        wp_enqueue_style('est-map-css', plugin_dir_url(__FILE__) . 'css/est_map.css', [], '1.0');
        wp_enqueue_style('reviews-by-est-css', plugin_dir_url(__FILE__) . 'css/reviews_by_est.css', [], '1.0');

        // Load Google Maps API
        wp_enqueue_script(
            'google-maps',
            'https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_KEY,
            [],
            null,
            true
        );
        wp_enqueue_script('est-map-js', plugin_dir_url(__FILE__) . 'js/est_map.js', ['google-maps'], '1.3', true);

        // Localize script to pass Snazzy Map JSON URL
        wp_localize_script('est-map-js', 'estMapStyles', [
            'url' => plugin_dir_url(__FILE__) . 'json/snazzy_map.json'
        ]);
    }
}
add_action('wp_enqueue_scripts', 'est_template_enqueue_assets');

// Add Help link under Plugin Name column in Plugin Admin page
function est_template_add_help_link($links) {
    $help_url = plugin_dir_url(__FILE__) . 'help.html';
    $links[] = '<a href="' . esc_url($help_url) . '" target="_blank">Help</a>';
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'est_template_add_help_link');
?>
