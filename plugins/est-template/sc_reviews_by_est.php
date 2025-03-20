<?php
/**
 * Shortcode: [reviews_by_est]
 * Description: Displays reviews related to the current "est" post.
 */

defined('ABSPATH') || exit;

function reviews_by_est_shortcode() {
    if (!is_singular('est')) {
        return ''; // Only execute on single "est" pages
    }

    global $post;
    $est_id = $post->ID;

    // Query "reviews" posts where "reviews_establishment_number" matches the current "est" post
    $args = [
        'post_type'      => 'reviews',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'reviews_establishment_number',
                'value'   => '"' . $est_id . '"', // ACF stores relationship fields as serialized arrays
                'compare' => 'LIKE'
            ]
        ]
    ];
    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>No reviews available for this establishment.</p>';
    }

    // Output wrapper
    $output = '<div class="reviews-grid">';

    while ($query->have_posts()) {
        $query->the_post();

        $post_id = get_the_ID();
        $post_title = get_the_title();
        $featured_image = get_the_post_thumbnail($post_id, 'medium');

        // Fallback if no featured image is set
        if (!$featured_image) {
            $featured_image = '<img src="' . esc_url(get_template_directory_uri() . '/assets/placeholder.webp') . '" alt="Placeholder">';
        }

        $output .= '<div class="review-item">';
        $output .= '<a href="' . get_permalink() . '">';
        $output .= $featured_image;
        $output .= '<p>' . esc_html($post_title) . '</p>';
        $output .= '</a>';
        $output .= '</div>';
    }

    $output .= '</div>'; // Close grid container
    wp_reset_postdata();

    return $output;
}
add_shortcode('reviews_by_est', 'reviews_by_est_shortcode');
?>
