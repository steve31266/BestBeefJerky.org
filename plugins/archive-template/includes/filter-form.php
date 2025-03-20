<?php
/**
 * Filter Form for Jerky Reviews - Supports dynamic filters including Rating, Carbs, and Meat.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get active filters from URL
$filters = get_active_filters();

echo '<form method="GET" class="rating-filter-form">';

// Define filters in an array for scalability
$filter_options = [
    'rating_filter' => [
        'label'   => 'Rating',
        'class'   => 'rating-dropdown',
        'options' => [
            '0'    => 'All',
            '754'  => 'Best',
            '755'  => 'Good',
            '756'  => 'Average',
            '757'  => 'Fair',
            '758'  => 'Dog Treats'
        ]
    ],
    'carbs_filter' => [
        'label'   => 'Total Carbs',
        'class'   => 'carbs-dropdown',
        'options' => [
            'all'  => 'All',
            'low'  => 'Low Carb',
            'zero' => 'Zero Carb'
        ]
    ],
    'meat_filter' => [
        'label'   => 'Meat',
        'class'   => 'meat-dropdown',
        'options' => []
    ]
];

// Dynamically populate Meat dropdown with terms from "meat-type" taxonomy
$meat_terms = get_terms([
    'taxonomy'   => 'meat-type',
    'hide_empty' => false
]);

$filter_options['meat_filter']['options']['all'] = 'All';
if (!empty($meat_terms) && !is_wp_error($meat_terms)) {
    foreach ($meat_terms as $meat) {
        $filter_options['meat_filter']['options'][$meat->slug] = $meat->name;
    }
}

// Loop through each filter and generate dropdowns dynamically
foreach ($filter_options as $name => $filter) {
    echo '<div class="filter-group">';
    echo '<label for="' . esc_attr($name) . '">' . esc_html($filter['label']) . '</label>';
    echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" class="' . esc_attr($filter['class']) . '">';
    
    foreach ($filter['options'] as $value => $label) {
        echo '<option value="' . esc_attr($value) . '" ' . selected($filters[str_replace('_filter', '', $name)], $value, false) . '>' . esc_html($label) . '</option>';
    }

    echo '</select>';
    echo '</div>';
}

// Submit button
echo '<button type="submit" class="rating-filter-button">Filter</button>';

// Add JavaScript to handle form submission
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('.rating-filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get current URL and page number
            const currentUrl = window.location.href;
            const pageMatch = currentUrl.match(/\/page\/(\d+)\//);
            const currentPage = pageMatch ? parseInt(pageMatch[1]) : 1;
            
            // If we're on page 1 or there's no page number, submit normally
            if (currentPage === 1) {
                this.submit();
                return;
            }
            
            // Remove page number from URL and redirect to page 1
            let newUrl = currentUrl.replace(/\/page\/\d+\//, '/');
            
            // Add filter parameters
            const formData = new FormData(this);
            const params = new URLSearchParams(formData);
            newUrl = newUrl.split('?')[0] + '?' + params.toString();
            
            // Redirect to page 1 with filters
            window.location.href = newUrl;
        });
    }
});
</script>
<?php

echo '</form>';
