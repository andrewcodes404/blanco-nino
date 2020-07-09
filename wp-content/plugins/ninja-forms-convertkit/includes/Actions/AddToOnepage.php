<?php

if (!defined('ABSPATH') || !class_exists('NF_Abstracts_Action'))
    exit;

/**
 * Class NF_GoogleContacts_Actions_AddToOnepage
 */
final class NF_OnepageCRM_Actions_AddToOnepage extends NF_Abstracts_Action {

    /**
     * @var string
     */
    protected $_name = 'addtoonepage';

    /**
     * @var array
     */
    protected $_tags = array();

    /**
     * @var string
     */
    protected $_timing = 'normal';

    /**
     * @var int
     */
    protected $_priority = '10';

    /**
     *
     * @var array Field map with labels and map instructions
     */
    protected $field_map_array;

    /**
     * Drop down version of field map array for option-repeater
     * @var array 
     */
    protected $field_map_dropdown;

    /**
     * Entry types and the entries that use them in a nested array
     * @var array 
     */
    protected $entry_type;

    /**
     * Drop down version of entry type array for option-repeater
     * @var array 
     */
    protected $entry_type_dropdown;

    /**
     * The custom fields array stored in account data
     * @var array
     */
    protected $custom_fields_array;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        $this->_nicename = __('Add To Onepage', 'ninja-forms-onepage-crm');

        $this->initialize_variables();

        $this->build_field_map_dropdown();

        $this->entry_type_dropdown = NF_OnepageCRM_ArrayConstruction::build_entry_type_dropdown($this->entry_type);

        add_action('admin_init', array($this, 'build_admin_settings'));

        add_action('ninja_forms_builder_templates', array($this, 'builder_templates'));
    }

    /**
     * Configure required settings
     */
    protected function initialize_variables() {

        // configure lookup list of fields to extract from form submission data
        $this->field_map_array = NF_OnepageCRM()->get_field_map_array();

        // configure entry type
        $this->entry_type = NF_OnepageCRM()->get_entry_type();

        $temp = get_option( NF_OnepageCRM_Constants::ACCOUNT_DATA);

        if ($temp['custom_fields']['custom_fields']) {

            $this->custom_fields_array = $temp['custom_fields']['custom_fields'];
        } else {
            $this->custom_fields_array = array();
        }
    }

    public function builder_templates() {

        NF_OnepageCRM::template('custom-field-map-row.html');
    }

    /**
     * Build admin settings that need to be constructed instead of configured
     * 
     * Drop down selector for field map is constructed because the full array
     * for the field map includes both readable names and mapping instructions
     * and thus can't be simply configured with a formatted array
     */
    public function build_admin_settings() {

        // configure array of action settings
        $settings = NF_OnepageCRM::config('ActionFieldMapSettings');

        $this->_settings = array_merge($this->_settings, $settings);

        // build drop down lists for field map
        $this->_settings[NF_OnepageCRM_Constants::FIELD_MAP_REPEATER_KEY]['columns']['field_map']['options'] = $this->field_map_dropdown;

        // build drop down list for entry type
        $this->_settings[NF_OnepageCRM_Constants::FIELD_MAP_REPEATER_KEY]['columns']['entry_type']['options'] = $this->entry_type_dropdown;
    }

    /*
     * PUBLIC METHODS
     */

    public function save($action_settings) {
        
    }

    public function process($action_settings, $form_id, $data) {

        NF_OnepageCRM::file_include('Comm', 'ProcessForm');

        $process_form_object = NF_OnepageCRM_Functions::get_alternate_process_value('form_processing');

        new $process_form_object($action_settings, $form_id, $data);

        return $data;
    }

    /**
     * Formats an option array for the drop down menu in the option repeater
     * 
     * 
     * @param array $field_map_array
     * @return array
     */
    protected function build_field_map_dropdown() {

        $this->field_map_dropdown = NF_OnepageCRM_ArrayConstruction::build_field_map_dropdown($this->field_map_array);

        if ($this->custom_fields_array) {

            $custom_field_map_dropdown = NF_OnepageCRM_ArrayConstruction::build_custom_field_map_dropdown($this->custom_fields_array);

            $this->field_map_dropdown = array_merge($this->field_map_dropdown, $custom_field_map_dropdown);
        }
    }

}
