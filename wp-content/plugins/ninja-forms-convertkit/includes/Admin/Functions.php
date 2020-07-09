<?php

/**
 * Static functions
 */
class NF_OnepageCRM_Functions {


    
    /**
     * Extracts the field map data for the given Fields to Extracts and builds
     * a nested array of the data
     * 
     * @param array $complete_field_map_data Entire field map data from Action
     * @param array $fields_to_extract Array of keys for fields to extract from repeater
     * 
     * @return array Array of field map data as a nested array
     */
    public static function extract_field_map_data($complete_field_map_data, $fields_to_extract) {

        $field_map_data = array(); // initialize

        foreach ($complete_field_map_data as $field_array) {

            $array = array();

            foreach ($fields_to_extract as $field) {

                $array[$field] = $field_array[$field];
            }
            $field_map_data[] = $array;
        }

        return $field_map_data;
    }

    /**
     * Formats an option array for the drop down menu in the option repeater
     * 
     * 
     * @param type $field_map_array
     * @return array Drop down version of array for the option repeater
     */
    public static function build_field_map_dropdown($field_map_array) {

        foreach ($field_map_array as $key => $label_map_array) {

            $field_map_dropdown[] = array(
                'label' => $label_map_array['label'],
                'value' => $key,
            );
        }

        return $field_map_dropdown;
    }

    /**
     * Build the entry type dropdown for the option repeater
     * 
     * Uses only the array keys from the list
     * @param $entry_type_array array Entry types for the field
     * @return array Drop down array for entry type
     */
    public static function build_entry_type_dropdown($entry_type_array) {

        $entry_type_dropdown = array(); // initialize

        $array_keys = array_keys($entry_type_array);

        foreach ($array_keys as $key) {

            $entry_type_dropdown[] = array(
                'label' => $key,
                'value' => $key,
            );
        }

        return $entry_type_dropdown;
    }

    /**
     * Extracts the advanced codes from the ninja_forms_settings option
     * 
     * @return array Advanced codes array
     */
    public static function extract_advanced_codes() {

        $settings_key = NF_OnepageCRM_Constants::ADVANCED_CODES_KEY;

        $advanced_codes_array = array(); //initialize

        $nf_settings_array = get_option(' ninja_forms_settings');

        if (isset($nf_settings_array[$settings_key])) {

            $advanced_codes_setting = $nf_settings_array[$settings_key];

            $advanced_codes_array = array_map('trim', explode(',', $advanced_codes_setting));
        }

        return $advanced_codes_array;
    }

    /**
     * Applies all configured filters from the Advanced Command setting
     * 
     * @param type $single_filter Apply a specific filter on demand
     * @return none
     */
    public static function add_advanced_command_filters($single_filter = '') {

        if (defined('DISABLE_NFONEPAGECRM_ADVANCED_COMMANDS')) {

            return;
        }

        $advanced_codes_array = self::extract_advanced_codes();

        if (0 === strlen($single_filter)) {

            $filters = include NFONEPAGECRM_PLUGIN_DIR . 'includes/Config/AdvancedCommandFilters.php';
        } else {

            $full_config = include NFONEPAGECRM_PLUGIN_DIR . 'includes/Config/AdvancedCommandFilters.php';

            $filters[$single_filter] = $full_config[$single_filter];
        }

        foreach ($advanced_codes_array as $code) {

            if (array_key_exists($code, $filters)) {

                $filter = $filters[$code]['filter'];
                $callback = $filters[$code]['filter_callback'];

                add_filter($filter, 'NF_OnepageCRM_Functions::' . $callback);
            }
        }
    }

    /**
     * Returns the process to use by checking if alternate processing is enabled 
     * and if the alternate process is activated
     * Accepted values: 
     * alternate_processing_enabled , form_processing , validation_object
     * @param string process  
     * 
     */
    public static function get_alternate_process_value($process = 'alternate_processing_enabled') {

        $return_array = array(
            'alternate_processing_enabled' => false,
            'form_processing' => 'OnepageProcessForm',
            'validation_object' => 'OnepageValidateFields',
        );

        if (!array_key_exists($process,$return_array)) {

            $process = 'alternate_processing_enabled';
        }

        $advanced_commands = NF_OnepageCRM_Functions::extract_advanced_codes();

        $alternate_processing_command = 'enable_alternate_processing';

        if (!in_array($alternate_processing_command, $advanced_commands)) {

            return $return_array[$process];
        }

        $return_array['alternate_processing_enabled'] = true;

        switch ($process) {

            case 'form_processing':
                if (class_exists('OnepageProcessFormAlternate')) {

                    $return_array['form_processing'] = 'OnepageProcessFormAlternate';
                }
                break;

            case 'validation_object':
                if (class_exists('OnepageValidateFieldsAlternate')) {

                    $return_array['validation_object'] = 'OnepageValidateFieldsAlternate';
                }
                break;
        }

        return $return_array[$process];
    }

    /**
     * Strips HTML tags, usually from text areas
     * 
     * @param mixed $field_data
     */
    public static function remove_html_tags($field_data) {

        $stripped_response = $field_data; // initialize

        $keep_tags = apply_filters('nfonepagecrm_keep_html_tags', FALSE);

        if (!$keep_tags) {
            $decoded = html_entity_decode($field_data);
            $stripped = wp_strip_all_tags($decoded);
            $stripped_response = esc_html($stripped);
        }

        return $stripped_response;
    }

    public static function return_true() {

        return true;
    }

}

add_action('init', 'NF_OnepageCRM_Functions::add_advanced_command_filters');
