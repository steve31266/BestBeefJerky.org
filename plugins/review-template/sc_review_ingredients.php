<?php
/**
 * Shortcode: [review_ingredients]
 * Description: Retrieves and displays the ingredient term(s) associated with a review post.
 *              Each ingredient is hyperlinked to its archive page and separated by a comma.
 */

function shortcode_review_ingredients() {
    if (!is_singular('reviews') && !is_admin()) {
        return '';
    }

    // Get the taxonomy terms associated with the post
    $ingredients = get_the_terms(get_the_ID(), 'ingredient');

    if ($ingredients && !is_wp_error($ingredients)) {
        $output = [];

        foreach ($ingredients as $ingredient) {
            $term_name = esc_html($ingredient->name);
            $term_link = esc_url(get_term_link($ingredient));

            // Build the linked term name
            $output[] = '<a href="' . $term_link . '">' . $term_name . '</a>';
        }

        // Return the terms as a comma-separated list on one line
        return implode(', ', $output);
    }

    return '';
}
add_shortcode('review_ingredients', 'shortcode_review_ingredients');
?>
