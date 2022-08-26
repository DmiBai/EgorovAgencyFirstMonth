<?php
/*
Template Name: home-page
*/
?>

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
            <?php get_header(); ?>
        </header>
        <div>
            <?php
            $query = new WP_Query(['post_type'=>'post']);
            $my_posts = $query->get_posts();
            foreach($my_posts as $post) {
                setup_postdata($post);
                $slider = get_field('slide');
                $count = count($slider);
                for($i = 0; $i < $count; $i++){
                $url = $slider[$i]['image'];
                $description = $slider[$i]['description'];
            ?>

            <img src="<?php echo $url;?>" class="slide" alt="hi">
            <p><?php echo $description; ?></p>
            <?php
                }
            }
            wp_reset_postdata();//to bugs prevent. imho - optional.
            ?>
        </div>

        <div class="form">
            <?php
                echo do_shortcode('[ninja_form id=1]');
            ?>
        </div>

        <footer class="footer">
            <?php get_footer();
            wp_footer();?>
        </footer>
    </body>
</html>