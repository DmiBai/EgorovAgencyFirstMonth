<?php
/*
Template Name: services-page
*/
?>
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title>
        <?php echo wp_get_document_title(); ?>
    </title>

    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" />

    <?php get_header(); ?>
</head>

<body>


<header class="header">
    <?php get_header(); ?>
</header>

<div>
    <?php

    // Запрашиваем продукты
    $query = new WP_Query( [
        'post_type' => 'Service'
    ] );

    //
    if ( $query->have_posts() ) {

        while ( $query->have_posts() ) {
            $query->the_post();?>

            <div class="news-item">
                <p class="post_title"><?php the_title(); ?></p>
                <p><?php $content = get_the_content(); echo $content;?></p>
            </div>

        <?php }

        wp_reset_postdata();
    }
    ?>


</div>


<footer class="footer">
    <?php get_footer();?>
</footer>

<?php wp_footer(); ?>
</body>

</html>