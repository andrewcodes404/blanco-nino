<div class="section-row ">

    <div class="row-col-2-text <?php echo the_field('row_col_2_text_background_choice')?>">
        <div class="row-col-2-text-content" data-aos="fade-up">

            <?php if( get_field('row_col_2_icon') ): ?>
            <div class="section-icon">
                <img
                    src="<?php echo get_template_directory_uri(); ?>/images/graphics/<?php  echo the_field('row_col_2_icon')?>">
            </div>
            <?php endif; ?>

            <h2 class="<?php echo the_field('row_col_2_title_color')?>">
                <?php the_field('row_col_2_title'); ?>
            </h2>
          <br>

          <div class="<?php echo the_field('row_col_2_body_color')?>">
          
          <?php the_field('row_col_2_body_text'); ?>
          </div>

            <!-- <h3 class="<?php echo the_field('row_col_2_body_color')?>">
                <?php the_field('row_col_2_strapline'); ?>
            </h3>
   

            <p class="<?php echo the_field('row_col_2_body_color')?>">
                <?php the_field('row_col_2_paragraph'); ?>
            </p> -->



            <?php if( get_field('row_col_2_link_text') ): ?>
       
                <span class="<?php echo the_field('row_col_2_body_color')?>">
                    <a
                        href="<?php the_field('row_col_2_link_address')?>"><?php the_field('row_col_2_link_text') ?><span>&rarr;</a></span>
                </span>
            </h3>
            <?php endif; ?>

            <div class="link-with-image">

                <?php if( get_field('row-col-2_link_image') ): ?>
                <a href="<?php the_field('row_col_2_link_address')?>" target="_blank">
                    <?php 
                    $image = get_field('row-col-2_link_image');
                    $size = 'full'; // (thumbnail, medium, large, full or custom size)
                    if( $image ) {
                        echo wp_get_attachment_image( 
                            $image['id'], 
                            $size,   
                            false,                                  
                            array ('title' => $image['title'], 'alt' => $image['alt']) );
                }?>
                    <?php endif; ?>
                </a>
            </div>




            <?php if( get_field('row-col-2_button_text') ): ?>
            <div class="button">
                <a href="<?php the_field('row-col-2_button_link') ?>">
                    <span><?php the_field('row-col-2_button_text')?></span>
                </a>
            </div>
            <?php endif; ?>






        </div>

        <div class="background-image-cont <?php echo the_field('text_background_choice') ?>">
            <?php if( get_field('row_col_2_text_background') ): ?>
            <?php 
                    $image = get_field('row_col_2_text_background');
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

    <div class="row-col-2-image">

        <div class="background-image-cont">
            <?php 
                    $image = get_field('row_col_2_image');
                    $size = 'full'; // (thumbnail, medium, large, full or custom size)
                    if( $image ) {
                        echo wp_get_attachment_image( 
                            $image['id'], 
                            $size,     
                            false,                                
                            array ('title' => $image['title'], 'alt' => $image['alt']));
                }?>
        </div>
    </div>
</div>