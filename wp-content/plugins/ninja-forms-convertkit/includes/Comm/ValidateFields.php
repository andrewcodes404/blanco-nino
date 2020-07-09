<?php

/**
 * Class of static methods for validating form submission values
 * 
 */
class OnepageValidateFields {

    public static function force_integer($value_in) {

        $value_out = intval($value_in);

        return $value_out;
    }

    public static function force_float($value_in) {

        $value_out = floatval($value_in);

        return $value_out;
    }

    public static function force_boolean($value_in) {

        $temp_value = $value_in;

        $false_values = array(
            'false',
            'FALSE',
            'unchecked'
        );

        if (in_array($temp_value, $false_values)) {
            $temp_value = FALSE;
        }

        $value_out = boolval($temp_value);

        return $value_out;
    }

    public static function limit_140_characters($value_in) {

        $value_out = substr($value_in, 0, 140);

        return $value_out;
    }

    public static function limit_60_characters($value_in) {

        $value_out = substr($value_in, 0, 60);

        return $value_out;
    }

    public static function convert_date_interval($value_in) {

        $date_format = NF_OnepageCRM_Constants::DATE_FORMAT;

        $date = new DateTime;

        $date_2 = new DateTime;

        $date_interval = date_interval_create_from_date_string($value_in);

        date_add($date, $date_interval);

        if ($date == $date_2) {

            $value_out = $value_in;
        } else {

            $value_out = $date->format($date_format);
        }

        return $value_out;
    }

    public static function format_date($value_in) {

        $value_out = date(NF_OnepageCRM_Constants::DATE_FORMAT, strtotime($value_in));

        return $value_out;
    }

    /**
     * Allowed values for Onepage Action Status
     * @param mixed $value_in
     * @return string
     */
    public static function allowed_action_status($value_in) {

        $allowed_array = array('asap', 'next', 'date', 'waiting', 'queued', 'date_time', 'queued_with_date');

        if (in_array($value_in, $allowed_array)) {

            $value_out = $value_in;
        } else {

            $value_out = 'asap';
        }

        return $value_out;
    }

}
