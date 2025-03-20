<?php
/**
 * Shortcode: [review_meat_type]
 * Description: Retrieves and displays the meat type(s) associated with a review post.
 *              Each meat type is hyperlinked to its archive page and separated by a comma.
 */

function shortcode_review_meat_type() {
    if (!is_singular('reviews') && !is_admin()) {
        return '';
    }

    // Get the taxonomy terms associated with the post
    $meat_types = get_the_terms(get_the_ID(), 'meat-type');

    if ($meat_types && !is_wp_error($meat_types)) {
        $output = [];

        foreach ($meat_types as $meat_type) {
            $term_name = esc_html($meat_type->name);
            $term_link = esc_url(get_term_link($meat_type));

            // Build the linked term name
            $output[] = '<a href="' . $term_link . '">' . $term_name . '</a>';
        }

        // Return the terms as a comma-separated list
        return implode(', ', $output);
    }

    return '';
}
add_shortcode('review_meat_type', 'shortcode_review_meat_type');
?>
