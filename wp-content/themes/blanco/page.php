<?php get_header();?>
<?php include "inc/nav.php";?>

<!-- <div class="<?php if (!is_front_page()) : echo 'height-for-nav'; else : echo ''; endif;?>"></div> -->





<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; else : ?>
<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>
</div>



<div class="google-map">
    <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1Lk8dXXYTKJvRUobJOimFMY-5mD1kNqvN" height="100%"
        width="100%"></iframe>
</div>
<?php get_footer(); ?>