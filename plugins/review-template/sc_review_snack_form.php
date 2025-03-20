<?php
/**
 * Shortcode: [review_snack_form]
 * Description: Retrieves and displays the snack form(s) associated with a review post.
 *              Each snack form is hyperlinked to its archive page and separated by a comma.
 */

function shortcode_review_snack_form() {
    if (!is_singular('reviews') && !is_admin()) {
        return '';
    }

    // Get the taxonomy terms associated with the post
    $snack_forms = get_the_terms(get_the_ID(), 'snack-form');

    if ($snack_forms && !is_wp_error($snack_forms)) {
        $output = [];

        foreach ($snack_forms as $snack_form) {
            $term_name = esc_html($snack_form->name);
            $term_link = esc_url(get_term_link($snack_form));

            // Build the linked term name
            $output[] = '<a href="' . $term_link . '">' . $term_name . '</a>';
        }

        // Return the terms as a comma-separated list on one line
        return implode(', ', $output);
    }

    return '';
}
add_shortcode('review_snack_form', 'shortcode_review_snack_form');
?>
