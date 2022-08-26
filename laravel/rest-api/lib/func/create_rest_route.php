<?php
add_action( 'rest_api_init', function(){

    $namespace = 'rest-api-theme/v1';

    $rout = '/pages';

    $rout_params = [
        'methods'  => 'GET',
        'callback' => 'my_awesome_func',
        'args'     => array(
            'number' => array(
                'type'    => 'integer',
                'default' => 1,
            ),
        ),
    ];

    register_rest_route( $namespace, $rout, $rout_params );

} );

function my_awesome_func(WP_REST_Request $request){
    $result = '';

    $page = $request->get_param('number');

    $args = array(
        'posts_per_page' => 3,
        'paged' => $page,
    );

    $query = new WP_Query($args);
    if(!($query->have_posts())){
        return 'none';
    }

    $posts = $query->get_posts();
    foreach($posts as $post){
        the_post();
        $result .= '<a href="' . get_the_permalink() . '"><h1>' . get_the_title($post) . '</h1></a>';
        $result .= '<p>' . get_the_excerpt($post) . '</p>';
    }
    wp_reset_postdata();

    return ($result);
}