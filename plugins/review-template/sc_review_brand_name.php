<?php
/**
 * Shortcode: [review_brand_name]
 * Description: Retrieves and displays the brand name associated with a review post, with a hyperlink to the brand post.
 */

function shortcode_review_brand_name() {
    if (!is_singular('reviews') && !is_admin()) {
        return '';
    }

    // Get the relationship field (Post Object)
    $brand = get_field('reviews_brand');

    // Check if the field is an array (multiple values) or a single post object
    if ($brand) {
        if (is_array($brand)) {
            // If multiple brands are related, get the first one
            $brand = reset($brand);
        }

        if (is_object($brand)) {
            // Get brand post title and permalink
            $brand_title = esc_html($brand->post_title);
            $brand_url = esc_url(get_permalink($brand->ID));

            return '<a href="' . $brand_url . '">' . $brand_title . '</a>';
        }
    }

    return '';
}
add_shortcode('review_brand_name', 'shortcode_review_brand_name');
?>
