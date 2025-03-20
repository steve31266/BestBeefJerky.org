<?php
/**
 * Plugin Name: 301 Redirects
 * Plugin URI: https://www.bestbeefjerky.org/
 * Description: Redirects old imported Blogger URLs to their new WordPress structure.
 * Version: 1.0
 * Author: Choctaw Websites
 * Author URI: https://www.bestbeefjerky.org/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function ob_redirect_old_urls() {
    // Get the requested URL path
    $requested_url = $_SERVER['REQUEST_URI'];

    // Load the JSON file with URL mappings
    $redirects_file = plugin_dir_path(__FILE__) . 'redirects.json';
    if (!file_exists($redirects_file)) {
        return;
    }
    
    $redirects = json_decode(file_get_contents($redirects_file), true);
    
    if (!is_array($redirects)) {
        return;
    }

    // Check if the requested URL matches any old URLs
    if (array_key_exists($requested_url, $redirects)) {
        wp_redirect($redirects[$requested_url], 301);
        exit;
    }
}
add_action('template_redirect', 'ob_redirect_old_urls');

// Add "Help" link on the Plugins page
function ob_redirects_plugin_links($links) {
    $help_link = '<a href="' . esc_url(plugin_dir_url(__FILE__) . 'help.html') . '" target="_blank">Help</a>';
    array_push($links, $help_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ob_redirects_plugin_links');