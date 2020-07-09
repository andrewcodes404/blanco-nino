<?php

if (!defined('ABSPATH'))
    exit;
/**
 * Sections in Onepage for which to build data and create with API requests
 * 
 */
return array(
    'contact' => array(
        'endpoint' => 'contacts.json',
        'parent_lookup' => '',
        'parent_endpoint' => '',
        'required_fields' => array(
            'last_name' => 'NinjaFormPlaceholder'
        ),
    ),
    'action' => array(
        'endpoint' => 'actions.json',
        'parent_lookup' => 'contact',
        'parent_endpoint' => 'contacts',
        'required_fields' => array(
            'text' => 'NinjaFormPlaceholder'
        ),
    ),
    'deal' => array(
        'endpoint' => 'deals.json',
        'parent_lookup' => 'contact',
        'parent_endpoint' => 'contacts',
        'required_fields' => array(
            'name' => 'NinjaFormPlaceholder'
        ),
    ),
    'note' => array(
        'endpoint' => 'notes.json',
        'parent_lookup' => 'contact',
        'parent_endpoint' => 'contacts',
        'required_fields' => array(),
    ),
    'custom_fields' => array(
        'endpoint' => 'custom_fields.json',
        'parent_lookup' => '',
        'parent_endpoint' => '',
        'required_fields' => array()
    ),
    'users' => array(
        'endpoint' => 'users.json',
        'parent_lookup' => '',
        'parent_endpoint' => '',
        'required_fields' => array()
    ),
);


