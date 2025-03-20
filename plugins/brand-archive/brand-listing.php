<?php
/**
 * Shortcode: [brand_listing]
 * Displays a list of all posts from the "brands" custom post type in a single-column format.
 * Now sorted in ascending order by Brand Name.
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Register shortcode
add_shortcode('brand_listing', 'brand_listing_shortcode');

function brand_listing_shortcode() {
    ob_start();

    $args = [
        'post_type'      => 'brands',
        'posts_per_page' => -1, // Display all brands
        'orderby'        => 'title', // Order by Brand Name
        'order'          => 'ASC',   // Ascending Order
    ];

    $custom_query = new WP_Query($args);

    if ($custom_query->have_posts()) {
        echo '<div class="brand-listing">';
        while ($custom_query->have_posts()) {
            $custom_query->the_post();
            
            // Store the current post ID
            $brand_id = get_the_ID();

            // Get ACF image field
            $brand_logo = get_field('brands_logo'); // Returns Image URL
            
            // Fallback image
            if (!$brand_logo) {
                $brand_logo = esc_url(plugin_dir_url(__FILE__) . 'images/placeholder.webp');
            }

            // Get ACF text field for headquarters
            $brand_headquartered = get_field('brands_headquartered');

            // Get the number of reviews using get_posts() (does not modify global $post)
            $reviews = get_posts([
                'post_type'  => 'reviews',
                'numberposts' => -1,
                'meta_query' => [[
                    'key'     => 'reviews_brand',
                    'value'   => '"' . $brand_id . '"',
                    'compare' => 'LIKE',
                ]],
            ]);
            $review_count = count($reviews);
            ?>
            <div class="brand-item">
                <div class="brand-image">
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo esc_url($brand_logo); ?>" alt="<?php the_title_attribute(); ?>">
                    </a>
                </div>
                <div class="brand-content">
                    <p class="brand-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                    <?php if ($brand_headquartered) : ?>
                        <p class="brand-headquartered"><?php echo esc_html($brand_headquartered); ?></p>
                    <?php endif; ?>
                    <p class="brand-reviews-count">Number of Reviews: <?php echo esc_html($review_count); ?></p>
                </div>
            </div>
            <?php
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>No brands found.</p>';
    }

    return ob_get_clean();
}
?>
