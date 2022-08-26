<?php
/*
 * Plugin Name: my-post-counter
 */
add_action('admin_notices', 'postCount');
function postCount(){
    $query = new WP_Query(array('post_type' => 'post'));
    $posts = get_posts($query);
    $counter = count($posts);
    echo 'Posts count:' . $counter;
}
