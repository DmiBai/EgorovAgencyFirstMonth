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

    <?php get_header(); ?>
</head>

<body>


<header class="header">
    <?php get_header(); ?>
</header>

<div class="middle">
    <h1 style="text-align: center">WELCOME HOME</h1>
    <?php /*
    if(have_posts()):
        while( have_posts() ):
            the_post();
            the_title();
            echo '<hr>';
            the_content();
            echo '<br>';
            echo '<hr>';
            echo '<hr>';
        endwhile;
    endif;*/
    ?>
</div>


<footer class="footer">
    <?php get_footer();?>
</footer>

<?php wp_footer(); ?>
</body>

</html>