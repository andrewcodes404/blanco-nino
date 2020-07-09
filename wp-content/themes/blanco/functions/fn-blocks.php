<?php
add_action('acf/init', 'my_register_blocks');
function my_register_blocks()
{

    // check function exists
    if (function_exists('acf_register_block')) {

        // register blocks here

        acf_register_block(array(
            'name' => 'front-page-poster',
            'title' => __('Blanco Front Page Poster'),
            'render_template' => 'blocks/block_front-page-poster.php',
            'category' => 'layout',
            'icon' => 'format-image',
            'mode' => 'edit',
            'keywords' => array('blanco', 'section'),
        ));

        acf_register_block(array(
            'name' => 'row-col-2',
            'title' => __('Blanco Row with 2 Columns'),
            'render_template' => 'blocks/block_row-col-2.php',
            'category' => 'layout',
            'icon' => 'format-image',
            'mode' => 'edit',
            'keywords' => array('blanco', 'section'),
        ));

        acf_register_block(array(
            'name' => 'full-width',
            'title' => __('Blanco Full Width Image'),
            'render_template' => 'blocks/block_full-width.php',
            'category' => 'layout',
            'icon' => 'format-gallery',
            'mode' => 'edit',
            'keywords' => array('blanco', 'split', 'section'),
        ));

        acf_register_block(array(
            'name' => 'video',
            'title' => __('Blanco Video'),
            'render_template' => 'blocks/block_video.php',
            'category' => 'layout',
            'icon' => 'format-gallery',
            'mode' => 'edit',
            'keywords' => array('blanco', 'split', 'section'),
        ));


        acf_register_block(array(
            'name' => 'info_graphic',
            'title' => __('Blanco Info Graphic'),
            'render_template' => 'blocks/block_info_graphic.php',
            'category' => 'layout',
            'icon' => 'format-image',
            'mode' => 'edit',
            'keywords' => array('blanco', 'section'),
        ));

        acf_register_block(array(
            'name' => 'product',
            'title' => __('Blanco Product'),
            'render_template' => 'blocks/block_product.php',
            'category' => 'layout',
            'icon' => 'format-image',
            'mode' => 'edit',
            'keywords' => array('blanco', 'section'),
        ));
    }
}
