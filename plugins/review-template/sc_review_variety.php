<?php
/**
 * Shortcode: [review_variety]
 * Description: Retrieves and displays the variety term(s) associated with a review post.
 *              Each variety term is hyperlinked to its archive page and separated by a comma.
 */

function shortcode_review_variety() {
    if (!is_singular('reviews') && !is_admin()) {
        return '';
    }

    // Get the taxonomy terms associated with the post
    $varieties = get_the_terms(get_the_ID(), 'variety');

    if ($varieties && !is_wp_error($varieties)) {
        $output = [];

        foreach ($varieties as $variety) {
            $term_name = esc_html($variety->name);
            $term_link = esc_url(get_term_link($variety));

            // Build the linked term name
            $output[] = '<a href="' . $term_link . '">' . $term_name . '</a>';
        }

        // Return the terms as a comma-separated list on one line
        return implode(', ', $output);
    }

    return '';
}
add_shortcode('review_variety', 'shortcode_review_variety');
?>
