<div class="full-width">
    <div class="container text-center">
        <h1 class="<?php  echo the_field('full_width_text_size')?> white"><?php the_field('full_width_title'); ?></h1>
        <h2></h2>
    </div>

    <div class="background-image-cont">
        <?php 
                    $image = get_field('full_width_image');
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