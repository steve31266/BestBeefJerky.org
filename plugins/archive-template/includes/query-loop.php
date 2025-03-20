<?php
/**
 * Custom Query Loop for Jerky Reviews - Supports multiple filters dynamically while retaining taxonomy archive filtering.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register shortcode
add_shortcode('jerky_review_loop', 'archive_template_query_loop');

function archive_template_query_loop($atts) {
    $atts = shortcode_atts([
        'type' => 'taxonomy',
    ], $atts, 'jerky_review_loop');

    ob_start();

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

    // Ensure Taxonomy Archive Filtering Works
    if (is_tax()) {
        $taxonomy = get_queried_object();
        if ($taxonomy) {
            $query_filters['tax_query'][] = [
                'taxonomy' => $taxonomy->taxonomy,
                'field'    => 'slug',
                'terms'    => $taxonomy->slug
            ];
        }
    }

    if (!empty($query_filters['meta_query'])) {
        $args['meta_query'] = $query_filters['meta_query'];
    }
    if (!empty($query_filters['tax_query'])) {
        $args['tax_query'] = $query_filters['tax_query'];
    }

    // Create custom query
    $custom_query = new WP_Query($args);

    // Display breadcrumbs
    display_breadcrumbs($atts);

    // Display archive title & description
    display_archive_info($atts, $custom_query);

    // Display filter form - prevent infinite loop by checking if we're not already in the shortcode
    if (!doing_filter('the_content')) {
        display_filter_form();
    }

    // Display post loop
    display_post_loop($custom_query);

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Get active filters from URL parameters.
 */
function get_active_filters() {
    return [
        'rating' => isset($_GET['rating_filter']) ? intval($_GET['rating_filter']) : 0,
        'carbs'  => isset($_GET['carbs_filter']) ? sanitize_text_field($_GET['carbs_filter']) : 'all',
        'meat'   => isset($_GET['meat_filter']) ? sanitize_text_field($_GET['meat_filter']) : 'all',
    ];
}

/**
 * Build WP_Query meta_query and tax_query from active filters.
 */
function build_meta_query($filters) {
    $meta_query = [];
    $tax_query  = [];

    // Detect if we are on a Taxonomy Archive
    if (is_tax()) {
        $taxonomy = get_queried_object();
        if ($taxonomy) {
            $tax_query[] = [
                'taxonomy' => $taxonomy->taxonomy,
                'field'    => 'slug',
                'terms'    => $taxonomy->slug
            ];
        }
    }

    // Rating filter (Meta Query)
    if ($filters['rating'] > 0) {
        $meta_query[] = [
            'key'     => 'reviews_rating',
            'value'   => $filters['rating'],
            'compare' => '='
        ];
    }

    // Carbs filter (Meta Query)
    if ($filters['carbs'] === 'low') {
        $meta_query[] = [
            'key'     => 'nutrition_total_carbs',
            'value'   => 3,
            'type'    => 'NUMERIC',
            'compare' => '<='
        ];
    } elseif ($filters['carbs'] === 'zero') {
        $meta_query[] = [
            'key'     => 'nutrition_total_carbs',
            'value'   => 0,
            'type'    => 'NUMERIC',
            'compare' => '='
        ];
    }

    // Meat filter (Tax Query)
    if ($filters['meat'] !== 'all') {
        $tax_query[] = [
            'taxonomy' => 'meat-type',
            'field'    => 'slug',
            'terms'    => $filters['meat']
        ];
    }

    return [
        'meta_query' => !empty($meta_query) ? array_merge(['relation' => 'AND'], $meta_query) : [],
        'tax_query'  => !empty($tax_query) ? array_merge(['relation' => 'AND'], $tax_query) : [],
    ];
}

/**
 * Display breadcrumb navigation.
 */
function display_breadcrumbs($atts) {
    // SVG icon for separator
    $separator_svg = '<svg class="breadcrumb-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="0.5em" height="0.5em">
        <path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path>
    </svg>';

    echo '<nav class="archive-breadcrumbs">';
    echo '<a href="' . esc_url(home_url('/')) . '">Home</a>';
    echo '<span class="breadcrumb-separator">' . $separator_svg . '</span>';

    if ($atts['type'] === 'all') {
        echo '<span class="breadcrumb-current">Jerky Reviews</span>';
    } else {
        echo '<a href="' . esc_url(home_url('/reviews/')) . '">Jerky Reviews</a>';
        $taxonomy = get_queried_object();
        $taxonomy_label = get_taxonomy($taxonomy->taxonomy)->labels->singular_name;
        $term_name = single_term_title('', false);
        echo '<span class="breadcrumb-separator">' . $separator_svg . '</span>';
        echo '<span class="breadcrumb-current">' . esc_html($taxonomy_label) . ': ' . esc_html($term_name) . '</span>';
    }
    echo '</nav>';
}

/**
 * Display archive title & dynamic filter description.
 */
function display_archive_info($atts, $custom_query) {
    // Display title and description for main reviews page
    if ($atts['type'] === 'all') {
        echo '<h1 class="archive-title">Jerky Reviews</h1>';
        
        // Get current filter values
        $rating_filter = isset($_GET['rating_filter']) ? $_GET['rating_filter'] : '0';
        $carbs_filter = isset($_GET['carbs_filter']) ? $_GET['carbs_filter'] : 'all';
        $meat_filter = isset($_GET['meat_filter']) ? $_GET['meat_filter'] : 'all';
        
        // Get rating label
        $rating_labels = [
            '0' => 'All',
            '754' => 'Best',
            '755' => 'Good',
            '756' => 'Average',
            '757' => 'Fair',
            '758' => 'Dog Treats'
        ];
        $rating_label = isset($rating_labels[$rating_filter]) ? $rating_labels[$rating_filter] : 'All';

        // Get carbs label
        $carbs_labels = [
            'all' => 'All',
            'low' => 'Low',
            'zero' => 'Zero'
        ];
        $carbs_label = isset($carbs_labels[$carbs_filter]) ? $carbs_labels[$carbs_filter] : 'All';

        // Get meat type label
        $meat_label = 'All';
        if ($meat_filter !== 'all') {
            $term = get_term_by('slug', $meat_filter, 'meat-type');
            if ($term) {
                $meat_label = $term->name;
            }
        }
        
        // Display filter info and post count in the same div
        echo '<div class="taxonomy-description">';
        echo '<strong>Filtered by:</strong> Rating: ' . esc_html($rating_label) . 
             ', Carbs: ' . esc_html($carbs_label) . 
             ', Meat Type: ' . esc_html($meat_label) . '<br>';
        echo '<strong>Number of Reviews Found:</strong> ' . $custom_query->found_posts;
        echo '</div>';
    } else {
        // Get the current term and taxonomy info
        $term = get_queried_object();
        $taxonomy_label = get_taxonomy($term->taxonomy)->labels->singular_name;
        echo '<h1 class="archive-title">' . esc_html($taxonomy_label) . ': ' . esc_html($term->name) . '</h1>';
        
        // Get and display the taxonomy description if it exists
        $description = $term->description;
        if (!empty($description)) {
            echo '<div class="taxonomy-description">' . wp_kses_post($description) . '</div>';
        }

        // Display post count for taxonomy archives
        echo '<div class="post-count">';
        echo '<strong>Number of Reviews Found:</strong> ' . $custom_query->found_posts;
        echo '</div>';
    }
}

/**
 * Display post loop.
 */
function display_post_loop($custom_query) {
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
        
        if (is_tax()) {
            // For taxonomy archives
            echo paginate_links(array(
                'current' => max(1, get_query_var('paged')),
                'total' => $custom_query->max_num_pages,
                'prev_text' => '&laquo; Previous',
                'next_text' => 'Next &raquo;',
                'type' => 'list'
            ));
        } else {
            // For the reviews page
            $reviews_page = get_page_by_path('reviews');
            if ($reviews_page) {
                $base = trailingslashit(get_permalink($reviews_page->ID)) . 'page/%#%/';
                
                echo paginate_links(array(
                    'base' => $base,
                    'format' => '',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $custom_query->max_num_pages,
                    'prev_text' => '&laquo; Previous',
                    'next_text' => 'Next &raquo;',
                    'type' => 'list',
                    'add_args' => false
                ));
            }
        }
        echo '</div>';
    } else {
        echo '<p>No reviews found.</p>';
    }
}

// Add new function to display the filter form
function display_filter_form() {
    include plugin_dir_path(__FILE__) . 'filter-form.php';
}
