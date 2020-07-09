<?php

class OnepageBuildRequestArray {

    /**
     * Action data from form submission used to build request array
     */
    protected $field_map_data;

    /**
     * Array of fields in the repeater to be extracted
     * 
     * Using a config file to maintain a single location
     * 
     * 'form_field'
     * 'field_map'
     * 'entry_type'
     */
    protected $fields_to_extract;

    /**
     * The configured lookup array for field maps
     * 
     * $key is a READABLE and unique value that is sent with the form submission
     * 
     * $label is the i10n descriptive version of the field map, used to help the
     * form designer select the desired field map location
     * 
     * $map_instructions is a period-delimited instruction set used to build the
     * array from which the XML is built
     */
    protected $field_map_lookup;

    /**
     * Entry types and the entries that use them in a nested array
     * 
     * @var array 
     */
    protected $entry_type;

    /**
     * Structured array into which all form data is placed.
     * 
     * @var array
     */
    protected $request_array;

    /**
     * Builds request array from the field map data
     * 
     * Cycles through the field map data provided by the FieldMapArray class
     * and builds the request array structure needed to construct the formatted
     * request.
     */
    public function __construct($field_map_data) {

        $this->field_map_data = $field_map_data;

        $this->fields_to_extract = NF_OnepageCRM::config('FieldsToExtract');

        $this->field_map_lookup = NF_OnepageCRM()->get_field_map_array();

        $this->entry_type = NF_OnepageCRM()->get_entry_type();

        $this->request_array = array(); // initialize

        $this->iterate_form_submission();
    }

    /*
     * Interate through each form field submission data
     */

    protected function iterate_form_submission() {

        foreach ($this->field_map_data as $field_data) {// iterate through each mapped field
            $map_args = array();

            foreach ($this->fields_to_extract as $field_to_extract) { // iterate through each column in the repeater
                if (isset($field_data[$field_to_extract])) {

                    $map_args[$field_to_extract] = $field_data[$field_to_extract];
                } else {

                    continue; // if any value isn't set, move on to next field
                }
            }

            $configured_map_args = $this->retrieve_field_args($map_args);

            $validated_map_args = $this->validate_field($configured_map_args);

            $this->insert_map_args_into_request_array($validated_map_args);
        }
    }

    /**
     * Uses "field_map" key to pull map instructions from the configured FieldMapArray
     * 
     * @param array $map_args
     * @return array
     */
    protected function retrieve_field_args($map_args) {

        $field_map_key = $map_args['field_map'];

        if(!isset($this->field_map_lookup[$field_map_key]['map_instructions'])){
                                
            $map_args['field_map_instructions'] = 'contact.'.$field_map_key;

            $map_args['request_array_structure'] = 'custom_field';

            $map_args['validation_functions'] = '';
        }else{
            
            $map_args['field_map_instructions'] = $this->field_map_lookup[$field_map_key]['map_instructions'];

            $map_args['request_array_structure'] = $this->field_map_lookup[$field_map_key]['request_array_structure'];

            $map_args['validation_functions'] = $this->field_map_lookup[$field_map_key]['validation_functions'];
        }
        return $map_args;
    }

    protected function validate_field($map_args) {

        $value_in = $map_args['form_field'];

        if ($map_args['validation_functions']) {

            $temp = $value_in;

            $validation_object = NF_OnepageCRM_Functions::get_alternate_process_value('validation_object');

            foreach ($map_args['validation_functions'] as $function_call) {

                if(!method_exists($validation_object,$function_call)){
                    
                    continue;
                }
                $temp = call_user_func(array($validation_object, $function_call), $temp);
            }

            $value_out = $temp;
        } else {
            $value_out = $value_in;
        }

        $map_args['form_field'] = $value_out;

        return $map_args;
    }
      
    
    /**
     * Inserts the form field in the request array as specified in the configuration
     * @param array $map_args
     */
    protected function insert_map_args_into_request_array($map_args) {

        $form_field = $map_args['form_field'];

        // instructions pulled from Field Map Array
        $map_instructions = $map_args['field_map_instructions'];

        // Switch out with validating function to ensure valid entry type is set
        // explode the instructions into an array of instructions
        $instruction_array = explode('.', $map_instructions);

        $module = $instruction_array[0];
        $field_name = $instruction_array[1];
        $request_array_structure = $map_args['request_array_structure'];
        $entry_type = $this->validate_entry_type($field_name, $map_args['entry_type']);

        // select processing method by element
        switch ($request_array_structure) {

            case 'single_element_entry_type':

                $this->request_array[$module][$field_name][$entry_type] = $form_field;

                break;

            case 'single_element':

                $this->request_array[$module][$field_name] = $form_field;
                break;

            case 'multiple_element_entry_type':

                $sub_element = $instruction_array[2];

                $this->request_array[$module][$field_name][$entry_type][$sub_element] = $form_field;
                break;

            case 'custom_field':
                
                $custom_field = (object)json_decode(json_encode(array('id'=>$field_name)));
                
                $this->request_array[$module]['custom_fields'][]=array(
                    'custom_field'=>$custom_field,
                    'value'=>$form_field,
                );
                
                break;
            
            default:
                $this->request_array['bad_data_dump'][] = array($form_field, $map_instructions, $entry_type,);
                break;
        }
    }

    /**
     * Ensures the entry type is valid for the given element
     * 
     * If the entry type doesn't have the given element in its array of valid
     * uses, cycle through each entry type to find the first valid one and use that
     * 
     * @param string $field_name 
     * @param string $incoming_entry_type Requested type specified in the action
     * @return string Validated entry_type
     */
    protected function validate_entry_type($field_name, $incoming_entry_type) {

        $validated_entry_type = 'Other'; // set default

        if (in_array($field_name, $this->entry_type[$incoming_entry_type])) {

            $validated_entry_type = $incoming_entry_type;
        } else {

            foreach ($this->entry_type as $type => $valid_elements_array) {

                if (in_array($field_name, $valid_elements_array)) {

                    $validated_entry_type = $type;
                    break;
                }
            }
        }

        return $validated_entry_type;
    }

    /**
     * Returns the request array as built from the form submission action
     * 
     * initialized as empty array on construct
     * 
     * @return array
     */
    public function get_request_array() {

        return $this->request_array;
    }

}
