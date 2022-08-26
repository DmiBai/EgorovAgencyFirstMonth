<?php

register_nav_menus(array(
    'top'    => 'Top menu',
    'bottom' => 'Bottom menu'      ));

require_once 'custom_post_types/add_slider.php';
add_action('init', 'create_slider');

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title'   => 'Theme Settings',
        'menu_title'  => 'Theme Settings',
        'menu_slug'   => 'theme-general-settings',
        'capability'  => 'edit_posts',
        'redirect'    => false
    ));

}
