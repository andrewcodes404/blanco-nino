<?php

if (!defined('ABSPATH'))
    exit;

return apply_filters('nfonepagecrm_plugin_settings', array(
    /*
      |--------------------------------------------------------------------------
      | Onepage API Authentication Token and Subdomain
      |--------------------------------------------------------------------------
     */
    NF_OnepageCRM_Constants::USERNAME => array(
        'id' => NF_OnepageCRM_Constants::USERNAME,
        'type' => 'textbox',
        'label' => __('User Name', 'ninja-forms-onepage-crm'),
    ),
    NF_OnepageCRM_Constants::PASSWORD => array(
        'id' => NF_OnepageCRM_Constants::PASSWORD,
        'type' => 'password',
        'label' => __('Password', 'ninja-forms-onepage-crm'),
    ),
    /*
      |--------------------------------------------------------------------------
      | Test connection and finalize setup
      |--------------------------------------------------------------------------
     */
    NF_OnepageCRM_Constants::CONNECTION_VERIFICATION => array(
        'id' => NF_OnepageCRM_Constants::CONNECTION_VERIFICATION,
        'type' => 'html',
        'label' => __('API Connection', 'ninja-forms-onepage-crm'),
        'html' => '', // created on construction  
    ),
    /*
      |--------------------------------------------------------------------------
      | Communication Status
      |--------------------------------------------------------------------------
     */
    NF_OnepageCRM_Constants::RESPONSE_SUMMARY => array(
        'id' => NF_OnepageCRM_Constants::RESPONSE_SUMMARY,
        'type' => 'html',
        'label' => __('Communication Summary', 'ninja-forms-onepage-crm'),
        'html' => '', // created on construction          
    ),
    NF_OnepageCRM_Constants::REFRESH_ACCOUNT => array(
        'id' => NF_OnepageCRM_Constants::REFRESH_ACCOUNT,
        'type' => 'html',
        'label' => __('Refresh Account Data', 'ninja-forms-onepage-crm'),
        'html' => '', // created on construction    
    ),
    /*
      |--------------------------------------------------------------------------
      | Advanced commands
      |--------------------------------------------------------------------------
     */
    NF_OnepageCRM_Constants::ADVANCED_CODES_KEY => array(
        'id' => NF_OnepageCRM_Constants::ADVANCED_CODES_KEY,
        'type' => 'textbox',
        'label' => __('Advanced Commands', 'ninja-forms-onepage-crm'),
    ),
    /*
      |--------------------------------------------------------------------------
      | Support
      |--------------------------------------------------------------------------
     */
    NF_OnepageCRM_Constants::FIELD_MAP_DATA => array(
        'id' => NF_OnepageCRM_Constants::FIELD_MAP_DATA,
        'type' => 'html',
        'label' => __('Submission Data', 'ninja-forms-onepage-crm'),
        'html' => '', // created on construction 
    ),
    NF_OnepageCRM_Constants::REQUEST_ARRAY => array(
        'id' => NF_OnepageCRM_Constants::REQUEST_ARRAY,
        'type' => 'html',
        'label' => __('Structured Array', 'ninja-forms-onepage-crm'),
        'html' => '', // created on construction 
    ),
    NF_OnepageCRM_Constants::FORMATTED_REQUEST => array(
        'id' => NF_OnepageCRM_Constants::FORMATTED_REQUEST,
        'type' => 'html',
        'label' => __('Formatted Request', 'ninja-forms-onepage-crm'),
        'html' => '', // created on construction           
    ),
    NF_OnepageCRM_Constants::FULL_RESPONSE => array(
        'id' => NF_OnepageCRM_Constants::FULL_RESPONSE,
        'type' => 'html',
        'label' => __('Full Response', 'ninja-forms-onepage-crm'),
        'html' => '', // created on construction        
    ),
        ));
