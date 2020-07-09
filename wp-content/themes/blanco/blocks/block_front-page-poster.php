<div class="section">
    <div class="section-row poster-wrapper">
        <!-- <?php include "inc/nav.php";?> -->
        <div class="poster-icon-wrapper">
            <div class="poster-icon">
                <img src="<?php the_field('poster_center_icon'); ?>" alt="Blanco Nino Poster Icon">
            </div>
        </div>

        <div class="poster-section-left section1-left">
            <h1>
                <?php the_field('poster_left_text'); ?>
            </h1>

            <div class="background-image-cont">
                <?php 
                    $image = get_field('poster_left_image');
                    $size = 'full'; // (thumbnail, medium, large, full or custom size)
                    if( $image ) {
                        echo wp_get_attachment_image( 
                            $image['id'], 
                            false,
                            $size,                                     
                            array ('title' => $image['title'], 'alt' => $image['alt']));
                }?>
            </div>


        </div>

        <div class="poster-section-right section1-right">

            <h1><?php the_field('poster_right_text'); ?></h1>

            <div class="background-image-cont">
                <?php 
                    $image = get_field('poster_right_image');
                    $size = 'full'; // (thumbnail, medium, large, full or custom size)
                    if( $image ) {
                        echo wp_get_attachment_image( 
                            $image['id'], 
                            false,
                            $size,                                     
                            array ('title' => $image['title'], 'alt' => $image['alt']));
                }?>
            </div>

        </div>
    </div>
</div>