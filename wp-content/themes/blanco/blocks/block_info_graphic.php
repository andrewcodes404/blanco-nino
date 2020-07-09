<div class="flex-col-center card-section <?php echo the_field('background_colour')?>">

    <!-- TITLE AND STRAPLINE -- TITLE AND STRAPLINE --  -->
    <div class="card-section-title" data-aos="fade-up">
        <h2 class="<?php echo the_field('title_colour')?>"><?php the_field('info_graphic_title')?></h2>
        <br>

        <?php if( get_field('info_graphic_strapline') ): ?>
        <h3><?php the_field('info_graphic_strapline')?></h3>
        <?php endif; ?>
    </div>


    <!-- CARDS --- CARDS --- CARDS --- CARDS ---  -->

    <div class="card-cont" data-aos="fade-up">

        <?php if( have_rows('info_graphic_card_repeater') ): ?>

        <?php while( have_rows('info_graphic_card_repeater') ): the_row(); 
                    // vars
                    $image = get_sub_field('info_graphic_card_image');
                    $title = get_sub_field('info_graphic_card_title');
                    $strapline = get_sub_field('info_graphic_card_strapline');
                    ?>

        <div class="card">
            <div class="card-icon">
                <img src="<?php echo $image['url']?>" alt="<?php echo $image['alt'] ?>" />
            </div>

            <div class="card-text">
                <h3><?php echo $title ?></h3>
                <p><?php echo $strapline ?></p>
            </div>
        </div>

        <?php endwhile; ?>

        <?php endif; ?>


    </div>
</div>