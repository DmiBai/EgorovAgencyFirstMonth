<?php /* Template Name: my-home-page */ ?>
<html lang="en">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <title>
            <?php echo wp_get_document_title(); ?>
        </title>
        <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" />
    </head>

    <body>
        <header class="header">
            <?php wp_head(); ?>
        </header>

        <div id="main">
            <?php
            $args = array(
                'posts_per_page' => 3,
                'post_type' => 'post',
            );
            $query = new WP_Query($args);
            if( $query->have_posts() ):
                while ( $query->have_posts() ):
                    $query->the_post();
                ?>
                    <h1><a href="'<?= get_permalink(); ?>'"><?= get_the_title(); ?></a></h1>
                    <div class="another"><?= get_the_content(); ?></div>
                <?php
                endwhile;
            endif;
            ?>
        </div>

        <input id="btn" class="button" type="button" value="Show more">

        <?= get_permalink();?>

        <footer class="footer">
            <?php wp_footer();?>
        </footer>
    </body>
</html>