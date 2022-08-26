<?php
/*
Template Name: home-page
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

        <div class="middle">
            <h1 style="text-align: center">WELCOME HOME</h1>
            <?php

            ?>
        </div>

        <footer class="footer">
            <?php get_footer();?>
        </footer>
    </body>
</html>
