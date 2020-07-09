<?php


/**
 * Support class is given a string snippet and responds with contextual help
 *
 */
class NF_OnepageCRM_Support {
    
    /**
     * Returns any help message available based on a given string
     * 
     * Returns false as default
     * 
     * @param string $string
     */
    public static function help($string, $detail=''){
        
        $help = false;
        
        switch($string){
            
            case 'Only following options are allowed for this custom field: Yes':

                $help= sprintf( esc_html__( 'One of your custom fields is a field that only can accept \'Yes\' for an answer.  Check your field map for the value you are sending.  The best option for this field is to create a check box and set the checked value as \'Yes\' and the unchecked value as BLANK SPACE.', 'ninja-forms-onepage-crm' ), $detail );
                break;
            
            default:
                
        }
        
        return $help;
    }
}
