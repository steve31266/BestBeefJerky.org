<?php
/**
 * Modify <title> Tag for "est" Custom Post Type
 */

defined('ABSPATH') || exit;

function est_modify_document_title($title) {
    if (is_singular('est')) {
        $title['title'] = 'USDA Establishment # ' . $title['title'];
    }
    return $title;
}
add_filter('document_title_parts', 'est_modify_document_title');
?>
