<?php
class My_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth=0, $args=array())
    {
        $output .= "<ul> ";
    }
    function end_lvl(&$output, $depth=0, $args=array())
    {
        $output .= "</ul> ";
    }
    function start_el(&$output, $item, $depth=0, $args=array(), $id = 0)
    {
        $output .= "<li class='" .  implode(" ", $item->classes) . "'>";

        if ($item->url && $item->url != '#') {
            $output .= '<a href="' . $item->url . '">';
        } else {
            $output .= '<span>';
        }

        $output .= $item->title;

        if ($item->url && $item->url != '#') {
            $output .= '</a>';
        } else {
            $output .= '</span>';
        }
    }

    function end_el(&$output, $item, $depth = 0, $args=array())
    {
        $output .= "</li> ";
    }

}