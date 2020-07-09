<?php

/**
 * Static functions that format data for HTML output
 */
class NF_OnepageCRM_Markup {

    public static function markup($key, $raw ) {

        switch ($key) {

            case NF_OnepageCRM_Constants::FIELD_MAP_DATA:
                $markup = self::markup_field_map_data($raw);
                break;

            case NF_OnepageCRM_Constants::REQUEST_ARRAY:
                $markup = self::markup_request_array($raw);
                break;

            case NF_OnepageCRM_Constants::FORMATTED_REQUEST:
                $markup = self::markup_json_array($raw);
                break;
            
            case NF_OnepageCRM_Constants::RESPONSE_SUMMARY:
                $markup = self::markup_response_summary($raw);
                break;
    
            case NF_OnepageCRM_Constants::FULL_RESPONSE:
                $markup = self::markup_full_response($raw);
                break;
            
            case NF_OnepageCRM_Constants::CONNECTION_VERIFICATION:
                $markup = self::markup_connection_verification($raw);
                break;
            
            default:
                $markup = '';
        }

        return $markup;
    }

    /**
     * 
     * @param array $response_data_array Array of support data given as an array 
     * keyed on module with a summary string 
     * 
     * @return string HTML table markup of support data 
     */
    public static function markup_response_summary($response_data_array) {

        $table = '<table><tbody><tr><td><strong>Section</strong></td><td><strong>Response Summary</strong></td></tr>';

        if (!empty($response_data_array)) {

            foreach ($response_data_array as $module => $summary_array) {
                
                if(!is_array($summary_array)){ continue;}
                
                $table .= '<tr>';

                $table .= '<td><strong>' . $module . '</strong></td><td>';
                foreach ($summary_array as $summary) {

                    $table .= $summary . '<br />';
                }
                $table .= '</td></tr>';
            }
        }
        $table .= '</tbody></table>';

        return $table;
    }

        public static function markup_full_response($full_response) {

        $table = '<table><tbody><tr><td><strong>Section</strong></td><td><strong>Full Response</strong></td></tr>';

        if (!empty($full_response)) {

            foreach ($full_response as $module => $module_response) {
                $table .= '<tr>';

                $table .= '<td><strong>' . $module . '</strong></td><td style="word-break: break-all; word-wrap: break-word;">';


                    $table .= serialize($module_response) . '<br />';
                
                $table .= '</td></tr>';
            }
        }
        $table .= '</tbody></table>';

        return $table;
    }
    
    
    /**
     * 
     * @param array $field_data_array Array of field data given as an array 
     * 
     * 
     * @return string HTML table markup of field data 
     */
    public static function markup_field_map_data($field_data_array) {

        $table = '<table><tbody>';
        if (!empty($field_data_array)) {

            foreach ($field_data_array as $single_field_array) {

                $table .= '<tr>';
                foreach ($single_field_array as $key => $value) {
                    $table .= '<td>' . $value . '</td>';
                }
                $table .= '</tr>';
            }
        }
        $table .= '</table>';

        return $table;
    }

    /**
     * 
     * @param array $request_array The request array keyed on module with
     * serialized data
     * 
     * @return string HTML table markup of request array data 
     */
    public static function markup_request_array($request_array) {

        $table = '<table><tbody><tr><td><strong>Module</strong></td><td><strong>Request Array</strong></td></tr>';

        if (!empty($request_array)) {

            foreach ($request_array as $module => $summary) {

                $table .= '<tr>';

                $table .= '<td><strong>' . $module . '</strong></td><td style="word-break: break-all; word-wrap: break-word;">' . serialize($summary) . '</td></tr>';
            }
        }
        $table .= '</tbody></table>';

        return $table;
    }

    /**
     * 
     * @param array $json_array Array of JSON data keyed on module
     * 
     * @return string Table markup for JSON data
     */
    public static function markup_json_array($json_array) {

        $table = '<table><tbody><tr><td><strong>Module</strong></td><td><strong>JSON</td></strong></tr>';

        if (!empty($json_array)) {

            foreach ($json_array as $module => $json) {

                $table .= '<tr>';

                $table .= '<td><strong>' . $module . '</strong></td><td style="word-break: break-all; word-wrap: break-word;">' . htmlentities($json) . '</td></tr>';
            }
        }

        $table .= '</tbody></table>';

        return $table;
    }

    public static function markup_connection_verification($raw) {

        if (!is_array($raw) || empty($raw)) {

            $markup = __('No recent communication with Onepage is recorded.', 'ninja-forms-onepage-crm');
        } else {
            
            $markup = implode('<br />', $this->support_data[NF_OnepageCRM_Constants::CONNECTION_VERIFICATION]);
        }

        return $markup;
    }

}
