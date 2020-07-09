<?php

class OnepageGetAccountData {

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
     * Get account data
     * 
     * 
     * @param string $module Module for which the request is made
     */
    public function __construct($module) {

        $this->module = $module;
        
        $this->module_config = NF_OnepageCRM::config('OnepageModules');
        
        $this->extract_credentials();

        $this->build_base_url();
        
        $this->build_endpoint();

        $this->authorize();

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

        $body = $auth_response['body'];

        $result = json_decode($body);

        $this->auth_status = $result->status;
        $this->timestamp = $result->timestamp;
        
        if (isset($result->error_message)) {
            $this->auth_error_message = $result->error_message;
        }

        $this->uid = $result->data->user_id;
        $this->key = base64_decode($result->data->auth_key);
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

        $auth_data = array($this->uid, $this->timestamp, 'GET', sha1($url));

       $hash = hash_hmac('sha256', implode('.', $auth_data), $this->key);

        $headers_array = array(
            'Content-Type' => 'application/json',
            'X-OnepageCRM-UID' => $this->uid,
            'X-OnepageCRM-TS' => $this->timestamp,
            'X-OnepageCRM-Auth' => $hash,
        );

        $args = array(
            'timeout' => 45,
            'redirection' => 0,
            'httpversion' => '1.0',
            'sslverify' => FALSE,
            'method' => 'GET',
            'headers' => $headers_array,
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

        switch ($this->module) {
            case 'custom_fields':
            case 'users':

                $this->endpoint = $this->module_config[$this->module]['endpoint'];
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
