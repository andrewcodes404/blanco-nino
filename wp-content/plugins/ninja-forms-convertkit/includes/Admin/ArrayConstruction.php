<?php

/**
 * Reconstructs incoming data into the structure required for use
 *
 */
class NF_OnepageCRM_ArrayConstruction {

    /**
     * Constructs the field map data for the given Fields to Extracts and builds
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
     * @param type $field_map_array label is displayed, value is the lookup key
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
     * Builds an array of entry types using the array key as both key and value
     * @param array $entry_type_array Configured EntryType
     * @return array
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


    /*
     * -----------------------
     * 
     * Specialized methods
     * 
     * -----------------------
     */

    /**
     * Builds field map dropdown array of incoming custom fields
     * 
     * @param array $custom_field_array Custom field array
     * @return array Field map dropdown array of custom fields
     */
    public static function build_custom_field_map_dropdown($custom_field_array) {

        foreach ($custom_field_array as $custom_field) {

                $field_map_dropdown[]=array(
                    'label'=> $custom_field['custom_field']['name'],
                    'value'=>(string)$custom_field['custom_field']['id'],
                );
        }

        return $field_map_dropdown;
    }

    /**
     * 
     * @param array $user_array Array of user data from CRM
     * @param string $prefix Prefix for merge tag; ensures uniqueness
     */
    public static function build_crm_user_merge_tag_array($user_array, $prefix = 'prefix') {

        $merge_tag_array = array();

        foreach ($user_array as $user) {
            $id = $user['id'];
            $label = $user['label'];

            $sanitized_label = sanitize_title($label);

            $merge_tag_array[(string) $id] = array(
                'id' => (string) $id,
                'tag' => '{' . $prefix . '_user:' . $sanitized_label . '}',
                'label' => $label,
                'callback' => 'user_' . $id,
            );

            return $merge_tag_array;
        }
    }
    
}
