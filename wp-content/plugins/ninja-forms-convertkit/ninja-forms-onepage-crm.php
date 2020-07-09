<?php

if (!defined('ABSPATH'))
    exit;

/*
 * Plugin Name: Ninja Forms - OnepageCRM
 * Plugin URI: http://lb3computingsolutions.com
 * Description: Send your Ninja Forms submission directly into your One Page CRM account
 * Version: 3.0.0
 * Author: Stuart Sequeira
 * Author URI: http://lb3computingsolutions.com/about
 * Text Domain: ninja-forms-onepage-crm
 *
 * Copyright 2016, 2017 Stuart Sequeira
 */

if (version_compare(get_option('ninja_forms_version', '0.0.0'), '3.0.0', '<') || get_option('ninja_forms_load_deprecated', FALSE)) {
    
} else {

    // define Onepage mode as POST3
    if (!defined('NFONEPAGECRM_MODE')) {

        define('NFONEPAGECRM_MODE', 'POST3');
    }

    // plugin folder url
    if (!defined('NFONEPAGECRM_PLUGIN_URL')) {
        define('NFONEPAGECRM_PLUGIN_URL', plugin_dir_url(__FILE__));
    }

// plugin folder path
    if (!defined('NFONEPAGECRM_PLUGIN_DIR')) {
        define('NFONEPAGECRM_PLUGIN_DIR', plugin_dir_path(__FILE__));
    }


    /*
     * Include functions, markup, and listener
     */
    include 'includes/Admin/Functions.php';
    include 'includes/Admin/ArrayConstruction.php';
    include 'includes/Admin/Markup.php';
    include 'includes/Admin/Listener.php';
    include 'includes/Admin/Constants.php';
    include 'includes/Admin/Support.php';

    /**
     * Class NF_OnepageCRM
     */
    final class NF_OnepageCRM {

        const VERSION = '3.0.0';
        const SLUG = 'onepagecrm';
        const NAME = 'OnepageCRM';
        const AUTHOR = 'Stuart Sequeira';
        const PREFIX = 'NF_OnepageCRM';
        const DOMAIN = 'ninja-forms-onepage-crm';

        /**
         * @var NF_OnepageCRM
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * Credentials stored in the db as a NF setting
         * 
         * @var array 
         */
        protected $credentials;

        /**
         * Support data stored in the db as a WP option
         * 
         * @var array 
         */
        protected $support_data;

        /**
         * Advanced codes array
         * @var array 
         */
        protected $advanced_codes;

        /**
         * Configuration array for field mapping
         * 
         * @var array 
         */
        protected $field_map_array;

        /**
         * List of all entry types with nested array of accepted fields for use
         * @var array
         */
        protected $entry_type;

        /**
         * Module configuration defining structure for communication with Onepage
         * @var array
         */
        protected $module_config;

        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_OnepageCRM Highlander Instance
         */
        public static function instance() {

            if (!isset(self::$instance) && !(self::$instance instanceof NF_OnepageCRM)) {
                self::$instance = new NF_OnepageCRM();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register(array(self::$instance, 'autoloader'));
            }

            return self::$instance;
        }

        public function __construct() {
            /*
             * load the global variables
             * do this internally and make available?
             */

            $this->load_config_variables();

            /**
             * Requires all plugins loaded
             * 
             * ex: methods that filter should wait until all plugins loaded
             */
            add_action('plugins_loaded', array($this, 'extract_support_data'));

            
            /**
             * Set up Licensing
             */
            add_action('admin_init', array($this, 'setup_license'));
            
            /**
             * Requires NF to be loaded before executing
             */
            add_action('ninja_forms_loaded', array($this, 'extract_credentials'));
            add_action('ninja_forms_loaded', array($this, 'setup_admin'));

            add_action('ninja_forms_builder_templates', array($this, 'builder_templates'));

            add_filter('ninja_forms_register_actions', array($this, 'register_actions'));
        }

        /**
         * Loads configured settings easy access
         */
        public function load_config_variables() {

            $this->module_config = self::config('OnepageModules');
            $this->entry_type = self::config('EntryType');

            $temp = self::config('FieldMapArray');
            if (NF_OnepageCRM_Functions::get_alternate_process_value()) {

                $this->field_map_array = apply_filters('nfonepagecrm_filter_field_map_array', $temp);
            } else {
                $this->field_map_array = $temp;
            }
        }

        public function register_actions($actions) {

            $actions['addtoonepage'] = new NF_OnepageCRM_Actions_AddToOnepage();

            return $actions;
        }

        /*
         * Set up the licensing
         */

        public function setup_license() {

            if (!class_exists('NF_Extension_Updater'))
                return;

            new NF_Extension_Updater(self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG);
        }

        /**
         * Create the settings page
         * 
         * Hooked into Ninja Forms
         */
        public function setup_admin() {

            if (!is_admin())
                return;

            new NF_OnepageCRM_Admin_Settings();
        }

        public function load_classes() {
            
        }

        /**
         * Returns a configuration specified in a given Config file
         * @param string $file_name
         * @return mixed
         */
        public static function config($file_name) {

            return include self::$dir . 'includes/Config/' . $file_name . '.php';
        }

        /**
         * Includes a specific file in an Includes directory
         * 
         * @param string $sub_dir
         * @param string $file_name
         */
        public static function file_include($sub_dir, $file_name) {

            include self::$dir . 'includes/' . $sub_dir . '/' . $file_name . '.php';
        }

        /**
         * Creates a template for display
         * 
         * @param string $file_name
         * @param array $data
         * @return mixed
         */
        public static function template($file_name = '', array $data = array()) {

            if (!$file_name) {
                return;
            }
            extract($data);

            include self::$dir . 'includes/Templates/' . $file_name;
        }

        /*
         * Optional methods for convenience.
         */

        public function autoloader($class_name) {

            if (class_exists($class_name))
                return;

            if (false === strpos($class_name, self::PREFIX))
                return;

            $class_name = str_replace(self::PREFIX, '', $class_name);
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }

        /**
         * Retrieves Username and Password from settings, sets 'valid' to FALSE if invalid
         */
        public function extract_credentials() {

            $connection_message = ''; // initialize
            $this->credentials['valid'] = true; // initialize

            /*
             * Password
             */
            if (!class_exists('Ninja_Forms')) {

                $setting = false;
            } else {

                $setting = Ninja_Forms()->get_setting(NF_OnepageCRM_Constants::PASSWORD);
            }

            if (0 == strlen($setting)) {

                $this->credentials['valid'] = false;
            } else {

                $this->credentials[NF_OnepageCRM_Constants::PASSWORD] = $setting;
            }

            /*
             * Username
             */
            if (!class_exists('Ninja_Forms')) {

                $setting = false;
            } else {

                $setting = Ninja_Forms()->get_setting(NF_OnepageCRM_Constants::USERNAME);
            }
            if (0 == strlen($setting)) {

                $this->credentials['valid'] = false;
            } else {

                $this->credentials[NF_OnepageCRM_Constants::USERNAME] = $setting;
            }

            $this->credentials['connection_message'] = $connection_message;
        }
        
        /** Extracts support data for use throughout */
        public function extract_support_data() {

            $settings_array = self::config('PluginSettings');

            $stored_support_data = get_option(NF_OnepageCRM_Constants::SUPPORT_DATA);

            foreach ($settings_array as $key => $settings) {

                if ('html' != $settings['type']) {
                    continue;
                }

                if (!isset($stored_support_data[$key])) {

                    $stored_support_data[$key] = array();
                }
            }

            $this->support_data = $stored_support_data;
        }

        /**
         * Modify the global support data
         * 
         * This doesn't update the database automatically to minimize db calls.
         * Several 'modify' functions can be used consecutively and updated
         * with a single 'update' command.
         * 
         * @param string $key Key under which data is stored
         * @param mixed $value Data to be stored
         * @param boolean $append Append the data to the end or replace current
         */
        public function modify_support_data($key = '', $value = '', $append = false) {

            if (0 == strlen($key)) {
                $key = 'no_key';
            }

            /*
             * Save three previous values
             * Revisit to save more if needed
             */
            if ($append) {
                $count = count($this->support_data[$key]);

                if (3 < $count) {

                    array_shift($this->support_data[$key]);
                }

                $this->support_data[$key][] = $value;
            } else {

                $this->support_data[$key] = $value;
            }
        }

        public function update_support_data($debug_only = '') {

            NF_OnepageCRM_Functions::add_advanced_command_filters('debug_mode');

            $debug_mode = apply_filters('nfonepagecrm_debug_mode', false);

            /*
             * return if value is only set in debug mode and debug mode is off
             */
            if ('debug' == $debug_only && !$debug_mode) {

                return;
            }

            update_option(NF_OnepageCRM_Constants::SUPPORT_DATA, $this->support_data);
        }

        /**
         * Returns the support data stored in the db
         * @return array 
         */
        public function get_support_data() {

            if (empty($this->support_data)) {

                return false;
            } else {

                return $this->support_data;
            }
        }

        /**
         * Returns the credentials for the account
         * 
         * Initialized to an array, 'valid' is set to false if any value is invalid
         * @return array 
         */
        public function get_credentials() {

            return $this->credentials;
        }

        /**
         * Module configuration defining structure for communication with Onepage
         * @return array 
         */
        public function get_module_config() {

            if (empty($this->module_config)) {

                return array();
            } else {

                return $this->module_config;
            }
        }

        /**
         * Returns the configured entry type array
         * @return array
         */
        public function get_entry_type() {

            if (empty($this->entry_type)) {

                return array();
            } else {

                return $this->entry_type;
            }
        }

        /**
         * Returns the configured field map array
         * @return array
         */
        public function get_field_map_array() {

            if (empty($this->field_map_array)) {

                return array();
            } else {

                return $this->field_map_array;
            }
        }

    }

    /**
     * The main function responsible for returning The Highlander Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Highlander Instance
     */
    function NF_OnepageCRM() {
        return NF_OnepageCRM::instance();
    }

    NF_OnepageCRM();
}

