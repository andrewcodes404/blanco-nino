<?php

if (!defined('ABSPATH'))
    exit;
/*
 * {advanced_command} => array(
 *      {filter_name} ,
 *      {function_that_returns_value_for_command}
 * )
 */
return array(
    'support' => array(
        'filter' => 'nfonepagecrm_display_support',
        'filter_callback' => 'return_true',
    ),
    'keep_html_tags'=>array(
        'filter'=>'nfonepagecrm_keep_html_tags',
        'filter_callback' => 'return_true',
    ),
    'debug_mode'=>array(
        'filter'=>'nfonepagecrm_debug_mode',
        'filter_callback'=>'return_true',
    ),
);

