<?php

require_once 'vendor/autoload.php';

wp_enqueue_script('slider_script', get_template_directory_uri() . '/scripts/slider.js');
wp_enqueue_style('slider_style', get_template_directory_uri() . '/styles/slider.css');

add_action( 'wp_enqueue_scripts', 'scf_theme_scripts' );

require_once 'lib/classes/Instagram_Media.php';

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' => 'Theme Settings',
        'menu_title' => 'Theme Settings',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}
