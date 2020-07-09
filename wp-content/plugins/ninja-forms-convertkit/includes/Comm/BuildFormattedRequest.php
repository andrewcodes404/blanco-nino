<?php

class OnepageBuildFormattedRequest {

    /**
     * Section for which the request is to be made
     * @var string 
     */
    protected $module;

    /**
     * Node name lookup for building the array
     * @var string 
     */
    protected $node_lookup;

    /**
     * Request array for a section
     * @var array 
     */
    protected $module_request;

    /**
     * The JSON array being built
     * @var array 
     */
    protected $json_array;

    /**
     *
     * @var string Final array for communication
     */
    protected $formatted_request_array = array(); // initialize

    /**
     * Given a module name and structure array data, builds the JSON object
     * 
     * Builds only one module's request at a time in case downstream modules
     * need linking id from parent module
     * 
     * @param string $module Name of the Onepage section
     * @param array $module_request Structured array of the section data to build into JSON object
     */
    public function __construct($module, $module_request) {

        $this->module = $module;

        $this->module_request = $module_request;

        $this->node_lookup = NF_OnepageCRM::config('NodeLookup');

        $this->iterate_section_request();
    }

    protected function iterate_section_request() {

        foreach ($this->module_request as $map_instructions => $data) {

            $instruction = explode('.', $map_instructions);

            $top = $instruction[0];

            switch ($top) {
                case 'email':
                case 'phone':
                case 'url':
                    $this->add_single_with_location($top, $data);

                    break;

                case 'address':
                    $this->add_address_node($top, $data);
                    break;
                
                default:
                    $this->add_single_element_node($top, $data);
            }
        }
    }

    protected function add_single_element_node($top, $data) {

        $node_name = $top;

        $this->json_array[$node_name] = $data;
    }

    protected function add_address_node($top, $data) {

        $node_name = $this->node_lookup($top);

        foreach ($data as $location => $address_array) {

            $this->json_array[$node_name][] = $address_array;
        }
    }

    /**
     * Adds nodes like phone, email, url that come in as array keyed on location
     * 
     * @param strip $top
     * @param array $data
     */
    protected function add_single_with_location($top, $data) {

        $node_name = $this->node_lookup($top);

        foreach ($data as $location => $value) {

            $array = array(
                'type' => $location,
                'value' => $value,
            );

            $this->json_array[$node_name][] = $array;
        }
    }

    /**
     * Returns the API node name for a selected node
     * 
     * Defaults to node if no node name in lookup
     * 
     * @param string $node
     */
    protected function node_lookup($node) {

        if (isset($this->node_lookup[$node])) {

            $node_name = $this->node_lookup[$node];
        } else {

            $node_name = $node;
        }
        return $node_name;
    }

    /**
     * Returns the generated JSON array
     * @return array 
     */
    public function get_json_array() {

        return $this->json_array;
    }

    /**
     * Returns the generated JSON object
     * 
     * @return object 
     */
    public function get_json_request() {

        return json_encode($this->json_array);
    }

}
