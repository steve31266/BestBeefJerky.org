<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

// Register the shortcode
function reviews_by_brand_shortcode() {
    if (!is_singular('brands')) {
        return ''; // Only execute on single "brands" pages
    }

    global $post;
    $brand_id = $post->ID;

    // Query "reviews" posts where "reviews_brand" matches the current brand
    $args = [
        'post_type'      => 'reviews',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'reviews_brand',
                'value'   => '"' . $brand_id . '"', // ACF stores relationship fields as serialized arrays
                'compare' => 'LIKE'
            ]
        ]
    ];
    $query = new WP_Query($args);

    ob_start();

    if (!$query->have_posts()) {
        echo '<p>No reviews available for this brand.</p>';
        return ob_get_clean();
    }

    // Display total count
    echo '<div class="taxonomy-description">';
    echo '<strong>Number of Reviews Found:</strong> ' . $query->found_posts;
    echo '</div>';

    // Display reviews listing
    echo '<div class="taxonomy-archive-posts">';
    while ($query->have_posts()) {
        $query->the_post();
        
        // Get rating image if it exists
        $rating_id = get_field('reviews_rating');
        $rating_image = '';
        if ($rating_id) {
            $rating_image = get_field('rating_image', 'rating_' . $rating_id);
        }
        ?>
        <div class="archive-post">
            <div class="archive-post-image">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php the_title_attribute(); ?>">
                    </a>
                <?php else : ?>
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/placeholder.webp'); ?>" alt="Placeholder Image">
                    </a>
                <?php endif; ?>
            </div>
            <div class="archive-post-content">
                <p class="archive-post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </p>

                <?php if ($rating_image) : ?>
                    <div class="rating-on-archive">
                        <img src="<?php echo esc_url($rating_image); ?>" alt="Review Rating">
                    </div>
                <?php endif; ?>

                <div class="excerpt">
                    <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?>
                </div>
            </div>
        </div>
        <?php
    }
    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('reviews_by_brand', 'reviews_by_brand_shortcode');
?>
