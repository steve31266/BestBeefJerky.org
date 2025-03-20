<?php
/**
 * Shortcode: [review_allergens]
 * Description: Retrieves and displays the allergen term(s) associated with a review post.
 *              Each allergen is hyperlinked to its archive page and separated by a comma.
 */

function shortcode_review_allergens() {
    if (!is_singular('reviews') && !is_admin()) {
        return '';
    }

    // Get the taxonomy terms associated with the post
    $allergens = get_the_terms(get_the_ID(), 'allergen');

    if ($allergens && !is_wp_error($allergens)) {
        $output = [];

        foreach ($allergens as $allergen) {
            $term_name = esc_html($allergen->name);
            $term_link = esc_url(get_term_link($allergen));

            // Build the linked term name
            $output[] = '<a href="' . $term_link . '">' . $term_name . '</a>';
        }

        // Return the terms as a comma-separated list on one line
        return implode(', ', $output);
    }

    return '';
}
add_shortcode('review_allergens', 'shortcode_review_allergens');
?>
