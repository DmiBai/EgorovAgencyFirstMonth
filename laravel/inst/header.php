<?php
//$query = new WP_Query(array('post_type'=>'service', 'locations'=>'boston'));
//echo var_dump($query->get_posts());

require ('lib/classes/my-walker.php');
//wp_nav_menu('top');
//wp_nav_menu(array('walker' => new My_Walker()));

//$terms = get_terms(array('taxonomy'=>'locations', 'hide_empty'=>false));
////echo var_dump($terms);
//foreach($terms as $term){
//    $queryServ = new WP_Query(array('post_type'=>'service', 'locations'=>$term->name, 'hide_empty'=>true));
//    $posts = $queryServ->get_posts();
//    foreach($posts as $post){
//        echo "<br>" . $post->post_title ;
//    }
//}
