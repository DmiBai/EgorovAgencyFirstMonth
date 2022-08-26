<?php
/*
Template Name: posts-page
*/
?>
<html>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <title>
            <?php echo wp_get_document_title(); ?>
        </title>

        <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" />
    </head>

    <body>
        <header class="header">
            <?php get_header(); ?>
        </header>

        <div>
            <?php

                $url =  $_SERVER['REQUEST_URI'];
                $url = explode('?', $url);
                $url = $url[0];
                $url = explode('/', $url);
                if($url[1] == 'locations') {
                    $taxonomy= get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                    echo '<h1>' . mb_strtoupper($taxonomy->slug) . '</h1>';
                } else {
                    the_post();
                    echo '<h1>' . get_the_title() . '</h1>';
                    $content = get_the_content();
                    echo $content;
                }
            ?>
        </div>

        <footer class="footer">
            <?php get_footer();?>
        </footer>
    </body>
</html>
