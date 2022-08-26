<?php

register_nav_menus(array(
    'top'    => 'Верхнее меню',
    'bottom' => 'Нижнее меню'
));

function create_posttype() {
    register_post_type( 'Service',
// CPT Options
        array(
            'labels' => array(
                'name' => __( 'Service' ),
                'singular_name' => __( 'Service' )
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'Service'),
            'taxonomies' => array( 'Locations' ),
        )
    );
}

function add_custom_taxonomies() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('Locations', ['Service'], array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Locations', 'taxonomy general name' ),
            'singular_name' => _x( 'Location', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Location' ),
            'all_items' => __( 'All Locations' ),
            'parent_item' => __( 'Parent Location' ),
            'parent_item_colon' => __( 'Parent Location:' ),
            'edit_item' => __( 'Edit Location' ),
            'update_item' => __( 'Update Location' ),
            'add_new_item' => __( 'Add New Location' ),
            'new_item_name' => __( 'New Location Name' ),
            'menu_name' => __( 'Locations' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'locations', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}

function wpshout_add_taxonomies_to_courses() {
    register_taxonomy_for_object_type( 'Location', 'Service' );
}

add_action( 'init', 'add_custom_taxonomies', 0 );
add_action( 'init', 'create_posttype' );
//add_action( 'init', 'wpshout_add_taxonomies_to_courses' );