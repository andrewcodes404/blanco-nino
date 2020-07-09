<?php

if (!defined('ABSPATH'))
    exit;
/*
 * $key is a READABLE and unique value that is sent with the form submission
 * It is stored as the "field_map" setting in the field mapping option repeater
 * 
 * 
 * $label is the i10n descriptive version of the field map, used to help the
 * form designer select the desired field map location
 * 
 * $map_instructions is a period-delimited instruction set used to build the
 * Array from which the formatted request is built
 */
return array(
    'None' => array(
        'label' => __('None', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'none',
        'request_array_structure' => 'none',
        'validation_functions' => array(),
    ),
    'ContactDivider' => array(
        'label' => __('<-- Contact Fields -->', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'none',
        'request_array_structure' => 'none',
        'validation_functions' => array(),
    ),
    'Title' => array(
        'label' => __('Title', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.title',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'FirstName' => array(
        'label' => __('First Name', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.first_name',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'LastName' => array(
        'label' => __('Last Name', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.last_name',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'ContactJobTitle' => array(
        'label' => __('Job Title', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.job_title',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'ContactBackground' => array(
        'label' => __('Contact Background', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.background',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'ContactStatus' => array(
        'label' => __('Contact\'s Status', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.status',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    /*
    'ContactTags' => array(
        'label' => __('Contact\'s Tags', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.job_title',
        'request_array_structure' => 'single_element', // investigate structure
        'validation_functions' => array(),
    ),
     * 
     */
    'ContactStarred' => array(
        'label' => __('Star this Contact', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.starred',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(
            'force_boolean'
        ),
    ),
    'CompanyName' => array(
        'label' => __('Company Name', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.company_name',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'CompanySize' => array(
        'label' => __('Company Size', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.company_size',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'ContactEmailAddress' => array(
        'label' => __('Email Address', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.email',
        'request_array_structure' => 'single_element_entry_type',
        'validation_functions' => array(),
    ),
    'ContactURL' => array(
        'label' => __('URL', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.url',
        'request_array_structure' => 'single_element_entry_type',
        'validation_functions' => array(),
    ),
    'ContactPhone' => array(
        'label' => __('Contact Phone', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.phone',
        'request_array_structure' => 'single_element_entry_type',
        'validation_functions' => array(),
    ),
    'StreetAddress-Contact' => array(
        'label' => __('Contact Street Address', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.address.address',
        'request_array_structure' => 'multiple_element_entry_type',
        'validation_functions' => array(),
    ),
    'Contact-City' => array(
        'label' => __('Contact City', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.address.city',
        'request_array_structure' => 'multiple_element_entry_type',
        'validation_functions' => array(),
    ),
    'Contact-State' => array(
        'label' => __('Contact State', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.address.state',
        'request_array_structure' => 'multiple_element_entry_type',
        'validation_functions' => array(),
    ),
    'Contact-Country' => array(
        'label' => __('Contact Country', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.address.country',
        'request_array_structure' => 'multiple_element_entry_type',
        'validation_functions' => array(),
    ),
    'Contact-Zip' => array(
        'label' => __('Contact Zip', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'contact.address.zip',
        'request_array_structure' => 'multiple_element_entry_type',
        'validation_functions' => array(),
    ),
    'ActionDivider' => array(
        'label' => __('<-- Action Fields -->', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'none',
        'request_array_structure' => 'none',
        'validation_functions' => array(),
    ),
    'Action-Text' => array(
        'label' => __('Action Text', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'action.text',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(
            'limit_140_characters',
        ),
    ),
    'Action-Date' => array(
        'label' => __('Action Date', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'action.date',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(
            'convert_date_interval',
            'format_date',
        ),
    ),
    'Action-Status' => array(
        'label' => __('Action Status', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'action.status',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(
            'allowed_action_status',
        ),
    ),
    'DealDivider' => array(
        'label' => __('<-- Deal Fields -->', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'none',
        'request_array_structure' => 'none',
        'validation_functions' => array(),
    ),
    'Deal-Name' => array(
        'label' => __('Deal Name', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'deal.name',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(
            'limit_60_characters',
        ),
    ),
    'Deal-Text' => array(
        'label' => __('Deal Text', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'deal.text',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'Deal-Amount' => array(
        'label' => __('Deal Amount', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'deal.amount',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(
            'force_float',
        ),
    ),
    'Deal-Months' => array(
        'label' => __('Deal Months', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'deal.months',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(
            'force_integer',
        ),
    ),
    'Deal-Status' => array(
        'label' => __('Deal Status', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'deal.status',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'Deal-ExpectedCloseDate' => array(
        'label' => __('Deal Expected Close Date', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'deal.expected_close_date',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(
            'convert_date_interval',
            'format_date',
        ),
    ),
    'NoteDivider' => array(
        'label' => __('<-- Note Fields -->', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'none',
        'request_array_structure' => 'none',
        'validation_functions' => array(),
    ),
    'Note-Text' => array(
        'label' => __('Note Text', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'note.text',
        'request_array_structure' => 'single_element',
        'validation_functions' => array(),
    ),
    'CustomFieldsDivider' => array(
        'label' => __('<-- Custom Fields -->', 'ninja-forms-onepage-crm'),
        'map_instructions' => 'none',
        'request_array_structure' => 'none',
        'validation_functions' => array(),
    ),
);
