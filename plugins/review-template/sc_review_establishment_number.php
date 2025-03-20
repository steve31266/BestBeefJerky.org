<?php
/**
 * Shortcode: [review_establishment_number]
 * Description: Retrieves and displays the establishment number linked to a review, formatted as:
 *              "<a href='link'>Est# Post Title</a>, (est_facility_name)"
 */

function shortcode_review_establishment_number() {
    if (!is_singular('reviews') && !is_admin()) {
        return '';
    }

    // Get the relationship field (Post Object)
    $establishment = get_field('reviews_establishment_number');

    // Check if the field is an array (multiple values) or a single post object
    if ($establishment) {
        if (is_array($establishment)) {
            // If multiple establishments are related, get the first one
            $establishment = reset($establishment);
        }

        if (is_object($establishment)) {
            // Get establishment post title and permalink
            $est_title = esc_html($establishment->post_title);
            $est_url = esc_url(get_permalink($establishment->ID));

            // Get the est_facility_name field from the "est" post type
            $facility_name = get_field('est_facility_name', $establishment->ID);
            $facility_name = !empty($facility_name) ? esc_html($facility_name) : '';

            // Format the output
            return '<a href="' . $est_url . '">Est# ' . $est_title . '</a>, (' . $facility_name . ')';
        }
    }

    return '';
}
add_shortcode('review_establishment_number', 'shortcode_review_establishment_number');
?>
