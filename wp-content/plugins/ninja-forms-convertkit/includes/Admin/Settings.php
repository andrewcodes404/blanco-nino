<?php

if (!defined('ABSPATH'))
    exit;


final class NF_OnepageCRM_Admin_Settings {

    /**
     * Communication Data array for support 
     * @var array 
     */
    protected $support_data;

    /**
     * Configured settings for display on settings page
     * 
     * @var array
     */
    protected $configured_settings;
    /**
     *
     * @var array Marked up html for readable display in settings
     */
    protected $support_data_markup;

    /**
 * Configures the plugin settings and formats support data
 * 
 * Uses shared functions from Functions.php
 */
    public function __construct() {

        $this->configured_settings = NF_OnepageCRM()->config('PluginSettings');
                
        $this->collect_support_data();
        
        $this->mark_up_connection_verification();
        
        $this->mark_up_support_data();
        
        $this->support_data_display_logic();

        $this->api_test_link();
        
        $this->refresh_account_data_link();
        
        add_filter('ninja_forms_plugin_settings', array($this, 'plugin_settings'), 10, 1);
        add_filter('ninja_forms_plugin_settings_groups', array($this, 'plugin_settings_groups'), 10, 1);
    }

    /**
     * Merge Onepage settings into settings
     * 
     * @param array $settings
     * @return array
     */
    public function plugin_settings($settings) {

        $settings[NF_OnepageCRM::SLUG] = $this->configured_settings;

        return $settings;
    }

    /**
     * Merge Onepage plugin group into settings groups
     * @param array $groups
     * @return array
     */
    public function plugin_settings_groups($groups) {

        $groups[NF_OnepageCRM::SLUG] = NF_OnepageCRM()->config('PluginSettingsGroups');
        
        return $groups;
    }

    /**
     * Consolidate support/connection data from db sources into support_data
     */
    protected function collect_support_data(){
        
        $support_data = NF_OnepageCRM()->get_support_data();
        
        $credential_data = NF_OnepageCRM()->get_credentials(); // holds connection message
        
        $this->support_data= array_merge($support_data,$credential_data);   
    }
    
    /**
     * Uses markup object to markup support data
     */   
    protected function mark_up_support_data() {

        $markup_array = array(
            NF_OnepageCRM_Constants::FIELD_MAP_DATA,
            NF_OnepageCRM_Constants::REQUEST_ARRAY,
            NF_OnepageCRM_Constants::FORMATTED_REQUEST,
            NF_OnepageCRM_Constants::RESPONSE_SUMMARY,
            NF_OnepageCRM_Constants::FULL_RESPONSE,
        );

        foreach ($markup_array as $key) {
            $this->configured_settings[$key]['html'] = NF_OnepageCRM_Markup::markup($key, $this->support_data[$key]);
        }
    }

    /**
     * Display or hide support logic based on advanced command
     */
    protected function support_data_display_logic(){
  
        NF_OnepageCRM_Functions::add_advanced_command_filters('support');
        
        $support_mode = apply_filters('nfonepagecrm_display_support', false);
        
        if (!$support_mode) {

            $support_mode_settings = array(
                NF_OnepageCRM_Constants::FIELD_MAP_DATA,
                NF_OnepageCRM_Constants::REQUEST_ARRAY,
                NF_OnepageCRM_Constants::FORMATTED_REQUEST,
                NF_OnepageCRM_Constants::FULL_RESPONSE,
            );

            foreach ($support_mode_settings as $setting) {

                unset($this->configured_settings[$setting]);
            }
        }   
    }
    
    /**
     * Returns the listener link to test the API
     */
    protected function api_test_link() {

        $url = home_url() . '/?' . NF_OnepageCRM_Listener::LISTENER_URL . '=' . NF_OnepageCRM_Listener::CONNECTION_TEST;
        $link = '<a id="onepage-connection-test" href="' . $url . '">Click to test your API connection</a>';

        $this->configured_settings[NF_OnepageCRM_Constants::CONNECTION_VERIFICATION]['html'] = $link;
    }
    
//    nfonepagecrm_refresh_account_data
    /**
     * Returns the refresh account data link
     */
    protected function refresh_account_data_link() {

        $url = home_url() . '/?' . NF_OnepageCRM_Listener::LISTENER_URL . '=' . NF_OnepageCRM_Listener::ACCOUNT_DATA_REFRESH;
        $link = '<a id="onepage-refresh-account-data" href="' . $url . '">Click to refresh your account data</a>';

        $this->configured_settings[NF_OnepageCRM_Constants::REFRESH_ACCOUNT]['html'] = $link;
    }    
    
    
    
    protected function mark_up_connection_verification() {
  
        if (!is_array($this->support_data[NF_OnepageCRM_Constants::CONNECTION_VERIFICATION]) || empty($this->support_data[NF_OnepageCRM_Constants::CONNECTION_VERIFICATION])) {

            $this->support_data[NF_OnepageCRM_Constants::CONNECTION_VERIFICATION] = __('No recent communication with Onepage is recorded.', 'ninja-forms-onepage-crm')
            ;
        } else {
            $this->support_data[NF_OnepageCRM_Constants::CONNECTION_VERIFICATION] = implode('<br />', $this->support_data[NF_OnepageCRM_Constants::CONNECTION_VERIFICATION]);
        }
    }

}

// End Class NF_OnepageCRM_Admin_Settings


