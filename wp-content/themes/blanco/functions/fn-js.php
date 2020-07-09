<?php

function apd_custom_theme_js()
{
    // 3 args
    // name your script // location of your script file// this array lists the the dependencies needed for your js eg jQuery
    wp_enqueue_script('app_js', get_template_directory_uri() . '/js/app.js', array('jquery'), '', true);

    wp_enqueue_script('aos_animation_js', get_template_directory_uri() . '/js/aos_animation.js', '', '', true);

    wp_enqueue_script('aos_init_js', get_template_directory_uri() . '/js/aos_init.js', '', '', true);

}

///AND DON’T FORGET TO CALL IT
add_action('wp_enqueue_scripts', 'apd_custom_theme_js');

?>