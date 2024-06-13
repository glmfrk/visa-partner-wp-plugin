<?php

// Register custom taxonomies
add_action('init', 'visa_partner_register_taxonomies');
function visa_partner_register_taxonomies() {
    $taxonomies = [
        'citizen_country' => 'Citizen Country',
        'travel_country' => 'Travel Country',
        'visa_category' => 'Visa Category'
    ];

    foreach ($taxonomies as $taxonomy => $label) {
        $labels = array(
            'name' => _x($label . 's', 'taxonomy general name', 'visa-partner'),
            'singular_name' => _x($label, 'taxonomy singular name', 'visa-partner'),
            'search_items' => __('Search ' . $label . 's', 'visa-partner'),
            'all_items' => __('All ' . $label . 's', 'visa-partner'),
            'parent_item' => __('Parent ' . $label, 'visa-partner'),
            'parent_item_colon' => __('Parent ' . $label . ':', 'visa-partner'),
            'edit_item' => __('Edit ' . $label, 'visa-partner'),
            'update_item' => __('Update ' . $label, 'visa-partner'),
            'add_new_item' => __('Add New ' . $label, 'visa-partner'),
            'new_item_name' => __('New ' . $label . ' Name', 'visa-partner'),
            'menu_name' => __($label, 'visa-partner'),
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $taxonomy),
        );

        register_taxonomy($taxonomy, array('evisa_country'), $args);
    }
}