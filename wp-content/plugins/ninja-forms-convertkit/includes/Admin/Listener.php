<?php

class NF_OnepageCRM_Listener {

    /**
     * URL for listening for instructions
     */
    const LISTENER_URL = 'nfonepagecrm_instructions';

    /**
     * GET value for testing API connection
     */
    const CONNECTION_TEST = 'test-connection';

    /**
     * GET value for refreshing custom fields
     */
    const ACCOUNT_DATA_REFRESH = 'refresh-onepage-data';

    /**
     * Listens for GET requests with specific commands
     * 
     * Calls specific functions based on the request made; uses 
     * a switch/case so that only vetted functions are called
     */
    public static function listener() {

        /*
         * Check that Ninja_Forms is loaded, otherwise stop listening
         */
        if (!class_exists('Ninja_Forms') || !class_exists('NF_OnepageCRM')) {

            return;
        }

        $trigger = filter_input(INPUT_GET, self::LISTENER_URL);

        switch ($trigger) {

            case self::CONNECTION_TEST:

                self::test_connection();
                break;

            case self::ACCOUNT_DATA_REFRESH:
                self::refresh_account_data();
                break;

            default:
                break;
        }
    }

    /**
     * Test the connection with a standard request
     */
    public static function test_connection() {

        NF_OnepageCRM::file_include('Comm', 'Create');
        NF_OnepageCRM::file_include('Comm', 'HandleResponse');

        $request = json_encode(NF_OnepageCRM()->config('ConnectionTest'));

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::FORMATTED_REQUEST, array('test'=>$request));
        NF_OnepageCRM()->update_support_data();

        $create_module = new OnepageCreate('contact', $request);
        $full_response = $create_module->get_full_response();

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::FULL_RESPONSE, array('test'=> $full_response));
        NF_OnepageCRM()->update_support_data();

        $processed_response_object = new OnepageHandleResponse($full_response);
        $response_summary = $processed_response_object->get_response_summary();

        NF_OnepageCRM()->modify_support_data(NF_OnepageCRM_Constants::RESPONSE_SUMMARY, array('test'=>$response_summary));
        NF_OnepageCRM()->update_support_data();

        $redirect = admin_url() . 'admin.php?page=nf-settings#' . NF_OnepageCRM::SLUG;

        wp_redirect($redirect);
        exit;
    }

    /**
     * Requests the account data and stores it in the database
     */
    public static function refresh_account_data() {

        NF_OnepageCRM::file_include('Comm', 'GetAccountData');
        NF_OnepageCRM::file_include('Comm', 'HandleResponse');
        
        $module_array = array('custom_fields','users');
        
        foreach ($module_array as $module) {

            $get_module = new OnepageGetAccountData($module);
            $response = $get_module->get_full_response();

            $processed_response = new OnepageHandleResponse($response);
                 
            $response_array[$module] = json_decode(json_encode($processed_response->get_body_json_data()),true);
        }
        update_option(NF_OnepageCRM_Constants::ACCOUNT_DATA,$response_array);
        
        $redirect = admin_url() . 'admin.php?page=nf-settings#' . NF_OnepageCRM::SLUG;

        wp_redirect($redirect);
        exit;
    }

}

add_action('init', 'NF_OnepageCRM_Listener::listener');
