<?php
class My_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth=0, $args=array())
    {
        $output .= ' ';
    }
    function end_lvl(&$output, $depth=0, $args=array())
    {
        $output .= ' ';
    }
    function start_el(&$output, $item, $depth=0, $args=array(), $id = 0)
    {
        $output .= '';

        $taxonomy= get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        if($taxonomy->taxonomy === 'locations'){
            the_post();
            $title = get_the_title();
            echo "<a href='http://wordpress/services/" . $title . "'>" . $title . " ";
        } else {
            wp_reset_postdata();
        }

        if ($item->url && $item->url != '#') {
            $output .= '<a href="' . $item->url . '">';
        } else {
            $output .= '';
        }

        $output .= $item->title;

        if ($item->url && $item->url != '#') {
            $output .= '</a>';
        } else {
            $output .= '';
        }
    }

    function end_el(&$output, $item, $depth = 0, $args=array())
    {
        $output .= " ";
        wp_reset_postdata();
    }

}
