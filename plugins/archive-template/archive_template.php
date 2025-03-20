<?php
/**
 * Plugin Name: Archive Template
 * Description: Custom taxonomy archive template for the "reviews" post type.
 * Version: 1.0
 * Author: Choctaw Websites
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the query loop file
include_once plugin_dir_path(__FILE__) . 'includes/query-loop.php';

// Enqueue the CSS file
function archive_template_enqueue_styles() {
    wp_enqueue_style(
        'archive-template-css',
        plugin_dir_url(__FILE__) . 'css/archive-style.css',
        [],
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'archive_template_enqueue_styles');

// Add "Help" link to the plugin listing page
function archive_template_plugin_links($links) {
    $help_link = '<a href="' . plugin_dir_url(__FILE__) . 'help.html" target="_blank">Help</a>';
    array_push($links, $help_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'archive_template_plugin_links');

// Add rewrite rules for the reviews archive
function archive_template_add_rewrite_rules() {
    add_rewrite_rule(
        'reviews/?$',
        'index.php?reviews_archive=true',
        'top'
    );
    add_rewrite_rule(
        'reviews/page/([0-9]+)/?$',
        'index.php?reviews_archive=true&paged=$matches[1]',
        'top'
    );
}
add_action('init', 'archive_template_add_rewrite_rules');

// Redirect page_id to clean URL
function archive_template_redirect() {
    if (isset($_GET['page_id']) && $_GET['page_id'] == '2311') {
        wp_redirect(home_url('/reviews/'), 301);
        exit;
    }
}
add_action('template_redirect', 'archive_template_redirect');

// Add custom query vars
function archive_template_query_vars($vars) {
    $vars[] = 'reviews_archive';
    return $vars;
}
add_filter('query_vars', 'archive_template_query_vars');

// Load custom template for reviews archive
function archive_template_template_include($template) {
    if (get_query_var('reviews_archive')) {
        return plugin_dir_path(__FILE__) . 'templates/archive-reviews.php';
    }
    return $template;
}
add_filter('template_include', 'archive_template_template_include');

// Flush rewrite rules on plugin activation
function archive_template_activate() {
    archive_template_add_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'archive_template_activate');

// Flush rewrite rules on plugin deactivation
function archive_template_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'archive_template_deactivate');
