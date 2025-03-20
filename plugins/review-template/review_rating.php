<?php
/**
 * Shortcode: [jerky_rating]
 * Description: Displays the review rating with an image and hyperlink to taxonomy archive.
 * Version: 2.1
 * Author: Choctaw Websites
 */

defined('ABSPATH') || exit; // Prevent direct access

// Enqueue CSS file for review rating
function review_rating_enqueue_styles() {
    if (!is_singular('reviews')) {
        return; // Ensure styles only load on review posts
    }
    wp_enqueue_style(
        'review_rating_css',
        plugin_dir_url(__FILE__) . 'review_rating.css',
        [],
        '2.1'
    );
}
add_action('wp_enqueue_scripts', 'review_rating_enqueue_styles');

// Shortcode function
function display_jerky_rating() {
    if (!is_singular('reviews')) {
        return ''; // Ensure it only appears on "reviews" post type
    }

    // Get the Term ID stored in 'reviews_rating' field
    $term_id = get_field('reviews_rating'); 
    if (!$term_id) {
        return ''; // Exit if no rating is set
    }

    // Get the term object from the 'rating' taxonomy
    $term = get_term($term_id, 'rating');
    if (!$term || is_wp_error($term)) {
        return ''; // Exit if term is invalid
    }

    // Get the taxonomy name (term name)
    $rating_name = $term->name;

    // Get the taxonomy archive URL
    $rating_link = get_term_link($term);
    if (is_wp_error($rating_link)) {
        $rating_link = ''; // Ensure no broken links
    }

    // Get the 'rating_image' field from the taxonomy
    $rating_image = get_field('rating_image', 'rating_' . $term_id);
    if (!$rating_image) {
        return ''; // Exit if no image is set
    }

    // Output HTML
    ob_start();
    ?>
    <div class="jerky-rating">
        <span class="rating-label">Rating:</span>
        <img class="rating-image" src="<?php echo esc_url($rating_image); ?>" alt="<?php echo esc_attr($rating_name); ?>">
        <a class="rating-text" href="<?php echo esc_url($rating_link); ?>"><?php echo esc_html($rating_name); ?></a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('jerky_rating', 'display_jerky_rating');
?>
