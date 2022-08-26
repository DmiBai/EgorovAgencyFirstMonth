<?php

function create_post_type() {
    register_post_type(
        'service',
        array(
            'labels'             => array(
                'name'               => _x( 'Services', 'post type general name' ),
                'singular_name'      => _x( 'Service', 'post type singular name' ),
                'menu_name'          => _x( 'Services', 'admin menu' ),
                'name_admin_bar'     => _x( 'Service', 'add new on admin bar' ),
                'add_new'            => _x( 'Add New', 'service' ),
                'add_new_item'       => __( 'Add New Service' ),
                'new_item'           => __( 'New Service' ),
                'edit_item'          => __( 'Edit Service' ),
                'view_item'          => __( 'View Service' ),
                'all_items'          => __( 'All Services' ),
                'search_items'       => __( 'Search Services' ),
                'parent_item_colon'  => __( 'Parent Services:' ),
                'not_found'          => __( 'No services found.' ),
                'not_found_in_trash' => __( 'No services found in Trash.' )
            ),
            'description'        => __( 'Description.' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'		  => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'services' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => null,
            'supports'           => array('title', 'thumbnail'),
        )
    );
}
