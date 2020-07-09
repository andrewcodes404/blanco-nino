<?php

//turn this back to true in prod
show_admin_bar(false);

//for page titles
add_theme_support('title-tag');

///


/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
// function blanco_setup() {
//     // Add support for editor styles.
//     add_theme_support( 'editor-styles' );

//   // Enqueue editor styles.
//   add_editor_style( 'style/style-custom.css' );
// }
// add_action( 'after_setup_theme', 'blanco' );



// CSS
require_once( get_template_directory() . '/functions/fn-css.php' );

// JS
require_once( get_template_directory() . '/functions/fn-js.php' );

// MENUS
require_once( get_template_directory() . '/functions/fn-menus.php' );

// BLOG and EXCERPT
require_once( get_template_directory() . '/functions/fn-blog.php' );

//GUTENBERG BLOCKS
require_once( get_template_directory() . '/functions/fn-blocks.php' );

