<?php
/**
 * Plugin Name: SEO Scripts
 * Description: Custom SEO enhancements for Jerky Reviews.
 * Version: 1.0
 * Author: Choctaw Websites
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Include the taxonomy archive meta modifications
include_once plugin_dir_path(__FILE__) . 'inc/taxonomy_archive_meta.php';

// Add "Help" link on the Plugins page
function seo_scripts_plugin_links($links) {
    $help_link = '<a href="' . esc_url(plugin_dir_url(__FILE__) . 'help.html') . '" target="_blank">Help</a>';
    array_push($links, $help_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'seo_scripts_plugin_links');
