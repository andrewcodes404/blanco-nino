<?php get_header(); ?>
<?php include "inc/nav.php";?>
<div class="height-for-nav"></div>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="single-container">

    <div class="single-cont">
        <h1><?php the_title()?></h1>

        <?php the_content(); ?>
    </div>

</div>

<?php endwhile; else : ?>
<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>
</div>

<?php get_footer(); ?>