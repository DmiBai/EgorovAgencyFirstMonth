
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php $counter = 0; ?>
        <?php while (have_rows('sliders')) : the_row() ?>
            <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $counter;?>" <?php if($counter == 0) {echo 'class="active"';}  ?>></li>
            <?php $counter++; ?>
        <?php endwhile;?>
    </ol>
    <div class="carousel-inner">
        <?php $counter = 0; ?>
        <?php while (have_rows('sliders')) : the_row() ?>
            <div class="carousel-item <?php if($counter == 0) {echo 'active';}  ?>">
                <?php $photo = get_sub_field('images'); ?>
                <img class="d-block w-100" src="<?php echo $photo['sizes']['large']; ?>" alt="">
            </div>
            <?php $counter++; ?>
        <?php endwhile; ?>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="sr-only">Next</span>
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </a>
</div>