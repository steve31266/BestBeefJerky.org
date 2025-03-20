<?php
/**
 * Plugin Name: Review Template
 * Description: Adds shortcodes related to the Reviews custom post type.
 * Version: 1.1
 * Author: Choctaw Websites
 */

defined('ABSPATH') || exit;

// Include shortcodes
include_once plugin_dir_path(__FILE__) . 'sc_review_brand_name.php';
include_once plugin_dir_path(__FILE__) . 'sc_review_establishment_number.php';
include_once plugin_dir_path(__FILE__) . 'sc_review_meat_type.php';
include_once plugin_dir_path(__FILE__) . 'sc_review_variety.php';
include_once plugin_dir_path(__FILE__) . 'sc_review_ingredients.php';
include_once plugin_dir_path(__FILE__) . 'sc_review_allergens.php';
include_once plugin_dir_path(__FILE__) . 'sc_review_advertised_claims.php';
include_once plugin_dir_path(__FILE__) . 'sc_review_snack_form.php';
include_once plugin_dir_path(__FILE__) . 'review_rating.php';
include_once plugin_dir_path(__FILE__) . 'heat_level.php';

/**
 * Add a Help link to the plugin settings page
 */
function review_template_add_help_link($links) {
    $help_url = get_site_url() . '/wp-content/plugins/review-template/help.html';
    $help_link = '<a href="' . esc_url($help_url) . '" target="_blank">Help</a>';
    array_push($links, $help_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'review_template_add_help_link');
?>
