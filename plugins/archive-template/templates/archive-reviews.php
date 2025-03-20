<?php
/**
 * Template for displaying the reviews archive
 */

get_header();
?>

<div class="gb-element-3c7bfe2b">
    <div class="site-padding gb-element-1723acaf">
        <div class="gb-element-c6738244">
            <div class="body-padding">
                <?php
                // Get filter values from URL
                $filters = get_active_filters();

                // Build the query
                $query_filters = build_meta_query($filters);

                // Get the current page number
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;

                $args = [
                    'post_type'      => 'reviews',
                    'posts_per_page' => 10,
                    'paged'          => $paged,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ];

                if (!empty($query_filters['meta_query'])) {
                    $args['meta_query'] = $query_filters['meta_query'];
                }
                if (!empty($query_filters['tax_query'])) {
                    $args['tax_query'] = $query_filters['tax_query'];
                }

                // Create custom query
                $custom_query = new WP_Query($args);

                // Display breadcrumbs
                display_breadcrumbs(['type' => 'all']);

                // Display archive title and info
                display_archive_info(['type' => 'all'], $custom_query);

                // Display filter form
                display_filter_form();

                // Display post loop
                if ($custom_query->have_posts()) {
                    echo '<div class="taxonomy-archive-posts">';
                    while ($custom_query->have_posts()) {
                        $custom_query->the_post();
                        ?>
                        <div class="archive-post">
                            <div class="archive-post-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php else : ?>
                                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/placeholder.webp'); ?>" alt="Placeholder Image">
                                <?php endif; ?>
                            </div>

                            <div class="archive-post-content">
                                <p class="archive-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>

                                <?php
                                $rating_id = get_field('reviews_rating', get_the_ID());
                                if ($rating_id) {
                                    $rating_image = get_field('rating_image', 'rating_' . $rating_id);
                                    if ($rating_image) {
                                        echo '<div class="rating-on-archive">';
                                        echo '<img src="' . esc_url($rating_image) . '" alt="Review Rating">';
                                        echo '</div>';
                                    }
                                }
                                ?>

                                <div class="excerpt"><?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?></div>
                            </div>
                        </div>
                        <?php
                    }
                    echo '</div>';

                    // Add pagination
                    echo '<div class="archive-pagination">';
                    echo paginate_links(array(
                        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        'format' => '',
                        'current' => $paged,
                        'total' => $custom_query->max_num_pages,
                        'prev_text' => '&laquo; Previous',
                        'next_text' => 'Next &raquo;',
                        'type' => 'list'
                    ));
                    echo '</div>';
                } else {
                    echo '<p>No reviews found.</p>';
                }

                wp_reset_postdata();
                ?>
            </div>
            <div class="sidebar"></div>
        </div>
    </div>
</div>

<?php
get_footer(); 