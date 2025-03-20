<?php
/**
 * Shortcode: [heat_level]
 * Description: Displays the heat level with an image and hyperlink to the taxonomy archive.
 * Version: 1.1
 * Author: Choctaw Websites
 */

defined('ABSPATH') || exit; // Prevent direct access

// Enqueue CSS file for heat level display
function heat_level_enqueue_styles() {
    if (!is_singular('reviews')) {
        return; // Ensure styles only load on review posts
    }
    wp_enqueue_style(
        'heat_level_css',
        plugin_dir_url(__FILE__) . 'heat_level.css',
        [],
        '1.1'
    );
}
add_action('wp_enqueue_scripts', 'heat_level_enqueue_styles');

// Shortcode function
function display_heat_level() {
    if (!is_singular('reviews')) {
        return ''; // Ensure it only appears on "reviews" post type
    }

    // Get the Term Object stored in 'reviews_heat_level' field
    $heat_terms = get_field('reviews_heat_level'); 

    if (!$heat_terms || is_wp_error($heat_terms)) {
        return ''; // Exit if no heat level is set
    }

    // Ensure it's an array (ACF allows multiple terms even if only one is picked)
    if (!is_array($heat_terms)) {
        $heat_terms = [$heat_terms]; // Convert single object to an array
    }

    // Get the first term (since users will always pick one)
    $term = reset($heat_terms);
    
    // Ensure it's a valid term object
    if (!isset($term->term_id)) {
        return ''; // Exit if term ID is missing
    }

    // Get the taxonomy name (term name)
    $heat_name = esc_html($term->name);

    // Get the taxonomy archive URL
    $heat_link = esc_url(get_term_link($term));
    
    // Get the 'heat_image' field from the taxonomy
    $heat_image = get_field('heat_image', 'heat-level_' . $term->term_id);
    if (!$heat_image) {
        return ''; // Exit if no image is set
    }

    // Output HTML
    ob_start();
    ?>
    <div class="heat-level">
        <span class="heat-label">Heat Level:</span>
        <img class="heat-image" src="<?php echo esc_url($heat_image); ?>" alt="<?php echo esc_attr($heat_name); ?>">
        <a class="heat-text" href="<?php echo esc_url($heat_link); ?>"><?php echo esc_html($heat_name); ?></a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('heat_level', 'display_heat_level');
?>
