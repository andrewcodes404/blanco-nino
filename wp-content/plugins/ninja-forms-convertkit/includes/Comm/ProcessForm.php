<?php

class OnepageProcessForm {

    /**
     * The submission data and field map, pulled from the complete action settings
     * 
     * @var array
     */
    protected $action_settings;

    /**
     * Submitted form data and action settings for the FieldsToExtract
     * @var array 
     */
    protected $field_map_data = array();

    /**
     * Field keys stored in the action that need to be extracted for handling
     * @var array 
     */
    protected $fields_to_extract;

    /**
     * Module configuration array
     * 
     * @var array
     */
    protected $module_config;

    /**
     * Full structured array extracted and build from the submission
     * @var array 
     * 
     * Array is keyed on each section with a nested array of the field data
     * for that given section
     */
    protected $request_array = array();

    /**
     * The JSON formatted request built from the request array
     * 
     * Stored as array keyed on module name; json-encoded version sent for request
     * @var array
     */
    protected $formatted_request = array();

    /**
     * Full response from each request, stored as array keyed on module
     * @var array
     */
    protected $full_response = array();

    /**
     * Human-readable responses
     * @var array
     */
    protected $response_summary = array();
    
    /**
     * Keyed IDs of newly created modules
     * @var array 
     */
    protected $new_id_array = array();

    /**
     * Iterates through the action settings to create an array of each field
     * map from the option repeater and builds a standard submission array
     * for building the request array
     * 
     * @param array $action_settings
     * @param integer $form_id
     * @param array $data
     * @return array
     */
    function __construct($action_settings, $form_id, $data) {

        $this->action_settings = $action_settings[NF_OnepageCRM_Constants::FIELD_MAP_REPEATER_KEY];

        $this->mise_en_place();

        $this->build_request_array();

        foreach ($this->module_config as $module => $config) {

            // check if module is in request, if not, continue to next
            if (!isset($this->request_array[$module])) {
                continue;
            }

            $this->merge_missing_required_fields($module);

            $this->build_formatted_request($module);

            $this->create_module($module);

            $this->process_response($module);
        }

        NF_OnepageCRM()->update_support_data();

        return $data;
    }

    /**
     * Load configuration files and classes, build and extract variables
     */
    protected function mise_en_place() {

        $this->load_process_classes();

        $this->module_config = NF_OnepageCRM()->get_module_config();

        // configure lookup list of fields to extract from form submission data
        $this->fields_to_extract = NF_OnepageCRM::config('FieldsToExtract');

        // isolate the action_data for the submission
        $this->extract_field_map_data($this->action_settings);

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::FIELD_MAP_DATA, $this->field_map_data);
    }

    /**
     * Builds the request array from the action settings
     */
    protected function build_request_array() {

        $request_instance = new OnepageBuildRequestArray($this->field_map_data);

        $this->request_array = $request_instance->get_request_array();

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::REQUEST_ARRAY, $this->request_array);
    }

    /**
     * Checks array against module config required fields and adds if missing
     */
    protected function merge_missing_required_fields($module) {

        if ((bool) $this->module_config[$module]['required_fields']) {

            $this->request_array[$module] = array_merge(
                    $this->module_config[$module]['required_fields'], $this->request_array[$module]);
        }
    }

    /**
     * Builds the JSON formatted request
     * @param string
     */
    protected function build_formatted_request($module) {

        NF_OnepageCRM()->update_support_data('debug');

        $formatted_request_object = new OnepageBuildFormattedRequest($module, $this->request_array[$module]);

        $this->formatted_request[$module] = $formatted_request_object->get_json_request();

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::FORMATTED_REQUEST, $this->formatted_request);
    }

    /**
     * Makes the request to add the module entry
     * @param string
     */
    protected function create_module($module) {

        NF_OnepageCRM()->update_support_data('debug');

        $create_module = new OnepageCreate($module, $this->formatted_request[$module], $this->new_id_array);

        $this->full_response[$module] = $create_module->get_full_response();

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::FULL_RESPONSE, $this->full_response); 
    }

    /**
     * Process the full response for updates and IDs
     * @param string $module
     */
    protected function process_response($module) {

        NF_OnepageCRM()->update_support_data('debug');

        $processed_response_object = new OnepageHandleResponse($this->full_response[$module]);

        $this->response_summary[$module] = $processed_response_object->get_response_summary();

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::RESPONSE_SUMMARY, $this->response_summary);

        if (empty($this->new_id_array)) {

            $this->new_id_array = $processed_response_object->get_new_id();
        } else {

            $this->new_id_array = array_merge($this->new_id_array, $processed_response_object->get_new_id());
        }

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::REQUEST_ARRAY, $this->request_array);
    }

    /**
     * Load classes required for processing
     */
    protected function load_process_classes() {

        NF_OnepageCRM::file_include('Comm', 'BuildRequestArray');
        NF_OnepageCRM::file_include('Comm', 'ValidateFields');
        NF_OnepageCRM::file_include('Comm', 'BuildFormattedRequest');
        NF_OnepageCRM::file_include('Comm', 'Create');
        NF_OnepageCRM::file_include('Comm', 'HandleResponse');
    }

    /**
     * Extracts the field map data for the given Fields to Extracts and builds
     * a nested array of the data
     * 
     * @param array $complete_field_map_data Entire field map data from Action
     */
    protected function extract_field_map_data($complete_field_map_data) {

        foreach ($complete_field_map_data as $field_array) {

            $array = array();

            foreach ($this->fields_to_extract as $field) {

                $array[$field] = $field_array[$field];
            }
            $this->field_map_data[] = $array;
        }
    }

}
