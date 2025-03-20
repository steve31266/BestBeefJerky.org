<?php
if (!defined('ABSPATH')) {
    exit;
}

// List of taxonomy keys and their corresponding ACF field names
$taxonomy_settings = [
    'meat-type'   => ['image' => 'meat-type-image', 'seo_title' => 'meat-type-seo-title'],
    'ingredient'  => ['image' => 'ingredient_image', 'seo_title' => 'ingredient_seo_title'],
    'claim'       => ['image' => 'claim_image', 'seo_title' => 'claim_seo_title'],
    'variety'     => ['image' => 'variety_image', 'seo_title' => 'variety_seo_title'],
    'allergen'    => ['image' => 'allergen_image', 'seo_title' => 'allergen_seo_title'],
    'snack-form'  => ['image' => 'snack_form_image', 'seo_title' => 'snack_form_seo_title']
];

// Function to modify the <title> tag dynamically for each taxonomy
function custom_taxonomy_archive_title($title) {
    global $taxonomy_settings;

    foreach ($taxonomy_settings as $taxonomy => $fields) {
        if (is_tax($taxonomy)) {
            $term = get_queried_object();
            if ($term) {
                // Get the SEO title field
                $seo_title = get_field($fields['seo_title'], $taxonomy . '_' . $term->term_id);
                
                if (!empty($seo_title)) {
                    $title['title'] = esc_attr($seo_title);
                } else {
                    $title['title'] = esc_attr($term->name) . ' Jerky Brands';
                }
            }
        }
    }
    return $title;
}
add_filter('document_title_parts', 'custom_taxonomy_archive_title');

// Function to generate meta tags dynamically for all taxonomy archives
function custom_taxonomy_meta_tags() {
    global $taxonomy_settings;

    foreach ($taxonomy_settings as $taxonomy => $fields) {
        if (is_tax($taxonomy)) {
            $term = get_queried_object();
            if ($term) {
                // Get SEO Title
                $seo_title = get_field($fields['seo_title'], $taxonomy . '_' . $term->term_id);
                $meta_title = !empty($seo_title) ? esc_attr($seo_title) : esc_attr($term->name) . ' Jerky Brands';

                // Get taxonomy description
                $taxonomy_description = term_description($term->term_id, $taxonomy);
                if (!empty($taxonomy_description)) {
                    $description = esc_attr(strip_tags($taxonomy_description));
                } else {
                    $description = 'Discover a wide variety of ' . esc_attr($term->name) . ' jerky brands that you can buy online. We\'ve taste-tested several ' . esc_attr($term->name) . ' jerky varieties and flavors for review.';
                }

                // Get taxonomy image from ACF
                $image_url = get_field($fields['image'], $taxonomy . '_' . $term->term_id);
                if (empty($image_url)) {
                    $image_url = get_site_url() . '/wp-content/uploads/2025/03/jerky-reviews-social-media-banner.webp';
                }

                // Get current page URL
                $page_url = get_term_link($term);

                // Output meta tags
                echo '<meta name="description" content="' . $description . '">' . "\n";

                // Open Graph Meta Tags
                echo '<meta property="og:title" content="' . $meta_title . '">' . "\n";
                echo '<meta property="og:description" content="' . $description . '">' . "\n";
                echo '<meta property="og:image" content="' . esc_url($image_url) . '">' . "\n";
                echo '<meta property="og:url" content="' . esc_url($page_url) . '">' . "\n";
                echo '<meta property="og:type" content="website">' . "\n";

                // Twitter Card Meta Tags
                echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
                echo '<meta name="twitter:title" content="' . $meta_title . '">' . "\n";
                echo '<meta name="twitter:description" content="' . $description . '">' . "\n";
                echo '<meta name="twitter:image" content="' . esc_url($image_url) . '">' . "\n";

                // Generate CollectionPage Schema
                $collection_page_schema = [
                    "@context" => "https://schema.org",
                    "@type" => "CollectionPage",
                    "name" => $meta_title,
                    "description" => $description,
                    "url" => esc_url($page_url),
                    "image" => esc_url($image_url)
                ];

                // Output Schema Markup in JSON-LD format
                echo '<script type="application/ld+json">' . json_encode($collection_page_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";

                // Stop looping once the correct taxonomy is found
                break;
            }
        }
    }
}
add_action('wp_head', 'custom_taxonomy_meta_tags');
