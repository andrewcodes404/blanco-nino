<?php get_header();?>

<?php include "inc/nav.php";?>
<!-- <div class="height-for-nav"></div> -->

<div class="full-width ">
    <div class="container text-center ">
        <h1 class="white">Tales of wild adventure, recipes to make your mouth water and the very latest news</h1>
        <h2>
        </h2>
    </div>


    <div class="background-image-cont blog-home-poster">

        <?php
      echo wp_get_attachment_image(
        695,
        $size,
        false,
        array('title' => $image['title'], 'alt' => $image['alt']));
      ?>
    </div>
</div>






<div class="blog-section-wrapper">

    <?php while (have_posts()): the_post();?>

    <div class="blog-section-row">

        <div class="blog-section-text">
            <div class="section-text-cont ">
                <h2 class=""><?php the_title()?></h2>
                <p><?php the_excerpt()?></p>
                <p><a href=" <?php echo get_permalink(); ?>">read more &rarr;</a></p>
            </div>
        </div>


        <div class="blog-section-img">

            <div class="background-image-cont">
                <?php 
                  
                  $post_thumbnail_id = get_post_thumbnail_id( $post_id );
                        echo wp_get_attachment_image( 
                            $post_thumbnail_id,
                            false,
                            $size,                                     
                            array ('title' => $image['title'], 'alt' => $image['alt']));
                ?>
            </div>
            
        </div>
    </div>

    <?php endwhile;?>

</div>


<?php get_footer();?>