<?php
require_once ('lib/classes/my-walker.php');
wp_nav_menu(array('theme_location'=>'top', 'walker' => new My_Walker()));

$terms = get_terms(array('taxonomy'=>'locations', 'hide_empty'=>false));
foreach($terms as $term){
    $queryServ = new WP_Query(array('post_type'=>'service', 'locations'=>$term->name));
    $posts = $queryServ->get_posts();

    foreach($posts as $post){
        if($post->post_name ==$term->name) {
            echo "<br>" . $post->post_title;
        }
    }
}
