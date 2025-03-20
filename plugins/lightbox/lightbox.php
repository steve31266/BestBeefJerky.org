<?php
/**
 * Plugin Name: Lightbox2 Integration
 * Description: Enables Lightbox2 for all images.
 * Version: 1.0
 * Author: Choctaw Websites
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Enqueue Lightbox2 scripts and styles
function enqueue_lightbox2() {
    wp_enqueue_style('lightbox-css', plugin_dir_url(__FILE__) . 'lightbox.css');
    wp_enqueue_script('lightbox-js', plugin_dir_url(__FILE__) . 'lightbox-plus-jquery.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_lightbox2');

// Add "Help" link in the Plugins page under the plugin name
function lightbox2_plugin_help_link($links, $file) {
    if ($file === plugin_basename(__FILE__)) {
        $help_link = '<a href="' . plugin_dir_url(__FILE__) . 'help.html" target="_blank">Help</a>';
        array_push($links, $help_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'lightbox2_plugin_help_link', 10, 2);
