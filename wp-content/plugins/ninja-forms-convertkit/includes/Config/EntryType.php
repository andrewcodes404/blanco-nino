<?php

if (!defined('ABSPATH'))
    exit;
/*
 * $key is the location value (called entry_type in all the code)
 * 
 * Nested array is list of all acceptable uses for that location value
 */
return array(
    'none'=>array(
        'address'
    ),
    'work' => array(
        'email',
        'phone',
        'im',
        'webaddress',
    ),
    'home' => array(
        'email',
        'phone',
    ),
    'direct' => array(
        'phone',
    ),
    'other' => array(
        'email',
        'phone',
        'im',
        'webaddress',
    ),
    'mobile' => array(
        'phone',
    ),
    'fax' => array(
        'phone',
    ),
    'skype' => array(
        'phone',
    ),
    'website' => array(
        'url'
    ),
    'blog' => array(
        'url'
    ),
    'twitter' => array(
        'url'
    ),
    'linkedin' => array(
        'url'
    ),
    'facebook' => array(
        'url'
    ),
    'google_plus' => array(
        'url'
    ),
);
