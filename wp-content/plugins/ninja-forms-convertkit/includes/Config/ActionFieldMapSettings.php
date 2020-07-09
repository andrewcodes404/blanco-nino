<?php

if (!defined('ABSPATH'))
    exit;

return array(
    /*
      |--------------------------------------------------------------------------
      | Onepage Field Map
      |--------------------------------------------------------------------------
     */
    NF_OnepageCRM_Constants::FIELD_MAP_REPEATER_KEY => array(
        'name' => NF_OnepageCRM_Constants::FIELD_MAP_REPEATER_KEY,
        'type' => 'option-repeater',
        'label' => __('Onepage Field Map', 'ninja-forms-onepage-crm') . ' <a href="#" class="nf-add-new">' . __('Add New') . '</a>',
        'width' => 'full',
        'group' => 'primary',
        'tmpl_row' => 'nf-tmpl-nfonepagecrm-custom-field-map-row',
        'value' => array(),
        'columns' => array(
            'form_field' => array(
                'header' => __('Form Field', 'ninja-forms-onepage-crm'),
                'default' => '',
                'options' => array() // created by Merge Tags or text
            ),
            'field_map' => array(
                'header' => __('Onepage Field', 'ninja-forms-onepage-crm'),
                'default' => '',
                'options' => array(), // created on constuction
            ),
            'entry_type' => array(
                'header' => __('Location', 'ninja-forms-onepage-crm'),
                'default' => '',
                'options' => array(), // created on constuction
            ),
        ),
    ),
);


