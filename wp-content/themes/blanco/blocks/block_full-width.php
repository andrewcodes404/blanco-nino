<div class="full-width hero-full-width">

    <?php if( get_field('title_text_or_title_image') == "text"  ): ?>
    <div class="container text-center">
        <h1 class="<?php  echo the_field('full_width_text_size')?> white"><?php the_field('full_width_title'); ?></h1>
        <h2></h2>
    </div>
    <?php endif; ?>


    <?php if( get_field('title_text_or_title_image') == "image"  ): ?>


    <div class="container text-center hero-title-image">

        <?php 
                    $image = get_field('title_image');
                    $size = 'large'; // (thumbnail, medium, large, full or custom size)
                    if( $image ) {
                        echo wp_get_attachment_image( 
                            $image['id'], 
                            false,
                            $size,                                     
                            array ('title' => $image['title'], 'alt' => $image['alt']));
                }?>

    </div>
    <?php endif; ?>


    <div class="background-image-cont">
        <?php 
                    $image = get_field('full_width_image');
                    $size = 'large'; // (thumbnail, medium, large, full or custom size)
                    if( $image ) {
                        echo wp_get_attachment_image( 
                            $image['id'], 
                            false,
                            $size,                                     
                            array ('title' => $image['title'], 'alt' => $image['alt']));
                }?>
    </div>

</div>