<?php

/**
 * Analyze the Onepage response and return all pertinent information
 *
 * 
 */
class OnepageHandleResponse {

    /**
     * The full response from Onepage, regardless of outcome
     * 
     * Can include WP errors, Onepage rejection errors, success messages,
     * IDs for newly created sections, data requests
     * @var mixed Full response from Onepage 
     */
    protected $full_response;

    /**
     * Body of the response in JSON for data extraction
     * @var Object 
     */
    protected $body_json;

    /**
     * The body data, made available for additional processing
     * @var object
     */
    protected $body_json_data;
    
    /**
     * Array of boolean flags to describe the status
     * @var array 
     */
    protected $flag;

    /**
     * Array of readable summaries of communication result
     * @var array 
     */
    protected $response_summary = array();

    /**
     * The response code
     * @var integer 
     */
    protected $code;

    /**
     * The response message
     * @var string 
     */
    protected $message;

    /**
     * array of IDs of the newly created entities within a module
     * @var array 
     */
    protected $new_id = array();

    /**
     * Response from Onepage
     * @param array|object $full_response 
     */
    public function __construct($full_response) {

        $this->initialize_variables();

        $this->full_response = $full_response;

        if (is_wp_error($this->full_response)) {

            $this->handle_wp_error();
            return;
        }
        $this->body_json = json_decode($this->full_response['body']);

        $this->extract_code_message();

        $this->handle_codes();
    }

    protected function handle_codes() {

        switch ($this->code) {

            case 0: // OK
                $this->extract_0_data();
                break;

            case 400: // Incomplete request data
                $this->build_400_message();
                break;

            case 401: // Unauthorized
                $this->build_401_message();
                break;

            default:
                $this->build_unhandled_responses();
                break;
        }
    }

    /**
     * Extract the response from a successful section entry
     */
    protected function extract_0_data() {


        $this->message = $this->body_json->message;

        $created_at = date("Y-m-d\TH:i:sP", $this->body_json->timestamp);

        $this->response_summary[] = __('Successfully created at: ', 'ninja-forms-onepage-crm') . $created_at;

        if (isset($this->body_json->data)) {

            $this->body_json_data = $this->body_json->data;
            
            $this->build_new_id_array();
        }
    }

    /**
     * Build 400-Bad Request message
     */
    protected function build_400_message() {

        $this->error = $this->body_json->error_name;
        $this->message = $this->body_json->error_message;

        $this->response_summary[] = $this->error . ' - ' . $this->message;
        
        $this->append_contextual_help($this->message,$this->error);
        
        if (isset($this->body_json->errors) && !empty($this->body_json->errors)) {

            foreach ($this->body_json->errors as $key => $error) {
                $this->response_summary[] = $key . ' - ' . $error;
                $this->append_contextual_help($error,$key);
            }
        }
    }

    /**
     * Build 401-Unauthorized Messages
     */
    protected function build_401_message() {

        $message = __('Please check your username and password.  One of them is may be incorrect.', 'ninja-forms-onepage-crm');
        $message .= '<br />' . $this->code . ' ' . $this->message;

        $this->response_summary[] = $message;
    }

    /**
     * Build response for unhandled response codes
     */
    protected function build_unhandled_responses() {

        $this->response_summary[] = $this->code . ' ' . $this->message;
    }

    /**
     * Extract the code and message from the response's "RESPONSE" 
     */
    protected function extract_code_message() {

        $this->code = $this->body_json->status;
        $this->message = $this->body_json->message;

    }

    protected function initialize_variables() {

        $this->flag = array(// initialize
            'wp_error' => false,
        );

    }

    protected function handle_wp_error() {

        $this->flag['wp_error'] = true;

        $code = $this->full_response->get_error_code();

        $message = $this->full_response->get_error_message();

        $this->response_summary[] = $code . ' - ' . $message;
    }
    /**
     * Appends contextual help to the response summary
     * 
     * @param string $string
     * @param string $detail
     */
    protected function append_contextual_help($string, $detail = '') {

        $contextual_help = NF_OnepageCRM_Support::help($string, $detail);

        if ($contextual_help) {

            $this->response_summary[] = $contextual_help;
        }
    }
       
    /**
     * Builds the new_id array of module entries and their ids
     */
   protected function build_new_id_array() {

        foreach ($this->body_json_data as $module => $response) {

            if (isset($response->id)) {

                $this->new_id[(string) $module] = (string) $response->id;
            }
        }
    }

    
    /**
     * Returns response summary
     * 
     * @return boolean|array
     */
    public function get_response_summary() {

        if(!isset($this->response_summary)){
            
            return false;
        }else{
            
        return $this->response_summary;
        }
    }

    /**
     * Returns array of new id integers, keyed on module
     * @return array 
     */
    public function get_new_id() {

        if (!isset($this->new_id)) {

            return false;
        } else {
            
            return $this->new_id;
        }
    }

    /**
     * Returns the JSON data
     * 
     * @return boolean|object
     */
    public function get_body_json_data(){
        
        if(!isset($this->body_json_data)){
            
            return false;
        }else{
            
            return $this->body_json_data;
        }
    }
}
