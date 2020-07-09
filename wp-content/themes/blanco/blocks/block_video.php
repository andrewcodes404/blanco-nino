<!-- THE VIDEO MODAL -- THE VIDEO MODAL --  -->
<div class="video-modal">
    <!-- <iframe src="https://player.vimeo.com/video/315517029" frameborder="0" webkitallowfullscreen mozallowfullscreen
        allowfullscreen></iframe> -->
</div>

<div class="close-button">
    <img src="<?php echo get_template_directory_uri(); ?>/images/graphics/video-close-button.png" alt="">
</div>

<!-- HERO MOBILE -- HERO MOBILE --  -->
<div class="full-width invest-hero-mobile">
    <div class="play-button-mobile">
        <a href="<?php the_field('video_url')?>" target="_blank">
            <img src="<?php echo get_template_directory_uri(); ?>/images/graphics/video-play-button.png" alt="">
        </a>
    </div>

    <div class="background-image-cont <?php echo the_field('text_background_choice') ?>">
        <?php if( get_field('video_poster_image') ): ?>
        <?php 
                $image = get_field('video_poster_image');
                $size = 'full'; // (thumbnail, medium, large, full or custom size)
                if( $image ) {
                    echo wp_get_attachment_image( 
                        $image['id'], 
                        $size,   
                        false,                                  
                        array ('title' => $image['title'], 'alt' => $image['alt']) );
            }?>
        <?php endif; ?>
    </div>
</div>


<!-- HERO DESKTOP -- HERO DESKTOP --  -->
<div class="full-width invest-hero-desktop">
    <div class="play-button-desktop">
        <img src="<?php echo get_template_directory_uri(); ?>/images/graphics/video-play-button.png" alt="">
    </div>


    <div class="background-image-cont <?php echo the_field('text_background_choice') ?>">
        <?php if( get_field('video_poster_image') ): ?>
        <?php 
                $image = get_field('video_poster_image');
                $size = 'full'; // (thumbnail, medium, large, full or custom size)
                if( $image ) {
                    echo wp_get_attachment_image( 
                        $image['id'], 
                        $size,   
                        false,                                  
                        array ('title' => $image['title'], 'alt' => $image['alt']) );
            }?>
        <?php endif; ?>
    </div>
</div>