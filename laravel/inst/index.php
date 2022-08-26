<?php
/*
Template Name: posts-page
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
            $appId = get_field('instagram_app_id', 'option');
            $appSecret = get_field('instagram_app_secret', 'option');
            $access = get_field('instagram_access_token', 'option');

            require_once 'lib/classes/Instagram_Media.php';
            $inst = \Harbinger_Marketing\Instagram_Media::init($appId, $appSecret, $access);
            $media = (array)$inst->get_media()->data;?>

            <div class="slider">
                <?php foreach ($media as $post){
                    if($post->media_type !== 'VIDEO'):?>
                        <div class="item">
                            <a href="<?= $post->permalink?>">
                                <img src="<?php echo $post->media_url;?>">
                                <!--div class="slideText">< $post->description?>></div-->
                            </a>
                        </div>
                    <?php endif;
                }?>

                <a class="prev" onclick="minusSlide()">&#10094;</a>
                <a class="next" onclick="plusSlide()">&#10095;</a>
            </div>

        <footer class="footer">
            <?php get_footer();?>
        </footer>

        <script src="scripts/slider.js"></script>
    </body>
</html>