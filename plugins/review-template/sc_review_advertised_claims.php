<?php
/**
 * Shortcode: [review_advertised_claims]
 * Description: Retrieves and displays the advertised claims associated with a review post.
 *              Each claim is hyperlinked to its archive page and separated by a comma.
 */

function shortcode_review_advertised_claims() {
    if (!is_singular('reviews') && !is_admin()) {
        return '';
    }

    // Get the taxonomy terms associated with the post
    $claims = get_the_terms(get_the_ID(), 'claim');

    if ($claims && !is_wp_error($claims)) {
        $output = [];

        foreach ($claims as $claim) {
            $term_name = esc_html($claim->name);
            $term_link = esc_url(get_term_link($claim));

            // Build the linked term name
            $output[] = '<a href="' . $term_link . '">' . $term_name . '</a>';
        }

        // Return the terms as a comma-separated list on one line
        return implode(', ', $output);
    }

    return '';
}
add_shortcode('review_advertised_claims', 'shortcode_review_advertised_claims');
?>
