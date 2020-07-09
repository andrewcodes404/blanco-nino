<?php

class OnepageCreate {

    /**
     * Section into which new entity is created
     * @var string 
     */
    protected $module;

    /**
     * Incoming JSON request
     * @var string 
     */
    protected $json_request;

    /**
     * Keyed array of already-created modules and their IDs for linking
     * @var array
     */
    protected $new_id_array;

    /**
     * Configuration array keyed on module
     * @var array
     */
    protected $module_config;
    
    /**
     * Endpoint for request
     * @var string
     */
    protected $endpoint;
    
    /**
     * User name for Onepage account
     * @var string 
     */
    protected $username;

    /**
     * Password for Onepage account
     * @var string 
     */
    protected $password;

    /**
     * URL base address for requests
     * @var string 
     */
    protected $base_url;

    /**
     * Status of the authorization request
     * @var integer 
     */
    protected $auth_status;

    /**
     * Timestamp of last authorization communication
     * @var integer 
     */
    protected $timestamp;

    /**
     * Error message during authorization
     * @var string 
     * 
     */
    protected $auth_error_message;

    /**
     * User ID retrieved during authorization
     * @var string 
     */
    protected $uid;

    /**
     * Key retrieved during authorization
     * @var string 
     */
    protected $key;

    /**
     * Endpoint for login authorization
     */
    const LOGIN_ENDPOINT = 'login.json';

    /**
     * Creates a new module in Onepage
     * 
     * 
     * @param string $module Module for which the create request is made
     * @param string $json_request Incoming JSON request string
     */
    public function __construct($module, $json_request, $new_id_array = array()) {

        $this->module = $module;

        $this->json_request = $json_request;

        $this->new_id_array = $new_id_array;
        
        $this->module_config = NF_OnepageCRM::config('OnepageModules');
        
        $this->extract_credentials();

        $this->build_base_url();
        
        $this->build_endpoint();

        $this->authorize();

        $this->build_connection_verification_message();

        $this->make_request();
    }

    /**
     * Makes the authorization request and gets request credentials
     */
    protected function authorize() {

        $url = $this->base_url . self::LOGIN_ENDPOINT;

        $auth_array = array(
            'login' => $this->username,
            'password' => $this->password,
        );

        $json_data = json_encode($auth_array);

        $headers_array = array(
            'Content-Type' => 'application/json'
        );

        $args = array(
            'timeout' => 45,
            'redirection' => 0,
            'httpversion' => '1.0',
            'sslverify' => FALSE,
            'method' => 'POST',
            'headers' => $headers_array,
            'body' => $json_data,
        );


        $auth_response = wp_remote_post(
                $url, $args
        );

        if (is_wp_error($auth_response)) {

            $result = $auth_response;
            $this->auth_error_message = $result->get_error_message();
        } else {

            $body = $auth_response['body'];

            $result = json_decode($body);


            if (isset($result->error_message)) {
                
                $this->auth_error_message = $result->error_message;
            } else {
                
                $this->auth_status = $result->status;
                $this->timestamp = $result->timestamp;
                $this->uid = $result->data->user_id;
                $this->key = base64_decode($result->data->auth_key);
            }
        }
    }

    /**
     * Evaluates authorization response and updates the connection verification
     * 
     */
    protected function build_connection_verification_message() {
        if (0 < strlen($this->auth_error_message)) {

            NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::CONNECTION_VERIFICATION, array(
                'Last check: ' . date("Y-m-d\TH:i:s\Z"),
                $this->auth_error_message)
            );

            NF_OnepageCRM()->update_support_data();
            return;
        } else {
            NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::CONNECTION_VERIFICATION, array(
                'Last check: ' . date("Y-m-d\TH:i:s\Z", $this->timestamp),
                __('You are authorized to communicate', 'ninja-forms-onepage-crm') . ', ' . $this->username)
            );
            NF_OnepageCRM()->update_support_data('debug'); // remove this after full cycle is complete
        }
    }

    /**
     * Retrieves login credentials
     */
    protected function extract_credentials() {

        $credentials = NF_OnepageCRM()->get_credentials();

        if (!$credentials['valid']) {

            return; // add some support message
        } else {

            $this->username = $credentials[NF_OnepageCRM_Constants::USERNAME];

            $this->password = $credentials[NF_OnepageCRM_Constants::PASSWORD];
        }
    }

    /**
     * Makes request to create entry
     */
    protected function make_request() {

        $url = $this->base_url . $this->endpoint;

        $timestamp = $this->timestamp;
        $auth_data = array($this->uid, $timestamp, 'POST', sha1($url));
        $auth_data[] = sha1($this->json_request);

        $hash = hash_hmac('sha256', implode('.', $auth_data), $this->key);

        $headers_array = array(
            'Content-Type' => 'application/json',
            'X-OnepageCRM-UID' => $this->uid,
            'X-OnepageCRM-TS' => $timestamp,
            'X-OnepageCRM-Auth' => $hash,
        );

        $args = array(
            'timeout' => 45,
            'redirection' => 0,
            'httpversion' => '1.0',
            'sslverify' => FALSE,
            'method' => 'POST',
            'headers' => $headers_array,
            'body' => $this->json_request
        );


        $this->full_response = wp_remote_post(
                $url, $args
        );
    }

    /**
     * Build the base URL from the subdomain
     */
    protected function build_base_url() {

        $this->base_url = 'https://app.onepagecrm.com/api/v3/';
    }

    /**
     * Build the endpoint for a module request
     */
    protected function build_endpoint() {

        switch($this->module){
            case 'action':
            case 'deal':
            case 'note':    
                $parent = $this->module_config[$this->module]['parent_lookup'];
                
                if(isset($this->new_id_array[$parent])){
                    
                    $this->endpoint = $this->module_config[$this->module]['parent_endpoint'];
                    $this->endpoint .= '/'.$this->new_id_array[$parent].'/';
                    $this->endpoint .= $this->module_config[$this->module]['endpoint'];           
                }else{
                    $this->endpoint = $this->module_config[$this->module]['endpoint'];
                }
                break;
            
            default:
                $this->endpoint = $this->module_config[$this->module]['endpoint'];
        }
    }

    /**
     * Returns the full response array
     * @return mixed
     */
    public function get_full_response() {

        if (empty($this->full_response)) {
            return false;
        } else {

            return $this->full_response;
        }
    }

}
