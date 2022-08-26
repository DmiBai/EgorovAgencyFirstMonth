<?php

function add_taxonomy_location() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy(
        'locations',
        ['service'],
        [
            'labels'            => array(
                'name'              => _x( 'Locations', 'taxonomy general name'),
                'singular_name'     => _x( 'Location', 'taxonomy singular name'),
                'search_items'      => __( 'Search Locations'),
                'all_items'         => __( 'All Locations' ),
                'parent_item'       => __( 'Parent Location' ),
                'parent_item_colon' => __( 'Parent Location:' ),
                'edit_item'         => __( 'Edit Location' ),
                'update_item'       => __( 'Update Location' ),
                'add_new_item'      => __( 'Add New Location' ),
                'new_item_name'     => __( 'New Location' ),
                'menu_name'         => __( 'Locations' )
            ),
            'public'                => true,
            'publicly_queryable'    => true,
            'query_var'             => true,
            'show_in_nav_menus'     => true,
            'show_ui'               => true,
            'show_tagcloud'         => false,
            'hierarchical'          => true,
            'show_admin_column'     => true,
            'rewrite'           	=> [
                'slug' => 'locations',
            ]
        ]
    );
}
