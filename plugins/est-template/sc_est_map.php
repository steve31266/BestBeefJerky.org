<?php
/**
 * Shortcode: [est_map]
 * Description: Displays a Google Map based on the 'est_geolocation' ACF field.
 */

defined('ABSPATH') || exit;

function est_map_shortcode() {
    if (!is_singular('est')) {
        return ''; // Only run on 'est' post type
    }

    // Get the geolocation field
    $location = get_field('est_geolocation');
    if (!$location) {
        return '<p>No location available.</p>';
    }

    return '<div id="est-map" data-location="' . esc_attr($location) . '"></div>';
}
add_shortcode('est_map', 'est_map_shortcode');
?>
