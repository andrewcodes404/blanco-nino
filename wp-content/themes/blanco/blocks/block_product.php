<div class="product-section-wrapper  <?php echo the_field('product_background_color')?>">

    <div class="product-section">

        <div class="product-text-cont">

            <div class="product-title">
                <?php $productImage = get_field('product_title'); ?>
                <img src="<?php echo $productImage['url']?>" alt="<?php echo $productImage['alt'] ?>" />
            </div>

                <div class="product-text-map-flex">

                    <div class="product-text">
                        <h3><?php the_field('product_sub_title')  ?></h3>
                        <?php the_field('product_description')?>
                    </div>

                    <div class="product-map">
                        <?php $mapImage = get_field('product_map_image'); ?>
                        <img src="<?php echo $mapImage['url']?>" alt="<?php echo $mapImage['alt'] ?>" />
                    </div>

                </div>
        </div>


        <div class="product-pack-image">
        <?php 
                    $image = get_field('product_image');
                    $size = 'full'; // (thumbnail, medium, large, full or custom size)
                    if( $image ) {
                        echo wp_get_attachment_image( 
                            $image['id'], 
                            $size,   
                            false,                                  
                            array ('title' => $image['title'], 'alt' => $image['alt']) );
                }?>

        </div>

    </div>
</div>
